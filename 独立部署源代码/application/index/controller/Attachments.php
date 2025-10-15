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

use think\image\Exception;
use think\Log;
use think\Debug;
use think\Db;
use think\File;
use think\Image;
use app\index\service\EventLogs;
use app\Defs;
use app\index\logic\Defs as IndexDefs;
use app\index\logic\Attachments as AttachmentsLogic;
class Attachments extends Common
{
    const ATTACHES_UI_BIG_STYLE = 1;
    const ATTACHES_UI_LIGHT_STYLE = 2;
    const ATTACHES_UI_TABLE_STYLE = 3;
    const ATTACHES_UI_DATAGRID_STYLE = 4;
    const ATTACHES_UI_LINK_STYLE = 5;

    const EXTENSION_2_MIME_TYPE = [
        'jpg'=>'image/jpeg',
        'png'=>'image/png',
        'jpeg'=>'image/jpeg',
        'gif'=>'image/gif',
        'zip'=>'application/zip',
        'xls'=>'application/vnd.ms-excel',
        'xlsx'=>'application/octet-stream',
        'pdf'=>'application/pdf',
        'doc'=>'application/msword',
    ];

    public function attaches($attachment_id=0,
                             $attachmentType=0,
                             $externalId=0,
                             $externalId2=0,
                             $callback='',
                             $uiStyle=self::ATTACHES_UI_BIG_STYLE,
                             $prompt='',
                             $fit=0,
                             $replace=0,
                             $singleSelect=0)
    {
        if(request()->isGet()){
            $urlHrefs = [
                'attaches'=>url('Attachments/attaches', ['attachmentType'=>$attachmentType,'externalId'=>$externalId,'externalId2'=>$externalId2]),
                'uploadAttach'=>url('Upload/uploadAttach', ['attachmentType'=>$attachmentType,'externalId'=>$externalId,'externalId2'=>$externalId2,'replace'=>$replace]),
                'deleteAttaches'=>url('Attachments/deleteAttaches'),
                'deleteAttach'=>url('Attachments/deleteAttach'),
            ];
            $this->assign('urlHrefs', $urlHrefs);
            $attachmentsLogic = AttachmentsLogic::newObj();
            if ($attachment_id) {
                //优先级高
                $attaches = [];
                $attach = $attachmentsLogic->getAttachById($attachment_id);
                if($attach){
                    $attaches[] = $attach;
                }
            } else {
                $attaches = $attachmentsLogic->getAttaches($attachmentType, $externalId, $externalId2);
            }
            for($i=0,$count=count($attaches); $i<$count; $i++){
                $mimeType = $attaches[$i]['mime_type'];
                if($mimeType == 'image/jpeg' || $mimeType == 'image/png' || $mimeType == 'image/gif'){
                    $attaches[$i]['thumbnail_url'] = url('Attachments/thumbnailImage', ['attachmentId'=>$attaches[$i]['attachment_id']]);
                }else{
                    $attaches[$i]['thumbnail_url'] = SITE_URL . '/static/img/file.png';
                }
                $attaches[$i]['download_url'] = url('Attachments/downloadAttach', ['attachmentId'=>$attaches[$i]['attachment_id']]);
            }
            $bindValues = [
                'attachmentType'=>$attachmentType,
                'externalId'=>$externalId,
                'attaches'=>$attaches,
                'replace'=>$replace, //每次上传会覆盖上一次上传的文件
                'callback'=>$callback,
                'singleSelect'=>$singleSelect//每次只能选择一个文件
            ];
            $this->assign('bindValues', $bindValues);
            $this->assign('uniqid', generateUniqid());
            $this->assign('prompt', $prompt);
            $this->assign('fit', $fit);
            switch($uiStyle){
                case self::ATTACHES_UI_BIG_STYLE:
                    return $this->fetch();
                    break;
                case self::ATTACHES_UI_LIGHT_STYLE:
                    return $this->fetch('attaches_light');
                    break;
                case self::ATTACHES_UI_TABLE_STYLE:
                    return $this->fetch('attaches_table');
                    break;
                case self::ATTACHES_UI_DATAGRID_STYLE:
                    return $this->fetch('attaches_datagrid');
                    break;
                case self::ATTACHES_UI_LINK_STYLE:
                    return $this->fetch('attaches_link');
                    break;
                default:
                    return $this->fetch();
            }
        }
        /***************************************************************************************************************/
        $attachmentsLogic = AttachmentsLogic::newObj();
        $attaches = $attachmentsLogic->getAttaches($attachmentType, $externalId, $externalId2);
        for($i=0,$count=count($attaches); $i<$count; $i++){
            $mimeType = $attaches[$i]['mime_type'];
            if($mimeType == 'image/jpeg' || $mimeType == 'image/png' || $mimeType == 'image/gif'){
                $attaches[$i]['thumbnail_url'] = url('Attachments/thumbnailImage', ['attachmentId'=>$attaches[$i]['attachment_id']]);
            }else{
                $attaches[$i]['thumbnail_url'] = SITE_URL . '/static/img/file.png';
            }
            $attaches[$i]['download_url'] = url('Attachments/downloadAttach', ['attachmentId'=>$attaches[$i]['attachment_id']]);
        }
        return json($attaches);
    }

