-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-04-2025 a las 01:18:18
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.2.22

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
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(600) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conceptos`
--

INSERT INTO `conceptos` (`id`, `categoria_id`, `nombre`, `descripcion`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 1, 'CONCEPTO #1', 'DESC. CONCEPTO 1', '2024-06-19', '2024-06-19 15:49:17', '2024-06-19 15:50:25'),
(2, 2, 'CONCEPTO #2', 'DESC CONCEPTO 2', '2024-06-19', '2024-06-19 15:52:01', '2024-06-19 15:52:01'),
(3, 3, 'CONCEPTO #3', '', '2024-06-24', '2024-06-24 17:59:37', '2024-06-24 17:59:37'),
(4, 4, 'CONCEPTO 4', '', '2024-06-24', '2024-06-24 17:59:45', '2024-06-24 17:59:45'),
(5, 1, 'CONCEPTO #6', '', '2024-06-24', '2024-06-24 18:00:16', '2024-06-24 18:00:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razon_social` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actividad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `razon_social`, `ciudad`, `dir`, `fono`, `correo`, `web`, `actividad`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'BRAKE', 'BK', 'BRAKE S.A,', 'LA PAZ', 'LOS OLIVOS', '7777777', 'BRAKE@GMAIL.COM', 'BRAKE.COM', 'ACTIVIDAD', '1716506212_1.jpg', NULL, '2025-03-29 19:47:20');

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

--
-- Volcado de datos para la tabla `egresos`
--

