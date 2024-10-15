-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-10-15 09:37:40
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `cookbook_db`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `ingredient_name` varchar(200) NOT NULL,
  `quantity` varchar(200) NOT NULL,
  `unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `ingredients`
--

INSERT INTO `ingredients` (`id`, `recipe_id`, `ingredient_name`, `quantity`, `unit`) VALUES
(132, 77, 'あ', 'あ', 'ああ'),
(133, 78, 'ああ', 'ああ', 'あ'),
(134, 79, 'あああ', 'ああ', '');

-- --------------------------------------------------------

--
-- テーブルの構造 `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `recipe_title` varchar(200) NOT NULL,
  `recipe_time` float NOT NULL,
  `recipe_difficulty` varchar(10) NOT NULL,
  `recipe_ServingSize` varchar(50) NOT NULL,
  `recipe_picture` varchar(200) NOT NULL,
  `recipe_introduction` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `recipes`
--

INSERT INTO `recipes` (`id`, `username`, `recipe_title`, `recipe_time`, `recipe_difficulty`, `recipe_ServingSize`, `recipe_picture`, `recipe_introduction`, `created_at`, `updated_at`) VALUES
(77, 'aaa', 'チャーハン', 30, '簡単', '2人分', 'recipe_images/tya-hann.jpg', 'チャーハンの作り方', '2024-10-08 01:22:06', '2024-10-01 03:01:11'),
(78, 'aaa', 'a', 3, '簡単', '2人分', 'recipe_images/katudon.jpg', 'aa', '2024-10-08 06:37:02', '2024-10-08 06:37:02'),
(79, 'aaa', 'ラーメン', 20, '簡単', '4人分', '', 'ああ', '2024-10-08 06:37:33', '2024-10-08 06:37:33');

-- --------------------------------------------------------

--
-- テーブルの構造 `recipe_procedure`
--

CREATE TABLE `recipe_procedure` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `step_numbers` int(11) NOT NULL,
  `recipe_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `recipe_procedure`
--

INSERT INTO `recipe_procedure` (`id`, `recipe_id`, `step_numbers`, `recipe_description`) VALUES
(76, 77, 1, 'ああああ'),
(77, 77, 2, 'あああ'),
(78, 77, 3, 'あああ'),
(79, 77, 4, 'ああ'),
(80, 78, 1, '');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(19, 'aaa', 'aaa@yse-c.net', '$2y$10$OGF2A7RrbrTlKsHylwiuouAa/N51CNSihRdOoZOLVVy7GJGsp8rfe', '2024-08-04 18:00:34', '2024-08-04 18:00:34'),
(22, 'aaaa', 'aaaa@gmail.com', '$2y$10$h45ENFBmSRfK3fjXZmY6QeMkE/FkS8dhbschdIlHgVNAEmoRup4.i', '2024-09-24 04:42:29', '2024-09-24 04:42:29');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `recipe_title` (`recipe_title`);

--
-- テーブルのインデックス `recipe_procedure`
--
ALTER TABLE `recipe_procedure`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_email_unique` (`email`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- テーブルの AUTO_INCREMENT `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- テーブルの AUTO_INCREMENT `recipe_procedure`
--
ALTER TABLE `recipe_procedure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
