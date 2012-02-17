#
# Table structure for table `avatar`
#

CREATE TABLE `avatar` (
  `avatar_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `avatar_file` varchar(30) NOT NULL DEFAULT '',
  `avatar_name` varchar(100) NOT NULL DEFAULT '',
  `avatar_mimetype` varchar(30) NOT NULL DEFAULT '',
  `avatar_created` int(10) NOT NULL DEFAULT '0',
  `avatar_display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `avatar_weight` smallint(5) unsigned NOT NULL DEFAULT '0',
  `avatar_type` char(1) NOT NULL DEFAULT '',
  PRIMARY KEY (`avatar_id`),
  KEY `avatar_type` (`avatar_type`,`avatar_display`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `avatar`
#

INSERT INTO `avatar` (`avatar_id`, `avatar_file`, `avatar_name`, `avatar_mimetype`, `avatar_created`, `avatar_display`, `avatar_weight`, `avatar_type`) VALUES
(1, 'avatars/default.png', 'default', '', CURRENT_TIMESTAMP, 1, 0, 's');

# ############################

#
# Table structure for table `avatar_user_link`
#

CREATE TABLE `avatar_user_link` (
  `avatar_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  KEY `avatar_user_id` (`avatar_id`,`user_id`)
) DEFAULT CHARSET=utf8;

# ############################

#
# Table structure for table `banner`
#

CREATE TABLE `banner` (
  `bid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imptotal` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `impmade` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `clicks` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `imageurl` varchar(255) NOT NULL DEFAULT '',
  `clickurl` varchar(255) NOT NULL DEFAULT '',
  `date` int(10) NOT NULL DEFAULT '0',
  `htmlbanner` tinyint(1) NOT NULL DEFAULT '0',
  `htmlcode` text,
  PRIMARY KEY (`bid`),
  KEY `idxbannercid` (`cid`),
  KEY `idxbannerbidcid` (`bid`,`cid`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `banner`
#

# ############################

#
# Table structure for table `bannerclient`
#



CREATE TABLE `bannerclient` (
  `cid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `contact` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `login` varchar(10) NOT NULL DEFAULT '',
  `passwd` varchar(10) NOT NULL DEFAULT '',
  `extrainfo` text,
  PRIMARY KEY (`cid`),
  KEY `login` (`login`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `bannerclient`
#

# ############################
#
# Table structure for table `bannerfinish`
#

CREATE TABLE `bannerfinish` (
  `bid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `impressions` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `clicks` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `datestart` int(10) unsigned NOT NULL DEFAULT '0',
  `dateend` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bid`),
  KEY `cid` (`cid`)
) DEFAULT CHARSET=utf8;

# ############################

#
# Table structure for table `block_module_link`
#

CREATE TABLE `block_module_link` (
  `block_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module_id` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`module_id`,`block_id`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `block_module_link`
#

INSERT INTO `block_module_link` (`block_id`, `module_id`) VALUES
(1, -1),
(2, -1),
(3, -1),
(4, -1),
(5, -1),
(6, -1),
(7, -1),
(8, -1),
(9, -1),
(10, -1),
(11, -1),
(12, -1),
(13, -1);

# ############################

#
# Table structure for table `cache_model`
#

CREATE TABLE `cache_model` (
  `cache_key` varchar(64) NOT NULL DEFAULT '',
  `cache_expires` int(10) unsigned NOT NULL DEFAULT '0',
  `cache_data` text,
  PRIMARY KEY (`cache_key`),
  KEY `cache_expires` (`cache_expires`)
) DEFAULT CHARSET=utf8;

# ############################

#
# Table structure for table `config`
#

CREATE TABLE `config` (
  `conf_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `conf_modid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `conf_catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `conf_name` varchar(25) NOT NULL DEFAULT '',
  `conf_title` varchar(255) NOT NULL DEFAULT '',
  `conf_value` text,
  `conf_desc` varchar(255) NOT NULL DEFAULT '',
  `conf_formtype` varchar(15) NOT NULL DEFAULT '',
  `conf_valuetype` varchar(10) NOT NULL DEFAULT '',
  `conf_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`conf_id`),
  KEY `conf_mod_cat_id` (`conf_modid`,`conf_catid`),
  KEY `conf_order` (`conf_order`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `config`
#

INSERT INTO `config` (`conf_id`, `conf_modid`, `conf_catid`, `conf_name`, `conf_title`, `conf_value`, `conf_desc`, `conf_formtype`, `conf_valuetype`, `conf_order`) VALUES
(1, 0, 1, 'sitename', '_MD_AM_SITENAME', 'Xoosla!', '_MD_AM_SITENAMEDSC', 'textbox', 'text', 0),
(2, 0, 1, 'slogan', '_MD_AM_SLOGAN', 'Together Everyone Achieves Miracles!', '_MD_AM_SLOGANDSC', 'textbox', 'text', 2),
(3, 0, 1, 'language', '_MD_AM_LANGUAGE', 'english', '_MD_AM_LANGUAGEDSC', 'language', 'other', 4),
(4, 0, 1, 'startpage', '_MD_AM_STARTPAGE', '#', '_MD_AM_STARTPAGEDSC', 'startpage', 'other', 6),
(5, 0, 1, 'server_TZ', '_MD_AM_SERVERTZ', '0', '_MD_AM_SERVERTZDSC', 'timezone', 'float', 8),
(6, 0, 1, 'default_TZ', '_MD_AM_DEFAULTTZ', '0', '_MD_AM_DEFAULTTZDSC', 'timezone', 'float', 10),
(7, 0, 1, 'theme_set', '_MD_AM_DTHEME', 'default', '_MD_AM_DTHEMEDSC', 'theme', 'other', 12),
(8, 0, 1, 'anonymous', '_MD_AM_ANONNAME', 'Anonymous', '_MD_AM_ANONNAMEDSC', 'textbox', 'text', 15),
(9, 0, 1, 'gzip_compression', '_MD_AM_USEGZIP', '0', '_MD_AM_USEGZIPDSC', 'yesno', 'int', 16),
(10, 0, 1, 'usercookie', '_MD_AM_USERCOOKIE', 'xoosla_user', '_MD_AM_USERCOOKIEDSC', 'textbox', 'text', 18),
(11, 0, 1, 'session_expire', '_MD_AM_SESSEXPIRE', '15', '_MD_AM_SESSEXPIREDSC', 'textbox', 'int', 22),
(12, 0, 1, 'banners', '_MD_AM_BANNERS', '1', '_MD_AM_BANNERSDSC', 'yesno', 'int', 26),
(13, 0, 1, 'debug_mode', '_MD_AM_DEBUGMODE', '0', '_MD_AM_DEBUGMODEDSC', 'select', 'int', 24),
(14, 0, 1, 'my_ip', '_MD_AM_MYIP', '127.0.0.1', '_MD_AM_MYIPDSC', 'textbox', 'text', 29),
(15, 0, 1, 'use_ssl', '_MD_AM_USESSL', '0', '_MD_AM_USESSLDSC', 'yesno', 'int', 30),
(16, 0, 1, 'session_name', '_MD_AM_SESSNAME', 'xoosla_session', '_MD_AM_SESSNAMEDSC', 'textbox', 'text', 20),
(17, 0, 2, 'minpass', '_MD_AM_MINPASS', '5', '_MD_AM_MINPASSDSC', 'textbox', 'int', 1),
(18, 0, 2, 'minuname', '_MD_AM_MINUNAME', '3', '_MD_AM_MINUNAMEDSC', 'textbox', 'int', 2),
(19, 0, 2, 'new_user_notify', '_MD_AM_NEWUNOTIFY', '1', '_MD_AM_NEWUNOTIFYDSC', 'yesno', 'int', 4),
(20, 0, 2, 'new_user_notify_group', '_MD_AM_NOTIFYTO', '1', '_MD_AM_NOTIFYTODSC', 'group', 'int', 6),
(21, 0, 2, 'activation_type', '_MD_AM_ACTVTYPE', '0', '_MD_AM_ACTVTYPEDSC', 'select', 'int', 8),
(22, 0, 2, 'activation_group', '_MD_AM_ACTVGROUP', '1', '_MD_AM_ACTVGROUPDSC', 'group', 'int', 10),
(23, 0, 2, 'uname_test_level', '_MD_AM_UNAMELVL', '0', '_MD_AM_UNAMELVLDSC', 'select', 'int', 12),
(24, 0, 2, 'avatar_allow_upload', '_MD_AM_AVATARALLOW', '0', '_MD_AM_AVATARALWDSC', 'yesno', 'int', 14),
(27, 0, 2, 'avatar_width', '_MD_AM_AVATARW', '120', '_MD_AM_AVATARWDSC', 'textbox', 'int', 16),
(28, 0, 2, 'avatar_height', '_MD_AM_AVATARH', '120', '_MD_AM_AVATARHDSC', 'textbox', 'int', 18),
(29, 0, 2, 'avatar_maxsize', '_MD_AM_AVATARMAX', '35000', '_MD_AM_AVATARMAXDSC', 'textbox', 'int', 20),
(30, 0, 1, 'adminmail', '_MD_AM_ADMINML', 'admin@admin.com', '_MD_AM_ADMINMLDSC', 'textbox', 'text', 3),
(31, 0, 2, 'self_delete', '_MD_AM_SELFDELETE', '0', '_MD_AM_SELFDELETEDSC', 'yesno', 'int', 22),
(32, 0, 1, 'com_mode', '_MD_AM_COMMODE', 'flat', '_MD_AM_COMMODEDSC', 'select', 'text', 34),
(33, 0, 1, 'com_order', '_MD_AM_COMORDER', '0', '_MD_AM_COMORDERDSC', 'select', 'int', 36),
(34, 0, 2, 'bad_unames', '_MD_AM_BADUNAMES', 'a:3:{i:0;s:9:"webmaster";i:1;s:6:"^xoosla";i:2;s:6:"^admin";}', '_MD_AM_BADUNAMESDSC', 'textarea', 'array', 24),
(35, 0, 2, 'bad_emails', '_MD_AM_BADEMAILS', 'a:1:{i:0;s:10:"xoosla.com$";}', '_MD_AM_BADEMAILSDSC', 'textarea', 'array', 26),
(36, 0, 2, 'maxuname', '_MD_AM_MAXUNAME', '10', '_MD_AM_MAXUNAMEDSC', 'textbox', 'int', 3),
(37, 0, 1, 'bad_ips', '_MD_AM_BADIPS', 'a:1:{i:0;s:9:"127.0.0.1";}', '_MD_AM_BADIPSDSC', 'textarea', 'array', 42),
(38, 0, 3, 'meta_keywords', '_MD_AM_METAKEY', 'xoosla, web applications, web 2.0, sns, news, technology, headlines, linux, software, download, downloads, free, community, forum, bulletin board, bbs, php, survey, polls, kernel, comment, comments, portal, odp, open source, opensource, FreeSoftware, gnu, gpl, license, Unix, *nix, mysql, sql, database, databases, web site, blog, wiki, module, modules, theme, themes, cms, content management', '_MD_AM_METAKEYDSC', 'textarea', 'text', 0),
(39, 0, 3, 'footer', '_MD_AM_FOOTER', 'Powered by Xoosla © 2012 <a href="http://xoosla.sourceforge.net" rel="external" title="Xoosla CMS">Xoosla CMS</a>', '_MD_AM_FOOTERDSC', 'textarea', 'text', 20),
(40, 0, 4, 'censor_enable', '_MD_AM_DOCENSOR', '0', '_MD_AM_DOCENSORDSC', 'yesno', 'int', 0),
(41, 0, 4, 'censor_words', '_MD_AM_CENSORWRD', 'a:2:{i:0;s:4:"fuck";i:1;s:4:"shit";}', '_MD_AM_CENSORWRDDSC', 'textarea', 'array', 1),
(42, 0, 4, 'censor_replace', '_MD_AM_CENSORRPLC', '#OOPS#', '_MD_AM_CENSORRPLCDSC', 'textbox', 'text', 2),
(43, 0, 3, 'meta_robots', '_MD_AM_METAROBOTS', 'index,follow', '_MD_AM_METAROBOTSDSC', 'select', 'text', 2),
(44, 0, 5, 'enable_search', '_MD_AM_DOSEARCH', '1', '_MD_AM_DOSEARCHDSC', 'yesno', 'int', 0),
(45, 0, 5, 'keyword_min', '_MD_AM_MINSEARCH', '5', '_MD_AM_MINSEARCHDSC', 'textbox', 'int', 1),
(46, 0, 2, 'avatar_minposts', '_MD_AM_AVATARMP', '0', '_MD_AM_AVATARMPDSC', 'textbox', 'int', 15),
(47, 0, 1, 'enable_badips', '_MD_AM_DOBADIPS', '0', '_MD_AM_DOBADIPSDSC', 'yesno', 'int', 40),
(48, 0, 3, 'meta_rating', '_MD_AM_METARATING', 'general', '_MD_AM_METARATINGDSC', 'select', 'text', 4),
(49, 0, 3, 'meta_author', '_MD_AM_METAAUTHOR', 'Xoosla!', '_MD_AM_METAAUTHORDSC', 'textbox', 'text', 6),
(50, 0, 3, 'meta_copyright', '_MD_AM_METACOPYR', 'Copyright @ 2012', '_MD_AM_METACOPYRDSC', 'textbox', 'text', 8),
(51, 0, 3, 'meta_description', '_MD_AM_METADESC', 'Xoosla! is a dynamic Object Oriented based open source portal script written in PHP.', '_MD_AM_METADESCDSC', 'textarea', 'text', 1),
(52, 0, 2, 'allow_chgmail', '_MD_AM_ALLWCHGMAIL', '0', '_MD_AM_ALLWCHGMAILDSC', 'yesno', 'int', 3),
(53, 0, 1, 'use_mysession', '_MD_AM_USEMYSESS', '0', '_MD_AM_USEMYSESSDSC', 'yesno', 'int', 19),
(54, 0, 2, 'reg_dispdsclmr', '_MD_AM_DSPDSCLMR', '1', '_MD_AM_DSPDSCLMRDSC', 'yesno', 'int', 30),
(55, 0, 2, 'reg_disclaimer', '_MD_AM_REGDSCLMR', 'While the administrators and moderators of this site will attempt to remove\r\nor edit any generally objectionable material as quickly as possible, it is\r\nimpossible to review every message. Therefore you acknowledge that all posts\r\nmade to this site express the views and opinions of the author and not the\r\nadministrators, moderators or webmaster (except for posts by these people)\r\nand hence will not be held liable.\r\n\r\nYou agree not to post any abusive, obscene, vulgar, slanderous, hateful,\r\nthreatening, sexually-orientated or any other material that may violate any\r\napplicable laws. Doing so may lead to you being immediately and permanently\r\nbanned (and your service provider being informed). The IP address of all\r\nposts is recorded to aid in enforcing these conditions. Creating multiple\r\naccounts for a single user is not allowed. You agree that the webmaster,\r\nadministrator and moderators of this site have the right to remove, edit,\r\nmove or close any topic at any time should they see fit. As a user you agree\r\nto any information you have entered above being stored in a database. While\r\nthis information will not be disclosed to any third party without your\r\nconsent the webmaster, administrator and moderators cannot be held\r\nresponsible for any hacking attempt that may lead to the data being\r\ncompromised.\r\n\r\nThis site system uses cookies to store information on your local computer.\r\nThese cookies do not contain any of the information you have entered above,\r\nthey serve only to improve your viewing pleasure. The email address is used\r\nonly for confirming your registration details and password (and for sending\r\nnew passwords should you forget your current one).\r\n\r\nBy clicking Register below you agree to be bound by these conditions.', '_MD_AM_REGDSCLMRDSC', 'textarea', 'text', 32),
(56, 0, 2, 'allow_register', '_MD_AM_ALLOWREG', '1', '_MD_AM_ALLOWREGDSC', 'yesno', 'int', 0),
(57, 0, 1, 'theme_fromfile', '_MD_AM_THEMEFILE', '0', '_MD_AM_THEMEFILEDSC', 'yesno', 'int', 13),
(58, 0, 1, 'closesite', '_MD_AM_CLOSESITE', '0', '_MD_AM_CLOSESITEDSC', 'yesno', 'int', 26),
(59, 0, 1, 'closesite_okgrp', '_MD_AM_CLOSESITEOK', 'a:1:{i:0;s:1:"1";}', '_MD_AM_CLOSESITEOKDSC', 'group_multi', 'array', 27),
(60, 0, 1, 'closesite_text', '_MD_AM_CLOSESITETXT', 'The site is currently closed for maintenance. Please come back later.', '_MD_AM_CLOSESITETXTDSC', 'textarea', 'text', 28),
(61, 0, 1, 'sslpost_name', '_MD_AM_SSLPOST', 'xoosla_ssl', '_MD_AM_SSLPOSTDSC', 'textbox', 'text', 31),
(62, 0, 1, 'module_cache', '_MD_AM_MODCACHE', '', '_MD_AM_MODCACHEDSC', 'module_cache', 'array', 50),
(63, 0, 1, 'template_set', '_MD_AM_DTPLSET', 'default', '_MD_AM_DTPLSETDSC', 'tplset', 'other', 14),
(64, 0, 6, 'mailmethod', '_MD_AM_MAILERMETHOD', 'mail', '_MD_AM_MAILERMETHODDESC', 'select', 'text', 4),
(65, 0, 6, 'smtphost', '_MD_AM_SMTPHOST', 'a:1:{i:0;s:0:"";}', '_MD_AM_SMTPHOSTDESC', 'textarea', 'array', 6),
(66, 0, 6, 'smtpuser', '_MD_AM_SMTPUSER', '', '_MD_AM_SMTPUSERDESC', 'textbox', 'text', 7),
(67, 0, 6, 'smtppass', '_MD_AM_SMTPPASS', '', '_MD_AM_SMTPPASSDESC', 'password', 'text', 8),
(68, 0, 6, 'sendmailpath', '_MD_AM_SENDMAILPATH', '/usr/sbin/sendmail', '_MD_AM_SENDMAILPATHDESC', 'textbox', 'text', 5),
(69, 0, 6, 'from', '_MD_AM_MAILFROM', '', '_MD_AM_MAILFROMDESC', 'textbox', 'text', 1),
(70, 0, 6, 'fromname', '_MD_AM_MAILFROMNAME', '', '_MD_AM_MAILFROMNAMEDESC', 'textbox', 'text', 2),

(71, 0, 1, 'sslloginlink', '_MD_AM_SSLLINK', 'https://', '_MD_AM_SSLLINKDSC', 'textbox', 'text', 33),
(72, 0, 1, 'theme_set_allowed', '_MD_AM_THEMEOK', 'a:1:{i:0;s:7:"default";}', '_MD_AM_THEMEOKDSC', 'theme_multi', 'array', 13),
(73, 0, 6, 'fromuid', '_MD_AM_MAILFROMUID', '1', '_MD_AM_MAILFROMUIDDESC', 'user', 'int', 3),
(74, 0, 7, 'auth_method', '_MD_AM_AUTHMETHOD', 'xoops', '_MD_AM_AUTHMETHODDESC', 'select', 'text', 1),
(75, 0, 7, 'ldap_port', '_MD_AM_LDAP_PORT', '389', '_MD_AM_LDAP_PORT', 'textbox', 'int', 2),
(76, 0, 7, 'ldap_server', '_MD_AM_LDAP_SERVER', 'your directory server', '_MD_AM_LDAP_SERVER_DESC', 'textbox', 'text', 3),
(77, 0, 7, 'ldap_base_dn', '_MD_AM_LDAP_BASE_DN', 'dc=xoosla,dc=com', '_MD_AM_LDAP_BASE_DN_DESC', 'textbox', 'text', 4),
(78, 0, 7, 'ldap_manager_dn', '_MD_AM_LDAP_MANAGER_DN', 'manager_dn', '_MD_AM_LDAP_MANAGER_DN_DESC', 'textbox', 'text', 5),
(79, 0, 7, 'ldap_manager_pass', '_MD_AM_LDAP_MANAGER_PASS', 'manager_pass', '_MD_AM_LDAP_MANAGER_PASS_DESC', 'password', 'text', 6),
(80, 0, 7, 'ldap_version', '_MD_AM_LDAP_VERSION', '3', '_MD_AM_LDAP_VERSION_DESC', 'textbox', 'text', 7),
(81, 0, 7, 'ldap_users_bypass', '_MD_AM_LDAP_USERS_BYPASS', 'a:1:{i:0;s:5:"admin";}', '_MD_AM_LDAP_USERS_BYPASS_DESC', 'textarea', 'array', 8),
(82, 0, 7, 'ldap_loginname_asdn', '_MD_AM_LDAP_LOGINNAME_ASDN', 'uid_asdn', '_MD_AM_LDAP_LOGINNAME_ASDN_D', 'yesno', 'int', 9),
(83, 0, 7, 'ldap_loginldap_attr', '_MD_AM_LDAP_LOGINLDAP_ATTR', 'uid', '_MD_AM_LDAP_LOGINLDAP_ATTR_D', 'textbox', 'text', 10),
(84, 0, 7, 'ldap_filter_person', '_MD_AM_LDAP_FILTER_PERSON', '', '_MD_AM_LDAP_FILTER_PERSON_DESC', 'textbox', 'text', 11),
(85, 0, 7, 'ldap_domain_name', '_MD_AM_LDAP_DOMAIN_NAME', 'mydomain', '_MD_AM_LDAP_DOMAIN_NAME_DESC', 'textbox', 'text', 12),
(86, 0, 7, 'ldap_provisionning', '_MD_AM_LDAP_PROVIS', '0', '_MD_AM_LDAP_PROVIS_DESC', 'yesno', 'int', 13),
(87, 0, 7, 'ldap_provisionning_group', '_MD_AM_LDAP_PROVIS_GROUP', 'a:1:{i:0;s:1:"2";}', '_MD_AM_LDAP_PROVIS_GROUP_DSC', 'group_multi', 'array', 14),
(88, 0, 7, 'ldap_mail_attr', '_MD_AM_LDAP_MAIL_ATTR', 'mail', '_MD_AM_LDAP_MAIL_ATTR_DESC', 'textbox', 'text', 15),
(89, 0, 7, 'ldap_givenname_attr', '_MD_AM_LDAP_GIVENNAME_ATTR', 'givenname', '_MD_AM_LDAP_GIVENNAME_ATTR_DSC', 'textbox', 'text', 16),
(90, 0, 7, 'ldap_surname_attr', '_MD_AM_LDAP_SURNAME_ATTR', 'sn', '_MD_AM_LDAP_SURNAME_ATTR_DESC', 'textbox', 'text', 17),
(91, 0, 7, 'ldap_field_mapping', '_MD_AM_LDAP_FIELD_MAPPING_ATTR', 'email=mail|name=displayname', '_MD_AM_LDAP_FIELD_MAPPING_DESC', 'textarea', 'text', 18),
(92, 0, 7, 'ldap_provisionning_upd', '_MD_AM_LDAP_PROVIS_UPD', '1', '_MD_AM_LDAP_PROVIS_UPD_DESC', 'yesno', 'int', 19),
(93, 0, 7, 'ldap_use_TLS', '_MD_AM_LDAP_USETLS', '0', '_MD_AM_LDAP_USETLS_DESC', 'yesno', 'int', 20),
(94, 0, 1, 'cpanel', '_MD_AM_CPANEL', 'default', '_MD_AM_CPANELDSC', 'cpanel', 'other', 11),
(95, 0, 2, 'welcome_type', '_MD_AM_WELCOMETYPE', '1', '_MD_AM_WELCOMETYPE_DESC', 'select', 'int', 3),
(96, 1, 0, 'break1', '_MI_SYSTEM_PREFERENCE_BREAK_GENERAL', 'head', '', 'line_break', 'textbox', 0),
(97, 1, 0, 'usetips', '_MI_SYSTEM_PREFERENCE_TIPS', '1', '_MI_SYSTEM_PREFERENCE_TIPS_DSC', 'yesno', 'int', 10),
(98, 1, 0, 'typeicons', '_MI_SYSTEM_PREFERENCE_ICONS', 'default', '', 'select', 'text', 20),
(99, 1, 0, 'typebreadcrumb', '_MI_SYSTEM_PREFERENCE_BREADCRUMB', 'default', '', 'select', 'text', 30),
(100, 1, 0, 'break2', '_MI_SYSTEM_PREFERENCE_BREAK_ACTIVE', 'head', '', 'line_break', 'textbox', 40),
(101, 1, 0, 'active_avatars', '_MI_SYSTEM_PREFERENCE_ACTIVE_AVATARS', '1', '', 'yesno', 'int', 50),
(102, 1, 0, 'active_banners', '_MI_SYSTEM_PREFERENCE_ACTIVE_BANNERS', '1', '', 'yesno', 'int', 60),
(103, 1, 0, 'active_blocksadmin', '_MI_SYSTEM_PREFERENCE_ACTIVE_BLOCKSADMIN', '1', '', 'hidden', 'int', 70),
(104, 1, 0, 'active_comments', '_MI_SYSTEM_PREFERENCE_ACTIVE_COMMENTS', '1', '', 'yesno', 'int', 80),
(105, 1, 0, 'active_filemanager', '_MI_SYSTEM_PREFERENCE_ACTIVE_FILEMANAGER', '1', '', 'yesno', 'int', 90),
(106, 1, 0, 'active_groups', '_MI_SYSTEM_PREFERENCE_ACTIVE_GROUPS', '1', '', 'hidden', 'int', 100),
(107, 1, 0, 'active_images', '_MI_SYSTEM_PREFERENCE_ACTIVE_IMAGES', '1', '', 'yesno', 'int', 110),
(108, 1, 0, 'active_mailusers', '_MI_SYSTEM_PREFERENCE_ACTIVE_MAILUSERS', '1', '', 'yesno', 'int', 120),
(109, 1, 0, 'active_modulesadmin', '_MI_SYSTEM_PREFERENCE_ACTIVE_MODULESADMIN', '1', '', 'hidden', 'int', 130),
(110, 1, 0, 'active_maintenance', '_MI_SYSTEM_PREFERENCE_ACTIVE_MAINTENANCE', '1', '', 'yesno', 'int', 140),
(111, 1, 0, 'active_preferences', '_MI_SYSTEM_PREFERENCE_ACTIVE_PREFERENCES', '1', '', 'hidden', 'int', 150),
(112, 1, 0, 'active_smilies', '_MI_SYSTEM_PREFERENCE_ACTIVE_SMILIES', '1', '', 'yesno', 'int', 160),
(113, 1, 0, 'active_tplsets', '_MI_SYSTEM_PREFERENCE_ACTIVE_TPLSETS', '1', '', 'hidden', 'int', 170),
(114, 1, 0, 'active_userrank', '_MI_SYSTEM_PREFERENCE_ACTIVE_USERRANK', '1', '', 'yesno', 'int', 180),
(115, 1, 0, 'active_users', '_MI_SYSTEM_PREFERENCE_ACTIVE_USERS', '1', '', 'yesno', 'int', 190),
(116, 1, 0, 'break3', '_MI_SYSTEM_PREFERENCE_BREAK_PAGER', 'head', '', 'line_break', 'textbox', 200),
(117, 1, 0, 'avatars_pager', '_MI_SYSTEM_PREFERENCE_AVATARS_PAGER', '10', '', 'textbox', 'int', 210),
(118, 1, 0, 'banners_pager', '_MI_SYSTEM_PREFERENCE_BANNERS_PAGER', '10', '', 'textbox', 'int', 220),
(119, 1, 0, 'comments_pager', '_MI_SYSTEM_PREFERENCE_COMMENTS_PAGER', '20', '', 'textbox', 'int', 230),
(120, 1, 0, 'groups_pager', '_MI_SYSTEM_PREFERENCE_GROUPS_PAGER', '15', '', 'textbox', 'int', 240),
(121, 1, 0, 'images_pager', '_MI_SYSTEM_PREFERENCE_IMAGES_PAGER', '15', '', 'textbox', 'int', 250),
(122, 1, 0, 'smilies_pager', '_MI_SYSTEM_PREFERENCE_SMILIES_PAGER', '20', '', 'textbox', 'int', 260),
(123, 1, 0, 'userranks_pager', '_MI_SYSTEM_PREFERENCE_USERRANKS_PAGER', '20', '', 'textbox', 'int', 270),
(124, 1, 0, 'users_pager', '_MI_SYSTEM_PREFERENCE_USERS_PAGER', '20', '', 'textbox', 'int', 280),
(125, 1, 0, 'break4', '_MI_SYSTEM_PREFERENCE_BREAK_EDITOR', 'head', '', 'line_break', 'textbox', 290),
(126, 1, 0, 'blocks_editor', '_MI_SYSTEM_PREFERENCE_BLOCKS_EDITOR', 'dhtmltextarea', '_MI_SYSTEM_PREFERENCE_BLOCKS_EDITOR_DSC', 'select', 'text', 300),
(127, 1, 0, 'comments_editor', '_MI_SYSTEM_PREFERENCE_COMMENTS_EDITOR', 'dhtmltextarea', '_MI_SYSTEM_PREFERENCE_COMMENTS_EDITOR_DSC', 'select', 'text', 310),
(128, 1, 0, 'general_editor', '_MI_SYSTEM_PREFERENCE_GENERAL_EDITOR', 'dhtmltextarea', '_MI_SYSTEM_PREFERENCE_GENERAL_EDITOR_DSC', 'select', 'text', 320),
(129, 1, 0, 'redirect', '_MI_SYSTEM_PREFERENCE_REDIRECT', 'admin.php?fct=preferences', '', 'hidden', 'text', 330),
(130, 1, 0, 'com_anonpost', '_MI_SYSTEM_PREFERENCE_ANONPOST', '', '', 'hidden', 'text', 340),
(133, 1, 0, 'jquery_theme', '_MI_SYSTEM_PREFERENCE_JQUERY_THEME', 'base', '', 'select', 'text', 35),
(134, 0, 1, 'redirect_message_ajax', '_MD_AM_CUSTOM_REDIRECT', '1', '_MD_AM_CUSTOM_REDIRECT_DESC', 'yesno', 'int', 12);

# ############################

#
# Table structure for table `configcategory`
#

CREATE TABLE `configcategory` (
  `confcat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `confcat_name` varchar(255) NOT NULL DEFAULT '',
  `confcat_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`confcat_id`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `configcategory`
#

INSERT INTO `configcategory` (`confcat_id`, `confcat_name`, `confcat_order`) VALUES
(1, '_MD_AM_GENERAL', 0),
(2, '_MD_AM_USERSETTINGS', 0),
(3, '_MD_AM_METAFOOTER', 0),
(4, '_MD_AM_CENSOR', 0),
(5, '_MD_AM_SEARCH', 0),
(6, '_MD_AM_MAILER', 0),
(7, '_MD_AM_AUTHENTICATION', 0);

# ############################

#
# Table structure for table `configoption`
#

CREATE TABLE `configoption` (
  `confop_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `confop_name` varchar(255) NOT NULL DEFAULT '',
  `confop_value` varchar(255) NOT NULL DEFAULT '',
  `conf_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`confop_id`),
  KEY `conf_id` (`conf_id`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `configoption`
#


INSERT INTO `configoption` (`confop_id`, `confop_name`, `confop_value`, `conf_id`) VALUES
(0, '_MD_AM_DEBUGMODE0', '0', 13),
(0, '_MD_AM_DEBUGMODE1', '1', 13),
(0, '_MD_AM_DEBUGMODE2', '2', 13),
(0, '_MD_AM_DEBUGMODE4', '4', 13),
(0, '_MD_AM_DEBUGMODE3', '3', 13),
(0, '_NESTED', 'nest', 32),
(0, '_FLAT', 'flat', 32),
(0, '_THREADED', 'thread', 32),
(0, '_NEWESTFIRST', '1', 33),
(0, '_OLDESTFIRST', '0', 33),
(0, '_MD_AM_USERACTV', '0', 21),
(0, '_MD_AM_AUTOACTV', '1', 21),
(0, '_MD_AM_ADMINACTV', '2', 21),
(0, '_MD_AM_STRICT', '0', 23),
(0, '_MD_AM_MEDIUM', '1', 23),
(0, '_MD_AM_LIGHT', '2', 23),
(0, '_MD_AM_INDEXFOLLOW', 'index,follow', 43),
(0, '_MD_AM_NOINDEXFOLLOW', 'noindex,follow', 43),
(0, '_MD_AM_INDEXNOFOLLOW', 'index,nofollow', 43),
(0, '_MD_AM_NOINDEXNOFOLLOW', 'noindex,nofollow', 43),
(0, '_MD_AM_METAOGEN', 'general', 48),
(0, '_MD_AM_METAO14YRS', '14 years', 48),
(0, '_MD_AM_METAOREST', 'restricted', 48),
(0, '_MD_AM_METAOMAT', 'mature', 48),
(0, 'PHP mail()', 'mail', 64),
(0, 'sendmail', 'sendmail', 64),
(0, 'SMTP', 'smtp', 64),
(0, 'SMTPAuth', 'smtpauth', 64),
(0, '_MD_AM_AUTH_CONFOPTION_XOOPS', 'xoops', 74),
(0, '_MD_AM_AUTH_CONFOPTION_LDAP', 'ldap', 74),
(0, '_MD_AM_AUTH_CONFOPTION_AD', 'ads', 74),
(0, '_NO', '0', 95),
(0, '_MD_AM_WELCOMETYPE_EMAIL', '1', 95),
(0, '_MD_AM_WELCOMETYPE_PM', '2', 95),
(0, '_MD_AM_WELCOMETYPE_BOTH', '3', 95),
(0, 'default', 'default', 533),
(0, 'default', 'default', 534),
(0, 'base', 'base', 535),
(0, 'cupertino', 'cupertino', 535),
(0, 'redmond', 'redmond', 535),
(0, 'smoothness', 'smoothness', 535),
(0, 'south-street', 'south-street', 535),
(0, 'ui-darkness', 'ui-darkness', 535),
(0, 'ui-lightness', 'ui-lightness', 535),
(0, 'dhtmltextarea', 'dhtmltextarea', 562),
(0, 'textarea', 'textarea', 562),
(0, 'tinymce', 'tinymce', 562),
(0, 'dhtmltextarea', 'dhtmltextarea', 563),
(0, 'textarea', 'textarea', 563),
(0, 'tinymce', 'tinymce', 563),
(0, 'dhtmltextarea', 'dhtmltextarea', 564),
(0, 'textarea', 'textarea', 564),
(0, 'tinymce', 'tinymce', 564);

# ############################

#
# Table structure for table `groups`
#

CREATE TABLE `groups` (
  `groupid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` text,
  `group_type` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`groupid`),
  KEY `group_type` (`group_type`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `groups`
#

INSERT INTO `groups` (`groupid`, `name`, `description`, `group_type`) VALUES
(1, 'Webmasters', 'Webmasters of this site', 'Admin'),
(2, 'Registered Users', 'Registered Users Group', 'User'),
(3, 'Anonymous Users', 'Anonymous Users Group', 'Anonymous');

# ############################

#
# Table structure for table `groups_users_link`
#

CREATE TABLE `groups_users_link` (
  `linkid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`linkid`),
  KEY `groupid_uid` (`groupid`,`uid`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `groups_users_link`
#

INSERT INTO `groups_users_link` (`linkid`, `groupid`, `uid`) VALUES
(1, 1, 1),
(2, 2, 1);

# ############################

#
# Table structure for table `group_permission`
#

CREATE TABLE `group_permission` (
  `gperm_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gperm_groupid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gperm_itemid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gperm_modid` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `gperm_name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`gperm_id`),
  KEY `groupid` (`gperm_groupid`),
  KEY `itemid` (`gperm_itemid`),
  KEY `gperm_modid` (`gperm_modid`,`gperm_name`(10))
) DEFAULT CHARSET=utf8 ;

#
# Dumping data for table `group_permission`
#

INSERT INTO `group_permission` (`gperm_id`, `gperm_groupid`, `gperm_itemid`, `gperm_modid`, `gperm_name`) VALUES
(1, 1, 1, 1, 'module_admin'),
(2, 1, 1, 1, 'module_read'),
(3, 2, 1, 1, 'module_read'),
(4, 3, 1, 1, 'module_read'),
(5, 1, 1, 1, 'system_admin'),
(6, 1, 2, 1, 'system_admin'),
(7, 1, 3, 1, 'system_admin'),
(8, 1, 4, 1, 'system_admin'),
(9, 1, 5, 1, 'system_admin'),
(10, 1, 6, 1, 'system_admin'),
(11, 1, 7, 1, 'system_admin'),
(12, 1, 8, 1, 'system_admin'),
(13, 1, 9, 1, 'system_admin'),
(14, 1, 10, 1, 'system_admin'),
(15, 1, 11, 1, 'system_admin'),
(16, 1, 12, 1, 'system_admin'),
(17, 1, 13, 1, 'system_admin'),
(18, 1, 14, 1, 'system_admin'),
(19, 1, 15, 1, 'system_admin'),
(20, 1, 16, 1, 'system_admin'),
(21, 1, 17, 1, 'system_admin'),
(22, 1, 1, 1, 'block_read'),
(23, 2, 1, 1, 'block_read'),
(24, 3, 1, 1, 'block_read'),
(25, 1, 2, 1, 'block_read'),
(26, 2, 2, 1, 'block_read'),
(27, 3, 2, 1, 'block_read'),
(28, 1, 3, 1, 'block_read'),
(29, 2, 3, 1, 'block_read'),
(30, 3, 3, 1, 'block_read'),
(31, 1, 4, 1, 'block_read'),
(32, 2, 4, 1, 'block_read'),
(33, 3, 4, 1, 'block_read'),
(34, 1, 5, 1, 'block_read'),
(35, 2, 5, 1, 'block_read'),
(36, 3, 5, 1, 'block_read'),
(37, 1, 6, 1, 'block_read'),
(38, 2, 6, 1, 'block_read'),
(39, 3, 6, 1, 'block_read'),
(40, 1, 7, 1, 'block_read'),
(41, 2, 7, 1, 'block_read'),
(42, 3, 7, 1, 'block_read'),
(43, 1, 8, 1, 'block_read'),
(44, 2, 8, 1, 'block_read'),
(45, 3, 8, 1, 'block_read'),
(46, 1, 9, 1, 'block_read'),
(47, 2, 9, 1, 'block_read'),
(48, 3, 9, 1, 'block_read'),
(49, 1, 10, 1, 'block_read'),
(50, 2, 10, 1, 'block_read'),
(51, 3, 10, 1, 'block_read'),
(52, 1, 11, 1, 'block_read'),
(53, 2, 11, 1, 'block_read'),
(54, 3, 11, 1, 'block_read'),
(55, 1, 12, 1, 'block_read'),
(56, 2, 12, 1, 'block_read'),
(57, 3, 12, 1, 'block_read'),
(58, 1, 13, 1, 'block_read'),
(59, 2, 13, 1, 'block_read'),
(60, 3, 13, 1, 'block_read');

# ############################

#
# Table structure for table `image`
#

CREATE TABLE `image` (
  `image_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `image_name` varchar(30) NOT NULL DEFAULT '',
  `image_nicename` varchar(255) NOT NULL DEFAULT '',
  `image_mimetype` varchar(30) NOT NULL DEFAULT '',
  `image_created` int(10) unsigned NOT NULL DEFAULT '0',
  `image_display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `image_weight` smallint(5) unsigned NOT NULL DEFAULT '0',
  `imgcat_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`image_id`),
  KEY `imgcat_id` (`imgcat_id`),
  KEY `image_display` (`image_display`)
) DEFAULT CHARSET=utf8;

# ############################

#
# Table structure for table `imagebody`
#

CREATE TABLE `imagebody` (
  `image_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `image_body` mediumblob,
  KEY `image_id` (`image_id`)
) DEFAULT CHARSET=utf8;

# ############################

#
# Table structure for table `imagecategory`
#

CREATE TABLE `imagecategory` (
  `imgcat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `imgcat_name` varchar(100) NOT NULL DEFAULT '',
  `imgcat_maxsize` int(8) unsigned NOT NULL DEFAULT '0',
  `imgcat_maxwidth` smallint(3) unsigned NOT NULL DEFAULT '0',
  `imgcat_maxheight` smallint(3) unsigned NOT NULL DEFAULT '0',
  `imgcat_display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `imgcat_weight` smallint(3) unsigned NOT NULL DEFAULT '0',
  `imgcat_type` char(1) NOT NULL DEFAULT '',
  `imgcat_storetype` varchar(5) NOT NULL DEFAULT '',
  PRIMARY KEY (`imgcat_id`),
  KEY `imgcat_display` (`imgcat_display`)
) DEFAULT CHARSET=utf8;

# ############################

#
# Table structure for table `imgset`
#

CREATE TABLE `imgset` (
  `imgset_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `imgset_name` varchar(50) NOT NULL DEFAULT '',
  `imgset_refid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`imgset_id`),
  KEY `imgset_refid` (`imgset_refid`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `imgset`
#

INSERT INTO `imgset` (`imgset_id`, `imgset_name`, `imgset_refid`) VALUES
(1, 'default', 0);

# ############################

#
# Table structure for table `imgsetimg`
#

CREATE TABLE `imgsetimg` (
  `imgsetimg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `imgsetimg_file` varchar(50) NOT NULL DEFAULT '',
  `imgsetimg_body` blob,
  `imgsetimg_imgset` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`imgsetimg_id`),
  KEY `imgsetimg_imgset` (`imgsetimg_imgset`)
) DEFAULT CHARSET=utf8;

# ############################

#
# Table structure for table `imgset_tplset_link`
#

CREATE TABLE `imgset_tplset_link` (
  `imgset_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `tplset_name` varchar(50) NOT NULL DEFAULT '',
  KEY `tplset_name` (`tplset_name`(10))
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `imgset_tplset_link`
#

INSERT INTO `imgset_tplset_link` (`imgset_id`, `tplset_name`) VALUES
(1, 'default');

# ############################

#
# Table structure for table `modules`
#

CREATE TABLE `modules` (
  `mid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL DEFAULT '',
  `version` smallint(5) unsigned NOT NULL DEFAULT '100',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `weight` smallint(3) unsigned NOT NULL DEFAULT '0',
  `isactive` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dirname` varchar(25) NOT NULL DEFAULT '',
  `hasmain` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasadmin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hassearch` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasconfig` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hascomments` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasnotification` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mid`),
  KEY `hasmain` (`hasmain`),
  KEY `hasadmin` (`hasadmin`),
  KEY `hassearch` (`hassearch`),
  KEY `hasnotification` (`hasnotification`),
  KEY `dirname` (`dirname`),
  KEY `name` (`name`(15)),
  KEY `isactive` (`isactive`),
  KEY `weight` (`weight`),
  KEY `hascomments` (`hascomments`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `modules`
#

INSERT INTO `modules` (`mid`, `name`, `version`, `last_update`, `weight`, `isactive`, `dirname`, `hasmain`, `hasadmin`, `hassearch`, `hasconfig`, `hascomments`, `hasnotification`) VALUES
(1, 'System', 210, CURRENT_TIMESTAMP, 0, 1, 'system', 0, 1, 0, 0, 0, 0);

# ############################

#
# Dumping data for table `newblocks`
#
CREATE TABLE `newblocks` (
  `bid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `mid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `func_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `options` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(150) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `content` text,
  `side` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `weight` smallint(5) unsigned NOT NULL DEFAULT '0',
  `visible` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `block_type` char(1) NOT NULL DEFAULT '',
  `c_type` char(1) NOT NULL DEFAULT '',
  `isactive` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dirname` varchar(50) NOT NULL DEFAULT '',
  `func_file` varchar(50) NOT NULL DEFAULT '',
  `show_func` varchar(50) NOT NULL DEFAULT '',
  `edit_func` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(50) NOT NULL DEFAULT '',
  `bcachetime` int(10) unsigned NOT NULL DEFAULT '0',
  `last_modified` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bid`),
  KEY `mid` (`mid`),
  KEY `visible` (`visible`),
  KEY `isactive_visible_mid` (`isactive`,`visible`,`mid`),
  KEY `mid_funcnum` (`mid`,`func_num`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `x632_newblocks`
#

INSERT INTO `newblocks` (`bid`, `mid`, `func_num`, `options`, `name`, `title`, `description`, `content`, `side`, `weight`, `visible`, `block_type`, `c_type`, `isactive`, `dirname`, `func_file`, `show_func`, `edit_func`, `template`, `bcachetime`, `last_modified`) VALUES
(1, 1, 0, '', 'User Menu', 'User Menu2', 'Shows user block', '', 0, 0, 1, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_user_show', '', 'system_block_user.html', 0, CURRENT_TIMESTAMP),
(2, 1, 2, '', 'Login', 'Login2', 'Shows login form', '', 0, 1, 1, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_login_show', '', 'system_block_login.html', 0, CURRENT_TIMESTAMP),
(3, 1, 3, '', 'Search', 'Search', 'Shows search form block', '', 1, 0, 1, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_search_show', '', 'system_block_search.html', 0, CURRENT_TIMESTAMP),
(4, 1, 4, '', 'Waiting Contents', 'Waiting Contents', 'Shows contents waiting for approval', '', 0, 3, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_waiting_show', '', 'system_block_waiting.html', 0, CURRENT_TIMESTAMP),
(5, 1, 5, '', 'Main Menu', 'Main Menu', 'Shows the main navigation menu of the site', '', 0, 2, 1, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_main_show', '', 'system_block_mainmenu.html', 0, CURRENT_TIMESTAMP),
(6, 1, 6, '320|190|s_poweredby.gif|1', 'Site Info', 'Site Info', 'Shows basic info about the site and a link to Recommend Us pop up window', '', 0, 4, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_info_show', 'b_system_info_edit', 'system_block_siteinfo.html', 0, CURRENT_TIMESTAMP),
(7, 1, 7, '', 'Who is Online', 'Who is Online', 'Displays users/guests currently online', '', 0, 5, 1, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_online_show', '', 'system_block_online.html', 0, CURRENT_TIMESTAMP),
(8, 1, 8, '10|1', 'Top Posters', 'Top Posters', 'Top posters', '', 0, 6, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_topposters_show', 'b_system_topposters_edit', 'system_block_topusers.html', 0, CURRENT_TIMESTAMP),
(9, 1, 9, '10|1', 'New Members', 'New Members', 'Shows most recent users', '', 0, 7, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_newmembers_show', 'b_system_newmembers_edit', 'system_block_newusers.html', 0, CURRENT_TIMESTAMP),
(10, 1, 10, '10', 'Recent Comments', 'Recent Comments', 'Shows most recent comments', '', 0, 8, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_comments_show', 'b_system_comments_edit', 'system_block_comments.html', 0, CURRENT_TIMESTAMP),
(11, 1, 11, '', 'Notification Options', 'Notification Options', 'Shows notification options', '', 0, 9, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_notification_show', '', 'system_block_notification.html', 0, CURRENT_TIMESTAMP),
(12, 1, 12, '0|80', 'Themes', 'Themes', 'Shows theme selection box', '', 0, 10, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_themes_show', 'b_system_themes_edit', 'system_block_themes.html', 0, CURRENT_TIMESTAMP),
(13, 0, 0, '', 'Xoosla Welcome', 'Welcome To Your New Xoosla Webportal', 'Welcome Block', 'Thank-you for choosing Xoosla CMS for your web portal and we hope that you continue to use it for a long time to come. \r\n\r\n[b]So what''s next? [/b]\r\n\r\nWe suggest that you update your configuration settings to better suit your own needs. Click on the administration button in the user menu to get you started. Remember to change the meta tags as well or you will be giving Xoosla plenty hits in the search engine department :)\r\n\r\nInstall some modules and a theme to help get you started.\r\n\r\n[b]Can I still Use Xoops Modules and Themes? [/b]\r\n\r\nFor the time being yes, but we do plan on changing/updating modules and themes in the very near future, and we are unsure how long compatibility will last. However, we can assure you that you will be giving plenty of warning what we are going to change, how these changes will affect you and how you can update your modules or themes.\r\n\r\nXoosla Team!', 5, 0, 1, 'C', 'S', 1, '', '', '', '', '', 0, CURRENT_TIMESTAMP);

# ############################

#
# Table structure for table `online`
#

CREATE TABLE `online` (
  `online_uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `online_uname` varchar(25) NOT NULL DEFAULT '',
  `online_updated` int(10) unsigned NOT NULL DEFAULT '0',
  `online_module` smallint(5) unsigned NOT NULL DEFAULT '0',
  `online_ip` varchar(15) NOT NULL DEFAULT '',
  KEY `online_module` (`online_module`),
  KEY `online_updated` (`online_updated`),
  KEY `online_uid` (`online_uid`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `online`
#

# ############################

#
# Table structure for table `priv_msgs`
#

CREATE TABLE `priv_msgs` (
  `msg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `msg_image` varchar(100) DEFAULT NULL,
  `subject` varchar(255) NOT NULL DEFAULT '',
  `from_userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `to_userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `msg_time` int(10) unsigned NOT NULL DEFAULT '0',
  `msg_text` text,
  `read_msg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`msg_id`),
  KEY `to_userid` (`to_userid`),
  KEY `touseridreadmsg` (`to_userid`,`read_msg`),
  KEY `msgidfromuserid` (`from_userid`,`msg_id`)
) DEFAULT CHARSET=utf8;

# ############################

#
# Table structure for table `ranks`
#

CREATE TABLE `ranks` (
  `rank_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `rank_title` varchar(50) NOT NULL DEFAULT '',
  `rank_min` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rank_max` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rank_special` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `rank_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`rank_id`),
  KEY `rank_min` (`rank_min`),
  KEY `rank_max` (`rank_max`),
  KEY `rankminrankmaxranspecial` (`rank_min`,`rank_max`,`rank_special`),
  KEY `rankspecial` (`rank_special`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `ranks`
#

INSERT INTO `ranks` (`rank_id`, `rank_title`, `rank_min`, `rank_max`, `rank_special`, `rank_image`) VALUES
(1, 'Just popping in', 0, 20, 0, 'ranks/rank3e632f95e81ca.gif'),
(2, 'Not too shy to talk', 21, 40, 0, 'ranks/rank3dbf8e94a6f72.gif'),
(3, 'Quite a regular', 41, 70, 0, 'ranks/rank3dbf8e9e7d88d.gif'),
(4, 'Just can''t stay away', 71, 150, 0, 'ranks/rank3dbf8ea81e642.gif'),
(5, 'Home away from home', 151, 10000, 0, 'ranks/rank3dbf8eb1a72e7.gif'),
(6, 'Moderator', 0, 0, 1, 'ranks/rank3dbf8edf15093.gif'),
(7, 'Webmaster', 0, 0, 1, 'ranks/rank3dbf8ee8681cd.gif');

# ############################

#
# Table structure for table `session`
#

CREATE TABLE `session` (
  `sess_id` varchar(32) NOT NULL DEFAULT '',
  `sess_updated` int(10) unsigned NOT NULL DEFAULT '0',
  `sess_ip` varchar(15) NOT NULL DEFAULT '',
  `sess_data` text,
  PRIMARY KEY (`sess_id`),
  KEY `updated` (`sess_updated`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `session`
#

# ############################

#
# Table structure for table `smiles`
#

CREATE TABLE `smiles` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL DEFAULT '',
  `smile_url` varchar(100) NOT NULL DEFAULT '',
  `emotion` varchar(75) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `smiles`
#

INSERT INTO `smiles` (`id`, `code`, `smile_url`, `emotion`, `display`) VALUES
(1, ':-D', 'smilies/biggrin.gif', 'Very Happy', 1),
(2, ':-)', 'smilies/smile.gif', 'Smile', 1),
(3, ':-(', 'smilies/sad.gif', 'Sad', 1),
(4, ':-o', 'smilies/ohmy.gif', 'OH MY GAWD!', 1),
(5, ':unsure:', 'smilies/unsure.gif', 'Confused', 1),
(6, '8-)', 'smilies/cool.gif', 'Cool', 1),
(7, ':lol:', 'smilies/laughing.gif', 'Laughing', 1),
(8, ':angry:', 'smilies/angry.gif', 'Angry', 1),
(9, ':-P', 'smilies/tongue.gif', 'Tongue', 1),
(10, ':oops:', 'smilies/redface.gif', 'Embaressed', 1),
(11, ':cry:', 'smilies/cry.gif', 'Crying (very sad)', 1),
(12, ':evil:', 'smilies/evil.gif', 'Evil or Very Mad', 1),
(13, ':rolleyes:', 'smilies/rolleyes.gif', 'Rolling Eyes', 1),
(14, ';-)', 'smilies/wink.gif', 'Wink', 1),
(15, ':pint:', 'smilies/pint.gif', 'Another pint of beer', 1),
(16, ':hammer:', 'smilies/tooltime.gif', 'ToolTimes at work', 1),
(17, ':idea:', 'smilies/idea.gif', 'I have an idea', 1),
(18, ':blush:', 'smilies/blush.gif', 'Blushing', 1),
(19, ':yes:', 'smilies/yes.gif', 'Yes!', 1),
(20, ':whistle:', 'smilies/whistle.gif', 'Whistle', 1),
(22, ':grin:', 'smilies/grin.gif', 'Grin', 1),
(23, ':wave:', 'smilies/bye.gif', 'Wave', 1),
(24, ':ok:', 'smilies/ok.gif', 'Ok', 1),
(25, ':ohyes:', 'smilies/ohyes.gif', 'Oh yes!', 1),
(26, ':thumbs:', 'smilies/good.gif', 'Good', 1),
(27, ':no:', 'smilies/no.gif', 'No', 0),
(28, ':phew:', 'smilies/phew.gif', 'Phew', 0),
(29, ':S', 'smilies/confused.gif', 'Confussed', 1),
(30, ':huh:', 'smilies/huh.gif', 'Huh?', 0);

# ############################

#
# Table structure for table `tplfile`
#

CREATE TABLE `tplfile` (
  `tpl_id` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `tpl_refid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `tpl_module` varchar(25) NOT NULL DEFAULT '',
  `tpl_tplset` varchar(50) NOT NULL DEFAULT '',
  `tpl_file` varchar(50) NOT NULL DEFAULT '',
  `tpl_desc` varchar(255) NOT NULL DEFAULT '',
  `tpl_lastmodified` int(10) unsigned NOT NULL DEFAULT '0',
  `tpl_lastimported` int(10) unsigned NOT NULL DEFAULT '0',
  `tpl_type` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`tpl_id`),
  KEY `tpl_refid` (`tpl_refid`,`tpl_type`),
  KEY `tpl_tplset` (`tpl_tplset`,`tpl_file`(10))
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `tplfile`
#

INSERT INTO `tplfile` (`tpl_id`, `tpl_refid`, `tpl_module`, `tpl_tplset`, `tpl_file`, `tpl_desc`, `tpl_lastmodified`, `tpl_lastimported`, `tpl_type`) VALUES
(1, 1, 'system', 'default', 'system_imagemanager.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(2, 1, 'system', 'default', 'system_imagemanager2.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(3, 1, 'system', 'default', 'system_userinfo.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(4, 1, 'system', 'default', 'system_userform.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(5, 1, 'system', 'default', 'system_rss.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(6, 1, 'system', 'default', 'system_redirect.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(7, 1, 'system', 'default', 'system_comment.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(8, 1, 'system', 'default', 'system_comments_flat.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(9, 1, 'system', 'default', 'system_comments_thread.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(10, 1, 'system', 'default', 'system_comments_nest.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(11, 1, 'system', 'default', 'system_siteclosed.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(12, 1, 'system', 'default', 'system_dummy.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(13, 1, 'system', 'default', 'system_notification_list.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(14, 1, 'system', 'default', 'system_notification_select.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(15, 1, 'system', 'default', 'system_block_dummy.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(16, 1, 'system', 'default', 'system_homepage.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(17, 1, 'system', 'default', 'system_bannerlogin.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(18, 1, 'system', 'default', 'system_banner.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(19, 1, 'system', 'default', 'system_bannerdisplay.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'module'),
(20, 1, 'system', 'default', 'system_header.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(21, 1, 'system', 'default', 'system_banners.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(22, 1, 'system', 'default', 'system_modules.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(23, 1, 'system', 'default', 'system_modules_confirm.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(24, 1, 'system', 'default', 'system_avatars.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(25, 1, 'system', 'default', 'system_smilies.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(26, 1, 'system', 'default', 'system_blocks.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(27, 1, 'system', 'default', 'system_blocks_item.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(28, 1, 'system', 'default', 'system_comments.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(29, 1, 'system', 'default', 'system_userrank.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(30, 1, 'system', 'default', 'system_users.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(31, 1, 'system', 'default', 'system_preferences.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(32, 1, 'system', 'default', 'system_mailusers.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(33, 1, 'system', 'default', 'system_groups.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(34, 1, 'system', 'default', 'system_images.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(35, 1, 'system', 'default', 'system_templates.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(36, 1, 'system', 'default', 'system_index.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(37, 1, 'system', 'default', 'system_maintenance.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(38, 1, 'system', 'default', 'system_help.html', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'admin'),
(39, 1, 'system', 'default', 'system_block_user.html', 'Shows user block', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'block'),
(40, 2, 'system', 'default', 'system_block_login.html', 'Shows login form', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'block'),
(41, 3, 'system', 'default', 'system_block_search.html', 'Shows search form block', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'block'),
(42, 4, 'system', 'default', 'system_block_waiting.html', 'Shows contents waiting for approval', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'block'),
(43, 5, 'system', 'default', 'system_block_mainmenu.html', 'Shows the main navigation menu of the site', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'block'),
(44, 6, 'system', 'default', 'system_block_siteinfo.html', 'Shows basic info about the site and a link to Recommend Us pop up window', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'block'),
(45, 7, 'system', 'default', 'system_block_online.html', 'Displays users/guests currently online', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'block'),
(46, 8, 'system', 'default', 'system_block_topusers.html', 'Top posters', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'block'),
(47, 9, 'system', 'default', 'system_block_newusers.html', 'Shows most recent users', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'block'),
(48, 10, 'system', 'default', 'system_block_comments.html', 'Shows most recent comments', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'block'),
(49, 11, 'system', 'default', 'system_block_notification.html', 'Shows notification options', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'block'),
(50, 12, 'system', 'default', 'system_block_themes.html', 'Shows theme selection box', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'block');

# ############################

#
# Table structure for table `tplset`
#

CREATE TABLE `tplset` (
  `tplset_id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `tplset_name` varchar(50) NOT NULL DEFAULT '',
  `tplset_desc` varchar(255) NOT NULL DEFAULT '',
  `tplset_credits` text,
  `tplset_created` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tplset_id`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `tplset`
#

INSERT INTO `tplset` (`tplset_id`, `tplset_name`, `tplset_desc`, `tplset_credits`, `tplset_created`) VALUES
(1, 'default', 'Xoosla Default Template Set', '', CURRENT_TIMESTAMP);

# ############################

#
# Table structure for table `tplsource`
#

CREATE TABLE `tplsource` (
  `tpl_id` mediumint(7) unsigned NOT NULL DEFAULT '0',
  `tpl_source` mediumtext,
  KEY `tpl_id` (`tpl_id`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `tplsource`
#

# ############################

#
# Table structure for table `users`
#

CREATE TABLE `users` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `uname` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `user_avatar` varchar(30) NOT NULL DEFAULT 'blank.gif',
  `user_regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `user_icq` varchar(15) NOT NULL DEFAULT '',
  `user_from` varchar(100) NOT NULL DEFAULT '',
  `user_sig` tinytext,
  `user_viewemail` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `actkey` varchar(8) NOT NULL DEFAULT '',
  `user_aim` varchar(18) NOT NULL DEFAULT '',
  `user_yim` varchar(25) NOT NULL DEFAULT '',
  `user_msnm` varchar(100) NOT NULL DEFAULT '',
  `pass` varchar(32) NOT NULL DEFAULT '',
  `posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `attachsig` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `rank` smallint(5) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `theme` varchar(100) NOT NULL DEFAULT '',
  `timezone_offset` float(3,1) NOT NULL DEFAULT '0.0',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0',
  `umode` varchar(10) NOT NULL DEFAULT '',
  `uorder` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `notify_method` tinyint(1) NOT NULL DEFAULT '1',
  `notify_mode` tinyint(1) NOT NULL DEFAULT '0',
  `user_occ` varchar(100) NOT NULL DEFAULT '',
  `bio` tinytext,
  `user_intrest` varchar(150) NOT NULL DEFAULT '',
  `user_mailok` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`),
  KEY `uname` (`uname`),
  KEY `email` (`email`),
  KEY `uiduname` (`uid`,`uname`),
  KEY `unamepass` (`uname`,`pass`),
  KEY `level` (`level`)
) DEFAULT CHARSET=utf8;

#
# Dumping data for table `users`
#


# ############################

#
# Table structure for table `xoopscomments`
#

CREATE TABLE `xoopscomments` (
  `com_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `com_pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `com_rootid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `com_modid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `com_itemid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `com_icon` varchar(25) NOT NULL DEFAULT '',
  `com_created` int(10) unsigned NOT NULL DEFAULT '0',
  `com_modified` int(10) unsigned NOT NULL DEFAULT '0',
  `com_uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `com_ip` varchar(15) NOT NULL DEFAULT '',
  `com_title` varchar(255) NOT NULL DEFAULT '',
  `com_text` text,
  `com_sig` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `com_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `com_exparams` varchar(255) NOT NULL DEFAULT '',
  `dohtml` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dosmiley` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `doxcode` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `doimage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dobr` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`com_id`),
  KEY `com_pid` (`com_pid`),
  KEY `com_itemid` (`com_itemid`),
  KEY `com_uid` (`com_uid`),
  KEY `com_title` (`com_title`(40)),
  KEY `com_status` (`com_status`)
) DEFAULT CHARSET=utf8;

# ############################

#
# Table structure for table `xoopsnotifications`
#

CREATE TABLE `xoopsnotifications` (
  `not_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `not_modid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `not_itemid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `not_category` varchar(30) NOT NULL DEFAULT '',
  `not_event` varchar(30) NOT NULL DEFAULT '',
  `not_uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `not_mode` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`not_id`),
  KEY `not_modid` (`not_modid`),
  KEY `not_itemid` (`not_itemid`),
  KEY `not_class` (`not_category`),
  KEY `not_uid` (`not_uid`),
  KEY `not_event` (`not_event`)
) DEFAULT CHARSET=utf8;
