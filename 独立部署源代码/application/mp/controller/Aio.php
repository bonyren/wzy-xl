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

use app\common\service\WException;
use think\Controller;
use think\Db;
use app\Defs;
use think\Request;
use think\Log;
use app\mp\service\Subjects;
use app\mp\service\Users;
use app\index\logic\Defs as IndexDefs;
use app\index\service\EventLogs as EventLogsService;
use app\index\logic\Subject as SubjectLogic;
use EasyWeChat\Factory;
use app\mp\logic\Aio as AioLogic;

class Aio extends Controller{
    protected $_token = '';
    protected $_aio_id = 0;
    protected function _initialize(){
        parent::_initialize();
        $controller = Request::instance()->controller();
		$action = Request::instance()->action();
        if(in_array($action, ['login', 'sniffer'])){
            return;
        }
        if($action == 'index' || $action == 'keepalive'){
            $this->_token = input('get.token');
        }else{
            $this->_token = session('token');
        }
        if(empty($this->_token)){
            if($this->request->isAjax()){
                abort(401, '未授权的访问-令牌为空');
            }else{
                exit($this->fetch('common/error', ['msg'=>'未授权的访问-令牌为空']));
            } 
        }
        $aio = Db::table('aio')->where(['access_token'=>$this->_token])
            ->field(true)
            ->find();
        if(empty($aio)){
            if($this->request->isAjax()){
                abort(401, '未授权的访问-令牌无效');
            }else{
                exit($this->fetch('common/error', ['msg'=>'未授权的访问-令牌无效']));
            }
        }
        if($action == 'index'){
            session('token', $this->_token);
        }
        $this->_aio_id = $aio['id'];
        if(request()->isGet()){
            $this->assign('_home_url', url('mp/Aio/index', ['token'=>$this->_token, 'salt'=>uniqid()]));
        }
    }
    /**
     * 一体机网络状态检查
     *
     * @return void
     */
    public function sniffer(){
        return 'success';
    }    
    /**
     * 一体机登录
     *
     * @param  mixed $uid
     * @param  mixed $computerName
     * @param  mixed $ver
     * @return void
     */
    public function login($uid="", $computerName="", $ver=""){
        if(empty($uid)){
            return ajaxError();
        }
        try{
            $accessToken = AioLogic::I()->login($uid, $computerName, $ver);
            return ajaxSuccess("操作成功", ['token'=>$accessToken]);
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }    
    /**
     * 一体机心跳
     *
     * @param  mixed $uid
     * @return void
     */
    public function keepAlive($uid){
        AioLogic::I()->keepAlive($uid);
        return ajaxSuccess();
    }

    public function index(){
        list($banners, $populars, $featureds) = AioLogic::I()->loadIndexSubjects();
        $this->assign([
            'banners' => $banners,
            'populars' => $populars,
            'featureds' => $featureds,
        ]);
        return $this->fetch();
    }
    public function category($categoryId=0, $name='', $page=1, $rows=DEFAULT_PAGE_ROWS){
        if ($this->request->isGet()) {
            $categories = Subjects::I()->getCategories();
            $this->assign('categories', $categories);
            $this->assign('name', $name);
            $this->assign('category_url', url('mp/Aio/category'));
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
    public function detail($id){
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
        //上次是否未完成
        $order = Db::table('aio_order')->where(['aio_id'=>$this->_aio_id, 'subject_id'=>$id])
            ->field(true)
            ->order('id desc')
            ->find();
        if($order && !$order['finished']){
            //继续测评
            $unfinished = $order;
        }else{
            //全新测评
            $unfinished = [];
        }
        $this->assign([
            'og_tag_type'=>'website',
            'og_tag_title'=>$subject['name'] . " - 介绍",
            'og_tag_url'=>request()->url(true),
            'og_tag_image'=>generateUploadFullUrl($subject['image_url']),
            'og_tag_description'=>generateShareDesc($subject['subject_desc'], 65),
            ////////////////////////////////////////////
            'subject' => $subject,
            'unfinished' => $unfinished,
            'gen_order_url'=> url('mp/Aio/genOrder', ['subject_id'=>$subject['id']]), 
            'test_url'=> url('mp/Aio/test')
        ]);
        return $this->fetch('dian/detail');
    }
    public function genOrder($subject_id){
        try {
            $orderNo = AioLogic::I()->genOrder($this->_aio_id, $subject_id);
            $data = [];
            $data['order_no'] = $orderNo;
            return ajaxSuccess('操作成功', $data);
        } catch (WException $e) {
            Log::error('订单生成失败：' . $e->getMessage());
            return ajaxError('订单生成失败：' . $e->getMessage());
        }
    }
    public function regenOrder($order_no){
        try{
            AioLogic::I()->regenOrder($order_no);
            return ajaxSuccess();
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function test($order_no, $skip_question=0){
        $order = Db::table('aio_order')->where(['order_no'=>$order_no])
            ->field(true)
            ->find();
        if(empty($order)){
            $this->error('测评订单不存在：'.$order_no);
        }
        if ($order['finished']) {
            $this->success('该测评订单已经完成，正跳转到报告', 
                url('mp/Aio/report', ['order_no'=>$order['order_no']]));
        }
        $subjectId = $order['subject_id'];
        $subject = Subjects::I()->getSubjectById($subjectId);
        if(empty($subject)){
            $this->error('该测评订单关联量表丢失：'.$order_no);
        }
        if(!$skip_question){
            if(!empty($order['question_form'])){
                $this->redirect('mp/Aio/question_form', ['order_no'=>$order_no]);
                return;
            }
        }
        $subjectItems = Subjects::I()->getSubjectItems($subjectId);
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
        $this->assign('test_url', url('mp/Aio/test'));
        $this->assign('answer_url', url('mp/Aio/answer'));
        $this->assign('regen_order_url', url('mp/Aio/regenOrder'));

        $this->assign([
            'og_tag_type'=>'website',
            'og_tag_title'=>$subject['name'] . " - 测评",
            'og_tag_url'=>request()->url(true),
            'og_tag_image'=>generateUploadFullUrl($subject['image_url']),
            'og_tag_description'=>generateShareDesc($subject['subject_desc'], 65)
        ]);
        return $this->fetch('dian/test');
    }
    public function question_form($order_no){
        if($this->request->isGet()){
            $order = Db::table('aio_order')->where(['order_no'=>$order_no])
                ->field(true)
                ->find();
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

            $this->assign('question_form_url', url('mp/Aio/question_form', ['order_no'=>$order_no]));
            $this->assign('test_url', url('mp/Aio/test', ['order_no'=>$order_no, 'skip_question'=>1]));
            $this->assign([
                'og_tag_type'=>'website',
                'og_tag_title'=>$subject['name'] . " - 调查",
                'og_tag_url'=>request()->url(true),
                'og_tag_image'=>generateUploadFullUrl($subject['image_url']),
                'og_tag_description'=>generateShareDesc($subject['subject_desc'], 65)
            ]);
            return $this->fetch('dian/question_form');
        }
        $questionAnswer = input('post.question_answer');
        Db::table('aio_order')->where(['order_no'=>$order_no])
            ->update([
                'question_answer'=>htmlspecialchars_decode($questionAnswer)
            ]);
        return ajaxSuccess();
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
    public function answer($order_no, $item_id, $item_type, $item_option=''){
        try{
            $redirectUrl = AioLogic::I()->answer($order_no, $item_id, $item_type, $item_option);
            if($redirectUrl){
                return ajaxSuccess('操作成功', $redirectUrl);
            }else{
                return ajaxSuccess();
            }
        }catch(WException $e){
            $exceptionCode = $e->getCode();
            if($exceptionCode == -1){
                //测评项目版本变更
                return ajaxError('测评项目版本变更', -1);
            }
            return ajaxError($e->getMessage());
        }
    }
    public function report($order_no){
        //Debug::remark('begin');
        $order = Db::table('aio_order')->where(['order_no'=>$order_no])
            ->field(true)
            ->find();
        if(empty($order)){
            return $this->fetch('common/missing', ['msg'=>'无法找到订单']);
        }
        if(!$order['finished']){
            //未测评完，继续
            $this->success('该订单未完成，请继续测评', 
                url('mp/Aio/test', ['order_no'=>$order['order_no']]));
        }
        if ($order && !empty($order['result'])) {
            $result = json_decode($order['result'], true);
            if($result && isset($result['reportList'])) {
                $order['report_list'] = $result['reportList'];
            }else{
                Log::error("failed to decode result for subject order: {$order_no}");
                $order['report_list'] = [];
            }
        }
        //Debug::remark('getOrderByNo');
        //Log::notice("report debug: getOrderByNo cost: " . Debug::getRangeTime('begin', 'getOrderByNo'));
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
        //Debug::remark('report_list');
        //Log::notice("report debug: report_list cost: " . Debug::getRangeTime('getOrderByNo', 'report_list'));
        $subject = Subjects::I()->getSubjectById($order['subject_id']);
        if(empty($subject)){
            return $this->fetch('common/missing', ['msg'=>'无法找到量表']);
        }
        if($subject['report_elements']){
            $subject['report_elements'] = explode(',', $subject['report_elements']);
        }else{
            $subject['report_elements'] = [];
        }
        //Debug::remark('getSubject');
        //Log::notice("report debug: getSubject cost: " . Debug::getRangeTime('report_list', 'getSubject'));

        $this->assign('order', $order);
        $this->assign('subject', $subject);
        
        $this->assign('uuid', uniqid());

        $this->assign([
            'og_tag_type'=>'website',
            'og_tag_title'=>$subject['name'] . " - 报告",
            'og_tag_url'=>request()->url(true),
            'og_tag_image'=>generateUploadFullUrl($subject['image_url']),
            'og_tag_description'=>generateShareDesc($subject['subject_desc'], 65)
        ]);
        $tpl_id = 'default';
        if($subject['report_template']){
            $tpl_id = $subject['report_template'];
        }
        if($tpl_id == 'default'){
            return $this->fetch("subject/report/{$tpl_id}", ['theme'=>'lavender', 'source'=>'dian']);
        }else{
            return $this->fetch(ROOT_PATH . 'report' . DS  . 'view' . DS . $tpl_id . '.php',
                ['theme'=>'lavender', 'source'=>'dian']
            );
        }
    }
    public function _empty(){
        if($this->request->isAjax()){
		    abort(404, '资源不存在或已经删除');
        }else{
            exit($this->fetch('common/error', ['msg'=>'资源不存在或已经删除。']));
        }
	}
    public function ucenter(){
        $map = [];
        $map['o.aio_id'] = $this->_aio_id;
        //未完成
        $map['o.finished'] = 0;
        $sort = 'o.order_time desc';
        $rows = Db::table('aio_order')->alias('o')
            ->join('subject s','s.id=o.subject_id')
            ->field('o.order_no,o.order_time,o.order_amount,o.finish_time,o.finished,
                s.id,s.name,s.current_price,s.participants_show as participants,s.image_url,s.subtitle,s.items')
            ->where($map)
            ->order($sort)
            ->select();
        foreach($rows as &$row){
            $row['image_url'] = generateThumbnailUrl($row['image_url'], 300);
        }
        $this->assign('rows',$rows);
        //已完成
        $map['o.finished'] = 1;
        $sort = 'o.finish_time desc';
        $rowsCompleted = Db::table('aio_order')->alias('o')
            ->join('subject s','s.id=o.subject_id')
            ->field('o.order_no,o.order_time,o.order_amount,o.finish_time,o.finished,
                s.id,s.name,s.current_price,s.participants_show as participants,s.image_url,s.subtitle,s.items')
            ->where($map)
            ->order($sort)
            ->select();
        foreach($rowsCompleted as &$rowCompleted){
            $rowCompleted['image_url'] = generateThumbnailUrl($rowCompleted['image_url'], 300);
        }
        $this->assign('rowsCompleted',$rowsCompleted);
        return $this->fetch();
    }
}