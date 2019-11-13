--
-- Скрипт сгенерирован Devart dbForge Studio 2019 for MySQL, Версия 8.2.23.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 14.11.2019 2:23:45
-- Версия сервера: 5.7.25
-- Версия клиента: 4.1
--

-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установить режим SQL (SQL mode)
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

--
-- Установка базы данных по умолчанию
--
USE tokyo_hotel;

--
-- Удалить представление `human_view_logs`
--
DROP VIEW IF EXISTS human_view_logs CASCADE;

--
-- Удалить таблицу `logs`
--
DROP TABLE IF EXISTS logs;

--
-- Удалить таблицу `operations`
--
DROP TABLE IF EXISTS operations;

--
-- Удалить таблицу `inside_persons_data`
--
DROP TABLE IF EXISTS inside_persons_data;

--
-- Удалить таблицу `service_queries`
--
DROP TABLE IF EXISTS service_queries;

--
-- Удалить таблицу `inside_persons`
--
DROP TABLE IF EXISTS inside_persons;

--
-- Удалить представление `clients_total_cost`
--
DROP VIEW IF EXISTS clients_total_cost CASCADE;

--
-- Удалить представление `human_view_service_queries`
--
DROP VIEW IF EXISTS human_view_service_queries CASCADE;

--
-- Удалить таблицу `service_bills`
--
DROP TABLE IF EXISTS service_bills;

--
-- Удалить таблицу `services`
--
DROP TABLE IF EXISTS services;

--
-- Удалить таблицу `service_types`
--
DROP TABLE IF EXISTS service_types;

--
-- Удалить представление `view1`
--
DROP VIEW IF EXISTS view1 CASCADE;

--
-- Удалить представление `nearest_booking`
--
DROP VIEW IF EXISTS nearest_booking CASCADE;

--
-- Удалить процедуру `CHECK_BOOKED`
--
DROP PROCEDURE IF EXISTS CHECK_BOOKED;

--
-- Удалить процедуру `ROOM_BOOKING`
--
DROP PROCEDURE IF EXISTS ROOM_BOOKING;

--
-- Удалить таблицу `booking_list`
--
DROP TABLE IF EXISTS booking_list;

--
-- Удалить таблицу `clients_data`
--
DROP TABLE IF EXISTS clients_data;

--
-- Удалить таблицу `living_list`
--
DROP TABLE IF EXISTS living_list;

--
-- Удалить таблицу `clients`
--
DROP TABLE IF EXISTS clients;

--
-- Удалить таблицу `rooms`
--
DROP TABLE IF EXISTS rooms;

--
-- Удалить таблицу `room_types`
--
DROP TABLE IF EXISTS room_types;

--
-- Установка базы данных по умолчанию
--
USE tokyo_hotel;

--
-- Создать таблицу `room_types`
--
CREATE TABLE room_types (
  IDrt int(11) NOT NULL AUTO_INCREMENT,
  r_typeName varchar(255) NOT NULL,
  r_typeSlug varchar(255) NOT NULL,
  r_typeImageDir int(11) NOT NULL,
  r_typeCost varchar(255) NOT NULL,
  r_typeDesc text NOT NULL,
  PRIMARY KEY (IDrt)
)
ENGINE = INNODB,
AUTO_INCREMENT = 6,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `rooms`
--
CREATE TABLE rooms (
  IDr int(11) NOT NULL AUTO_INCREMENT,
  IDrt int(11) NOT NULL DEFAULT 1,
  roomNumber int(11) NOT NULL,
  PRIMARY KEY (IDr)
)
ENGINE = INNODB,
AUTO_INCREMENT = 19,
AVG_ROW_LENGTH = 1170,
CHARACTER SET utf8,
COLLATE utf8_general_ci,
COMMENT = 'номера';

--
-- Создать индекс `RoomNUM_UK` для объекта типа таблица `rooms`
--
ALTER TABLE rooms
ADD UNIQUE INDEX RoomNUM_UK (roomNumber);

--
-- Создать внешний ключ
--
ALTER TABLE rooms
ADD CONSTRAINT RoomType_FK FOREIGN KEY (IDrt)
REFERENCES room_types (IDrt) ON UPDATE CASCADE;

