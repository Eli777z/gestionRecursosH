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
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int NOT NULL,
  `cat_tipo_documento_id` int NOT NULL,
  `nombre` varchar(85) DEFAULT NULL,
  `ruta` varchar(255) NOT NULL,
  `fecha_subida` date DEFAULT NULL,
  `observacion` text,
  PRIMARY KEY (`id`,`empleado_id`),
  KEY `fk_empleado_idx` (`empleado_id`),
  KEY `fk_tipo_documento_idx` (`cat_tipo_documento_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento`
--

LOCK TABLES `documento` WRITE;
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
INSERT INTO `documento` VALUES (39,14,1,'CURP','C:\\inetpub\\gestionRH\\runtime/empleados/Sergio Eli_Peña Paniagua/documentos/kumi.pdf','2024-05-13',NULL),(40,14,4,'quiensabe','C:\\inetpub\\gestionRH\\runtime/empleados/Sergio Eli_Peña Paniagua/documentos/diagrama_BD_RH.pdf','2024-05-13',NULL),(43,14,2,'NSS','C:\\inetpub\\gestionRH\\runtime/empleados/Sergio Eli_Peña Paniagua/documentos/diagrama_BD_RH.pdf','2024-05-20',NULL),(53,36,1,'','C:\\inetpub\\gestionRH\\runtime/empleados/Otro Vacaciones_Prueba E/documentos/cronograma_seguimiento_residencia.pdf','2024-06-17','una'),(54,36,3,'','C:\\inetpub\\gestionRH\\runtime/empleados/Otro Vacaciones_Prueba E/documentos/n.pdf','2024-06-17','otra'),(55,36,6,'si','C:\\inetpub\\gestionRH\\runtime/empleados/Otro Vacaciones_Prueba E/documentos/a.pdf','2024-06-17','hey'),(56,36,7,'siva','C:\\inetpub\\gestionRH\\runtime/empleados/Otro Vacaciones_Prueba E/documentos/ANTEPROYECTO PORTADA.pdf','2024-06-17','aaa'),(57,36,2,'NSS','C:\\inetpub\\gestionRH\\runtime/empleados/Otro Vacaciones_Prueba E/documentos/comision_especial.pdf','2024-06-17','nss'),(60,67,2,'NSS','C:\\inetpub\\gestionRH\\runtime/empleados/Empleado Exp_Medico Prueba/documentos/permiso_economico_31_1718299915.pdf','2024-06-26','unas'),(61,67,3,'RFC','C:\\inetpub\\gestionRH\\runtime/empleados/Empleado Exp_Medico Prueba/documentos/portadaEscaneada.pdf','2024-07-02','<ol><li><strong>unass<em></em></strong><span class=\"redactor-invisible-space\"></span></li></ol>'),(62,67,3,'RFC','C:\\inetpub\\gestionRH\\runtime/empleados/Empleado Exp_Medico Prueba/documentos/portadaEscaneada.pdf','2024-07-03','<p><strong>Este documeto</strong></p><p>Tiene</p><p>asi</p><p>y</p><p>amamkmskmskmkmsmks</p>'),(65,89,1,'CURP','C:\\inetpub\\gestionRH\\runtime/empleados/Samuel_Perez Sanchez/documentos/plantilla_comision_especial.pdf','2024-07-31','<p><strong>CURP</strong></p>'),(66,101,1,'CURP','C:\\inetpub\\gestionRH\\runtime/empleados/Jose Antonio_Vivanco Medina/documentos/CURP_PEPS010407HMNXNRA9.pdf','2024-08-14','<p><em><span style=\"color: rgb(247, 218, 100);\">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus hic ea dolor labore ipsa earum. At optio atque, cum reiciendis, non praesentium quas neque dolorum adipisci nam doloremque dolore voluptatem.</span></em></p>'),(67,90,8,'CURRICULUM','C:\\inetpub\\gestionRH\\runtime/empleados/Oscar Giovanni_Carillo Ibarrola/documentos/Reporte_Tiempo_Extra_General_Este Es Un Empleado.pdf','2024-08-28','<p>UNA</p>');
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:56:07
