DROP TABLE IF EXISTS `cake_schedules`;
CREATE TABLE IF NOT EXISTS `cake_schedules` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `complete` int(3) DEFAULT '0',
  `begintime` date DEFAULT NULL,
  `begintime_unix` varchar(20) DEFAULT NULL,
  `endtime` date DEFAULT NULL,
  `endtime_unix` varchar(20) DEFAULT NULL,
  `message` text,
  `alarmable` tinyint(1) DEFAULT '0',
  `alarmtime_unix` varchar(20) DEFAULT NULL,
  `recurrable` tinyint(1) DEFAULT '0',
  `recurrencetype` varchar(10) DEFAULT NULL,
  `day_times` int(3) DEFAULT '1',
  `week_mon` tinyint(1) DEFAULT '0',
  `week_tues` tinyint(1) DEFAULT '0',
  `week_wen` tinyint(1) DEFAULT '0',
  `week_thur` tinyint(1) DEFAULT '0',
  `week_fri` tinyint(1) DEFAULT '0',
  `week_sat` tinyint(1) DEFAULT '0',
  `week_sun` tinyint(1) DEFAULT '0',
  `month_recurr_type` varchar(20) NOT NULL,
  `month_calendric` varchar(20) NOT NULL,
  `month_day` tinyint(3) NOT NULL,
  `month_week` tinyint(3) NOT NULL,
  `month_weeknum` tinyint(3) NOT NULL,
  `year_recurr_type` varchar(20) NOT NULL,
  `year_calendric` varchar(10) DEFAULT 'solar',
  `year_month` varchar(20) DEFAULT NULL,
  `year_day` tinyint(3) DEFAULT NULL,
  `year_weeknum` tinyint(3) DEFAULT '0',
  `year_week` tinyint(3) DEFAULT '0',
  `year_weekmonth` tinyint(3) DEFAULT NULL,
  `endtype` varchar(10) DEFAULT 'no',
  `end_times` int(11) DEFAULT '1',
  `end_date` varchar(10) DEFAULT NULL,
  `end_date_unix` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
