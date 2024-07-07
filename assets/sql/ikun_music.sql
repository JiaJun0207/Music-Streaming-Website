-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2024 at 09:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ikun_music`
--

-- --------------------------------------------------------

--
-- Table structure for table `artist`
--

CREATE TABLE `artist` (
  `artist_id` int(11) NOT NULL,
  `artist_name` varchar(255) NOT NULL,
  `artist_email` varchar(255) NOT NULL,
  `artist_youtube` varchar(255) DEFAULT NULL,
  `artist_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `artist`
--

INSERT INTO `artist` (`artist_id`, `artist_name`, `artist_email`, `artist_youtube`, `artist_photo`) VALUES
(1, 'NewJeans ', 'newjeans@gmail.com', 'https://www.youtube.com/@NewJeans_official', 'uploads/artist/banner-newjeanssupershy-680x1020.jpg'),
(6, 'Yoasobi', 'yoasobi@gmail.com', 'https://www.youtube.com/@Ayase_YOASOBI', 'uploads/artist/YOASOBI.png'),
(8, 'HOYO-MiX', 'hoyo-mix@gmail.com', 'https://www.youtube.com/@HOYOMiX', 'uploads/artist/HoYo-MiX.png'),
(10, '周杰伦', 'jaychou@gmail.com', 'https://www.youtube.com/channel/UC8CU5nVhCQIdAGrFFp4loOQ', 'uploads/artist/周杰伦.png'),
(11, 'KOTOKO', 'kotoko@gmail.com', 'https://www.youtube.com/@KOTOKO_Official_Channel', 'uploads/artist/senrenbanka.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `song_id`, `user_id`, `comment_text`, `created_at`) VALUES
(14, 11, 1, 'hello', '2024-07-04 18:31:21'),
(25, 11, 31, 'hi jia jun im cai xu kun', '2024-07-05 13:58:16'),
(57, 14, 1, 'hello jiajun', '2024-07-07 03:12:09'),
(58, 12, 1, 'hi', '2024-07-07 15:24:54');

-- --------------------------------------------------------

--
-- Table structure for table `liked_songs`
--

CREATE TABLE `liked_songs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `liked_songs`
--

INSERT INTO `liked_songs` (`id`, `user_id`, `song_id`, `created_at`) VALUES
(22, 31, 12, '2024-07-05 13:58:51'),
(23, 31, 11, '2024-07-05 14:17:02'),
(30, 1, 12, '2024-07-06 15:34:14'),
(44, 1, 14, '2024-07-07 03:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `playlist_id` int(11) NOT NULL,
  `playlist_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `playlist_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `playlist`
--

INSERT INTO `playlist` (`playlist_id`, `playlist_name`, `created_at`, `playlist_image`) VALUES
(5, 'kpop', '2024-07-04 17:06:02', 'uploads/playlist_images/allkpop_1688755874_untitled-1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `playlists`
--

CREATE TABLE `playlists` (
  `playlist_id` int(11) NOT NULL,
  `playlist_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `playlist_image` varchar(255) DEFAULT NULL,
  `song_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `playlists`
--

INSERT INTO `playlists` (`playlist_id`, `playlist_name`, `created_at`, `user_id`, `playlist_image`, `song_id`) VALUES
(21, 'Liked Songs', '2024-07-03 10:31:57', 29, 'uploads/playlist_images/love-song.png', NULL),
(24, 'Liked Songs', '2024-07-05 10:16:49', 1, 'uploads/playlist_images/love-song.png', NULL),
(25, 'Liked Songs', '2024-07-05 13:59:05', 31, 'uploads/playlist_images/love-song.png', NULL),
(26, 'Liked Songs', '2024-07-07 03:09:27', 33, 'uploads/playlist_images/love-song.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `playlist_songs`
--

CREATE TABLE `playlist_songs` (
  `id` int(11) NOT NULL,
  `playlist_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `playlist_songs`
--

INSERT INTO `playlist_songs` (`id`, `playlist_id`, `song_id`) VALUES
(9, 5, 11);

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `id` int(11) NOT NULL,
  `song_title` varchar(255) NOT NULL,
  `artist_id` int(11) NOT NULL,
  `language` varchar(255) DEFAULT NULL,
  `categories` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `mp3_upload` varchar(255) DEFAULT NULL,
  `profile_picture_upload` varchar(255) DEFAULT NULL,
  `background_picture_upload` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`id`, `song_title`, `artist_id`, `language`, `categories`, `release_date`, `mp3_upload`, `profile_picture_upload`, `background_picture_upload`) VALUES
