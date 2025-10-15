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
use app\index\logic\SurveyOrganization as SurveyOrganizationLogic;
use app\index\logic\Customer as CustomerLogic;

class SurveyOrganization extends Common{
    public function index($surveyId){
        if(request()->isGet()) {
            $this->assign('surveyId', $surveyId);
            $urlHref = [
                'index'=>url('index/SurveyOrganization/index', ['surveyId'=>$surveyId]),
                'organizationAdd'=>url('index/SurveyOrganization/add', ['surveyId'=>$surveyId]),
                'organizationUpdate'=>url('index/SurveyOrganization/update', ['surveyId'=>$surveyId]),
                'organizationDelete'=>url('index/SurveyOrganization/delete', ['surveyId'=>$surveyId]),
                'organizationUp'=>url('index/SurveyOrganization/up', ['surveyId'=>$surveyId]),
                'organizationDown'=>url('index/SurveyOrganization/down', ['surveyId'=>$surveyId]),
            ];
            $this->assign('urlHrefs', $urlHref);
            return $this->fetch();
        }
        return json(SurveyOrganizationLogic::I()->loadTreeDatas($surveyId));
    }
    public function add($parentId, $surveyId){
        $name = input('post.name');
        $result = SurveyOrganizationLogic::I()->add($surveyId, $name, $parentId);
        if($result){
            return ajaxSuccess();
        }else{
            return ajaxError();
        }
    }
    public function update($id){
        $name = input('post.name');
        $result = SurveyOrganizationLogic::I()->update($id, $name);
        if($result){
            return ajaxSuccess();
        }else{
            return ajaxError();
        }
    }
    public function delete($surveyId, $id){
        $result = SurveyOrganizationLogic::I()->delete($surveyId, $id);
        if($result){
            return ajaxSuccess();
        }else{
            return ajaxError();
        }
    }
    public function up($id){
        SurveyOrganizationLogic::I()->up($id);
        return ajaxSuccess();
    }
    public function down($id){
        SurveyOrganizationLogic::I()->down($id);
        return ajaxSuccess();
    }
    public function changeLevel($id, $parentId){
        $result = SurveyOrganizationLogic::I()->changeLevel($id, $parentId);
        if($result){
            return ajaxSuccess();
        }else{
            return ajaxError();
        }
    }
    /*
    public function customers($organizationId, $search=[],$page=1,$rows=DEFAULT_PAGE_ROWS,$sort='',$order=''){
        if(request()->isGet()){
            $this->assign('urlHrefs', [
                'index'=>url('index/SurveyOrganization/customers', ['organizationId'=>$organizationId]),
            ]);
            return $this->fetch('customer/index');
        }
        $customerLogic = CustomerLogic::newObj();
        $search['organization_id'] = $organizationId;
        return json($customerLogic->load($search, $page, $rows, $sort, $order));
    }*/
}
