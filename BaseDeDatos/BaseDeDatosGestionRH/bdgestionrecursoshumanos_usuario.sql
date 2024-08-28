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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:56:00
