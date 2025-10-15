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
use app\index\logic\Defs as IndexDefs;
use think\Db;
use think\Log;
use app\index\logic\Subject as SubjectLogic;
use app\index\logic\Expert as ExpertLogic;
use app\index\service\RequestContext;

class Trash extends Common{
    public function subjects($search = [], $page = 1, $rows = DEFAULT_PAGE_ROWS, $sort = '', $order = ''){
        if($this->request->isGet()) {
            $this->assign('categories', SubjectLogic::I()->getAvailableCategories());
            return $this->fetch();
        }
        $search['delete_flag'] = 1;//删除
        $data = SubjectLogic::I()->load($search, $page, $rows, $sort, $order);
        return json($data);
    }
    public function subjectCombinations(){
        if($this->request->isGet()) {
            return $this->fetch();
        }
    }
    public function experts($search=[],$page=1,$rows=DEFAULT_PAGE_ROWS,$sort='',$order=''){
        if(request()->isGet()){
            return $this->fetch();
        }
        $expertLogic = ExpertLogic::newObj();
        $search['delete_flag'] = 1;//删除
        return json($expertLogic->load($search, $page, $rows, $sort, $order));
    }
    public function surveies(){
        if($this->request->isGet()) {
            return $this->fetch();
        }
    }
}
