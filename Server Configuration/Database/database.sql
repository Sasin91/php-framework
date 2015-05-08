/*
SQLyog Community v12.11 (64 bit)
MySQL - 10.0.17-MariaDB : Database - theupcycle_auth
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`theupcycle_auth` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `theupcycle_auth`;

/*Table structure for table `members` */

DROP TABLE IF EXISTS `members`;

CREATE TABLE `members` (
  `uid` int(11) NOT NULL COMMENT 'User ID',
  `role` varchar(40) NOT NULL,
  `position` varchar(40) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `role_reference` (`role`),
  CONSTRAINT `user_reference` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `members` */

insert  into `members`(`uid`,`role`,`position`) values (1,'Admin','God'),(21,'member','user');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(30) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `psw` varchar(100) DEFAULT NULL,
  `ip` varchar(45) NOT NULL,
  `location` varchar(40) NOT NULL,
  `joindate` datetime DEFAULT NULL,
  `image` varchar(40) DEFAULT NULL,
  `info` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`label`,`email`,`psw`,`ip`,`location`,`joindate`,`image`,`info`) values (1,'Lotd','sasin91@gmail.com','$2y$10$qTROMUdsiIT0jQW9Gsb.HeJ/zAuow1HI7ANmljhn2HTAxCG3mJcSG','127.0.0.1','local','0000-00-00 00:00:00','lotd.png','# Hello!'),(21,'test','test@testing.ton','$2y$10$U2N87xpnXeVfw5uR4xhKO.V8be3rX27W8gMvYYPjoCfmiTKX20c7S','127.0.0.1','','2015-04-27 11:04:49','smiley.png',NULL),(22,'test','test@testing.ton','$2y$10$U2N87xpnXeVfw5uR4xhKO.V8be3rX27W8gMvYYPjoCfmiTKX20c7S','127.0.0.1','','2015-04-27 11:04:49','smiley.png',NULL),(23,'test','test@testing.ton','$2y$10$U2N87xpnXeVfw5uR4xhKO.V8be3rX27W8gMvYYPjoCfmiTKX20c7S','127.0.0.1','','2015-04-27 11:04:49','smiley.png',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*
SQLyog Community v12.11 (64 bit)
MySQL - 10.0.17-MariaDB : Database - theupcycle_ids
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`theupcycle_ids` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `theupcycle_ids`;

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `type` varchar(255) NOT NULL,
  `data` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `cache` */

insert  into `cache`(`type`,`data`,`created`,`modified`) values ('\'storage\'','a:75:{i:0;a:5:{s:2:\"id\";s:1:\"1\";s:4:\"rule\";s:41:\"(?:\"[^\"]*[^-]?>)|(?:[^\\w\\s]\\s*\\/>)|(?:>\")\";s:6:\"impact\";s:1:\"4\";s:4:\"tags\";a:1:{i:0;a:2:{i:0;s:3:\"xss\";i:1;s:4:\"csrf\";}}s:11:\"description\";s:59:\"finds html breaking injections including whitespace attacks\";','2015-05-05 13:26:13','2015-05-05 13:26:13');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
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
/*
SQLyog Community v12.11 (64 bit)
MySQL - 10.0.17-MariaDB : Database - theupcycle_shop
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`theupcycle_shop` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `theupcycle_shop`;

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `category_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `category_pic` varchar(255) DEFAULT NULL,
  `category_link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `categories` */

insert  into `categories`(`category_id`,`category`,`category_description`,`category_pic`,`category_link`) values (1,'Upcycles','The Upcycles are unique piece by piece. With a strong durable cross-frame as the base , the Upcycles are further build up by 1st rate quality bicycle parts, and other upcycled accessories. The Upcycle searches for parts that are produced as closely to home as possible, to minimize the carbon footprint, ecological impact of the Upcycles. In this manner the saddles are crafted in the Netherlands by one of our partners Lepper, and for example the handles are produced by Brooks in the United Kingdom, and the mudguards are made out of old reused bicycle tires.','upcycle.jpg','upcycles'),(2,'Family Products','Not all old bike parts have to be used in a new bicycle. Adding a bit of cleverness to some old parts makes for beautiful jewelry, furniture, and an ever growing list of cool products. Because we use a wastestream as a basis, every upcycle family product is a unique \"one of a kind\" product. Orders may differ a bit from the pictures on the website because of this, however in all our hand made products is put a lot of effort and care to make it as beautiful as possible.','family.jpg','family');

/*Table structure for table `product_pictures` */

DROP TABLE IF EXISTS `product_pictures`;

CREATE TABLE `product_pictures` (
  `picture_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(100) NOT NULL,
  `placement` smallint(5) unsigned NOT NULL,
  `fk_product_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`picture_id`),
  KEY `fk_product_id` (`fk_product_id`),
  CONSTRAINT `product_pictures_ibfk_1` FOREIGN KEY (`fk_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

/*Data for the table `product_pictures` */

insert  into `product_pictures`(`picture_id`,`path`,`placement`,`fk_product_id`) values (1,'2012-11-30-211317large.jpg',0,1),(2,'2012-11-30-211336large.jpg',0,1),(3,'2012-11-30-211359large.jpg',0,1),(4,'2012-11-30-211423large.jpg',0,1),(5,'2012-11-30-211825large.jpg',0,1),(6,'2012-11-30-211836large.jpg',0,1),(7,'2012-11-24-201037large.jpg',0,4),(17,'2012-11-24-201249large.jpg',0,4),(18,'2012-11-24-201321large.jpg',0,4),(19,'2012-11-30-231458large.jpg',0,4),(21,'2012-12-15-111148large.jpg',0,5),(22,'2012-12-15-111205large.jpg',0,5),(23,'2012-12-15-111228large.jpg',0,5),(24,'2013-06-14-161906large.jpg',0,7),(25,'2013-06-14-161927large.jpg',0,7),(26,'2013-06-14-161942large.jpg',0,7),(27,'2013-06-14-161955large.jpg',0,7),(28,'2013-06-14-162005large.jpg',0,7),(29,'2012-11-23-162124large.jpg',0,6),(30,'2012-11-24-200031large.jpg',0,6),(31,'2012-11-24-200105large.jpg',0,6),(32,'2013-01-13-174416large.jpg',0,6),(33,'2013-01-13-174433large.jpg',0,6),(34,'2013-06-16-110615large.jpg',0,8),(35,'2013-06-16-110630large.jpg',0,8),(36,'2013-06-16-110645large.jpg',0,8),(37,'2013-06-16-110703large.jpg',0,8),(38,'2013-06-16-110716large.jpg',0,8);

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `qty` int(11) unsigned NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `fk_categories_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_categories_id` (`fk_categories_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`fk_categories_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `products` */

insert  into `products`(`id`,`label`,`short_description`,`description`,`qty`,`price`,`fk_categories_id`) values (1,'Upcycle','Upcycles are unisexy bicycles with an used frame as a basis.','Upcycles are unisexy bicycles with an used frame as a basis. Each one of them has a unique frame, the bikes are custom made with a lot of attention to every detail. You can order an Upcycle by mailing us, the shipping time is approximately 1 month. Besides the kinky turquoise colour, you can recognize an Upcycle by its mudguards made from outer tires. All the bikes are provided with high quality parts, like the leather handlebars from Brooks and the saddle from Lepper. Also the transportation distances are taken into consideration, limiting the ecological impact. All Upcycles are produced by apprenticeship centre Biesieklette in Den Hague.',10,'888.00',1),(4,'PANTS UP','Wonderful unique belts made from used bicycle tires.','Wonderful unique belts made from used bicycle tires. Since there are hundreds of different types of bicycle tires with each one literally having had thier own bicycle life, we can guarantee a unique Pants Up for everyone.\r\n\r\nBecause we are using sturdy and durable tyres we can assure a long life for our Pants Up. Furthermore the more you wear them the easier they will fit. With frequent use, a natural shine develops on the belt, giving it even more character.',10,'35.00',2),(5,'CHIN UP','The Chin Up is funky bicycle chain bracelet.','The Chin Up is funky bicycle chain bracelet. It is made out of bicycle chains produced in excess by Azor Bikes, one of Hollands authentic bicycle production companies. They produce around 50 bicycles per day.',10,'15.00',2),(6,'Lights Up','The Light Up is a unique desk lamp which is almost entirely made up of used bicycle parts. ','The Light Up is a unique desk lamp which is almost entirely made up of used bicycle parts. The base is made from recycled wood, with around it a woven inner tube. With the help of a nifty piece of bicycle chain, intertwined wheel spokes, and an used old head light, you have the recipe for a versatile adjustable groovy desk lamp, Light Up.\r\n\r\nThe lamp inside is a led light, emmiting a warm white light. It is self cooling and is ready for a long beautiful life where ever it lights up! You can buy the Light Up with three different coloured cords; turquoise, sexy pink or black.',10,'99.00',2),(7,'Legs Up','The Legs Up is as futon (leg chair) entirely upholstered using inner tubes!','\r\nThe Legs Up is as futon (leg chair) entirely upholstered using inner tubes! Its ridiculously comfortable, fun to use, and has a hidden compartment to stow away a comfy blanket or your favourite naughty books! Its frame is constructed with the use of different kinds of used wood picked out of the building industry, and its cushion is made out of metisse (an insulation material made out of used clothing no longer suitable for reuse).\r\n\r\nSo sit back relax, put up ya legs on our Legs Ups, and just enjoy yourself, take it easy now, Living Loving Life!',10,'144.00',2),(8,'PAY UP','The Pay Up is a very functional (business) card holder. Because of the elastic properties for outer tire everything you put in there will be clamped properly.','The Pay Up is a very functional (business) card holder. Because of the elastic properties for outer tire everything you put in there will be clamped properly. Besides the outer tire we have used the remainder leather of our saddle manufactuer Lepper. They use large leather patches to cut out their saddles, however a lot of leather is being disposed of because it\'s to small for a new saddle. Too bad, especially because this leather forms perfectly to you buttocks. Pay Up!',10,'19.00',2);

/*Table structure for table `purchased` */

DROP TABLE IF EXISTS `purchased`;

CREATE TABLE `purchased` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `item` varchar(255) NOT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `qty` int(11) unsigned NOT NULL,
  `purchased` datetime NOT NULL,
  `delivered` tinyint(1) unsigned NOT NULL DEFAULT '0',
  KEY `user_id` (`user_id`),
  CONSTRAINT `purchased_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `theupcycle_auth`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `purchased` */

insert  into `purchased`(`user_id`,`username`,`recipient`,`address`,`item`,`comments`,`qty`,`purchased`,`delivered`) values (1,'Lotd','','','Upcycle','',6,'2015-05-06 00:00:00',0),(1,'Lotd','','','Upcycle','',6,'2015-05-06 00:00:00',0),(1,'Lotd','Sorøvej 41','jonas hansen','Upcycle','',6,'2015-05-06 00:00:00',0),(1,'Lotd','sorøvej 41','sorøvej 41','Upcycle','',2,'2015-05-06 00:00:00',0);

/*Table structure for table `services` */

DROP TABLE IF EXISTS `services`;

CREATE TABLE `services` (
  `fk_product_id` int(11) NOT NULL,
  `service_price` decimal(10,0) NOT NULL,
  `service_description` varchar(255) NOT NULL,
  PRIMARY KEY (`fk_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `services` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
