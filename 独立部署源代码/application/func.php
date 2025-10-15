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
use think\Session;
use think\Db;
use think\Request;
use think\Env;
use app\Defs;
use think\Config;
use think\Log;
use app\index\service\EventLogs as EventLogsService;
use app\index\service\Messages as MessagesService;
use app\index\model\Setting as SettingModel;
use app\index\model\Base as BaseModel;
use app\common\service\WException;

function ajaxSuccess($msg = '操作成功', $data = '', $html = ''){
    if(empty($msg)){
        $msg = '操作成功';
    }
	return json([
		'code'=>1,
		'msg'=>$msg,
		'data'=>$data,
		'html'=>$html
	]);	
}
function ajaxError($msg = '操作失败', $data = '', $html = ''){
    if(empty($msg)){
        $msg = '操作失败';
    }
	return json([
		'code'=>0,
		'msg'=>$msg,
		'data'=>$data,
		'html'=>$html,
        //兼容datagrid load data
        'total'=>0,
        'rows'=>[]
	]);	
}
//it must return text/html content, so can't use ajaxSuccess
function uploadSuccess($msg = '', $data = [], $html = ''){
	exit(json_encode([
		'code'=>1,
		'msg'=>$msg,
		'data'=>$data,
		'html'=>$html
	]));
}
function uploadError($msg = '', $data = [], $html = ''){
	exit(json_encode([
		'code'=>0,
		'msg'=>$msg,
		'data'=>$data,
		'html'=>$html
	]));
}

function dict($key = '', $fileName = 'setting') {
    static $_dictFileCache  =   array();
    $file = APP_PATH . 'dict' . DS  . $fileName . '.php';
    if (!file_exists($file)){
        unset($_dictFileCache);
        return null;
    }
    if(!$key && !empty($_dictFileCache)) return $_dictFileCache;
    if($key && isset($_dictFileCache[$key])) return $_dictFileCache[$key];
    $data = require_once $file;
    $_dictFileCache = $data;
    return $key ? $data[$key] : $data;
}
function wexception($msg, $code = 0){
    throw new WException($msg, $code);
}

/**获取系统设置
 * @param $field
 * @return mixed|null
 */
function systemSetting($field){
    $settingModel = new SettingModel();
    $value = $settingModel->getSetting($field);
    return $value;
}
function systemSettingSetTemp($field, $value){
    $settingModel = new SettingModel();
    $settingModel->setMemorySetting($field, $value);
}
/**记录事件
 * @param $content
 * @param int $severity
 */
function logEvent($content, $severity=EventLogsService::eSeverityInfo){
    EventLogsService::I()->logEvent($content, $severity);
}

/**管理員消息
 * @param $title
 * @param $content
 */
function adminMessage($title, $content){
    MessagesService::I()->sendAdminMessage($title, $content);
}
/*************************************************************************/
function convertUploadSaveName2FullUrl($saveName){
    $urlFilePath = str_replace(DS, '/' , $saveName);
    $url = UPLOAD_URL_ROOT . $urlFilePath;
    return $url;
}
function convertUploadSaveName2RelativeUrl($saveName){
    $urlFilePath = str_replace(DS, '/' , $saveName);
    $url = UPLOAD_FOLDER . '/' . $urlFilePath;
    return $url;
}
function convertUploadSaveName2AbsoluteUrl($saveName){
    $urlFilePath = str_replace(DS, '/' , $saveName);
    $url = SCRIPT_DIR . '/' . UPLOAD_FOLDER . '/' . $urlFilePath;
    return $url;
}
function convertUploadSaveName2DiskFullPath($saveName){
    $diskPath = UPLOAD_DIR . DS . $saveName;
    return $diskPath;
}
function convertUploadRelativeUrl2DiskFullPath($relativeUrl){
    $localRelativePath = str_replace('/', DS , $relativeUrl);
    return SITE_DIR . DIRECTORY_SEPARATOR . $localRelativePath;
}
function convertUploadAbsoluteUrl2DiskFullPath($absoluteUrl){
    $localRelativePath = str_replace('/', DS , $absoluteUrl);
    return SITE_DIR . $localRelativePath;
}
function convertUploadSaveNameThumbnail2DiskFullPath($saveName, $size=100){
    $basename = basename($saveName);
    $pos = strrpos($basename, '.');
    if($pos !== false){
        $basename = substr($basename, 0, $pos) . '_' . $size . substr($basename, $pos);
    }
    $thumbnailPath = UPLOAD_DIR . DS . 'thumbnails' . DS . $basename;
    return $thumbnailPath;
}
/**
 * 生成上传文件的完整url
 * @param $url
 */
