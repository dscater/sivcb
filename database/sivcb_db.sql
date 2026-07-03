-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 03-07-2026 a las 18:34:28
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
(1, 1, 205, '2024-10-04 02:36:35', '2025-11-06 23:39:03');

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
  `sucursal_id` bigint UNSIGNED DEFAULT NULL,
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

INSERT INTO `clientes` (`id`, `sucursal_id`, `nombre`, `ci`, `fono`, `correo`, `dir`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, NULL, 'FELIPE GONZALES', '1122', '77777777', 'FELIPE@GMAIL.COM', 'ZONA LOS OLIVOS C.11 #322', '2024-09-30', '2024-09-30 20:47:02', '2024-09-30 20:47:02'),
(2, NULL, 'JESUS RAMIRES', '0', '78787878', '', '', '2024-09-30', '2024-09-30 20:48:50', '2024-09-30 20:48:50'),
(3, NULL, 'MARIA MAMANI', '3333', '67676767', '', '', '2024-09-30', '2024-09-30 20:52:02', '2024-09-30 20:52:02'),
(4, 1, 'RAMIRO CONDORI', '22222', '77777', 'RAMIRO@GMAIL.COM', 'ZONA LOS OLIVOS', '2024-10-03', '2024-10-03 20:54:30', '2024-10-03 20:54:30');

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
-- Estructura de tabla para la tabla `distribucion_productos`
--

