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
namespace app\cli\controller;

use app\cli\model\JobQueue as JobQueueModel;
use app\cli\model\Scheduler as SchedulerModel;
use think\Log;

class Dispatcher extends Common
{
    public $max_jobs = 10; //每次最多运行几个任务
    public $max_runtime = 60;

    public $timeout = 7200; //2小时：单个job最大执行时间

    public function __construct(){
        parent::_initialize();
    }

    public function cleanup(){
        $date = date('Y-m-d H:i:s',time() - $this->timeout);
        $rows = JobQueueModel::where(['status'=>JobQueueModel::JOB_STATUS_RUNNING,'execute_time'=>['elt',$date]])->select();
        foreach ($rows as $job) {
            $job->failJob('执行超时，强制设为失败');
        }
    }

    public function getMyId(){
        return 'CRON:'.getmypid();
    }

    public function run()
    {
        //清理超时job
        $this->cleanup();
        $this->runSchedulers();

        $cutoff = time() + $this->max_runtime;
        $myid = $this->getMyId();
        for($index=0; $index<$this->max_jobs; $index++) {
            $job = $this->nextJob($myid);
            if(empty($job)) {
                break;
            }
            if(!$job->runJob()) {
                Log::record("计划任务： Job {$job->id} ({$job->name}) 运行失败",Log::ERROR);
            }
            if(time() >= $cutoff) {
                break;
            }
        }
    }

    public function runSchedulers(){
        $schedulers = SchedulerModel::where(['disabled'=>0,'deleted'=>0])->select();
        if(empty($schedulers)) {
            Log::notice('-----> No Schedulers found');
        } else {
            foreach($schedulers as $scheduler) {
                if($this->validateScheduler($scheduler)) {
                    $this->joinJob($scheduler);
                }
            }
        }
    }

    //校验计划任务是否符合运行条件
    public function validateScheduler($scheduler){
        if(empty($scheduler->id)) {
            return false;
        }
        $job = JobQueueModel::where(['scheduler_id'=>$scheduler->id,'status'=>['lt',JobQueueModel::JOB_STATUS_DONE]])->limit(1)->field('id')->find();
        if ($job && $job->id) {
            //已有任务在排队或者运行
            return false;
        }
        // 格式化当前时间戳并转成 分 时 日 月 周 格式
        // i  有前导零的分钟数 00 到 59>
        // G  小时，24 小时格式，没有前导零 0 到 23
        // j  月份中的第几天，没有前导零 1 到 31
        // n  数字表示的月份，没有前导零 1 到 12
        // w  星期中的第几天，数字表示 0（表示星期天）到 6（表示星期六）
        $now = explode(' ', date('i G j n w', time()));
        $valid_times = $this->parseInterval($scheduler->interval);
        //循环当前时间，如果有一项不符合则不能向下执行
        foreach ($now as $k => $piece) {
            if (!in_array($piece, $valid_times[$k])) {
                return false;
            }
        }
        return true;
    }

    //解析运行间隔
    public function parseInterval($interval)
    {
        $times = [];
        $slices = explode(' ', $interval, 5); //['*/5', '*', '*', '*', '*']
        //针对每个时间类型进行解析
        $dimensions = array(
            array(0, 59), //Minutes
            array(0, 23), //Hours
            array(1, 31), //Days
            array(1, 12), //Months
            array(0, 6),  //Weekdays
        );
        foreach ($slices as $key => $item) {
            // 标记是哪种命令格式，通过使用的crontab命令可以分为两大类
            // 1.每几分钟或每小时这样的 */10 * * * *
            // 2.几点几分这样的 10,20,30-50 * * * *
            list($repeat, $every) = explode('/', $item, 2) + [false, 1];
            if ($repeat === '*') {
                $times[$key] = range($dimensions[$key][0], $dimensions[$key][1]);
            } else {
                // 处理逗号拼接的命令
                $tmpRaw = explode(',', $item);
                foreach ($tmpRaw as $tmp) {
                    // 处理10-20这样范围的命令
                    $tmp = explode('-', $tmp, 2);
                    if (count($tmp) == 2) {
                        $times[$key] = array_merge($times[$key], range($tmp[0], $tmp[1]));
                    } else {
                        $times[$key][] = $tmp[0];
                    }
                }
            }
            // 判断*/10 这种类型的
            if ($every > 1) {
                foreach ($times[$key] as $k => $v) {
                    if ($v % $every != 0) {
                        unset($times[$key][$k]);
                    }
                }
            }
        }
        return $times;
    }

    //加入新的job
    public function joinJob($scheduler){
        $job = new JobQueueModel();
        $job->scheduler_id = $scheduler->id;
        $job->name = $scheduler->name;
        $job->target = $scheduler->job;
        $job->status = JobQueueModel::JOB_STATUS_QUEUED;
        $job->result = JobQueueModel::JOB_RESULT_PENDING;
        $job->save();
        return $job;
    }

    public function nextJob($client_id){
        $now = date('Y-m-d H:i:s');
        $job = JobQueueModel::where(['status'=>JobQueueModel::JOB_STATUS_QUEUED,'execute_time'=>['elt',$now]])
            ->limit(1)
            ->order('id ASC')
            ->find();
        if(empty($job)) {
            return null;
        }
        $job->status = JobQueueModel::JOB_STATUS_RUNNING;
        $job->client = $client_id;
        //开始执行时间
        $job->execute_time = $now;
        $job->save();
        return $job;
    }
}