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
namespace app\mp\controller;
use app\Defs;
use app\mp\service\Subjects;
use EasyWeChat\Factory;
use think\Db;
use think\Log;
use think\Debug;
use app\index\service\EventLogs as EventLogsService;
use app\index\logic\SurveyOrganization as SurveyOrganizationLogic;
use app\common\service\Redis as RedisService;
use app\common\service\WException;
use Dompdf\Dompdf;
use Dompdf\Options;
use app\mp\service\Users as UsersService;
use app\index\logic\Defs as IndexDefs;
use app\index\logic\Subject as SubjectLogic;

class Subject extends Base
{
    public function index() {}

    /**测评详情
     * @param $id
     * @param int $cb_order_id
     * @return mixed
     */
    public function detail($id, $cb_order_id=0, $survey_order_id=0){
        $subject = Subjects::I()->getSubjectById($id);
        if(!$subject){
            $this->error("无法找到该量表");
        }
        if($subject['delete_flag'] == 1){
            $this->error("该量表已删除");
        }
        if($subject['status'] == IndexDefs::ENTITY_DRAFT_STATUS){
            $this->error("该量表未发布");
        }
        if($subject['status'] == IndexDefs::ENTITY_END_STATUS){
            $this->error("该量表已中止");
        }
        if ($cb_order_id) {
            //组合测试, 允许其中的量表被软删除
            $cb_order = Db::table('combination_order')->field('uid')->where(['id'=>$cb_order_id, 'uid'=>$this->_uid])->find();
            if (empty($cb_order)) {
                //非组合测试
                $cb_order_id = 0;
            }
        }else if($survey_order_id){
            //普查测试, 允许其中的量表被软删除
            $survey_order = Db::table('survey_order')->field('*')->where(['id'=>$survey_order_id, 'uid'=>$this->_uid])->find();
            if (empty($survey_order)) {
                //非组合测试
                $survey_order_id = 0;
            }else{
                $survey = Db::table('survey')->where('id', $survey_order['survey_id'])->find();
                if(empty($survey)) {
                    $this->error('普查测试不存在');
                }
                if(intval($survey['cfg_free'])){
                    //免费模式
                    systemSettingSetTemp('subject_show_price', 'no');
                    //重置价格
                    $subject['current_price'] = 0;
                }
            }
        }/*else{
            //单个量表测评，检查该量表是否已删除
            $subjectExist = Db::table('subject')->where(['id'=>$id, 'delete_flag'=>0])
                ->field('id')
                ->find();
            if(!$subjectExist){
                $this->error("无法找到该量表");
            }
        }*/
        //是否收藏
        $subject['is_collected'] = Subjects::I()->isCollected($this->_uid, $id);
        //上次是否未完成
        $unfinished = Subjects::I()->getUnFinishTest($this->_uid, $id, $cb_order_id, $survey_order_id);
        if(!$unfinished){
            $unfinished = [];
        }
        $this->assign([
            'subject' => $subject,
            'unfinished' => $unfinished,
            'cb_order_id' => intval($cb_order_id),
            'survey_order_id'=>intval($survey_order_id)
        ]);
        return $this->fetch();
    }
    //收藏
    public function collect($id,$type){
        Subjects::I()->collect($this->_uid,$id,$type);
        return ajaxSuccess($type?'收藏成功':'已取消收藏');
    }
    //生成测评订单
    public function genOrder($subject_id, $cb_order_id=0, $survey_order_id=0){
        try {
            $order = Subjects::I()->generateOrder($this->_uid, $subject_id, Defs::CHANNEL_WX, $cb_order_id, $survey_order_id);
        } catch (\Exception $e) {
            Log::error('订单生成失败：' . $e->getMessage());
            return ajaxError('订单生成失败：' . $e->getMessage());
        }
        $data = [];
        $data['order_no'] = $order['order_no'];
        return ajaxSuccess('操作成功', $data);
    }    
    /**
     * 重新生成订单
     *
     * @param  mixed $order_no
     * @return void
     */
    public function regenOrder($order_no){
        try {
            Subjects::I()->regenerateOrder($order_no);
        } catch (\Exception $e) {
            Log::error('订单重新生成失败：' . $e->getMessage());
            return ajaxError('订单重新生成失败：' . $e->getMessage());
        }
        return ajaxSuccess();
    }