--
-- Создать таблицу `clients`
--
CREATE TABLE clients (
  IDc int(11) NOT NULL AUTO_INCREMENT,
  user_login varchar(30) NOT NULL,
  user_password varchar(32) NOT NULL,
  user_hash varchar(32) NOT NULL DEFAULT '',
  account_status tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (IDc)
)
ENGINE = INNODB,
AUTO_INCREMENT = 55,
AVG_ROW_LENGTH = 1489,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать индекс `clientLogin_UK` для объекта типа таблица `clients`
--
ALTER TABLE clients
ADD UNIQUE INDEX clientLogin_UK (user_login);

--
-- Создать таблицу `living_list`
--
CREATE TABLE living_list (
  IDliv_l int(11) NOT NULL AUTO_INCREMENT,
  IDc int(11) NOT NULL,
  IDr int(11) NOT NULL,
  PRIMARY KEY (IDliv_l)
)
ENGINE = INNODB,
AUTO_INCREMENT = 31,
AVG_ROW_LENGTH = 5461,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать внешний ключ
--
ALTER TABLE living_list
ADD CONSTRAINT livingClientID_FK FOREIGN KEY (IDc)
REFERENCES clients (IDc) ON UPDATE CASCADE;

--
-- Создать внешний ключ
--
ALTER TABLE living_list
ADD CONSTRAINT livingRoomID_FK FOREIGN KEY (IDr)
REFERENCES rooms (IDr) ON UPDATE CASCADE;

--
-- Создать таблицу `clients_data`
--
CREATE TABLE clients_data (
  IDcdat int(11) NOT NULL AUTO_INCREMENT,
  IDc int(11) NOT NULL,
  client_name varchar(255) NOT NULL,
  client_fam varchar(255) NOT NULL DEFAULT '',
  client_phone varchar(20) NOT NULL DEFAULT '',
  client_email varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (IDcdat)
)
ENGINE = INNODB,
AUTO_INCREMENT = 23,
AVG_ROW_LENGTH = 1489,
CHARACTER SET utf8,
COLLATE utf8_general_ci,
COMMENT = 'данные клиентского аккаунта';

--
-- Создать внешний ключ
--
ALTER TABLE clients_data
ADD CONSTRAINT clients_data_FK FOREIGN KEY (IDc)
REFERENCES clients (IDc) ON UPDATE CASCADE;

--
-- Создать таблицу `booking_list`
--
CREATE TABLE booking_list (
  IDbook_l int(11) NOT NULL AUTO_INCREMENT,
  IDc int(11) NOT NULL,
  IDr int(11) NOT NULL,
  bookDate datetime NOT NULL,
  comingDate datetime NOT NULL,
  outDate datetime NOT NULL,
  book_status tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (IDbook_l)
)
ENGINE = INNODB,
AUTO_INCREMENT = 53,
AVG_ROW_LENGTH = 1489,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать внешний ключ
--
ALTER TABLE booking_list
ADD CONSTRAINT bookingClientID_FK FOREIGN KEY (IDc)
REFERENCES clients (IDc) ON UPDATE CASCADE;

--
-- Создать внешний ключ
--
ALTER TABLE booking_list
ADD CONSTRAINT bookingRoomID_FK FOREIGN KEY (IDr)
REFERENCES rooms (IDr) ON UPDATE CASCADE;

DELIMITER $$

--
-- Создать процедуру `ROOM_BOOKING`
--
CREATE DEFINER = 'root'@'%'
PROCEDURE ROOM_BOOKING (IN `room_ID` int, IN `client_ID` int, IN `coming_Date` date, IN `out_Date` date)
BEGIN
  INSERT INTO booking_list (IDr, IDc, bookDate, comingDate, outDate)
    VALUES (room_ID, client_ID, NOW(), coming_date, out_date);
END
$$

--
-- Создать процедуру `CHECK_BOOKED`
--
CREATE DEFINER = 'root'@'%'
PROCEDURE CHECK_BOOKED (IN `roomID` int, IN `dateBeg` date, IN `dateEnd` date)
BEGIN
  SELECT
    COUNT(*)
  FROM `booking_list` `bl`
  WHERE ((`bl`.`IDr` = roomID)
  AND ((`bl`.`comingDate` BETWEEN dateBeg AND dateEnd)
  OR (`bl`.`outDate` BETWEEN dateBeg AND dateEnd)
  OR (dateBeg BETWEEN `bl`.`comingDate` AND `bl`.`outDate`)
  OR (dateEnd BETWEEN `bl`.`comingDate` AND `bl`.`outDate`)));
