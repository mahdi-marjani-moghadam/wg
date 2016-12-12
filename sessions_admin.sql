# Host: 127.0.0.1  (Version 5.6.26)
# Date: 2016-12-10 23:17:22
# Generator: MySQL-Front 5.4  (Build 1.26)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "sessions_admin"
#

CREATE TABLE `sessions_admin` (
  `Sessions_admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `remote_addr` char(20) NOT NULL DEFAULT '',
  `last_access_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `admin_id` int(11) DEFAULT NULL,
  `remember_me` int(11) DEFAULT NULL,
  `browser_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Sessions_admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
