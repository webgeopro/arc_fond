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

/*Data for the table `about` */

insert  into `about`(`id`,`txt`,`owner_id`) values (1,'Текст \"О компании\", пользователя с id=1',1);

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

/*Data for the table `articles` */

insert  into `articles`(`id`,`rubricator_id`,`owner_id`,`articles_source_id`,`data`,`title`,`descr`,`author`) values (1,2,1,NULL,'2011-04-25 00:00:00','Заголовок статьи!','<p><strike><u><em>Описание </em></u></strike><span style=\"color: rgb(255, 0, 0);\">статьи</span>..</p>',''),(3,1,1,NULL,'2011-04-25 00:00:00','Заголовок статьи','<p><sup>Описание </sup><u><strong>статьи</strong></u></p>','Журнал \'Вокруг света\''),(5,1,1,NULL,'2011-05-06 00:00:00','Заголовок статьи','Описание статьи',NULL),(6,1,1,NULL,'2011-05-13 00:00:00','Заголовок статьи','Описание статьи',NULL),(7,1,3,NULL,'2011-05-16 00:00:00','Заголовок статьи','Описание статьи',NULL);

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

/*Data for the table `articles_source` */

insert  into `articles_source`(`id`,`article_id`,`owner_id`,`name`,`file`) values (1,3,0,'Пробный','page.jpg'),(2,3,0,'Второй файл','source.png');

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

/*Data for the table `com_app` */

insert  into `com_app`(`id`,`owner_id`,`position`,`fname`,`sname`,`lname`,`phone`,`email`,`skype`,`icq`,`image`) values (1,1,'Генеральный директор','Владимир','Игнатьевич','Спиридонов','2546888','sp@com.ru','Skype',NULL,'08Jun2011_12-07-50tulips_jpg.jpg'),(2,1,'Водитель','Сергей','Андреевич','Волков','2546800','volkov@com.ru','volkov',NULL,'15Jun2011_12-41-43lighthouse_jpg.jpg'),(3,1,'Программист','Андрей','Алексеевич','Симонов','2546812','it@com.ru','AntonIT',NULL,'08Jun2011_12-29-16hydrangeas_jpg.jpg'),(4,1,'Главный бухгалтер','Антонина','Владимировна','Спиридонова','2546877','sp2@com.ru',NULL,'35122626','21Jun2011_12-28-42desert_jpg.jpg'),(10,1,'SEO-специалист',NULL,NULL,NULL,'2012568','seo@com.ru','seoKras','123456789','08Jun2011_14-10-19koala_jpg.jpg');

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

/*Data for the table `discounts` */

insert  into `discounts`(`id`,`owner_id`,`procent`,`data`,`title`,`descr`) values (1,1,10,'2011-04-22','Новая коллекция! 10%-ая скидка на товары!..','<p><u><em>Поступила </em></u>коллекция <u>нового </u>сезона. 10%-ая <span style=\"color: rgb(255, 0, 0);\">скидка на аксессуары</span> <span style=\"color: rgb(0, 0, 255);\">предыдущей </span>коллекции!</p>'),(3,1,15,'2011-04-22','Заголовок скидки.','<p>Описание <strong>скидки</strong></p>'),(5,3,20,'2011-05-16','Заголовок скидки. Открытие!','<p>Описание скидки. <span style=\"color: rgb(255, 0, 0);\"><strong><span style=\"font-size: x-large;\">Открытие</span></strong></span>!</p><p>&nbsp;</p>');

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

/*Data for the table `jobs` */

