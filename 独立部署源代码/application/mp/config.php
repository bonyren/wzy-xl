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
//配置文件
return [
    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------
    'dispatch_success_tmpl'  => APP_PATH . 'mp' . DS . 'view' . DS . 'common' . DS . 'dispatch_jump.php',
    'dispatch_error_tmpl'    => APP_PATH . 'mp' . DS . 'view' . DS . 'common' . DS . 'dispatch_jump.php',
    'exception_tmpl'         => APP_PATH . 'mp' . DS . 'view' . DS . 'common' . DS . 'think_exception.php',
    'session'                => [
        // SESSION 前缀
        'prefix'         => 'wzyer-mp',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],
    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写
        'auto_rule'    => 1,
        // 模板路径
        'view_path'    => APP_PATH . 'mp' . DS  . 'view' . DS,
        // 模板后缀
        'view_suffix'  => 'php',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '<{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}>',
        // 标签库标签开始标记
        'taglib_begin' => '<{',
        // 标签库标签结束标记
        'taglib_end'   => '}>',
    ],
];