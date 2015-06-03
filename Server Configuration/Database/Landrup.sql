-- MySQL dump 10.13  Distrib 5.5.43, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: landrup
-- ------------------------------------------------------
-- Server version	5.5.43-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `landrup`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `landrup` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `landrup`;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery` (
  `id` int(11) unsigned NOT NULL,
  `images` int(11) unsigned NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `images` (`images`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
INSERT INTO `gallery` VALUES (1,1,'Billede af  begynder hold'),(2,0,'Billede af  øvede hold'),(3,1,'Billede  af elite hold');
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gallery` int(11) unsigned NOT NULL,
  `description` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `gallery` (`gallery`),
  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`gallery`) REFERENCES `gallery` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` VALUES (1,1,'Latin','ballet.jpg'),(2,1,'Disco','criancas.jpg'),(3,2,'House','Mavedans.jpg'),(4,2,'Brugsdans','bonecos.jpg'),(5,2,'Standard','cabow.jpg'),(6,1,'Break Dance','junior_danse.jpg'),(7,3,'Jitterbug','estrela.jpg'),(8,3,'Funk','danca_rua.jpg'),(9,3,'Hip Hop','circo_danse.jpg');
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `uid` int(11) NOT NULL COMMENT 'User ID',
  `role` varchar(40) NOT NULL,
  `position` varchar(40) NOT NULL,
  `isInstructor` tinyint(1) unsigned NOT NULL,
  `teaches` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `role_reference` (`role`),
  CONSTRAINT `user_reference` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES (1,'Admin','God',0,NULL),(2,'member','user',0,NULL);
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `gallery` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'home','# hello',1);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_categories`
--

DROP TABLE IF EXISTS `shop_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_categories` (
  `category_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `category_pic` varchar(255) DEFAULT NULL,
  `category_link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_categories`
--

LOCK TABLES `shop_categories` WRITE;
/*!40000 ALTER TABLE `shop_categories` DISABLE KEYS */;
INSERT INTO `shop_categories` VALUES (1,'dans','# Danse undervisning	','dance.png','dance');
/*!40000 ALTER TABLE `shop_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_product_pictures`
--

DROP TABLE IF EXISTS `shop_product_pictures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_product_pictures` (
  `picture_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(100) NOT NULL,
  `placement` smallint(5) unsigned NOT NULL,
  `fk_product_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`picture_id`),
  KEY `fk_product_id` (`fk_product_id`),
  CONSTRAINT `shop_product_pictures_ibfk_1` FOREIGN KEY (`fk_product_id`) REFERENCES `shop_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_product_pictures`
--

LOCK TABLES `shop_product_pictures` WRITE;
/*!40000 ALTER TABLE `shop_product_pictures` DISABLE KEYS */;
INSERT INTO `shop_product_pictures` VALUES (1,'dans.png',0,1);
/*!40000 ALTER TABLE `shop_product_pictures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_products`
--

DROP TABLE IF EXISTS `shop_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_products` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `qty` int(11) unsigned NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `fk_categories_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_categories_id` (`fk_categories_id`),
  CONSTRAINT `shop_products_ibfk_1` FOREIGN KEY (`fk_categories_id`) REFERENCES `shop_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_products`
--

LOCK TABLES `shop_products` WRITE;
/*!40000 ALTER TABLE `shop_products` DISABLE KEYS */;
INSERT INTO `shop_products` VALUES (1,'dans','# Type 1','# YabaDaba Ding Dong',10,150.00,1),(2,'tango','# Tango','# Swingin',5,100.00,1);
/*!40000 ALTER TABLE `shop_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_purchased`
--

DROP TABLE IF EXISTS `shop_purchased`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_purchased` (
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
  CONSTRAINT `shop_purchased_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_purchased`
--

LOCK TABLES `shop_purchased` WRITE;
/*!40000 ALTER TABLE `shop_purchased` DISABLE KEYS */;
INSERT INTO `shop_purchased` VALUES (1,'Lotd','Jonas Hansen','Sorøvej 41','Dans','Boogie voogie',1,'2015-06-01 21:07:27',1);
/*!40000 ALTER TABLE `shop_purchased` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_services`
--

DROP TABLE IF EXISTS `shop_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_services` (
  `fk_product_id` smallint(5) unsigned NOT NULL,
  `service_price` decimal(10,0) NOT NULL,
  `service_description` varchar(255) NOT NULL,
  PRIMARY KEY (`fk_product_id`),
  CONSTRAINT `shop_services_ibfk_1` FOREIGN KEY (`fk_product_id`) REFERENCES `shop_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_services`
--

LOCK TABLES `shop_services` WRITE;
/*!40000 ALTER TABLE `shop_services` DISABLE KEYS */;
INSERT INTO `shop_services` VALUES (1,80,'repair');
/*!40000 ALTER TABLE `shop_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL,
  `time` datetime NOT NULL,
  `description` varchar(255) NOT NULL,
  `age_group` varchar(255) NOT NULL,
  `style` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Lotd','sasin91@gmail.com','$2y$10$qTROMUdsiIT0jQW9Gsb.HeJ/zAuow1HI7ANmljhn2HTAxCG3mJcSG','127.0.0.1','local','2015-05-21 12:21:15','lotd.png','# Hello!'),(2,'Looted','michaelkervinhansen@gmail.com','$2y$10$Gtb6WksrVtayy.zsfBZZuOOfYbT2fY/CS0v4DSgC65HE.qnVjT2bC','127.0.0.1','','2015-05-22 02:05:13','smiley.png',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-06-02 16:11:07
