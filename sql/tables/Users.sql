CREATE TABLE `Users` (
    `id` char(36) COLLATE utf8_unicode_ci NOT NULL,
    `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
    `password` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
    `created_on` datetime NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `id` (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
