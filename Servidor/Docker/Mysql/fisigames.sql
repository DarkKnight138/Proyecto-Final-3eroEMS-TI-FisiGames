-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2025 a las 02:00:24
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `fisigames`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `id_cuenta` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permiso` tinyint(4) NOT NULL,
  `puntuacion_total` int(11) DEFAULT 0,
  `habilitado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`id_cuenta`, `nombre`, `email`, `password`, `permiso`, `puntuacion_total`, `habilitado`) VALUES
(1, 'matias', 'matiaspro@gmail.com', '$2y$10$Vlh9CqQ/EpfZPbF4biOghOAhOjRXLfNkFDt26.ubEoC6jcZ/H2u6.', 1, 0, 1),
(3, 'nacho', 'nacho@gmail.com', '$2y$10$Xx0F9HA4Yng.l2qMsQp9XOS492Ev11XRwGkIpOQBbGkpHFtU/Q/Ki', 1, 165, 1),
(6, 'Marcos', 'marcos@gmail.com', '$2y$10$d5zkmAlB3EKUhtUBHA8xPetQmeVsNz3We8OMyN2cTlY85rR1pYneO', 1, 0, 1),
(7, 'joaquin', 'jj@gmail.com', '$2y$10$efSksVLGY2GC.t9vFLcnA.o2/q8RqOyl1DKrxhwkrRQAVoNnLxtd.', 1, 0, 1),
(8, 'hola', 'hola@gmail.com', '$2y$10$llyGU/dxo6Awk7v5QTkbROAdQxEfgwaUuaHVDBtpjSPqlymKQezLC', 1, 0, 1),
(9, 'juan', 'juan@gmail.com', '$2y$10$f6eqpVMiCD0Ez2iLIljuWeNGy9p8nFnf7YK1dCdpoUoxcbTgDojsW', 1, 0, 1),
(10, 'Admin', 'admin@gmail.com', '$2y$10$cftXf6Pc1A1SILtZjj3kU.17ZG7EKahAIUKY.qqc/5YsoqgU1gnwK', 3, 0, 1),
(12, 'igna', 'ignaa@gmail.com', '$2y$10$um/0RuUw9yy5IrSJfEYfxeUgHOPTw13jeWWzoHpVwjn7kfswR.j.i', 2, 0, 1),
(13, 'Santiago', 'ares@gmail.com', '$2y$10$sSliYojEpCUX7xn8ZCq/tuFVHpODL7DsDmnMnAdQSloP2AVC7Gloy', 1, 0, 1),
(14, 'JUAcin', 'jjua@gmail.com', '$2y$10$vLjgzLSRfwCN7e3xax.Tsu5W3SE2cFDA5bzKBls9mMohMAz3NGWp6', 1, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `desbloquean`
--

CREATE TABLE `desbloquean` (
  `id_cuenta` int(11) NOT NULL,
  `id_juego` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id_grupo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `usuarios` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `nombre`, `creado_por`, `contraseña`, `usuarios`) VALUES
(7, 'The Papus', NULL, '1234', NULL),
(9, 'Peñarol', NULL, '1234', NULL),
(10, 'patapim', 1, '1234', '7'),
(11, 'papap', 1, '1234', NULL),
(12, 'ppp', 6, '1234', NULL),
(13, 'Mafilia', 3, '1234', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegos`
--

CREATE TABLE `juegos` (
  `id_juego` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id_mensaje` int(11) NOT NULL,
  `usr_emisor` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidas_jugadas`
--

CREATE TABLE `partidas_jugadas` (
  `id_partida` int(11) NOT NULL,
  `id_cuenta` int(11) DEFAULT NULL,
  `id_juego` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `puntaje` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pertenece_a`
--

CREATE TABLE `pertenece_a` (
  `id_cuenta` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pertenece_a`
--

INSERT INTO `pertenece_a` (`id_cuenta`, `id_grupo`) VALUES
(1, 9),
(1, 10),
(1, 11),
(3, 13),
(6, 12),
(7, 10),
(7, 13);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`id_cuenta`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `desbloquean`
--
ALTER TABLE `desbloquean`
  ADD PRIMARY KEY (`id_cuenta`,`id_juego`),
  ADD KEY `id_juego` (`id_juego`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id_grupo`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD PRIMARY KEY (`id_juego`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `usr_emisor` (`usr_emisor`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indices de la tabla `partidas_jugadas`
--
ALTER TABLE `partidas_jugadas`
  ADD PRIMARY KEY (`id_partida`),
  ADD KEY `id_cuenta` (`id_cuenta`),
  ADD KEY `id_juego` (`id_juego`);

--
-- Indices de la tabla `pertenece_a`
--
ALTER TABLE `pertenece_a`
  ADD PRIMARY KEY (`id_cuenta`,`id_grupo`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `id_cuenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `juegos`
--
ALTER TABLE `juegos`
  MODIFY `id_juego` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `partidas_jugadas`
--
ALTER TABLE `partidas_jugadas`
  MODIFY `id_partida` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `desbloquean`
--
ALTER TABLE `desbloquean`
  ADD CONSTRAINT `desbloquean_ibfk_1` FOREIGN KEY (`id_cuenta`) REFERENCES `cuentas` (`id_cuenta`),
  ADD CONSTRAINT `desbloquean_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juegos` (`id_juego`);

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `cuentas` (`id_cuenta`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`usr_emisor`) REFERENCES `cuentas` (`id_cuenta`),
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`);

--
-- Filtros para la tabla `partidas_jugadas`
--
ALTER TABLE `partidas_jugadas`
  ADD CONSTRAINT `partidas_jugadas_ibfk_1` FOREIGN KEY (`id_cuenta`) REFERENCES `cuentas` (`id_cuenta`),
  ADD CONSTRAINT `partidas_jugadas_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juegos` (`id_juego`);

--
-- Filtros para la tabla `pertenece_a`
--
ALTER TABLE `pertenece_a`
  ADD CONSTRAINT `pertenece_a_ibfk_1` FOREIGN KEY (`id_cuenta`) REFERENCES `cuentas` (`id_cuenta`) ON DELETE CASCADE,
  ADD CONSTRAINT `pertenece_a_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
