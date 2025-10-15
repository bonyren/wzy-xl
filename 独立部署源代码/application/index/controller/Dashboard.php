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
use app\index\logic\Defs;
use app\index\logic\Dashboard as DashboardLogic;
class Dashboard extends Common{
	public function dashboard() {
		$dashboardLogic = DashboardLogic::newObj();
		$bindValues = [
			'statistic'=>$dashboardLogic->loadStatistic()
		];
		$this->assign('bindValues', $bindValues);
		return $this->fetch();
	}
	public function trend($beginDate=null, $endDate=null){
		if(request()->isGet()){
			$endDate = date('Y-m-d');
			$beginDate = date('Y-m-d', strtotime($endDate . ' -1 month'));
			$this->assign([
				'beginDate'=>$beginDate,
				'endDate'=>$endDate
			]);
			return $this->fetch();
		}
		$dates = [];
		$newUsers = [];
		$evaluateUsers = [];
		$appointUsers = [];
		if(empty($beginDate) || empty($endDate) || $beginDate>$endDate){
			return json(['newUsers'=>$newUsers, 'evaluateUsers'=>$evaluateUsers, 'appointUsers'=>$appointUsers]);
		}
		while($beginDate <= $endDate){
			$dates[] = $beginDate;
			$newUsers[] = Db::table('customer')->where("DATE_FORMAT(register_time,'%Y-%m-%d')='{$beginDate}'")->count();
			$evaluateOrders[] = Db::table('subject_order')->where("DATE_FORMAT(order_time,'%Y-%m-%d')='{$beginDate}'")->count();
			$appointOrders[] = Db::table('expert_order')->where("DATE_FORMAT(order_time,'%Y-%m-%d')='{$beginDate}'")->count();
			$beginDate = date('Y-m-d', strtotime($beginDate . ' +1 day'));
		}
		return json(['dates'=>$dates,
			'newUsers'=>$newUsers,
			'evaluateOrders'=>$evaluateOrders,
			'appointOrders'=>$appointOrders]
		);
	}
}
?>