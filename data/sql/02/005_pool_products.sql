DROP TABLE IF EXISTS `cake_pool_products`;
CREATE TABLE IF NOT EXISTS `cake_pool_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weshare_id` int(11) NOT NULL,
  `share_name` varchar(60) DEFAULT NULL,
  `share_img` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `cake_weshare_products` ADD channel_price int(11) AFTER price;
ALTER TABLE `cake_pool_products` ADD FOREIGN KEY fk_pool_products_weshareId_cakeWeshares_id (weshare_id) REFERENCES cake_weshares(id);

INSERT INTO `cake_pool_products` VALUES (0, 2664, '谷物麦片/白/黑芝麻', 'http://static.tongshijia.com/images/218f3c22-e1d7-11e5-a821-00163e1600b6.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 3700 WHERE id = 5807;
UPDATE `cake_weshare_products` SET `channel_price` = 4100 WHERE id = 5808;
UPDATE `cake_weshare_products` SET `channel_price` = 6000 WHERE id = 5809;
INSERT INTO `cake_pool_products` VALUES (0, 2620, '泰国椰青', 'http://static.tongshijia.com/images/1f31de58-e05b-11e5-a821-00163e1600b6.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 4500 WHERE id = 5687;
UPDATE `cake_weshare_products` SET `channel_price` = 8700 WHERE id = 5688;
INSERT INTO `cake_pool_products` VALUES (0, 2619, '新疆骏枣和甘肃琉璃苣蜂蜜', 'http://static.tongshijia.com/images/574fcad0-e05a-11e5-a821-00163e1600b6.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 5400 WHERE id = 5682;
UPDATE `cake_weshare_products` SET `channel_price` = 5000 WHERE id = 5685;
INSERT INTO `cake_pool_products` VALUES (0, 2589, '海鸭蛋', 'http://static.tongshijia.com/images/f87be962-df8c-11e5-a821-00163e1600b6.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 5000 WHERE id = 5626;
INSERT INTO `cake_pool_products` VALUES (0, 2586, '茂谷柑', 'http://static.tongshijia.com/images/8c15c85e-df84-11e5-a821-00163e1600b6.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 5000 WHERE id = 5620;
UPDATE `cake_weshare_products` SET `channel_price` = 9000 WHERE id = 5621;
UPDATE `cake_weshare_products` SET `channel_price` = 16000 WHERE id = 5622;
INSERT INTO `cake_pool_products` VALUES (0, 2583, '茂谷柑', 'http://static.tongshijia.com/images/12421510-df83-11e5-a821-00163e1600b6.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 10500 WHERE id = 5617;
INSERT INTO `cake_pool_products` VALUES (0, 2499, '香辣豆干', 'http://static.tongshijia.com/images/31323380-dc5c-11e5-a821-00163e1600b6.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 1100 WHERE id = 5436;
UPDATE `cake_weshare_products` SET `channel_price` = 1100 WHERE id = 5437;
INSERT INTO `cake_pool_products` VALUES (0, 2489, '蔓越莓', 'http://static.tongshijia.com/images/777d6be4-dc38-11e5-a821-00163e1600b6.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 4800 WHERE id = 5417;
UPDATE `cake_weshare_products` SET `channel_price` = 9000 WHERE id = 5418;
INSERT INTO `cake_pool_products` VALUES (0, 2418, '雷诺啤酒', 'http://static.tongshijia.com/images/b9745658-dae3-11e5-a821-00163e1600b6.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 8000 WHERE id = 5236;
UPDATE `cake_weshare_products` SET `channel_price` = 14500 WHERE id = 5245;
UPDATE `cake_weshare_products` SET `channel_price` = 8600 WHERE id = 5246;
UPDATE `cake_weshare_products` SET `channel_price` = 15700 WHERE id = 5247;
UPDATE `cake_weshare_products` SET `channel_price` = 8600 WHERE id = 5248;
UPDATE `cake_weshare_products` SET `channel_price` = 15700 WHERE id = 5249;
INSERT INTO `cake_pool_products` VALUES (0, 2426, '空运大五星枇杷', 'http://static.tongshijia.com/images/583fe8ee-da1a-11e5-a821-00163e1600b6.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 7800 WHERE id = 5258;
INSERT INTO `cake_pool_products` VALUES (0, 2427, '新疆天山脚下的“新”骏枣', 'http://static.tongshijia.com/images/584006e4-da1a-11e5-a821-00163e1600b6.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 10500 WHERE id = 5260;
UPDATE `cake_weshare_products` SET `channel_price` = 11500 WHERE id = 5261;
INSERT INTO `cake_pool_products` VALUES (0, 2022, '现摘发货茂谷柑', 'http://static.tongshijia.com/images/1ce8a2d2-b91c-11e5-a8c5-00163e001f59.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 11000 WHERE id = 4252;
INSERT INTO `cake_pool_products` VALUES (0, 2098, '巨好吃的鲜8纯芝麻酱【全国包邮】', 'http://static.tongshijia.com/images/633dae9e-bb60-11e5-94c2-00163e001f59.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 2500 WHERE id = 4397;
UPDATE `cake_weshare_products` SET `channel_price` = 4300 WHERE id = 4398;
INSERT INTO `cake_pool_products` VALUES (0, 2472, '巨好吃的鲜8纯芝麻酱【全国包邮】', 'http://static.tongshijia.com/images/633dae9e-bb60-11e5-94c2-00163e001f59.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 2500 WHERE id = 5366;
UPDATE `cake_weshare_products` SET `channel_price` = 4300 WHERE id = 5367;
INSERT INTO `cake_pool_products` VALUES (0, 1437, '鲜活银耳【全国顺丰包邮】', 'http://51daifan-images.stor.sinaapp.com/files/201601/thumb_m/a1455b6560a_0104.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 9800 WHERE id = 2916;
INSERT INTO `cake_pool_products` VALUES (0, 2050, '有机纯正红薯粉条 无任何添加剂', 'http://static.tongshijia.com/images/e9770c56-b9eb-11e5-a8c5-00163e001f59.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 7600 WHERE id = 4301;
UPDATE `cake_weshare_products` SET `channel_price` = 9100 WHERE id = 4302;
INSERT INTO `cake_pool_products` VALUES (0, 1917, '宝宝的山楂条  添加胡萝卜和苹果', 'http://51daifan-images.stor.sinaapp.com/files/201601/thumb_m/4aee3d3205f_0106.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 5500 WHERE id = 4072;
UPDATE `cake_weshare_products` SET `channel_price` = 6500 WHERE id = 4073;
INSERT INTO `cake_pool_products` VALUES (0, 1894, '宝宝和老人特别喜欢的花牛苹果', 'http://51daifan-images.stor.sinaapp.com/files/201601/thumb_m/9fe70d2d40a_0104.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 6000 WHERE id = 4016;
INSERT INTO `cake_pool_products` VALUES (0, 1703, '永兴冰糖橙【限北京】', 'http://51daifan-images.stor.sinaapp.com/files/201601/thumb_m/83862b90237_0104.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 6500 WHERE id = 3572;
INSERT INTO `cake_pool_products` VALUES (0, 1783, '忆味蕾，富平霜降柿饼重磅归来！', 'http://51daifan-images.stor.sinaapp.com/files/201512/thumb_m/bf24186cec7_1229.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 2100 WHERE id = 3758;
UPDATE `cake_weshare_products` SET `channel_price` = 6300 WHERE id = 3759;
UPDATE `cake_weshare_products` SET `channel_price` = 10500 WHERE id = 3760;
INSERT INTO `cake_pool_products` VALUES (0, 1884, '富硒砂糖橘', 'http://51daifan-images.stor.sinaapp.com/files/201601/thumb_m/1802770e8f3_0104.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 7000 WHERE id = 3998;
INSERT INTO `cake_pool_products` VALUES (0, 1411, '雾岭山楂条5袋装', 'http://51daifan-images.stor.sinaapp.com/files/201512/83a26e7f545_1204.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 900 WHERE id = 2869;
INSERT INTO `cake_pool_products` VALUES (0, 1492, '小火团贡玉米 规格12棒/箱', 'http://51daifan-images.stor.sinaapp.com/files/201512/b17c9f68199_1208.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 5800 WHERE id = 3069;
UPDATE `cake_weshare_products` SET `channel_price` = 7300 WHERE id = 4743;
INSERT INTO `cake_pool_products` VALUES (0, 1600, '好吃的真空低温油浴果蔬套装（黄秋葵+香菇+什锦果蔬）', 'http://51daifan-images.stor.sinaapp.com/files/201512/c9c10cbd197_1215.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 4600 WHERE id = 3334;
INSERT INTO `cake_pool_products` VALUES (0, 1607, '泉林本色 180g卷筒纸 5提共50卷套装', 'http://51daifan-images.stor.sinaapp.com/files/201512/4eef34e2ed7_1216.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 13500 WHERE id = 3355;
INSERT INTO `cake_pool_products` VALUES (0, 1449, '俄罗斯紫皮糖', 'http://51daifan-images.stor.sinaapp.com/files/201512/50ee44b0bf0_1205.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 7000 WHERE id = 2963;
INSERT INTO `cake_pool_products` VALUES (0, 1432, '越南黑虎虾仁 【纯野生虾仁】', 'http://51daifan-images.stor.sinaapp.com/files/201512/8950ca1e7b1_1205.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 13000 WHERE id = 2903;
INSERT INTO `cake_pool_products` VALUES (0, 1430, '口口相传的艳艳山药', 'http://51daifan-images.stor.sinaapp.com/files/201512/0a8c5657319_1205.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 6800 WHERE id = 2898;
INSERT INTO `cake_pool_products` VALUES (0, 1450, '超值新鲜正宗内蒙古中式羔羊排块，箱门爽口、口齿留香！', 'http://51daifan-images.stor.sinaapp.com/files/201512/187724807dc_1205.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 11000 WHERE id = 2964;
INSERT INTO `cake_pool_products` VALUES (0, 1447, '第一抗癌食品窖藏红薯、紫薯【限北京】', 'http://51daifan-images.stor.sinaapp.com/files/201512/206035c42ce_1205.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 3700 WHERE id = 2950;
UPDATE `cake_weshare_products` SET `channel_price` = 6500 WHERE id = 2953;
UPDATE `cake_weshare_products` SET `channel_price` = 2400 WHERE id = 2955;
UPDATE `cake_weshare_products` SET `channel_price` = 6300 WHERE id = 2956;
UPDATE `cake_weshare_products` SET `channel_price` = 9100 WHERE id = 2957;
INSERT INTO `cake_pool_products` VALUES (0, 1433, '德庆贡柑，专供陛下娘娘^_^', 'http://51daifan-images.stor.sinaapp.com/files/201512/bdd78834f19_1205.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 4000 WHERE id = 2904;
INSERT INTO `cake_pool_products` VALUES (0, 1438, '那那家五常稻花香米', 'http://51daifan-images.stor.sinaapp.com/files/201512/15844587ff8_1205.jpg', '2016-03-07 01:50:25', 1, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 15000 WHERE id = 2920;
INSERT INTO `cake_pool_products` VALUES (0, 1445, '怀柔散养老杨家黑猪肉', 'http://51daifan-images.stor.sinaapp.com/files/201512/a093ee98c6c_1205.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 2900 WHERE id = 2942;
UPDATE `cake_weshare_products` SET `channel_price` = 2900 WHERE id = 2943;
UPDATE `cake_weshare_products` SET `channel_price` = 5900 WHERE id = 2944;
UPDATE `cake_weshare_products` SET `channel_price` = 2900 WHERE id = 2945;
UPDATE `cake_weshare_products` SET `channel_price` = 3900 WHERE id = 2946;
UPDATE `cake_weshare_products` SET `channel_price` = 5900 WHERE id = 2947;
INSERT INTO `cake_pool_products` VALUES (0, 1448, '有机翠香猕猴桃', 'http://51daifan-images.stor.sinaapp.com/files/201512/c899b3bbcbc_1205.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 8800 WHERE id = 2958;
UPDATE `cake_weshare_products` SET `channel_price` = 16600 WHERE id = 2959;
INSERT INTO `cake_pool_products` VALUES (0, 1489, '黄山头茬野生冬笋', 'http://51daifan-images.stor.sinaapp.com/files/201512/f88a0295cd9_1208.jpg', '2016-03-07 01:50:25', 0, 0);
UPDATE `cake_weshare_products` SET `channel_price` = 12500 WHERE id = 3064;

UPDATE `cake_weshare_products` SET `channel_price` = `price` WHERE `channel_price` IS NULL;
