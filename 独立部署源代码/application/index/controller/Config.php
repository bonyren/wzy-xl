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
use think\Controller;
use think\Db;
use think\Log;
use think\Debug;
use think\Request;
use app\index\logic\Config as ConfigLogic;

class Config extends Common{
    public function index(){
        $urlHrefs = [
            'fieldItems'=>url('config/fieldItems'),
            'categories'=>url('config/categories'),
            'targets'=>url('config/targets'),
        ];
        $this->assign('urlHrefs', $urlHrefs);
        return $this->fetch();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function fieldItems(){
        if(request()->isGet()){
            return $this->fetch();
        }
        $configLogic = ConfigLogic::I();
        return json($configLogic->loadFieldItems());
    }
    public function fieldItemSave($id=0, $fieldId=0){
        $configLogic = ConfigLogic::I();
        if(request()->isGet()){
            if(!$id){
                $infos = [
                    'field_item'=>''
                ];
            }else{
                $infos = $configLogic->getFieldItemInfos($id);
            }
            if(!$infos){
                return $this->fetch('common/error', ['msg'=>'无法找到该项目']);
            }
            $this->assign('infos', $infos);
            return $this->fetch();
        }
        $infos = input('post.infos/a');
        try {
            if(!$id){
                //新增
                $infos['field_id'] = $fieldId;
            }
            $configLogic->saveFieldItem($id, $infos);
            return ajaxSuccess();
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }
    public function fieldItemDelete($id=0){
        $configLogic = ConfigLogic::I();
        $configLogic->deleteFieldItem($id);
        return ajaxSuccess('删除成功');
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function fieldSave($id=0){
        $configLogic = ConfigLogic::I();
        if(request()->isGet()){
            if(!$id){
                $infos = [
                    'field'=>''
                ];
            }else{
                $infos = $configLogic->getFieldInfos($id);
            }
            if(!$infos){
                return $this->fetch('common/error', ['msg'=>'无法找到该项目']);
            }
            $this->assign('infos', $infos);
            return $this->fetch();
        }
        $infos = input('post.infos/a');
        try {
            $configLogic->saveField($id, $infos);
            return ajaxSuccess();
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }
    public function fieldDelete($id=0){
        $configLogic = ConfigLogic::I();
        $configLogic->deleteField($id);
        return ajaxSuccess('删除成功');
    }
    //////////////////////分类///////////////////////////////////////////////////////////////////////////////////
    public function categories(){
        if(request()->isGet()){
            return $this->fetch();
        }
        $configLogic = ConfigLogic::I();
        return json($configLogic->loadCategories());
    }
    public function categorySave($id){
        $configLogic = ConfigLogic::I();
        if(request()->isGet()){
            if(!$id){
                $infos = [
                    'name'=>'',
                    'img_url'=>'',
                    'sort'=>100
                ];
            }else{
                $infos = $configLogic->getCategoryInfos($id);
            }
            if(!$infos){
                return $this->fetch('common/error', ['msg'=>'无法找到该分类']);
            }
            $this->assign('infos', $infos);
            return $this->fetch();
        }
        $infos = input('post.infos/a');
        try {
            $configLogic->saveCategory($id, $infos);
            return ajaxSuccess();
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }
    public function categoryDelete($id){
        $configLogic = ConfigLogic::I();
        $configLogic->deleteCategory($id);
        return ajaxSuccess('删除成功');
    }
    //////////////////////咨询对象///////////////////////////////////////////////////////////////////////////////////
    public function targets(){
        if(request()->isGet()){
            return $this->fetch();
        }
        $configLogic = ConfigLogic::I();
        return json($configLogic->loadTargets());
    }
    public function targetSave($id){
        $configLogic = ConfigLogic::I();
        if(request()->isGet()){
            if(!$id){
                $infos = [
                    'target'=>''
                ];
            }else{
                $infos = $configLogic->getTargetInfos($id);
            }
            if(!$infos){
                return $this->fetch('common/error', ['msg'=>'无法找到该对象']);
            }
            $this->assign('infos', $infos);
            return $this->fetch();
        }
        $infos = input('post.infos/a');
        try {
            $configLogic->saveTarget($id, $infos);
            return ajaxSuccess();
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }
    public function targetDelete($id){
        $configLogic = ConfigLogic::I();
        $configLogic->deleteTarget($id);
        return ajaxSuccess('删除成功');
    }
}