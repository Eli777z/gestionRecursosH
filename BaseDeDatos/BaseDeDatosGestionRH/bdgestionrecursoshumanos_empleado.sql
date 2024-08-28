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
-- Table structure for table `empleado`
--

DROP TABLE IF EXISTS `empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_empleado` int NOT NULL,
  `usuario_id` int NOT NULL,
  `informacion_laboral_id` int NOT NULL,
  `cat_nivel_estudio_id` int DEFAULT NULL,
  `parametro_formato_id` int DEFAULT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(60) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `edad` int DEFAULT NULL,
  `sexo` varchar(12) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `estado_civil` varchar(12) DEFAULT NULL,
  `colonia` varchar(50) DEFAULT NULL,
  `calle` varchar(85) DEFAULT NULL,
  `numero_casa` varchar(4) DEFAULT NULL,
  `codigo_postal` int DEFAULT NULL,
  `nombre_contacto_emergencia` varchar(90) DEFAULT NULL,
  `relacion_contacto_emergencia` varchar(25) DEFAULT NULL,
  `telefono_contacto_emergencia` varchar(15) DEFAULT NULL,
  `institucion_educativa` varchar(65) DEFAULT NULL,
  `titulo_grado` varchar(65) DEFAULT NULL,
  `profesion` varchar(15) DEFAULT NULL,
  `curp` varchar(18) DEFAULT NULL,
  `rfc` varchar(13) DEFAULT NULL,
  `nss` varchar(13) DEFAULT NULL,
  `municipio` varchar(45) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `expediente_medico_id` int DEFAULT NULL,
  PRIMARY KEY (`id`,`usuario_id`,`informacion_laboral_id`),
  KEY `fk_usuario_idx` (`usuario_id`),
  KEY `fk_informacion_laboral_idx` (`informacion_laboral_id`),
  KEY `fk_cat_nivel_estudio_idx` (`cat_nivel_estudio_id`),
  KEY `fk_expediente_medico_empleado_idx` (`expediente_medico_id`),
  CONSTRAINT `fk_cat_nivel_estudio` FOREIGN KEY (`cat_nivel_estudio_id`) REFERENCES `cat_nivel_estudio` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_expediente_medico_empleado` FOREIGN KEY (`expediente_medico_id`) REFERENCES `expediente_medico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado`
--

LOCK TABLES `empleado` WRITE;
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
INSERT INTO `empleado` VALUES (68,988,78,77,NULL,NULL,'Medico Prueba','Medio Empresa',NULL,NULL,NULL,'C:\\inetpub\\gestionRH\\runtime/empleados/Medico Prueba_Medio Empresa/foto_empleado/medico.png',NULL,'ghosteli@hotmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'DR.',NULL,NULL,NULL,NULL,NULL,13),(72,527,82,81,2,NULL,'Este Es','Un Empleado','1996-02-29',28,'Femenino','C:\\inetpub\\gestionRH\\runtime/empleados/Este Es_Un Empleado/foto_empleado/si.png','4521901684','ghosteli@hotmail.com','Soltero/a','unooo','siii','45',60137,'','','','UNA',NULL,'LIC.','PEGJ850315HJCRRN07','SOCC001112MU6','12345678978','Uruapan','MICHOACÁN',17),(90,650,235,234,NULL,NULL,'Oscar Giovanni','Carillo Ibarrola',NULL,NULL,NULL,'C:\\inetpub\\gestionRH\\runtime/empleados/Oscar Giovanni_Carillo Ibarrola/foto_empleado/descarga (1).png',NULL,'ghosteli@hotmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ING.',NULL,NULL,NULL,NULL,NULL,166),(95,9,242,241,NULL,NULL,'Alberto','Aviles Garcia',NULL,NULL,NULL,'C:\\inetpub\\gestionRH\\runtime/empleados/Alberto_Aviles Garcia/foto_empleado/oooo.jpg',NULL,'ghosteli@hotmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'LIC.',NULL,NULL,NULL,NULL,NULL,173),(98,241,251,250,NULL,NULL,'Sergio Eli','Peña Paniagua',NULL,NULL,NULL,'C:\\inetpub\\gestionRH\\runtime/empleados/Sergio Eli_Peña Paniagua/foto_empleado/images.png',NULL,'ghosteli@hotmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'LIC.',NULL,NULL,NULL,NULL,NULL,181),(99,719,252,251,NULL,NULL,'Victor Rafael','Aguilar Naranjo',NULL,NULL,NULL,'C:\\inetpub\\gestionRH\\runtime/empleados/Victor Rafael_Aguilar Naranjo/foto_empleado/images (1).png',NULL,'ghosteli@hotmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ING.',NULL,NULL,NULL,NULL,NULL,182),(100,45,253,252,NULL,NULL,'Uribe','Macias Aleman',NULL,NULL,NULL,'C:\\inetpub\\gestionRH\\runtime/empleados/Uribe_Macias Aleman/foto_empleado/images.png',NULL,'ghosteli@hotmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ING.',NULL,NULL,NULL,NULL,NULL,183),(101,457,254,253,6,NULL,'Jose Antonio','Vivanco Medina','1985-08-18',38,'Masculino','C:\\inetpub\\gestionRH\\runtime/empleados/Jose Antonio_Vivanco Medina/foto_empleado/minnn.png','4521941187','ghosteli@hotmail.com','Soltero/a','Hacienda San','Manzana','#14',60137,'Arturo Lopez Perez','Compañero/a de trabajo','4521751676','Instituto de Ejemplo',NULL,'LIC.','SOCC001112HNELHRA2','SOCC001112MU6','18081985111','Uruapan','MICHOACÁN',184);
/*!40000 ALTER TABLE `empleado` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:55:56
