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

use think\Controller;
use think\Log;
use think\Debug;
use think\Request;
use think\Db;
use app\common\service\WException;
use app\index\logic\Defs as IndexDefs;
use app\index\logic\Config as ConfigLogic;
use app\index\logic\Expert as ExpertLogic;


class Expert extends Common
{
    public function index($search=[],$page=1,$rows=DEFAULT_PAGE_ROWS,$sort='',$order='',$expertId=0){
        if(request()->isGet()){
            $urlHrefs = [];
            $urlHrefs['index'] = url('index/Expert/index', ['expertId'=>$expertId]);
            $this->assign('urlHrefs', $urlHrefs);
            return $this->fetch();
        }
        if($expertId){
            $search['expert_id'] = $expertId;
        }
        $expertLogic = ExpertLogic::newObj();
        return json($expertLogic->load($search, $page, $rows, $sort, $order));
    }
    public function save($expertId=0){
        $expertLogic = ExpertLogic::newObj();
        if(request()->isGet()){
            if(!$expertId){
                $infos = $expertLogic->getDefaultInfos();
            }else{
                $infos = $expertLogic->getInfos($expertId);
            }
            if(!$infos){
                return $this->fetch('common/error', ['msg'=>'无法找到该专家']);
            }
            $this->assign('infos', $infos);
            //分类
            $categories = Db::table('categories')->column('name', 'id');
            $this->assign('categories', $categories);
            //咨询对象
            $targets = Db::table('expert_target')->column('target', 'id');
            $this->assign('targets', $targets);
            //擅长领域
            $fields = Db::table('expert_field')->field('id as field_id, field')->select();
            foreach($fields as &$field){
                $fieldId = $field['field_id'];
                $fieldItems = Db::table('expert_field_item')->where('field_id', $fieldId)->field('id as field_item_id, field_item')->select();
                $field['field_items'] = $fieldItems;
            }
            $this->assign('fields', $fields);
            //预约工作日
            $appointIntervals = [];
            $startHour = IndexDefs::APPOINT_START_TIME;
            $endHour = IndexDefs::APPOINT_END_TIME;
            $nowDate = date('Y-m-d');
            while($startHour < $endHour){
                $nextHour = date('H:i', strtotime($nowDate . ' ' . $startHour . ':00' . ' +15 minutes'));
                $appointIntervals[] = $startHour . '-' . $nextHour;
                $startHour = $nextHour;
            }
            $this->assign('appointIntervals', $appointIntervals);
            return $this->fetch();
        }
        $infos = input('post.infos/a');
        try {
            if (!$expertId) {
                $expertLogic->add($infos);
            } else {
                $expertLogic->edit($expertId, $infos);
            }
            return ajaxSuccess();
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }
    public function delete($expertId){
        $expertLogic = ExpertLogic::newObj();
        try {
            $expertLogic->delete($expertId);
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess();
    }
    public function deleteForce($expertId){
        $expertLogic = ExpertLogic::newObj();
        try {
            $expertLogic->deleteForce($expertId);
        }catch (WException $e){
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess();
    }
    public function restore($expertId){
        $expertLogic = ExpertLogic::newObj();
        try {
            $expertLogic->restore($expertId);
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess();
    }
    public function view($expertId){
        $expertLogic = ExpertLogic::newObj();
        $infos = $expertLogic->getInfos($expertId);
        if(!$infos){
            return $this->fetch('common/error', ['msg'=>'无法找到该专家']);
        }
        //分类
        $infos['categoryNames'] = [];
        foreach($infos['categoryIds'] as $categoryId){
            $categoryName = Db::table('categories')->where('id', $categoryId)->value('name');
            if($categoryName){
                $infos['categoryNames'][] = $categoryName;
            }
        }
        //咨询对象
        $infos['targetNames'] = [];
        foreach($infos['targetIds'] as $targetId){
            $targetName = Db::table('expert_target')->where('id', $targetId)->value('target');
            if($targetName){
                $infos['targetNames'][] = $targetName;
            }
        }
        //擅长领域
        $infos['fields'] = [];
        foreach($infos['fieldItemIds'] as $fieldItemId){
            $items = Db::table('expert_field_item')->alias('EFI')
                ->join('expert_field EF', 'EFI.field_id=EF.id')
                ->where('EFI.id',$fieldItemId)
                ->field('EFI.field_item,EF.field')
                ->select();
            foreach($items as $item){
                if(!isset($infos['fields'][$item['field']])){
                    $infos['fields'][$item['field']] = [];
                }
                $infos['fields'][$item['field']][] = $item['field_item'];
            }
        }
        //预约工作日
        $appointIntervals = [];
        $startHour = IndexDefs::APPOINT_START_TIME;
        $endHour = IndexDefs::APPOINT_END_TIME;
        $nowDate = date('Y-m-d');
        while($startHour < $endHour){
            $nextHour = date('H:i', strtotime($nowDate . ' ' . $startHour . ':00' . ' +15 minutes'));
            $appointIntervals[] = $startHour . '-' . $nextHour;
            $startHour = $nextHour;
        }
        $this->assign('appointIntervals', $appointIntervals);

        $this->assign('infos', $infos);
        return $this->fetch();
    }
    /******************************************************************************************************************/
    public function appointment($expertId=0){
        if(request()->isGet()){
            $this->assign('expertId', $expertId);
            return $this->fetch();
        }
    }
    public function appointmentSchedule($expertId=0){
        if(request()->isGet()){
            //未来7天的预约情况，包括今天
            $dateFields = [];
            $nowDate = date('Y-m-d');
            for($i=0; $i<7; $i++){
                $weekDay = IndexDefs::$appointDayDefs[date('w', strtotime($nowDate))];
                $dateFields[] = $nowDate . '(' . $weekDay . ')';
                $nowDate = date('Y-m-d', strtotime($nowDate . ' +1 day'));
            }
            $this->assign('dateFields', $dateFields);
            /**********************************************************************************************************/
            $appointments = [];
            foreach(IndexDefs::$appointIntervalDefs as $interval){
                $appointment = ['interval'=>$interval, 'dateOrders'=>[]];
                $nowDate = date('Y-m-d');
                for($i=0; $i<7; $i++) {
                    $orders = Db::table('expert_order')->alias('EO')
                        ->join('customer C', 'EO.customer_id=C.id')
                        ->where(['EO.expert_id' => $expertId, 'EO.appoint_date' => $nowDate, 'EO.appoint_time' => $interval])
                        ->field('EO.order_no,EO.linkman,EO.cellphone,EO.appoint_duration,EO.status,C.nickname,C.real_name,C.cellphone as customer_cellphone')
                        ->select();
                    $appointment['dateOrders'][$nowDate] = $orders;
                    $nowDate = date('Y-m-d', strtotime($nowDate . ' +1 day'));
                }
                $appointments[] = $appointment;
            }
            $this->assign('appointments', $appointments);
            return $this->fetch();
        }
    }
    /******************************************************************************************************************/
    public function manageAppointment($expertId=0, $page=1, $rows=DEFAULT_PAGE_ROWS){
        if(request()->isGet()){
            $this->assign('expertId', $expertId);
            //15分钟一个标识
            $appointIntervals = [];
            $startHour = IndexDefs::APPOINT_START_TIME;
            $endHour = IndexDefs::APPOINT_END_TIME;
            $nowDate = date('Y-m-d');
            while($startHour < $endHour){
                $nextHour = date('H:i', strtotime($nowDate . ' ' . $startHour . ':00' . ' +15 minutes'));
                $appointIntervals[] = $startHour . '-' . $nextHour;
                $startHour = $nextHour;
            }
            $this->assign('appointIntervals', $appointIntervals);
            return $this->fetch();
        }
        $today = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime($today . ' +' . ($page-1)*$rows . ' days'));
        $index = 0;
        $nowDate = $startDate;
        $appointments = [];
        while($index < $rows){
            $weekDay = date('w', strtotime($nowDate));
            $weekDayText = IndexDefs::$appointDayDefs[$weekDay];
            $appointment = ['week_day_text'=>$nowDate . '(' . $weekDayText . ')', 'date'=>$nowDate];
            $appointments[] = $appointment;

            $nowDate = date('Y-m-d', strtotime($nowDate . ' +1 day'));
            $index++;
        }
        return json($appointments);
    }
    public function manageAppointmentInterval($expertId=0, $date){
        if(request()->isGet()){
            $this->assign('expertId', $expertId);
            $this->assign('date', $date);
            //15分钟一个标识
            $appointIntervals = [];
            $startHour = IndexDefs::APPOINT_START_TIME;
            $endHour = IndexDefs::APPOINT_END_TIME;
            $nowDate = date('Y-m-d');
            while($startHour < $endHour){
                $nextHour = date('H:i', strtotime($nowDate . ' ' . $startHour . ':00' . ' +15 minutes'));
                $appointIntervals[] = $startHour . '-' . $nextHour;
                $startHour = $nextHour;
            }
            $this->assign('appointIntervals', $appointIntervals);
            $conditions = ['EO.expert_id' => $expertId,
                'EO.appoint_date' => $date,
                'EO.status'=>['in', [IndexDefs::ORDER_PENDING_STATUS, IndexDefs::ORDER_APPOINTED_STATUS, IndexDefs::ORDER_FINISH_STATUS]]];
            $orders = Db::table('expert_order')->alias('EO')->join('customer C', 'EO.customer_id=C.id')
                ->where($conditions)
                ->field('EO.order_no,
                    EO.linkman,
                    EO.cellphone,
                    EO.appoint_date,
                    EO.appoint_time,
                    EO.appoint_duration,
                    EO.appoint_mode,
                    EO.status,
                    C.nickname,
                    C.real_name,
                    C.cellphone as customer_cellphone')->select();
            $this->assign('appointOrders', $orders);
            return $this->fetch();
        }
    }
    /******************************************************************************************************************/
    public function manageSchedule($expertId=0){
        if(request()->isGet()){
            $this->assign('expertId', $expertId);
            return $this->fetch();
        }
    }
    /******************************************************************************************************************/
    public function schedule($expertId=0){
        if(request()->isGet()){
            $this->assign('expertId', $expertId);
            //15分钟一个标识
            $appointIntervals = [];
            $startHour = IndexDefs::APPOINT_START_TIME;
            $endHour = IndexDefs::APPOINT_END_TIME;
            $nowDate = date('Y-m-d');
            while($startHour < $endHour){
                $nextHour = date('H:i', strtotime($nowDate . ' ' . $startHour . ':00' . ' +15 minutes'));
                $appointIntervals[] = $startHour . '-' . $nextHour;
                $startHour = $nextHour;
            }
            $this->assign('appointIntervals', $appointIntervals);

            $expertLogic = ExpertLogic::newObj();
            $infos = $expertLogic->getInfos($expertId);
            if(!$infos){
                return $this->fetch('common/error', ['msg'=>'无法找到该专家']);
            }
            $this->assign('infos', $infos);

            return $this->fetch();
        }
    }
    public function defaultSchedule($expertId, $weekDay){
        ExpertLogic::I()->defaultSchedule($expertId, $weekDay);
        return ajaxSuccess();
    }
    public function set45AppointTime($expertId, $weekDay, $intervals){
        try{
            ExpertLogic::I()->set45AppointTime($expertId, $weekDay, $intervals);
            return ajaxSuccess();
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function set15AppointTime($expertId, $weekDay, $intervals){
        try{
            ExpertLogic::I()->set15AppointTime($expertId, $weekDay, $intervals);
            return ajaxSuccess();
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function cancelAppointTime($expertId, $weekDay, $intervals){
        try{
            ExpertLogic::I()->cancelAppointTime($expertId, $weekDay, $intervals);
            return ajaxSuccess();
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function qrcode($id){
        if ($this->request->isGet()) {
            $expertLogic = ExpertLogic::newObj();
            $expert = $expertLogic->getInfos($id);
            if(!$expert){
                return $this->fetch('common/missing');
            }
            $this->assign('id', $id);
            $this->assign('name', $expert['real_name']);
            $this->assign('qrcode', $expert['qrcode']);
            return $this->fetch();
        }
        try{
            $path = ExpertLogic::newObj()->qrcode($id);
            return ajaxSuccess('生成二维码成功', $path);
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
}