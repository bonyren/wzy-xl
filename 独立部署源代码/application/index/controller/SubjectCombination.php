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
use app\Defs;
use think\Db;
use think\Log;
use app\common\service\WException;
use app\index\service\RequestContext;
use app\index\service\OperationLogs;
use app\index\logic\Defs as IndexDefs;
use app\index\logic\SubjectCombination as SubjectCombinationLogic;
/**
 * 组合测评管理
 * @package app\index\controller
 */
class SubjectCombination extends Common{
    public function index($page=1, $rows=DEFAULT_PAGE_ROWS, $sort='', $order=''){
        if ($this->request->isGet()) {
            return $this->fetch();
        }
        $search = input('post.search/a', []);
        return json(SubjectCombinationLogic::I()->loadSubjectCombination($search, $page, $rows, $sort, $order));
    }

    public function save($id = 0, $type = 0){
        if ($this->request->isGet()) {
            $row = [
                'id'=>0,
                'name'=>'',
                'banner'=>'',
                'qrcode'=>'',
                'description'=>'',
                'subjects'=>'',
                'status'=>IndexDefs::ENTITY_DRAFT_STATUS
            ];
            if($id){
                //修改
                $row = SubjectCombinationLogic::I()->getSubjectCombination($id);
                if(!$row){
                    return $this->fetch('common/missing');
                }
            }
            $this->assign('row', $row);
            return $this->fetch();
        }
        $data = input('post.data/a');
        try{
            SubjectCombinationLogic::I()->saveSubjectCombination($id, $data);
            return ajaxSuccess('保存成功');
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }

    public function remove($id){
        try{
            SubjectCombinationLogic::I()->deleteSubjectCombination($id);
            return ajaxSuccess('删除成功');
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function removeForce($id){
        try{
            SubjectCombinationLogic::I()->deleteForceSubjectCombination($id);
            return ajaxSuccess('删除成功');
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function restore($id){
        Db::table('subject_combination')->where(['id'=>$id])->update(['delete_flag'=>0]);
        //操作日志
        $name = Db::table('subject_combination')->where(['id' => $id])->value('name');
        OperationLogs::I()->subjecCombinationtLog('恢复组合量表', $name, OperationLogs::OPT_RESTORE_TYPE, $id);
        return ajaxSuccess('恢复成功');
    }

    public function view($id){
        $combination = SubjectCombinationLogic::I()->getSubjectCombination($id);
        if(!$combination){
            return $this->fetch('common/missing');
        }
        $this->assign('combination', $combination);
        return $this->fetch();
    }
    
    /**
     * 推送二维码
     *
     * @param  mixed $id
     * @return void
     */
    public function qrcode($id){
        $subject = SubjectCombinationLogic::I()->getSubjectCombination($id);
        if ($this->request->isGet()) {
            if(!$subject){
                return $this->fetch('common/missing');
            }
            $this->assign('id', $id);
            $this->assign('name', $subject['name']);
            $this->assign('qrcode', $subject['qrcode']);
            return $this->fetch();
        }
        try{
            $path = SubjectCombinationLogic::I()->qrcode($id);
            return ajaxSuccess('生成二维码成功', $path);
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
}