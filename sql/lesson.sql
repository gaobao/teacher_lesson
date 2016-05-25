/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : lesson

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-05-03 09:05:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for le_attachment
-- ----------------------------
DROP TABLE IF EXISTS `le_attachment`;
CREATE TABLE `le_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(200) DEFAULT NULL COMMENT '文件名',
  `lesson_id` int(11) unsigned DEFAULT NULL COMMENT '课程id',
  `file_url` varchar(256) DEFAULT NULL COMMENT '文件地址',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '上传时间',
  PRIMARY KEY (`id`),
  KEY `material_lesson_id` (`lesson_id`) USING BTREE,
  CONSTRAINT `le_attachment_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `le_lesson` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='附件';

-- ----------------------------
-- Records of le_attachment
-- ----------------------------
INSERT INTO `le_attachment` VALUES ('4', '教师.docx', '7', '95c4588f4472b4fed8f05a63e40640de.docx', '2016-04-28 23:11:38');
INSERT INTO `le_attachment` VALUES ('5', 'lesson.sql', '7', '495b475b3d3e07f8483aaf6d899b6d23.sql', '2016-04-28 23:23:47');

-- ----------------------------
-- Table structure for le_job
-- ----------------------------
DROP TABLE IF EXISTS `le_job`;
CREATE TABLE `le_job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(200) DEFAULT NULL COMMENT '作业名称',
  `filename` varchar(200) DEFAULT NULL COMMENT '上传作业名称',
  `lesson_id` int(10) unsigned NOT NULL COMMENT '课程id',
  `end_time` datetime NOT NULL COMMENT '作业截止时间',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '作业发布时间',
  PRIMARY KEY (`id`),
  KEY `job_lesson_id` (`lesson_id`),
  CONSTRAINT `job_lesson_id` FOREIGN KEY (`lesson_id`) REFERENCES `le_lesson` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='课程作业';

-- ----------------------------
-- Records of le_job
-- ----------------------------
INSERT INTO `le_job` VALUES ('1', '作业测试', '31a7d390b3f8aaeb078cb9e89cc3d425.docx', '7', '2016-04-06 06:30:00', '2016-04-28 00:58:08');
INSERT INTO `le_job` VALUES ('2', '作业测试2', '49b8d4103348c880af427c96ed788010.docx', '7', '2016-05-02 09:35:00', '2016-05-02 12:34:07');

-- ----------------------------
-- Table structure for le_job_record
-- ----------------------------
DROP TABLE IF EXISTS `le_job_record`;
CREATE TABLE `le_job_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(10) unsigned NOT NULL COMMENT '作业id',
  `student_id` int(10) unsigned NOT NULL COMMENT '学生id',
  `filename` text COMMENT '完成的作业文件',
  `grade` float(5,2) unsigned DEFAULT NULL,
  `comment` text,
  `finish_time` datetime DEFAULT NULL COMMENT '完成时间',
  `mark_time` datetime DEFAULT NULL COMMENT '批阅时间',
  PRIMARY KEY (`id`),
  KEY `record_job_id` (`job_id`),
  CONSTRAINT `record_job_id` FOREIGN KEY (`job_id`) REFERENCES `le_job` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='作业记录';

-- ----------------------------
-- Records of le_job_record
-- ----------------------------
INSERT INTO `le_job_record` VALUES ('1', '1', '5', '31a7d390b3f8aaeb078cb9e89cc3d425.docx', '20.00', '评语测试', '2016-05-01 17:06:19', '2016-05-01 17:06:24');

