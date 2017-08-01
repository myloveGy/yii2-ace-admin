/*
Navicat MySQL Data Transfer

Source Server         : 我的数据库
Source Server Version : 50636
Source Host           : localhost:3306
Source Database       : yii2_advanced

Target Server Type    : MYSQL
Target Server Version : 50636
File Encoding         : 65001

Date: 2017-08-01 18:32:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for yii2_admin
-- ----------------------------
DROP TABLE IF EXISTS `yii2_admin`;
CREATE TABLE `yii2_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '管理员账号',
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '管理员邮箱',
  `face` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员头像',
  `role` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员角色',
  `status` smallint(2) NOT NULL DEFAULT '10' COMMENT '状态',
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT '上一次登录时间',
  `last_ip` char(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '上一次登录的IP',
  `address` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '地址信息',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `created_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建用户',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `updated_id` int(11) NOT NULL DEFAULT '0' COMMENT '修改用户',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员信息表';

-- ----------------------------
-- Records of yii2_admin
-- ----------------------------
INSERT INTO `yii2_admin` VALUES ('1', 'super', 'Super@admin.com', '/public/assets/avatars/59618ea0d78b5.jpg', 'administrator', '10', 'gKkLFMdB2pvIXOFNpF_Aeemvdf1j0YUM', '$2y$13$Nuf1mzDRoCMxrWI.rIjENu20QshJG41smdEeHFHxq0qdmS99YytHy', '5vLaPpUS-I-XxJaoGP-GZDk474WdnaK3_1469073015', '1500113056', '127.0.0.1', '湖南省,岳阳市,岳阳县', '1457337222', '1', '1500113056', '1');

-- ----------------------------
-- Table structure for yii2_arrange
-- ----------------------------
DROP TABLE IF EXISTS `yii2_arrange`;
CREATE TABLE `yii2_arrange` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '事件标题',
  `desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '事件描述',
  `status` smallint(2) NOT NULL DEFAULT '0' COMMENT '状态[0 - 待处理 1 - 已委派 2 - 完成 3 延期]',
  `time_status` smallint(2) NOT NULL DEFAULT '0' COMMENT '事件状态[0 - 延缓 1 - 正常 2 - 紧急]',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '委派管理员',
  `start_at` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_at` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `created_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建用户',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `updated_id` int(11) NOT NULL DEFAULT '0' COMMENT '修改用户',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='日程记录信息表';

-- ----------------------------
-- Records of yii2_arrange
-- ----------------------------

-- ----------------------------
-- Table structure for yii2_auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `yii2_auth_assignment`;
CREATE TABLE `yii2_auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `yii2_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `yii2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yii2_auth_assignment
-- ----------------------------
INSERT INTO `yii2_auth_assignment` VALUES ('administrator', '1', '1501582767');

-- ----------------------------
-- Table structure for yii2_auth_item
-- ----------------------------
DROP TABLE IF EXISTS `yii2_auth_item`;
CREATE TABLE `yii2_auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `yii2_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `yii2_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yii2_auth_item
-- ----------------------------
INSERT INTO `yii2_auth_item` VALUES ('admin', '1', '管理员', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('admin/address', '2', '管理员地址信息查询', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('admin/create', '2', '创建管理员信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('admin/delete', '2', '删除管理员信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('admin/delete-all', '2', '批量删除管理员信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('admin/editable', '2', '管理员信息行内编辑', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('admin/export', '2', '管理员西信息导出', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('admin/index', '2', '显示管理员信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('admin/search', '2', '搜索管理员信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('admin/update', '2', '修改管理员信息', 'admin', null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('admin/upload', '2', '上传管理员头像信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('admin/view', '2', '查看管理员详情信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('administrator', '1', '超级管理员', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('arrange/arrange', '2', '我的日程查询', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('arrange/calendar', '2', '我的日程信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('arrange/create', '2', '创建日程管理', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('arrange/delete', '2', '删除日程管理', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('arrange/delete-all', '2', '批量删除日程信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('arrange/editable', '2', '日程管理行内编辑', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('arrange/export', '2', '日程信息导出', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('arrange/index', '2', '显示日程管理', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('arrange/search', '2', '搜索日程管理', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('arrange/update', '2', '修改日程管理', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('auth-assignment/create', '2', '创建角色分配', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('auth-assignment/delete', '2', '删除角色分配', 'auth-assignment', null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('auth-assignment/export', '2', '导出角色分配', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('auth-assignment/index', '2', '显示角色分配', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('auth-assignment/search', '2', '搜索角色分配', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/create', '2', '创建规则管理', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/delete', '2', '删除规则管理', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/delete-all', '2', '规则管理-多删除', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/export', '2', '导出规则管理', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/index', '2', '显示规则管理', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/search', '2', '搜索规则管理', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('auth-rule/update', '2', '修改规则管理', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('authority/create', '2', '创建权限信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('authority/delete', '2', '删除权限信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('authority/delete-all', '2', '权限信息多删除操作', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('authority/export', '2', '权限信息导出', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('authority/index', '2', '显示权限信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('authority/search', '2', '搜索权限信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('authority/update', '2', '修改权限信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('china/create', '2', '创建地址信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('china/delete', '2', '删除地址信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('china/export', '2', '地址信息导出', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('china/index', '2', '显示地址信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('china/search', '2', '搜索地址信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('china/update', '2', '修改地址信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('menu/create', '2', '创建导航栏目', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('menu/delete', '2', '删除导航栏目', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('menu/delete-all', '2', '批量删除导航栏目信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('menu/export', '2', '导航栏目信息导出', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('menu/index', '2', '显示导航栏目', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('menu/search', '2', '搜索导航栏目', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('menu/update', '2', '修改导航栏目', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('module/create', '2', '创建模块生成', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('module/index', '2', '显示模块生成', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('module/produce', '2', '模块生成配置文件', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('module/update', '2', '修改模块生成', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('role/create', '2', '创建角色信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('role/delete', '2', '删除角色信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('role/edit', '2', '角色分配权限', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('role/export', '2', '角色信息导出', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('role/index', '2', '显示角色信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('role/search', '2', '搜索角色信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('role/update', '2', '修改角色信息', null, null, '1501582767', '1501582767');
INSERT INTO `yii2_auth_item` VALUES ('role/view', '2', '角色权限查看', null, null, '1501582767', '1501582767');

-- ----------------------------
-- Table structure for yii2_auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `yii2_auth_item_child`;
CREATE TABLE `yii2_auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `yii2_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `yii2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `yii2_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `yii2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yii2_auth_item_child
-- ----------------------------
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/address');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/delete-all');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/editable');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/export');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/search');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/update');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/upload');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'admin/view');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/arrange');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/calendar');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/delete-all');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/editable');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/export');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'arrange/search');
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
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'china/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'china/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'china/export');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'china/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'china/search');
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
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/create');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/edit');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/export');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/index');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/search');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/update');
INSERT INTO `yii2_auth_item_child` VALUES ('administrator', 'role/view');

-- ----------------------------
-- Table structure for yii2_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `yii2_auth_rule`;
CREATE TABLE `yii2_auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yii2_auth_rule
-- ----------------------------
INSERT INTO `yii2_auth_rule` VALUES ('admin', 0x4F3A32333A226261636B656E645C72756C65735C41646D696E52756C65223A333A7B733A343A226E616D65223B733A353A2261646D696E223B733A393A22637265617465644174223B693A313439393030363036393B733A393A22757064617465644174223B693A313439393030363036393B7D, '1501582767', '1501582767');
INSERT INTO `yii2_auth_rule` VALUES ('auth-assignment', 0x4F3A33323A226261636B656E645C72756C65735C4175746841737369676E6D656E7452756C65223A373A7B733A343A226E616D65223B733A31353A22617574682D61737369676E6D656E74223B733A34373A22006261636B656E645C72756C65735C4175746841737369676E6D656E7452756C650061646D696E526F6C654E616D65223B733A31333A2261646D696E6973747261746F72223B733A34333A22006261636B656E645C72756C65735C4175746841737369676E6D656E7452756C6500696E74557365724964223B693A313B733A393A22637265617465644174223B693A313530303130353233383B733A393A22757064617465644174223B693A313530303130353233383B733A34373A22206261636B656E645C72756C65735C4175746841737369676E6D656E7452756C652061646D696E526F6C654E616D65223B733A31333A2261646D696E6973747261746F72223B733A34333A22206261636B656E645C72756C65735C4175746841737369676E6D656E7452756C6520696E74557365724964223B693A313B7D, '1501582767', '1501582767');

-- ----------------------------
-- Table structure for yii2_menu
-- ----------------------------
DROP TABLE IF EXISTS `yii2_menu`;
CREATE TABLE `yii2_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '导航栏目ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父类ID(只支持两级)',
  `menu_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '导航栏目',
  `icons` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'icon-desktop' COMMENT '使用的小图标',
  `url` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'site/index' COMMENT '访问地址',
  `status` smallint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `sort` smallint(6) NOT NULL DEFAULT '100' COMMENT '排序',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `created_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建用户',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `updated_id` int(11) NOT NULL DEFAULT '0' COMMENT '修改用户',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='后台导航栏目信息表';

-- ----------------------------
-- Records of yii2_menu
-- ----------------------------
INSERT INTO `yii2_menu` VALUES ('1', '0', '后台管理', 'menu-icon fa fa-cog', '', '1', '2', '1501582255', '1', '1501582255', '1');
INSERT INTO `yii2_menu` VALUES ('2', '1', '管理员信息', '', 'admin/index', '1', '1', '1501582255', '1', '1501582255', '1');
INSERT INTO `yii2_menu` VALUES ('3', '1', '角色管理', '', 'role/index', '1', '2', '1501582255', '1', '1501582255', '1');
INSERT INTO `yii2_menu` VALUES ('4', '1', '角色分配', 'icon-cog', 'auth-assignment/index', '1', '3', '1501582255', '1', '1501582255', '1');
INSERT INTO `yii2_menu` VALUES ('5', '1', '权限管理', '', 'authority/index', '1', '4', '1501582255', '1', '1501582255', '1');
INSERT INTO `yii2_menu` VALUES ('6', '1', '规则管理', 'menu-icon fa fa-shield', 'auth-rule/index', '1', '5', '1501582255', '1', '1501582255', '1');
INSERT INTO `yii2_menu` VALUES ('7', '1', '导航栏目', '', 'menu/index', '1', '6', '1501582255', '1', '1501582255', '1');
INSERT INTO `yii2_menu` VALUES ('8', '1', '模块生成', '', 'module/index', '1', '7', '1501582255', '1', '1501582255', '1');
INSERT INTO `yii2_menu` VALUES ('9', '0', '地址信息', 'menu-icon fa fa-bank', 'china/index', '1', '3', '1501582255', '1', '1501582255', '1');
INSERT INTO `yii2_menu` VALUES ('10', '0', '日程管理', 'menu-icon fa fa-calendar', 'arrange/index', '1', '1', '1501582255', '1', '1501582255', '1');

-- ----------------------------
-- Table structure for yii2_user
-- ----------------------------
DROP TABLE IF EXISTS `yii2_user`;
CREATE TABLE `yii2_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yii2_user
-- ----------------------------
