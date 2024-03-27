-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: il3_eureka
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB-1:10.4.32+maria~ubu2004

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `il3_departamento`
--

DROP TABLE IF EXISTS `il3_departamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `il3_departamento` (
  `idDepartamento` int(11) NOT NULL AUTO_INCREMENT,
  `idEmpresa` int(11) DEFAULT NULL,
  `dsDepartamento` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idDepartamento`),
  KEY `fk_il3_departamento_il3_empresa_idx` (`idEmpresa`),
  CONSTRAINT `fk_il3_departamento_il3_empresa` FOREIGN KEY (`idEmpresa`) REFERENCES `il3_empresa` (`idEmpresa`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `il3_departamento`
--

LOCK TABLES `il3_departamento` WRITE;
/*!40000 ALTER TABLE `il3_departamento` DISABLE KEYS */;
INSERT INTO `il3_departamento` VALUES (1,1,'Departamento1.1'),(2,1,'Departamento1.2'),(3,2,'Departamento2.1'),(4,2,'Departamento2.2');
/*!40000 ALTER TABLE `il3_departamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `il3_empresa`
--

DROP TABLE IF EXISTS `il3_empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `il3_empresa` (
  `idEmpresa` int(11) NOT NULL AUTO_INCREMENT,
  `dsEmpresa` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idEmpresa`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `il3_empresa`
--

LOCK TABLES `il3_empresa` WRITE;
/*!40000 ALTER TABLE `il3_empresa` DISABLE KEYS */;
INSERT INTO `il3_empresa` VALUES (1,'Empresa1'),(2,'Empesa2');
/*!40000 ALTER TABLE `il3_empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `il3_permiso`
--

DROP TABLE IF EXISTS `il3_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `il3_permiso` (
  `idPermiso` int(11) NOT NULL AUTO_INCREMENT,
  `dsPermiso` varchar(45) DEFAULT NULL,
  `cdPermiso` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idPermiso`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `il3_permiso`
--

LOCK TABLES `il3_permiso` WRITE;
/*!40000 ALTER TABLE `il3_permiso` DISABLE KEYS */;
INSERT INTO `il3_permiso` VALUES (1,'opcion 1','opc1'),(2,'opcion 2','opc2'),(3,'opcion 3','opc3'),(4,'opcion 4','opc4'),(5,'opcion 5','opc5');
/*!40000 ALTER TABLE `il3_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `il3_rol`
--

DROP TABLE IF EXISTS `il3_rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `il3_rol` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT,
  `dsRol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idRol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `il3_rol`
--

LOCK TABLES `il3_rol` WRITE;
/*!40000 ALTER TABLE `il3_rol` DISABLE KEYS */;
INSERT INTO `il3_rol` VALUES (1,'admin'),(2,'gestor'),(3,'visitante');
/*!40000 ALTER TABLE `il3_rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `il3_rol_permiso`
--

DROP TABLE IF EXISTS `il3_rol_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `il3_rol_permiso` (
  `idRol` int(11) NOT NULL,
  `idPermiso` int(11) NOT NULL,
  PRIMARY KEY (`idRol`,`idPermiso`),
  KEY `fk_il3_rol_permiso_il3_rol1_idx` (`idRol`),
  KEY `fk_il3_rol_permiso_il3_permiso1_idx` (`idPermiso`),
  CONSTRAINT `fk_il3_rol_permiso_il3_permiso1` FOREIGN KEY (`idPermiso`) REFERENCES `il3_permiso` (`idPermiso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_il3_rol_permiso_il3_rol1` FOREIGN KEY (`idRol`) REFERENCES `il3_rol` (`idRol`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `il3_rol_permiso`
--

LOCK TABLES `il3_rol_permiso` WRITE;
/*!40000 ALTER TABLE `il3_rol_permiso` DISABLE KEYS */;
INSERT INTO `il3_rol_permiso` VALUES (1,1),(1,2),(2,1),(2,3),(3,5);
/*!40000 ALTER TABLE `il3_rol_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `il3_usuario`
--

DROP TABLE IF EXISTS `il3_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `il3_usuario` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `idDepartamento` int(11) DEFAULT NULL,
  `dsUsuario` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idUsuario`),
  KEY `fk_il3_usuaro_il3_departamento1_idx` (`idDepartamento`),
  CONSTRAINT `fk_il3_usuaro_il3_departamento1` FOREIGN KEY (`idDepartamento`) REFERENCES `il3_departamento` (`idDepartamento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `il3_usuario`
--

LOCK TABLES `il3_usuario` WRITE;
/*!40000 ALTER TABLE `il3_usuario` DISABLE KEYS */;
INSERT INTO `il3_usuario` VALUES (1,1,'prueba');
/*!40000 ALTER TABLE `il3_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `il3_usuario_rol`
--

DROP TABLE IF EXISTS `il3_usuario_rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `il3_usuario_rol` (
  `idUsuario` int(11) NOT NULL,
  `idRol` int(11) NOT NULL,
  PRIMARY KEY (`idUsuario`,`idRol`),
  KEY `fk_table1_il3_usuaro1_idx` (`idUsuario`),
  KEY `fk_table1_il3_usuario_rol1_idx` (`idRol`),
  CONSTRAINT `fk_table1_il3_usuario_rol1` FOREIGN KEY (`idRol`) REFERENCES `il3_rol` (`idRol`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_il3_usuaro1` FOREIGN KEY (`idUsuario`) REFERENCES `il3_usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `il3_usuario_rol`
--

LOCK TABLES `il3_usuario_rol` WRITE;
/*!40000 ALTER TABLE `il3_usuario_rol` DISABLE KEYS */;
INSERT INTO `il3_usuario_rol` VALUES (1,1),(1,2);
/*!40000 ALTER TABLE `il3_usuario_rol` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-14 18:44:33
