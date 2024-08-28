CREATE DATABASE  IF NOT EXISTS `bdgestionrecursoshumanos` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `bdgestionrecursoshumanos`;
-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: bdgestionrecursoshumanos
-- ------------------------------------------------------
-- Server version	8.0.36

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
-- Table structure for table `antecedente_hereditario`
--

DROP TABLE IF EXISTS `antecedente_hereditario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `antecedente_hereditario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `expediente_medico_id` int DEFAULT NULL,
  `cat_antecedente_hereditario_id` int DEFAULT NULL,
  `parentezco` enum('Abuelos','Hermanos','Padre','Madre') DEFAULT NULL,
  `observacion` text,
  PRIMARY KEY (`id`),
  KEY `fk_antecedente_ex_medico_idx` (`expediente_medico_id`),
  KEY `fk_antecedente_cat_ant_hereditario_idx` (`cat_antecedente_hereditario_id`),
  CONSTRAINT `fk_antecedente_cat_ant_hereditario` FOREIGN KEY (`cat_antecedente_hereditario_id`) REFERENCES `cat_antecedente_hereditario` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_antecedente_ex_medico` FOREIGN KEY (`expediente_medico_id`) REFERENCES `expediente_medico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antecedente_hereditario`
--

LOCK TABLES `antecedente_hereditario` WRITE;
/*!40000 ALTER TABLE `antecedente_hereditario` DISABLE KEYS */;
INSERT INTO `antecedente_hereditario` VALUES (1,NULL,NULL,NULL,NULL),(7,NULL,NULL,NULL,NULL),(61,6,9,'Abuelos','mmmmmmas'),(62,6,9,'Hermanos','mmmmmmas'),(63,6,13,'Madre','mmmmmmas'),(77,7,9,'Abuelos','mlmlmlmlmlmlmm'),(78,7,10,'Hermanos','mlmlmlmlmlmlmm'),(79,7,11,'Hermanos','mlmlmlmlmlmlmm'),(80,7,15,'Abuelos','mlmlmlmlmlmlmm'),(81,NULL,NULL,NULL,NULL),(85,8,9,'Abuelos','sisi'),(86,8,10,'Abuelos','sisi'),(87,8,11,'Abuelos','sisi'),(88,NULL,NULL,NULL,NULL),(89,NULL,NULL,NULL,NULL),(90,NULL,NULL,NULL,NULL),(91,NULL,NULL,NULL,NULL),(108,NULL,NULL,NULL,NULL),(109,NULL,NULL,NULL,NULL),(110,NULL,NULL,NULL,NULL),(111,NULL,NULL,NULL,NULL),(116,NULL,NULL,NULL,NULL),(129,NULL,NULL,NULL,NULL),(132,NULL,NULL,NULL,NULL),(135,NULL,NULL,NULL,NULL),(137,NULL,NULL,NULL,NULL),(140,NULL,NULL,NULL,NULL),(141,NULL,NULL,NULL,NULL),(169,NULL,NULL,NULL,NULL),(170,NULL,NULL,NULL,NULL),(171,NULL,NULL,NULL,NULL),(175,NULL,NULL,NULL,NULL),(178,NULL,NULL,NULL,NULL),(179,NULL,NULL,NULL,NULL),(180,NULL,NULL,NULL,NULL),(181,NULL,NULL,NULL,NULL),(267,NULL,NULL,NULL,NULL),(268,NULL,NULL,NULL,NULL),(269,NULL,NULL,NULL,NULL),(274,NULL,NULL,NULL,NULL),(280,NULL,NULL,NULL,NULL),(281,NULL,NULL,NULL,NULL),(283,NULL,NULL,NULL,NULL),(285,17,NULL,NULL,NULL),(287,166,NULL,NULL,NULL),(289,166,9,'Abuelos','NOOOO'),(290,166,10,'Hermanos','NOOOO'),(291,166,11,'Padre','NOOOO'),(292,166,9,'Abuelos','NOOOO'),(293,166,10,'Hermanos','NOOOO'),(294,166,11,'Padre','NOOOO'),(295,166,13,'Madre','NOOOO'),(296,166,13,'Padre','NOOOO'),(297,166,9,'Abuelos','NOOOO'),(298,166,9,'Madre','NOOOO'),(299,166,10,'Hermanos','NOOOO'),(300,166,11,'Padre','NOOOO'),(301,166,13,'Madre','NOOOO'),(302,166,13,'Padre','NOOOO'),(303,166,9,'Abuelos','NOOOO'),(304,166,9,'Madre','NOOOO'),(305,166,10,'Hermanos','NOOOO'),(306,166,11,'Padre','NOOOO'),(307,166,13,'Madre','NOOOO'),(308,166,13,'Padre','NOOOO'),(309,166,9,'Abuelos','NOOOO'),(310,166,9,'Madre','NOOOO'),(311,166,10,'Hermanos','NOOOO'),(312,166,11,'Padre','NOOOO'),(313,166,13,'Madre','NOOOO'),(314,166,13,'Padre','NOOOO'),(315,181,NULL,NULL,NULL),(316,182,NULL,NULL,NULL),(317,183,NULL,NULL,NULL),(318,184,NULL,NULL,NULL),(319,184,9,'Abuelos',''),(320,184,10,'Hermanos',''),(321,184,9,'Abuelos','Una observación'),(322,184,10,'Hermanos','Una observación'),(323,173,NULL,NULL,NULL),(324,13,NULL,NULL,NULL),(325,182,9,'Abuelos','unas'),(326,182,10,'Madre','unas'),(327,182,12,'Padre','unas');
/*!40000 ALTER TABLE `antecedente_hereditario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:56:04
