/*
Navicat MySQL Data Transfer

Source Server         : Dekra
Source Server Version : 50555
Source Host           : 31.14.141.98:3306
Source Database       : site_dbglobal

Target Server Type    : MYSQL
Target Server Version : 50555
File Encoding         : 65001

Date: 2017-08-29 16:59:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `#__gg_configs`
-- ----------------------------
DROP TABLE IF EXISTS `#__gg_configs`;
CREATE TABLE `#__gg_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(100) DEFAULT NULL,
  `config_value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of #__gg_configs
-- ----------------------------
INSERT INTO `#__gg_configs` VALUES ('1', 'integrazione', 'eb');
INSERT INTO `#__gg_configs` VALUES ('2', 'campo_event_booking_auto_abilitazione_coupon', '1');
INSERT INTO `#__gg_configs` VALUES ('3', 'campo_community_builder_auto_abilitazione_coupon', '17');
INSERT INTO `#__gg_configs` VALUES ('4', 'verifica_cf', '0');
INSERT INTO `#__gg_configs` VALUES ('5', 'campo_event_booking_controllo_cf', '1');
INSERT INTO `#__gg_configs` VALUES ('6', 'campo_community_builder_controllo_cf', 'canvas');
INSERT INTO `#__gg_configs` VALUES ('7', 'abilita_breadcrumbs', '1');
INSERT INTO `#__gg_configs` VALUES ('8', 'visualizza_ultimo', '1');
INSERT INTO `#__gg_configs` VALUES ('9', 'visualizza_primo_item', '1');
INSERT INTO `#__gg_configs` VALUES ('10', 'customizza_primo_item', '0');
INSERT INTO `#__gg_configs` VALUES ('11', 'customizza_testo_primo_item', 'Corsi');
INSERT INTO `#__gg_configs` VALUES ('12', 'customizza_link_primo_item', 'index.php?option=com_gglms');
INSERT INTO `#__gg_configs` VALUES ('13', 'visualizza_solo_mieicorsi', '1');
INSERT INTO `#__gg_configs` VALUES ('14', 'nomenclatura_unita', 'Raccolte');
INSERT INTO `#__gg_configs` VALUES ('15', 'titolo_unita_visibile', '0');
INSERT INTO `#__gg_configs` VALUES ('16', 'nomenclatura_moduli', 'Elementi');
INSERT INTO `#__gg_configs` VALUES ('17', 'visibilita_durata', '1,2');
INSERT INTO `#__gg_configs` VALUES ('18', 'larghezza_box_unita', 'size-25');
INSERT INTO `#__gg_configs` VALUES ('19', 'larghezza_box_contenuti', 'size-25');
INSERT INTO `#__gg_configs` VALUES ('20', 'id_gruppi_visibili', '2,7,8');


-- ----------------------------
-- Table structure for `#__gg_contenuti`
-- ----------------------------
DROP TABLE IF EXISTS `#__gg_contenuti`;
CREATE TABLE `#__gg_contenuti` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titolo` text NOT NULL,
  `alias` text,
  `pubblicato` tinyint(1) NOT NULL DEFAULT '1',
  `durata` int(10) unsigned NOT NULL DEFAULT '0',
  `descrizione` longtext,
  `access` int(10) unsigned DEFAULT '0',
  `meta_tag` varchar(255) DEFAULT '-',
  `abstract` mediumtext,
  `datapubblicazione` date DEFAULT NULL,
  `slide` tinyint(1) NOT NULL DEFAULT '0',
  `path` varchar(255) DEFAULT NULL,
  `id_quizdeluxe` int(4) DEFAULT NULL,
  `tipologia` int(4) NOT NULL DEFAULT '1',
  `files` varchar(255) DEFAULT NULL COMMENT 'NON CANCELLARE SERVE PER IL MAP',
  `mod_track` int(255) DEFAULT '1',
  `prerequisiti` varchar(255) DEFAULT NULL,
  `id_completed_data` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `titolo` (`titolo`,`abstract`,`descrizione`,`meta_tag`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of #__gg_contenuti
-- ----------------------------

-- ----------------------------
-- Table structure for `#__gg_contenuti_acl`
-- ----------------------------
DROP TABLE IF EXISTS `#__gg_contenuti_acl`;
CREATE TABLE `#__gg_contenuti_acl` (
  `id_contenuto` int(10) NOT NULL DEFAULT '0',
  `id_group` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_contenuto`,`id_group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of #__gg_contenuti_acl
-- ----------------------------

-- ----------------------------
-- Table structure for `#__gg_contenuti_tipology`
-- ----------------------------
DROP TABLE IF EXISTS `#__gg_contenuti_tipology`;
CREATE TABLE `#__gg_contenuti_tipology` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipologia` varchar(50) DEFAULT NULL,
  `descrizione` varchar(50) DEFAULT NULL,
  `ordinamento` int(10) DEFAULT NULL,
  `pubblicato` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of #__gg_contenuti_tipology
-- ----------------------------
INSERT INTO `#__gg_contenuti_tipology` VALUES ('1', 'videoslide', 'Contenuti', '1', '1');
INSERT INTO `#__gg_contenuti_tipology` VALUES ('2', 'solovideo', 'Contenuto Video', '2', '0');
INSERT INTO `#__gg_contenuti_tipology` VALUES ('3', 'allegati', 'Allegati', '3', '0');
INSERT INTO `#__gg_contenuti_tipology` VALUES ('4', 'scorm', 'Scorm', '4', '1');
INSERT INTO `#__gg_contenuti_tipology` VALUES ('5', 'attestato', 'Attestato', '5', '1');
INSERT INTO `#__gg_contenuti_tipology` VALUES ('6', 'testuale', 'Testuale', '6', '1');
INSERT INTO `#__gg_contenuti_tipology` VALUES ('7', 'quizdeluxe', 'Quiz Deluxe', '7', '1');
INSERT INTO `#__gg_contenuti_tipology` VALUES ('8', 'singlesignon', 'sso', '8', '1');

-- ----------------------------
-- Table structure for `#__gg_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `#__gg_coupon`;
CREATE TABLE `#__gg_coupon` (
  `coupon` varchar(100) NOT NULL DEFAULT '',
  `corsi_abilitati` varchar(255) DEFAULT NULL,
  `id_utente` int(11) DEFAULT NULL,
  `gruppo` varchar(255) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `abilitato` tinyint(4) DEFAULT '0',
  `id_iscrizione` varchar(33) DEFAULT NULL,
  `data_utilizzo` datetime DEFAULT NULL,
  `data_abilitazione` datetime DEFAULT NULL,
  `data_scadenza` datetime DEFAULT NULL,
  `durata` int(3) unsigned DEFAULT '60',
  `attestato` tinyint(1) unsigned DEFAULT NULL,
  `trial` tinyint(1) unsigned DEFAULT '0',
  `id_societa` int(11) DEFAULT NULL,
  PRIMARY KEY (`coupon`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of #__gg_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for `#__gg_files`
-- ----------------------------
DROP TABLE IF EXISTS `#__gg_files`;
CREATE TABLE `#__gg_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` int(4) NOT NULL DEFAULT '1',
  `date` date DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;



-- ----------------------------
-- Table structure for `#__gg_files_map`
-- ----------------------------
DROP TABLE IF EXISTS `#__gg_files_map`;
CREATE TABLE `#__gg_files_map` (
  `idlink` int(11) NOT NULL AUTO_INCREMENT,
  `idcontenuto` int(11) unsigned NOT NULL,
  `idfile` int(11) unsigned NOT NULL,
  `ordinamento` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`idlink`)
) ENGINE=MyISAM AUTO_INCREMENT=431 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `#__gg_log`
-- ----------------------------
DROP TABLE IF EXISTS `#__gg_log`;
CREATE TABLE `#__gg_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_utente` int(10) DEFAULT NULL,
  `id_contenuto` int(10) DEFAULT NULL,
  `data_accesso` datetime DEFAULT NULL,
  `supporto` tinytext,
  `ip_address` varchar(20) DEFAULT NULL,
  `uniqid` varchar(30) DEFAULT NULL,
  `permanenza` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=187993 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of #__gg_log
-- ----------------------------


-- ----------------------------
-- Table structure for `#__gg_scormvars`
-- ----------------------------
DROP TABLE IF EXISTS `#__gg_scormvars`;
CREATE TABLE `#__gg_scormvars` (
  `scoid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `varName` varchar(255) NOT NULL DEFAULT '',
  `varValue` text,
  PRIMARY KEY (`scoid`,`userid`,`varName`),
  KEY `SCOInstanceID` (`scoid`),
  KEY `varName` (`varName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of #__gg_scormvars
-- ----------------------------


-- ----------------------------
-- Table structure for `#__gg_unit`
-- ----------------------------
DROP TABLE IF EXISTS `#__gg_unit`;
CREATE TABLE `#__gg_unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titolo` varchar(255) NOT NULL DEFAULT '1',
  `alias` varchar(255) DEFAULT NULL,
  `descrizione` mediumtext,
  `unitapadre` int(10) DEFAULT '0',
  `id_event_booking` int(10) DEFAULT NULL,
  `pubblicato` int(11) NOT NULL DEFAULT '0',
  `ordinamento` int(10) DEFAULT NULL,
  `accesso` text,
  `is_corso` int(10) DEFAULT NULL,
  `id_contenuto_completamento` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `titolo` (`titolo`,`descrizione`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of #__gg_unit
-- ----------------------------
INSERT INTO `#__gg_unit` VALUES ('1', 'Corsi', 'corsi', '', '0', '1', '101', '0', 'Accesso libero', '1', '0');

-- ----------------------------
-- Table structure for `#__gg_unit_map`
-- ----------------------------
DROP TABLE IF EXISTS `#__gg_unit_map`;
CREATE TABLE `#__gg_unit_map` (
  `idlink` int(11) NOT NULL AUTO_INCREMENT,
  `idcontenuto` int(11) unsigned NOT NULL,
  `idunita` int(11) unsigned NOT NULL,
  `ordinamento` int(11) DEFAULT '99',
  `data` date DEFAULT NULL,
  PRIMARY KEY (`idlink`)
) ENGINE=MyISAM AUTO_INCREMENT=1481 DEFAULT CHARSET=utf8;



-- ----------------------------
-- Table of #__gg_usergroup_map
-- ----------------------------

DROP TABLE IF EXISTS `#__gg_usergroup_map`;
CREATE TABLE `#__gg_usergroup_map` (
  `idunita` int(11) unsigned NOT NULL,
  `idgruppo` int(11) unsigned NOT NULL,
  PRIMARY KEY (`idunita`,`idgruppo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


