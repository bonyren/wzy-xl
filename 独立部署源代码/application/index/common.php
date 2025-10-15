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
use app\index\service\RequestContext;
function generateTextImage($canvasWidth, $canvasHeight, $text, $fontSize, $saveFile){
    $fontFile = RES_DIR . DS . 'simsun.ttc';
    $x = 0;
    $y = 0;
    $image = imagecreatetruecolor($canvasWidth, $canvasHeight); 
    //background color
    $backgroudColor = imagecolorallocate($image, 240, 255, 255);
    imagefill($image, 0, 0, $backgroudColor);
     //text color
    $textColor = imagecolorallocate($image, 0, 0, 0);
    //convert
    //$text=mb_convert_encoding($text, "html-entities", "utf-8"); 
    
    $dimensions_array = imagettfbbox($fontSize, 0, $fontFile, $text);
    $image_width = abs($dimensions_array[2] - $dimensions_array[0]) - 10;
    $image_height = abs($dimensions_array[5] - $dimensions_array[3]);
    
    if($image_width > $canvasWidth*2){
        return false;
    }
    if($image_width > $canvasWidth){
        $cutPos = ceil(mb_strlen($text)/2);
        //two line
        $textFirst = mb_substr($text, 0, $cutPos);
        $textSecond = mb_substr($text, $cutPos);
        $x = 5;
        $y=$canvasHeight/2 - $fontSize;
        imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontFile, $textFirst);
        $y=$canvasHeight/2 + $fontSize;
        imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontFile, $textSecond);
    }else{
        //one line
        $x = ($canvasWidth - $image_width)/2;
        $y=$canvasHeight/2;
        imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontFile, $text);
    }
    imagepng($image, $saveFile);
    imagedestroy($image);
    return true;
}
function autoGenerateImage($text, $curUrl=''){
    if($curUrl && !startsWith($curUrl, '/' . UPLOAD_FOLDER. '/images/')){
        //自定义图片
        return false;
    }
    $canvasWidth = 280;
    $canvasHeight = 180;
    $fontSizes = [30,25,20,15,10];
    //每次保存都重新生成图片，跟随$text更新
    $fileName = md5(date('Y-m-d H:i:s') . $text . uniqid()) . '.png';
    $folder = date('Ymd');
    $savePath = UPLOAD_DIR . DS . 'images' . DS . $folder;
    if(!file_exists($savePath)){
        mkdir($savePath);
    }

    $saveFile = $savePath . DS . $fileName;
    $imageUrl = '';
    foreach($fontSizes as $fontSize){
        $result = generateTextImage($canvasWidth, $canvasHeight, $text, $fontSize, $saveFile);
        if($result){
            $imageUrl = '/' . UPLOAD_FOLDER. '/images/' . $folder . '/' . $fileName;
            break;
        }
    }
    return $imageUrl;
}
function autoGenerateBannerImage($text, $curUrl=''){
    if($curUrl && !startsWith($curUrl, '/' . UPLOAD_FOLDER. '/images/')){
        //自定义图片
        return false;
    }
    $canvasWidth = 750;
    $canvasHeight = 370;
    $fontSizes = [60,50,40,30,25,20,15,10];
    $fileName = md5(date('Y-m-d H:i:s') . $text . uniqid()) . '.png';
    //每次保存都重新生成图片，跟随$text更新
    $folder = date('Ymd');
    $savePath = UPLOAD_DIR . DS . 'images' . DS . $folder;
    if(!file_exists($savePath)){
        mkdir($savePath);
    }
    $saveFile = $savePath . DS . $fileName;
    $imageUrl = '';
    foreach($fontSizes as $fontSize){
        $result = generateTextImage($canvasWidth, $canvasHeight, $text, $fontSize, $saveFile);
        if($result){
            $imageUrl = '/' . UPLOAD_FOLDER. '/images/' . $folder . '/' . $fileName;
            break;
        }
    }
    return $imageUrl;
}
function downloadRemoteUploadFile($fullUrl){
    if(empty($fullUrl)){
        return '';
    }
    if(!startsWith($fullUrl, 'http://') && !startsWith($fullUrl, 'https://')){
        return '';
    }
    //文件夹
    $folder = date('Ymd');
    $savePath = UPLOAD_DIR . DS .  $folder;
    if(!file_exists($savePath)){
        mkdir($savePath);
    }
    //文件名
    $fileName = md5(date('Y-m-d H:i:s') .uniqid());
    $extensionName = pathinfo($fullUrl, PATHINFO_EXTENSION);
    if($extensionName){
        $fileName .= '.';
        $fileName .= $extensionName;
    }

    $saveFullname = $savePath . DS . $fileName;
    $content = file_get_contents($fullUrl);
    if(empty($content)){
        return '';
    }
    $result = file_put_contents($saveFullname, $content);
    if(empty($result)){
        return '';
    }
    //返回数据库中保存的上传文件合法路径
    return '/' . UPLOAD_FOLDER . '/' . $folder . '/' . $fileName;
}
function loginAsAdvanced(){
    return false;
}