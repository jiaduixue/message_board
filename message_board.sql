/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : message_board

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2026-07-10 17:37:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '留言ID，主键自增',
  `username` varchar(50) NOT NULL COMMENT '留言人昵称',
  `email` varchar(100) DEFAULT NULL COMMENT '留言人邮箱（可选）',
  `content` text NOT NULL COMMENT '留言内容',
  `parent_id` int(11) DEFAULT '0' COMMENT '父留言ID，0表示顶级留言，非0表示回复某条留言',
  `ip_address` varchar(45) DEFAULT NULL COMMENT '留言人IP地址',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '留言创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '留言更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`) COMMENT '为父ID建立索引，加快查询某条留言下的回复'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='留言板数据表';

-- ----------------------------
-- Table structure for session
-- ----------------------------
DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `id` char(40) NOT NULL,
  `expire` int(11) unsigned NOT NULL,
  `data` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `auth_key` varchar(255) DEFAULT NULL,
  `real_name` varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称/花名',
  `gender` enum('男','女','保密') DEFAULT '保密' COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '出生日期',
  `phone` varchar(20) DEFAULT NULL COMMENT '联系电话',
  `email` varchar(100) DEFAULT NULL COMMENT '电子邮箱',
  `avatar_url` varchar(255) DEFAULT NULL COMMENT '头像图片链接',
  `bio` varchar(500) DEFAULT NULL COMMENT '个人简介/一句话签名',
  `skills` text COMMENT '技能标签（如：Java, MySQL, Vue）',
  `github_link` varchar(255) DEFAULT NULL COMMENT 'GitHub 主页链接',
  `blog_link` varchar(255) DEFAULT NULL COMMENT '个人博客链接',
  `status` int(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '记录创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='我的个人信息表';
