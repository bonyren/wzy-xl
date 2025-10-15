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
use app\index\logic\Admins as AdminsLogic;
use app\index\logic\Menu as MenuLogic;
use think\Db;
use think\Log;
use app\common\service\WException;

class Menus extends Common
{
    public function index($search=[], $page=1, $rows=DEFAULT_PAGE_ROWS, $sort='', $order=''){
        if($this->request->isGet()){
            $urls = [
                'list'=>url('index/Menus/index'),
                'edit'=>url('index/Menus/edit'),
                'delete'=>url('index/Menus/delete'),
            ];
            $this->assign('urls', $urls);
            return $this->fetch();
        }

        $menus = [];
        if (isset($_GET['show_empty'])) {
            $menus[] = ['id'=>0, 'text'=>''];
        }
        MenuLogic::I()->loadLeftMenuRecursively(0, '', $menus, true);
        return json($menus);
    }

    //添加/编辑
    public function edit($id=0, $pid=0){
        if ($this->request->isPost()) {
            //保存
            $data = input('post.data/a', []);
            try {
                MenuLogic::I()->save($id, $data);
                return ajaxSuccess('保存成功');
            } catch (\Exception $e) {
                return ajaxError($e->getMessage());
            }
        }
        if ($id) {
            //修改
            $row = MenuLogic::I()->getRow($id);
            if(empty($row)){
                return $this->fetch('common/missing');
            }
        } else {
            //新增
            $row = MenuLogic::I()->getDefaultRow();
            $row['pid'] = intval($pid);
        }
        $this->assign('row', $row);
        $this->assign('tree_data_url', url('index/Menus/index','show_empty=1'));
        return $this->fetch();
    }

    //删除
    public function delete($id){
        try {
            MenuLogic::I()->delete($id);
        } catch (\Exception $e) {
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess('删除成功');
    }

    /**微信菜单
     * @return mixed|\think\response\Json
     */
    public function wxMenuList(){
        if ($this->request->isGet()) {
            return $this->fetch();
        }
        return json(MenuLogic::I()->wxLoadMenus());
    }

    public function wxMenuSave($id=0, $pid=0){
        if ($this->request->isGet()) {
            $row = $id ? Db::table('wx_menus')->where(['id'=>$id])->find() : [];
            if($id){
                //修改
                $pid = $row['pid'];
            }
            $this->assign([
                'pid'=>$pid,
                'row'=>$row,
                'parents'=>Db::table('wx_menus')->field('id,name')->where(['pid'=>0])->select(),
            ]);
            return $this->fetch();
        }
        $data = $this->request->post('data/a');
        try{
            MenuLogic::I()->wxMenuSave($id, $pid, $data);
            return ajaxSuccess('保存成功');
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }

    public function wxMenuRemove($id){
        Db::table('wx_menus')->where(['id'=>$id])->delete();
        return ajaxSuccess('删除成功');
    }

    public function wxMenuSync(){
        try{
            $res = MenuLogic::I()->wxMenuSync();
            return ajaxSuccess('同步成功', $res);
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
}