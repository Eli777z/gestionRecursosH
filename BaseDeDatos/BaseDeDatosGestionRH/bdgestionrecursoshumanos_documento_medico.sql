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
-- Table structure for table `documento_medico`
--

DROP TABLE IF EXISTS `documento_medico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento_medico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(85) DEFAULT NULL,
  `ruta` varchar(255) DEFAULT NULL,
  `fecha_subida` date DEFAULT NULL,
  `observacion` text,
  `empleado_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_doc_medico_idx` (`empleado_id`),
  CONSTRAINT `fk_empleado_doc_medico` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento_medico`
--

LOCK TABLES `documento_medico` WRITE;
/*!40000 ALTER TABLE `documento_medico` DISABLE KEYS */;
INSERT INTO `documento_medico` VALUES (2,'ESTUDIOO DE','C:\\inetpub\\gestionRH\\runtime/empleados/Empleado Exp_Medico Prueba/documentos_medicos/bd.pdf','2024-07-18','<p><strong>SI AJA</strong></p>',NULL),(5,'estudio de','C:\\inetpub\\gestionRH\\runtime/empleados/Samuel_Perez Sanchez/documentos_medicos/Permiso_Fuera_Trabajo_Este Es Un Empleado.pdf','2024-07-31','<p><span style=\"color: rgb(184, 49, 47);\">estudio de sangre</span></p>',NULL),(6,'NSS','C:\\inetpub\\gestionRH\\runtime/empleados/Jose Antonio_Vivanco Medina/documentos_medicos/CURP_PEPS010407HMNXNRA9.pdf','2024-08-16','<p><strong><span style=\"font-size: 18px; color: rgb(85, 57, 130);\">N&uacute;mero de seguro social.</span></strong></p>',101);
/*!40000 ALTER TABLE `documento_medico` ENABLE KEYS */;
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
