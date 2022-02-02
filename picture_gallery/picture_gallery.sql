-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               10.4.22-MariaDB - mariadb.org binary distribution
-- Операционная система:         Win64
-- HeidiSQL Версия:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Дамп структуры базы данных picture_gallery
CREATE DATABASE IF NOT EXISTS `picture_gallery` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `picture_gallery`;

-- Дамп структуры для таблица picture_gallery.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `posted_by` varchar(20) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `content` varchar(250) DEFAULT NULL,
  `approval` tinyint(1) DEFAULT NULL,
  `image_id` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `posted_by` (`posted_by`),
  KEY `image_id` (`image_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`posted_by`) REFERENCES `users` (`username`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `pictures` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Дамп данных таблицы picture_gallery.comments: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` (`comment_id`, `posted_by`, `email`, `created_at`, `content`, `approval`, `image_id`) VALUES
	(12, 'Bipki', 'fakemail@mail.ru', '2021-12-21 08:12:22', 's:20:"Cool car, nice logo!";', 1, 14),
	(13, 'TheBoss', 'boss@bossmail.com', '2021-12-21 10:12:38', 's:17:"I am the admiiin!";', NULL, 14);
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;

-- Дамп структуры для таблица picture_gallery.galleries
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `title` tinytext NOT NULL,
  `previev_path` text DEFAULT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Дамп данных таблицы picture_gallery.galleries: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
INSERT INTO `galleries` (`id`, `title`, `previev_path`, `created_at`) VALUES
	(1, 'Cats', 'C:/Users/Nerehta/Pictures', '2021-11-26'),
	(2, 'Landscapes', 'C:/Users/Nerehta/Pictures', '2021-11-26'),
	(5, 'Cars', 'C:/Users/Nerehta/Pictures', '2021-12-03'),
	(9, 'Birds', 'null', '2021-12-21');
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;

-- Дамп структуры для таблица picture_gallery.pictures
CREATE TABLE IF NOT EXISTS `pictures` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) DEFAULT NULL,
  `image_path` text DEFAULT NULL,
  `created_at` date DEFAULT current_timestamp(),
  `gallery_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_gallery_id` (`gallery_id`),
  CONSTRAINT `fk_gallery_id` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- Дамп данных таблицы picture_gallery.pictures: ~9 rows (приблизительно)
/*!40000 ALTER TABLE `pictures` DISABLE KEYS */;
INSERT INTO `pictures` (`id`, `title`, `image_path`, `created_at`, `gallery_id`) VALUES
	(2, 'MountainGrasslands', 'Images/MountainGrasslands.jpg', '2021-12-07', 2),
	(4, 'OrangeKitten', 'Images/OrangeKitten.jpg', '2021-12-07', 1),
	(5, 'WhiteCat', 'Images/WhiteCat.jpg', '2021-12-07', 1),
	(6, 'MountainSunset', 'Images/MountainSunset.jpg', '2021-12-07', 2),
	(7, 'MountainRiver', 'Images/MountainRiver.jpg', '2021-12-07', 2),
	(9, 'OrangeCar', 'Images/OrangeCar.jpg', '2021-12-13', 5),
	(14, 'RedCar', 'Images/RedCar.jpg', '2021-12-16', 5),
	(15, 'RedBird', 'Images/RedBird.jpg', '2021-12-21', 9),
	(16, 'TwoColorfulBirds', 'Images/TwoColorfulBirds.jpg', '2021-12-21', 9);
/*!40000 ALTER TABLE `pictures` ENABLE KEYS */;

-- Дамп структуры для таблица picture_gallery.users
CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(20) NOT NULL,
  `passw` varchar(12) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `admin_flag` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Дамп данных таблицы picture_gallery.users: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`username`, `passw`, `email`, `admin_flag`) VALUES
	('Bipki', 'qwerty', 'fakemail@mail.ru', 0),
	('TheBoss', 'iamtheboss', 'boss@bossmail.com', 1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
