DROP TABLE IF EXISTS `cake_menu_i18ns`;
CREATE TABLE IF NOT EXISTS `cake_menu_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT '',
  `cate_id` int(11) DEFAULT '0',
  `creator` bigint(13) DEFAULT '0',
  `lastupdator` bigint(13) DEFAULT '0',
  `remoteurl` varchar(200) DEFAULT '',
  `status` int(11) DEFAULT '0',
  `published` tinyint(1) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `foreign_key` bigint(11) DEFAULT '0',
  `locale` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `foreign_key` (`foreign_key`,`locale`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
REPLACE INTO `cake_i18nfields` (`id`, `name`, `savetodb`, `translate`, `type`, `model`, `locale`, `length`, `sort`, `allowadd`, `allowedit`, `selectmodel`, `selectvaluefield`, `selecttxtfield`, `selectparentid`, `selectautoload`, `selectvalues`, `associateflag`, `associateelement`, `associatefield`, `associatetype`, `formtype`, `default`, `allownull`, `validationregular`, `description`, `onchange`, `explodeimplode`, `explain`, `deleted`, `created`, `updated`, `conditions`) VALUES (NULL, 'id', '1', '编号', 'integer', 'MenuI18n', 'zh_cn', '11', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-03-13 22:43:35', '2011-03-13 22:43:35', NULL),
(NULL, 'name', '1', 'name', 'string', 'MenuI18n', 'zh_cn', '200', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-03-13 22:43:35', '2011-03-13 22:43:35', NULL),
(NULL, 'cate_id', '1', '所属分类', 'integer', 'MenuI18n', 'zh_cn', '11', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-03-13 22:43:35', '2011-03-13 22:43:35', NULL),
(NULL, 'creator', '1', 'creator', 'integer', 'MenuI18n', 'zh_cn', '13', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-03-13 22:43:35', '2011-03-13 22:43:35', NULL),
(NULL, 'lastupdator', '1', 'lastupdator', 'integer', 'MenuI18n', 'zh_cn', '13', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-03-13 22:43:35', '2011-03-13 22:43:35', NULL),
(NULL, 'remoteurl', '1', 'remoteurl', 'string', 'MenuI18n', 'zh_cn', '200', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-03-13 22:43:35', '2011-03-13 22:43:35', NULL),
(NULL, 'status', '1', 'status', 'integer', 'MenuI18n', 'zh_cn', '11', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-03-13 22:43:35', '2011-03-13 22:43:35', NULL),
(NULL, 'published', '1', '是否发布', 'boolean', 'MenuI18n', 'zh_cn', '1', 0, '1', '1', NULL, NULL, NULL, NULL, '1', '0=>否\n1=>是', '0', NULL, NULL, 'equal', 'select', '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-03-13 22:43:35', '2011-03-13 22:43:35', NULL),
(NULL, 'deleted', '1', '是否删除', 'boolean', 'MenuI18n', 'zh_cn', '1', 0, '1', '1', NULL, NULL, NULL, NULL, '1', '0=>否\n1=>是', '0', NULL, NULL, 'equal', 'select', '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-03-13 22:43:35', '2011-03-13 22:43:35', NULL),
(NULL, 'created', '1', '创建时间', 'datetime', 'MenuI18n', 'zh_cn', NULL, 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'datetime', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-03-13 22:43:35', '2011-03-13 22:43:35', NULL),
(NULL, 'updated', '1', '修改时间', 'datetime', 'MenuI18n', 'zh_cn', NULL, 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'datetime', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-03-13 22:43:35', '2011-03-13 22:43:35', NULL),
(NULL, 'foreign_key', '1', '对应主模块的外键', 'integer', 'MenuI18n', 'zh_cn', '11', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'input', '', '1', '', NULL, '', '', '', 0, '2011-03-13 22:44:31', '2011-03-13 22:44:31', NULL),
(NULL, 'locale', '1', '语言类型', 'string', 'MenuI18n', 'zh_cn', '10', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'input', '', '1', '', NULL, '', '', '', 0, '2011-03-13 22:44:58', '2011-03-13 22:44:58', NULL);
REPLACE INTO `cake_modelextends` (`id`, `name`, `cname`, `belongtype`, `modeltype`, `idtype`, `status`, `created`, `updated`, `tablename`, `related_model`, `security`, `operatorfields`, `deleted`, `cate_id`, `localetype`) VALUES (NULL, 'MenuI18n', '菜单多语言', '', 'default', '', 27, '2011-03-13 22:35:57', '2011-03-13 22:35:57', 'cake_menu_i18ns', '', '', '', '0', 0, 0);



REPLACE INTO `cake_menu_i18ns` (`id`, `name`, `cate_id`, `creator`, `lastupdator`, `remoteurl`, `status`, `published`, `deleted`, `created`, `updated`, `foreign_key`, `locale`) VALUES (3, 'Articles', 0, 0, 0, '', 0, '0', '0', '2011-03-13 23:09:24', '2011-03-13 23:09:24', 22, 'en_us'),
(2, 'Columns', 0, 0, 0, '', 0, '0', '0', '2011-03-13 23:06:27', '2012-08-15 20:34:37', 29, 'en_us'),
(4, 'Crawls', 0, 0, 0, '', 0, '0', '0', '2011-03-13 23:09:58', '2012-03-03 14:28:23', 16, 'en_us'),
(5, 'Content Manage', 0, 0, 0, '', 0, '0', '0', '2011-03-13 23:10:16', '2011-03-13 23:11:21', 3, 'en_us'),
(6, 'Products', 0, 0, 0, '', 0, '0', '0', '2011-03-13 23:10:39', '2011-03-13 23:10:39', 21, 'en_us'),
(8, 'Content', 0, 0, 0, '', 0, '0', '0', '2011-03-13 23:15:50', '2011-03-13 23:15:50', 2, 'en_us'),
(9, 'Users', 0, 0, 0, '', 0, '0', '0', '2011-03-13 23:16:06', '2012-08-16 23:23:12', 10, 'en_us'),
(11, 'Marketing', 0, 0, 0, '', 0, '0', '0', '2011-03-13 23:16:50', '2011-03-13 23:16:50', 36, 'en_us'),
(12, 'Advertise', 0, 0, 0, '', 0, '0', '0', '2011-06-03 11:51:53', '2011-06-03 11:51:53', 97, 'en_us'),
(13, 'Update Language Cache', 0, 0, 0, '', 0, '0', '0', '2011-08-04 21:16:51', '2011-08-04 21:16:51', 98, 'en_us'),
(14, '', 0, 0, 0, '', 0, '0', '0', '2011-08-09 22:26:27', '2011-08-09 22:26:27', 99, 'en_us'),
(16, '', 0, 0, 0, '', 0, '0', '0', '2011-08-27 09:42:07', '2011-08-27 09:42:07', 28, 'en_us'),
(17, '', 0, 0, 0, '', 0, '0', '0', '2011-08-27 09:46:09', '2012-03-03 14:29:17', 101, 'en_us'),
(22, '', 0, 0, 0, '', 0, '0', '0', '2011-09-17 21:44:26', '2011-09-17 21:44:26', 106, 'en_us'),
(24, '', 0, 0, 0, '', 0, '0', '0', '2011-12-24 13:54:54', '2012-03-03 13:45:52', 108, 'en_us'),
(25, '', 0, 0, 0, '', 0, '0', '0', '2011-12-24 21:02:22', '2011-12-24 21:02:22', 109, 'en_us'),
(26, '', 0, 0, 0, '', 0, '0', '0', '2011-12-25 16:15:10', '2011-12-25 16:15:10', 110, 'en_us'),
(27, '', 0, 0, 0, '', 0, '0', '0', '2011-12-26 22:01:08', '2011-12-28 23:05:44', 111, 'en_us'),
(28, '', 0, 0, 0, '', 0, '0', '0', '2012-01-26 10:57:22', '2012-01-26 10:57:22', 112, 'en_us'),
(29, '', 0, 0, 0, '', 0, '0', '0', '2012-01-26 10:58:35', '2012-01-26 10:59:27', 113, 'en_us'),
(30, '', 0, 0, 0, '', 0, '0', '0', '2012-01-26 10:59:56', '2012-01-29 11:31:08', 114, 'en_us'),
(33, 'Maintain', 0, 0, 0, '', 0, '0', '0', '2012-08-16 23:18:28', '2012-08-19 22:26:23', 57, 'en_us'),
(35, '', 0, 0, 0, '', 0, '0', '0', '2012-08-16 23:21:14', '2012-08-16 23:21:14', 12, 'en_us'),
(36, '', 0, 0, 0, '', 0, '0', '0', '2012-08-16 23:21:47', '2012-08-16 23:21:47', 65, 'en_us'),
(38, 'Content', 0, 0, 0, '', 0, '0', '0', '2012-08-17 22:29:05', '2012-08-19 22:24:48', 117, 'en_us'),
(42, 'System', 0, 0, 0, '', 0, '0', '0', '2012-08-22 15:29:52', '2012-08-22 15:29:52', 30, 'en_us'),
(43, 'Dashboard', 0, 0, 0, '', 0, '0', '0', '2012-08-22 15:30:41', '2012-08-22 15:30:41', 53, 'en_us');