CREATE DATABASE music_database;
USE music_database;

CREATE TABLE songs (
    song_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    artist VARCHAR(255) NOT NULL,
    album VARCHAR(255) NOT NULL,
    cover_image VARCHAR(255) NOT NULL,
    audio_url VARCHAR(255) NOT NULL,
    description TEXT
);
