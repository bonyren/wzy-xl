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
namespace app\index\widget;
use think\Db;
use think\Log;
use think\View;
use think\Controller;

class Audio extends Controller
{
    public function save($inputCtrlName, $audioUrl='', $width=120){
        return $this->fetch('widget/audio_save', [
            'inputCtrlName'=>$inputCtrlName,
            'audioUrl'=>$audioUrl,
            'width'=>$width,
            'uniqid'=>generateUniqid()
        ]);
    }
}