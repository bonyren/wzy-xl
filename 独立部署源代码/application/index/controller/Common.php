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
use think\Session;
use think\Cookie;
use think\Request;
use think\Log;
use app\index\service\RequestContext;
use crypt\PhpEncrypter;
use app\index\logic\Admins as AdminsLogic;
use app\index\service\Authorize as AuthorizeService;
use app\index\logic\Defs as IndexDefs;
use app\Defs;

class Common extends Controller
{
	//管理员登录用户
	protected $loginUserId = null;
	protected $loginUserName = null;
	protected $loginRealName = null;
	protected $loginUserRoleId = null;
	protected $loginSuperUser = null;
	//共用
	protected $loginCurMenuPriv = IndexDefs::AUTHORIZE_READ_ONLY_TYPE;//只读
	protected $loginTime = null;
	protected $loginIp = null;
	protected $loginMobile = null;

	protected function _initialize(){
		$this->loginUserId = Session::get('userid');
		$this->loginUserName = Session::get('username');
		$this->loginRealName = Session::get('realname');
		$this->loginUserRoleId = Session::get('userroleid');
		$this->loginSuperUser = Session::get('super_user');
		$this->loginTime = Session::get('lastlogintime');
		$this->loginIp = Session::get('lastloginip');
		$this->loginMobile = request()->isMobile();
		$result = self::checkLogin();
		//管理员
		RequestContext::I()->loginUserId = $this->loginUserId;
		RequestContext::I()->loginUserName = $this->loginUserName;
		RequestContext::I()->loginRealName = $this->loginRealName;
		RequestContext::I()->loginUserRoleId = $this->loginUserRoleId;
		RequestContext::I()->loginSuperUser = $this->loginSuperUser;
		RequestContext::I()->loginTime = $this->loginTime;
		RequestContext::I()->loginIp = $this->loginIp;
		RequestContext::I()->loginMobile = $this->loginMobile;
		if($result === true){
			self::checkPriv();
		}
		//管理员
		$this->assign('loginUserId', $this->loginUserId);
		$this->assign('loginUserName', $this->loginUserName);
        $this->assign('loginSuperUser', $this->loginSuperUser);
		$this->assign('loginCurMenuPriv', $this->loginCurMenuPriv);
		$this->assign('loginMobile', $this->loginMobile);
        $this->assign('current_request_url',$this->request->url());
	}
	public function _empty(){
		abort(404, '资源不存在或已经删除');
	}
	protected function invalidRequest(){
		abort(400, '错误的请求');
	}
	final public function checkLogin(){
		$controller = Request::instance()->controller();
		$action = Request::instance()->action();
		if($controller =='Index' && in_array($action, array('public_login', 'public_logout', 'public_captcha')) ) {
			return 0;
		}
		//附件缩略图
		if($controller =='Attachments' && in_array(strtolower($action), array('thumbnailimage', 'thumbnailuploadimage')) ) {
			return 0;
		}
		if($this->loginUserId){
			return true;
		}
		if(request()->isAjax()){
			header('HTTP/1.1 401 Unauthorized');
			exit();
		}else if(!request()->isAjax()) {
			Log::notice('CommonController::checkLogin, please login firstly, session: ' . var_export($_SESSION, true) . ', url:' . url('index/Index/public_login'));
			$this->redirect('index/Index/public_login');
		}else{
			exit();
		}
	}
    final public function checkPriv(){
        if($this->loginSuperUser){
            //超级管理员, 读写
            $this->loginCurMenuPriv = IndexDefs::AUTHORIZE_READ_WRITE_TYPE;
            return;
        }
        if($this->request->isGet() && $this->request->isAjax()){
            $menuId = Request::instance()->get('leftMenuId');
			if($menuId){
				$roleId = $this->loginUserRoleId;
				$privType = AuthorizeService::I()->checkPriv($roleId, $menuId);
				if(!$privType){
					return;
				}
				$this->loginCurMenuPriv = $privType;
			}
            //暂时不考虑参数
			/*
			$c = Request::instance()->controller();
            $a = Request::instance()->action();
            $privType = AuthorizeService::I()->checkPriv($this->loginUserRoleId, $c, $a);
            if(!$privType){
                return;
            }
            $this->loginCurMenuPriv = $privType;
			*/
        }
    }
	/***********************************************************************************/
	const AUTO_LOGIN_ENCRYPT_PASS = 'hello123)(*';
	protected function autoLogin(){
		$autoLoginToken = Cookie::get('auto_login_token');
		if(!$autoLoginToken){
			return false;
		}
		$crypt = new PhpEncrypter();
		$token = $crypt->decrypt($autoLoginToken, self::AUTO_LOGIN_ENCRYPT_PASS);
		list($username, $password) = explode("#*#*#*", $token);
		try{
			AdminsLogic::I()->login($username, $password);
		}catch (\Exception $e){
			return false;
		}
		Log::notice("autoLogin success");
		return true;
	}
	protected function autoLoginToken($username, $password){
		$crypt = new PhpEncrypter();
		$autoLoginToken = $crypt->encrypt($username .'#*#*#*'.$password, self::AUTO_LOGIN_ENCRYPT_PASS);
		return $autoLoginToken;
	}
	protected function checkFormToken($formData, $name="__token__"){
		if(!isset($formData[$name])){
			return false;
		}
		if(empty($formData[$name])){
			return false;
		}
		$token = Session::pull($name);
		if(empty($token)){
			return false;
		}
		if($token != $formData[$name]){
			return false;
		}
		return true;
	}
}