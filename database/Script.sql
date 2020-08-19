-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 30-07-2020 a las 07:37:14
-- Versión del servidor: 5.7.24
-- Versión de PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `weber`
--
CREATE DATABASE IF NOT EXISTS `weber` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `weber`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE IF NOT EXISTS `detalle_ventas` (
                                                `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                                `ventas_id` int(11) UNSIGNED NOT NULL,
                                                `producto_id` int(11) UNSIGNED NOT NULL,
                                                `cantidad` int(11) UNSIGNED NOT NULL,
                                                `precio_venta` double NOT NULL,
                                                PRIMARY KEY (`Id`),
                                                KEY `fk_detalle_ventas_ventas1_idx` (`ventas_id`),
                                                KEY `fk_detalle_ventas_producto1_idx` (`producto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE IF NOT EXISTS `productos` (
                                           `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                           `nombres` varchar(244) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `precio` double NOT NULL,
                                           `porcentaje_ganancia` float NOT NULL,
                                           `stock` int(11) UNSIGNED NOT NULL,
                                           `estado` enum('Activo','Inactivo') COLLATE utf8mb4_unicode_ci NOT NULL,
                                           PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
                                          `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                                          `nombres` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `apellidos` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `tipo_documento` enum('C.C','C.E','T.I') COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `documento` bigint(19) UNSIGNED NOT NULL,
                                          `telefono` bigint(19) UNSIGNED NOT NULL,
                                          `direccion` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `fecha_nacimiento` date NOT NULL,
                                          `user` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                          `password` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                          `rol` enum('Empleado','Cliente') COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `estado` enum('Activo','Inactivo') COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                          PRIMARY KEY (`id`),
                                          UNIQUE KEY `documento_UNIQUE` (`documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `tipo_documento`, `documento`, `telefono`, `direccion`, `fecha_nacimiento`, `user`, `password`, `rol`, `estado`, `fecha_registro`) VALUES
(1, 'Diego Alonso', 'Ojeda Medina', 'C.C', 12312312, 311324325, 'Transveral 27 15 -35', '2020-03-03', NULL, NULL, 'Cliente', 'Activo', '2020-07-27 00:00:00'),
(2, 'Juan Pablo', 'Garcia Gonzales', 'C.C', 46357159, 3118864151, 'Transveral 27 18-34', '2019-09-09', NULL, NULL, 'Cliente', 'Activo', '2020-07-27 00:00:00'),
(3, 'Juan Ricardo', 'Gonzales', 'C.C', 18328382, 42534647, 'Cra 27 N 2F - 05', '2019-07-16', NULL, NULL, 'Empleado', 'Activo', '2020-07-27 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE IF NOT EXISTS `ventas` (
                                        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                        `numero_serie` varchar(244) COLLATE utf8mb4_unicode_ci NOT NULL,
                                        `cliente_id` int(10) UNSIGNED NOT NULL,
                                        `empleado_id` int(10) UNSIGNED NOT NULL,
                                        `fecha_venta` datetime NOT NULL,
                                        `monto` double NOT NULL,
                                        `estado` enum('Activo','Inactivo') COLLATE utf8mb4_unicode_ci NOT NULL,
                                        PRIMARY KEY (`id`),
                                        KEY `fk_ventas_Usuarios1_idx` (`cliente_id`),
                                        KEY `fk_ventas_Usuarios2_idx` (`empleado_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
    ADD CONSTRAINT `fk_detalle_ventas_producto1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_detalle_ventas_ventas1` FOREIGN KEY (`ventas_id`) REFERENCES `ventas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
    ADD CONSTRAINT `fk_ventas_Usuarios1` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_ventas_Usuarios2` FOREIGN KEY (`empleado_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;