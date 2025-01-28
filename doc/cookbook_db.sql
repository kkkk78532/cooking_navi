-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2025-01-28 03:50:18
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
(73, 53, '鶏もも', '1', '枚'),
(74, 53, '油', '適量', ''),
(75, 53, '片栗粉', '適量', ''),
(82, 57, 'ひき肉', '100', 'g'),
(83, 58, 'ウィンナー', '3', '本'),
(84, 58, '冷凍ご飯', '100', 'g'),
(85, 58, '卵', '1', '個'),
(90, 63, 'ラ王', '1', '袋'),
(117, 67, '木綿豆腐', '1丁', ''),
(118, 67, '豚ひき肉', '100g', ''),
(119, 67, '長ネギ', '1/2本', ''),
(120, 67, 'しょうゆ', '大さじ2', ''),
(121, 67, '豆板醤', '大さじ1', ''),
(122, 67, '甜麺醤', '大さじ1', ''),
(123, 67, '砂糖', '小さじ1', ''),
(124, 67, '酒', '大さじ1', ''),
(125, 67, '片栗粉', '大さじ1', ''),
(126, 67, '水', '100ml', ''),
(127, 67, 'ごま油', '小さじ1', ''),
(128, 67, '花椒', '少々', ''),
(129, 67, 'ラー油', 'お好みで', ''),
(137, 68, 'バゲット', '2切れ', ''),
(138, 68, '卵', '2個', ''),
(139, 68, '牛乳', '100ml', ''),
(140, 68, '砂糖', '大さじ1', ''),
(141, 68, 'バニラエッセンス', '少々', ''),
(142, 68, 'バター', '適量', ''),
(143, 68, 'メープルシロップ', '適量', ''),
(270, 81, '鶏むね肉', '100g', ''),
(271, 81, '玉ねぎ', '1/2個', ''),
(272, 81, '米', '1/2カップ', ''),
(273, 81, 'だし汁', '100ml', ''),
(274, 81, '醤油', '小さじ1', ''),
(275, 81, 'みりん', '小さじ1/2', ''),
(276, 81, '卵', '2個', ''),
(277, 81, 'バター', '10g', ''),
(278, 81, '青ネギ', '適量', '');

-- --------------------------------------------------------

--
-- テーブルの構造 `meal_plans`
--

CREATE TABLE `meal_plans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `meal_type` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `meal_plans`
--

INSERT INTO `meal_plans` (`id`, `user_id`, `recipe_id`, `date`, `meal_type`, `created_at`, `updated_at`) VALUES
(1, 0, 64, '2025-01-02', 'afternoon', '2025-01-20 14:59:34', '2025-01-20 14:59:34'),
(3, 0, 68, '2025-01-21', 'afternoon', '2025-01-20 16:17:27', '2025-01-20 16:17:27'),
(5, 0, 74, '2025-01-21', 'evening', '2025-01-20 16:17:50', '2025-01-20 16:17:50'),
(6, 0, 65, '2025-01-21', 'morning', '2025-01-20 16:17:54', '2025-01-20 16:17:54'),
(7, 0, 67, '2025-01-16', 'evening', '2025-01-21 11:08:38', '2025-01-21 11:08:38'),
(8, 0, 77, '2025-01-24', 'afternoon', '2025-01-21 11:08:52', '2025-01-21 11:08:52'),
(9, 0, 67, '2025-02-07', 'morning', '2025-01-21 11:16:24', '2025-01-21 11:16:24'),
(10, 0, 69, '2025-02-08', 'evening', '2025-01-21 11:16:30', '2025-01-21 11:16:30'),
(11, 0, 67, '2025-01-01', 'morning', '2025-01-21 11:57:38', '2025-01-21 11:57:38'),
(12, 0, 68, '2025-01-01', 'afternoon', '2025-01-21 11:57:50', '2025-01-21 11:57:50'),
(13, 0, 77, '2025-01-01', 'evening', '2025-01-21 11:57:58', '2025-01-21 11:57:58'),
(14, 0, 67, '2025-01-03', 'morning', '2025-01-23 11:17:53', '2025-01-23 11:17:53');

-- --------------------------------------------------------

--
-- テーブルの構造 `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
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

