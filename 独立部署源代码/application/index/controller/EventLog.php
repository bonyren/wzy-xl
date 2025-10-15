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
use app\index\service\EventLogs as EventLogsService;
use think\Controller;
use think\Db;
use think\Log;

class EventLog extends Common{
    public function index($search=[],$page=1,$rows=DEFAULT_PAGE_ROWS){
        if($this->request->isGet()){
            return $this->fetch();
        }
        $conditions = [];
        if(!emptyInArray($search, 'severity')){
            $conditions['E.severity'] = $search['severity'];
        }
        $total = Db::table('event_logs')->alias('E')->where($conditions)->count();
        $records = Db::table('event_logs')->alias('E')
            ->join('admins A', 'E.user_id=A.admin_id', 'LEFT')
            ->where($conditions)
            ->page($page, $rows)
            ->order('entered desc')
            ->field('E.*,A.realname')
            ->select();
        return json([
            'total'=>$total,
            'rows'=>$records
        ]);
    }
}