--

DROP TABLE IF EXISTS `desembolso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `desembolso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `credito_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `comprobante_id` int DEFAULT NULL,
  `fecha_desembolso` date NOT NULL,
  `comprobante_externo` varchar(45) DEFAULT NULL,
  `desembolso_at` datetime DEFAULT NULL,
  `desembolso_in` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_desembolso_credito1_idx` (`credito_id`),
  KEY `fk_desembolso_usuario1_idx` (`usuario_id`),
  KEY `fk_desembolso_comprobante1_idx` (`comprobante_id`),
  CONSTRAINT `fk_desembolso_comprobante1` FOREIGN KEY (`comprobante_id`) REFERENCES `comprobante` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_desembolso_credito1` FOREIGN KEY (`credito_id`) REFERENCES `credito` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_desembolso_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=744 DEFAULT CHARSET=utf8mb3;



DROP TABLE IF EXISTS `modalidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modalidad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `modalidad` varchar(255) NOT NULL,
  `despacho` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modalidad`
--

LOCK TABLES `modalidad` WRITE;
/*!40000 ALTER TABLE `modalidad` DISABLE KEYS */;
INSERT INTO `modalidad` VALUES (1,'TODAS',0),(2,'COLECTIVO',0),(3,'URBANO',0),(4,'INTERMUNICIPAL',1),
(5,'MIXTO',1),(6,'PRIVADO',0),(7,'ESPECIAL',0),(8,'ESCOLAR',0);
/*!40000 ALTER TABLE `modalidad` ENABLE KEYS */;
UNLOCK TABLES;


CREATE TABLE `sucursal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sucursal` varchar(255) NOT NULL,
  `ciudad_id` int NOT NULL,
  `contacto` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `celular` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `sucursal_at` datetime DEFAULT NULL,
  `sucursal_in` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sucursal_ciudad_idx` (`ciudad_id`),
  CONSTRAINT `fk_sucursal_ciudad1` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudad` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sucursal`
--

LOCK TABLES `sucursal` WRITE;
/*!40000 ALTER TABLE `sucursal` DISABLE KEYS */;
INSERT INTO `sucursal` VALUES (100,'OFICINA PRINCIPAL',54498,'SANDRA LEMUS',NULL,'CALLE 7 # 56-211 - LA ONDINA','5611012','3142197149',1,NULL,NULL),(101,'OFICINA PARQUE',54498,'ROBINSON ASCANIO ORTIZ',NULL,'CRA 13 # 10-35','5696999','3142154286',1,'2016-10-28 08:38:50','2022-12-12 11:28:03'),(102,'OFICINA MERCADO',54498,'JUAN GREGORIO CARRASCAL HERNANDEZ',NULL,'CALLE 8 # 13-17','5691584','3142157928',1,'2016-11-03 10:39:18','2022-12-12 11:27:11'),(103,'OFICINA CONVENCION',54206,'CARMEN CECILIA LOPEZ PALLARES',NULL,'PARQUE PRINCIPAL','5630378','3142151647',1,'2016-11-03 10:40:46','2022-12-12 11:24:41'),(104,'OFICINA AGUACHICA',20011,'CONTACTO',NULL,'TERMINAL DE TRANSPORTES','5650332','3145490918',1,'2016-11-03 10:41:44',NULL),(105,'OFICINA LA PLAYA',54398,'DIANA MARCELA GARCIA GARCIA',NULL,'PARQUE PRINCIPAL','5632131','3142155316',1,'2016-11-03 10:42:33','2022-12-12 11:26:31'),(106,'OFICINA ABREGO',54003,'SINDY LORENA CAÑIZAREZ',NULL,'CLL 14 # 5-54','3166574964','3107448726',1,'2016-11-03 10:43:18',NULL),(107,'OFICINA VALLEDUPAR',20001,'LINA PATRICIA HERNANEZ ORTIZ',NULL,'TERMINAL DE TRANSPORTES','5717517','3142179357',1,'2016-11-03 10:44:14',NULL),(108,'OFICINA EL CARMEN',54245,'X',NULL,'PARQUE PRINCIPAL',NULL,'3112743459',1,'2016-11-03 10:44:55','2022-12-12 11:23:27'),(109,'OFICINA EL CARMEN 2',54245,'LUZ MERY QUINTERO',NULL,'KDX 17-460',NULL,'3142136552',1,'2016-11-03 10:45:57','2016-11-03 10:46:04'),(110,'OFICINA ALMACEN',54498,'X',NULL,'CALLE 7 # 56-211 - LA ONDINA',NULL,NULL,1,'2017-06-07 11:36:41','2022-12-12 11:21:02'),(111,'OFICINA SANTA CLARA',54498,'NIXSON TRIGOS',NULL,'TERMINAL SANTA CLARA','5613400','3118891320',1,'2019-03-31 21:59:24','2020-12-03 10:24:53'),(112,'OFICINA PARQUE 2',54498,'HUGER CARRILLO',NULL,'PARQUE PRINCIPAL','3142154286',NULL,1,'2021-02-20 17:50:27','2023-06-15 09:27:56'),(113,'OFICINA PARQUE VALLE',54498,'KELLY PEREZ',NULL,'PARQUE PRINCIPAL VALLE','3142154286',NULL,1,'2021-12-21 11:41:24','2021-12-22 09:42:28'),(114,'OFICINA LA ONDINA',54498,'MARIA FERNANDA CARRASCAL',NULL,'CALLE 7 # 56-211 - LA ONDINA','314444103',NULL,1,'2023-05-11 15:29:10',NULL),(115,'OFICINA PARQUE 3',54498,'SANDRA LEMUS',NULL,'PARQUE PRINCIPAL','3142154286','3142154286',1,'2023-06-20 12:27:30',NULL);
/*!40000 ALTER TABLE `sucursal` ENABLE KEYS */;
UNLOCK TABLES;


