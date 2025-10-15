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

class Video extends Controller
{
    public function save($inputCtrlName, $videoUrl='', $width=120){
        return $this->fetch('widget/video_save', [
            'inputCtrlName'=>$inputCtrlName,
            'videoUrl'=>$videoUrl,
            'width'=>$width,
            'uniqid'=>generateUniqid()
        ]);
    }
}