    //组合测试, 扫码进入   
    /**
     * combination_test
     *
     * @param  mixed $combination_id
     * @return void
     */
    public function combination_test($combination_id, $force_new_order=0){
        $cb = Db::table('subject_combination')->where(['delete_flag'=>0, 'id'=>$combination_id])->field(true)->find();
        if(empty($cb)) {
            //$this->error('组合测试不存在');
            return $this->fetch('common/error', ['msg'=>'组合测试不存在']);
        }
        if($cb['status'] == IndexDefs::ENTITY_DRAFT_STATUS){
            return $this->fetch('common/error', ['msg'=>'该组合测试未发布']);
        }
        if($cb['status'] == IndexDefs::ENTITY_END_STATUS){
            return $this->fetch('common/error', ['msg'=>'该组合测试已中止']);
        }
        if(empty($cb['subjects'])){
            //$this->error('组合测试不存在量表');
            return $this->fetch('common/error', ['msg'=>'组合测试不存在量表']);
        }
        do{
            if($force_new_order) {
                break;
            }
            //查找未完成的组合测评
            $map = [
                'uid'=>$this->_uid,
                'combination_id'=>$combination_id,
            ];
            $cb_order = Db::table('combination_order')->where($map)->order('id desc')->find();
            if (empty($cb_order) || $cb_order['finished'] == 1){
                break;
            }else{
                $subjectOrder = Db::table('subject_order')->where(['cb_order_id'=>$cb_order['id']])->find();
                if(!$subjectOrder){
                    //没有开始具体量表测评, 删除上次创建订单,启用新订单
                    Db::table('combination_order')->where(['id'=>$cb_order['id']])->delete();
                    break;
                }
            }
            //历史订单
            $cb_order_id = $cb_order['id'];
            $subjects = json_decode($cb_order['data'], true);
            $next_test_id = 0;
            foreach ($subjects as $sid=>$finished) {
                if (empty($finished)) {
                    $next_test_id = $sid;
                    break;
                }
            }
            if(empty($next_test_id)){
                //$this->error('无法找到要测评的量表');
                return $this->fetch('common/error', ['msg'=>'无法找到要继续测评的量表']);
            }            
            return $this->fetch('continue_last', ['title'=>'测评', 
                'continue_url'=>url('mp/Subject/detail',['id'=>$next_test_id, 'cb_order_id'=>$cb_order_id]), 
                'new_url'=>url('mp/Subject/combination_test', ['combination_id'=>$combination_id, 'force_new_order'=>1])
            ]);
        }while(false);
        $costTime = Db::table('subject')->where('id', 'in', $cb['subjects'])->sum('expect_finish_time');
        return $this->fetch('combination_welocme', [
            'banner'=>$cb['banner'],
            'name'=>$cb['name'],
            'costTime'=>$costTime,//分钟
            'subjectCount'=>count(explode(',', $cb['subjects'])),
            'description'=>$cb['description'],
            'beginTestUrl'=>url('mp/Subject/combination_test_run',['combination_id'=>$combination_id])
        ]);
    }
    public function combination_test_run($combination_id){
        $cb = Db::table('subject_combination')->where(['delete_flag'=>0,'id'=>$combination_id])->find();
        if(empty($cb)) {
            $this->error('组合测试不存在');
        }
        if(empty($cb['subjects'])){
            $this->error('组合测试不存在量表');
        }
        //新订单
        $subjectIds = explode(',', $cb['subjects']);
        //量表状态
        $subjects = [];
        foreach ($subjectIds as $subjectId) {
            $subjects[$subjectId] = 0;
        }
        $map = [
            'uid'=>$this->_uid,
            'combination_id'=>$combination_id,
            'finished'=>0//未完成
        ];
        $cb_order_id = Db::table('combination_order')->insertGetId(array_merge($map, ['data'=>json_encode($subjects)]));
        if (empty($cb_order_id)) {
            $this->error('组合测试订单生成失败');
        }
        $next_test_id = 0;
        foreach ($subjects as $sid=>$finished) {
            if (empty($finished)) {
                $next_test_id = $sid;
                break;
            }
        }
        if(empty($next_test_id)){
            $this->error('无法找到要开始测评的量表');
        }
        $this->redirect('mp/Subject/detail',['id'=>$next_test_id, 'cb_order_id'=>$cb_order_id]);
    }
    public function combination_result($cb_order_id){
        $combination_order = Db::table('combination_order')->where(['id'=>$cb_order_id])->find();
        if(empty($combination_order)){
            return $this->fetch('common/error', ['msg'=>'无法找到组合测评订单']);
        }
        $combination = Db::table('subject_combination')->where(['id'=>$combination_order['combination_id']])->find();
        if(empty($combination)){
            return $this->fetch('common/error', ['msg'=>'无法找到关联的组合测评']);
        }
        $cb_order_id = $combination_order['id'];
        $subjects = json_decode($combination_order['data'], true);
        $next_test_id = 0;

        $completed_count = 0;
        $subjectIdFirst = 0;
        foreach ($subjects as $sid=>$finished) {
            if($subjectIdFirst === 0){
                $subjectIdFirst = $sid;
            }
            if (empty($finished)) {
                $next_test_id = $sid;
                break;
            }else{
                $completed_count++;
            }
        }
        if(empty($next_test_id)){
            //允许查看报告
            //return $this->fetch('common/success', ['msg'=>'祝贺！您已该完成组合全部量表测评']);
            return $this->fetch('continue_report', ['msg'=>"祝贺！您已该完成组合全部量表测评", 
                'report_url'=>url('mp/Subject/reportGroup',['cb_order_id'=>$cb_order_id])]
            );
        }else{
            $total = count($subjects);
            $title = '当前量表测评已完成';
            if(empty($completed_count)){
                //从"我的"->"继续测评进入"
                $title = $combination['name'];
            }
            return $this->fetch('continue_test', ['title'=>$title, 'msg'=>"本次组合测评包含<strong>{$total}</strong>个量表，已完成<strong>{$completed_count}</strong>个", 
                'next_url'=>url('mp/Subject/detail',['id'=>$next_test_id, 'cb_order_id'=>$cb_order_id])]
            );
        }
    }
    //普查测评, 扫码进入    
    /**
     * survey_test
     *
     * @param  mixed $survey_id
     * @return void
     */
    public function survey_test($survey_id, $force_new_order=0){
        $survey = Db::table('survey')->where(['delete_flag'=>0,'id'=>$survey_id])->field(true)->find();
        if(empty($survey)) {
            return $this->fetch('common/error', ['msg'=>'普查测试不存在']);
        }
        if($survey['status'] == IndexDefs::ENTITY_DRAFT_STATUS){
            return $this->fetch('common/error', ['msg'=>'该普查测试未发布']);
        }
        if($survey['status'] == IndexDefs::ENTITY_END_STATUS){
            return $this->fetch('common/error', ['msg'=>'该普查测试已中止']);
        }
        if(empty($survey['subjects'])){
            return $this->fetch('common/error', ['msg'=>'普查测试不存在量表']);
        }
        do{
            if($force_new_order){
                break;
            }
            //查找未完成的组合测评
            $map = [
                'uid'=>$this->_uid,
                'survey_id'=>$survey_id,
            ];
            $survey_order = Db::table('survey_order')->where($map)->order('id desc')->find();
            if (empty($survey_order) || $survey_order['finished'] == 1) {
                break;
            }else{
                $subjectOrder = Db::table('subject_order')->where(['survey_order_id'=>$survey_order['id']])->find();
                if(!$subjectOrder && !$survey['cfg_enter_personal_data']){
                    //没有开始具体量表测评,并且不要求录入个人信息
                    //删除上次创建订单,启用新订单
                    Db::table('survey_order')->where(['id'=>$survey_order['id']])->delete();
                    break;
                }
            }
            //历史订单
            $survey_order_id = $survey_order['id'];
            $subjects = json_decode($survey_order['data'], true);
            $next_test_id = 0;
            foreach ($subjects as $sid=>$finished) {
                if (empty($finished)) {
                    $next_test_id = $sid;
                    break;
                }
            }
            if(empty($next_test_id)){
                return $this->fetch('common/error', ['msg'=>'无法找到要继续测评的量表']);
            }
            if(!$survey['cfg_enter_personal_data']){
                return $this->fetch('continue_last', ['title'=>'普查', 
                    'continue_url'=>url('mp/Subject/detail',['id'=>$next_test_id, 'survey_order_id'=>$survey_order_id]), 
                    'new_url'=>url('mp/Subject/survey_test', ['survey_id'=>$survey_id, 'force_new_order'=>1])
                ]);
            }else{
                return $this->fetch('continue_last', ['title'=>'普查', 
                    'continue_url'=>url('mp/Subject/survey_personal_data',['survey_id'=>$survey_id, 'survey_order_id'=>$survey_order_id]), 
                    'new_url'=>url('mp/Subject/survey_test', ['survey_id'=>$survey_id, 'force_new_order'=>1])
                ]);
            }
        }while(false);
        if($survey['cfg_enter_personal_data']){
            //录入个人资料
            $this->redirect('mp/Subject/survey_personal_data',['survey_id'=>$survey_id]);
        }else{
            $costTime = Db::table('subject')->where('id', 'in', $survey['subjects'])->sum('expect_finish_time');
            return $this->fetch('survey_welocme', [
                'banner'=>$survey['banner'],
                'name'=>$survey['name'],
                'costTime'=>$costTime,//分钟
                'subjectCount'=>count(explode(',', $survey['subjects'])),
                'description'=>$survey['description'],
                'beginTestUrl'=>url('mp/Subject/survey_test_run',['survey_id'=>$survey_id])
            ]);
        }
    }
    public function survey_test_run($survey_id){
        $survey = Db::table('survey')->where(['delete_flag'=>0,'id'=>$survey_id])->find();
        if(empty($survey)) {
            $this->error('普查测试不存在');
        }
        if(empty($survey['subjects'])){
            $this->error('普查测试不存在量表');
        }
        $map = [
            'uid'=>$this->_uid,
            'survey_id'=>$survey_id,
            'finished'=>0//未完成
        ];
        //新订单
        $subjectIds = explode(',', $survey['subjects']);
        //量表状态
        $subjects = [];
        foreach ($subjectIds as $subjectId) {
            $subjects[$subjectId] = 0;
        }
        $survey_order_id = Db::table('survey_order')->insertGetId(array_merge($map, ['data'=>json_encode($subjects)]));
        if (empty($survey_order_id)) {
            $this->error('组合测试订单生成失败');
        }
        $next_test_id = 0;
        foreach ($subjects as $sid=>$finished) {
            if (empty($finished)) {
                $next_test_id = $sid;
                break;
            }
        }
        if(empty($next_test_id)){
            $this->error('无法找到要测评的量表');
        }
        $this->redirect('mp/Subject/detail',['id'=>$next_test_id, 'survey_order_id'=>$survey_order_id]);
    }
    public function survey_personal_data($survey_id=0, $survey_order_id=0){
        $survey = Db::table('survey')->where('id', $survey_id)->find();
        if(empty($survey)) {
            return $this->fetch('common/error', ['msg'=>'普查测试不存在']);
        }
        if(empty($survey['subjects'])){
            return $this->fetch('common/error', ['msg'=>'普查测试不存在量表']);
        }
        if($this->request->isGet()){
            //欢迎信息
            if($survey_order_id){
                //修改
                $surveyOrder = Db::table('survey_order')->where('id', $survey_order_id)->find();
                if(empty($surveyOrder)){
                    return $this->fetch('common/error', ['msg'=>'普查测试订单不存在']);
                }
                $subjects = json_decode($surveyOrder['data'], true);
                $costTime = Db::table('subject')->where('id', 'in', array_keys($subjects))->sum('expect_finish_time');
                $this->assign([
                    'banner'=>$survey['banner'],
                    'name'=>$survey['name'],
                    'costTime'=>$costTime,//分钟
                    'subjectCount'=>count($subjects),
                    'description'=>$survey['description'],
                ]);
            }else{
                //新订单
                $costTime = Db::table('subject')->where('id', 'in', $survey['subjects'])->sum('expect_finish_time');
                $this->assign([
                    'banner'=>$survey['banner'],
                    'name'=>$survey['name'],
                    'costTime'=>$costTime,//分钟
                    'subjectCount'=>count(explode(',', $survey['subjects'])),
                    'description'=>$survey['description'],
                ]);
            }

            //配置项
            $cfg_personal_data = [];
            if($survey['cfg_personal_data']){
                $cfg_personal_data = explode(',', $survey['cfg_personal_data']);
            }
            $this->assign('cfg_personal_data', $cfg_personal_data);
            $surveyOrganizationDatas = SurveyOrganizationLogic::I()->loadComboDatasLeaf($survey_id, 0, '', []);
            $this->assign('surveyOrganizationDatas', $surveyOrganizationDatas);
            //配置值
            $personal_data = [
                'name'=>'',
                'sex'=>1,
                'age'=>16,
                'mobile'=>'',
                'address'=>'',
                'organization'=>0,
                'id_card'=>''
            ];
            if(isset($surveyOrder) && $surveyOrder['personal_data']){
                $personal_data = array_merge($personal_data, json_decode($surveyOrder['personal_data'], true));
            }
            $this->assign('personal_data', $personal_data);
            //url
            $this->assign('submitUrl', url('mp/Subject/survey_personal_data', ['survey_id'=>$survey_id, 'survey_order_id'=>$survey_order_id]));
            return $this->fetch('survey_personal_data');
        }
        $formData = input('post.formData/a');
        if(!$survey_order_id){
            //新增订单
            $subjectIds = explode(',', $survey['subjects']);
            //量表状态
            $subjects = [];
            foreach ($subjectIds as $subjectId) {
                $subjects[$subjectId] = 0;
            }
            $survey_order_id = Db::table('survey_order')->insertGetId(array_merge([
                'uid'=>$this->_uid,
                'survey_id'=>$survey_id,
                'finished'=>0//未完成
            ], ['data'=>json_encode($subjects)]));
            if (empty($survey_order_id)) {
                $this->error('组合测试订单生成失败');
            }
            $surveyOrder = Db::table('survey_order')->where('id', $survey_order_id)->find();
            if(empty($surveyOrder)){
                $this->error('普查测试订单不存在');
            }
        }else{
            $surveyOrder = Db::table('survey_order')->where('id', $survey_order_id)->find();
            if(empty($surveyOrder)){
                $this->error('普查测试订单不存在');
            }
        }
        //保存
        Db::table('survey_order')->where('id', $survey_order_id)->setField('personal_data', json_encode($formData));
        //处理所在的组织
        $survey_organization_id = 0;
        if(isset($formData['organization'])){
            $survey_organization_id = $formData['organization'];
        }
        Db::table('survey_order')->where('id', $survey_order_id)->setField('survey_organization_id', $survey_organization_id);
        //跳转量表
        $subjects = json_decode($surveyOrder['data'], true);
        $next_test_id = 0;
        foreach ($subjects as $sid=>$finished) {
            if (empty($finished)) {
                $next_test_id = $sid;
                break;
            }
        }
        if(empty($next_test_id)){
            $this->error('无法找到要测评的量表');
        }
        $this->redirect('mp/Subject/detail',['id'=>$next_test_id, 'survey_order_id'=>$survey_order_id]);
    }
    public function survey_result($survey_order_id){
        $survey_order = Db::table('survey_order')->where(['id'=>$survey_order_id])->find();
        if(empty($survey_order)){
            //$this->error('无法找到普查测试订单');
            return $this->fetch('common/error', ['msg'=>'无法找到普查测评订单']);
        }
        $survey = Db::table('survey')->where(['id'=>$survey_order['survey_id']])->find();
        if(empty($survey)){
            return $this->fetch('common/error', ['msg'=>'无法找到关联的普查']);
        }
        $survey_order_id = $survey_order['id'];
        $subjects = json_decode($survey_order['data'], true);
        $next_test_id = 0;

        $completed_count = 0;
        foreach ($subjects as $sid=>$finished) {
            if (empty($finished)) {
                $next_test_id = $sid;
                break;
            }else{
                $completed_count++;
            }
        }
        if(empty($next_test_id)){
            //$this->success('普查测评已完成');
            $allowViewReport = Db::table('survey')->where(['id'=>$survey_order['survey_id']])->value('cfg_view_report');
            if($allowViewReport){
                return $this->fetch('continue_report', ['msg'=>"祝贺！您已该完成普查全部量表测评", 
                    'report_url'=>url('mp/Subject/reportGroup',['survey_order_id'=>$survey_order_id])]
                );
            }else{
                return $this->fetch('common/success', ['msg'=>'祝贺！您已该完成普查全部量表测评']);
            }
        }else{
            $total = count($subjects);
            $title = '当前量表测评已完成';
            if(empty($completed_count)){
                //从"我的"->"继续测评进入"
                $title = $survey['name'];
            }
            return $this->fetch('continue_test', ['title'=>$title, 'msg'=>"本次普查测评包含<strong>{$total}</strong>个量表，已完成<strong>{$completed_count}</strong>个", 
                'next_url'=>url('mp/Subject/detail',['id'=>$next_test_id, 'survey_order_id'=>$survey_order_id])]
            );
        }
    }
    public function question_form($order_no){
        if($this->request->isGet()){
            $order = Subjects::I()->getOrderByNo($order_no);
            if(empty($order)){
                $this->error('测评订单不存在：'.$order_no);
            }
            $subject = Subjects::I()->getSubjectById($order['subject_id']);
            if(empty($subject)){
                $this->error('该测评订单关联量表丢失：'.$order_no);
            }
            $questionForm = '';
            if($order['question_form']){
                $questionFormItems = json_decode($order['question_form'], true);
                foreach($questionFormItems as $questionFormItem){
                    $questionForm .= $questionFormItem['html'];
                }
            }
            $questionAnswer = '[]';
            if($order['question_answer']){
                $questionAnswer = $order['question_answer'];
            }
            $this->assign('questionForm', $questionForm);
            $this->assign('questionAnswer', $questionAnswer);
            $this->assign('subject', $subject);
            $this->assign('order',$order);
            return $this->fetch();
        }
        $questionAnswer = input('post.question_answer');
        Db::table('subject_order')->where(['order_no'=>$order_no])
            ->update([
                'question_answer'=>htmlspecialchars_decode($questionAnswer)
            ]);
        return ajaxSuccess();
    }
    //标准量表测评
    public function test($order_no, $skip_question=0){
        $order = Subjects::I()->getOrderByNo($order_no);
        if(empty($order)){
            $this->error('测评订单不存在：'.$order_no);
        }
        if(empty($order['customer_id'])){
            //指定用户
            Db::table('subject_order')->where(['order_no'=>$order_no])->setField('customer_id', $this->_uid);
            $order['customer_id'] = $this->_uid;
        }
        if($order['customer_id'] != $this->_uid) {
            //订单是否只能由该用户来操作
            $this->error('该测评订单属于其他用户：'.$order_no);
        }
        if ($order['pay_status'] != Defs::PAY_SUCCESS) {
            $this->error('该订单尚未支付：'.$order_no, url('mp/Subject/detail',['id'=>$order['subject_id'], 'cb_order_id'=>$order['cb_order_id'], 'survey_order_id'=>$order['survey_order_id']]));
        }
        if ($order['finished']) {
            $this->success("该测评订单已经完成，正跳转到报告", url('mp/Subject/report', ['order_no'=>$order_no]));
        }
        $subject = Subjects::I()->getSubjectById($order['subject_id']);
        if(empty($subject)){
            $this->error('该测评订单关联量表丢失：'.$order_no);
        }
        if(!$skip_question){
            if(!empty($order['question_form'])){
                $this->redirect('mp/Subject/question_form', ['order_no'=>$order_no]);
                return;
            }
        }
        $subjectItems = Subjects::I()->getSubjectItems($order['subject_id']);
        if (empty($subjectItems)) {
            $this->error('获取测评题目失败：'.$order_no);
        }
        if($order['items']){
            $subjectItemsExist = [];
            $itemExists = explode(',', $order['items']);
            foreach ($itemExists as $itemExist) {
                $itemSections = explode('&', $itemExist);
                if(count($itemSections) != 3){
                    continue;
                }
                $itemIdExist = $itemSections[0];
                $itemTypeExist = $itemSections[1];
                $itemValueExist = $itemSections[2];
                $subjectItemsExist[$itemIdExist] = [
                    'type'=>$itemTypeExist,
                    'value'=>($itemTypeExist == Defs::QUESTION_CHECKBOX)?explode('-', $itemValueExist):$itemValueExist
                ];
            }
            foreach($subjectItems as $subjectItemId=>&$subjectItem){
                if(isset($subjectItemsExist[$subjectItemId]) &&
                    $subjectItem['type'] == $subjectItemsExist[$subjectItemId]['type']){
                    $subjectItem['value'] = $subjectItemsExist[$subjectItemId]['value'];
                }
            }
        }
        foreach($subjectItems as $subjectItemId=>&$subjectItem){
            if($subjectItem['image']){
                //缩略图
                $subjectItem['image'] = generateThumbnailUrl($subjectItem['image'], 200);
            }
            $optionIndex = 1;
            while($optionIndex<=12){
                $imageField = 'image_' . $optionIndex;
                if($subjectItem[$imageField]){
                    //缩略图
                    $subjectItem[$imageField] = generateThumbnailUrl($subjectItem[$imageField], 100);
                }
                $optionIndex++;
            }
        }
        //输入到js对象，会自动按照key进行升序排序，增加字母前缀，禁用自动排序
        $subjectItemsClone = [];
        foreach($subjectItems as $key=>$item){
            $subjectItemsClone['id_' . $key] = $item;
        }
        $this->assign('subject', $subject);
        $this->assign('subjectItems',json_encode($subjectItemsClone, JSON_UNESCAPED_UNICODE));
        $this->assign('order',$order);
        return $this->fetch();
    }
    /**
     * 测评答题
     *
     * @param  mixed $order_no
     * @param  mixed $item_id
     * @param  mixed $item_type
     * @param  mixed $item_option, 多选允许为空的情况下,item_option参数会缺失
     * @return void
     */
    //答题 单选:$item_option为option id, 多选:$item_option为数组, 填写: $item_option为文本    
    public function answer($order_no, $item_id, $item_type, $item_option=''){
        try {
            $order = Subjects::I()->answerItem($order_no, $item_id, $item_type, $item_option);
        } catch (\Exception $e) {
            $exceptionCode = $e->getCode();
            if($exceptionCode == -1){
                //测评项目版本变更
                return ajaxError('测评项目版本变更', -1);
            }
            return ajaxError($e->getMessage());
        }
        if($order['finished']){
            if($order['survey_order_id']){
                //普查
                $redirectUrl = url('mp/Subject/survey_result',['survey_order_id'=>$order['survey_order_id']]);
                return ajaxSuccess('操作成功', $redirectUrl);
            }else if($order['cb_order_id']){
                //组合测评
                $redirectUrl = url('mp/Subject/combination_result',['cb_order_id'=>$order['cb_order_id']]);
                return ajaxSuccess('操作成功', $redirectUrl);
            }
            //结束测评
            if ($order['pay_status'] != Defs::PAY_SUCCESS) {
                //支付
                $redirectUrl = url('mp/Subject/buy',['order_no'=>$order_no]);
            }else{
                //报告
                $redirectUrl = url('mp/Subject/report',['order_no'=>$order_no]);
            }
            if(empty($order['test_allow_view_report'])){
                //不允许查看报告, 跳转“我的”
                $redirectUrl = url('mp/Ucenter/index');
            }
            return ajaxSuccess('操作成功', $redirectUrl);
        }else{
            return ajaxSuccess();
        }
    }
    public function buy($order_no){
        if(request()->isGet()) {
            //“先测评再购买”专用
            $order = Subjects::I()->getOrderByNo($order_no);
            if (empty($order)) {
                return $this->fetch('common/missing');
            }
            if ($order['pay_status'] == Defs::PAY_SUCCESS) {
                //已经支付
                $this->redirect('mp/Subject/report', ['order_no' => $order_no]);
            }
            $subject = Subjects::I()->getSubjectById($order['subject_id']);
            $this->assign([
                'pageTitle'=>'测评报告',
                'order'=>$order,
                'subject'=>$subject
            ]);
            return $this->fetch();
        }
        if($this->_uid == 1){
            return ajaxError("当前环境不支持支付操作");
        }
        $order = Subjects::I()->getOrderByNo($order_no);
        if (empty($order)) {
            return ajaxError("无法找到该订单");
        }
        if ($order['pay_status'] == Defs::PAY_SUCCESS) {
            //已经支付
            return ajaxError("该订单已经支付");
        }
        //更新支付配置
        wxPaySetting();
        $app = Factory::payment(array_merge(config('wx.payment'), ['app_id'=>config('wx.official_account')['app_id']]));
        $res = $app->order->unify([
            'body' => '测评费用',
            'out_trade_no' => $order['order_no'],
            'total_fee' => $order['order_amount'] * 100, //分为单位，整数
            'notify_url' => url('mp/Wx/paidSubjectOrder','',false,true), //微信异步通知地址，不能携带参数
            'trade_type' => 'JSAPI', //H5支付
            'openid' => $this->_openid,
        ]);
        /*
         * array (
              'return_code' => 'SUCCESS',
              'return_msg' => 'OK',
              'result_code' => 'FAIL',
              'err_code_des' => 'appid和openid不匹配',
              'err_code' => 'PARAM_ERROR',
              'mch_id' => '1598729631',
              'appid' => 'wx24cbf5a879d1b1a3',
              'nonce_str' => 'JjsQDV8tz8YW7YL7',
              'sign' => '2B45420A6A435B27F0910A654217BAF6',
            )
         */
        Log::notice("wx pay return: " . var_export($res, true));
        if(empty($res)){
            Log::error("{$order_no} 微信下单失败：返回空");
            return ajaxError('微信下单失败：返回空');
        }
        if(!is_array($res)){
            Log::error("{$order_no} 微信下单失败：返回 " . $res);
            return ajaxError('微信下单失败：返回' . $res);
        }
        if ($res['return_code'] == 'FAIL') {
            logEvent("测评订单{$order_no}支付失败, {$res['return_msg']}", EventLogsService::eSeverityWarning);
            return ajaxError('微信下单失败：' . $res['return_msg']);
        }
        if ($res['result_code'] == 'FAIL') {
            logEvent("测评订单{$order_no}支付失败, {$res['err_code_des']}", EventLogsService::eSeverityWarning);
            return ajaxError('微信下单失败：' . $res['err_code_des']);
        }
        if(!isset($res['prepay_id'])){
            return ajaxError('微信下单失败：无法找到prepay_id');
        }
        //prepay_id:预支付交易会话标识。用于后续接口调用中使用，该值有效期为2小时
        Db::table('subject_order')->where(['order_no'=>$order['order_no']])->setField('prepay_id',$res['prepay_id']);
        $data = [];
        $data['order_no'] = $order_no;
        $data['need_pay'] = $order['order_amount']>0?true:false;
        $data['config'] = $app->jssdk->bridgeConfig($res['prepay_id'],false); // 返回数组
        return ajaxSuccess('操作成功', $data);
    }
    public function checkOrderPaid($order_no){
        $order = Subjects::I()->getOrderByNo($order_no);
        if (empty($order) || $order['customer_id'] != $this->_uid) {
            return ajaxError('订单不存在');
        }
        return ajaxSuccess('操作成功', $order['pay_status'] == Defs::PAY_SUCCESS);
    }
        /**
     * 重新支付
     *
     * @param  mixed $orderNo
     * @return void
     */
    public function uCenterBuy($id=''){
        $order = Subjects::I()->getOrderById($id);
        if (empty($order) || $order['customer_id'] != $this->_uid) {
            return ajaxError('订单不存在');
        }
        $order_no = $order['order_no'];
        if($order['prepay_id']) {
            //已经发起过支付的，则要重新生成order no
            $order_no = Subjects::I()->refreshOrderNoById($order['customer_id'], $id);
            if(!$order_no){
                return ajaxError('刷新订单编号失败');
            }
        }
        return $this->buy($order_no);
    }  
    //报告    
    /**
     * report
     *
     * @param  mixed $order_no
     * @param  mixed $tpl
     * @return void
     */
    public function report($order_no, $source='', $internalView=0){
        Debug::remark('begin');
        $order = Subjects::I()->getOrderByNo($order_no);
        if(empty($order)){
            //$this->error('无法找到订单');
            return $this->fetch('common/missing', ['msg'=>'无法找到订单']);
        }
        Debug::remark('getOrderByNo');
        Log::notice("report debug: getOrderByNo cost: " . Debug::getRangeTime('begin', 'getOrderByNo'));
        foreach($order['report_list'] as &$reportList){
            $standardRemark = '';
            $standardId = $reportList['standard_id']??0;
            if($standardId){
                $standardRemark = Db::table('subject_standard')->where('id', $standardId)->value('remark');
            }
            if(empty($standardRemark)){
                $standardRemark = '';
            }
            $reportList['standard_remark'] = $standardRemark;
        }
        Debug::remark('report_list');
        Log::notice("report debug: report_list cost: " . Debug::getRangeTime('getOrderByNo', 'report_list'));
        //内部自动登录不受支付状态影响
        if ($order['pay_status'] != Defs::PAY_SUCCESS) {
            //该订单尚未支付
            $this->redirect('mp/Subject/buy', ['order_no'=>$order_no]);
        }
        //没有当前微信用户只能查看自己的报告的限制
        $subject = Subjects::I()->getSubjectById($order['subject_id']);
        if(empty($subject)){
            return $this->fetch('common/missing', ['msg'=>'无法找到量表']);
        }
        if($subject['report_elements']){
            $subject['report_elements'] = explode(',', $subject['report_elements']);
        }else{
            $subject['report_elements'] = [];
        }
        //用户
        $user = UsersService::I()->getUserById($order['customer_id']);
        //don't care the $user value
        Debug::remark('getSubject');
        Log::notice("report debug: getSubject cost: " . Debug::getRangeTime('report_list', 'getSubject'));

        $this->assign('order',$order);
        $this->assign('subject',$subject);
        $this->assign('user', $user);
        
        $tpl_id = 'default';
        if($subject['report_template']){
            $tpl_id = $subject['report_template'];
        }
        $this->assign('source', $source);
        $this->assign('uuid', uniqid());
        $this->assign('internalView', $internalView);
        $this->assign('pdfUrl', url('mp/Subject/pdf', ['order_no'=>$order_no]));
        if($tpl_id == 'default'){
            return $this->fetch("subject/report/{$tpl_id}", ['theme'=>'lavender']);
        }else{
            return $this->fetch(ROOT_PATH . 'report' . DS  . 'view' . DS . $tpl_id . '.php',
                ['theme'=>'lavender']
            );
        }
    }
    public function reportGroup($cb_order_id=0, $survey_order_id=0){
        $conditions = [];
        $orderTime = '';
        $personalDataItems = [];
        if($cb_order_id){
            //组合测评
            $conditions['cb_order_id'] = $cb_order_id;
            $groupOrder = Db::table('combination_order')->alias('O')
                ->join('subject_combination C', 'O.combination_id=C.id')
                ->where('O.id', $cb_order_id)->field('C.name,C.banner,O.uid,O.ctime')->find();
            if($groupOrder === null){
                return $this->fetch('common/missing', ['msg'=>'无法找到该组合测评订单']);
            }
            $orderTime = $groupOrder['ctime'];
        }else if($survey_order_id){
            //普查
            $conditions['survey_order_id'] = $survey_order_id;
            $groupOrder = Db::table('survey_order')->alias('O')
                ->join('survey S', 'O.survey_id=S.id')
                ->where('O.id', $survey_order_id)
                ->field('S.name,S.banner,O.uid,O.ctime,O.personal_data')
                ->find();
            if($groupOrder === null){
                return $this->fetch('common/missing', ['msg'=>'无法找到该普查测评订单']);
            }
            $orderTime = $groupOrder['ctime'];
            if(!empty($groupOrder['personal_data'])){
                $personalData = json_decode($groupOrder['personal_data'], true);
                foreach($personalData as $key=>$value){
                    $title = IndexDefs::SURVEY_ENTER_PERSONAL_DATA_ITEMS[$key]??'';
                    if($key == 'address'){
                        $value = nl2br($value);
                    }
                    if($key == 'sex'){
                        $value = $value == 1?'男':'女';
                    }
                    if($key == 'organization'){
                        $value = SurveyOrganizationLogic::I()->loadFullText($value, '');
                    }
                    $personalDataItems[] = [
                        'title'=>$title,
                        'value'=>$value
                    ];
                }
            }
        }else{
            return $this->fetch('common/error', ['msg'=>'非法访问']);
        }


        $orderNos = Db::table('subject_order')->where($conditions)->column('order_no');
        if(empty($orderNos)){
            $orderNos = [];
        }
        //用户
        $user = UsersService::I()->getUserById($groupOrder['uid']);
        $this->assign('user', $user);
        $this->assign('groupOrder', $groupOrder);
        $this->assign('orderNos', $orderNos);
        $this->assign('orderTime', $orderTime);
        $this->assign('personalDataItems', $personalDataItems);
        return $this->fetch('subject/report/group');
    }
    public function pdf($order_no){
        $order = Subjects::I()->getOrderByNo($order_no);
        if(empty($order)){
            exit('无法找到订单');
        }
        if ($order['pay_status'] != Defs::PAY_SUCCESS) {
            exit('该订单尚未支付');
        }
        $subject = Subjects::I()->getSubjectById($order['subject_id']);
        if(empty($subject)){
            exit('无法找到量表');
        }
        //用户
        $user = UsersService::I()->getUserById($order['customer_id']);
        //don't care the $user value

        $options = new Options();
        $options->set('defaultFont', 'simsun');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $fileName = "{$subject['name']}_{$order['order_no']}_{$order['order_time']}";

        if($subject['report_elements']){
            $subject['report_elements'] = explode(',', $subject['report_elements']);
        }else{
            $subject['report_elements'] = [];
        }
        $this->assign('order',$order);
        $this->assign('subject',$subject);
        $this->assign('user', $user);
        
        $this->assign('uuid', uniqid());
        $content = $this->fetch(APP_PATH . 'mp' . DS  . 'view' . DS . 'subject/report/pdf.php');
        $dompdf->loadHtml($content);
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser
        $dompdf->stream($fileName);
    }
    public function getStandardJson($subject_id){
        $rows = Subjects::I()->getSubjectStandards($subject_id);
        return json($rows);
    }

