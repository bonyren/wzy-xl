<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
define('SCHEMA', ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))?'https':'http');
// 定义应用目录
define('APP_PATH', __DIR__ . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR);
// 站点目录
define('SITE_DIR', dirname(__FILE__));

define('SCRIPT_DIR', (isset($_SERVER['SCRIPT_NAME']) ? rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/\\') : ''));

define('SITE_URL', isset($_SERVER['HTTP_HOST']) ? SCHEMA . '://' . $_SERVER['HTTP_HOST'] . SCRIPT_DIR : '');

define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');

define('STATIC_DIR', SITE_DIR . DIRECTORY_SEPARATOR . 'static');

define('UPLOAD_FOLDER', 'upload');
define('UPLOAD_DIR', SITE_DIR . DIRECTORY_SEPARATOR . UPLOAD_FOLDER);
define('UPLOAD_URL_ROOT', SITE_URL . '/upload/');

define('EXPORT_FOLDER', 'export');
define('EXPORT_DIR', SITE_DIR . DIRECTORY_SEPARATOR . EXPORT_FOLDER);
define('EXPORT_URL_ROOT', SITE_URL . '/export/');

define('IMPORT_FOLDER', 'import');
define('IMPORT_DIR', SITE_DIR . DIRECTORY_SEPARATOR . IMPORT_FOLDER);
define('IMPORT_URL_ROOT', SITE_URL . '/import/');

define('BIN_FOLDER', 'bin');
define('BIN_DIR', SITE_DIR . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . BIN_FOLDER);

define('TEMP_FOLDER', 'temp');
define('TEMP_DIR', SITE_DIR . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . TEMP_FOLDER);

define('RES_FOLDER', 'res');
define('RES_DIR', SITE_DIR . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . RES_FOLDER);

define('STATIC_VER', '20221061');
//define('STATIC_VER', rand());
define('TEST', true);
define('VERSION', '2.0.1');
define('DEVELOPER_EMAIL', '');

define('UNIQID', uniqid());
define('JVAR','JVAR_'.UNIQID);
define('DATAGRID_ID','DATAGRID_'.UNIQID);
define('TOOLBAR_ID','TOOLBAR_'.UNIQID);
define('FORM_ID','FORM_'.UNIQID);
define('DEFAULT_PAGE_ROWS', 30);
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