END
$$

DELIMITER ;

--
-- Создать представление `nearest_booking`
--
CREATE
DEFINER = 'root'@'%'
VIEW nearest_booking
AS
SELECT
  `booking_list`.`IDc` AS `IDc`,
  `clients`.`user_login` AS `bookNumber`,
  `booking_list`.`comingDate` AS `comingDate`,
  `booking_list`.`outDate` AS `outDate`,
  `rooms`.`IDr` AS `IDr`,
  `rooms`.`roomNumber` AS `roomNumber`,
  (TO_DAYS(`booking_list`.`outDate`) - TO_DAYS(`booking_list`.`comingDate`)) AS `totalDaysCount`,
  (`room_types`.`r_typeCost` * (TO_DAYS(`booking_list`.`outDate`) - TO_DAYS(`booking_list`.`comingDate`))) AS `totalCost`
FROM (((`booking_list`
  JOIN `clients`
    ON ((`booking_list`.`IDc` = `clients`.`IDc`)))
  JOIN `rooms`
    ON ((`booking_list`.`IDr` = `rooms`.`IDr`)))
  JOIN `room_types`
    ON ((`rooms`.`IDrt` = `room_types`.`IDrt`)))
WHERE ((`booking_list`.`book_status` = 1)
AND (`booking_list`.`comingDate` <= CURDATE()));

--
-- Создать представление `view1`
--
CREATE
DEFINER = 'root'@'%'
VIEW view1
AS
SELECT
  `nearest_booking`.`bookNumber` AS `bookNumber`,
  `nearest_booking`.`comingDate` AS `comingDate`,
  `nearest_booking`.`outDate` AS `outDate`,
  `nearest_booking`.`roomNumber` AS `roomNumber`,
  `nearest_booking`.`totalCost` AS `totalCost`,
  `nearest_booking`.`totalDaysCount` AS `totalDaysCount`,
  `nearest_booking`.`IDr` AS `IDr`,
  `nearest_booking`.`IDc` AS `IDc`
FROM (`nearest_booking`
  LEFT JOIN `living_list`
    ON ((`nearest_booking`.`IDc` = `living_list`.`IDc`)))
WHERE ISNULL(`living_list`.`IDc`);

--
-- Создать таблицу `service_types`
--
CREATE TABLE service_types (
  IDst int(11) NOT NULL AUTO_INCREMENT,
  stNames varchar(255) NOT NULL,
  PRIMARY KEY (IDst)
)
ENGINE = INNODB,
AUTO_INCREMENT = 5,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `services`
--
CREATE TABLE services (
  IDs int(11) NOT NULL AUTO_INCREMENT,
  IDst int(11) NOT NULL,
  sName varchar(255) NOT NULL,
  sCost int(11) NOT NULL,
  simgpath varchar(255) NOT NULL,
  PRIMARY KEY (IDs)
)
ENGINE = INNODB,
AUTO_INCREMENT = 17,
AVG_ROW_LENGTH = 1170,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать внешний ключ
--
ALTER TABLE services
ADD CONSTRAINT services_types_IDst_FK FOREIGN KEY (IDst)
REFERENCES service_types (IDst) ON UPDATE CASCADE;

--
-- Создать таблицу `service_bills`
--
CREATE TABLE service_bills (
  IDsb int(11) NOT NULL AUTO_INCREMENT,
  IDs int(11) NOT NULL,
  IDliv_l int(11) NOT NULL,
  sbCount int(11) NOT NULL DEFAULT 1,
  sbCreateDate datetime NOT NULL,
  sbResolveDate datetime DEFAULT NULL,
  sbStatus tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (IDsb)
)
ENGINE = INNODB,
AUTO_INCREMENT = 41,
AVG_ROW_LENGTH = 780,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать внешний ключ
--
ALTER TABLE service_bills
ADD CONSTRAINT service_IDliv_l_FK FOREIGN KEY (IDliv_l)
REFERENCES living_list (IDliv_l) ON UPDATE CASCADE;

--
-- Создать внешний ключ
--
ALTER TABLE service_bills
ADD CONSTRAINT service_bills_IDs_FK FOREIGN KEY (IDs)
REFERENCES services (IDs) ON UPDATE CASCADE;

