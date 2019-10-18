/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : db_syb

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-07-07 19:11:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for syb_admin
-- ----------------------------
DROP TABLE IF EXISTS `syb_admin`;
CREATE TABLE `syb_admin` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(100) NOT NULL,
  `admin_password` varchar(100) NOT NULL,
  `add_at` datetime NOT NULL,
  `update_at` datetime DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of syb_admin
-- ----------------------------
INSERT INTO `syb_admin` VALUES ('1', 'admin', 'syb', '2019-02-20 22:49:02', '0000-00-00 00:00:00', '2019-07-07 19:10:51');

-- ----------------------------
-- Table structure for syb_exam_paper
-- ----------------------------
DROP TABLE IF EXISTS `syb_exam_paper`;
CREATE TABLE `syb_exam_paper` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `finish_time` int(3) DEFAULT NULL COMMENT '完成时间',
  `exam_num` int(3) DEFAULT NULL COMMENT '考试有几场',
  `stu_num` int(4) DEFAULT NULL COMMENT '每场容纳学生数',
  `question_2_id` longtext COMMENT '抽到的判断题题目id对应的数组',
  `question_4_id` longtext COMMENT '抽到的单选题题目id组成的数组',
  `score_2` tinyint(2) DEFAULT NULL COMMENT '判断题分支',
  `score_4` tinyint(2) DEFAULT NULL COMMENT '单选题分值',
  `add_time` datetime DEFAULT NULL,
  `operator` varchar(100) DEFAULT NULL COMMENT '谁组卷',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of syb_exam_paper
-- ----------------------------

-- ----------------------------
-- Table structure for syb_formal_exam
-- ----------------------------
DROP TABLE IF EXISTS `syb_formal_exam`;
CREATE TABLE `syb_formal_exam` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `student_id` int(20) DEFAULT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `submit_time` datetime DEFAULT NULL,
  `grades` int(3) DEFAULT NULL,
  `detail` longtext,
  `answer_ok` longtext,
  `paper_id` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of syb_formal_exam
-- ----------------------------
INSERT INTO `syb_formal_exam` VALUES ('30', '12', '12', '2019-05-24 15:27:15', '30', '[\"y\",\"y\",\"y\",\"y\",\"y\",\"y\",\"y\",\"y\",\"y\",\"y\",\"a\",\"a\",\"c\",\"a\",\"c\",\"c\",\"a\",\"c\",\"c\",\"a\"]', '[\"y\",\"n\",\"n\",\"y\",\"n\",\"y\",\"y\",\"n\",\"n\",\"y\",\"b\",\"d\",\"c\",\"b\",\"d\",\"b\",\"b\",\"a\",\"a\",\"b\"]', '30');

-- ----------------------------
-- Table structure for syb_mock_test_exam
-- ----------------------------
DROP TABLE IF EXISTS `syb_mock_test_exam`;
CREATE TABLE `syb_mock_test_exam` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `student_id` int(20) DEFAULT NULL,
  `submit_time` datetime DEFAULT NULL,
  `grades` int(3) DEFAULT NULL,
  `detail` longtext,
  `answer_ok` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of syb_mock_test_exam
-- ----------------------------
INSERT INTO `syb_mock_test_exam` VALUES ('24', '20151211', '2019-05-24 10:47:59', '25', '[\"y\",\"y\",\"a\",\"c\"]', '[\"n\",\"y\",\"d\",\"b\"]');

-- ----------------------------
-- Table structure for syb_msg
-- ----------------------------
DROP TABLE IF EXISTS `syb_msg`;
CREATE TABLE `syb_msg` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `sender` varchar(255) NOT NULL,
  `receiver` int(10) NOT NULL,
  `content` longtext NOT NULL,
  `send_time` datetime NOT NULL,
  `viewor` tinyint(1) DEFAULT '0' COMMENT '查看与否，默认未查看0，查看后置为1',
  `title` varchar(255) DEFAULT NULL,
  `state` tinyint(1) DEFAULT '1' COMMENT '删除为0，默认为1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of syb_msg