INSERT INTO `recipes` (`id`, `user_id`, `recipe_title`, `recipe_time`, `recipe_difficulty`, `recipe_ServingSize`, `recipe_picture`, `recipe_introduction`, `created_at`, `updated_at`) VALUES
(67, '19', '麻婆豆腐', 30, '中級', '2人前', 'recipe_images/ma-bo-.jpg', '本格的な味わいの麻婆豆腐。花椒の痺れる辛さと旨みが絶妙です。', '2025-01-20 06:27:56', '2025-01-20 06:26:09'),
(68, '19', '簡単！2人分フレンチトースト', 20, '簡単', '2人前', 'recipe_images/hurenntito-suto.jpg', 'バゲットを使った、簡単で美味しいフレンチトースト。週末のブランチにぴったりです。', '2025-01-20 06:30:27', '2025-01-20 06:29:22'),
(69, '19', 'チキンマサラ', 60, '中級', '2人前', 'recipe_images/kare-.jpg', 'クリーミーでスパイシーなチキンマサラのレシピです。本格的な味わいを自宅で楽しめます。', '2025-01-20 06:32:52', '2025-01-20 06:31:55'),
(74, '21', '白菜とキムチの鍋', 20, '中級', '2人前', 'recipe_images/kimutinabe.jpg', '白菜とキムチの旨味が溶け込んだ、簡単で美味しい重ね煮です。白菜の甘みとキムチの辛さが絶妙にマッチします。', '2025-01-20 06:55:42', '2025-01-20 06:54:41'),
(80, '21', '豚肉の生姜焼き丼', 20, '中級', '1人前', 'recipe_images/２３００２９pig.avif', '甘辛いタレがご飯に合う！簡単に作れる豚肉の生姜焼き丼です。', '2025-01-27 06:52:32', '2025-01-27 06:52:06'),
(81, '19', '和風だしオムライス', 30, '中級', '1人前', '', '和風だしを使った、あっさりとしたオムライスです。卵はふわふわに仕上げ、だしと鶏肉の旨味がご飯に染み込みます。', '2025-01-28 00:05:06', '2025-01-28 00:05:06');

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
(7, 57, 0, 'ひき肉を炒める'),
(8, 58, 0, 'ウィンナーを細かく切り焼く'),
(9, 58, 2, 'ごはんと卵を追加し炒める'),
(17, 63, 1, 'あああ'),
(18, 63, 2, 'いいい'),
(19, 63, 3, 'ううう'),
(20, 63, 4, 'えええ'),
(21, 63, 5, 'おおお'),
(40, 67, 1, '豆腐は水切りし、1.5cm角に切る。長ネギはみじん切りにする。'),
(41, 67, 2, 'フライパンにごま油を熱し、豚ひき肉を炒める。色が変わったら長ネギを加えて炒める。'),
(42, 67, 3, '豆板醤、甜麺醤を加えて炒め、香りが立ってきたらしょうゆ、砂糖、酒を加える。'),
(43, 67, 4, '水を加え、煮立ったら豆腐を加える。弱火で5分程煮る。'),
(44, 67, 5, '水溶き片栗粉でとろみをつける。花椒を振りかけ、お好みでラー油を回しかける。'),
(50, 68, 1, 'ボウルに卵、牛乳、砂糖、バニラエッセンスを入れ、よく混ぜ合わせる。'),
(51, 68, 2, 'バゲットを一口大に切る。'),
(52, 68, 3, 'バゲットを卵液に1分程浸す。'),
(53, 68, 4, 'フライパンにバターを熱し、バゲットを両面焼き色がつくまで焼く。'),
(54, 68, 5, 'メープルシロップをかけて完成。'),
(80, 69, 1, '鶏むね肉を一口大に切る。玉ねぎ、トマト、ニンニク、生姜をみじん切りにする。'),
(81, 69, 2, 'フライパンにサラダ油を熱し、クミンシードを炒める。香りが立ってきたら、玉ねぎを炒め、透き通ってきたらニンニクと生姜を加えて炒める。'),
(82, 69, 3, 'カレー粉、ガラムマサラ、ターメリック、チリパウダーを加えて炒め、香りが立ってきたらトマトを加えて炒める。'),
(83, 69, 4, '鶏むね肉を加えて炒め、ヨーグルト、塩、水を加えて煮込む。沸騰したら弱火にし、20分ほど煮込む。'),
(84, 69, 5, '仕上げにパクチーを散らして完成。'),
(121, 74, 1, '白菜はざく切りにする。豚バラ肉は食べやすい大きさに切る。'),
(122, 74, 2, '鍋に白菜、豚バラ肉、キムチを層になるように重ねて入れる。'),
(123, 74, 3, 'だし汁、醤油、みりん、酒、砂糖を加えて中火にかける。'),
(124, 74, 4, '沸騰したら弱火にし、蓋をして15分ほど煮込む。'),
(125, 74, 5, '仕上げに味をみて、必要であれば塩で調整する。'),
(157, 80, 1, '豚ロース肉は食べやすい大きさに切り、生姜はみじん切りにする。玉ねぎは薄切りにする。'),
(158, 80, 2, 'ボウルに醤油、みりん、砂糖、酒、片栗粉を入れて混ぜ合わせる。'),
(159, 80, 3, 'フライパンにサラダ油を熱し、豚肉を炒める。焼き色がついたら玉ねぎと生姜を加えて炒める。'),
(160, 80, 4, '2のタレを加えて炒め、肉にタレが絡んだら火を止める。'),
(161, 80, 5, 'ご飯の上に4を乗せて完成。'),
(162, 81, 1, '鶏むね肉は一口大に切り、玉ねぎはみじん切りにする。フライパンにバターを熱し、鶏肉と玉ねぎを炒める。'),
(163, 81, 2, 'だし汁、醤油、みりんを加えて煮詰める。'),
(164, 81, 3, 'ご飯を加えて混ぜ合わせる。'),
(165, 81, 4, '別のフライパンにバターを熱し、溶き卵を流し入れる。'),
(166, 81, 5, '卵が半熟になったら、ご飯を乗せて包む。'),
(167, 81, 6, '器に盛り付け、青ネギを散らす。');

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
(21, 'テストサーバー', 'yse@yse-c.net', '$2y$10$.vzB9fgftM7TX9QOxBeNT.9gjJh35qF1/gwfufwIaIQhtVviymW5C', '2025-01-20 06:02:36', '2025-01-20 06:02:36'),
(23, 'YSEテスト', 'test@example.com', '$2y$10$pEoYFOCe10ZaUswoLAHKR.HcvlKpqHTUaj2yFtEqzqnIkWNdbCE1O', '2025-01-21 02:54:58', '2025-01-21 02:54:58');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=279;

--
-- テーブルの AUTO_INCREMENT `meal_plans`
--
ALTER TABLE `meal_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- テーブルの AUTO_INCREMENT `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- テーブルの AUTO_INCREMENT `recipe_procedure`
--
ALTER TABLE `recipe_procedure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
