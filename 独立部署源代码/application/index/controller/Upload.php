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
use app\index\logic\Defs as IndexDefs;
use think\image\Exception;
use think\Log;
use think\Debug;
use think\Db;
use think\File;
use think\Image;
use app\index\service\EventLogs;
use app\Defs;
use app\index\logic\Attachments as AttachmentsLogic;
class Upload extends Common{
    /**
     * 单文件上传
     */
    public function upload($ext=[]){
        $file = request()->file('upload');
        if($file == null){
            //if the uploading file size exceeded the allowed size, the $file is null
            uploadError('failed to get the upload file');
        }
        $uploadInfo = $file->getInfo();
        $originalName = $uploadInfo['name'];
        $originalType = $uploadInfo['type'];
        $originalSize = $uploadInfo['size'];
        $originalTmpName = $uploadInfo['tmp_name'];
        $originalError = $uploadInfo['error'];
        if($originalError != UPLOAD_ERR_OK){
            $errorDesc = "";
            if($originalError == UPLOAD_ERR_NO_FILE){
                $errorDesc = 'No file sent.';
            }else if($originalError == UPLOAD_ERR_INI_SIZE || $originalError == UPLOAD_ERR_FORM_SIZE){
                $errorDesc = 'Exceeded filesize limit ' . config('upload.size') . '.';
            }else{
                $errorDesc = 'Unknown errors.';
            }
            uploadError('failed to upload file, cause: ' . $errorDesc);
        }
        $rules = array_merge(config('upload'), $ext);
        $fileInfo = $file->validate($rules)->move(UPLOAD_DIR);
        if($fileInfo == null){
            uploadError('失败 - ' . $file->getError());
        }
        $saveName = $fileInfo->getSaveName();
        $url = convertUploadSaveName2FullUrl($saveName);
        $relativeUrl = convertUploadSaveName2RelativeUrl($saveName);
        $absoluteUrl = convertUploadSaveName2AbsoluteUrl($saveName);
        uploadSuccess('success', [
            'original_name'=>$originalName,
            'save_name'=>$saveName,
            'url'=>$url,
            'relative_url'=>$relativeUrl,
            'absolute_url'=>$absoluteUrl
        ]);
    }

    /**
     * 单图片文件上传
     */
    public function uploadImage(){
        $this->upload(['ext'=>'jpg,png,gif,jpeg']);
    }
    /**
     * 单视频文件上传
     */
    public function uploadVideo(){
        $this->upload(['ext'=>'webm,mp4,ogg,ogv']);
    }
    /**
     * 单音频文件上传
     */
    public function uploadAudio(){
        $this->upload(['ext'=>'mp3,mp4,ogg']);
    }

    /**
     * 数据导入上传
     */
    public function uploadImport(){
        $file = request()->file('upload');
        if($file == null){
            //if the uploading file size exceeded the allowed size, the $file is null
            uploadError('failed to get the upload file');
        }
        $uploadInfo = $file->getInfo();
        $originalName = $uploadInfo['name'];
        $originalType = $uploadInfo['type'];
        $originalSize = $uploadInfo['size'];
        $originalTmpName = $uploadInfo['tmp_name'];
        $originalError = $uploadInfo['error'];
        if($originalError != UPLOAD_ERR_OK){
            $errorDesc = "";
            if($originalError == UPLOAD_ERR_NO_FILE){
                $errorDesc = 'No file sent.';
            }else if($originalError == UPLOAD_ERR_INI_SIZE || $originalError == UPLOAD_ERR_FORM_SIZE){
                $errorDesc = 'Exceeded filesize limit ' . config('upload.size') . '.';
            }else{
                $errorDesc = 'Unknown errors.';
            }
            uploadError('failed to upload file, cause: ' . $errorDesc);
        }
        $rules = array_merge(config('upload'), ['ext'=>'csv,xls,xlsx,data']);
        $fileInfo = $file->validate($rules)->move(IMPORT_DIR);
        if($fileInfo == null){
            uploadError('失败 - ' . $file->getError());
        }
        $saveName = $fileInfo->getSaveName();
        $url = convertUploadSaveName2FullUrl($saveName);
        $relativeUrl = convertUploadSaveName2RelativeUrl($saveName);
        $absoluteUrl = convertUploadSaveName2AbsoluteUrl($saveName);
        uploadSuccess('success', [
            'original_name'=>$originalName,
            'save_name'=>$saveName,
            'url'=>$url,
            'relative_url'=>$relativeUrl,
            'absolute_url'=>$absoluteUrl
        ]);
    }
    public function uploadImageFromCKEditor($CKEditorFuncNum){
        //$CKEditorFuncNum = input('get.CKEditorFuncNum');
        $file = request()->file('upload');
        if($file == null){
            //if the uploading file size exceeded the allowed size, the $file is null
            echo "<script type=\"text/javascript\">";
            echo "window.parent.CKEDITOR.tools.callFunction(" . $CKEditorFuncNum . ",''," . "'failed to receive the file');";
            echo "</script>";
            exit();
        }
        $fileName = $file->getFilename();
        $fileMime = $file->getMime();
        if($fileMime != "image/pjpeg" &&
            $fileMime != "image/jpeg" &&
            $fileMime != "image/png" &&
            $fileMime != "image/x-png" &&
            $fileMime != "image/gif" &&
            $fileMime != "image/bmp"){
            echo "<script type=\"text/javascript\">";
            echo "window.parent.CKEDITOR.tools.callFunction(" . $CKEditorFuncNum . ",''," . "'File format is incorrect（must be .jpg/.gif/.bmp/.png file）');";
            echo "</script>";
            exit();
        }
        $fileSize = $file->getSize();
        if($fileSize > 2 * 1024 * 1024){
            echo "<script type=\"text/javascript\">";
            echo "window.parent.CKEDITOR.tools.callFunction(" . $CKEditorFuncNum . ",''," . "'File size must not be greater than 2M');";
            echo "</script>";
            exit();
        }
        $uploadInfo = $file->getInfo();
        $originalName = $uploadInfo['name'];
        /******************************************************************************/
        $fileInfo = $file->move(UPLOAD_DIR);
        if($fileInfo == null){
            echo "<script type=\"text/javascript\">";
            echo "window.parent.CKEDITOR.tools.callFunction(" . $CKEditorFuncNum . ",''," . "'failed to receive the file');";
            echo "</script>";
            exit();
        }
        $saveName = $fileInfo->getSaveName();
        $url = convertUploadSaveName2AbsoluteUrl($saveName);//use the relative path to site root
        echo "<script type=\"text/javascript\">";
        echo "window.parent.CKEDITOR.tools.callFunction(" . $CKEditorFuncNum . ",'" . $url . "','');";
        echo "</script>";
        exit();
    }