insert  into `jobs`(`id`,`owner_id`,`salary`,`exp`,`male`,`ageFrom`,`ageTill`,`education`,`contact`,`towns_id`,`specialities_id`,`data`,`comment`) values (1,1,20500,2,0,30,50,4,1,3,1,'2011-04-26','<ol><li>Текст примечания. Отредактировано</li><li>Второй пункт</li></ol>'),(2,1,30000,1,2,20,35,1,1,3,1,'2011-04-27','<p><span style=\"color: rgb(255, 102, 0); \">Текст </span><strong><em>примечания</em></strong>.&nbsp;</p>'),(3,3,30000,1,2,20,35,1,1,3,1,'2011-05-16','<p><span style=\"color: rgb(255, 102, 0); \">Текст </span><strong><em>примечания</em></strong>.&nbsp;</p>'),(5,1,20000,2,1,20,23,5,NULL,3,1,NULL,'<p>Текст <u><strong>вакансии</strong></u></p>');

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

/*Data for the table `messages` */

insert  into `messages`(`id`,`owner_id`,`recipient_id`,`title`,`body`,`data`,`status`) values (1,3,1,'Проба','Тело сообщения','2011-06-08 09:45:21','2'),(2,0,1,'Проба 2','Тело сообщения 2','2011-06-09 09:45:21','2'),(3,3,1,'Проба 3','Тело сообщения 3','2011-06-09 09:45:21','1'),(4,4,1,'Проба 4','Тело сообщения 4','2011-06-09 09:43:21','2'),(5,4,1,'Проба5','Тело сообщения 5','2011-06-10 09:43:21','3'),(6,1,3,NULL,'','2011-06-16 11:18:53','0'),(7,1,3,NULL,'фывыв','2011-06-16 11:24:49','0'),(8,1,3,NULL,'ываывавыав','2011-06-16 11:37:40','0'),(9,1,3,NULL,'1','2011-06-16 12:55:25','0'),(10,1,3,NULL,'123','2011-06-16 14:51:19','0'),(11,1,3,NULL,'sdsaasdasdas212654656544444','2011-06-23 11:56:40','0'),(12,1,3,NULL,'fghfhh','2011-06-27 16:13:07','0'),(13,1,3,NULL,'fghfhh','2011-06-27 16:13:18','0'),(14,1,3,NULL,'fdfhdf fgdhf','2011-06-27 16:14:10','0');

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

/*Data for the table `news` */

