CREATE DATABASE ikun_music;
USE ikun_music;

DROP TABLE IF EXISTS Comments;
DROP TABLE IF EXISTS Songs;

CREATE TABLE users (
    user_id INT  AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);
------------------------------------
CREATE TABLE Songs (
    id INT(11) NOT NULL AUTO_INCREMENT,
    song_title VARCHAR(255) NOT NULL,
    artist VARCHAR(255) NOT NULL,
    language VARCHAR(255),
    categories VARCHAR(255),
    release_date DATE,
    mp3_upload VARCHAR(255),
    profile_picture_upload VARCHAR(255),
    background_picture_upload VARCHAR(255),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-------------------------------------
CREATE TABLE Comments (
    id INT(11) NOT NULL AUTO_INCREMENT,
    song_id INT(11) NOT NULL,
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_song_id (song_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
---------------------------------------
ALTER TABLE Comments
ADD CONSTRAINT fk_song_id
FOREIGN KEY (song_id) REFERENCES Songs(id) ON DELETE CASCADE ON UPDATE CASCADE;