    /**多文件附件上传
     * @param $attachmentType
     * @param $externalId
     * @param int $externalId2
     * @param int $replace
     * @param int $pid
     */
    public function uploadAttach($attachmentType,
                                 $externalId,
                                 $externalId2=0,
                                 $replace=0,
                                 $pid=0){
        $multi_files = request()->file('upload');
        if($multi_files == null){
            uploadError('failed to get the upload file');
        }
        $rules = config('upload');
        $uploaded = [];
        $failed = [];
        $attachmentsLogic = AttachmentsLogic::newObj();
        if($replace){
            //删除历史的
            $attachmentsLogic->deleteAttaches($externalId, $attachmentType);
        }
        foreach ($multi_files as $idx=>$file) {
            Log::notice('uploadAttach, file info: ' . var_export($file->getInfo(), true));
            $uploadInfo = $file->getInfo();
            $originalName = $uploadInfo['name'];
            Log::notice('uploadAttach, original file name: ' . $originalName);
            /**************************************************************************/
            $fileInfo = $file->validate($rules)->move(UPLOAD_DIR);
            if($fileInfo == null){
                $failed[] = $uploadInfo['name'] . ': ' . $fileInfo->getError();
            }
            //20160820/42a79759f284b767dfcb2a0197904287.jpg
            //20160820\42a79759f284b767dfcb2a0197904287.jpg under windows
            $saveName = $fileInfo->getSaveName();
            //jpg
            $extensionName = $fileInfo->getExtension();
            //42a79759f284b767dfcb2a0197904287.jpg
            $fileName = $fileInfo->getFilename();
            $fileMime = $fileInfo->getMime();
            $fileSize = $fileInfo->getSize();
            Log::notice("uploadAttach, orginalName: {$originalName}, saveName: {$saveName}, fileName: {$fileName}, fileMine: {$fileMime}, filesize: {$fileSize}");
            //the absolute file path
            $filePath = convertUploadSaveName2DiskFullPath($fileInfo->getSaveName());
            $attachmentId = $attachmentsLogic->insertAttach($originalName,
                $saveName,
                $fileMime,
                $fileSize,
                '',//description
                $attachmentType,
                $externalId,
                $externalId2,
                $pid
            );
            if($attachmentId){
                if($fileMime == 'image/jpeg' || $fileMime == 'image/png' || $fileMime == 'image/gif'){
                    $thumbnail = url('index/Upload/thumbnailImage', ['attachmentId'=>$attachmentId]);
                }else{
                    $thumbnail = SITE_URL . '/static/img/file.png';
                }
                $uploaded[] = [
                    'attachment_id'=>$attachmentId,
                    'attachment_type'=>$attachmentType,
                    'name'=>$originalName,
                    'size'=>round($fileSize/1024,2),
                    'href_url'=>convertUploadSaveName2FullUrl($saveName),
                    'thumbnail_url'=>$thumbnail,
                    'entered' => date('Y-m-d H:i'),
                    'download_url'=>url('index/Attachments/downloadAttach', ['attachmentId'=>$attachmentId])
                ];
            }
        }
        if (empty($uploaded)) {
            uploadError('fail');
        }
        $html = '';
        if ($failed) {
            $html .= '上传成功'.count($uploaded).'个，'.count($failed).'个失败：<br>';
            $html .= join('<br>',$failed);
        }
        uploadSuccess('success', $uploaded, $html);
    }
}
