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
use think\Validate;
use think\Db;
use think\Env;
use think\Log;

// 应用公共文件
\think\Loader::addNamespace('report','../report/');
function startsWith($haystack, $needle) {
	// search backwards starting from haystack length characters from the end
	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function endsWith($haystack, $needle) {
	// search forward starting from end minus needle length characters
	return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

function abstractNameFromEmail($email){
	$pos = strpos($email, '@');
	if($pos === false){
		return $email;
	}
	return substr($email, 0, $pos);
}
function validateEmail($email){
	$pattern = '/^[a-z0-9]+([._-][a-z0-9]+)*@([0-9a-z]+\.[a-z]{2,14}(\.[a-z]{2})?)$/i';
	if(preg_match($pattern, $email)){
		return true;
	}else{
		return false;
	}
}
function validateMobile($mobile){
	$pattern = '/^1\d{10}$/';
	if(preg_match($pattern, $mobile)){
		return true;
	}else{
		return false;
	}
}
function validateUrl($url){
	$pattern = "/^(https?:\/\/)?(((www\.)?[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)?\.([a-zA-Z]+))|(([0-1]?[0-9]?[0-9]|2[0-5][0-5])\.([0-1]?[0-9]?[0-9]|2[0-5][0-5])\.([0-1]?[0-9]?[0-9]|2[0-5][0-5])\.([0-1]?[0-9]?[0-9]|2[0-5][0-5]))(\:\d{0,4})?)(\/[\w- .\/?%&=]*)?$/i";
	if(preg_match($pattern, $url)){
		return true;
	}else{
		return false;
	}
}
function validateDate($date){
	//匹配日期格式
	if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
		//检测是否为日期,checkdate为月日年
		if(checkdate($parts[2],$parts[3],$parts[1])) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
function validateDatetime($datetime){
	//匹配日期格式
	if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $datetime, $parts)) {
		//检测是否为日期,checkdate为月日年
		if(checkdate($parts[2],$parts[3],$parts[1])) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
function emptyInArray(&$arr, $key){
    if(!isset($arr[$key])){
        return true;
    }
    return empty($arr[$key]);
}
function emptyStringInArray(&$arr, $key){
    if(!isset($arr[$key])){
        return true;
    }
    return $arr[$key] === '';
}
/**********************************************************************************************************************/
function obfuscateString($string){
	$l = strlen(($string));
	if ($l > 3) {
		$obf = substr($string, 0, 1);
		$obf .= str_repeat('*', $l - 2);
		$obf .= substr($string, -1, 1);
	} else {
		$obf = str_repeat('*', $l);
	}
	return $obf;
}
function obfuscateEmailAddress($emailAddress){
	if (validateEmail($emailAddress)) {
		list($userName, $domain) = explode('@', strtolower($emailAddress));
		$obf = obfuscateString($userName).'@';
		$domainParts = explode('.', $domain);
		$TLD = array_pop($domainParts);
		foreach ($domainParts as $dPart) {
			$obf .= obfuscateString($dPart).'.';
		}
		return $obf.$TLD;
	}
	return $emailAddress;
}
function truncateString($str, $len){
	if(empty($str)){
		return $str;
	}
	if(strlen($str) > $len){
		$str = substr($str, 0, $len);
	}
	return $str;
}
function mb_truncateString($str, $len){
	if(empty($str)){
		return $str;
	}
	if(mb_strlen($str) > $len){
		$str = mb_substr($str, 0, $len);
	}
	return $str;
}
function beautifyLogArray($infos, $originals = [], $table=''){
	//计算差异
	if($originals && is_array($originals)){
		foreach($infos as $key=>$val){
			if(isset($originals[$key]) && $val == $originals[$key]){
				unset($infos[$key]);
			}
		}
	}
	//翻译
	$logInfos = [];
	$dbFields = include APP_PATH . 'dict' . DS  . 'db_fields.php';
	if($table && isset($dbFields[$table])){
		$fields = $dbFields[$table];
		foreach($infos as $key=>$val){
			if(!isset($fields[$key])){
				//只记录db_fields中存在的字段
				continue;
			}
			$label = $fields[$key]['label'];
			$value = $val;
			if($fields[$key]['converter']){
				$value = call_user_func($fields[$key]['converter'], $val);
			}
			$logInfos[$label] = $value;
		}
		if(empty($logInfos)){
			return '';
		}
	}
	if($logInfos){
		return str_replace('array', '', var_export($logInfos, true));
	}else{
		return str_replace('array', '', var_export($infos, true));
	}    
    //return preg_replace('/^array \( (.*), \)$/', "[$1]", var_export($logInfos, true));
}

/**递归删除文件和目录
 * @param $dir
 */
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir. DS .$object) && !is_link($dir."/".$object))
                    rrmdir($dir. DS .$object);
                else
                    unlink($dir. DS .$object);
            }
        }
        rmdir($dir);
    }
}
function is_dir_empty($dir) {
    if (!is_readable($dir)) return null; 
    return (count(scandir($dir)) == 2);
}
/**
 * randomPassword
 *
 * @return void
 */
function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
function moneyFormat($input){
    return number_format($input, 2);
}
/**
 * sanitizeStringToJsVariable
 *
 * @param  mixed $input
 * @return void
 * @usage
 var title = "<?=$input>";
 */
function sanitizeStringForJsVariable($input){
	return str_replace(array("\r\n", "\n", "\r", "\""), ' ', $input);
}
function ellipsisString($input, $len){
	if(is_array($input)){
		foreach($input as &$value){
			$value = ellipsisString($value, $len);
		}
		return $input;
	}
	if(mb_strlen($input) > $len){
		$input = mb_substr($input, 0, $len);
		$input .= '...';
	}
	return $input;
}
/**
 * 文件路径扩展名
 *
 * @param  mixed $path
 * @return string
 */
function getPathExtName($path){
    return pathinfo($path, PATHINFO_EXTENSION);
}
/**
 * URL扩展名
 *
 * @param  mixed $url
 * @return string
 */
function getUrlExtName($url){
    return pathinfo(parse_url($url)['path'])['extension'];
}
/**
 * 网络文件是否存在
 *
 * @param  mixed $url
 * @return bool
 */
function urlExists(string $url){
    return strpos(get_headers($url)[0], "200 OK") !== false;
}
/**
 * 异或加解密
 *
 * @param  mixed $data
 * @param  mixed $key
 * @return string
 */
function xorEnde($data, $key) {
	$output = '';
	for ($i = 0; $i < strlen($data); $i++) {
		$output .= $data[$i] ^ $key[$i % strlen($key)];
	}
	return $output;
}