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
use app\index\logic\Help as HelpLogic;
class Help extends Common{
	public function index(){
	}
    public function help($topicId){
        $helpLogic = HelpLogic::newObj();
        $tpl = $helpLogic->getTpl($topicId);
        if(!$tpl){
            return $this->fetch('common/error', ['msg'=>'无法找到该项目']);
        }
        return $this->fetch($tpl);
    }
}