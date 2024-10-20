-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Gép: localhost:3306
-- Létrehozás ideje: 2024. Okt 20. 21:36
-- Kiszolgáló verziója: 8.0.37-0ubuntu0.22.04.3
-- PHP verzió: 8.1.2-1ubuntu2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `etterem`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `foods`
--

CREATE TABLE `foods` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type_id` int NOT NULL,
  `image_path` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- A tábla adatainak kiíratása `foods`
--

INSERT INTO `foods` (`id`, `name`, `price`, `description`, `type_id`, `image_path`, `created_at`) VALUES
(6, 'Tejbegríz', 480, '', 2, 'tejbegriz.jpeg', '2024-10-08 14:56:01'),
(12, 'Kukoricasaláta', 700, 'A kukoricasaláta egy frissítő és ízletes köret vagy önálló étel, melyet konzerv vagy főtt kukoricából, majonézes vagy joghurtos öntettel.', 5, 'kukoricasalata.jfif', '2024-10-20 15:39:12'),
(13, 'Paradicsomleves', 790, 'A paradicsomleves a paradicsom kipréselt, átszűrt levéből rántással készített zöldségleves, amibe rizst vagy metéltet tesznek. Világszerte elterjedt étel.', 1, 'paradicsomleves.jpg', '2024-10-20 16:55:30'),
(14, 'Brassói aprópecsenye', 1560, 'A brassói aprópecsenye egy sertéshúsból készített főfogás. Eredete ismeretlen, többféle legenda övezi.', 2, 'brassoi_apropecsenye.png', '2024-10-20 18:47:51'),
(15, 'Gyümölcsleves', 700, 'Édes, krémes, leginkább nyári. Kicsit szertelen, mert olyan, mintha a desszertet ennénk elsőként. A nyári melegben ugyanakkor rendkívül hűsítő és üdítő is egyben.', 1, 'gyumolcsleves.jpg', '2024-10-20 18:52:19');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `foods_meta`
--

CREATE TABLE `foods_meta` (
  `id` int NOT NULL,
  `food_id` int NOT NULL,
  `meta_key` varchar(100) NOT NULL,
  `meta_value` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `delivery_home` tinyint(1) NOT NULL DEFAULT '0',
  `delivery_time` datetime DEFAULT NULL,
  `food_id` int NOT NULL,
  `doses` int NOT NULL,
  `total_amount` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- A tábla adatainak kiíratása `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `created_at`, `delivery_home`, `delivery_time`, `food_id`, `doses`, `total_amount`) VALUES
(37, 8, '2024-10-08 16:14:04', 0, NULL, 6, 6, 2880),
(38, 8, '2024-10-20 18:44:19', 1, '2024-10-20 20:45:00', 13, 1, 990),
(39, 17, '2024-10-20 18:55:59', 1, '2024-10-20 20:58:00', 14, 4, 6440);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `type_of_food`
--

CREATE TABLE `type_of_food` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- A tábla adatainak kiíratása `type_of_food`
--

INSERT INTO `type_of_food` (`id`, `name`) VALUES
(1, 'Leves'),
(2, 'Főétel'),
(4, 'Desszert'),
(5, 'Saláta');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `created_at`) VALUES
(8, 'admin@gmail.com', '$2y$12$R2vvAIY492MLp69DbRnCduZKeoaJPKRzS.a2UdnO5ooqEEgImoErS', 'admin', '2024-09-18 20:34:20'),
(17, 'elso@gmail.com', '$2y$12$oSqjtki2qnKlRG6S2NJtle2N825mkjVIiNWpejsUl0EQ5W9WfFhHa', 'user', '2024-10-05 21:25:37');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users_meta`
--

CREATE TABLE `users_meta` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `meta_key` varchar(50) NOT NULL,
  `meta_value` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- A tábla adatainak kiíratása `users_meta`
--

INSERT INTO `users_meta` (`id`, `user_id`, `meta_key`, `meta_value`, `created_at`) VALUES
(31, 17, 'phone_number', '1', '2024-10-05 21:25:37'),
(32, 17, 'lastname', 'Teszt', '2024-10-05 21:25:37'),
(33, 17, 'firstname', 'Elek', '2024-10-05 21:25:37'),
(34, 17, 'street', 'Alma utca', '2024-10-05 21:25:37'),
(35, 17, 'number', '52 A', '2024-10-05 21:25:37');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `type_id_2` (`type_id`);

--
-- A tábla indexei `foods_meta`
--
ALTER TABLE `foods_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `food_id` (`food_id`);

--
-- A tábla indexei `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `food_id` (`food_id`);

--
-- A tábla indexei `type_of_food`
--
ALTER TABLE `type_of_food`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A tábla indexei `users_meta`
--
ALTER TABLE `users_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT a táblához `foods_meta`
--
ALTER TABLE `foods_meta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT a táblához `type_of_food`
--
ALTER TABLE `type_of_food`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT a táblához `users_meta`
--
ALTER TABLE `users_meta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `foods`
--
ALTER TABLE `foods`
  ADD CONSTRAINT `foods_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `type_of_food` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Megkötések a táblához `foods_meta`
--
ALTER TABLE `foods_meta`
  ADD CONSTRAINT `foods_meta_ibfk_1` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Megkötések a táblához `users_meta`
--
ALTER TABLE `users_meta`
  ADD CONSTRAINT `users_meta_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
