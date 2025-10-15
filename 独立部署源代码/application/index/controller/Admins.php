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
use app\index\logic\Admins as AdminsLogic;

class Admins extends Common{
	public function admins($search=[],$page=1,$rows=DEFAULT_PAGE_ROWS,$sort='',$order=''){
		if(request()->isGet()){
			$urlHrefs = [
				'admins'=>url('index/Admins/admins'),
				'adminsAdd'=>url('index/Admins/adminsAdd'),
				'adminsEdit'=>url('index/Admins/adminsEdit'),
				'adminsDelete'=>url('index/Admins/adminsDelete'),
				'adminsChangePwd'=>url('index/Admins/adminsChangePwd')
			];
			$this->assign('urlHrefs', $urlHrefs);
			return $this->fetch();
		}
		$adminsLogic = AdminsLogic::newObj();
		return json($adminsLogic->load($search, $page, $rows, $sort, $order));
	}
	public function adminsAdd(){
		$adminsLogic = AdminsLogic::newObj();
		if(request()->isGet()){
			$urlHrefs = [
				'checkAdminEmail'=>url('index/Admins/checkAdminEmail', ['oldValue'=>''])
			];
			$this->assign('urlHrefs', $urlHrefs);
			$bindValues = [
				'adminRolePairs'=>$adminsLogic->getAdminRolePairs()
			];
			$this->assign('bindValues', $bindValues);
			return $this->fetch();
		}
		$infos = input('post.infos/a', []);
		try {
			$adminsLogic->addAdmin($infos);
			return ajaxSuccess();
		}catch (\Exception $e){
			return ajaxError($e->getMessage());
		}
	}
	public function adminsEdit($adminId){
		$adminsLogic = AdminsLogic::newObj();
		if(request()->isGet()){
			$infos = $adminsLogic->getAdminInfos($adminId);
			if(!$infos){
				return $this->fetch('common/error', ['msg'=>'无法找到该管理员']);
			}
			if(empty($infos['role_id'])){
				$infos['role_id'] = '';
			}
			$bindValues = [
				'adminRolePairs'=>$adminsLogic->getAdminRolePairs(),
				'infos'=>$infos
			];
			$this->assign('bindValues', $bindValues);

			$urlHrefs = [
				'checkAdminEmail'=>url('index/Admins/checkAdminEmail', ['oldValue'=>$infos['email']])
			];
			$this->assign('urlHrefs', $urlHrefs);
			return $this->fetch();
		}
		$infos = input('post.infos/a', []);
		try{
			$adminsLogic->editAdmin($adminId, $infos);
			return ajaxSuccess();
		}catch (\Exception $e){
			return ajaxError($e->getMessage());
		}
	}
	public function adminsDelete($adminId){
		$adminsLogic = AdminsLogic::newObj();
		try{
			$adminsLogic->deleteAdmin($adminId);
			return ajaxSuccess();
		}catch (\Exception $e){
			return ajaxError($e->getMessage());
		}
	}
	public function adminsChangePwd($adminId){
		$adminsLogic = AdminsLogic::newObj();
		if(request()->isGet()){
			$infos = $adminsLogic->getAdminInfos($adminId);
			if(!$infos){
				return $this->fetch('common/error', ['msg'=>'无法找到该管理员']);
			}
			$bindValues = [
				'infos'=>$infos
			];
			$this->assign('bindValues', $bindValues);

			return $this->fetch();
		}
		$infos = input('post.infos/a');
		try{
			$adminsLogic->changeAdminPwd($adminId, $infos);
			return ajaxSuccess();
		}catch (\Exception $e){
			return ajaxError($e->getMessage());
		}
	}
	public function checkAdminEmail($oldValue, $email){
		if ($oldValue == $email) {
			return 'true';
		}
		$exists = false;
		if ($exists) {
			return 'false';
		}else{
			return 'true';
		}
	}

    /**
     * 通过id批量获取用户
     * @param string|array $ids
     * @return json string
     */
	/*
	public function getUsersById($ids){
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
		$where = [];
        $where['admin_id'] = isset($ids[1]) ? ['in',$ids] : $ids[0];
        $rows = Db::table('admins')->field('admin_id,email,realname')
            ->where($where)->order('admin_id asc')->select();
        if (empty($rows)) {
            $rows = [];
        }
        return json($rows);
    }*/
}