-- ----------------------------
INSERT INTO `syb_msg` VALUES ('23', 'admin', '4', '发给用户4的内容\n1\n2\n3', '2019-04-10 10:39:21', '1', '发给用户4的内容标题', '0');
INSERT INTO `syb_msg` VALUES ('24', 'admin', '2015121', '给2015121用户发送的消息内容\n。。。', '2019-04-10 18:47:40', '1', '给2015121用户发送的消息标题', '0');
INSERT INTO `syb_msg` VALUES ('25', 'admin', '2015121', '1', '2019-04-10 18:57:37', '1', '1', '0');
INSERT INTO `syb_msg` VALUES ('26', 'admin', '20151211', '测试消息内容。测试消息内容。测试消息内容。测试消息内容。测试消息内容。测试消息内容。\n测试消息内容。\n测试消息内容。\n测试消息内容。测试消息内容。测试消息内容。', '2019-05-24 10:41:29', '1', '测试消息标题', '1');
INSERT INTO `syb_msg` VALUES ('27', 'admin', '1', ',', '2019-05-24 13:18:18', '0', ',', '1');
INSERT INTO `syb_msg` VALUES ('28', 'admin', '2015121', ',', '2019-05-24 13:18:39', '1', ',', '0');
INSERT INTO `syb_msg` VALUES ('29', 'admin', '201512122', '111111111111111111\nv\n\n\n\nc', '2019-05-24 15:12:24', '1', '11111111', '0');

-- ----------------------------
-- Table structure for syb_news
-- ----------------------------
DROP TABLE IF EXISTS `syb_news`;
CREATE TABLE `syb_news` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `operator` varchar(255) NOT NULL,
  `add_at` datetime NOT NULL,
  `seenums` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of syb_news
-- ----------------------------
INSERT INTO `syb_news` VALUES ('4', '标题1', '内容1\r\n内容neir\r\n内容如', 'admin', '2019-02-28 21:18:49', '38');
INSERT INTO `syb_news` VALUES ('5', '公告2', '公告2内容', 'admin', '2019-02-28 21:38:19', '17');
INSERT INTO `syb_news` VALUES ('6', '12121212', '121212121', 'admin', '2019-03-20 15:07:56', '7');
INSERT INTO `syb_news` VALUES ('7', '最新公告', '最新公告内容。。', 'admin', '2019-03-26 14:19:04', '4');
INSERT INTO `syb_news` VALUES ('8', '最后的测试公告', '最后的测试公告内容', 'admin', '2019-04-10 10:37:09', '7');
INSERT INTO `syb_news` VALUES ('9', '测试公告', '测试公告内容', 'admin', '2019-04-10 18:46:50', '7');
INSERT INTO `syb_news` VALUES ('10', '测试公告', '测试公告内容测试公告内容测试公告内容测试公告内容\r\n测试公告内容测试公告内容测试公告内容测试公告内容\r\n测试公告内容\r\n测试公告内容\r\n测试公告内容\r\n测试公告内容测试公告内容\r\n测试公告内容测试公告内容测试公告内容\r\n测试公告内容测试公告内容测试公告内容', 'admin', '2019-05-24 10:40:43', '3');
INSERT INTO `syb_news` VALUES ('11', '1', '1', 'admin', '2019-05-24 13:17:42', '3');
INSERT INTO `syb_news` VALUES ('12', '222222222', '222222222222222222222v222222222222222222222\r\n222222222222222222222\r\n222222222222222222222\r\n222222222222222222222v222222222222222222222222222222222222222222222222222222222222222', 'admin', '2019-05-24 15:11:20', '4');

-- ----------------------------
-- Table structure for syb_question_bank
-- ----------------------------
DROP TABLE IF EXISTS `syb_question_bank`;
CREATE TABLE `syb_question_bank` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `question_type` tinyint(2) NOT NULL COMMENT '问题类型：2判断  4选择 44多选',
  `title` varchar(255) NOT NULL COMMENT '问题题目',
  `answer_a` varchar(255) DEFAULT NULL,
  `answer_b` varchar(255) DEFAULT NULL,
  `answer_c` varchar(255) DEFAULT NULL,
  `answer_d` varchar(255) DEFAULT NULL,
  `answer_ok` varchar(100) NOT NULL COMMENT '正确答案',
  `add_time` datetime NOT NULL COMMENT '出题时间',
  `test_point` varchar(255) NOT NULL COMMENT '考点',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=526 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of syb_question_bank
