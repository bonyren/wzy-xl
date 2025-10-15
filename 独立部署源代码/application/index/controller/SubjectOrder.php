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

use think\Db;
use think\Log;
use app\Defs;
use app\index\logic\Defs as IndexDefs;
use think\Request;
use app\mp\service\Subjects;
use app\mp\service\Users;
use app\index\logic\Subject as SubjectLogic;
use app\index\service\RequestContext;
use app\index\logic\SurveyOrganization as SurveyOrganizationLogic;
use Dompdf\Dompdf;
use Dompdf\Options;
use app\index\logic\SubjectOrder as SubjectOrderLogic;

class SubjectOrder extends Common{
    public function orders($search=[], $page=1, $rows=DEFAULT_PAGE_ROWS, $sort='', $order='',
        $customer_id=0,
        $subject_id=0,
        $combination_id=0, 
        $survey_id=0, 
        $survey_organization_id=0){
        if ($this->request->isGet()) {
            $urlHrefs = [];
            $urlHrefs['orders'] = url('index/SubjectOrder/orders', [
                'customer_id'=>$customer_id,
                'subject_id'=>$subject_id,
                'combination_id'=>$combination_id,
                'survey_id'=>$survey_id,
                'survey_organization_id'=>$survey_organization_id]);
            $urlHrefs['export'] = url('index/SubjectOrder/exportOrders', [
                'customer_id'=>$customer_id,
                'subject_id'=>$subject_id,
                'combination_id'=>$combination_id,
                'survey_id'=>$survey_id,
                'survey_organization_id'=>$survey_organization_id]);
            $this->assign('urlHrefs', $urlHrefs);
            $this->assign('combination_id', $combination_id);
            $this->assign('survey_id', $survey_id);
            return $this->fetch();
        }
        return json(SubjectOrderLogic::I()->loadOrders($search, $page, $rows, $sort, $order, $customer_id, $subject_id, $combination_id, $survey_id, $survey_organization_id));
    }
    public function exportOrders($search = [], 
        $customer_id=0,
        $subject_id=0,
        $combination_id=0, 
        $survey_id=0, 
        $survey_organization_id=0){

        $fileName = "测评订单导出_" . date('YmdHis');
        $head = [];
        if($combination_id){
            $head[] = '微信昵称';
            $head[] = '是否完成';
        }
        if($survey_id){
            $head[] = '个人资料';
            $head[] = '是否完成';
        }
        $head[] = '订单号';
        $head[] = '昵称';
        $head[] = '姓名';
        $head[] = '测评量表';
        if(!$combination_id && !$survey_id){
            $head[] = '普查/组合';
        }
        $head[] = '时间/耗时(分钟)';
        $head[] = '金额(元)';
        $head[] = '完成度';
        $head[] = '订单状态';
        $head[] = '支付状态';
        $head[] = '预警';
        // 输出Excel文件头
        header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.urlencode($fileName).'.csv"');
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'a'); // 打开PHP文件句柄，php://output 表示直接输出到浏览器
        fwrite($fp, chr(0XEF) . chr(0xBB) . chr(0XBF)); //输出BOM头
        fputcsv($fp, $head);
        $page = 1;
        $rowNum = 0;
        do{
            $result = SubjectOrderLogic::I()->loadOrders($search, $page++, 100, '', '', $customer_id, $subject_id, $combination_id, $survey_id, $survey_organization_id);
            if(empty($result['rows'])){
                break;
            }
            foreach($result['rows'] as $row){
                $record = [];
                if($combination_id){
                    $record[] = $row['nickname'];
                    $record[] = $row['combination_order_finished']?'是':'否';
                }
                if($survey_id){
                    $record[] = str_replace('<br/>', '-', $row['survey_personal_data']);
                    $record[] = $row['survey_order_finished']?'是':'否';
                }
                $record[] = $row['order_no'];
                $record[] = $row['nickname'];
                $record[] = $row['real_name'];
                $record[] = $row['subject_name'];
                if(!$combination_id && !$survey_id){
                    $record[] = $row['belongs_to'];
                }
                $record[] = strip_tags($row['time_cost']);
                $record[] = $row['order_amount'];
                $record[] = $row['completion'];

                $record[] = IndexDefs::$subjectOrderStatusDefs[$row['finished']]??'';
                $record[] = Defs::PAYS[$row['pay_status']]??'';
                $record[] = Defs::MEASURE_WARNINGS[$row['warning_level']]??'';
                fputcsv($fp, $record);
                $rowNum++;
                if($rowNum%1000 == 0){
                    ob_flush();
                    flush();
                }
            }
        }while(true);
        fclose($fp);
    }
    public function orderDetail($order_no){
        $order = Subjects::I()->getOrderByNo($order_no);
        if (empty($order)) {
            return $this->fetch('common/missing');
        }
        //量表
        $order['subject'] = Subjects::I()->getSubjectById($order['subject_id']);
        if(!$order['subject']){
            $order['subject'] = [];
        }
        //用户
        $order['user'] = Users::I()->getUserById($order['customer_id']);
        $order['items'] = Subjects::I()->parseOrderItems($order['items']);
        $items = Subjects::I()->getSubjectItems($order['subject_id']);
        
        $this->assign('order',$order);
        $this->assign('items',$items);
        return $this->fetch();
    }
    public function orderQuestionForm($order_no){
        $order = Subjects::I()->getOrderByNo($order_no);
        if (empty($order)) {
            return $this->fetch('common/missing');
        }
        $questionForm = '';
        if($order['question_form']){
            $questionFormItems = json_decode($order['question_form'], true);
            if($questionFormItems && is_array($questionFormItems)){
                foreach($questionFormItems as $questionFormItem){
                    $questionForm .= $questionFormItem['html'];
                }
            }
        }
        $questionAnswer = '[]';
        if($order['question_answer']){
            $questionAnswer = $order['question_answer'];
        }
        $this->assign('questionForm', $questionForm);
        $this->assign('questionAnswer', $questionAnswer);
        return $this->fetch();
    }
    public function orderReport($order_no){
        $order = Subjects::I()->getOrderByNo($order_no);
        if (empty($order)) {
            return $this->fetch('common/missing');
        }
        $this->assign('src', url('mp/Subject/report',array_merge(
                generateMpAutoLoginParams($order['customer_id']),
                [
                    'order_no'=>$order_no, 
                    'internalView'=>1
                ]
        )));
        return $this->fetch('common/iframe');
    }
    public function orderReportPdf($order_no){
        $order = Subjects::I()->getOrderByNo($order_no);
        if (empty($order)) {
            //新标签页下载，直接提示
            return $this->fetch('common/error', ['msg'=>"无法找到相关订单"]);
        }
        //量表
        $subject = Subjects::I()->getSubjectById($order['subject_id']);
        if(empty($subject)){
            //新标签页下载，直接提示
            return $this->fetch('common/error', ['msg'=>"无法找到相关量表"]);
        }
        //用户
        //$user = Users::I()->getUserById($order['customer_id']);
        if(empty($order['report_pdf'])){
            Request::instance()->get(generateMpAutoLoginParams($order['customer_id']));
            action('mp/Subject/pdf', [
                'order_no'=>$order_no,
            ]);
        }else{
            $pdfUrl = EXPORT_URL_ROOT . "report_pdf/" . $order['report_pdf'];
            $this->redirect($pdfUrl);
        }
    }
    public function view($order_no){
        $order = Subjects::I()->getOrderByNo($order_no);
        if (empty($order)) {
            return $this->fetch('common/missing');
        }
        $combinationId = 0;
        if($order['cb_order_id']){
            $combinationId = Db::table('combination_order')->alias('O')
                ->join('subject_combination C', 'O.combination_id=C.id')
                ->where('O.id', $order['cb_order_id'])->value('C.id');
            if(empty($combinationId)) $combinationId = 0;
        }
        $surveyId = 0;
        if($order['survey_order_id']){
            $surveyId = Db::table('survey_order')->alias('O')
                ->join('survey S', 'O.survey_id=S.id')
                ->where('O.id', $order['survey_order_id'])->value('S.id');
            if(empty($surveyId)) $surveyId = 0;
        }
        $this->assign('orderInfos', $order);
        $this->assign('urlHrefs', [
            'customer'=>url('index/Customer/view', ['customerId'=>$order['customer_id']]),
            'subject'=>url('index/Subject/view', ['id'=>$order['subject_id']]),
            'order'=>url('index/SubjectOrder/orderDetail', ['order_no'=>$order_no]),
            'orderQuestionForm'=>url('index/SubjectOrder/orderQuestionForm', ['order_no'=>$order_no]),
            'combination'=>url('index/SubjectCombination/view', ['id'=>$combinationId]),
            'survey'=>url('index/Survey/view', ['id'=>$surveyId])
        ]);
        return $this->fetch();
    }
}