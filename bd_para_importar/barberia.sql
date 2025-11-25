-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2025 a las 22:01:18
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
-- Base de datos: `barberia`
--
CREATE DATABASE IF NOT EXISTS `barberia` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `barberia`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidad_peluquero`
--

CREATE TABLE `disponibilidad_peluquero` (
  `id` int(11) NOT NULL,
  `peluquero_id` int(11) NOT NULL,
  `dia_semana` enum('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peluquero_servicios`
--

CREATE TABLE `peluquero_servicios` (
  `peluquero_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `peluquero_servicios`
--

INSERT INTO `peluquero_servicios` (`peluquero_id`, `servicio_id`) VALUES
(2, 1),
(2, 3),
(2, 6),
(3, 2),
(3, 3),
(3, 6),
(4, 1),
(4, 3),
(5, 1),
(5, 4),
(5, 5),
(6, 1),
(6, 2),
(6, 4),
(7, 1),
(7, 5),
(8, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `duracion_estimada` int(11) DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion_estimada`) VALUES
(1, 'Corte Clásico', 'Corte con tijera y máquina, acabado prolijo.', 5000.00, 30),
(2, 'Corte Fade (Degradado)', 'Estilo moderno con degradado a piel.', 6000.00, 45),
(3, 'Barba Premium', 'Perfilado con navaja, toalla caliente y aceites.', 4000.00, 30),
(4, 'Color / Tintura', 'Coloración completa o reflejos.', 15000.00, 90),
(5, 'Alisado / Keratina', 'Tratamiento para alisar y nutrir.', 20000.00, 120),
(6, 'Servicio Completo', 'Corte + Barba + Lavado.', 9000.00, 60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `servicio` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(20) DEFAULT 'pendiente',
  `servicio_id` int(11) DEFAULT NULL,
  `peluquero_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id`, `usuario_id`, `fecha`, `hora`, `servicio`, `created_at`, `estado`, `servicio_id`, `peluquero_id`) VALUES