--
-- Создать представление `human_view_service_queries`
--
CREATE
DEFINER = 'root'@'%'
VIEW human_view_service_queries
AS
SELECT
  `living_list`.`IDc` AS `IDc`,
  `clients`.`user_login` AS `book_number`,
  `living_list`.`IDr` AS `IDr`,
  `rooms`.`roomNumber` AS `roomNumber`,
  `service_bills`.`IDsb` AS `IDsb`,
  `services`.`IDs` AS `IDs`,
  `services`.`sName` AS `sName`,
  `service_bills`.`sbCount` AS `sbCount`,
  `services`.`sCost` AS `sCost`,
  (`services`.`sCost` * `service_bills`.`sbCount`) AS `totalCost`,
  `service_bills`.`sbCreateDate` AS `sbCreateDate`,
  `service_bills`.`sbResolveDate` AS `sbResolveDate`,
  `service_bills`.`sbStatus` AS `sbStatus`
FROM ((((`service_bills`
  JOIN `living_list`
    ON ((`service_bills`.`IDliv_l` = `living_list`.`IDliv_l`)))
  JOIN `rooms`
    ON ((`living_list`.`IDr` = `rooms`.`IDr`)))
  JOIN `clients`
    ON ((`living_list`.`IDc` = `clients`.`IDc`)))
  JOIN `services`
    ON ((`service_bills`.`IDs` = `services`.`IDs`)))
ORDER BY `service_bills`.`sbCreateDate` DESC;

--
-- Создать представление `clients_total_cost`
--
CREATE
DEFINER = 'root'@'%'
VIEW clients_total_cost
AS
SELECT
  `nearest_booking`.`totalCost` AS `totalCost`,
  `nearest_booking`.`totalDaysCount` AS `totalDaysCount`,
  `nearest_booking`.`bookNumber` AS `bookNumber`,
  `nearest_booking`.`roomNumber` AS `expr1`,
  `nearest_booking`.`IDc` AS `IDc`,
  `nearest_booking`.`IDr` AS `IDr`
FROM (`human_view_service_queries`
  JOIN `nearest_booking`)
WHERE (`nearest_booking`.`IDc` = `human_view_service_queries`.`IDc`);

--
-- Создать таблицу `inside_persons`
--
CREATE TABLE inside_persons (
  IDip int(11) NOT NULL AUTO_INCREMENT,
  user_login varchar(255) NOT NULL,
  user_password varchar(255) NOT NULL,
  user_hash varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (IDip)
)
ENGINE = INNODB,
AUTO_INCREMENT = 3,
AVG_ROW_LENGTH = 5461,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать индекс `UK_inside_persons_personName` для объекта типа таблица `inside_persons`
--
ALTER TABLE inside_persons
ADD UNIQUE INDEX UK_inside_persons_personName (user_login);

--
-- Создать таблицу `service_queries`
--
CREATE TABLE service_queries (
  IDsq int(11) NOT NULL AUTO_INCREMENT,
  IDsb int(11) NOT NULL,
  IDip int(11) NOT NULL,
  PRIMARY KEY (IDsq)
)
ENGINE = INNODB,
AUTO_INCREMENT = 8,
AVG_ROW_LENGTH = 2730,
CHARACTER SET utf8,
COLLATE utf8_general_ci,
COMMENT = 'Подтвержденные услуги';

--
-- Создать внешний ключ
--
ALTER TABLE service_queries
ADD CONSTRAINT FK_service_queries_IDip FOREIGN KEY (IDip)
REFERENCES inside_persons (IDip) ON UPDATE CASCADE;

--
-- Создать внешний ключ
--
ALTER TABLE service_queries
ADD CONSTRAINT FK_service_queries_IDsb FOREIGN KEY (IDsb)
REFERENCES service_bills (IDsb) ON UPDATE CASCADE;

--
-- Создать таблицу `inside_persons_data`
--
CREATE TABLE inside_persons_data (
  IDipd int(11) NOT NULL AUTO_INCREMENT,
  IDip int(11) NOT NULL,
  person_name varchar(255) NOT NULL,
  person_fam varchar(255) NOT NULL,
  person_phone varchar(20) NOT NULL,
  PRIMARY KEY (IDipd)
)
ENGINE = INNODB,
AUTO_INCREMENT = 2,
CHARACTER SET utf8,
COLLATE utf8_general_ci,
COMMENT = 'информация о работниках';

--
-- Создать внешний ключ
--
ALTER TABLE inside_persons_data
ADD CONSTRAINT inside_persons_IDip_FK FOREIGN KEY (IDip)
REFERENCES inside_persons (IDip) ON UPDATE CASCADE;

