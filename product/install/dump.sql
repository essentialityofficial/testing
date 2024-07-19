CREATE TABLE `users` (
`user_id` int NOT NULL AUTO_INCREMENT,
`email` varchar(320) NOT NULL,
`password` varchar(128) DEFAULT NULL,
`name` varchar(64) NOT NULL,
`billing` text,
`api_key` varchar(32) DEFAULT NULL,
`token_code` varchar(32) DEFAULT NULL,
`twofa_secret` varchar(16) DEFAULT NULL,
`anti_phishing_code` varchar(8) DEFAULT NULL,
`one_time_login_code` varchar(32) DEFAULT NULL,
`pending_email` varchar(128) DEFAULT NULL,
`email_activation_code` varchar(32) DEFAULT NULL,
`lost_password_code` varchar(32) DEFAULT NULL,
`type` tinyint NOT NULL DEFAULT '0',
`status` tinyint NOT NULL DEFAULT '0',
`is_newsletter_subscribed` tinyint NOT NULL DEFAULT '0',
`has_pending_internal_notifications` tinyint NOT NULL DEFAULT '0',
`plan_id` varchar(16) NOT NULL DEFAULT '',
`plan_expiration_date` datetime DEFAULT NULL,
`plan_settings` text,
`plan_trial_done` tinyint DEFAULT '0',
`plan_expiry_reminder` tinyint DEFAULT '0',
`payment_subscription_id` varchar(64) DEFAULT NULL,
`payment_processor` varchar(16) DEFAULT NULL,
`payment_total_amount` float DEFAULT NULL,
`payment_currency` varchar(4) DEFAULT NULL,
`referral_key` varchar(32) DEFAULT NULL,
`referred_by` varchar(32) DEFAULT NULL,
`referred_by_has_converted` tinyint DEFAULT '0',
`language` varchar(32) DEFAULT 'english',
`currency` varchar(4) DEFAULT NULL,
`timezone` varchar(32) DEFAULT 'UTC',
`preferences` text,
`extra` text,
`datetime` datetime DEFAULT NULL,
`ip` varchar(64) DEFAULT NULL,
`continent_code` varchar(8) DEFAULT NULL,
`country` varchar(8) DEFAULT NULL,
`city_name` varchar(32) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`browser_language` varchar(32) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`last_activity` datetime DEFAULT NULL,
`total_logins` int DEFAULT '0',
`user_deletion_reminder` tinyint(4) DEFAULT '0',
`source` varchar(32) DEFAULT 'direct',
PRIMARY KEY (`user_id`),
KEY `plan_id` (`plan_id`),
KEY `api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `users` (`user_id`, `email`, `password`, `api_key`, `referral_key`, `name`, `type`, `status`, `plan_id`, `plan_expiration_date`, `plan_settings`, `datetime`, `ip`, `last_activity`)
VALUES (1,'admin','$2y$10$uFNO0pQKEHSFcus1zSFlveiPCB3EvG9ZlES7XKgJFTAl5JbRGFCWy', md5(rand()), md5(rand()), 'AltumCode',1,1,'custom','2030-01-01 12:00:00', '{"no_ads":true,"email_reports_is_enabled":true,"teams_is_enabled":true,"websites_limit":-1,"sessions_events_limit":-1,"events_children_limit":-1,"events_children_retention":365,"sessions_replays_limit":-1,"sessions_replays_retention":30,"sessions_replays_time_limit":60,"websites_heatmaps_limit":-1,"websites_goals_limit":-1,"api_is_enabled":true,"affiliate_is_enabled":true}', NOW(),'',NOW());

-- SEPARATOR --

CREATE TABLE `users_logs` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`type` varchar(64) DEFAULT NULL,
`ip` varchar(64) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`continent_code` varchar(8) DEFAULT NULL,
`country_code` varchar(8) DEFAULT NULL,
`city_name` varchar(32) DEFAULT NULL,
`browser_language` varchar(32) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `users_logs_user_id` (`user_id`),
KEY `users_logs_ip_type_datetime_index` (`ip`,`type`,`datetime`),
CONSTRAINT `users_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `plans` (
`plan_id` int NOT NULL AUTO_INCREMENT,
`name` varchar(64) NOT NULL DEFAULT '',
`description` varchar(256) NOT NULL DEFAULT '',
`translations` text NOT NULL,
`prices` text NOT NULL,
`trial_days` int unsigned NOT NULL DEFAULT '0',
`settings` longtext NOT NULL,
`taxes_ids` text,
`color` varchar(16) DEFAULT NULL,
`status` tinyint(4) NOT NULL,
`order` int(10) unsigned DEFAULT '0',
`datetime` datetime NOT NULL,
PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- SEPARATOR --

CREATE TABLE `pages_categories` (
`pages_category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`icon` varchar(32) DEFAULT NULL,
`order` int NOT NULL DEFAULT '0',
`language` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`pages_category_id`),
KEY `url` (`url`),
KEY `pages_categories_url_language_index` (`url`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `pages` (
`page_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`pages_category_id` bigint unsigned DEFAULT NULL,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`icon` varchar(32) DEFAULT NULL,
`keywords` varchar(256) CHARACTER SET utf8mb4 DEFAULT NULL,
`editor` varchar(16) DEFAULT NULL,
`content` longtext,
`type` varchar(16) DEFAULT '',
`position` varchar(16) NOT NULL DEFAULT '',
`language` varchar(32) DEFAULT NULL,
`open_in_new_tab` tinyint DEFAULT '1',
`order` int DEFAULT '0',
`total_views` bigint unsigned DEFAULT '0',
`is_published` tinyint DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`page_id`),
KEY `pages_pages_category_id_index` (`pages_category_id`),
KEY `pages_url_index` (`url`),
KEY `pages_is_published_index` (`is_published`),
KEY `pages_language_index` (`language`),
CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`pages_category_id`) REFERENCES `pages_categories` (`pages_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `pages` (`pages_category_id`, `url`, `title`, `description`, `content`, `type`, `position`, `order`, `total_views`, `datetime`, `last_datetime`) VALUES
(NULL, 'https://altumcode.com/', 'Software by AltumCode', '', '', 'external', 'bottom', 1, 0, NOW(), NOW()),
(NULL, 'https://altumco.de/66analytics', 'Built with 66Analytics', '', '', 'external', 'bottom', 0, 0, NOW(), NOW());

-- SEPARATOR --

CREATE TABLE `blog_posts_categories` (
`blog_posts_category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`order` int NOT NULL DEFAULT '0',
`language` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`blog_posts_category_id`),
KEY `url` (`url`),
KEY `blog_posts_categories_url_language_index` (`url`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `blog_posts` (
`blog_post_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`blog_posts_category_id` bigint unsigned DEFAULT NULL,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`keywords` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`editor` varchar(16) DEFAULT NULL,
`content` longtext,
`language` varchar(32) DEFAULT NULL,
`total_views` bigint unsigned DEFAULT '0',
`is_published` tinyint DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`blog_post_id`),
KEY `blog_post_id_index` (`blog_post_id`),
KEY `blog_post_url_index` (`url`),
KEY `blog_posts_category_id` (`blog_posts_category_id`),
KEY `blog_posts_is_published_index` (`is_published`),
KEY `blog_posts_language_index` (`language`),
CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`blog_posts_category_id`) REFERENCES `blog_posts_categories` (`blog_posts_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `websites` (
`website_id` int NOT NULL AUTO_INCREMENT,
`pixel_key` varchar(16) NOT NULL DEFAULT '',
`user_id` int NOT NULL,
`domain_id` int DEFAULT NULL,
`name` varchar(256) NOT NULL DEFAULT '',
`scheme` varchar(8) NOT NULL DEFAULT '',
`host` varchar(128) NOT NULL DEFAULT '',
`path` varchar(256) DEFAULT NULL,
`tracking_type` varchar(16) DEFAULT 'normal',
`excluded_ips` text,
`query_parameters_tracking_is_enabled` tinyint DEFAULT '0',
`bot_exclusion_is_enabled` tinyint DEFAULT '1',
`current_month_sessions_events` int NOT NULL DEFAULT '0',
`current_month_events_children` int NOT NULL DEFAULT '0',
`current_month_sessions_replays` int NOT NULL DEFAULT '0',
`events_children_is_enabled` tinyint(4) NOT NULL DEFAULT '1',
`sessions_replays_is_enabled` tinyint(4) NOT NULL DEFAULT '0',
`email_reports_is_enabled` tinyint(4) NOT NULL DEFAULT '0',
`email_reports_last_date` datetime DEFAULT NULL,
`is_enabled` tinyint(4) NOT NULL DEFAULT '0',
`datetime` datetime NOT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`website_id`),
KEY `user_id` (`user_id`),
KEY `pixel` (`pixel_key`),
KEY `host` (`host`),
CONSTRAINT `websites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ROW_FORMAT=DYNAMIC ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `domains` (
`domain_id` int NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`scheme` varchar(8) NOT NULL DEFAULT '',
`host` varchar(128) NOT NULL DEFAULT '',
`custom_index_url` varchar(256) DEFAULT NULL,
`custom_not_found_url` varchar(256) DEFAULT NULL,
`is_enabled` tinyint(4) DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`domain_id`),
KEY `user_id` (`user_id`),
KEY `host` (`host`),
CONSTRAINT `domains_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ROW_FORMAT=DYNAMIC ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

alter table websites add constraint websites_domains_domain_id_fk foreign key (domain_id) references domains (domain_id) on update cascade on delete set null;

-- SEPARATOR --

CREATE TABLE `lightweight_events` (
`event_id` int NOT NULL AUTO_INCREMENT,
`website_id` int NOT NULL,
`type` varchar(16) DEFAULT NULL,
`path` varchar(1024) DEFAULT NULL,
`referrer_host` varchar(256) DEFAULT NULL,
`referrer_path` varchar(1024) DEFAULT NULL,
`utm_source` varchar(128) DEFAULT NULL,
`utm_medium` varchar(128) DEFAULT NULL,
`utm_campaign` varchar(128) DEFAULT NULL,
`country_code` varchar(8) DEFAULT NULL,
`city_name` varchar(128) DEFAULT NULL,
`os_name` varchar(32) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`browser_language` varchar(16) DEFAULT NULL,
`screen_resolution` varchar(16) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`date` datetime DEFAULT NULL,
`expiration_date` date DEFAULT NULL,
PRIMARY KEY (`event_id`),
KEY `website_id` (`website_id`),
KEY `date` (`date`) USING BTREE,
KEY `expiration_date` (`expiration_date`),
CONSTRAINT `lightweight_events_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `teams` (
`team_id` int NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`name` varchar(32) DEFAULT NULL,
`websites_ids` text,
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`team_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `teams_associations` (
`team_association_id` int unsigned NOT NULL AUTO_INCREMENT,
`team_id` int NOT NULL,
`user_id` int DEFAULT NULL,
`user_email` varchar(128) DEFAULT NULL,
`is_accepted` tinyint(4) NOT NULL DEFAULT '0',
`date` datetime NOT NULL,
`accepted_date` datetime DEFAULT NULL,
PRIMARY KEY (`team_association_id`),
KEY `team_id` (`team_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `teams_associations_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `teams_associations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `websites_visitors` (
`visitor_id` int NOT NULL AUTO_INCREMENT,
`visitor_uuid` varchar(64) NOT NULL DEFAULT '',
`website_id` int NOT NULL,
`custom_parameters` text,
`country_code` varchar(8) DEFAULT NULL,
`city_name` varchar(128) DEFAULT NULL,
`os_name` varchar(32) DEFAULT NULL,
`os_version` varchar(16) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`browser_version` varchar(16) DEFAULT NULL,
`browser_language` varchar(16) DEFAULT NULL,
`screen_resolution` varchar(16) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`total_sessions` int DEFAULT '0',
`last_event_id` int DEFAULT NULL,
`date` datetime NOT NULL,
`last_date` datetime DEFAULT NULL,
PRIMARY KEY (`visitor_id`),
UNIQUE KEY `visitor_id` (`visitor_uuid`),
KEY `website_id` (`website_id`),
CONSTRAINT `websites_visitors_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ROW_FORMAT=DYNAMIC ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `visitors_sessions` (
`session_id` int NOT NULL AUTO_INCREMENT,
`session_uuid` varchar(64) NOT NULL,
`visitor_id` int NOT NULL,
`website_id` int NOT NULL,
`total_events` int DEFAULT '1',
`date` datetime NOT NULL,
PRIMARY KEY (`session_id`),
UNIQUE KEY `session_uuid` (`session_uuid`),
KEY `website_id` (`website_id`),
KEY `visitor_id` (`visitor_id`),
KEY `session_id` (`session_id`),
KEY `date` (`date`),
CONSTRAINT `visitors_sessions_ibfk_1` FOREIGN KEY (`visitor_id`) REFERENCES `websites_visitors` (`visitor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `visitors_sessions_ibfk_2` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ROW_FORMAT=DYNAMIC ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `sessions_events` (
`event_id` int NOT NULL AUTO_INCREMENT,
`event_uuid` varchar(64) NOT NULL DEFAULT '',
`session_id` int NOT NULL,
`visitor_id` int NOT NULL,
`website_id` int NOT NULL,
`type` varchar(16) NOT NULL DEFAULT '',
`path` varchar(1024) NOT NULL DEFAULT '',
`title` varchar(512) DEFAULT NULL,
`referrer_host` varchar(256) DEFAULT NULL,
`referrer_path` varchar(1024) DEFAULT NULL,
`utm_source` varchar(128) DEFAULT NULL,
`utm_medium` varchar(128) DEFAULT NULL,
`utm_campaign` varchar(128) DEFAULT NULL,
`utm_term` varchar(128) DEFAULT NULL,
`utm_content` varchar(128) DEFAULT NULL,
`viewport_width` int DEFAULT NULL,
`viewport_height` int DEFAULT NULL,
`has_bounced` int DEFAULT NULL,
`date` datetime NOT NULL,
`expiration_date` date DEFAULT NULL,
PRIMARY KEY (`event_id`),
KEY `visitor_id` (`visitor_id`),
KEY `session_id` (`session_id`),
KEY `event_uuid` (`event_uuid`),
KEY `date` (`date`),
KEY `expiration_date` (`expiration_date`),
KEY `website_id` (`website_id`),
CONSTRAINT `sessions_events_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `sessions_events_ibfk_2` FOREIGN KEY (`visitor_id`) REFERENCES `websites_visitors` (`visitor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `sessions_events_ibfk_3` FOREIGN KEY (`session_id`) REFERENCES `visitors_sessions` (`session_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `events_children` (
`id` int NOT NULL AUTO_INCREMENT,
`event_id` int NOT NULL,
`session_id` int NOT NULL,
`visitor_id` int NOT NULL,
`snapshot_id` int DEFAULT NULL,
`website_id` int NOT NULL,
`type` varchar(16) NOT NULL DEFAULT '',
`data` longtext,
`count` int DEFAULT '1',
`date` datetime NOT NULL,
`expiration_date` date DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `visitor_id` (`visitor_id`),
KEY `website_id` (`website_id`),
KEY `session_id` (`session_id`),
KEY `event_uuid` (`event_id`),
KEY `snapshot_id` (`snapshot_id`),
KEY `expiration_date` (`expiration_date`),
CONSTRAINT `events_children_ibfk_1` FOREIGN KEY (`visitor_id`) REFERENCES `websites_visitors` (`visitor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `events_children_ibfk_2` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `events_children_ibfk_3` FOREIGN KEY (`session_id`) REFERENCES `visitors_sessions` (`session_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `sessions_replays` (
`replay_id` int unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`session_id` int NOT NULL,
`visitor_id` int NOT NULL,
`website_id` int NOT NULL,
`events` int DEFAULT NULL,
`is_offloaded` tinyint DEFAULT '0',
`datetime` datetime NOT NULL,
`last_datetime` datetime DEFAULT NULL,
`expiration_date` date DEFAULT NULL,
PRIMARY KEY (`replay_id`),
UNIQUE KEY `sessions_replays_pk` (`session_id`),
KEY `session_id` (`session_id`),
KEY `visitor_id` (`visitor_id`),
KEY `website_id` (`website_id`),
KEY `expiration_date` (`expiration_date`),
KEY `sessions_replays_users_user_id_fk` (`user_id`),
CONSTRAINT `sessions_replays_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `visitors_sessions` (`session_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `sessions_replays_ibfk_2` FOREIGN KEY (`visitor_id`) REFERENCES `websites_visitors` (`visitor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `sessions_replays_ibfk_3` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `sessions_replays_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `websites_heatmaps` (
`heatmap_id` int NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`website_id` int NOT NULL,
`snapshot_id_desktop` int DEFAULT NULL,
`snapshot_id_tablet` int DEFAULT NULL,
`snapshot_id_mobile` int DEFAULT NULL,
`name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`path` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`is_enabled` tinyint NOT NULL DEFAULT '1',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`heatmap_id`),
KEY `website_id` (`website_id`),
KEY `snapshot_id_desktop` (`snapshot_id_desktop`),
KEY `snapshot_id_tablet` (`snapshot_id_tablet`),
KEY `snapshot_id_mobile` (`snapshot_id_mobile`),
KEY `websites_heatmaps_users_user_id_fk` (`user_id`),
CONSTRAINT `websites_heatmaps_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `websites_heatmaps_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `heatmaps_snapshots` (
`snapshot_id` int NOT NULL AUTO_INCREMENT,
`heatmap_id` int NOT NULL,
`website_id` int NOT NULL,
`type` varchar(16) NOT NULL DEFAULT '',
`data` longblob NOT NULL,
`date` datetime NOT NULL,
PRIMARY KEY (`snapshot_id`),
KEY `heatmap_id` (`heatmap_id`),
KEY `website_id` (`website_id`),
KEY `type` (`type`),
CONSTRAINT `heatmaps_snapshots_ibfk_1` FOREIGN KEY (`heatmap_id`) REFERENCES `websites_heatmaps` (`heatmap_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `heatmaps_snapshots_ibfk_2` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

alter table events_children add constraint events_children_heatmaps_snapshots_snapshot_id_fk foreign key (snapshot_id) references heatmaps_snapshots (snapshot_id) on update cascade on delete set null;

-- SEPARATOR --

alter table websites_heatmaps add CONSTRAINT `websites_heatmaps_ibfk_2` FOREIGN KEY (`snapshot_id_desktop`) REFERENCES `heatmaps_snapshots` (`snapshot_id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- SEPARATOR --

alter table websites_heatmaps add  CONSTRAINT `websites_heatmaps_ibfk_3` FOREIGN KEY (`snapshot_id_tablet`) REFERENCES `heatmaps_snapshots` (`snapshot_id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- SEPARATOR --

alter table websites_heatmaps add  CONSTRAINT `websites_heatmaps_ibfk_4` FOREIGN KEY (`snapshot_id_mobile`) REFERENCES `heatmaps_snapshots` (`snapshot_id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- SEPARATOR --

CREATE TABLE `websites_goals` (
`goal_id` int NOT NULL AUTO_INCREMENT,
`website_id` int NOT NULL,
`key` varchar(16) NOT NULL DEFAULT '',
`type` varchar(16) NOT NULL DEFAULT '',
`path` varchar(256) DEFAULT NULL,
`name` varchar(64) DEFAULT NULL,
`date` datetime NOT NULL,
PRIMARY KEY (`goal_id`),
KEY `website_id` (`website_id`),
KEY `key` (`key`),
CONSTRAINT `websites_goals_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `goals_conversions` (
`conversion_id` int NOT NULL AUTO_INCREMENT,
`event_id` int DEFAULT NULL,
`session_id` int DEFAULT NULL,
`visitor_id` int DEFAULT NULL,
`goal_id` int NOT NULL,
`website_id` int NOT NULL,
`date` datetime NOT NULL,
PRIMARY KEY (`conversion_id`),
KEY `event_id` (`event_id`),
KEY `session_id` (`session_id`),
KEY `visitor_id` (`visitor_id`),
KEY `goal_id` (`goal_id`),
KEY `website_id` (`website_id`),
KEY `date` (`date`),
CONSTRAINT `goals_conversions_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `sessions_events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `goals_conversions_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `visitors_sessions` (`session_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `goals_conversions_ibfk_3` FOREIGN KEY (`visitor_id`) REFERENCES `websites_visitors` (`visitor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `goals_conversions_ibfk_4` FOREIGN KEY (`goal_id`) REFERENCES `websites_goals` (`goal_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `goals_conversions_ibfk_5` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `email_reports` (
`id` int NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`website_id` int NOT NULL,
`date` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `user_id` (`user_id`),
KEY `website_id` (`website_id`),
KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `broadcasts` (
`broadcast_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(64) DEFAULT NULL,
`subject` varchar(128) DEFAULT NULL,
`content` text,
`segment` varchar(64) DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`users_ids` longtext CHARACTER SET utf8mb4,
`sent_users_ids` longtext,
`sent_emails` int unsigned DEFAULT '0',
`total_emails` int unsigned DEFAULT '0',
`status` varchar(16) DEFAULT NULL,
`views` bigint unsigned DEFAULT '0',
`clicks` bigint unsigned DEFAULT '0',
`last_sent_email_datetime` datetime DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`broadcast_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `broadcasts_statistics` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`broadcast_id` bigint unsigned DEFAULT NULL,
`type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`target` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `broadcast_id` (`broadcast_id`),
KEY `broadcasts_statistics_user_id_broadcast_id_type_index` (`broadcast_id`,`user_id`,`type`),
CONSTRAINT `broadcasts_statistics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `broadcasts_statistics_ibfk_2` FOREIGN KEY (`broadcast_id`) REFERENCES `broadcasts` (`broadcast_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `internal_notifications` (
`internal_notification_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`for_who` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`from_who` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`icon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`url` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`is_read` tinyint unsigned DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`read_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`internal_notification_id`),
KEY `user_id` (`user_id`),
KEY `users_notifications_for_who_idx` (`for_who`) USING BTREE,
CONSTRAINT `internal_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `settings` (
`id` int NOT NULL AUTO_INCREMENT,
`key` varchar(64) NOT NULL DEFAULT '',
`value` longtext NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

SET @cron_key = MD5(RAND());

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`)
VALUES
('main', '{"title":"Your title","default_language":"english","default_theme_style":"light","default_timezone":"UTC","index_url":"","terms_and_conditions_url":"","privacy_policy_url":"","not_found_url":"","se_indexing":true,"ai_scraping_is_allowed":true,"display_index_plans":true,"display_index_testimonials":true,"display_index_faq":true,"default_results_per_page":100,"default_order_type":"DESC","auto_language_detection_is_enabled":true,"blog_is_enabled":false,"api_is_enabled":true,"theme_style_change_is_enabled":true,"logo_light":"","logo_dark":"","logo_email":"","opengraph":"","favicon":"","openai_api_key":"","openai_model":"gpt-3.5-turbo","force_https_is_enabled":false,"broadcasts_statistics_is_enabled":false,"breadcrumbs_is_enabled":true,"display_pagination_when_no_pages":true}'),
('languages', '{"english":{"status":"active"}}'),
('users', '{"login_rememberme_checkbox_is_checked": false,"email_confirmation":false,"welcome_email_is_enabled":false,"register_is_enabled":true,"register_only_social_logins":false,"register_display_newsletter_checkbox":false,"auto_delete_unconfirmed_users":30,"auto_delete_inactive_users":90,"user_deletion_reminder":0,"blacklisted_domains":"","blacklisted_countries":[],"login_lockout_is_enabled":true,"login_lockout_max_retries":3,"login_lockout_time":60,"lost_password_lockout_is_enabled":true,"lost_password_lockout_max_retries":3,"lost_password_lockout_time":60,"resend_activation_lockout_is_enabled":true,"resend_activation_lockout_max_retries":3,"resend_activation_lockout_time":60,"register_lockout_is_enabled":true,"register_lockout_max_registrations":3,"register_lockout_time":10}'),
('ads', '{"ad_blocker_detector_is_enabled":true,"ad_blocker_detector_lock_is_enabled":false,"ad_blocker_detector_delay":5,"header":"","footer":"","header_biolink":"","footer_biolink":"","header_splash":"","footer_splash":""}'),
('captcha', '{"type":"basic","recaptcha_public_key":"","recaptcha_private_key":"","login_is_enabled":0,"register_is_enabled":0,"lost_password_is_enabled":0,"resend_activation_is_enabled":0}'),
('cron', concat('{\"key\":\"', @cron_key, '\"}')),
('email_notifications', '{"emails":"","new_user":false,"delete_user":false,"new_payment":false,"new_domain":false,"new_affiliate_withdrawal":false,"contact":false}'),
('internal_notifications', '{"users_is_enabled":true,"admins_is_enabled":true,"new_user":true,"delete_user":true,"new_newsletter_subscriber":true,"new_payment":true,"new_affiliate_withdrawal":true}'),
('content', '{"blog_is_enabled":true,"blog_share_is_enabled":true,"blog_categories_widget_is_enabled":true,"blog_popular_widget_is_enabled":true,"blog_views_is_enabled":true,"pages_is_enabled":true,"pages_share_is_enabled":true,"pages_popular_widget_is_enabled":true,"pages_views_is_enabled":true}'),
('sso', '{"is_enabled":true,"display_menu_items":true,"websites":{}}'),
('facebook', '{"is_enabled":false,"app_id":"","app_secret":""}'),
('google', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('twitter', '{"is_enabled":false,"consumer_api_key":"","consumer_api_secret":""}'),
('discord', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('linkedin', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('microsoft', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('plan_custom', '{"plan_id":"custom","name":"Custom","description":"Contact us for enterprise pricing.","price":"Custom","custom_button_url":"mailto:sample@example.com","color":null,"status":2,"settings":{}}'),
('plan_free', '{"plan_id":"free","name":"Free","days":null,"status":0,"settings":{"no_ads":false,"email_reports_is_enabled":false,"teams_is_enabled":false,"websites_limit":1,"sessions_events_limit":15000,"events_children_limit":10000,"events_children_retention":90,"sessions_replays_limit":25,"sessions_replays_retention":30,"sessions_replays_time_limit":30,"websites_heatmaps_limit":1,"websites_goals_limit":10,"api_is_enabled":false,"affiliate_is_enabled":false}}'),
('payment', '{"is_enabled":false,"type":"both","default_payment_frequency":"monthly","currencies":{"USD":{"code":"USD","symbol":"$","default_payment_processor":"offline_payment"}},"default_currency":"USD","codes_is_enabled":true,"taxes_and_billing_is_enabled":true,"invoice_is_enabled":true,"user_plan_expiry_reminder":0,"user_plan_expiry_checker_is_enabled":0,"currency_exchange_api_key":""}'),
('paypal', '{\"is_enabled\":\"0\",\"mode\":\"sandbox\",\"client_id\":\"\",\"secret\":\"\"}'),
('stripe', '{\"is_enabled\":\"0\",\"publishable_key\":\"\",\"secret_key\":\"\",\"webhook_secret\":\"\"}'),
('offline_payment', '{\"is_enabled\":\"0\",\"instructions\":\"Your offline payment instructions go here..\"}'),
('coinbase', '{"is_enabled":false,"api_key":"","webhook_secret":"","currencies":["USD"]}'),
('payu', '{"is_enabled":false,"mode":"sandbox","merchant_pos_id":"","signature_key":"","oauth_client_id":"","oauth_client_secret":"","currencies":["USD"]}'),
('iyzico', '{"is_enabled":false,"mode":"live","api_key":"","secret_key":"","currencies":["USD"]}'),
('paystack', '{"is_enabled":false,"public_key":"","secret_key":"","currencies":["USD"]}'),
('razorpay', '{"is_enabled":false,"key_id":"","key_secret":"","webhook_secret":"","currencies":["USD"]}'),
('mollie', '{"is_enabled":false,"api_key":""}'),
('yookassa', '{"is_enabled":false,"shop_id":"","secret_key":""}'),
('crypto_com', '{"is_enabled":false,"publishable_key":"","secret_key":"","webhook_secret":""}'),
('paddle', '{"is_enabled":false,"mode":"sandbox","vendor_id":"","api_key":"","public_key":"","currencies":["USD"]}'),
('mercadopago', '{"is_enabled":false,"access_token":"","currencies":["USD"]}'),
('midtrans', '{"is_enabled":false,"server_key":"","mode":"sandbox","currencies":["USD"]}'),
('flutterwave', '{"is_enabled":false,"secret_key":"","currencies":["USD"]}'),
('smtp', '{"from_name":"","from":"","host":"","encryption":"tls","port":"","auth":false,"username":"","password":"","display_socials":false,"company_details":""}'),
('theme', '{"light_is_enabled": false, "dark_is_enabled": false}'),
('custom', '{"head_js":"","head_css":"","head_js_biolink":"","head_css_biolink":"","head_js_splash_page":"","head_css_splash_page":""}'),
('socials', '{"threads":"","youtube":"","facebook":"","x":"","instagram":"","tiktok":"","linkedin":"","whatsapp":"","email":""}'),
('announcements', '{"guests_id":"16e2fdd0e771da32ec9e557c491fe17d","guests_content":"","guests_text_color":"#ffffff","guests_background_color":"#000000","users_id":"16e2fdd0e771da32ec9e557c491fe17d","users_content":"","users_text_color":"#dbebff","users_background_color":"#000000"}'),
('business', '{\"invoice_is_enabled\":\"0\",\"name\":\"\",\"address\":\"\",\"city\":\"\",\"county\":\"\",\"zip\":\"\",\"country\":\"\",\"email\":\"\",\"phone\":\"\",\"tax_type\":\"\",\"tax_id\":\"\",\"custom_key_one\":\"\",\"custom_value_one\":\"\",\"custom_key_two\":\"\",\"custom_value_two\":\"\"}'),
('webhooks', '{"user_new":"","user_delete":"","payment_new":"","code_redeemed":"","contact":"","cron_start":"","cron_end":"","domain_new":"","domain_update":""}'),
('analytics', '{\"email_reports_is_enabled\":0,\"sessions_replays_is_enabled\":\"1\",\"sessions_replays_minimum_duration\":\"1\",\"websites_heatmaps_is_enabled\":\"1\",\"pixel_cache\": 300,\"pixel_exposed_identifier\":\"analytics\"}'),
('cookie_consent', '{"is_enabled":false,"logging_is_enabled":false,"necessary_is_enabled":true,"analytics_is_enabled":true,"targeting_is_enabled":true,"layout":"bar","position_y":"middle","position_x":"center"}'),
('license', '{\"license\":\"xxxxxxxx\",\"type\":\"extended\"}'),
('support', '{}'),
('product_info', '{\"version\":\"33.0.0\", \"code\":\"3300\"}');
-- SEPARATOR --CREATE TABLE `payments` (
`id` int unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`plan_id` int DEFAULT NULL,
`processor` varchar(16) DEFAULT NULL,
`type` varchar(16) DEFAULT NULL,
`frequency` varchar(16) DEFAULT NULL,
`payment_id` varchar(128) DEFAULT NULL,
`email` varchar(256) DEFAULT NULL,
`name` varchar(256) DEFAULT NULL,
`plan` text,
`billing` text,
`business` text,
`taxes_ids` text,
`base_amount` float DEFAULT NULL,
`total_amount` float DEFAULT NULL,
`total_amount_default_currency` float DEFAULT null,
`code` varchar(32) DEFAULT NULL,
`discount_amount` float DEFAULT NULL,
`currency` varchar(4) DEFAULT NULL,
`payment_proof` varchar(40) DEFAULT NULL,
`status` tinyint(4) DEFAULT '1',
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `payments_user_id` (`user_id`),
KEY `plan_id` (`plan_id`),
CONSTRAINT `payments_plans_plan_id_fk` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`plan_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `payments_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE IF NOT EXISTS `codes` (
`code_id` int NOT NULL AUTO_INCREMENT,
`name` varchar(64) DEFAULT NULL,
`type` varchar(16) DEFAULT NULL,
`days` int unsigned DEFAULT NULL,
`code` varchar(32) NOT NULL DEFAULT '',
`discount` int unsigned NOT NULL,
`quantity` int unsigned NOT NULL DEFAULT '1',
`redeemed` int unsigned NOT NULL DEFAULT '0',
`plans_ids` text,
`datetime` datetime NOT NULL,
PRIMARY KEY (`code_id`),
KEY `type` (`type`),
KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE IF NOT EXISTS `redeemed_codes` (
`id` int NOT NULL AUTO_INCREMENT,
`code_id` int NOT NULL,
`user_id` int NOT NULL,
`type` varchar(16) DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `code_id` (`code_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `redeemed_codes_ibfk_1` FOREIGN KEY (`code_id`) REFERENCES `codes` (`code_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `redeemed_codes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `taxes` (
`tax_id` int unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(64) DEFAULT NULL,
`description` varchar(256) DEFAULT NULL,
`value` float DEFAULT NULL,
`value_type` enum('percentage','fixed') DEFAULT NULL,
`type` enum('inclusive','exclusive') DEFAULT NULL,
`billing_type` enum('personal','business','both') DEFAULT NULL,
`countries` text,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;