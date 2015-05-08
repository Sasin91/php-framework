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
