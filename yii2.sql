/*
Navicat MySQL Data Transfer

Source Server         : 我的数据库
Source Server Version : 50636
Source Host           : localhost:3306
Source Database       : yii2

Target Server Type    : MYSQL
Target Server Version : 50636
File Encoding         : 65001

Date: 2017-07-15 23:10:45
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yii2_admin
-- ----------------------------
INSERT INTO `yii2_admin` VALUES ('1', 'super', 'Super@admin.com', '/public/assets/avatars/59618ea0d78b5.jpg', 'administrator', '1', 'gKkLFMdB2pvIXOFNpF_Aeemvdf1j0YUM', '$2y$13$Nuf1mzDRoCMxrWI.rIjENu20QshJG41smdEeHFHxq0qdmS99YytHy', '5vLaPpUS-I-XxJaoGP-GZDk474WdnaK3_1469073015', '1457337222', '1', '1500113056', '1', '1500113056', '127.0.0.1', '湖南省,岳阳市,岳阳县', '24', '学会微笑，学会面对，学会放下，让一切随心，随意，随缘！', '', '1', '', '', '');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日程记录信息表';

-- ----------------------------
-- Records of yii2_arrange
-- ----------------------------

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
INSERT INTO `yii2_auth_assignment` VALUES ('admin', '1', '1500118446');
INSERT INTO `yii2_auth_assignment` VALUES ('administrator', '1', '1499696115');
INSERT INTO `yii2_auth_assignment` VALUES ('user', '1', '1500118446');

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
INSERT INTO `yii2_auth_item` VALUES ('admin', '1', '管理员', null, null, '1476085137', '1499614793');
INSERT INTO `yii2_auth_item` VALUES ('admin/address', '2', '管理员地址信息查询', null, null, '1476093015', '1476093015');
INSERT INTO `yii2_auth_item` VALUES ('admin/create', '2', '创建管理员信息', null, null, '1476085130', '1476085130');
INSERT INTO `yii2_auth_item` VALUES ('admin/delete', '2', '删除管理员信息', null, null, '1476085130', '1476085130');
INSERT INTO `yii2_auth_item` VALUES ('admin/delete-all', '2', '批量删除管理员信息', null, null, '1476095763', '1476095763');
INSERT INTO `yii2_auth_item` VALUES ('admin/editable', '2', '管理员信息行内编辑', null, null, '1476090733', '1476090733');
INSERT INTO `yii2_auth_item` VALUES ('admin/export', '2', '管理员西信息导出', null, null, '1498977638', '1498977638');
INSERT INTO `yii2_auth_item` VALUES ('admin/index', '2', '显示管理员信息', null, null, '1476085130', '1476085130');
INSERT INTO `yii2_auth_item` VALUES ('admin/search', '2', '搜索管理员信息', null, null, '1476085130', '1476085130');
INSERT INTO `yii2_auth_item` VALUES ('admin/update', '2', '修改管理员信息', 'admin', null, '1476085130', '1476085130');
INSERT INTO `yii2_auth_item` VALUES ('admin/upload', '2', '上传管理员头像信息', null, null, '1476088424', '1476088424');
INSERT INTO `yii2_auth_item` VALUES ('admin/view', '2', '查看管理员详情信息', null, null, '1476088536', '1476088536');
INSERT INTO `yii2_auth_item` VALUES ('administrator', '1', '超级管理员', null, null, '1476085134', '1476085134');
INSERT INTO `yii2_auth_item` VALUES ('arrange/arrange', '2', '我的日程查询', null, null, '1477753543', '1477753543');
INSERT INTO `yii2_auth_item` VALUES ('arrange/calendar', '2', '我的日程信息', null, null, '1477752315', '1477752315');
INSERT INTO `yii2_auth_item` VALUES ('arrange/create', '2', '创建日程管理', null, null, '1476085131', '1476085131');
INSERT INTO `yii2_auth_item` VALUES ('arrange/delete', '2', '删除日程管理', null, null, '1476085131', '1476085131');
INSERT INTO `yii2_auth_item` VALUES ('arrange/delete-all', '2', '批量删除日程信息', null, null, '1476095790', '1476095790');
INSERT INTO `yii2_auth_item` VALUES ('arrange/editable', '2', '日程管理行内编辑', null, null, '1476088444', '1476088444');
INSERT INTO `yii2_auth_item` VALUES ('arrange/export', '2', '日程信息导出', null, null, '1476090884', '1476090884');
INSERT INTO `yii2_auth_item` VALUES ('arrange/index', '2', '显示日程管理', null, null, '1476085130', '1476085130');
INSERT INTO `yii2_auth_item` VALUES ('arrange/search', '2', '搜索日程管理', null, null, '1476085130', '1476085130');
INSERT INTO `yii2_auth_item` VALUES ('arrange/update', '2', '修改日程管理', null, null, '1476085131', '1476085131');
INSERT INTO `yii2_auth_item` VALUES ('auth-assignment/create', '2', '创建角色分配', null, null, '1500096941', '1500096941');
INSERT INTO `yii2_auth_item` VALUES ('auth-assignment/delete', '2', '删除角色分配', 'auth-assignment', null, '1500096941', '1500105283');
INSERT INTO `yii2_auth_item` VALUES ('auth-assignment/export', '2', '导出角色分配', null, null, '1500096942', '1500096942');
INSERT INTO `yii2_auth_item` VALUES ('auth-assignment/index', '2', '显示角色分配', null, null, '1500096941', '1500096941');
INSERT INTO `yii2_auth_item` VALUES ('auth-assignment/search', '2', '搜索角色分配', null, null, '1500096941', '1500096941');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/create', '2', '创建规则管理', null, null, '1499566202', '1499566202');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/delete', '2', '删除规则管理', null, null, '1499566202', '1499566202');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/delete-all', '2', '规则管理-多删除', null, null, '1499586110', '1499589996');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/export', '2', '导出规则管理', null, null, '1499566202', '1499566202');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/index', '2', '显示规则管理', null, null, '1499566201', '1499566201');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/search', '2', '搜索规则管理', null, null, '1499566202', '1499566202');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/update', '2', '修改规则管理', null, null, '1499566202', '1499566202');
INSERT INTO `yii2_auth_item` VALUES ('authority/create', '2', '创建权限信息', null, null, '1476085131', '1476085131');
INSERT INTO `yii2_auth_item` VALUES ('authority/delete', '2', '删除权限信息', null, null, '1476085132', '1476085132');
INSERT INTO `yii2_auth_item` VALUES ('authority/delete-all', '2', '权限信息多删除操作', null, null, '1498976870', '1498976870');
INSERT INTO `yii2_auth_item` VALUES ('authority/export', '2', '权限信息导出', null, null, '1476090709', '1476090709');
INSERT INTO `yii2_auth_item` VALUES ('authority/index', '2', '显示权限信息', null, null, '1476085131', '1476085131');
INSERT INTO `yii2_auth_item` VALUES ('authority/search', '2', '搜索权限信息', null, null, '1476085131', '1476085131');
INSERT INTO `yii2_auth_item` VALUES ('authority/update', '2', '修改权限信息', null, null, '1476085131', '1476085131');
INSERT INTO `yii2_auth_item` VALUES ('china/create', '2', '创建地址信息', null, null, '1476085132', '1476085132');
INSERT INTO `yii2_auth_item` VALUES ('china/delete', '2', '删除地址信息', null, null, '1476085132', '1476085132');
INSERT INTO `yii2_auth_item` VALUES ('china/export', '2', '地址信息导出', null, null, '1482669906', '1482669906');
INSERT INTO `yii2_auth_item` VALUES ('china/index', '2', '显示地址信息', null, null, '1476085132', '1476085132');
INSERT INTO `yii2_auth_item` VALUES ('china/search', '2', '搜索地址信息', null, null, '1476085132', '1476085132');
INSERT INTO `yii2_auth_item` VALUES ('china/update', '2', '修改地址信息', null, null, '1476085132', '1476085132');
INSERT INTO `yii2_auth_item` VALUES ('menu/create', '2', '创建导航栏目', null, null, '1476085132', '1476085132');
INSERT INTO `yii2_auth_item` VALUES ('menu/delete', '2', '删除导航栏目', null, null, '1476085132', '1476085132');
INSERT INTO `yii2_auth_item` VALUES ('menu/delete-all', '2', '批量删除导航栏目信息', null, null, '1476095845', '1476095845');
INSERT INTO `yii2_auth_item` VALUES ('menu/export', '2', '导航栏目信息导出', null, null, '1498977289', '1498977289');
INSERT INTO `yii2_auth_item` VALUES ('menu/index', '2', '显示导航栏目', null, null, '1476085132', '1476085132');
INSERT INTO `yii2_auth_item` VALUES ('menu/search', '2', '搜索导航栏目', null, null, '1476085132', '1476085132');
INSERT INTO `yii2_auth_item` VALUES ('menu/update', '2', '修改导航栏目', null, null, '1476085132', '1476085132');
INSERT INTO `yii2_auth_item` VALUES ('module/create', '2', '创建模块生成', null, null, '1476085133', '1476085133');
INSERT INTO `yii2_auth_item` VALUES ('module/index', '2', '显示模块生成', null, null, '1476085133', '1476085133');
INSERT INTO `yii2_auth_item` VALUES ('module/produce', '2', '模块生成配置文件', null, null, '1476085133', '1476093990');
INSERT INTO `yii2_auth_item` VALUES ('module/update', '2', '修改模块生成', null, null, '1476085133', '1476085133');
INSERT INTO `yii2_auth_item` VALUES ('role/create', '2', '创建角色信息', null, null, '1476085133', '1476085133');
INSERT INTO `yii2_auth_item` VALUES ('role/delete', '2', '删除角色信息', null, null, '1476085134', '1476085134');
INSERT INTO `yii2_auth_item` VALUES ('role/edit', '2', '角色分配权限', null, null, '1476096038', '1476096038');
INSERT INTO `yii2_auth_item` VALUES ('role/export', '2', '角色信息导出', null, null, '1499866826', '1499866826');
INSERT INTO `yii2_auth_item` VALUES ('role/index', '2', '显示角色信息', null, null, '1476085133', '1476085133');
INSERT INTO `yii2_auth_item` VALUES ('role/search', '2', '搜索角色信息', null, null, '1476085133', '1476085133');
INSERT INTO `yii2_auth_item` VALUES ('role/update', '2', '修改角色信息', null, null, '1476085134', '1476085134');
INSERT INTO `yii2_auth_item` VALUES ('role/view', '2', '角色权限查看', null, null, '1476096101', '1476096101');
INSERT INTO `yii2_auth_item` VALUES ('user', '1', '普通用户', null, null, '1476085137', '1500112637');
INSERT INTO `yii2_auth_item` VALUES ('user/create', '2', '创建用户信息', null, null, '1476095210', '1476095210');
INSERT INTO `yii2_auth_item` VALUES ('user/delete', '2', '删除用户信息', null, null, '1476095210', '1476095210');
INSERT INTO `yii2_auth_item` VALUES ('user/delete-all', '2', '批量删除用户信息', null, null, '1476096229', '1476096229');
INSERT INTO `yii2_auth_item` VALUES ('user/export', '2', '导出用户信息', null, null, '1476095210', '1476095210');
INSERT INTO `yii2_auth_item` VALUES ('user/index', '2', '显示用户信息', null, null, '1476095210', '1476095210');
INSERT INTO `yii2_auth_item` VALUES ('user/search', '2', '搜索用户信息', null, null, '1476095210', '1476095210');
INSERT INTO `yii2_auth_item` VALUES ('user/update', '2', '修改用户信息', null, null, '1476095210', '1476095210');
INSERT INTO `yii2_auth_item` VALUES ('user/upload', '2', '上传用户头像信息', null, null, '1476149415', '1476149415');

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
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/address');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'admin/address');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/create');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'admin/create');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('user', 'admin/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/delete-all');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/delete-all');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/editable');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/editable');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/export');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/index');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/search');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/search');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/update');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/update');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/upload');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/upload');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'admin/view');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/view');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/arrange');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/calendar');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/create');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/delete-all');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/delete-all');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/editable');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/editable');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/export');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/export');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/index');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/search');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/search');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'arrange/update');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/update');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'auth-assignment/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'auth-assignment/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'auth-assignment/export');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'auth-assignment/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'auth-assignment/search');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'auth-rule/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'auth-rule/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'auth-rule/delete-all');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'auth-rule/export');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'auth-rule/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'auth-rule/search');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'auth-rule/update');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'authority/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'authority/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'authority/delete-all');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'authority/export');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'authority/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'authority/search');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'authority/update');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'china/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'china/create');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'china/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'china/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'china/export');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'china/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'china/index');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'china/search');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'china/search');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'china/update');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'china/update');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'menu/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'menu/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'menu/delete-all');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'menu/export');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'menu/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'menu/search');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'menu/update');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'module/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'module/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'module/produce');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'module/update');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'role/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/create');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'role/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'role/edit');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/edit');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/export');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'role/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/index');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'role/search');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/search');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'role/update');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/update');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'role/view');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/view');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'user/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'user/create');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'user/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'user/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'user/delete-all');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'user/export');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'user/export');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'user/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'user/index');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'user/search');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'user/search');
INSERT INTO `yii2_auth_item_child` VALUES ('admin', 'user/update');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'user/update');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'user/upload');

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
INSERT INTO `yii2_auth_rule` VALUES ('admin', 'O:23:\"backend\\rules\\AdminRule\":3:{s:4:\"name\";s:5:\"admin\";s:9:\"createdAt\";i:1499006069;s:9:\"updatedAt\";i:1499006069;}', '1499006069', '1499006069');
INSERT INTO `yii2_auth_rule` VALUES ('auth-assignment', 'O:32:\"backend\\rules\\AuthAssignmentRule\":5:{s:4:\"name\";s:15:\"auth-assignment\";s:47:\"\0backend\\rules\\AuthAssignmentRule\0adminRoleName\";s:13:\"administrator\";s:43:\"\0backend\\rules\\AuthAssignmentRule\0intUserId\";i:1;s:9:\"createdAt\";i:1500105238;s:9:\"updatedAt\";i:1500105238;}', '1500105238', '1500105238');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='导航栏信息表';

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
INSERT INTO `yii2_menu` VALUES ('8', '0', '日程管理', 'menu-icon fa fa-calendar', 'arrange/index', '1', '1', '1474340682', '1', '1498979333', '1');
INSERT INTO `yii2_menu` VALUES ('9', '1', '规则管理', 'menu-icon fa fa-shield', 'auth-rule/index', '1', '100', '1499566072', '1', '1499566516', '1');
INSERT INTO `yii2_menu` VALUES ('10', '1', '角色分配', 'icon-cog', 'auth-assignment/index', '1', '100', '1499696448', '1', '1500089615', '1');

-- ----------------------------
-- Table structure for yii2_session
-- ----------------------------
DROP TABLE IF EXISTS `yii2_session`;
CREATE TABLE `yii2_session` (
  `session_id` varchar(100) NOT NULL DEFAULT '' COMMENT 'session_id',
  `session_data` text COMMENT 'session 保存的值',
  `session_expire` int(11) NOT NULL COMMENT 'session 创建的时间',
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yii2_session
-- ----------------------------
