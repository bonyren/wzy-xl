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
namespace app\common\behavior;
use think\Log;
use think\Config;
use think\Env;
use app\common\service\Redis as RedisService;

class AppEnd{
    public function run(&$params){
        //redis
        if (Env::get('redis_enable')) {
            RedisService::fini();
        }
    }
}