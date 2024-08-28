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
-- Table structure for table `contrato_para_personal_eventual`
--

DROP TABLE IF EXISTS `contrato_para_personal_eventual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contrato_para_personal_eventual` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_contrato` int DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_termino` date DEFAULT NULL,
  `duracion` int DEFAULT NULL,
  `modalidad` varchar(85) DEFAULT NULL,
  `actividades_realizar` varchar(255) DEFAULT NULL,
  `origen_contrato` varchar(255) DEFAULT NULL,
  `empleado_id` int DEFAULT NULL,
  `solicitud_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_contrato_eventual_idx` (`empleado_id`),
  KEY `fk_solicitud_contrato_eventual_idx` (`solicitud_id`),
  CONSTRAINT `fk_empleado_contrato_eventual` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_contrato_eventual` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contrato_para_personal_eventual`
--

LOCK TABLES `contrato_para_personal_eventual` WRITE;
/*!40000 ALTER TABLE `contrato_para_personal_eventual` DISABLE KEYS */;
INSERT INTO `contrato_para_personal_eventual` VALUES (1,1,'2024-06-03','2024-06-30',28,'LISTA DE RAYA','<p>ACTUALIZACI&Oacute;N APP &nbsp;REPORTES &nbsp;Y DESARROLLO APP IOS</p>','<p>DESARROLLO DE APP</p>',98,427),(2,1,'2024-06-03','2024-06-30',28,'LISTA DE RAYA','<p>ACTUALIZACI&Oacute;N APP &nbsp;REPORTES &nbsp;Y DESARROLLO APP IOS</p>','<p>DESARROLLO DE APP</p>',72,428),(3,2,'2024-06-03','2024-06-30',28,'LISTA DE RAYA','<p>ACTUALIZACI&Oacute;N APP &nbsp;REPORTES &nbsp;Y DESARROLLO APP IOS</p>','<p>DESARROLLO DE APP</p>',72,429),(4,1,'2024-06-03','2024-06-30',28,'LISTA DE RAYA','<p>MUCHAS QUE HACER</p>','<p>SE OCUPA</p>',101,430),(5,3,'2024-08-16','2024-08-31',20,'LISTA DE RAYA','<p>UNAAAS</p>','<p>SE NECESITABA</p>',72,432),(6,2,'2024-06-03','2024-06-30',28,'LISTA DE RAYA','<p>UNAS</p>','<p>POR UN MOTIVO</p>',101,433),(7,3,'2024-08-03','2024-08-30',28,'LISTA DE RAYA','<p>CONTINUACION</p>','<p>SIGUE</p>',101,434),(8,4,'2024-09-03','2024-09-30',28,'LISTA DE RAYA','<p>CONTINUACION</p>','<p>SIGUE</p>',101,435),(9,5,'2024-10-03','2024-10-30',28,'LISTA DE RAYA','<p>CONTINUACION</p>','<p>SIGUE</p>',101,436),(10,4,'2024-11-03','2024-11-30',28,'LISTA DE RAYA','<p>DIFERENTES ACTIVIDADES</p>','<p>NECESIDAD DEL AREA</p>',72,446),(11,1,'2024-08-03','2024-08-31',29,'LISTA DE RAYA','<p>TAL COSA</p>','<p>POR TAL RAZON</p>',99,483);
/*!40000 ALTER TABLE `contrato_para_personal_eventual` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:56:03
