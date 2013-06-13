/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50150
Source Host           : localhost:3306
Source Database       : webwinkel

Target Server Type    : MYSQL
Target Server Version : 50150
File Encoding         : 65001

Date: 2013-06-13 02:43:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `bestellingdetail`
-- ----------------------------
DROP TABLE IF EXISTS `bestellingdetail`;
CREATE TABLE `bestellingdetail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDBestelling` int(11) DEFAULT NULL,
  `IDProduct` int(11) DEFAULT NULL,
  `AantalBesteld` double DEFAULT NULL,
  `Prijs` double DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDBestelling` (`IDBestelling`),
  CONSTRAINT `bestellingdetail_ibfk_1` FOREIGN KEY (`IDBestelling`) REFERENCES `bestellingheader` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bestellingdetail
-- ----------------------------

-- ----------------------------
-- Table structure for `bestellingheader`
-- ----------------------------
DROP TABLE IF EXISTS `bestellingheader`;
CREATE TABLE `bestellingheader` (
  `ID` int(11) NOT NULL DEFAULT '0',
  `IDGebruiker` int(11) DEFAULT NULL,
  `totaalprijs` double DEFAULT NULL,
  `datumbestelling` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `leveringsadres` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bestellingheader
-- ----------------------------

-- ----------------------------
-- Table structure for `categorie`
-- ----------------------------
DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Omschrijving` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of categorie
-- ----------------------------
INSERT INTO `categorie` VALUES ('1', 'Standaard');
INSERT INTO `categorie` VALUES ('2', 'Pasen');
INSERT INTO `categorie` VALUES ('3', 'Communie');
INSERT INTO `categorie` VALUES ('4', 'Halloween');

-- ----------------------------
-- Table structure for `categorieproduct`
-- ----------------------------
DROP TABLE IF EXISTS `categorieproduct`;
CREATE TABLE `categorieproduct` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDCategorie` int(11) DEFAULT NULL,
  `IDProduct` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of categorieproduct
-- ----------------------------
INSERT INTO `categorieproduct` VALUES ('1', '1', '1');

-- ----------------------------
-- Table structure for `firma`
-- ----------------------------
DROP TABLE IF EXISTS `firma`;
CREATE TABLE `firma` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Firma` varchar(50) DEFAULT NULL,
  `Straat` varchar(50) DEFAULT NULL,
  `Postcode` varchar(10) DEFAULT NULL,
  `Gemeente` varchar(50) DEFAULT NULL,
  `Website` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of firma
-- ----------------------------
INSERT INTO `firma` VALUES ('1', 'Saverde', 'Ingelmunstersteenweg 157 ', '8780', 'Oostrozebeke', 'www.saverde.be');

-- ----------------------------
-- Table structure for `foto`
-- ----------------------------
DROP TABLE IF EXISTS `foto`;
CREATE TABLE `foto` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `fileName` varchar(250) NOT NULL,
  `fileNameOrig` varchar(250) DEFAULT NULL,
  `screenName` varchar(250) DEFAULT NULL,
  `mimeType` varchar(80) DEFAULT NULL,
  `fileSize` int(11) DEFAULT NULL,
  `filePath` text,
  `identifier` int(11) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT NULL,
  `userName` varchar(50) NOT NULL,
  `lastUpdate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of foto
-- ----------------------------
INSERT INTO `foto` VALUES ('1', 'Contract_-_DC_Technics_-_DEEL_1.pdf', 'Contract - DC Technics - DEEL 1.pdf', null, null, '714207', 'uploads/plaatsers/', '200001', '2013-06-06 14:19:56', 'VINCENTV', '2013-06-06 14:19:56');
INSERT INTO `foto` VALUES ('2', 'Contract_-_DC_Technics_-_DEEL_2.pdf', 'Contract - DC Technics - DEEL 2.pdf', null, null, '1399456', 'uploads/plaatsers/', '200001', '2013-06-06 14:21:09', 'VINCENTV', '2013-06-06 14:21:09');
INSERT INTO `foto` VALUES ('3', 'Contract_-_Almozon_-_DEEL_1.pdf', 'Contract - Almozon - DEEL 1.pdf', null, null, '714507', 'uploads/plaatsers/', '106828', '2013-06-06 17:13:26', 'VINCENTV', '2013-06-06 17:13:26');
INSERT INTO `foto` VALUES ('4', 'Contract_-_Almozon_-_DEEL_2.pdf', 'Contract - Almozon - DEEL 2.pdf', null, null, '1523518', 'uploads/plaatsers/', '106828', '2013-06-06 17:13:40', 'VINCENTV', '2013-06-06 17:13:40');
INSERT INTO `foto` VALUES ('7', 'Contract_-_Maenhout_-_Deel_1-.pdf', 'Contract - Maenhout - Deel 1-.pdf', null, null, '707247', 'uploads/plaatsers/', '200001', '2013-06-06 17:42:58', 'VINCENTV', '2013-06-06 17:42:58');
INSERT INTO `foto` VALUES ('8', 'Contract_-_Maenhout_-_Deel_2.pdf', 'Contract - Maenhout - Deel 2.pdf', null, null, '1782153', 'uploads/plaatsers/', '200001', '2013-06-06 17:43:23', 'VINCENTV', '2013-06-06 17:43:23');
INSERT INTO `foto` VALUES ('9', 'Contract_-_TSS_Montage_-_DEEL_1.pdf', 'Contract - TSS Montage - DEEL 1.pdf', null, null, '727455', 'uploads/plaatsers/', '200002', '2013-06-06 17:45:09', 'VINCENTV', '2013-06-06 17:45:09');
INSERT INTO `foto` VALUES ('10', 'Contract_-_TSS_Montage_-_DEEL_2.pdf', 'Contract - TSS Montage - DEEL 2.pdf', null, null, '1404407', 'uploads/plaatsers/', '200002', '2013-06-06 17:45:27', 'VINCENTV', '2013-06-06 17:45:27');

-- ----------------------------
-- Table structure for `fotolocale`
-- ----------------------------
DROP TABLE IF EXISTS `fotolocale`;
CREATE TABLE `fotolocale` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDFoto` int(11) DEFAULT NULL,
  `titel` varchar(50) DEFAULT NULL,
  `teaser` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fotolocale
