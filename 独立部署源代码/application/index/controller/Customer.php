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
use app\index\logic\Defs;
use app\index\logic\Config as ConfigLogic;
use app\index\logic\Customer as CustomerLogic;
use app\index\logic\Organization as OrganizationLogic;

class Customer extends Common
{
    public function index($search=[],$page=1,$rows=DEFAULT_PAGE_ROWS,$sort='',$order=''){
        if(request()->isGet()){
            $this->assign('urlHrefs', [
                'index'=>url('index/Customer/index')
            ]);
            return $this->fetch();
        }
        $customerLogic = CustomerLogic::newObj();
        return json($customerLogic->load($search, $page, $rows, $sort, $order));
    }
    public function view($customerId){
        $customerLogic = CustomerLogic::newObj();
        $infos = $customerLogic->getInfos($customerId);
        if(!$infos){
            return $this->fetch('common/missing');
        }
        $infos['organization'] = OrganizationLogic::I()->loadFullText($infos['organization_id']);
        $this->assign('infos', $infos);
        return $this->fetch();
    }
    public function save($customerId){
        $customerLogic = CustomerLogic::newObj();
        if(request()->isGet()){
            $infos = $customerLogic->getInfos($customerId);
            if(!$infos){
                return $this->fetch('common/missing');
            }
            $this->assign('formData', $infos);
            return $this->fetch();
        }
        $formData = input('post.formData/a');
        try{
            $customerLogic->save($customerId, $formData);
        }catch(\Exception $e){
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess();
    }
}