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
use app\Defs;
use app\mp\service\Subjects;
use app\mp\service\Users;
use EasyWeChat\Factory;
use think\Controller;
use think\Db;
use think\Session;
use think\Log;
use app\index\logic\Defs as IndexDefs;

use app\index\logic\AppointOrder as AppointOrderLogic;
use app\index\logic\Expert as ExpertLogic;
use app\index\logic\Sms as SmsLogic;
use app\index\service\EventLogs as EventLogsService;
use app\mp\logic\Wx as WxLogic;
use app\common\service\WException;
/**
 * 处理微信各种回调
 */
class Wx extends Controller
{
    public function _initialize(){
        parent::_initialize();
    }
    protected function isWx(){
        if (false === strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')) {
            return false;
        } else {
            return true;
        }
    }
    //微信网页授权
    //https://www.easywechat.com/6.x/common/oauth.html#网页授权实例
    /*
     * array (
  'id' => 'oDGSvwYFe1xKwzq6VKlSQnKT_EKE',
  'name' => 'xxx',
  'nickname' => 'xxx',
  'avatar' => 'https://thirdwx.qlogo.cn/mmopen/vi_32/3bPXCPePwy38gncGQoA4HicSxIKNO4qaPeXQDMEGcQmvFG9iazfGCwS4cIZQwbZ4BVVXicuJuMZviaiaUOcwwdpqpRg/132',
  'email' => NULL,
  'raw' =>
  array (
    'openid' => 'oDGSvwYFe1xKwzq6VKlSQnKT_EKE',
    'nickname' => 'xxx',
    'sex' => 0,
    'language' => '',
    'city' => '',
    'province' => '',
    'country' => '',
    'headimgurl' => 'https://thirdwx.qlogo.cn/mmopen/vi_32/3bPXCPePwy38gncGQoA4HicSxIKNO4qaPeXQDMEGcQmvFG9iazfGCwS4cIZQwbZ4BVVXicuJuMZviaiaUOcwwdpqpRg/132',
    'privilege' =>
    array (
    ),
  ),
  'access_token' => '50_TCT8VfW2FHpOLhVaWIWmUAunl21Qd3Y9XBo5HiBHCBK23jqwzUaf21lS37caH5sNPkV5ODjXZuyz1re-lck-69z-1qaMzheCwos1TD0-F8s',
  'refresh_token' => '50_XBcmBI0H3OKm7-IXnDlrgGeb4vG88-VA680OGot253-0_p4WKQ3VcZO8dGEzLMXyWh_CufLVCFReBWyw14AB2vz18UWHKfg4UmuwAkVk7uo',
  'expires_in' => 7200,
)**
Wx oauth return user: array (
  'id' => 'oDGSvwdHuyNvLBYf3fInTbmKyEFE',
  'name' => '微信用户',
  'nickname' => '微信用户',
  'avatar' => 'https://thirdwx.qlogo.cn/mmopen/vi_32/Q3auHgzwzM6FDSZnXyLU8f9sFREclGvVbtyibROF9icKIS2ibo6ZIHTeY2iaoMeegb8icD8p8A1KEoibdjOdzibeEO46Q/132',
  'email' => NULL,
  'raw' =>
  array (
    'openid' => 'oDGSvwdHuyNvLBYf3fInTbmKyEFE',
    'nickname' => '微信用户',
    'sex' => 0,
    'language' => '',
    'city' => '',
    'province' => '',
    'country' => '',
    'headimgurl' => 'https://thirdwx.qlogo.cn/mmopen/vi_32/Q3auHgzwzM6FDSZnXyLU8f9sFREclGvVbtyibROF9icKIS2ibo6ZIHTeY2iaoMeegb8icD8p8A1KEoibdjOdzibeEO46Q/132',
    'privilege' =>
    array (
    ),
  ),
  'access_token' => '74_bN6pHbMxkVQp2ryJgyixn2SxqXyoTgMiZjqsBv1sKugarf00zf6uCHM00gGHvaGa-CnWHUEWjPaR5imZAXSN0mjP1cttwyYsLmXcbQMk8LM',
  'refresh_token' => '74_J_7ELrumzO6YQwO5TWyp_BVqYStIMrHK_HlFB6_GbIcHbtMZUW4bVQauo6KRJbuLYb4edf7CjZFoj0JRXNHGMUaw8eEX0GZMQ07LLDUnxC8',
  'expires_in' => 7200,
  'is_snapshotuser' => 1,
)
www.wzyer.com/mp/wx/weboauth.html?referer_url=%2Fmp%2Fsubject%2Fdetail.html%3Fid%3D660%26code=041gVXZv3LFDK13e420w37gCLc3gVXZX&state=1fe1d5657e6bdbe8c5492d6938c2abaa
     */
    /*通过code换取网页授权access_token，快照页返回
    {
    "access_token":"ACCESS_TOKEN",
    "expires_in":7200,
    "refresh_token":"REFRESH_TOKEN",
    "openid":"OPENID",
    "scope":"SCOPE",
    "is_snapshotuser": 1,
    "unionid": "UNIONID"
    }
    */
    public function webOauth($code='', $referer_url=''){
        Log::notice("webOauth request header: " . var_export($this->request->header(), true));
        Log::notice("webOauth sessions: " . var_export($_SESSION, true));
        try{
            $this->redirect(WxLogic::I()->webOauth($code, $referer_url));
        }catch(WException $e){
            return $e->getMessage();
        }
    }
    protected function checkSignature($signature, $nonce, $timestamp, $token)
    {
        //把这三个参数存到一个数组里面
        $tmpArr = array($timestamp, $nonce, $token);
        //进行字典排序
        sort($tmpArr);
        //把数组中的元素合并成字符串，impode()函数是用来将一个数组合并成字符串的
        $tmpStr = implode($tmpArr);
        //sha1加密，调用sha1函数
        $tmpStr = sha1($tmpStr);
        //判断加密后的字符串是否和signature相等
        if ($tmpStr == $signature) {
            return true;
        }
        return false;
    }
    public function events(){
        if ($this->request->isGet()) {
            //token验证
            $token = config('wx.official_account')['token'];
            $requestParams = input('get.');
            do{
                if(empty($requestParams['signature']) || 
                    empty($requestParams['nonce']) || 
                    empty($requestParams['timestamp']) || 
                    empty($requestParams['echostr'])){
                    break;
                }
                if(!$this->checkSignature($requestParams['signature'], $requestParams['nonce'], $requestParams['timestamp'], $token)){
                    break;
                }
                return $requestParams['echostr'];
            }while(false);
            return '';
        }
        WxLogic::I()->events();
    }
    public function _empty(){
        $action = trim(request()->action());
        Log::notice("Wx {$action} is invoked");
        if(startsWith($action, 'paidsubjectorder_')){
            return WxLogic::I()->paidSubjectOrder();
        }else if(startsWith($action, 'paidexpertorder_')){
            return WxLogic::I()->paidExpertOrder();
        }
	}
}