-- ----------------------------

-- ----------------------------
-- Table structure for `gebruiker`
-- ----------------------------
DROP TABLE IF EXISTS `gebruiker`;
CREATE TABLE `gebruiker` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `paswoord` varchar(10) DEFAULT NULL,
  `role` enum('USER','ADMIN','GUEST') DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gebruiker
-- ----------------------------

-- ----------------------------
-- Table structure for `pagina`
-- ----------------------------
DROP TABLE IF EXISTS `pagina`;
CREATE TABLE `pagina` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pagina
-- ----------------------------

-- ----------------------------
-- Table structure for `paginalocale`
-- ----------------------------
DROP TABLE IF EXISTS `paginalocale`;
CREATE TABLE `paginalocale` (
  `ID` int(11) NOT NULL DEFAULT '0',
  `IDPagina` int(11) DEFAULT NULL,
  `titel` varchar(50) DEFAULT NULL,
  `teaser` varchar(50) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `locale` varchar(5) DEFAULT NULL,
  `vertaald` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of paginalocale
-- ----------------------------

-- ----------------------------
-- Table structure for `product`
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `prijs` double DEFAULT NULL,
  `homepagina` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES ('1', 'SRH5 S637', '1', '50', '1');
INSERT INTO `product` VALUES ('2', 'SRH5 S649', '1', '60', '1');

-- ----------------------------
-- Table structure for `productfoto`
-- ----------------------------
DROP TABLE IF EXISTS `productfoto`;
CREATE TABLE `productfoto` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDProduct` int(11) DEFAULT NULL,
  `IDFoto` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of productfoto
-- ----------------------------

-- ----------------------------
-- Table structure for `productlocale`
-- ----------------------------
DROP TABLE IF EXISTS `productlocale`;
CREATE TABLE `productlocale` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDProduct` int(11) DEFAULT NULL,
  `titel` varchar(50) DEFAULT NULL,
  `teaser` varchar(50) DEFAULT NULL,
  `omschrijving` varchar(50) DEFAULT NULL,
  `locale` varchar(5) DEFAULT NULL,
  `vertaald` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDProduct` (`IDProduct`),
  CONSTRAINT `productlocale_ibfk_1` FOREIGN KEY (`IDProduct`) REFERENCES `product` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of productlocale
-- ----------------------------
INSERT INTO `productlocale` VALUES ('1', '1', 'Nougatine', '1box=360 chocolates', null, 'nl_BE', '1');
INSERT INTO `productlocale` VALUES ('2', '2', 'Speculoos', '1box=360 chocolates', null, 'nl_BE', '1');

-- ----------------------------
-- Table structure for `winkelmand`
-- ----------------------------
DROP TABLE IF EXISTS `winkelmand`;
CREATE TABLE `winkelmand` (
  `ID` int(11) NOT NULL DEFAULT '0',
  `IDSession` int(11) DEFAULT NULL,
  `IDProduct` int(11) DEFAULT NULL,
  `Aantal` double DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of winkelmand
-- ----------------------------
