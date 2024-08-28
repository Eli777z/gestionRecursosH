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
-- Table structure for table `informacion_laboral`
--

DROP TABLE IF EXISTS `informacion_laboral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `informacion_laboral` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cat_tipo_contrato_id` int DEFAULT NULL,
  `cat_puesto_id` int DEFAULT NULL,
  `cat_departamento_id` int DEFAULT NULL,
  `vacaciones_id` int DEFAULT NULL,
  `cat_direccion_id` int DEFAULT NULL,
  `junta_gobierno_id` int DEFAULT NULL,
  `fecha_ingreso` date NOT NULL,
  `horario_laboral_inicio` time DEFAULT NULL,
  `horario_laboral_fin` time DEFAULT NULL,
  `horario_dias_trabajo` varchar(150) DEFAULT NULL,
  `cat_dpto_cargo_id` int DEFAULT NULL,
  `dias_laborales` varchar(65) DEFAULT NULL,
  `numero_cuenta` varchar(25) DEFAULT NULL,
  `salario` float DEFAULT NULL,
  `horas_extras` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cat_departamento_idx` (`cat_departamento_id`),
  KEY `fk_cat_direccion_idx` (`cat_direccion_id`),
  KEY `fk_cat_puesto_idx` (`cat_puesto_id`),
  KEY `fk_cat_contrato_idx` (`cat_tipo_contrato_id`),
  KEY `fk_junta_gobierno_idx` (`junta_gobierno_id`),
  KEY `fk_cat_dpto_idx` (`cat_dpto_cargo_id`),
  KEY `fk_vacaciones_informacion_laboral_idx` (`vacaciones_id`),
  CONSTRAINT `fk_cat_contrato` FOREIGN KEY (`cat_tipo_contrato_id`) REFERENCES `cat_tipo_contrato` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cat_departamento` FOREIGN KEY (`cat_departamento_id`) REFERENCES `cat_departamento` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cat_direccion` FOREIGN KEY (`cat_direccion_id`) REFERENCES `cat_direccion` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cat_dpto` FOREIGN KEY (`cat_dpto_cargo_id`) REFERENCES `cat_dpto_cargo` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cat_puesto` FOREIGN KEY (`cat_puesto_id`) REFERENCES `cat_puesto` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_junta_gobierno` FOREIGN KEY (`junta_gobierno_id`) REFERENCES `junta_gobierno` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_vacaciones_informacion_laboral` FOREIGN KEY (`vacaciones_id`) REFERENCES `vacaciones` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=254 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `informacion_laboral`
--

LOCK TABLES `informacion_laboral` WRITE;
/*!40000 ALTER TABLE `informacion_laboral` DISABLE KEYS */;
INSERT INTO `informacion_laboral` VALUES (21,1,12,NULL,NULL,1,NULL,'2024-05-10','11:45:00','11:45:00',NULL,NULL,NULL,NULL,NULL,NULL),(23,2,32,NULL,NULL,1,NULL,'2024-05-10','11:30:00','11:30:00',NULL,NULL,NULL,NULL,NULL,NULL),(29,NULL,31,NULL,NULL,1,NULL,'2024-05-15','11:30:00','11:30:00',NULL,NULL,NULL,NULL,NULL,NULL),(30,NULL,NULL,NULL,NULL,2,NULL,'2024-05-16',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(31,2,16,NULL,NULL,2,NULL,'2024-05-17','10:00:00','20:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(32,1,6,NULL,NULL,2,NULL,'2024-05-17','10:30:00','10:30:00',NULL,NULL,NULL,NULL,NULL,NULL),(33,NULL,NULL,NULL,NULL,2,NULL,'2024-05-17',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(34,1,16,NULL,NULL,2,NULL,'2002-05-09','13:15:00','13:15:00',NULL,NULL,NULL,NULL,NULL,NULL),(38,NULL,NULL,NULL,2,1,NULL,'2024-05-27','11:30:00','11:30:00',NULL,NULL,NULL,NULL,NULL,NULL),(42,2,NULL,NULL,6,1,NULL,'1985-03-29',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(44,2,6,NULL,8,2,NULL,'2006-02-28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(50,2,NULL,NULL,14,2,NULL,'2024-06-04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(52,2,NULL,NULL,16,1,NULL,'2024-06-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(53,2,NULL,NULL,17,1,NULL,'2024-06-04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(54,2,NULL,NULL,18,2,NULL,'2024-06-04',NULL,NULL,NULL,NULL,'Lunes, Martes, Miércoles, Jueves, Viernes, Sábado','422121511',9555.04,NULL),(77,1,16,40,41,1,NULL,'2024-07-03',NULL,NULL,NULL,7,NULL,NULL,NULL,NULL),(79,2,1,58,43,4,NULL,'2024-07-04',NULL,NULL,NULL,24,NULL,NULL,NULL,NULL),(81,2,10,44,45,1,41,'2019-08-23','01:00:00','09:00:00',NULL,11,'Lunes, Martes, Miércoles','123456789123456789',8000,4),(98,1,1,41,62,2,NULL,'2024-07-26',NULL,NULL,NULL,7,NULL,'',NULL,NULL),(134,1,2,62,98,1,NULL,'2024-07-24',NULL,NULL,NULL,11,NULL,'',NULL,NULL),(232,1,30,63,196,1,NULL,'2000-01-06',NULL,NULL,NULL,11,NULL,'',102,NULL),(234,1,32,41,198,1,NULL,'2019-05-01','08:00:00','15:00:00',NULL,8,'Lunes, Martes, Miércoles, Viernes','',NULL,10),(241,1,34,45,205,1,NULL,'1996-05-01',NULL,NULL,NULL,12,NULL,NULL,NULL,NULL),(250,1,16,52,214,2,NULL,'2018-02-12',NULL,NULL,NULL,19,NULL,NULL,NULL,NULL),(251,3,35,44,215,1,41,'2018-12-16','10:38:00','15:38:00',NULL,11,'Lunes, Martes, Miércoles, Jueves','123456789123456789',20000,3),(252,2,31,40,216,1,NULL,'2021-02-03',NULL,NULL,NULL,7,NULL,NULL,NULL,NULL),(253,1,35,44,217,1,41,'2008-07-16','08:00:00','15:00:00',NULL,11,'Lunes, Martes, Miércoles, Jueves, Viernes, Sábado','123456789123456789',8000,2);
/*!40000 ALTER TABLE `informacion_laboral` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:56:06
