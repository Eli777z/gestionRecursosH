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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:55:59
