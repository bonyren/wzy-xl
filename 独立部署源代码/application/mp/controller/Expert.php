<?php
// +----------------------------------------------------------------------
// | WZYCODING [ SIMPLE SOFTWARE IS THE BEST ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2025 wzycoding All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://spdx.org/licenses/GPL-2.0.html )
// +----------------------------------------------------------------------
// | Author: wzycoding <wzycoding@qq.com>
// +----------------------------------------------------------------------
namespace app\mp\controller;

use app\common\service\WException;
use app\index\logic\AppointOrder;
use think\Controller;
use think\Db;
use think\Log;
use think\Debug;
use think\Request;
use app\index\service\EventLogs as EventLogsService;

use app\Defs;
use app\index\logic\Defs as IndexDefs;
use app\mp\logic\Customer as CustomerLogic;
use app\index\logic\Expert as ExpertLogic;
use app\index\logic\Sms as SmsLogic;
use app\index\service\Mailer as MailerService;
use app\index\logic\AppointOrder as AppointOrderLogic;
use EasyWeChat\Factory;
use app\mp\service\Users;
use app\mp\service\RequestContext;
use app\mp\service\Subjects as SubjectsService;
use app\mp\service\Expert as ExpertService;

class Expert extends Base
{
    public function index(){
        $categories = SubjectsService::I()->getCategories();
        $this->assign([
            '_current_tab'=>'expert',
            'categories'=>$categories
        ]);
        return $this->fetch();
    }
    public function expert($categoryId=0, $name='', $page=1, $rows=DEFAULT_PAGE_ROWS){
        return json(ExpertService::I()->loadExperts($categoryId, $name, $page, $rows));
    }
    public function detail($expertId){
        try{
            $record = ExpertService::I()->getExpert($expertId);
        }catch(WException $e){
            $this->error($e->getMessage());
        }
        $this->assign('infos', $record);
        return $this->fetch();
    }
    public function appointTime($expertId){
        if(request()->isGet()){
            $this->assign('expertId', $expertId);
            return $this->fetch();
        }
        $weekDayList = ExpertService::I()->getAvailableAppointTime($expertId);
        return ajaxSuccess('操作成功',[
            'weekDayList'=>$weekDayList
        ]);
    }
    public function appointInfo(){
        if(request()->isGet()) {
            $expertId = input('get.expertId');
            //预约时间
            $date = input('get.date');
            $duration = input('get.duration');
            $time = input('get.time');
            $mode = IndexDefs::APPOINT_FACE2FACE_MODE;
            if(empty($expertId) || empty($date) || empty($duration) || empty($time)){
                //return $this->fetch('common/error');
                $this->error("参数不完整");
            }
            Log::notice('appointInfo: ' . var_export(input('get.'), true));

            $customerInfos = CustomerLogic::I()->getInfos($this->_uid);
            if(!$customerInfos){
                $customerInfos = [
                    'real_name'=>'',
                    'cellphone'=>'',
                ];
            }
            $showTime = convertAppointTimesToShow($time);
            $this->assign('infos', [
                'expertId'=>$expertId,
                'appointDate'=>$date,
                'appointDuration'=>$duration,
                'appointTime'=>$time,
                'appointMode'=>$mode,
                'realName'=>$customerInfos['real_name'],
                'appointTimeFull'=>$date . ' ' . $showTime . '(' . $duration . '分钟)',
                'cellphone'=>$customerInfos['cellphone']
            ]);
            /*
            $linkmen = AppointOrderLogic::I()->getLinkmen($this->_uid);
            //最多保留11个供选择
            $linkmen = array_slice($linkmen, 0, 11);
            $this->assign('linkmen', $linkmen);*/
            return $this->fetch();
        }
        //表单提交
        $postData = input('post.');
        try{
            $orderNo = ExpertService::I()->submitAppointOrder($this->_uid, $postData);
            return ajaxSuccess('操作成功', [
                'url'=>url('mp/Expert/appointConfirm',['orderNo'=>$orderNo])
            ]);
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function appointConfirm($orderNo=''){
        if(request()->isGet()){
            $orderInfos = AppointOrderLogic::I()->getOrderInfos($this->_uid, $orderNo);
            if(!$orderInfos){
                $this->error('订单不存在, ' . $orderNo);
            }
            $expertInfos = ExpertLogic::I()->getInfos($orderInfos['expert_id']);
            if(!$expertInfos){
                $this->error('预约专家不存在, ' . $orderNo);
            }
            $infos = $orderInfos;
            $infos['appointTimeFull'] = $orderInfos['appoint_date'] . ' ' . convertAppointTimesToShow($orderInfos['appoint_time']) . '(' . $orderInfos['appoint_duration'] . '分钟)';
            $infos['expert_name'] = $expertInfos['real_name'];
            $infos['expert_cellphone'] = $expertInfos['cellphone'];
            $infos['workimg_url'] = $expertInfos['workimg_url'];
            $infos['appoint_address'] = systemSetting('appoint_order_office_address');

            $infos['appoint_time'] = convertAppointTimesToShow($infos['appoint_time']);
            $this->assign('infos', $infos);
            return $this->fetch();
        }
    }    
    /**
     * 支付请求
     *
     * @param  mixed $orderNo
     * @return void
     */
    public function appointPay($orderNo=''){
        //进行支付
        /*
         * array (
              'return_code' => 'SUCCESS',
              'return_msg' => 'OK',
              'result_code' => 'FAIL',
              'err_code_des' => '201 商户订单号重复',
              'err_code' => 'INVALID_REQUEST',
              'mch_id' => '1582862851',
              'appid' => 'wx684a76fdab232c61',
              'nonce_str' => 'OTlwjMSuS8EgYIKE',
              'sign' => '1F654E6F2B3E8E4E60604BC92FC3BB02',
            )
        */
        try{
            $data = ExpertService::I()->appointPay($this->_uid, $orderNo);
            return ajaxSuccess('操作成功', $data);
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function checkOrderPaid($orderNo){
        $orderInfos = AppointOrderLogic::I()->getOrderInfos($this->_uid, $orderNo);
        if (empty($orderInfos)) {
            return ajaxError('订单不存在');
        }
        return ajaxSuccess('操作成功', $orderInfos['pay_status'] == Defs::PAY_SUCCESS);
    }    
    /**
     * 预约成功
     *
     * @param  mixed $orderNo
     * @return void
     */
    public function appointSuccess($orderNo=''){
        $orderInfos = AppointOrderLogic::I()->getOrderInfos($this->_uid, $orderNo);
        if(!$orderInfos){
            $this->error('订单不存在, ' . $orderNo);
        }
        if ($orderInfos['pay_status'] != Defs::PAY_SUCCESS && $orderInfos['pay_status'] != Defs::PAY_OFFLINE) {
            $this->error('订单未支付, ' . $orderNo);
        }
        return $this->fetch();
    }    
    /**
     * 重新支付
     *
     * @param  mixed $orderNo
     * @return void
     */
    public function uCenterPay($id){
        //已经发起过支付的，则要重新生成order no
        $orderInfos = AppointOrderLogic::I()->getOrderInfosById($id);
        if(!$orderInfos){
            return ajaxError('订单不存在');
        }
        $orderNo = $orderInfos['order_no'];
        if($orderInfos['prepay_id']) {
            $orderNo = AppointOrderLogic::I()->refreshOrderNoById($this->_uid, $id);
            if(!$orderNo){
                return ajaxError('刷新订单编号失败');
            }
        }
        return $this->appointPay($orderNo);
    }    
    /**
     * 取消订单
     *
     * @param  mixed $orderNo
     * @return void
     */
    public function appointCancel($id){
        $orderInfos = AppointOrderLogic::I()->getOrderInfosById($id);
        if(!$orderInfos){
            return ajaxError('订单不存在');
        }
        if($orderInfos['status'] == IndexDefs::ORDER_APPOINTED_STATUS &&
            $orderInfos['order_amount'] > 0 &&
            $orderInfos['pay_status'] == Defs::PAY_SUCCESS){
            //已预约并且成功支付，则不允许自动取消
            return ajaxError('已支付的订单请联系客服或者预约专家取消');
        }
        AppointOrderLogic::I()->cancelOrder($id);
        return ajaxSuccess();
    }
}