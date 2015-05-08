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