REPLACE INTO `cake_i18nfields` (`id`, `name`, `savetodb`, `translate`, `type`, `model`, `locale`, `length`, `sort`, `allowadd`, `allowedit`, `selectmodel`, `selectvaluefield`, `selecttxtfield`, `selectparentid`, `selectautoload`, `selectvalues`, `associateflag`, `associateelement`, `associatefield`, `associatetype`, `formtype`, `default`, `allownull`, `validationregular`, `description`, `onchange`, `explodeimplode`, `explain`, `deleted`, `created`, `updated`, `conditions`) VALUES (NULL, 'id', '1', '编号', 'integer', 'Schedule', 'zh_cn', '11', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'name', '1', 'name', 'string', 'Schedule', 'zh_cn', '255', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'icon', '1', 'icon', 'string', 'Schedule', 'zh_cn', '255', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'complete', '1', 'complete', 'integer', 'Schedule', 'zh_cn', '3', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'begintime', '1', 'begintime', 'string', 'Schedule', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'begintime_unix', '1', 'begintime_unix', 'string', 'Schedule', 'zh_cn', '20', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'endtime', '1', 'endtime', 'string', 'Schedule', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'endtime_unix', '1', 'endtime_unix', 'string', 'Schedule', 'zh_cn', '20', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'message', '1', 'message', 'text', 'Schedule', 'zh_cn', NULL, 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'alarmable', '1', 'alarmable', 'boolean', 'Schedule', 'zh_cn', '1', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'alarmtime_unix', '1', 'alarmtime_unix', 'string', 'Schedule', 'zh_cn', '20', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'recurrable', '1', 'recurrable', 'boolean', 'Schedule', 'zh_cn', '1', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'recurrencetype', '1', 'recurrencetype', 'string', 'Schedule', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'day_times', '1', 'day_times', 'integer', 'Schedule', 'zh_cn', '3', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '1', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'week_mon', '1', 'week_mon', 'boolean', 'Schedule', 'zh_cn', '1', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'week_tues', '1', 'week_tues', 'boolean', 'Schedule', 'zh_cn', '1', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'week_wen', '1', 'week_wen', 'boolean', 'Schedule', 'zh_cn', '1', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'week_thur', '1', 'week_thur', 'boolean', 'Schedule', 'zh_cn', '1', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:00', '2011-04-22 11:16:00', NULL),
(NULL, 'week_fri', '1', 'week_fri', 'boolean', 'Schedule', 'zh_cn', '1', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'week_sat', '1', 'week_sat', 'boolean', 'Schedule', 'zh_cn', '1', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'week_sun', '1', 'week_sun', 'boolean', 'Schedule', 'zh_cn', '1', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'year_calendric', '1', 'year_calendric', 'string', 'Schedule', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, 'solar', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'year_month', '1', 'year_month', 'string', 'Schedule', 'zh_cn', '20', 0, '1', '1', '', NULL, NULL, NULL, '1', '1\n2\n3\n4\n5\n6\n7\n8\n9\n10\n11\n12', '0', '', '', 'equal', 'select', '', '1', '', NULL, '', '', '', 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'year_day', '1', 'year_day', 'string', 'Schedule', 'zh_cn', '20', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'year_weeknum', '1', 'year_weeknum', 'string', 'Schedule', 'zh_cn', '20', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'year_week', '1', 'year_week', 'string', 'Schedule', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'year_weekmonth', '1', 'year_weekmonth', 'string', 'Schedule', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'endtype', '1', 'endtype', 'string', 'Schedule', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, 'no', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'end_times', '1', 'end_times', 'integer', 'Schedule', 'zh_cn', '11', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, '1', '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'end_date', '1', 'end_date', 'string', 'Schedule', 'zh_cn', '10', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'end_date_unix', '1', 'end_date_unix', 'string', 'Schedule', 'zh_cn', '20', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-04-22 11:16:01', '2011-04-22 11:16:01', NULL),
(NULL, 'month_recurr_type', '1', 'month_recurr_type', 'string', 'Schedule', 'zh_cn', '20', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-06-25 13:48:05', '2011-06-25 13:48:05', NULL),
(NULL, 'month_calendric', '1', 'month_calendric', 'string', 'Schedule', 'zh_cn', '20', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-06-25 13:48:05', '2011-06-25 13:48:05', NULL),
(NULL, 'month_day', '1', 'month_day', 'integer', 'Schedule', 'zh_cn', '3', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-06-25 13:48:05', '2011-06-25 13:48:05', NULL),
(NULL, 'month_week', '1', 'month_week', 'integer', 'Schedule', 'zh_cn', '3', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-06-25 13:48:05', '2011-06-25 13:48:05', NULL),
(NULL, 'month_weeknum', '1', 'month_weeknum', 'integer', 'Schedule', 'zh_cn', '3', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-06-25 13:48:05', '2011-06-25 13:48:05', NULL),
(NULL, 'year_recurr_type', '1', 'year_recurr_type', 'string', 'Schedule', 'zh_cn', '20', 0, '1', '1', NULL, NULL, NULL, NULL, '1', NULL, '0', NULL, NULL, 'equal', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, 0, '2011-06-25 13:48:05', '2011-06-25 13:48:05', NULL);
REPLACE INTO `cake_modelextends` (`id`, `name`, `cname`, `belongtype`, `modeltype`, `idtype`, `status`, `created`, `updated`, `tablename`, `related_model`, `security`, `operatorfields`, `deleted`, `cate_id`, `localetype`) VALUES (NULL, 'Schedule', 'Schedule', 'onetomany', 'default', NULL, 1, '2011-06-25 09:04:31', '2011-06-25 09:04:31', 'cake_schedules', NULL, NULL, NULL, '0', 0, 0);