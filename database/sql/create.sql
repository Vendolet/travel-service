USE travel_service;

CREATE TABLE IF NOT EXISTS `traveler` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name_traveler` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(11) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `city` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name_city` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `place` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name_place` VARCHAR(255) NOT NULL,
    `rank` TINYINT NOT NULL DEFAULT 0,
    `distance` FLOAT(4,2) NOT NULL,
    `city_id` INT NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city_id`) REFERENCES `city` (`id`)
     ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `score` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `score` TINYINT NOT NULL,
    `traveler_id` INT,
    `place_id` INT NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`traveler_id`) REFERENCES `traveler` (`id`)
     ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (`place_id`) REFERENCES `place` (`id`)
     ON DELETE CASCADE ON UPDATE CASCADE
);
