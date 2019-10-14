-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 14 2019 г., 23:47
-- Версия сервера: 5.7.25
-- Версия PHP: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tokyo_hotel`
--

DELIMITER $$
--
-- Процедуры
--
CREATE DEFINER=`root`@`%` PROCEDURE `CHECK_BOOKED` (IN `roomID` INT, IN `dateBeg` DATE, IN `dateEnd` DATE)  BEGIN
 SELECT
  COUNT(*)
  FROM `booking_list` `bl`
  WHERE ((`bl`.`IDr` = roomID)
  AND ((`bl`.`comingDate` BETWEEN dateBeg AND dateEnd)
  OR (`bl`.`outDate` BETWEEN dateBeg AND dateEnd)
  OR (dateBeg BETWEEN `bl`.`comingDate` AND `bl`.`outDate`)
  OR (dateEnd BETWEEN `bl`.`comingDate` AND `bl`.`outDate`)));    
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `ROOM_BOOKING` (IN `room_ID` INT, IN `client_ID` INT, IN `coming_Date` DATE, IN `out_Date` DATE)  BEGIN
    INSERT INTO booking_list (IDr, IDc, bookDate, comingDate, outDate)
      VALUES (room_ID, client_ID, NOW(), coming_date, out_date);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `booking_list`
--

CREATE TABLE `booking_list` (
  `IDbook_l` int(11) NOT NULL,
  `IDc` int(11) NOT NULL,
  `IDr` int(11) NOT NULL,
  `bookDate` datetime NOT NULL,
  `comingDate` datetime NOT NULL,
  `outDate` datetime NOT NULL,
  `book_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `booking_list`
--

