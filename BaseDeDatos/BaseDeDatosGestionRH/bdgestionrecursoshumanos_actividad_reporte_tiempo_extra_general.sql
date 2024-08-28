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
-- Table structure for table `actividad_reporte_tiempo_extra_general`
--

DROP TABLE IF EXISTS `actividad_reporte_tiempo_extra_general`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actividad_reporte_tiempo_extra_general` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_empleado` int DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `actividad` text,
  `reporte_tiempo_extra_general_id` int DEFAULT NULL,
  `no_horas` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reporte_tiempo_extra_gral_idx` (`reporte_tiempo_extra_general_id`),
  CONSTRAINT `fk_reporte_tiempo_extra_gral` FOREIGN KEY (`reporte_tiempo_extra_general_id`) REFERENCES `reporte_tiempo_extra_general` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actividad_reporte_tiempo_extra_general`
--

LOCK TABLES `actividad_reporte_tiempo_extra_general` WRITE;
/*!40000 ALTER TABLE `actividad_reporte_tiempo_extra_general` DISABLE KEYS */;
INSERT INTO `actividad_reporte_tiempo_extra_general` VALUES (1,NULL,'2024-08-26','10:51:00','11:51:00','uuu',4,1),(2,NULL,'2024-08-26','11:52:00','12:52:00','mmm',4,1),(3,12,'2024-08-26','11:24:00','12:24:00','UNA',5,1),(5,12,'2024-08-26','11:34:00','12:34:00','una',7,1),(8,12,'2024-08-26','11:36:00','13:36:00','si',10,2),(9,13,'2024-08-27','10:36:00','13:36:00','no',10,3),(10,9,'2024-08-26','12:10:00','13:10:00','tal',12,1),(11,650,'2024-08-19','14:10:00','16:10:00','si',12,2),(13,719,'2024-08-26','13:59:00','14:59:00','no',15,1),(14,650,'2024-08-26','14:59:00','15:59:00','si',15,1),(15,650,'2024-08-14','11:11:00','13:11:00','UNA MAS',16,2),(16,650,'2024-08-22','12:11:00','16:11:00','OTRA MAS',16,4),(17,719,'2024-08-27','11:11:00','14:11:00','HIZO TAL',16,3);
/*!40000 ALTER TABLE `actividad_reporte_tiempo_extra_general` ENABLE KEYS */;
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