function generateUploadFullUrl($url){
    if(empty($url)){
        return $url;
    }
    if(startsWith($url, SCHEMA)){
        return $url;
    }
    if(startsWith($url, '/')){
        return SITE_URL . $url;
    }
    return SITE_URL . '/' . $url;
}
function generateWzyerUploadFullUrl($url){
    if(empty($url)){
        return $url;
    }
    if(startsWith($url, 'http://') || startsWith($url, 'https://')){
        return $url;
    }
    if(startsWith($url, '/')){
        return 'https://www.wzyer.com' . $url;
    }
    return 'https://www.wzyer.com' . '/' . $url;
}
/*************************************************************************/
function getUnzipFullPath(){
    return BIN_DIR . DIRECTORY_SEPARATOR . 'unzip.exe';
}
function getMysqldumpFullPath(){
    return BIN_DIR . DIRECTORY_SEPARATOR . 'mysqldump.exe';
}
/*************************************************************************/
/*
function generateAppointOrderNo(){
	$prefix = 'YY';
	$rand = mt_rand(0,9999);
	$orderNo = $prefix . date('YmdHis') . str_pad($rand,4,'0',STR_PAD_LEFT);
	return $orderNo;
}*/
function generateSequenceNo($uid, $key, $prefix=''){
    /*
	if (Env::get('production')) {
		Db::execute("lock tables redis write");
	}
	$nowValue = Db::table('redis')->where(['key'=>$key])->value('value');
	if(empty($nowValue)){
		$nowValue = 0;
	}else{
		$nowValue = intval($nowValue);
	}
	if($nowValue == 0){
		Db::table('redis')->insert([
			'key' => $key,
			'value' => strval($nowValue + 1)
		]);
	}else {
		Db::table('redis')->where(['key' => $key])->setField('value', strval($nowValue + 1));
	}
	if (Env::get('production')) {
		Db::execute("unlock tables");
	}
	$order_no = $prefix . date('Y') . str_pad($nowValue,8,'0',STR_PAD_LEFT);*/
    $order_no = $prefix . date('ymdHis') . $uid;
   // $order_no = $prefix . date('dHis') . $uid;//enough unique
	return $order_no;
}
/*************************************************************************/
function generateUniqid(){
    if(version_compare(PHP_VERSION, '7.0.0') >= 0) {
        $id = bin2hex(random_bytes(16));
    }else{
        $id = md5(uniqid());
    }
    return $id;
}
function generateSubjectSn(){
    return 'M' . strtoupper(bin2hex(random_bytes(6)));
}
/**********************************************************************************************************************/
function createFormToken(){
    $token = generateUniqid();
    Session::set('form-token', $token);
    return $token;
}
function verifyFormToken($formToken){
    $token = Session::get('form-token');
    if($token && $token == $formToken){
        Session::set('form-token', '');
        return true;
    }else{
        return false;
    }
}
/****************************************************************************************/
function password($password, $encrypt='') {
    $pwd = array();
    $pwd['encrypt'] =  $encrypt ? $encrypt : \think\helper\Str::random(6);
    $pwd['password'] = md5(md5(trim($password)).$pwd['encrypt']);
    return $encrypt ? $pwd['password'] : $pwd;
}

/**日期过滤
 * @param $input
 * @return string
 */