    public function category($categoryId=0, $name='', $page=1, $rows=DEFAULT_PAGE_ROWS){
        if ($this->request->isGet()) {
            $categories = Subjects::I()->getCategories();
            $this->assign('categories', $categories);
            $this->assign('name', $name);
            $this->assign('category_url', url('mp/Subject/category'));
            $this->assign('pageTitle', '量表测评');
            return $this->fetch();
        }
        $map = [];
        $map['field'] = 'id, name, subtitle, current_price, items, participants_show, image_url';
        $map['where'] = ['type'=>Defs::SUBJECT_TYPE_PSYCHOLOGY];
        $map['search'] = ['category_id'=>$categoryId, 'name'=>$name];

        $result = Subjects::I()->getList($map, $page, $rows, true);
        $total = $result['total'];
        $totalReturn = ($page-1)*$rows + count($result['rows']);

        foreach($result['rows'] as &$row){
            $categoryNames = Db::table('subject_category_relate')->alias('SCR')
                ->join('categories C', 'SCR.category_id=C.id and SCR.subject_id='.$row['id'])
                ->column('C.name');
            $row['category_names'] = $categoryNames;
            $row['image_url'] = generateThumbnailUrl($row['image_url'], 300);
            $row['participants'] = formatTimes($row['participants_show']);
        }
        return json(['rows'=>$result['rows'], 'page_end'=>($totalReturn>=$total)]);
    }
}