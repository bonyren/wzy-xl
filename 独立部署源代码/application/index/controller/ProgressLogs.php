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
use app\index\logic\Attachments as AttachmentsLogic;
use app\index\logic\ProgressLogs as ProgressLogsLogic;
use app\index\controller\Attachments as AttachmentsController;

class ProgressLogs extends  Common{
    public function index($search=[],
                          $page=1,
                          $rows=DEFAULT_PAGE_ROWS,
                          $sort='progress_log_id',
                          $order='desc',
                          $category=ProgressLogsLogic::PROGRESS_LOG_ALL_CATEGORY,
                          $externalId=0,
                          $readOnly=0)
    {
        $src = intval($_GET['src']);  //调用来源module
        if (request()->isPost()) {
            $search['src'] = $src;
            $progressLogsLogic = ProgressLogsLogic::newObj();
            return json($progressLogsLogic->load($search, $page, $rows, $sort, $order, $category, $externalId));
        }

        $uniqid = generateUniqid();
        $urlHrefs = [
            'index'=>url('index/ProgressLogs/index', ['category'=>$category,'externalId'=>$externalId,'src'=>$src]),
            'add'=>url('index/ProgressLogs/add', ['category'=>$category,'externalId'=>$externalId,'src'=>$src]),
            'delete'=>url('index/ProgressLogs/delete'),
            'attachments'=>url('index/Upload/attaches', [
                'src'=>$src,
                'attachmentType'=>AttachmentsLogic::ATTACH_PROGRESS_LOGS,
                'externalId'=>0,
                'uiStyle'=>AttachmentsController::ATTACHES_UI_LIGHT_STYLE,
                'callback'=>'progressLogsModule_' . $uniqid . '.onAttachmentsUploaded'
            ])
        ];
        $this->assign('urlHrefs', $urlHrefs);
        $this->assign('uniqid', $uniqid);
        $this->assign('readOnly', $readOnly);
        $this->assign('src', $src);
        $bindValues = [
            'curDate'=>date('Y-m-d'),
        ];
        $this->assign('bindValues', $bindValues);
        return $this->fetch();
    }
    public function light($search=[],
                          $page=1,
                          $rows=DEFAULT_PAGE_ROWS,
                          $sort='progress_log_id',
                          $order='desc',
                          $category=ProgressLogsLogic::PROGRESS_LOG_ALL_CATEGORY,
                          $externalId=0,
                          $readOnly=0)
    {
        $src = intval($_GET['src']);  //调用来源module
        $uniqid = generateUniqid();
        $urlHrefs = [
            'index'=>url('index/ProgressLogs/index', ['category'=>$category,'externalId'=>$externalId,'src'=>$src]),
            'add'=>url('index/ProgressLogs/add', ['category'=>$category,'externalId'=>$externalId,'src'=>$src]),
            'delete'=>url('index/ProgressLogs/delete'),
        ];
        $this->assign('src', $src);
        $this->assign('urlHrefs', $urlHrefs);
        $this->assign('uniqid', $uniqid);
        $this->assign('readOnly', $readOnly);
        return $this->fetch();
    }

    public function add($category, $externalId, $src=0){
        if(request()->isGet()){
            $uniqid = generateUniqid();
            $this->assign('uniqid', $uniqid);
            $urlHrefs = [
                'attachments'=>url('index/Upload/attaches', ['attachmentType'=>AttachmentsLogic::ATTACH_PROGRESS_LOGS,
                    'src'=>$src,
                    'externalId'=>0,
                    'uiStyle'=>AttachmentsController::ATTACHES_UI_LIGHT_STYLE,
                    'callback'=>'progressLogsModuleAdd_' . $uniqid . '.onAttachmentsUploaded'
                ])
            ];
            $this->assign('urlHrefs', $urlHrefs);
            $bindValues = [
                'curDate'=>date('Y-m-d')
            ];
            $this->assign('bindValues', $bindValues);
            return $this->fetch();
        }
        $infos = input('post.infos/a');
        $infos['src'] = $src;
        $progressLogsLogic = ProgressLogsLogic::newObj();
        $result = $progressLogsLogic->add($category, $externalId, $infos);
        if($result){
            return ajaxSuccess();
        }else{
            return ajaxError();
        }
    }

    public function edit($id, $src=0) {
        if ($this->request->isPost()) {
            $data = input('post.infos/a');
            Db::table('progress_logs')->where('progress_log_id',$id)->update($data);
            return ajaxSuccess('修改成功');
        }
        $row = ProgressLogsLogic::I()->get($id);
        $this->assign('row', $row);
        $this->assign('attachment_url', url('index/Upload/attaches', [
            'src'=>$src,
            'attachmentType'=>AttachmentsLogic::ATTACH_PROGRESS_LOGS,
            'externalId'=>$id,
            'uiStyle'=>AttachmentsController::ATTACHES_UI_LIGHT_STYLE
        ]));
        return $this->fetch();
    }

    public function view($id)
    {
        $row = ProgressLogsLogic::I()->get($id);
        $this->assign('row', $row);
        $this->assign('attachment_url', url('index/Upload/viewAttaches', [
            'attachmentType'=>AttachmentsLogic::ATTACH_PROGRESS_LOGS,
            'externalId'=>$id,
            'uiStyle'=>AttachmentsController::ATTACHES_UI_LINK_STYLE
        ]));
        return $this->fetch();
    }

    public function delete($progressLogId){
        $progressLogsLogic = ProgressLogsLogic::newObj();
        $result = $progressLogsLogic->delete($progressLogId);
        if($result){
            return ajaxSuccess();
        }else{
            return ajaxError();
        }
    }
}