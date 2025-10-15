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
use app\index\controller\Common;
use think\Db;
use think\Log;
use app\index\logic\Defs as IndexDefs;

class Studio extends Common{
    public function preview(){
        //工作室设置
        $studio = [];
        $studioSetting = Db::table('studio')->column('value', 'key');
        $studioSetting = $studioSetting??[];
        $studio['store_name'] = $studioSetting['store_name']??'';
        $studio['store_desc'] = $studioSetting['store_desc']??'';
        $studio['store_contact'] = $studioSetting['store_contact']??'';

        if(!empty($studioSetting['store_index_sections'])){
            $studio['store_index_sections'] = explode(',', $studioSetting['store_index_sections']);
        }else{
            $studio['store_index_sections'] = [];
        }
        if(!empty($studioSetting['store_bottom_tabs'])){
            $studio['store_bottom_tabs'] = explode(',', $studioSetting['store_bottom_tabs']);
        }else{
            $studio['store_bottom_tabs'] = [];
        }
        $this->assign('studio', $studio);
        $this->assign('url', url('mp/Index/index', generateMpAutoLoginParams(Defs::BUILT_IN_MP_USER['id'])));
        return $this->fetch();
    }
    public function setting(){
        if($this->request->isGet()){
            $formData = [];
            $studioSetting = Db::table('studio')->column('value', 'key');
            $studioSetting = $studioSetting??[];
            $formData['store_name'] = $studioSetting['store_name']??'';
            $formData['store_desc'] = $studioSetting['store_desc']??'';
            $formData['store_contact'] = $studioSetting['store_contact']??'';
            $formData['store_index_sections'] = $studioSetting['store_index_sections']??'';
            $formData['store_bottom_tabs'] = $studioSetting['store_bottom_tabs']??'';
            $this->assign('formData', $formData);
            return $this->fetch();
        }
        $formData = input('post.formData/a');
        if(empty($formData['store_index_sections'])){
            $formData['store_index_sections'] = '';
        }else{
            $formData['store_index_sections'] = implode(',', $formData['store_index_sections']);
        }
        if(empty($formData['store_bottom_tabs'])){
            $formData['store_bottom_tabs'] = '';
        }else{
            //首页必须要保留
            array_unshift($formData['store_bottom_tabs'], IndexDefs::STORE_BOTTOM_TAB_INDEX);
            $formData['store_bottom_tabs'] = implode(',', $formData['store_bottom_tabs']);
        }
        foreach($formData as $key=>$value){
            Db::table('studio')->where(['key'=>$key])->setField('value', $value);
        }
        return ajaxSuccess();
    }
}