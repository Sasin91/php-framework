/*
SQLyog Community v12.11 (64 bit)
MySQL - 10.0.17-MariaDB : Database - theupcycle_layout
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`theupcycle_layout` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `theupcycle_layout`;

/*Table structure for table `carousel` */

DROP TABLE IF EXISTS `carousel`;

CREATE TABLE `carousel` (
  `page` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `btn_link` varchar(255) NOT NULL,
  `btn_label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `carousel` */

/*Table structure for table `menu` */

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(20) DEFAULT NULL,
  `link` varchar(20) DEFAULT NULL,
  `parent` int(11) unsigned DEFAULT NULL,
  `dropdown` tinyint(1) unsigned DEFAULT NULL,
  `provider` int(11) unsigned NOT NULL,
  `access` int(1) unsigned DEFAULT NULL,
  `orientation` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `child` (`parent`),
  KEY `level` (`access`),
  KEY `provider` (`provider`),
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`provider`) REFERENCES `menu_provider` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `menu` */

insert  into `menu`(`id`,`label`,`link`,`parent`,`dropdown`,`provider`,`access`,`orientation`) values (1,'home','/home',NULL,NULL,1,0,'left'),(5,'users','/users',NULL,1,1,0,'right'),(6,'authenticate','/users/authenticate',5,0,1,0,'right'),(7,'create','/users/create',5,0,1,0,'right'),(8,'logout','/users/logout',5,0,1,1,'right'),(9,'Shop','/shop',NULL,NULL,1,0,'left'),(10,'blog','/blog',NULL,NULL,1,0,'left');

/*Table structure for table `menu_provider` */

DROP TABLE IF EXISTS `menu_provider`;

CREATE TABLE `menu_provider` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `menu_provider` */

insert  into `menu_provider`(`id`,`label`) values (1,'theupcycle');

/*Table structure for table `page_content` */

DROP TABLE IF EXISTS `page_content`;

CREATE TABLE `page_content` (
  `page` varchar(30) NOT NULL,
  `part` varchar(30) NOT NULL,
  `contains` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `page_content` */

/*Table structure for table `pages` */

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `label` varchar(40) NOT NULL,
  `namespace` varchar(255) DEFAULT NULL,
  `contains` text,
  `hasContentInDatabase` tinyint(1) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pages` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
