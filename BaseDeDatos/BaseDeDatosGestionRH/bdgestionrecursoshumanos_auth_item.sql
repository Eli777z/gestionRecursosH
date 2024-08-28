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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:56:09
