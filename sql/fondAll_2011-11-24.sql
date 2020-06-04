/*
SQLyog Ultimate v9.02 
MySQL - 5.1.41 : Database - fond
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `about` */

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

insert  into `articles`(`id`,`rubricator_id`,`owner_id`,`articles_source_id`,`data`,`title`,`descr`,`author`) values (1,2,1,NULL,'2011-04-25 00:00:00','Заголовок статьи!','<p><strike><u><em>Описание </em></u></strike><span style=\"color: rgb(255, 0, 0);\">статьи</span>..</p>',''),(3,1,1,NULL,'2011-04-25 00:00:00','Заголовок статьи','<p><sup>Описание </sup><u><strong>статьи</strong></u></p>','Журнал \'Вокруг света\''),(5,1,1,NULL,'2011-05-06 00:00:00','Заголовок статьи','<p><strong>Описание </strong>статьи 123</p>',NULL),(6,1,1,NULL,'2011-05-13 00:00:00','Заголовок статьи.','Описание статьи',NULL),(7,1,3,NULL,'2011-05-16 00:00:00','Заголовок статьи','Описание статьи',NULL);

/*Table structure for table `articles_source` */

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
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Data for the table `com_app` */

insert  into `com_app`(`id`,`owner_id`,`position`,`fname`,`sname`,`lname`,`phone`,`email`,`skype`,`icq`,`image`) values (1,1,'Генеральный директор','Владимир','Игнатьевич','Спиридонов','2546999','sp@com.ru','Skype','333000111','21Nov2011_11-26-54tulips_jpg.jpg'),(2,1,'Водитель','Сергей','Андреевич','Волков','2546800','volkov@com.ru','volkov','301256684','15Nov2011_14-35-00chrysanthemum_jpg.jpg'),(3,1,'Программист','Андрей','Алексеевич','Симонов','2546812','it@com.ru','AntonIT',NULL,'08Jun2011_12-29-16hydrangeas_jpg.jpg'),(4,1,'Главный бухгалтер','Антонина','Владимировна','Спиридонова','2546877','sp2@com.ru','SpAV','35122626','21Jun2011_12-28-42desert_jpg.jpg'),(10,1,'SEO-специалист',NULL,NULL,'Наливайко','2012568','seo@com.ru','seoKras','123456789','08Jun2011_14-10-19koala_jpg.jpg'),(13,11,'Директор',NULL,NULL,NULL,'21212121','dir@new.ru',NULL,'456789','25Aug2011_15-02-17koala_jpg.jpg'),(15,3,'123',NULL,NULL,NULL,'12','12','12','12','15Nov2011_12-11-38chrysanthemum_jpg.jpg');

/*Table structure for table `discounts` */