-- ----------------------------
INSERT INTO `syb_question_bank` VALUES ('421', '2', '招聘人员试用期一律为三个月', null, null, null, null, 'n', '2019-05-24 15:15:21', '试用期');
INSERT INTO `syb_question_bank` VALUES ('422', '2', '业主要根据自己企业的实际情况决定商业保险的险种，不要过度依赖保险', null, null, null, null, 'y', '2019-05-24 15:15:21', '保险');
INSERT INTO `syb_question_bank` VALUES ('423', '2', '流转税包括：增值税、营业税和海关关税等', null, null, null, null, 'y', '2019-05-24 15:15:21', '流转税');
INSERT INTO `syb_question_bank` VALUES ('424', '2', '创办企业只要域名了企业名称，就拥有了合法的身份', null, null, null, null, 'n', '2019-05-24 15:15:21', '企业');
INSERT INTO `syb_question_bank` VALUES ('425', '2', '个人独资企业不具有法人资格，投资人以其个人财产对企业债务承担无限责任', null, null, null, null, 'y', '2019-05-24 15:15:21', '企业');
INSERT INTO `syb_question_bank` VALUES ('426', '2', '非法人企业的法律地位是不具有法人资格，不能独立承担民事责任，能独立支配和处分所经营管理的财产 ', null, null, null, null, 'n', '2019-05-24 15:15:21', '企业');
INSERT INTO `syb_question_bank` VALUES ('427', '2', '对企业债务责任承担无限责任的企业法律形态，有个体经营、个人独资、合伙企业和有限责任公司四种类型', null, null, null, null, 'n', '2019-05-24 15:15:21', '企业');
INSERT INTO `syb_question_bank` VALUES ('428', '2', '个体工商户由家庭为主经营的，以家庭财产承担无限责任', null, null, null, null, 'y', '2019-05-24 15:15:21', '个体工商户');
INSERT INTO `syb_question_bank` VALUES ('429', '2', '附加产品包括：提供信贷、免费送货、质量保证、安装、售后服务', null, null, null, null, 'y', '2019-05-24 15:15:21', '产品');
INSERT INTO `syb_question_bank` VALUES ('430', '2', '附加产品是核心产品借以实现的形式，指产品的质量、设计、包装、品牌等要素', null, null, null, null, 'n', '2019-05-24 15:15:21', '产品');
INSERT INTO `syb_question_bank` VALUES ('431', '2', '如果你是零售商和批发商，那么提供的服务就是你的产品', null, null, null, null, 'n', '2019-05-24 15:15:21', '产品');
INSERT INTO `syb_question_bank` VALUES ('432', '2', '产品指你计划向顾客销售的东西，即你计划向目标客户提供的产品和服务的组合 ', null, null, null, null, 'y', '2019-05-24 15:15:21', '产品');
INSERT INTO `syb_question_bank` VALUES ('433', '2', '只要预测准确，决策就会是正确的', null, null, null, null, 'n', '2019-05-24 15:15:21', '决策');
INSERT INTO `syb_question_bank` VALUES ('434', '2', '市场调查与预测研究中，调查单位和报告单位通常是一致的', null, null, null, null, 'y', '2019-05-24 15:15:21', '决策');
INSERT INTO `syb_question_bank` VALUES ('435', '2', '调查内容较少，项目简单可采用面谈访问或留置问卷方式进行调查', null, null, null, null, 'n', '2019-05-24 15:15:21', '调查');
INSERT INTO `syb_question_bank` VALUES ('436', '2', '季节折扣是为扩大产品销路，生产企业向中间商提供促销津贴', null, null, null, null, 'n', '2019-05-24 15:15:21', '促销');
INSERT INTO `syb_question_bank` VALUES ('437', '2', '为产品定价有两种导向，一种是产品导向，另一种是价值导向', null, null, null, null, 'y', '2019-05-24 15:15:21', '产品');
INSERT INTO `syb_question_bank` VALUES ('438', '2', '现金流量计划如果某月出现负债说明当月是亏损的', null, null, null, null, 'y', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('439', '2', '制定利润计划主要包括销售成本计划和现金流量计划', null, null, null, null, 'y', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('440', '2', '制定价格的方法有差价法、成本法', null, null, null, null, 'y', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('441', '2', '设备折旧项目不会出现在现金流量计划里', null, null, null, null, 'y', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('442', '2', '成功的企业都要制定现金流量计划', null, null, null, null, 'y', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('443', '2', '利润来自销售收入减去企业经营成本', null, null, null, null, 'y', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('444', '2', '折旧是一种特殊成本', null, null, null, null, 'y', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('445', '2', '制定价格之前，必须先摸清成本', null, null, null, null, 'y', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('446', '2', '企业材料成本永远属于固定成本', null, null, null, null, 'n', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('447', '2', '流动资金是指企业日常运转所需要支出的资金', null, null, null, null, 'y', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('448', '2', '当你计划开办一个新企业时，你应该预测第一年中每月的利润', null, null, null, null, 'y', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('449', '2', '创办企业不必做销售预测', null, null, null, null, 'n', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('450', '2', '慎重地采购原材料和选择服务可以降低成本并提高利润', null, null, null, null, 'y', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('451', '2', '业主自己不必从企业拿工资', null, null, null, null, 'n', '2019-05-24 15:15:21', '业主');
INSERT INTO `syb_question_bank` VALUES ('452', '2', '企业的库存多多益善', null, null, null, null, 'n', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('453', '2', '租房的改造、装修和租金都属于流动资金支出部分', null, null, null, null, 'n', '2019-05-24 15:15:21', '现金流量计划');
INSERT INTO `syb_question_bank` VALUES ('454', '2', '机会是指周边地区存在的对企业有利的事情', null, null, null, null, 'y', '2019-05-24 15:15:21', '企业');
INSERT INTO `syb_question_bank` VALUES ('455', '2', '如果你用自己的积蓄去开办企业，就要把自己所有的钱投进去', null, null, null, null, 'n', '2019-05-24 15:15:21', '企业');
INSERT INTO `syb_question_bank` VALUES ('456', '2', '一个成功的企业始于正确的理念和好的构思，合理而又周密的企业构思可以避免日后的失望和损失 ', null, null, null, null, 'y', '2019-05-24 15:15:21', '企业构思');
INSERT INTO `syb_question_bank` VALUES ('457', '2', '一个成功的企业始于正确的理念和庞大的资金', null, null, null, null, 'n', '2019-05-24 15:15:21', '企业');
INSERT INTO `syb_question_bank` VALUES ('458', '2', '一个好的企业构思必须从个人爱好和顾客需要两个方面出发', null, null, null, null, 'n', '2019-05-24 15:15:21', '企业构思');
INSERT INTO `syb_question_bank` VALUES ('459', '2', '只有既能满足市场需要而又懂行的企业构思才是可行的', null, null, null, null, 'y', '2019-05-24 15:15:21', '企业构思');
INSERT INTO `syb_question_bank` VALUES ('460', '2', '不具备创业必须的所有素质或技能，创业一定不会成功', null, null, null, null, 'n', '2019-05-24 15:15:21', '创业');
INSERT INTO `syb_question_bank` VALUES ('461', '2', '一个好的企业构思只要包含“必须有市场机会”这个方面就行', null, null, null, null, 'n', '2019-05-24 15:15:21', '企业构思');
INSERT INTO `syb_question_bank` VALUES ('462', '2', '同行是冤家，对于竞争对手过多的了解是没有多少意义的', null, null, null, null, 'n', '2019-05-24 15:15:21', '竞争');
INSERT INTO `syb_question_bank` VALUES ('463', '2', '如果你的企业是服务型企业，那么所提供的服务就是你的产品', null, null, null, null, 'y', '2019-05-24 15:15:21', '产品');
INSERT INTO `syb_question_bank` VALUES ('464', '2', 'SWTO 分析是指优势、劣势、机会、威胁', null, null, null, null, 'n', '2019-05-24 15:15:21', 'SWTO');
INSERT INTO `syb_question_bank` VALUES ('465', '2', '一个企业只能从事贸易、制造、服务等经营类型中的一种类型', null, null, null, null, 'n', '2019-05-24 15:15:21', '企业');
INSERT INTO `syb_question_bank` VALUES ('466', '2', 'SWOT 的优势和劣势是属于外部因素', null, null, null, null, 'n', '2019-05-24 15:15:21', 'SWTO');
INSERT INTO `syb_question_bank` VALUES ('467', '2', '微小企业的创办原则：规模小、志向大、计算精', null, null, null, null, 'y', '2019-05-24 15:15:21', '创业');
INSERT INTO `syb_question_bank` VALUES ('468', '2', '促销的实质是商品交换', null, null, null, null, 'n', '2019-05-24 15:15:21', '促销');
INSERT INTO `syb_question_bank` VALUES ('469', '2', '当企业以”推“的策略为主进行促销时，对渠道的依赖性较大', null, null, null, null, 'y', '2019-05-24 15:15:21', '促销');
INSERT INTO `syb_question_bank` VALUES ('470', '2', '推销员除了要负责为企业推销产品外，还应该成为顾客的顾问', null, null, null, null, 'y', '2019-05-24 15:15:21', '促销');
INSERT INTO `syb_question_bank` VALUES ('471', '2', '互联网作为市场营销调研工具的主要优势在于它成本低', null, null, null, null, 'y', '2019-05-24 15:15:21', '互联网销售');
INSERT INTO `syb_question_bank` VALUES ('472', '2', '适合在互联网上销售的产品，主要是一些鲜活商品', null, null, null, null, 'n', '2019-05-24 15:15:21', '互联网销售');
INSERT INTO `syb_question_bank` VALUES ('473', '2', '市场细分是目标市场营销的基础', null, null, null, null, 'y', '2019-05-24 15:15:21', '市场营销');
INSERT INTO `syb_question_bank` VALUES ('474', '2', '企业促销组合有三种方式组成，即广告、人员推销和公共关系', null, null, null, null, 'n', '2019-05-24 15:15:21', '促销');
INSERT INTO `syb_question_bank` VALUES ('475', '2', '促销就是企业为其产品作广告', null, null, null, null, 'n', '2019-05-24 15:15:21', '促销');
INSERT INTO `syb_question_bank` VALUES ('476', '2', '直销是指生产商直接把产品销售到顾客手中，去除中间的环节', null, null, null, null, 'y', '2019-05-24 15:15:21', '促销');
INSERT INTO `syb_question_bank` VALUES ('477', '2', '广告中禁止使用国家级、最高级、最佳等用语', null, null, null, null, 'y', '2019-05-24 15:15:21', '促销');
INSERT INTO `syb_question_bank` VALUES ('478', '2', '制定产品价格时，你只需要考虑产品的成本', null, null, null, null, 'n', '2019-05-24 15:15:21', '产品');
INSERT INTO `syb_question_bank` VALUES ('479', '2', '公司最直接的竞争者是那些同一行业同一战略群体的公司', null, null, null, null, 'y', '2019-05-24 15:15:21', '竞争');
INSERT INTO `syb_question_bank` VALUES ('480', '2', '在同类产品市场上，同一细分市场的顾客需求具有较多的共同性', null, null, null, null, 'y', '2019-05-24 15:15:21', '市场');
INSERT INTO `syb_question_bank` VALUES ('481', '2', '市场定位是市场细分的基础', null, null, null, null, 'y', '2019-05-24 15:15:21', '市场');
INSERT INTO `syb_question_bank` VALUES ('482', '2', '定价目标是决定企业价格策略的一个重要因素', null, null, null, null, 'y', '2019-05-24 15:15:21', '企业');
INSERT INTO `syb_question_bank` VALUES ('483', '2', '经营者为了排挤竞争对手或独占市场，可以低于成本的价格倾销', null, null, null, null, 'n', '2019-05-24 15:15:21', '竞争');
INSERT INTO `syb_question_bank` VALUES ('484', '2', '创业成功的基础是做好市场调查分析', null, null, null, null, 'y', '2019-05-24 15:15:21', '创业');
INSERT INTO `syb_question_bank` VALUES ('485', '2', '恩格尔定律认为：当家庭收入增加时，多种消费的比例会相应增加，而用于购买食物支出的比例会下降', null, null, null, null, 'y', '2019-05-24 15:15:21', '收入与消费');
INSERT INTO `syb_question_bank` VALUES ('486', '2', '差异性市场营销能节省各项成本和费用', null, null, null, null, 'y', '2019-05-24 15:15:21', '市场营销');
INSERT INTO `syb_question_bank` VALUES ('487', '2', '在填写创业计划书时，应尽量用简单而准确的词语来描述每件事', null, null, null, null, 'y', '2019-05-24 15:15:21', '创业');
INSERT INTO `syb_question_bank` VALUES ('488', '2', '创业热情是必要的，但要注意保护创业计划书中的商业秘密', null, null, null, null, 'y', '2019-05-24 15:15:21', '创业');
INSERT INTO `syb_question_bank` VALUES ('489', '2', '好的创业计划书是获得投资的关键', null, null, null, null, 'y', '2019-05-24 15:15:21', '创业');
INSERT INTO `syb_question_bank` VALUES ('490', '2', '市场调查中抽样访问的顾客可随意选取，不一定要针对潜在顾客', null, null, null, null, 'n', '2019-05-24 15:15:21', '调查');
INSERT INTO `syb_question_bank` VALUES ('491', '2', '通常企业会采用市场调查的方法来收集、处理和分析有关信息', null, null, null, null, 'y', '2019-05-24 15:15:21', '调查');
INSERT INTO `syb_question_bank` VALUES ('492', '2', '调查问卷中，封闭性问题会给出可能的答案供选择', null, null, null, null, 'y', '2019-05-24 15:15:21', '调查');
INSERT INTO `syb_question_bank` VALUES ('493', '2', '执行调研计划这一工作必须自己完成，不能外包', null, null, null, null, 'n', '2019-05-24 15:15:21', '调查');
INSERT INTO `syb_question_bank` VALUES ('494', '2', '使命是企业实现所有设定目标的最终目的', null, null, null, null, 'y', '2019-05-24 15:15:21', '企业');
INSERT INTO `syb_question_bank` VALUES ('495', '4', '属于有形差异化的是哪一项', '产品产量', '雇员数量', '企业福利', '促销活动', 'd', '2019-05-24 15:15:21', '差异化');
INSERT INTO `syb_question_bank` VALUES ('496', '4', '差异化的领域主要有哪两个方面', '有形和无形', '虚拟和实体', '个体和企业', '公有和私有', 'a', '2019-05-24 15:15:21', '差异化');
INSERT INTO `syb_question_bank` VALUES ('497', '4', '差异化的立足点是', '企业', '顾客', '学生', '职员', 'b', '2019-05-24 15:15:21', '差异化');
INSERT INTO `syb_question_bank` VALUES ('498', '4', '市场定位的方法', '区域定位、阶层定位、职业定位、个性定位、年龄定位', '区域定位、阶层定位、职业定位、价格定位、年龄定位', '阶层定位、职业定位、价格定位、年龄定位、品牌定位', '阶层定位、职业定位、价格定位、年龄定位、个性定位', 'a', '2019-05-24 15:15:21', '市场');
INSERT INTO `syb_question_bank` VALUES ('499', '4', '所谓营销组合是哪个', '产品、质量、价格、促销', '产品、价格、分销、促销', '产品、质量、分销、产量', '产品、价格、产量、销量', 'b', '2019-05-24 15:15:21', '市场营销');
INSERT INTO `syb_question_bank` VALUES ('500', '4', '不属于市场定位的方式', '避强定位', '分析定位', '重新定位', '迎头定位', 'b', '2019-05-24 15:15:21', '市场');
INSERT INTO `syb_question_bank` VALUES ('501', '4', '差异化策略带来的风险', '供给大众化', '被更为差异化的产品替代', '无法使消费者对品牌忠诚', '增加的成本可能会超过差异化带来的利润', 'c', '2019-05-24 15:15:21', '差异化');
INSERT INTO `syb_question_bank` VALUES ('502', '4', '差异化不能给消费者带来的利益', '产品质量更好', '产品价格更低', '购买力上升', '享受的服务更好', 'c', '2019-05-24 15:15:21', '差异化');
INSERT INTO `syb_question_bank` VALUES ('503', '4', '以下哪一项不是给生产者带来的利益', '能有效地回避正面碰撞和竞争', '能够使生产者垄断市场价格', '削弱购买者手上的权力，因为市场缺乏可比的选择', '阻碍后来的竞争者，因为在差异化策略下，得到满足的顾客会相应产生品牌忠诚度', 'b', '2019-05-24 15:15:21', '利益');
INSERT INTO `syb_question_bank` VALUES ('504', '4', '差异化带来的利益有哪两种', '对供给者或生产者、消费者带来利益', '给生产商、代理商带来利益', '给企业家、创业者带来利益', '给政府、群众带来利益', 'a', '2019-05-24 15:15:21', '差异化');
INSERT INTO `syb_question_bank` VALUES ('505', '4', '所谓产品定位', '企业对目标消费者或目标消费者市场的选择', '产品定位就是市场定位', '企业对应什么样的产品来满足目标消费者或目标消费市场的需求', '竞争者现有产品在市场上所处的位置', 'c', '2019-05-24 15:15:21', '产品');
INSERT INTO `syb_question_bank` VALUES ('506', '4', '产品定位的基本原则', '适应性原则和竞争性原则', '统一性原则和适应性原则', '特殊性原则和适应性原则', '合理性原则和竞争性原则', 'a', '2019-05-24 15:15:21', '产品');
INSERT INTO `syb_question_bank` VALUES ('507', '4', '合伙企业的业主是（ ）', '一个人', '两个人以上', '三个人', '多少个都可以', 'b', '2019-05-24 15:15:21', '业主');
INSERT INTO `syb_question_bank` VALUES ('508', '4', '个体工商户业主是（ ）', '一个人', '一个家庭', '两个人以上', '一个人或者家庭', 'd', '2019-05-24 15:15:21', '业主');
INSERT INTO `syb_question_bank` VALUES ('509', '4', '要缴纳企业所得税是（ ）企业', '个体工商户', '个人独资企业', '合伙企业', '股份合作制企业', 'd', '2019-05-24 15:15:21', '企业');
INSERT INTO `syb_question_bank` VALUES ('510', '4', '个体工商户注册资本是', '无资本数量限制', '注册资本10 万元以上', '注册资本30 万元以上', '注册资本50 万元以上', 'a', '2019-05-24 15:15:21', '个体工商户');
INSERT INTO `syb_question_bank` VALUES ('511', '4', '以下哪种法律形态的企业可以起字号（ ）', '有限责任公司', '个体工商户', '个人独资企业', '合伙企业', 'b', '2019-05-24 15:15:21', '个体工商户');
INSERT INTO `syb_question_bank` VALUES ('512', '4', '我市某就业困难人员， 想开办一家企业， 希望尽可能多地减免各项费用和税收。其业务规模不太大， 不需要多少人手。 就目前的优惠政策而言， 最好把企业注册为（） ', '有限责任公司', '个体工商户', '个人独资企业', '合伙企业', 'b', '2019-05-24 15:15:21', '个体工商户');
INSERT INTO `syb_question_bank` VALUES ('513', '4', '包装决策的原则是保护、经济、（ ）、促销等', '口碑', '奢华', '复杂', '方便', 'd', '2019-05-24 15:15:21', '包装决策');
INSERT INTO `syb_question_bank` VALUES ('514', '4', '企业产品决策的基本内容，首先是向市场投放（ ）的产品', '标新立异', '适销对路', '特别', '大众化', 'c', '2019-05-24 15:15:21', '包装决策');
INSERT INTO `syb_question_bank` VALUES ('515', '4', '（  ）是指主体提供给客体参与及消费体验与分享的活动集', '欲望', '需要', '服务', '体验', 'c', '2019-05-24 15:15:21', '服务');
INSERT INTO `syb_question_bank` VALUES ('516', '4', '核心产品的决策原则是：选用有（ ）的使用价值去满足目标顾客所追求的效用或利益 ', '驱动力', '驱使力', '决策力', '驱策力', 'd', '2019-05-24 15:15:21', '利益');
INSERT INTO `syb_question_bank` VALUES ('517', '4', '美国学者莱维特曾经指出；“新的竞争不是发生在各个公司的工厂生产什么产品，而是发生在其产品能提供何种（）” ', '附加利润', '附加成本', '附加产品', '附加利益', 'd', '2019-05-24 15:15:21', '利益');
INSERT INTO `syb_question_bank` VALUES ('518', '4', '（ ）包括：提供信贷、免费送货、质量保证、安装、售后服务', '核心产品', '外部产品', '附加产品', '有型产品', 'c', '2019-05-24 15:15:21', '产品');
INSERT INTO `syb_question_bank` VALUES ('519', '4', '（ ）是指产品的使用价值，是顾客真正要买的东西，因而是在产品整体概念中也是最基本、 最主要的部分 ', '核心产品', '无形产品', '附加产品', '有型产品', 'a', '2019-05-24 15:15:21', '产品');
INSERT INTO `syb_question_bank` VALUES ('520', '4', '如果你是零售商和批发商，那么提供的（ ）就是你的产品', '商誉', '实物', '商标', '服务', 'b', '2019-05-24 15:15:21', '产品');
INSERT INTO `syb_question_bank` VALUES ('521', '4', '年度计划控制过程的第一步是', '确定目标', '评估执行情况', '规定企业任务', '选择目标市场', 'b', '2019-05-24 15:15:21', '年度计划');
INSERT INTO `syb_question_bank` VALUES ('522', '4', '当产品处于生命周期的引入期时，促销工作的重点是', '认识了解商品，提高知名度', '促成信任、购买', '增进信任与偏爱', '满足需求的多样性', 'a', '2019-05-24 15:15:21', '促销');
INSERT INTO `syb_question_bank` VALUES ('523', '4', '在市场对产品价格极为敏感，企业的生产成本和经营费用会随着生产经营的增加而下降， 降价不会引起实际和潜在的竞争， 企业宜对此产品采用 ', '撇脂定价', '渗透定价', '中间定价', '理解价值定价', 'b', '2019-05-24 15:15:21', '市场营销');
INSERT INTO `syb_question_bank` VALUES ('524', '4', '一个市场要跟人完全地划分，区割要做得非常明显，反映了资源利用的哪方面', '弹性利用', '有效利用', '集中利用', '分散利用', 'b', '2019-05-24 15:15:21', '市场');
INSERT INTO `syb_question_bank` VALUES ('525', '4', '不属于市场细分的是以下哪一个', '区域细分', '地理细分', '人口细分', '心理细分', 'a', '2019-05-24 15:15:21', '市场');

-- ----------------------------
-- Table structure for syb_question_type_exam
-- ----------------------------
DROP TABLE IF EXISTS `syb_question_type_exam`;
CREATE TABLE `syb_question_type_exam` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `student_id` int(20) NOT NULL,
  `question_type` tinyint(2) NOT NULL,
  `grade` int(4) NOT NULL,
  `grade_detail` longtext NOT NULL,
  `exam_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of syb_question_type_exam
-- ----------------------------
INSERT INTO `syb_question_type_exam` VALUES ('10', '2015122', '4', '7', '[\"b\",\"b\",\"b\",\"a\",\"a\",\"a\",\"b\",\"b\",\"d\",\"a\",\"a\",\"d\",\"b\",\"b\",\"a\",\"a\",\"c\",\"c\",\"a\",\"a\",\"b\",\"b\",\"d\",\"a\",\"c\",\"a\",\"d\",\"b\",\"c\",\"c\",\"a\"]', '2019-04-20 22:40:01');

-- ----------------------------
-- Table structure for syb_reg_exam
-- ----------------------------
DROP TABLE IF EXISTS `syb_reg_exam`;
CREATE TABLE `syb_reg_exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(20) NOT NULL,
  `reg_exam_state` tinyint(1) NOT NULL DEFAULT '0',
  `reg_exam_time` datetime NOT NULL,
  `exam_num` int(4) DEFAULT NULL COMMENT '选择的考试场次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of syb_reg_exam
-- ----------------------------
INSERT INTO `syb_reg_exam` VALUES ('40', '20151211', '1', '2019-05-24 10:48:10', '1');
INSERT INTO `syb_reg_exam` VALUES ('41', '201512122', '1', '2019-05-24 15:18:27', '2');
INSERT INTO `syb_reg_exam` VALUES ('42', '12', '0', '2019-05-24 15:26:48', '1');
INSERT INTO `syb_reg_exam` VALUES ('43', '2015121', '1', '2019-05-25 12:24:52', '3');

-- ----------------------------
-- Table structure for syb_test_point_exam
-- ----------------------------
DROP TABLE IF EXISTS `syb_test_point_exam`;
CREATE TABLE `syb_test_point_exam` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `student_id` int(20) NOT NULL,
  `test_point` varchar(255) NOT NULL COMMENT '考点',
  `grade` int(4) NOT NULL COMMENT '某个考点的成绩（正确个数）',
  `exam_time` datetime NOT NULL COMMENT '作答时间',
  `grade_detail` longtext COMMENT '作答详细记录',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of syb_test_point_exam
-- ----------------------------
INSERT INTO `syb_test_point_exam` VALUES ('109', '20151211', '试用期', '0', '2019-05-24 10:47:27', '[\"y\"]');
INSERT INTO `syb_test_point_exam` VALUES ('110', '12', '试用期', '0', '2019-05-24 12:48:43', '[\"y\"]');
INSERT INTO `syb_test_point_exam` VALUES ('111', '2015121', '流转税', '0', '2019-05-24 13:19:55', '[\"n\"]');
INSERT INTO `syb_test_point_exam` VALUES ('112', '201512122', '试用期', '0', '2019-05-24 15:15:35', '[\"y\"]');
INSERT INTO `syb_test_point_exam` VALUES ('113', '12', '试用期', '1', '2019-05-24 23:39:37', '[\"n\"]');
INSERT INTO `syb_test_point_exam` VALUES ('114', '2015121', '个体工商户', '2', '2019-05-25 12:24:13', '[\"y\",\"d\",\"d\",\"b\"]');
INSERT INTO `syb_test_point_exam` VALUES ('115', '2015121', '试用期', '1', '2019-05-25 14:41:54', '[\"n\"]');
INSERT INTO `syb_test_point_exam` VALUES ('116', '2015121', '试用期', '0', '2019-05-25 14:42:26', '[\"y\"]');

-- ----------------------------
-- Table structure for syb_user
-- ----------------------------
DROP TABLE IF EXISTS `syb_user`;
CREATE TABLE `syb_user` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `student_id` int(10) NOT NULL,
  `student_name` varchar(10) NOT NULL,
  `password` varchar(12) NOT NULL,
  `register_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of syb_user
-- ----------------------------
INSERT INTO `syb_user` VALUES ('1', '12', '王保清', '12590', '2019-02-13 13:06:36', null, '1', '');
INSERT INTO `syb_user` VALUES ('19', '1', '1', '111111', '2019-06-05 09:53:40', null, '1', '1@gmail.com');