CREATE TABLE `distribucion_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `distribucion_productos`
--

INSERT INTO `distribucion_productos` (`id`, `sucursal_id`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(2, 1, '2024-10-03', '2024-10-04 02:43:02', '2024-10-04 02:43:02');

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
(1, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000<br/>cantidad: 5<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2024-10-03 22:36:35<br/>', NULL, 'INGRESO DE PRODUCTOS', '2024-10-03', '22:36:35', '2024-10-04 02:36:35', '2024-10-04 02:36:35'),
(2, 2, 'CREACIÓN', 'EL USUARIO JPERES REGISTRO UN INGRESO DE PRODUCTO', 'id: 2<br/>origen: SUCURSAL<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 4000<br/>cantidad: 5<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL A SUCURSAL<br/>lugar: SUCURSAL<br/>sucursal_id: 1<br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:37:04<br/>updated_at: 2024-10-03 22:37:04<br/>', NULL, 'INGRESO DE PRODUCTOS', '2024-10-03', '22:37:04', '2024-10-04 02:37:04', '2024-10-04 02:37:04'),
(3, 2, 'CREACIÓN', 'EL USUARIO JPERES REGISTRO UN INGRESO DE PRODUCTO', 'id: 3<br/>origen: SUCURSAL<br/>producto_id: 2<br/>proveedor_id: 2<br/>precio: 2330<br/>cantidad: 5<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL PROD. 2 SUCURSAL<br/>lugar: SUCURSAL<br/>sucursal_id: 1<br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:37:31<br/>updated_at: 2024-10-03 22:37:31<br/>', NULL, 'INGRESO DE PRODUCTOS', '2024-10-03', '22:37:31', '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(4, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN DISTRIBUCIÓN DE PRODUCTO', 'id: 1<br/>sucursal_id: 1<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:42:09<br/>updated_at: 2024-10-03 22:42:09<br/>', NULL, 'DISTRIBUCIÓN DE PRODUCTOS', '2024-10-03', '22:42:09', '2024-10-04 02:42:09', '2024-10-04 02:42:09'),
(5, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN DISTRIBUCIÓN DE PRODUCTO', 'id: 1<br/>sucursal_id: 1<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:42:09<br/>updated_at: 2024-10-03 22:42:09<br/>', 'id: 1<br/>sucursal_id: 1<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:42:09<br/>updated_at: 2024-10-03 22:42:09<br/>', 'DISTRIBUCIÓN DE PRODUCTOS', '2024-10-03', '22:42:24', '2024-10-04 02:42:24', '2024-10-04 02:42:24'),
(6, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN DISTRIBUCIÓN DE PRODUCTO', 'id: 1<br/>sucursal_id: 1<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:42:09<br/>updated_at: 2024-10-03 22:42:09<br/>', 'id: 1<br/>sucursal_id: 1<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:42:09<br/>updated_at: 2024-10-03 22:42:09<br/>', 'DISTRIBUCIÓN DE PRODUCTOS', '2024-10-03', '22:42:28', '2024-10-04 02:42:28', '2024-10-04 02:42:28'),
(7, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN DISTRIBUCIÓN DE PRODUCTO', 'id: 1<br/>sucursal_id: 1<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:42:09<br/>updated_at: 2024-10-03 22:42:09<br/>', NULL, 'DISTRIBUCIÓN DE PRODUCTOS', '2024-10-03', '22:42:39', '2024-10-04 02:42:39', '2024-10-04 02:42:39'),
(8, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN DISTRIBUCIÓN DE PRODUCTO', 'id: 2<br/>sucursal_id: 1<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:43:02<br/>updated_at: 2024-10-03 22:43:02<br/>', NULL, 'DISTRIBUCIÓN DE PRODUCTOS', '2024-10-03', '22:43:02', '2024-10-04 02:43:02', '2024-10-04 02:43:02'),
(9, 2, 'CREACIÓN', 'EL USUARIO JPERES REGISTRO UNA VENTA', 'id: 1<br/>sucursal_id: 1<br/>cliente_id: 4<br/>user_id: 2<br/>nit: 22222<br/>total: 300.00<br/>descuento: 0<br/>total_final: 300.00<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:43:20<br/>updated_at: 2024-10-03 22:43:20<br/>', NULL, 'VENTAS', '2024-10-03', '22:43:20', '2024-10-04 02:43:20', '2024-10-04 02:43:20'),
(10, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA VENTA', 'id: 2<br/>sucursal_id: 1<br/>cliente_id: 1<br/>user_id: 1<br/>nit: 1122<br/>total: 1800.00<br/>descuento: 0<br/>total_final: 1800.00<br/>fecha_registro: 2025-07-09<br/>created_at: 2025-07-09 17:12:03<br/>updated_at: 2025-07-09 17:12:03<br/>', NULL, 'VENTAS', '2025-07-09', '17:12:03', '2025-07-09 21:12:03', '2025-07-09 21:12:03'),
(11, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN DISTRIBUCIÓN DE PRODUCTO', 'id: 2<br/>sucursal_id: 1<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:43:02<br/>updated_at: 2024-10-03 22:43:02<br/>', 'id: 2<br/>sucursal_id: 1<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:43:02<br/>updated_at: 2024-10-03 22:43:02<br/>', 'DISTRIBUCIÓN DE PRODUCTOS', '2025-09-01', '18:40:28', '2025-09-01 22:40:28', '2025-09-01 22:40:28'),
(12, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 5<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2024-10-03 22:36:35<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 7<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 18:40:57<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '18:40:57', '2025-09-01 22:40:57', '2025-09-01 22:40:57'),
(13, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 7<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 18:40:57<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 35<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 18:41:31<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '18:41:31', '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(14, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 35<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 18:41:31<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 36<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 18:47:50<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '18:47:50', '2025-09-01 22:47:50', '2025-09-01 22:47:50'),
(15, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 36<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 18:47:50<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 37<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:14:45<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '19:14:45', '2025-09-01 23:14:45', '2025-09-01 23:14:45'),
(16, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 37<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:14:45<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 55<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:15:35<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '19:15:35', '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(17, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 55<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:15:35<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 55<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:15:35<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '19:24:10', '2025-09-01 23:24:10', '2025-09-01 23:24:10'),
(18, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 55<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:15:35<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 56<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:27:10<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '19:27:10', '2025-09-01 23:27:10', '2025-09-01 23:27:10'),
(19, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 56<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:27:10<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 69<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:27:28<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '19:27:28', '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(20, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 69<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:27:28<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 92<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:27:55<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '19:27:55', '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(21, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 92<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:27:55<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 93<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:47:57<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '19:47:57', '2025-09-01 23:47:57', '2025-09-01 23:47:57'),
(22, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 93<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:47:57<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 121<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:48:24<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '19:48:24', '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(23, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 121<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:48:24<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 165<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:48:53<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '19:48:53', '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(24, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN INGRESO DE PRODUCTO', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 165<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:48:53<br/>', 'id: 1<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 1<br/>precio: 3000.00<br/>cantidad: 206<br/>tipo_ingreso_id: 1<br/>descripcion: INGRESO INICIAL ALMACEN<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2024-10-03<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:36:35<br/>updated_at: 2025-09-01 19:49:30<br/>', 'INGRESO DE PRODUCTOS', '2025-09-01', '19:49:30', '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(25, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN INGRESO DE PRODUCTO', 'id: 5<br/>origen: ADMIN<br/>producto_id: 1<br/>proveedor_id: 2<br/>precio: 2000<br/>cantidad: 2<br/>tipo_ingreso_id: 1<br/>descripcion: DESC<br/>lugar: ALMACÉN<br/>sucursal_id: <br/>fecha_ingreso: 2025-11-06<br/>fecha_registro: 2025-11-06<br/>created_at: 2025-11-06 19:39:03<br/>updated_at: 2025-11-06 19:39:03<br/>', NULL, 'INGRESO DE PRODUCTOS', '2025-11-06', '19:39:03', '2025-11-06 23:39:03', '2025-11-06 23:39:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_productos`
--

