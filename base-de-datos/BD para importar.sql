-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2025 a las 20:56:17
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

--
-- Volcado de datos para la tabla `disponibilidad_peluquero`
--

INSERT INTO `disponibilidad_peluquero` (`id`, `peluquero_id`, `dia_semana`, `hora_inicio`, `hora_fin`) VALUES
(1, 4, 'Lunes', '09:00:00', '14:00:00'),
(2, 4, 'Miercoles', '09:00:00', '14:00:00'),
(3, 4, 'Viernes', '09:00:00', '14:00:00'),
(4, 5, 'Lunes', '15:00:00', '20:00:00'),
(5, 5, 'Miercoles', '15:00:00', '20:00:00'),
(6, 5, 'Viernes', '15:00:00', '20:00:00'),
(7, 6, 'Martes', '10:00:00', '19:00:00'),
(8, 6, 'Jueves', '10:00:00', '19:00:00'),
(9, 7, 'Martes', '14:00:00', '20:00:00'),
(10, 7, 'Jueves', '14:00:00', '20:00:00'),
(11, 7, 'Sabado', '10:00:00', '18:00:00'),
(12, 8, 'Lunes', '10:00:00', '18:00:00'),
(13, 8, 'Miercoles', '10:00:00', '18:00:00'),
(14, 9, 'Viernes', '12:00:00', '20:00:00'),
(15, 9, 'Sabado', '09:00:00', '15:00:00'),
(16, 10, 'Lunes', '09:00:00', '13:00:00'),
(17, 10, 'Martes', '09:00:00', '13:00:00'),
(18, 10, 'Miercoles', '09:00:00', '13:00:00'),
(19, 10, 'Jueves', '09:00:00', '13:00:00'),
(20, 10, 'Viernes', '09:00:00', '13:00:00'),
(21, 10, 'Sabado', '09:00:00', '13:00:00');

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

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `usuario_id`, `mensaje`, `leido`, `creado_en`) VALUES
(1, 1, 'Nuevo turno: Sofia Nueva reservó para hoy a las 16:00.', 0, '2025-11-23 18:23:08'),
(2, 5, 'Nuevo turno asignado: Sofia Nueva hoy a las 16:00.', 0, '2025-11-23 18:23:08'),
(3, 2, 'Tu turno pasado ha sido cancelado exitosamente.', 1, '2025-11-23 18:23:08'),
(4, 1, 'Nuevo turno: Sofia Nueva reservó el 2025-11-29 a las 10:00', 0, '2025-11-24 00:49:28'),
(5, 9, 'Nuevo turno: Sofia Nueva reservó el 2025-11-29 a las 10:00', 0, '2025-11-24 00:49:28');

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
(4, 1),
(4, 3),
(4, 6),
(5, 2),
(5, 3),
(5, 6),
(6, 1),
(6, 3),
(7, 1),
(7, 4),
(7, 5),
(8, 1),
(8, 2),
(8, 4),
(9, 1),
(9, 5),
(10, 3);

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
(6, 'Servicio Completo', 'Corte + Barba + Lavado.', 9000.00, 60),
(7, 'Ejemplo', '', 2000.00, 30);

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
(1, 2, '2025-11-23', '10:00:00', '', '2025-11-23 18:23:08', 'confirmado', 1, 4),
(2, 3, '2025-11-23', '16:00:00', '', '2025-11-23 18:23:08', 'pendiente', 2, 5),
(3, 2, '2025-11-23', '11:00:00', '', '2025-11-23 18:23:08', 'confirmado', 5, 8),
(4, 3, '2025-11-24', '15:00:00', '', '2025-11-23 18:23:08', 'confirmado', 4, 7),
(5, 2, '2025-11-24', '10:30:00', '', '2025-11-23 18:23:08', 'pendiente', 3, 6),
(6, 2, '2025-11-21', '18:00:00', '', '2025-11-23 18:23:08', 'cancelado_cliente', 2, 5),
(7, 3, '2025-11-29', '10:00:00', '', '2025-11-24 00:49:28', 'pendiente', 5, 9);

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
(1, 'Administrador', 'Administrador', 'admin@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-23 18:23:07', 0, NULL, NULL, NULL, NULL),
(2, 'Carlos Cliente', 'Cliente', 'carlos@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-23 18:23:07', 0, NULL, NULL, '5491122334455', NULL),
(3, 'Sofia Nueva', 'Cliente', 'sofia@gmail.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-23 18:23:07', 0, NULL, NULL, '5491155667788', NULL),
(4, 'Juan Perez', 'Peluquero', 'juan@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-23 18:23:07', 0, NULL, 'PeluqueroGenerico1.jpg', '5491111111111', 'Especialista en cortes clásicos y navaja. 10 años de experiencia.'),
(5, 'Pedro Gomez', 'Peluquero', 'pedro@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-23 18:23:07', 0, NULL, 'PeluqueroGenerico2.jpg', '5491122222222', 'Experto en Fade y estilos urbanos. El rey de la máquina.'),
(6, 'Martin Ruiz', 'Peluquero', 'martin@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-23 18:23:07', 0, NULL, 'PeluqueroGenerico3.jpg', '5491133333333', 'Barbero tradicional. Afeitado clásico y cuidado de barba.'),
(7, 'Ana Lopez', 'Peluquero', 'ana@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-23 18:23:07', 0, NULL, 'PeluqueraGenerica1.jpg', '5491144444444', 'Estilista colorista. Cambios de look radicales y tratamientos.'),
(8, 'Laura Diaz', 'Peluquero', 'laura@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-23 18:23:07', 0, NULL, 'PeluqueraGenerica2.jpg', '5491155555555', 'Cortes modernos y peinados para eventos.'),
(9, 'Clara Vega', 'Peluquero', 'clara@barberia.com', '$2y$10$5AaXNna.CjLbTmlmNLXn0.ujq8MDhWKTd/ygNh3USBvOce6GApShe', '2025-11-23 18:23:07', 0, NULL, 'PeluqueraGenerica3.jpg', '5491166666666', 'Especialista en alisados y nutrición capilar.'),
(10, 'Juan Carlos', 'Peluquero', 'jc@gmail.com', '$2y$10$/2pBrcyEqNL41Z2piv4skOPHg5jNnkgmcjDmduaJ7AACWpKd4Fo.a', '2025-11-24 00:59:32', 0, NULL, 'pro_6923adf4a6780.jpg', '02923511691', 'Especialista en barba');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
