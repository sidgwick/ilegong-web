DROP TABLE IF EXISTS `cake_taobao_shops`;
CREATE TABLE IF NOT EXISTS `cake_taobao_shops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT '',
  `sid` bigint(11) DEFAULT '0',
  `cid` bigint(11) DEFAULT '0',
  `nick` varchar(60) DEFAULT NULL,
  `pic_path` varchar(200) DEFAULT NULL,
  `bulletin` text,
  `published` tinyint(1) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `content` text,
  `item_score` varchar(10) DEFAULT NULL,
  `service_score` varchar(10) DEFAULT NULL,
  `delivery_score` varchar(10) DEFAULT NULL,
  `user_id` varchar(44) DEFAULT NULL,
  `click_url` varchar(200) DEFAULT NULL,
  `commission_rate` varchar(10) DEFAULT NULL,
  `seller_credit` bigint(10) DEFAULT '0',
  `shop_type` varchar(10) DEFAULT NULL,
  `total_auction` bigint(44) DEFAULT '0',
  `auction_count` bigint(44) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
REPLACE INTO `cake_i18nfields` (`id`, `name`, `savetodb`, `translate`, `type`, `model`, `locale`, `length`, `sort`, `allowadd`, `allowedit`, `selectmodel`, `selectvaluefield`, `selecttxtfield`, `selectparentid`, `selectautoload`, `selectvalues`, `associateflag`, `associateelement`, `associatefield`, `associatetype`, `formtype`, `default`, `allownull`, `validationregular`, `description`, `onchange`, `explodeimplode`, `explain`, `deleted`, `created`, `updated`, `conditions`) VALUES (NULL, 'id', '1', '编号', 'integer', 'TaobaoShop', 'zh_cn', '11', 6, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-08-27 17:02:44', '2011-08-27 17:02:44', NULL),
(NULL, 'name', '1', '名称', 'string', 'TaobaoShop', 'zh_cn', '200', 5, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-08-27 17:02:44', '2011-08-27 17:02:44', NULL),
(NULL, 'sid', '1', '淘宝店铺编号', 'integer', 'TaobaoShop', 'zh_cn', '11', 6, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', 'equal', '', '', '1', '', NULL, '', '', '', 0, '2011-08-27 17:02:44', '2011-08-27 17:02:44', ''),
(NULL, 'cid', '1', '店铺类目', 'integer', 'TaobaoShop', 'zh_cn', '11', 6, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', 'equal', '', '', '1', '', NULL, '', '', '', 0, '2011-08-27 17:02:44', '2011-08-27 17:02:44', ''),
(NULL, 'nick', '1', '卖家昵称', 'string', 'TaobaoShop', 'zh_cn', '60', 6, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', 'equal', '', '', '1', '', NULL, '', '', '', 0, '2011-08-27 17:02:44', '2011-08-27 17:02:44', ''),
(NULL, 'pic_path', '1', '图片地址', 'string', 'TaobaoShop', 'zh_cn', '200', 5, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', 'equal', '', '', '1', '', NULL, '', '', '', 0, '2011-08-27 17:02:44', '2011-08-27 17:02:44', ''),
(NULL, 'bulletin', '1', '店铺公告', 'content', 'TaobaoShop', 'zh_cn', '', 3, '1', '1', '', NULL, NULL, NULL, '1', '0=>否\r\n1=>是', '0', '', '', 'equal', 'select', '0', '1', '', NULL, '', '', '', 0, '2011-08-27 17:02:44', '2011-08-27 17:02:44', ''),
(NULL, 'published', '1', '是否发布', 'integer', 'TaobaoShop', 'zh_cn', '11', 3, '1', '1', NULL, NULL, NULL, NULL, '1', '0=>否\n1=>是', '0', NULL, NULL, 'equal', 'select', '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-08-27 17:02:44', '2011-08-27 17:02:44', NULL),
(NULL, 'deleted', '1', '是否删除', 'integer', 'TaobaoShop', 'zh_cn', '11', 3, '1', '1', NULL, NULL, NULL, NULL, '1', '0=>否\n1=>是', '0', NULL, NULL, 'equal', 'select', '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-08-27 17:02:44', '2011-08-27 17:02:44', NULL),
(NULL, 'created', '1', '创建时间', 'datetime', 'TaobaoShop', 'zh_cn', NULL, 2, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'datetime', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-08-27 17:02:44', '2011-08-27 17:02:44', NULL),
(NULL, 'updated', '1', '修改时间', 'datetime', 'TaobaoShop', 'zh_cn', NULL, 1, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'datetime', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-08-27 17:02:44', '2011-08-27 17:02:44', NULL),
(NULL, 'content', '1', '内容', 'content', 'TaobaoShop', 'zh_cn', '', NULL, '1', '1', '', NULL, NULL, NULL, '1', '', '0', '', '', '', 'ckeditor', '', '1', '', NULL, '', '', '', 0, '2011-10-08 23:23:23', '2011-10-08 23:23:23', ''),
(NULL, 'item_score', '1', '商品描述评分', 'string', 'TaobaoShop', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'input', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-10-08 23:24:58', '2011-10-08 23:24:58', NULL),
(NULL, 'service_score', '1', '服务态度评分', 'string', 'TaobaoShop', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'input', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-10-08 23:24:58', '2011-10-08 23:24:58', NULL),
(NULL, 'delivery_score', '1', '发货速度评分', 'string', 'TaobaoShop', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'input', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-10-08 23:24:58', '2011-10-08 23:24:58', NULL),
(NULL, 'user_id', '1', '店铺用户id', 'string', 'TaobaoShop', 'zh_cn', '44', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'input', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-10-12 21:46:49', '2011-10-12 21:46:49', NULL),
(NULL, 'click_url', '1', '店铺推广url', 'string', 'TaobaoShop', 'zh_cn', '200', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'input', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-10-12 21:46:49', '2011-10-12 21:46:49', NULL),
(NULL, 'commission_rate', '1', '店铺佣金', 'string', 'TaobaoShop', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'input', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-10-12 21:46:49', '2011-10-12 21:46:49', NULL),
(NULL, 'seller_credit', '1', '店铺信用等级', 'integer', 'TaobaoShop', 'zh_cn', '', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'input', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-10-12 21:46:49', '2011-10-12 21:46:49', NULL),
(NULL, 'shop_type', '1', '店铺类型 B=商城卖家 C=普通卖家', 'string', 'TaobaoShop', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'input', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-10-12 21:46:49', '2011-10-12 21:46:49', NULL),
(NULL, 'total_auction', '1', '累计推广量', 'integer', 'TaobaoShop', 'zh_cn', '44', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'input', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-10-12 21:46:49', '2011-10-12 21:46:49', NULL),
(NULL, 'auction_count', '1', '店铺内商品总数', 'integer', 'TaobaoShop', 'zh_cn', '44', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', 'input', NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-10-12 21:46:49', '2011-10-12 21:46:49', NULL);
REPLACE INTO `cake_modelextends` (`id`, `name`, `cname`, `belongtype`, `modeltype`, `idtype`, `status`, `created`, `updated`, `tablename`, `related_model`, `security`, `operatorfields`, `deleted`, `cate_id`, `localetype`) VALUES (NULL, 'TaobaoShop', '淘宝店铺', '', 'default', '', 27, '2011-08-27 17:02:44', '2011-08-27 17:02:44', 'cake_taobao_shops', '', '', '', '0', 0, 0);