CREATE TABLE `tipo_vehiculo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo_vehiculo` varchar(45) NOT NULL,
  `referencia` varchar(255) DEFAULT NULL,
  `capacidad` int(2) unsigned zerofill DEFAULT NULL,
  `filas` int unsigned DEFAULT NULL,
  `columnas` int unsigned DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb3;

LOCK TABLES `tipo_vehiculo` WRITE;
/*!40000 ALTER TABLE `tipo_vehiculo` DISABLE KEYS */;
INSERT INTO `tipo_vehiculo` VALUES (1,'AUTOMÓVIL',NULL,04,2,3,1),(2,'MICROBUS','URVAN',09,4,3,0),(3,'MICROBUS','15P',15,5,4,1),(4,'MICROBUS','19P',19,6,5,1),(5,'MICROBUS','18P',18,6,5,1),(6,'MICROBUS','06P',06,3,3,1),(7,'BUS','26P',26,8,5,1),(8,'BUS','24P',24,7,5,1),(9,'MICROBUS','CARNIVAL',07,3,3,0),(10,'MICROBUS','20P',20,6,5,1),(11,'MICROBUS','09P',09,4,4,1),(12,'MICROBUS','SPRINTER',15,5,4,0),(13,'MICROBUS','17P',17,6,4,1),(14,'MICROBUS','17P2',17,6,4,0),(15,'MICROBUS','17P3',17,6,4,0),(16,'MICROBUS','15P3',15,5,4,0),(17,'MICROBUS','18P2',18,6,5,0),(18,'CAMIONETA',NULL,07,3,3,1),(19,'JEEP',NULL,05,3,3,1),(20,'CAMION',NULL,08,2,5,1),(21,'BUSETA',NULL,22,6,5,1),(22,'MICROBUS','8P',08,4,3,1),(23,'BUS','30P',30,8,5,1),(24,'BUS','28P',28,8,5,1),(25,'BUS','34P',34,9,5,1),(26,'BUS','33P',33,10,5,1),(27,'BUS','32P',32,10,5,1),(28,'BUS','36P',36,11,5,1),(29,'MICROBUS','12P',12,4,4,1),(30,'MICROBUS','16P',16,6,4,1),(31,'MICROBUS','14P',14,6,4,1),(32,'BUS','38P',38,11,5,1),(33,'BUS','37P',37,11,5,1),(34,'BUS','25 P',25,7,5,1),(35,'MICROBUS','11 P',11,4,3,1),(36,'MOTO',NULL,01,1,1,1);
/*!40000 ALTER TABLE `tipo_vehiculo` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `vehiculo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehiculo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha_ingreso` date DEFAULT NULL,
  `numero` int NOT NULL,
  `placa` varchar(10) DEFAULT NULL,
  `tipo_vehiculo_id` int NOT NULL,
  `modalidad_id` int NOT NULL,
  `sucursal_id` int NOT NULL,
  `capacidad` int(2) unsigned zerofill DEFAULT NULL,
  `filas` int unsigned DEFAULT NULL,
  `columnas` int unsigned DEFAULT NULL,
  `marca` varchar(255) DEFAULT NULL,
  `referencia` varchar(255) DEFAULT NULL,
  `cilindraje` int DEFAULT NULL,
  `modelo` int DEFAULT NULL,
  `tipo_combustible` varchar(10) DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  `carroceria` varchar(255) DEFAULT NULL,
  `motor` varchar(255) DEFAULT NULL,
  `motor_regrabado` varchar(255) DEFAULT NULL,
  `serie` varchar(255) DEFAULT NULL,
  `serie_regrabado` varchar(255) DEFAULT NULL,
  `chasis` varchar(255) DEFAULT NULL,
  `chasis_regrabado` varchar(255) DEFAULT NULL,
  `ejes` int DEFAULT NULL,
  `fotografia` varchar(255) DEFAULT NULL,
  `vehiculo_at` datetime DEFAULT NULL,
  `vehiculo_in` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_vehiculo_tipo_vehiculo_idx` (`tipo_vehiculo_id`),
  KEY `fk_vehiculo_modalidad_idx` (`modalidad_id`),
  KEY `fk_vehiculo_sucursal1_idx` (`sucursal_id`),
  CONSTRAINT `fk_vehiculo_modalidad1` FOREIGN KEY (`modalidad_id`) REFERENCES `modalidad` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_vehiculo_sucursal1` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursal` (`id`),
  CONSTRAINT `fk_vehiculo_tipo_vehiculo1` FOREIGN KEY (`tipo_vehiculo_id`) REFERENCES `tipo_vehiculo` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=348 DEFAULT CHARSET=utf8mb3;

