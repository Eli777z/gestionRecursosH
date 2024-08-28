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
-- Table structure for table `parametro_formato`
--

DROP TABLE IF EXISTS `parametro_formato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parametro_formato` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo_permiso` varchar(255) DEFAULT NULL,
  `limite_anual` int DEFAULT NULL,
  `cat_tipo_contrato_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cat_tipo_contratp_parametro_formato_idx` (`cat_tipo_contrato_id`),
  CONSTRAINT `fk_cat_tipo_contratp_parametro_formato` FOREIGN KEY (`cat_tipo_contrato_id`) REFERENCES `cat_tipo_contrato` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parametro_formato`
--

LOCK TABLES `parametro_formato` WRITE;
/*!40000 ALTER TABLE `parametro_formato` DISABLE KEYS */;
INSERT INTO `parametro_formato` VALUES (6,'PERMISO FUERA DEL TRABAJO',9,1),(7,'PERMISO FUERA DEL TRABAJO',10,2),(8,'COMISION ESPECIAL',14,2),(9,'COMISION ESPECIAL',15,1),(10,'CAMBIO DE DIA LABORAL',1,1),(11,'CAMBIO DE DIA LABORAL',9,2),(12,'CAMBIO DE HORARIO DE TRABAJO',5,2),(13,'CAMBIO DE HORARIO DE TRABAJO',6,1),(14,'PERMISO ECONOMICO',11,1),(15,'PERMISO ECONOMICO',12,2),(16,'PERMISO SIN GOCE DE SUELDO',9,1),(17,'PERMISO SIN GOCE DE SUELDO',8,2),(18,'CAMBIO DE PERIODO VACACIONAL',6,1),(19,'COMISION ESPECIAL',8,3),(20,'PERMISO FUERA DEL TRABAJO',7,3);
/*!40000 ALTER TABLE `parametro_formato` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:55:59
