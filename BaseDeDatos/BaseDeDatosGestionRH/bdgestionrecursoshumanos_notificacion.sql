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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:56:01
