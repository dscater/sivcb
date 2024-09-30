-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 30-09-2024 a las 17:01:23
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
-- Base de datos: `sivcb_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacen_productos`
--

CREATE TABLE `almacen_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `stock_actual` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `almacen_productos`
--

INSERT INTO `almacen_productos` (`id`, `producto_id`, `stock_actual`, `created_at`, `updated_at`) VALUES
(1, 1, 10, '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(2, 2, 5, '2024-09-28 19:22:04', '2024-09-30 20:30:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'CATEGORIA  #1', '2024-09-24 20:21:47', '2024-09-24 20:21:47'),
(2, 'CATEGORIA #2', '2024-09-25 21:08:52', '2024-09-25 21:08:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `ci`, `fono`, `correo`, `dir`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'FELIPE GONZALES', '1122', '77777777', 'FELIPE@GMAIL.COM', 'ZONA LOS OLIVOS C.11 #322', '2024-09-30', '2024-09-30 20:47:02', '2024-09-30 20:47:02'),
(2, 'JESUS RAMIRES', '0', '78787878', '', '', '2024-09-30', '2024-09-30 20:48:50', '2024-09-30 20:48:50'),
(3, 'MARIA MAMANI', '3333', '67676767', '', '', '2024-09-30', '2024-09-30 20:52:02', '2024-09-30 20:52:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actividad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `razon_social`, `nit`, `ciudad`, `dir`, `fono`, `web`, `actividad`, `correo`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'SIVCB', 'SC', 'SIVCB S.A.', NULL, 'LA PAZ', 'ZONA LOS OLIVOS', '77777777', 'SIVCB.COM', 'ACTIVIDAD', 'SIVCB@GMAIL.COM', '1725897866_1.jpg', NULL, '2024-09-23 19:29:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distribucion_detalles`
--

