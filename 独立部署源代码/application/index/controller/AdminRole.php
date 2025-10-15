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
use think\Log;
use think\Debug;
use think\Request;
use think\Db;
use app\index\service\Authorize as AuthorizeService;
use app\index\logic\AdminRole as AdminRoleLogic;
use app\index\logic\Defs as IndexDefs;

class AdminRole extends Common
{
    public function adminRole($search=[],$page=1,$rows=DEFAULT_PAGE_ROWS,$sort='',$order=''){
        if(request()->isGet()){
            $urlHrefs = [
                'adminRole'=>url('index/AdminRole/adminRole'),
                'save'=>url('index/AdminRole/save'),
                'delete'=>url('index/AdminRole/delete'),
                'authorize'=>url('index/AdminRole/authorize')
            ];
            $this->assign('urlHrefs', $urlHrefs);
            return $this->fetch();
        }
        return json(AdminRoleLogic::I()->load($search, $page, $rows, $sort, $order));
    }

    public function save($roleId){
        if(request()->isGet()){
            if($roleId){
                //修改
                $infos = AdminRoleLogic::I()->get($roleId);
                if(!$infos){
                    return $this->fetch('common/error', ['msg'=>'无法找到该角色']);
                }
            }else{
                //增加
                $infos = AdminRoleLogic::I()->getDefault();
            }
            $bindValues = [
                'infos'=>$infos
            ];
            $this->assign('bindValues', $bindValues);
            return $this->fetch();
        }
        $infos = input('post.infos/a');
        try{
            AdminRoleLogic::I()->save($roleId, $infos);
            return ajaxSuccess();
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }
    public function delete($roleId){
        try{
            AdminRoleLogic::I()->delete($roleId);
            return ajaxSuccess();
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }
    public function authorize($roleId){
        if(request()->isGet()){
            $urlHrefs = [
                'roleNodes'=>url('index/AdminRole/roleAuthTreeNodes', ['roleId'=>$roleId])
            ];
            $this->assign('urlHrefs', $urlHrefs);
            $conditions = ['role_id'=>$roleId];
            $infos = Db::table('admin_role')->where($conditions)->field(true)->find();
            if(!$infos){
                return $this->fetch('common/error', ['msg'=>'无法找到该角色']);
            }
            $bindValues = [
                'infos'=>$infos
            ];
            $this->assign('bindValues', $bindValues);
            return $this->fetch();
        }
        //保存
        $this->editRoleAuth($roleId, input('post.', []));
        return ajaxSuccess();
    }
    public function roleAuthTreeNodes($roleId){
        $nodes = array();
        $this->loadAuthTreeRecursively($roleId, 0, $nodes);
        $returnRows = [['id'=>'0', 'text'=>'根节点', 'iconCls'=>'fa fa-navicon', 'children'=>$nodes]];
        return json($returnRows);
    }
    protected function editRoleAuth($roleId, $data){
        $nodeIds = $data['nodeIds'];
        if(empty($nodeIds)){
            return;
        }
        $menuDatas = [];

        foreach ($nodeIds as $nodeId){
            $nodeIdArray = explode('_', $nodeId);
            if(count($nodeIdArray) != 2){
                continue;
            }
            $menuDatas[] = ['role_id'=>$roleId,
                'menu_id'=>$nodeIdArray[0],
                'type'=>$nodeIdArray[1]
            ];
        }
        if (empty($menuDatas)) {
            return;
        }
        Db::table('admin_role_menu')->where(array('role_id'=>$roleId))->delete();
        foreach($menuDatas as $menuData) {
            Db::table('admin_role_menu')->insert($menuData);
        }
        return;
    }
    protected function loadAuthTreeRecursively($roleId, $pid, &$nodes){
        $rows = Db::table('menu')
            ->where(array('pid'=>$pid))
            ->field("id,pid,level,name,icon_cls,c,a,params")
            ->order('order_id ASC')
            ->select();
        if(empty($rows)){
            return;
        }
        foreach ($rows as $key => $value) {
            $id = $value['id'];

            $node = array();
            $node['id'] = $id;
            $node['name'] = $value['name'];
            $node['text'] = $value['name'];
            $node['iconCls'] = $value['icon_cls'];

            $subNodes = array();
            $this->loadAuthTreeRecursively($roleId, $id, $subNodes);
            if(!empty($subNodes)){
                $node['children'] = $subNodes;
            }else{
                $roleMenu = AuthorizeService::I()->check($roleId, $id);
                $node['id'] = '';
                if($roleMenu) {
                    if($roleMenu['type'] == IndexDefs::AUTHORIZE_READ_ONLY_TYPE){
                        $finalNode = array(
                            array('id'=>$id.'_'.IndexDefs::AUTHORIZE_READ_ONLY_TYPE,'text'=>'只读','iconCls'=>'fa fa-eye','checked'=>true),
                            array('id'=>$id.'_'.IndexDefs::AUTHORIZE_READ_WRITE_TYPE,'text'=>'读写','iconCls'=>'fa fa-pencil')
                        );
                    }elseif($roleMenu['type'] == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){
                        $finalNode = array(
                            array('id'=>$id.'_'.IndexDefs::AUTHORIZE_READ_ONLY_TYPE,'text'=>'只读','iconCls'=>'fa fa-eye'),
                            array('id'=>$id.'_'.IndexDefs::AUTHORIZE_READ_WRITE_TYPE,'text'=>'读写','iconCls'=>'fa fa-pencil','checked'=>true)
                        );
                    }
                    $node['children'] = $finalNode;
                }else{
                    $node['children'] = array(
                        array('id'=>$id.'_'.IndexDefs::AUTHORIZE_READ_ONLY_TYPE,'text'=>'只读','iconCls'=>'fa fa-eye'),
                        array('id'=>$id.'_'.IndexDefs::AUTHORIZE_READ_WRITE_TYPE,'text'=>'读写','iconCls'=>'fa fa-pencil')
                    );
                }
            }
            $nodes[] = $node;
        }
    }
}