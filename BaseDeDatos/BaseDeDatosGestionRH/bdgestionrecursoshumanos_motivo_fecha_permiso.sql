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
-- Table structure for table `motivo_fecha_permiso`
--

DROP TABLE IF EXISTS `motivo_fecha_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `motivo_fecha_permiso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha_permiso` date DEFAULT NULL,
  `motivo` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=327 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motivo_fecha_permiso`
--

LOCK TABLES `motivo_fecha_permiso` WRITE;
/*!40000 ALTER TABLE `motivo_fecha_permiso` DISABLE KEYS */;
INSERT INTO `motivo_fecha_permiso` VALUES (315,'2024-08-13','<p>Salida fuera</p>'),(316,'2024-08-19','<p>MOTIVOS PERSONALES</p>'),(317,'2024-08-19','<p>MANTENIMIENTO EN OTRA AREA</p>'),(318,'2024-08-19','<p>SITUACIONES PERSONALES</p>'),(319,'2024-08-19','<p>MOTIVOS PERSONALES</p>'),(320,'2024-08-19','<p>MOTIVOS</p>'),(321,'2024-08-19','<p>MOTIVOS PERSONALES</p>'),(322,'2024-08-20','<p>MOTIVO DE AREA</p>'),(323,'2024-08-27','<p>MOTIVO LABORAL</p>'),(324,'2024-08-28','<p>ALGUNO</p>'),(325,'2024-08-28','<p>familiares</p>'),(326,'2024-08-28','<p>uno</p>');
/*!40000 ALTER TABLE `motivo_fecha_permiso` ENABLE KEYS */;
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