(1, 9, '2025-11-28', '10:00:00', '', '2025-11-25 20:51:41', 'confirmado', 1, 2),
(2, 11, '2025-11-28', '11:30:00', '', '2025-11-25 20:51:41', 'confirmado', 2, 3),
(3, 13, '2025-11-28', '15:00:00', '', '2025-11-25 20:51:41', 'pendiente', 3, 8),
(4, 15, '2025-11-29', '09:00:00', '', '2025-11-25 20:51:41', 'confirmado', 6, 2),
(5, 17, '2025-11-29', '14:00:00', '', '2025-11-25 20:51:41', 'cancelado_cliente', 4, 5),
(6, 19, '2025-11-29', '16:30:00', '', '2025-11-25 20:51:41', 'confirmado', 5, 7),
(7, 21, '2025-12-02', '10:00:00', '', '2025-11-25 20:51:41', 'confirmado', 1, 4),
(8, 23, '2025-12-02', '12:00:00', '', '2025-11-25 20:51:41', 'pendiente', 2, 6),
(9, 25, '2025-12-03', '09:30:00', '', '2025-11-25 20:51:41', 'confirmado', 3, 2),
(10, 27, '2025-12-03', '15:00:00', '', '2025-11-25 20:51:41', 'confirmado', 1, 4),
(11, 10, '2025-12-04', '11:00:00', '', '2025-11-25 20:51:41', 'confirmado', 4, 6),
(12, 12, '2025-12-04', '16:00:00', '', '2025-11-25 20:51:41', 'confirmado', 2, 3),
(13, 14, '2025-12-05', '10:00:00', '', '2025-11-25 20:51:41', 'pendiente', 6, 2),
(14, 16, '2025-12-05', '14:30:00', '', '2025-11-25 20:51:41', 'confirmado', 5, 7),
(15, 18, '2025-12-06', '09:00:00', '', '2025-11-25 20:51:41', 'confirmado', 1, 4),
(16, 20, '2025-12-06', '11:00:00', '', '2025-11-25 20:51:41', 'confirmado', 2, 6),
(17, 22, '2025-12-06', '15:00:00', '', '2025-11-25 20:51:41', 'cancelado', 3, 8),
(18, 24, '2025-12-09', '10:30:00', '', '2025-11-25 20:51:41', 'confirmado', 1, 2),
(19, 26, '2025-12-09', '14:00:00', '', '2025-11-25 20:51:41', 'confirmado', 4, 5),
(20, 28, '2025-12-10', '09:00:00', '', '2025-11-25 20:51:41', 'pendiente', 2, 3),
(21, 9, '2025-12-10', '16:00:00', '', '2025-11-25 20:51:41', 'confirmado', 6, 2),
(22, 11, '2025-12-11', '11:00:00', '', '2025-11-25 20:51:41', 'confirmado', 3, 8),
(23, 13, '2025-12-11', '15:30:00', '', '2025-11-25 20:51:41', 'confirmado', 1, 4),
(24, 15, '2025-12-12', '10:00:00', '', '2025-11-25 20:51:41', 'confirmado', 5, 7),
(25, 17, '2025-12-12', '18:00:00', '', '2025-11-25 20:51:41', 'pendiente', 2, 6),
(26, 19, '2025-12-13', '09:30:00', '', '2025-11-25 20:51:41', 'confirmado', 1, 2),
(27, 21, '2025-12-13', '12:00:00', '', '2025-11-25 20:51:41', 'confirmado', 4, 5),
(28, 23, '2025-12-16', '10:00:00', '', '2025-11-25 20:51:41', 'confirmado', 3, 8),
(29, 25, '2025-12-16', '14:00:00', '', '2025-11-25 20:51:41', 'confirmado', 2, 3),
(30, 27, '2025-12-17', '11:00:00', '', '2025-11-25 20:51:41', 'pendiente', 6, 2),
(31, 10, '2025-12-17', '16:00:00', '', '2025-11-25 20:51:41', 'confirmado', 1, 4),
(32, 12, '2025-12-18', '09:00:00', '', '2025-11-25 20:51:41', 'confirmado', 5, 7),
(33, 14, '2025-12-18', '13:00:00', '', '2025-11-25 20:51:41', 'confirmado', 2, 6),
(34, 16, '2025-12-19', '15:00:00', '', '2025-11-25 20:51:41', 'pendiente', 4, 5),
(35, 18, '2025-12-19', '17:30:00', '', '2025-11-25 20:51:41', 'confirmado', 1, 2),
(36, 20, '2025-12-20', '10:00:00', '', '2025-11-25 20:51:41', 'confirmado', 3, 8),
(37, 22, '2025-12-20', '12:00:00', '', '2025-11-25 20:51:41', 'confirmado', 2, 3),
(38, 24, '2025-12-22', '09:00:00', '', '2025-11-25 20:51:41', 'confirmado', 6, 2),
(39, 26, '2025-12-22', '14:00:00', '', '2025-11-25 20:51:41', 'confirmado', 1, 4),
(40, 28, '2025-12-23', '10:00:00', '', '2025-11-25 20:51:41', 'confirmado', 4, 5),
(41, 9, '2025-12-23', '16:00:00', '', '2025-11-25 20:51:41', 'pendiente', 2, 6),
(42, 11, '2025-12-24', '09:00:00', '', '2025-11-25 20:51:41', 'confirmado', 3, 8),
(43, 13, '2025-12-24', '11:00:00', '', '2025-11-25 20:51:41', 'confirmado', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rol` enum('Administrador','Cliente','Peluquero') NOT NULL DEFAULT 'Cliente',
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `intentos_fallidos` int(11) DEFAULT 0,
  `bloqueado_hasta` datetime DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `biografia` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `rol`, `email`, `password_hash`, `fecha_registro`, `intentos_fallidos`, `bloqueado_hasta`, `foto`, `telefono`, `biografia`) VALUES
(1, 'Administrador', 'Administrador', 'admin@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, NULL, NULL),
(2, 'Juan Perez', 'Peluquero', 'juan@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, 'PeluqueroGenerico1.jpg', '5491111111111', 'Especialista en cortes clásicos y navaja.'),
(3, 'Pedro Gomez', 'Peluquero', 'pedro@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, 'PeluqueroGenerico2.jpg', '5491122222222', 'Experto en Fade y estilos urbanos.'),
(4, 'Martin Ruiz', 'Peluquero', 'martin@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, 'PeluqueroGenerico3.jpg', '5491133333333', 'Barbero tradicional. Afeitado clásico.'),
(5, 'Ana Lopez', 'Peluquero', 'ana@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, 'PeluqueraGenerica1.jpg', '5491144444444', 'Estilista colorista. Cambios de look.'),
(6, 'Laura Diaz', 'Peluquero', 'laura@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, 'PeluqueraGenerica2.jpg', '5491155555555', 'Cortes modernos y peinados.'),
(7, 'Clara Vega', 'Peluquero', 'clara@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, 'PeluqueraGenerica3.jpg', '5491166666666', 'Especialista en alisados.'),
(8, 'Juan Carlos', 'Peluquero', 'jc@gmail.com', '$2y$10$/2pBrcyEqNL41Z2piv4skOPHg5jNnkgmcjDmduaJ7AACWpKd4Fo.a', '2025-11-25 20:51:41', 0, NULL, 'pro_6923adf4a6780.jpg', '02923511691', 'Especialista en barba'),
(9, 'Carlos Cliente', 'Cliente', 'carlos@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491111111111', NULL),
(10, 'Sofia Nueva', 'Cliente', 'sofia@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491122222222', NULL),
(11, 'Lucas Rodriguez', 'Cliente', 'lucas@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491133333333', NULL),
(12, 'Valentina Fernandez', 'Cliente', 'valen@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491144444444', NULL),
(13, 'Mateo Gonzalez', 'Cliente', 'mateo@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491155555555', NULL),
(14, 'Camila Lopez', 'Cliente', 'cami@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491166666666', NULL),
(15, 'Nicolas Martinez', 'Cliente', 'nico@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491177777777', NULL),
(16, 'Julieta Torres', 'Cliente', 'juli@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491188888888', NULL),
(17, 'Tomas Diaz', 'Cliente', 'tomi@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491199999999', NULL),
(18, 'Florencia Ruiz', 'Cliente', 'flor@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491100000000', NULL),
(19, 'Agustin Silva', 'Cliente', 'agus@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491112121212', NULL),
(20, 'Martina Perez', 'Cliente', 'marti@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491123232323', NULL),
(21, 'Facundo Castro', 'Cliente', 'facu@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491134343434', NULL),
(22, 'Rocio Gomez', 'Cliente', 'rocio@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491145454545', NULL),
(23, 'Joaquin Sosa', 'Cliente', 'joaco@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491156565656', NULL),
(24, 'Micaela Benitez', 'Cliente', 'mica@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491167676767', NULL),
(25, 'Santiago Romero', 'Cliente', 'santi@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491178787878', NULL),
(26, 'Lucia Herrera', 'Cliente', 'lu@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491189898989', NULL),
(27, 'Bruno Flores', 'Cliente', 'bruno@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491190909090', NULL),
(28, 'Delfina Acuña', 'Cliente', 'delfi@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-25 20:51:41', 0, NULL, NULL, '5491101010101', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `disponibilidad_peluquero`
--
ALTER TABLE `disponibilidad_peluquero`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peluquero_id` (`peluquero_id`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `peluquero_servicios`
--
ALTER TABLE `peluquero_servicios`
  ADD PRIMARY KEY (`peluquero_id`,`servicio_id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_turno_servicio` (`servicio_id`),
  ADD KEY `fk_turno_peluquero` (`peluquero_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `disponibilidad_peluquero`
--
ALTER TABLE `disponibilidad_peluquero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `disponibilidad_peluquero`
--
ALTER TABLE `disponibilidad_peluquero`
  ADD CONSTRAINT `disponibilidad_peluquero_ibfk_1` FOREIGN KEY (`peluquero_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `peluquero_servicios`
--
ALTER TABLE `peluquero_servicios`
  ADD CONSTRAINT `peluquero_servicios_ibfk_1` FOREIGN KEY (`peluquero_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peluquero_servicios_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `fk_turno_peluquero` FOREIGN KEY (`peluquero_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_turno_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `turnos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