INSERT INTO `booking_list` (`IDbook_l`, `IDc`, `IDr`, `bookDate`, `comingDate`, `outDate`, `book_status`) VALUES
(36, 38, 18, '2019-09-30 17:31:29', '2019-09-30 00:00:00', '2019-10-06 00:00:00', 0),
(37, 39, 14, '2019-09-30 20:40:21', '2019-10-04 00:00:00', '2019-11-10 00:00:00', 1),
(38, 40, 10, '2019-09-30 20:40:36', '2019-09-30 00:00:00', '2019-10-06 00:00:00', 0),
(39, 41, 10, '2019-10-15 03:06:38', '2019-10-18 00:00:00', '2019-10-19 00:00:00', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `IDc` int(11) NOT NULL,
  `user_login` varchar(30) NOT NULL,
  `user_password` varchar(32) NOT NULL,
  `user_hash` varchar(32) NOT NULL DEFAULT '',
  `account_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`IDc`, `user_login`, `user_password`, `user_hash`, `account_status`) VALUES
(38, '753876561', 'password_has_been_reset', '', 0),
(39, '371426617', 'c7e5d0235864bfeb2d8289da2c7d286a', '', 1),
(40, '592198351', 'password_has_been_reset', '', 0),
(41, '841356122', 'dc6d571e0fbfbc2262bf3a9f34a5d2e4', '43114fbb0b017428c078231ab37a1b42', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `clients_data`
--

CREATE TABLE `clients_data` (
  `IDcdat` int(11) NOT NULL,
  `IDc` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_fam` varchar(255) NOT NULL DEFAULT '',
  `client_phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='данные клиентского аккаунта';

--
-- Дамп данных таблицы `clients_data`
--

INSERT INTO `clients_data` (`IDcdat`, `IDc`, `client_name`, `client_fam`, `client_phone`) VALUES
(6, 38, 'DVMON', 'KrutoyChel', '+7(228)133-14-48'),
(7, 39, 'w', '', '+7(511)125-12-51'),
(8, 40, 'rqwr', 'safasfsafa', '+7(112)512-51-52'),
(9, 41, 'lol', 'asfasfa', '+7(214)124-12-41');

-- --------------------------------------------------------

--
-- Структура таблицы `living_list`
--

CREATE TABLE `living_list` (
  `IDliv_l` int(11) NOT NULL,
  `IDc` int(11) NOT NULL,
  `IDr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `a_date` datetime NOT NULL,
  `a_caption` varchar(255) NOT NULL,
  `a_initiator` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `logs`
--

INSERT INTO `logs` (`log_id`, `a_date`, `a_caption`, `a_initiator`) VALUES
(3, '2019-10-15 03:46:09', 'Сброшен бронь и аккаунт с ID = 38 по истечению срока брони', 'SERVER'),
(4, '2019-10-15 03:46:09', 'Сброшен бронь и аккаунт с ID = 40 по истечению срока брони', 'SERVER');

-- --------------------------------------------------------

--
-- Структура таблицы `rooms`
--

CREATE TABLE `rooms` (
  `IDr` int(11) NOT NULL,
  `IDrt` int(11) NOT NULL DEFAULT '1',
  `roomNumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='номера';

--
-- Дамп данных таблицы `rooms`
--

INSERT INTO `rooms` (`IDr`, `IDrt`, `roomNumber`) VALUES
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
-- Триггеры `rooms`
--
DELIMITER $$
CREATE TRIGGER `NumContinue` BEFORE INSERT ON `rooms` FOR EACH ROW BEGIN
  SET @prev_num = (SELECT roomNumber FROM rooms ORDER BY IDr DESC LIMIT 1);
  IF
    (@prev_num=NULL)THEN
    SET @prev_num = 0;
  END IF;
  SET NEW.roomNumber = @prev_num + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `room_types`
--

CREATE TABLE `room_types` (
  `IDrt` int(11) NOT NULL,
  `r_typeName` varchar(255) NOT NULL,
  `r_typeSlug` varchar(255) NOT NULL,
  `r_typeImageDir` int(11) NOT NULL,
  `r_typeCost` varchar(255) NOT NULL,
  `r_typeDesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `room_types`
--

INSERT INTO `room_types` (`IDrt`, `r_typeName`, `r_typeSlug`, `r_typeImageDir`, `r_typeCost`, `r_typeDesc`) VALUES
(2, 'Двухместный эконом', 'econom_2', 3, '30', 'Подойдет тебе, если ты имеешь тридцатку'),
(3, 'Четерехместный эконом', 'econom_4', 2, '55', 'Подойдет тебе, если вас 4 и у вас есть пятьдесят пять рублей'),
(4, 'Двухместный полулюкс', 'semilux_2', 1, '60', 'За 60-ку просто роскошь, сам бы в таком жил'),
(5, 'Двухместный президенский люкс', 'lux_2', 4, '90', 'Три тридцатки, но зато сколько расслабона и балдежа');

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `view1`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `view1` (
`IDc` int(11)
);

-- --------------------------------------------------------

--
-- Структура для представления `view1`
--
DROP TABLE IF EXISTS `view1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `view1`  AS  select `booking_list`.`IDc` AS `IDc` from `booking_list` where ((`booking_list`.`outDate` < curdate()) and (`booking_list`.`book_status` = 1)) ;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `booking_list`
--
ALTER TABLE `booking_list`
  ADD PRIMARY KEY (`IDbook_l`),
  ADD KEY `bookingClientID_FK` (`IDc`),
  ADD KEY `bookingRoomID_FK` (`IDr`);

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`IDc`),
  ADD UNIQUE KEY `clientLogin_UK` (`user_login`);

--
-- Индексы таблицы `clients_data`
--
ALTER TABLE `clients_data`
  ADD PRIMARY KEY (`IDcdat`),
  ADD KEY `clients_data_FK` (`IDc`);

--
-- Индексы таблицы `living_list`
--
ALTER TABLE `living_list`
  ADD PRIMARY KEY (`IDliv_l`),
  ADD KEY `livingClientID_FK` (`IDc`),
  ADD KEY `livingRoomID_FK` (`IDr`);

--
-- Индексы таблицы `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Индексы таблицы `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`IDr`),
  ADD UNIQUE KEY `RoomNUM_UK` (`roomNumber`),
  ADD KEY `RoomType_FK` (`IDrt`);

--
-- Индексы таблицы `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`IDrt`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `booking_list`
--
ALTER TABLE `booking_list`
  MODIFY `IDbook_l` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `IDc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT для таблицы `clients_data`
--
ALTER TABLE `clients_data`
  MODIFY `IDcdat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `rooms`
--
ALTER TABLE `rooms`
  MODIFY `IDr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `room_types`
--
ALTER TABLE `room_types`
  MODIFY `IDrt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `booking_list`
--
ALTER TABLE `booking_list`
  ADD CONSTRAINT `bookingClientID_FK` FOREIGN KEY (`IDc`) REFERENCES `clients` (`IDc`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingRoomID_FK` FOREIGN KEY (`IDr`) REFERENCES `rooms` (`IDr`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `clients_data`
--
ALTER TABLE `clients_data`
  ADD CONSTRAINT `clients_data_FK` FOREIGN KEY (`IDc`) REFERENCES `clients` (`IDc`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `living_list`
--
ALTER TABLE `living_list`
  ADD CONSTRAINT `livingClientID_FK` FOREIGN KEY (`IDc`) REFERENCES `clients` (`IDc`) ON UPDATE CASCADE,
  ADD CONSTRAINT `livingRoomID_FK` FOREIGN KEY (`IDr`) REFERENCES `rooms` (`IDr`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `RoomType_FK` FOREIGN KEY (`IDrt`) REFERENCES `room_types` (`IDrt`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
