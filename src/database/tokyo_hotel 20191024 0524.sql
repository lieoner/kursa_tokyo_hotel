--
-- Скрипт сгенерирован Devart dbForge Studio 2019 for MySQL, Версия 8.2.23.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 24.10.2019 5:24:20
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
-- Удалить таблицу `inside_persons_data`
--
DROP TABLE IF EXISTS inside_persons_data;

--
-- Удалить таблицу `logs`
--
DROP TABLE IF EXISTS logs;

--
-- Удалить таблицу `inside_persons`
--
DROP TABLE IF EXISTS inside_persons;

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
AUTO_INCREMENT = 51,
AVG_ROW_LENGTH = 1365,
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
AUTO_INCREMENT = 27,
AVG_ROW_LENGTH = 4096,
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
AUTO_INCREMENT = 19,
AVG_ROW_LENGTH = 1365,
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
AUTO_INCREMENT = 49,
AVG_ROW_LENGTH = 1365,
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
  `rooms`.`roomNumber` AS `roomNumber`,
  (TO_DAYS(`booking_list`.`outDate`) - TO_DAYS(`booking_list`.`comingDate`)) AS `totalDaysCount`,
  (`room_types`.`r_typeCost` * (TO_DAYS(`booking_list`.`outDate`) - TO_DAYS(`booking_list`.`comingDate`))) AS `totalCost`,
  `rooms`.`IDr` AS `IDr`
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
-- Создать таблицу `logs`
--
CREATE TABLE logs (
  log_id int(11) NOT NULL AUTO_INCREMENT,
  a_date datetime NOT NULL,
  a_caption varchar(255) NOT NULL,
  IDip int(11) NOT NULL,
  PRIMARY KEY (log_id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 24,
AVG_ROW_LENGTH = 2340,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать внешний ключ
--
ALTER TABLE logs
ADD CONSTRAINT InsidePersonsID_FK FOREIGN KEY (IDip)
REFERENCES inside_persons (IDip) ON UPDATE CASCADE;

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
-- Вывод данных для таблицы room_types
--
INSERT INTO room_types VALUES
(2, 'Двухместный эконом', 'econom_2', 3, '30', 'Подойдет тебе, если ты имеешь тридцатку'),
(3, 'Четерехместный эконом', 'econom_4', 2, '55', 'Подойдет тебе, если вас 4 и у вас есть пятьдесят пять рублей'),
(4, 'Двухместный полулюкс', 'semilux_2', 1, '60', 'За 60-ку просто роскошь, сам бы в таком жил'),
(5, 'Двухместный президенский люкс', 'lux_2', 4, '90', 'Три тридцатки, но зато сколько расслабона и балдежа');

-- 
-- Вывод данных для таблицы inside_persons
--
INSERT INTO inside_persons VALUES
(1, 'SERVER', '1', ''),
(2, 'dVmon', '88a4b282d4b2d31968490ed20d02b39a', '220210e6e7e86708646244faed8644e5');

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
(42, '675036040', '458da96be88fc7af60e4a73461f95fef', 'eeeae54d6e61da23ee35445462f934ec', 1),
(43, '193418680', '932fd55aaba99f24a5f1d2d5ba215062', '3411fe3dc5bf9a5afaa318c3ac0874c2', 1),
(44, '989424122', '0c03ff5476d6702ec3298d8707ebce2f', '', 1),
(45, '175754296', 'f519f2dc0aa5fe350c60d84b8f538753', '', 1),
(46, '717111881', 'd2b7bf6f364f618a7b93ed895ccc38aa', '', 1),
(47, '678362795', '1d46b2fbbb21d0cf674b65949631811d', '', 1),
(48, '101241848', 'ca88442b1cb89f5953f07c407d5f0c1f', '', 1),
(49, '279463692', '83f9077adc71dfd3ef46772a2ac17300', '', 1),
(50, '302874300', '310570baf4e4a2c42da424158d20f487', '', 1);

-- 
-- Вывод данных для таблицы logs
--
INSERT INTO logs VALUES
(3, '2019-10-15 03:46:09', 'Сброшен бронь и аккаунт с ID = 38 по истечению срока брони', 1),
(4, '2019-10-15 03:46:09', 'Сброшен бронь и аккаунт с ID = 40 по истечению срока брони', 1),
(5, '2019-10-15 18:07:01', 'Сброшен бронь и аккаунт с ID = 41 по истечению срока брони', 1),
(7, '2019-10-22 22:17:03', 'Сброшен бронь и аккаунт с ID = 39 по истечению срока брони', 1),
(21, '2019-10-24 05:23:40', 'Подтверждено заселение клиента ID = 43 в комнату ID = 7', 2),
(22, '2019-10-24 05:23:45', 'Подтверждено заселение клиента ID = 47 в комнату ID = 10', 2),
(23, '2019-10-24 05:23:52', 'Подтверждено заселение клиента ID = 46 в комнату ID = 5', 2);

-- 
-- Вывод данных для таблицы living_list
--
INSERT INTO living_list VALUES
(1, 49, 1),
(24, 43, 7),
(25, 47, 10),
(26, 46, 5);

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
(18, 50, 'nevajsno', '', '+7(234)156-46-54', 'nevajsno@sobaka.sobaka');

-- 
-- Вывод данных для таблицы booking_list
--
INSERT INTO booking_list VALUES
(36, 38, 18, '2019-09-30 17:31:29', '2019-09-30 00:00:00', '2019-10-06 00:00:00', 0),
(37, 39, 14, '2019-09-30 20:40:21', '2019-10-04 00:00:00', '2019-10-10 00:00:00', 0),
(38, 40, 10, '2019-09-30 20:40:36', '2019-09-30 00:00:00', '2019-10-06 00:00:00', 0),
(39, 41, 10, '2019-10-15 03:06:38', '2019-10-13 00:00:00', '2019-10-14 00:00:00', 0),
(40, 42, 13, '2019-10-16 03:50:09', '2019-10-16 00:00:00', '2019-11-10 00:00:00', 1),
(41, 43, 7, '2019-10-20 20:34:20', '2019-10-20 00:00:00', '2019-10-27 00:00:00', 1),
(42, 44, 14, '2019-10-22 03:58:38', '2019-10-23 00:00:00', '2019-10-24 00:00:00', 1),
(43, 45, 12, '2019-10-23 03:59:45', '2019-10-23 00:00:00', '2019-10-26 00:00:00', 1),
(44, 46, 5, '2019-10-23 04:00:02', '2019-10-23 00:00:00', '2019-10-24 00:00:00', 1),
(45, 47, 10, '2019-10-23 04:00:50', '2019-10-23 00:00:00', '2019-10-27 00:00:00', 1),
(46, 48, 9, '2019-10-23 04:01:05', '2019-10-23 00:00:00', '2019-10-26 00:00:00', 1),
(47, 49, 1, '2019-10-23 04:01:30', '2019-10-23 00:00:00', '2019-10-26 00:00:00', 1),
(48, 50, 18, '2019-10-24 04:59:02', '2019-10-24 00:00:00', '2019-10-25 00:00:00', 1);

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