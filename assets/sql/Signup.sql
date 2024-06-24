CREATE DATABASE ikun_music;
USE ikun_music;

CREATE TABLE user (
    user_id INT  AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    PRIMARY KEY (user_id)
);