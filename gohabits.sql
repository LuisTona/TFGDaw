-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-06-2025 a las 15:14:59
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

CREATE DATABASE IF NOT EXISTS `gohabits` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gohabits`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gohabits`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `comentarioUser` text NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_publicacion` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitos`
--

CREATE TABLE `habitos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `habito` int(11) NOT NULL,
  `desde` timestamp NOT NULL DEFAULT current_timestamp(),
  `duracion` int(11) NOT NULL,
  `frecuencia` int(1) DEFAULT NULL,
  `nivel` int(11) NOT NULL DEFAULT 0,
  `realizadoHoy` tinyint(1) NOT NULL DEFAULT 0,
  `realizadoFrecuencia` int(11) NOT NULL DEFAULT 0,
  `tiempoRealizado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habitos`
--

INSERT INTO `habitos` (`id`, `user_id`, `habito`, `desde`, `duracion`, `frecuencia`, `nivel`, `realizadoHoy`, `realizadoFrecuencia`, `tiempoRealizado`) VALUES
(51, 3, 13, '2025-06-11 08:51:44', 5, 4, 0, 1, 0, 300);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitslist`
--

CREATE TABLE `habitslist` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habitslist`
--

INSERT INTO `habitslist` (`id`, `nombre`) VALUES
(7, 'Andar en bicicleta'),
(19, 'Aprender diseño o edición digital'),
(16, 'Dibujar o pintar'),
(10, 'Dormir siesta breve'),
(3, 'Escribir (novela, diario, blog)'),
(4, 'Escuchar audiolibros'),
(13, 'Estudiar para exámenes'),
(2, 'Estudiar un nuevo idioma'),
(20, 'Hablar con familia o amigos'),
(6, 'Hacer ejercicio (gym, correr, etc.)'),
(5, 'Hacer un curso online'),
(17, 'Jugar o hacer una actividad recreativa'),
(1, 'Leer libros'),
(18, 'Limpiar o ordenar espacios'),
(14, 'Meditar'),
(12, 'Organizar archivos o notas'),
(15, 'Pasear al aire libre'),
(9, 'Preparar comida saludable'),
(11, 'Trabajar sin distracciones (pomodoro)'),
(8, 'Yoga o estiramientos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `id` int(11) NOT NULL,
  `url_imagen` varchar(100) NOT NULL,
  `experiencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`id`, `url_imagen`, `experiencia`) VALUES
(1, '../imagenes/lvl1.png', 0),
(2, '../imagenes/lvl2.png', 5),
(3, '../imagenes/lvl3.png', 13),
(4, '../imagenes/lvl4.png', 22),
(5, '../imagenes/lvl5.png', 32),
(6, '../imagenes/lvl6.png', 43),
(7, '../imagenes/lvl7.png', 55),
(8, '../imagenes/lvl8.png', 68),
(9, '../imagenes/lvl9.png', 82),
(10, '../imagenes/lvl10.png', 97);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `id_imagen` int(11) NOT NULL,
  `id_habito` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_publicacion_habito` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `id_user`, `comentario`, `id_imagen`, `id_habito`, `fecha`, `id_publicacion_habito`) VALUES
(32, 3, 'Hola comenzamos con el nuevo reto', 1, 13, '2025-06-11 08:51:52', 51);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguidores`
--

CREATE TABLE `seguidores` (
  `id_user` int(11) NOT NULL,
  `id_userSeguidor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seguidores`
--

INSERT INTO `seguidores` (`id_user`, `id_userSeguidor`) VALUES
(3, 4),
(4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `imagenUser` varchar(150) DEFAULT '../imagenes/defaultImage.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `imagenUser`) VALUES
(3, 'luis', '$2y$10$SwiRxlQWeriEbgIqiAfl0OIO/oZIoOh5YN.n3vem7Irhl8J5Wk2DS', 'l@gmail.com', '../imagenes/defaultImage.jpg'),
(4, 'alberto', '$2y$10$WM8UCdl6snD1qmbFsQmZOepREcStJdON89ZxMlHb4W3DbpT3UNy/S', 'a@gmail.com', '../imagenes/defaultImage.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_publicacion` (`id_publicacion`);

--
-- Indices de la tabla `habitos`
--
ALTER TABLE `habitos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `habito` (`habito`);

--
-- Indices de la tabla `habitslist`
--
ALTER TABLE `habitslist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_imagen` (`id_imagen`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_habito` (`id_habito`),
  ADD KEY `id_publicacion_habito` (`id_publicacion_habito`);

--
-- Indices de la tabla `seguidores`
--
ALTER TABLE `seguidores`
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_userSeguidor` (`id_userSeguidor`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `habitos`
--
ALTER TABLE `habitos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `habitslist`
--
ALTER TABLE `habitslist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_publicacion`) REFERENCES `publicaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `habitos`
--
ALTER TABLE `habitos`
  ADD CONSTRAINT `habitos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `habitos_ibfk_2` FOREIGN KEY (`habito`) REFERENCES `habitslist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `publicaciones_ibfk_2` FOREIGN KEY (`id_publicacion_habito`) REFERENCES `habitos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `seguidores`
--
ALTER TABLE `seguidores`
  ADD CONSTRAINT `seguidores_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seguidores_ibfk_2` FOREIGN KEY (`id_userSeguidor`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
