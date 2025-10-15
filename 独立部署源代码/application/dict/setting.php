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
return array(
    /* 全站设置  */
    'general_site_title' => array(
        'name'    => '系统标题',
        'group'   => '通用',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '***心理咨询系统',
    ),
    'general_site_keywords' => array(
        'name'    => '系统关键字',
        'group'   => '通用',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '***心理咨询系统',
    ),
    'general_site_description' => array(
        'name'    => '系统描述',
        'group'   => '通用',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '***心理咨询系统',
    ),
    /*
    'general_admin_address' => array(
        'name'    => '管理员邮箱(多个用英文,分割)',
        'group'   => '通用',
        //'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','email','length[0,255]'))),
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),*/
    'general_organisation_name' => array(
        'name'    => '组织名字',
        'group'   => '通用',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '***心理咨询公司',
    ),
    'general_organisation_logo' => array(
        'name'    => '系统LOGO(200x200 pixels)',
        'group'   => '通用',
        'editor'  => array('type'=>'image','options'=>array( 'handler'=>'systemSettingModule.image', 'zoom'=>false)),
        'default' => '/static/img/logo.png',
    ),
    'general_organisation_hotline' => array(
        'name'    => '客服热线',
        'group'   => '通用',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'general_power_by_text' => array(
        'name'    => '系统版权',
        'group'   => '通用',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => 'powered by ***',
    ),
    'general_site_beian' => array(
        'name'    => '备案号',
        'group'   => '通用',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    /**************************************************************************************************************/
    'LOGIN_ONLY_ONE'=>array(
        'name'    => '启用单点登录',
        'group'   => '安全设置',
        'editor'  => array('type'=>'checkbox','options'=>array('on'=>'yes','off'=>'no')),
        'default' => 'yes',
    ),
    /******************************量表测评设置*****************************/
    'subject_show_price'=>[
        'name'    => '显示价格',
        'group'   => '量表测评设置',
        'editor'  => ['type'=>'checkbox','options'=>['on'=>'yes','off'=>'no']],
        'default' => 'yes',
    ],
    /******************************预约设置*****************************/
    'appoint_order_pay_model'=>[
        'name'    => '咨询费支付模式',
        'group'   => '预约设置',
        'editor'  => ['type'=>'combobox','options'=>['data'=>[['text'=>'在线支付','value'=>'在线支付'], ['text'=>'线下支付','value'=>'线下支付']], 'editable'=>false]],
        'default' => '在线支付',
    ],
    'appoint_order_ahead_of_days' => array(
        'name'    => '提前预约的天数',
        'group'   => '预约设置',
        'editor'  => array('type'=>'numberbox','options'=>array('tipPosition'=>'left', 'min'=>0)),
        'default' => 1,
    ),
    'appoint_order_allow_days' => array(
        'name'    => '允许预约的天数',
        'group'   => '预约设置',
        'editor'  => array('type'=>'numberbox','options'=>array('tipPosition'=>'left', 'min'=>1)),
        'default' => 6,
    ),
    'appoint_order_office_address' => array(
        'name'    => '预约接待地址',
        'group'   => '预约设置',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'send_appoint_order_to_expert' => array(
        'name'    => '预约订单短信通知专家',
        'group'   => '预约设置',
        'editor'  => array('type'=>'checkbox','options'=>array('on'=>'yes','off'=>'no')),
        'default' => 'yes',
    ),
    'send_appoint_order_to_customer' => array(
        'name'    => '预约订单短信通知客户',
        'group'   => '预约设置',
        'editor'  => array('type'=>'checkbox','options'=>array('on'=>'yes','off'=>'no')),
        'default' => 'yes',
    ),
    'send_appoint_order_to_support_tels' => array(
        'name'    => '客服短信接收预约订单通知手机号码(多个用英文,分割)',
        'group'   => '预约设置',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'appoint_tip' => array(
        'name'    => '预约注意事项提示',
        'group'   => '预约设置',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left','multiline'=>true,'height'=>150,'validType'=>array('length[0,1024]'))),
        'default' => <<<TIP
            <h5>预约成功后，我们工作人员将在24小时内与 您联系，并确定预约时间、地点等；</h5>
            <p><b>客服热线：</b>******； </p>
            <p><b>隐私安全：</b>我们将会对您的所有信息进行保密； </p>
            <p><b>变更预约：</b>若因为不可抗力需要变更/取消已协商好 的咨询预约，请务必提前24小时联络工作人员，否则 咨询将如期开始； </p>
            <p><b>爽约/迟到：</b>若没有提前24小时告知情况，爽约/迟到20分钟以上，则默认这次咨询已经完成。其他特殊情况，需与我公司协商处理； </p>
            <p><b>退款说明：</b>48小时以上取消预约的我们将无条件全额退款；小于48小时大于24小时内取消的，平台将收取20%的违约费；小于24（含）小时，全额不退款。</p>
        TIP
    ),
    /**************************************************************************************************************/
    /* 邮箱设置  */
    /*
    'EMAIL_SMTP' => array(
        'name'    => '发送服务器主机(SMTP)',
        'group'   => '邮箱设置(不支持ssl)',
        'editor'  => array('type'=>'validatebox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
        'collection' => 'email'
    ),
    'EMAIL_PORT' => array(
        'name'    => '发送服务器端口(SMTP)',
        'group'   => '邮箱设置(不支持ssl)',
        'editor'  => 'numberbox',
        'default' => 25,
        'collection' => 'email'
    ),
    'EMAIL_USER' => array(
        'name'    => '用户名',
        'group'   => '邮箱设置(不支持ssl)',
        'editor'  => array('type'=>'validatebox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
        'collection' => 'email'
    ),
    'EMAIL_PWD' => array(
        'name'    => '密码',
        'group'   => '邮箱设置(不支持ssl)',
        'editor'  => array('type'=>'validatebox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
        'collection' => 'email'
    ),
    'EMAIL_FROM_ADDRESS' => array(
        'name'    => '发信地址',
        'group'   => '邮箱设置(不支持ssl)',
        'editor'  => array('type'=>'validatebox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
        'collection' => 'email'
    ),
    'EMAIL_FROM_NAME' => array(
        'name'    => '发信名字',
        'group'   => '邮箱设置(不支持ssl)',
        'editor'  => array('type'=>'validatebox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
        'collection' => 'email'
    ),*/
    /**************************************************************************************************************/
    /* 数据备份  */
    'DB_BACKUP_PATH' => array(
        'name'    => '备份路径',
        'group'   => '数据备份',
        'editor'  => array('type'=>'validatebox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => ROOT_PATH . 'db' . DS . 'backup',
        'collection' => 'DB_BACKUP'
    ),
    'DB_BACKUP_EXPIRATION' => array(
        'name'    => '保留天数',
        'group'   => '数据备份',
        'editor'  => 'numberbox',
        'default' => 30,
        'collection' => 'DB_BACKUP'
    ),
    /**************************************************************************************************************/
    /*微信服务号设置*/
    'WX_OFFICE_ACCOUNT_NAME' => array(
        'name'    => '微信公众号名称',
        'group'   => '微信公众号',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '***心理咨询系统',
    ),
    'WX_OFFICE_ACCOUNT_APP_ID' => array(
        'name'    => '开发者ID(AppID)',
        'group'   => '微信公众号',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'WX_OFFICE_ACCOUNT_APP_SECRET' => array(
        'name'    => '开发者密码(AppSecret)',
        'group'   => '微信公众号',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'WX_OFFICE_ACCOUNT_SERVER_TOKEN' => array(
        'name'    => '令牌(Token)',
        'group'   => '微信公众号',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    /**************************************************************************************************************/
    /*微信支付设置*/
    'WX_PAYMENT_MCH_ID' => array(
        'name'    => '微信支付商户号',
        'group'   => '微信支付',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'WX_PAYMENT_KEY' => array(
        'name'    => 'APIv2密钥',
        'group'   => '微信支付',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    /**************************************************************************************************************/
    /*微信小程序设置*/
    'WX_MINI_PROGRAM_APP_ID' => array(
        'name'    => 'AppID(小程序ID)',
        'group'   => '微信小程序',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'WX_MINI_PROGRAM_APP_SECRET' => array(
        'name'    => 'AppSecret(小程序密钥)',
        'group'   => '微信小程序',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    /**************************************************************************************************************/
    /*阿里云短信通道配置*/
    'ALIYUN_SMS_SIGNNAME' => array(
        'name'    => '签名',
        'group'   => '阿里云短信通道',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'ALIYUN_SMS_ACCESSKEY_ID' => array(
        'name'    => 'AccessKey ID',
        'group'   => '阿里云短信通道',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'ALIYUN_SMS_ACCESSKEY_SECRET' => array(
        'name'    => 'AccessKey Secret',
        'group'   => '阿里云短信通道',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'ALIYUN_SMS_CAPTCHA_TEMPLATE' => array(
        'name'    => '验证码短信模板',
        'group'   => '阿里云短信通道',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'ALIYUN_SMS_APPOINT_TO_EXPERT_TEMPLATE' => array(
        'name'    => '预约通知专家短信模板',
        'group'   => '阿里云短信通道',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'ALIYUN_SMS_APPOINT_TO_SUPPORT_TEMPLATE' => array(
        'name'    => '预约通知客服短信模板',
        'group'   => '阿里云短信通道',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
    'ALIYUN_SMS_APPOINT_TO_CUSTOMER_TEMPLATE' => array(
        'name'    => '预约通知客户短信模板',
        'group'   => '阿里云短信通道',
        'editor'  => array('type'=>'textbox','options'=>array('tipPosition'=>'left', 'validType'=>array('nothtml','length[0,255]'))),
        'default' => '',
    ),
);