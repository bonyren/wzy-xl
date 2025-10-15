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
use app\Defs;
use app\index\logic\Defs as IndexDefs;
use app\index\logic\Sms as SmsLogic;
use app\mp\service\Users;
use think\Db;
use app\index\logic\Customer as CustomerLogic;
use app\index\logic\Organization as OrganizationLogic;
use app\index\logic\AppointOrder as AppointOrderLogic;

class Ucenter extends Base
{
    public function index(){
        $user = Users::I()->getUserById($this->_uid);
        if(!$user){
            $this->error("无法找到当前用户");
        }
        $this->assign('user',$user);

        $organizationDatas = OrganizationLogic::I()->loadComboDatas(0, '', []);
        $this->assign('organizationDatas', $organizationDatas);
        return $this->fetch();
    }

    //发送短信验证码
    public function captchaSms($cellphone=''){
        if(!validateMobile($cellphone)){
            return ajaxError('号码错误');
        }
        $smsLogic = SmsLogic::I();
        try{
            $smsLogic->sendCaptcha($cellphone, 6, 'APPOINT');
        }catch(\Exception $e){
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess();
    }

    public function saveInfo(){
        $data = input('post.');
        $customer = Db::table('customer')->where(['id'=>$this->_uid])->find();
        if(empty($customer)){
            return ajaxError("无法找到对应的客户");
        }
        $customerLogic = CustomerLogic::newObj();
        try{
            $customerLogic->save($this->_uid, $data);
        }catch(\Exception $e){
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess();
    }    
    /**
     * appoints
     *
     * @param  mixed $status
     * @return void
     */
    public function appoints($status = IndexDefs::ORDER_APPOINTED_STATUS){
        //用户信息
        $user = Users::I()->getUserById($this->_uid);
        $this->assign('user',$user);
        //预约数据
        $this->assign('statusFilter', $status);
        //已预约
        $appointedOrders = AppointOrderLogic::I()->getOrders($this->_uid, IndexDefs::ORDER_APPOINTED_STATUS);
        foreach($appointedOrders as &$order){
            $order['appointTimeFull'] = $order['appoint_date'] . ' ' . convertAppointTimesToShow($order['appoint_time']) . '(' . $order['appoint_duration'] . '分钟)';
            $order['appointModeText'] = IndexDefs::$appointModeDefs[$order['appoint_mode']];
            $order['appointAddress'] = systemSetting('appoint_order_office_address');

            $order['workimg_url'] = generateThumbnailUrl($order['workimg_url'], 300);
            $order['appoint_time'] = convertAppointTimesToShow($order['appoint_time']);
        }
        //已完成
        $finishedOrders = AppointOrderLogic::I()->getOrders($this->_uid, IndexDefs::ORDER_FINISH_STATUS);
        foreach($finishedOrders as &$order){
            $order['appointTimeFull'] = $order['appoint_date'] . ' ' . convertAppointTimesToShow($order['appoint_time']) . '(' . $order['appoint_duration'] . '分钟)';
            $order['appointModeText'] = IndexDefs::$appointModeDefs[$order['appoint_mode']];
            $order['appointAddress'] = systemSetting('appoint_order_office_address');

            $order['workimg_url'] = generateThumbnailUrl($order['workimg_url'], 300);
            $order['appoint_time'] = convertAppointTimesToShow($order['appoint_time']);
        }
        //未支付
        $pendingOrders = AppointOrderLogic::I()->getOrders($this->_uid, IndexDefs::ORDER_PENDING_STATUS);
        foreach($pendingOrders as &$order){
            $order['appointTimeFull'] = $order['appoint_date'] . ' ' . convertAppointTimesToShow($order['appoint_time']) . '(' . $order['appoint_duration'] . '分钟)';
            $order['appointModeText'] = IndexDefs::$appointModeDefs[$order['appoint_mode']];
            $order['appointAddress'] = systemSetting('appoint_order_office_address');

            $order['workimg_url'] = generateThumbnailUrl($order['workimg_url'], 300);
            $order['appoint_time'] = convertAppointTimesToShow($order['appoint_time']);
        }
        $this->assign('appointedOrders', $appointedOrders);
        $this->assign('finishedOrders', $finishedOrders);
        $this->assign('pendingOrders', $pendingOrders);
        $this->assign('pageTitle', '我的预约');
        return $this->fetch();
    } 
    //我的测试
    /**
     * @param $finished 0:未完成测试, 1:已完成测试
     * @return mixed
     */
    public function tests(){
        $map = [];
        $map['o.customer_id'] = $this->_uid;
        //不是组合测评和普查订单
        $map['o.cb_order_id'] = 0;
        $map['o.survey_order_id'] = 0;
        //未完成
        $map['o.finished'] = 0;
        $sort = 'o.order_time desc';
        $rows = Db::table('subject_order')->alias('o')
            ->join('subject s','s.id=o.subject_id')
            ->field('o.id,o.order_no,o.order_time,o.order_amount,o.pay_status,o.finish_time,o.finished,
                s.id,s.name,s.current_price,s.participants_show as participants,s.image_url,s.subtitle,s.items')
            ->where($map)
            ->order($sort)
            ->select();
        foreach($rows as &$row){
            /*
            $categoryNames = Db::table('subject_category_relate')->alias('SCR')
                ->join('categories C', 'SCR.category_id=C.id and SCR.subject_id='.$row['id'])
                ->column('C.name');
            $row['category_names'] = $categoryNames;
            */
            $row['image_url'] = generateThumbnailUrl($row['image_url'], 300);
        }
        $this->assign('rows',$rows);
        //已完成
        $map['o.finished'] = 1;
        $sort = 'o.finish_time desc';
        $rowsCompleted = Db::table('subject_order')->alias('o')
            ->join('subject s','s.id=o.subject_id')
            ->field('o.id,o.order_no,o.order_time,o.order_amount,o.pay_status,o.finish_time,o.finished,
                s.id,s.name,s.current_price,s.participants_show as participants,s.image_url,s.subtitle,s.items,s.test_allow_view_report')
            ->where($map)
            ->order($sort)
            ->select();
        foreach($rowsCompleted as &$rowCompleted){
            $rowCompleted['image_url'] = generateThumbnailUrl($rowCompleted['image_url'], 300);
        }
        $this->assign('rowsCompleted',$rowsCompleted);
        $this->assign('pageTitle', '我的测评');
        return $this->fetch();
    }

    //我的收藏
    public function collections(){
        $map = ['s.delete_flag'=>0, 's.status'=>IndexDefs::ENTITY_PUBLISH_STATUS];
        $map['c.customer_id'] = $this->_uid;
        $rows = Db::table('subject_collect')->alias('c')
            ->join('subject s','s.id=c.subject_id')
            ->field('s.id,s.name,s.current_price,s.participants_show as participants,s.image_url,s.subtitle,s.items')
            ->where($map)
            ->order('c.id desc')
            ->select();
        foreach($rows as &$row){
            $categoryNames = Db::table('subject_category_relate')->alias('SCR')->join('categories C', 'SCR.category_id=C.id and SCR.subject_id='.$row['id'])->column('C.name');
            $row['category_names'] = $categoryNames;

            $row['image_url'] = generateThumbnailUrl($row['image_url'], 300);
        }
        $this->assign('rows',$rows);
        $this->assign('pageTitle', '收藏测评');
        return $this->fetch();
    }
    
    /**
     * 我的普查
     *
     * @return void
     */
    public function survey(){
        $this->assign('pageTitle', '我的普查');
        $map = [];
        //未完成
        $map['o.finished'] = 0;
        $sort = 'o.ctime desc';
        $rows = Db::table('survey_order')->alias('o')
            ->join('survey s', "o.survey_id=s.id and o.uid={$this->_uid}")
            ->where($map)
            ->order($sort)
            ->field('o.id, s.name, s.banner, s.subjects, o.ctime')
            ->select();
        foreach($rows as &$row){
            $row['subject_count'] = count(explode(',', $row['subjects']));
            $row['banner'] = generateThumbnailUrl($row['banner'], 300);
        }
        $this->assign('rows',$rows);
        //已完成
        $map['o.finished'] = 1;
        $rowsCompleted = Db::table('survey_order')->alias('o')
            ->join('survey s', "o.survey_id=s.id and o.uid={$this->_uid}")
            ->where($map)
            ->order($sort)
            ->field('o.id, s.name, s.banner, s.subjects, s.cfg_view_report, o.ctime')
            ->select();
        foreach($rowsCompleted as &$row){
            $row['subject_count'] = count(explode(',', $row['subjects']));
            $row['banner'] = generateThumbnailUrl($row['banner'], 300);
        }
        $this->assign('rowsCompleted',$rowsCompleted);
        return $this->fetch();
    }    
    /**
     * 我的组合测评
     *
     * @return void
     */
    public function combination(){
        $this->assign('pageTitle', '组合测评');
        $map = [];
        //未完成
        $map['o.finished'] = 0;
        $sort = 'o.ctime desc';
        $rows = Db::table('combination_order')->alias('o')
            ->join('subject_combination c', "o.combination_id=c.id and o.uid={$this->_uid}")
            ->where($map)
            ->order($sort)
            ->field('o.id, c.name, c.banner, c.subjects, o.ctime')
            ->select();
        foreach($rows as &$row){
            $row['subject_count'] = count(explode(',', $row['subjects']));
            $row['banner'] = generateThumbnailUrl($row['banner'], 300);
        }
        $this->assign('rows',$rows);
        //已完成
        $map['o.finished'] = 1;
        $rowsCompleted = Db::table('combination_order')->alias('o')
            ->join('subject_combination c', "o.combination_id=c.id and o.uid={$this->_uid}")
            ->where($map)
            ->order($sort)
            ->field('o.id, c.name, c.banner, c.subjects, o.ctime')
            ->select();
        foreach($rowsCompleted as &$row){
            $row['subject_count'] = count(explode(',', $row['subjects']));
            $row['banner'] = generateThumbnailUrl($row['banner'], 300);
        }
        $this->assign('rowsCompleted',$rowsCompleted);
        return $this->fetch();
    }    
    /**
     * 关于我们
     *
     * @return void
     */
    public function aboutus(){
        $this->assign('pageTitle', '关于我们');
        return $this->fetch();
    }
}