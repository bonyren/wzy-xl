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
use app\mp\logic\UniApp as UniAppLogic;

class UniApp extends Controller{
	const DEBUG_ENV = true;
	protected $login_user_id = 0;
	protected $login_user_channel = null;
	protected $login_user_openid = null;
    public function _initialize(){
        $controller = Request::instance()->controller();
        $action = Request::instance()->action();
        Log::notice("_initialize {$controller} {$action}");
        if(in_array(strtolower($action), array('createorder', 'my', 'uploadavatar')) ) {
            $this->mustLogin();
        }else{
            $this->tryLogin();
        }
    }
    protected function mustLogin(){
        $sessionKey = input('param.session_key', '');
        if(empty($sessionKey)){
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
        $user = Db::table('uni_app_users')->where('session_key', $sessionKey)->find();
        if(!$user){
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
        $this->login_user_id = $user['id'];
		$this->login_user_channel = $user['channel'];
        $this->login_user_openid = $user['openid'];
    }
    protected function tryLogin(){
        $sessionKey = input('param.session_key', '');
        if($sessionKey){
            $user = Db::table('uni_app_users')->where('session_key', $sessionKey)->find();
            if($user){
                $this->login_user_id = $user['id'];
				$this->login_user_channel = $user['channel'];
                $this->login_user_openid = $user['openid'];
            }
        }
    }
	/**
     * @param $code
     * @return \think\response\Json
     * @throws \think\Exception
     */
    public function login($channel, $code=''){
        try{
            $result = UniAppLogic::I()->login($channel, $code);
            return ajaxSuccess('操作成功', $result);
        }catch(\Exception $e){
            return ajaxError($e->getMessage());
        }
    }
   
    public function logout($session_key){
		UniAppLogic::I()->logout($session_key);
		return ajaxSuccess();
	}    
    /**
     * 首页配置
     *
     * @return void
     */
    public function homeConfig($channel='WEIXIN'){
        $icons = [
            'WEIXIN'=>['gift', 'heart', 'unhappy', /*'system-3'*/'rocket'],
            'BAIDU'=>['collect', 'volunteer', 'dislike', /*'classification'*/'hot']
        ];
        $shortcuts = [
            [
                'id'=>0,
                'title'=>'免费测量',
                'icon'=>$icons[$channel][0],
                'url'=>'/pages/index/index',
                'category_id'=>-1
            ],
            [
                'id'=>1,
                'title'=>'爱情婚姻',
                'icon'=>$icons[$channel][1],
                'url'=>'/pages/index/index',
                'category_id'=>4
            ],
            [
                'id'=>2,
                'title'=>'心理健康',
                'icon'=>$icons[$channel][2],
                'url'=>'/pages/index/index',
                'category_id'=>5
            ],
            /*
            [
                'id'=>3,
                'title'=>'更多分类',
                'icon'=>$icons[$channel][3],
                'url'=>'/pages/index/index',
                'category_id'=>0
            ],*/
            [
                'id'=>3,
                'title'=>'能力潜质',
                'icon'=>$icons[$channel][3],
                'url'=>'/pages/index/index',
                'category_id'=>13
            ],
        ];
        return ajaxSuccess('操作成功',[
            'shortcuts'=>$shortcuts,
            'show_price'=>true
        ]);
    }
	/**
	热门测评
	*/
	public function subjectsPopular(){
        return ajaxSuccess('操作成功', UniAppLogic::I()->subjectsPopular());
	}
	/**
	精选测评
	*/
	public function subjectsFeatured(){
		return ajaxSuccess('操作成功', UniAppLogic::I()->subjectsFeatured());
	}
    /**测评分类
     * @return \think\response\Json
     */
    public function subjectCategories(){
        $where = [];
        $rows =  Db::table('categories')
            ->where($where)
            ->order('sort asc')
            ->field('id, name, img_url')
            ->select();
        foreach($rows as &$row){
            $row['img_url'] = generateUploadFullUrl(generateThumbnailUrl($row['img_url'], 300));
        }
        array_unshift($rows, ['id'=>-1, 'name'=>'免费测量', 'img_url'=>'']);
		array_unshift($rows, ['id'=>0, 'name'=>'全部', 'img_url'=>'']);
        return ajaxSuccess('操作成功', $rows);
    }
	/**
	 * 查询类别下面的量表
	 *
	 * @param  mixed $categoryId 0：全部，-1：免费
	 * @param  mixed $kw
	 * @return void
	 */
	public function subjectsInCategory($categoryId=0, $kw=''){
        return ajaxSuccess('操作成功', UniAppLogic::I()->subjectsInCategory($categoryId, $kw));
    }
    public function categoryDetail($id){
        if($id == -1){
            //免费
            return ajaxSuccess("操作成功", [
                'name'=>'免费测量',
                'img_url'=>''
            ]);
        }
        $record = Db::table('categories')->where(['id'=>$id])
            ->field('name, img_url')
            ->find();
        if(empty($record)){
            return ajaxError("无法找到该类别");
        }
        $record['img_url'] = generateUploadFullUrl(generateThumbnailUrl($record['img_url'], 300));
        return ajaxSuccess("操作成功", $record);
    }
	/**测评详情
     * @param $id
     */
    public function subjectDetail($id){
        try{
            $record = UniAppLogic::I()->getSubject($this->login_user_id, $id);
            return ajaxSuccess("操作成功", $record);
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
	/**创建测评订单
     * @param $id subject id
     * @return \think\response\Json
     * @throws \think\Exception
     */
    public function createOrder($id, $order_no=''){
        try{
            $order_no = UniAppLogic::I()->createOrder($this->login_user_id, $id, $order_no);
            return ajaxSuccess('操作成功',$order_no);
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function regenOrder($order_no){
        try{
            UniAppLogic::I()->regenOrder($order_no);
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
	public function queryOrder($order_no){
		$order = $this->getOrderByNo($order_no);
		if(empty($order)){
			return ajaxError('无法找到该测评订单');
		}
		$order['order_amount'] = sprintf('%.2f', $order['order_amount']);
		return ajaxSuccess('操作成功', $order);
	}
	protected function getOrderByNo($order_no){
        if (empty($order_no)) {
            return [];
        }
        $row = Db::table('uni_app_orders')->where(['order_no'=>$order_no])->field('*')->find();
        if ($row && !empty($row['result'])) {
            $result = json_decode($row['result'], true);
            if($result && isset($result['reportList'])) {
                $row['report_list'] = $result['reportList'];
            }else{
                Log::error("failed to decode result for subject order: {$order_no}");
                $row['report_list'] = [];
            }
        }
        return $row;
    }
	/**获取测评订单的数据
     * @param $order_no
     * @return \think\response\Json
     */
    public function test($order_no){
        $order = $this->getOrderByNo($order_no);
        if (empty($order)) {
            return ajaxError('无法找到该测评订单');
        }
        if ($order['finished']) {
            return ajaxError('订单测评已完成');
        }
        $subject = Db::table('subject')->where(['id'=>$order['subject_id']])->field(true)->find();
        if(empty($subject)){
            return ajaxError('无法找到该测评量表');
        }
        $subjectItems = Subjects::I()->getSubjectItems($order['subject_id']);
        if (empty($subjectItems)) {
            return ajaxError('该评测量表不可用');
        }
        //已完成的item id, 未开始为0
        $currItem = $order['curr_item'];
        //已完成的item数量, 未开始为0
        $testItems = $order['test_items'];
        //已完成的项目
        $items = $order['items'];
        return ajaxSuccess('操作成功', [
            'order'=>$order,
            'subject'=>$subject,
            'subjectItems'=>UniAppLogic::I()->makeTestSubjectItems($items, $subjectItems)
        ]);
    }

    /**测评答题
     * @param $order_no
     * @param $item_id
     * @param $item_type
     * @param $item_option, 多选允许为空的情况下,item_option参数会缺失
     * @return \think\response\Json
     */
    //答题 单选:$item_option为option id, 多选:$item_option为数组, 填写: $item_option为文本
    public function answer($order_no, $item_id, $item_type, $item_option=''){
        try{
            return ajaxSuccess('操作成功', UniAppLogic::I()->answer($order_no, $item_id, $item_type, $item_option));
        }catch(WException $e){
            $exceptionCode = $e->getCode();
            if($exceptionCode == -1){
                //测评项目版本变更
                return ajaxSuccess('测评项目版本变更', -1);
            }
            return ajaxError($e->getMessage());
        }
    }
	const REPORT_SECRET = 'abc4d410dde66c68c37e3b7d0d8d2a17701602aa';
    public function generateReport($order_no){
        $order = $this->getOrderByNo($order_no);
        if (empty($order)) {
            return ajaxError('无法找到该测评订单');
        }
        if(!$order['finished']){
            return ajaxError('该测评订单未完成');
        }
        if($order['pay_status'] != Defs::PAY_SUCCESS){
            //待支付
        }
        //report url
        $timestamp = time();
        $sign = md5(self::REPORT_SECRET . $order_no . $timestamp);
        $reportUrl = url('mp/UniApp/report', ['sign'=>$sign, 'order_no'=>$order_no, 'timestamp'=>$timestamp], true, true);
        $data = [
            'showPay'=>$order['pay_status'] != Defs::PAY_SUCCESS?true:false,
            'order'=>['order_amount'=>$order['order_amount']],
            'report_url'=>$reportUrl
        ];
        return ajaxSuccess("操作成功", $data);
    }
    /**获取测评报告
     * @param $order_no
     * @param $origin "", "share"
     * @return mixed
     */
    public function report($sign, $order_no, $timestamp){
        $signCalc = md5(self::REPORT_SECRET . $order_no . $timestamp);
        if($sign != $signCalc){
            return '非法请求';
        }
        $timestampNow = time();
        if($timestampNow > $timestamp && ($timestampNow - $timestamp) > 300){
            return '请求有效期超时';
        }
        $order = $this->getOrderByNo($order_no);
        if (empty($order) || !$order['finished']) {
            return '无法找到该测评订单';
        }
        if($order['pay_status'] != Defs::PAY_SUCCESS){
            //待支付
            return '订单未支付';
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
        $subject = Subjects::I()->getSubjectById($order['subject_id']);
        if(empty($subject)){
            return '无法找到该测评量表';
        }
        if($subject['report_elements']){
            $subject['report_elements'] = explode(',', $subject['report_elements']);
        }else{
            $subject['report_elements'] = [];
        }
        $imageUrl = $subject['image_url'];
		$subject['image_url'] = generateUploadFullUrl(generateThumbnailUrl($imageUrl, 300));
        //轮播图
        $subject['banner_img'] = generateUploadFullUrl(generateThumbnailUrl($imageUrl, 300));
		
        $user = Db::table('uni_app_users')->where(['id'=>$order['user_id']])->field('*')->find();
        if(empty($user)){
            return '无法找到该测评用户';
        }
        $this->assign('order',$order);
        $this->assign('user',$user);
        $this->assign('subject',$subject);
        $this->assign('uuid', uniqid());

        $storeName = Db::table('studio')->where('key', 'store_name')->value('value');
        $storeName = $storeName??'';
        $this->assign('_studio', ['store_name'=>$storeName]);
        
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
	public function my(){
		$ordersArray = [0=>[], 1=>[]];
		foreach($ordersArray as $index=>&$orders){
			$orders = Db::table('uni_app_orders')
				->alias('O')
				->join('subject S', 'O.subject_id=S.id')
				->where(['O.user_id'=>$this->login_user_id, 'O.finished'=>$index])
				->order("O.order_time desc")
				->field('O.order_no, O.order_time, O.finished, O.subject_id, O.test_items, O.total_items, O.pay_status,
                    S.name as subject_name, S.subtitle as subject_subtitle, S.image_url, S.banner_img')
				->select();
			foreach($orders as &$order){
                $imageUrl = $order['image_url'];
				$order['image_url'] = generateUploadFullUrl(generateThumbnailUrl($imageUrl, 300));
				//轮播图
				$order['banner_img'] = generateUploadFullUrl(generateThumbnailUrl($imageUrl, 300));
				$order['progress_percent'] = floor($order['test_items']/$order['total_items']*100);
			}
		}
		
        return ajaxSuccess('操作成功', [
            'ordersInProgress'=>$ordersArray[0],
			'ordersFinished'=>$ordersArray[1]
        ]);
    }
    public function uploadAvatar(){
        $ext = ['ext'=>'jpg,png,gif,jpeg'];
        $file = request()->file('avatar');
        if($file == null){
            return ajaxError('failed to get the upload file');
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
            return ajaxError('failed to upload file, cause: ' . $errorDesc);
        }
        $rules = array_merge(config('upload'), $ext);
        $fileInfo = $file->validate($rules)->move(UPLOAD_DIR);
        if($fileInfo == null){
            return ajaxError('失败 - ' . $file->getError());
        }
        $saveName = $fileInfo->getSaveName();
        $url = convertUploadSaveName2FullUrl($saveName);
        $relativeUrl = convertUploadSaveName2RelativeUrl($saveName);
        $absoluteUrl = convertUploadSaveName2AbsoluteUrl($saveName);
        Db::table('uni_app_users')->where(['id'=>$this->login_user_id])->update(['headimg_url'=>$absoluteUrl]);
        return ajaxSuccess('操作成功', generateUploadFullUrl($absoluteUrl));
    }
    public function applyAuthorizeCode($order_no, $code){
        $order = $this->getOrderByNo($order_no);
        if (empty($order)) {
            return ajaxError('无法找到该测评订单');
        }
        $row = Db::table('uni_app_authorize_code')->where(['code'=>$code])
            ->field(true)->find();
        if(empty($row)){
            return ajaxError('无法找到该授权码信息');
        }
        if($row['used'] >= $row['total']){
            return ajaxError('该授权码配额已用完');
        }
        Db::table('uni_app_authorize_code')->where(['code'=>$code])->setInc('used');
        //更新订单信息
        Db::table('uni_app_orders')->where(['order_no'=>$order_no])
            ->update(['order_amount'=>0, 'pay_status'=>Defs::PAY_SUCCESS]);
        return ajaxSuccess('操作成功');
    }
	public function generateOrderPay($order_no){
        $order = $this->getOrderByNo($order_no);
        if (empty($order)) {
            return ajaxError('无法找到该测评订单');
        }
		if($this->login_user_channel == 'BAIDU'){
            return $this->_generateBaiduPay($order_no, $order['order_amount']);
		}else if($this->login_user_channel == 'WEIXIN'){
            return $this->_generateWeixinPay($order_no, $order['order_amount']);
		}else{
            //NATIVE扫码支付
			/*
            return $this->_generateWeixinPay($order_no, $order['order_amount'], false);
            */
			Db::table('uni_app_orders')
                ->where(['order_no'=>$order_no])
                ->update(['pay_status'=>Defs::PAY_SUCCESS,
                    'notify_time'=>date('Y-m-d H:i:s'), 
                    'pay_time'=>date('Y-m-d H:i:s')
                ]);
			return ajaxSuccess('操作成功',[]);
		}
    }
    protected function _generateBaiduPay($order_no, $order_amount){
        $amount = intval($order_amount*100);//分为单位
        $title = "定制开发功能请联系（wzycoding@qq.com）";
        //$privateKey = "MIICXgIBAAKBgQCvoj1JfsI9kUNRG5KSvtn7gZHjH86vQqIuWMb3WYP2uEecWqDC/oFBwT2I4vfYw98DRjxNnT4Af2/6VdBS+2LEB9lQo1r1KV8b1a5Enw3BF+I9M1lzTGByPWFuhHkZsBUny9HXBGlFcS3MKIYGhjNk9qW60jiDsK35GsnPnOmxbQIDAQABAoGBAKObNQ4Wh2iEvbl7PtF1+Wbg7v4s7gKyxpL33fgiKdyVExgiECk0nUGcee7extPkugS504jVViFulOgUMihqxjeciG6hkZLAUJV5Es3tjp0+Mfm8KXpEi0+p+PT9BPP3Ij3G1y9fhNL6NShr3/bhNKmgVMuMqqjeotktUPLDkTcBAkEA2f89u+Q4hse583ji1WwRJLuLcgXDGxMQTbvZo6wq02wmcMTIWmibhsn6O6vd4Vx/6wxgYKy5eXVQGZK1undXLQJBAM5AaXR3+PlHdCjh00IbyTcu5ZfEBJrM597hEZFMioGa1yyksTVDVO7WqBNNuXKzkQCNTmkvAn1AYpbawTfYK0ECQDWcPJ1ZecFDmupSX05nHiwvZxKqchnVbVwAh3xl2b5WyXlQG4mIUj8qqrxD/vPuIJM4XvdHYvGItSMk3kY2FR0CQQCmdA9ngqd5rvQNRmtzr+8NTRjYCcdZk0MSefvukqI5hNhhXCzz+noiIzUUFycybb75fhEpDFpq8Tpf2v4S7q/BAkEAvKIzuN74upqUiGl/uM4YZfvg20/lhXOD5RzDREzbvmZaoko4oaKKYnHIrjD1hDUS0Sy3EPssx7TR7FnD2lR4xQ==";
        //$privateKey = chunk_split($privateKey, 64, "\n");
        //$privateKey = "-----BEGIN RSA PRIVATE KEY-----\n$privateKey-----END RSA PRIVATE KEY-----\n";
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----
定制开发功能请联系（wzycoding@qq.com）
-----END RSA PRIVATE KEY-----
";
        $rsaSign = \baidu\RSASign::sign([
            'appKey'=>'',
            'dealId'=>'',
            'tpOrderId'=>$order_no,
            'totalAmount'=>strval($amount)
        ], $privateKey);

        return ajaxSuccess('操作成功',[
            'dealId'=>'',
            'appKey'=>'',
            'totalAmount'=>strval($amount),
            'tpOrderId'=>$order_no,
            'dealTitle'=>$title,
            'signFieldsRange'=>'1',
            'rsaSign'=>$rsaSign,
            'payResultUrl'=>'/pages/report/report?orderNo=' . $order_no,
            'bizInfo'=>json_encode([])//optional
        ]);
    }
    /*
	payCallback, method:POST, params:array (
      'unitPrice' => '100',
      'orderId' => '84822615128566',
      'payTime' => '1618544293',
      'dealId' => '1795604569',
      'tpOrderId' => 'e8f8e4b5c22caea6ef5d9630eb9657b2',
      'count' => '1',
      'totalMoney' => '100',
      'hbBalanceMoney' => '0',
      'userId' => '3406186723',
      'promoMoney' => '0',
      'promoDetail' => '',
      'hbMoney' => '0',
      'giftCardMoney' => '0',
      'payMoney' => '100',
      'payType' => '1117',
      'returnData' => '',
      'partnerId' => '6000001',
      'rsaSign' => 'MWZ13bhGyev2hsbj/3dryasokeC/YOEJR8AhoVtKS6P3HklCmJW5AVXaZ86RdgJ8Xbvo2IRbSMBJDWuKUAu/5XXx/HserULMSgXusDFhEYA4OtbhoB+iQi8aFoPS9FrRkPavCbXU3X6aJlu6oeS/5nYJdms5t442vDT9lXIKGlY=',
      'status' => '2',
    )*/
    public function payCallbackBaidu(){
        $params = input('param.');
        Log::notice("payCallbackBaidu, method:" . request()->method() . ", params:" . var_export($params, true));
		if(isset($params['tpOrderId']) && isset($params['tpOrderId'])){
			Db::table('uni_app_orders')
                ->where(['order_no'=>$params['tpOrderId']])
                ->update(['pay_status'=>Defs::PAY_SUCCESS,
                    'notify_time'=>date('Y-m-d H:i:s'), 
                    'pay_time'=>date('Y-m-d H:i:s')
                ]);
		}
        return "ok";
    }
    protected function _generateWeixinPay($order_no, $order_amount, $jsapi=true){
        $app = Factory::payment(array_merge(config('wx.payment'), ['app_id'=>config('wx.mini_program')['app_id']]));
        $res = $app->order->unify([
            'body' => '测评费用',
            'out_trade_no' => $order_no,
            'total_fee' => $order_amount * 100, //分为单位，整数
            'notify_url' => url('mp/UniApp/payCallbackWeixin','',false,true), //微信异步通知地址，不能携带参数
            'trade_type' => $jsapi?'JSAPI':'NATIVE', //H5支付
            'openid' => $this->login_user_openid,
        ]);
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
            return ajaxError('微信下单失败：' . $res['return_msg']);
        }
        if ($res['result_code'] == 'FAIL') {
            return ajaxError('微信下单失败：' . $res['err_code_des']);
        }
        if(!isset($res['prepay_id'])){
            return ajaxError('微信下单失败：无法找到prepay_id');
        }
        $data = [];
        Db::table('uni_app_orders')->where(['order_no'=>$order_no])->setField('prepay_id',$res['prepay_id']);
        $data['config'] = $app->jssdk->bridgeConfig($res['prepay_id'],false); // 返回数组
        return ajaxSuccess('操作成功', $data);
    }
    public function payCallbackWeixin(){
        $config_payment = config('wx.payment');
        $app = Factory::payment($config_payment);
        $response = $app->handlePaidNotify(function ($msg, $fail) {
            file_put_contents(LOG_PATH.'wx_pay.log',var_export($msg,true),FILE_APPEND);
            // return_code 表示通信状态，不代表支付状态
            if ($msg['return_code'] == 'FAIL') {
                return $fail('通信失败，请稍后再通知我');
            }

            $order = $this->getOrderByNo($msg['out_trade_no']);
            if (empty($order) || $order['pay_status'] == Defs::PAY_SUCCESS) {
                return true;
            }
            $save = [];
            $save['notify_time'] = date('Y-m-d H:i:s');
            // 用户是否支付成功
            if ($msg['result_code'] == 'SUCCESS') {
                $save['pay_status'] = Defs::PAY_SUCCESS;
                $save['pay_time'] = date('Y-m-d H:i:s', strtotime($msg['time_end']));
            } else {
                // 用户支付失败
                $save['pay_status'] = Defs::PAY_FAIL;
            }
            Db::table('uni_app_orders')->where(['order_no'=>$order['order_no']])->update($save);
            return true;
        });
        $response->send();
    }
    public function queryOrderPay($order_no){
        $order = $this->getOrderByNo($order_no);
        if (empty($order)) {
            return ajaxError('无法找到该测评订单');
        }
        if(!$order['finished']){
            return ajaxError('该测评订单未完成');
        }
        if($order['pay_status'] != Defs::PAY_SUCCESS){
            return ajaxSuccess("操作成功", false);
        }else{
            return ajaxSuccess("操作成功", true);
        }
    }
}