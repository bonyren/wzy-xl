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
namespace app;
class Defs{
    /*设备类型定义*/
    const DEVICE_DESKTOP = 1;
    const DEVICE_MOBILE = 2;
    const DEVICE_DEFS = [
        self::DEVICE_DESKTOP=>'电脑',
        self::DEVICE_MOBILE=>'手机'
    ];
    public static $DeviceHtmlDefs = [
        self::DEVICE_DESKTOP=>'<span class="fa fa-desktop" title="电脑"></span>',
        self::DEVICE_MOBILE=>'<span class="fa fa-mobile" title="手机"></span>'
    ];
    const AJAX_ERR_CODE_BAD_REQUEST = 400;
    const AJAX_ERR_CODE_UNAUTHORIZED = 401;
    const AJAX_ERR_CODE_FORBIDDEN = 403;
    //管理员用户状态
    const eEnableStatus = 1;
    const eDisabledStatus = 2;
    public static $eStatusDefs = array(
        self::eEnableStatus=>'有效',
        self::eDisabledStatus=>'无效'
    );
    //电商商品状态
    const eGoodsPending = 1;
    const eGoodsUsed = 2;
    public static $eGoodsStatusDefs = [
        self::eGoodsPending=>'未使用',
        self::eGoodsUsed=>'已使用'
    ];
    public static $eGoodsStatusHtmlDefs = [
        self::eGoodsPending=>'<span class="badge badge-secondary">未使用</span>',
        self::eGoodsUsed=>'<span class="badge badge-success">已使用</span>'
    ];
    /*****************************************************************/
    const DEFAULT_DB_DATE_VALUE = '0000-00-00';
    const DEFAULT_DB_DATETIME_VALUE = '0000-00-00 00:00:00';
    const WZYER_WXPAY_MCH_ID = '1598729631';
    /**内部超级访问特权**/
    const INTERNAL_PRIVILEGE_TOKEN = 'D751A17428B0CD6077668A706177B8B1';
    const BUILT_IN_MP_USER = [
        'id' => 1,
        'openid' => 'robot',
        'nickname' => 'robot',
        'headimg_url' => '/static/mp.ionic/img/robot.png',
    ];
    /** 订单支付状态 */
    const PAY_FAIL = -1; //支付错误
    const PAY_WAIT = 1; //待支付
    const PAY_SUCCESS = 100; //支付成功
    const PAY_REFUND = 110;//已退款
    const PAY_OFFLINE = 400;//线下支付
    const PAYS = [
        self::PAY_FAIL=>'支付错误',
        self::PAY_WAIT=>'待支付',
        self::PAY_SUCCESS=>'支付成功',
        self::PAY_REFUND=>'已退款',
        self::PAY_OFFLINE=>'线下支付'
    ];
    const PAYS_HTML = [
        self::PAY_FAIL=>'<span class="badge badge-warning">支付错误</span>',
        self::PAY_WAIT=>'<span class="badge badge-secondary">待支付</span>',
        self::PAY_SUCCESS=>'<span class="badge badge-success">支付成功</span>',
        self::PAY_REFUND=>'<span class="badge badge-danger">已退款</span>',
        self::PAY_OFFLINE=>'<span class="badge badge-primary">线下支付</span>'
    ];
    /** 渠道 */
    const CHANNEL_WX = 1;
    const CHANNELS = [
        self::CHANNEL_WX => '公众号'
    ];
    const CHANNELS_HTML = [
        self::CHANNEL_WX => '<span class="badge badge-primary">公众号</span>',
    ];
    /** 微信菜单类型 */
    const WX_MENU_TYPES = [
        'view' => '跳转网页',
        'miniprogram' => '跳转小程序',
        'click' => '发送消息',
    ];

    /** 量表类型 */
    const SUBJECT_TYPE_PSYCHOLOGY = 1; //心理量表
    const SUBJECT_TYPE_HEALTH = 2; //健康量表

