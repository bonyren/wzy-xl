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
use think\Db;
use think\Log;
class OperationLogs extends Common
{
    public function index($search=[],
                          $page=1,
                          $rows=DEFAULT_PAGE_ROWS,
                          $sort='',
                          $order=''
    ){
        if(request()->isGet()){
            $urlHrefs = [
                'index'=>url('index/OperationLogs/index'),
            ];
            $this->assign('urlHrefs', $urlHrefs);
            return $this->fetch();
        }
        if($sort == 'entered'){
            $order = 'entered ' . $order;
        }else{
            $order = 'id desc';
        }
        $conditions = [];
        if(isset($search['category']) && $search['category'] !== ''){
            $conditions['L.category'] = $search['category'];
        }
        if(!empty($search['type'])){
            $conditions['L.type'] = $search['type'];
        }
        $whereKeyword = '1=1';
        if(!emptyInArray($search, 'keyword')){
            //$conditions['desc'] = ['like', '%'.$search['keyword'].'%'];
            $whereKeyword = "(match(L.title, L.content) against ('" . addslashes($search['keyword']) . "'))";
        }
        $totalCount = Db::table('operation_logs')
            ->alias('L')
            ->where($conditions)
            ->where($whereKeyword)
            ->count();
        $records = Db::table('operation_logs')
            ->alias('L')
            ->where($conditions)
            ->where($whereKeyword)
            ->page($page, $rows)
            ->order($order)
            ->field('L.id,L.category,L.type,L.entered,L.title,left(L.content,128) as content,L.changed_by,L.device,L.ip,L.channel')
            ->select();
        return json([
            'total'=>$totalCount,
            'rows'=>$records
        ]);
    }
    public function view($id){
        $log = Db::table('operation_logs')->where('id', $id)->field(true)->find();
        if(empty($log)){
            return $this->fetch('common/missing');
        }
        $this->assign('log', $log);
        return $this->fetch();
    }
}