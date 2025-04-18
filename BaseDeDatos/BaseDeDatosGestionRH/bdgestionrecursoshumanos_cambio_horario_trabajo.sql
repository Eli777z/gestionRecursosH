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
-- Table structure for table `cambio_horario_trabajo`
--

DROP TABLE IF EXISTS `cambio_horario_trabajo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cambio_horario_trabajo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `solicitud_id` int DEFAULT NULL,
  `motivo_fecha_permiso_id` int DEFAULT NULL,
  `turno` varchar(12) DEFAULT NULL,
  `horario_inicio` time DEFAULT NULL,
  `horario_fin` time DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_termino` date DEFAULT NULL,
  `nombre_jefe_departamento` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_cambio_horario_idx` (`empleado_id`),
  KEY `fk_empleado_solicitud_cambio_horario_idx` (`solicitud_id`),
  KEY `fk_motivo_fecha_cambio_horario_idx` (`motivo_fecha_permiso_id`),
  CONSTRAINT `fk_empleado_cambio_horario` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_motivo_fecha_cambio_horario` FOREIGN KEY (`motivo_fecha_permiso_id`) REFERENCES `motivo_fecha_permiso` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_cambio_horario` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cambio_horario_trabajo`
--

LOCK TABLES `cambio_horario_trabajo` WRITE;
/*!40000 ALTER TABLE `cambio_horario_trabajo` DISABLE KEYS */;
INSERT INTO `cambio_horario_trabajo` VALUES (14,72,443,320,'MATUTINO','08:00:00','15:00:00','2024-08-19','2024-08-23',NULL);
/*!40000 ALTER TABLE `cambio_horario_trabajo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:56:02
