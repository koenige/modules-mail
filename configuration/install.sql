/**
 * mail module
 * SQL for installation of mail module
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/mail
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2023 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


-- mails --
CREATE TABLE `mails` (
  `mail_id` int unsigned NOT NULL AUTO_INCREMENT,
  `mail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_date` datetime NOT NULL,
  `mail_status_category_id` int unsigned NOT NULL,
  `last_update` timestamp NOT NULL,
  PRIMARY KEY (`mail_id`),
  KEY `mail_status_category_id` (`mail_status_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'categories', 'category_id', (SELECT DATABASE()), 'mails', 'mail_id', 'mail_status_category_id', 'no-delete');

INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('Mail Status', NULL, NULL, 'mail-status', '&alias=mail-status', NULL, NOW());
INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('Draft', NULL, (SELECT category_id FROM categories c WHERE path = 'mail-status'), 'mail-status/draft', '&alias=mail-status/draft', 1, NOW());
INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('Sent', NULL, (SELECT category_id FROM categories c WHERE path = 'mail-status'), 'mail-status/sent', '&alias=mail-status/sent', 2, NOW());
INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('Template', NULL, (SELECT category_id FROM categories c WHERE path = 'mail-status'), 'mail-status/template', '&alias=mail-status/template', 3, NOW());


-- mails_contacts --
CREATE TABLE `mails_contacts` (
  `mail_contact_id` int unsigned NOT NULL AUTO_INCREMENT,
  `mail_id` int unsigned NOT NULL,
  `contact_id` int unsigned NOT NULL,
  `header_field_category_id` int unsigned NOT NULL,
  `last_update` timestamp NOT NULL,
  PRIMARY KEY (`mail_contact_id`),
  UNIQUE KEY `mail_id_contact_id_header_field_category_id` (`mail_id`,`contact_id`,`header_field_category_id`),
  KEY `contact_id` (`contact_id`),
  KEY `header_field_category_id` (`header_field_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'mails', 'mail_id', (SELECT DATABASE()), 'mails_contacts', 'mail_contact_id', 'mail_id', 'delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'contacts', 'contact_id', (SELECT DATABASE()), 'mails_contacts', 'mail_contact_id', 'contact_id', 'no-delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'categories', 'category_id', (SELECT DATABASE()), 'mails_contacts', 'mail_contact_id', 'header_field_category_id', 'no-delete');


-- mails_headers --
CREATE TABLE `mails_headers` (
  `mail_header_id` int unsigned NOT NULL AUTO_INCREMENT,
  `mail_id` int unsigned NOT NULL,
  `header_field_category_id` int unsigned NOT NULL,
  `header_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`mail_header_id`),
  UNIQUE KEY `mail_id_header_field_category_id` (`mail_id`,`header_field_category_id`),
  KEY `header_field_category_id` (`header_field_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'mails', 'mail_id', (SELECT DATABASE()), 'mails_headers', 'mail_header_id', 'mail_id', 'delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'categories', 'category_id', (SELECT DATABASE()), 'mails_headers', 'mail_header_id', 'header_field_category_id', 'no-delete');

INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('Mail Headers', NULL, NULL, 'mail-headers', '&alias=mail-headers', NULL, NOW());
INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('Subject', NULL, (SELECT category_id FROM categories c WHERE path = 'mail-headers'), 'mail-headers/subject', '&alias=mail-headers/subject', 1, NOW());
INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('Date', NULL, (SELECT category_id FROM categories c WHERE path = 'mail-headers'), 'mail-headers/date', '&alias=mail-headers/date&type=date', 2, NOW());
INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('To', NULL, (SELECT category_id FROM categories c WHERE path = 'mail-headers'), 'mail-headers/to', '&alias=mail-headers/to&type=mailto', 3, NOW());
INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('From', NULL, (SELECT category_id FROM categories c WHERE path = 'mail-headers'), 'mail-headers/from', '&alias=mail-headers/from&type=mailto', 4, NOW());
INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('Reply-To', NULL, (SELECT category_id FROM categories c WHERE path = 'mail-headers'), 'mail-headers/reply-to', '&alias=mail-headers/reply-to&type=mailto', 5, NOW());
INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('Cc', NULL, (SELECT category_id FROM categories c WHERE path = 'mail-headers'), 'mail-headers/cc', '&alias=mail-headers/cc&type=mailto', 6, NOW());
INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('Bcc', NULL, (SELECT category_id FROM categories c WHERE path = 'mail-headers'), 'mail-headers/bcc', '&alias=mail-headers/bcc&type=mailto', 7, NOW());


-- mails_media --
CREATE TABLE `mails_media` (
  `mail_medium_id` int unsigned NOT NULL AUTO_INCREMENT,
  `mail_id` int unsigned NOT NULL,
  `medium_id` int unsigned NOT NULL,
  `sequence` tinyint unsigned NOT NULL,
  `last_update` timestamp NOT NULL,
  PRIMARY KEY (`mail_medium_id`),
  UNIQUE KEY `mail_id_medium_id` (`mail_id`,`medium_id`),
  KEY `medium_id` (`medium_id`),
  KEY `sequence` (`sequence`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'mails', 'mail_id', (SELECT DATABASE()), 'mails_media', 'mail_medium_id', 'mail_id', 'delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'media', 'medium_id', (SELECT DATABASE()), 'mails_media', 'mail_medium_id', 'medium_id', 'no-delete');
