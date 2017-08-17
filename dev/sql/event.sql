/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : event

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-08-17 00:36:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for event_log
-- ----------------------------
DROP TABLE IF EXISTS `event_log`;
CREATE TABLE `event_log` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT '事件日志ID',
  `event` tinyint(4) NOT NULL COMMENT '事件ID',
  `user` varchar(32) NOT NULL DEFAULT '0' COMMENT '用户',
  `data` longtext NOT NULL COMMENT 'json数据',
  `ip` varchar(32) NOT NULL COMMENT 'ip地址',
  `created` int(11) NOT NULL COMMENT '事件事件',
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_type` (`id`,`event`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COMMENT='事件日志';

-- ----------------------------
-- Records of event_log
-- ----------------------------

-- ----------------------------
-- Table structure for event_module
-- ----------------------------
DROP TABLE IF EXISTS `event_module`;
CREATE TABLE `event_module` (
  `module` tinyint(4) DEFAULT NULL COMMENT '模块',
  `event` tinyint(4) DEFAULT NULL COMMENT '事件ID',
  `last_id` int(11) NOT NULL DEFAULT '0' COMMENT '最后执行的event_log.id',
  `event_ids` longtext COMMENT '未执行的ids"逗号分隔",示例(1,2,3,4,5)',
  UNIQUE KEY `module_type` (`module`,`event`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='事件模块执行记录';

-- ----------------------------
-- Records of event_module
-- ----------------------------