function dateFilter($input){
    if($input == Defs::DEFAULT_DB_DATE_VALUE){
        return '';
    }
    return $input;
}
function dateTimeFilter($input){
    if($input == Defs::DEFAULT_DB_DATETIME_VALUE){
        return '';
    }
    return $input;
}
function dateDbConverter($input){
    if(empty($input)){
        return Defs::DEFAULT_DB_DATE_VALUE;
    }
    return $input;
}
function dateTimeDbConverter($input){
    if(empty($input)){
        return Defs::DEFAULT_DB_DATETIME_VALUE;
    }
    return $input;
}
/****************************************************************************************/
function tableExists($table) {
    $sql = "SHOW TABLES LIKE '" . $table . "'";
    $info = Db::query($sql);
    if (!empty($info)) {
        return true;
    } else {
        return false;
    }
}
function isWeixinVisit(){
    $userAgent = Request::instance()->header('user-agent');
    if (stripos($userAgent, 'MicroMessenger') !== false) {
        return true;
    } else {
        return false;
    }
}
function getWeekdayText($weekDay){
    $weekDays = ['周日', '周一', '周二', '周三', '周四', '周五', '周六'];
    if(isset($weekDays[$weekDay])){
        return $weekDays[$weekDay];
    }else{
        return '';
    }
}
function convertLineBreakToEscapeChars($str){
    return str_replace("'", "\'", str_replace("\n", "\\n", str_replace("\r\n", "\\r\\n", $str)));
}
function formatBoolean($val){
    if($val){
        return '<span class="badge badge-success">是</span>';
    }else{
        return '<span class="badge badge-warning">否</span>';
    }
}
function convertSubjectExpression($expression){
    $expression = str_replace('${TW}', '总分', $expression);
    $expression = str_replace('${AW}', '平均分', $expression);
    $expression = str_replace('${PIC}', '阳性项目数', $expression);
    $expression = str_replace('${NIC}', '阴性项目数', $expression);
    $expression = str_replace('${PAW}', '阳性平均分', $expression);
    $i = 0;
    while($i<=6){
        $expression = str_replace('${WD' . $i . '}', "分数{$i}项目数", $expression);
        $i++;
    }
    $expression = str_replace('&&', ' And ', $expression);
    $expression = str_replace('||', ' Or ', $expression);
    return $expression;
}
//appointTime 09:00-09:15,09:15-09:30,09:30-09:45
function convertAppointTimesToShow($appointTime){
    $appointTimeSections = explode('-', $appointTime);
    $appointTimeShow = $appointTimeSections[0] . '-' . $appointTimeSections[count($appointTimeSections)-1];
    return $appointTimeShow;
}
function formatTimes($times, $decimals=2){
	if(empty($times)){
		return '0';
	}
	$k = 10000;
	$dm = $decimals<0?0:$decimals;
	$sizes = ['', '万', '亿'];
	$i = floor(log($times)/log($k));
	return round($times / pow($k, $i), $dm) . ' ' . $sizes[$i];
}
function generateMpAutoLoginParams($uid){
    $timestamp = time();
    return [
        'auto_login_uid'=>$uid,
        'auto_login_sign'=>md5($uid . '_' . $timestamp . '_' . Defs::INTERNAL_PRIVILEGE_TOKEN),
        'auto_login_timestamp'=>$timestamp
    ];
}
function checkMpAutoLoginSign($uid, $timestamp, $sign){
    return md5($uid . '_' . $timestamp . '_' . Defs::INTERNAL_PRIVILEGE_TOKEN) == $sign;
}

function wxPaySetting(){
    //微信支付商户号
    $mchId = systemSetting('WX_PAYMENT_MCH_ID');
    if(empty($mchId)){
        //未配置独立微信支付
        Log::notice('wxPaySetting: 未配置微信支付');
        return;
    }
    //APIv2密钥
    $key = systemSetting('WX_PAYMENT_KEY');
    /*
    Config::set('wx.payment.mch_id', $mchId);
    Config::set('wx.payment.key', $key);
    */
    //API证书, 独立存放
    $certPath = ROOT_PATH.'conf'.DS.'wxpay'.DS."{$mchId}_cert.pem";
    $keyPath = ROOT_PATH.'conf'.DS.'wxpay'.DS."{$mchId}_key.pem";
    if(!file_exists($certPath)){
        $certPath = '';
    }
    if(!file_exists($keyPath)){
        $keyPath = '';
    }
    Config::set([
        'wx'=>[
            'official_account'=>array_merge(config('wx.official_account'), [
                'app_id'=>systemSetting('WX_OFFICE_ACCOUNT_APP_ID'),
                'secret'=>systemSetting('WX_OFFICE_ACCOUNT_APP_SECRET'),
                'token'=>systemSetting('WX_OFFICE_ACCOUNT_SERVER_TOKEN'),
            ]),
            'payment'=>array_merge(config('wx.payment'), [
                'mch_id'=>$mchId,
                'key'=>$key,
                'cert_path'=>$certPath,
                'key_path'=>$keyPath
            ]),
            'mini_program'=>array_merge(config('wx.mini_program'), [
                'app_id'=>systemSetting('WX_MINI_PROGRAM_APP_ID'),
                'secret'=>systemSetting('WX_MINI_PROGRAM_APP_SECRET')
            ])
        ]
    ]);
    Log::notice("wxPaySetting: " . var_export(Config::get('wx.payment'), true));
}
function generateThumbnailUrl($uploadUrl, $size=100, $emptyUrl=''){
    if(empty($uploadUrl)){
        if($emptyUrl){
            return $emptyUrl;
        }
        //return \app\Defs::DEFAULT_IMG_DATA_URL;
        return '/static/img/no-image.png';
        //return '/static/img/dot.png';
    }
    //gif不处理
    if(endsWith(strtolower($uploadUrl), '.gif')){
        return $uploadUrl;
    }
    return url('index/Attachments/thumbnailUploadImage', ['absoluteUrl'=>$uploadUrl, 'size'=>$size]);
}