insert  into `news`(`id`,`owner_id`,`rubricator_id`,`title`,`descr`,`data`) values (1,1,2,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lacus lectus, convallis eu interdum et, pharetra eu sapien. Aenean non leo massa metus!','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc iaculis volutpat porttitor. Mauris pharetra fermentum eros id vehicula. Cras elementum, tortor ac viverra accumsan, purus leo mattis tellus, sit amet adipiscing lectus urna quis nibh amet!','2011-04-19'),(10,1,2,'гне','апр','2011-04-20'),(13,1,2,'некнек','Описание новости','2011-04-21'),(12,1,1,'Новая. Первая','Новая. Вторая. ред.','2011-04-21'),(14,3,2,'Заголовок новости. Отредактировано','Описание новости. Отредактировано','2011-05-16');

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

/*Data for the table `opinions` */

insert  into `opinions`(`id`,`owner_id`,`recipient_id`,`body`,`data`) values (14,3,1,'Тело сообщения 5','2011-06-10'),(5,4,1,'Тело сообщения 5','2011-06-10'),(6,2,3,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris porta, tortor et viverra lacinia, purus sapien consequat lectus, sit amet vulputate est ligula sed turpis. In varius nulla non neque fermentum in rhoncus diam placerat. Praesent velit justo, pharetra non pretium eu, pellentesque nullam.','2011-06-16'),(7,1,3,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris porta, tortor et viverra lacinia, purus sapien consequat lectus, sit amet vulputate est ligula sed turpis.','2011-06-27'),(9,4,3,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque et magna diam. Donec feugiat, urna at dapibus eleifend, leo enim blandit orci, sed pretium metus risus vestibulum lorem. Ut accumsan, nunc et posuere ullamcorper, nisi mauris eleifend urna, sed sollicitudin magna sapien a posuere.','2011-06-16');

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

/*Data for the table `partners` */

insert  into `partners`(`id`,`from_id`,`to_id`,`data`,`status`) values (1,1,3,'2011-06-22',0),(2,1,2,'2011-06-22',0),(3,3,1,'2011-06-23',0);

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

/*Data for the table `rating` */

insert  into `rating`(`id`,`recipient_id`,`owner_id`,`rate`,`data`) values (8,2,2,4,'2011-06-17'),(7,3,2,3,'2011-06-17'),(6,3,1,5,'2011-07-06');

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

/*Data for the table `requisites` */

insert  into `requisites`(`user_id`,`name_short`,`base`,`bank_account`,`current_account`,`correspondent_account`,`bik`,`ogrn`,`okpo`) values (1,'ООО \"ДБФ\"','Устав','СФ ОАО АКБ \"МЕЖДУНАРОДНЫЙ ФИНАНСОВЫЙ КЛУБ\" г. Красноярск','40702810202000000262','30101810100000000592','040407592','1102468050585','0123456789'),(3,'ООО \"КЭСИНН\"','Устав','СФ ОАО АКБ \"МЕЖДУНАРОДНЫЙ ФИНАНСОВЫЙ КЛУБ\" г. Красноярск','40702810202000000262','30101810100000000592','040407592','1102468050585','0123456789');

/*Table structure for table `rub_articles` */

DROP TABLE IF EXISTS `rub_articles`;

CREATE TABLE `rub_articles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `comment` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `rub_articles` */

insert  into `rub_articles`(`id`,`title`,`comment`) values (1,'Заголовок 1','Не мудрствую лукаво.'),(2,'Заголовок \'Номер 2\'','Комментарий ко второму заголовку.');

/*Table structure for table `rub_news` */

DROP TABLE IF EXISTS `rub_news`;

CREATE TABLE `rub_news` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `comment` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `rub_news` */

insert  into `rub_news`(`id`,`title`,`comment`) values (1,'Открытие','Открытие магазина, филиала и т.д'),(2,'Распродажа','Распродажа товаров и прочее');

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

/*Data for the table `specialities` */

insert  into `specialities`(`id`,`root`,`lft`,`rgt`,`level`,`name`,`comment`) values (1,0,1,1,1,'Финансы и кредит','Описание. Финансы и кредит.');

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

/*Data for the table `tabs` */

insert  into `tabs`(`id`,`owner_id`,`name`,`txt`,`data`) values (1,1,'Пользовательская','<p><u><span style=\"color: rgb(255, 0, 0);\">Содержимое</span></u></p>','2011-05-27 10:02:10'),(2,1,'Пользовательская 2','<p><span style=\"background-color: rgb(255, 0, 0);\">Содержимое 2</span></p>','2011-05-27 10:02:10'),(4,1,'Новая','<p><span style=\"background-color: rgb(51, 153, 102);\">Текст </span>новой закладки</p>','2011-05-27 13:50:33');

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

/*Data for the table `tbl_profiles` */

insert  into `tbl_profiles`(`user_id`,`cface`,`address`,`cphone`,`fax`,`inn`,`kpp`,`orgname`,`address0`,`orgdate`,`site`,`form`,`town`,`town0`,`index`,`index0`,`image`,`descr`,`quant`) values (1,'','Аэровокзальная, 12','26-12-2556','29-153-152',0,0,'','Аэровокзальная 13','0000-00-00','www.site.com',0,3,3,660064,660065,'23Jun2011_10-47-12lighthouse_jpg.jpg','Краткое описание (255 символов)',30),(2,'Адрианов Олег Викторович','Железногорск, Ленинский пр-т 12-103','','',0,0,'','','2003-03-14','',1,2,2,660000,660000,NULL,NULL,25),(3,'Дарахвелидзе В.Р.','Семафорная 233-15','29-655-59','79135575577',1234567890,123456789,'СибГеоПроект','Гладкова 8-641','2011-12-12','www.sibgeopro.ru',2,3,3,660064,660063,NULL,NULL,20),(4,'Дарахвелидзе В.Р.','Семафорная 233-15','29-655-59','2-64-45-12',1234567890,123456789,'СибГеоПроект','Гладкова 8-641','2001-01-15','www.testsite.ru',2,4,4,660010,660009,NULL,NULL,20),(5,'Дарахвелидзе В.Р.','Семафорная 233-15','29-655-59','29-151-51',1234567890,123456789,'СибГеоПроект','Гладкова 8-641','2005-10-05','company.com',2,5,5,660016,660015,NULL,NULL,NULL),(6,'Белов Владимир Анатольевич','Красноярск, ул. Профсоюзов,14','211-90-79','296-6996',0,0,'','','2010-12-12','',1,6,6,660093,660092,NULL,NULL,NULL);

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

/*Data for the table `tbl_profiles_fields` */

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

/*Data for the table `tbl_users` */

insert  into `tbl_users`(`id`,`username`,`password`,`email`,`activkey`,`createtime`,`lastvisit`,`superuser`,`status`) values (1,'admin','21232f297a57a5a743894a0e4a801fc3','webmaster@example.com','9a24eff8c15a6a141ece27eb6947da0f',1261146094,1309766031,1,1),(2,'vah','fe01ce2a7fbac8fafaed7c982a04e229','demo@example.com','099f825543f7850cc038b90aaff39fac',1261146096,1297855293,0,1),(3,'dvah','fe01ce2a7fbac8fafaed7c982a04e229','vah@ler.net.ru','3fbce309e4f7b83e1dabcb1161081864',1291360929,1305529261,0,1),(4,'ddv','b5040cb72ee3744c820142ff10f63f95','ddv@ler.net.ru','0ba31db245e86fb01a4a501f545ca2eb',1291363938,1291364622,0,1),(5,'ddd','b5040cb72ee3744c820142ff10f63f95','ddd@ler.net.ru','d2f3ca15e85651f10fc198764f75d58b',1291365214,1291705365,0,1),(6,'srk','953cdbd5747772fd609cfebf0a4384f9','info@specrk.ru','2a19a0c9634f075d2b3822e71e53825c',1298905670,1305207098,0,1);

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

/*Data for the table `towns` */

insert  into `towns`(`id`,`parent_id`,`name`,`lft`,`rgt`,`root`,`level`,`rubricator`,`comment`) values (1,NULL,'Красноярский край',1,10,2,0,NULL,NULL),(2,NULL,'Московская область',1,8,3,0,NULL,NULL),(3,1,'Красноярск',8,9,2,1,NULL,NULL),(4,1,'Абакан',2,3,2,1,NULL,NULL),(5,NULL,'Бурятия',1,6,1,0,NULL,NULL),(6,5,'Улан-Удэ',4,5,1,1,NULL,NULL),(7,5,'Гусиноозерск',2,3,1,1,NULL,NULL),(8,2,'Москва',4,5,3,1,NULL,NULL),(9,2,'Черноголовка',6,7,3,1,NULL,NULL),(10,1,'Канск',6,7,2,1,NULL,NULL),(11,1,'Ачинск',4,5,2,1,NULL,NULL),(12,2,'Клин',2,3,3,1,NULL,NULL);

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

/*Data for the table `worktime` */

insert  into `worktime`(`user_id`,`mon1`,`mon2`,`tue1`,`tue2`,`wed1`,`wed2`,`thu1`,`thu2`,`fri1`,`fri2`,`sat1`,`sat2`,`sun1`,`sun2`,`din1`,`din2`) values (1,'08:30:00','20:30:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','20:00:00','13:00:00','14:00:00'),(2,'10:00:00','18:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','20:00:00','13:00:00','14:00:00'),(3,'10:00:00','18:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','20:00:00','13:00:00','14:00:00'),(4,'10:00:00','18:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','20:00:00','13:00:00','14:00:00'),(5,'10:00:00','18:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','20:00:00','13:00:00','14:00:00'),(6,'10:00:00','18:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','20:00:00','13:00:00','14:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
