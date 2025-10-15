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

namespace app\index\controller;
use app\mp\service\Users;
use EasyWeChat\Factory;
use think\Controller;
use think\Log;
use think\Debug;
use think\Request;
use think\Db;
use app\index\logic\Defs;
use app\index\logic\Config as ConfigLogic;
use app\index\logic\AppointOrder as AppointOrderLogic;
use app\index\logic\Customer as CustomerLogic;
use app\index\logic\Expert as ExpertLogic;
use app\index\service\EventLogs as EventLogsService;
use app\index\service\RequestContext;
use app\index\service\OperationLogs as OperationLogsService;

class AppointOrder extends Common
{
    public function index($search=[],
        $page=1,
        $rows=DEFAULT_PAGE_ROWS,
        $sort='',
        $order='', 
        $expertId=0, 
        $customerId=0){
        if(request()->isGet()){
            $urlHrefs = [];
            $urlHrefs['index'] = url('index/AppointOrder/index', ['expertId'=>$expertId,'customerId'=>$customerId]);
            $this->assign('urlHrefs', $urlHrefs);
            return $this->fetch();
        }
        //专家
        if($expertId){
            $search['expert_id'] = $expertId;
        }
        //客户
        if($customerId){
            $search['customer_id'] = $customerId;
        }
        $appointOrderLogic = AppointOrderLogic::newObj();
        return json($appointOrderLogic->load($search, $page, $rows, $sort, $order));
    }
    public function finish($orderNo){
        $appointOrderLogic = AppointOrderLogic::newObj();
        $appointOrderLogic->finish($orderNo);
        return ajaxSuccess();
    }
    public function cancel($orderNo){
        $appointOrderLogic = AppointOrderLogic::newObj();
        $appointOrderLogic->cancel($orderNo);
        return ajaxSuccess();
    }
    public function refund($orderNo){
        $appointOrderLogic = AppointOrderLogic::newObj();
        try {
            $appointOrderLogic->refund($orderNo);
        }catch (\Exception $e){
            Log::error('refund exception: ' . $e->getMessage());
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess();
    }
    public function changeTime($orderNo, $appointDate, $appointTime){
        $appointOrderLogic = AppointOrderLogic::newObj();
        $appointOrderLogic->changeTime($orderNo, $appointDate, $appointTime);
        return ajaxSuccess();
    }
    public function view($orderNo){
        $appointOrderLogic = AppointOrderLogic::newObj();
        $orderInfos = $appointOrderLogic->getInfos($orderNo);
        if(!$orderInfos){
            return $this->fetch('common/error', ['msg'=>'无法找到该订单']);
        }
        $customerInfos = CustomerLogic::I()->getInfos($orderInfos['customer_id']);
        $expertInfos = ExpertLogic::I()->getInfos($orderInfos['expert_id']);

        $this->assign('orderInfos', $orderInfos);
        $this->assign('customerInfos', $customerInfos);
        $this->assign('expertInfos', $expertInfos);
        return $this->fetch();
    }
}