    public function viewAttaches($attachmentType, $externalId, $externalId2=0, $uiStyle=self::ATTACHES_UI_BIG_STYLE, $fit=0)
    {
        if(request()->isGet()){
            $urlHrefs = [
                'attaches'=>url('Attachments/attaches', ['attachmentType'=>$attachmentType, 'externalId'=>$externalId,'externalId2'=>$externalId2]),
            ];
            $this->assign('urlHrefs', $urlHrefs);
            $attachmentsLogic = AttachmentsLogic::newObj();
            $attaches = $attachmentsLogic->getAttaches($attachmentType, $externalId, $externalId2);
            for($i=0,$count=count($attaches); $i<$count; $i++){
                $mimeType = $attaches[$i]['mime_type'];
                if($mimeType == 'image/jpeg' || $mimeType == 'image/png' || $mimeType == 'image/gif'){
                    $attaches[$i]['thumbnail_url'] = url('Attachments/thumbnailImage', ['attachmentId'=>$attaches[$i]['attachment_id']]);
                }else{
                    $attaches[$i]['thumbnail_url'] = SITE_URL . '/static/img/file.png';
                }
                $attaches[$i]['download_url'] = url('Attachments/downloadAttach', ['attachmentId'=>$attaches[$i]['attachment_id']]);
            }
            $bindValues = [
                'attachmentType'=>$attachmentType,
                'externalId'=>$externalId,
                'externalId2'=>$externalId2,
                'attaches'=>$attaches
            ];
            $this->assign('bindValues', $bindValues);
            $this->assign('uniqid', generateUniqid());
            $this->assign('fit', $fit);
            switch($uiStyle){
                case self::ATTACHES_UI_BIG_STYLE:
                    return $this->fetch();
                    break;
                case self::ATTACHES_UI_LIGHT_STYLE:
                    return $this->fetch('view_attaches_light');
                    break;
                case self::ATTACHES_UI_TABLE_STYLE:
                    return $this->fetch('view_attaches_table');
                    break;
                case self::ATTACHES_UI_DATAGRID_STYLE:
                    return $this->fetch('view_attaches_datagrid');
                    break;
                case self::ATTACHES_UI_LINK_STYLE:
                    return $this->fetch('view_attaches_link');
                    break;
                default:
                    return $this->fetch();
            }
        }
        /***************************************************************************************************************/
        $attachmentsLogic = AttachmentsLogic::newObj();
        $attaches = $attachmentsLogic->getAttaches($attachmentType, $externalId, $externalId2);
        for($i=0,$count=count($attaches); $i<$count; $i++){
            $mimeType = $attaches[$i]['mime_type'];
            if($mimeType == 'image/jpeg' || $mimeType == 'image/png' || $mimeType == 'image/gif'){
                $attaches[$i]['thumbnail_url'] = url('Attachments/thumbnailImage', ['attachmentId'=>$attaches[$i]['attachment_id']]);
            }else{
                $attaches[$i]['thumbnail_url'] = SITE_URL . '/static/img/file.png';
            }
            $attaches[$i]['download_url'] = url('Attachments/downloadAttach', ['attachmentId'=>$attaches[$i]['attachment_id']]);
        }
        return json($attaches);
    }

