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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:55:53
