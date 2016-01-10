/*
    schema.mysql.sql

    Copyright Stefan Fisk 2012.
*/

CREATE TABLE `feed` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `image` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `type` TINYINT NOT NULL,
    `width` SMALLINT UNSIGNED  NOT NULL,
    `height` SMALLINT UNSIGNED NOT NULL,
    `size` INT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `user` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,

    `first_name` varchar(128) NOT NULL,
    `last_name` varchar(128) NOT NULL,

    `email_address` varchar(128) UNIQUE,
    `password_hash` varchar(128),

    `password_reset_key` VARCHAR(128),

    `facebook_profile_id` INTEGER UNIQUE,

    CONSTRAINT credentials CHECK (`email_address` IS NOT NULL XOR `facebook_profile_id` IS NOT NULL),

    `registration_time` DATETIME NOT NULL,

    `status` TINYINT NOT NULL,

    `is_admin` BOOL NOT NULL DEFAULT FALSE,

    `language` varchar(16),

    `description` TEXT,
    `image_id` INTEGER,
    FOREIGN KEY (image_id) REFERENCES image (id)
        ON DELETE CASCADE
        ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `feed_follower` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,

    `user_id` INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `feed_id` INTEGER NOT NULL,
    FOREIGN KEY (feed_id) REFERENCES feed (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT user_id_feed_id UNIQUE (user_id, feed_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `message` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,

    `creation_time` DATETIME NOT NULL,

    `sender_id` INTEGER NOT NULL,
    FOREIGN KEY (sender_id) REFERENCES user (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `recipient_id` INTEGER NOT NULL,
    FOREIGN KEY (recipient_id) REFERENCES user (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `text` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `post` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,

    `type` VARCHAR(8) NOT NULL,

    `creation_time` DATETIME NOT NULL,

    `user_id` INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `feed_id` INTEGER NOT NULL,
    FOREIGN KEY (feed_id) REFERENCES feed (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `status` varchar(128),

-- Text Posts
    `text` TEXT,

    `urgent` BOOL,

-- Image Posts
    `image_id` INTEGER,
    FOREIGN KEY (image_id) REFERENCES image (id)
        ON DELETE CASCADE
        ON UPDATE SET NULL,

-- Resource Posts
    `resource_type` VARCHAR(16)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `comment` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,

    `creation_time` DATETIME NOT NULL,

    `post_id` INTEGER NOT NULL,
    FOREIGN KEY (post_id) REFERENCES post (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `user_id` INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `text` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `region` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,

    `southwest_longitude` DOUBLE NOT NULL,
    `southwest_latitude` DOUBLE NOT NULL,
    `northeast_longitude` DOUBLE NOT NULL,
    `northeast_latitude` DOUBLE NOT NULL,

    `time_zone` VARCHAR(128) NOT NULL,

    `name` varchar(128) NOT NULL,
    `slug` varchar(128) NOT NULL UNIQUE,
    `description` TEXT,

    `image_id` INTEGER,
    FOREIGN KEY (image_id) REFERENCES image (id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    `feed_id` INTEGER NOT NULL,
    FOREIGN KEY (feed_id) REFERENCES feed (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `footer` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `location` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,

    `creation_time` DATETIME NOT NULL,
    `created_by_user_id` INTEGER,
    FOREIGN KEY (created_by_user_id) REFERENCES user (id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    `region_id` INTEGER NOT NULL,
    FOREIGN KEY (region_id) REFERENCES region (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `longitude` DOUBLE NOT NULL,
    `latitude` DOUBLE NOT NULL,

    `name` varchar(128) NOT NULL,
    `slug` varchar(128) NOT NULL UNIQUE,
    `description` TEXT,

    `image_id` INTEGER,
    FOREIGN KEY (image_id) REFERENCES image (id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    `feed_id` INTEGER NOT NULL,
    FOREIGN KEY (feed_id) REFERENCES feed (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `project` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,

    `creation_time` DATETIME NOT NULL,
    `created_by_user_id` INTEGER,
    FOREIGN KEY (created_by_user_id) REFERENCES user (id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    `status` varchar(128) NOT NULL,

    `location_id` INTEGER NOT NULL,
    FOREIGN KEY (location_id) REFERENCES location (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `name` varchar(128) NOT NULL,
    `slug` varchar(128) NOT NULL UNIQUE,
    `description` TEXT NOT NULL,

    `image_id` INTEGER,
    FOREIGN KEY (image_id) REFERENCES image (id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    `feed_id` INTEGER NOT NULL,
    FOREIGN KEY (feed_id) REFERENCES feed (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `project_champ` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,

    `user_id` INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `project_id` INTEGER NOT NULL,
    FOREIGN KEY (project_id) REFERENCES project (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT user_id_project_id UNIQUE (user_id, project_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `record_operation_log` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,

    `time` DATETIME NOT NULL,

    `user_id` INTEGER,
    FOREIGN KEY (user_id) REFERENCES user (id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    `action` varchar(32) NOT NULL,
    `model` varchar(32) NOT NULL,
    `model_id` INTEGER NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `notification` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,

    `user_id` INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `record_operation_log_id` INTEGER NOT NULL,
    FOREIGN KEY (record_operation_log_id) REFERENCES record_operation_log (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    `viewed` BOOL NOT NULL DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

INSERT INTO `feed` VALUES(1);

INSERT INTO `region` VALUES(1, 12.788086, 55.852577, 12.87529, 55.90496, 'Landskrona', 'landskrona', '', NULL, 1);

INSERT INTO `feed` VALUES(2);

INSERT INTO `location` VALUES(1, UTC_TIMESTAMP(), NULL, 1, 12.8, 55.87, 'Testplats', 'testplats', 'Ett test.', NULL, 2);
