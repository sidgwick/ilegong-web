DROP TABLE IF EXISTS `cake_comments`;
CREATE TABLE IF NOT EXISTS `cake_comments` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `data_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `username` varchar(50) DEFAULT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` varchar(1000) DEFAULT NULL,
  `rating` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `notify` tinyint(1) DEFAULT '0',
  `type` varchar(100) DEFAULT NULL,
  `comment_type` varchar(100) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM  DEFAULT CHARSET=utf8;
--alter table cake_comments add pictures varchar(10000) not null default '';
REPLACE INTO `cake_i18nfields` (`id`, `name`, `savetodb`, `translate`, `type`, `model`, `locale`, `length`, `sort`, `allowadd`, `allowedit`, `selectmodel`, `selectvaluefield`, `selecttxtfield`, `selectparentid`, `selectautoload`, `selectvalues`, `associateflag`, `associateelement`, `associatefield`, `associatetype`, `formtype`, `default`, `allownull`, `validationregular`, `description`, `onchange`, `explodeimplode`, `explain`, `deleted`, `created`, `updated`, `conditions`) VALUES (NULL, 'id', '1', '编号', 'integer', 'Comment', 'zh_cn', '20', 19, '0', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', '', NULL, '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'parent_id', '1', '父级分类', 'integer', 'Comment', 'zh_cn', '11', 18, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'data_id', '1', '数据编号', 'integer', 'Comment', 'zh_cn', '11', 17, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'user_id', '1', '用户编号', 'integer', 'Comment', 'zh_cn', '11', 16, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '0', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'username', '1', '用户名', 'string', 'Comment', 'zh_cn', '50', 15, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', 'equal', '', '', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'ip', '1', '用户ip', 'string', 'Comment', 'zh_cn', '100', 12, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'title', '1', '标题', 'string', 'Comment', 'zh_cn', '255', 11, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', '', NULL, '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'body', '1', '评论内容', 'string', 'Comment', 'zh_cn', '1000', 10, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', 'textarea', '', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'rating', '1', '评分', 'integer', 'Comment', 'zh_cn', '11', 9, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'status', '1', '发布状态', 'boolean', 'Comment', 'zh_cn', '1', 8, '1', '1', 'Misccate', 'id', 'name', 25, '1', NULL, '0', NULL, NULL, 'treenode', 'select', '0', '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'notify', '1', '回复通知', 'integer', 'Comment', 'zh_cn', '1', 7, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '0', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'type', '1', '类型', 'string', 'Comment', 'zh_cn', '100', 6, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', '', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'comment_type', '1', '评论类型', 'string', 'Comment', 'zh_cn', '100', 5, '1', '1', '', '', '', NULL, '1', '', '0', '', '', '', '', 'comment', '1', '', '', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(NULL, 'lft', '1', '树左节点', 'integer', 'Comment', 'zh_cn', '11', 4, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', '', NULL, '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'rght', '1', '树右节点', 'integer', 'Comment', 'zh_cn', '11', 3, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', '', NULL, '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'updated', '1', '修改时间', 'datetime', 'Comment', 'zh_cn', NULL, 1, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'datetime', NULL, '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(NULL, 'created', '1', '创建时间', 'datetime', 'Comment', 'zh_cn', NULL, 2, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'datetime', NULL, '1', NULL, '', NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);
REPLACE INTO `cake_modelextends` (`id`, `name`, `cname`, `belongtype`, `modeltype`, `idtype`, `status`, `created`, `updated`, `tablename`, `related_model`, `security`, `operatorfields`, `deleted`, `cate_id`, `localetype`) VALUES (NULL, 'Comment', '评论', 'onetomany', 'default', '', 26, '2010-06-30 23:06:27', '2010-06-30 23:06:27', 'cake_comments', '', '', '', '0', 1, 0);