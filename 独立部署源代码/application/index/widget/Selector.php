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
use think\View as ThinkView;

class Selector
{
    /**
     * @param $inputCtrlName 控件名称
     * @param $inputCtrlValue 控件值
     * @param $dbTable 关联的数据表
     * @param $labelField 关联的数据表文字展示字段
     * @param $valueField 关联数据表值字段
     * @param $selectUrl dialog选择url
     * @param bool|true $multiple 是否多选
     * @param bool|false $readonly 是否只读
     */
    public function save($inputCtrlName,
                         $inputCtrlValue,
                         $dbTable,
                         $labelField,
                         $valueField,
                         $selectUrl,
                         $multiple=true,
                         $readonly=false){
        $selectedRows = [];
        if($inputCtrlValue){
            $values = explode(',', $inputCtrlValue);
            $selectedRows = Db::table($dbTable)->where($valueField, 'in', $values)->field([$valueField, $labelField])->select();
        }
        return ThinkView::instance()->fetch('widget/selector_save', [
            'inputCtrlName'=>$inputCtrlName,
            'inputCtrlValue'=>$inputCtrlValue,
            'selectedRows'=>$selectedRows,
            'labelField'=>$labelField,
            'valueField'=>$valueField,
            'selectUrl'=>$selectUrl,
            'multiple'=>$multiple,
            'readonly'=>$readonly,
            'uniqid'=>generateUniqid()
        ]);
    }
}