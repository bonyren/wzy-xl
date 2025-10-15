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
use think\Controller;
use think\Db;
use think\Env;
use think\Session;
use think\Log;
use think\Cookie;
use app\Defs;
use app\mp\service\RequestContext;
use app\common\service\Redis as RedisService;
use app\common\service\WException;
use app\mp\service\Subjects;
use app\index\logic\Defs as IndexDefs;
use app\index\logic\Subject as SubjectLogic;
use think\Debug;
use app\mp\logic\Dian as DianLogic;

class Dian extends Controller{
    protected $_token = '';
    protected $_goods_id = 0;
    protected $_subject_id = 0;
    protected function _initialize(){
        parent::_initialize();
        $token = input('get.token');
        if(empty($token)){
            if($this->request->isAjax()){
                abort(400, '错误的请求');
            }else{
                exit($this->fetch('common/error', ['msg'=>'错误的请求']));
            } 
        }
        $goods = Db::table('dian_goods')->where(['token'=>$token])
            ->field(true)
            ->find();
        if(empty($goods)){
            if($this->request->isAjax()){
                abort(400, '错误的请求');
            }else{
                exit($this->fetch('common/error', ['msg'=>'商品不存在或已经删除。']));
            }
        }
        $this->_token = $token;
        $this->_goods_id = $goods['id'];
        $this->_subject_id = $goods['subject_id'];
        $this->assign('_home_url', url('mp/Dian/detail', ['token'=>$this->_token, 'salt'=>uniqid()]));
    }
    public function detail(){
        $subject = Subjects::I()->getSubjectById($this->_subject_id);
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
        $order = Db::table('dian_order')->where(['goods_id'=>$this->_goods_id])
            ->field(true)
            ->find();
        if($order && $order['finished']){
            //跳转报告
            $this->success('该测评订单已经完成，正跳转到报告', 
                url('mp/Dian/report', ['token'=>$this->_token, 'order_no'=>$order['order_no']]));
        }else if($order && !$order['finished']){
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
            'gen_order_url'=> url('mp/Dian/genOrder', ['token'=>$this->_token]), 
            'test_url'=> url('mp/Dian/test', ['token'=>$this->_token])
        ]);
        return $this->fetch();
    }
    public function genOrder(){
        try{
            $orderNo = DianLogic::I()->genOrder($this->_goods_id, $this->_subject_id);
            $data = [];
            $data['order_no'] = $orderNo;
            return ajaxSuccess('操作成功', $data);
        }catch(WException $e){
            Log::error('订单生成失败：' . $e->getMessage());
            return ajaxError('订单生成失败：' . $e->getMessage());
        }
    }
    public function regenOrder($order_no){
        try{
            DianLogic::I()->regenOrder($this->_subject_id, $order_no);
            return ajaxSuccess();
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function test($order_no, $skip_question=0){
        $order = Db::table('dian_order')->where(['order_no'=>$order_no])
            ->field(true)
            ->find();
        if(empty($order)){
            $this->error('测评订单不存在：'.$order_no);
        }
        if ($order['finished']) {
            $this->success('该测评订单已经完成，正跳转到报告', 
                url('mp/Dian/report', ['token'=>$this->_token, 'order_no'=>$order['order_no']]));
        }
        $subject = Subjects::I()->getSubjectById($this->_subject_id);
        if(empty($subject)){
            $this->error('该测评订单关联量表丢失：'.$order_no);
        }
        if(!$skip_question){
            if(!empty($order['question_form'])){
                $this->redirect('mp/Dian/question_form', ['token'=>$this->_token, 'order_no'=>$order_no]);
                return;
            }
        }
        $subjectItems = Subjects::I()->getSubjectItems($this->_subject_id);
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
        $this->assign('test_url', url('mp/Dian/test', ['token'=>$this->_token]));
        $this->assign('answer_url', url('mp/Dian/answer', ['token'=>$this->_token]));
        $this->assign('regen_order_url', url('mp/Dian/regenOrder', ['token'=>$this->_token]));

        $this->assign([
            'og_tag_type'=>'website',
            'og_tag_title'=>$subject['name'] . " - 测评",
            'og_tag_url'=>request()->url(true),
            'og_tag_image'=>generateUploadFullUrl($subject['image_url']),
            'og_tag_description'=>generateShareDesc($subject['subject_desc'], 65)
        ]);
        return $this->fetch();
    }
    public function question_form($order_no){
        if($this->request->isGet()){
            $order = Db::table('dian_order')->where(['order_no'=>$order_no])
                ->field(true)
                ->find();
            if(empty($order)){
                $this->error('测评订单不存在：'.$order_no);
            }
            $subject = Subjects::I()->getSubjectById($this->_subject_id);
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

            $this->assign('question_form_url', url('mp/Dian/question_form', ['token'=>$this->_token, 'order_no'=>$order_no]));
            $this->assign('test_url', url('mp/Dian/test', ['token'=>$this->_token, 'order_no'=>$order_no, 'skip_question'=>1]));
            $this->assign([
                'og_tag_type'=>'website',
                'og_tag_title'=>$subject['name'] . " - 调查",
                'og_tag_url'=>request()->url(true),
                'og_tag_image'=>generateUploadFullUrl($subject['image_url']),
                'og_tag_description'=>generateShareDesc($subject['subject_desc'], 65)
            ]);
            return $this->fetch();
        }
        $questionAnswer = input('post.question_answer');
        Db::table('dian_order')->where(['order_no'=>$order_no])
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
            $order = DianLogic::I()->answer($this->_subject_id, $order_no, $item_id, $item_type, $item_option);
            if($order['finished']){
                $redirectUrl = url('mp/Dian/report',['token'=>$this->_token, 'order_no'=>$order_no]);
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
        $order = Db::table('dian_order')->where(['order_no'=>$order_no])
            ->field(true)
            ->find();
        if(empty($order)){
            return $this->fetch('common/missing', ['msg'=>'无法找到订单']);
        }
        if(!$order['finished']){
            //未测评完，继续
            $this->success('该订单未完成，请继续测评', 
                url('mp/Dian/test', ['token'=>$this->_token, 'order_no'=>$order['order_no']]));
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
        $subject = Subjects::I()->getSubjectById($this->_subject_id);
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
        
        $storeName = Db::table('studio')->where('key', 'store_name')->value('value');
        $storeName = $storeName??'';
        $this->assign('_studio', ['store_name'=>$storeName]);

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
}