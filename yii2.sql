/*
Navicat MySQL Data Transfer

Source Server         : 我的数据库
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : yii2

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2016-09-21 17:56:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for yii2_admin
-- ----------------------------
DROP TABLE IF EXISTS `yii2_admin`;
CREATE TABLE `yii2_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '管理员账号',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱',
  `face` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '管理员头像',
  `role` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user' COMMENT '角色',
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT '状态',
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '自动登录密钥',
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码哈希值',
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '重新登录哈希值',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `created_id` int(11) NOT NULL COMMENT '创建用户',
  `updated_at` int(11) NOT NULL COMMENT '修改时间',
  `updated_id` int(11) DEFAULT NULL COMMENT '修改用户',
  `last_time` int(11) DEFAULT NULL COMMENT '上一次登录时间',
  `last_ip` char(12) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '上一次登录IP',
  `address` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '地址信息',
  `age` tinyint(3) DEFAULT '18' COMMENT '年龄',
  `maxim` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '座右铭',
  `nickname` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '真实姓名',
  `sex` tinyint(1) DEFAULT '1' COMMENT '性别（1 男 0 女）',
  `home_url` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '个人主页',
  `facebook` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT 'facebook账号',
  `birthday` varchar(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '生日',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role` (`role`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yii2_admin
-- ----------------------------
INSERT INTO `yii2_admin` VALUES ('1', 'super', 'Super@admin.com', '/public/assets/avatars/57956e2620e65.jpg', 'admin', '1', 'gKkLFMdB2pvIXOFNpF_Aeemvdf1j0YUM', '$2y$13$Nuf1mzDRoCMxrWI.rIjENu20QshJG41smdEeHFHxq0qdmS99YytHy', '5vLaPpUS-I-XxJaoGP-GZDk474WdnaK3_1469073015', '1457337222', '1', '1474434900', '1', '1474434900', '127.0.0.1', '上海市,普陀区', '20', '学会微笑，学会面对，学会放下，让一切随心，随意，随缘！', '', '1', '', '', '');
INSERT INTO `yii2_admin` VALUES ('2', 'liujinxing', '821901008@qq.com', '/public/assets/avatars/57957eb2a5ca2.jpg', 'user', '1', 'yz4AxLNDC_33mLQoz31ptePpTCWJOHbk', '$2y$13$OonjJGSZ.QpanoOdZbLbAOsB80UKYcWeXGA/vtNCTM1iMz1TNZR0u', 'Gtl9Z0Wk2CxRwI2IXWiv-SeBeNASmV3c_1469413639', '1469413639', '1', '1469415815', '2', '1469415815', '127.0.0.1', '湖南省,岳阳市,岳阳县', '18', '', '', '1', '', '', '');

-- ----------------------------
-- Table structure for yii2_arrange
-- ----------------------------
DROP TABLE IF EXISTS `yii2_arrange`;
CREATE TABLE `yii2_arrange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '时间标题',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '事件描述',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '时间状态(0 待处理 1 已委派处理 2 完成 3 延期 )',
  `time_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '时间状态(0 可延缓 1 正常 2 紧急)',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '委派处理的管理员',
  `start_at` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_at` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `created_at` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `created_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加用户',
  `updated_at` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `updated_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改用户',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='日程记录信息表';

-- ----------------------------
-- Records of yii2_arrange
-- ----------------------------
INSERT INTO `yii2_arrange` VALUES ('1', '这个事件已经处理了123', '这个事件已经处理了', '2', '1', '1', '1474352360', '1474449563', '1474344037', '1', '1474449846', '1');
INSERT INTO `yii2_arrange` VALUES ('2', '首页问题', '首页处理一些其他问题', '1', '1', '1', '1473782400', '1473868800', '1474353783', '1', '1474445505', '1');
INSERT INTO `yii2_arrange` VALUES ('3', '手游问题', '首页的一些问题', '1', '1', '1', '1473955200', '1474041600', '1474353822', '1', '1474445822', '1');
INSERT INTO `yii2_arrange` VALUES ('4', '其他问题', '其他问题', '1', '2', '1', '1473436800', '1473523200', '1474353858', '1', '1474445849', '1');
INSERT INTO `yii2_arrange` VALUES ('6', '这一天要办的事情好多呵呵', '这一天要办的事情好多呵呵', '0', '0', '1', '1475856000', '1475942400', '1474354086', '1', '1474451481', '1');
INSERT INTO `yii2_arrange` VALUES ('7', '这个事件已经延期了不要了吧', '这个事件已经延期了', '0', '1', '0', '1474357344', '1474617222', '1474354171', '1', '1474444440', '1');
INSERT INTO `yii2_arrange` VALUES ('8', '测试数据', '测试数据', '1', '0', '1', '1474041600', '1474128000', '1474354386', '1', '1474445929', '1');
INSERT INTO `yii2_arrange` VALUES ('11', '测试数据哦', '测试数据哦', '0', '1', '0', '1471449600', '1471536000', '1474435082', '1', '1474444943', '1');
INSERT INTO `yii2_arrange` VALUES ('14', '学习Laravel', '基本了解Laravel', '0', '1', '0', '1472801049', '1473233307', '1474440310', '1', '1474445521', '1');
INSERT INTO `yii2_arrange` VALUES ('15', '学习Laravel', '基本了解Laravel', '0', '1', '0', '1473350400', '1473436800', '1474445541', '1', '1474445541', '1');
INSERT INTO `yii2_arrange` VALUES ('16', '其他问题', '其他问题', '0', '2', '0', '1472659200', '1472745600', '1474445843', '1', '1474445845', '1');
INSERT INTO `yii2_arrange` VALUES ('17', '学习Laravel', '基本了解Laravel', '1', '1', '1', '1474646400', '1474732800', '1474445919', '1', '1474445919', '1');
INSERT INTO `yii2_arrange` VALUES ('18', '这个事件已经延期了不要了吧', '这个事件已经延期了', '1', '1', '1', '1472572800', '1472659200', '1474446601', '1', '1474446601', '1');
INSERT INTO `yii2_arrange` VALUES ('19', '学习Laravel121212', '基本了解Laravel', '1', '1', '1', '1473609600', '1473696000', '1474447449', '1', '1474449134', '1');
INSERT INTO `yii2_arrange` VALUES ('20', '测试数据哦', '测试数据哦', '1', '1', '1', '1472745600', '1472832000', '1474447468', '1', '1474447468', '1');
INSERT INTO `yii2_arrange` VALUES ('21', '测试数据哦', '测试数据哦', '2', '1', '1', '1473264000', '1473350400', '1474447478', '1', '1474451496', '1');
INSERT INTO `yii2_arrange` VALUES ('22', '测试数据哦', '测试数据哦', '1', '1', '1', '1475164800', '1475313805', '1474447487', '1', '1474449837', '1');
INSERT INTO `yii2_arrange` VALUES ('23', '学习Laravel', '基本了解Laravel', '1', '1', '1', '1474560000', '1474646400', '1474447511', '1', '1474447511', '1');
INSERT INTO `yii2_arrange` VALUES ('24', '学习Laravel', '基本了解Laravel', '1', '1', '1', '1474992000', '1475078400', '1474447514', '1', '1474447518', '1');
INSERT INTO `yii2_arrange` VALUES ('25', '123', '121212', '1', '1', '1', '1472486400', '1472572800', '1474448178', '1', '1474449249', '1');
INSERT INTO `yii2_arrange` VALUES ('26', '这一天要办的事情好多呵呵', '这一天要办的事情好多呵呵', '1', '0', '1', '1473868800', '1475683200', '1474449746', '1', '1474449746', '1');
INSERT INTO `yii2_arrange` VALUES ('28', '学习Laravel', '基本了解Laravel', '1', '1', '1', '1473091200', '1473177600', '1474451327', '1', '1474451327', '1');
INSERT INTO `yii2_arrange` VALUES ('29', '这一天要办的事情好多呵呵', '这一天要办的事情好多呵呵', '1', '0', '1', '1473004800', '1473091200', '1474451437', '1', '1474451437', '1');

-- ----------------------------
-- Table structure for yii2_auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `yii2_auth_assignment`;
CREATE TABLE `yii2_auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `yii2_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `yii2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yii2_auth_assignment
-- ----------------------------
INSERT INTO `yii2_auth_assignment` VALUES ('admin', '1', '1469076860');
INSERT INTO `yii2_auth_assignment` VALUES ('user', '2', '1469413639');

-- ----------------------------
-- Table structure for yii2_auth_item
-- ----------------------------
DROP TABLE IF EXISTS `yii2_auth_item`;
CREATE TABLE `yii2_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `yii2_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `yii2_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yii2_auth_item
-- ----------------------------
INSERT INTO `yii2_auth_item` VALUES ('admin', '1', '超级管理员', null, null, '1469009779', '1469009779');
INSERT INTO `yii2_auth_item` VALUES ('admin/address', '2', '管理员获取地址信息', null, null, '1469414812', '1469414812');
INSERT INTO `yii2_auth_item` VALUES ('admin/editable', '2', '管理员信息行内编辑', null, null, '1469177671', '1469177671');
INSERT INTO `yii2_auth_item` VALUES ('admin/index', '2', '管理员信息显示', null, null, '1469009816', '1469009816');
INSERT INTO `yii2_auth_item` VALUES ('admin/search', '2', '管理员信息搜索', null, null, '1469009816', '1469009816');
INSERT INTO `yii2_auth_item` VALUES ('admin/update', '2', '管理员信息编辑', null, null, '1469009816', '1469009816');
INSERT INTO `yii2_auth_item` VALUES ('admin/upload', '2', '管理员头像上传', null, null, '1469157297', '1469157297');
INSERT INTO `yii2_auth_item` VALUES ('admin/view', '2', '管理员个人信息', null, null, '1469096927', '1469096927');
INSERT INTO `yii2_auth_item` VALUES ('arrange/arrange', '2', '管理员的日程管理', null, null, '1474446322', '1474446322');
INSERT INTO `yii2_auth_item` VALUES ('arrange/calendar', '2', '管理员日程', null, null, '1474350037', '1474350037');
INSERT INTO `yii2_auth_item` VALUES ('arrange/index', '2', '日程管理显示', null, null, '1474340589', '1474340589');
INSERT INTO `yii2_auth_item` VALUES ('arrange/search', '2', '日程管理搜索', null, null, '1474340610', '1474340610');
INSERT INTO `yii2_auth_item` VALUES ('arrange/update', '2', '日程管理编辑', null, null, '1474340628', '1474340628');
INSERT INTO `yii2_auth_item` VALUES ('authority/export', '2', '权限信息导出', null, null, '1469437214', '1469437214');
INSERT INTO `yii2_auth_item` VALUES ('authority/index', '2', '权限信息显示', null, null, '1469078967', '1469080494');
INSERT INTO `yii2_auth_item` VALUES ('authority/search', '2', '权限信息搜索', null, null, '1469078967', '1469080591');
INSERT INTO `yii2_auth_item` VALUES ('authority/update', '2', '权限信息编辑', null, null, '1469094174', '1469094174');
INSERT INTO `yii2_auth_item` VALUES ('china/export', '2', '地址信息导出', null, null, '1469418224', '1469418224');
INSERT INTO `yii2_auth_item` VALUES ('china/index', '2', '地址信息显示', null, null, '1469415343', '1469415343');
INSERT INTO `yii2_auth_item` VALUES ('china/search', '2', '地址信息搜索', null, null, '1469415343', '1469415343');
INSERT INTO `yii2_auth_item` VALUES ('china/update', '2', '地址信息编辑', null, null, '1469415343', '1469415343');
INSERT INTO `yii2_auth_item` VALUES ('deleteAdmin', '2', '管理员信息删除', null, null, '1469081818', '1469081818');
INSERT INTO `yii2_auth_item` VALUES ('deleteAuthority', '2', '权限信息删除', null, null, '1469081803', '1469081803');
INSERT INTO `yii2_auth_item` VALUES ('menu/deleteAll', '2', '导航信息多删除', null, null, '1469085213', '1469085213');
INSERT INTO `yii2_auth_item` VALUES ('menu/index', '2', '导航信息显示', null, null, '1469081716', '1469081716');
INSERT INTO `yii2_auth_item` VALUES ('menu/search', '2', '导航信息搜索', null, null, '1469081752', '1469081752');
INSERT INTO `yii2_auth_item` VALUES ('menu/update', '2', '导航信息编辑', null, null, '1469081736', '1469081736');
INSERT INTO `yii2_auth_item` VALUES ('module/create', '2', '模块生成预览表单', null, null, '1469091119', '1469091119');
INSERT INTO `yii2_auth_item` VALUES ('module/index', '2', '模块生成显示', null, null, '1469091078', '1469091078');
INSERT INTO `yii2_auth_item` VALUES ('module/produce', '2', '模块生成最终文件', null, null, '1469091179', '1469091179');
INSERT INTO `yii2_auth_item` VALUES ('module/update', '2', '模块生成预览文件', null, null, '1469091147', '1469091147');
INSERT INTO `yii2_auth_item` VALUES ('OrdinaryUsers', '1', '普通用户', null, null, '1469096255', '1469096255');
INSERT INTO `yii2_auth_item` VALUES ('role/create', '2', '角色信息分配权限', null, null, '1469094244', '1469094244');
INSERT INTO `yii2_auth_item` VALUES ('role/index', '2', '角色信息显示', null, null, '1469080022', '1469080022');
INSERT INTO `yii2_auth_item` VALUES ('role/search', '2', '角色信息搜索', null, null, '1469081628', '1469081628');
INSERT INTO `yii2_auth_item` VALUES ('role/update', '2', '角色信息编辑', null, null, '1469081575', '1469081575');
INSERT INTO `yii2_auth_item` VALUES ('role/view', '2', '角色信息查看详情', null, null, '1469094284', '1469094284');
INSERT INTO `yii2_auth_item` VALUES ('user', '1', '管理员', null, null, '1469083867', '1469415054');

-- ----------------------------
-- Table structure for yii2_auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `yii2_auth_item_child`;
CREATE TABLE `yii2_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `yii2_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `yii2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `yii2_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `yii2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yii2_auth_item_child
-- ----------------------------
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/address');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'admin/address');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/editable');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'admin/editable');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/index');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'admin/index');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/search');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'admin/search');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/update');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'admin/update');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/upload');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'admin/upload');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/view');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'admin/view');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/arrange');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/calendar');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/index');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/search');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/update');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'authority/export');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'authority/index');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'authority/search');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'authority/update');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'china/export');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'china/index');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'china/search');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'china/update');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'deleteAdmin');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'deleteAuthority');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'menu/deleteAll');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'menu/index');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'menu/search');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'menu/update');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'module/create');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'module/create');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'module/index');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'module/index');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'module/produce');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'module/produce');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'module/update');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'module/update');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'role/create');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'role/create');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'role/index');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'role/index');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'role/search');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'role/search');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'role/update');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'role/update');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'role/view');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'role/view');

-- ----------------------------
-- Table structure for yii2_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `yii2_auth_rule`;
CREATE TABLE `yii2_auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yii2_auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for yii2_menu
-- ----------------------------
DROP TABLE IF EXISTS `yii2_menu`;
CREATE TABLE `yii2_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父类ID(只支持两级分类)',
  `menu_name` varchar(50) NOT NULL COMMENT '栏目名称',
  `icons` varchar(50) NOT NULL DEFAULT 'icon-desktop' COMMENT '使用的icons',
  `url` varchar(50) DEFAULT 'site/index' COMMENT '访问的地址',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态（1启用 0 停用）',
  `sort` int(4) NOT NULL DEFAULT '100' COMMENT '排序字段',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `created_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建用户',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `updated_id` int(11) NOT NULL DEFAULT '0' COMMENT '修改用户',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='导航栏信息表';

-- ----------------------------
-- Records of yii2_menu
-- ----------------------------
INSERT INTO `yii2_menu` VALUES ('1', '0', '后台管理', 'menu-icon fa fa-cog', '', '1', '2', '1468985587', '1', '1474340768', '1');
INSERT INTO `yii2_menu` VALUES ('2', '1', '导航栏目', '', 'menu/index', '1', '4', '1468987221', '1', '1468994846', '1');
INSERT INTO `yii2_menu` VALUES ('3', '1', '模块生成', '', 'module/index', '1', '5', '1468994283', '1', '1468994860', '1');
INSERT INTO `yii2_menu` VALUES ('4', '1', '角色管理', '', 'role/index', '1', '2', '1468994665', '1', '1468994676', '1');
INSERT INTO `yii2_menu` VALUES ('5', '1', '管理员信息', '', 'admin/index', '1', '2', '1468994769', '1', '1474340722', '1');
INSERT INTO `yii2_menu` VALUES ('6', '1', '权限管理', '', 'authority/index', '1', '3', '1468994819', '1', '1469410899', '1');
INSERT INTO `yii2_menu` VALUES ('7', '0', '地址信息', 'menu-icon fa fa-bank', 'china/index', '1', '3', '1469415343', '2', '1474340794', '1');
INSERT INTO `yii2_menu` VALUES ('8', '0', '日程管理', 'menu-icon fa fa-calendar', 'arrange/index', '1', '1', '1474340682', '1', '1474340932', '1');
