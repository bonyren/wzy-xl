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
namespace app\index\behavior;
use think\Log;
use think\Config;

class LoadConfig{
    public function run(&$params){
        Config::set([
            'wx'=>[
                'official_account'=>array_merge(config('wx.official_account'), [
                    'app_id'=>systemSetting('WX_OFFICE_ACCOUNT_APP_ID'),
                    'secret'=>systemSetting('WX_OFFICE_ACCOUNT_APP_SECRET'),
                    'token'=>systemSetting('WX_OFFICE_ACCOUNT_SERVER_TOKEN'),
                ]),
                'payment'=>array_merge(config('wx.payment'), [
                    'mch_id'=>systemSetting('WX_PAYMENT_MCH_ID'),
                    'key'=>systemSetting('WX_PAYMENT_KEY')
                ]),
                'mini_program'=>array_merge(config('wx.mini_program'), [
                    'app_id'=>systemSetting('WX_MINI_PROGRAM_APP_ID'),
                    'secret'=>systemSetting('WX_MINI_PROGRAM_APP_SECRET')
                ])
            ]
        ]);
        //Log::notice("wx config behavior: " . var_export(config('wx'), true));
    }
}