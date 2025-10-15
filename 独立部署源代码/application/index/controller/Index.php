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
use think\Log;
use think\Url;
use think\Cookie;
use think\Session;
use think\captcha\Captcha;
use think\Request;
use think\Cache;
use app\index\logic\Defs as IndexDefs;
use app\index\logic\Messages as MessagesLogic;
use app\index\logic\Admins as AdminsLogic;
use app\index\logic\Expert as ExpertLogic;
use app\index\logic\Menu as MenuLogic;
use app\index\model\Setting as SettingModel;
class Index extends Common
{
	public function _initialize(){
		parent::_initialize();
	}

	public function sessionLife(){
		$loginUrl = url('index/Index/public_login');
		if(empty($this->loginUserId)){
			return ajaxError('请先登录', $loginUrl);
		}
		//单点登录判断
		if(systemSetting('LOGIN_ONLY_ONE') == 'yes'){
			if(session_id() !== Cache::get('SESSION_ID_' . $this->loginUserId)){
				return ajaxError('帐号已在其他地方登录，您已被迫下线！',url('index/Index/public_logout'));
			}
		}
		return ajaxSuccess();
	}
	public function clearCache(){
		/*
		$tempPath = TEMP_PATH;
		if ($handle = opendir($tempPath)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					unlink($tempPath . $file);
				}
			}
			closedir($handle);
		}*/
		rrmdir(TEMP_PATH);
		$settingModel = new SettingModel();
		$settingModel->clearCache();
		//开启单点登录，会引起重新登录
		//rrmdir(CACHE_PATH);
		return ajaxSuccess();
	}
	/******************************************************************************************************************/
	public function public_index(){
		$this->redirect('index/Index/index');
	}
    public function index(){
		$urlHrefs = array(
			'loadLeftMenu'=>Url::build('index/Index/loadLeftMenu'),
			'logout'=>url('index/Index/public_logout'),
			'sessionLife'=>url('index/Index/sessionLife'),
			'license'=>url('index/System/license'),
			'clearCache'=>url('index/Index/clearCache'),
			'modifyPwd'=>url('index/Index/modifyPwd'),
			'main'=>url('index/Index/main')
		);
		$this->assign('urlHrefs', $urlHrefs);
		/**************************************************************************************************************/
		$loginUserInfos = [
			'username'=>$this->loginUserName,
			'realname'=>$this->loginRealName,
			'lastlogintime'=>date('Y-m-d H:i', $this->loginTime),
			'lastloginip'=>$this->loginIp,
			'unreadMessageCount'=>MessagesLogic::newObj()->unreadCount($this->loginUserId)
		];
		$this->assign('loginUserInfos', $loginUserInfos);

	    if ($this->loginMobile) {
			//移动浏览器
			//管理员
			$menus = [];

			MenuLogic::newObj()->loadLeftMenuRecursively(
				0, 
				'', 
				$menus,
				$this->loginSuperUser,
				$this->loginUserRoleId);
            return $this->fetch('mobile',['menus'=>$menus]);
        } else {
			$this->assign('urlHrefs', $urlHrefs);
            return $this->fetch('index_tab');
        }
    }

	/**
	 * 根据登录用户的身份设置相应的命令菜单
	 */
	public function loadLeftMenu(){
		$leftMenuDefs = array();
		MenuLogic::newObj()->loadLeftMenuRecursively(
			0, 
			'', 
			$leftMenuDefs,
			$this->loginSuperUser,
			$this->loginUserRoleId);
		return json($leftMenuDefs);
	}
	
