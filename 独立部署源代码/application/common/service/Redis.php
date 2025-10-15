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
namespace app\common\service;

class Redis{
    protected function __construct(){  
    }
    protected static $redisInstance = null;
    public static function init(){
        if(!extension_loaded('redis')){
            abort(500, "加载redis模块失败");
        }
        if(self::$redisInstance){
            return;
        }
        self::$redisInstance = new \Redis();
        $result = self::$redisInstance->connect('127.0.0.1', 6379);
        if(!$result){
            abort(500, "连接redis服务失败");
        }
    }
    public static function getInstance(){
        return self::$redisInstance;
    }
    public static function fini(){
        if(self::$redisInstance){
            self::$redisInstance->close();
            self::$redisInstance = null;
        }
    }
}