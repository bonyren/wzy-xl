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
use app\Defs;
use app\index\logic\Defs as IndexDefs;
use think\Db;
use think\Log;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class Qrcode extends Common
{
    public function download($name, $savePath){

        $diskPath = SITE_DIR . $savePath;
        if(!file_exists($diskPath)){
            return $this->fetch('common/error', ['msg'=>'无法找到该二维码图片文件']);
        }
        $path_parts = pathinfo($savePath);
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/force-download');
        header('Content-Length: ' . filesize($diskPath));
        header("Content-Disposition: attachment; filename=" . $name . "." . $path_parts['extension']);
        readfile($diskPath);
        exit();
    }

    public function displayQrcode($text, $title=""){
        if(empty($title)){
            $title = "二维码";
        }
        //the & in url will be converted to &amp;
        $text = htmlspecialchars_decode($text);

        $this->assign('title', $title);
        $this->assign('text', $text);
        $logoPath = convertUploadAbsoluteUrl2DiskFullPath('/static/img/logo.png');
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($text)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(360)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->logoPath($logoPath)
            ->logoResizeToWidth(50)
            ->logoPunchoutBackground(true)
            ->labelText($title)
            ->labelFont(new NotoSans(20))
            ->labelAlignment(new LabelAlignmentCenter())
            ->validateResult(false)
            ->build();
        /*
        // Directly output the QR code
        header('Content-Type: '.$result->getMimeType());
        echo $result->getString();

        // Save it to a file
        $result->saveToFile(__DIR__.'/qrcode.png');

        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        $dataUri = $result->getDataUri();
        */
        $dataUri = $result->getDataUri();
        $this->assign('dataUri', $dataUri);
        return $this->fetch();
    }
}