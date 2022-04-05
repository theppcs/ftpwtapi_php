-- MySQL dump 10.13  Distrib 8.0.23, for Win64 (x86_64)
--
-- Host: localhost    Database: ftpwtapi
-- ------------------------------------------------------
-- Server version	8.0.28-0ubuntu0.20.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `apikey_granted`
--

DROP TABLE IF EXISTS `apikey_granted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apikey_granted` (
  `id` int NOT NULL AUTO_INCREMENT,
  `apikey` varchar(100) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `added_date` datetime DEFAULT NULL,
  `is_delete` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apikey_granted`
--

LOCK TABLES `apikey_granted` WRITE;
/*!40000 ALTER TABLE `apikey_granted` DISABLE KEYS */;
INSERT INTO `apikey_granted` VALUES (1,'4cc905243ea781a473946acc24841e468ba09e39efd36ef0','DITS','2021-07-16 00:00:00',0),(2,'1112212112122','Thep',NULL,0);
/*!40000 ALTER TABLE `apikey_granted` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `machine_config`
--

DROP TABLE IF EXISTS `machine_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `machine_config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `site` varchar(45) DEFAULT NULL,
  `weightmachine` varchar(45) DEFAULT NULL,
  `ftp_url` varchar(200) DEFAULT NULL,
  `ftp_username` varchar(100) DEFAULT NULL,
  `ftp_password` varchar(100) DEFAULT NULL,
  `ftp_port` int DEFAULT NULL,
  `ftp_filepath` varchar(200) DEFAULT NULL,
  `ftp_filename` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machine_config`
--

LOCK TABLES `machine_config` WRITE;
/*!40000 ALTER TABLE `machine_config` DISABLE KEYS */;
INSERT INTO `machine_config` VALUES (1,'PACM','1','192.168.100.3','hmi','12345678',21,'/HMI/HMI-000/History/CSV','HMI1.csv'),(2,'PACM','2','192.168.100.6','hmi','12345678',21,'/HMI/HMI-000/History/CSV','HMI2.csv'),(3,'PACM','3','172.16.88.1','weight','Pacm@1234',21,'/zzz','HMI1.csv');
/*!40000 ALTER TABLE `machine_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usage_log`
--

DROP TABLE IF EXISTS `usage_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usage_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `apikey` varchar(100) DEFAULT NULL,
  `use_date_time` datetime DEFAULT NULL,
  `detail` varchar(100) DEFAULT NULL,
  `numbytes` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-05 13:56:13
