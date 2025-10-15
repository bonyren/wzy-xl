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
use think\Db;
class Storage{
    protected function __construct(){  
    }
    protected static $_instance = null;
    public static function getInstance(){
        if(empty(self::$storageInstance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    const WZYER_APP_ID = 'WZYER_ID';
    const WZYER_AUTH_KEY = 'WZYER_AUTH_KEY';
    const WZYER_BIND_TIME = 'WZYER_BIND_TIME';
    public function __get($name)
    {
        $value = Db::table('redis')->where('key', $name)->value('value');
        return $value;
    }
    public function __set($name, $value)
    {
        if($value === null){
            //删除
            Db::table('redis')->where('key', $name)->delete();
            return;
        }
        $row = Db::table('redis')->where('key', $name)->find();
        if($row){
            Db::table('redis')->where('key', $name)->setField('value', $value);
        }else{
            Db::table('redis')->insert([
                'key'=>$name,
                'value'=>$value
            ]);
        }
    }
}