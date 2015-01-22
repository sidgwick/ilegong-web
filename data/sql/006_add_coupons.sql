insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '315', 1500);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '577', 1000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '652', 1000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '336', 1000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '560', 1500);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '300', 2000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '606', 1500);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '610', 2000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '262', 2000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '666', 2000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '302', 500 );
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '365', 1000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '275', 1000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '274', 2000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '266', 500 );
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '561', 2000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '703', 2000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '329', 2000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '664', 500);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '704', 3000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '707', 3000);
insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '453', 1500);

insert into cake_coupons(name, valid_begin, valid_end, last_updator, type, product_list, reduced_price) values('年货专场', '2015-01-21 00:00:00', '2015-02-02 00:00:00', 632, 2, '709', 2000);

update cake_coupons c inner join cake_products p on c.product_list = p.id  set c.name = concat('年货', '(', p.name, ')'), c.status = 1,  c.brand_id = p.brand_id, c.published = 1  where valid_end='2015-02-02 00:00:00'  and last_updator=632;
-- update cake_coupons c set valid_begin='2015-01-20 00:00:00'  where valid_end='2015-02-02 00:00:00';