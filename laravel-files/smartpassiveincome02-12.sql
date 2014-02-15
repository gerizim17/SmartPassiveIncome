-- MySQL dump 10.13  Distrib 5.6.11, for osx10.7 (i386)
--
-- Host: localhost    Database: smartpassiveincome
-- ------------------------------------------------------
-- Server version	5.6.11

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
-- Table structure for table `estimate`
--

DROP TABLE IF EXISTS `estimate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estimate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realestate_id` int(10) unsigned NOT NULL,
  `rent` decimal(8,2) NOT NULL,
  `repairs` decimal(8,2) NOT NULL,
  `cashflow` decimal(8,2) NOT NULL,
  `variable_expenses` decimal(8,2) NOT NULL,
  `fixed_expenses` decimal(8,2) NOT NULL,
  `roi` decimal(8,2) NOT NULL,
  `cashflow2` decimal(8,2) NOT NULL,
  `fixed_expenses2` decimal(8,2) NOT NULL,
  `roi2` decimal(8,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `estimate_realestate_id_foreign` (`realestate_id`),
  CONSTRAINT `estimate_realestate_id_foreign` FOREIGN KEY (`realestate_id`) REFERENCES `realestate` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estimate`
--

LOCK TABLES `estimate` WRITE;
/*!40000 ALTER TABLE `estimate` DISABLE KEYS */;
INSERT INTO `estimate` VALUES (2,8,1361.25,99.92,336.22,241.88,455.00,28.82,140.34,37.92,12.03,'2014-01-05 08:18:56','2014-02-06 23:22:49'),(3,16,1083.33,74.29,284.24,194.29,604.80,24.72,366.90,43.51,31.90,'2014-01-09 09:24:16','2014-01-09 09:25:42'),(4,18,820.42,98.75,267.49,180.79,195.00,37.32,147.24,16.25,20.54,'2014-02-02 12:47:49','2014-02-04 09:09:51'),(5,21,1500.00,201.38,121.90,351.38,420.00,13.93,-319.93,35.00,-36.56,'2014-02-13 02:39:45','2014-02-13 02:41:22');
/*!40000 ALTER TABLE `estimate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fixedexpense`
--

DROP TABLE IF EXISTS `fixedexpense`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fixedexpense` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realestate_id` int(10) unsigned NOT NULL,
  `taxes` decimal(8,2) NOT NULL DEFAULT '0.00',
  `insurance` decimal(8,2) NOT NULL DEFAULT '0.00',
  `utilities` decimal(8,2) NOT NULL DEFAULT '0.00',
  `misc` decimal(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fixedexpense_realestate_id_foreign` (`realestate_id`),
  CONSTRAINT `fixedexpense_realestate_id_foreign` FOREIGN KEY (`realestate_id`) REFERENCES `realestate` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fixedexpense`
--

LOCK TABLES `fixedexpense` WRITE;
/*!40000 ALTER TABLE `fixedexpense` DISABLE KEYS */;
INSERT INTO `fixedexpense` VALUES (2,8,260.00,55.00,140.00,0.00,'2014-01-05 08:17:51','2014-02-03 09:48:01'),(3,16,250.00,60.00,50.00,0.00,'2014-01-09 09:24:15','2014-01-09 09:25:41'),(4,18,145.00,50.00,0.00,0.00,'2014-02-02 05:38:28','2014-02-04 09:09:51'),(5,21,300.00,70.00,50.00,0.00,'2014-02-13 02:39:44','2014-02-13 02:39:44');
/*!40000 ALTER TABLE `fixedexpense` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2013_12_28_065834_create_rentaldetail',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mortgage`
--

DROP TABLE IF EXISTS `mortgage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mortgage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realestate_id` int(10) unsigned NOT NULL,
  `monthly_payment` decimal(8,2) NOT NULL DEFAULT '0.00',
  `monthly_payment2` decimal(8,2) NOT NULL DEFAULT '0.00',
  `sale_price` decimal(8,2) NOT NULL,
  `interest_rate` decimal(8,2) DEFAULT NULL,
  `percent_down` decimal(8,2) DEFAULT NULL,
  `pmi` decimal(8,2) DEFAULT NULL,
  `pmi2` decimal(8,2) DEFAULT NULL,
  `term` decimal(8,2) DEFAULT NULL,
  `term2` decimal(8,2) DEFAULT NULL,
  `calculator` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `mortgage_realestate_id_foreign` (`realestate_id`),
  CONSTRAINT `mortgage_realestate_id_foreign` FOREIGN KEY (`realestate_id`) REFERENCES `realestate` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mortgage`
--

LOCK TABLES `mortgage` WRITE;
/*!40000 ALTER TABLE `mortgage` DISABLE KEYS */;
INSERT INTO `mortgage` VALUES (4,8,328.16,195.88,61000.00,2.62,20.00,0.00,0.00,15.00,30.00,1,'2014-01-02 07:56:29','2014-02-06 23:22:49'),(9,16,244.80,162.14,40000.00,4.50,20.00,0.00,0.00,15.00,30.00,1,'2014-01-09 09:19:00','2014-01-09 09:25:41'),(10,17,0.00,0.00,210000.00,NULL,NULL,NULL,0.00,NULL,NULL,1,'2014-01-11 05:21:11','2014-01-11 05:21:11'),(11,18,177.14,120.25,28000.00,5.00,20.00,0.00,0.00,15.00,30.00,1,'2014-02-02 05:01:59','2014-02-04 09:09:51'),(14,21,606.73,441.82,75000.00,6.00,10.00,37.13,37.13,15.00,30.00,1,'2014-02-13 02:36:15','2014-02-13 02:41:22');
/*!40000 ALTER TABLE `mortgage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `realestate`
--

DROP TABLE IF EXISTS `realestate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `realestate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `address1` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `realestate`
--

LOCK TABLES `realestate` WRITE;
/*!40000 ALTER TABLE `realestate` DISABLE KEYS */;
INSERT INTO `realestate` VALUES (8,1,'2136 Lyon Street','','Des Moines','Iowa','50317','2014-01-02 07:56:29','2014-02-06 23:40:29'),(16,1,'1118 23rd Street','','Des Moines','','','2014-01-09 09:19:00','2014-01-09 09:19:00'),(17,2,'El Molino','','Las Cruces','','','2014-01-11 05:21:11','2014-01-11 05:21:11'),(18,1,'1022 27th','','Des Moines','','','2014-02-02 05:01:59','2014-02-02 05:01:59'),(21,1,'1048 19th','','Des Moines','','','2014-02-13 02:36:15','2014-02-13 02:36:15');
/*!40000 ALTER TABLE `realestate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rentaldetail`
--

DROP TABLE IF EXISTS `rentaldetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rentaldetail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realestate_id` int(10) unsigned NOT NULL,
  `months_min` int(11) NOT NULL DEFAULT '8',
  `months_max` int(11) NOT NULL DEFAULT '12',
  `repair_min` int(11) NOT NULL DEFAULT '0',
  `repair_max` int(11) NOT NULL DEFAULT '0',
  `pm_monthly_charge` int(11) NOT NULL DEFAULT '0',
  `pm_vacancy_charge` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `rentaldetail_realestate_id_foreign` (`realestate_id`),
  CONSTRAINT `rentaldetail_realestate_id_foreign` FOREIGN KEY (`realestate_id`) REFERENCES `realestate` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rentaldetail`
--

LOCK TABLES `rentaldetail` WRITE;
/*!40000 ALTER TABLE `rentaldetail` DISABLE KEYS */;
INSERT INTO `rentaldetail` VALUES (3,8,10,12,0,200,10,70,'2014-01-05 08:17:51','2014-02-06 23:22:49'),(4,16,8,12,0,150,10,70,'2014-01-09 09:24:01','2014-01-09 09:25:41'),(5,18,10,12,50,150,10,0,'2014-02-02 05:38:28','2014-02-04 09:09:51'),(6,21,8,12,100,300,10,0,'2014-02-13 02:39:44','2014-02-13 02:41:22');
/*!40000 ALTER TABLE `rentaldetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rentalhistory`
--

DROP TABLE IF EXISTS `rentalhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rentalhistory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realestate_id` int(10) unsigned NOT NULL,
  `date` date NOT NULL,
  `rent` decimal(8,2) NOT NULL,
  `mortgage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `property_management` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(8,2) NOT NULL DEFAULT '0.00',
  `insurance` decimal(8,2) NOT NULL DEFAULT '0.00',
  `electricity` decimal(8,2) NOT NULL DEFAULT '0.00',
  `water` decimal(8,2) NOT NULL DEFAULT '0.00',
  `cashflow` decimal(8,2) NOT NULL DEFAULT '0.00',
  `repairs` decimal(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `rentalhistorie_realestate_id_foreign` (`realestate_id`),
  CONSTRAINT `rentalhistorie_realestate_id_foreign` FOREIGN KEY (`realestate_id`) REFERENCES `realestate` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rentalhistory`
--

LOCK TABLES `rentalhistory` WRITE;
/*!40000 ALTER TABLE `rentalhistory` DISABLE KEYS */;
INSERT INTO `rentalhistory` VALUES (19,8,'2013-12-01',1485.00,331.64,148.50,261.67,56.87,37.55,116.40,432.37,100.00,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(20,8,'2013-11-01',1485.00,331.64,148.50,261.67,56.87,37.55,122.43,326.34,200.00,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(21,8,'2013-10-01',1485.00,331.64,148.50,261.67,56.87,33.90,122.43,429.99,100.00,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(22,8,'2013-09-01',1485.00,331.64,148.50,261.67,56.87,30.61,122.43,183.28,350.00,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(23,8,'2013-08-01',1485.00,331.64,148.50,261.67,56.87,40.57,152.73,493.02,0.00,'0000-00-00 00:00:00','2014-01-09 06:58:41'),(24,8,'2013-07-01',1485.00,331.64,148.50,261.67,56.87,38.16,152.73,495.43,0.00,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(25,8,'2013-06-01',1485.00,331.64,148.50,261.67,56.87,41.17,128.60,516.55,0.00,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(26,8,'2013-05-01',1485.00,331.64,148.50,261.67,56.87,43.73,116.55,416.54,109.50,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(27,8,'2013-04-01',1485.00,331.64,148.50,261.67,56.87,35.73,116.55,-665.96,1200.00,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(28,8,'2013-03-01',1485.00,331.64,148.50,261.67,56.87,43.73,122.58,420.01,100.00,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(29,8,'2013-02-01',1485.00,331.64,148.50,261.67,56.87,38.15,128.60,419.57,100.00,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(30,8,'2013-01-01',1485.00,331.64,148.50,261.67,56.87,36.82,115.50,534.00,0.00,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(44,8,'2012-11-01',1485.00,331.64,148.50,261.67,56.87,37.55,116.40,432.37,100.00,'2014-01-09 04:58:07','2014-01-09 04:58:07'),(45,8,'2014-01-01',1485.00,331.64,148.50,261.67,56.87,53.55,122.40,510.37,0.00,'2014-01-16 10:40:20','2014-01-16 13:23:50'),(46,8,'2012-01-01',1485.00,331.64,148.50,261.67,56.87,53.55,122.40,410.37,100.00,'2014-01-28 07:41:26','2014-01-28 07:41:26');
/*!40000 ALTER TABLE `rentalhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `renttier`
--

DROP TABLE IF EXISTS `renttier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `renttier` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realestate_id` int(10) unsigned NOT NULL,
  `units` int(11) NOT NULL,
  `rent` float(8,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `renttier_realestate_id_foreign` (`realestate_id`),
  CONSTRAINT `renttiers_re_id_foreign` FOREIGN KEY (`realestate_id`) REFERENCES `realestate` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `renttier`
--

LOCK TABLES `renttier` WRITE;
/*!40000 ALTER TABLE `renttier` DISABLE KEYS */;
INSERT INTO `renttier` VALUES (3,8,1,1485.00,'2014-01-05 08:17:51','2014-02-06 23:22:49'),(4,16,1,1300.00,'2014-01-09 09:24:01','2014-01-09 09:25:41'),(5,18,1,895.00,'2014-02-02 05:38:28','2014-02-04 09:09:51'),(6,21,1,1800.00,'2014-02-13 02:39:44','2014-02-13 02:41:22');
/*!40000 ALTER TABLE `renttier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `returnoninvestment`
--

DROP TABLE IF EXISTS `returnoninvestment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `returnoninvestment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realestate_id` int(10) unsigned NOT NULL,
  `down_payment` decimal(8,2) NOT NULL DEFAULT '0.00',
  `closing_costs` decimal(8,2) NOT NULL DEFAULT '0.00',
  `misc_expenses` decimal(8,2) NOT NULL DEFAULT '0.00',
  `init_investment` decimal(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `returnoninvestment_realestate_id_foreign` (`realestate_id`),
  CONSTRAINT `returnoninvestment_realestate_id_foreign` FOREIGN KEY (`realestate_id`) REFERENCES `realestate` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `returnoninvestment`
--

LOCK TABLES `returnoninvestment` WRITE;
/*!40000 ALTER TABLE `returnoninvestment` DISABLE KEYS */;
INSERT INTO `returnoninvestment` VALUES (2,8,11000.00,3000.00,0.00,14000.00,'2014-01-05 08:18:10','2014-02-06 23:22:49'),(3,16,11800.00,2000.00,0.00,13800.00,'2014-01-09 09:24:15','2014-01-09 09:25:41'),(4,18,5600.00,3000.00,0.00,8600.00,'2014-02-02 05:38:37','2014-02-04 09:09:51'),(5,21,7500.00,3000.00,0.00,10500.00,'2014-02-13 02:39:44','2014-02-13 02:39:44');
/*!40000 ALTER TABLE `returnoninvestment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(20) DEFAULT NULL,
  `lastname` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Alejandro','Saenz','alejandro.saenz88@gmail.com','$2y$10$DAc8lzzlCqKKGYmCBxLQRu.jXPJAXMbauAcmnfUPaAWRYtroiNyeS','2014-02-09 00:52:24','2014-02-09 00:52:24'),(2,'Lupita','Chavira','lchavira61@hotmail.com','$2y$10$px27m6I2cFw0k2a5rG7UPu7/8v06bYA8/0vpZJ/bb6tcD0s5LvhHy','2014-02-10 00:30:36','2014-02-10 00:30:36');
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

-- Dump completed on 2014-02-12 15:24:45