--
-- Создать таблицу `operations`
--
CREATE TABLE operations (
  IDop int(11) NOT NULL AUTO_INCREMENT,
  opName varchar(255) NOT NULL,
  PRIMARY KEY (IDop)
)
ENGINE = INNODB,
AUTO_INCREMENT = 6,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `logs`
--
CREATE TABLE logs (
  log_id int(11) NOT NULL AUTO_INCREMENT,
  a_date datetime NOT NULL,
  a_caption varchar(255) NOT NULL,
  IDip int(11) NOT NULL,
  IDop int(11) NOT NULL,
  PRIMARY KEY (log_id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 38,
AVG_ROW_LENGTH = 780,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать внешний ключ
--
ALTER TABLE logs
ADD CONSTRAINT FK_logs_IDop FOREIGN KEY (IDop)
REFERENCES operations (IDop) ON UPDATE CASCADE;

--
-- Создать внешний ключ
--
ALTER TABLE logs
ADD CONSTRAINT InsidePersonsID_FK FOREIGN KEY (IDip)
REFERENCES inside_persons (IDip) ON UPDATE CASCADE;

--
-- Создать представление `human_view_logs`
--
CREATE
DEFINER = 'root'@'%'
VIEW human_view_logs
AS
SELECT
  `logs`.`log_id` AS `log_id`,
  `logs`.`a_date` AS `a_date`,
  `logs`.`a_caption` AS `a_caption`,
  `operations`.`IDop` AS `IDop`,
  `operations`.`opName` AS `opName`,
  `inside_persons`.`IDip` AS `IDip`,
  `inside_persons`.`user_login` AS `ipName`
FROM ((`logs`
  JOIN `operations`
    ON ((`logs`.`IDop` = `operations`.`IDop`)))
  JOIN `inside_persons`
    ON ((`logs`.`IDip` = `inside_persons`.`IDip`)))
ORDER BY `logs`.`a_date` DESC;

-- 
-- Вывод данных для таблицы room_types
--
INSERT INTO room_types VALUES
(2, 'Двухместный эконом', 'econom_2', 3, '30', 'Подойдет тебе, если ты имеешь тридцатку'),
(3, 'Четерехместный эконом', 'econom_4', 2, '55', 'Подойдет тебе, если вас 4 и у вас есть пятьдесят пять рублей'),
(4, 'Двухместный полулюкс', 'semilux_2', 1, '60', 'За 60-ку просто роскошь, сам бы в таком жил'),
(5, 'Двухместный президенский люкс', 'lux_2', 4, '90', 'Три тридцатки, но зато сколько расслабона и балдежа');

-- 
-- Вывод данных для таблицы service_types
--
INSERT INTO service_types VALUES
(1, 'Еда/Вода'),
(3, 'Эскурсия'),
(4, 'Другое');

-- 
-- Вывод данных для таблицы rooms
--
INSERT INTO rooms VALUES
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

-- 
-- Вывод данных для таблицы clients
--
INSERT INTO clients VALUES
(38, '753876561', 'password_has_been_reset', '', 0),
(39, '371426617', 'password_has_been_reset', '', 0),
(40, '592198351', 'password_has_been_reset', '', 0),
(41, '841356122', 'password_has_been_reset', '', 0),
(42, '675036040', 'password_has_been_reset', '', 0),
(43, '193418680', 'password_has_been_reset', '', 0),
(44, '989424122', 'password_has_been_reset', '', 0),
(45, '175754296', 'password_has_been_reset', '', 0),
(46, '717111881', 'password_has_been_reset', '', 0),
(47, '678362795', 'password_has_been_reset', '', 0),
(48, '101241848', 'password_has_been_reset', '', 0),
(49, '279463692', 'password_has_been_reset', '', 0),
(50, '302874300', 'password_has_been_reset', '', 0),
(51, '679283788', 'password_has_been_reset', '', 0),
(52, '229084199', 'password_has_been_reset', '', 0),
(53, '111358767', '61635640a274dc9eb26f832f6e074196', '8136d364bf292c3b4282f2bb560f96f8', 1),
(54, '847800547', '0a05e0b42af7c275fd7c7364363c11b5', 'db0ce0593f72b1a18f9d1368089d5dc2', 1);

-- 
-- Вывод данных для таблицы services
--
INSERT INTO services VALUES
(1, 1, 'Гнилая_плоть.png', 4, 'src\\image\\eat\\Гнилая_плоть.png'),
(3, 1, 'Жареная_баранина.png', 10, 'src\\image\\eat\\Жареная_баранина.png'),
(4, 1, 'Жареная_говядина.png', 9, 'src\\image\\eat\\Жареная_говядина.png'),
(5, 1, 'Жареная_курятина.png', 7, 'src\\image\\eat\\Жареная_курятина.png'),
(6, 1, 'Жареная_свинина.png', 8, 'src\\image\\eat\\Жареная_свинина.png'),
(7, 1, 'Жареная_треска.png', 9, 'src\\image\\eat\\Жареная_треска.png'),
(8, 1, 'Печёный_картофель.png', 7, 'src\\image\\eat\\Печёный_картофель.png'),
(9, 1, 'Сырая_говядина.png', 5, 'src\\image\\eat\\Сырая_говядина.png'),
(10, 1, 'Тушёные_грибы.png', 7, 'src\\image\\eat\\Тушёные_грибы.png'),
(11, 1, 'Тыквенный_пирог.png', 5, 'src\\image\\eat\\Тыквенный_пирог.png'),
(12, 1, 'Хлеб.png', 1, 'src\\image\\eat\\Хлеб.png'),
(13, 1, 'Яблоко.png', 1, 'src\\image\\eat\\Яблоко.png'),
(14, 1, 'Вода', 5, 'src\\image\\eat\\Вода.png'),
(15, 3, 'Экскурсия на вулканы', 15, 'src\\image\\travel\\вулканы.png'),
(16, 3, 'Экскурсия в пещеры', 15, 'src\\image\\travel\\пещеры.jpg');

-- 
-- Вывод данных для таблицы living_list
--
INSERT INTO living_list VALUES
(1, 49, 1),
(24, 43, 7),
(25, 47, 10),
(26, 46, 5),
(27, 51, 14),
(28, 52, 14),
(29, 53, 14),
(30, 54, 13);

-- 
-- Вывод данных для таблицы service_bills
--
INSERT INTO service_bills VALUES
(20, 1, 29, 2, '2019-11-08 00:39:18', '2019-11-12 00:00:00', 1),
(21, 15, 29, 2, '2019-11-08 00:41:28', '2019-11-12 00:00:00', 1),
(22, 3, 29, 2, '2019-11-08 00:41:54', '2019-11-12 00:00:00', 1),
(23, 14, 29, 3, '2019-11-08 00:41:54', '2019-11-12 00:00:00', 1),
(24, 3, 29, 2, '2019-11-08 00:43:32', '2019-11-12 00:00:00', 1),
(25, 1, 29, 10, '2019-11-11 02:09:22', '2019-11-12 00:00:00', 1),
(26, 3, 30, 15, '2019-11-13 02:31:15', NULL, 0),
(27, 6, 30, 3, '2019-11-13 02:31:15', NULL, 0),
(28, 7, 30, 2, '2019-11-13 02:31:15', NULL, 0),
(29, 10, 30, 1, '2019-11-13 02:31:15', NULL, 0),
(30, 11, 30, 1, '2019-11-13 02:31:15', NULL, 0),
(31, 15, 30, 1, '2019-11-13 02:31:15', NULL, 0),
(32, 1, 30, 1, '2019-11-13 02:31:45', NULL, 0),
(33, 4, 30, 1, '2019-11-13 02:31:45', NULL, 0),
(34, 6, 30, 1, '2019-11-13 02:31:45', NULL, 0),
(35, 9, 30, 1, '2019-11-13 02:31:45', NULL, 0),
(36, 10, 30, 1, '2019-11-13 02:31:45', NULL, 0),
(37, 12, 30, 1, '2019-11-13 02:31:45', NULL, 0),
(38, 11, 30, 1, '2019-11-13 02:31:45', NULL, 0),
(39, 13, 30, 1, '2019-11-13 02:31:45', NULL, 0),
(40, 14, 30, 1, '2019-11-13 02:31:45', NULL, 0);

-- 
-- Вывод данных для таблицы operations
--
INSERT INTO operations VALUES
(1, 'Заселение'),
(2, 'Выселение'),
(3, 'Олата'),
(4, 'Предоставление услуги'),
(5, 'Другое');

-- 
-- Вывод данных для таблицы inside_persons
--
INSERT INTO inside_persons VALUES
(1, 'SERVER', '1', ''),
(2, 'dVmon', '88a4b282d4b2d31968490ed20d02b39a', '5fe67d858e9715e79d0d360a53ee1ac5');

-- 
-- Вывод данных для таблицы service_queries
--
INSERT INTO service_queries VALUES
(1, 20, 2),
(3, 21, 2),
(4, 22, 2),
(5, 23, 2),
(6, 24, 2),
(7, 25, 2);

-- 
-- Вывод данных для таблицы logs
--
INSERT INTO logs VALUES
(3, '2019-10-15 03:46:09', 'Сброшен бронь и аккаунт с ID = 38 по истечению срока брони', 1, 2),
(4, '2019-10-15 03:46:09', 'Сброшен бронь и аккаунт с ID = 40 по истечению срока брони', 1, 2),
(5, '2019-10-15 18:07:01', 'Сброшен бронь и аккаунт с ID = 41 по истечению срока брони', 1, 2),
(7, '2019-10-22 22:17:03', 'Сброшен бронь и аккаунт с ID = 39 по истечению срока брони', 1, 2),
(21, '2019-10-24 05:23:40', 'Подтверждено заселение клиента ID = 43 в комнату ID = 7', 2, 1),
(22, '2019-10-24 05:23:45', 'Подтверждено заселение клиента ID = 47 в комнату ID = 10', 2, 1),
(23, '2019-10-24 05:23:52', 'Подтверждено заселение клиента ID = 46 в комнату ID = 5', 2, 1),
(24, '2019-10-25 00:01:01', 'Сброшен бронь и аккаунт с ID = 44 по истечению срока брони', 1, 2),
(25, '2019-10-25 00:01:01', 'Сброшен бронь и аккаунт с ID = 46 по истечению срока брони', 1, 2),
(26, '2019-10-26 20:01:00', 'Сброшен бронь и аккаунт с ID = 50 по истечению срока брони', 1, 2),
(27, '2019-10-26 21:26:01', 'Подтверждено заселение клиента ID = 51 в комнату ID = 14', 2, 1),
(28, '2019-10-27 00:01:00', 'Сброшен бронь и аккаунт с ID = 45 по истечению срока брони', 1, 2),
(29, '2019-10-27 00:01:00', 'Сброшен бронь и аккаунт с ID = 48 по истечению срока брони', 1, 2),
(30, '2019-10-27 00:01:00', 'Сброшен бронь и аккаунт с ID = 49 по истечению срока брони', 1, 2),
(31, '2019-10-28 17:01:01', 'Сброшен бронь и аккаунт с ID = 43 по истечению срока брони', 1, 2),
(32, '2019-10-28 17:01:01', 'Сброшен бронь и аккаунт с ID = 47 по истечению срока брони', 1, 2),
(33, '2019-10-28 17:01:01', 'Сброшен бронь и аккаунт с ID = 51 по истечению срока брони', 1, 2),
(34, '2019-10-28 17:43:37', 'Подтверждено заселение клиента ID = 52 в комнату ID = 14', 2, 1),
(35, '2019-11-07 16:01:01', 'Сброшен бронь и аккаунт с ID = 52 по истечению срока брони', 1, 2),
(36, '2019-11-08 00:23:39', 'Подтверждено заселение клиента ID = 53 в комнату ID = 14', 2, 1),
(37, '2019-11-11 00:01:01', 'Сброшен бронь и аккаунт с ID = 42 по истечению срока брони', 1, 2);

-- 
-- Вывод данных для таблицы inside_persons_data
--
INSERT INTO inside_persons_data VALUES
(1, 2, 'Dimon', 'Saltuxa', '+7(228)133-14-48');

-- 
-- Вывод данных для таблицы clients_data
--
INSERT INTO clients_data VALUES
(6, 38, 'DVMON', 'KrutoyChel', '+7(228)133-14-48', ''),
(7, 39, 'w', '', '+7(511)125-12-51', ''),
(8, 40, 'rqwr', 'safasfsafa', '+7(112)512-51-52', ''),
(9, 41, 'lol', 'asfasfa', '+7(214)124-12-41', ''),
(10, 42, 'DvMon', '', '+7(214)124-12-41', ''),
(11, 43, 'tester', '', '+7(212)512-51-51', ''),
(12, 44, 'root', '', '+7(123)123-12-31', '13123123@asdsa.ru'),
(13, 45, 'user1', '', '+7(111)111-11-11', 'example@mail.mail'),
(14, 46, 'user2', '', '+7(222)222-22-22', 'example@mail.mail'),
(15, 47, 'user4', '', '+7(141)241-24-12', 'example@mail.mail'),
(16, 48, 'usadrsafa', '', '+7(156)125-12-51', 'example@mail.mail'),
(17, 49, 'asfasfa', '', '+7(512)512-51-25', 'example@mail.mail'),
(18, 50, 'nevajsno', '', '+7(234)156-46-54', 'nevajsno@sobaka.sobaka'),
(19, 51, 'DvMon', 'asdasdasd', '+7(111)111-11-11', 'dvmon@dvmon.dvmon'),
(20, 52, 'DvMon', '', '+7(215)465-46-54', 'asdasd@asa.wasf'),
(21, 53, 'DvMon', 'MVdon', '+7(893)333-86-76', 'popcaric@gmail.com'),
(22, 54, 'DvMon', '', '+7(134)124-12-41', '124141');

-- 
-- Вывод данных для таблицы booking_list
--
INSERT INTO booking_list VALUES
(36, 38, 18, '2019-09-30 17:31:29', '2019-09-30 00:00:00', '2019-10-06 00:00:00', 0),
(37, 39, 14, '2019-09-30 20:40:21', '2019-10-04 00:00:00', '2019-10-10 00:00:00', 0),
(38, 40, 10, '2019-09-30 20:40:36', '2019-09-30 00:00:00', '2019-10-06 00:00:00', 0),
(39, 41, 10, '2019-10-15 03:06:38', '2019-10-13 00:00:00', '2019-10-14 00:00:00', 0),
(40, 42, 13, '2019-10-16 03:50:09', '2019-10-16 00:00:00', '2019-11-10 00:00:00', 0),
(41, 43, 7, '2019-10-20 20:34:20', '2019-10-20 00:00:00', '2019-10-27 00:00:00', 0),
(42, 44, 14, '2019-10-22 03:58:38', '2019-10-23 00:00:00', '2019-10-24 00:00:00', 0),
(43, 45, 12, '2019-10-23 03:59:45', '2019-10-23 00:00:00', '2019-10-26 00:00:00', 0),
(44, 46, 5, '2019-10-23 04:00:02', '2019-10-23 00:00:00', '2019-10-24 00:00:00', 0),
(45, 47, 10, '2019-10-23 04:00:50', '2019-10-23 00:00:00', '2019-10-27 00:00:00', 0),
(46, 48, 9, '2019-10-23 04:01:05', '2019-10-23 00:00:00', '2019-10-26 00:00:00', 0),
(47, 49, 1, '2019-10-23 04:01:30', '2019-10-23 00:00:00', '2019-10-26 00:00:00', 0),
(48, 50, 18, '2019-10-24 04:59:02', '2019-10-24 00:00:00', '2019-10-25 00:00:00', 0),
(49, 51, 14, '2019-10-26 21:19:44', '2019-10-26 00:00:00', '2019-10-27 00:00:00', 0),
(50, 52, 14, '2019-10-28 17:42:41', '2019-10-28 00:00:00', '2019-11-03 00:00:00', 0),
(51, 53, 14, '2019-11-07 16:29:43', '2019-11-08 00:00:00', '2019-11-23 00:00:00', 1),
(52, 54, 13, '2019-11-12 19:40:56', '2019-11-12 00:00:00', '2019-12-01 00:00:00', 1);

--
-- Установка базы данных по умолчанию
--
USE tokyo_hotel;

--
-- Удалить триггер `NumContinue`
--
DROP TRIGGER IF EXISTS NumContinue;

--
-- Установка базы данных по умолчанию
--
USE tokyo_hotel;

DELIMITER $$

--
-- Создать триггер `NumContinue`
--
CREATE
DEFINER = 'root'@'%'
TRIGGER NumContinue
BEFORE INSERT
ON rooms
FOR EACH ROW
BEGIN
  SET @prev_num = (SELECT
      roomNumber
    FROM rooms
    ORDER BY IDr DESC LIMIT 1);
  IF
    (@prev_num = NULL) THEN
    SET @prev_num = 0;
  END IF;
  SET NEW.roomNumber = @prev_num + 1;
END
$$

DELIMITER ;

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;