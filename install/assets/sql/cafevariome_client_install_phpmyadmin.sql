SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

DROP TABLE IF EXISTS `applications`;
CREATE TABLE IF NOT EXISTS `applications` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT '',
  `client_id` varchar(32) NOT NULL DEFAULT '',
  `client_secret` varchar(32) NOT NULL DEFAULT '',
  `redirect_uri` varchar(250) NOT NULL DEFAULT '',
  `auto_approve` tinyint(1) NOT NULL DEFAULT '0',
  `autonomous` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('development','pending','approved','rejected') NOT NULL DEFAULT 'development',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `notes` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `autocomplete`;
CREATE TABLE IF NOT EXISTS `autocomplete` (
  `term` varchar(1000) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `beacon_sharing_policies`;
CREATE TABLE IF NOT EXISTS `beacon_sharing_policies` (
  `setting_id` int(10) NOT NULL,
  `sharing_policy` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `last_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `beacon_sharing_policies` (`setting_id`, `sharing_policy`, `status`, `last_changed`) VALUES
(1, 'openAccess', 0, '2014-11-17 14:05:50'),
(2, 'linkedAccess', 0, '2014-11-17 14:05:50'),
(3, 'restrictedAccess', 0, '2014-11-17 14:06:08');

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `core_fields`;
CREATE TABLE IF NOT EXISTS `core_fields` (
  `core_field_id` int(11) NOT NULL,
  `core_field_name` varchar(150) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

INSERT INTO `core_fields` (`core_field_id`, `core_field_name`) VALUES
(1, 'variant_id'),
(2, 'gene'),
(3, 'LRG'),
(4, 'ref'),
(5, 'hgvs'),
(6, 'phenotype'),
(7, 'individual_id'),
(8, 'gender'),
(9, 'ethnicity'),
(10, 'pathogenicity'),
(11, 'location_ref'),
(12, 'start'),
(13, 'end'),
(14, 'build'),
(15, 'source_url'),
(16, 'comment'),
(17, 'sharing_policy');

DROP TABLE IF EXISTS `curators`;
CREATE TABLE IF NOT EXISTS `curators` (
  `curator_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `data_requests`;
CREATE TABLE IF NOT EXISTS `data_requests` (
  `request_id` int(10) NOT NULL,
  `justification` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `datetime` text NOT NULL,
  `source` varchar(50) NOT NULL,
  `term` text NOT NULL,
  `ip` text NOT NULL,
  `string` varchar(35) NOT NULL,
  `resultreason` text NOT NULL,
  `result` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `display_fields`;
CREATE TABLE IF NOT EXISTS `display_fields` (
  `display_field_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `visible_name` text NOT NULL,
  `order` int(11) NOT NULL,
  `sharing_policy` varchar(20) NOT NULL DEFAULT 'openAccess',
  `type` varchar(20) NOT NULL DEFAULT 'search_result'
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

INSERT INTO `display_fields` (`display_field_id`, `name`, `visible_name`, `order`, `sharing_policy`, `type`) VALUES
(1, 'cafevariome_id', 'Cafe Variome ID', 1, 'openAccess', 'search_result'),
(2, 'gene', 'Gene', 2, 'openAccess', 'search_result'),
(3, 'ref', 'Reference', 3, 'openAccess', 'search_result'),
(4, 'hgvs', 'HGVS', 4, 'openAccess', 'search_result'),
(5, 'phenotype', 'Phenotype', 5, 'openAccess', 'search_result'),
(6, 'location_ref', 'Chr', 6, 'openAccess', 'search_result'),
(7, 'start', 'Start', 7, 'openAccess', 'search_result'),
(8, 'end', 'End', 8, 'openAccess', 'search_result'),
(9, 'source_url', 'Source URL', 9, 'openAccess', 'search_result'),
(10, 'cafevariome_id', 'Cafe Variome ID', 1, 'linkedAccess', 'search_result'),
(11, 'source_url', 'Source URL', 2, 'linkedAccess', 'search_result'),
(12, 'cafevariome_id', 'Cafe Variome ID', 1, 'restrictedAccess', 'search_result'),
(13, 'gene', 'Gene', 2, 'restrictedAccess', 'search_result'),
(14, 'ref', 'Reference', 3, 'restrictedAccess', 'search_result'),
(15, 'hgvs', 'HGVS', 4, 'restrictedAccess', 'search_result'),
(16, 'phenotype', 'Phenotype', 5, 'restrictedAccess', 'search_result'),
(17, 'pathogenicity', 'Pathogenicity', 6, 'restrictedAccess', 'search_result'),
(18, 'location_ref', 'Chr', 7, 'restrictedAccess', 'search_result'),
(19, 'start', 'Start', 8, 'restrictedAccess', 'search_result'),
(20, 'end', 'End', 9, 'restrictedAccess', 'search_result'),
(21, 'source_url', 'Source URL', 10, 'restrictedAccess', 'search_result'),
(22, 'cafevariome_id', 'Cafe Variome ID', 1, '', 'individual_record'),
(23, 'gene', 'Gene', 2, '', 'individual_record'),
(24, 'ref', 'Reference', 3, '', 'individual_record'),
(25, 'hgvs', 'HGVS', 4, '', 'individual_record'),
(26, 'phenotype', 'Phenotype', 5, '', 'individual_record'),
(27, 'location_ref', 'Chr', 6, '', 'individual_record'),
(28, 'start', 'Start', 7, '', 'individual_record'),
(29, 'end', 'End', 8, '', 'individual_record'),
(30, 'dbsnp_id', 'dbSNP rsID', 9, '', 'individual_record'),
(31, 'comment', 'Comment', 10, '', 'individual_record');

DROP TABLE IF EXISTS `federated`;
CREATE TABLE IF NOT EXISTS `federated` (
  `federated_id` int(11) NOT NULL,
  `federated_name` varchar(50) NOT NULL,
  `federated_uri` varchar(50) NOT NULL,
  `federated_status` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `frequencies`;
CREATE TABLE IF NOT EXISTS `frequencies` (
  `frequency_id` int(10) NOT NULL,
  `cafevariome_id` int(15) NOT NULL,
  `frequency_type` varchar(50) NOT NULL,
  `number_samples` int(10) NOT NULL,
  `population_type` varchar(50) NOT NULL,
  `population_term` varchar(50) NOT NULL,
  `population_accession` varchar(10) NOT NULL,
  `frequency` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `gene2omim`;
CREATE TABLE IF NOT EXISTS `gene2omim` (
  `gene` varchar(25) NOT NULL,
  `disorder` text NOT NULL,
  `omim_id` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `genes`;
CREATE TABLE IF NOT EXISTS `genes` (
  `gene_symbol` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'general', 'General User'),
(3, 'curator', 'Curator');

DROP TABLE IF EXISTS `inabox_downloads`;
CREATE TABLE IF NOT EXISTS `inabox_downloads` (
  `id` int(11) NOT NULL,
  `fullname` text NOT NULL,
  `institute` text NOT NULL,
  `email` text NOT NULL,
  `description` text NOT NULL,
  `ip` text NOT NULL,
  `date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `installations`;
CREATE TABLE IF NOT EXISTS `installations` (
  `installation_id` int(11) NOT NULL,
  `installation_key` varchar(100) NOT NULL,
  `installation_name` text NOT NULL,
  `installation_base_url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `installation_networks`;
CREATE TABLE IF NOT EXISTS `installation_networks` (
  `installation_networks_id` int(11) NOT NULL,
  `installation_key` varchar(100) NOT NULL,
  `network_key` varchar(100) NOT NULL,
  `installation_base_url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `keys`;
CREATE TABLE IF NOT EXISTS `keys` (
  `id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` mediumint(8) unsigned NOT NULL,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `authorized` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mailing_list`;
CREATE TABLE IF NOT EXISTS `mailing_list` (
  `id` int(11) NOT NULL,
  `fullname` text NOT NULL,
  `email` text NOT NULL,
  `ip` text NOT NULL,
  `date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `menus` (`menu_id`, `menu_name`) VALUES
(3, 'Contact'),
(2, 'Discover'),
(1, 'Home');

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(10) NOT NULL,
  `subject` text NOT NULL,
  `body` text NOT NULL,
  `thread_id` int(11) NOT NULL,
  `sent_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `message_threads`;
CREATE TABLE IF NOT EXISTS `message_threads` (
  `thread_id` int(11) NOT NULL,
  `status` int(10) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `msg_messages`;
CREATE TABLE IF NOT EXISTS `msg_messages` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `priority` int(2) NOT NULL DEFAULT '0',
  `sender_id` int(11) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `msg_participants`;
CREATE TABLE IF NOT EXISTS `msg_participants` (
  `user_id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `msg_status`;
CREATE TABLE IF NOT EXISTS `msg_status` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `msg_threads`;
CREATE TABLE IF NOT EXISTS `msg_threads` (
  `id` int(11) NOT NULL,
  `subject` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `networks`;
CREATE TABLE IF NOT EXISTS `networks` (
  `network_id` int(11) NOT NULL,
  `network_name` text NOT NULL,
  `network_key` varchar(100) NOT NULL,
  `network_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `network_requests`;
CREATE TABLE IF NOT EXISTS `network_requests` (
  `request_id` int(10) NOT NULL,
  `network_name` text NOT NULL,
  `network_key` varchar(100) NOT NULL,
  `installation_key` varchar(100) NOT NULL,
  `justification` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `ip` text NOT NULL,
  `resultreason` text NOT NULL,
  `result` varchar(50) NOT NULL DEFAULT 'pending'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `node_list`;
CREATE TABLE IF NOT EXISTS `node_list` (
  `node_id` int(11) NOT NULL,
  `node_name` varchar(50) NOT NULL,
  `node_uri` varchar(50) NOT NULL,
  `node_key` varchar(32) NOT NULL,
  `node_status` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `oauth_sessions`;
CREATE TABLE IF NOT EXISTS `oauth_sessions` (
  `id` int(11) unsigned NOT NULL,
  `client_id` varchar(32) NOT NULL DEFAULT '',
  `redirect_uri` varchar(250) NOT NULL DEFAULT '',
  `type_id` varchar(64) DEFAULT NULL,
  `type` enum('user','auto') NOT NULL DEFAULT 'user',
  `code` text,
  `access_token` varchar(50) DEFAULT '',
  `stage` enum('request','granted') NOT NULL DEFAULT 'request',
  `first_requested` int(10) unsigned NOT NULL,
  `last_updated` int(10) unsigned NOT NULL,
  `limited_access` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Used for user agent flows'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `oauth_session_scopes`;
CREATE TABLE IF NOT EXISTS `oauth_session_scopes` (
  `id` int(11) unsigned NOT NULL,
  `session_id` int(11) unsigned NOT NULL,
  `access_token` varchar(50) NOT NULL DEFAULT '',
  `scope` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ontology_list`;
CREATE TABLE IF NOT EXISTS `ontology_list` (
  `id` int(11) NOT NULL,
  `virtualid` int(15) NOT NULL,
  `abbreviation` varchar(20) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `ranking` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `ontology_list` (`id`, `virtualid`, `abbreviation`, `name`, `ranking`) VALUES
(1, 0, 'LocalList', 'Local Phenotype Descriptions', 1);

DROP TABLE IF EXISTS `orcid_alert`;
CREATE TABLE IF NOT EXISTS `orcid_alert` (
  `user_id` int(5) NOT NULL,
  `alert_shown` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `page_id` int(11) NOT NULL,
  `page_name` varchar(50) NOT NULL,
  `page_content` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_menu` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `pages` (`page_id`, `page_name`, `page_content`, `date_created`, `parent_menu`) VALUES
(1, 'Home', '<div class="well">\r\n<h3 style="text-align: center;">Welcome</h3>\r\n<hr />\r\n<h1 style="text-align: center;"></h1>\r\n<p style="text-align: center;">This is the default page for a new Cafe Variome installation. The Cafe Variome data discovery platform can be used by diagnostic networks/disease consortia to allow the controlled discovery of patients and variants without revealing detailed information that might compromise the research value of the data.</p>\r\n<p style="text-align: center;">Individual research/clinical groups can deposit data in an installation and control access to their data. The access-control levels range from fully open and immediately available, to fully controlled and only available to authorised users.</p>\r\n<p style="text-align: center;">A user-friendly, intuitive administrator dashboard gives owners complete control over their data and installation. Dashboard configuration options include a content management system for adding/editing custom pages and menus, full control over site appearance (logo, colours, backgrounds, themes). Easy import of source mutation data via templates, control over how search results are displayed (ordering and specifying of fields) and a comprehensive access control system for users and groups.</p><hr />\r\n</div>\r\n<div class="well">\r\n<p style="text-align: center;"><em><strong>This front page welcome message can be modified (and additional pages/menus added) through the content management area of the administrator dashboard.</strong></em></p>\r\n</div>', '2013-11-25 10:17:52', 'Home'),
(2, 'Contact', '<h2 style="text-align: center;">Contact</h2><hr /><p style="text-align: center;"><em><strong>This contact page can be modified (and additional pages/menus added) through the administrators dashboard. The contact page is required so that the Cafe Variome branding can be included in your installation.</strong></em></p><hr />', '2013-11-25 11:18:40', 'Contact');

DROP TABLE IF EXISTS `phenotypes`;
CREATE TABLE IF NOT EXISTS `phenotypes` (
  `id` int(11) NOT NULL,
  `cafevariome_id` int(11) NOT NULL,
  `attribute_sourceID` varchar(15) DEFAULT NULL,
  `attribute_termID` varchar(200) DEFAULT NULL,
  `attribute_termName` varchar(200) DEFAULT NULL,
  `attribute_qualifier` varchar(200) DEFAULT NULL,
  `value` varchar(200) DEFAULT 'present',
  `type` enum('quality','qualityValue','numeric') NOT NULL DEFAULT 'quality'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pheno_dag`;
CREATE TABLE IF NOT EXISTS `pheno_dag` (
  `id` int(11) NOT NULL,
  `ontology` varchar(100) DEFAULT NULL,
  `termid` varchar(100) NOT NULL,
  `parentid` varchar(100) NOT NULL,
  `termname` varchar(200) NOT NULL,
  `terminalnode` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL,
  `post_title` varchar(120) NOT NULL,
  `post_body` text NOT NULL,
  `post_date_sort` datetime NOT NULL,
  `post_date` varchar(30) NOT NULL,
  `post_visible` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `preferences`;
CREATE TABLE IF NOT EXISTS `preferences` (
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `preferences` (`name`, `value`) VALUES
('current_font_link', 'Muli'),
('header_colour_from', '#6c737e'),
('header_colour_to', '#afb3ba'),
('background', 'grey.png'),
('logo', 'cafevariome-logo-full.png'),
('font_size', '14px'),
('current_font_name', 'Muli'),
('id_prefix', 'vx'),
('id_current', '234333355'),
('report_usage', '1'),
('navbar_font_colour', '#eeeeee'),
('navbar_font_colour_hover', '#ffffff'),
('navbar_selected_tab_colour', '#6c737e');

DROP TABLE IF EXISTS `prefixes`;
CREATE TABLE IF NOT EXISTS `prefixes` (
  `prefix_id` int(11) NOT NULL,
  `prefix` varchar(10) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `primary_phenotype_lookup`;
CREATE TABLE IF NOT EXISTS `primary_phenotype_lookup` (
  `id` int(11) NOT NULL,
  `sourceId` varchar(15) NOT NULL,
  `termId` varchar(200) NOT NULL,
  `termName` varchar(200) NOT NULL,
  `termDefinition` varchar(250) DEFAULT NULL,
  `qualifier` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `query_builder_history`;
CREATE TABLE IF NOT EXISTS `query_builder_history` (
  `id` int(11) NOT NULL,
  `query_id` varchar(10) NOT NULL,
  `total_results` varchar(10) NOT NULL,
  `endpoint` varchar(100) NOT NULL,
  `query_statement` text NOT NULL,
  `query_response` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `refseq`;
CREATE TABLE IF NOT EXISTS `refseq` (
  `accession` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `scopes`;
CREATE TABLE IF NOT EXISTS `scopes` (
  `id` int(11) unsigned NOT NULL,
  `scope` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(100) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `search_fields`;
CREATE TABLE IF NOT EXISTS `search_fields` (
  `search_field_id` int(11) NOT NULL,
  `field_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `setting_id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(100) NOT NULL,
  `info` text NOT NULL,
  `validation_rules` varchar(100) NOT NULL DEFAULT 'required|xss_clean'
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

INSERT INTO `settings` (`setting_id`, `name`, `value`, `info`, `validation_rules`) VALUES
(1, 'site_title', 'Cafe Variome Client', 'Main title for the site that will be shown in metadata.', 'xss_clean'),
(2, 'site_description', 'Cafe Variome Instance', 'Brief description of the site that will be shown in metadata.', 'xss_clean'),
(3, 'site_author', 'Administrator', 'Name of site author that will be shown in metadata.', 'xss_clean'),
(4, 'site_keywords', 'mutation, diagnostics, database', 'Site keywords metadata to help with search engine optimisation and traffic.', 'xss_clean'),
(5, 'email', 'admin@cafevariome.org', '', 'required|xss_clean'),
(6, 'twitter', '', 'If Twitter username is here set then Twitter icon link appears in contact page. Leave blank to disable.', 'xss_clean'),
(7, 'rss', 'local', 'Specify a VALID rss feed or to use the internal Cafe Variome news feed then just enter local (on its own)', 'callback_rss_check|xss_clean'),
(8, 'google_analytics', '', 'Google Analytics tracking ID', 'xss_clean'),
(9, 'cvid_prefix', 'one', 'Prefix that is prepended to Cafe Variome IDs', 'xss_clean'),
(10, 'stats', 'on', '', 'xss_clean'),
(11, 'max_variants', '30000', '', 'required|xss_clean'),
(12, 'feature_table_name', 'variants', '', 'required|xss_clean'),
(13, 'messaging', 'on', 'Enables/disables the internal messaging system for all users', 'xss_clean'),
(14, 'database_structure', 'off', 'Enables the tab to change database structure in the settings admin interface', 'xss_clean'),
(15, 'federated', 'off', 'If set to on then the federated API is enables and allows remote discovery queries from other Cafe Variome installs', 'xss_clean'),
(16, 'federated_head', 'off', 'Sets this installation as the main federated head through which installs can be', 'xss_clean'),
(17, 'show_orcid_reminder', 'off', 'Shows a one off message to users on the home page reminding them to link their ORCID to their Cafe Variome account', 'xss_clean'),
(18, 'atomserver_enabled', 'off', '', 'xss_clean'),
(19, 'atomserver_user', '', '', 'xss_clean'),
(20, 'atomserver_password', '', '', 'xss_clean'),
(21, 'atomserver_uri', 'http://www.cafevariome.org/atomserver/v1/cafevariome/variants', '', 'xss_clean'),
(22, 'cafevariome_central', 'off', 'If set to on then this is a Cafe Variome Central installation - additional menus for describing the system will be enabled', 'xss_clean'),
(23, 'allow_registrations', 'on', 'If set to on then users can register on the site, otherwise the signup is hidden', 'xss_clean'),
(24, 'variant_count_cutoff', '0', 'If the number of variants discovered in a source is less than this then the results are hidden and the message in the variant_count_cutoff_message setting is displayed', 'xss_clean'),
(25, 'variant_count_cutoff_message', 'Unable to display results for this source, please contact admin@cafevariome.org', 'Message that is shown when the number of variants in less than that specified in the variant_count_cutoff setting', 'xss_clean'),
(26, 'dasigniter', 'on', 'If set to on then DASIgniter is enabled and variants in sources that are openAccess and linkedAccess will be available via DAS', 'xss_clean'),
(27, 'bioportalkey', '6d7d7db8-698c-4a56-9792-107217b3965c', 'In order to use phenotype ontologies you must sign up for a BioPortal account and supply your API key here. If this is left blank you only be able to use free text for phenotypes. Sign up at http://bioportal.bioontology.org/accounts/new', 'xss_clean'),
(28, 'template', 'default', 'Specify the name of the css template file (located in views/css/)', 'xss_clean'),
(29, 'discovery_requires_login', 'off', 'If set to on then discovery searches cannot be done unless a user is logged in.', 'xss_clean'),
(30, 'show_sources_in_discover', 'on', 'If set to off then only the search box will be shown in the discovery interface (i.e. not the sources to search)', 'xss_clean'),
(31, 'use_elasticsearch', 'on', 'If set to on then elasticsearch will be used instead of the basic search (elasticsearch needs to be running of course)', 'xss_clean'),
(32, 'auth_server', 'https://auth.cafevariome.org', 'Central Cafe Variome Auth server url (WARNING: do not change)', 'xss_clean'),
(33, 'installation_key', '098f6bcd4621d373cade4e832627b4f6', 'Unique key for this installation (WARNING: do not change this value unless you know what you are doing)', ''),
(34, 'all_records_require_an_id', 'on', 'Checks whether all records have a record ID during import (which must be unique)', 'xss_clean'),
(35, 'site_requires_login', 'off', 'If enabled then users will be required to log in to access any part of the site. If not logged in they will be presented with a login form.', 'xss_clean'),
(36, 'allow_record_hits_display', 'on', 'If set to on then record hits will be viewable by users', 'required|xss_clean'),
(37, 'allow_individual_record_display', 'on', 'If set to on then indiviaul records will be viewable by users', 'required|xss_clean');

DROP TABLE IF EXISTS `sources`;
CREATE TABLE IF NOT EXISTS `sources` (
  `source_id` int(11) NOT NULL,
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
  `producer_email` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `sources_groups`;
CREATE TABLE IF NOT EXISTS `sources_groups` (
  `id` mediumint(8) unsigned NOT NULL,
  `source_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `stats_api`;
CREATE TABLE IF NOT EXISTS `stats_api` (
  `num` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(25) NOT NULL,
  `uri` text NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `stats_logins`;
CREATE TABLE IF NOT EXISTS `stats_logins` (
  `num` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(25) NOT NULL,
  `username` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL,
  `baseurl` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `stats_orcid_alert`;
CREATE TABLE IF NOT EXISTS `stats_orcid_alert` (
  `user_id` int(5) NOT NULL,
  `alert_shown` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `stats_registrations`;
CREATE TABLE IF NOT EXISTS `stats_registrations` (
  `num` int(11) NOT NULL DEFAULT '0',
  `baseurl` varchar(50) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `stats_searches`;
CREATE TABLE IF NOT EXISTS `stats_searches` (
  `num` int(11) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `user` varchar(50) NOT NULL,
  `term` varchar(250) NOT NULL,
  `source` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `stats_variant`;
CREATE TABLE IF NOT EXISTS `stats_variant` (
  `cafevariome_id` int(11) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `stats_variants`;
CREATE TABLE IF NOT EXISTS `stats_variants` (
  `num` int(11) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `term` text NOT NULL,
  `source` varchar(50) NOT NULL,
  `sharing_policy` varchar(20) NOT NULL,
  `format` varchar(20) NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `themes`;
CREATE TABLE IF NOT EXISTS `themes` (
  `theme_id` int(11) NOT NULL,
  `theme_name` varchar(30) NOT NULL,
  `header_colour_from` varchar(20) NOT NULL,
  `header_colour_to` varchar(20) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `background` varchar(50) NOT NULL,
  `navbar_font_colour` varchar(20) NOT NULL,
  `navbar_font_colour_hover` varchar(20) NOT NULL,
  `navbar_selected_tab_colour` varchar(20) NOT NULL,
  `font_name` varchar(50) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `themes` (`theme_id`, `theme_name`, `header_colour_from`, `header_colour_to`, `logo`, `background`, `navbar_font_colour`, `navbar_font_colour_hover`, `navbar_selected_tab_colour`, `font_name`, `date_time`) VALUES
(1, 'cv_default', '#6c737e', '#afb3ba', 'cafevariome-logo-full.png', 'grey.png', '#eeeeee', '#ffffff', '#6c737e', 'Muli', '2013-12-12 10:08:33');

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` mediumint(8) unsigned NOT NULL,
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
  `is_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1);

DROP TABLE IF EXISTS `variants`;
CREATE TABLE IF NOT EXISTS `variants` (
  `cafevariome_id` int(11) NOT NULL,
  `variant_id` varchar(50) NOT NULL,
  `source` varchar(50) NOT NULL,
  `laboratory` varchar(50) NOT NULL,
  `gene` varchar(50) NOT NULL,
  `LRG` varchar(10) NOT NULL,
  `ref` varchar(50) NOT NULL,
  `hgvs` varchar(50) NOT NULL,
  `genomic_ref` varchar(150) NOT NULL,
  `genomic_hgvs` varchar(150) NOT NULL,
  `protein_ref` varchar(150) NOT NULL,
  `protein_hgvs` varchar(150) NOT NULL,
  `phenotype` text NOT NULL,
  `individual_id` varchar(50) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `ethnicity` varchar(30) NOT NULL,
  `location_ref` varchar(50) NOT NULL,
  `start` int(15) NOT NULL,
  `end` int(15) NOT NULL,
  `base_change` text NOT NULL,
  `strand` varchar(2) NOT NULL,
  `build` varchar(50) NOT NULL,
  `pathogenicity` varchar(50) NOT NULL DEFAULT 'Unknown',
  `pathogenicity_list_type` varchar(30) NOT NULL,
  `detection_method` text NOT NULL,
  `germline_or_somatic` varchar(10) NOT NULL,
  `comment` text NOT NULL,
  `sharing_policy` varchar(50) NOT NULL DEFAULT 'openAccess',
  `mutalyzer_check` tinyint(1) NOT NULL,
  `source_url` text NOT NULL,
  `dbsnp_id` varchar(15) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pmid` varchar(20) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `variants_to_phenotypes`;
CREATE TABLE IF NOT EXISTS `variants_to_phenotypes` (
  `variants_to_phenotypes_id` int(11) NOT NULL,
  `cafevariome_id` int(11) NOT NULL,
  `termName` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `variant_aliases`;
CREATE TABLE IF NOT EXISTS `variant_aliases` (
  `alias_id` int(10) NOT NULL,
  `hgvs` varchar(50) NOT NULL,
  `ref` varchar(50) NOT NULL,
  `cafevariome_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `client_id` (`client_id`);

ALTER TABLE `autocomplete`
  ADD KEY `term` (`term`), ADD KEY `type` (`type`);

ALTER TABLE `beacon_sharing_policies`
  ADD PRIMARY KEY (`setting_id`), ADD UNIQUE KEY `sharing_policy` (`sharing_policy`);

ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`);

ALTER TABLE `core_fields`
  ADD PRIMARY KEY (`core_field_id`);

ALTER TABLE `curators`
  ADD PRIMARY KEY (`curator_id`);

ALTER TABLE `data_requests`
  ADD PRIMARY KEY (`request_id`), ADD KEY `string` (`string`);

ALTER TABLE `display_fields`
  ADD PRIMARY KEY (`display_field_id`);

ALTER TABLE `federated`
  ADD PRIMARY KEY (`federated_id`), ADD KEY `federated_uri` (`federated_uri`);

ALTER TABLE `gene2omim`
  ADD KEY `gene` (`gene`), ADD KEY `omim_id` (`omim_id`);

ALTER TABLE `genes`
  ADD KEY `gene_symbol` (`gene_symbol`);

ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `inabox_downloads`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `installations`
  ADD PRIMARY KEY (`installation_id`);

ALTER TABLE `installation_networks`
  ADD PRIMARY KEY (`installation_networks_id`);

ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mailing_list`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `menus`
  ADD PRIMARY KEY (`menu_id`), ADD UNIQUE KEY `menu_name` (`menu_name`);

ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`), ADD KEY `from_user_id` (`sender_id`,`thread_id`);

ALTER TABLE `message_threads`
  ADD PRIMARY KEY (`thread_id`);

ALTER TABLE `msg_messages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `msg_participants`
  ADD PRIMARY KEY (`user_id`,`thread_id`);

ALTER TABLE `msg_status`
  ADD PRIMARY KEY (`message_id`,`user_id`);

ALTER TABLE `msg_threads`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `networks`
  ADD PRIMARY KEY (`network_id`);

ALTER TABLE `network_requests`
  ADD PRIMARY KEY (`request_id`);

ALTER TABLE `node_list`
  ADD PRIMARY KEY (`node_id`);

ALTER TABLE `oauth_sessions`
  ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

ALTER TABLE `oauth_session_scopes`
  ADD PRIMARY KEY (`id`), ADD KEY `session_id` (`session_id`), ADD KEY `scope` (`scope`), ADD KEY `access_token` (`access_token`);

ALTER TABLE `ontology_list`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `orcid_alert`
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`);

ALTER TABLE `phenotypes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pheno_dag`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `prefixes`
  ADD PRIMARY KEY (`prefix_id`);

ALTER TABLE `primary_phenotype_lookup`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `refseq`
  ADD KEY `accession` (`accession`);

ALTER TABLE `scopes`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `scope` (`scope`);

ALTER TABLE `search_fields`
  ADD PRIMARY KEY (`search_field_id`);

ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

ALTER TABLE `sources`
  ADD PRIMARY KEY (`source_id`), ADD UNIQUE KEY `name` (`name`), ADD KEY `status` (`status`), ADD KEY `type` (`type`);

ALTER TABLE `sources_groups`
  ADD PRIMARY KEY (`id`), ADD KEY `source_id` (`source_id`), ADD KEY `group_id` (`group_id`);

ALTER TABLE `stats_searches`
  ADD PRIMARY KEY (`num`);

ALTER TABLE `stats_variant`
  ADD PRIMARY KEY (`cafevariome_id`);

ALTER TABLE `stats_variants`
  ADD PRIMARY KEY (`num`);

ALTER TABLE `themes`
  ADD PRIMARY KEY (`theme_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`), ADD KEY `username` (`username`), ADD KEY `orcid` (`orcid`);

ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `variants`
  ADD KEY `cafevariome_id` (`cafevariome_id`), ADD KEY `source` (`source`), ADD KEY `gene` (`gene`), ADD KEY `location_ref` (`location_ref`,`start`,`end`), ADD KEY `ref` (`ref`), ADD KEY `hgvs` (`hgvs`), ADD KEY `active` (`active`), ADD FULLTEXT KEY `phenotype` (`phenotype`);

ALTER TABLE `variants_to_phenotypes`
  ADD PRIMARY KEY (`variants_to_phenotypes_id`), ADD KEY `cafevariome_id` (`cafevariome_id`);

ALTER TABLE `variant_aliases`
  ADD PRIMARY KEY (`alias_id`), ADD KEY `hgvs` (`hgvs`,`ref`,`cafevariome_id`);


ALTER TABLE `applications`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `beacon_sharing_policies`
  MODIFY `setting_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
ALTER TABLE `core_fields`
  MODIFY `core_field_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
ALTER TABLE `curators`
  MODIFY `curator_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `data_requests`
  MODIFY `request_id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `display_fields`
  MODIFY `display_field_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
ALTER TABLE `federated`
  MODIFY `federated_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
ALTER TABLE `inabox_downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `installations`
  MODIFY `installation_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `installation_networks`
  MODIFY `installation_networks_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `login_attempts`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mailing_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `menus`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `message_threads`
  MODIFY `thread_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `msg_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `msg_threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `networks`
  MODIFY `network_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `network_requests`
  MODIFY `request_id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `node_list`
  MODIFY `node_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `oauth_sessions`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `oauth_session_scopes`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `ontology_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
ALTER TABLE `phenotypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pheno_dag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `prefixes`
  MODIFY `prefix_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `primary_phenotype_lookup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `scopes`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `search_fields`
  MODIFY `search_field_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings`
  MODIFY `setting_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
ALTER TABLE `sources`
  MODIFY `source_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `sources_groups`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `stats_searches`
  MODIFY `num` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `stats_variants`
  MODIFY `num` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `themes`
  MODIFY `theme_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `users`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `users_groups`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
ALTER TABLE `variants`
  MODIFY `cafevariome_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `variants_to_phenotypes`
  MODIFY `variants_to_phenotypes_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `variant_aliases`
  MODIFY `alias_id` int(10) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
