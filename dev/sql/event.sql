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
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '事件日志ID',
  `event` int(11) NOT NULL COMMENT '事件ID',
  `user` varchar(32) NOT NULL DEFAULT '0' COMMENT '用户',
  `data` longtext NOT NULL COMMENT 'json数据',
  `ip` varchar(32) NOT NULL COMMENT 'ip地址',
  `created` int(11) NOT NULL COMMENT '事件创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_type` (`id`,`event`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='事件日志';


-- ----------------------------
-- Records of event_log
-- ----------------------------

-- ----------------------------
-- Table structure for event_module
-- ----------------------------
DROP TABLE IF EXISTS `event_module_log`;
CREATE TABLE `event_module_log` (
  `mid` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `module_name` varchar(64) DEFAULT NULL COMMENT '模块',
  `event_id` tinyint(4) DEFAULT NULL COMMENT '事件ID',
  `event_log_last_id` int(11) NOT NULL DEFAULT '0' COMMENT '最后执行的event_log.id',
  `event_log_ids` longtext COMMENT 'event_log表:未执行的ids"逗号分隔",示例(1,2,3,4,5)',
  PRIMARY KEY (`mid`),
  UNIQUE KEY `unique` (`module_name`,`event_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='事件模块执行记录';;



-- ----------------------------
-- Records of event_module
-- ----------------------------
