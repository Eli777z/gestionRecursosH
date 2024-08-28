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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:55:54
