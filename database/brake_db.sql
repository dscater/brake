-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 19-06-2024 a las 16:37:13
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `brake_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `tipo`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'CATEGORIA #1', 'DESC. CATEGORIA 1', 'INGRESO', '2024-06-19', '2024-06-19 15:48:37', '2024-06-19 15:48:37'),
(2, 'CATEGORIA #2', '', 'EGRESO', '2024-06-19', '2024-06-19 15:48:45', '2024-06-19 15:48:45'),
(3, 'CATEGORIA 3', '', 'INGRESO', '2024-06-19', '2024-06-19 15:48:53', '2024-06-19 15:48:53'),
(4, 'CATEGORIA 4', '', 'EGRESO', '2024-06-19', '2024-06-19 15:48:59', '2024-06-19 15:48:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptos`
--

CREATE TABLE `conceptos` (
  `id` bigint UNSIGNED NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conceptos`
--

INSERT INTO `conceptos` (`id`, `categoria_id`, `nombre`, `descripcion`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 1, 'CONCEPTO #1', 'DESC. CONCEPTO 1', '2024-06-19', '2024-06-19 15:49:17', '2024-06-19 15:50:25'),
(2, 2, 'CONCEPTO #2', 'DESC CONCEPTO 2', '2024-06-19', '2024-06-19 15:52:01', '2024-06-19 15:52:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actividad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `razon_social`, `ciudad`, `dir`, `fono`, `correo`, `web`, `actividad`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'BRAKE', 'BK', 'BRAKE S.A.', 'LA PAZ', 'LOS OLIVOS', '7777777', 'BRAKE@GMAIL.COM', 'BRAKE.COM', 'ACTIVIDAD', '1716506212_1.jpg', NULL, '2024-05-23 23:16:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos`
--

CREATE TABLE `egresos` (
  `id` bigint UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egreso_detalles`
--

CREATE TABLE `egreso_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `concepto_id` bigint UNSIGNED NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` double(8,2) NOT NULL,
  `monto` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_accions`
--

CREATE TABLE `historial_accions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `accion` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_original` text COLLATE utf8mb4_unicode_ci,
  `datos_nuevo` text COLLATE utf8mb4_unicode_ci,
  `modulo` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_accions`
--

INSERT INTO `historial_accions` (`id`, `user_id`, `accion`, `descripcion`, `datos_original`, `datos_nuevo`, `modulo`, `fecha`, `hora`, `created_at`, `updated_at`) VALUES
(1, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 1<br/>nombre: CATEGORIA #1<br/>descripcion: DESC CATEGORIA 1<br/>tipo: INGRESO<br/>fecha_registro: 2024-06-18<br/>created_at: 2024-06-18 12:41:24<br/>updated_at: 2024-06-18 12:41:24<br/>', NULL, 'CATEGORIAS', '2024-06-18', '12:41:25', '2024-06-18 16:41:25', '2024-06-18 16:41:25'),
(2, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 1<br/>nombre: CATEGORIA #1<br/>descripcion: DESC CATEGORIA 1<br/>tipo: INGRESO<br/>fecha_registro: 2024-06-18<br/>created_at: 2024-06-18 12:41:24<br/>updated_at: 2024-06-18 12:41:24<br/>', 'id: 1<br/>nombre: CATEGORIA #1<br/>descripcion: DESC CATEGORIA 1<br/>tipo: EGRESO<br/>fecha_registro: 2024-06-18<br/>created_at: 2024-06-18 12:41:24<br/>updated_at: 2024-06-18 12:41:31<br/>', 'CATEGORIAS', '2024-06-18', '12:41:31', '2024-06-18 16:41:31', '2024-06-18 16:41:31'),
(3, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 1<br/>nombre: CATEGORIA #1<br/>descripcion: DESC CATEGORIA 1<br/>tipo: EGRESO<br/>fecha_registro: 2024-06-18<br/>created_at: 2024-06-18 12:41:24<br/>updated_at: 2024-06-18 12:41:31<br/>', 'id: 1<br/>nombre: CATEGORIA #1<br/>descripcion: DESC CATEGORIA 1<br/>tipo: INGRESO<br/>fecha_registro: 2024-06-18<br/>created_at: 2024-06-18 12:41:24<br/>updated_at: 2024-06-18 12:41:38<br/>', 'CATEGORIAS', '2024-06-18', '12:41:38', '2024-06-18 16:41:38', '2024-06-18 16:41:38'),
(4, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 1<br/>nombre: CATEGORIA #1<br/>descripcion: DESC CATEGORIA 1<br/>tipo: INGRESO<br/>fecha_registro: 2024-06-18<br/>created_at: 2024-06-18 12:41:24<br/>updated_at: 2024-06-18 12:41:38<br/>', 'id: 1<br/>nombre: CATEGORIA #1ADS<br/>descripcion: DESC CATEGORIA 1ASD<br/>tipo: INGRESO<br/>fecha_registro: 2024-06-18<br/>created_at: 2024-06-18 12:41:24<br/>updated_at: 2024-06-18 12:41:41<br/>', 'CATEGORIAS', '2024-06-18', '12:41:41', '2024-06-18 16:41:41', '2024-06-18 16:41:41'),
(5, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 1<br/>nombre: CATEGORIA #1ADS<br/>descripcion: DESC CATEGORIA 1ASD<br/>tipo: INGRESO<br/>fecha_registro: 2024-06-18<br/>created_at: 2024-06-18 12:41:24<br/>updated_at: 2024-06-18 12:41:41<br/>', 'id: 1<br/>nombre: CATEGORIA #1<br/>descripcion: DESC CATEGORIA 1<br/>tipo: INGRESO<br/>fecha_registro: 2024-06-18<br/>created_at: 2024-06-18 12:41:24<br/>updated_at: 2024-06-18 12:41:45<br/>', 'CATEGORIAS', '2024-06-18', '12:41:45', '2024-06-18 16:41:45', '2024-06-18 16:41:45'),
(6, 1, 'ELIMINACIÓN', 'EL USUARIO  ELIMINÓ UNA CATEGORIA', 'id: 1<br/>nombre: CATEGORIA #1<br/>descripcion: DESC CATEGORIA 1<br/>tipo: INGRESO<br/>fecha_registro: 2024-06-18<br/>created_at: 2024-06-18 12:41:24<br/>updated_at: 2024-06-18 12:41:45<br/>', NULL, 'CATEGORIAS', '2024-06-19', '11:48:10', '2024-06-19 15:48:10', '2024-06-19 15:48:10'),
(7, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 1<br/>nombre: CATEGORIA #1<br/>descripcion: DESC. CATEGORIA 1<br/>tipo: INGRESO<br/>fecha_registro: 2024-06-19<br/>created_at: 2024-06-19 11:48:37<br/>updated_at: 2024-06-19 11:48:37<br/>', NULL, 'CATEGORIAS', '2024-06-19', '11:48:37', '2024-06-19 15:48:37', '2024-06-19 15:48:37'),
(8, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 2<br/>nombre: CATEGORIA #2<br/>descripcion: <br/>tipo: EGRESO<br/>fecha_registro: 2024-06-19<br/>created_at: 2024-06-19 11:48:45<br/>updated_at: 2024-06-19 11:48:45<br/>', NULL, 'CATEGORIAS', '2024-06-19', '11:48:45', '2024-06-19 15:48:45', '2024-06-19 15:48:45'),
(9, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 3<br/>nombre: CATEGORIA 3<br/>descripcion: <br/>tipo: INGRESO<br/>fecha_registro: 2024-06-19<br/>created_at: 2024-06-19 11:48:53<br/>updated_at: 2024-06-19 11:48:53<br/>', NULL, 'CATEGORIAS', '2024-06-19', '11:48:53', '2024-06-19 15:48:53', '2024-06-19 15:48:53'),
(10, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 4<br/>nombre: CATEGORIA 4<br/>descripcion: <br/>tipo: EGRESO<br/>fecha_registro: 2024-06-19<br/>created_at: 2024-06-19 11:48:59<br/>updated_at: 2024-06-19 11:48:59<br/>', NULL, 'CATEGORIAS', '2024-06-19', '11:48:59', '2024-06-19 15:48:59', '2024-06-19 15:48:59'),
(11, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 1<br/>categoria_id: 1<br/>nombre: CONCEPTO #1<br/>descripcion: DESC. CONCEPTO 1<br/>fecha_registro: 2024-06-19<br/>created_at: 2024-06-19 11:49:17<br/>updated_at: 2024-06-19 11:49:17<br/>', NULL, 'CATEGORIAS', '2024-06-19', '11:49:17', '2024-06-19 15:49:17', '2024-06-19 15:49:17'),
(12, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 1<br/>categoria_id: 1<br/>nombre: CONCEPTO #1<br/>descripcion: DESC. CONCEPTO 1<br/>fecha_registro: 2024-06-19<br/>created_at: 2024-06-19 11:49:17<br/>updated_at: 2024-06-19 11:49:17<br/>', 'id: 1<br/>categoria_id: 2<br/>nombre: CONCEPTO #1ASD<br/>descripcion: DESC. CONCEPTO 1ASD<br/>fecha_registro: 2024-06-19<br/>created_at: 2024-06-19 11:49:17<br/>updated_at: 2024-06-19 11:49:37<br/>', 'CATEGORIAS', '2024-06-19', '11:49:37', '2024-06-19 15:49:37', '2024-06-19 15:49:37'),
(13, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 1<br/>categoria_id: 2<br/>nombre: CONCEPTO #1ASD<br/>descripcion: DESC. CONCEPTO 1ASD<br/>fecha_registro: 2024-06-19<br/>created_at: 2024-06-19 11:49:17<br/>updated_at: 2024-06-19 11:49:37<br/>', 'id: 1<br/>categoria_id: 1<br/>nombre: CONCEPTO #1<br/>descripcion: DESC. CONCEPTO 1<br/>fecha_registro: 2024-06-19<br/>created_at: 2024-06-19 11:49:17<br/>updated_at: 2024-06-19 11:50:25<br/>', 'CATEGORIAS', '2024-06-19', '11:50:25', '2024-06-19 15:50:25', '2024-06-19 15:50:25'),
(14, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 2<br/>categoria_id: 2<br/>nombre: CONCEPTO #2<br/>descripcion: DESC CONCEPTO 2<br/>fecha_registro: 2024-06-19<br/>created_at: 2024-06-19 11:51:33<br/>updated_at: 2024-06-19 11:51:33<br/>', NULL, 'CATEGORIAS', '2024-06-19', '11:51:33', '2024-06-19 15:51:33', '2024-06-19 15:51:33'),
(15, 1, 'ELIMINACIÓN', 'EL USUARIO  ELIMINÓ UNA CATEGORIA', 'id: 2<br/>categoria_id: 2<br/>nombre: CONCEPTO #2<br/>descripcion: DESC CONCEPTO 2<br/>fecha_registro: 2024-06-19<br/>created_at: 2024-06-19 11:51:33<br/>updated_at: 2024-06-19 11:51:33<br/>', NULL, 'CATEGORIAS', '2024-06-19', '11:51:45', '2024-06-19 15:51:45', '2024-06-19 15:51:45'),
(16, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 2<br/>categoria_id: 2<br/>nombre: CONCEPTO #2<br/>descripcion: DESC CONCEPTO 2<br/>fecha_registro: 2024-06-19<br/>created_at: 2024-06-19 11:52:01<br/>updated_at: 2024-06-19 11:52:01<br/>', NULL, 'CATEGORIAS', '2024-06-19', '11:52:01', '2024-06-19 15:52:01', '2024-06-19 15:52:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id` bigint UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_detalles`
--

CREATE TABLE `ingreso_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `concepto_id` bigint UNSIGNED NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` double(8,2) NOT NULL,
  `monto` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acceso` int NOT NULL DEFAULT '1',
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `password`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `dir`, `email`, `fono`, `tipo`, `foto`, `acceso`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 'admin', NULL, NULL, '0', '', '', 'admin@gmail.com', '', 'GERENTE', NULL, 1, '2024-01-31', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `conceptos`
--
ALTER TABLE `conceptos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conceptos_categoria_id_foreign` (`categoria_id`);

--
-- Indices de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `egresos_categoria_id_foreign` (`categoria_id`);

--
-- Indices de la tabla `egreso_detalles`
--
ALTER TABLE `egreso_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `egreso_detalles_concepto_id_foreign` (`concepto_id`);

--
-- Indices de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingresos_categoria_id_foreign` (`categoria_id`);

--
-- Indices de la tabla `ingreso_detalles`
--
ALTER TABLE `ingreso_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingreso_detalles_concepto_id_foreign` (`concepto_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_usuario_unique` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `conceptos`
--
ALTER TABLE `conceptos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `egresos`
--
ALTER TABLE `egresos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `egreso_detalles`
--
ALTER TABLE `egreso_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingreso_detalles`
--
ALTER TABLE `ingreso_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `conceptos`
--
ALTER TABLE `conceptos`
  ADD CONSTRAINT `conceptos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD CONSTRAINT `egresos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `egreso_detalles`
--
ALTER TABLE `egreso_detalles`
  ADD CONSTRAINT `egreso_detalles_concepto_id_foreign` FOREIGN KEY (`concepto_id`) REFERENCES `conceptos` (`id`);

--
-- Filtros para la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `ingresos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `ingreso_detalles`
--
ALTER TABLE `ingreso_detalles`
  ADD CONSTRAINT `ingreso_detalles_concepto_id_foreign` FOREIGN KEY (`concepto_id`) REFERENCES `conceptos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