    const SUBJECT_WHOLE_STANDARD = '---整体---';
    /** 题目类型 */
    const QUESTION_RADIO = 1;
    const QUESTION_CHECKBOX = 2;
    const QUESTION_TEXT = 3;
    const QUESTION_TYPES = [
        self::QUESTION_RADIO => '单选',
        self::QUESTION_CHECKBOX => '多选',
        self::QUESTION_TEXT => '填写',
    ];
    const QUESTION_HTML_TYPES = [
        self::QUESTION_RADIO => '<span class="badge badge-primary">单选</span>',
        self::QUESTION_CHECKBOX => '<span class="badge badge-secondary">多选</span>',
        self::QUESTION_TEXT => '<span class="badge badge-info">填写</span>',
    ];
    /**测评题目标签**/
    const SUBJECT_ITEM_TAG_NONE = 'none';
    const SUBJECT_ITEM_TAG_SEX = 'sex';
    const SUBJECT_ITEM_TAG_AGE = 'age';
    const SUBJECT_ITEM_TAG_DEFS = [
        self::SUBJECT_ITEM_TAG_SEX=>'性别',
        self::SUBJECT_ITEM_TAG_AGE=>'年龄'
    ];
    /** 题目选项性质 */
    const SUBJECT_ITEM_OPTION_NATURE_NONE = 0;//未定
    const SUBJECT_ITEM_OPTION_NATURE_POSITIVE = 1;//阳性
    const SUBJECT_ITEM_OPTION_NATURE_NEGATIVE = 2;//阴性
    const SUBJECT_ITEM_OPTION_NATURES =[
        self::SUBJECT_ITEM_OPTION_NATURE_NONE=>'无',
        self::SUBJECT_ITEM_OPTION_NATURE_POSITIVE=>'阳性',
        self::SUBJECT_ITEM_OPTION_NATURE_NEGATIVE=>'阴性'
    ];
    const SUBJECT_ITEM_OPTION_ICONS_NATURES =[
        self::SUBJECT_ITEM_OPTION_NATURE_NONE=>'icons-gray',
        self::SUBJECT_ITEM_OPTION_NATURE_POSITIVE=>'icons-red',
        self::SUBJECT_ITEM_OPTION_NATURE_NEGATIVE=>'icons-green'
    ];
    const SUBJECT_ITEM_OPTION_HTML_NATURES =[
        self::SUBJECT_ITEM_OPTION_NATURE_NONE=>'无',
        self::SUBJECT_ITEM_OPTION_NATURE_POSITIVE=>'<span class="badge badge-danger">阳性</span>',
        self::SUBJECT_ITEM_OPTION_NATURE_NEGATIVE=>'<span class="badge badge-success">阴性</span>'
    ];
    /*分数统计类型*/
    const LATITUDE_MEASURE_WEIGHT_ORIGINAL = 1;//原始分
    const LATITUDE_MEASURE_WEIGHT_STANDARD = 2;//标准分
    const LATITUDE_MEASURE_WEIGHT_TYPES = [
        self::LATITUDE_MEASURE_WEIGHT_ORIGINAL=>'原始分',
        self::LATITUDE_MEASURE_WEIGHT_STANDARD=>'标准分'
    ];