-- ----------------------------
-- Table structure for le_lesson
-- ----------------------------
DROP TABLE IF EXISTS `le_lesson`;
CREATE TABLE `le_lesson` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `lesson_name` varchar(200) DEFAULT NULL COMMENT '课程名称',
  `lesson_code` varchar(40) DEFAULT NULL COMMENT '课程唯一code',
  `teacher_id` int(11) unsigned NOT NULL COMMENT '教师id',
  `lesson_desc` text COMMENT '课程介绍',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lesson_teacher_id` (`teacher_id`),
  CONSTRAINT `lesson_teacher_id` FOREIGN KEY (`teacher_id`) REFERENCES `le_teacher` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='课程';

-- ----------------------------
-- Records of le_lesson
-- ----------------------------
INSERT INTO `le_lesson` VALUES ('1', '课程测试', 'K225WZ', '1', '测试', '2016-03-27 21:44:31');
INSERT INTO `le_lesson` VALUES ('2', '课程测试2', 'RQ0VFC', '1', '测试2', '2016-03-27 21:44:31');
INSERT INTO `le_lesson` VALUES ('4', '课程测试4', '15CQ48', '1', '测试', '2016-03-27 21:50:42');
INSERT INTO `le_lesson` VALUES ('5', '课程测试4', 'DBZLHJ', '1', '测试', '2016-03-27 21:50:49');
INSERT INTO `le_lesson` VALUES ('6', '课程测试4', 'GB2DQW', '1', '测试', '2016-03-27 21:50:50');
INSERT INTO `le_lesson` VALUES ('7', '测试开始', '74J3GK', '1', '测试', '2016-03-27 21:52:28');

-- ----------------------------
-- Table structure for le_lesson_record
-- ----------------------------
DROP TABLE IF EXISTS `le_lesson_record`;
CREATE TABLE `le_lesson_record` (
  `lesson_id` int(10) unsigned NOT NULL COMMENT '课程id',
  `student_id` int(10) unsigned NOT NULL COMMENT '学生id',
  `status` enum('wait','agree','refuse') DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`lesson_id`,`student_id`),
  KEY `record_lesson_id` (`lesson_id`),
  CONSTRAINT `record_lesson_id` FOREIGN KEY (`lesson_id`) REFERENCES `le_lesson` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='选课记录';

-- ----------------------------
-- Records of le_lesson_record
-- ----------------------------
INSERT INTO `le_lesson_record` VALUES ('6', '5', 'wait');
INSERT INTO `le_lesson_record` VALUES ('7', '5', 'agree');

-- ----------------------------
-- Table structure for le_material
-- ----------------------------
DROP TABLE IF EXISTS `le_material`;
CREATE TABLE `le_material` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(200) DEFAULT NULL COMMENT '文件名',
  `lesson_id` int(11) unsigned DEFAULT NULL COMMENT '课程id',
  `file_url` varchar(256) DEFAULT NULL COMMENT '文件地址',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '上传时间',
  PRIMARY KEY (`id`),
  KEY `material_lesson_id` (`lesson_id`),
  CONSTRAINT `material_lesson_id` FOREIGN KEY (`lesson_id`) REFERENCES `le_lesson` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='讲义';

-- ----------------------------
-- Records of le_material
-- ----------------------------
INSERT INTO `le_material` VALUES ('1', '新建文本文档.txt', '7', 'b486c3b6f501167208c4eb8db869397f.txt', '2016-04-28 23:25:11');

-- ----------------------------
-- Table structure for le_sign
-- ----------------------------
DROP TABLE IF EXISTS `le_sign`;
CREATE TABLE `le_sign` (
  `id` int(11) NOT NULL,
  `sign_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '签到名称',
  `sign_code` int(6) unsigned NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('over','begin') CHARACTER SET utf8 NOT NULL DEFAULT 'begin' COMMENT '签到',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of le_sign
-- ----------------------------

-- ----------------------------
-- Table structure for le_sign_record
-- ----------------------------
DROP TABLE IF EXISTS `le_sign_record`;
CREATE TABLE `le_sign_record` (
  `id` int(11) NOT NULL,
  `sign_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sign_id` (`sign_id`),
  CONSTRAINT `sign_id` FOREIGN KEY (`sign_id`) REFERENCES `le_sign` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of le_sign_record
-- ----------------------------

-- ----------------------------
-- Table structure for le_student
-- ----------------------------
DROP TABLE IF EXISTS `le_student`;
CREATE TABLE `le_student` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '唯一id',
  `student_id` char(8) DEFAULT NULL COMMENT '学号',
  `name` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `image_url` varchar(256) DEFAULT NULL COMMENT '头像地址',
  `passwd` char(40) DEFAULT NULL COMMENT '密码',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `remark` varchar(200) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='学生';

-- ----------------------------
-- Records of le_student
-- ----------------------------
INSERT INTO `le_student` VALUES ('1', null, null, null, '6adfb183a4a2c94a2f92dab5ade762a47889a5a1', 'test@test.com', null);
INSERT INTO `le_student` VALUES ('2', null, null, null, '7c4a8d09ca3762af61e59520943dc26494f8941b', 'gaoxu@test.com', null);
INSERT INTO `le_student` VALUES ('3', null, null, null, '7c4a8d09ca3762af61e59520943dc26494f8941b', 'gaoxu@163.com', null);
INSERT INTO `le_student` VALUES ('4', null, null, null, '7c4a8d09ca3762af61e59520943dc26494f8941b', 'gaoxu1@163.com', null);
INSERT INTO `le_student` VALUES ('5', '03113333', '测试2', '0b384da745eb54b4a30324e7651172d0.jpg', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'student@test.com', null);

-- ----------------------------
-- Table structure for le_teacher
-- ----------------------------
DROP TABLE IF EXISTS `le_teacher`;
CREATE TABLE `le_teacher` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `teacher_id` char(10) DEFAULT NULL COMMENT '教工号',
  `name` varchar(20) DEFAULT NULL COMMENT '教师姓名',
  `passwd` varchar(40) DEFAULT NULL COMMENT '密码',
  `image_url` varchar(256) DEFAULT NULL COMMENT '头像地址',
  `email` varchar(200) DEFAULT NULL COMMENT '邮箱',
  `remark` varchar(256) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='教师信息表';

-- ----------------------------
-- Records of le_teacher
-- ----------------------------
INSERT INTO `le_teacher` VALUES ('1', '12345678', '你好', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'afb30bcf4ed6a373f8d7bea7de3b880d.jpg', 'teacher@test.com', null);