CREATE TABLE `discounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned NOT NULL DEFAULT '0',
  `procent` tinyint(3) unsigned DEFAULT '0',
  `data` date DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `descr` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `discounts` */

insert  into `discounts`(`id`,`owner_id`,`procent`,`data`,`title`,`descr`) values (1,1,20,'2011-04-22','Новая коллекция! 10%-ая скидка на товары!..','<p><u><em>Поступила </em></u>коллекция <u>нового </u>сезона. 10%-ая <span style=\"color: rgb(255, 0, 0);\">скидка на аксессуары</span> <span style=\"color: rgb(0, 0, 255);\">предыдущей </span>коллекции!</p>'),(3,1,65,'2011-04-22','Заголовок скидки.','<p><span style=\"color: rgb(255, 0, 0);\">Описание <strong>скидки</strong></span></p>'),(5,3,20,'2011-05-16','Заголовок скидки. Открытие!','<p>Описание скидки. <span style=\"color: rgb(255, 0, 0);\"><strong><span style=\"font-size: x-large;\">Открытие</span></strong></span>!</p><p>&nbsp;</p>'),(6,11,20,'2011-08-25','Заголовок скидки/ Редактировано','<p>Описание скидки. <strong>Отредактировано</strong></p><br />');

/*Table structure for table `jobs` */

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `jobs` */

insert  into `jobs`(`id`,`owner_id`,`salary`,`exp`,`male`,`ageFrom`,`ageTill`,`education`,`contact`,`towns_id`,`specialities_id`,`data`,`comment`) values (1,1,20500,2,0,30,50,4,1,3,1,'2011-04-26','<ol><li>Текст примечания. Отредактировано</li><li>Второй пункт</li></ol>'),(2,1,30000,1,2,20,35,1,1,3,1,'2011-04-27','<p><span style=\"color: rgb(255, 102, 0); \">Текст </span><strong><em>примечания</em></strong>.&nbsp;</p>'),(3,3,30000,1,2,20,35,1,1,3,1,'2011-05-16','<p><span style=\"color: rgb(255, 102, 0); \">Текст </span><strong><em>примечания</em></strong>.&nbsp;</p>'),(5,1,20000,2,1,20,23,5,NULL,3,1,NULL,'<p>Текст <u><strong>вакансии</strong></u></p>'),(6,11,12000,1,1,40,45,1,NULL,3,1,NULL,'<p>Пробный текст с <span style=\"color: rgb(255, 0, 0);\"><strong>формаитрованием</strong></span>.</p>');

/*Table structure for table `messages` */

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
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

/*Data for the table `messages` */

insert  into `messages`(`id`,`owner_id`,`recipient_id`,`title`,`body`,`data`,`status`) values (1,3,1,'Проба','Тело сообщения','2011-06-08 09:45:21','2'),(2,0,1,'Проба 2','Тело сообщения 2','2011-06-09 09:45:21','1'),(3,3,1,'Проба 3','Тело сообщения 3','2011-06-09 09:45:21','1'),(4,4,1,'Проба 4','Тело сообщения 4','2011-06-09 09:43:21','2'),(5,4,1,'Проба5','Тело сообщения 5','2011-06-10 09:43:21','1'),(6,1,3,NULL,'','2011-06-16 11:18:53','0'),(7,1,3,NULL,'фывыв','2011-06-16 11:24:49','0'),(8,1,3,NULL,'ываывавыав','2011-06-16 11:37:40','0'),(9,1,3,NULL,'1','2011-06-16 12:55:25','0'),(10,1,3,NULL,'123','2011-06-16 14:51:19','0'),(11,1,3,NULL,'sdsaasdasdas212654656544444','2011-06-23 11:56:40','0'),(12,1,3,NULL,'fghfhh','2011-06-27 16:13:07','0'),(13,1,3,NULL,'fghfhh','2011-06-27 16:13:18','0'),(14,1,3,NULL,'fdfhdf fgdhf','2011-06-27 16:14:10','0'),(15,0,1,NULL,'Проба сохранения для незарегистрированного пользователя.','2011-07-26 13:50:43','1'),(16,0,3,NULL,'dfgfdg','2011-07-26 13:55:17','0'),(17,0,3,NULL,'фыв','2011-07-26 13:59:35','0'),(18,0,3,NULL,'вап','2011-07-26 14:15:15','0'),(19,0,3,NULL,'sdfsdf','2011-07-26 14:15:50','0'),(20,3,11,NULL,'sdfsdf','2011-07-26 14:15:50','1'),(21,1,3,NULL,'фывфы фвыв фыв фыв','2011-11-02 14:37:58','0'),(22,0,1,NULL,'Pellentesque ultrices dignissim libero eu imperdiet. Nam interdum quam at erat rutrum nec dictum ante cursus. Nam quis urna ut dolor pulvinar posuere. Praesent vitae erat risus, vitae fermentum neque. Aenean sodales turpis id odio tincidunt tincidunt. Praesent vulputate ligula sit amet urna dignissim id pharetra nunc porta. Donec est dui, viverra ac varius sed, adipiscing eget nunc. Aliquam metus.','2011-11-03 11:00:09','1'),(23,0,1,NULL,'sdf sdf sd fsd f','2011-11-03 11:09:50','0'),(24,0,1,NULL,'sdfhghdf','2011-11-08 09:11:02','2');

/*Table structure for table `news` */

CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned DEFAULT NULL,
  `rubricator_id` tinyint(4) unsigned DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `descr` text,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `news` */

insert  into `news`(`id`,`owner_id`,`rubricator_id`,`title`,`descr`,`data`) values (1,1,2,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lacus lectus, convallis eu interdum et, pharetra eu sapien. Aenean non leo massa metus!','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc iaculis volutpat porttitor. Mauris pharetra fermentum eros id vehicula. Cras elementum, tortor ac viverra accumsan, purus leo mattis tellus, sit amet adipiscing lectus urna quis nibh amet!','2011-04-19'),(10,1,2,'гне','апр','2011-04-20'),(13,1,2,'некнек','Описание новости','2011-04-21'),(12,1,1,'Новая. Первая','Новая. Вторая. ред.','2011-04-21'),(14,3,2,'Заголовок новости. Отредактировано','Описание новости. Отредактировано','2011-05-16'),(16,11,2,'Отредкатированный Заголовок новости','Описание новости. Отредактированное','2011-08-25');

/*Table structure for table `opinions` */

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

insert  into `opinions`(`id`,`owner_id`,`recipient_id`,`body`,`data`) values (14,3,1,'Тело сообщения 5 id14','2011-06-10'),(5,4,1,'Тело сообщения 5 id5','2011-06-10'),(6,2,3,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris porta, tortor et viverra lacinia, purus sapien consequat lectus, sit amet vulputate est ligula sed turpis. In varius nulla non neque fermentum in rhoncus diam placerat. Praesent velit justo, pharetra non pretium eu, pellentesque nullam.','2011-06-16'),(7,1,3,'Отредактировано dolor sit amet, consectetur adipiscing elit. Mauris porta, tortor et viverra lacinia, purus sapien consequat lectus, sit amet vulputate est ligula sed turpis.','2011-11-11'),(9,4,3,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque et magna diam. Donec feugiat, urna at dapibus eleifend, leo enim blandit orci, sed pretium metus risus vestibulum lorem. Ut accumsan, nunc et posuere ullamcorper, nisi mauris eleifend urna, sed sollicitudin magna sapien a posuere.','2011-06-16');

/*Table structure for table `partners` */

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

/*Table structure for table `photo` */

CREATE TABLE `photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned DEFAULT NULL,
  `albumID` int(10) unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  KEY `albumID` (`albumID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Data for the table `photo` */

/*Table structure for table `photoalbum` */

CREATE TABLE `photoalbum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned DEFAULT NULL,
  `cnt` tinyint(3) unsigned DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `photoalbum` */

/*Table structure for table `rating` */

CREATE TABLE `rating` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `recipient_id` int(10) DEFAULT NULL,
  `owner_id` int(10) DEFAULT NULL,
  `rate` tinyint(1) DEFAULT NULL,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipient_id` (`recipient_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `rating` */

insert  into `rating`(`id`,`recipient_id`,`owner_id`,`rate`,`data`) values (8,2,2,4,'2011-06-17'),(7,3,2,3,'2011-06-17'),(6,3,1,3,'2011-07-26'),(9,2,1,2,'2011-11-22');

/*Table structure for table `requisites` */

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

CREATE TABLE `rub_articles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `comment` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `rub_articles` */

insert  into `rub_articles`(`id`,`title`,`comment`) values (1,'Заголовок 1','Не мудрствую лукаво.'),(2,'Заголовок \'Номер 2\'','Комментарий ко второму заголовку.');

/*Table structure for table `rub_news` */

CREATE TABLE `rub_news` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `comment` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `rub_news` */

insert  into `rub_news`(`id`,`title`,`comment`) values (1,'Открытие','Открытие магазина, филиала и т.д'),(2,'Распродажа','Распродажа товаров и прочее');

/*Table structure for table `specialities` */

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

CREATE TABLE `tabs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `txt` text,
  `data` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `tabs` */

insert  into `tabs`(`id`,`owner_id`,`name`,`txt`,`data`) values (1,1,'Пользовательская','<p><u><span style=\"color: rgb(255, 0, 0);\">Содержимое</span></u></p>','2011-05-27 10:02:10'),(2,1,'Пользовательская 2','<p><span style=\"background-color: rgb(255, 0, 0);\">Содержимое 2</span></p>','2011-05-27 10:02:10'),(4,1,'Новая','<p><span style=\"background-color: rgb(51, 153, 102);\">Текст </span>новой закладки</p>','2011-05-27 13:50:33'),(9,11,'Новая','<p>Отредактированный текст. <u><strong><span style=\"color: rgb(51, 153, 102);\">Форматирование</span></strong></u>.</p>','2011-08-25 15:11:31');

/*Table structure for table `tbl_profiles` */

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
  `activities` varchar(200) DEFAULT NULL,
  `quant` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `form` (`form`),
  KEY `town` (`town`),
  KEY `town0` (`town0`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_profiles` */

insert  into `tbl_profiles`(`user_id`,`cface`,`address`,`cphone`,`fax`,`inn`,`kpp`,`orgname`,`address0`,`orgdate`,`site`,`form`,`town`,`town0`,`index`,`index0`,`image`,`descr`,`activities`,`quant`) values (1,'','Аэровокзальная, 12','26-12-2556','29-153-152',0,0,'Частное лицо','Аэровокзальная 13','0000-00-00','www.site.com',0,3,3,660064,660065,'21Nov2011_11-24-49hydrangeas_jpg.jpg','Краткое описание (255 символов)','Donec luctus tempor tincidunt. Etiam et elit libero. Pellentesque molestie sagittis nibh, sit amet volutpat quam euismod id. Sed venenatis varius velit, malesuada lacinia felis fringilla eu. Ut a sed.',30),(2,'Адрианов Олег Викторович','Железногорск, Ленинский пр-т 12-103','','',0,0,'','','2003-03-14','',1,2,2,660000,660000,NULL,NULL,NULL,25),(3,'Дарахвелидзе В.Р.','Семафорная 233-15','29-655-59','79135575577',1234567890,123456789,'СибГеоПроект','Гладкова 8-641','2011-12-12','www.sibgeopro.ru',2,3,3,660064,660063,NULL,NULL,NULL,20),(4,'Дарахвелидзе В.Р.','Семафорная 233-15','29-655-59','2-64-45-12',1234567890,123456789,'СибГеоПроект','Гладкова 8-641','2001-01-15','www.testsite.ru',2,4,4,660010,660009,NULL,NULL,NULL,20),(5,'Дарахвелидзе В.Р.','Семафорная 233-15','29-655-59','29-151-51',1234567890,123456789,'СибГеоПроект','Гладкова 8-641','2005-10-05','company.com',2,5,5,660016,660015,NULL,NULL,NULL,NULL),(6,'Белов Владимир Анатольевич','Красноярск, ул. Профсоюзов,14','211-90-79','296-6996',0,0,'','','2010-12-12','',1,6,6,660093,660092,NULL,NULL,NULL,NULL),(11,'','660064, Красноярск, Семафорная 233-15','29-655-59','',1234567890,0,'Новая организация','Гладкова 8-641','0000-00-00','',4,4,1,0,0,'25Aug2011_14-58-05chrysanthemum_jpg.jpg',NULL,NULL,NULL);

/*Table structure for table `tbl_profiles_fields` */

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_users` */

insert  into `tbl_users`(`id`,`username`,`password`,`email`,`activkey`,`createtime`,`lastvisit`,`superuser`,`status`) values (1,'admin','21232f297a57a5a743894a0e4a801fc3','webmaster@example.com','9a24eff8c15a6a141ece27eb6947da0f',1261146094,1322041348,1,1),(2,'vah','fe01ce2a7fbac8fafaed7c982a04e229','demo@example.com','099f825543f7850cc038b90aaff39fac',1261146096,1297855293,0,1),(3,'dvah','21232f297a57a5a743894a0e4a801fc3','vah@ler.net.ru','3fbce309e4f7b83e1dabcb1161081864',1291360929,1321333451,0,1),(4,'ddv','b5040cb72ee3744c820142ff10f63f95','ddv@ler.net.ru','0ba31db245e86fb01a4a501f545ca2eb',1291363938,1291364622,0,1),(5,'ddd','b5040cb72ee3744c820142ff10f63f95','ddd@ler.net.ru','d2f3ca15e85651f10fc198764f75d58b',1291365214,1291705365,0,1),(6,'srk','953cdbd5747772fd609cfebf0a4384f9','info@specrk.ru','2a19a0c9634f075d2b3822e71e53825c',1298905670,1305207098,0,1),(11,'newUser','a01610228fe998f515a72dd730294d87','vahtang@darahvelidze.ru','0ed012d402f1bb98b17af940468a6cb7',1314254444,1314254516,0,1);

/*Table structure for table `towns` */

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

/*Table structure for table `vac_categories` */

CREATE TABLE `vac_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT '0',
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8;

/*Data for the table `vac_categories` */

insert  into `vac_categories`(`id`,`parent_id`,`name`,`description`) values (1,0,'Название1',''),(6,1,'Еще одна категория',''),(9,0,'И еще одна категория',''),(10,0,'IT компьютеры интернет',''),(11,0,'Финансы экономика аудит банк',''),(12,0,'Бухгалтерия кассовый учет',''),(13,0,'Управленческий персонал администраторы',''),(14,0,'Секретариат офисный персонал АХО',''),(15,0,'Продажи торговля дистрибуция',''),(16,0,'Менеджеры по персоналу, HR-служба',''),(17,0,'Логистика, снабжение, перевозки, склад',''),(18,0,'Производство и строительство, рабочие специальности',''),(19,0,'Юриспруденция',''),(20,0,'Инженеры, проектировщики',''),(21,0,'Редакторы, журналисты, переводчики',''),(22,0,'Маркетинг, PR-служба',''),(23,0,'Повара, официанты, бармены, сомелье',''),(24,0,'Учебный, научный отдел',''),(25,0,'Охрана, служба безопасности, милиция',''),(26,0,'Дизайн, творческие профессии',''),(27,0,'Фармация, медицинский персонал',''),(28,0,'Услуги для населения, персонал для дома, спорт',''),(29,0,'Прочее, без опыта работы, подработка',''),(30,10,'программисты',''),(31,10,'системные администраторы',''),(32,10,'ит-специалисты',''),(33,10,'верстальщики',''),(34,10,'администраторы сайтов',''),(35,11,'экономисты',''),(36,11,'финансовые менеджеры',''),(37,11,'аудиторы',''),(38,11,'ревизоры',''),(39,11,'специалисты по кредитованию',''),(40,11,'банковские служащие',''),(41,11,'специалисты по страхованию',''),(42,12,'бухгалтеры',''),(43,12,'главные бухгалтеры',''),(44,12,'кассиры',''),(45,13,'администраторы',''),(46,13,'региональные представители',''),(47,13,'директора',''),(48,13,'супервайзеры',''),(49,13,'топ-менеджеры',''),(50,13,'менеджеры проектов',''),(51,13,'начальники отделов',''),(52,14,'офис-менеджеры',''),(53,14,'секретари',''),(54,14,'операторы',''),(55,14,'помощники руководителей',''),(56,14,'операторы 1С',''),(57,14,'специалисты АХО',''),(58,15,'менеджеры по продажам',''),(59,15,'торговые представители',''),(60,15,'продавцы',''),(61,15,'мерчендайзеры',''),(62,15,'менеджеры по работе с клиентами',''),(63,15,'риелторы',''),(64,15,'менеджеры по туризму',''),(65,15,'оценщики',''),(66,15,'страховые агенты',''),(67,15,'продакт менеджеры',''),(68,16,'менеджеры по персоналу',''),(69,16,'менеджеры по подбору персонала',''),(70,17,'водители',''),(71,17,'снабженцы',''),(72,17,'кладовщики',''),(73,17,'логисты',''),(74,17,'экспедиторы',''),(75,17,'курьеры',''),(76,17,'товароведы',''),(77,17,'диспетчеры',''),(78,17,'комплектовщики',''),(79,17,'менеджеры по перевозкам',''),(80,17,'остальные',''),(81,18,'механики',''),(82,18,'прорабы',''),(83,18,'электрики',''),(84,18,'монтажники',''),(85,18,'строители',''),(86,18,'слесари',''),(87,18,'сварщики',''),(88,18,'отделочники',''),(89,18,'сантехники',''),(90,18,'столяры',''),(92,18,'остальные',''),(93,19,'юристы',''),(94,19,'коллекторы',''),(95,19,'судебные приставы',''),(96,20,'инженеры',''),(97,20,'технологи',''),(98,20,'проектировщики',''),(99,20,'конструкторы',''),(100,20,'энергетики',''),(101,20,'сметчики',''),(102,20,'инженеры-электрики',''),(103,20,'инженеры по охране труда',''),(104,20,'инженеры по качеству',''),(105,20,'геодезисты',''),(106,20,'остальные',''),(107,21,'журналисты',''),(108,21,'редакторы',''),(109,21,'копирайтеры',''),(110,21,'писатели',''),(111,21,'прессатташе',''),(112,22,'маркетологи',''),(113,22,'pr-менеджеры',''),(114,22,'менеджеры по рекламе',''),(115,22,'бренд-менеджеры',''),(116,23,'повара',''),(117,23,'официанты',''),(118,23,'бармены',''),(119,23,'кондитеры',''),(120,24,'преподаватели',''),(121,24,'химики',''),(122,24,'бизнес-тренеры',''),(123,24,'лаборанты',''),(124,24,'тренинг менеджеры',''),(125,25,'охранники',''),(126,25,'сторожа',''),(127,25,'сотрудники службы безопасности',''),(128,25,'телохранители',''),(129,25,'милиционеры',''),(130,26,'дизайнеры',''),(131,26,'архитекторы',''),(132,26,'фотографы',''),(133,26,'флористы',''),(134,26,'иллюстраторы',''),(135,26,'модельеры',''),(136,26,'певцы',''),(137,27,'медицинские представители',''),(138,27,'врачи',''),(139,27,'медработники',''),(140,27,'массажисты',''),(141,27,'провизоры',''),(142,27,'фельдшеры',''),(143,28,'маникюристы',''),(144,28,'няни',''),(145,28,'спортивные тренеры',''),(146,28,'домработники',''),(147,28,'косметологи',''),(148,28,'парикмахеры',''),(149,28,'воспитатели',''),(150,28,'портные',''),(151,29,'промоутеры',''),(152,29,'наборщики текстов',''),(153,29,'грузчики',''),(154,29,'разнорабочие',''),(155,29,'уборщики',''),(156,29,'расклейщики объявлений',''),(157,29,'другие','');

/*Table structure for table `worktime` */

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

insert  into `worktime`(`user_id`,`mon1`,`mon2`,`tue1`,`tue2`,`wed1`,`wed2`,`thu1`,`thu2`,`fri1`,`fri2`,`sat1`,`sat2`,`sun1`,`sun2`,`din1`,`din2`) values (1,'08:30:00','20:35:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','20:00:00','13:30:00','14:30:00'),(2,'10:00:00','18:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','20:00:00','13:00:00','14:00:00'),(3,'10:00:00','18:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','20:00:00','13:00:00','14:00:00'),(4,'10:00:00','18:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','20:00:00','13:00:00','14:00:00'),(5,'10:00:00','18:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','20:00:00','13:00:00','14:00:00'),(6,'10:00:00','18:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','19:00:00','10:00:00','20:00:00','13:00:00','14:00:00'),(8,'00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00'),(9,'00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00'),(10,'00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00'),(11,'00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00','00:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
