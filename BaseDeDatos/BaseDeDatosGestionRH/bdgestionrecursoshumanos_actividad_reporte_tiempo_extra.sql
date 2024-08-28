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
-- Table structure for table `actividad_reporte_tiempo_extra`
--

DROP TABLE IF EXISTS `actividad_reporte_tiempo_extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actividad_reporte_tiempo_extra` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `actividad` text,
  `reporte_tiempo_extra_id` int DEFAULT NULL,
  `no_horas` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reporte_tiempo_ex_act_idx` (`reporte_tiempo_extra_id`),
  CONSTRAINT `fk_reporte_tiempo_ex_act` FOREIGN KEY (`reporte_tiempo_extra_id`) REFERENCES `reporte_tiempo_extra` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actividad_reporte_tiempo_extra`
--

LOCK TABLES `actividad_reporte_tiempo_extra` WRITE;
/*!40000 ALTER TABLE `actividad_reporte_tiempo_extra` DISABLE KEYS */;
INSERT INTO `actividad_reporte_tiempo_extra` VALUES (24,'2024-08-19','09:04:00','10:04:00','Realizo cierta actividad',12,1),(25,'2024-08-18','10:04:00','11:04:00','Realizo otra actividad',12,1),(26,'2024-08-13','13:17:00','14:17:00','Realizo tal cosa',13,1),(27,'2024-08-20','13:23:00','15:23:00','Se realizo lo otro',13,2),(28,'2024-08-21','09:55:00','10:55:00','una',14,1),(29,'2024-08-21','10:55:00','11:55:00','otra',14,1),(30,'2024-08-21','09:55:00','10:55:00','una',15,NULL),(31,'2024-08-27','11:09:00','13:09:00','UNA',16,2),(32,'2024-08-28','13:09:00','15:09:00','OTRA',16,2),(33,'2024-08-28','10:26:00','12:26:00','UNA ACTIVIDAD',17,2),(34,'2024-08-29','11:27:00','13:27:00','OTRA',17,2);
/*!40000 ALTER TABLE `actividad_reporte_tiempo_extra` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:55:55
