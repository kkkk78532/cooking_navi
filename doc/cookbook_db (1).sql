-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-11-07 15:29:00
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
(134, 79, 'あああ', 'ああ', ''),
(135, 80, '鶏肉', '300g', ''),
(136, 80, '玉ねぎ', '1個', ''),
(137, 82, 'サバ', '2切れ', ''),
(138, 82, '味噌', '大さじ2', ''),
(139, 82, 'みりん', '大さじ1', ''),
(140, 83, 'スパゲッティ', '100g', ''),
(141, 83, 'トマト', '2個', ''),
(142, 83, 'ニンニク', '1かけ', ''),
(143, 83, 'オリーブオイル', '大さじ2', ''),
(144, 83, 'バジル', '5枚', ''),
(145, 83, '塩', '少々', ''),
(146, 83, '黒胡椒', '少々', ''),
(147, 84, '食パン', '2枚', ''),
(148, 84, '卵', '2個', ''),
(149, 84, 'マヨネーズ', '大さじ1', ''),
(150, 84, '牛乳', '大さじ1', ''),
(151, 84, '塩', '少々', ''),
(152, 84, 'レタス', '2枚', ''),
(153, 84, 'トマト', '1/2個', '');

-- --------------------------------------------------------

--
-- テーブルの構造 `meal_plans`
--

CREATE TABLE `meal_plans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `plan_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `recipes`
--

INSERT INTO `recipes` (`id`, `username`, `recipe_title`, `recipe_time`, `recipe_difficulty`, `recipe_ServingSize`, `recipe_picture`, `recipe_introduction`, `created_at`, `updated_at`) VALUES
(77, 'aaa', 'チャーハン', 30, '簡単', '2人分', 'recipe_images/tya-hann.jpg', 'チャーハンの作り方', '2024-10-08 01:22:06', '2024-10-01 03:01:11'),
(78, 'aaa', 'a', 3, '簡単', '2人分', 'recipe_images/katudon.jpg', 'aa', '2024-10-08 06:37:02', '2024-10-08 06:37:02'),
(79, 'aaa', 'ラーメン', 20, '簡単', '4人分', '', 'ああ', '2024-10-08 06:37:33', '2024-10-08 06:37:33'),
(80, '', 'チキンカレー', 30, '中級', '4人前', '', '簡単で美味しいチキンカレーのレシピです。', '2024-10-29 01:12:49', '2024-10-29 01:12:49'),
(82, '', 'サバの味噌煮', 30, '中級', '2人前', '', 'サバの旨みが染み込んだ、ご飯によく合う家庭料理です。味噌とみりんの甘辛い味付けが食欲をそそります。', '2024-11-05 02:31:49', '2024-11-05 02:31:49'),
(83, '', 'トマトとバジルのパスタ', 20, '中級', '1人前', '', 'シンプルながらも味わい深い、トマトとバジルのパスタ。暑い夏にぴったりの爽やかな一品。', '2024-11-05 02:33:15', '2024-11-05 02:33:15'),
(84, '', 'ふわふわたまごサンド', 15, '簡単', '1人前', '', 'ふわふわのたまご焼きと、みずみずしい野菜をサンドした、朝食にぴったりの簡単レシピです。', '2024-11-05 02:39:52', '2024-11-05 02:39:52');

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
(80, 78, 1, ''),
(81, 80, 1, '鶏肉を一口大に切り、玉ねぎをみじん切りにする。'),
(82, 80, 2, '鍋に油を熱し、玉ねぎを炒めて透明になるまで加熱する。'),
(83, 82, 1, 'サバは軽く塩を振り、水気を切っておく。'),
(84, 82, 2, '鍋に水、酒、みりん、砂糖、しょうゆ、味噌、生姜を入れて煮立てる'),
(85, 83, 1, '鍋に湯を沸かし、塩を加えてスパゲッティをパッケージの表示時間通りに茹でる。'),
(86, 83, 2, 'トマトは湯むきして、1cm角に切る。ニンニクは薄切りにする。バジルは手でちぎる。'),
(87, 83, 3, 'フライパンにオリーブオイルとニンニクを入れ、弱火で熱する。香りが立ってきたら、トマトを加えて炒め、塩コショウで味を調える。'),
(88, 83, 4, '茹で上がったスパゲッティをフライパンに加え、よく混ぜ合わせる。'),
(89, 83, 5, 'バジルを加えて混ぜ合わせ、器に盛り付けたら完成。'),
(90, 84, 1, 'ボウルに卵を割りほぐし、牛乳、マヨネーズ、塩を加えてよく混ぜる。'),
(91, 84, 2, 'フライパンに油をひき、弱火で熱する。卵液を流し込み、菜箸などでかき混ぜながら焼き、裏返して両面を焼く。'),
(92, 84, 3, 'レタスとトマトは洗って水気を切り、レタスは食べやすい大きさにちぎる。トマトは薄切りにする。'),
(93, 84, 4, '食パンにマヨネーズを塗る。'),
(94, 84, 5, '食パンにレタス、たまご焼き、トマトの順に重ねてサンドする。');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
-- テーブルのインデックス `meal_plans`
--
ALTER TABLE `meal_plans`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- テーブルの AUTO_INCREMENT `meal_plans`
--
ALTER TABLE `meal_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- テーブルの AUTO_INCREMENT `recipe_procedure`
--
ALTER TABLE `recipe_procedure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
