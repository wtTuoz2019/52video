<?php exit;?>DROP TABLE IF EXISTS dc_user
CREATE TABLE `dc_user` (  `uid` int(10) NOT NULL AUTO_INCREMENT,  `gid` int(10) DEFAULT '1',  `username` varchar(250) DEFAULT NULL,  `nicename` varchar(250) DEFAULT NULL,  `email` varchar(250) DEFAULT NULL,  `password` varchar(250) DEFAULT NULL,  `avatar` varchar(250) DEFAULT NULL,  `salt` varchar(250) DEFAULT NULL,  `reg_time` int(10) DEFAULT NULL,  `reg_ip` varchar(250) DEFAULT NULL,  `last_time` int(10) DEFAULT NULL,  `last_ip` varchar(250) DEFAULT NULL,  `status` int(1) DEFAULT '1' COMMENT '审核',  `verify_type` int(10) DEFAULT NULL,  PRIMARY KEY (`uid`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS dc_user_append
CREATE TABLE `dc_user_append` (  `id` int(10) NOT NULL AUTO_INCREMENT,  `uid` int(10) DEFAULT NULL,  `sex` int(10) DEFAULT NULL,  `tel` varchar(250) DEFAULT NULL,  `qq` varchar(250) DEFAULT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS dc_user_collection
CREATE TABLE `dc_user_collection` (  `uid` int(10) DEFAULT NULL,  `aid` int(10) DEFAULT NULL,  `remark` varchar(250) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS dc_user_config
CREATE TABLE `dc_user_config` (  `name` varchar(250) DEFAULT NULL,  `value` text) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO dc_user_config VALUES('base_name','duxcms 会员中心')
INSERT INTO dc_user_config VALUES('base_welcome','DUXCMS - 专注于企业、政府建站系统')
INSERT INTO dc_user_config VALUES('base_keywords','duxcms,dux用户中心')
INSERT INTO dc_user_config VALUES('base_description','dux会员中心基于duxcms插件进行开发')
INSERT INTO dc_user_config VALUES('base_status','1')
INSERT INTO dc_user_config VALUES('base_statistical','')
INSERT INTO dc_user_config VALUES('reg_status','1')
INSERT INTO dc_user_config VALUES('reg_agreement','当您申请用户时，表示您已经同意遵守本规章。 欢迎您加入本站点参加交流和讨论，本站点为公共论坛，为维护网上公共秩序和社会稳定，请您自觉遵守以下条款： <br>\n一、不得利用本站危害国家安全、泄露国家秘密，不得侵犯国家社会集体的和公民的合法权益，不得利用本站制作、复制和传播下列信息： <br>\n（一）煽动抗拒、破坏宪法和法律、行政法规实施的；\n（二）煽动颠覆国家政权，推翻社会主义制度的；<br>\n（三）煽动分裂国家、破坏国家统一的；<br>\n（四）煽动民族仇恨、民族歧视，破坏民族团结的；<br>\n（五）捏造或者歪曲事实，散布谣言，扰乱社会秩序的；<br>\n（六）宣扬封建迷信、淫秽、色情、赌博、暴力、凶杀、恐怖、教唆犯罪的；<br>\n（七）公然侮辱他人或者捏造事实诽谤他人的，或者进行其他恶意攻击的；<br>\n（八）损害国家机关信誉的；<br>\n（九）其他违反宪法和法律行政法规的；<br>\n（十）进行商业广告行为的。<br>\n二、互相尊重，对自己的言论和行为负责。<br>\n三、禁止在申请用户时使用相关本站的词汇，或是带有侮辱、毁谤、造谣类的或是有其含义的各种语言进行注册用户，否则我们会将其删除。<br>\n四、禁止以任何方式对本站进行各种破坏行为。<br>\n五、如果您有违反国家相关法律法规的行为，本站概不负责，您的登录论坛信息均被记录无疑，必要时，我们会向相关的国家管理部门提供此类信息。 ')
INSERT INTO dc_user_config VALUES('reg_filter','创始人,管理员,admin,duxcms')
INSERT INTO dc_user_config VALUES('reg_interval','0')
INSERT INTO dc_user_config VALUES('reg_audit','1')
INSERT INTO dc_user_config VALUES('reg_activation_title','来自{sitename}的注册激活邮件')
INSERT INTO dc_user_config VALUES('reg_activation_content','尊敬的{username}，\n<br/>欢迎你注册成为{sitename}的会员！\n<br/>请点击下面的链接进行帐号的激活：\n<br/>{url}\n<br/>如果不能点击链接，请复制到浏览器地址输入框访问。\n<br/>\n<br/>{sitename}\n<br/>{time}')
INSERT INTO dc_user_config VALUES('reg_username_length_min','3')
INSERT INTO dc_user_config VALUES('reg_username_length_max','15')
INSERT INTO dc_user_config VALUES('reg_password_length_min','6')
INSERT INTO dc_user_config VALUES('reg_password_length_max','15')
INSERT INTO dc_user_config VALUES('login_shield_ip','')
INSERT INTO dc_user_config VALUES('login_forget_title','{username}您好，这是{sitename}发送给您的密码重置邮件')
INSERT INTO dc_user_config VALUES('login_forget_content','尊敬的{username}，这是来自{sitename}的密码重置邮件。\n点击下面的链接重置您的密码：<br/>\n{url}<br/>\n如果链接无法点击，请将链接粘贴到浏览器的地址栏中访问。<br/>\n{sitename} <br/>\n{time}')
INSERT INTO dc_user_config VALUES('verification_reg','1')
INSERT INTO dc_user_config VALUES('verification_login','1')
INSERT INTO dc_user_config VALUES('verification_forget','1')
INSERT INTO dc_user_config VALUES('smtp_host','smtp.qq.com')
INSERT INTO dc_user_config VALUES('smtp_port','25')
INSERT INTO dc_user_config VALUES('smtp_ssl','0')
INSERT INTO dc_user_config VALUES('smtp_username','244328880@qq.com')
INSERT INTO dc_user_config VALUES('smtp_password','')
INSERT INTO dc_user_config VALUES('smtp_auth','1')
INSERT INTO dc_user_config VALUES('smtp_form_to','244328880@qq.com')
INSERT INTO dc_user_config VALUES('smtp_form_name','duxcms')
INSERT INTO dc_user_config VALUES('smtp_debug','0')
INSERT INTO dc_user_config VALUES('reg_off_reason','会员中心暂时关闭注册中，具体开放注册请等待！')
INSERT INTO dc_user_config VALUES('base_off_reason','系统临时维护中...')
DROP TABLE IF EXISTS dc_user_field
CREATE TABLE `dc_user_field` (  `id` int(10) NOT NULL AUTO_INCREMENT,  `name` varchar(250) DEFAULT NULL COMMENT '描述',  `field` varchar(250) DEFAULT NULL COMMENT '字段名',  `type` int(10) DEFAULT '1' COMMENT '字段类型',  `property` int(10) DEFAULT NULL COMMENT '保护字段',  `len` int(10) DEFAULT NULL COMMENT '字段长度',  `decimal` int(10) DEFAULT NULL COMMENT '小数',  `default` varchar(250) DEFAULT NULL COMMENT '默认内容',  `sequence` int(10) DEFAULT '0' COMMENT '字段排序',  `config` text COMMENT '字段配置',  `must` int(10) DEFAULT '0' COMMENT '必须填写',  `reg` int(10) DEFAULT '0' COMMENT '注册时显示',  `verification` text COMMENT '正则验证',  `verification_tip` varchar(250) DEFAULT NULL COMMENT '验证提示',  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
INSERT INTO dc_user_field VALUES('1','性别','sex','8','2','10','0','3','0','男|1\n女|2\n保密|3','1','1','cb0dgVfkPZrQMoyIqiGE1FqOizDZP4bUrAxHJLyAy6HDo6O4','')
INSERT INTO dc_user_field VALUES('5','QQ号码','qq','1','1','250','0','','0','','1','0','1d01DJOSx5vLHYX/JtvXLWLQW0Lq9p5GFHO4dU72SHd+CMJ8DohZfTWrb+nY05HPQal35A','')
INSERT INTO dc_user_field VALUES('3','手机','tel','1','1','250','0','','0','','0','0','b8dazmzpcEhuutsedRNMKPexgqwmmhWnEXBDV9El2o8qt5Lf+oSWKgo1TjA','')
DROP TABLE IF EXISTS dc_user_friends
CREATE TABLE `dc_user_friends` (  `uid` int(10) DEFAULT NULL,  `fid` int(10) DEFAULT NULL,  `remark` varchar(250) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS dc_user_group
CREATE TABLE `dc_user_group` (  `gid` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户组ID',  `name` varchar(250) DEFAULT NULL COMMENT '用户组描述',  `special` int(10) DEFAULT '0',  `menu_power` text,  `model_power` text,  `upload_status` int(10) DEFAULT '0',  `upload_type` text,  `upload_size` int(10) DEFAULT NULL,  `upload_total_size` int(10) DEFAULT NULL,  PRIMARY KEY (`gid`)) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
INSERT INTO dc_user_group VALUES('1','普通会员','1','a:1:{i:0;s:1:\"1\";}','a:2:{i:2;a:2:{i:0;s:1:\"2\";i:1;s:5:\"visit\";}i:3;a:2:{i:0;s:1:\"3\";i:1;s:5:\"visit\";}}','0','','2','100')
INSERT INTO dc_user_group VALUES('2','禁止会员','1','N;','a:2:{i:2;a:2:{i:0;s:4:\"view\";i:1;s:4:\"edit\";}i:3;a:1:{i:0;s:4:\"edit\";}}','0','','0','0')
DROP TABLE IF EXISTS dc_user_menu
CREATE TABLE `dc_user_menu` (  `id` int(10) NOT NULL AUTO_INCREMENT,  `pid` int(10) DEFAULT NULL,  `name` varchar(250) DEFAULT NULL,  `module` varchar(250) DEFAULT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;
INSERT INTO dc_user_menu VALUES('1','0','信息管理','')
INSERT INTO dc_user_menu VALUES('2','1','站内短信','message')
INSERT INTO dc_user_menu VALUES('3','1','我的好友','friends')
INSERT INTO dc_user_menu VALUES('4','1','我的收藏','collection')
DROP TABLE IF EXISTS dc_user_message
CREATE TABLE `dc_user_message` (  `mid` int(10) NOT NULL AUTO_INCREMENT,  `title` varchar(250) DEFAULT NULL,  `content` varchar(250) DEFAULT NULL,  `time` int(10) DEFAULT NULL,  `ip` varchar(250) DEFAULT NULL,  `view` int(10) DEFAULT '0',  PRIMARY KEY (`mid`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS dc_user_message_relation
CREATE TABLE `dc_user_message_relation` (  `mid` int(10) DEFAULT NULL,  `uid` int(10) DEFAULT NULL,  `to_uid` int(10) DEFAULT NULL,  `system` int(10) DEFAULT '0',  `del` int(10) DEFAULT '0',  `to_del` int(10) DEFAULT '0') ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS dc_user_power
CREATE TABLE `dc_user_power` (  `sequence` int(10) DEFAULT NULL,  `action` varchar(250) DEFAULT NULL,  `name` varchar(250) DEFAULT NULL,  `pid` int(10) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO dc_user_power VALUES('1','visit','使用','2')
INSERT INTO dc_user_power VALUES('1','visit','使用','3')
INSERT INTO dc_user_power VALUES('1','visit','使用','4')
DROP TABLE IF EXISTS dc_user_verify
CREATE TABLE `dc_user_verify` (  `vid` int(10) NOT NULL AUTO_INCREMENT,  `code` varchar(250) DEFAULT NULL,  `starttime` int(10) DEFAULT NULL,  `stoptime` int(10) DEFAULT '0',  PRIMARY KEY (`vid`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS dc_user_verify_forget
CREATE TABLE `dc_user_verify_forget` (  `uid` int(10) DEFAULT NULL,  `vid` int(10) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS dc_user_verify_reg
CREATE TABLE `dc_user_verify_reg` (  `uid` int(10) DEFAULT NULL,  `vid` int(10) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
