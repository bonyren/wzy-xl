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
use app\index\logic\Organization as OrganizationLogic;
use app\index\logic\Customer as CustomerLogic;

class Organization extends Common{
    public function index(){
        if(request()->isGet()) {
            $urlHref = [
                'index'=>url('index/Organization/index'),
                'organizationAdd'=>url('index/Organization/add'),
                'organizationUpdate'=>url('index/Organization/update'),
                'organizationDelete'=>url('index/Organization/delete'),
                'organizationUp'=>url('index/Organization/up'),
                'organizationDown'=>url('index/Organization/down'),
            ];
            $this->assign('urlHrefs', $urlHref);
            return $this->fetch();
        }
        return json(OrganizationLogic::I()->loadTreeDatas());
    }
    public function add($parentId){
        $name = input('post.name');
        $result = OrganizationLogic::I()->add($name, $parentId);
        if($result){
            return ajaxSuccess();
        }else{
            return ajaxError();
        }
    }
    public function update($id){
        $name = input('post.name');
        $result = OrganizationLogic::I()->update($id, $name);
        if($result){
            return ajaxSuccess();
        }else{
            return ajaxError();
        }
    }
    public function delete($id){
        $result = OrganizationLogic::I()->delete($id);
        if($result){
            return ajaxSuccess();
        }else{
            return ajaxError();
        }
    }
    public function up($id){
        OrganizationLogic::I()->up($id);
        return ajaxSuccess();
    }
    public function down($id){
        OrganizationLogic::I()->down($id);
        return ajaxSuccess();
    }
    public function changeLevel($id, $parentId){
        $result = OrganizationLogic::I()->changeLevel($id, $parentId);
        if($result){
            return ajaxSuccess();
        }else{
            return ajaxError();
        }
    }
    public function customers($organizationId, $search=[],$page=1,$rows=DEFAULT_PAGE_ROWS,$sort='',$order=''){
        if(request()->isGet()){
            $this->assign('urlHrefs', [
                'index'=>url('index/Organization/customers', ['organizationId'=>$organizationId]),
            ]);
            return $this->fetch('customer/index');
        }
        $customerLogic = CustomerLogic::newObj();
        $search['organization_id'] = $organizationId;
        return json($customerLogic->load($search, $page, $rows, $sort, $order));
    }
}
