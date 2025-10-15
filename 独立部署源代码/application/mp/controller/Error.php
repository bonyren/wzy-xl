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
namespace app\mp\controller;
use think\Request;
use think\Log;
use think\Db;
use think\Session;
use think\Cookie;
use think\Controller;
use app\mp\service\Subjects;

class Error extends Controller{
    public function index(){
        if($this->request->isAjax()){
            abort(403, "禁止访问");
        }else{
            exit($this->fetch('common/error', ['msg'=>'禁止访问']));
            //header('HTTP/1.1 403 Forbidden');
            //exit();
        }
        //return action('mp/Index/index');
    }
}