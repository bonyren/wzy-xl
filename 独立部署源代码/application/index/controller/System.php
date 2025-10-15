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
use think\Db;
use think\Log;
use think\Debug;
use app\index\model\Setting;
use PHPMailer\PHPMailer\PHPMailer;
use app\common\service\Storage as StorageService;
use app\index\service\Wzyer as WzyerService;
use app\common\service\WException;
use app\index\logic\License as LicenseLogic;
use app\index\logic\Subject as SubjectLogic;
class System extends Common{
    public function setting(){
        if(request()->isPost()){
            $settingModel = model('Setting');
            if(input('get.dosubmit')){
                //保存
                $state = $settingModel->saveSetting(input('post.data/a'));
                if($state){
                    $settingModel->clearCache();
                    return ajaxSuccess();
                }else{
                    return ajaxError();
                }
            }else{
                $data = array_values($settingModel->getSetting());
                return json($data);
            }
        }else {
            /*
            if(!$this->loginSuperUser){
                return $this->fetch('common/tip', ['msg'=>'超级管理员才能进行此操作']);
            }*/
            $urlHrefs = [
                'setting'=>url('index/System/setting'),
                'settingSave'=>url('index/System/setting', ['dosubmit'=>1]),
                'settingDefault'=>url('index/System/settingDefault'),
                'settingExport'=>url('index/System/settingExport'),
                'settingImport'=>url('index/System/settingImport'),
                'importUpload'=>url('index/Upload/uploadImport'),
                'fileUpload'=>url('index/Upload/upload'),
                'settingTestMailbox'=>url('index/System/settingTestMailbox')
            ];
            $this->assign('urlHrefs', $urlHrefs);
            return $this->fetch();
        }
    }
    public function settingDefault(){
        if(request()->isPost()){
            $settingModel = model('Setting');
            if($settingModel->count()){
                $state = $settingModel->where("`key` <> ''")->delete();
                if($state){
                    $settingModel->clearCache();
                    return ajaxSuccess();
                }else{
                    return ajaxError();
                }
            }
            return ajaxSuccess();
        }
    }
    public function settingExport($filename = ''){
        if(request()->isPost()) {
            $settingModel = model('Setting');
            $data = array('type'=>'setting');
            $data['data'] = Db::table('setting')->select();
            $data['verify'] = md5(var_export($data['data'], true) . $data['type']);
            //数据进行多次加密，防止数据泄露
            $data = base64_encode(gzdeflate(json_encode($data)));
            $uniqid = uniqid();
            $filename = EXPORT_DIR . DS . $uniqid . '.data';
            if(file_put_contents($filename, $data)){
                return ajaxSuccess('操作成功', url('index/System/settingExport', array('filename'=>$uniqid)));
            }
            return ajaxError();
        }else{
            //过滤特殊字符，防止非法下载文件
            $filename = str_replace(array('.', '/', '\\'), '', $filename);
            $filename = EXPORT_DIR . DS . $filename . '.data';
            if(!file_exists($filename)) {
                return $this->fetch('common/error', ['msg'=>'无法找到该设置文件']);
            }
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename="system_setting.data"');
            readfile($filename);
            unlink($filename);
            exit();
        }
    }
    public function settingImport($filename = ''){
        if(request()->isPost()) {
            //过滤特殊字符，防止非法下载文件
            $filePath = IMPORT_DIR . DS . $filename;
            if(!file_exists($filePath)){
                Log::error("file: {$filePath} is not exist");
                return ajaxError();
            }
            $content = file_get_contents($filePath);
            //解密
            try {
                $data  = gzinflate(base64_decode($content));
            }catch (\Exception $e){
                Log::error("file: {$filePath}, failed to gzinflate it");
                unlink($filePath);
                return ajaxError();
            };
            if(!isset($data)){
                Log::error("file: {$filePath}, failed to decrypt it");
                unlink($filePath);
                return ajaxError();
            }
            //防止非法数据
            try {
                $data = json_decode($data, true);
            }catch (\Exception $e){};
            if(!is_array($data) || !isset($data['type']) || $data['type'] != 'setting' || !isset($data['verify']) || !isset($data['data'])){
                unlink($filePath);
                Log::error("file: {$filePath}, failed to decode it");
                return ajaxError();
            }
            if($data['verify'] != md5(var_export($data['data'], true) . $data['type'])){
                unlink($filePath);
                Log::error("settingImport, file: {$filePath}, failed to verify it, verify: {$data['verify']}");
                return ajaxError();
            }
            $settingModel = model('Setting');
            //先清空数据再导入
            $settingModel->where("`key` <> ''")->delete();
            $settingModel->clearCache();
            //开始导入
            asort($data['data']);
            foreach($data['data'] as $add) {
                $settingModel->key = $add['key'];
                $settingModel->value = $add['value'];
                $settingModel->isUpdate(false)->save();
            }
            unlink($filePath);
            return ajaxSuccess();
        }else{
            return ajaxError();
        }
    }
    public function settingTestMailbox(){
        $smtpHost = systemSetting('EMAIL_SMTP');
        $smptPort = systemSetting('EMAIL_PORT');
        $account = systemSetting('EMAIL_USER');
        $password = systemSetting('EMAIL_PWD');
        if(empty($smtpHost) || empty($smptPort)){
            return ajaxError("请先完成邮箱设置");
        }
        try {
            $mail = new PHPMailer(true);
            $mail->SMTPAuth = true;
            $mail->Username = $account;
            $mail->Password = $password;
            $mail->Host = $smtpHost;
            $mail->Port = $smptPort;
            $mail->SMTPSecure = '';
            $mail->SMTPAutoTLS = false;
            $validCredentials = $mail->SmtpConnect();
            if($validCredentials){
                $mail->smtpClose();
                return ajaxSuccess('邮箱设置测试成功');
            }else{
                return ajaxError('邮箱设置测试失败');
            }
        }catch(\Exception $e){
            return ajaxError('邮箱设置测试失败');
        }
        return ajaxSuccess('邮箱设置测试成功');
    }
    /******************************************************************************************************************/
    public function dbBackups(){
        if($this->request->isGet()){
            return $this->fetch();
        }
        $backupPath = systemSetting('DB_BACKUP_PATH');
        $backupFiles = [];
        if (is_dir($backupPath) && file_exists($backupPath) && $handle = opendir($backupPath)) {
            //xinling_own_new_20211105.zip
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $slices = explode('.',$file);
                    $backupDate = explode('#',$slices[0])[1];
                    $backupFiles[] = [
                        'name'=>$file,
                        'date'=>date('Y-m-d', strtotime($backupDate))
                    ];
                }
            }
            closedir($handle);
        }
        //排序
        $dates = array_column($backupFiles, 'date');
        array_multisort($dates, SORT_DESC, $backupFiles);
        return json($backupFiles);
    }
    
    /**
     * 系统异常
     *
     * @param  mixed $page
     * @return void
     */
    public function sysErrExp($page=1,
        $rows=DEFAULT_PAGE_ROWS,
        $sort='',
        $order=''){
        if(request()->isGet()){
            $urlHrefs = [
                'index'=>url('index/System/sysErrExp'),
            ];
            $this->assign('urlHrefs', $urlHrefs);
            return $this->fetch();
        }
        if($sort == 'time'){
            $order = 'time ' . $order;
        }else{
            $order = 'id desc';
        }
        $conditions = [];
        $totalCount = Db::table('sys_err_exp')->where($conditions)->count();
        $records = Db::table('sys_err_exp')
            ->where($conditions)
            ->page($page, $rows)
            ->order($order)
            ->field('*')
            ->select();
        return json([
            'total'=>$totalCount,
            'rows'=>$records
        ]);
    }
    public function clearSysErrExp(){
        Db::execute("truncate table sys_err_exp");
        return ajaxSuccess();
    }
     
    /**
     * 数据库慢查询
     *
     * @param  mixed $page
     * @param  mixed $rows
     * @param  mixed $sort
     * @param  mixed $order
     * @return void
     */
    public function dbSlowQuery($page=1,$rows=DEFAULT_PAGE_ROWS,$sort='',$order=''){
        if($this->request->isGet()){
            $urlHrefs  = [
                'index'=>url('index/System/dbSlowQuery')
            ];
            $this->assign('urlHrefs', $urlHrefs);
            return $this->fetch();
        }

        if(in_array($sort, ['occur_time', 'query_time', 'lock_time', 'rows_sent', 'rows_examined'])){
            $order = $sort . ' ' . $order;
        }else{
            $order = 'id desc';
        }
        //搜索
        $conditions = [];
        $total = Db::table('slow_query')->where($conditions)->count();
        $rows = Db::table('slow_query')
            ->where($conditions)
            ->page($page, $rows)
            ->order($order)
            ->field(true)
            ->select();
        return json([
            'total'=>$total,
            'rows'=>$rows
        ]);
    }
    public function clearDbSlowQuery(){
        Db::execute("truncate table slow_query");
        return ajaxSuccess();
    }
    public function fixDbSlowQuery($id){
        Db::table('slow_query')->where(['id'=>$id])->update(['status'=>1]);
        return ajaxSuccess();
    }
    
    /**
     * 软件授权
     *
     * @return void
     */
    public function license(){
        if($this->request->isGet()){
            //刷新授权
            LicenseLogic::I()->refresh();
            $this->assign(array_merge([
                    'buy_url'=>LicenseLogic::I()->getBuyUrl()
                ],LicenseLogic::I()->fetch())
            );
            return $this->fetch();
        }
        $action = input('post.action');
        try{
            if($action == 'unbind'){
                LicenseLogic::I()->unbind();
            }else if($action == 'bind'){
                $authKey = input('post.auth_key');
                if(empty($authKey)){
                    return ajaxError('参数非法');
                }
                LicenseLogic::I()->bind($authKey);
            }else if($action == 'refresh'){
                LicenseLogic::I()->refresh();
            }
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess();
    }
    public function downloadReportAlgo(){
        try{
            SubjectLogic::I()->downloadReportAlgo();
            return ajaxSuccess();
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
}