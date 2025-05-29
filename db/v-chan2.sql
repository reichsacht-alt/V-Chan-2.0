-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-05-2025 a las 01:16:23
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

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
-- Estructura de tabla para la tabla `userpicture`
--

CREATE TABLE `userpicture` (
  `id` int(11) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `directory` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `userpicture`
--

INSERT INTO `userpicture` (`id`, `uid`, `image`, `directory`) VALUES
(1, '1', 'ero3.png', 'img/users/');

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
(1, 'UID000001', 'reichsacht', '$2y$10$xu96jYomZfEN9Nnr14HlkeuK5DzUMiZGIh95IPspZQurSrfEAx9ye', 'rechtenbann@gmail.com', 'REICHS', 1, 0, '2025-05-29 11:57:30', NULL);

--
-- Índices para tablas volcadas
--

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
-- AUTO_INCREMENT de la tabla `userpicture`
--
ALTER TABLE `userpicture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
