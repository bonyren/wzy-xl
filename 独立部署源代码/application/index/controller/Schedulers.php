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

use app\cli\model\JobQueue as JobQueueModel;
use app\cli\model\Scheduler as SchedulerModel;
use app\cli\logic\Scheduler as SchedulerLogic;
use think\Log;
use think\Debug;

class Schedulers extends Common
{
    public function index($page=1,$rows=DEFAULT_PAGE_ROWS) {
        if ($this->request->isGet()) {
            return $this->fetch();
        }
        $where = ['deleted'=>0];
        $total = SchedulerModel::where($where)->count();
        if (empty($total)) {
            return json([]);
        }
        $items = SchedulerModel::where($where)->page($page,$rows)->order('id desc')->select();
        return json(['total'=>$total, 'rows'=>$items]);
    }

    public function edit($id='') {
        if ($this->request->isGet()) {
            if ($id) {
                $row = SchedulerLogic::I()->getScheduler($id);
                if(!$row){
                    return $this->fetch('common/error', ['msg'=>'无法找到该计划任务']);
                }
            } else {
                $row = [
                    'id'=>0,
                    'name'=>'',
                    'disabled'=>0,
                    'job'=>'',
                    'interval'=>'',
                    'date_time_start'=>date('Y-m-d H:i'),
                    'date_time_end'=>''
                ];
            }
            return $this->fetch('',[
                'row' => $row
            ]);
        }
        $data = input('post.');
        $data['id'] = intval($id);
        SchedulerLogic::I()->saveScheduler($data);
        return ajaxSuccess('保存成功');
    }

    public function remove($id) {
        try {
            SchedulerModel::update(['deleted'=>1],['id'=>$id]);
        } catch (\Exception $e) {
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess('删除成功');
    }

    public function view($id) {
        $row = SchedulerLogic::I()->getScheduler($id);
        return $this->fetch('',[
            'row' => $row
        ]);
    }

    public function logs($page=1,$rows=DEFAULT_PAGE_ROWS,$scheduler_id=0) {
        if ($this->request->isGet()) {
            return $this->fetch('',['scheduler_id'=>$scheduler_id]);
        }
        $where = ['scheduler_id'=>$scheduler_id];
        $total = JobQueueModel::where($where)->count();
        if (empty($total)) {
            return json([]);
        }
        //return the model object array
        $items = JobQueueModel::where($where)->page($page, $rows)->order('id DESC')->select();
        return json(['total'=>$total, 'rows'=>$items]);
    }
}