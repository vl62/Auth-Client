-- MySQL dump 10.13  Distrib 5.6.23, for osx10.8 (x86_64)
--
-- Host: localhost    Database: cafevariome_client
-- ------------------------------------------------------
-- Server version	5.6.23

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `client_id` varchar(32) NOT NULL DEFAULT '',
  `client_secret` varchar(32) NOT NULL DEFAULT '',
  `redirect_uri` varchar(250) NOT NULL DEFAULT '',
  `auto_approve` tinyint(1) NOT NULL DEFAULT '0',
  `autonomous` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('development','pending','approved','rejected') NOT NULL DEFAULT 'development',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `notes` tinytext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applications`
--

LOCK TABLES `applications` WRITE;
/*!40000 ALTER TABLE `applications` DISABLE KEYS */;
/*!40000 ALTER TABLE `applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `autocomplete`
--

DROP TABLE IF EXISTS `autocomplete`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `autocomplete` (
  `term` varchar(1000) NOT NULL,
  `type` varchar(50) NOT NULL,
  KEY `term` (`term`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autocomplete`
--

LOCK TABLES `autocomplete` WRITE;
/*!40000 ALTER TABLE `autocomplete` DISABLE KEYS */;
INSERT INTO `autocomplete` VALUES ('CAPN10','gene'),('NM_000088.3','ref');
/*!40000 ALTER TABLE `autocomplete` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `beacon_sharing_policies`
--

DROP TABLE IF EXISTS `beacon_sharing_policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `beacon_sharing_policies` (
  `setting_id` int(10) NOT NULL AUTO_INCREMENT,
  `sharing_policy` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `last_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `sharing_policy` (`sharing_policy`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beacon_sharing_policies`
--

LOCK TABLES `beacon_sharing_policies` WRITE;
/*!40000 ALTER TABLE `beacon_sharing_policies` DISABLE KEYS */;
INSERT INTO `beacon_sharing_policies` VALUES (1,'openAccess',0,'2014-11-17 14:05:50'),(2,'linkedAccess',0,'2014-11-17 14:05:50'),(3,'restrictedAccess',0,'2014-11-17 14:06:08');
/*!40000 ALTER TABLE `beacon_sharing_policies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_fields`
--

DROP TABLE IF EXISTS `core_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `core_fields` (
  `core_field_id` int(11) NOT NULL AUTO_INCREMENT,
  `core_field_name` varchar(150) NOT NULL,
  PRIMARY KEY (`core_field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_fields`
--

LOCK TABLES `core_fields` WRITE;
/*!40000 ALTER TABLE `core_fields` DISABLE KEYS */;
INSERT INTO `core_fields` VALUES 
(1,'cafevariome_id'),
(2, 'record_id'),
(3, 'genome_chr'),
(4, 'genome_build'),
(5, 'genome_start'),
(6, 'genome_stop'),
(7, 'accession_ref'),
(8, 'accession_start'),
(9, 'accession_stop'),
(10, 'dna_sequence'),
(11, 'protein_sequence'),
(12, 'gene_symbol'),
(13, 'hgvs_reference'),
(14, 'hgvs_name'),
(15, 'phenotype'),
(16, 'laboratory'),
(17, 'individual_id'),
(18, 'variant_id'),
(19, 'pathogenicity'),
(20, 'detection_method'),
(21, 'comment'),
(22, 'dbsnp_id'),
(23, 'pmid');

/*!40000 ALTER TABLE `core_fields` ENABLE KEYS */;
UNLOCK TABLES;


-- ALTER TABLE `variants` ADD INDEX(`cafevariome_id`);


--
-- Table structure for table `curators`
--

DROP TABLE IF EXISTS `curators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `curators` (
  `curator_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  PRIMARY KEY (`curator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `curators`
--

LOCK TABLES `curators` WRITE;
/*!40000 ALTER TABLE `curators` DISABLE KEYS */;
/*!40000 ALTER TABLE `curators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_requests`
--

DROP TABLE IF EXISTS `data_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_requests` (
  `request_id` int(10) NOT NULL AUTO_INCREMENT,
  `justification` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `datetime` text NOT NULL,
  `source` varchar(50) NOT NULL,
  `term` text NOT NULL,
  `ip` text NOT NULL,
  `string` varchar(35) NOT NULL,
  `resultreason` text NOT NULL,
  `result` varchar(50) NOT NULL,
  PRIMARY KEY (`request_id`),
  KEY `string` (`string`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_requests`
--

LOCK TABLES `data_requests` WRITE;
/*!40000 ALTER TABLE `data_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `display_fields`
--

DROP TABLE IF EXISTS `display_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `display_fields` (
  `display_field_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `visible_name` text NOT NULL,
  `order` int(11) NOT NULL,
  `sharing_policy` varchar(20) NOT NULL DEFAULT 'openAccess',
  `type` varchar(20) NOT NULL DEFAULT 'search_result',
  PRIMARY KEY (`display_field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `display_fields`
--

LOCK TABLES `display_fields` WRITE;
/*!40000 ALTER TABLE `display_fields` DISABLE KEYS */;
INSERT INTO `display_fields` VALUES 
(1,'cafevariome_id','Cafe Variome ID',1,'openAccess','search_result'),
(2, 'record_id', 'Record Id', 2, 'openAccess', 'search_result'),
(3, 'genome_chr', 'Genome Chr', 3, 'openAccess', 'search_result'),
(4, 'genome_build', 'Genome Build', 4, 'openAccess', 'search_result'),
(5, 'genome_start', 'Genome Start', 5, 'openAccess', 'search_result'),
(6, 'genome_stop', 'Genome Stop', 6, 'openAccess', 'search_result'),
(7, 'accession_ref', 'Accession Ref', 7, 'openAccess', 'search_result'),
(8, 'accession_start', 'Accession Start', 8, 'openAccess', 'search_result'),
(9, 'accession_stop', 'Accession Stop', 9, 'openAccess', 'search_result'),
(10, 'dna_sequence', 'Dna Sequence', 10, 'openAccess', 'search_result'),
(11, 'protein_sequence', 'Protein Sequence', 11, 'openAccess', 'search_result'),
(12, 'gene_symbol', 'Gene Symbol', 12, 'openAccess', 'search_result'),
(13, 'hgvs_reference', 'Hgvs Reference', 13, 'openAccess', 'search_result'),
(14, 'hgvs_name', 'Hgvs Name', 14, 'openAccess', 'search_result'),
(15, 'phenotype', 'Phenotype', 15, 'openAccess', 'search_result'),
(16, 'laboratory', 'Laboratory', 16, 'openAccess', 'search_result'),
(17, 'individual_id', 'Individual Id', 17, 'openAccess', 'search_result'),
(18, 'variant_id', 'Variant Id', 18, 'openAccess', 'search_result'),
(19, 'pathogenicity', 'Pathogenicity', 19, 'openAccess', 'search_result'),
(20, 'detection_method', 'Detection Method', 20, 'openAccess', 'search_result'),
(21, 'comment', 'Comment', 21, 'openAccess', 'search_result'),
(22, 'dbsnp_id', 'Dbsnp Id', 22, 'openAccess', 'search_result'),
(23, 'pmid', 'Pmid', 23, 'openAccess', 'search_result');
/*!40000 ALTER TABLE `display_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `federated`
--

DROP TABLE IF EXISTS `federated`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `federated` (
  `federated_id` int(11) NOT NULL AUTO_INCREMENT,
  `federated_name` varchar(50) NOT NULL,
  `federated_uri` varchar(50) NOT NULL,
  `federated_status` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`federated_id`),
  KEY `federated_uri` (`federated_uri`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `federated`
--

LOCK TABLES `federated` WRITE;
/*!40000 ALTER TABLE `federated` DISABLE KEYS */;
/*!40000 ALTER TABLE `federated` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `frequencies`
--

DROP TABLE IF EXISTS `frequencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `frequencies` (
  `frequency_id` int(10) NOT NULL,
  `cafevariome_id` int(15) NOT NULL,
  `frequency_type` varchar(50) NOT NULL,
  `number_samples` int(10) NOT NULL,
  `population_type` varchar(50) NOT NULL,
  `population_term` varchar(50) NOT NULL,
  `population_accession` varchar(10) NOT NULL,
  `frequency` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frequencies`
--

LOCK TABLES `frequencies` WRITE;
/*!40000 ALTER TABLE `frequencies` DISABLE KEYS */;
/*!40000 ALTER TABLE `frequencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gene2omim`
--

DROP TABLE IF EXISTS `gene2omim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gene2omim` (
  `gene` varchar(25) NOT NULL,
  `disorder` text NOT NULL,
  `omim_id` varchar(15) NOT NULL,
  KEY `gene` (`gene`),
  KEY `omim_id` (`omim_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gene2omim`
--

LOCK TABLES `gene2omim` WRITE;
/*!40000 ALTER TABLE `gene2omim` DISABLE KEYS */;
/*!40000 ALTER TABLE `gene2omim` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genes`
--

DROP TABLE IF EXISTS `genes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `genes` (
  `gene_symbol` varchar(50) NOT NULL,
  KEY `gene_symbol` (`gene_symbol`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genes`
--

LOCK TABLES `genes` WRITE;
/*!40000 ALTER TABLE `genes` DISABLE KEYS */;
/*!40000 ALTER TABLE `genes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'admin','Administrator'),(2,'general','General User'),(3,'curator','Curator');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inabox_downloads`
--

DROP TABLE IF EXISTS `inabox_downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inabox_downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` text NOT NULL,
  `institute` text NOT NULL,
  `email` text NOT NULL,
  `description` text NOT NULL,
  `ip` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inabox_downloads`
--

LOCK TABLES `inabox_downloads` WRITE;
/*!40000 ALTER TABLE `inabox_downloads` DISABLE KEYS */;
/*!40000 ALTER TABLE `inabox_downloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `installation_networks`
--

DROP TABLE IF EXISTS `installation_networks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `installation_networks` (
  `installation_networks_id` int(11) NOT NULL AUTO_INCREMENT,
  `installation_key` varchar(100) NOT NULL,
  `network_key` varchar(100) NOT NULL,
  `installation_base_url` varchar(100) NOT NULL,
  PRIMARY KEY (`installation_networks_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `installation_networks`
--

LOCK TABLES `installation_networks` WRITE;
/*!40000 ALTER TABLE `installation_networks` DISABLE KEYS */;
/*!40000 ALTER TABLE `installation_networks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `installations`
--

DROP TABLE IF EXISTS `installations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `installations` (
  `installation_id` int(11) NOT NULL AUTO_INCREMENT,
  `installation_key` varchar(100) NOT NULL,
  `installation_name` text NOT NULL,
  `installation_base_url` varchar(100) NOT NULL,
  PRIMARY KEY (`installation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `installations`
--

LOCK TABLES `installations` WRITE;
/*!40000 ALTER TABLE `installations` DISABLE KEYS */;
/*!40000 ALTER TABLE `installations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keys`
--

DROP TABLE IF EXISTS `keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keys`
--

LOCK TABLES `keys` WRITE;
/*!40000 ALTER TABLE `keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_attempts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `authorized` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailing_list`
--

DROP TABLE IF EXISTS `mailing_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailing_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` text NOT NULL,
  `email` text NOT NULL,
  `ip` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailing_list`
--

LOCK TABLES `mailing_list` WRITE;
/*!40000 ALTER TABLE `mailing_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `mailing_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(20) NOT NULL,
  PRIMARY KEY (`menu_id`),
  UNIQUE KEY `menu_name` (`menu_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (3,'Contact'),(2,'Discover'),(1,'Home');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_threads`
--

DROP TABLE IF EXISTS `message_threads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_threads` (
  `thread_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(10) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`thread_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_threads`
--

LOCK TABLES `message_threads` WRITE;
/*!40000 ALTER TABLE `message_threads` DISABLE KEYS */;
/*!40000 ALTER TABLE `message_threads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(10) NOT NULL,
  `subject` text NOT NULL,
  `body` text NOT NULL,
  `thread_id` int(11) NOT NULL,
  `sent_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `from_user_id` (`sender_id`,`thread_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `msg_messages`
--

DROP TABLE IF EXISTS `msg_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `msg_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `priority` int(2) NOT NULL DEFAULT '0',
  `sender_id` int(11) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `msg_messages`
--

LOCK TABLES `msg_messages` WRITE;
/*!40000 ALTER TABLE `msg_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `msg_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `msg_participants`
--

DROP TABLE IF EXISTS `msg_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `msg_participants` (
  `user_id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`thread_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `msg_participants`
--

LOCK TABLES `msg_participants` WRITE;
/*!40000 ALTER TABLE `msg_participants` DISABLE KEYS */;
/*!40000 ALTER TABLE `msg_participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `msg_status`
--

DROP TABLE IF EXISTS `msg_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `msg_status` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`message_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `msg_status`
--

LOCK TABLES `msg_status` WRITE;
/*!40000 ALTER TABLE `msg_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `msg_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `msg_threads`
--

DROP TABLE IF EXISTS `msg_threads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `msg_threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `msg_threads`
--

LOCK TABLES `msg_threads` WRITE;
/*!40000 ALTER TABLE `msg_threads` DISABLE KEYS */;
/*!40000 ALTER TABLE `msg_threads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `network_requests`
--

DROP TABLE IF EXISTS `network_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `network_requests` (
  `request_id` int(10) NOT NULL AUTO_INCREMENT,
  `network_name` text NOT NULL,
  `network_key` varchar(100) NOT NULL,
  `installation_key` varchar(100) NOT NULL,
  `justification` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `ip` text NOT NULL,
  `resultreason` text NOT NULL,
  `result` varchar(50) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`request_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `network_requests`
--

LOCK TABLES `network_requests` WRITE;
/*!40000 ALTER TABLE `network_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `network_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `networks`
--

DROP TABLE IF EXISTS `networks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `networks` (
  `network_id` int(11) NOT NULL AUTO_INCREMENT,
  `network_name` text NOT NULL,
  `network_key` varchar(100) NOT NULL,
  `network_type` varchar(50) NOT NULL,
  PRIMARY KEY (`network_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `networks`
--

LOCK TABLES `networks` WRITE;
/*!40000 ALTER TABLE `networks` DISABLE KEYS */;
/*!40000 ALTER TABLE `networks` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `networks_phenotypes_attributes_values`
--

DROP TABLE IF EXISTS `networks_phenotypes_attributes_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `networks_phenotypes_attributes_values` (
  `networks_phenotypes_attributes_values_id` int(11) NOT NULL AUTO_INCREMENT,
  `network_key` varchar(50) NOT NULL,
  `attribute` varchar(200) NOT NULL,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY (`networks_phenotypes_attributes_values_id`),
  UNIQUE KEY `unique_index` (`network_key`,`attribute`,`value`),
  KEY `attribute` (`attribute`),
  KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `networks_phenotypes_attributes_values`
--

LOCK TABLES `networks_phenotypes_attributes_values` WRITE;
/*!40000 ALTER TABLE `networks_phenotypes_attributes_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `networks_phenotypes_attributes_values` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `node_list`
--

DROP TABLE IF EXISTS `node_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `node_list` (
  `node_id` int(11) NOT NULL AUTO_INCREMENT,
  `node_name` varchar(50) NOT NULL,
  `node_uri` varchar(50) NOT NULL,
  `node_key` varchar(32) NOT NULL,
  `node_status` varchar(10) NOT NULL,
  PRIMARY KEY (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `node_list`
--

LOCK TABLES `node_list` WRITE;
/*!40000 ALTER TABLE `node_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `node_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_session_scopes`
--

DROP TABLE IF EXISTS `oauth_session_scopes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_session_scopes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` int(11) unsigned NOT NULL,
  `access_token` varchar(50) NOT NULL DEFAULT '',
  `scope` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  KEY `scope` (`scope`),
  KEY `access_token` (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_session_scopes`
--

LOCK TABLES `oauth_session_scopes` WRITE;
/*!40000 ALTER TABLE `oauth_session_scopes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_session_scopes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_sessions`
--

DROP TABLE IF EXISTS `oauth_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_sessions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(32) NOT NULL DEFAULT '',
  `redirect_uri` varchar(250) NOT NULL DEFAULT '',
  `type_id` varchar(64) DEFAULT NULL,
  `type` enum('user','auto') NOT NULL DEFAULT 'user',
  `code` text,
  `access_token` varchar(50) DEFAULT '',
  `stage` enum('request','granted') NOT NULL DEFAULT 'request',
  `first_requested` int(10) unsigned NOT NULL,
  `last_updated` int(10) unsigned NOT NULL,
  `limited_access` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Used for user agent flows',
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_sessions`
--

LOCK TABLES `oauth_sessions` WRITE;
/*!40000 ALTER TABLE `oauth_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ontology_list`
--

DROP TABLE IF EXISTS `ontology_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ontology_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `virtualid` int(15) NOT NULL,
  `abbreviation` varchar(20) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `ranking` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ontology_list`
--

LOCK TABLES `ontology_list` WRITE;
/*!40000 ALTER TABLE `ontology_list` DISABLE KEYS */;
INSERT INTO `ontology_list` VALUES (1,0,'LocalList','Local Phenotype Descriptions',1);
/*!40000 ALTER TABLE `ontology_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orcid_alert`
--

DROP TABLE IF EXISTS `orcid_alert`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orcid_alert` (
  `user_id` int(5) NOT NULL,
  `alert_shown` tinyint(1) NOT NULL,
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orcid_alert`
--

LOCK TABLES `orcid_alert` WRITE;
/*!40000 ALTER TABLE `orcid_alert` DISABLE KEYS */;
/*!40000 ALTER TABLE `orcid_alert` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(50) NOT NULL,
  `page_content` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_menu` varchar(20) NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Home','<div class=\"well\">\r\n<h3 style=\"text-align: center;\">Welcome</h3>\r\n<hr />\r\n<h1 style=\"text-align: center;\"></h1>\r\n<p style=\"text-align: center;\">This is the default page for a new Cafe Variome installation. The Cafe Variome data discovery platform can be used by diagnostic networks/disease consortia to allow the controlled discovery of patients and variants without revealing detailed information that might compromise the research value of the data.</p>\r\n<p style=\"text-align: center;\">Individual research/clinical groups can deposit data in an installation and control access to their data. The access-control levels range from fully open and immediately available, to fully controlled and only available to authorised users.</p>\r\n<p style=\"text-align: center;\">A user-friendly, intuitive administrator dashboard gives owners complete control over their data and installation. Dashboard configuration options include a content management system for adding/editing custom pages and menus, full control over site appearance (logo, colours, backgrounds, themes). Easy import of source mutation data via templates, control over how search results are displayed (ordering and specifying of fields) and a comprehensive access control system for users and groups.</p><hr />\r\n</div>\r\n<div class=\"well\">\r\n<p style=\"text-align: center;\"><em><strong>This front page welcome message can be modified (and additional pages/menus added) through the content management area of the administrator dashboard.</strong></em></p>\r\n</div>','2013-11-25 10:17:52','Home'),(2,'Contact','<h2 style=\"text-align: center;\">Contact</h2><hr /><p style=\"text-align: center;\"><em><strong>This contact page can be modified (and additional pages/menus added) through the administrators dashboard. The contact page is required so that the Cafe Variome branding can be included in your installation.</strong></em></p><hr />','2013-11-25 11:18:40','Contact');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pheno_dag`
--

DROP TABLE IF EXISTS `pheno_dag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pheno_dag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ontology` varchar(100) DEFAULT NULL,
  `termid` varchar(100) NOT NULL,
  `parentid` varchar(100) NOT NULL,
  `termname` varchar(200) NOT NULL,
  `terminalnode` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pheno_dag`
--

LOCK TABLES `pheno_dag` WRITE;
/*!40000 ALTER TABLE `pheno_dag` DISABLE KEYS */;
/*!40000 ALTER TABLE `pheno_dag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phenotypes`
--

DROP TABLE IF EXISTS `phenotypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phenotypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cafevariome_id` int(11) NOT NULL,
  `attribute_sourceID` varchar(15) DEFAULT NULL,
  `attribute_termID` varchar(200) DEFAULT NULL,
  `attribute_termName` varchar(200) DEFAULT NULL,
  `attribute_qualifier` varchar(200) DEFAULT NULL,
  `value` varchar(200) DEFAULT 'present',
  `type` enum('quality','qualityValue','numeric') NOT NULL DEFAULT 'quality',
  PRIMARY KEY (`id`),  KEY `cvid` (`cafevariome_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `local_phenotypes_lookup`;
CREATE TABLE `local_phenotypes_lookup` (
  `lookup_id` int(11) NOT NULL AUTO_INCREMENT,
  `network_key` varchar(32) NOT NULL,
  `phenotype_attribute` varchar(128) NOT NULL,
  `phenotype_values` varchar(256) NOT NULL,
  PRIMARY KEY (`lookup_id`),
  KEY `network_key` (`network_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phenotypes`
--

LOCK TABLES `phenotypes` WRITE;
/*!40000 ALTER TABLE `phenotypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `phenotypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_title` varchar(120) NOT NULL,
  `post_body` text NOT NULL,
  `post_date_sort` datetime NOT NULL,
  `post_date` varchar(30) NOT NULL,
  `post_visible` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preferences`
--

DROP TABLE IF EXISTS `preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preferences` (
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preferences`
--

LOCK TABLES `preferences` WRITE;
/*!40000 ALTER TABLE `preferences` DISABLE KEYS */;
INSERT INTO `preferences` VALUES ('current_font_link','Muli'),('header_colour_from','#6c737e'),('header_colour_to','#afb3ba'),('background','grey.png'),('logo','cafevariome-logo-full.png'),('font_size','14px'),('current_font_name','Muli'),('id_prefix','vx'),('id_current','234333355'),('report_usage','1'),('navbar_font_colour','#eeeeee'),('navbar_font_colour_hover','#ffffff'),('navbar_selected_tab_colour','#6c737e');
/*!40000 ALTER TABLE `preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prefixes`
--

DROP TABLE IF EXISTS `prefixes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prefixes` (
  `prefix_id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(10) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`prefix_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prefixes`
--

LOCK TABLES `prefixes` WRITE;
/*!40000 ALTER TABLE `prefixes` DISABLE KEYS */;
/*!40000 ALTER TABLE `prefixes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `primary_phenotype_lookup`
--

DROP TABLE IF EXISTS `primary_phenotype_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `primary_phenotype_lookup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sourceId` varchar(15) NOT NULL,
  `termId` varchar(200) NOT NULL,
  `termName` varchar(200) NOT NULL,
  `termDefinition` varchar(250) DEFAULT NULL,
  `qualifier` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `primary_phenotype_lookup`
--

LOCK TABLES `primary_phenotype_lookup` WRITE;
/*!40000 ALTER TABLE `primary_phenotype_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `primary_phenotype_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `query_builder_history`
--

DROP TABLE IF EXISTS `query_builder_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `query_builder_history` (
  `id` int(11) NOT NULL,
  `query_id` varchar(10) NOT NULL,
  `total_results` varchar(10) NOT NULL,
  `endpoint` varchar(100) NOT NULL,
  `query_statement` text NOT NULL,
  `query_response` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `query_builder_history`
--

LOCK TABLES `query_builder_history` WRITE;
/*!40000 ALTER TABLE `query_builder_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `query_builder_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refseq`
--

DROP TABLE IF EXISTS `refseq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `refseq` (
  `accession` varchar(25) NOT NULL,
  KEY `accession` (`accession`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refseq`
--

LOCK TABLES `refseq` WRITE;
/*!40000 ALTER TABLE `refseq` DISABLE KEYS */;
/*!40000 ALTER TABLE `refseq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scopes`
--

DROP TABLE IF EXISTS `scopes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scopes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `scope` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `scope` (`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scopes`
--

LOCK TABLES `scopes` WRITE;
/*!40000 ALTER TABLE `scopes` DISABLE KEYS */;
/*!40000 ALTER TABLE `scopes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `search_fields`
--

DROP TABLE IF EXISTS `search_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `search_fields` (
  `search_field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(50) NOT NULL,
  PRIMARY KEY (`search_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_fields`
--

LOCK TABLES `search_fields` WRITE;
/*!40000 ALTER TABLE `search_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `search_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `setting_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(100) NOT NULL,
  `info` text NOT NULL,
  `validation_rules` varchar(100) NOT NULL DEFAULT 'required|xss_clean',
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_title','Cafe Variome','Main title for the site that will be shown in metadata.','xss_clean'),(2,'site_description','Cafe Variome Client','Brief description of the site that will be shown in metadata.','xss_clean'),(3,'site_author','Administrator','Name of site author that will be shown in metadata.','xss_clean'),(4,'site_keywords','mutation, diagnostics, database','Site keywords metadata to help with search engine optimisation and traffic.','xss_clean'),(5,'email','admin@cafevariome.org','','required|xss_clean'),(6,'twitter','','If Twitter username is here set then Twitter icon link appears in contact page. Leave blank to disable.','xss_clean'),(7,'rss','local','Specify a VALID rss feed or to use the internal Cafe Variome news feed then just enter local (on its own)','callback_rss_check|xss_clean'),(8,'google_analytics','','Google Analytics tracking ID','xss_clean'),(9,'cvid_prefix','vx','Prefix that is prepended to Cafe Variome IDs','xss_clean'),(10,'stats','on','','xss_clean'),(11,'max_variants','30000','','required|xss_clean'),(12,'feature_table_name','variants','','required|xss_clean'),(13,'messaging','on','Enables/disables the internal messaging system for all users','xss_clean'),(14,'database_structure','off','Enables the tab to change database structure in the settings admin interface','xss_clean'),(15,'federated','off','If set to on then the federated API is enables and allows remote discovery queries from other Cafe Variome installs','xss_clean'),(16,'federated_head','off','Sets this installation as the main federated head through which installs can be','xss_clean'),(17,'show_orcid_reminder','off','Shows a one off message to users on the home page reminding them to link their ORCID to their Cafe Variome account','xss_clean'),(18,'atomserver_enabled','off','','xss_clean'),(19,'atomserver_user','','','xss_clean'),(20,'atomserver_password','','','xss_clean'),(21,'atomserver_uri','http://www.cafevariome.org/atomserver/v1/cafevariome/variants','','xss_clean'),(22,'cafevariome_central','off','If set to on then this is a Cafe Variome Central installation - additional menus for describing the system will be enabled','xss_clean'),(23,'allow_registrations','on','If set to on then users can register on the site, otherwise the signup is hidden','xss_clean'),(24,'variant_count_cutoff','0','If the number of variants discovered in a source is less than this then the results are hidden and the message in the variant_count_cutoff_message setting is displayed','xss_clean'),(25,'variant_count_cutoff_message','Unable to display results for this source, please contact admin@cafevariome.org','Message that is shown when the number of variants in less than that specified in the variant_count_cutoff setting','xss_clean'),(26,'dasigniter','on','If set to on then DASIgniter is enabled and variants in sources that are openAccess and linkedAccess will be available via DAS','xss_clean'),(27,'bioportalkey','6d7d7db8-698c-4a56-9792-107217b3965c','In order to use phenotype ontologies you must sign up for a BioPortal account and supply your API key here. If this is left blank you only be able to use free text for phenotypes. Sign up at http://bioportal.bioontology.org/accounts/new','xss_clean'),(28,'template','default','Specify the name of the css template file (located in views/css/)','xss_clean'),(29,'discovery_requires_login','off','If set to on then discovery searches cannot be done unless a user is logged in.','xss_clean'),(30,'show_sources_in_discover','on','If set to off then only the search box will be shown in the discovery interface (i.e. not the sources to search)','xss_clean'),(31,'use_elasticsearch','on','If set to on then elasticsearch will be used instead of the basic search (elasticsearch needs to be running of course)','xss_clean'),(32,'auth_server','https://auth.cafevariome.org/','Central Cafe Variome Auth server url (WARNING: do not change)','xss_clean'),(33,'installation_key','098f6bcd4621d373cade4e832627b4f6','Unique key for this installation (WARNING: do not change this value unless you know what you are doing)',''),(34,'all_records_require_an_id','on','Checks whether all records have a record ID during import (which must be unique)','xss_clean'),(35,'site_requires_login','on','If enabled then users will be required to log in to access any part of the site. If not logged in they will be presented with a login form.','xss_clean'),(36,'disable_record_hits_display','off','If set to on then record hits will not be viewable to users','xss_clean'),(37,'disable_individual_record_display','off','If set to on then individual records will not be viewable to users','xss_clean'),(38, 'allow_discovery', 'on', 'If set to on then users can discover on the site, otherwise the discovery is hidden', 'xss_clean');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sources`
--

DROP TABLE IF EXISTS `sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sources` (
  `source_id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_name` text NOT NULL,
  `owner_address` text NOT NULL,
  `owner_orcid` varchar(50) NOT NULL,
  `email` text NOT NULL,
  `name` varchar(30) NOT NULL,
  `uri` text NOT NULL,
  `description` text NOT NULL,
  `long_description` text NOT NULL,
  `status` varchar(15) NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'mysql',
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `curator_name` varchar(50) NOT NULL,
  `curator_address` text NOT NULL,
  `curator_orcid` varchar(50) NOT NULL,
  `curator_email` varchar(100) NOT NULL,
  `producer_name` varchar(50) NOT NULL,
  `producer_address` text NOT NULL,
  `producer_orcid` varchar(50) NOT NULL,
  `producer_email` varchar(100) NOT NULL,
  PRIMARY KEY (`source_id`),
  UNIQUE KEY `name` (`name`),
  KEY `status` (`status`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sources`
--

LOCK TABLES `sources` WRITE;
/*!40000 ALTER TABLE `sources` DISABLE KEYS */;
/*!40000 ALTER TABLE `sources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sources_groups`
--

DROP TABLE IF EXISTS `sources_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sources_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `source_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `source_id` (`source_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sources_groups`
--

LOCK TABLES `sources_groups` WRITE;
/*!40000 ALTER TABLE `sources_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `sources_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats_api`
--

DROP TABLE IF EXISTS `stats_api`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats_api` (
  `num` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(25) NOT NULL,
  `uri` text NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats_api`
--

LOCK TABLES `stats_api` WRITE;
/*!40000 ALTER TABLE `stats_api` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_api` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats_logins`
--

DROP TABLE IF EXISTS `stats_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats_logins` (
  `num` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(25) NOT NULL,
  `username` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL,
  `baseurl` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats_logins`
--

LOCK TABLES `stats_logins` WRITE;
/*!40000 ALTER TABLE `stats_logins` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats_orcid_alert`
--

DROP TABLE IF EXISTS `stats_orcid_alert`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats_orcid_alert` (
  `user_id` int(5) NOT NULL,
  `alert_shown` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats_orcid_alert`
--

LOCK TABLES `stats_orcid_alert` WRITE;
/*!40000 ALTER TABLE `stats_orcid_alert` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_orcid_alert` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats_registrations`
--

DROP TABLE IF EXISTS `stats_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats_registrations` (
  `num` int(11) NOT NULL DEFAULT '0',
  `baseurl` varchar(50) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats_registrations`
--

LOCK TABLES `stats_registrations` WRITE;
/*!40000 ALTER TABLE `stats_registrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats_searches`
--

DROP TABLE IF EXISTS `stats_searches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats_searches` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(25) NOT NULL,
  `user` varchar(50) NOT NULL,
  `term` varchar(250) NOT NULL,
  `source` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats_searches`
--

LOCK TABLES `stats_searches` WRITE;
/*!40000 ALTER TABLE `stats_searches` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_searches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats_variant`
--

DROP TABLE IF EXISTS `stats_variant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats_variant` (
  `cafevariome_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`cafevariome_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats_variant`
--

LOCK TABLES `stats_variant` WRITE;
/*!40000 ALTER TABLE `stats_variant` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_variant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats_variants`
--

DROP TABLE IF EXISTS `stats_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats_variants` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(25) NOT NULL,
  `term` text NOT NULL,
  `source` varchar(50) NOT NULL,
  `sharing_policy` varchar(20) NOT NULL,
  `format` varchar(20) NOT NULL,
  `datetime` varchar(50) NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats_variants`
--

LOCK TABLES `stats_variants` WRITE;
/*!40000 ALTER TABLE `stats_variants` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `themes` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` varchar(30) NOT NULL,
  `header_colour_from` varchar(20) NOT NULL,
  `header_colour_to` varchar(20) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `background` varchar(50) NOT NULL,
  `navbar_font_colour` varchar(20) NOT NULL,
  `navbar_font_colour_hover` varchar(20) NOT NULL,
  `navbar_selected_tab_colour` varchar(20) NOT NULL,
  `font_name` varchar(50) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`theme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `themes`
--

LOCK TABLES `themes` WRITE;
/*!40000 ALTER TABLE `themes` DISABLE KEYS */;
INSERT INTO `themes` VALUES (1,'cv_default','#6c737e','#afb3ba','cafevariome-logo-full.png','grey.png','#eeeeee','#ffffff','#6c737e','Muli','2013-12-12 10:08:33');
/*!40000 ALTER TABLE `themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` varchar(50) CHARACTER SET utf8 NOT NULL,
  `last_login` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `company` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `orcid` varchar(25) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `orcid` (`orcid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'','admin','e10adc3949ba59abbe56e057f20f883e','','test@gmail.com','','',0,'','','0',1,'admin','admin','admin','',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_groups`
--

LOCK TABLES `users_groups` WRITE;
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;
INSERT INTO `users_groups` VALUES (1,1,1),(3,1,1);
/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `variant_aliases`
--

DROP TABLE IF EXISTS `variant_aliases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `variant_aliases` (
  `alias_id` int(10) NOT NULL AUTO_INCREMENT,
  `hgvs` varchar(50) NOT NULL,
  `ref` varchar(50) NOT NULL,
  `cafevariome_id` int(10) NOT NULL,
  PRIMARY KEY (`alias_id`),
  KEY `hgvs` (`hgvs`,`ref`,`cafevariome_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variant_aliases`
--

LOCK TABLES `variant_aliases` WRITE;
/*!40000 ALTER TABLE `variant_aliases` DISABLE KEYS */;
/*!40000 ALTER TABLE `variant_aliases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `variants`
--

DROP TABLE IF EXISTS `variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;

CREATE TABLE `variants` (
  `cafevariome_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` varchar(200) NOT NULL,
  `included` tinyint(1) NOT NULL DEFAULT 1,
  `requested` tinyint(1) NOT NULL DEFAULT 0,
  `IE_date_time` timestamp NOT NULL DEFAULT "0000-00-00 00:00:00",
  `req_date_time` timestamp,
  `source` varchar(50) NOT NULL,
  `sharing_policy` varchar(50) NOT NULL DEFAULT 'openAccess',
  `mutalyzer_check` tinyint(1) NOT NULL,
  `source_url` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `genome_chr` varchar(50) NOT NULL,
  `genome_build` varchar(50) NOT NULL,
  `genome_start` int(11) NOT NULL,
  `genome_stop` int(11) NOT NULL,
  `accession_ref` varchar(50) NOT NULL,
  `accession_start` int(11) NOT NULL,
  `accession_stop` int(11) NOT NULL,
  `dna_sequence` varchar(150) NOT NULL,
  `protein_sequence` varchar(150) NOT NULL,
  `gene_symbol` varchar(50) NOT NULL,
  `hgvs_reference` varchar(50) NOT NULL,
  `hgvs_name` varchar(50) NOT NULL,
  `laboratory` varchar(50) NOT NULL,
  `individual_id` varchar(50) NOT NULL,
  `variant_id` varchar(50) NOT NULL,
  `pathogenicity` varchar(50) NOT NULL DEFAULT 'Unknown',
  `detection_method` text NOT NULL,
  `comment` text NOT NULL,
  `dbsnp_id` varchar(15) NOT NULL,
  `pmid` varchar(20) NOT NULL,
  PRIMARY KEY (`cafevariome_id`),
  KEY `source` (`source`),
  KEY `gene_symbol` (`gene_symbol`),
  KEY `genome_chr` (`genome_chr`,`genome_start`,`genome_stop`),
  KEY `accession_ref` (`accession_ref`,`accession_start`,`accession_stop`),
  KEY `hgvs_reference` (`hgvs_reference`, `hgvs_name`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- CREATE TABLE `variants` (
--   `cafevariome_id` int(11) NOT NULL AUTO_INCREMENT,
--   `variant_id` varchar(50) NOT NULL,
--   `source` varchar(50) NOT NULL,
--   `laboratory` varchar(50) NOT NULL,
--   `gene` varchar(50) NOT NULL,
--   `LRG` varchar(10) NOT NULL,
--   `ref` varchar(50) NOT NULL,
--   `hgvs` varchar(50) NOT NULL,
--   `genomic_ref` varchar(150) NOT NULL,
--   `genomic_hgvs` varchar(150) NOT NULL,
--   `protein_ref` varchar(150) NOT NULL,
--   `protein_hgvs` varchar(150) NOT NULL,
--   `phenotype` text NOT NULL,
--   `individual_id` varchar(50) NOT NULL,
--   `gender` varchar(20) NOT NULL,
--   `ethnicity` varchar(30) NOT NULL,
--   `location_ref` varchar(50) NOT NULL,
--   `start` int(15) NOT NULL,
--   `end` int(15) NOT NULL,
--   `base_change` text NOT NULL,
--   `strand` varchar(2) NOT NULL,
--   `build` varchar(50) NOT NULL,
--   `pathogenicity` varchar(50) NOT NULL DEFAULT 'Unknown',
--   `pathogenicity_list_type` varchar(30) NOT NULL,
--   `detection_method` text NOT NULL,
--   `germline_or_somatic` varchar(10) NOT NULL,
--   `comment` text NOT NULL,
--   `sharing_policy` varchar(50) NOT NULL DEFAULT 'openAccess',
--   `mutalyzer_check` tinyint(1) NOT NULL,
--   `source_url` text NOT NULL,
--   `dbsnp_id` varchar(15) NOT NULL,
--   `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
--   `pmid` varchar(20) NOT NULL,
--   `active` tinyint(4) NOT NULL DEFAULT '1',
--   PRIMARY KEY (`cafevariome_id`),
--   KEY `source` (`source`),
--   KEY `gene` (`gene`),
--   KEY `location_ref` (`location_ref`,`start`,`end`),
--   KEY `ref` (`ref`),
--   KEY `hgvs` (`hgvs`),
--   KEY `active` (`active`),
--   FULLTEXT KEY `phenotype` (`phenotype`)
-- ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
-- /*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variants`
--


ALTER TABLE `variants` ADD INDEX(`cafevariome_id`);



LOCK TABLES `variants` WRITE;
/*!40000 ALTER TABLE `variants` DISABLE KEYS */;
/*!40000 ALTER TABLE `variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `variants_to_phenotypes`
--

DROP TABLE IF EXISTS `variants_to_phenotypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `variants_to_phenotypes` (
  `variants_to_phenotypes_id` int(11) NOT NULL AUTO_INCREMENT,
  `cafevariome_id` int(11) NOT NULL,
  `termName` text NOT NULL,
  PRIMARY KEY (`variants_to_phenotypes_id`),
  KEY `cafevariome_id` (`cafevariome_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variants_to_phenotypes`
--

LOCK TABLES `variants_to_phenotypes` WRITE;
/*!40000 ALTER TABLE `variants_to_phenotypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `variants_to_phenotypes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-06-23 15:53:39

CREATE TABLE `view_derids` (
  `count` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) DEFAULT NULL,
  `username` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;