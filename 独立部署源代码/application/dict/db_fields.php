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
return [
    'subject'=>[
        'id'=>['label'=>'量表ID', 'converter'=>null],
        'type'=>['label'=>'量表类型', 'converter'=>null],
        'name'=>['label'=>'量表名称', 'converter'=>null],
        'subtitle'=>['label'=>'副标题', 'converter'=>null],
        'current_price'=>['label'=>'价格', 'converter'=>null],
        'status'=>['label'=>'状态', 'converter'=>'convertSubjectStatusToText'],
        'expect_finish_time'=>['label'=>'预期完成时间', 'converter'=>null],
        'label'=>['label'=>'轮播|热门|精选', 'converter'=>null],
        'participants_show'=>['label'=>'参与测评人数', 'converter'=>null],
        'image_url'=>['label'=>'量表图片', 'converter'=>null],
        //'subject_desc'=>['label'=>'量表介绍', 'converter'=>null],
        'report_image1'=>['label'=>'专家建议图片-1', 'converter'=>null],
        'report_image2'=>['label'=>'专家建议图片-2', 'converter'=>null],
        'report_image3'=>['label'=>'专家建议图片-3', 'converter'=>null],
        'report_image4'=>['label'=>'专家建议图片-4', 'converter'=>null],
        'report_image5'=>['label'=>'专家建议图片-5', 'converter'=>null],
        'report_image6'=>['label'=>'专家建议图片-6', 'converter'=>null],
        'report_story1'=>['label'=>'专家建议-1', 'converter'=>null],
        'report_story2'=>['label'=>'专家建议-2', 'converter'=>null],
        'report_story3'=>['label'=>'专家建议-3', 'converter'=>null],
        'report_story4'=>['label'=>'专家建议-4', 'converter'=>null],
        'report_story5'=>['label'=>'专家建议-5', 'converter'=>null],
        'report_story6'=>['label'=>'专家建议-6', 'converter'=>null],
        'banner_img'=>['label'=>'轮播图', 'converter'=>null],
        'video_url'=>['label'=>'视频', 'converter'=>null],
        'audio_url'=>['label'=>'音频', 'converter'=>null],
        'report_elements'=>['label'=>'报告组成', 'converter'=>null],
        'rating'=>['label'=>'评价', 'converter'=>null],
        'test_allow_back'=>['label'=>'答题后退', 'converter'=>null],
        'test_allow_view_report'=>['label'=>'用户查看报告', 'converter'=>null],
        'test_allow_answer_empty'=>['label'=>'是否允许答题为空', 'converter'=>null]
    ],
    'expert'=>[
        'id'=>['label'=>'专家ID', 'converter'=>null],
        'cellphone'=>['label'=>'手机号码', 'converter'=>null],
        'real_name'=>['label'=>'真实姓名', 'converter'=>null],
        'workimg_url'=>['label'=>'头像', 'converter'=>null],
        'workplace'=>['label'=>'工作单位', 'converter'=>null],
        'first_job_time'=>['label'=>'从业时间', 'converter'=>null],
        'appoint_fee'=>['label'=>'咨询价格', 'converter'=>null],
        'appoint_review_fee'=>['label'=>'复诊价格', 'converter'=>null],
        'expert_profile'=>['label'=>'个人介绍', 'converter'=>null],
        'expert_quality'=>['label'=>'从业资质', 'converter'=>null],
        'remark'=>['label'=>'备注', 'converter'=>null],
        'consult_quantity'=>['label'=>'咨询经验', 'converter'=>null]
    ],
    'subject_combination'=>[
        'id'=>['label'=>'组合量表ID', 'converter'=>null],
        'name'=>['label'=>'名称', 'converter'=>null],
        'banner'=>['label'=>'图片', 'converter'=>null],
        'description'=>['label'=>'介绍', 'converter'=>null],
        'subjects'=>['label'=>'量表', 'converter'=>null]
    ],
    'survey'=>[
        'id'=>['label'=>'专家ID', 'converter'=>null],
        'name'=>['label'=>'名称', 'converter'=>null],
        'banner'=>['label'=>'图片', 'converter'=>null],
        'description'=>['label'=>'介绍', 'converter'=>null],
        'subjects'=>['label'=>'量表', 'converter'=>null],
        'cfg_free'=>['label'=>'付费策略', 'converter'=>null],
        'cfg_enter_personal_data'=>['label'=>'录入个人资料', 'converter'=>null],
        'cfg_personal_data'=>['label'=>'个人资料项目', 'converter'=>null],
        'cfg_view_report'=>['label'=>'用户查看报告', 'converter'=>null],
    ],
    'admins'=>[
        'admin_id'=>['label'=>'管理员ID', 'converter'=>null],
        'login_name'=>['label'=>'登录名', 'converter'=>null],
        'realname'=>['label'=>'姓名', 'converter'=>null],
        'super_user'=>['label'=>'超级管理员', 'converter'=>null],
        'role_id'=>['label'=>'角色', 'converter'=>null],
    ],
    'admin_role'=>[
        'role_id'=>['label'=>'角色ID', 'converter'=>null],
        'role_name'=>['label'=>'角色名称', 'converter'=>null],
        'description'=>['label'=>'描述', 'converter'=>null]
    ]
];