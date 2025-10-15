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
namespace app\cli\controller;
use app\cli\logic\BackupDB;
use app\cli\logic\CleanServer;
use app\cli\logic\System;
use app\cli\logic\Orders;
use think\Log;
use app\common\service\WException;

/**
 * 各种任务入口在此定义
 * @package app\scheduler\controller
 */
class Jobs
{
    //数据库备份
    public function backupDB() {
        Log::notice('Jobs::backupDB');
        $backup = new BackupDB();
        $resultMsg = '';
        $resultMsg .= $backup->cleanUp();
        $resultMsg .= '; ';
        $resultMsg .= $backup->backup();
        return $resultMsg;

    }
    //清理服务器，包括数据库过期数据，系统日志文件，应用日志文件
    public function cleanServer(){
        Log::notice('Jobs::cleanServer');
        $clean = new CleanServer();
        return $clean->clean();
    }
    //检测数据库慢查询
    public function detectSlowQuery(){
        Log::notice('Jobs::detectSlowQuery');
        $system = new System();
        return $system->detectSlowQuery();
    }
    //测评报告pdf文件
    public function generateReportPdfs(){
        Log::notice('Jobs::generateReportPdfs');
        $orders = new Orders();
        return $orders->generateReportPdfs();
    }
}