    public function previewAttach($attachmentId, $newTab=0)
    {
        $attach = Db::table('attachments')->where('attachment_id', $attachmentId)->field('attachment_id,save_name,size')->find();
        if(!$attach){
            return $this->fetch('common/error', ['msg'=>'无法找到该附件']);
        }
        $ext = explode('.',$attach['save_name']);
        $ext = $ext[count($ext)-1];
        //we must convert the \ to / in url to compatible the windows system
        $url = convertUploadSaveName2FullUrl($attach['save_name']);
        $preview_url = $url;
        switch (strtolower($ext)) {
            case 'doc':
            case 'docx':
            case 'xls':
            case 'xlsx':
            case 'ppt':
            case 'pptx':
                $attach['preview_type'] = 'office';
                $preview_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$url;
                break;
            case 'pdf':
                $attach['preview_type'] = 'pdf';
                break;
            case 'jpg':
            case 'png':
            case 'gif':
            case 'jpeg':
                $attach['preview_type'] = 'image';
                break;
            default:
                $attach['preview_type'] = false;
                $attach['error_msg'] = "can't support the file type to preview, please download it";
        }
        if (false != $attach['preview_type']) {
            $filePath = convertUploadSaveName2DiskFullPath($attach['save_name']);
            if(!file_exists($filePath)){
                $attach['preview_type'] = false;
                $attach['error_msg'] = 'can not find the file';
            }else {
                $filesize = filesize(iconv("UTF-8", "gb2312", $filePath));
                if ($filesize > config('upload.allow_preview_max_size')) {
                    $attach['preview_type'] = false;
                    $attach['error_msg'] = 'the file size is too large, max allowed file size: ' . config('upload.allow_preview_max_size');
                }
            }
        }
        if ($newTab) {
            if (!$attach['preview_type']) {
                return $attach['error_msg'];
            }
            $this->redirect($preview_url);
        }

        $attach['preview_url'] = $preview_url;
        $this->assign($attach);
        return $this->fetch();
    }

