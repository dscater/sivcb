-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 03-10-2024 a las 22:47:05
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
(1, 1, 2, '2024-10-04 02:36:35', '2024-10-04 02:43:02');

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
(9, 2, 'CREACIÓN', 'EL USUARIO JPERES REGISTRO UNA VENTA', 'id: 1<br/>sucursal_id: 1<br/>cliente_id: 4<br/>user_id: 2<br/>nit: 22222<br/>total: 300.00<br/>descuento: 0<br/>total_final: 300.00<br/>fecha_registro: 2024-10-03<br/>created_at: 2024-10-03 22:43:20<br/>updated_at: 2024-10-03 22:43:20<br/>', NULL, 'VENTAS', '2024-10-03', '22:43:20', '2024-10-04 02:43:20', '2024-10-04 02:43:20');

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
(1, 'ADMIN', 1, 1, 3000.00, 5, 1, 'INGRESO INICIAL ALMACEN', 'ALMACÉN', NULL, '2024-10-03', '2024-10-03', '2024-10-04 02:36:35', '2024-10-04 02:36:35'),
(2, 'SUCURSAL', 1, 1, 4000.00, 5, 1, 'INGRESO INICIAL A SUCURSAL', 'SUCURSAL', 1, '2024-10-03', '2024-10-03', '2024-10-04 02:37:04', '2024-10-04 02:37:04'),
(3, 'SUCURSAL', 2, 2, 2330.00, 5, 1, 'INGRESO INICIAL PROD. 2 SUCURSAL', 'SUCURSAL', 1, '2024-10-03', '2024-10-03', '2024-10-04 02:37:31', '2024-10-04 02:37:31');

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
(1, 'ALMACÉN', NULL, 'INGRESO', 1, 1, 'VALOR INICIAL', 300.00, 'INGRESO', 5, NULL, 5, 300.00, 1500.00, NULL, 1500.00, '2024-10-03', '2024-10-04 02:36:35', '2024-10-04 02:36:35'),
(2, 'SUCURSAL', 1, 'INGRESO', 2, 1, 'VALOR INICIAL', 300.00, 'INGRESO', 5, NULL, 5, 300.00, 1500.00, NULL, 1500.00, '2024-10-03', '2024-10-04 02:37:04', '2024-10-04 02:37:04'),
(3, 'SUCURSAL', 1, 'INGRESO', 3, 2, 'VALOR INICIAL', 150.00, 'INGRESO', 5, NULL, 5, 150.00, 750.00, NULL, 750.00, '2024-10-03', '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(4, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 1, 1, 'DISTRIBUCIÓN DE PRODUCTO', 300.00, 'EGRESO', NULL, 1, 4, 300.00, NULL, 300.00, 1200.00, '2024-10-03', '2024-10-04 02:42:09', '2024-10-04 02:42:09'),
(5, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 1, 1, 'INGRESO POR DISTRIBUCIÓN DESDE ALMACÉN', 300.00, 'INGRESO', 1, NULL, 6, 300.00, 300.00, NULL, 1800.00, '2024-10-03', '2024-10-04 02:42:09', '2024-10-04 02:42:09'),
(6, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 2, 1, 'DISTRIBUCIÓN DE PRODUCTO', 300.00, 'EGRESO', NULL, 1, 3, 300.00, NULL, 300.00, 900.00, '2024-10-03', '2024-10-04 02:42:28', '2024-10-04 02:42:28'),
(7, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 2, 1, 'INGRESO POR DISTRIBUCIÓN DESDE ALMACÉN', 300.00, 'INGRESO', 1, NULL, 7, 300.00, 300.00, NULL, 2100.00, '2024-10-03', '2024-10-04 02:42:28', '2024-10-04 02:42:28'),
(8, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 1, 1, 'INGRESO POR ELIMINACIÓN DE DISTRIBUCIÓN', 300.00, 'INGRESO', 1, NULL, 4, 300.00, 300.00, NULL, 1200.00, '2024-10-03', '2024-10-04 02:42:39', '2024-10-04 02:42:39'),
(9, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 1, 1, 'EGRESO POR ELIMINACIÓN DE DISTRIBUCIÓN', 300.00, 'EGRESO', NULL, 1, 6, 300.00, NULL, 300.00, 1800.00, '2024-10-03', '2024-10-04 02:42:39', '2024-10-04 02:42:39'),
(10, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 2, 1, 'INGRESO POR ELIMINACIÓN DE DISTRIBUCIÓN', 300.00, 'INGRESO', 1, NULL, 5, 300.00, 300.00, NULL, 1500.00, '2024-10-03', '2024-10-04 02:42:39', '2024-10-04 02:42:39'),
(11, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 2, 1, 'EGRESO POR ELIMINACIÓN DE DISTRIBUCIÓN', 300.00, 'EGRESO', NULL, 1, 5, 300.00, NULL, 300.00, 1500.00, '2024-10-03', '2024-10-04 02:42:39', '2024-10-04 02:42:39'),
(12, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 1, 1, 'DISTRIBUCIÓN DE PRODUCTO', 300.00, 'EGRESO', NULL, 1, 4, 300.00, NULL, 300.00, 1200.00, '2024-10-03', '2024-10-04 02:43:02', '2024-10-04 02:43:02'),
(13, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 1, 1, 'INGRESO POR DISTRIBUCIÓN DESDE ALMACÉN', 300.00, 'INGRESO', 1, NULL, 6, 300.00, 300.00, NULL, 1800.00, '2024-10-03', '2024-10-04 02:43:02', '2024-10-04 02:43:02'),
(14, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 2, 1, 'DISTRIBUCIÓN DE PRODUCTO', 300.00, 'EGRESO', NULL, 1, 3, 300.00, NULL, 300.00, 900.00, '2024-10-03', '2024-10-04 02:43:02', '2024-10-04 02:43:02'),
(15, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 2, 1, 'INGRESO POR DISTRIBUCIÓN DESDE ALMACÉN', 300.00, 'INGRESO', 1, NULL, 7, 300.00, 300.00, NULL, 2100.00, '2024-10-03', '2024-10-04 02:43:02', '2024-10-04 02:43:02'),
(16, 'ALMACÉN', NULL, 'DISTRIBUCIÓN', 3, 1, 'DISTRIBUCIÓN DE PRODUCTO', 300.00, 'EGRESO', NULL, 1, 2, 300.00, NULL, 300.00, 600.00, '2024-10-03', '2024-10-04 02:43:02', '2024-10-04 02:43:02'),
(17, 'SUCURSAL', 1, 'DISTRIBUCIÓN', 3, 1, 'INGRESO POR DISTRIBUCIÓN DESDE ALMACÉN', 300.00, 'INGRESO', 1, NULL, 8, 300.00, 300.00, NULL, 2400.00, '2024-10-03', '2024-10-04 02:43:02', '2024-10-04 02:43:02'),
(18, 'SUCURSAL', 1, 'VENTA', 1, 1, 'VENTA DE PRODUCTO', 300.00, 'EGRESO', NULL, 1, 7, 300.00, NULL, 300.00, 2100.00, '2024-10-03', '2024-10-04 02:43:20', '2024-10-04 02:43:20');

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
(1, 1, '111', 'SUCURSAL', 1, 1, NULL, 1, 1, 2, '2024-10-04 02:36:35', '2024-10-04 02:43:20'),
(2, 1, '112', 'SUCURSAL', 1, 1, NULL, NULL, NULL, 2, '2024-10-04 02:36:35', '2024-10-04 02:43:02'),
(3, 1, '113', 'SUCURSAL', 1, 1, NULL, NULL, NULL, 2, '2024-10-04 02:36:35', '2024-10-04 02:43:02'),
(4, 1, '114', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2024-10-04 02:36:35', '2024-10-04 02:36:35'),
(5, 1, '115', 'ALMACÉN', NULL, 1, NULL, NULL, NULL, NULL, '2024-10-04 02:36:35', '2024-10-04 02:36:35'),
(6, 1, '1111', 'SUCURSAL', 1, 2, NULL, NULL, NULL, NULL, '2024-10-04 02:37:04', '2024-10-04 02:37:04'),
(7, 1, '1112', 'SUCURSAL', 1, 2, NULL, NULL, NULL, NULL, '2024-10-04 02:37:04', '2024-10-04 02:37:04'),
(8, 1, '1113', 'SUCURSAL', 1, 2, NULL, NULL, NULL, NULL, '2024-10-04 02:37:04', '2024-10-04 02:37:04'),
(9, 1, '1114', 'SUCURSAL', 1, 2, NULL, NULL, NULL, NULL, '2024-10-04 02:37:04', '2024-10-04 02:37:04'),
(10, 1, '1115', 'SUCURSAL', 1, 2, NULL, NULL, NULL, NULL, '2024-10-04 02:37:04', '2024-10-04 02:37:04'),
(11, 2, '2221', 'SUCURSAL', 1, 3, NULL, NULL, NULL, NULL, '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(12, 2, '2222', 'SUCURSAL', 1, 3, NULL, NULL, NULL, NULL, '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(13, 2, '2223', 'SUCURSAL', 1, 3, NULL, NULL, NULL, NULL, '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(14, 2, '2224', 'SUCURSAL', 1, 3, NULL, NULL, NULL, NULL, '2024-10-04 02:37:31', '2024-10-04 02:37:31'),
(15, 2, '2225', 'SUCURSAL', 1, 3, NULL, NULL, NULL, NULL, '2024-10-04 02:37:31', '2024-10-04 02:37:31');

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
(1, 1, 1, 7, '2024-10-04 02:37:04', '2024-10-04 02:43:20'),
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
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `sucursal_id`, `cliente_id`, `user_id`, `nit`, `total`, `descuento`, `total_final`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 2, '22222', 300.00, 0, 300.00, '2024-10-03', '2024-10-04 02:43:20', '2024-10-04 02:43:20');

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
(1, 1, 1, 1, 300.00, 300.00, 0, 300.00, '2024-10-04 02:43:20', '2024-10-04 02:43:20');

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ingreso_productos`
--
ALTER TABLE `ingreso_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `kardex_productos`
--
ALTER TABLE `kardex_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `venta_detalles`
--
ALTER TABLE `venta_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
