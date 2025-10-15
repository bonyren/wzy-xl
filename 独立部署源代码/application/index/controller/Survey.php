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
use app\Defs;
use app\index\logic\Defs as IndexDefs;
use app\index\logic\Survey as SurveyLogic;
use app\index\logic\SurveyOrganization as SurveyOrganizationLogic;
use app\index\service\OperationLogs;

class Survey extends Common{
    public function index($search=[], $page=1, $rows=DEFAULT_PAGE_ROWS, $sort='', $order=''){
        if ($this->request->isGet()) {
            return $this->fetch();
        }
        return json(SurveyLogic::I()->loadSurvey($search, $page, $rows, $sort, $order));
    }
    public function save($id){
        if($this->request->isGet()){
            if(empty($id)){
                //new
                $formData = SurveyLogic::I()->getDefaultSurvey();
            }else{
                //update
                $formData = SurveyLogic::I()->getSurvey($id);
                if(empty($formData)){
                    return $this->fetch('common/missing');
                }    
            }
            $this->assign('id', $id);
            $this->assign('formData', $formData);
            return $this->fetch();
        }
        $formData = input('post.formData/a');
        if (empty($formData['subjects'])) {
            return ajaxError('请选择量表');
        }
        try{
            SurveyLogic::I()->saveSurvey($id, $formData);
        }catch(\Exception $e){
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess();
    }
    public function restore($id){
        Db::table('survey')->where(['id'=>$id])->update(['delete_flag'=>0]);
        //操作日志
        $name = Db::table('survey')->where(['id' => $id])->value('name');
        OperationLogs::I()->surveyLog('恢复普查', $name, OperationLogs::OPT_RESTORE_TYPE, $id);
        return ajaxSuccess('恢复成功');
    }
    public function view($id){
        $survey = SurveyLogic::I()->getSurvey($id);
        if(empty($survey)){
            return $this->fetch('common/missing');
        }
        $this->assign('survey', $survey);
        return $this->fetch();
    }
    public function delete($id){
        SurveyLogic::I()->deleteSurvey($id);
        //操作日志
        $name = Db::table('survey')->where(['id' => $id])->value('name');
        OperationLogs::I()->surveyLog('删除普查', $name, OperationLogs::OPT_DELETE_TYPE, $id);
        return ajaxSuccess();
    }
    public function deleteForce($id){
        SurveyLogic::I()->deleteForceSurvey($id);
        return ajaxSuccess();
    }
}