INSERT INTO `egresos` (`id`, `fecha`, `categoria_id`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, '2024-06-20', 2, '2024-06-20', '2024-06-20 15:55:26', '2024-06-20 15:55:26'),
(2, '2024-06-26', 4, '2024-06-26', '2024-06-26 14:21:35', '2024-06-26 14:21:35'),
(3, '2024-06-26', 2, '2024-06-26', '2024-06-26 14:24:40', '2024-06-26 14:24:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egreso_detalles`
--

CREATE TABLE `egreso_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `egreso_id` bigint UNSIGNED NOT NULL,
  `concepto_id` bigint UNSIGNED NOT NULL,
  `descripcion` varchar(600) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` double(8,2) NOT NULL,
  `monto` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `egreso_detalles`
--

INSERT INTO `egreso_detalles` (`id`, `egreso_id`, `concepto_id`, `descripcion`, `cantidad`, `monto`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'DESC EGRESO', 23.00, 1200.00, '2024-06-20 15:55:26', '2024-06-20 15:55:26'),
(2, 2, 4, 'DESC', 10.00, 3600.00, '2024-06-26 14:21:35', '2024-06-26 14:21:35'),
(3, 3, 2, 'DESC.', 100.00, 30000.00, '2024-06-26 14:24:40', '2024-06-26 14:24:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_accions`
--

CREATE TABLE `historial_accions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accion` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_original` json DEFAULT NULL,
  `datos_nuevo` json DEFAULT NULL,
  `modulo` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabla` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `sistema` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `creatorUserName` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creatorFullUserName` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creatorIp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updaterUserName` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updaterFullUserName` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updaterIp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleterUserName` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleterFullUserName` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleterIp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_accions`
--

INSERT INTO `historial_accions` (`id`, `user_id`, `role`, `accion`, `descripcion`, `datos_original`, `datos_nuevo`, `modulo`, `tabla`, `fecha`, `hora`, `sistema`, `creatorUserName`, `creatorFullUserName`, `creatorIp`, `updaterUserName`, `updaterFullUserName`, `updaterIp`, `deleterUserName`, `deleterFullUserName`, `deleterIp`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'GERENTE', 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA CATEGORÍA', '{\"id\": 12, \"tipo\": \"EGRESO\", \"nombre\": \"CATEGORIA CINCO\", \"created_at\": \"2025-04-22T01:16:46.000000Z\", \"updated_at\": \"2025-04-22T01:16:46.000000Z\", \"descripcion\": \"DESC\", \"fecha_registro\": \"2025-04-21\"}', NULL, 'CATEGORÍAS', 'categorias', '2025-04-21', '21:16:46', 'Equipo Escritorio - S.O. Windows - Navegador Chrome', 'admin', 'admin ', '127.0.0.1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-22 01:16:46', '2025-04-22 01:16:46'),
(2, 1, 'GERENTE', 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA CATEGORÍA', '{\"id\": 12, \"tipo\": \"EGRESO\", \"nombre\": \"CATEGORIA CINCO\", \"created_at\": \"2025-04-22T01:16:46.000000Z\", \"updated_at\": \"2025-04-22T01:16:46.000000Z\", \"descripcion\": \"DESC\", \"fecha_registro\": \"2025-04-21\"}', '{\"id\": 12, \"tipo\": \"EGRESO\", \"nombre\": \"CATEGORIA CINCO\", \"created_at\": \"2025-04-22T01:16:46.000000Z\", \"updated_at\": \"2025-04-22T01:17:50.000000Z\", \"descripcion\": \"DESCRIPCION CINCO\", \"fecha_registro\": \"2025-04-21\"}', 'CATEGORÍAS', 'categorias', '2025-04-21', '21:17:50', 'Equipo Escritorio - S.O. Windows - Navegador Chrome', NULL, NULL, NULL, 'admin', 'admin ', '127.0.0.1', NULL, NULL, NULL, NULL, '2025-04-22 01:17:50', '2025-04-22 01:17:50'),
(3, 1, 'GERENTE', 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA CATEGORÍA', '{\"id\": 12, \"tipo\": \"EGRESO\", \"nombre\": \"CATEGORIA CINCO\", \"created_at\": \"2025-04-22T01:16:46.000000Z\", \"updated_at\": \"2025-04-22T01:17:50.000000Z\", \"descripcion\": \"DESCRIPCION CINCO\", \"fecha_registro\": \"2025-04-21\"}', NULL, 'CATEGORÍAS', 'categorias', '2025-04-21', '21:18:02', 'Equipo Escritorio - S.O. Windows - Navegador Chrome', NULL, NULL, NULL, NULL, NULL, NULL, 'admin', 'admin ', '127.0.0.1', NULL, '2025-04-22 01:18:02', '2025-04-22 01:18:02');

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

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id`, `fecha`, `categoria_id`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, '2024-06-20', 1, '2024-06-20', '2024-06-20 15:29:23', '2024-06-20 15:29:23'),
(2, '2024-06-24', 1, '2024-06-24', '2024-06-24 18:00:43', '2024-06-24 18:00:43'),
(3, '2024-06-26', 1, '2024-06-26', '2024-06-26 14:27:02', '2024-06-26 14:27:02'),
(4, '2024-06-26', 1, '2024-06-26', '2024-06-26 14:39:25', '2024-06-26 14:39:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_detalles`
--

CREATE TABLE `ingreso_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `ingreso_id` bigint UNSIGNED NOT NULL,
  `concepto_id` bigint UNSIGNED NOT NULL,
  `descripcion` varchar(600) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` double(8,2) NOT NULL,
  `monto` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ingreso_detalles`
--

INSERT INTO `ingreso_detalles` (`id`, `ingreso_id`, `concepto_id`, `descripcion`, `cantidad`, `monto`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'DESC. DETALLE', 100.00, 100000.00, '2024-06-20 15:29:23', '2024-06-20 15:29:23'),
(2, 2, 1, 'DESC', 3.00, 3500.00, '2024-06-24 18:00:43', '2024-06-24 18:00:43'),
(3, 2, 5, '', 10.00, 9400.00, '2024-06-24 18:00:43', '2024-06-24 18:00:43'),
(4, 3, 1, 'DESC', 300.00, 15000.00, '2024-06-26 14:27:02', '2024-06-26 14:27:02'),
(5, 3, 5, 'DESC', 200.00, 10000.00, '2024-06-26 14:27:02', '2024-06-26 14:27:02'),
(6, 4, 5, 'DES', 10.00, 2500.70, '2024-06-26 14:39:25', '2024-06-26 14:39:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_productos`
--

CREATE TABLE `ingreso_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `tipo_producto_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` int NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fecha_ingreso` date NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ingreso_productos`
--

INSERT INTO `ingreso_productos` (`id`, `tipo_producto_id`, `producto_id`, `cantidad`, `descripcion`, `fecha_ingreso`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(4, 2, 2, 200, '', '2024-08-28', '2024-08-28', '2024-08-28 12:58:06', '2024-08-28 12:58:06'),
(5, 2, 3, 300, '', '2024-08-28', '2024-08-28', '2024-08-28 13:44:08', '2024-08-28 13:44:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex_productos`
--

CREATE TABLE `kardex_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `tipo_registro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registro_id` bigint UNSIGNED DEFAULT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `detalle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(24,2) DEFAULT NULL,
  `tipo_is` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad_ingreso` double DEFAULT NULL,
  `cantidad_salida` double DEFAULT NULL,
  `cantidad_saldo` double NOT NULL,
  `cu` decimal(24,2) NOT NULL,
  `monto_ingreso` decimal(24,2) DEFAULT NULL,
  `monto_salida` decimal(24,2) DEFAULT NULL,
  `monto_saldo` decimal(24,2) NOT NULL,
  `fecha` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `kardex_productos`
--

INSERT INTO `kardex_productos` (`id`, `tipo_registro`, `registro_id`, `producto_id`, `detalle`, `precio`, `tipo_is`, `cantidad_ingreso`, `cantidad_salida`, `cantidad_saldo`, `cu`, `monto_ingreso`, `monto_salida`, `monto_saldo`, `fecha`, `created_at`, `updated_at`) VALUES
(4, 'INGRESO', 4, 2, 'VALOR INICIAL', 300.00, 'INGRESO', 200, NULL, 200, 300.00, 60000.00, NULL, 60000.00, '2024-08-28', '2024-08-28 12:58:06', '2025-03-29 19:37:38'),
(7, 'INGRESO', 5, 3, 'VALOR INICIAL', 322.00, 'INGRESO', 300, NULL, 300, 322.00, 96600.00, NULL, 96600.00, '2024-08-28', '2024-08-28 13:44:08', '2024-08-28 13:44:08'),
(8, 'SALIDA', 3, 3, 'SALIDA DE PRUEBA', 322.00, 'EGRESO', NULL, 3, 297, 322.00, NULL, 966.00, 95634.00, '2024-08-28', '2024-08-28 13:44:22', '2024-08-28 13:44:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_08_27_151737_create_tipo_productos_table', 1),
(2, '2024_08_27_151753_create_productos_table', 2),
(3, '2024_08_27_151754_create_ingreso_productos_table', 3),
(4, '2024_08_27_151755_create_salida_productos_table', 4),
(5, '2024_08_27_151756_create_kardex_productos_table', 5),
(6, '2024_02_02_205431_create_historial_accions_table', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint UNSIGNED NOT NULL,
  `tipo_producto_id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_actual` double(8,2) NOT NULL DEFAULT '0.00',
  `unidad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(24,2) NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `tipo_producto_id`, `nombre`, `stock_actual`, `unidad`, `precio`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(2, 2, 'PRODUCTO #1', 200.00, 'UNIDAD', 300.00, '2024-08-27', '2024-08-27 21:00:53', '2025-03-29 19:37:38'),
(3, 2, 'PRODUCTO #2', 297.00, 'UNIDAD', 322.00, '2024-08-28', '2024-08-28 13:11:27', '2024-08-28 13:44:22'),
(4, 2, 'PRODUCTO #3', 0.00, 'UNIDAD', 400.00, '2024-08-28', '2024-08-28 13:11:36', '2024-08-28 13:11:36'),
(5, 3, 'PRODUCTO #4', 0.00, 'UNIDAD', 322.00, '2024-08-28', '2024-08-28 13:11:44', '2024-08-28 13:11:44'),
(6, 3, 'PRODUCTO #5', 0.00, 'UNIDAD', 211.00, '2024-08-28', '2024-08-28 13:11:54', '2024-08-28 13:11:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida_productos`
--

CREATE TABLE `salida_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `tipo_producto_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` int NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fecha_salida` date NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `salida_productos`
--

INSERT INTO `salida_productos` (`id`, `tipo_producto_id`, `producto_id`, `cantidad`, `descripcion`, `fecha_salida`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(3, 2, 3, 3, 'SALIDA DE PRUEBA', '2024-08-28', '2024-08-28', '2024-08-28 13:44:22', '2024-08-28 13:44:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_productos`
--

CREATE TABLE `tipo_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_productos`
--

INSERT INTO `tipo_productos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(2, 'TIPO DE PRODUCTO #1', '2024-08-27 19:32:23', '2024-08-27 19:32:23'),
(3, 'TIPO DE PRODUCTO #2', '2024-08-27 19:32:29', '2024-08-27 19:32:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `materno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acceso` int NOT NULL DEFAULT '1',
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `password`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `dir`, `email`, `fono`, `tipo`, `foto`, `acceso`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 'admin', NULL, NULL, '0', '', '', 'admin@gmail.com', '', 'GERENTE', NULL, 1, '2024-01-31', NULL, NULL),
(2, 'JPERES', '$2y$12$6ZebcD12TFgENacNcyLmC.buP0KZlyW7gqfG.wD5/fI6T7JzJ4X6S', 'JUAN', 'PERES', 'MAMANI', '1111', 'LP', 'LOS OLIVOS', 'JUAN@GMAIL.COM', '77777777', 'OPERADOR', '1719251944_JPERES.jpg', 1, '2024-06-24', '2024-06-24 17:59:04', '2024-06-24 17:59:04');

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
  ADD KEY `egreso_detalles_concepto_id_foreign` (`concepto_id`),
  ADD KEY `egreso_id` (`egreso_id`);

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
  ADD KEY `ingreso_detalles_concepto_id_foreign` (`concepto_id`),
  ADD KEY `ingreso_id` (`ingreso_id`);

--
-- Indices de la tabla `ingreso_productos`
--
ALTER TABLE `ingreso_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingreso_productos_tipo_producto_id_foreign` (`tipo_producto_id`),
  ADD KEY `ingreso_productos_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `kardex_productos`
--
ALTER TABLE `kardex_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kardex_productos_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `salida_productos`
--
ALTER TABLE `salida_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salida_productos_tipo_producto_id_foreign` (`tipo_producto_id`),
  ADD KEY `salida_productos_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `tipo_productos`
--
ALTER TABLE `tipo_productos`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `conceptos`
--
ALTER TABLE `conceptos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `egresos`
--
ALTER TABLE `egresos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `egreso_detalles`
--
ALTER TABLE `egreso_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `ingreso_detalles`
--
ALTER TABLE `ingreso_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `ingreso_productos`
--
ALTER TABLE `ingreso_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `kardex_productos`
--
ALTER TABLE `kardex_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `salida_productos`
--
ALTER TABLE `salida_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_productos`
--
ALTER TABLE `tipo_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `egreso_detalles_concepto_id_foreign` FOREIGN KEY (`concepto_id`) REFERENCES `conceptos` (`id`),
  ADD CONSTRAINT `egreso_detalles_ibfk_1` FOREIGN KEY (`egreso_id`) REFERENCES `egresos` (`id`);

--
-- Filtros para la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `ingresos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `ingreso_detalles`
--
ALTER TABLE `ingreso_detalles`
  ADD CONSTRAINT `ingreso_detalles_concepto_id_foreign` FOREIGN KEY (`concepto_id`) REFERENCES `conceptos` (`id`),
  ADD CONSTRAINT `ingreso_detalles_ibfk_1` FOREIGN KEY (`ingreso_id`) REFERENCES `ingresos` (`id`);

--
-- Filtros para la tabla `ingreso_productos`
--
ALTER TABLE `ingreso_productos`
  ADD CONSTRAINT `ingreso_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `ingreso_productos_tipo_producto_id_foreign` FOREIGN KEY (`tipo_producto_id`) REFERENCES `tipo_productos` (`id`);

--
-- Filtros para la tabla `kardex_productos`
--
ALTER TABLE `kardex_productos`
  ADD CONSTRAINT `kardex_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `salida_productos`
--
ALTER TABLE `salida_productos`
  ADD CONSTRAINT `salida_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `salida_productos_tipo_producto_id_foreign` FOREIGN KEY (`tipo_producto_id`) REFERENCES `tipo_productos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
