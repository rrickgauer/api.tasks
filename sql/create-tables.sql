CREATE TABLE `Event_Cancelations` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `event_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `Event_Cancelations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `Events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `Event_Completions` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `event_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `Event_Completions_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `Events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `Event_Notes` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `event_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `Event_Notes_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `Events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `Event_Recurrences` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `event_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `week` int(11) DEFAULT NULL,
  `day` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `Event_Recurrences_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `Events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `Events` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `phone_number` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location_address_1` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location_address_2` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location_city` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location_state` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location_zip` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `starts_on` date DEFAULT NULL,
  `ends_on` date DEFAULT NULL,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `frequency` enum('ONCE','DAILY','WEEKLY','MONTHLY','YEARLY') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ONCE',
  `seperation` int(10) unsigned DEFAULT '1',
  `count` int(10) unsigned DEFAULT NULL,
  `until` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `Events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `Users` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
