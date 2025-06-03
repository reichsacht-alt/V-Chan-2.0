-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-06-2025 a las 04:59:36
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `v-chan2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesslevel`
--

CREATE TABLE `accesslevel` (
  `id` int(11) NOT NULL,
  `level` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `accesslevel`
--

INSERT INTO `accesslevel` (`id`, `level`) VALUES
(1, 'owner'),
(2, 'user'),
(3, 'mod'),
(4, 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `original` varchar(100) NOT NULL,
  `preview` varchar(100) NOT NULL,
  `original_path` varchar(19) NOT NULL DEFAULT 'img/posts/original/',
  `preview_path` varchar(18) NOT NULL DEFAULT 'img/posts/preview/',
  `nsfw` tinyint(1) NOT NULL DEFAULT 0,
  `up_date` datetime NOT NULL,
  `dl_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `useraccesslevel`
--

CREATE TABLE `useraccesslevel` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `lid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `useraccesslevel`
--

INSERT INTO `useraccesslevel` (`id`, `uid`, `lid`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `userpicture`
--

CREATE TABLE `userpicture` (
  `id` int(11) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `directory` varchar(200) NOT NULL DEFAULT 'img/users/default/'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `userpicture`
--

INSERT INTO `userpicture` (`id`, `uid`, `image`, `directory`) VALUES
(1, '1', 'default1.png', 'img/users/default/'),
(2, '2', 'default3.png', 'img/users/default/'),
(3, '3', 'default1.png', 'img/users/default/');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uid` varchar(36) DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `code` varchar(6) NOT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `cr_date` datetime NOT NULL,
  `dl_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `uid`, `username`, `password`, `email`, `code`, `confirmed`, `verified`, `cr_date`, `dl_date`) VALUES
(1, 'UID000001', 'reichsacht', '$2y$10$xu96jYomZfEN9Nnr14HlkeuK5DzUMiZGIh95IPspZQurSrfEAx9ye', 'rechtenbann@gmail.com', 'REICHS', 1, 1, '2025-05-29 11:57:30', NULL),
(2, 'UID000002', 'Ryuu', '$2y$10$3bwAp4z.63gm.dvat3m/COJPpZeU8QeadVPyum9Q7K79drSFsCxVy', 'hratzeld@gmail.com', 'KQ703A', 1, 0, '2025-05-30 13:31:58', NULL),
(3, 'UID000003', 'reichsacht2', '$2y$10$NIh.jcy7nytxfzUJ8dZhsez7ZMNWpUK7jAQQRJPnPunJSNhLLNaNG', 'reichsacht2@gmail.com', 'HUX8EK', 0, 0, '2025-05-30 19:01:47', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesslevel`
--
ALTER TABLE `accesslevel`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `useraccesslevel`
--
ALTER TABLE `useraccesslevel`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `userpicture`
--
ALTER TABLE `userpicture`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesslevel`
--
ALTER TABLE `accesslevel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `useraccesslevel`
--
ALTER TABLE `useraccesslevel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `userpicture`
--
ALTER TABLE `userpicture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