(11, 'OMG', 1, 'korean', 'POP', '2024-07-05', 'uploads/mp3/OMG.mp3', 'uploads/profile/NewJeans_OMG_cover.jpg', 'uploads/background/NewJeans_OMG_cover.jpg'),
(12, 'アイドル', 6, 'japanese', 'POP', '2024-07-05', 'uploads/mp3/アイドル.mp3', 'uploads/profile/Yoasobi-Idol.png', 'uploads/background/Yoasobi-Idol.png'),
(14, 'Sway to My Beat in Cosmos', 8, 'english', 'POP', '2024-07-06', 'uploads/mp3/在银河中孤独摇摆-知更鸟&HOYO-MiX&Chevy.mp3', 'uploads/profile/Sway to My Beat in Cosmos.png', 'uploads/background/Sway to My Beat in Cosmos.png'),
(16, '搁浅', 10, 'chinese', 'POP', '2024-07-08', 'uploads/mp3/搁浅.mp3', 'uploads/profile/搁浅专辑封面.png', 'uploads/background/搁浅专辑封面.png'),
(19, '恋ひ恋う縁', 11, 'japanese', 'Japan', '2024-07-08', 'uploads/mp3/恋ひ恋う縁.mp3', 'uploads/profile/千恋＊万花.jpg', 'uploads/background/千恋＊万花.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password_hash`, `phone`, `profile_image`, `reset_token`, `token_expiry`) VALUES
(1, 'JiaJunChan', 'chanjiajun321@gmail.com', '$2y$10$o3FvYrALFTFNZOHYV25t7.fDfwtbp7.uYs/shFSJO3O0EuGtg2ZAq', '0149316628', 'uploads/profile/6686e1d5be2b0.png', NULL, NULL),
(27, '111', 'sandderrare1985@hotmail.com', '$2y$10$I8HY6ffEAmHysmGS2K2ga.M6TFfjs2guCfq3zQFsa4E//7ohaSsLS', '12345678', 'uploads/profile/banner-newjeanssupershy-680x1020.jpg', NULL, NULL),
(29, '1', 'chongjiensheng@gmail.com', '$2y$10$7QO0L.Affl6Lkx4X8bZfJ.H.9bj3WFmBNz6vi.Of.Oq97UaOSUM8W', '12345678', 'uploads/profile/allkpop_1688755874_untitled-1.jpg', NULL, NULL),
(31, 'Juin Wei', 'seowjuinwei@gmail.com', '$2y$10$LhNaVBU.u7h6MXrPRKfVyupT2maKhrsFjb1io.FlcKZmoZ0ZvH8G.', '', 'uploads/profile/6687fbdd23d5b.jpg', NULL, NULL),
(33, 'Chan Jia Jun', 'jiajunchanpsn0207@gmail.com', '$2y$10$Bug6Bw4CozM4gK0xJL9Sre5pfN/9fSnU4shfT.eDALBBGhhavJyU2', '', 'uploads/profile/668a0707c2d5b.jpg', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artist`
--
ALTER TABLE `artist`
  ADD PRIMARY KEY (`artist_id`),
  ADD UNIQUE KEY `artist_name_UNIQUE` (`artist_name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_song_id` (`song_id`) USING BTREE;

--
-- Indexes for table `liked_songs`
--
ALTER TABLE `liked_songs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `song_id` (`song_id`);

--
-- Indexes for table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`playlist_id`);

--
-- Indexes for table `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`playlist_id`),
  ADD KEY `fk_user_playlist` (`user_id`),
  ADD KEY `fk_song_id` (`song_id`);

--
-- Indexes for table `playlist_songs`
--
ALTER TABLE `playlist_songs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `playlist_id` (`playlist_id`),
  ADD KEY `song_id` (`song_id`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artist_id` (`artist_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artist`
--
ALTER TABLE `artist`
  MODIFY `artist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `liked_songs`
--
ALTER TABLE `liked_songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `playlist`
--
ALTER TABLE `playlist`
  MODIFY `playlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `playlists`
--
ALTER TABLE `playlists`
  MODIFY `playlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `playlist_songs`
--
ALTER TABLE `playlist_songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`song_id`) REFERENCES `songs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `liked_songs`
--
ALTER TABLE `liked_songs`
  ADD CONSTRAINT `liked_songs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `liked_songs_ibfk_2` FOREIGN KEY (`song_id`) REFERENCES `songs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `playlists`
--
ALTER TABLE `playlists`
  ADD CONSTRAINT `fk_song_id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_playlist` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `playlist_songs`
--
ALTER TABLE `playlist_songs`
  ADD CONSTRAINT `playlist_songs_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `playlist` (`playlist_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `playlist_songs_ibfk_2` FOREIGN KEY (`song_id`) REFERENCES `songs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `songs`
--
ALTER TABLE `songs`
  ADD CONSTRAINT `songs_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artist` (`artist_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
