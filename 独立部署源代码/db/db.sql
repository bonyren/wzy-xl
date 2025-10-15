/*
 Navicat Premium Dump SQL

 Source Server         : wzyer
 Source Server Type    : MySQL
 Source Server Version : 80032 (8.0.32)
 Source Host           : www.wzyer.com:3306
 Source Schema         : wzyer_standalone

 Target Server Type    : MySQL
 Target Server Version : 80032 (8.0.32)
 File Encoding         : 65001

 Date: 01/10/2025 12:00:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role`  (
  `role_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '管理员角色' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES (34, '社媒投流', '否则社交媒体流量投放');
INSERT INTO `admin_role` VALUES (35, '客户测试', '线上客户测试');

-- ----------------------------
-- Table structure for admin_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_menu`;
CREATE TABLE `admin_role_menu`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int UNSIGNED NULL DEFAULT 0,
  `menu_id` int UNSIGNED NULL DEFAULT 0,
  `type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '1:只读,2:读写',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `role_menu`(`role_id` ASC, `menu_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 68 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '管理员角色功能菜单' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_role_menu
-- ----------------------------
INSERT INTO `admin_role_menu` VALUES (4, 34, 55, 1);
INSERT INTO `admin_role_menu` VALUES (5, 34, 69, 1);
INSERT INTO `admin_role_menu` VALUES (6, 34, 29, 1);
INSERT INTO `admin_role_menu` VALUES (7, 34, 78, 1);
INSERT INTO `admin_role_menu` VALUES (38, 35, 55, 2);
INSERT INTO `admin_role_menu` VALUES (39, 35, 70, 2);
INSERT INTO `admin_role_menu` VALUES (40, 35, 62, 2);
INSERT INTO `admin_role_menu` VALUES (41, 35, 69, 2);
INSERT INTO `admin_role_menu` VALUES (42, 35, 29, 2);
INSERT INTO `admin_role_menu` VALUES (43, 35, 51, 2);
INSERT INTO `admin_role_menu` VALUES (44, 35, 52, 2);
INSERT INTO `admin_role_menu` VALUES (45, 35, 49, 2);
INSERT INTO `admin_role_menu` VALUES (46, 35, 78, 2);
INSERT INTO `admin_role_menu` VALUES (47, 35, 87, 2);
INSERT INTO `admin_role_menu` VALUES (48, 35, 81, 2);
INSERT INTO `admin_role_menu` VALUES (49, 35, 82, 2);
INSERT INTO `admin_role_menu` VALUES (50, 35, 3, 1);
INSERT INTO `admin_role_menu` VALUES (51, 35, 4, 2);
INSERT INTO `admin_role_menu` VALUES (52, 35, 5, 2);
INSERT INTO `admin_role_menu` VALUES (53, 35, 30, 2);
INSERT INTO `admin_role_menu` VALUES (54, 35, 40, 2);
INSERT INTO `admin_role_menu` VALUES (55, 35, 17, 2);
INSERT INTO `admin_role_menu` VALUES (56, 35, 56, 2);
INSERT INTO `admin_role_menu` VALUES (57, 35, 88, 2);
INSERT INTO `admin_role_menu` VALUES (58, 35, 36, 2);
INSERT INTO `admin_role_menu` VALUES (59, 35, 38, 2);
INSERT INTO `admin_role_menu` VALUES (60, 35, 71, 2);
INSERT INTO `admin_role_menu` VALUES (61, 35, 72, 2);
INSERT INTO `admin_role_menu` VALUES (62, 35, 83, 2);
INSERT INTO `admin_role_menu` VALUES (63, 35, 63, 2);
INSERT INTO `admin_role_menu` VALUES (64, 35, 90, 2);
INSERT INTO `admin_role_menu` VALUES (65, 35, 91, 2);
INSERT INTO `admin_role_menu` VALUES (66, 35, 92, 2);
INSERT INTO `admin_role_menu` VALUES (67, 35, 93, 2);

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins`  (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `login_name` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `login_password` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `login_encrypt` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `realname` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `email` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `disabled` tinyint NOT NULL DEFAULT 1 COMMENT '1-enabled, 2-disabled',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `password_changed` date NOT NULL DEFAULT '0000-00-00' COMMENT ' 密码修改日期',
  `super_user` tinyint NOT NULL DEFAULT 2 COMMENT '1-super user, 2-common user',
  `role_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色id',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '最后一次登录时间',
  PRIMARY KEY (`admin_id`) USING BTREE,
  UNIQUE INDEX `login_name`(`login_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '管理员' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES (30, 'admin', 'f97d6de3ceed1d432092d3c98d517f97', 'ZlgwIP', '超级管理员', 'admin@sohu.com', 1, '2024-09-26 10:21:15', '2025-10-01 11:55:22', '0000-00-00', 1, 0, '2025-10-01 11:55:22');
INSERT INTO `admins` VALUES (32, 'test', '15ca3ac03accc8b03532a6655beff102', 'xHPtzE', '测试', '', 1, '2024-10-09 17:29:10', '2025-10-01 11:57:02', '2024-10-09', 1, 0, '2025-10-01 11:57:02');

-- ----------------------------
-- Table structure for aio
-- ----------------------------
DROP TABLE IF EXISTS `aio`;
CREATE TABLE `aio`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `computer_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '计算机名',
  `no` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '设备编号',
  `login_time` datetime NULL DEFAULT NULL COMMENT '登陆时间',
  `login_ip` char(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '登录IP',
  `access_token` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '访问令牌',
  `keepalive_time` datetime NULL DEFAULT NULL COMMENT '上次心跳时间',
  `report_title` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '测评报告标题',
  `report_suffix` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '测评报告后缀',
  `enable_print_report` tinyint NOT NULL DEFAULT 0 COMMENT '打印报告有效',
  `ver` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '一体机软件版本号',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE,
  INDEX `access_token`(`access_token` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '一体机' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of aio
-- ----------------------------

-- ----------------------------
-- Table structure for aio_order
-- ----------------------------
DROP TABLE IF EXISTS `aio_order`;
CREATE TABLE `aio_order`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_no` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '测评订单号',
  `aio_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '一体机id',
  `subject_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '量表id',
  `order_amount` double(20, 2) NOT NULL DEFAULT 0.00 COMMENT '测评金额',
  `order_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '测评时间',
  `finish_time` timestamp NULL DEFAULT NULL COMMENT '完成时间',
  `test_items` int NULL DEFAULT 0 COMMENT '已经测试的题目数量',
  `total_items` int NULL DEFAULT 0 COMMENT '测试题目的总数',
  `curr_item` int NOT NULL DEFAULT 0 COMMENT '当前题目Id',
  `items` varchar(4096) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '评估项:itemt1,item2,item3...',
  `result` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '测评结果',
  `finished` int NULL DEFAULT 0 COMMENT '题目是否完成',
  `warning_level` tinyint NOT NULL DEFAULT 0 COMMENT '预警级别，0-未定义，1-绿，2-黄，3-红',
  `item_version` int NOT NULL DEFAULT 0 COMMENT '测评项目版本号',
  `question_form` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '前置问卷',
  `question_answer` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '前置问卷答案',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_no`(`order_no` ASC) USING BTREE,
  INDEX `aio_id`(`aio_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '一体机测评订单' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of aio_order
-- ----------------------------

-- ----------------------------
-- Table structure for attachments
-- ----------------------------
DROP TABLE IF EXISTS `attachments`;
CREATE TABLE `attachments`  (
  `attachment_id` int NOT NULL AUTO_INCREMENT,
  `original_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `save_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `mime_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `size` int NOT NULL DEFAULT 0,
  `attachment_type` int NOT NULL DEFAULT 1,
  `external_id` int NOT NULL DEFAULT 0 COMMENT '关联外键',
  `external_id2` int NOT NULL DEFAULT 0 COMMENT '关联外键2',
  `entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '1-ok, -1-deleted',
  PRIMARY KEY (`attachment_id`) USING BTREE,
  INDEX `idx_eid_type`(`external_id` ASC, `attachment_type` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2286 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '附件' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of attachments
-- ----------------------------

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '分类名称',
  `status` int UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态-0禁用1启用',
  `sort` smallint NOT NULL DEFAULT 100,
  `img_url` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '分类图标',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '量表类别' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES (1, '日常生活', 1, 100, '', '2018-12-02 01:45:55');
INSERT INTO `categories` VALUES (2, '职场人际', 1, 100, '', '2018-12-04 19:51:30');
INSERT INTO `categories` VALUES (3, '爱情情感', 1, 100, '', '2018-12-04 19:51:58');
INSERT INTO `categories` VALUES (4, '婚姻家庭', 1, 100, '', '2018-12-04 19:52:20');
INSERT INTO `categories` VALUES (5, '心理健康', 1, 100, '', '2018-12-07 02:45:36');
INSERT INTO `categories` VALUES (8, '学生校园', 1, 100, '', '2018-12-07 03:12:36');
INSERT INTO `categories` VALUES (9, '儿童亲子', 1, 100, '', '2018-12-07 03:12:36');
INSERT INTO `categories` VALUES (12, '性格个性', 1, 200, '', '2019-03-30 11:20:52');
INSERT INTO `categories` VALUES (13, '能力潜质', 1, 100, '', '2024-02-29 12:01:37');

-- ----------------------------
-- Table structure for combination_order
-- ----------------------------
DROP TABLE IF EXISTS `combination_order`;
CREATE TABLE `combination_order`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `combination_id` int NOT NULL COMMENT '组合测试id',
  `finished` tinyint NOT NULL DEFAULT 0 COMMENT '是否全部测评完成',
  `data` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT 'json业务数据',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE,
  INDEX `combination_id`(`combination_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 417 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '组合量表订单' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for customer
-- ----------------------------
DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '客户编号',
  `openid` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '微信公众号openid',
  `nickname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '微信昵称',
  `headimg_url` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '微信头像',
  `sex` int NULL DEFAULT NULL COMMENT '微信性别',
  `city` varchar(80) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '微信城市',
  `province` varchar(80) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '微信省份',
  `country` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '微信国家',
  `cellphone` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '手机号码',
  `real_name` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '真实姓名',
  `idcard` varchar(24) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '身份证',
  `address` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '住址',
  `register_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  `latest_login_time` timestamp NULL DEFAULT NULL COMMENT '最近登录时间',
  `latest_test_time` timestamp NULL DEFAULT NULL COMMENT '最近测验时间',
  `total_test_quantity` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '测验总次数',
  `total_test_amount` double(20, 2) NOT NULL DEFAULT 0.00 COMMENT '测验总金额',
  `latest_appoint_time` timestamp NULL DEFAULT NULL COMMENT '最近预约医生时间',
  `total_appoint_quantity` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '预约医生总次数',
  `total_appoint_amount` double(20, 2) NOT NULL DEFAULT 0.00 COMMENT '预约医生总金额',
  `remark` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '备注',
  `delete_flag` int NOT NULL DEFAULT 0 COMMENT '1删除',
  `age` tinyint UNSIGNED NULL DEFAULT NULL,
  `profession` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `company` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `job` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `work_age` tinyint UNSIGNED NULL DEFAULT NULL,
  `disease` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `channel_id` tinyint NOT NULL DEFAULT 1 COMMENT '1-公众号，2-一体机，3-医院应用',
  `organization_id` int NOT NULL DEFAULT 0 COMMENT '开放平台组织架构id',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `openid`(`openid` ASC) USING BTREE,
  INDEX `nickname`(`nickname` ASC) USING BTREE,
  INDEX `cellphone`(`cellphone` ASC) USING BTREE,
  INDEX `real_name`(`real_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 238 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '用户表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for dian_goods
-- ----------------------------
DROP TABLE IF EXISTS `dian_goods`;
CREATE TABLE `dian_goods`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject_id` int NOT NULL DEFAULT 0 COMMENT '量表id',
  `token` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '访问令牌',
  `url` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '测评url',
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '1-未使用，2-已使用',
  `entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `token`(`token` ASC) USING BTREE,
  INDEX `subject_id`(`subject_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12975 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '电商店铺测评商品' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for dian_order
-- ----------------------------
DROP TABLE IF EXISTS `dian_order`;
CREATE TABLE `dian_order`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_no` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '评估订单号',
  `goods_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `order_amount` double(20, 2) NOT NULL DEFAULT 0.00 COMMENT '评估金额',
  `order_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '评估时间',
  `finish_time` timestamp NULL DEFAULT NULL COMMENT '完成时间',
  `test_items` int NULL DEFAULT 0 COMMENT '已经测试的题目数量',
  `total_items` int NULL DEFAULT 0 COMMENT '测试题目的总数',
  `curr_item` int NOT NULL DEFAULT 0 COMMENT '当前题目Id',
  `items` varchar(4096) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '评估项:itemt1,item2,item3...',
  `result` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '评估结果',
  `finished` int NULL DEFAULT 0 COMMENT '题目是否完成',
  `warning_level` tinyint NOT NULL DEFAULT 0 COMMENT '预警级别，0-未定义，1-绿，2-黄，3-红',
  `item_version` int NOT NULL DEFAULT 0 COMMENT '测评项目版本号',
  `question_form` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '前置问卷',
  `question_answer` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '前置问卷答案',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_no`(`order_no` ASC) USING BTREE,
  INDEX `goods_id`(`goods_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2390 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '电商店铺测评订单' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for event_logs
-- ----------------------------
DROP TABLE IF EXISTS `event_logs`;
CREATE TABLE `event_logs`  (
  `event_log_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL DEFAULT 0,
  `severity` tinyint NOT NULL DEFAULT 3 COMMENT '1-error,2-warning,3-info',
  `entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `entry` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`event_log_id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 338 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '系统事件日志' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for expert
-- ----------------------------
DROP TABLE IF EXISTS `expert`;
CREATE TABLE `expert`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '专家编号',
  `wxuser_id` int NULL DEFAULT NULL COMMENT '微信账号信息',
  `cellphone` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '手机号码',
  `real_name` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '真实姓名',
  `workimg_url` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '工作照链接',
  `workplace` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '工作单位',
  `register_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  `first_job_time` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '从业时间',
  `latest_appoint_time` timestamp NULL DEFAULT NULL COMMENT '最近预约时间',
  `total_appoint_quantity` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '预约总次数',
  `total_appoint_amount` double(20, 2) NOT NULL DEFAULT 0.00 COMMENT '预约总金额',
  `appoint_fee` double(20, 2) NOT NULL DEFAULT 0.00 COMMENT '预约费用',
  `appoint_review_fee` double(20, 2) NOT NULL DEFAULT 0.00 COMMENT '复诊费用',
  `expert_profile` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '专家简介',
  `expert_quality` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '从业资质',
  `remark` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '备注',
  `delete_flag` int NOT NULL DEFAULT 0 COMMENT '1删除',
  `consult_quantity` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '咨询经验',
  `qrcode` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '二维码',
  `status` int NULL DEFAULT 1 COMMENT '状态(1.草稿；2.发布；3.中止服务)',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `wxuser_id`(`wxuser_id` ASC) USING BTREE,
  INDEX `real_name`(`real_name` ASC) USING BTREE,
  INDEX `cellphone`(`cellphone` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 56 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '专家表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for expert_appoint_time
-- ----------------------------
DROP TABLE IF EXISTS `expert_appoint_time`;
CREATE TABLE `expert_appoint_time`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `expert_id` int UNSIGNED NOT NULL COMMENT '专家编号',
  `week_day` int UNSIGNED NOT NULL COMMENT '周期：0周日，1周一，2周二，3周三，4周四，5周五，6周六',
  `appoint_time` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '预约时间段:8:00-9:00',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `expert_day`(`expert_id` ASC, `week_day` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3050 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '专家可预约时间表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for expert_category_relate
-- ----------------------------
DROP TABLE IF EXISTS `expert_category_relate`;
CREATE TABLE `expert_category_relate`  (
  `expert_id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`expert_id`, `category_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '专家评估分类关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for expert_field
-- ----------------------------
DROP TABLE IF EXISTS `expert_field`;
CREATE TABLE `expert_field`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `field` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '领域',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '擅长领域' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of expert_field
-- ----------------------------
INSERT INTO `expert_field` VALUES (4, '情绪管理领域');
INSERT INTO `expert_field` VALUES (5, '个人成长领域');
INSERT INTO `expert_field` VALUES (6, '心理健康领域');

-- ----------------------------
-- Table structure for expert_field_item
-- ----------------------------
DROP TABLE IF EXISTS `expert_field_item`;
CREATE TABLE `expert_field_item`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `field_id` int NOT NULL DEFAULT 0 COMMENT '领域',
  `field_item` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '细分领域',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '擅长细分领域' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of expert_field_item
-- ----------------------------
INSERT INTO `expert_field_item` VALUES (5, 4, '婚姻家庭');
INSERT INTO `expert_field_item` VALUES (6, 4, '情绪困扰');
INSERT INTO `expert_field_item` VALUES (10, 5, '人际关系');
INSERT INTO `expert_field_item` VALUES (11, 5, '个人成长');
INSERT INTO `expert_field_item` VALUES (13, 5, '职场发展');
INSERT INTO `expert_field_item` VALUES (14, 6, '神经症');
INSERT INTO `expert_field_item` VALUES (15, 6, '性心理');
INSERT INTO `expert_field_item` VALUES (19, 4, '恋爱情感');
INSERT INTO `expert_field_item` VALUES (20, 6, '心理健康类');
INSERT INTO `expert_field_item` VALUES (21, 5, '亲子教育');

-- ----------------------------
-- Table structure for expert_field_relate
-- ----------------------------
DROP TABLE IF EXISTS `expert_field_relate`;
CREATE TABLE `expert_field_relate`  (
  `expert_id` int UNSIGNED NOT NULL,
  `field_id` int UNSIGNED NOT NULL,
  `field_item_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`expert_id`, `field_id`, `field_item_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '专家擅长领域关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of expert_field_relate
-- ----------------------------

-- ----------------------------
-- Table structure for expert_order
-- ----------------------------
DROP TABLE IF EXISTS `expert_order`;
CREATE TABLE `expert_order`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_no` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '预约订单号',
  `customer_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '客户账号',
  `expert_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '专家编号',
  `order_amount` double(20, 2) NOT NULL DEFAULT 0.00 COMMENT '预约金额',
  `order_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '订单时间',
  `appoint_date` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '预约日期',
  `appoint_time` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '预约时间',
  `appoint_duration` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '预约时长',
  `appoint_mode` tinyint NOT NULL DEFAULT 1 COMMENT '1-面对面，2-视频',
  `finish_time` timestamp NULL DEFAULT NULL COMMENT '完成时间',
  `status` int NOT NULL DEFAULT 1 COMMENT '预约状态：1待确认，2已预约，3已完成、4已取消',
  `linkman` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '联系人',
  `cellphone` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '手机号码',
  `remark` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '预约备注信息',
  `result` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '评估结果',
  `pay_time` timestamp NULL DEFAULT NULL COMMENT '支付时间',
  `notify_time` timestamp NULL DEFAULT NULL COMMENT '支付通知时间',
  `pay_status` int NOT NULL DEFAULT 0 COMMENT '支付状态',
  `pay_desc` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '支付描述',
  `prepay_id` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '预支付ID',
  `refund_order_no` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '取消预约退款订单号',
  `refund_amount` double(20, 2) NOT NULL DEFAULT 0.00 COMMENT '客户取消预约的退款',
  `refund_time` timestamp NULL DEFAULT NULL COMMENT '退款时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_no`(`order_no` ASC) USING BTREE,
  INDEX `customer_id`(`customer_id` ASC) USING BTREE,
  INDEX `expert_id`(`expert_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '专家预约订单' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for expert_target
-- ----------------------------
DROP TABLE IF EXISTS `expert_target`;
CREATE TABLE `expert_target`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `target` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '服务对象',
  `remark` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '服务对象列表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of expert_target
-- ----------------------------
INSERT INTO `expert_target` VALUES (1, '青年人', NULL);
INSERT INTO `expert_target` VALUES (2, '中年人', NULL);
INSERT INTO `expert_target` VALUES (3, '老年人', NULL);
INSERT INTO `expert_target` VALUES (5, '留学生', NULL);
INSERT INTO `expert_target` VALUES (6, '职场人', NULL);
INSERT INTO `expert_target` VALUES (7, '孕产妇', NULL);
INSERT INTO `expert_target` VALUES (8, '性少数人群', NULL);
INSERT INTO `expert_target` VALUES (9, '精神康复者', NULL);
INSERT INTO `expert_target` VALUES (10, '吸毒者', NULL);
INSERT INTO `expert_target` VALUES (11, '儿童', NULL);
INSERT INTO `expert_target` VALUES (12, '青少年', NULL);
INSERT INTO `expert_target` VALUES (13, '伴侣/夫妻', NULL);

-- ----------------------------
-- Table structure for expert_target_relate
-- ----------------------------
DROP TABLE IF EXISTS `expert_target_relate`;
CREATE TABLE `expert_target_relate`  (
  `expert_id` int UNSIGNED NOT NULL,
  `target_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`expert_id`, `target_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '专家服务对象关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for job_queue
-- ----------------------------
DROP TABLE IF EXISTS `job_queue`;
CREATE TABLE `job_queue`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `scheduler_id` int NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `target` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `execute_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `execute_end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint NOT NULL DEFAULT 0,
  `result` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `message` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `client` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `scheduler_id`(`scheduler_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 242537 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '后台计划任务执行队列' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for login_logs
-- ----------------------------
DROP TABLE IF EXISTS `login_logs`;
CREATE TABLE `login_logs`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `userid` int NOT NULL DEFAULT 0,
  `usertype` tinyint NOT NULL DEFAULT 1,
  `useragent` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `device` tinyint NOT NULL DEFAULT 1,
  `ip` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2313 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '管理员登录日志' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `level` tinyint NOT NULL DEFAULT 1 COMMENT '层级(1,2,3)',
  `pid` smallint NOT NULL DEFAULT 0 COMMENT '父id',
  `c` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '控制器',
  `a` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '方法',
  `params` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT 'url附加参数',
  `icon_cls` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT 'icon样式',
  `order_id` int NOT NULL DEFAULT 0 COMMENT '排序号',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pid`(`pid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 95 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '系统功能菜单' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (2, '系统', 1, 0, '', '', '', 'fa fa-caret-right', 1000);
INSERT INTO `menu` VALUES (3, '管理员', 2, 2, 'Admins', 'admins', '', 'icons-point', 0);
INSERT INTO `menu` VALUES (4, '管理员角色', 2, 2, 'AdminRole', 'adminRole', '', 'icons-point', 5);
INSERT INTO `menu` VALUES (5, '系统设置', 2, 2, 'System', 'setting', '', 'icons-point', 10);
INSERT INTO `menu` VALUES (8, '专家', 1, 0, '', '', '', 'fa fa-caret-right', 3);
INSERT INTO `menu` VALUES (17, '菜单管理', 2, 2, 'Menus', 'index', 'type=1', 'icons-point', 20);
INSERT INTO `menu` VALUES (29, '专家列表', 2, 8, 'Expert', 'index', '', 'icons-point', 20);
INSERT INTO `menu` VALUES (30, '数据配置', 2, 2, 'Config', 'index', '', 'icons-point', 11);
INSERT INTO `menu` VALUES (36, '登录日志', 2, 2, 'LoginLogs', 'index', '', 'icons-point', 100);
INSERT INTO `menu` VALUES (38, '变更日志', 2, 2, 'OperationLogs', 'index', '', 'icons-point', 200);
INSERT INTO `menu` VALUES (40, '计划任务', 2, 2, 'Schedulers', 'index', '', 'icons-point', 15);
INSERT INTO `menu` VALUES (48, '用户', 1, 0, '', '', '', 'fa fa-caret-right', 5);
INSERT INTO `menu` VALUES (49, '用户管理', 2, 48, 'Customer', 'index', '', 'icons-point', 0);
INSERT INTO `menu` VALUES (50, '订单', 1, 0, '', '', '', 'fa fa-caret-right', 4);
INSERT INTO `menu` VALUES (51, '预约订单', 2, 50, 'AppointOrder', 'index', '', 'icons-point', 0);
INSERT INTO `menu` VALUES (52, '测评订单', 2, 50, 'SubjectOrder', 'orders', '', 'icons-point', 0);
INSERT INTO `menu` VALUES (53, '量表', 1, 0, '', '', '', 'fa fa-caret-right', 1);
INSERT INTO `menu` VALUES (55, '心理量表', 2, 53, 'Subject', 'index', 'type=1', 'icons-point', 0);
INSERT INTO `menu` VALUES (56, '微信菜单', 2, 2, 'Menus', 'wxMenuList', '', 'icons-point', 25);
INSERT INTO `menu` VALUES (62, '组合测评', 2, 53, 'SubjectCombination', 'index', '', 'icons-point', 100);
INSERT INTO `menu` VALUES (63, '数据库备份', 1, 2, 'System', 'dbBackups', '', 'icons-point', 800);
INSERT INTO `menu` VALUES (68, '普查', 1, 0, '', '', '', 'fa fa-caret-right', 2);
INSERT INTO `menu` VALUES (69, '普查列表', 1, 68, 'Survey', 'index', '', 'icons-point', 0);
INSERT INTO `menu` VALUES (70, '健康量表', 1, 53, 'Subject', 'index', 'type=2', 'icons-point', 10);
INSERT INTO `menu` VALUES (71, '系统异常', 1, 2, 'System', 'sysErrExp', '', 'icons-point', 300);
INSERT INTO `menu` VALUES (72, '慢查询', 1, 2, 'System', 'dbSlowQuery', '', 'icons-point', 500);
INSERT INTO `menu` VALUES (77, '电商', 1, 0, '', '', '', 'fa fa-caret-right', 500);
INSERT INTO `menu` VALUES (78, '商品列表', 1, 77, 'Dian', 'goods', '', 'icons-point', 0);
INSERT INTO `menu` VALUES (80, '小程序', 1, 0, '', '', '', 'fa fa-caret-right', 600);
INSERT INTO `menu` VALUES (81, '用户', 1, 80, 'UniApp', 'users', '', 'icons-point', 0);
INSERT INTO `menu` VALUES (82, '订单', 1, 80, 'UniApp', 'orders', '', 'icons-point', 10);
INSERT INTO `menu` VALUES (83, '报告素材库', 1, 2, 'ReportLibrary', 'index', '', 'icons-point', 600);
INSERT INTO `menu` VALUES (86, '咨询室', 1, 0, '', '', '', 'fa fa-caret-right', 550);
INSERT INTO `menu` VALUES (87, '设置', 1, 86, 'Studio', 'preview', '', 'icons-point', 0);
INSERT INTO `menu` VALUES (88, '组织架构', 1, 2, 'Organization', 'index', '', 'icons-point', 50);
INSERT INTO `menu` VALUES (89, '回收站', 1, 0, '', '', '', 'fa fa-caret-right', 2000);
INSERT INTO `menu` VALUES (90, '量表', 1, 89, 'Trash', 'subjects', '', 'icons-point', 0);
INSERT INTO `menu` VALUES (91, '专家', 1, 89, 'Trash', 'experts', '', 'icons-point', 10);
INSERT INTO `menu` VALUES (92, '组合量表', 1, 89, 'Trash', 'subjectCombinations', '', 'icons-point', 20);
INSERT INTO `menu` VALUES (93, '普查', 1, 89, 'Trash', 'surveies', '', 'icons-point', 30);
INSERT INTO `menu` VALUES (94, '软件授权', 1, 2, 'System', 'license', '', 'icons-point', 1000);

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages`  (
  `message_id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int NOT NULL DEFAULT 0,
  `title` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `category` tinyint NOT NULL DEFAULT 0,
  `is_read` tinyint NOT NULL DEFAULT 0,
  `read_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`) USING BTREE,
  INDEX `admin_id`(`admin_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 532 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '系统消息' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of messages
-- ----------------------------

-- ----------------------------
-- Table structure for operation_logs
-- ----------------------------
DROP TABLE IF EXISTS `operation_logs`;
CREATE TABLE `operation_logs`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` tinyint NOT NULL DEFAULT 0 COMMENT '分类',
  `record_id` int NOT NULL DEFAULT 0 COMMENT '数据实体id',
  `title` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '描述',
  `type` tinyint NOT NULL DEFAULT 1 COMMENT '1-add, 2-update, 3-delete',
  `changed_by` char(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '操作者',
  `ip` char(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '操作ip',
  `device` tinyint NOT NULL DEFAULT 1 COMMENT '1-computer, 2-mobile',
  `channel` tinyint NOT NULL DEFAULT 0 COMMENT '渠道',
  `entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `record_id`(`record_id` ASC) USING BTREE,
  FULLTEXT INDEX `title_content`(`title`, `content`)
) ENGINE = InnoDB AUTO_INCREMENT = 4856 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '系统操作日志' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for organization
-- ----------------------------
DROP TABLE IF EXISTS `organization`;
CREATE TABLE `organization`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int UNSIGNED NOT NULL DEFAULT 0,
  `name` char(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `order` int NOT NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_parent`(`parent_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '组织架构' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for progress_logs
-- ----------------------------
DROP TABLE IF EXISTS `progress_logs`;
CREATE TABLE `progress_logs`  (
  `progress_log_id` int NOT NULL AUTO_INCREMENT,
  `category` tinyint NOT NULL DEFAULT 0,
  `occur_date` date NOT NULL DEFAULT '0000-00-00',
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `entry` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `entered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `external_id` int UNSIGNED NOT NULL DEFAULT 0,
  `show_timeline` tinyint NOT NULL DEFAULT 0 COMMENT '是否展示到时间轴',
  `admin_id` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`progress_log_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 53 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '进度日志' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of progress_logs
-- ----------------------------

-- ----------------------------
-- Table structure for redis
-- ----------------------------
DROP TABLE IF EXISTS `redis`;
CREATE TABLE `redis`  (
  `key` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `value` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for schedulers
-- ----------------------------
DROP TABLE IF EXISTS `schedulers`;
CREATE TABLE `schedulers`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `job` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `interval` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `date_time_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_time_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_run` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `disabled` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-enable, 1-disabled',
  `deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active, 1-deleted',
  `created_by` int UNSIGNED NOT NULL DEFAULT 0,
  `entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '后台计划任务' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of schedulers
-- ----------------------------
INSERT INTO `schedulers` VALUES (10, 'backupDB', '数据库备份', '0 2 * * *', '2021-07-30 12:17:00', '0000-00-00 00:00:00', '2025-10-01 02:00:02', 0, 0, 25, '2021-07-30 12:18:48');
INSERT INTO `schedulers` VALUES (11, 'cleanServer', '清理服务器', '0 3 * * *', '2021-07-30 12:19:00', '0000-00-00 00:00:00', '2025-10-01 03:00:01', 0, 0, 25, '2021-07-30 12:19:25');
INSERT INTO `schedulers` VALUES (13, 'detectSlowQuery', '数据库慢查询检测', '0 */1 * * *', '2023-05-18 11:10:00', '0000-00-00 00:00:00', '2025-10-01 12:00:02', 0, 0, 25, '2023-05-18 11:11:20');
INSERT INTO `schedulers` VALUES (15, 'generateReportPdfs', '生成测评报告pdf文件', '*/5 * * * *', '2025-09-11 22:49:00', '0000-00-00 00:00:00', '2025-10-01 11:35:02', 0, 0, 30, '2025-09-11 22:50:13');

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting`  (
  `key` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `value` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '系统设置' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for slow_query
-- ----------------------------
DROP TABLE IF EXISTS `slow_query`;
CREATE TABLE `slow_query`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `occur_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `occur_user` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `occur_thread` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `query_time` decimal(18, 6) NOT NULL DEFAULT 0.000000,
  `lock_time` decimal(18, 6) NOT NULL DEFAULT 0.000000,
  `rows_sent` int NOT NULL DEFAULT 0,
  `rows_examined` int NOT NULL DEFAULT 0,
  `occur_sql` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT 0 COMMENT '0-pending, 1-fixed',
  `fixed_time` datetime NULL DEFAULT NULL,
  `fixed_user` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `occur_time`(`occur_time` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 53 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '数据库慢查询' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for studio
-- ----------------------------
DROP TABLE IF EXISTS `studio`;
CREATE TABLE `studio`  (
  `key` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  INDEX `key`(`key` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '工作室设置' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Records of studio
-- ----------------------------
INSERT INTO `studio` VALUES ('store_name', '***心理咨询公司');
INSERT INTO `studio` VALUES ('store_desc', '***心理咨询公司');
INSERT INTO `studio` VALUES ('store_contact', '13511110000');
INSERT INTO `studio` VALUES ('store_index_sections', '1,2,3');
INSERT INTO `studio` VALUES ('store_bottom_tabs', '1,2,3,4');
-- ----------------------------
-- Table structure for subject
-- ----------------------------
DROP TABLE IF EXISTS `subject`;
CREATE TABLE `subject`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` tinyint NOT NULL DEFAULT 1 COMMENT '1-标准，2-临时',
  `sn` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT 'sn',
  `name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `subtitle` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '副标题',
  `current_price` double(20, 2) NULL DEFAULT 0.00 COMMENT '现价',
  `sort` int UNSIGNED NOT NULL DEFAULT 1000 COMMENT '排序-数字越小越靠前',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `status` int NULL DEFAULT 1 COMMENT '状态（1.草稿；2.发布；3.中止服务）',
  `expect_finish_time` int NOT NULL DEFAULT 8 COMMENT '预期完成时间',
  `label` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '标签',
  `participants` int NOT NULL DEFAULT 0 COMMENT '参与测评人数',
  `participants_show` int NOT NULL DEFAULT 0 COMMENT '参与测评人数(展示用)',
  `total_amount` double(20, 2) NOT NULL DEFAULT 0.00 COMMENT '评估总金额',
  `image_url` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '图片链接',
  `subject_desc` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '项目介绍',
  `subject_tip` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '测前提示',
  `items` int NULL DEFAULT 0 COMMENT '评估量表题目数',
  `report_image1` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '报告图片1',
  `report_image2` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '报告图片2',
  `report_image3` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '报告图片3',
  `report_image4` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '报告图片4',
  `report_image5` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '报告图片5',
  `report_image6` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '报告图片6',
  `delete_flag` int NOT NULL DEFAULT 0 COMMENT '1删除',
  `report_story1` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '报告小故事1',
  `report_story2` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '报告小故事2',
  `report_story3` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '报告小故事3',
  `report_story4` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '报告小故事4',
  `report_story5` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '报告小故事5',
  `report_story6` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '报告小故事6',
  `banner_img` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '轮播图',
  `video_url` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '音视频路径',
  `audio_url` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '音频路径',
  `report_elements` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '10,20,30,40' COMMENT '报告组成',
  `qrcode` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '二维码',
  `rating` tinyint UNSIGNED NOT NULL DEFAULT 5 COMMENT '评价, 1-10之间',
  `test_allow_back` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '答题的过程中是否允许后退',
  `test_allow_answer_empty` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否允许答题为空，直接跳过',
  `statistic_weight_min` double(11, 2) NOT NULL DEFAULT 0.00 COMMENT '分数最小',
  `statistic_weight_max` double(11, 2) NOT NULL DEFAULT 0.00 COMMENT '分数最大',
  `standard_weight_min` double(10, 2) NULL DEFAULT NULL COMMENT '标准分最小值',
  `standard_weight_max` double(10, 2) NULL DEFAULT NULL COMMENT '标准分最大值',
  `uni_app` tinyint NOT NULL DEFAULT 0 COMMENT '是否应用于百度app',
  `item_version` int NOT NULL DEFAULT 0 COMMENT '测评项目版本号',
  `question_form` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '前置问卷',
  `report_generator` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '自定义报告生成器',
  `report_template` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '自定义报告模板',
  `report_demo_images` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '示例报告图片',
  `test_option_col_layout` tinyint NULL DEFAULT 1 COMMENT '答题界面每行的选项数',
  `test_allow_view_report` tinyint NOT NULL DEFAULT 1 COMMENT '是否允许用户查看报告,1:允许，0:不允许',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `name`(`name` ASC) USING BTREE,
  INDEX `sn`(`sn` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2268 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '评估量表项目' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for subject_category_relate
-- ----------------------------
DROP TABLE IF EXISTS `subject_category_relate`;
CREATE TABLE `subject_category_relate`  (
  `subject_id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`subject_id`, `category_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '量表分类关联表' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for subject_collect
-- ----------------------------
DROP TABLE IF EXISTS `subject_collect`;
CREATE TABLE `subject_collect`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `customer_id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `customer_subject`(`customer_id` ASC, `subject_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 318 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '我的收藏' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of subject_collect
-- ----------------------------

-- ----------------------------
-- Table structure for subject_combination
-- ----------------------------
DROP TABLE IF EXISTS `subject_combination`;
CREATE TABLE `subject_combination`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `banner` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `qrcode` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '二维码',
  `description` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '说明',
  `subjects` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '量表id',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  `delete_flag` int NOT NULL DEFAULT 0 COMMENT '1删除',
  `status` int NULL DEFAULT 1 COMMENT '状态(1.草稿；2.发布；3.中止服务)',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `name`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '测评量表' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for subject_item
-- ----------------------------
DROP TABLE IF EXISTS `subject_item`;
CREATE TABLE `subject_item`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '项目信息',
  `type` int UNSIGNED NOT NULL DEFAULT 1 COMMENT '类型：1单选，2多选, 3填写',
  `item` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '评估题目描述',
  `item_2` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '评估题目描述2',
  `option_1` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项1',
  `option_2` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项2',
  `option_3` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项3',
  `option_4` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项4',
  `option_5` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项5',
  `option_6` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项6',
  `option_7` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项7',
  `option_8` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项8',
  `option_9` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项9',
  `option_10` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项10',
  `option_11` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项11',
  `option_12` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项12',
  `remark` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '题目说明',
  `weight_1` int NULL DEFAULT NULL COMMENT '选项1的权重',
  `weight_2` int NULL DEFAULT NULL COMMENT '选项2的权重',
  `weight_3` int NULL DEFAULT NULL COMMENT '选项3的权重',
  `weight_4` int NULL DEFAULT NULL COMMENT '选项4的权重',
  `weight_5` int NULL DEFAULT NULL COMMENT '选项5的权重',
  `weight_6` int NULL DEFAULT NULL COMMENT '选项6的权重',
  `weight_7` int NULL DEFAULT NULL COMMENT '选项7的权重',
  `weight_8` int NULL DEFAULT NULL COMMENT '选项8的权重',
  `weight_9` int NULL DEFAULT NULL COMMENT '选项9的权重',
  `weight_10` int NULL DEFAULT NULL COMMENT '选项10的权重',
  `weight_11` int NULL DEFAULT NULL COMMENT '选项11的权重',
  `weight_12` int NULL DEFAULT NULL COMMENT '选项12的权重',
  `nature_1` int NULL DEFAULT NULL COMMENT '选项1的性质-0:未定,1-阴性,2-阳性',
  `nature_2` int NULL DEFAULT NULL COMMENT '选项2的性质-0:未定,1-阴性,2-阳性',
  `nature_3` int NULL DEFAULT NULL COMMENT '选项3的性质-0:未定,1-阴性,2-阳性',
  `nature_4` int NULL DEFAULT NULL COMMENT '选项4的性质-0:未定,1-阴性,2-阳性',
  `nature_5` int NULL DEFAULT NULL COMMENT '选项5的性质-0:未定,1-阴性,2-阳性',
  `nature_6` int NULL DEFAULT NULL COMMENT '选项6的性质-0:未定,1-阴性,2-阳性',
  `nature_7` int NULL DEFAULT NULL COMMENT '选项7的性质-0:未定,1-阴性,2-阳性',
  `nature_8` int NULL DEFAULT NULL COMMENT '选项8的性质-0:未定,1-阴性,2-阳性',
  `nature_9` int NULL DEFAULT NULL COMMENT '选项9的性质-0:未定,1-阴性,2-阳性',
  `nature_10` int NULL DEFAULT NULL COMMENT '选项10的性质-0:未定,1-阴性,2-阳性',
  `nature_11` int NULL DEFAULT NULL COMMENT '选项11的性质-0:未定,1-阴性,2-阳性',
  `nature_12` int NULL DEFAULT NULL COMMENT '选项12的性质-0:未定,1-阴性,2-阳性',
  `sort` int NULL DEFAULT 1000 COMMENT '排序',
  `image` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '题目图片',
  `image_1` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项1的图片',
  `image_2` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项2的图片',
  `image_3` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项3的图片',
  `image_4` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项4的图片',
  `image_5` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项5的图片',
  `image_6` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项6的图片',
  `image_7` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项7的图片',
  `image_8` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项8的图片',
  `image_9` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项9的图片',
  `image_10` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项10的图片',
  `image_11` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项11的图片',
  `image_12` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '选项12的图片',
  `tag` enum('none','age','sex') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'none' COMMENT '标签',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `subject_id`(`subject_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 102110 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '评测科目题目' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for subject_item_standard
-- ----------------------------
DROP TABLE IF EXISTS `subject_item_standard`;
CREATE TABLE `subject_item_standard`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` int NOT NULL DEFAULT 0 COMMENT '评估量表项目ID',
  `item_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '评估问题项ID',
  `standard_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '评估标准ID',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `subject_item_standard`(`subject_id` ASC, `item_id` ASC, `standard_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 88595 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '评测科目问题权重表' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for subject_order
-- ----------------------------
DROP TABLE IF EXISTS `subject_order`;
CREATE TABLE `subject_order`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_no` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '评估订单号',
  `customer_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '客户账号',
  `subject_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '评估项目信息',
  `order_amount` double(20, 2) NOT NULL DEFAULT 0.00 COMMENT '评估金额',
  `order_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '评估时间',
  `finish_time` timestamp NULL DEFAULT NULL COMMENT '完成时间',
  `test_items` int NULL DEFAULT 0 COMMENT '已经测试的题目数量',
  `total_items` int NULL DEFAULT 0 COMMENT '测试题目的总数',
  `curr_item` int NOT NULL DEFAULT 0 COMMENT '当前题目Id',
  `items` varchar(4096) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '评估项:itemt1,item2,item3...',
  `result` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '评估结果',
  `pay_time` timestamp NULL DEFAULT NULL COMMENT '支付时间',
  `notify_time` timestamp NULL DEFAULT NULL COMMENT '支付通知时间',
  `pay_status` int NOT NULL DEFAULT 0 COMMENT '支付状态',
  `pay_desc` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '支付描述',
  `prepay_id` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '预支付ID',
  `finished` int NULL DEFAULT 0 COMMENT '题目是否完成',
  `channel_id` int UNSIGNED NOT NULL DEFAULT 1 COMMENT '渠道编号：1微信，2一体机',
  `cb_order_id` int NOT NULL DEFAULT 0 COMMENT '组合测试id',
  `survey_order_id` int NOT NULL DEFAULT 0 COMMENT '普查测试id',
  `warning_level` tinyint NOT NULL DEFAULT 0 COMMENT '预警级别，0-未定义，1-绿，2-黄，3-红',
  `item_version` int NOT NULL DEFAULT 0 COMMENT '测评项目版本号',
  `question_form` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '前置问卷',
  `question_answer` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '前置问卷答案',
  `report_pdf` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT 'pdf报告',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_no`(`order_no` ASC) USING BTREE,
  INDEX `subject_id`(`subject_id` ASC) USING BTREE,
  INDEX `cb_order_id`(`cb_order_id` ASC) USING BTREE,
  INDEX `survey_order_id`(`survey_order_id` ASC) USING BTREE,
  INDEX `customer_id`(`customer_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 760 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '客户评估订单' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for subject_standard
-- ----------------------------
DROP TABLE IF EXISTS `subject_standard`;
CREATE TABLE `subject_standard`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '项目信息',
  `latitude` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '纬度',
  `remark` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '备注',
  `statistic_weight_min` double(11, 2) NOT NULL DEFAULT 0.00 COMMENT '分数最小',
  `statistic_weight_max` double(11, 2) NOT NULL DEFAULT 0.00 COMMENT '分数最大',
  `standard_weight_min` double(10, 2) NULL DEFAULT NULL COMMENT '标准分最小值',
  `standard_weight_max` double(10, 2) NULL DEFAULT NULL COMMENT '标准分最大值',
  `sort` int NULL DEFAULT 1000 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `subject_id`(`subject_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7802 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '评测科目标准' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for subject_standard_latitude
-- ----------------------------
DROP TABLE IF EXISTS `subject_standard_latitude`;
CREATE TABLE `subject_standard_latitude`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '项目信息',
  `standard_id` int NOT NULL COMMENT '维度id',
  `latitude` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '纬度',
  `weight_type` tinyint NOT NULL DEFAULT 1 COMMENT '1-原始分，2-标准分',
  `expression` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '分数统计表达式',
  `expression_json` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '分数统计表达式json',
  `stand_desc` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '评估说明',
  `remark` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '备注',
  `warning_level` tinyint NOT NULL DEFAULT 0 COMMENT '预警级别，0-未定义，1-绿，2-黄，3-红',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `subject_standard`(`subject_id` ASC, `standard_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26492 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '评测科目标准范围' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for subject_standard_mapping
-- ----------------------------
DROP TABLE IF EXISTS `subject_standard_mapping`;
CREATE TABLE `subject_standard_mapping`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '量表id',
  `standard_id` int NOT NULL DEFAULT 0 COMMENT '维度id, 0为整体',
  `weight_min` double(11, 2) NOT NULL DEFAULT 0.00 COMMENT '原始分最小值',
  `weight_max` double(11, 2) NOT NULL DEFAULT 0.00 COMMENT '原始分最大值',
  `standard_weight_min` double(11, 2) NOT NULL DEFAULT 0.00 COMMENT '标准分最小值',
  `standard_weight_max` double(11, 2) NOT NULL DEFAULT 0.00 COMMENT '标准分最大值',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `subject_standard`(`subject_id` ASC, `standard_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '量表原始分标准分区间映射' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of subject_standard_mapping
-- ----------------------------

-- ----------------------------
-- Table structure for survey
-- ----------------------------
DROP TABLE IF EXISTS `survey`;
CREATE TABLE `survey`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `banner` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `qrcode` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '二维码',
  `description` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '说明',
  `subjects` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '量表id',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  `delete_flag` int NOT NULL DEFAULT 0 COMMENT '1删除',
  `cfg_free` tinyint NOT NULL DEFAULT 1 COMMENT '是否免费普查',
  `cfg_enter_personal_data` tinyint NOT NULL DEFAULT 1 COMMENT '是否录入个人资料',
  `cfg_personal_data` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '个人资料项目配置',
  `cfg_view_report` tinyint NOT NULL DEFAULT 0 COMMENT '是否允许用户查看报告',
  `status` int NULL DEFAULT 1 COMMENT '状态(1.草稿；2.发布；3.中止服务)',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `name`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 66 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '普查活动' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for survey_order
-- ----------------------------
DROP TABLE IF EXISTS `survey_order`;
CREATE TABLE `survey_order`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `survey_id` int NOT NULL COMMENT '普查id',
  `finished` tinyint NOT NULL DEFAULT 0 COMMENT '是否全部测评完成',
  `data` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '量表测试状态',
  `personal_data` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '个人资料数据',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `survey_organization_id` int NOT NULL DEFAULT 0 COMMENT '普查组织架构id',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `survey_id`(`survey_id` ASC) USING BTREE,
  INDEX `survey_organization_id`(`survey_organization_id` ASC) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 448 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '普查订单' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for survey_organization
-- ----------------------------
DROP TABLE IF EXISTS `survey_organization`;
CREATE TABLE `survey_organization`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `survey_id` int UNSIGNED NOT NULL DEFAULT 0,
  `parent_id` int UNSIGNED NOT NULL DEFAULT 0,
  `name` char(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `order` int NOT NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_parent`(`survey_id` ASC, `parent_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 56 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '普查组织架构' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of survey_organization
-- ----------------------------

-- ----------------------------
-- Table structure for sys_err_exp
-- ----------------------------
DROP TABLE IF EXISTS `sys_err_exp`;
CREATE TABLE `sys_err_exp`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `severity` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '错误级别',
  `message` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `file` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `line` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `trace` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '1-pending, 2-fixed',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 217 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '系统运行异常' ROW_FORMAT = DYNAMIC;
-- ----------------------------
-- Table structure for uni_app_orders
-- ----------------------------
DROP TABLE IF EXISTS `uni_app_orders`;
CREATE TABLE `uni_app_orders`  (
  `order_no` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '评估订单号',
  `user_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '客户账号',
  `subject_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '评估项目信息',
  `order_amount` double(20, 2) NOT NULL DEFAULT 0.00 COMMENT '评估金额',
  `order_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '评估时间',
  `finish_time` timestamp NULL DEFAULT NULL COMMENT '完成时间',
  `test_items` int NOT NULL DEFAULT 0 COMMENT '已经测试的题目数量',
  `total_items` int NOT NULL DEFAULT 0 COMMENT '测试题目的总数',
  `curr_item` int NOT NULL DEFAULT 0 COMMENT '当前题目Id',
  `items` varchar(4096) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '评估项:itemt1,item2,item3...',
  `result` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '评估结果',
  `pay_time` timestamp NULL DEFAULT NULL COMMENT '支付时间',
  `notify_time` timestamp NULL DEFAULT NULL COMMENT '支付通知时间',
  `pay_status` int NOT NULL DEFAULT 0 COMMENT '支付状态',
  `pay_desc` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '支付描述',
  `prepay_id` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '预支付ID',
  `finished` int NOT NULL DEFAULT 0 COMMENT '题目是否完成',
  `warning_level` tinyint NOT NULL DEFAULT 0 COMMENT '预警级别，0-未定义，1-绿，2-黄，3-红',
  `item_version` int NOT NULL DEFAULT 0 COMMENT '测评项目版本号',
  PRIMARY KEY (`order_no`) USING BTREE,
  INDEX `subject_id`(`subject_id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '小程序订单' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of uni_app_orders
-- ----------------------------

-- ----------------------------
-- Table structure for uni_app_subjects
-- ----------------------------
DROP TABLE IF EXISTS `uni_app_subjects`;
CREATE TABLE `uni_app_subjects`  (
  `subject_id` int NOT NULL DEFAULT 0,
  `baidu_submit_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`subject_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '小程序量表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of uni_app_subjects
-- ----------------------------

-- ----------------------------
-- Table structure for uni_app_users
-- ----------------------------
DROP TABLE IF EXISTS `uni_app_users`;
CREATE TABLE `uni_app_users`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `openid` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `nickname` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `headimg_url` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `session_key` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `register_time` datetime NULL DEFAULT NULL,
  `latest_login_time` datetime NULL DEFAULT NULL,
  `channel` enum('ALIPAY','WEIXIN','JD','KUAISHOU','QQ','LARK','TOUTIAO','QUICK','360','H5','BAIDU') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '小程序类型',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `openid`(`openid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 68 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '小程序用户' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of uni_app_users
-- ----------------------------

-- ----------------------------
-- Table structure for wx_menus
-- ----------------------------
DROP TABLE IF EXISTS `wx_menus`;
CREATE TABLE `wx_menus`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `pid` int NOT NULL DEFAULT 0 COMMENT '父节点id',
  `sort` smallint NOT NULL DEFAULT 0 COMMENT '排序值',
  `name` varchar(6) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '菜单的响应动作类型',
  `url` varchar(340) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `key` varchar(42) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT 'click等点击类型必须',
  `msg` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT 'click类型时候填写',
  `appid` char(18) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '小程序的appid(miniprogram类型必须)',
  `pagepath` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '小程序的页面路径(miniprogram类型必须)',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pid`(`pid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '微信公众号菜单' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of wx_menus
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