    public function deleteAttach($attachmentId){
        $attachmentsLogic = AttachmentsLogic::newObj();
        $attachmentsLogic->deleteAttach($attachmentId);
        return ajaxSuccess();
    }
    public function deleteAttaches($attachmentIds){
        $attachmentsLogic = AttachmentsLogic::newObj();
        foreach($attachmentIds as $attachmentId){
            $attachmentsLogic->deleteAttach($attachmentId);
        }
        return ajaxSuccess();
    }
    public function downloadAttach($attachmentId){
        $attach = Db::table('attachments')->where('attachment_id', $attachmentId)->field('attachment_id,original_name,save_name,mime_type')->find();
        if(!$attach){
            return $this->fetch('common/error', ['msg'=>'无法找到该附件']);
        }
        $originalPath = convertUploadSaveName2DiskFullPath($attach['save_name']);
        if(!file_exists($originalPath)){
            return $this->fetch('common/error', ['msg'=>'附件文件不存在']);
        }
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/force-download');
        header('Content-Length: ' . filesize($originalPath));
        header("Content-Disposition: attachment; filename=" . $attach['original_name']);
        readfile($originalPath);
        exit();
    }
    public function thumbnailImage($attachmentId, $size=100){
        $imagePath = STATIC_DIR . DS . 'img/no_image.jpg';
        $imageType = 'image/jpeg';
        $attach = Db::table('attachments')->where('attachment_id', $attachmentId)->field('attachment_id,save_name,mime_type')->find();
        //check if the thumbnail image exist, otherwise create it.
        if($attach){
            $thumbnailPath = convertUploadSaveNameThumbnail2DiskFullPath($attach['save_name'], $size);
            if(file_exists($thumbnailPath)){
                $imagePath = $thumbnailPath;
                $imageType = $attach['mime_type'];
            }else{
                //create the thumbnail image
                $originalPath = convertUploadSaveName2DiskFullPath($attach['save_name']);
                if(file_exists($originalPath)){
                    try {
                        $image = Image::open($originalPath);
                        $image->thumb($size, $size);
                        $image->save($thumbnailPath);
                        $imagePath = $thumbnailPath;
                        $imageType = $attach['mime_type'];
                    }catch (Exception $e){
                        Log::error('exception occur when create thumbnail image for ' . $originalPath);
                    }
                }
            }
        }
        //output the image
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: ' . $imageType);
        header('Content-Length: ' . filesize($imagePath));
        echo file_get_contents($imagePath);
        exit();
    }    
    /**
     * thumbnailUploadImage
     *
     * @param  mixed $absoluteUrl e.g. /upload/20230208/f073ec418ed2e8714b97393051ffb78f.jpg
     * @param  mixed $size
     * @return void
     */
    // support /static/img/logo.png
    public function thumbnailUploadImage($absoluteUrl, $size=100){
        $thumbnailUrlRedirect = SITE_URL . '/static/img/no_image.jpg';

        $imagePath = STATIC_DIR . DS . 'img/no_image.jpg';
        $imageType = 'image/jpeg';
        $saveName = str_replace(SCRIPT_DIR . '/' . UPLOAD_FOLDER . '/', '', $absoluteUrl);
        $saveName = str_replace('/', DS, $saveName);
        //$saveName 20230208\f073ec418ed2e8714b97393051ffb78f.jpg
        $thumbnailPath = convertUploadSaveNameThumbnail2DiskFullPath($saveName, $size);
        if($absoluteUrl){
            if(file_exists($thumbnailPath)){
                $imagePath = $thumbnailPath;
                $imageType = self::EXTENSION_2_MIME_TYPE[strtolower(pathinfo($saveName)['extension'])];
                //重定向文件
                $thumbnailUrlRedirect = str_replace(SITE_DIR, '', $thumbnailPath);
                $thumbnailUrlRedirect = SCRIPT_DIR . str_replace(DS, '/', $thumbnailUrlRedirect);
            }else{
                //create the thumbnail image
                $originalPath = convertUploadAbsoluteUrl2DiskFullPath($absoluteUrl);
                if(file_exists($originalPath)){
                    try {
                        $image = Image::open($originalPath);
                        $width = $image->width();
                        $height = $image->height();
                        if(max($width, $height) <=  $size){
                            //小尺寸，直接使用原始文件
                            $this->redirect($absoluteUrl);
                            return;
                        }
                        $image->thumb($size, $size);
                        $image->save($thumbnailPath);
                        $imagePath = $thumbnailPath;
                        $imageType = self::EXTENSION_2_MIME_TYPE[strtolower(pathinfo($saveName)['extension'])];

                        //重定向文件
                        $thumbnailUrlRedirect = str_replace(SITE_DIR, '', $thumbnailPath);
                        $thumbnailUrlRedirect = SCRIPT_DIR . str_replace(DS, '/', $thumbnailUrlRedirect);
                    }catch (Exception $e){
                        Log::error('exception occur when create thumbnail image for ' . $originalPath);
                    }
                }
            }
        }
        //output the image
        $this->redirect($thumbnailUrlRedirect);
        /*
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: ' . $imageType);
        header('Content-Length: ' . filesize($imagePath));
        echo file_get_contents($imagePath);
        exit();*/
    }
    /*******************************************************************************************************************/
}