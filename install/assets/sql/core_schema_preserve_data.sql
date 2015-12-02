-- MySQL SQL Dump

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `cafevariome`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `autocomplete`
--

DROP TABLE IF EXISTS `autocomplete`;
CREATE TABLE `autocomplete` (
  `term` varchar(1000) NOT NULL,
  `type` varchar(50) NOT NULL,
  KEY `term` (`term`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `core_fields`
--

DROP TABLE IF EXISTS `core_fields`;
CREATE TABLE `core_fields` (
  `core_field_id` int(11) NOT NULL AUTO_INCREMENT,
  `core_field_name` varchar(150) NOT NULL,
  PRIMARY KEY (`core_field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `curators`
--

DROP TABLE IF EXISTS `curators`;
CREATE TABLE `curators` (
  `curator_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  PRIMARY KEY (`curator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `data_requests`
--

DROP TABLE IF EXISTS `data_requests`;
CREATE TABLE `data_requests` (
  `request_id` int(10) NOT NULL AUTO_INCREMENT,
  `justification` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `datetime` text NOT NULL,
  `source` varchar(50) NOT NULL,
  `ip` text NOT NULL,
  `string` varchar(35) NOT NULL,
  `resultreason` text NOT NULL,
  `result` varchar(50) NOT NULL,
  PRIMARY KEY (`request_id`),
  KEY `string` (`string`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `display_fields`
--

DROP TABLE IF EXISTS `display_fields`;
CREATE TABLE `display_fields` (
  `display_field_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `visible_name` text NOT NULL,
  `order` int(11) NOT NULL,
  `sharing_policy` varchar(20) NOT NULL DEFAULT 'openAccess',
  `type` varchar(20) NOT NULL DEFAULT 'search_result',
  PRIMARY KEY (`display_field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=432 ;

-- --------------------------------------------------------

--
-- Table structure for table `frequencies`
--

DROP TABLE IF EXISTS `frequencies`;
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

-- --------------------------------------------------------

--
-- Table structure for table `gene2omim`
--

DROP TABLE IF EXISTS `gene2omim`;
CREATE TABLE `gene2omim` (
  `gene` varchar(25) NOT NULL,
  `disorder` text NOT NULL,
  `omim_id` varchar(15) NOT NULL,
  KEY `gene` (`gene`),
  KEY `omim_id` (`omim_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `genes`
--

DROP TABLE IF EXISTS `genes`;
CREATE TABLE `genes` (
  `gene_symbol` varchar(50) NOT NULL,
  KEY `gene_symbol` (`gene_symbol`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `inabox_downloads`
--

DROP TABLE IF EXISTS `inabox_downloads`;
CREATE TABLE `inabox_downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` text NOT NULL,
  `institute` text NOT NULL,
  `email` text NOT NULL,
  `ip` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

DROP TABLE IF EXISTS `keys`;
CREATE TABLE `keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mailing_list`
--

DROP TABLE IF EXISTS `mailing_list`;
CREATE TABLE `mailing_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` text NOT NULL,
  `email` text NOT NULL,
  `ip` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(20) NOT NULL,
  PRIMARY KEY (`menu_id`),
  UNIQUE KEY `menu_name` (`menu_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `message_threads`
--

DROP TABLE IF EXISTS `message_threads`;
CREATE TABLE `message_threads` (
  `thread_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(10) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`thread_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `msg_messages`
--

DROP TABLE IF EXISTS `msg_messages`;
CREATE TABLE `msg_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `priority` int(2) NOT NULL DEFAULT '0',
  `sender_id` int(11) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `msg_participants`
--

DROP TABLE IF EXISTS `msg_participants`;
CREATE TABLE `msg_participants` (
  `user_id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`thread_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `msg_status`
--

DROP TABLE IF EXISTS `msg_status`;
CREATE TABLE `msg_status` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`message_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `msg_threads`
--

DROP TABLE IF EXISTS `msg_threads`;
CREATE TABLE `msg_threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `node_list`
--

DROP TABLE IF EXISTS `node_list`;
CREATE TABLE `node_list` (
  `node_id` int(11) NOT NULL AUTO_INCREMENT,
  `node_name` varchar(50) NOT NULL,
  `node_uri` varchar(50) NOT NULL,
  `node_key` varchar(32) NOT NULL,
  `node_status` varchar(10) NOT NULL,
  PRIMARY KEY (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_sessions`
--

DROP TABLE IF EXISTS `oauth_sessions`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_session_scopes`
--

DROP TABLE IF EXISTS `oauth_session_scopes`;
CREATE TABLE `oauth_session_scopes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` int(11) unsigned NOT NULL,
  `access_token` varchar(50) NOT NULL DEFAULT '',
  `scope` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  KEY `scope` (`scope`),
  KEY `access_token` (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ontology_list`
--

DROP TABLE IF EXISTS `ontology_list`;
CREATE TABLE `ontology_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `virtualid` int(15) NOT NULL,
  `abbreviation` varchar(20) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `ranking` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `orcid_alert`
--

DROP TABLE IF EXISTS `orcid_alert`;
CREATE TABLE `orcid_alert` (
  `user_id` int(5) NOT NULL,
  `alert_shown` tinyint(1) NOT NULL,
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(50) NOT NULL,
  `page_content` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_menu` varchar(20) NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `phenotypes`
--

DROP TABLE IF EXISTS `phenotypes`;
CREATE TABLE `phenotypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cafevariome_id` int(11) NOT NULL,
  `sourceId` varchar(15) NOT NULL,
  `termId` varchar(200) NOT NULL,
  `termName` varchar(200) NOT NULL,
  `ontologyVersion` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `local_phenotypes_lookup`;
CREATE TABLE `local_phenotypes_lookup` (
  `lookup_id` int(11) NOT NULL AUTO_INCREMENT,
  `network_key` varchar(32) NOT NULL,
  `phenotype_attribute` varchar(128) NOT NULL,
  `phenotype_values` varchar(256) NOT NULL,
  PRIMARY KEY (`lookup_id`),
  KEY `network_key` (`network_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pheno_dag`
--

DROP TABLE IF EXISTS `pheno_dag`;
CREATE TABLE `pheno_dag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ontology` varchar(100) DEFAULT NULL,
  `termid` varchar(100) NOT NULL,
  `parentid` varchar(100) NOT NULL,
  `termname` varchar(200) NOT NULL,
  `terminalnode` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_title` varchar(120) NOT NULL,
  `post_body` text NOT NULL,
  `post_date_sort` datetime NOT NULL,
  `post_date` varchar(30) NOT NULL,
  `post_visible` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `preferences`
--

DROP TABLE IF EXISTS `preferences`;
CREATE TABLE `preferences` (
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prefixes`
--

DROP TABLE IF EXISTS `prefixes`;
CREATE TABLE `prefixes` (
  `prefix_id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(10) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`prefix_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `primary_phenotype_lookup`
--

DROP TABLE IF EXISTS `primary_phenotype_lookup`;
CREATE TABLE `primary_phenotype_lookup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sourceId` varchar(15) NOT NULL,
  `termId` varchar(200) NOT NULL,
  `termName` varchar(200) NOT NULL,
  `termDefinition` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `refseq`
--

DROP TABLE IF EXISTS `refseq`;
CREATE TABLE `refseq` (
  `accession` varchar(25) NOT NULL,
  KEY `accession` (`accession`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `scopes`
--

DROP TABLE IF EXISTS `scopes`;
CREATE TABLE `scopes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `scope` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `scope` (`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `setting_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(100) NOT NULL,
  `info` text NOT NULL,
  `validation_rules` varchar(100) NOT NULL DEFAULT 'required|xss_clean',
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

DROP TABLE IF EXISTS `sources`;
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
  `date_time` datetime NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `sources_groups`
--

DROP TABLE IF EXISTS `sources_groups`;
CREATE TABLE `sources_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `source_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `source_id` (`source_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `stats_api`
--

DROP TABLE IF EXISTS `stats_api`;
CREATE TABLE `stats_api` (
  `num` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(25) NOT NULL,
  `uri` text NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stats_logins`
--

DROP TABLE IF EXISTS `stats_logins`;
CREATE TABLE `stats_logins` (
  `num` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(25) NOT NULL,
  `username` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL,
  `baseurl` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stats_orcid_alert`
--

DROP TABLE IF EXISTS `stats_orcid_alert`;
CREATE TABLE `stats_orcid_alert` (
  `user_id` int(5) NOT NULL,
  `alert_shown` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stats_registrations`
--

DROP TABLE IF EXISTS `stats_registrations`;
CREATE TABLE `stats_registrations` (
  `num` int(11) NOT NULL DEFAULT '0',
  `baseurl` varchar(50) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stats_searches`
--

DROP TABLE IF EXISTS `stats_searches`;
CREATE TABLE `stats_searches` (
  `num` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(25) NOT NULL,
  `username` varchar(50) NOT NULL,
  `term` varchar(250) NOT NULL,
  `source` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stats_variant`
--

DROP TABLE IF EXISTS `stats_variant`;
CREATE TABLE `stats_variant` (
  `cafevariome_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`cafevariome_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stats_variants`
--

DROP TABLE IF EXISTS `stats_variants`;
CREATE TABLE `stats_variants` (
  `num` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(25) NOT NULL,
  `term` text NOT NULL,
  `source` varchar(50) NOT NULL,
  `sharing_policy` varchar(20) NOT NULL,
  `format` varchar(20) NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
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
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `orcid` (`orcid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE `users_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

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
--   `phenotype_omim` varchar(15) NOT NULL,
--   `individual_id` varchar(50) NOT NULL,
--   `gender` varchar(20) NOT NULL,
--   `ethnicity` varchar(30) NOT NULL,
--   `location_ref` varchar(50) NOT NULL,
--   `start` int(15) NOT NULL,
--   `end` int(15) NOT NULL,
--   `strand` varchar(2) NOT NULL,
--   `build` varchar(50) NOT NULL,
--   `pathogenicity` varchar(30) NOT NULL DEFAULT 'Unknown',
--   `pathogenicity_list_type` varchar(30) NOT NULL,
--   `detection_method` text NOT NULL,
--   `germline_or_somatic` varchar(10) NOT NULL,
--   `comment` text NOT NULL,
--   `sharing_policy` varchar(50) NOT NULL DEFAULT 'openAccess',
--   `mutalyzer_check` tinyint(1) NOT NULL,
--   `source_url` text NOT NULL,
--   `dbsnp_id` varchar(15) NOT NULL,
--   `date_time` varchar(30) NOT NULL,
--   `pmid` varchar(20) NOT NULL,
--   `active` tinyint(4) NOT NULL DEFAULT '1',
--   `foobar` text NOT NULL,
--   KEY `cafevariome_id` (`cafevariome_id`),
--   KEY `source` (`source`),
--   KEY `gene` (`gene`),
--   KEY `location_ref` (`location_ref`,`start`,`end`),
--   KEY `ref` (`ref`),
--   KEY `hgvs` (`hgvs`),
--   KEY `active` (`active`),
--   FULLTEXT KEY `phenotype` (`phenotype`)
-- ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4032 ;

-- --------------------------------------------------------

--
-- Table structure for table `variant_aliases`
--



-- ALTER TABLE `variants` ADD INDEX(`cafevariome_id`);


DROP TABLE IF EXISTS `variant_aliases`;
CREATE TABLE `variant_aliases` (
  `alias_id` int(10) NOT NULL AUTO_INCREMENT,
  `hgvs` varchar(50) NOT NULL,
  `ref` varchar(50) NOT NULL,
  `cafevariome_id` int(10) NOT NULL,
  PRIMARY KEY (`alias_id`),
  KEY `hgvs` (`hgvs`,`ref`,`cafevariome_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