	/**
	 * 首页
	 *
	 * @return void
	 */
	public function main(){
		$urlHrefs = [
			'dashboard'=>url('index/Dashboard/dashboard'),
			'trend'=>url('index/Dashboard/trend'),
			'latestAppointments'=>url('index/AppointOrder/index'),
			'latestSubjects'=>url('index/SubjectOrder/orders'),
            'eventLogs'=>url('index/EventLog/index')
		];
		$this->assign('urlHrefs', $urlHrefs);
		return $this->fetch();
	}
	public function public_login(){
		if(request()->isGet()){
		    if ($this->loginUserId) {
		        $this->redirect('index/Index/index');
            }
			if($this->autoLogin()){
				$this->redirect('index/Index/index');
			}
			$this->assign('login_captcha_enable', Session::get('login_captcha_enable'));
			$this->assign('urls', [
                'captcha'=>url('index/Index/public_captcha', [
                    'code_len'=>4,
                    'font_size'=>12,
                    'width'=>95,
                    'height'=>30,
                    'code'=>time()
                ]),
                'login'=>url('index/Index/public_login')
            ]);
			return $this->fetch();
		}
		$username = input('post.username/s', '');
		$password = input('post.password/s', '');
		if(Session::get('login_captcha_enable')) {
			$captchaCode = input('post.captcha/s');
			$captcha = new Captcha();
			$captchaOk = $captcha->check($captchaCode, 'login');
			if (!$captchaOk) {
				//return ajaxError('验证码错误');
				$this->error('验证码错误');
			}
		}
		$autoLogin = input('post.auto_login', null);
		//管理员登录
		$adminsLogic = AdminsLogic::newObj();
		$adminInfos = $adminsLogic->login($username, $password);
		if(!$adminInfos){
			//return ajaxError('用户名密码错误');
			Session::set('login_captcha_enable', true);
			//$this->error('用户名密码错误');//微信浏览器history.go(-1)使用缓存导致captcha无法显示
			$this->error('用户名密码错误', 'index/Index/public_login');
		}
		//return ajaxSuccess('操作成功', url('index/Index/index'));
		Session::delete('login_captcha_enable');
		if($autoLogin){
			//set cookie
			$autoLoginToken = $this->autoLoginToken($username, $password);
			Cookie::set('auto_login_token', $autoLoginToken, 3600*24*30);//30 days
		}else{
			//clear cookie
			Cookie::delete('auto_login_token');
		}
		//$this->success('操作成功', 'index/Index/index');
		$this->redirect('index/Index/index');
	}
	public function public_logout(){
        $adminsLogic = AdminsLogic::newObj();
        $adminsLogic->logout();
		Cookie::delete('auto_login_token');
		$this->success('成功登出', 'index/Index/public_login');
		//return ajaxSuccess('操作成功', url('index/Index/public_login'));
	}
	public function public_captcha(){
		$captcha = new Captcha();
		$captcha->useCurve = false;
		$captcha->useNoise = false;
		$captcha->bg = array(255, 255, 255);

		if (input('get.code_len')) $captcha->length = intval(input('get.code_len'));
		if ($captcha->length > 8 || $captcha->length < 2) $captcha->length = 4;

		if (input('get.font_size')) $captcha->fontSize = intval(input('get.font_size'));

		if (input('get.width')) $captcha->imageW = intval(input('get.width'));
		if ($captcha->imageW <= 0) $captcha->imageW = 130;

		if (input('get.height')) $captcha->imageH = intval(input('get.height'));
		if ($captcha->imageH <= 0) $captcha->imageH = 50;

		return $captcha->entry('login');
	}

	public function modifyPwd(){
		if(request()->isGet()){
			$this->assign('info', [
				'username'=>$this->loginUserName
			]);
			return $this->fetch();
		}
		$oldPassword = input('post.old_password/s');
		$newPassword = input('post.new_password/s');
        try {
			$adminsLogic = AdminsLogic::newObj();
			$result = $adminsLogic->modifyAdminPwd($this->loginUserId, $oldPassword, $newPassword);
			if ($result) {
				$adminsLogic->logout();
			}
        }catch (\Exception $e){
            return ajaxError('修改密码失败, ' . $e->getMessage());
        }
        Cookie::delete('auto_login_token');
        return ajaxSuccess('成功修改密码，请重新登录', url('index/Index/public_login'));
	}
}