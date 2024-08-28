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
-- Table structure for table `antecedente_no_patologico`
--

DROP TABLE IF EXISTS `antecedente_no_patologico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `antecedente_no_patologico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `p_ejercicio` tinyint DEFAULT NULL,
  `p_deporte` tinyint DEFAULT NULL,
  `p_a_deporte` varchar(65) DEFAULT NULL,
  `p_frecuencia_deporte` varchar(65) DEFAULT NULL,
  `p_dormir_dia` tinyint DEFAULT NULL,
  `p_horas_sueño` float DEFAULT NULL,
  `p_desayuno` tinyint DEFAULT NULL,
  `p_cafe` tinyint DEFAULT NULL,
  `p_refresco` tinyint DEFAULT NULL,
  `p_dieta` tinyint DEFAULT NULL,
  `p_info_dieta` text,
  `p_comidas_x_dia` smallint DEFAULT NULL,
  `p_tazas_x_dia` smallint DEFAULT NULL,
  `observacion_comida` text,
  `p_alcohol` tinyint DEFAULT NULL,
  `p_ex_alcoholico` tinyint DEFAULT NULL,
  `p_edad_alcoholismo` smallint DEFAULT NULL,
  `p_edad_fin_alcoholismo` smallint DEFAULT NULL,
  `p_copas_x_dia` smallint DEFAULT NULL,
  `p_cervezas_x_dia` smallint DEFAULT NULL,
  `p_frecuencia_alcohol` varchar(25) DEFAULT NULL,
  `observacion_alcoholismo` text,
  `p_fuma` tinyint DEFAULT NULL,
  `p_ex_fumador` tinyint DEFAULT NULL,
  `p_fumador_pasivo` tinyint DEFAULT NULL,
  `p_edad_tabaquismo` smallint DEFAULT NULL,
  `p_no_cigarros_x_dia` smallint DEFAULT NULL,
  `p_edad_fin_tabaquismo` smallint DEFAULT NULL,
  `p_frecuencia_tabaquismo` varchar(25) DEFAULT NULL,
  `observacion_tabaquismo` text,
  `p_act_dias_libres` text,
  `p_situaciones` varchar(20) DEFAULT NULL,
  `p_drogas` tinyint DEFAULT NULL,
  `p_ex_adicto` tinyint DEFAULT NULL,
  `p_droga_intravenosa` tinyint DEFAULT NULL,
  `p_edad_droga` smallint DEFAULT NULL,
  `p_edad_fin_droga` smallint DEFAULT NULL,
  `observacion_droga` text,
  `observacion_general` text,
  `datos_vivienda` text,
  `expediente_medico_id` int DEFAULT NULL,
  `p_minutos_x_dia_ejercicio` smallint DEFAULT NULL,
  `observacion_actividad_fisica` text,
  `p_frecuencia_droga` varchar(25) DEFAULT NULL,
  `tipo_sangre` varchar(6) DEFAULT NULL,
  `religion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ex_medico_no_patologico_idx` (`expediente_medico_id`),
  CONSTRAINT `fk_ex_medico_no_patologico` FOREIGN KEY (`expediente_medico_id`) REFERENCES `expediente_medico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antecedente_no_patologico`
--

LOCK TABLES `antecedente_no_patologico` WRITE;
/*!40000 ALTER TABLE `antecedente_no_patologico` DISABLE KEYS */;
INSERT INTO `antecedente_no_patologico` VALUES (1,1,NULL,'una','2po',NULL,5,1,NULL,NULL,1,'la dieta asi y asi',4,NULL,'kkkkk',1,NULL,21,28,4,2,'Moderado','oooooo',1,NULL,1,25,78,NULL,'Intenso','fumo','muchas','Embarazos',1,NULL,1,21,NULL,'nnnnnnnnnnn','el paciente cuenta con','chiquilla',8,20,'mmmmm','Moderado','A+',NULL),(2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,NULL,NULL,NULL,NULL,NULL),(3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,NULL,NULL,NULL,NULL,NULL),(4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,NULL,NULL,NULL,NULL,NULL),(6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13,NULL,NULL,NULL,NULL,NULL),(7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,14,NULL,NULL,NULL,NULL,NULL),(8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,15,NULL,NULL,NULL,NULL,NULL),(9,0,0,'','',0,NULL,0,0,0,0,'',NULL,NULL,'',0,0,NULL,NULL,NULL,NULL,'','',0,0,0,NULL,NULL,NULL,'','','','',0,0,0,NULL,NULL,'','','',16,NULL,'','','','Católica'),(10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,17,NULL,NULL,NULL,NULL,NULL),(26,1,0,'','',0,NULL,0,0,0,0,'',NULL,NULL,'',0,0,NULL,NULL,NULL,NULL,'','',0,0,0,NULL,NULL,NULL,'','','','',0,0,0,NULL,NULL,'','','',166,5,'','','',''),(28,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,173,NULL,NULL,NULL,NULL,NULL),(31,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,181,NULL,NULL,NULL,NULL,NULL),(32,1,1,'futbol','2 x semana',1,6,0,1,1,1,'tal',NULL,3,'',1,0,18,NULL,NULL,NULL,'Casual','',1,0,1,NULL,NULL,NULL,'Moderado','','','',0,1,0,NULL,55,'','','',182,20,'','','O+','Católica'),(33,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,183,NULL,NULL,NULL,NULL,NULL),(34,1,1,'futbol','2 x semana',0,7,1,0,1,0,'',4,4,'<p>Todo normal</p>',1,0,18,NULL,8,7,'Casual','<p>PASA ESTO Y AQUELLO</p>',1,0,0,18,2,NULL,'Casual','<p>Sucede esto y asi</p>','Tiempo familiar','Ninguna',1,0,1,18,NULL,'<p>PRESENTA LO SIGUIENTE</p>','<p><span style=\"background-color: rgb(251, 160, 38);\">EN LO GENERAL TAL Y TAL Y ASI</span></p>','<p>Vive en una vivienda asi, que tiene esto y aquello</p>',184,15,'<p>Algunas</p>','Casual','O+','Católica');
/*!40000 ALTER TABLE `antecedente_no_patologico` ENABLE KEYS */;
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
