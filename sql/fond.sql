/*
SQLyog Community v9.0 RC
MySQL - 5.1.41 : Database - fond
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`fond` /*!40100 DEFAULT CHARACTER SET utf8 */;

/*Table structure for table `about` */

DROP TABLE IF EXISTS `about`;

CREATE TABLE `about` (
  `id` int(10) unsigned NOT NULL,
  `txt` text,
  `owner_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `articles` */

DROP TABLE IF EXISTS `articles`;

CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rubricator_id` smallint(5) unsigned DEFAULT '1',
  `owner_id` int(10) unsigned DEFAULT NULL,
  `articles_source_id` int(10) unsigned DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `title` varchar(300) DEFAULT NULL,
  `descr` text,
  `author` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rubricator_id` (`rubricator_id`),
  KEY `owner_id` (`owner_id`),
  KEY `articles_source_id` (`articles_source_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `articles_source` */

DROP TABLE IF EXISTS `articles_source`;

CREATE TABLE `articles_source` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `owner_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `file` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `com_app` */

DROP TABLE IF EXISTS `com_app`;

CREATE TABLE `com_app` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) DEFAULT NULL,
  `position` varchar(30) DEFAULT NULL,
  `fname` varchar(20) DEFAULT NULL,
  `sname` varchar(20) DEFAULT NULL,
  `lname` varchar(25) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `skype` varchar(50) DEFAULT NULL,
  `icq` varchar(12) DEFAULT NULL,
  `image` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`owner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Table structure for table `discounts` */

DROP TABLE IF EXISTS `discounts`;

CREATE TABLE `discounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned NOT NULL DEFAULT '0',
  `procent` tinyint(3) unsigned DEFAULT '0',
  `data` date DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `descr` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) DEFAULT NULL,
  `salary` float DEFAULT NULL,
  `exp` tinyint(1) DEFAULT NULL,
  `male` tinyint(1) DEFAULT NULL,
  `ageFrom` tinyint(2) DEFAULT NULL,
  `ageTill` tinyint(2) DEFAULT NULL,
  `education` tinyint(2) DEFAULT NULL,
  `contact` tinyint(2) DEFAULT NULL,
  `towns_id` int(10) DEFAULT NULL,
  `specialities_id` tinyint(4) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `contact` (`contact`),
  KEY `town` (`towns_id`),
  KEY `speciality` (`specialities_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) DEFAULT NULL,
  `recipient_id` int(10) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `body` varchar(400) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `status` char(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  KEY `recipient_id` (`recipient_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Table structure for table `news` */

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned DEFAULT NULL,
  `rubricator_id` tinyint(4) unsigned DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `descr` text,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Table structure for table `opinions` */

DROP TABLE IF EXISTS `opinions`;

CREATE TABLE `opinions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) DEFAULT NULL,
  `recipient_id` int(10) DEFAULT NULL,
  `body` varchar(300) DEFAULT NULL,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  KEY `recipient_id` (`recipient_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `partners` */

DROP TABLE IF EXISTS `partners`;

CREATE TABLE `partners` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `from_id` int(10) DEFAULT NULL,
  `to_id` int(10) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `from_id` (`from_id`),
  KEY `to_id` (`to_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `rating` */

DROP TABLE IF EXISTS `rating`;

CREATE TABLE `rating` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `recipient_id` int(10) DEFAULT NULL,
  `owner_id` int(10) DEFAULT NULL,
  `rate` tinyint(1) DEFAULT NULL,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipient_id` (`recipient_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `requisites` */

DROP TABLE IF EXISTS `requisites`;

CREATE TABLE `requisites` (
  `user_id` int(10) unsigned NOT NULL,
  `name_short` varchar(100) NOT NULL,
  `base` varchar(50) NOT NULL,
  `bank_account` varchar(100) NOT NULL,
  `current_account` varchar(20) NOT NULL,
  `correspondent_account` varchar(20) NOT NULL,
  `bik` varchar(9) NOT NULL,
  `ogrn` varchar(13) NOT NULL,
  `okpo` varchar(10) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `rub_articles` */

DROP TABLE IF EXISTS `rub_articles`;

CREATE TABLE `rub_articles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `comment` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `rub_news` */

DROP TABLE IF EXISTS `rub_news`;

CREATE TABLE `rub_news` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `comment` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `specialities` */

DROP TABLE IF EXISTS `specialities`;

CREATE TABLE `specialities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `root` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `comment` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `root` (`root`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `tabs` */

DROP TABLE IF EXISTS `tabs`;

CREATE TABLE `tabs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `txt` text,
  `data` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `tbl_profiles` */

DROP TABLE IF EXISTS `tbl_profiles`;

CREATE TABLE `tbl_profiles` (
  `user_id` int(11) NOT NULL,
  `cface` varchar(100) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `cphone` varchar(20) NOT NULL DEFAULT '',
  `fax` varchar(20) NOT NULL DEFAULT '',
  `inn` int(12) NOT NULL DEFAULT '0',
  `kpp` int(9) NOT NULL DEFAULT '0',
  `orgname` varchar(50) NOT NULL DEFAULT '',
  `address0` varchar(255) NOT NULL DEFAULT '',
  `orgdate` date NOT NULL,
  `site` varchar(40) NOT NULL,
  `form` tinyint(2) NOT NULL,
  `town` int(11) NOT NULL,
  `town0` int(11) NOT NULL,
  `index` mediumint(6) NOT NULL,
  `index0` mediumint(6) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `descr` varchar(255) DEFAULT NULL,
  `quant` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `form` (`form`),
  KEY `town` (`town`),
  KEY `town0` (`town0`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tbl_profiles_fields` */

DROP TABLE IF EXISTS `tbl_profiles_fields`;

CREATE TABLE `tbl_profiles_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL,
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` varchar(5000) NOT NULL DEFAULT '',
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` varchar(5000) NOT NULL DEFAULT '',
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`,`widget`,`visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tbl_users` */

DROP TABLE IF EXISTS `tbl_users`;

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `lastvisit` int(10) NOT NULL DEFAULT '0',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `superuser` (`superuser`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `towns` */

DROP TABLE IF EXISTS `towns`;

CREATE TABLE `towns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `root` int(10) unsigned DEFAULT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `rubricator` smallint(5) unsigned DEFAULT NULL,
  `comment` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `root` (`root`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Table structure for table `worktime` */

DROP TABLE IF EXISTS `worktime`;

CREATE TABLE `worktime` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `mon1` time DEFAULT '00:00:00',
  `mon2` time DEFAULT '00:00:00',
  `tue1` time DEFAULT '00:00:00',
  `tue2` time DEFAULT '00:00:00',
  `wed1` time DEFAULT '00:00:00',
  `wed2` time DEFAULT '00:00:00',
  `thu1` time DEFAULT '00:00:00',
  `thu2` time DEFAULT '00:00:00',
  `fri1` time DEFAULT '00:00:00',
  `fri2` time DEFAULT '00:00:00',
  `sat1` time DEFAULT '00:00:00',
  `sat2` time DEFAULT '00:00:00',
  `sun1` time DEFAULT '00:00:00',
  `sun2` time DEFAULT '00:00:00',
  `din1` time DEFAULT '00:00:00',
  `din2` time DEFAULT '00:00:00',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
