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

class Figure extends Controller{
    public function save($inputCtrlName, $figureUrl='', $width=120, $compact=false){
        /*
        $view = new View();
        return $view->fetch('widget/figure_save', [
            'inputCtrlName'=>$inputCtrlName,
            'figureUrl'=>$figureUrl,
            'width'=>$width,
            'uniqid'=>generateUniqid()
        ]);*/
        $tpl = 'figure_save';
        if($compact){
            $tpl = 'figure_save_compact';
        }
        return $this->fetch('widget/' . $tpl, [
            'inputCtrlName'=>$inputCtrlName,
            'figureUrl'=>$figureUrl,
            'width'=>$width,
            'uniqid'=>generateUniqid()
        ]);
        /*
        return view('widget/figure_save', [
            'inputCtrlName'=>$inputCtrlName,
            'figureUrl'=>$figureUrl,
            'width'=>$width,
            'uniqid'=>generateUniqid()
        ]);*/
    }
}