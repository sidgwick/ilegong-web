DROP TABLE IF EXISTS `cake_users`;
CREATE TABLE IF NOT EXISTS `cake_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT '0',
  `username` varchar(60) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `activation_key` varchar(60) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `bio` text,
  `timezone` varchar(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `updated` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `sex` int(11) DEFAULT NULL,
  `idcard` varchar(18) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `description` varchar(400) DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `province` varchar(60) DEFAULT NULL,
  `screen_name` varchar(60) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
REPLACE INTO `cake_i18nfields` (`id`, `name`, `savetodb`, `translate`, `type`, `model`, `locale`, `length`, `sort`, `allowadd`, `allowedit`, `selectmodel`, `selectvaluefield`, `selecttxtfield`, `selectparentid`, `selectautoload`, `selectvalues`, `associateflag`, `associateelement`, `associatefield`, `associatetype`, `formtype`, `default`, `allownull`, `validationregular`, `description`, `onchange`, `explodeimplode`, `explain`, `deleted`, `created`, `updated`, `conditions`) VALUES (NULL, 'id', '1', '编号', 'integer', 'User', 'zh_cn', '20', 18, '0', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', '', NULL, '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'role_id', '1', '用户角色', 'integer', 'User', 'zh_cn', '11', 17, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'username', '1', '用户名', 'string', 'User', 'zh_cn', '60', 16, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'password', '1', '密码', 'string', 'User', 'zh_cn', '100', 15, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '', '1', '', '', '', '', '当不修改密码时，请保留为空', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'nickname', '1', '昵称', 'string', 'User', 'zh_cn', '50', 14, '1', '1', '', '', '', NULL, '1', NULL, '0', NULL, '', 'equal', '', '', '1', '', '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'email', '1', '邮箱', 'string', 'User', 'zh_cn', '100', 11, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'website', '1', '个人站点', 'string', 'User', 'zh_cn', '100', 10, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'activation_key', '1', '激活码', 'string', 'User', 'zh_cn', '60', 9, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '', '1', '', '', '', '', '激活帐号或者找回密码时的地址传参', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'image', '1', '头像', 'string', 'User', 'zh_cn', '255', 8, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'bio', '1', 'bio', 'text', 'User', 'zh_cn', NULL, 7, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', '', NULL, '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'timezone', '1', '所在时区', 'string', 'User', 'zh_cn', '10', 6, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '0', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'status', '1', '发布状态', 'boolean', 'User', 'zh_cn', '1', 4, '1', '1', 'Misccate', 'id', 'name', 25, '1', NULL, '0', NULL, NULL, 'treenode', 'select', '0', '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'updated', '1', '修改时间', 'datetime', 'User', 'zh_cn', NULL, 3, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'datetime', NULL, '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'created', '1', '创建时间', 'datetime', 'User', 'zh_cn', NULL, 2, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'datetime', NULL, '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'last_login', '1', '上次登录时间', 'datetime', 'User', 'zh_cn', NULL, 5, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', '', NULL, '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'sex', '1', '性别', 'integer', 'User', 'zh_cn', '1', 13, '1', '1', '', '', '', NULL, '1', '0=>女\n1=>男\n2=>不详', '0', NULL, NULL, 'equal', 'select', '1', '1', '', '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'idcard', '1', '身份证', 'string', 'User', 'zh_cn', '18', 12, '1', '1', '', '', '', NULL, '1', '', '0', NULL, NULL, 'equal', 'input', '', '1', '', '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'deleted', '1', '是否删除', 'boolean', 'User', 'zh_cn', '1', 1, '0', '1', NULL, NULL, NULL, NULL, '1', '0=>否\n1=>是', '0', NULL, NULL, 'equal', 'select', '0', '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'province', '1', '省份', 'string', 'User', 'zh_cn', '60', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'input', '', '1', '', NULL, '', '', '', 0, '2010-10-17 12:44:17', '2010-10-17 12:44:17', NULL),
(NULL, 'city', '1', '城市', 'string', 'User', 'zh_cn', '60', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'input', '', '1', '', NULL, '', '', '', 0, '2010-10-17 12:43:53', '2010-10-17 12:43:53', NULL),
(NULL, 'description', '1', '描述', 'string', 'User', 'zh_cn', '400', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'input', '', '1', '', NULL, '', '', '', 0, '2010-10-17 12:41:43', '2010-10-17 12:41:43', NULL),
(NULL, 'location', '1', '所在地址', 'string', 'User', 'zh_cn', '200', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'input', '', '1', '', NULL, '', '', '', 0, '2010-10-17 12:41:14', '2010-10-17 12:41:14', NULL),
(NULL, 'sina_token_secret', '1', '新浪认证密钥', 'string', 'User', 'zh_cn', '32', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'input', '', '1', '', NULL, '', '', '', 0, '2010-10-17 12:15:47', '2010-10-17 12:15:47', NULL),
(NULL, 'sina_token', '1', '新浪认证', 'string', 'User', 'zh_cn', '32', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'input', '', '1', '', NULL, '', '', '', 0, '2010-10-17 12:15:03', '2010-10-17 12:15:03', NULL),
(NULL, 'sina_uid', '1', '新浪用户id', 'integer', 'User', 'zh_cn', '13', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'input', '', '1', '', NULL, '', '', '', 0, '2010-10-17 12:13:45', '2010-10-17 12:13:45', NULL),
(NULL, 'sina_domain', '1', '个性化域名', 'string', 'User', 'zh_cn', '60', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'input', '', '1', '', NULL, '', '', '', 0, '2010-10-17 17:53:38', '2010-10-17 17:53:38', NULL),
(NULL, 'screen_name', '1', '显示名', 'string', 'User', 'zh_cn', '60', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'input', '', '1', '', NULL, '', '', '', 0, '2010-10-20 13:36:04', '2010-10-20 13:36:04', NULL),
(NULL, 'published', '1', '是否发布', 'integer', 'User', 'zh_cn', '1', NULL, '1', '1', '', NULL, NULL, NULL, '1', '0=>否\n1=>是', '0', '', '', '', 'select', '1', '1', '', NULL, '', '', '', 0, '2010-10-27 23:24:50', '2010-10-27 23:24:50', NULL);
REPLACE INTO `cake_modelextends` (`id`, `name`, `cname`, `belongtype`, `modeltype`, `idtype`, `status`, `created`, `updated`, `tablename`, `related_model`, `security`, `operatorfields`, `deleted`, `cate_id`, `localetype`) VALUES (NULL, 'User', '用户', 'onetomany', 'default', '', 26, '2010-06-30 23:06:27', '2010-06-30 23:06:27', 'cake_users', NULL, NULL, NULL, '0', 0, 0);