    /** 分数统计指标 */
    const MEASURE_INDICATOR_TOTAL_SCORE = 1;//总分
    const MEASURE_INDICATOR_AVERAGE_SCORE = 2;//均分
    const MEASURE_INDICATOR_POSITIVE_ITEM_COUNT = 3;//阳性项目数
    const MEASURE_INDICATOR_NEGATIVE_ITEM_COUNT = 4;//阴性项目数
    const MEASURE_INDICATOR_POSITIVE_ITEM_TOTAL_SCORE = 5;//阳性项目总分
    const MEASURE_INDICATOR_POSITIVE_ITEM_AVERAGE_SCORE = 6;//阳性项目均分
    const MEASURE_TARGETS = [
        self::MEASURE_INDICATOR_TOTAL_SCORE=>'总分',
        self::MEASURE_INDICATOR_AVERAGE_SCORE=>'均分',
        self::MEASURE_INDICATOR_POSITIVE_ITEM_COUNT=>'阳性项目数',
        self::MEASURE_INDICATOR_NEGATIVE_ITEM_COUNT=>'阴性项目数',
        self::MEASURE_INDICATOR_POSITIVE_ITEM_TOTAL_SCORE=>'阳性项目总分',
        self::MEASURE_INDICATOR_POSITIVE_ITEM_AVERAGE_SCORE=>'阳性项目均分'
    ];
    /** 测评结果预警 */
    const MEASURE_WARNING_UNKOWN_LEVEL = 0;//未定义
    const MEASURE_WARNING_GREEN_LEVEL = 1;//绿
    const MEASURE_WARNING_YELLOW_LEVEL = 2;//黄
    const MEASURE_WARNING_RED_LEVEL = 3;//红
    const MEASURE_WARNINGS = [
        self::MEASURE_WARNING_UNKOWN_LEVEL=>'未定义',
        self::MEASURE_WARNING_GREEN_LEVEL=>'无异常',
        self::MEASURE_WARNING_YELLOW_LEVEL=>'警觉',
        self::MEASURE_WARNING_RED_LEVEL=>'严重'
    ];
    const MEASURE_WARNINGS_HTML = [
        self::MEASURE_WARNING_UNKOWN_LEVEL=>'<span class="badge badge-light">未定义</span>',
        self::MEASURE_WARNING_GREEN_LEVEL=>'<span class="badge badge-success">无异常</span>',
        self::MEASURE_WARNING_YELLOW_LEVEL=>'<span class="badge badge-warning">警觉</span>',
        self::MEASURE_WARNING_RED_LEVEL=>'<span class="badge badge-danger">严重</span>'
    ];
    const MEASURE_WARNINGS_MP_HTML = [
		self::MEASURE_WARNING_UNKOWN_LEVEL=>'<ion-badge color="light">未定义</ion-badge>',
		self::MEASURE_WARNING_GREEN_LEVEL=>'<ion-badge color="success">无异常</ion-badge>',
		self::MEASURE_WARNING_YELLOW_LEVEL=>'<ion-badge color="warning">警觉</ion-badge>',
		self::MEASURE_WARNING_RED_LEVEL=>'<ion-badge color="danger">严重</ion-badge>'
	];
    /** 测评报告组成 */
    const SUBJECT_REPORT_ELEMENT_TOTAL_WEIGHT_CHART = 10;//总分图表
    const SUBJECT_REPORT_ELEMENT_AVERAGE_WEIGHT_CHART = 11;//平均分图表
    const SUBJECT_REPORT_ELEMENT_POSITIVE_ITEM_COUNT_CHART = 12;//阳性数量图表
    const SUBJECT_REPORT_ELEMENT_POSITIVE_AVERAGE_WEIGHT_CHART = 13;//阳性平均分图表

    const SUBJECT_REPORT_ELEMENT_RESULT_DESC = 20;//结果解读
    const SUBJECT_REPORT_ELEMENT_RESULT_WEIGHT = 21;//结果分数

    const SUBJECT_REPORT_ELEMENT_VIDEO_AUDIO = 30;//视频音频解说
    const SUBJECT_REPORT_ELEMENT_STORY = 40;//专家建议

    const REPORT_ELEMENTS = [
        self::SUBJECT_REPORT_ELEMENT_TOTAL_WEIGHT_CHART=>'总分图表',
        self::SUBJECT_REPORT_ELEMENT_AVERAGE_WEIGHT_CHART=>'平均分图表',
        self::SUBJECT_REPORT_ELEMENT_POSITIVE_ITEM_COUNT_CHART=>'阳性数量图表',
        self::SUBJECT_REPORT_ELEMENT_POSITIVE_AVERAGE_WEIGHT_CHART=>'阳性平均分图表',
        self::SUBJECT_REPORT_ELEMENT_RESULT_DESC=>'结果解读',
        self::SUBJECT_REPORT_ELEMENT_RESULT_WEIGHT=>'结果分数',
        self::SUBJECT_REPORT_ELEMENT_VIDEO_AUDIO=>'视频音频解说',
        self::SUBJECT_REPORT_ELEMENT_STORY=>'专家建议'
    ];


    /**量表标签*/
    const SUBJECT_LABELS = [
        'banner' => '轮播',
        'popular' => '热门',
        'featured'=>'精选'
    ];
    //量表
    const SUBJECT_ORDER_NO_SEQ_NUM_KEY = 'SUBJECT_ORDER_NO_SEQ_NUM_KEY';
    const SUBJECT_ORDER_REFUND_NO_SEQ_NUM_KEY = 'SUBJECT_ORDER_REFUND_NO_SEQ_NUM_KEY';
    //用户名称
    const CUSTOMER_NAME_SEQ_NUM_KEY = 'CUSTOMER_NAME_SEQ_NUM_KEY';
    //专家预约
    const APPOINT_ORDER_NO_SEQ_NUM_KEY = 'APPOINT_ORDER_NO_SEQ_NUM_KEY';
    const APPOINT_ORDER_REFUND_NO_SEQ_NUM_KEY = 'APPOINT_ORDER_REFUND_NO_SEQ_NUM_KEY';

    const DEFAULT_IMG_DATA_URL = 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=';
}