CREATE TABLE `ingreso_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `origen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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

INSERT INTO `ingreso_productos` (`id`, `origen`, `producto_id`, `proveedor_id`, `precio`, `cantidad`, `tipo_ingreso_id`, `descripcion`, `lugar`, `sucursal_id`, `fecha_ingreso`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'ADMIN', 1, 1, 3000.00, 206, 1, 'INGRESO INICIAL ALMACEN', 'ALMACÉN', NULL, '2024-10-03', '2024-10-03', '2024-10-04 02:36:35', '2025-09-01 23:49:30'),
(2, 'SUCURSAL', 1, 1, 4000.00, 5, 1, 'INGRESO INICIAL A SUCURSAL', 'SUCURSAL', 1, '2024-10-03', '2024-10-03', '2024-10-04 02:37:04', '2024-10-04 02:37:04'),
(3, 'SUCURSAL', 2, 2, 2330.00, 5, 1, 'INGRESO INICIAL PROD. 2 SUCURSAL', 'SUCURSAL', 1, '2024-10-03', '2024-10-03', '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(5, 'ADMIN', 1, 2, 2000.00, 2, 1, 'DESC', 'ALMACÉN', NULL, '2025-11-06', '2025-11-06', '2025-11-06 23:39:03', '2025-11-06 23:39:03');

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
(1, 'ALMACÉN', NULL, 'INGRESO', 1, 1, 'VALOR INICIAL', 300.00, 'INGRESO', 206, NULL, 206, 300.00, 61800.00, NULL, 61800.00, '2024-10-03', '2024-10-04 02:36:35', '2025-09-01 23:49:30'),
(2, 'SUCURSAL', 1, 'INGRESO', 2, 1, 'VALOR INICIAL', 300.00, 'INGRESO', 5, NULL, 5, 300.00, 1500.00, NULL, 1500.00, '2024-10-03', '2024-10-04 02:37:04', '2024-10-04 02:37:04'),
(3, 'SUCURSAL', 1, 'INGRESO', 3, 2, 'VALOR INICIAL', 150.00, 'INGRESO', 5, NULL, 5, 150.00, 750.00, NULL, 750.00, '2024-10-03', '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(4, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 1, 1, 'DISTRIBUCIÓN DE PRODUCTO', 0.00, 'EGRESO', NULL, NULL, 0, 0.00, NULL, NULL, 0.00, '2024-10-03', '2024-10-04 02:42:09', '2025-09-01 23:49:30'),
(5, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 1, 1, 'INGRESO POR DISTRIBUCIÓN DESDE ALMACÉN', 300.00, 'INGRESO', 1, NULL, 6, 300.00, 300.00, NULL, 1800.00, '2024-10-03', '2024-10-04 02:42:09', '2024-10-04 02:42:09'),
(6, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 2, 1, 'DISTRIBUCIÓN DE PRODUCTO', 0.00, 'EGRESO', NULL, NULL, 0, 0.00, NULL, NULL, 0.00, '2024-10-03', '2024-10-04 02:42:28', '2025-09-01 23:49:30'),
(7, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 2, 1, 'INGRESO POR DISTRIBUCIÓN DESDE ALMACÉN', 300.00, 'INGRESO', 1, NULL, 7, 300.00, 300.00, NULL, 2100.00, '2024-10-03', '2024-10-04 02:42:28', '2024-10-04 02:42:28'),
(8, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 1, 1, 'INGRESO POR ELIMINACIÓN DE DISTRIBUCIÓN', 0.00, 'INGRESO', NULL, NULL, 0, 0.00, NULL, NULL, 0.00, '2024-10-03', '2024-10-04 02:42:39', '2025-09-01 23:49:30'),
(9, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 1, 1, 'EGRESO POR ELIMINACIÓN DE DISTRIBUCIÓN', 300.00, 'EGRESO', NULL, 1, 6, 300.00, NULL, 300.00, 1800.00, '2024-10-03', '2024-10-04 02:42:39', '2024-10-04 02:42:39'),
(10, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 2, 1, 'INGRESO POR ELIMINACIÓN DE DISTRIBUCIÓN', 0.00, 'INGRESO', NULL, NULL, 0, 0.00, NULL, NULL, 0.00, '2024-10-03', '2024-10-04 02:42:39', '2025-09-01 23:49:30'),
(11, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 2, 1, 'EGRESO POR ELIMINACIÓN DE DISTRIBUCIÓN', 300.00, 'EGRESO', NULL, 1, 5, 300.00, NULL, 300.00, 1500.00, '2024-10-03', '2024-10-04 02:42:39', '2024-10-04 02:42:39'),
(12, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 1, 1, 'DISTRIBUCIÓN DE PRODUCTO', 0.00, 'EGRESO', NULL, NULL, 0, 0.00, NULL, NULL, 0.00, '2024-10-03', '2024-10-04 02:43:02', '2025-09-01 23:49:30'),
(13, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 1, 1, 'INGRESO POR DISTRIBUCIÓN DESDE ALMACÉN', 300.00, 'INGRESO', 1, NULL, 6, 300.00, 300.00, NULL, 1800.00, '2024-10-03', '2024-10-04 02:43:02', '2024-10-04 02:43:02'),
(14, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 2, 1, 'DISTRIBUCIÓN DE PRODUCTO', 0.00, 'EGRESO', NULL, NULL, 0, 0.00, NULL, NULL, 0.00, '2024-10-03', '2024-10-04 02:43:02', '2025-09-01 23:49:30'),
(15, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 2, 1, 'INGRESO POR DISTRIBUCIÓN DESDE ALMACÉN', 300.00, 'INGRESO', 1, NULL, 7, 300.00, 300.00, NULL, 2100.00, '2024-10-03', '2024-10-04 02:43:02', '2024-10-04 02:43:02'),
(16, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 3, 1, 'DISTRIBUCIÓN DE PRODUCTO', 0.00, 'EGRESO', NULL, NULL, 0, 0.00, NULL, NULL, 0.00, '2024-10-03', '2024-10-04 02:43:02', '2025-09-01 23:49:30'),
(17, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 3, 1, 'INGRESO POR DISTRIBUCIÓN DESDE ALMACÉN', 300.00, 'INGRESO', 1, NULL, 8, 300.00, 300.00, NULL, 2400.00, '2024-10-03', '2024-10-04 02:43:02', '2024-10-04 02:43:02'),
(18, 'SUCURSAL', 1, 'VENTA', 1, 1, 'VENTA DE PRODUCTO', 300.00, 'EGRESO', NULL, 1, 7, 300.00, NULL, 300.00, 2100.00, '2024-10-03', '2024-10-04 02:43:20', '2024-10-04 02:43:20'),
(19, 'SUCURSAL', 1, 'VENTA', 2, 1, 'VENTA DE PRODUCTO', 300.00, 'EGRESO', NULL, 6, 1, 300.00, NULL, 1800.00, 300.00, '2025-07-09', '2025-07-09 21:12:03', '2025-07-09 21:12:03'),
(20, 'ALMACÉN', NULL, 'INGRESO', 5, 1, 'DESC', 300.00, 'INGRESO', 2, NULL, 2, 300.00, 600.00, NULL, 600.00, '2025-11-06', '2025-11-06 23:39:03', '2025-11-06 23:39:03');

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
(22, '2024_09_26_143104_create_almacen_productos_table', 2),
(23, '2024_11_02_153309_create_roles_table', 3),
(24, '2024_11_02_153315_create_permisos_table', 3);

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
  `venta_id` bigint UNSIGNED DEFAULT NULL,
  `venta_detalle_id` bigint UNSIGNED DEFAULT NULL,
  `distribucion_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `producto_barras`
--

INSERT INTO `producto_barras` (`id`, `producto_id`, `codigo`, `lugar`, `sucursal_id`, `ingreso_id`, `salida_id`, `venta_id`, `venta_detalle_id`, `distribucion_id`, `created_at`, `updated_at`) VALUES
(1, 1, '111', 'ALMACÉN', NULL, 1, NULL, 1, 1, 2, '2024-10-04 02:36:35', '2025-09-01 22:40:57'),
(2, 1, '112', 'ALMACÉN', NULL, 1, NULL, 2, 2, 2, '2024-10-04 02:36:35', '2025-09-01 22:40:57'),
(3, 1, '113', 'ALMACÉN', NULL, 1, NULL, 2, 2, 2, '2024-10-04 02:36:35', '2025-09-01 22:40:57'),
(4, 1, '114', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2024-10-04 02:36:35', '2024-10-04 02:36:35'),
(5, 1, '115', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2024-10-04 02:36:35', '2024-10-04 02:36:35'),
(6, 1, '1111', 'SUCURSAL', 1, 2, NULL, 2, 2, NULL, '2024-10-04 02:37:04', '2025-07-09 21:12:03'),
(7, 1, '1112', 'SUCURSAL', 1, 2, NULL, 2, 2, NULL, '2024-10-04 02:37:04', '2025-07-09 21:12:03'),
(8, 1, '1113', 'SUCURSAL', 1, 2, NULL, 2, 2, NULL, '2024-10-04 02:37:04', '2025-07-09 21:12:03'),
(9, 1, '1114', 'SUCURSAL', 1, 2, NULL, 2, 2, NULL, '2024-10-04 02:37:04', '2025-07-09 21:12:03'),
(10, 1, '1115', 'SUCURSAL', 1, 2, NULL, NULL, NULL, NULL, '2024-10-04 02:37:04', '2024-10-04 02:37:04'),
(11, 2, '2221', 'SUCURSAL', 1, 3, NULL, NULL, NULL, NULL, '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(12, 2, '2222', 'SUCURSAL', 1, 3, NULL, NULL, NULL, NULL, '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(13, 2, '2223', 'SUCURSAL', 1, 3, NULL, NULL, NULL, NULL, '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(14, 2, '2224', 'SUCURSAL', 1, 3, NULL, NULL, NULL, NULL, '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(15, 2, '2225', 'SUCURSAL', 1, 3, NULL, NULL, NULL, NULL, '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(16, 1, '1231', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:40:57', '2025-09-01 22:40:57'),
(17, 1, '123', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:40:57', '2025-09-01 22:40:57'),
(18, 1, '23', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(19, 1, '32', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(20, 1, '42', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(21, 1, '43', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(22, 1, '34', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(23, 1, '54', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(24, 1, '45', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(25, 1, '2', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(26, 1, '232', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(27, 1, '3', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(28, 1, '4343', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(29, 1, '11221', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(30, 1, '4453', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(31, 1, '6656', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(32, 1, '567567', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(33, 1, '565', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(34, 1, '5665', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(35, 1, '4545', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(36, 1, '3434', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(37, 1, '3443', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(38, 1, '43534', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(39, 1, '345', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(40, 1, '354', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(41, 1, '433', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(42, 1, '4', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(43, 1, '334', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(44, 1, '4334', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(45, 1, '322323', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:41:31', '2025-09-01 22:41:31'),
(46, 1, '34234', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 22:47:50', '2025-09-01 22:47:50'),
(47, 1, '234234', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:14:45', '2025-09-01 23:14:45'),
(48, 1, '243234', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(49, 1, '24234', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(50, 1, '5454', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(51, 1, '545465', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(52, 1, '6565', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(53, 1, '54554', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(54, 1, '343443', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(55, 1, '655665', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(56, 1, '7676', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(57, 1, '767676', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(58, 1, '677676', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(59, 1, '6767', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(60, 1, '766776', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(61, 1, '67767', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(62, 1, '6776', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(63, 1, '877887', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(64, 1, '8778', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(65, 1, '7667', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:15:35', '2025-09-01 23:15:35'),
(66, 1, '343434', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:10', '2025-09-01 23:27:10'),
(67, 1, '456456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(68, 1, '776', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(69, 1, '676776', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(70, 1, '45324324', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(71, 1, '23243', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(72, 1, '324234', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(73, 1, '342234234', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(74, 1, '14143', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(75, 1, '14141234', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(76, 1, '1421234', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(77, 1, '455465436', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(78, 1, '363564', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(79, 1, '34563456364', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:28', '2025-09-01 23:27:28'),
(80, 1, '563634', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(81, 1, '3456365', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(82, 1, '3563543564', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(83, 1, '35463563456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(84, 1, '364534563456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(85, 1, '345634563456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(86, 1, '34563456345', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(87, 1, '63435634563456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(88, 1, '4356346', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(89, 1, '364356', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(90, 1, '324143', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(91, 1, '142314', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(92, 1, '1241234124', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(93, 1, '413124134', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(94, 1, '123412342314', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(95, 1, '14242131234', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(96, 1, '12341243', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(97, 1, '3452342345', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(98, 1, '324523452354', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(99, 1, '235435223452354', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(100, 1, '25342354', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(101, 1, '23523543524', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(102, 1, '2354252534', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:27:55', '2025-09-01 23:27:55'),
(103, 1, '452345', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:47:57', '2025-09-01 23:47:57'),
(104, 1, '544536456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(105, 1, '36536365', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(106, 1, '34563563456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(107, 1, '35463563645', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(108, 1, '3546363456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(109, 1, '3456345634', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(110, 1, '34563456346', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(111, 1, '345634563', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(112, 1, '354634563456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(113, 1, '34563564356', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(114, 1, '3645363456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(115, 1, '3465346345', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(116, 1, '6364363546', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(117, 1, '235254', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(118, 1, '245254254', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(119, 1, '2452452345', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(120, 1, '25422525', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(121, 1, '52532543', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(122, 1, '2356363465', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(123, 1, '6354635463', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(124, 1, '4653654', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(125, 1, '56336654', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(126, 1, '65436354', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(127, 1, '365', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(128, 1, '3465', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(129, 1, '3456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(130, 1, '363546', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(131, 1, '34563456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:24', '2025-09-01 23:48:24'),
(132, 1, '75685786', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(133, 1, '57685786', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(134, 1, '567856785876', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(135, 1, '57685876', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(136, 1, '578675865876', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(137, 1, '57685785867', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(138, 1, '7865587857', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(139, 1, '576857868756', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(140, 1, '576858675768', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(141, 1, '85765876', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(142, 1, '536356434', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(143, 1, '5636536', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(144, 1, '34536', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(145, 1, '533564', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(146, 1, '56335463645', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(147, 1, '67585678', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(148, 1, '75688575876', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(149, 1, '78568758576', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(150, 1, '578658768756', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(151, 1, '576887568756', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(152, 1, '75858675687', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(153, 1, '75688576756', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(154, 1, '58758765876', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(155, 1, '5786587856', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(156, 1, '5878567857', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(157, 1, '5875876587', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(158, 1, '578857857', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(159, 1, '58586', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(160, 1, '5678588', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(161, 1, '5785687857', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(162, 1, '585885', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(163, 1, '8585857', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(164, 1, '857857875', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(165, 1, '587587', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(166, 1, '578587875', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(167, 1, '578857', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(168, 1, '6544765', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(169, 1, '7467447', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(170, 1, '74647', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(171, 1, '465765477456', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(172, 1, '46754576', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(173, 1, '465746754675', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(174, 1, '46574765', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(175, 1, '45767465', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:48:53', '2025-09-01 23:48:53'),
(176, 1, '7585768', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(177, 1, '75685877', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(178, 1, '578657865876', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(179, 1, '576878565876', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(180, 1, '587587587', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(181, 1, '5858765867', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(182, 1, '578687565876', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(183, 1, '57858765786', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(184, 1, '7586875876', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(185, 1, '8679698', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(186, 1, '698986698', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(187, 1, '689776899867', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(188, 1, '86979686798', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(189, 1, '69879686789', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(190, 1, '8967986968', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(191, 1, '6879698968', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(192, 1, '869986698', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(193, 1, '689986968', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(194, 1, '689698896', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(195, 1, '6896989687', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(196, 1, '6897968698', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(197, 1, '69869987', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(198, 1, '687998676978', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(199, 1, '6897689698', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(200, 1, '89698679678', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(201, 1, '689796879876', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(202, 1, '689986698', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(203, 1, '869796879678', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(204, 1, '86978969687', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(205, 1, '9686986987', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(206, 1, '86979676978', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(207, 1, '869769876978', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(208, 1, '86976986987', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(209, 1, '698698968', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(210, 1, '698689', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(211, 1, '6789698986', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(212, 1, '6896798', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(213, 1, '678967896789', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(214, 1, '67896978', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(215, 1, '678967987968', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(216, 1, '67967998', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2025-09-01 23:49:30', '2025-09-01 23:49:30'),
(217, 1, '432', 'ALMACÉN', NULL, 5, NULL, NULL, NULL, NULL, '2025-11-06 23:39:03', '2025-11-06 23:39:03'),
(218, 1, '4323', 'ALMACÉN', NULL, 5, NULL, NULL, NULL, NULL, '2025-11-06 23:39:03', '2025-11-06 23:39:03');

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
  `origen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(1, 1, 1, 1, '2024-10-04 02:37:04', '2025-07-09 21:12:03'),
(2, 2, 1, 5, '2024-10-04 02:37:31', '2024-10-04 02:37:31');

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
(2, 'JPERES', 'JUAN', 'PERES', 'MAMANI', '1111', 'LP', 'ZONA LOS OLIVOS', 'JUAN@GMAIL.COM', '77777777', '$2y$12$3QHG0syHSXFGDhyC3x7bqOfm.Rdms.qawkgu01540bhCLdNMlwzLm', 'SUPERVISOR DE SUCURSAL', '1727192573_JPERES.jpg', '2024-09-24', 1, 1, '2024-09-24 19:42:52', '2024-09-24 19:42:53'),
(3, 'MMAMANI', 'MARIA', 'MAMANI', 'MAMANI', '2222', 'LP', 'ZONA LOS OLIVOS C3 #222', 'MARIA@GMAIL.COM', '7777777', '$2y$12$XfzQKIK5F1aDFc5YQWNBvu60YDjDgWbcefXvC504mDZOIRUDaSf..', 'OPERADOR', '1727899401_MMAMANI.jpg', '2024-10-02', 1, 1, '2024-10-03 00:03:21', '2024-10-03 00:03:21'),
(4, 'JRAMIRES', 'JAVIER', 'RAMIRES', 'CONDORI', '3333', 'LP', 'ZONA LOS OLIVOS', '', '67676767', '$2y$12$CPIYkOTuZ2TF5tm4MrLpAu.5BZXQ8cqUkwAhom.UU3rzddGta6YLa', 'ADMINISTRADOR', NULL, '2024-10-03', 1, NULL, '2024-10-03 19:25:01', '2024-10-03 19:25:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `descuento` double NOT NULL,
  `total_final` decimal(24,2) NOT NULL,
  `tipo_pago` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EFECTIVO',
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `sucursal_id`, `cliente_id`, `user_id`, `nit`, `total`, `descuento`, `total_final`, `tipo_pago`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 2, '22222', 300.00, 0, 300.00, 'EFECTIVO', '2024-10-03', '2024-10-04 02:43:20', '2024-10-04 02:43:20'),
(2, 1, 1, 1, '1122', 1800.00, 0, 1800.00, 'EFECTIVO', '2025-07-09', '2025-07-09 21:12:03', '2025-07-09 21:12:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_detalles`
--

CREATE TABLE `venta_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `venta_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `precio` decimal(24,2) NOT NULL,
  `subtotal` decimal(24,2) NOT NULL,
  `descuento` double NOT NULL,
  `subtotaltotal` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `venta_detalles`
--

INSERT INTO `venta_detalles` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio`, `subtotal`, `descuento`, `subtotaltotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 300.00, 300.00, 0, 300.00, '2024-10-04 02:43:20', '2024-10-04 02:43:20'),
(2, 2, 1, 6, 300.00, 1800.00, 0, 1800.00, '2025-07-09 21:12:03', '2025-07-09 21:12:03');

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `distribucion_productos`
--
ALTER TABLE `distribucion_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `ingreso_productos`
--
ALTER TABLE `ingreso_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `kardex_productos`
--
ALTER TABLE `kardex_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `producto_barras`
--
ALTER TABLE `producto_barras`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT de la tabla `proveedors`
--
ALTER TABLE `proveedors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `salida_productos`
--
ALTER TABLE `salida_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `venta_detalles`
--
ALTER TABLE `venta_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