CREATE TABLE `distribucion_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `distribucion_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distribucion_productos`
--

CREATE TABLE `distribucion_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `fecha_registro` date NOT NULL,
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
(1, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN TIPO DE INGRESO', 'id: 1<br/>nombre: TIPO INGRESO #1<br/>descripcion: <br/>created_at: 2024-09-27 17:13:31<br/>updated_at: 2024-09-27 17:13:31<br/>', NULL, 'TIPO DE INGRESOS', '2024-09-27', '17:13:31', '2024-09-27 21:13:31', '2024-09-27 21:13:31'),
(2, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN TIPO DE INGRESO', 'id: 2<br/>nombre: TIPO INGRESO #2<br/>descripcion: <br/>created_at: 2024-09-27 17:13:37<br/>updated_at: 2024-09-27 17:13:37<br/>', NULL, 'TIPO DE INGRESOS', '2024-09-27', '17:13:37', '2024-09-27 21:13:37', '2024-09-27 21:13:37'),
(3, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN INGRESO DE PRODUCTO', 'id: 1<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3400<br/>cantidad: 10<br/>tipo_ingreso_id: 1<br/>descripcion: PRIMER INGRESO ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-09-28<br/>fecha_registro: 2024-09-28<br/>created_at: 2024-09-28 15:21:31<br/>updated_at: 2024-09-28 15:21:31<br/>', NULL, 'INGRESO DE PRODUCTOS', '2024-09-28', '15:21:31', '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(4, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN INGRESO DE PRODUCTO', 'id: 2<br/>producto_id: 2<br/>proveedor_id: 2<br/>precio: 35400<br/>cantidad: 6<br/>tipo_ingreso_id: 1<br/>descripcion: DESC. PRIMER INGRESO PROD. 2 ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-09-28<br/>fecha_registro: 2024-09-28<br/>created_at: 2024-09-28 15:22:04<br/>updated_at: 2024-09-28 15:22:04<br/>', NULL, 'INGRESO DE PRODUCTOS', '2024-09-28', '15:22:04', '2024-09-28 19:22:04', '2024-09-28 19:22:04'),
(5, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN INGRESO DE PRODUCTO', 'id: 5<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 1400<br/>cantidad: 5<br/>tipo_ingreso_id: 2<br/>descripcion: INGRESO DIRECTO SUCURSAL PROD. 1<br/>lugar: SUCURSAL<br/>sucursal_id: 1<br/>fecha_ingreso: 2024-09-28<br/>fecha_registro: 2024-09-28<br/>created_at: 2024-09-28 15:24:03<br/>updated_at: 2024-09-28 15:24:03<br/>', NULL, 'INGRESO DE PRODUCTOS', '2024-09-28', '15:24:03', '2024-09-28 19:24:03', '2024-09-28 19:24:03'),
(6, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN INGRESO DE PRODUCTO', 'id: 6<br/>producto_id: 2<br/>proveedor_id: 2<br/>precio: 1400<br/>cantidad: 5<br/>tipo_ingreso_id: 2<br/>descripcion: INGRESO DIRECTO A SUCURSAL 2 PROD 1<br/>lugar: SUCURSAL<br/>sucursal_id: 2<br/>fecha_ingreso: 2024-09-28<br/>fecha_registro: 2024-09-28<br/>created_at: 2024-09-28 15:24:40<br/>updated_at: 2024-09-28 15:24:40<br/>', NULL, 'INGRESO DE PRODUCTOS', '2024-09-28', '15:24:40', '2024-09-28 19:24:40', '2024-09-28 19:24:40'),
(7, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 6<br/>producto_id: 2<br/>proveedor_id: 2<br/>precio: 1400.00<br/>cantidad: 5<br/>tipo_ingreso_id: 2<br/>descripcion: INGRESO DIRECTO A SUCURSAL 2 PROD 1<br/>lugar: SUCURSAL<br/>sucursal_id: 2<br/>fecha_ingreso: 2024-09-28<br/>fecha_registro: 2024-09-28<br/>created_at: 2024-09-28 15:24:40<br/>updated_at: 2024-09-28 15:24:40<br/>', 'id: 6<br/>producto_id: 2<br/>proveedor_id: 2<br/>precio: 1400.00<br/>cantidad: 5<br/>tipo_ingreso_id: 2<br/>descripcion: INGRESO DIRECTO A SUCURSAL 2 PROD 2<br/>lugar: SUCURSAL<br/>sucursal_id: 2<br/>fecha_ingreso: 2024-09-28<br/>fecha_registro: 2024-09-28<br/>created_at: 2024-09-28 15:24:40<br/>updated_at: 2024-09-28 15:24:52<br/>', 'INGRESO DE PRODUCTOS', '2024-09-28', '15:24:52', '2024-09-28 19:24:52', '2024-09-28 19:24:52'),
(8, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SALIDA DE PRODUCTO', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:18:55<br/>', NULL, 'SALIDA DE PRODUCTOS', '2024-09-30', '16:18:55', '2024-09-30 20:18:55', '2024-09-30 20:18:55'),
(9, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA SALIDA DE PRODUCTO', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:18:55<br/>', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACENXD<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:23:04<br/>', 'SALIDA DE PRODUCTOS', '2024-09-30', '16:23:04', '2024-09-30 20:23:04', '2024-09-30 20:23:04'),
(10, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA SALIDA DE PRODUCTO', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACENXD<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:23:04<br/>', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACENXDE<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:23:27<br/>', 'SALIDA DE PRODUCTOS', '2024-09-30', '16:23:27', '2024-09-30 20:23:27', '2024-09-30 20:23:27'),
(11, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA SALIDA DE PRODUCTO', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACENXDE<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:23:27<br/>', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACENXDE<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:23:27<br/>', 'SALIDA DE PRODUCTOS', '2024-09-30', '16:24:20', '2024-09-30 20:24:20', '2024-09-30 20:24:20'),
(12, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA SALIDA DE PRODUCTO', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACENXDE<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:23:27<br/>', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACENXDE<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:23:27<br/>', 'SALIDA DE PRODUCTOS', '2024-09-30', '16:26:09', '2024-09-30 20:26:09', '2024-09-30 20:26:09'),
(13, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA SALIDA DE PRODUCTO', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACENXDE<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:23:27<br/>', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACENXDE<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:23:27<br/>', 'SALIDA DE PRODUCTOS', '2024-09-30', '16:26:47', '2024-09-30 20:26:47', '2024-09-30 20:26:47'),
(14, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA SALIDA DE PRODUCTO', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACENXDE<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:23:27<br/>', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 2<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACENXDEEE<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:26:56<br/>', 'SALIDA DE PRODUCTOS', '2024-09-30', '16:26:56', '2024-09-30 20:26:56', '2024-09-30 20:26:56'),
(15, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA SALIDA DE PRODUCTO', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 2<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACENXDEEE<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:26:56<br/>', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:27:02<br/>', 'SALIDA DE PRODUCTOS', '2024-09-30', '16:27:02', '2024-09-30 20:27:02', '2024-09-30 20:27:02'),
(16, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA SALIDA DE PRODUCTO', 'id: 2<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA PROD 2 ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:18:55<br/>updated_at: 2024-09-30 16:27:02<br/>', NULL, 'SALIDA DE PRODUCTOS', '2024-09-30', '16:28:06', '2024-09-30 20:28:06', '2024-09-30 20:28:06'),
(17, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SALIDA DE PRODUCTO', 'id: 1<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: SALIDA ALMACEN PRUEBA<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:28:34<br/>updated_at: 2024-09-30 16:28:34<br/>', NULL, 'SALIDA DE PRODUCTOS', '2024-09-30', '16:28:34', '2024-09-30 20:28:34', '2024-09-30 20:28:34'),
(18, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA SALIDA DE PRODUCTO', 'id: 1<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: SALIDA ALMACEN PRUEBA<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:28:34<br/>updated_at: 2024-09-30 16:28:34<br/>', NULL, 'SALIDA DE PRODUCTOS', '2024-09-30', '16:29:41', '2024-09-30 20:29:41', '2024-09-30 20:29:41'),
(19, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SALIDA DE PRODUCTO', 'id: 1<br/>producto_id: 2<br/>cantidad: 1<br/>fecha_salida: 2024-09-30<br/>tipo_salida_id: 1<br/>descripcion: PRUEBA SALIDA DE ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:30:21<br/>updated_at: 2024-09-30 16:30:21<br/>', NULL, 'SALIDA DE PRODUCTOS', '2024-09-30', '16:30:21', '2024-09-30 20:30:21', '2024-09-30 20:30:21'),
(20, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN CLIENTE', 'id: 1<br/>nombre: FELIPE GONZALES<br/>ci: 1122<br/>fono: 77777777<br/>correo: FELIPE@GMAIL.COM<br/>dir: ZONA LOS OLIVOS C.11 #322<br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:47:02<br/>updated_at: 2024-09-30 16:47:02<br/>', NULL, 'CLIENTES', '2024-09-30', '16:47:02', '2024-09-30 20:47:02', '2024-09-30 20:47:02'),
(21, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN CLIENTE', 'id: 1<br/>nombre: FELIPE GONZALES<br/>ci: 1122<br/>fono: 77777777<br/>correo: FELIPE@GMAIL.COM<br/>dir: ZONA LOS OLIVOS C.11 #322<br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:47:02<br/>updated_at: 2024-09-30 16:47:02<br/>', 'id: 1<br/>nombre: FELIPE GONZALES<br/>ci: 1122<br/>fono: 77777777<br/>correo: FELIPE@GMAIL.COM<br/>dir: ZONA LOS OLIVOS C.11 #322<br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:47:02<br/>updated_at: 2024-09-30 16:47:02<br/>', 'CLIENTES', '2024-09-30', '16:48:14', '2024-09-30 20:48:14', '2024-09-30 20:48:14'),
(22, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN CLIENTE', 'id: 1<br/>nombre: FELIPE GONZALES<br/>ci: 1122<br/>fono: 77777777<br/>correo: FELIPE@GMAIL.COM<br/>dir: ZONA LOS OLIVOS C.11 #322<br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:47:02<br/>updated_at: 2024-09-30 16:47:02<br/>', 'id: 1<br/>nombre: FELIPE GONZALES<br/>ci: 1122<br/>fono: 77777777<br/>correo: FELIPE@GMAIL.COM<br/>dir: ZONA LOS OLIVOS C.11 #322<br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:47:02<br/>updated_at: 2024-09-30 16:47:02<br/>', 'CLIENTES', '2024-09-30', '16:48:35', '2024-09-30 20:48:35', '2024-09-30 20:48:35'),
(23, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN CLIENTE', 'id: 2<br/>nombre: JESUS RAMIRES<br/>ci: 0<br/>fono: 78787878<br/>correo: <br/>dir: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:48:50<br/>updated_at: 2024-09-30 16:48:50<br/>', NULL, 'CLIENTES', '2024-09-30', '16:48:50', '2024-09-30 20:48:50', '2024-09-30 20:48:50'),
(24, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN CLIENTE', 'id: 3<br/>nombre: MARIA MAMANI<br/>ci: 0<br/>fono: <br/>correo: <br/>dir: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:50:48<br/>updated_at: 2024-09-30 16:50:48<br/>', NULL, 'CLIENTES', '2024-09-30', '16:50:48', '2024-09-30 20:50:48', '2024-09-30 20:50:48'),
(25, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN CLIENTE', 'id: 3<br/>nombre: MARIA MAMANI<br/>ci: 0<br/>fono: <br/>correo: <br/>dir: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:50:48<br/>updated_at: 2024-09-30 16:50:48<br/>', 'id: 3<br/>nombre: MARIA MAMANI<br/>ci: 0<br/>fono: <br/>correo: <br/>dir: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:50:48<br/>updated_at: 2024-09-30 16:50:48<br/>', 'CLIENTES', '2024-09-30', '16:51:29', '2024-09-30 20:51:29', '2024-09-30 20:51:29'),
(26, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN CLIENTE', 'id: 3<br/>nombre: MARIA MAMANI<br/>ci: 0<br/>fono: <br/>correo: <br/>dir: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:50:48<br/>updated_at: 2024-09-30 16:50:48<br/>', 'id: 3<br/>nombre: MARIA MAMANI<br/>ci: 0<br/>fono: <br/>correo: <br/>dir: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:50:48<br/>updated_at: 2024-09-30 16:50:48<br/>', 'CLIENTES', '2024-09-30', '16:51:39', '2024-09-30 20:51:39', '2024-09-30 20:51:39'),
(27, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN CLIENTE', 'id: 3<br/>nombre: MARIA MAMANI<br/>ci: 0<br/>fono: <br/>correo: <br/>dir: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:50:48<br/>updated_at: 2024-09-30 16:50:48<br/>', NULL, 'CLIENTES', '2024-09-30', '16:51:44', '2024-09-30 20:51:44', '2024-09-30 20:51:44'),
(28, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN CLIENTE', 'id: 3<br/>nombre: MARIA MAMANI<br/>ci: 3333<br/>fono: 67676767<br/>correo: <br/>dir: <br/>fecha_registro: 2024-09-30<br/>created_at: 2024-09-30 16:52:02<br/>updated_at: 2024-09-30 16:52:02<br/>', NULL, 'CLIENTES', '2024-09-30', '16:52:02', '2024-09-30 20:52:02', '2024-09-30 20:52:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_productos`
--

CREATE TABLE `ingreso_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `proveedor_id` bigint UNSIGNED NOT NULL,
  `precio` decimal(24,2) NOT NULL,
  `cantidad` double NOT NULL,
  `tipo_ingreso_id` bigint UNSIGNED NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lugar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` bigint UNSIGNED DEFAULT NULL,
  `fecha_ingreso` date NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ingreso_productos`
--

INSERT INTO `ingreso_productos` (`id`, `producto_id`, `proveedor_id`, `precio`, `cantidad`, `tipo_ingreso_id`, `descripcion`, `lugar`, `sucursal_id`, `fecha_ingreso`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3400.00, 10, 1, 'PRIMER INGRESO ALMACEN', 'ALMACÉN', NULL, '2024-09-28', '2024-09-28', '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(2, 2, 2, 35400.00, 6, 1, 'DESC. PRIMER INGRESO PROD. 2 ALMACEN', 'ALMACÉN', NULL, '2024-09-28', '2024-09-28', '2024-09-28 19:22:04', '2024-09-28 19:22:04'),
(5, 1, 1, 1400.00, 5, 2, 'INGRESO DIRECTO SUCURSAL PROD. 1', 'SUCURSAL', 1, '2024-09-28', '2024-09-28', '2024-09-28 19:24:03', '2024-09-28 19:24:03'),
(6, 2, 2, 1400.00, 5, 2, 'INGRESO DIRECTO A SUCURSAL 2 PROD 2', 'SUCURSAL', 2, '2024-09-28', '2024-09-28', '2024-09-28 19:24:40', '2024-09-28 19:24:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex_productos`
--

CREATE TABLE `kardex_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `lugar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sucursal_id` bigint UNSIGNED DEFAULT NULL,
  `tipo_registro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registro_id` bigint UNSIGNED DEFAULT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `detalle` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(24,2) DEFAULT NULL,
  `tipo_is` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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

INSERT INTO `kardex_productos` (`id`, `lugar`, `sucursal_id`, `tipo_registro`, `registro_id`, `producto_id`, `detalle`, `precio`, `tipo_is`, `cantidad_ingreso`, `cantidad_salida`, `cantidad_saldo`, `cu`, `monto_ingreso`, `monto_salida`, `monto_saldo`, `fecha`, `created_at`, `updated_at`) VALUES
(1, 'ALMACÉN', NULL, 'INGRESO', 1, 1, 'VALOR INICIAL', 300.00, 'INGRESO', 10, NULL, 10, 300.00, 3000.00, NULL, 3000.00, '2024-09-28', '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(2, 'ALMACÉN', NULL, 'INGRESO', 2, 2, 'VALOR INICIAL', 150.00, 'INGRESO', 6, NULL, 6, 150.00, 900.00, NULL, 900.00, '2024-09-28', '2024-09-28 19:22:04', '2024-09-30 20:29:41'),
(3, 'SUCURSAL', 1, 'INGRESO', 5, 1, 'VALOR INICIAL', 300.00, 'INGRESO', 5, NULL, 5, 300.00, 1500.00, NULL, 1500.00, '2024-09-28', '2024-09-28 19:24:03', '2024-09-28 19:24:03'),
(4, 'SUCURSAL', 2, 'INGRESO', 6, 2, 'VALOR INICIAL', 150.00, 'INGRESO', 5, NULL, 5, 150.00, 750.00, NULL, 750.00, '2024-09-28', '2024-09-28 19:24:40', '2024-09-28 19:24:40'),
(5, 'ALMACÉN', NULL, 'SALIDA', 1, 2, 'PRUEBA SALIDA DE ALMACEN', 150.00, 'EGRESO', NULL, 1, 5, 150.00, NULL, 150.00, 750.00, '2024-09-30', '2024-09-30 20:30:21', '2024-09-30 20:30:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'MARCA #1', '2024-09-25 20:25:33', '2024-09-25 20:25:33'),
(2, 'MARCA #2', '2024-09-25 20:25:38', '2024-09-25 20:25:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2024_01_31_165641_create_configuracions_table', 1),
(3, '2024_02_02_205431_create_historial_accions_table', 1),
(4, '2024_09_23_134352_create_sucursals_table', 1),
(5, '2024_09_23_134359_create_proveedors_table', 1),
(6, '2024_09_23_134427_create_categorias_table', 1),
(7, '2024_09_23_134438_create_marcas_table', 1),
(8, '2024_09_23_134447_create_unidad_medidas_table', 1),
(9, '2024_09_23_134448_create_productos_table', 1),
(10, '2024_09_23_134615_create_tipo_ingresos_table', 1),
(11, '2024_09_23_134616_create_ingreso_productos_table', 1),
(12, '2024_09_23_134842_create_tipo_salidas_table', 1),
(13, '2024_09_23_134843_create_salida_productos_table', 1),
(14, '2024_09_23_135010_create_clientes_table', 1),
(15, '2024_09_23_135135_create_ventas_table', 1),
(16, '2024_09_23_135322_create_venta_detalles_table', 1),
(17, '2024_09_23_135323_create_producto_barras_table', 1),
(18, '2024_09_23_135354_create_distribucion_productos_table', 1),
(19, '2024_09_23_135358_create_distribucion_detalles_table', 1),
(20, '2024_09_23_135420_create_sucursal_productos_table', 1),
(21, '2024_09_23_143923_create_kadexs_table', 1),
(22, '2024_09_26_143104_create_almacen_productos_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `marca_id` bigint UNSIGNED NOT NULL,
  `unidad_medida_id` bigint UNSIGNED NOT NULL,
  `precio` decimal(24,2) NOT NULL,
  `stock_min` double NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `categoria_id`, `marca_id`, `unidad_medida_id`, `precio`, `stock_min`, `imagen`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'PRODUCTO #1', 1, 1, 1, 300.00, 15, '1727284361_1.png', '2024-09-25', '2024-09-25 21:12:41', '2024-09-25 21:12:41'),
(2, 'PRODUCTO #2', 1, 2, 1, 150.00, 3, NULL, '2024-09-26', '2024-09-26 18:33:36', '2024-09-26 18:33:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_barras`
--

CREATE TABLE `producto_barras` (
  `id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lugar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` bigint UNSIGNED DEFAULT NULL,
  `ingreso_id` bigint UNSIGNED DEFAULT NULL,
  `salida_id` bigint UNSIGNED DEFAULT NULL,
  `venta_detalle_id` bigint UNSIGNED DEFAULT NULL,
  `distribucion_detalle_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `producto_barras`
--

INSERT INTO `producto_barras` (`id`, `producto_id`, `codigo`, `lugar`, `sucursal_id`, `ingreso_id`, `salida_id`, `venta_detalle_id`, `distribucion_detalle_id`, `created_at`, `updated_at`) VALUES
(1, 1, '111', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(2, 1, '112', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(3, 1, '113', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(4, 1, '114', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(5, 1, '115', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(6, 1, '116', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(7, 1, '117', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(8, 1, '118', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(9, 1, '119', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(10, 1, '110', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, '2024-09-28 19:21:31', '2024-09-28 19:21:31'),
(11, 2, '211', 'ALMACÉN', NULL, 2, NULL, NULL, NULL, '2024-09-28 19:22:04', '2024-09-28 19:22:04'),
(12, 2, '212', 'ALMACÉN', NULL, 2, NULL, NULL, NULL, '2024-09-28 19:22:04', '2024-09-28 19:22:04'),
(13, 2, '213', 'ALMACÉN', NULL, 2, NULL, NULL, NULL, '2024-09-28 19:22:04', '2024-09-28 19:22:04'),
(14, 2, '214', 'ALMACÉN', NULL, 2, NULL, NULL, NULL, '2024-09-28 19:22:04', '2024-09-28 19:22:04'),
(15, 2, '215', 'ALMACÉN', NULL, 2, 1, NULL, NULL, '2024-09-28 19:22:04', '2024-09-30 20:30:21'),
(16, 2, '216', 'ALMACÉN', NULL, 2, NULL, NULL, NULL, '2024-09-28 19:22:04', '2024-09-28 19:22:04'),
(18, 1, '1111', 'SUCURSAL', 1, 5, NULL, NULL, NULL, '2024-09-28 19:24:03', '2024-09-28 19:24:03'),
(19, 1, '1112', 'SUCURSAL', 1, 5, NULL, NULL, NULL, '2024-09-28 19:24:03', '2024-09-28 19:24:03'),
(20, 1, '1113', 'SUCURSAL', 1, 5, NULL, NULL, NULL, '2024-09-28 19:24:03', '2024-09-28 19:24:03'),
(21, 1, '1114', 'SUCURSAL', 1, 5, NULL, NULL, NULL, '2024-09-28 19:24:03', '2024-09-28 19:24:03'),
(22, 1, '1115', 'SUCURSAL', 1, 5, NULL, NULL, NULL, '2024-09-28 19:24:03', '2024-09-28 19:24:03'),
(23, 2, '1116', 'SUCURSAL', 2, 6, NULL, NULL, NULL, '2024-09-28 19:24:40', '2024-09-28 19:24:40'),
(24, 2, '1117', 'SUCURSAL', 2, 6, NULL, NULL, NULL, '2024-09-28 19:24:40', '2024-09-28 19:24:40'),
(25, 2, '1118', 'SUCURSAL', 2, 6, NULL, NULL, NULL, '2024-09-28 19:24:40', '2024-09-28 19:24:40'),
(26, 2, '1119', 'SUCURSAL', 2, 6, NULL, NULL, NULL, '2024-09-28 19:24:40', '2024-09-28 19:24:40'),
(27, 2, '1110', 'SUCURSAL', 2, 6, NULL, NULL, NULL, '2024-09-28 19:24:40', '2024-09-28 19:24:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedors`
--

CREATE TABLE `proveedors` (
  `id` bigint UNSIGNED NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_contacto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proveedors`
--

INSERT INTO `proveedors` (`id`, `razon_social`, `nit`, `dir`, `fono`, `nombre_contacto`, `descripcion`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'PROVEEDOR 1 S.A.', '1111111111', 'ZONA LOS MANZANOS C. 1 #44444', '222222', 'EDUARDO ALVARES', 'DESC. PROVEEDOR 1', '2024-09-24', '2024-09-24 20:11:54', '2024-09-24 20:11:54'),
(2, 'PROVEEDOR 2 S.R.L.', '222222222222', 'ZONA LOS HEROES C. 3 #22222', '2727277', 'JORGE PAREDES', '', '2024-09-24', '2024-09-24 20:12:29', '2024-09-24 20:12:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida_productos`
--

CREATE TABLE `salida_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `fecha_salida` date NOT NULL,
  `tipo_salida_id` bigint UNSIGNED NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lugar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` bigint UNSIGNED DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `salida_productos`
--

INSERT INTO `salida_productos` (`id`, `producto_id`, `cantidad`, `fecha_salida`, `tipo_salida_id`, `descripcion`, `lugar`, `sucursal_id`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2024-09-30', 1, 'PRUEBA SALIDA DE ALMACEN', 'ALMACÉN', NULL, '2024-09-30', '2024-09-30 20:30:21', '2024-09-30 20:30:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursals`
--

CREATE TABLE `sucursals` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sucursals`
--

INSERT INTO `sucursals` (`id`, `nombre`, `fono`, `dir`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'SUCURSAL #1', '77777777 - 66666666', 'ZONA LOS PEDREGALES C. 3 #4444', '2024-09-24', '2024-09-24 19:30:36', '2024-09-24 19:30:36'),
(2, 'SUCURSAL #2', '78787878 - 67676767', 'ZONA LOS OLIVOS C. A #2222', '2024-09-24', '2024-09-24 19:30:59', '2024-09-24 19:30:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal_productos`
--

CREATE TABLE `sucursal_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `stock_actual` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sucursal_productos`
--

INSERT INTO `sucursal_productos` (`id`, `producto_id`, `sucursal_id`, `stock_actual`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, '2024-09-28 19:24:03', '2024-09-28 19:24:03'),
(2, 2, 2, 5, '2024-09-28 19:24:40', '2024-09-28 19:24:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_ingresos`
--

CREATE TABLE `tipo_ingresos` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_ingresos`
--

INSERT INTO `tipo_ingresos` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'TIPO INGRESO #1', '', '2024-09-27 21:13:31', '2024-09-27 21:13:31'),
(2, 'TIPO INGRESO #2', '', '2024-09-27 21:13:37', '2024-09-27 21:13:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_salidas`
--

CREATE TABLE `tipo_salidas` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_salidas`
--

INSERT INTO `tipo_salidas` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'TIPO SALIDA #1', 'DESC. SALIDA 1', '2024-09-26 18:56:30', '2024-09-26 18:56:30'),
(2, 'TIPO SALIDA #2', '', '2024-09-26 18:56:37', '2024-09-26 18:56:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_medidas`
--

CREATE TABLE `unidad_medidas` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `unidad_medidas`
--

INSERT INTO `unidad_medidas` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'UNIDAD #1', '2024-09-25 20:28:57', '2024-09-25 20:28:57'),
(2, 'UNIDAD #2', '2024-09-25 20:29:02', '2024-09-25 20:29:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `acceso` int NOT NULL,
  `sucursal_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `dir`, `email`, `fono`, `password`, `tipo`, `foto`, `fecha_registro`, `acceso`, `sucursal_id`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin', NULL, '0', '', NULL, NULL, '', '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 'ADMINISTRADOR', NULL, '2024-09-23', 1, NULL, NULL, NULL),
(2, 'JPERES', 'JUAN', 'PERES', 'MAMANI', '1111', 'LP', 'ZONA LOS OLIVOS', 'JUAN@GMAIL.COM', '77777777', '$2y$12$3QHG0syHSXFGDhyC3x7bqOfm.Rdms.qawkgu01540bhCLdNMlwzLm', 'SUPERVISOR DE SUCURSAL', '1727192573_JPERES.jpg', '2024-09-24', 1, 1, '2024-09-24 19:42:52', '2024-09-24 19:42:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `descuento` double NOT NULL,
  `total_final` decimal(24,2) NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_detalles`
--

CREATE TABLE `venta_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `venta_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `precio` decimal(8,2) NOT NULL,
  `subtotal` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `almacen_productos`
--
ALTER TABLE `almacen_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `almacen_productos_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categorias_nombre_unique` (`nombre`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `distribucion_detalles`
--
ALTER TABLE `distribucion_detalles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `distribucion_productos`
--
ALTER TABLE `distribucion_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `distribucion_productos_sucursal_id_foreign` (`sucursal_id`);

--
-- Indices de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ingreso_productos`
--
ALTER TABLE `ingreso_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingreso_productos_producto_id_foreign` (`producto_id`),
  ADD KEY `ingreso_productos_proveedor_id_foreign` (`proveedor_id`),
  ADD KEY `ingreso_productos_tipo_ingreso_id_foreign` (`tipo_ingreso_id`),
  ADD KEY `ingreso_productos_sucursal_id_foreign` (`sucursal_id`);

--
-- Indices de la tabla `kardex_productos`
--
ALTER TABLE `kardex_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kadexs_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `marcas_nombre_unique` (`nombre`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `productos_nombre_unique` (`nombre`),
  ADD KEY `productos_categoria_id_foreign` (`categoria_id`),
  ADD KEY `productos_marca_id_foreign` (`marca_id`),
  ADD KEY `productos_unidad_medida_id_foreign` (`unidad_medida_id`);

--
-- Indices de la tabla `producto_barras`
--
ALTER TABLE `producto_barras`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `producto_barras_codigo_unique` (`codigo`),
  ADD KEY `producto_barras_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `proveedors`
--
ALTER TABLE `proveedors`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `salida_productos`
--
ALTER TABLE `salida_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salida_productos_producto_id_foreign` (`producto_id`),
  ADD KEY `salida_productos_tipo_salida_id_foreign` (`tipo_salida_id`);

--
-- Indices de la tabla `sucursals`
--
ALTER TABLE `sucursals`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sucursal_productos`
--
ALTER TABLE `sucursal_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sucursal_productos_producto_id_foreign` (`producto_id`),
  ADD KEY `sucursal_productos_sucursal_id_foreign` (`sucursal_id`);

--
-- Indices de la tabla `tipo_ingresos`
--
ALTER TABLE `tipo_ingresos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo_ingresos_nombre_unique` (`nombre`);

--
-- Indices de la tabla `tipo_salidas`
--
ALTER TABLE `tipo_salidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo_salidas_nombre_unique` (`nombre`);

--
-- Indices de la tabla `unidad_medidas`
--
ALTER TABLE `unidad_medidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unidad_medidas_nombre_unique` (`nombre`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_usuario_unique` (`usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `venta_detalles`
--
ALTER TABLE `venta_detalles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `almacen_productos`
--
ALTER TABLE `almacen_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `distribucion_detalles`
--
ALTER TABLE `distribucion_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `distribucion_productos`
--
ALTER TABLE `distribucion_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `ingreso_productos`
--
ALTER TABLE `ingreso_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `kardex_productos`
--
ALTER TABLE `kardex_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `producto_barras`
--
ALTER TABLE `producto_barras`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `proveedors`
--
ALTER TABLE `proveedors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `salida_productos`
--
ALTER TABLE `salida_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sucursals`
--
ALTER TABLE `sucursals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sucursal_productos`
--
ALTER TABLE `sucursal_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_ingresos`
--
ALTER TABLE `tipo_ingresos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_salidas`
--
ALTER TABLE `tipo_salidas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `unidad_medidas`
--
ALTER TABLE `unidad_medidas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `venta_detalles`
--
ALTER TABLE `venta_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `almacen_productos`
--
ALTER TABLE `almacen_productos`
  ADD CONSTRAINT `almacen_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `distribucion_productos`
--
ALTER TABLE `distribucion_productos`
  ADD CONSTRAINT `distribucion_productos_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`);

--
-- Filtros para la tabla `ingreso_productos`
--
ALTER TABLE `ingreso_productos`
  ADD CONSTRAINT `ingreso_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `ingreso_productos_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedors` (`id`),
  ADD CONSTRAINT `ingreso_productos_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`),
  ADD CONSTRAINT `ingreso_productos_tipo_ingreso_id_foreign` FOREIGN KEY (`tipo_ingreso_id`) REFERENCES `tipo_ingresos` (`id`);

--
-- Filtros para la tabla `kardex_productos`
--
ALTER TABLE `kardex_productos`
  ADD CONSTRAINT `kadexs_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `productos_marca_id_foreign` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`),
  ADD CONSTRAINT `productos_unidad_medida_id_foreign` FOREIGN KEY (`unidad_medida_id`) REFERENCES `unidad_medidas` (`id`);

--
-- Filtros para la tabla `producto_barras`
--
ALTER TABLE `producto_barras`
  ADD CONSTRAINT `producto_barras_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `salida_productos`
--
ALTER TABLE `salida_productos`
  ADD CONSTRAINT `salida_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `salida_productos_tipo_salida_id_foreign` FOREIGN KEY (`tipo_salida_id`) REFERENCES `tipo_salidas` (`id`);

--
-- Filtros para la tabla `sucursal_productos`
--
ALTER TABLE `sucursal_productos`
  ADD CONSTRAINT `sucursal_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `sucursal_productos_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
