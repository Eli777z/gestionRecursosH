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

--
-- Table structure for table `actividad_reporte_tiempo_extra_general`
--

DROP TABLE IF EXISTS `actividad_reporte_tiempo_extra_general`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actividad_reporte_tiempo_extra_general` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_empleado` int DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `actividad` text,
  `reporte_tiempo_extra_general_id` int DEFAULT NULL,
  `no_horas` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reporte_tiempo_extra_gral_idx` (`reporte_tiempo_extra_general_id`),
  CONSTRAINT `fk_reporte_tiempo_extra_gral` FOREIGN KEY (`reporte_tiempo_extra_general_id`) REFERENCES `reporte_tiempo_extra_general` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actividad_reporte_tiempo_extra_general`
--

LOCK TABLES `actividad_reporte_tiempo_extra_general` WRITE;
/*!40000 ALTER TABLE `actividad_reporte_tiempo_extra_general` DISABLE KEYS */;
INSERT INTO `actividad_reporte_tiempo_extra_general` VALUES (1,NULL,'2024-08-26','10:51:00','11:51:00','uuu',4,1),(2,NULL,'2024-08-26','11:52:00','12:52:00','mmm',4,1),(3,12,'2024-08-26','11:24:00','12:24:00','UNA',5,1),(5,12,'2024-08-26','11:34:00','12:34:00','una',7,1),(8,12,'2024-08-26','11:36:00','13:36:00','si',10,2),(9,13,'2024-08-27','10:36:00','13:36:00','no',10,3),(10,9,'2024-08-26','12:10:00','13:10:00','tal',12,1),(11,650,'2024-08-19','14:10:00','16:10:00','si',12,2),(13,719,'2024-08-26','13:59:00','14:59:00','no',15,1),(14,650,'2024-08-26','14:59:00','15:59:00','si',15,1),(15,650,'2024-08-14','11:11:00','13:11:00','UNA MAS',16,2),(16,650,'2024-08-22','12:11:00','16:11:00','OTRA MAS',16,4),(17,719,'2024-08-27','11:11:00','14:11:00','HIZO TAL',16,3);
/*!40000 ALTER TABLE `actividad_reporte_tiempo_extra_general` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alergia`
--

DROP TABLE IF EXISTS `alergia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alergia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `p_alergia` text,
  `expediente_medico_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_alergia_ex_medico_idx` (`expediente_medico_id`),
  CONSTRAINT `fk_alergia_ex_medico` FOREIGN KEY (`expediente_medico_id`) REFERENCES `expediente_medico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alergia`
--

LOCK TABLES `alergia` WRITE;
/*!40000 ALTER TABLE `alergia` DISABLE KEYS */;
INSERT INTO `alergia` VALUES (2,NULL,13),(3,NULL,14),(4,NULL,15),(5,NULL,16),(6,NULL,17),(22,'<p>SIII</p>',166),(24,NULL,173),(27,NULL,181),(28,NULL,182),(29,NULL,183),(30,'<p>Esta y aquella</p>',184);
/*!40000 ALTER TABLE `alergia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `antecedente_ginecologico`
--

DROP TABLE IF EXISTS `antecedente_ginecologico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `antecedente_ginecologico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `expediente_medico_id` int DEFAULT NULL,
  `p_menarca` int DEFAULT NULL,
  `p_menopausia` int DEFAULT NULL,
  `p_f_u_m` date DEFAULT NULL,
  `p_f_u_citologia` date DEFAULT NULL,
  `p_alteracion_frecuencia` varchar(25) DEFAULT NULL,
  `p_alteracion_duracion` varchar(25) DEFAULT NULL,
  `p_alteracion_cantidad` varchar(25) DEFAULT NULL,
  `p_inicio_vida_s` int DEFAULT NULL,
  `p_no_parejas` int DEFAULT NULL,
  `p_vaginits` tinyint DEFAULT NULL,
  `p_cervicitis_mucopurulenta` tinyint DEFAULT NULL,
  `p_chancroide` tinyint DEFAULT NULL,
  `p_clamidia` tinyint DEFAULT NULL,
  `p_eip` tinyint DEFAULT NULL,
  `p_gonorrea` tinyint DEFAULT NULL,
  `p_hepatitis` tinyint DEFAULT NULL,
  `p_herpes` tinyint DEFAULT NULL,
  `p_lgv` tinyint DEFAULT NULL,
  `p_molusco_cont` tinyint DEFAULT NULL,
  `p_ladillas` tinyint DEFAULT NULL,
  `p_sarna` tinyint DEFAULT NULL,
  `p_sifilis` tinyint DEFAULT NULL,
  `p_tricomoniasis` tinyint DEFAULT NULL,
  `p_vb` tinyint DEFAULT NULL,
  `p_vih` tinyint DEFAULT NULL,
  `p_vph` tinyint DEFAULT NULL,
  `p_tipo_anticoncepcion` varchar(85) DEFAULT NULL,
  `p_inicio_anticoncepcion` date DEFAULT NULL,
  `p_suspension_anticoncepcion` date DEFAULT NULL,
  `observacion` text,
  PRIMARY KEY (`id`),
  KEY `fk_ex_medico_antecedente_gine_idx` (`expediente_medico_id`),
  CONSTRAINT `fk_ex_medico_antecedente_gine` FOREIGN KEY (`expediente_medico_id`) REFERENCES `expediente_medico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antecedente_ginecologico`
--

LOCK TABLES `antecedente_ginecologico` WRITE;
/*!40000 ALTER TABLE `antecedente_ginecologico` DISABLE KEYS */;
INSERT INTO `antecedente_ginecologico` VALUES (2,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,14,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,16,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,17,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(22,166,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(24,173,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,181,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,182,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(29,183,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(30,184,15,40,'2024-08-14',NULL,NULL,NULL,NULL,18,1,0,1,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,'nose','2024-08-14','2024-08-28','<p>SUCEDE LO SIGUIENTE <span style=\"color: rgb(41, 105, 176);\">AQUELLO Y ASI</span></p>');
/*!40000 ALTER TABLE `antecedente_ginecologico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `antecedente_hereditario`
--

DROP TABLE IF EXISTS `antecedente_hereditario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `antecedente_hereditario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `expediente_medico_id` int DEFAULT NULL,
  `cat_antecedente_hereditario_id` int DEFAULT NULL,
  `parentezco` enum('Abuelos','Hermanos','Padre','Madre') DEFAULT NULL,
  `observacion` text,
  PRIMARY KEY (`id`),
  KEY `fk_antecedente_ex_medico_idx` (`expediente_medico_id`),
  KEY `fk_antecedente_cat_ant_hereditario_idx` (`cat_antecedente_hereditario_id`),
  CONSTRAINT `fk_antecedente_cat_ant_hereditario` FOREIGN KEY (`cat_antecedente_hereditario_id`) REFERENCES `cat_antecedente_hereditario` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_antecedente_ex_medico` FOREIGN KEY (`expediente_medico_id`) REFERENCES `expediente_medico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antecedente_hereditario`
--

LOCK TABLES `antecedente_hereditario` WRITE;
/*!40000 ALTER TABLE `antecedente_hereditario` DISABLE KEYS */;
INSERT INTO `antecedente_hereditario` VALUES (1,NULL,NULL,NULL,NULL),(7,NULL,NULL,NULL,NULL),(61,6,9,'Abuelos','mmmmmmas'),(62,6,9,'Hermanos','mmmmmmas'),(63,6,13,'Madre','mmmmmmas'),(77,7,9,'Abuelos','mlmlmlmlmlmlmm'),(78,7,10,'Hermanos','mlmlmlmlmlmlmm'),(79,7,11,'Hermanos','mlmlmlmlmlmlmm'),(80,7,15,'Abuelos','mlmlmlmlmlmlmm'),(81,NULL,NULL,NULL,NULL),(85,8,9,'Abuelos','sisi'),(86,8,10,'Abuelos','sisi'),(87,8,11,'Abuelos','sisi'),(88,NULL,NULL,NULL,NULL),(89,NULL,NULL,NULL,NULL),(90,NULL,NULL,NULL,NULL),(91,NULL,NULL,NULL,NULL),(108,NULL,NULL,NULL,NULL),(109,NULL,NULL,NULL,NULL),(110,NULL,NULL,NULL,NULL),(111,NULL,NULL,NULL,NULL),(116,NULL,NULL,NULL,NULL),(129,NULL,NULL,NULL,NULL),(132,NULL,NULL,NULL,NULL),(135,NULL,NULL,NULL,NULL),(137,NULL,NULL,NULL,NULL),(140,NULL,NULL,NULL,NULL),(141,NULL,NULL,NULL,NULL),(169,NULL,NULL,NULL,NULL),(170,NULL,NULL,NULL,NULL),(171,NULL,NULL,NULL,NULL),(175,NULL,NULL,NULL,NULL),(178,NULL,NULL,NULL,NULL),(179,NULL,NULL,NULL,NULL),(180,NULL,NULL,NULL,NULL),(181,NULL,NULL,NULL,NULL),(267,NULL,NULL,NULL,NULL),(268,NULL,NULL,NULL,NULL),(269,NULL,NULL,NULL,NULL),(274,NULL,NULL,NULL,NULL),(280,NULL,NULL,NULL,NULL),(281,NULL,NULL,NULL,NULL),(283,NULL,NULL,NULL,NULL),(285,17,NULL,NULL,NULL),(287,166,NULL,NULL,NULL),(289,166,9,'Abuelos','NOOOO'),(290,166,10,'Hermanos','NOOOO'),(291,166,11,'Padre','NOOOO'),(292,166,9,'Abuelos','NOOOO'),(293,166,10,'Hermanos','NOOOO'),(294,166,11,'Padre','NOOOO'),(295,166,13,'Madre','NOOOO'),(296,166,13,'Padre','NOOOO'),(297,166,9,'Abuelos','NOOOO'),(298,166,9,'Madre','NOOOO'),(299,166,10,'Hermanos','NOOOO'),(300,166,11,'Padre','NOOOO'),(301,166,13,'Madre','NOOOO'),(302,166,13,'Padre','NOOOO'),(303,166,9,'Abuelos','NOOOO'),(304,166,9,'Madre','NOOOO'),(305,166,10,'Hermanos','NOOOO'),(306,166,11,'Padre','NOOOO'),(307,166,13,'Madre','NOOOO'),(308,166,13,'Padre','NOOOO'),(309,166,9,'Abuelos','NOOOO'),(310,166,9,'Madre','NOOOO'),(311,166,10,'Hermanos','NOOOO'),(312,166,11,'Padre','NOOOO'),(313,166,13,'Madre','NOOOO'),(314,166,13,'Padre','NOOOO'),(315,181,NULL,NULL,NULL),(316,182,NULL,NULL,NULL),(317,183,NULL,NULL,NULL),(318,184,NULL,NULL,NULL),(319,184,9,'Abuelos',''),(320,184,10,'Hermanos',''),(321,184,9,'Abuelos','Una observación'),(322,184,10,'Hermanos','Una observación'),(323,173,NULL,NULL,NULL),(324,13,NULL,NULL,NULL),(325,182,9,'Abuelos','unas'),(326,182,10,'Madre','unas'),(327,182,12,'Padre','unas');
/*!40000 ALTER TABLE `antecedente_hereditario` ENABLE KEYS */;
UNLOCK TABLES;

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

--
-- Table structure for table `antecedente_obstrectico`
--

DROP TABLE IF EXISTS `antecedente_obstrectico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `antecedente_obstrectico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `p_f_p_p` date DEFAULT NULL,
  `p_gestacion` int DEFAULT NULL,
  `p_aborto` int DEFAULT NULL,
  `p_parto` int DEFAULT NULL,
  `p_cesarea` int DEFAULT NULL,
  `p_nacidos_vivo` int DEFAULT NULL,
  `p_nacidos_muerto` int DEFAULT NULL,
  `p_viven` int DEFAULT NULL,
  `p_muerto_primera_semana` int DEFAULT NULL,
  `p_muerto_despues_semana` int DEFAULT NULL,
  `p_intergenesia` tinyint DEFAULT NULL,
  `p_malformaciones` tinyint DEFAULT NULL,
  `p_atencion_prenatal` tinyint DEFAULT NULL,
  `p_parto_prematuro` tinyint DEFAULT NULL,
  `p_isoinmunizacion` tinyint DEFAULT NULL,
  `p_no_consultas` int DEFAULT NULL,
  `p_medicacion_gestacional` text,
  `observacion` text,
  `expediente_medico_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_exp_medico_obstrectico_idx` (`expediente_medico_id`),
  CONSTRAINT `fk_exp_medico_obstrectico` FOREIGN KEY (`expediente_medico_id`) REFERENCES `expediente_medico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antecedente_obstrectico`
--

LOCK TABLES `antecedente_obstrectico` WRITE;
/*!40000 ALTER TABLE `antecedente_obstrectico` DISABLE KEYS */;
INSERT INTO `antecedente_obstrectico` VALUES (2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13),(3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,14),(4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,15),(5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,16),(6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,17),(22,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,166),(24,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,173),(27,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,181),(28,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,182),(29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,183),(30,NULL,1,NULL,1,NULL,1,NULL,1,NULL,NULL,0,0,0,0,0,NULL,'no','<p>PASA LO SIGUIENTE Y<strong>&nbsp;ESTO TODO AQUELLO Y ASI</strong></p>',184);
/*!40000 ALTER TABLE `antecedente_obstrectico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `antecedente_patologico`
--

DROP TABLE IF EXISTS `antecedente_patologico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `antecedente_patologico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descripcion_antecedentes` text,
  `expediente_medico_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ex_medico_patologico_idx` (`expediente_medico_id`),
  CONSTRAINT `fk_ex_medico_patologico` FOREIGN KEY (`expediente_medico_id`) REFERENCES `expediente_medico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antecedente_patologico`
--

LOCK TABLES `antecedente_patologico` WRITE;
/*!40000 ALTER TABLE `antecedente_patologico` DISABLE KEYS */;
INSERT INTO `antecedente_patologico` VALUES (1,'edad sisis',7),(2,'esots y auellosssss y si',8),(3,'',9),(4,'',10),(5,'',11),(7,NULL,13),(8,NULL,14),(9,NULL,15),(10,NULL,16),(11,'<p>nooo</p>',17),(27,'<p>TODOOOO BIEN</p>',166),(29,NULL,173),(32,NULL,181),(33,NULL,182),(34,NULL,183),(35,'<p><strong>Tiene lo siguiente</strong> y siguiente</p>',184);
/*!40000 ALTER TABLE `antecedente_patologico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `antecedente_perinatal`
--

DROP TABLE IF EXISTS `antecedente_perinatal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `antecedente_perinatal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `p_hora_nacimiento` time DEFAULT NULL,
  `p_parto` tinyint DEFAULT NULL,
  `p_cesarea` tinyint DEFAULT NULL,
  `p_no_gestacion` int DEFAULT NULL,
  `p_edad_gestacional` int DEFAULT NULL,
  `p_sitio_parto` varchar(65) DEFAULT NULL,
  `p_peso_nacer` float DEFAULT NULL,
  `p_talla` float DEFAULT NULL,
  `p_cefalico` float DEFAULT NULL,
  `p_toracico` float DEFAULT NULL,
  `p_abdominal` float DEFAULT NULL,
  `test` tinyint DEFAULT NULL,
  `p_apgar` int DEFAULT NULL,
  `p_ballard` int DEFAULT NULL,
  `p_silverman` int DEFAULT NULL,
  `p_capurro` int DEFAULT NULL,
  `p_complicacion` text,
  `p_anestesia` tinyint DEFAULT NULL,
  `p_especifique_anestecia` text,
  `p_apnea_neonatal` tinyint DEFAULT NULL,
  `p_cianosis` tinyint DEFAULT NULL,
  `p_hemorragias` tinyint DEFAULT NULL,
  `p_ictericia` tinyint DEFAULT NULL,
  `p_convulsiones` tinyint DEFAULT NULL,
  `observacion` text,
  `expediente_medico_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ex_medico_ant_perinatal_idx` (`expediente_medico_id`),
  CONSTRAINT `fk_ex_medico_ant_perinatal` FOREIGN KEY (`expediente_medico_id`) REFERENCES `expediente_medico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antecedente_perinatal`
--

LOCK TABLES `antecedente_perinatal` WRITE;
/*!40000 ALTER TABLE `antecedente_perinatal` DISABLE KEYS */;
INSERT INTO `antecedente_perinatal` VALUES (2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13),(3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,14),(4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,15),(5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,16),(6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,17),(22,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,166),(24,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,173),(27,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,181),(28,NULL,0,0,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,'',0,NULL,0,1,1,0,0,'',182),(29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,183),(30,'14:00:00',1,0,1,20,'Un hospital',3,40,20,21,15,0,13,NULL,NULL,NULL,'No',0,'',0,0,0,0,0,'<p>Se encuentra todo normal</p>',184);
/*!40000 ALTER TABLE `antecedente_perinatal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('administrador','232',1722268992),('administrador','235',1724874487),('editar-expediente-medico','31',1723144672),('empleado','133',1721848270),('empleado','134',1721848672),('empleado','135',1721849586),('empleado','139',1721924168),('empleado','142',1721924933),('empleado','143',1721925950),('empleado','144',1721930913),('empleado','145',1722013288),('empleado','22',1720465489),('empleado','23',1720465497),('empleado','231',1722014910),('empleado','232',1722269013),('empleado','233',1722442260),('empleado','241',1722615056),('empleado','242',1722617537),('empleado','244',1722621585),('empleado','245',1722622500),('empleado','252',1723524647),('empleado','253',1723524970),('empleado','254',1723570789),('empleado','33',1720632202),('empleado','77',1720465507),('empleado','82',1720714377),('empleado','87',1721407950),('empleado','90',1721408210),('empleado','93',1721408277),('empleado','95',1721408478),('empleado','98',1721408838),('empleado','99',1721408942),('gestor-rh','251',1723523919),('gestor-rh','31',1720203198),('manejo-empleados','65',1720204992),('medico','78',1720203226),('ver-documentos-medicos','31',1721329952),('ver-empleados-departamento','244',1723525127),('ver-empleados-departamento','77',1722620675),('ver-empleados-direccion','82',1724106640),('ver-expediente-medico','31',1720459905);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` smallint NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `rule_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('/*',2,NULL,NULL,NULL,1720541383,1720541383),('/admin/*',2,NULL,NULL,NULL,1724252616,1724252616),('/aviso/create',2,NULL,NULL,NULL,1724253516,1724253516),('/aviso/delete',2,NULL,NULL,NULL,1724253516,1724253516),('/aviso/update',2,NULL,NULL,NULL,1724253516,1724253516),('/cambio-dia-laboral/*',2,NULL,NULL,NULL,1720712270,1720712270),('/cambio-dia-laboral/delete',2,NULL,NULL,NULL,1722968855,1722968855),('/cambio-dia-laboral/export',2,NULL,NULL,NULL,1724787637,1724787637),('/cambio-dia-laboral/export-html',2,NULL,NULL,NULL,1722963262,1722963262),('/cambio-dia-laboral/export-html-segundo-caso',2,NULL,NULL,NULL,1722963262,1722963262),('/cambio-dia-laboral/historial',2,NULL,NULL,NULL,1722961644,1722961644),('/cambio-dia-laboral/view',2,NULL,NULL,NULL,1722961872,1722961872),('/cambio-horario-trabajo/*',2,NULL,NULL,NULL,1720712274,1720712274),('/cambio-horario-trabajo/delete',2,NULL,NULL,NULL,1722968855,1722968855),('/cambio-horario-trabajo/export-html',2,NULL,NULL,NULL,1722963262,1722963262),('/cambio-horario-trabajo/export-html-segundo-caso',2,NULL,NULL,NULL,1722963262,1722963262),('/cambio-horario-trabajo/historial',2,NULL,NULL,NULL,1722961644,1722961644),('/cambio-horario-trabajo/view',2,NULL,NULL,NULL,1722961879,1722961879),('/cambio-periodo-vacacional/*',2,NULL,NULL,NULL,1720712276,1720712276),('/cambio-periodo-vacacional/delete',2,NULL,NULL,NULL,1722968855,1722968855),('/cambio-periodo-vacacional/export',2,NULL,NULL,NULL,1724787637,1724787637),('/cambio-periodo-vacacional/export-html',2,NULL,NULL,NULL,1722963262,1722963262),('/cambio-periodo-vacacional/export-html-segundo-caso',2,NULL,NULL,NULL,1722963262,1722963262),('/cambio-periodo-vacacional/historial',2,NULL,NULL,NULL,1722961644,1722961644),('/cambio-periodo-vacacional/view',2,NULL,NULL,NULL,1722961904,1722961904),('/cat-departamento/create',2,NULL,NULL,NULL,1724253333,1724253333),('/cat-departamento/delete',2,NULL,NULL,NULL,1724253333,1724253333),('/cat-departamento/update',2,NULL,NULL,NULL,1724253333,1724253333),('/cat-puesto/create',2,NULL,NULL,NULL,1724253216,1724253216),('/cat-puesto/delete',2,NULL,NULL,NULL,1724253216,1724253216),('/cat-puesto/update',2,NULL,NULL,NULL,1724253216,1724253216),('/cita-medica/*',2,NULL,NULL,NULL,1721057460,1721057460),('/cita-medica/delete',2,NULL,NULL,NULL,1722971641,1722971641),('/cita-medica/historial',2,NULL,NULL,NULL,1722961644,1722961644),('/cita-medica/view',2,NULL,NULL,NULL,1722961836,1722961836),('/comision-especial/*',2,NULL,NULL,NULL,1720712288,1720712288),('/comision-especial/delete',2,NULL,NULL,NULL,1722968863,1722968863),('/comision-especial/export-html',2,NULL,NULL,NULL,1722963262,1722963262),('/comision-especial/export-html-segundo-caso',2,NULL,NULL,NULL,1722963262,1722963262),('/comision-especial/historial',2,NULL,NULL,NULL,1722961644,1722961644),('/comision-especial/view',2,NULL,NULL,NULL,1722961862,1722961862),('/consulta-medica/*',2,NULL,NULL,NULL,1720546501,1720546501),('/contrato-para-personal-eventual/*',2,NULL,NULL,NULL,1724862973,1724862973),('/contrato-para-personal-eventual/export-html',2,NULL,NULL,NULL,1724787637,1724787637),('/debug/*',2,NULL,NULL,NULL,1721061952,1721061952),('/departamento/create',2,NULL,NULL,NULL,1724253224,1724253224),('/departamento/delete',2,NULL,NULL,NULL,1724253224,1724253224),('/departamento/update',2,NULL,NULL,NULL,1724253224,1724253224),('/documento-medico/*',2,NULL,NULL,NULL,1721322172,1721322172),('/documento/*',2,NULL,NULL,NULL,1721316037,1721316037),('/empleado/*',2,NULL,NULL,NULL,1720468638,1720468638),('/empleado/index',2,NULL,NULL,NULL,1720638991,1720638991),('/empleado/view',2,NULL,NULL,NULL,1720715100,1720715100),('/junta-gobierno/*',2,NULL,NULL,NULL,1721848703,1721848703),('/parametro-formato/create',2,NULL,NULL,NULL,1724253190,1724253190),('/parametro-formato/delete',2,NULL,NULL,NULL,1724253190,1724253190),('/parametro-formato/update',2,NULL,NULL,NULL,1724253190,1724253190),('/permiso-economico/*',2,NULL,NULL,NULL,1720712232,1720712232),('/permiso-economico/delete',2,NULL,NULL,NULL,1722968879,1722968879),('/permiso-economico/export-html',2,NULL,NULL,NULL,1722963262,1722963262),('/permiso-economico/export-html-segundo-caso',2,NULL,NULL,NULL,1722963262,1722963262),('/permiso-economico/historial',2,NULL,NULL,NULL,1722961644,1722961644),('/permiso-economico/view',2,NULL,NULL,NULL,1722961888,1722961888),('/permiso-fuera-trabajo/*',2,NULL,NULL,NULL,1720712249,1720712249),('/permiso-fuera-trabajo/delete',2,NULL,NULL,NULL,1722968879,1722968879),('/permiso-fuera-trabajo/export-html',2,NULL,NULL,NULL,1722963262,1722963262),('/permiso-fuera-trabajo/export-html-segundo-caso',2,NULL,NULL,NULL,1722963262,1722963262),('/permiso-fuera-trabajo/historial',2,NULL,NULL,NULL,1722961644,1722961644),('/permiso-fuera-trabajo/view',2,NULL,NULL,NULL,1722961845,1722961845),('/permiso-sin-sueldo/*',2,NULL,NULL,NULL,1720712253,1720712253),('/permiso-sin-sueldo/delete',2,NULL,NULL,NULL,1722968879,1722968879),('/permiso-sin-sueldo/export-html',2,NULL,NULL,NULL,1722963262,1722963262),('/permiso-sin-sueldo/export-html-segundo-caso',2,NULL,NULL,NULL,1722963262,1722963262),('/permiso-sin-sueldo/historial',2,NULL,NULL,NULL,1722961644,1722961644),('/permiso-sin-sueldo/view',2,NULL,NULL,NULL,1722961893,1722961893),('/reporte-tiempo-extra-general/*',2,NULL,NULL,NULL,1724268267,1724268267),('/reporte-tiempo-extra-general/delete',2,NULL,NULL,NULL,1722968879,1722968879),('/reporte-tiempo-extra-general/export-html',2,NULL,NULL,NULL,1724787637,1724787637),('/reporte-tiempo-extra-general/export-html-segundo-caso',2,NULL,NULL,NULL,1724787637,1724787637),('/reporte-tiempo-extra-general/historial',2,NULL,NULL,NULL,1724772884,1724772884),('/reporte-tiempo-extra-general/view',2,NULL,NULL,NULL,1722961921,1722961921),('/reporte-tiempo-extra/*',2,NULL,NULL,NULL,1724255423,1724255423),('/reporte-tiempo-extra/create',2,NULL,NULL,NULL,1724255000,1724255000),('/reporte-tiempo-extra/delete',2,NULL,NULL,NULL,1722968879,1722968879),('/reporte-tiempo-extra/export-html',2,NULL,NULL,NULL,1722963262,1722963262),('/reporte-tiempo-extra/export-html-segundo-caso',2,NULL,NULL,NULL,1724787637,1724787637),('/reporte-tiempo-extra/historial',2,NULL,NULL,NULL,1722961644,1722961644),('/reporte-tiempo-extra/index',2,NULL,NULL,NULL,1724255000,1724255000),('/reporte-tiempo-extra/reporte',2,NULL,NULL,NULL,1724265398,1724265398),('/reporte-tiempo-extra/reporte3',2,NULL,NULL,NULL,1724778439,1724778439),('/reporte-tiempo-extra/view',2,NULL,NULL,NULL,1722961914,1722961914),('/site/*',2,NULL,NULL,NULL,1720468625,1720468625),('/site/cambiarcontrasena',2,NULL,NULL,NULL,1720714557,1720714557),('/site/configuracion',2,NULL,NULL,NULL,1724253704,1724253704),('/site/error',2,NULL,NULL,NULL,1724253704,1724253704),('/site/login',2,NULL,NULL,NULL,1720632114,1720632114),('/site/logout',2,NULL,NULL,NULL,1720632118,1720632118),('/site/portal-medico',2,NULL,NULL,NULL,1720542593,1720542593),('/site/portalempleado',2,NULL,NULL,NULL,1720632009,1720632009),('/site/portalgestionrh',2,NULL,NULL,NULL,1724253704,1724253704),('/solicitud/aprobar-solicitud',2,NULL,NULL,NULL,1724259155,1724259155),('/solicitud/aprobar-solicitud-medica',2,NULL,NULL,NULL,1724262039,1724262039),('/solicitud/index',2,NULL,NULL,NULL,1722356824,1722356824),('/solicitud/view',2,NULL,NULL,NULL,1720716739,1720716739),('/usuario/cambiarcontrasena',2,NULL,NULL,NULL,1720714617,1720714617),('acceso-formatos-incidencias',2,'El usuario tiene permitido acceder a los formularios de los formatos de incidencias',NULL,NULL,1720712352,1720712352),('acciones-documentos-medicos',2,'El usuario tiene permitido agregar, eliminar, descargar los documentos médicos del empleado',NULL,NULL,1721330266,1721330266),('administrador',1,'Acceso total a todo el sistema',NULL,NULL,1720203018,1720203047),('aprobar-solicitudes',2,'El usuario tiene permitido aprobar o rechazar solicitudes de reportes de tiempo extra.',NULL,NULL,1724259466,1724259466),('aprobar-solicitudes-medicas',2,'El usuario tiene permitido aceptar o rechazar solicitudes de citas medicas',NULL,NULL,1724260909,1724260909),('borrar-registros-formatos-incidencias',2,'El usuario tiene permitido eliminar los registros de los formatos de incidencias. (Permiso fuera del trabajo, comisión especial, cambio de día labora, permiso económico, etc.)',NULL,NULL,1722968816,1722968816),('Control-total',2,'El usuario tiene control total del sistema (Acceso a todas las rutas y todos los permisos)',NULL,NULL,1722268761,1722268761),('crear-cita-medica',2,'El usuario tiene permitido crear citas medicas de los empleados',NULL,NULL,1721070920,1724105331),('crear-consulta-medica',2,'El usuario tendrá habilitado el botón para acceder al formulario para generar una consulta medica',NULL,NULL,1720625745,1720625745),('crear-formatos-incidencias-empleados',2,'El usuario tiene permitido crear formatos de incidencias de los empleados',NULL,NULL,1721144627,1721144627),('editar-expediente-medico',2,'El usuario tiene permitido editar la información del expediente medico',NULL,NULL,1720206554,1720206554),('empleado',1,'Acceso a funciones principales solo de empleados',NULL,NULL,1720203073,1720203073),('gestor-rh',1,'acceso a funciones de gestor de recursos humanos',NULL,NULL,1720203166,1720203166),('imprimir-formatos-incidencias',2,'El usuario tiene permitido realizar la impresión de los formatos de incidencias.',NULL,NULL,1722963225,1722963225),('manejo-avisos',2,'El usuario tendrá permitido publicar, editar o eliminar avisos que se mostraran a los empleados.',NULL,NULL,1723230905,1723230905),('manejo-empleados',2,'El usuario tiene permitido crear, eliminar, desactivar usuarios/ empleados',NULL,NULL,1720204080,1720204931),('medico',1,'Acceso a funciones de medico',NULL,NULL,1720203120,1720468426),('menu-formatos',2,'El usuario tiene acceso al menú de formatos de incidencias',NULL,NULL,1721232366,1721232366),('modificar-informacion-empleados',2,'El usuario tiene permitido modificar la información personal de los empleados',NULL,NULL,1720205205,1720205310),('solicitudes-medicas-view-empleado',2,'El usuario tiene permitido solo visualizar su historial de solicitudes medicas.',NULL,NULL,1722353221,1722353221),('solicitudes-medicas-view-medico',2,'El usuario tiene permitido ver el historial de solicitudes medicas, eliminar/archivar',NULL,NULL,1722353167,1722353167),('todos-expediente-medico',2,'El usuario tiene acceso a todos los permisos de expediente medico',NULL,NULL,1720459827,1720459827),('ver-consultas-medicas',2,'El usuario podrá observar el historial de consultas medica de los empleados ',NULL,NULL,1720624976,1720624976),('ver-documentos',2,'Ver documentos de los empleados',NULL,NULL,1720203321,1720468215),('ver-documentos-medicos',2,'El usuario tiene permitido ver los documentos médicos de los empleados',NULL,NULL,1721329717,1721329717),('ver-empleados-departamento',2,'El usuario tiene permitido ver una lista solo de los empleados que pertenecen al mismo departamento',NULL,NULL,1720638760,1720638760),('ver-empleados-direccion',2,'El usuario tiene permitido ver una lista solo de los empleados que pertenecen a la misma dirección',NULL,NULL,1720717832,1720717832),('ver-expediente-medico',2,'El usuario tiene permitido ver el expediente medico de los empleados',NULL,NULL,1720206107,1720206107),('ver-historial-incidencias-empleados',2,'El empleado tiene permitido el acceso a ver el historial de incidencias de los empleados.',NULL,NULL,1722961620,1722961620),('ver-informacion-completa-empleados',2,'El usuario podrá ver la información completa de los empleados',NULL,NULL,1720625286,1720625286),('ver-informacion-vacacional',2,'El usuario tiene permitido ver la información vacacional de los empleados',NULL,NULL,1720205779,1720205779),('ver-lista-directores-jefes',2,'El usuario puede ver la lista de jefes y directores (eliminar de lista y visualizarlos)',NULL,NULL,1720203840,1720203840),('ver-solicitudes-formatos',2,'El usuario tiene permitido ver el historial de solicitudes de formatos de incidencias de los empleados',NULL,NULL,1721065049,1721065049),('ver-solicitudes-medicas',2,'El usuario tiene permitido ver el historial de solicitudes de citas medicas',NULL,NULL,1721064994,1721065089),('ver-todos-empleados',2,'El usuario tiene permitido ver la lista de todos lo empleados',NULL,NULL,1720638701,1720638701);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `child` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('Control-total','/*'),('Control-total','/admin/*'),('gestor-rh','/aviso/create'),('gestor-rh','/aviso/delete'),('gestor-rh','/aviso/update'),('acceso-formatos-incidencias','/cambio-dia-laboral/*'),('borrar-registros-formatos-incidencias','/cambio-dia-laboral/delete'),('imprimir-formatos-incidencias','/cambio-dia-laboral/export-html'),('imprimir-formatos-incidencias','/cambio-dia-laboral/export-html-segundo-caso'),('ver-historial-incidencias-empleados','/cambio-dia-laboral/historial'),('ver-historial-incidencias-empleados','/cambio-dia-laboral/view'),('acceso-formatos-incidencias','/cambio-horario-trabajo/*'),('borrar-registros-formatos-incidencias','/cambio-horario-trabajo/delete'),('imprimir-formatos-incidencias','/cambio-horario-trabajo/export-html'),('imprimir-formatos-incidencias','/cambio-horario-trabajo/export-html-segundo-caso'),('ver-historial-incidencias-empleados','/cambio-horario-trabajo/historial'),('ver-historial-incidencias-empleados','/cambio-horario-trabajo/view'),('acceso-formatos-incidencias','/cambio-periodo-vacacional/*'),('borrar-registros-formatos-incidencias','/cambio-periodo-vacacional/delete'),('imprimir-formatos-incidencias','/cambio-periodo-vacacional/export-html'),('imprimir-formatos-incidencias','/cambio-periodo-vacacional/export-html-segundo-caso'),('ver-historial-incidencias-empleados','/cambio-periodo-vacacional/historial'),('ver-historial-incidencias-empleados','/cambio-periodo-vacacional/view'),('gestor-rh','/cat-departamento/create'),('gestor-rh','/cat-departamento/delete'),('gestor-rh','/cat-departamento/update'),('gestor-rh','/cat-puesto/create'),('gestor-rh','/cat-puesto/delete'),('gestor-rh','/cat-puesto/update'),('empleado','/cita-medica/*'),('medico','/cita-medica/*'),('borrar-registros-formatos-incidencias','/cita-medica/delete'),('ver-historial-incidencias-empleados','/cita-medica/historial'),('ver-historial-incidencias-empleados','/cita-medica/view'),('acceso-formatos-incidencias','/comision-especial/*'),('borrar-registros-formatos-incidencias','/comision-especial/delete'),('imprimir-formatos-incidencias','/comision-especial/export-html'),('imprimir-formatos-incidencias','/comision-especial/export-html-segundo-caso'),('ver-historial-incidencias-empleados','/comision-especial/historial'),('ver-historial-incidencias-empleados','/comision-especial/view'),('acceso-formatos-incidencias','/consulta-medica/*'),('crear-consulta-medica','/consulta-medica/*'),('medico','/consulta-medica/*'),('empleado','/contrato-para-personal-eventual/*'),('empleado','/contrato-para-personal-eventual/export-html'),('imprimir-formatos-incidencias','/contrato-para-personal-eventual/export-html'),('empleado','/debug/*'),('medico','/documento-medico/*'),('gestor-rh','/documento/*'),('gestor-rh','/empleado/*'),('medico','/empleado/*'),('ver-empleados-departamento','/empleado/index'),('ver-empleados-direccion','/empleado/index'),('ver-empleados-departamento','/empleado/view'),('ver-empleados-direccion','/empleado/view'),('gestor-rh','/junta-gobierno/*'),('gestor-rh','/parametro-formato/create'),('gestor-rh','/parametro-formato/delete'),('gestor-rh','/parametro-formato/update'),('acceso-formatos-incidencias','/permiso-economico/*'),('borrar-registros-formatos-incidencias','/permiso-economico/delete'),('imprimir-formatos-incidencias','/permiso-economico/export-html'),('imprimir-formatos-incidencias','/permiso-economico/export-html-segundo-caso'),('ver-historial-incidencias-empleados','/permiso-economico/historial'),('ver-historial-incidencias-empleados','/permiso-economico/view'),('acceso-formatos-incidencias','/permiso-fuera-trabajo/*'),('borrar-registros-formatos-incidencias','/permiso-fuera-trabajo/delete'),('imprimir-formatos-incidencias','/permiso-fuera-trabajo/export-html'),('imprimir-formatos-incidencias','/permiso-fuera-trabajo/export-html-segundo-caso'),('ver-historial-incidencias-empleados','/permiso-fuera-trabajo/historial'),('ver-historial-incidencias-empleados','/permiso-fuera-trabajo/view'),('acceso-formatos-incidencias','/permiso-sin-sueldo/*'),('borrar-registros-formatos-incidencias','/permiso-sin-sueldo/delete'),('imprimir-formatos-incidencias','/permiso-sin-sueldo/export-html'),('imprimir-formatos-incidencias','/permiso-sin-sueldo/export-html-segundo-caso'),('ver-historial-incidencias-empleados','/permiso-sin-sueldo/historial'),('ver-historial-incidencias-empleados','/permiso-sin-sueldo/view'),('acceso-formatos-incidencias','/reporte-tiempo-extra-general/*'),('borrar-registros-formatos-incidencias','/reporte-tiempo-extra-general/delete'),('imprimir-formatos-incidencias','/reporte-tiempo-extra-general/export-html'),('imprimir-formatos-incidencias','/reporte-tiempo-extra-general/export-html-segundo-caso'),('ver-historial-incidencias-empleados','/reporte-tiempo-extra-general/historial'),('ver-historial-incidencias-empleados','/reporte-tiempo-extra-general/view'),('acceso-formatos-incidencias','/reporte-tiempo-extra/*'),('borrar-registros-formatos-incidencias','/reporte-tiempo-extra/delete'),('imprimir-formatos-incidencias','/reporte-tiempo-extra/export-html'),('imprimir-formatos-incidencias','/reporte-tiempo-extra/export-html-segundo-caso'),('ver-historial-incidencias-empleados','/reporte-tiempo-extra/historial'),('gestor-rh','/reporte-tiempo-extra/reporte'),('gestor-rh','/reporte-tiempo-extra/reporte3'),('ver-historial-incidencias-empleados','/reporte-tiempo-extra/view'),('gestor-rh','/site/configuracion'),('gestor-rh','/site/error'),('medico','/site/portal-medico'),('empleado','/site/portalempleado'),('gestor-rh','/site/portalempleado'),('medico','/site/portalempleado'),('gestor-rh','/site/portalgestionrh'),('gestor-rh','/solicitud/aprobar-solicitud'),('aprobar-solicitudes-medicas','/solicitud/aprobar-solicitud-medica'),('gestor-rh','/solicitud/index'),('empleado','/solicitud/view'),('gestor-rh','/solicitud/view'),('medico','/solicitud/view'),('ver-empleados-departamento','/solicitud/view'),('ver-empleados-direccion','/solicitud/view'),('empleado','/usuario/cambiarcontrasena'),('Control-total','acceso-formatos-incidencias'),('empleado','acceso-formatos-incidencias'),('Control-total','acciones-documentos-medicos'),('medico','acciones-documentos-medicos'),('gestor-rh','aprobar-solicitudes'),('gestor-rh','aprobar-solicitudes-medicas'),('gestor-rh','borrar-registros-formatos-incidencias'),('administrador','Control-total'),('Control-total','crear-cita-medica'),('medico','crear-cita-medica'),('Control-total','crear-consulta-medica'),('medico','crear-consulta-medica'),('Control-total','crear-formatos-incidencias-empleados'),('ver-empleados-departamento','crear-formatos-incidencias-empleados'),('ver-empleados-direccion','crear-formatos-incidencias-empleados'),('Control-total','editar-expediente-medico'),('medico','editar-expediente-medico'),('todos-expediente-medico','editar-expediente-medico'),('administrador','empleado'),('administrador','gestor-rh'),('gestor-rh','imprimir-formatos-incidencias'),('gestor-rh','manejo-avisos'),('Control-total','manejo-empleados'),('gestor-rh','manejo-empleados'),('administrador','medico'),('Control-total','menu-formatos'),('empleado','menu-formatos'),('Control-total','modificar-informacion-empleados'),('gestor-rh','modificar-informacion-empleados'),('empleado','solicitudes-medicas-view-empleado'),('medico','solicitudes-medicas-view-medico'),('Control-total','todos-expediente-medico'),('Control-total','ver-consultas-medicas'),('medico','ver-consultas-medicas'),('Control-total','ver-documentos'),('gestor-rh','ver-documentos'),('Control-total','ver-documentos-medicos'),('medico','ver-documentos-medicos'),('Control-total','ver-expediente-medico'),('medico','ver-expediente-medico'),('todos-expediente-medico','ver-expediente-medico'),('gestor-rh','ver-historial-incidencias-empleados'),('Control-total','ver-informacion-completa-empleados'),('empleado','ver-informacion-completa-empleados'),('gestor-rh','ver-informacion-completa-empleados'),('Control-total','ver-informacion-vacacional'),('empleado','ver-informacion-vacacional'),('gestor-rh','ver-informacion-vacacional'),('Control-total','ver-lista-directores-jefes'),('gestor-rh','ver-lista-directores-jefes'),('Control-total','ver-solicitudes-formatos'),('empleado','ver-solicitudes-formatos'),('gestor-rh','ver-solicitudes-formatos'),('Control-total','ver-solicitudes-medicas'),('medico','ver-solicitudes-medicas'),('Control-total','ver-todos-empleados'),('gestor-rh','ver-todos-empleados'),('medico','ver-todos-empleados');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aviso`
--

DROP TABLE IF EXISTS `aviso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aviso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mensaje` text,
  `titulo` varchar(150) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aviso`
--

LOCK TABLES `aviso` WRITE;
/*!40000 ALTER TABLE `aviso` DISABLE KEYS */;
INSERT INTO `aviso` VALUES (8,'<p id=\"isPasted\">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quas fugiat id modi, mollitia sit veritatis dolore praesentium beatae adipisci quasi <span style=\"background-color: rgb(247, 218, 100);\">consequatur illo doloribus dolor eius quidem nulla esse sed deserunt.</span></p><p><br></p>','URGENTE','2024-08-13 12:19:23','66bba3ab3516f.png');
/*!40000 ALTER TABLE `aviso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cambio_dia_laboral`
--

DROP TABLE IF EXISTS `cambio_dia_laboral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cambio_dia_laboral` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `solicitud_id` int DEFAULT NULL,
  `motivo_fecha_permiso_id` int DEFAULT NULL,
  `fecha_a_laborar` date DEFAULT NULL,
  `nombre_jefe_departamento` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_cambio_dia_idx` (`empleado_id`),
  KEY `fk_solicitud_cambio_dia_idx` (`solicitud_id`),
  KEY `fk_motivo_fecha_permiso_cambio_dia_idx` (`motivo_fecha_permiso_id`),
  CONSTRAINT `fk_empleado_cambio_dia` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_motivo_fecha_permiso_cambio_dia` FOREIGN KEY (`motivo_fecha_permiso_id`) REFERENCES `motivo_fecha_permiso` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_cambio_dia` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cambio_dia_laboral`
--

LOCK TABLES `cambio_dia_laboral` WRITE;
/*!40000 ALTER TABLE `cambio_dia_laboral` DISABLE KEYS */;
INSERT INTO `cambio_dia_laboral` VALUES (17,72,441,318,'2024-08-20',NULL);
/*!40000 ALTER TABLE `cambio_dia_laboral` ENABLE KEYS */;
UNLOCK TABLES;

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

--
-- Table structure for table `cambio_periodo_vacacional`
--

DROP TABLE IF EXISTS `cambio_periodo_vacacional`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cambio_periodo_vacacional` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `solicitud_id` int DEFAULT NULL,
  `motivo` text,
  `primera_vez` varchar(3) DEFAULT NULL,
  `nombre_jefe_departamento` varchar(90) DEFAULT NULL,
  `numero_periodo` varchar(12) DEFAULT NULL,
  `fecha_inicio_periodo` date DEFAULT NULL,
  `fecha_fin_periodo` date DEFAULT NULL,
  `año` varchar(8) DEFAULT NULL,
  `fecha_inicio_original` date DEFAULT NULL,
  `fecha_fin_original` date DEFAULT NULL,
  `dias_pendientes` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `empleado_id_idx` (`empleado_id`),
  KEY `fk_solicitud_cambio_periodo_idx` (`solicitud_id`),
  CONSTRAINT `fk_empleado_cambio_periodo` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_cambio_periodo` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cambio_periodo_vacacional`
--

LOCK TABLES `cambio_periodo_vacacional` WRITE;
/*!40000 ALTER TABLE `cambio_periodo_vacacional` DISABLE KEYS */;
INSERT INTO `cambio_periodo_vacacional` VALUES (31,101,458,'<p>siii</p>','Sí',NULL,'2do','2024-08-23','2024-09-02','2024','2024-08-01','2024-08-10',1),(32,90,481,'<p>ALGUNO</p>','Sí',NULL,'1ero','2024-11-01','2024-11-08','2024','2024-08-28','2024-09-03',2);
/*!40000 ALTER TABLE `cambio_periodo_vacacional` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_actividad_fisica`
--

DROP TABLE IF EXISTS `cat_actividad_fisica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cat_actividad_fisica` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_actvidad` varchar(85) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_actividad_fisica`
--

LOCK TABLES `cat_actividad_fisica` WRITE;
/*!40000 ALTER TABLE `cat_actividad_fisica` DISABLE KEYS */;
INSERT INTO `cat_actividad_fisica` VALUES (1,'Correr');
/*!40000 ALTER TABLE `cat_actividad_fisica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_antecedente_hereditario`
--

DROP TABLE IF EXISTS `cat_antecedente_hereditario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cat_antecedente_hereditario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(85) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_antecedente_hereditario`
--

LOCK TABLES `cat_antecedente_hereditario` WRITE;
/*!40000 ALTER TABLE `cat_antecedente_hereditario` DISABLE KEYS */;
INSERT INTO `cat_antecedente_hereditario` VALUES (9,'Alergias'),(10,'Anomalías Congénitas '),(11,'Cancer'),(12,'Cardiopatías '),(13,'Diabetes'),(14,'Daltonismo'),(15,'Ceguera congénita'),(16,'A.V.C'),(17,'Enf. Mental'),(18,'Enf. Pulmonar'),(19,'Entem. Congenitas'),(20,'Epilepsia'),(21,'Hipertensión Arterial'),(22,'Neoplasia'),(23,'Oncologicos'),(24,'Sordera'),(25,'T.B.C. Pulmonar');
/*!40000 ALTER TABLE `cat_antecedente_hereditario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_departamento`
--

DROP TABLE IF EXISTS `cat_departamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cat_departamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_departamento` varchar(100) NOT NULL,
  `cat_direccion_id` int DEFAULT NULL,
  `cat_dpto_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_departamento_UNIQUE` (`nombre_departamento`),
  KEY `fk_cat_direccion_depa_idx` (`cat_direccion_id`),
  KEY `fk_cat_dpto_depa_idx` (`cat_dpto_id`),
  CONSTRAINT `fk_cat_direccion_depa` FOREIGN KEY (`cat_direccion_id`) REFERENCES `cat_direccion` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cat_dpto_depa` FOREIGN KEY (`cat_dpto_id`) REFERENCES `cat_dpto_cargo` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_departamento`
--

LOCK TABLES `cat_departamento` WRITE;
/*!40000 ALTER TABLE `cat_departamento` DISABLE KEYS */;
INSERT INTO `cat_departamento` VALUES (40,'DIRECCIÓN GENERAL',1,7),(41,'COMISARÍA',1,8),(42,'UNIDAD DE CONTRALORÍA',1,9),(43,'UNIDAD DE DIFUSIÓN Y CULTURA DEL AGUA',1,10),(44,'UNIDAD DE INFORMÁTICA',1,11),(45,'UNIDAD JURÍDICA',1,12),(46,'UNIDAD DE JUNTAS LOCALES',1,13),(47,'UNIDAD DE SECRETARÍA TÉCNICA',1,14),(48,'UNIDAD DE TRANSPARENCIA',1,15),(49,'DIRECCIÓN DE ADMINISTRACIÓN',2,16),(50,'DEPARTAMENTO DE ADQUISIÓN Y SERVICIO GRALES',2,17),(51,'DEPARTAMENTO DE FINANZAS',2,18),(52,'DEPARTAMENTO DE RECURSOS HUMANOS',2,19),(54,'DIRECCIÓN COMERCIAL',3,20),(55,'DEPARTAMENTO DE ATENCIÓN A USUARIOS',3,21),(56,'DEPARTAMENTO DE CATASTRO Y PADRÓN DE USUARIOS',3,22),(57,'DEPARTAMENTO DE PROCESOS COMERCIALES',3,23),(58,'DIRECCIÓN DE OPERACIONES',4,24),(59,'DEPARTAMENTO ELECTROMÉCANICO',4,25),(60,'DEPARTAMENTO DE PRODUCCIÓN',4,26),(61,'DEPARTAMENTO DE REDES',4,27),(62,'DEPARTAMENTO DE SANEAMIENTO',4,28),(63,'DIRECCIÓN DE PLANEACIÓN',5,29),(64,'DEPARTAMENTO DE INGENIERÍA Y CONSTRUCCIÓN',5,30),(65,'DEPARTAMENTO DE PLANEACIÓN',5,31);
/*!40000 ALTER TABLE `cat_departamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_direccion`
--

DROP TABLE IF EXISTS `cat_direccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cat_direccion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_direccion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_direccion`
--

LOCK TABLES `cat_direccion` WRITE;
/*!40000 ALTER TABLE `cat_direccion` DISABLE KEYS */;
INSERT INTO `cat_direccion` VALUES (1,'1.- GENERAL'),(2,'2.- ADMINISTRACIÓN'),(3,'3.- COMERCIAL'),(4,'4.- OPERACIONES'),(5,'5.- PLANEACIÓN');
/*!40000 ALTER TABLE `cat_direccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_dpto_cargo`
--

DROP TABLE IF EXISTS `cat_dpto_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cat_dpto_cargo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_dpto` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_dpto_cargo`
--

LOCK TABLES `cat_dpto_cargo` WRITE;
/*!40000 ALTER TABLE `cat_dpto_cargo` DISABLE KEYS */;
INSERT INTO `cat_dpto_cargo` VALUES (7,'DGDG'),(8,'DGUC'),(9,'DGCI'),(10,'DGUD'),(11,'DGUI'),(12,'DGUJ'),(13,'DGUJL'),(14,'DGUST'),(15,'DGUT'),(16,'DADA'),(17,'DAAD'),(18,'DAFI'),(19,'DARH'),(20,'DCDC'),(21,'DCAU'),(22,'DCCP'),(23,'DCPC'),(24,'DODO'),(25,'DOEE'),(26,'DOPR'),(27,'DORE'),(28,'DOSA'),(29,'DPDP'),(30,'DPIC'),(31,'DPPL');
/*!40000 ALTER TABLE `cat_dpto_cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_nivel_estudio`
--

DROP TABLE IF EXISTS `cat_nivel_estudio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cat_nivel_estudio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nivel_estudio` varchar(85) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_nivel_estudio`
--

LOCK TABLES `cat_nivel_estudio` WRITE;
/*!40000 ALTER TABLE `cat_nivel_estudio` DISABLE KEYS */;
INSERT INTO `cat_nivel_estudio` VALUES (1,'PREPARATORIA'),(2,'SECUNDARIA'),(3,'PRIMARIA'),(4,'SIN COMPROBANTE DE ESTUDIOS'),(5,'TECNICO'),(6,'LICENCIATURA'),(7,'BACHILLERATO'),(8,'INGENIERIA'),(9,'AUN ESTUDIANDO'),(10,'TRUNCO');
/*!40000 ALTER TABLE `cat_nivel_estudio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_puesto`
--

DROP TABLE IF EXISTS `cat_puesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cat_puesto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_puesto` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_puesto_UNIQUE` (`nombre_puesto`)
) ENGINE=InnoDB AUTO_INCREMENT=481 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_puesto`
--

LOCK TABLES `cat_puesto` WRITE;
/*!40000 ALTER TABLE `cat_puesto` DISABLE KEYS */;
INSERT INTO `cat_puesto` VALUES (12,'ALMACENISTA'),(446,'ASESOR DE DIRECCION GENERAL'),(130,'ASISTENTE DE DIRECCION COMERCIAL'),(23,'ASISTENTE DE LA DIRECCION ADMVA'),(135,'ASISTENTE DE LA DIRECCION ADMVA  B'),(324,'ASISTENTE DIRECCION DE OPERACIONES'),(150,'ASISTENTE DIRECCION DE PLANEACION'),(9,'ASISTENTE DIRECCION GENERAL'),(157,'ASISTENTE DIRECCION GENERAL B'),(30,'ATENCION A USUARIOS'),(249,'AUX ADMVO JURIDICO'),(328,'AUX ADMVO UNIDAD DE TRANSPARENCIA'),(436,'AUX COMISARIA'),(392,'AUX CONTRALORIA INTERNA'),(337,'AUX DE DIRECCION COMERCIAL'),(151,'AUX DE DIRECCION COMERCIAL B'),(171,'AUX DE DIRECCION GENERAL'),(283,'AUX DE INGENIERIA Y CONSTRUCCION'),(379,'AUX DE SECRETARIA TECNICA'),(375,'AUX DE TOPOGRAFIA'),(437,'AUX ING Y CONSTRUCCION'),(430,'AUX. ADMINISTRATIVO'),(320,'AUX. DE ADQUISICIONES'),(16,'AUXILIAR ADMINISTRATIVO A'),(170,'AUXILIAR ADMINISTRATIVO B'),(21,'AUXILIAR ADMINISTRATIVO ESPECIAL'),(329,'AUXILIAR COMISARIO'),(335,'AUXILIAR CONTABLE'),(7,'AUXILIAR CONTABLE A'),(241,'AUXILIAR DE ATENCION AL PUBLICO B'),(144,'AUXILIAR DE CATASTRO B'),(35,'AUXILIAR DE COMPUTO Y SISTEMAS'),(242,'AUXILIAR DE CULTURA DEL AGUA'),(227,'AUXILIAR DE CULTURA DEL AGUA B'),(299,'AUXILIAR DE DIFUSION SOCIAL'),(19,'AUXILIAR DE DIRECCION COMERCIAL'),(27,'AUXILIAR DE MANTENIMIENTO  A'),(209,'AUXILIAR DE MANTENIMIENTO B'),(188,'AUXILIAR DE OPERADOR'),(192,'AUXILIAR DE OPERADOR DE BOMBEO'),(292,'AUXILIAR DE PROYECTOS'),(223,'AUXILIAR DE RECURSOS HUMANOS'),(17,'AUXILIAR DE SUPERVISION'),(401,'AUXILIAR DE SUPERVISOR'),(153,'AUXILIAR DE SUPERVISOR DE OBRA'),(326,'AUXILIAR DIRECCION DE OPERACIONES'),(441,'AUXILIAR SISTEMAS'),(393,'AUXILIAR SUPERVISOR'),(139,'AYUDANTE DIVERSO'),(117,'CAJERA'),(172,'CAJERO'),(271,'CAJERO B'),(282,'CAJERO C'),(83,'CHOFER A'),(261,'CHOFER B'),(357,'CHOFER DE PIPA'),(13,'CHOFER ESPECIAL'),(280,'COMISARIO'),(245,'COMISIONADA DG A TRANSPARENCIA'),(222,'DIRECTOR COMERCIAL'),(318,'DIRECTOR DE OPERACIONES'),(33,'DIRECTOR DE PLANEACIÓN'),(31,'DIRECTOR GENERAL'),(6,'DIRECTORA DE ADMINISTRACION'),(298,'DISEÃ‘ADOR GRAFICO'),(168,'ENC DE CLORACION Y MONITOREOS'),(254,'ENC DEL DEPTO DE ADQ Y SERV GRALES'),(113,'ENC DEL DEPTO DE RECURSOS HUMANOS'),(149,'ENC. DE CONVENIOS'),(26,'ENC. DE GRANDES DEUDORES'),(204,'ENC. DE INGENIERIA Y CONSTRUCCION'),(134,'ENC. DE PTAR'),(287,'ENC. DESCARGAS RESIDUALES'),(143,'ENC. SUPERVISOR DE COBRANZA'),(99,'ENC.DE DIF SOCIAL Y CULTURA DEL AGUA'),(177,'ENCARGADA DE NOMINAS'),(118,'GESTOR DE COBRO A'),(112,'INSPECTOR A'),(164,'INSPECTOR B'),(2,'INSPECTOR ESPECIAL'),(98,'INTENDENCIA'),(115,'JARDINERO'),(5,'JEFE DE ATENCION A USUARIOS'),(53,'JEFE DE CATASTRO Y PADRON DE USUARIOS'),(11,'JEFE DE CATASTROFES Y PADRON DE USUARIOS'),(3,'JEFE DE CONTROL VEHICULAR'),(155,'JEFE DE PROCESOS COMERCIALES'),(32,'JEFE DE UNIDAD INFORMATICA'),(34,'JEFE DE UNIDAD JURIDICA'),(262,'JEFE DEL AREA  ELECTROMECANICA'),(4,'JEFE DEL DEPARTAMENTO DE RECURSOS Y SERVICIOS GENERALES'),(94,'JEFE DEL DEPTO DE PLANEACION'),(41,'JEFE DEL DEPTO DE REC Y SERV GRALES'),(18,'JEFE DEPTO DE CONTABILIDAD Y FINANZAS'),(166,'JEFE DEPTO DE PRODUCCION'),(281,'JEFE UNIDAD DE CONTRALORIA'),(368,'JEFE UNIDAD DE INFORMATICA'),(386,'JEFE UNIDAD DE JUNTAS LOCALES ZONA I'),(369,'JEFE UNIDAD DE JUNTAS LOCALES ZONA II'),(403,'JEFE UNIDAD DE TRANSPARENCIA'),(429,'MECANICO'),(89,'OF OPERADOR DE CAMION HIDRONEUMATICO'),(286,'OFICIAL DE MANTENIMIENTO'),(175,'OFICIAL DE MANTENIMIENTO A'),(195,'OFICIAL DE MANTENIMIENTO B'),(20,'OFICIAL DE MANTENIMIENTO ESPECIAL'),(233,'OFICIAL DE MTTO ELECTROMECANICO'),(8,'OFICIAL DE OPERACIONES A'),(29,'OFICIAL DE OPERACIONES B'),(1,'OFICIAL DE OPERACIONES ESPECIAL'),(173,'OFICIAL MECANICO'),(103,'OPERADOR DE BOMBEO'),(303,'OPERADOR DE MAQUINARIA'),(86,'OPERADOR DE PLANTA'),(399,'OPERADOR DE PLANTA  B'),(87,'OPERADOR DE PRODUCCION'),(22,'PROYECTISTA'),(77,'REJILLERO'),(331,'SECRETARIO TECNICO'),(59,'SUP DE INSTALACIONES DOMICILIARIAS'),(24,'SUPERVISOR DE CAJAS'),(15,'SUPERVISOR DE INSTALACIONES DOMICILIARIAS'),(129,'SUPERVISOR DE MULTAS E INSPECCIONES'),(338,'SUPERVISOR DE OBRA'),(215,'SUPERVISOR DE OPERACIONES'),(14,'SUPERVISOR GENERAL'),(10,'SUPERVISOR OPERACION A'),(124,'VIGILANTE');
/*!40000 ALTER TABLE `cat_puesto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_tipo_contrato`
--

DROP TABLE IF EXISTS `cat_tipo_contrato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cat_tipo_contrato` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_tipo` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_tipo_contrato`
--

LOCK TABLES `cat_tipo_contrato` WRITE;
/*!40000 ALTER TABLE `cat_tipo_contrato` DISABLE KEYS */;
INSERT INTO `cat_tipo_contrato` VALUES (1,'Confianza'),(2,'Sindicalizado'),(3,'Eventual'),(4,'Prueba');
/*!40000 ALTER TABLE `cat_tipo_contrato` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_tipo_documento`
--

DROP TABLE IF EXISTS `cat_tipo_documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cat_tipo_documento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_tipo` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_tipo_documento`
--

LOCK TABLES `cat_tipo_documento` WRITE;
/*!40000 ALTER TABLE `cat_tipo_documento` DISABLE KEYS */;
INSERT INTO `cat_tipo_documento` VALUES (1,'CURP'),(2,'NSS'),(3,'RFC'),(4,'OTRO'),(5,'Nuevoo'),(6,'si'),(7,'siva'),(8,'CURRICULUM');
/*!40000 ALTER TABLE `cat_tipo_documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cita_medica`
--

DROP TABLE IF EXISTS `cita_medica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cita_medica` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `solicitud_id` int DEFAULT NULL,
  `fecha_para_cita` date DEFAULT NULL,
  `comentario` text,
  `horario_inicio` time DEFAULT NULL,
  `horario_finalizacion` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cita_medica_solicitud_idx` (`solicitud_id`),
  KEY `fk_cita_medica_empleado_idx` (`empleado_id`),
  CONSTRAINT `fk_cita_medica_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_cita_medica_solicitud` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cita_medica`
--

LOCK TABLES `cita_medica` WRITE;
/*!40000 ALTER TABLE `cita_medica` DISABLE KEYS */;
INSERT INTO `cita_medica` VALUES (37,101,431,'2024-08-16','<p>UNA SITUACI&Oacute;N</p>','01:00:00','15:00:00'),(38,101,437,'2024-08-16','<p id=\"isPasted\">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Impedit eius suscipit soluta et labore esse, odio obcaecati modi aut facere cupiditate nostrum corporis assumenda aspernatur velit debitis quaerat sit beatae.</p><p><br></p>','08:26:00',NULL),(39,72,451,'2024-08-21','<p>SINTOMAS</p>','11:22:00',NULL),(40,90,477,'2024-08-28','<p>DOLORES</p>','07:47:00',NULL);
/*!40000 ALTER TABLE `cita_medica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comision_especial`
--

DROP TABLE IF EXISTS `comision_especial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comision_especial` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `solicitud_id` int DEFAULT NULL,
  `motivo_fecha_permiso_id` int DEFAULT NULL,
  `nombre_jefe_departamento` varchar(90) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_comision_idx` (`empleado_id`),
  KEY `fk_solicitud_comision_idx` (`solicitud_id`),
  KEY `fk_motivo_fecha_permiso_comision_idx` (`motivo_fecha_permiso_id`),
  CONSTRAINT `fk_empleado_comision` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_motivo_fecha_permiso_comision` FOREIGN KEY (`motivo_fecha_permiso_id`) REFERENCES `motivo_fecha_permiso` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_comision` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comision_especial`
--

LOCK TABLES `comision_especial` WRITE;
/*!40000 ALTER TABLE `comision_especial` DISABLE KEYS */;
INSERT INTO `comision_especial` VALUES (55,101,426,315,NULL,'2024-08-13 12:00:02',NULL),(56,72,440,317,NULL,'2024-08-19 10:13:15',NULL),(57,101,442,319,NULL,'2024-08-19 11:15:45',NULL),(58,101,448,322,NULL,'2024-08-20 12:58:50',NULL),(59,90,476,323,NULL,'2024-08-27 11:39:15',NULL),(60,72,479,325,NULL,'2024-08-28 10:21:29',NULL);
/*!40000 ALTER TABLE `comision_especial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consulta_medica`
--

DROP TABLE IF EXISTS `consulta_medica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consulta_medica` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cita_medica_id` int DEFAULT NULL,
  `motivo` text,
  `sintomas` text,
  `diagnostico` text,
  `tratamiento` text,
  `presion_arterial_minimo` float DEFAULT NULL,
  `presion_arterial_maximo` float DEFAULT NULL,
  `temperatura_corporal` float DEFAULT NULL,
  `aspecto_fisico` text,
  `nivel_glucosa` float DEFAULT NULL,
  `oxigeno_sangre` float DEFAULT NULL,
  `medico_atendio` varchar(45) DEFAULT NULL,
  `frecuencia_cardiaca` float DEFAULT NULL,
  `frecuencia_respiratoria` float DEFAULT NULL,
  `estatura` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `imc` float DEFAULT NULL,
  `expediente_medico_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_consulta_ex_medico_idx` (`expediente_medico_id`),
  CONSTRAINT `fk_consulta_ex_medico` FOREIGN KEY (`expediente_medico_id`) REFERENCES `expediente_medico` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consulta_medica`
--

LOCK TABLES `consulta_medica` WRITE;
/*!40000 ALTER TABLE `consulta_medica` DISABLE KEYS */;
INSERT INTO `consulta_medica` VALUES (1,NULL,'<p><strong><span style=\"font-size: 18px;\">Estos, aquellos y asi</span></strong></p><ol><li>a</li><li>a</li><li>a</li><li>a</li></ol>','<p>tiene</p><ol><li>tambie</li><li>y</li><li>aq</li></ol><p><br></p>','<p>presenta aja y aja</p>','<p><span style=\"color: rgb(251, 160, 38);\">necesita y tambien </span></p>',25,22.04,25.23,'<p><span style=\"font-family: Impact,Charcoal,sans-serif;\">Se le ve asi y asi</span></p>',10,10.2,NULL,120,42,165,68,72,NULL,NULL),(3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-07-09 00:00:00'),(4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-07-09 00:00:00'),(5,NULL,'<p><strong>UNOS</strong></p>','<p><strong>AQUELLOS</strong></p>','<p><span style=\"color: rgb(65, 168, 95);\">SIIIIIIIIIIIIIII</span></p>','<p>ESTEEEEEEEEEEEE<span class=\"fr-emoticon fr-deletable fr-emoticon-img\" style=\"background: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/1f601.svg);\">&nbsp;</span></p>',55,111,22,'<p><span style=\"font-family: Impact,Charcoal,sans-serif;\">ESTOSS</span></p>',44,12,NULL,22,11,22,220.3,25.5,NULL,'2024-07-09 00:00:00'),(8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-07-18 00:00:00'),(9,NULL,'<p>ss</p>','<p>ss</p>','','',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,166,'2024-08-11 00:00:00'),(10,NULL,'<p>sisis</p>','<p>sisisis</p>','','',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,17,'2024-08-13 00:00:00'),(11,NULL,'<p id=\"isPasted\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>&nbsp; Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi obcaecati perferendis accusantium impedit eaque, animi cumque aspernatur ex quia officia distinctio eum. Ex cum voluptatum autem. Similique assumenda dolore error.</strong></p><p><br></p>','<p id=\"isPasted\" style=\"text-align: left;\"><u><em>Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi obcaecati perferendis accusantium impedit eaque, animi cumque aspernatur ex quia officia distinctio eum. Ex cum voluptatum autem. Similique assumenda dolore error.</em></u></p><p><br></p>','<p style=\"text-align: left;\" id=\"isPasted\"><em>Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi obcaecati perferendis accusantium impedit eaque, animi cumque aspernatur ex quia officia distinctio eum. Ex cum voluptatum autem. Similique assumenda dolore error.</em></p>','<p style=\"text-align: left;\" id=\"isPasted\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi obcaecati perferendis accusantium impedit eaque, animi cumque aspernatur ex quia officia distinctio eum. Ex cum voluptatum autem. Similique assumenda dolore error.</p>',0,0,0,'<p style=\"text-align: left;\" id=\"isPasted\"><span style=\"color: rgb(235, 107, 86); font-size: 24px;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi obcaecati perferendis accusantium impedit eaque, animi cumque aspernatur ex quia officia distinctio eum. Ex cum voluptatum autem. Similique assumenda dolore error.</span></p>',0,0,NULL,0,0,0,0,0,184,'2024-08-15 13:30:22'),(12,NULL,'','','','<p>tAL</p>',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,166,'2024-08-28 09:55:59');
/*!40000 ALTER TABLE `consulta_medica` ENABLE KEYS */;
UNLOCK TABLES;

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

--
-- Table structure for table `expediente_medico`
--

DROP TABLE IF EXISTS `expediente_medico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expediente_medico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `consulta_medica_id` int DEFAULT NULL,
  `documento_id` int DEFAULT NULL,
  `antecedente_hereditario_id` int DEFAULT NULL,
  `antecedente_patologico_id` int DEFAULT NULL,
  `antecedente_no_patologico_id` int DEFAULT NULL,
  `medicacion_necesitada` text,
  `alergias` text,
  `no_seguridad_social` int DEFAULT NULL,
  `empleado_id` int DEFAULT NULL,
  `exploracion_fisica_id` int DEFAULT NULL,
  `interrogatorio_medico_id` int DEFAULT NULL,
  `antecedente_perinatal_id` int DEFAULT NULL,
  `antecedente_ginecologico_id` int DEFAULT NULL,
  `antecedente_obstrectico_id` int DEFAULT NULL,
  `alergia_id` int DEFAULT NULL,
  `primera_revision` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ex_medico_documento_idx` (`documento_id`),
  KEY `fk_ex_medico_ant_hereditario_idx` (`antecedente_hereditario_id`),
  KEY `fk_ex_medico_ant_patologico_idx` (`antecedente_patologico_id`),
  KEY `fk_ex_medico_ante_no_patologico_idx` (`antecedente_no_patologico_id`),
  KEY `fk_ex_medico_empleado_idx` (`empleado_id`),
  KEY `fk_ex_medico_exploracion_fisica_idx` (`exploracion_fisica_id`),
  KEY `fk_ex_medico_interrogario_medico_idx` (`interrogatorio_medico_id`),
  KEY `fk_ex_medico_ante_perinatal_idx` (`antecedente_perinatal_id`),
  KEY `fk_ex_medico_ant_ginecologico_idx` (`antecedente_ginecologico_id`),
  KEY `fk_ex_medico_ant_obstrectico_idx` (`antecedente_obstrectico_id`),
  KEY `fk_ex_medico_alergia_idx` (`alergia_id`),
  KEY `fk_ex_medico_consulta_idx` (`consulta_medica_id`),
  CONSTRAINT `fk_ex_medico_alergia` FOREIGN KEY (`alergia_id`) REFERENCES `alergia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ex_medico_ant_ginecologico` FOREIGN KEY (`antecedente_ginecologico_id`) REFERENCES `antecedente_ginecologico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ex_medico_ant_hereditario` FOREIGN KEY (`antecedente_hereditario_id`) REFERENCES `antecedente_hereditario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ex_medico_ant_obstrectico` FOREIGN KEY (`antecedente_obstrectico_id`) REFERENCES `antecedente_obstrectico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ex_medico_ant_patologico` FOREIGN KEY (`antecedente_patologico_id`) REFERENCES `antecedente_patologico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ex_medico_ante_no_patologico` FOREIGN KEY (`antecedente_no_patologico_id`) REFERENCES `antecedente_no_patologico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ex_medico_ante_perinatal` FOREIGN KEY (`antecedente_perinatal_id`) REFERENCES `antecedente_perinatal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ex_medico_consulta` FOREIGN KEY (`consulta_medica_id`) REFERENCES `consulta_medica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ex_medico_documento` FOREIGN KEY (`documento_id`) REFERENCES `documento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ex_medico_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ex_medico_exploracion_fisica` FOREIGN KEY (`exploracion_fisica_id`) REFERENCES `exploracion_fisica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ex_medico_interrogario_medico` FOREIGN KEY (`interrogatorio_medico_id`) REFERENCES `interrogatorio_medico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expediente_medico`
--

LOCK TABLES `expediente_medico` WRITE;
/*!40000 ALTER TABLE `expediente_medico` DISABLE KEYS */;
INSERT INTO `expediente_medico` VALUES (2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(6,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(7,NULL,NULL,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(8,NULL,NULL,81,2,NULL,NULL,NULL,NULL,NULL,2,2,NULL,NULL,NULL,NULL,1),(9,NULL,NULL,88,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(10,NULL,NULL,89,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(11,NULL,NULL,90,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(13,NULL,NULL,324,7,6,NULL,NULL,NULL,68,3,3,2,2,2,2,1),(14,NULL,NULL,109,8,7,NULL,NULL,NULL,NULL,4,4,3,3,3,3,1),(15,NULL,NULL,110,9,8,NULL,NULL,NULL,NULL,5,5,4,4,4,4,1),(16,NULL,NULL,111,10,9,NULL,NULL,NULL,NULL,6,6,5,5,5,5,1),(17,NULL,NULL,285,11,10,NULL,NULL,NULL,72,7,7,6,6,6,6,1),(22,NULL,NULL,129,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(28,NULL,NULL,135,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(166,NULL,NULL,287,27,26,NULL,NULL,NULL,90,23,23,22,22,22,22,1),(173,NULL,NULL,323,29,28,NULL,NULL,NULL,95,25,25,24,24,24,24,1),(181,NULL,NULL,315,32,31,NULL,NULL,NULL,98,28,28,27,27,27,27,0),(182,NULL,NULL,316,33,32,NULL,NULL,NULL,99,29,29,28,28,28,28,1),(183,NULL,NULL,317,34,33,NULL,NULL,NULL,100,30,30,29,29,29,29,0),(184,NULL,NULL,318,35,34,NULL,NULL,NULL,101,31,31,30,30,30,30,1);
/*!40000 ALTER TABLE `expediente_medico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exploracion_fisica`
--

DROP TABLE IF EXISTS `exploracion_fisica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exploracion_fisica` (
  `id` int NOT NULL AUTO_INCREMENT,
  `expediente_medico_id` int DEFAULT NULL,
  `desc_habitus_exterior` text,
  `desc_cabeza` text,
  `desc_ojos` text,
  `desc_otorrinolaringologia` text,
  `desc_cuello` text,
  `desc_torax` text,
  `desc_abdomen` text,
  `desc_exploración_ginecologica` text,
  `desc_genitales` text,
  `desc_columna_vertebral` text,
  `desc_extremidades` text,
  `desc_exploracion_neurologica` text,
  PRIMARY KEY (`id`),
  KEY `fk_ex_exploracion_fisica_idx` (`expediente_medico_id`),
  CONSTRAINT `fk_ex_exploracion_fisica` FOREIGN KEY (`expediente_medico_id`) REFERENCES `expediente_medico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exploracion_fisica`
--

LOCK TABLES `exploracion_fisica` WRITE;
/*!40000 ALTER TABLE `exploracion_fisica` DISABLE KEYS */;
INSERT INTO `exploracion_fisica` VALUES (2,8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,14,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,16,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,17,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(23,166,'<p>SII</p>','',NULL,NULL,'','','','','','','',''),(25,173,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,181,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(29,182,'','',NULL,NULL,'','','',NULL,'','','','<p>ALGUNA</p>'),(30,183,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(31,184,'<p>SE VE ASI Y ASI</p>','<p>TIENE ESTO Y AQUELLO</p>',NULL,NULL,'<p>MUESTRA ESO&nbsp;</p>','<p>TIENE ESTO Y ASI</p>','<p>SE LE VE ASI</p>','<p>CUENTA CON AQUELLO Y AQUEL</p>','<p>PRESENTA SINTOMAS</p>','<p>TIENE ESTO Y ASI</p>','<p>LE FALTA</p>','<p>SE VE ASI</p>');
/*!40000 ALTER TABLE `exploracion_fisica` ENABLE KEYS */;
UNLOCK TABLES;

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

--
-- Table structure for table `interrogatorio_medico`
--

DROP TABLE IF EXISTS `interrogatorio_medico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `interrogatorio_medico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `expediente_medico_id` int DEFAULT NULL,
  `desc_cardiovascular` text,
  `desc_digestivo` text,
  `desc_endocrino` text,
  `desc_hemolinfatico` text,
  `desc_mamas` text,
  `desc_musculo_esqueletico` text,
  `desc_piel_anexos` text,
  `desc_reproductor` text,
  `desc_respiratorio` text,
  `desc_sistema_nervioso` text,
  `desc_sistemas_generales` text,
  `desc_urinario` text,
  PRIMARY KEY (`id`),
  KEY `fk_ex_medico_interrogatorio_idx` (`expediente_medico_id`),
  CONSTRAINT `fk_ex_medico_interrogatorio` FOREIGN KEY (`expediente_medico_id`) REFERENCES `expediente_medico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interrogatorio_medico`
--

LOCK TABLES `interrogatorio_medico` WRITE;
/*!40000 ALTER TABLE `interrogatorio_medico` DISABLE KEYS */;
INSERT INTO `interrogatorio_medico` VALUES (2,8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,14,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,16,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,17,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(23,166,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,173,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,181,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(29,182,'','','',NULL,NULL,NULL,'','','','','','<p><span class=\"fr-emoticon fr-deletable fr-emoticon-img\" style=\"background: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/1f605.svg);\">&nbsp;</span></p>'),(30,183,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(31,184,'<p><em>PASO ESTO Y ASI</em></p>','<p>CUENTA CON SITUACIONES</p>','<p>COSAS Y ASI</p>','<p>PRESENTA Y TIENE</p>','<p>SUCEDE ESTO Y AQUELLO</p>',NULL,'<p>PASA ESTO</p>','<p>LE PASA AQUELLO</p>','<p>LE FALTA</p>','<p>TIENE ESTO ASI Y AQUL</p>','<p>SE VEN AIS</p>','<p>BIEN Y ASI</p>');
/*!40000 ALTER TABLE `interrogatorio_medico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `junta_gobierno`
--

DROP TABLE IF EXISTS `junta_gobierno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `junta_gobierno` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cat_direccion_id` int DEFAULT NULL,
  `cat_departamento_id` int DEFAULT NULL,
  `empleado_id` int DEFAULT NULL,
  `nivel_jerarquico` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_direccion_idx` (`cat_direccion_id`),
  KEY `fk_departamento_idx` (`cat_departamento_id`),
  KEY `fk_empleado_idx` (`empleado_id`),
  CONSTRAINT `fk_departamento` FOREIGN KEY (`cat_departamento_id`) REFERENCES `cat_departamento` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_direccion` FOREIGN KEY (`cat_direccion_id`) REFERENCES `cat_direccion` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_empleado_junta` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `junta_gobierno`
--

LOCK TABLES `junta_gobierno` WRITE;
/*!40000 ALTER TABLE `junta_gobierno` DISABLE KEYS */;
INSERT INTO `junta_gobierno` VALUES (41,1,44,90,'Jefe de unidad'),(45,1,45,95,'Jefe de unidad'),(46,1,40,100,'Director');
/*!40000 ALTER TABLE `junta_gobierno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `order` int DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1711991202),('m140506_102106_rbac_init',1711991206),('m140602_111327_create_menu_table',1711991890),('m160312_050000_create_user',1711991890),('m170907_052038_rbac_add_index_on_auth_assignment_user_id',1711991206),('m180523_151638_rbac_updates_indexes_without_prefix',1711991206),('m200409_110543_rbac_update_mssql_trigger',1711991206);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

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

--
-- Table structure for table `notificacion`
--

DROP TABLE IF EXISTS `notificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `mensaje` text,
  `leido` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `permiso_fuera_trabajo_id` int DEFAULT NULL,
  `comision_especial_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_usuario_notificacion_idx` (`usuario_id`),
  KEY `fk_permiso_fuera_trabajo_noti_idx` (`permiso_fuera_trabajo_id`),
  KEY `fk_comision_especial_noti_idx` (`comision_especial_id`),
  CONSTRAINT `fk_comision_especial_noti` FOREIGN KEY (`comision_especial_id`) REFERENCES `comision_especial` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_permiso_fuera_trabajo_noti` FOREIGN KEY (`permiso_fuera_trabajo_id`) REFERENCES `permiso_fuera_trabajo` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_usuario_notificacion` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=374 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacion`
--

LOCK TABLES `notificacion` WRITE;
/*!40000 ALTER TABLE `notificacion` DISABLE KEYS */;
INSERT INTO `notificacion` VALUES (333,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-13 12:00:02',NULL,NULL),(334,251,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-15 10:17:37',NULL,NULL),(335,82,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-15 10:25:26',NULL,NULL),(336,82,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-15 10:25:51',NULL,NULL),(337,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-15 10:41:53',NULL,NULL),(338,82,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-16 11:44:28',NULL,NULL),(339,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-16 11:57:49',NULL,NULL),(340,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-16 12:00:11',NULL,NULL),(341,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-16 12:01:07',NULL,NULL),(342,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-16 12:01:34',NULL,NULL),(343,82,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-19 09:47:50',NULL,NULL),(344,82,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-19 10:13:15',NULL,NULL),(345,82,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-19 10:28:42',NULL,NULL),(346,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-19 11:15:45',NULL,NULL),(347,82,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-19 11:34:01',NULL,NULL),(348,82,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-19 11:53:19',NULL,NULL),(349,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-19 12:22:29',NULL,NULL),(350,82,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-19 13:06:47',NULL,NULL),(351,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-20 12:58:50',NULL,NULL),(352,254,NULL,0,'2024-08-21 10:52:58',NULL,NULL),(353,82,NULL,0,'2024-08-21 11:35:14',NULL,NULL),(354,254,NULL,0,'2024-08-21 12:09:07',NULL,NULL),(355,254,NULL,0,'2024-08-21 13:17:05',NULL,NULL),(356,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-23 11:39:48',NULL,NULL),(357,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-23 11:41:36',NULL,NULL),(358,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-23 11:49:27',NULL,NULL),(359,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-23 11:56:53',NULL,NULL),(360,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-23 12:15:50',NULL,NULL),(361,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-23 12:19:46',NULL,NULL),(362,254,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-23 12:39:07',NULL,NULL),(363,82,NULL,0,'2024-08-27 09:56:09',NULL,NULL),(364,82,NULL,0,'2024-08-27 11:08:51',NULL,NULL),(365,235,NULL,0,'2024-08-27 11:10:19',NULL,NULL),(366,82,NULL,0,'2024-08-27 11:12:34',NULL,NULL),(367,235,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-27 11:39:15',NULL,NULL),(368,82,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-28 10:19:36',NULL,NULL),(369,82,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-28 10:21:29',NULL,NULL),(370,82,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-28 10:22:11',NULL,NULL),(371,235,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-28 10:24:56',NULL,NULL),(372,82,NULL,0,'2024-08-28 10:27:59',NULL,NULL),(373,252,'Tienes una nueva solicitud pendiente de revisión.',0,'2024-08-28 10:40:25',NULL,NULL);
/*!40000 ALTER TABLE `notificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacion_medico`
--

DROP TABLE IF EXISTS `notificacion_medico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacion_medico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `mensaje` text,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `leida` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_usuario_noti_id_idx` (`usuario_id`),
  CONSTRAINT `fk_usuario_noti_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacion_medico`
--

LOCK TABLES `notificacion_medico` WRITE;
/*!40000 ALTER TABLE `notificacion_medico` DISABLE KEYS */;
INSERT INTO `notificacion_medico` VALUES (3,78,'Se ha creado una nueva cita médica para el empleado Este Es Un Empleado.','2024-07-29 16:50:39',NULL),(4,78,'Se ha creado una nueva cita médica para el empleado Este Es Un Empleado.','2024-07-29 16:51:18',NULL),(5,78,'Se ha creado una nueva cita médica para el empleado Este Es Un Empleado.','2024-07-29 17:22:27',NULL);
/*!40000 ALTER TABLE `notificacion_medico` ENABLE KEYS */;
UNLOCK TABLES;

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

--
-- Table structure for table `periodo_vacacional`
--

DROP TABLE IF EXISTS `periodo_vacacional`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `periodo_vacacional` (
  `id` int NOT NULL AUTO_INCREMENT,
  `año` varchar(8) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_final` date DEFAULT NULL,
  `original` varchar(3) DEFAULT NULL,
  `dias_vacaciones_periodo` int DEFAULT NULL,
  `dias_disponibles` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=218 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `periodo_vacacional`
--

LOCK TABLES `periodo_vacacional` WRITE;
/*!40000 ALTER TABLE `periodo_vacacional` DISABLE KEYS */;
INSERT INTO `periodo_vacacional` VALUES (1,'2024','2024-05-30','2024-05-30','Si',NULL,NULL),(2,NULL,NULL,NULL,NULL,NULL,NULL),(3,'2024','2024-05-01','2024-05-04','Si',NULL,NULL),(4,'2026','2024-05-22','2024-05-25','Si',NULL,NULL),(5,NULL,NULL,NULL,NULL,NULL,NULL),(6,NULL,NULL,NULL,NULL,NULL,NULL),(7,'2023','2024-11-14','2024-11-17','No',12,8),(8,'2024','2024-06-03','2024-06-15','Si',13,0),(9,NULL,NULL,NULL,NULL,13,NULL),(10,NULL,NULL,NULL,NULL,0,NULL),(12,NULL,NULL,NULL,NULL,0,NULL),(14,NULL,NULL,NULL,NULL,0,NULL),(15,NULL,NULL,NULL,NULL,0,NULL),(16,NULL,NULL,NULL,NULL,0,NULL),(17,NULL,NULL,NULL,NULL,0,NULL),(18,NULL,NULL,NULL,NULL,0,NULL),(19,NULL,NULL,NULL,NULL,0,NULL),(20,NULL,NULL,NULL,NULL,11,NULL),(21,NULL,NULL,NULL,NULL,12,NULL),(24,NULL,NULL,NULL,NULL,14,NULL),(25,NULL,NULL,NULL,NULL,11,NULL),(27,NULL,NULL,NULL,NULL,0,NULL),(28,NULL,NULL,NULL,NULL,0,NULL),(30,NULL,NULL,NULL,NULL,0,NULL),(31,NULL,NULL,NULL,NULL,0,NULL),(32,NULL,NULL,NULL,NULL,0,NULL),(33,NULL,NULL,NULL,NULL,0,NULL),(34,NULL,NULL,NULL,NULL,0,NULL),(35,NULL,NULL,NULL,NULL,0,NULL),(36,NULL,NULL,NULL,NULL,0,NULL),(37,NULL,NULL,NULL,NULL,0,NULL),(38,NULL,NULL,NULL,NULL,0,NULL),(39,NULL,NULL,NULL,NULL,0,NULL),(40,'2024',NULL,NULL,'',0,NULL),(41,NULL,NULL,NULL,NULL,0,NULL),(42,NULL,NULL,NULL,NULL,0,NULL),(43,NULL,NULL,NULL,NULL,0,NULL),(44,NULL,NULL,NULL,NULL,0,NULL),(45,NULL,NULL,NULL,NULL,0,NULL),(50,NULL,NULL,NULL,NULL,0,NULL),(53,NULL,NULL,NULL,NULL,0,NULL),(56,NULL,NULL,NULL,NULL,0,NULL),(58,NULL,NULL,NULL,NULL,0,NULL),(61,NULL,NULL,NULL,NULL,0,NULL),(62,NULL,NULL,NULL,NULL,0,NULL),(96,NULL,NULL,NULL,NULL,0,NULL),(97,NULL,NULL,NULL,NULL,0,NULL),(98,NULL,NULL,NULL,NULL,0,NULL),(102,NULL,NULL,NULL,NULL,0,NULL),(105,NULL,NULL,NULL,NULL,0,NULL),(106,NULL,NULL,NULL,NULL,0,NULL),(107,NULL,NULL,NULL,NULL,0,NULL),(108,NULL,NULL,NULL,NULL,0,NULL),(194,NULL,NULL,NULL,NULL,0,NULL),(195,NULL,NULL,NULL,NULL,0,NULL),(196,'2023','2024-04-11','2024-04-23','No',13,0),(198,'2024','2024-11-01','2024-11-08','No',11,3),(204,NULL,NULL,NULL,NULL,13,NULL),(205,NULL,NULL,NULL,NULL,14,NULL),(207,NULL,NULL,NULL,NULL,12,NULL),(208,NULL,NULL,NULL,NULL,15,NULL),(214,NULL,NULL,NULL,NULL,10,NULL),(215,NULL,NULL,NULL,NULL,10,NULL),(216,NULL,NULL,NULL,NULL,11,NULL),(217,'2024','2024-08-23','2024-08-28','No',12,6);
/*!40000 ALTER TABLE `periodo_vacacional` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `periodo_vacacional_historial`
--

DROP TABLE IF EXISTS `periodo_vacacional_historial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `periodo_vacacional_historial` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `periodo` varchar(20) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_final` date DEFAULT NULL,
  `año` varchar(4) DEFAULT NULL,
  `dias_disponibles` int DEFAULT NULL,
  `original` varchar(3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_historial_periodos_idx` (`empleado_id`),
  CONSTRAINT `fk_empleado_historial_periodos` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `periodo_vacacional_historial`
--

LOCK TABLES `periodo_vacacional_historial` WRITE;
/*!40000 ALTER TABLE `periodo_vacacional_historial` DISABLE KEYS */;
INSERT INTO `periodo_vacacional_historial` VALUES (2,NULL,'primer periodo','2024-05-01','2024-05-10','2024',2,'Si',NULL),(3,NULL,'segundo periodo','2024-09-04','2024-09-11','2024',4,'Si',NULL),(4,NULL,'primer periodo','2024-05-08','2024-05-17','2024',3,'Si',NULL),(5,NULL,'primer periodo','2024-03-01','2024-03-13','2024',0,'Si',NULL),(6,NULL,'segundo periodo','2024-08-01','2024-08-13','2024',0,'Si',NULL),(15,101,'segundo periodo','2024-08-23','2024-09-02','2024',1,'No',NULL),(16,90,'primer periodo','2024-04-02','2024-04-12','2024',0,'No',NULL);
/*!40000 ALTER TABLE `periodo_vacacional_historial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso_economico`
--

DROP TABLE IF EXISTS `permiso_economico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permiso_economico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `solicitud_id` int DEFAULT NULL,
  `motivo_fecha_permiso_id` int DEFAULT NULL,
  `fecha_permiso_anterior` date DEFAULT NULL,
  `no_permiso_anterior` int DEFAULT NULL,
  `nombre_jefe_departamento` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_permiso_economico_idx` (`empleado_id`),
  KEY `fk_solicitud_permiso_economico_idx` (`solicitud_id`),
  KEY `fk_motivo_fecha_permiso_economico_idx` (`motivo_fecha_permiso_id`),
  CONSTRAINT `fk_empleado_permiso_economico` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_motivo_fecha_permiso_economico` FOREIGN KEY (`motivo_fecha_permiso_id`) REFERENCES `motivo_fecha_permiso` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_permiso_economico` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso_economico`
--

LOCK TABLES `permiso_economico` WRITE;
/*!40000 ALTER TABLE `permiso_economico` DISABLE KEYS */;
INSERT INTO `permiso_economico` VALUES (53,72,444,321,NULL,NULL,NULL),(54,72,480,326,'2024-08-19',1,NULL);
/*!40000 ALTER TABLE `permiso_economico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso_fuera_trabajo`
--

DROP TABLE IF EXISTS `permiso_fuera_trabajo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permiso_fuera_trabajo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `solicitud_id` int DEFAULT NULL,
  `motivo_fecha_permiso_id` int DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `hora_regreso` time DEFAULT NULL,
  `fecha_a_reponer` date DEFAULT NULL,
  `horario_fecha_a_reponer` time DEFAULT NULL,
  `nombre_jefe_departamento` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pft_empleado_idx` (`empleado_id`),
  KEY `fk_motivo_fecha_permiso_idx` (`motivo_fecha_permiso_id`),
  KEY `fk_solicitud_pft_idx` (`solicitud_id`),
  CONSTRAINT `fk_motivo_fecha_permiso` FOREIGN KEY (`motivo_fecha_permiso_id`) REFERENCES `motivo_fecha_permiso` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pft_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_pft` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitud` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso_fuera_trabajo`
--

LOCK TABLES `permiso_fuera_trabajo` WRITE;
/*!40000 ALTER TABLE `permiso_fuera_trabajo` DISABLE KEYS */;
INSERT INTO `permiso_fuera_trabajo` VALUES (59,72,439,316,'09:47:00','10:47:00','2024-08-20','15:00:00',NULL),(60,72,478,324,'10:19:00','11:19:00','2024-08-29','10:19:00',NULL);
/*!40000 ALTER TABLE `permiso_fuera_trabajo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso_sin_sueldo`
--

DROP TABLE IF EXISTS `permiso_sin_sueldo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permiso_sin_sueldo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `solicitud_id` int DEFAULT NULL,
  `motivo_fecha_permiso_id` int DEFAULT NULL,
  `fecha_permiso_anterior` date DEFAULT NULL,
  `no_permiso_anterior` int DEFAULT NULL,
  `nombre_jefe_departamento` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_permiso_sin_sueldo_idx` (`empleado_id`),
  KEY `fk_solicitud_permiso_sin_sueldo_idx` (`solicitud_id`),
  KEY `fk_motivo_fecha_permiso_sin_sueldo_idx` (`motivo_fecha_permiso_id`),
  CONSTRAINT `fk_empleado_permiso_sin_sueldo` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_motivo_fecha_permiso_sin_sueldo` FOREIGN KEY (`motivo_fecha_permiso_id`) REFERENCES `motivo_fecha_permiso` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_permiso_sin_sueldo` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso_sin_sueldo`
--

LOCK TABLES `permiso_sin_sueldo` WRITE;
/*!40000 ALTER TABLE `permiso_sin_sueldo` DISABLE KEYS */;
/*!40000 ALTER TABLE `permiso_sin_sueldo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receta_medica`
--

DROP TABLE IF EXISTS `receta_medica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `receta_medica` (
  `id` int NOT NULL AUTO_INCREMENT,
  `consulta_medica_id` int NOT NULL,
  `medicamento_recetado` text NOT NULL,
  `fecha_receta` date NOT NULL,
  `duracion_medicamento` text NOT NULL,
  `consultorio` varchar(45) NOT NULL,
  PRIMARY KEY (`id`,`consulta_medica_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receta_medica`
--

LOCK TABLES `receta_medica` WRITE;
/*!40000 ALTER TABLE `receta_medica` DISABLE KEYS */;
/*!40000 ALTER TABLE `receta_medica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reporte_tiempo_extra`
--

DROP TABLE IF EXISTS `reporte_tiempo_extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reporte_tiempo_extra` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `solicitud_id` int DEFAULT NULL,
  `total_horas` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_tiempo_extra_idx` (`empleado_id`),
  KEY `fk_solicitud_tiempo_extra_idx` (`solicitud_id`),
  CONSTRAINT `fk_empleado_tiempo_extra` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_tiempo_extra` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reporte_tiempo_extra`
--

LOCK TABLES `reporte_tiempo_extra` WRITE;
/*!40000 ALTER TABLE `reporte_tiempo_extra` DISABLE KEYS */;
INSERT INTO `reporte_tiempo_extra` VALUES (12,101,438,2,'2024-08-19 09:04:34'),(13,72,447,3,'2024-08-19 13:23:43'),(14,101,449,2,'2024-08-21 09:56:02'),(15,101,450,0,'2024-08-21 10:10:21'),(16,90,474,4,'2024-08-27 11:09:52'),(17,72,482,4,'2024-08-28 10:27:22');
/*!40000 ALTER TABLE `reporte_tiempo_extra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reporte_tiempo_extra_general`
--

DROP TABLE IF EXISTS `reporte_tiempo_extra_general`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reporte_tiempo_extra_general` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `solicitud_id` int DEFAULT NULL,
  `total_horas` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_tiempo_extra_gral_idx` (`empleado_id`),
  KEY `fk_solicitud_tiempo_extra_gral_idx` (`solicitud_id`),
  CONSTRAINT `fk_empleado_tiempo_extra_gral` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_tiempo_extra_gral` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitud` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reporte_tiempo_extra_general`
--

LOCK TABLES `reporte_tiempo_extra_general` WRITE;
/*!40000 ALTER TABLE `reporte_tiempo_extra_general` DISABLE KEYS */;
INSERT INTO `reporte_tiempo_extra_general` VALUES (4,72,462,2,'2024-08-26 10:52:11'),(5,72,463,1,'2024-08-26 11:25:21'),(7,72,465,1,'2024-08-26 11:34:40'),(10,72,468,5,'2024-08-26 11:36:27'),(12,72,470,3,'2024-08-26 12:10:49'),(15,72,473,2,'2024-08-26 13:59:35'),(16,72,475,9,'2024-08-27 11:12:02');
/*!40000 ALTER TABLE `reporte_tiempo_extra_general` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `segundo_periodo_vacacional`
--

DROP TABLE IF EXISTS `segundo_periodo_vacacional`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `segundo_periodo_vacacional` (
  `id` int NOT NULL AUTO_INCREMENT,
  `año` varchar(8) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_final` date DEFAULT NULL,
  `original` varchar(3) DEFAULT NULL,
  `dias_vacaciones_periodo` int DEFAULT NULL,
  `dias_disponibles` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `segundo_periodo_vacacional`
--

LOCK TABLES `segundo_periodo_vacacional` WRITE;
/*!40000 ALTER TABLE `segundo_periodo_vacacional` DISABLE KEYS */;
INSERT INTO `segundo_periodo_vacacional` VALUES (1,'2025','2024-05-28','2024-05-31','Si',NULL,NULL),(2,NULL,NULL,NULL,NULL,NULL,NULL),(3,NULL,NULL,NULL,NULL,NULL,NULL),(4,'2022','2024-12-05','2024-12-08','No',12,8),(5,'2024','2024-07-03','2024-07-12','Si',13,3),(6,NULL,NULL,NULL,NULL,13,NULL),(7,NULL,NULL,NULL,NULL,0,NULL),(9,NULL,NULL,NULL,NULL,0,NULL),(11,NULL,NULL,NULL,NULL,0,NULL),(12,NULL,NULL,NULL,NULL,0,NULL),(13,NULL,NULL,NULL,NULL,0,NULL),(14,NULL,NULL,NULL,NULL,0,NULL),(15,NULL,NULL,NULL,NULL,0,NULL),(16,NULL,NULL,NULL,NULL,0,NULL),(17,NULL,NULL,NULL,NULL,11,NULL),(18,NULL,NULL,NULL,NULL,12,NULL),(21,NULL,NULL,NULL,NULL,14,NULL),(22,NULL,NULL,NULL,NULL,11,NULL),(24,NULL,NULL,NULL,NULL,0,NULL),(25,NULL,NULL,NULL,NULL,0,NULL),(27,NULL,NULL,NULL,NULL,0,NULL),(28,NULL,NULL,NULL,NULL,0,NULL),(29,NULL,NULL,NULL,NULL,0,NULL),(30,NULL,NULL,NULL,NULL,0,NULL),(31,NULL,NULL,NULL,NULL,0,NULL),(32,NULL,NULL,NULL,NULL,0,NULL),(33,NULL,NULL,NULL,NULL,0,NULL),(34,NULL,NULL,NULL,NULL,0,NULL),(35,NULL,NULL,NULL,NULL,0,NULL),(36,NULL,NULL,NULL,NULL,0,NULL),(37,NULL,NULL,NULL,NULL,0,NULL),(38,NULL,NULL,NULL,NULL,0,NULL),(39,NULL,NULL,NULL,NULL,0,NULL),(40,NULL,NULL,NULL,NULL,0,NULL),(41,NULL,NULL,NULL,NULL,0,NULL),(42,NULL,NULL,NULL,NULL,0,NULL),(47,NULL,NULL,NULL,NULL,0,NULL),(50,NULL,NULL,NULL,NULL,0,NULL),(53,NULL,NULL,NULL,NULL,0,NULL),(55,NULL,NULL,NULL,NULL,0,NULL),(58,NULL,NULL,NULL,NULL,0,NULL),(59,NULL,NULL,NULL,NULL,0,NULL),(93,NULL,NULL,NULL,NULL,0,NULL),(94,NULL,NULL,NULL,NULL,0,NULL),(95,NULL,NULL,NULL,NULL,0,NULL),(99,NULL,NULL,NULL,NULL,0,NULL),(102,NULL,NULL,NULL,NULL,0,NULL),(103,NULL,NULL,NULL,NULL,0,NULL),(104,NULL,NULL,NULL,NULL,0,NULL),(105,NULL,NULL,NULL,NULL,0,NULL),(191,NULL,NULL,NULL,NULL,0,NULL),(192,NULL,NULL,NULL,NULL,0,NULL),(193,'2024','2024-12-01','2024-12-13','No',13,0),(195,'2024','2024-10-01','2024-10-11','Si',11,0),(201,NULL,NULL,NULL,NULL,13,NULL),(202,NULL,NULL,NULL,NULL,14,NULL),(204,NULL,NULL,NULL,NULL,12,NULL),(205,NULL,NULL,NULL,NULL,15,NULL),(211,NULL,NULL,NULL,NULL,10,NULL),(212,NULL,NULL,NULL,NULL,10,NULL),(213,NULL,NULL,NULL,NULL,11,NULL),(214,'2024','2024-08-23','2024-09-02','No',12,1);
/*!40000 ALTER TABLE `segundo_periodo_vacacional` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud`
--

DROP TABLE IF EXISTS `solicitud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitud` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empleado_id` int DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `comentario` text,
  `fecha_aprobacion` datetime DEFAULT NULL,
  `nombre_aprobante` varchar(90) DEFAULT NULL,
  `nombre_formato` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `aprobacion` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_solicitud_empleado_idx` (`empleado_id`),
  CONSTRAINT `fk_solicitud_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=484 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud`
--

LOCK TABLES `solicitud` WRITE;
/*!40000 ALTER TABLE `solicitud` DISABLE KEYS */;
INSERT INTO `solicitud` VALUES (426,101,'2024-08-13','Visto','',NULL,NULL,'COMISION ESPECIAL','2024-08-13 12:00:02',NULL),(427,98,'2024-08-15','Nueva','',NULL,NULL,'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL','2024-08-15 10:17:37',NULL),(428,72,'2024-08-15','Nueva','',NULL,NULL,'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL','2024-08-15 10:25:26',NULL),(429,72,'2024-08-15','Visto','',NULL,NULL,'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL','2024-08-15 10:25:51',NULL),(430,101,'2024-08-15','Visto','',NULL,NULL,'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL','2024-08-15 10:41:53',NULL),(431,101,'2024-08-15','Visto','',NULL,NULL,'CITA MEDICA','2024-08-15 12:59:54',NULL),(432,72,'2024-08-16','Nueva','',NULL,NULL,'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL','2024-08-16 11:44:28',NULL),(433,101,'2024-08-16','Nueva','',NULL,NULL,'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL','2024-08-16 11:57:49',NULL),(434,101,'2024-08-16','Nueva','',NULL,NULL,'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL','2024-08-16 12:00:11',NULL),(435,101,'2024-08-16','Nueva','',NULL,NULL,'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL','2024-08-16 12:01:07',NULL),(436,101,'2024-08-16','Visto','',NULL,NULL,'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL','2024-08-16 12:01:34',NULL),(437,101,'2024-08-16','Visto','',NULL,NULL,'CITA MEDICA','2024-08-16 22:26:50','PENDIENTE'),(438,101,'2024-08-19','Nueva','',NULL,NULL,'REPORTE DE TIEMPO EXTRA','2024-08-19 09:04:34',NULL),(439,72,'2024-08-19','Visto','',NULL,NULL,'PERMISO FUERA DEL TRABAJO','2024-08-19 09:47:50',NULL),(440,72,'2024-08-19','Nueva','',NULL,NULL,'COMISION ESPECIAL','2024-08-19 10:13:15',NULL),(441,72,'2024-08-19','Nueva','',NULL,NULL,'CAMBIO DE DIA LABORAL','2024-08-19 10:28:42',NULL),(442,101,'2024-08-19','Nueva','',NULL,NULL,'COMISION ESPECIAL','2024-08-19 11:15:45',NULL),(443,72,'2024-08-19','Nueva','',NULL,NULL,'CAMBIO DE HORARIO DE TRABAJO','2024-08-19 11:34:01',NULL),(444,72,'2024-08-19','Visto','',NULL,NULL,'PERMISO ECONOMICO','2024-08-19 11:53:19',NULL),(446,72,'2024-08-19','Nueva','',NULL,NULL,'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL','2024-08-19 13:06:47',NULL),(447,72,'2024-08-19','Visto','',NULL,NULL,'REPORTE DE TIEMPO EXTRA','2024-08-19 13:23:43',NULL),(448,101,'2024-08-20','Visto','',NULL,NULL,'COMISION ESPECIAL','2024-08-20 12:58:50',NULL),(449,101,'2024-08-21','Visto','OK','2024-08-21 12:09:07','Sergio Eli Peña Paniagua','REPORTE DE TIEMPO EXTRA','2024-08-21 09:56:02','APROBADO'),(450,101,'2024-08-21','Visto','todo bien','2024-08-21 13:17:05','Sergio Eli Peña Paniagua','REPORTE DE TIEMPO EXTRA','2024-08-21 10:10:21','RECHAZADO'),(451,72,'2024-08-21','Visto','ok','2024-08-21 11:45:19','Sergio Eli Peña Paniagua','CITA MEDICA','2024-08-21 11:22:55','RECHAZADO'),(458,101,'2024-08-23','Visto','',NULL,NULL,'CAMBIO DE PERIODO VACACIONAL','2024-08-23 12:39:06',NULL),(462,72,'2024-08-26','Nueva','',NULL,NULL,'REPORTE DE TIEMPO EXTRA','2024-08-26 10:52:11','PENDIENTE'),(463,72,'2024-08-26','Nueva','',NULL,NULL,'REPORTE DE TIEMPO EXTRA GENERAL','2024-08-26 11:25:21','PENDIENTE'),(465,72,'2024-08-26','Nueva','',NULL,NULL,'REPORTE DE TIEMPO EXTRA GENERAL','2024-08-26 11:34:40','PENDIENTE'),(468,72,'2024-08-26','Visto','',NULL,NULL,'REPORTE DE TIEMPO EXTRA GENERAL','2024-08-26 11:36:27','PENDIENTE'),(470,72,'2024-08-26','Visto','',NULL,NULL,'REPORTE DE TIEMPO EXTRA GENERAL','2024-08-26 12:10:49','PENDIENTE'),(473,72,'2024-08-26','Visto','va','2024-08-27 11:08:51','Sergio Eli Peña Paniagua','REPORTE DE TIEMPO EXTRA GENERAL','2024-08-26 13:59:35','RECHAZADO'),(474,90,'2024-08-27','Visto','SI','2024-08-27 11:10:19','Sergio Eli Peña Paniagua','REPORTE DE TIEMPO EXTRA','2024-08-27 11:09:52','APROBADO'),(475,72,'2024-08-27','Visto','VA','2024-08-27 11:12:34','Sergio Eli Peña Paniagua','REPORTE DE TIEMPO EXTRA GENERAL','2024-08-27 11:12:02','APROBADO'),(476,90,'2024-08-27','Visto','',NULL,NULL,'COMISION ESPECIAL','2024-08-27 11:39:15',NULL),(477,90,'2024-08-28','Visto','','2024-08-28 09:49:57','Sergio Eli Peña Paniagua','CITA MEDICA','2024-08-28 09:48:10','APROBADO'),(478,72,'2024-08-28','Nueva','',NULL,NULL,'PERMISO FUERA DEL TRABAJO','2024-08-28 10:19:36',NULL),(479,72,'2024-08-28','Nueva','',NULL,NULL,'COMISION ESPECIAL','2024-08-28 10:21:29',NULL),(480,72,'2024-08-28','Nueva','',NULL,NULL,'PERMISO ECONOMICO','2024-08-28 10:22:11',NULL),(481,90,'2024-08-28','Nueva','',NULL,NULL,'CAMBIO DE PERIODO VACACIONAL','2024-08-28 10:24:56',NULL),(482,72,'2024-08-28','Visto','','2024-08-28 10:27:59','Sergio Eli Peña Paniagua','REPORTE DE TIEMPO EXTRA','2024-08-28 10:27:22','APROBADO'),(483,99,'2024-08-28','Nueva','',NULL,NULL,'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL','2024-08-28 10:40:25',NULL);
/*!40000 ALTER TABLE `solicitud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` smallint NOT NULL DEFAULT '10',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `password` char(64) NOT NULL,
  `status` smallint NOT NULL,
  `rol` smallint NOT NULL,
  `nuevo` smallint NOT NULL,
  `username` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=255 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (78,'$2y$13$xf2TuMzCcUCI7U4VYlEhUeQlhrXlal4GLb5yQN5HEDHSIkW1Z263e',10,3,0,'mmedioe'),(82,'$2y$13$a5Ppb.TbptFUwk.nO5zQB.MjmsFV8C434/MIw/USV0Yo4r6svFgn.',10,1,0,'eune'),(235,'$2y$13$BmDXqGZ8Knafbtcd2fUhLOlRqsfooud9zkOegFJz/20xBplwbQRUW',10,1,0,'ocarilloi'),(242,'$2y$13$10l6UoaOI9ym0GQMXGVuOePL.DJKK7B8xpWJ6bEzR.v1kLYsjDMa.',0,1,0,'aavilesg'),(251,'$2y$13$.Dpg.z5wDD5MneoDEfUXM.8LvklzGLrrsMVQ4EDTeSGUpAUJh2evi',10,2,0,'gestor-rh'),(252,'$2y$13$ys8xpRM8HXA3Hlcis5/fv.gziifYKsxjVellk.eRVNkRwS/16a4lW',10,1,0,'vaguilarn'),(253,'$2y$13$KhepoN73Wejni3To5zVEu.qTDuPxcqFkWCyXWKayY7rSgyP9IXsVK',10,1,4,'umaciasa'),(254,'$2y$13$ZXHqnhHpr7PYyYYfAF89NOJ45vi.4SDbtLqWyT1RC5C7znySjcyau',10,1,0,'jvivancom');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacaciones`
--

DROP TABLE IF EXISTS `vacaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vacaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `periodo_vacacional_id` int DEFAULT NULL,
  `empleado_id` int DEFAULT NULL,
  `dias_vacaciones` int DEFAULT NULL,
  `total_dias_vacaciones` int DEFAULT NULL,
  `segundo_periodo_vacacional_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_periodo_vacacional_idx` (`periodo_vacacional_id`),
  KEY `fk_empleado_vacaciones_idx` (`empleado_id`),
  KEY `fk_segundo_periodo_vacacional_idx` (`segundo_periodo_vacacional_id`),
  CONSTRAINT `fk_empleado_vacaciones` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_periodo_vacacional` FOREIGN KEY (`periodo_vacacional_id`) REFERENCES `periodo_vacacional` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_segundo_periodo_vacacional` FOREIGN KEY (`segundo_periodo_vacacional_id`) REFERENCES `segundo_periodo_vacacional` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=218 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacaciones`
--

LOCK TABLES `vacaciones` WRITE;
/*!40000 ALTER TABLE `vacaciones` DISABLE KEYS */;
INSERT INTO `vacaciones` VALUES (2,1,NULL,NULL,NULL,NULL),(3,3,NULL,NULL,NULL,NULL),(4,4,NULL,NULL,NULL,1),(5,5,NULL,NULL,NULL,2),(6,6,NULL,NULL,34,3),(7,7,NULL,NULL,24,4),(8,8,NULL,NULL,26,5),(9,9,NULL,NULL,26,6),(10,10,NULL,NULL,0,7),(12,12,NULL,NULL,0,9),(14,14,NULL,NULL,0,11),(15,15,NULL,NULL,0,12),(16,16,NULL,NULL,0,13),(17,17,NULL,NULL,0,14),(18,18,NULL,NULL,0,15),(19,19,NULL,NULL,0,16),(20,20,NULL,NULL,22,17),(21,21,NULL,NULL,24,18),(24,24,NULL,NULL,28,21),(25,25,NULL,NULL,22,22),(27,27,NULL,NULL,0,24),(28,28,NULL,NULL,0,25),(30,30,NULL,NULL,0,27),(31,31,NULL,NULL,0,28),(32,32,NULL,NULL,0,29),(33,33,NULL,NULL,0,30),(34,34,NULL,NULL,0,31),(35,35,NULL,NULL,0,32),(36,36,NULL,NULL,0,33),(37,37,NULL,NULL,0,34),(38,38,NULL,NULL,0,35),(39,39,NULL,NULL,0,36),(40,40,NULL,NULL,0,37),(41,41,NULL,NULL,0,38),(42,42,NULL,NULL,0,39),(43,43,NULL,NULL,0,40),(44,44,NULL,NULL,0,41),(45,45,NULL,NULL,22,42),(50,50,NULL,NULL,0,47),(53,53,NULL,NULL,0,50),(56,56,NULL,NULL,0,53),(58,58,NULL,NULL,0,55),(61,61,NULL,NULL,0,58),(62,62,NULL,NULL,0,59),(96,96,NULL,NULL,0,93),(97,97,NULL,NULL,0,94),(98,98,NULL,NULL,0,95),(102,102,NULL,NULL,0,99),(105,105,NULL,NULL,0,102),(106,106,NULL,NULL,0,103),(107,107,NULL,NULL,0,104),(108,108,NULL,NULL,0,105),(194,194,NULL,NULL,0,191),(195,195,NULL,NULL,0,192),(196,196,NULL,NULL,26,193),(198,198,NULL,NULL,20,195),(204,204,NULL,NULL,26,201),(205,205,NULL,NULL,28,202),(207,207,NULL,NULL,24,204),(208,208,NULL,NULL,30,205),(214,214,NULL,NULL,20,211),(215,215,NULL,NULL,20,212),(216,216,NULL,NULL,22,213),(217,217,NULL,NULL,24,214);
/*!40000 ALTER TABLE `vacaciones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:57:31
