# Host: 127.0.0.1  (Version 5.6.26)
# Date: 2016-12-10 23:17:07
# Generator: MySQL-Front 5.4  (Build 1.26)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "portfolio"
#

CREATE TABLE `portfolio` (
  `Portfolio_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) COLLATE utf8_persian_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `description` varchar(1000) COLLATE utf8_persian_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `originPic` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `otherPic` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  PRIMARY KEY (`Portfolio_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;
