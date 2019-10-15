--
-- Скрипт сгенерирован Devart dbForge Studio 2019 for MySQL, Версия 8.2.23.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 16.10.2019 4:19:37
-- Версия сервера: 5.7.25
-- Версия клиента: 4.1
--


SET NAMES 'AUTO';

INSERT INTO tokyo_hotel.booking_list(IDbook_l, IDc, IDr, bookDate, comingDate, outDate, book_status) VALUES
(36, 38, 18, '2019-09-30 17:31:29', '2019-09-30 00:00:00', '2019-10-06 00:00:00', 0),
(37, 39, 14, '2019-09-30 20:40:21', '2019-10-04 00:00:00', '2019-11-10 00:00:00', 1),
(38, 40, 10, '2019-09-30 20:40:36', '2019-09-30 00:00:00', '2019-10-06 00:00:00', 0),
(39, 41, 10, '2019-10-15 03:06:38', '2019-10-13 00:00:00', '2019-10-14 00:00:00', 0),
(40, 42, 13, '2019-10-16 03:50:09', '2019-10-16 00:00:00', '2019-11-10 00:00:00', 1);


INSERT INTO tokyo_hotel.clients(IDc, user_login, user_password, user_hash, account_status) VALUES
(38, '753876561', 'password_has_been_reset', '', 0),
(39, '371426617', 'c7e5d0235864bfeb2d8289da2c7d286a', '', 1),
(40, '592198351', 'password_has_been_reset', '', 0),
(41, '841356122', 'password_has_been_reset', '', 0),
(42, '675036040', '458da96be88fc7af60e4a73461f95fef', 'eeeae54d6e61da23ee35445462f934ec', 1);


INSERT INTO tokyo_hotel.clients_data(IDcdat, IDc, client_name, client_fam, client_phone) VALUES
(6, 38, 'DVMON', 'KrutoyChel', '+7(228)133-14-48'),
(7, 39, 'w', '', '+7(511)125-12-51'),
(8, 40, 'rqwr', 'safasfsafa', '+7(112)512-51-52'),
(9, 41, 'lol', 'asfasfa', '+7(214)124-12-41'),
(10, 42, 'DvMon', '', '+7(214)124-12-41');


INSERT INTO tokyo_hotel.inside_persons(IDip, user_login, user_password, user_hash) VALUES
(1, 'SERVER', '1', ''),
(2, 'dVmon', '88a4b282d4b2d31968490ed20d02b39a', '');



INSERT INTO tokyo_hotel.logs(log_id, a_date, a_caption, IDip) VALUES
(3, '2019-10-15 03:46:09', 'Сброшен бронь и аккаунт с ID = 38 по истечению срока брони', 1),
(4, '2019-10-15 03:46:09', 'Сброшен бронь и аккаунт с ID = 40 по истечению срока брони', 1),
(5, '2019-10-15 18:07:01', 'Сброшен бронь и аккаунт с ID = 41 по истечению срока брони', 1);


INSERT INTO tokyo_hotel.rooms(IDr, IDrt, roomNumber) VALUES
(1, 2, 1),
(4, 3, 2),
(5, 4, 3),
(6, 5, 4),
(7, 2, 5),
(9, 3, 7),
(10, 3, 8),
(12, 4, 9),
(13, 4, 10),
(14, 4, 11),
(15, 5, 12),
(16, 5, 13),
(17, 5, 14),
(18, 5, 15);


INSERT INTO tokyo_hotel.room_types(IDrt, r_typeName, r_typeSlug, r_typeImageDir, r_typeCost, r_typeDesc) VALUES
(2, 'Двухместный эконом', 'econom_2', 3, '30', 'Подойдет тебе, если ты имеешь тридцатку'),
(3, 'Четерехместный эконом', 'econom_4', 2, '55', 'Подойдет тебе, если вас 4 и у вас есть пятьдесят пять рублей'),
(4, 'Двухместный полулюкс', 'semilux_2', 1, '60', 'За 60-ку просто роскошь, сам бы в таком жил'),
(5, 'Двухместный президенский люкс', 'lux_2', 4, '90', 'Три тридцатки, но зато сколько расслабона и балдежа');
