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
-- Table structure for table `comision_especial`
--

DROP TABLE IF EXISTS `comision_especial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comision_especial` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `solicitud_id` int DEFAULT NULL,
  `motivo_fecha_permiso_id` int DEFAULT NULL,
  `nombre_jefe_departamento` varchar(90) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_comision_idx` (`empleado_id`),
  KEY `fk_solicitud_comision_idx` (`solicitud_id`),
  KEY `fk_motivo_fecha_permiso_comision_idx` (`motivo_fecha_permiso_id`),
  CONSTRAINT `fk_empleado_comision` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_motivo_fecha_permiso_comision` FOREIGN KEY (`motivo_fecha_permiso_id`) REFERENCES `motivo_fecha_permiso` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_comision` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comision_especial`
--

LOCK TABLES `comision_especial` WRITE;
/*!40000 ALTER TABLE `comision_especial` DISABLE KEYS */;
INSERT INTO `comision_especial` VALUES (55,101,426,315,NULL,'2024-08-13 12:00:02',NULL),(56,72,440,317,NULL,'2024-08-19 10:13:15',NULL),(57,101,442,319,NULL,'2024-08-19 11:15:45',NULL),(58,101,448,322,NULL,'2024-08-20 12:58:50',NULL),(59,90,476,323,NULL,'2024-08-27 11:39:15',NULL),(60,72,479,325,NULL,'2024-08-28 10:21:29',NULL);
/*!40000 ALTER TABLE `comision_especial` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:55:57
