DROP TABLE IF EXISTS `cake_auto_signs`;
CREATE TABLE IF NOT EXISTS `cake_auto_signs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `coverimg` varchar(200) DEFAULT '',
  `cate_id` int(11) DEFAULT '0',
  `creator` int(11) DEFAULT '0',
  `lastupdator` int(11) DEFAULT '0',
  `remoteurl` varchar(200) DEFAULT '',
  `status` tinyint(4) DEFAULT '0',
  `published` tinyint(1) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `locale` char(5) DEFAULT '',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `signsite` varchar(30) DEFAULT NULL,
  `sslpem` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
REPLACE INTO `cake_i18nfields` (`id`, `name`, `savetodb`, `translate`, `type`, `model`, `locale`, `length`, `sort`, `allowadd`, `allowedit`, `selectmodel`, `selectvaluefield`, `selecttxtfield`, `selectparentid`, `selectautoload`, `selectvalues`, `associateflag`, `associateelement`, `associatefield`, `associatetype`, `formtype`, `default`, `allownull`, `validationregular`, `description`, `onchange`, `explodeimplode`, `explain`, `deleted`, `created`, `updated`, `conditions`) VALUES (NULL, 'updated', '1', '修改时间', 'datetime', 'AutoSign', NULL, NULL, 1, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'datetime', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2012-11-01 23:31:16', '2012-11-01 23:31:16', NULL),
(NULL, 'created', '1', '创建时间', 'datetime', 'AutoSign', NULL, NULL, 2, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'datetime', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2012-11-01 23:31:16', '2012-11-01 23:31:16', NULL),
(NULL, 'locale', '1', '语言类型', 'char', 'AutoSign', NULL, '5', 3, '1', '1', NULL, NULL, NULL, NULL, '1', 'zh_cn', '0', NULL, NULL, 'equal', 'select', '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2012-11-01 23:31:16', '2012-11-01 23:31:16', NULL),
(NULL, 'deleted', '1', '是否删除', 'integer', 'AutoSign', NULL, '11', 3, '1', '1', NULL, NULL, NULL, NULL, '1', '0=>否\n1=>是', '0', NULL, NULL, 'equal', 'select', '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2012-11-01 23:31:16', '2012-11-01 23:31:16', NULL),
(NULL, 'published', '1', '是否发布', 'integer', 'AutoSign', NULL, '11', 3, '1', '1', NULL, NULL, NULL, NULL, '1', '0=>否\n1=>是', '0', NULL, NULL, 'equal', 'select', '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2012-11-01 23:31:16', '2012-11-01 23:31:16', NULL),
(NULL, 'status', '1', '状态', 'integer', 'AutoSign', NULL, '11', 3, '1', '1', NULL, NULL, NULL, NULL, '1', '0=>否\n1=>是', '0', NULL, NULL, 'equal', 'select', '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2012-11-01 23:31:16', '2012-11-01 23:31:16', NULL),
(NULL, 'remoteurl', '1', '引用地址', 'string', 'AutoSign', NULL, '200', 5, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2012-11-01 23:31:16', '2012-11-01 23:31:16', NULL),
(NULL, 'lastupdator', '1', '最后修改人', 'integer', 'AutoSign', NULL, '11', 6, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 1, '2012-11-01 23:31:16', '2012-11-01 23:31:16', NULL),
(NULL, 'creator', '1', '编创建者', 'integer', 'AutoSign', NULL, '11', 6, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2012-11-01 23:31:16', '2012-11-01 23:31:16', NULL),
(NULL, 'cate_id', '1', '所属分类', 'integer', 'AutoSign', NULL, '11', 6, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2012-11-01 23:31:16', '2012-11-01 23:31:16', NULL),
(NULL, 'coverimg', '1', '封面图片', 'string', 'AutoSign', NULL, '200', 5, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 1, '2012-11-01 23:31:16', '2012-11-01 23:31:16', NULL),
(NULL, 'name', '1', '用户名', 'string', 'AutoSign', NULL, '200', 5, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', 'equal', '', '', '1', '', NULL, '', '', '', 0, '2012-11-01 23:31:16', '2012-11-01 23:31:16', ''),
(NULL, 'id', '1', '编号', 'integer', 'AutoSign', NULL, '11', 6, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2012-11-01 23:31:16', '2012-11-01 23:31:16', NULL),
(NULL, 'password', '1', '密码', 'string', 'AutoSign', 'zh_cn', '200', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', '', '', '1', '', NULL, '', '', '', 0, '2012-11-02 15:40:40', '2012-11-02 15:40:40', ''),
(NULL, 'signsite', '1', '签到站点', 'string', 'AutoSign', 'zh_cn', '30', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', '', '', '1', '', NULL, '', '', '', 0, '2012-11-02 15:48:35', '2012-11-02 15:48:35', ''),
(NULL, 'sslpem', '1', 'ssl证书', 'string', 'AutoSign', 'zh_cn', '200', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'file', '', '1', '', NULL, '', '', 'https协议时需要。可以从firefox页面信息中导出', 0, '2012-11-02 21:21:55', '2012-11-02 21:21:55', '');
REPLACE INTO `cake_modelextends` (`id`, `name`, `cname`, `belongtype`, `modeltype`, `idtype`, `status`, `created`, `updated`, `tablename`, `related_model`, `security`, `operatorfields`, `deleted`, `cate_id`, `localetype`) VALUES (NULL, 'AutoSign', '自动签到', '', 'default', '', 27, '2012-11-01 23:31:16', '2012-11-01 23:31:16', 'cake_auto_signs', '', '', '', '0', NULL, NULL);
