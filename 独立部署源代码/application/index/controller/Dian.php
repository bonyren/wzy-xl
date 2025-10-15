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
use app\Defs;

class Dian extends Common{
    public function goods($search=[],
        $page=1,
        $rows=DEFAULT_PAGE_ROWS,
        $sort='',
        $order=''){
        if(request()->isGet()){
            $this->assign('urlHrefs', [
                'goods'=>url('index/Dian/goods'),
                'export'=>url('index/Dian/goodsExport'),
                'delete'=>url('index/Dian/goodsDelete'),
                'report'=>url('index/Dian/goodsReport'),
                'add'=>url('index/Dian/goodsAdd')
            ]);
            return $this->fetch();
        }
        $conditions = [];
        if(!empty($search['subject_id'])){
            $conditions['G.subject_id'] = $search['subject_id'];
        }
        if(!empty($search['status'])){
            $conditions['G.status'] = $search['status'];
        }
        $order = 'G.id desc';

        $total = Db::table('dian_goods')->alias('G')
            ->join('subject S', 'G.subject_id=S.id')
            ->where($conditions)
            ->count();
        $records = Db::table('dian_goods')->alias('G')
            ->join('subject S', 'G.subject_id=S.id')
            ->join('dian_order O', 'G.id=O.goods_id', 'LEFT')
            ->where($conditions)
            ->page($page, $rows)
            ->order($order)
            ->field('G.*, S.name as subject_name, O.finished')
            ->select();
        return json([
            'total'=>$total,
            'rows'=>$records
        ]);
    }
    public function goodsExport($search = []){
        if (empty($search['subject_id'])) {
            return '请选择评估量表';
        }
        $subjectName = Db::table('subject')->where('id', $search['subject_id'])->value('name');
        if($subjectName === null){
            return '无法找到对应量表';
        }
        $conditions = [];
        if(!empty($search['subject_id'])){
            $conditions['G.subject_id'] = $search['subject_id'];
        }
        if(!empty($search['status'])){
            $conditions['G.status'] = $search['status'];
        }
        $order = 'G.id desc';

        $fileName = $subjectName . " - 测评商品链接";
        // 输出Excel文件头，可把user.csv换成你要的文件名
        header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$fileName.'.csv"');
        header('Cache-Control: max-age=0');
        // 打开PHP文件句柄，php://output 表示直接输出到浏览器
        $fp = fopen('php://output', 'w');
        //输出BOM头
        fwrite($fp, chr(0XEF) . chr(0xBB) . chr(0XBF));
        $head = array('量表名称','商品链接','状态','创建时间');
        // 将数据通过fputcsv写到文件句柄
        fputcsv($fp, $head);
        $exportRows = (function() use ($conditions, $order) {
            $page = 1;
            $rows = 1000;
            do{
                $records = Db::table('dian_goods')->alias('G')
                    ->join('subject S', 'G.subject_id=S.id')
                    ->where($conditions)
                    ->page($page++, $rows)
                    ->order($order)
                    ->field('G.*, S.name as subject_name')
                    ->select();
                if(empty($records)){
                    break;
                }
                foreach($records as $record){
                    $exportRow = [];
                    //量表名称
                    $exportRow[] = $record['subject_name'];
                    //商品链接
                    $exportRow[] = $record['url'];
                    //状态
                    $exportRow[] = Defs::$eGoodsStatusDefs[$record['status']]??'';
                    //创建时间
                    $exportRow[] = $record['entered'];
                    yield $exportRow;
                }
            }while(true);
        })();
        foreach($exportRows as $index=>$exportRow){
            fputcsv($fp, $exportRow);
            if($index%500 == 0){
                ob_flush();
                flush();
            }
        }    
        fclose($fp);
    }
    public function goodsDelete($id){
        Db::table('dian_goods')->where('id', $id)->delete();
        Db::table('dian_order')->where('goods_id', $id)->delete();
        return ajaxSuccess();
    }
    public function goodsReport($id){
        $order = Db::table('dian_goods')->alias('G')->join('dian_order O', 'G.id=O.goods_id')
            ->where(['G.id'=>$id])
            ->field('G.token, O.order_no')
            ->find();
        if(empty($order)){
            return $this->fetch('common/missing');
        }
        $src = url('mp/Dian/report', ['token'=>$order['token'], 'order_no'=>$order['order_no']], true, true);
        $this->assign('src', $src);
        return $this->fetch('common/iframe');
    }
    public function goodsAdd(){
        if(request()->isGet()){
            return $this->fetch();
        }
        $formData = input('post.');
        $subjectId = $formData['subject_id'];
        $quantity = $formData['quantity'];
        $urls = [];
        $i = 0;
        while($i++ < $quantity){
            $token = $this->genGoodsToken($subjectId);
            $url = SITE_URL . '/mp/Dian/detail?token=' . $token;
        
            Db::table('dian_goods')->insert([
                'subject_id'=>$subjectId,
                'token'=>$token,
                'url'=>$url
            ]);
            $urls[] = $url;
        }
        return ajaxSuccess('操作成功', implode("\r\n", $urls));
    }
    protected function genGoodsToken($subjectId){
        return md5($subjectId . generateUniqid());
    }
}
