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
use app\Defs;

class UniApp extends Common{
    public function users($search=[],$page=1,$rows=DEFAULT_PAGE_ROWS,$sort='',$order=''){
        if(request()->isGet()){
            $this->assign('urlHrefs', [
                'users'=>url('index/UniApp/users')
            ]);
            return $this->fetch();
        }
        $conditions = [];
        if(!empty($search['nickname'])){
            $conditions['nickname'] = ['like', '%' . $search['nickname'] . '%'];
        }
        $order = 'id desc';
        $total = Db::table('uni_app_users')->where($conditions)->count();
        $records = Db::table('uni_app_users')->where($conditions)
            ->page($page, $rows)
            ->order($order)
            ->field(true)
            ->select();
        return json([
            'total'=>$total,
            'rows'=>$records
        ]);
    }
    public function orders($search=[],$page=1,$rows=DEFAULT_PAGE_ROWS,$sort='',$order=''){
        if(request()->isGet()){
            $this->assign('urlHrefs', [
                'orders'=>url('index/UniApp/orders')
            ]);
            return $this->fetch();
        }
        $conditions = [];
        if(!empty($search['order_no'])){
            $conditions['order_no'] = $search['order_no'];
        }
        $order = 'O.order_time desc';
        $total = Db::table('uni_app_orders')->alias('O')->where($conditions)->count();
        $records = Db::table('uni_app_orders')->alias('O')
            ->join('uni_app_users U', 'O.user_id=U.id', 'LEFT')
            ->join('subject S', 'O.subject_id=S.id', 'LEFT')
            ->where($conditions)
            ->page($page, $rows)
            ->order($order)
            ->field('O.order_no,O.user_id,O.subject_id,O.order_amount,O.order_time,O.finish_time,O.pay_status,O.finished,
                U.openid,U.nickname,U.channel,S.name as subject_name')
            ->select();
        return json([
            'total'=>$total,
            'rows'=>$records
        ]);
    }
}