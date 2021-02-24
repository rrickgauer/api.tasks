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