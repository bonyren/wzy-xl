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
namespace app\cli\controller;
use think\Controller;
use think\Db;
use think\Log;
use app\Defs;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use app\index\logic\Defs as IndexDefs;

class Tools extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function hello(){
        echo "hello";
    }
    public function duplicateSubjectFromSaas($subjectId){
        //源量表id
        //$subjectId = 1;
        $saasDbInstance = Db::connect('db_saas');
        $subject = $saasDbInstance->table('subject')->where('id', $subjectId)->field('*')->find();
        if(empty($subject)){
            exception('无法找到量表');
        }
        //清洗数据
        unset($subject['id']);
        $subject['current_price'] = 0;
        $subject['sort'] = 1000;
        unset($subject['create_time']);
        unset($subject['modify_time']);
        unset($subject['saas_user_id']);
        $subject['status'] = IndexDefs::ENTITY_DRAFT_STATUS;//草稿
        $subject['label'] = '';
        $subject['participants'] = 0;
        $subject['participants_show'] = 0;
        $subject['total_amount'] = 0;
        $subject['delete_flag'] = 0;
        $subject['qrcode'] = '';
        $subject['uni_app'] = 0;

        $subjectIdNew = Db::table('subject')->insertGetId($subject);
        //附件图片
        if($subject['image_url']){
            $this->copyUploadFile($subject['image_url']);
        }
        if($subject['report_image1']){
            $this->copyUploadFile($subject['image_url']);
        }
        if($subject['report_image2']){
            $this->copyUploadFile($subject['image_url']);
        }
        if($subject['report_image3']){
            $this->copyUploadFile($subject['image_url']);
        }
        if($subject['report_image4']){
            $this->copyUploadFile($subject['image_url']);
        }
        if($subject['report_image5']){
            $this->copyUploadFile($subject['image_url']);
        }
        if($subject['report_image6']){
            $this->copyUploadFile($subject['image_url']);
        }
        if($subject['banner_img']){
            $this->copyUploadFile($subject['image_url']);
        }
        if($subject['video_url']){
            $this->copyUploadFile($subject['image_url']);
        }
        if($subject['audio_url']){
            $this->copyUploadFile($subject['image_url']);
        }
        if($subject['report_demo_images']){
            $images = explode(',', $subject['report_demo_images']);
            foreach($images as $image){
                $this->copyUploadFile($image);
            }
        }
        //题目
        $subjectItemsNew = [];
        $subjectItems = $saasDbInstance->table('subject_item')->where('subject_id', $subjectId)->field('*')->select();
        foreach($subjectItems as $subjectItem){
            $subjectItemId = $subjectItem['id'];
            unset($subjectItem['id']);
            $subjectItem['subject_id'] = $subjectIdNew;
            $subjectItemIdNew = Db::table('subject_item')->insertGetId($subjectItem);
            $subjectItem['id'] = $subjectItemIdNew;
            $subjectItemsNew[$subjectItemId] = $subjectItem;
            if($subjectItem['image']){
                $this->copyUploadFile($subjectItem['image']);
            }
            $i = 1;
            while($i <= 12){
                if($subjectItem['image_' . $i]){
                    $this->copyUploadFile($subjectItem['image_' . $i]);
                }
                $i++;
            }
        }
        //维度
        $subjectStandardsNew = [];
        $subjectStandards = $saasDbInstance->table('subject_standard')->where('subject_id', $subjectId)->field('*')->select();
        foreach($subjectStandards as $subjectStandard){
            $subjectStandardId = $subjectStandard['id'];
            unset($subjectStandard['id']);
            $subjectStandard['subject_id'] = $subjectIdNew;
            $subjectStandardIdNew = Db::table('subject_standard')->insertGetId($subjectStandard);
            $subjectStandard['id'] = $subjectStandardIdNew;
            $subjectStandardsNew[$subjectStandardId] = $subjectStandard;
        }
        //subject_standard_latitude
        //---整体---
        $subjectStandardLatitudes = $saasDbInstance->table('subject_standard_latitude')->where(['subject_id'=>$subjectId, 'standard_id'=>0])->field('*')->select();
        foreach($subjectStandardLatitudes as $subjectStandardLatitude){
            unset($subjectStandardLatitude['id']);
            $subjectStandardLatitude['subject_id'] = $subjectIdNew;
            $subjectStandardLatitude['standard_id'] = 0;
            Db::table('subject_standard_latitude')->insert($subjectStandardLatitude);
        }
        foreach($subjectStandardsNew as $subjectStandardId=>$subjectStandardNew){
            $subjectStandardLatitudes = $saasDbInstance->table('subject_standard_latitude')->where(['subject_id'=>$subjectId, 'standard_id'=>$subjectStandardId])->field('*')->select();
            foreach($subjectStandardLatitudes as $subjectStandardLatitude){
                unset($subjectStandardLatitude['id']);
                $subjectStandardLatitude['subject_id'] = $subjectIdNew;
                $subjectStandardLatitude['standard_id'] = $subjectStandardNew['id'];
                Db::table('subject_standard_latitude')->insert($subjectStandardLatitude);
            }
        }
        //subject_item_standard
        $subjectItemStandards = $saasDbInstance->table('subject_item_standard')->where('subject_id', $subjectId)->field('*')->select();
        foreach($subjectItemStandards as $subjectItemStandard){
            unset($subjectItemStandard['id']);
            $subjectItemStandard['subject_id'] = $subjectIdNew;
            $subjectItemStandard['item_id'] = $subjectItemsNew[$subjectItemStandard['item_id']]['id'];
            $subjectItemStandard['standard_id'] = $subjectStandardsNew[$subjectItemStandard['standard_id']]['id'];
            Db::table('subject_item_standard')->insert($subjectItemStandard);
        }
        //subject_category_relate
        $subjectCategories = $saasDbInstance->table('subject_category_relate')->where('subject_id', $subjectId)->field('*')->select();
        foreach($subjectCategories as $subjectCategory){
            $subjectCategory['subject_id'] = $subjectIdNew;
            Db::table('subject_category_relate')->insert($subjectCategory);
        }
        echo "done\n";
    }    
    /**
     * copyUploadFile
     *
     * @param  mixed $fileName /upload/20240928/c694a07927ea33a0bb8151e66b52f2c4.jpeg
     * @return void
     */
    protected function copyUploadFile($fileName){
        $saasUploadDir =  str_replace('wzyer-standalone', 'wzyer-saas', SITE_DIR);
        $uploadDir = SITE_DIR;
        if(!file_exists($saasUploadDir . $fileName)){
            //源文件不存在
            return;
        }
        if(file_exists($uploadDir . $fileName)){
            //目标文件已存在
            return;
        }
        //判断目标文件夹
        if(!file_exists(dirname($uploadDir . $fileName))){
            mkdir(dirname($uploadDir . $fileName));
        }
        copy($saasUploadDir . $fileName, $uploadDir . $fileName);
    }
    public function updateSubjectSn(){
        $page = 1;
        do{
            $rows = Db::table('subject')->order('id asc')->page($page++, 100)->field('id')->select();
            if(empty($rows)){
                break;
            }
            foreach($rows as $row){
                Db::table('subject')->where('id', $row['id'])->update(['sn'=>generateSubjectSn()]);
            }
        }while(true);
        echo "over" . PHP_EOL;
    }
}