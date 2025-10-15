<?php
 namespace app\index\service; use think\Db; use think\Log; use think\Debug; class RequestContext extends Base { protected function __construct() { } public $loginUserId = null; public $loginUserName = null; public $loginRealName = null; public $loginUserRoleId = null; public $loginSuperUser = null; public $loginTime = null; public $loginIp = null; public $loginMobile = null; }
