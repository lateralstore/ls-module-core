CREATE TABLE IF NOT EXISTS `core_configuration_records` (
  `id` int(11) NOT NULL auto_increment,
  `record_code` varchar(150) default NULL,
  `config_data` text,
  PRIMARY KEY  (`id`),
  KEY `record_code` (`record_code`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `core_cron_jobs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`que_name` varchar(255) DEFAULT NULL,
	`handler_name` varchar(100) DEFAULT NULL,
	`param_data` text,
	`created_at` datetime DEFAULT NULL,
	`available_at` datetime DEFAULT NULL,
	`started_at` datetime DEFAULT NULL,
	`retry` tinyint(4) DEFAULT NULL,
	`attempts` int(11) DEFAULT NULL,
	`version` int(11) DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `job_index` (`handler_name`)
);

CREATE TABLE IF NOT EXISTS `core_cron_table` (
	 `record_code` varchar(50) NOT NULL,
	 `updated_at` datetime DEFAULT NULL,
	 `postpone_until` DATETIME NULL,
	 PRIMARY KEY (`record_code`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `core_eula_unread_users` (
	`user_id` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`user_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `core_eula_info` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`agreement_text` text,
	`accepted_by` int(11) DEFAULT NULL,
	`accepted_on` datetime DEFAULT NULL,
	PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `core_metrics` (
  `id` int(11) NOT NULL auto_increment,
  `total_amount` decimal(15,2) default NULL,
  `total_order_num` int(11) default NULL,
  `page_views` int(11) default NULL,
  `updated` date default NULL,
  `update_lock` datetime default NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO core_metrics(id, total_amount, total_order_num, page_views, updated) VALUES (1, 0, 0, 0, CURRENT_DATE()) ON DUPLICATE KEY UPDATE id = id;
ALTER TABLE `db_session_data` CHANGE `client_ip` `client_ip` VARCHAR(45);
