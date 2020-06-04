CREATE TABLE IF NOT EXISTS `activity` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `tbl_profile_activity` (
`user_id` INT( 11 ) NOT NULL ,
`activity_id` INT( 11 ) NOT NULL ,
PRIMARY KEY (  `user_id` ,  `activity_id` )
) ENGINE =InnoDB DEFAULT CHARSET=utf8  ;