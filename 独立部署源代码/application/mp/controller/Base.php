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
namespace app\mp\controller;
use think\Controller;
use think\Db;
use think\Env;
use think\Session;
use think\Log;
use think\Cookie;
use think\Config;
use app\Defs;
use app\mp\service\RequestContext;
use app\mp\service\Users as UsersService;
use app\common\service\Redis as RedisService;
/**
 * 移动端基类
 * @package app\mp\controller
 */
class Base extends Controller
{
    protected $_uid = 0;            //当前用户id
    protected $_user = [];          //当前用户信息
    protected $_openid = '';        //当前微信openid
    protected $_home_url = '/mp';      //首页url
    protected $_current_url = '';   //当前请求url地址
    protected $_enter_scene = '';         //来源场景, 支持"qrcode"
    protected $_studio = [];
    protected function _initialize(){
        parent::_initialize();
        //redis
        if (Env::get('redis_enable')) {
            RedisService::init();
        }
        $this->_enter_scene = input('get.enter_scene', '');
        $this->_current_url = $this->request->url();

        //判断是否是内部自动登录
        $visitUid = $this->request->get('auto_login_uid');
        $visitSign = $this->request->get('auto_login_sign');
        $visitTimestamp = $this->request->get('auto_login_timestamp');
        if($visitUid && $visitSign &&  checkMpAutoLoginSign($visitUid, $visitTimestamp, $visitSign)){
            //管理台查看报告,pdf报告下载,咨询室预览
            $this->autoLogin($visitUid);
        }else {
            $this->checkLogin();
        }
        //工作室设置
        $studioSetting = Db::table('studio')->column('value', 'key');
        $studioSetting = $studioSetting??[];
        $this->_studio['store_name'] = $studioSetting['store_name']??'';
        $this->_studio['store_desc'] = $studioSetting['store_desc']??'';
        $this->_studio['store_contact'] = $studioSetting['store_contact']??'';

        if(!empty($studioSetting['store_index_sections'])){
            $this->_studio['store_index_sections'] = explode(',', $studioSetting['store_index_sections']);
        }else{
            $this->_studio['store_index_sections'] = [];
        }
        if(!empty($studioSetting['store_bottom_tabs'])){
            $this->_studio['store_bottom_tabs'] = explode(',', $studioSetting['store_bottom_tabs']);
        }else{
            $this->_studio['store_bottom_tabs'] = [];
        }
        if ($this->request->isGet()) {
            $this->assign('_current_url',$this->_current_url);
            $this->assign('_home_url', $this->_home_url);
            $this->assign('_current_tab',strtolower($this->request->controller()));
            $this->assign('_enter_scene', $this->_enter_scene);
            $this->assign('_studio', $this->_studio);
        }
    }
    public function _empty(){
        if($this->request->isAjax()){
		    abort(404, '资源不存在或已经删除');
        }else{
            exit($this->fetch('common/error', ['msg'=>'资源不存在或已经删除。']));
            //header('HTTP/1.1 404 Not Found');
            //exit();
        }
	}
    protected function invalidRequest(){
        if($this->request->isAjax()){
		    abort(400, '错误的请求');
        }else{
            exit($this->fetch('common/error', ['msg'=>'错误的请求。']));
            //header('HTTP/1.1 400 Bad Request');
            //exit();
        }
	}
    protected function autoLogin($uid){
        if($uid == 1){
            //robot, 咨询室预览
            $user = Defs::BUILT_IN_MP_USER;
        }else{
            //后台查看报告，pdf报告下载
            $user = Db::table('customer')->where('id', $uid)->find();
            if(empty($user)){
                exit("无法找到该用户");
            }
        }
        //设置session
        UsersService::I()->setLoginSession($user);
        $this->_user = session('user');
        $this->_uid = $this->_user['id'];
        $this->_openid = $this->_user['openid'];
        $this->assign('_user', $this->_user);
    }
    protected function checkLogin(){
        if (Env::get('production')) {
            //生产环境
            $this->_user = session('user');
        } else {
            //本地测试
            $this->_user = Defs::BUILT_IN_MP_USER;
        }
        if ($this->_user) {
            //已登录
            $this->_uid = $this->_user['id'];
            $this->_openid = $this->_user['openid'];
            $this->assign('_user', $this->_user);
            return;
        }
        if(request()->isAjax()){
			header('HTTP/1.1 401 Unauthorized');
			exit();
		}else{
            if($this->_enter_scene == 'qrcode'){
                //不需要强制登录
                return;
            }
            //Log::notice("checkLogin request header: " . var_export($this->request->header(), true));
            //Log::notice("checkLogin sessions: " . var_export($_SESSION, true));
            //以下为微信网页授权获取用户信息
            //{"errcode":41002,"errmsg":"appid missing rid: 66fe9a46-7bf98238-3d6ead24"}
            try{
                $config = config('wx.official_account');
                $config['oauth']['callback'] = url('mp/Wx/webOauth', ['referer_url'=>$this->_current_url], true, true);
                $app = \EasyWeChat\Factory::officialAccount($config);
                //https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx24cbf5a879d1b1a3&redirect_uri=https%3A%2F%2Fxl.wsworking.com%2Fwx_oauth.html&response_type=code&scope=snsapi_userinfo&state=6f0f118f1a911cb5dc8e51c3d258c7c8&connect_redirect=1#wechat_redirect
                /*新微信用户量表扫码和授权回调cookie中的PHPSESSID会不同，session保存referer_url无效*/
                $redirectUrl = $app->oauth->redirect();
            }catch(\Exception $e){
                exit($this->fetch('common/error', ['msg'=>$e->getMessage()]));
            }
            $this->redirect($redirectUrl);
            exit();
        }
    }
}