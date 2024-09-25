-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 25-09-2024 a las 17:12:49
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
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SUCURSAL', 'id: 1<br/>nombre: SUCURSAL #1<br/>fono: 77777 - 666666<br/>dir: ZONA LOS PEDREGALES C. 4 #2222<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 15:24:59<br/>updated_at: 2024-09-24 15:24:59<br/>', NULL, 'SUCURSALES', '2024-09-24', '15:24:59', '2024-09-24 19:24:59', '2024-09-24 19:24:59'),
(2, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA SUCURSAL', 'id: 1<br/>nombre: SUCURSAL #1<br/>fono: 77777 - 666666<br/>dir: ZONA LOS PEDREGALES C. 4 #2222<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 15:24:59<br/>updated_at: 2024-09-24 15:24:59<br/>', 'id: 1<br/>nombre: SUCURSAL #1A<br/>fono: 77777 - 666666B<br/>dir: ZONA LOS PEDREGALES C. 4 #2222C<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 15:24:59<br/>updated_at: 2024-09-24 15:29:34<br/>', 'SUCURSALES', '2024-09-24', '15:29:34', '2024-09-24 19:29:34', '2024-09-24 19:29:34'),
(3, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA SUCURSAL', 'id: 1<br/>nombre: SUCURSAL #1A<br/>fono: 77777 - 666666B<br/>dir: ZONA LOS PEDREGALES C. 4 #2222C<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 15:24:59<br/>updated_at: 2024-09-24 15:29:34<br/>', 'id: 1<br/>nombre: SUCURSAL #1A<br/>fono: 77777 - 666666B<br/>dir: ZONA LOS PEDREGALES C. 4 #2222C<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 15:24:59<br/>updated_at: 2024-09-24 15:29:34<br/>', 'SUCURSALES', '2024-09-24', '15:30:07', '2024-09-24 19:30:07', '2024-09-24 19:30:07'),
(4, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA SUCURSAL', 'id: 1<br/>nombre: SUCURSAL #1A<br/>fono: 77777 - 666666B<br/>dir: ZONA LOS PEDREGALES C. 4 #2222C<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 15:24:59<br/>updated_at: 2024-09-24 15:29:34<br/>', NULL, 'SUCURSALES', '2024-09-24', '15:30:11', '2024-09-24 19:30:11', '2024-09-24 19:30:11'),
(5, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SUCURSAL', 'id: 1<br/>nombre: SUCURSAL #1<br/>fono: 77777777 - 66666666<br/>dir: ZONA LOS PEDREGALES C. 3 #4444<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 15:30:36<br/>updated_at: 2024-09-24 15:30:36<br/>', NULL, 'SUCURSALES', '2024-09-24', '15:30:36', '2024-09-24 19:30:36', '2024-09-24 19:30:36'),
(6, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SUCURSAL', 'id: 2<br/>nombre: SUCURSAL #2<br/>fono: 78787878 - 67676767<br/>dir: ZONA LOS OLIVOS C. A #2222<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 15:30:59<br/>updated_at: 2024-09-24 15:30:59<br/>', NULL, 'SUCURSALES', '2024-09-24', '15:30:59', '2024-09-24 19:30:59', '2024-09-24 19:30:59'),
(7, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN USUARIO', 'id: 2<br/>usuario: JPERES<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: ZONA LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 77777777<br/>password: $2y$12$sCckfT5X32Ajbwqs1QFCIuSiN9dJ5rwZsuY7uqCOSvXFEJGLvJ2fa<br/>tipo: SUPERVISOR DE SUCURSAL<br/>foto: 1727192424_JPERES.jpg<br/>fecha_registro: 2024-09-24<br/>acceso: 1<br/>sucursal_id: 1<br/>created_at: 2024-09-24 15:40:24<br/>updated_at: 2024-09-24 15:40:24<br/>', NULL, 'USUARIOS', '2024-09-24', '15:40:24', '2024-09-24 19:40:24', '2024-09-24 19:40:24'),
(8, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN USUARIO', 'id: 2<br/>usuario: JPERES<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: ZONA LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 77777777<br/>password: $2y$12$sCckfT5X32Ajbwqs1QFCIuSiN9dJ5rwZsuY7uqCOSvXFEJGLvJ2fa<br/>tipo: SUPERVISOR DE SUCURSAL<br/>foto: 1727192424_JPERES.jpg<br/>fecha_registro: 2024-09-24<br/>acceso: 1<br/>sucursal_id: 1<br/>created_at: 2024-09-24 15:40:24<br/>updated_at: 2024-09-24 15:40:24<br/>', 'id: 2<br/>usuario: JPERES<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: ZONA LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 77777777<br/>password: $2y$12$sCckfT5X32Ajbwqs1QFCIuSiN9dJ5rwZsuY7uqCOSvXFEJGLvJ2fa<br/>tipo: ADMINISTRADOR<br/>foto: 1727192424_JPERES.jpg<br/>fecha_registro: 2024-09-24<br/>acceso: 1<br/>sucursal_id: <br/>created_at: 2024-09-24 15:40:24<br/>updated_at: 2024-09-24 15:40:38<br/>', 'USUARIOS', '2024-09-24', '15:40:38', '2024-09-24 19:40:38', '2024-09-24 19:40:38'),
(9, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN USUARIO', 'id: 2<br/>usuario: JPERES<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: ZONA LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 77777777<br/>password: $2y$12$sCckfT5X32Ajbwqs1QFCIuSiN9dJ5rwZsuY7uqCOSvXFEJGLvJ2fa<br/>tipo: ADMINISTRADOR<br/>foto: 1727192424_JPERES.jpg<br/>fecha_registro: 2024-09-24<br/>acceso: 1<br/>sucursal_id: <br/>created_at: 2024-09-24 15:40:24<br/>updated_at: 2024-09-24 15:40:38<br/>', 'id: 2<br/>usuario: JPERES<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: ZONA LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 77777777<br/>password: $2y$12$sCckfT5X32Ajbwqs1QFCIuSiN9dJ5rwZsuY7uqCOSvXFEJGLvJ2fa<br/>tipo: OPERADOR<br/>foto: 1727192424_JPERES.jpg<br/>fecha_registro: 2024-09-24<br/>acceso: 1<br/>sucursal_id: 2<br/>created_at: 2024-09-24 15:40:24<br/>updated_at: 2024-09-24 15:40:57<br/>', 'USUARIOS', '2024-09-24', '15:40:57', '2024-09-24 19:40:57', '2024-09-24 19:40:57'),
(10, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN USUARIO', 'id: 2<br/>usuario: JPERES<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: ZONA LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 77777777<br/>password: $2y$12$sCckfT5X32Ajbwqs1QFCIuSiN9dJ5rwZsuY7uqCOSvXFEJGLvJ2fa<br/>tipo: OPERADOR<br/>foto: 1727192424_JPERES.jpg<br/>fecha_registro: 2024-09-24<br/>acceso: 1<br/>sucursal_id: 2<br/>created_at: 2024-09-24 15:40:24<br/>updated_at: 2024-09-24 15:40:57<br/>', NULL, 'USUARIOS', '2024-09-24', '15:42:10', '2024-09-24 19:42:10', '2024-09-24 19:42:10'),
(11, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN USUARIO', 'id: 2<br/>usuario: JPERES<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: ZONA LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 77777777<br/>password: $2y$12$3QHG0syHSXFGDhyC3x7bqOfm.Rdms.qawkgu01540bhCLdNMlwzLm<br/>tipo: SUPERVISOR DE SUCURSAL<br/>foto: 1727192573_JPERES.jpg<br/>fecha_registro: 2024-09-24<br/>acceso: 1<br/>sucursal_id: 1<br/>created_at: 2024-09-24 15:42:52<br/>updated_at: 2024-09-24 15:42:53<br/>', NULL, 'USUARIOS', '2024-09-24', '15:42:53', '2024-09-24 19:42:53', '2024-09-24 19:42:53'),
(12, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA SUCURSAL', 'id: 1<br/>nombre: SUCURSAL #1<br/>fono: 77777777 - 66666666<br/>dir: ZONA LOS PEDREGALES C. 3 #4444<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 15:30:36<br/>updated_at: 2024-09-24 15:30:36<br/>', 'id: 1<br/>nombre: SUCURSAL #1<br/>fono: 77777777 - 66666666<br/>dir: ZONA LOS PEDREGALES C. 3 #4444<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 15:30:36<br/>updated_at: 2024-09-24 15:30:36<br/>', 'SUCURSALES', '2024-09-24', '15:56:50', '2024-09-24 19:56:50', '2024-09-24 19:56:50'),
(13, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PROVEEDOR', 'id: 1<br/>razon_social: PROVEEDOR1 S.A.<br/>nit: 1111111111<br/>dir: ZONA LOS MANZANOS C3 #22<br/>fono: 2222222<br/>nombre_contacto: EDUARDO MARTINEZ<br/>descripcion: DESC. PROVEEDOR 1<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 16:10:51<br/>updated_at: 2024-09-24 16:10:51<br/>', NULL, 'PROVEEDORES', '2024-09-24', '16:10:51', '2024-09-24 20:10:51', '2024-09-24 20:10:51'),
(14, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN PROVEEDOR', 'id: 1<br/>razon_social: PROVEEDOR1 S.A.<br/>nit: 1111111111<br/>dir: ZONA LOS MANZANOS C3 #22<br/>fono: 2222222<br/>nombre_contacto: EDUARDO MARTINEZ<br/>descripcion: DESC. PROVEEDOR 1<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 16:10:51<br/>updated_at: 2024-09-24 16:10:51<br/>', 'id: 1<br/>razon_social: PROVEEDOR1 S.A.A<br/>nit: 1111111111B<br/>dir: ZONA LOS MANZANOS C3 #22C<br/>fono: 2222222D<br/>nombre_contacto: EDUARDO MARTINEZE<br/>descripcion: DESC. PROVEEDOR 1F<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 16:10:51<br/>updated_at: 2024-09-24 16:10:59<br/>', 'PROVEEDORES', '2024-09-24', '16:10:59', '2024-09-24 20:10:59', '2024-09-24 20:10:59'),
(15, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN PROVEEDOR', 'id: 1<br/>razon_social: PROVEEDOR1 S.A.A<br/>nit: 1111111111B<br/>dir: ZONA LOS MANZANOS C3 #22C<br/>fono: 2222222D<br/>nombre_contacto: EDUARDO MARTINEZE<br/>descripcion: DESC. PROVEEDOR 1F<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 16:10:51<br/>updated_at: 2024-09-24 16:10:59<br/>', NULL, 'PROVEEDORES', '2024-09-24', '16:11:22', '2024-09-24 20:11:22', '2024-09-24 20:11:22'),
(16, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PROVEEDOR', 'id: 1<br/>razon_social: PROVEEDOR 1 S.A.<br/>nit: 1111111111<br/>dir: ZONA LOS MANZANOS C. 1 #44444<br/>fono: 222222<br/>nombre_contacto: EDUARDO ALVARES<br/>descripcion: DESC. PROVEEDOR 1<br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 16:11:54<br/>updated_at: 2024-09-24 16:11:54<br/>', NULL, 'PROVEEDORES', '2024-09-24', '16:11:54', '2024-09-24 20:11:54', '2024-09-24 20:11:54'),
(17, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PROVEEDOR', 'id: 2<br/>razon_social: PROVEEDOR 2 S.R.L.<br/>nit: 222222222222<br/>dir: ZONA LOS HEROES C. 3 #22222<br/>fono: 2727277<br/>nombre_contacto: JORGE PAREDES<br/>descripcion: <br/>fecha_registro: 2024-09-24<br/>created_at: 2024-09-24 16:12:29<br/>updated_at: 2024-09-24 16:12:29<br/>', NULL, 'PROVEEDORES', '2024-09-24', '16:12:29', '2024-09-24 20:12:29', '2024-09-24 20:12:29'),
(18, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA CATEGORÍA', 'id: 1<br/>nombre: CATEGORIA #1<br/>created_at: 2024-09-24 16:21:05<br/>updated_at: 2024-09-24 16:21:05<br/>', NULL, 'CATEGORIAS', '2024-09-24', '16:21:05', '2024-09-24 20:21:05', '2024-09-24 20:21:05'),
(19, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA CATEGORÍA', 'id: 1<br/>nombre: CATEGORIA #1<br/>created_at: 2024-09-24 16:21:05<br/>updated_at: 2024-09-24 16:21:05<br/>', 'id: 1<br/>nombre: CATEGORIA #1 ASD<br/>created_at: 2024-09-24 16:21:05<br/>updated_at: 2024-09-24 16:21:25<br/>', 'CATEGORIAS', '2024-09-24', '16:21:25', '2024-09-24 20:21:25', '2024-09-24 20:21:25'),
(20, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA CATEGORÍA', 'id: 1<br/>nombre: CATEGORIA #1 ASD<br/>created_at: 2024-09-24 16:21:05<br/>updated_at: 2024-09-24 16:21:25<br/>', NULL, 'CATEGORIAS', '2024-09-24', '16:21:28', '2024-09-24 20:21:28', '2024-09-24 20:21:28'),
(21, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA CATEGORÍA', 'id: 1<br/>nombre: CATEGORIA  #1<br/>created_at: 2024-09-24 16:21:47<br/>updated_at: 2024-09-24 16:21:47<br/>', NULL, 'CATEGORIAS', '2024-09-24', '16:21:47', '2024-09-24 20:21:47', '2024-09-24 20:21:47'),
(22, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA CATEGORÍA', 'id: 1<br/>nombre: MARCA #1<br/>created_at: 2024-09-25 16:25:10<br/>updated_at: 2024-09-25 16:25:10<br/>', NULL, 'CATEGORIAS', '2024-09-25', '16:25:10', '2024-09-25 20:25:10', '2024-09-25 20:25:10'),
(23, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA CATEGORÍA', 'id: 1<br/>nombre: MARCA #1<br/>created_at: 2024-09-25 16:25:10<br/>updated_at: 2024-09-25 16:25:10<br/>', 'id: 1<br/>nombre: MARCA #1ASD<br/>created_at: 2024-09-25 16:25:10<br/>updated_at: 2024-09-25 16:25:15<br/>', 'CATEGORIAS', '2024-09-25', '16:25:15', '2024-09-25 20:25:15', '2024-09-25 20:25:15'),
(24, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA CATEGORÍA', 'id: 1<br/>nombre: MARCA #1ASD<br/>created_at: 2024-09-25 16:25:10<br/>updated_at: 2024-09-25 16:25:15<br/>', NULL, 'CATEGORIAS', '2024-09-25', '16:25:21', '2024-09-25 20:25:21', '2024-09-25 20:25:21'),
(25, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA CATEGORÍA', 'id: 1<br/>nombre: MARCA #1<br/>created_at: 2024-09-25 16:25:33<br/>updated_at: 2024-09-25 16:25:33<br/>', NULL, 'CATEGORIAS', '2024-09-25', '16:25:33', '2024-09-25 20:25:33', '2024-09-25 20:25:33'),
(26, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA CATEGORÍA', 'id: 2<br/>nombre: MARCA #2<br/>created_at: 2024-09-25 16:25:38<br/>updated_at: 2024-09-25 16:25:38<br/>', NULL, 'CATEGORIAS', '2024-09-25', '16:25:38', '2024-09-25 20:25:38', '2024-09-25 20:25:38'),
(27, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA UNIDAD DE MEDIDA', 'id: 1<br/>nombre: UNIDAD #1<br/>created_at: 2024-09-25 16:28:37<br/>updated_at: 2024-09-25 16:28:37<br/>', NULL, 'UNIDADES DE MEDIDA', '2024-09-25', '16:28:37', '2024-09-25 20:28:37', '2024-09-25 20:28:37'),
(28, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA UNIDAD DE MEDIDA', 'id: 1<br/>nombre: UNIDAD #1<br/>created_at: 2024-09-25 16:28:37<br/>updated_at: 2024-09-25 16:28:37<br/>', 'id: 1<br/>nombre: UNIDAD #1ASD<br/>created_at: 2024-09-25 16:28:37<br/>updated_at: 2024-09-25 16:28:41<br/>', 'UNIDADES DE MEDIDA', '2024-09-25', '16:28:41', '2024-09-25 20:28:41', '2024-09-25 20:28:41'),
(29, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA UNIDAD DE MEDIDA', 'id: 1<br/>nombre: UNIDAD #1ASD<br/>created_at: 2024-09-25 16:28:37<br/>updated_at: 2024-09-25 16:28:41<br/>', NULL, 'UNIDADES DE MEDIDA', '2024-09-25', '16:28:45', '2024-09-25 20:28:45', '2024-09-25 20:28:45'),
(30, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA UNIDAD DE MEDIDA', 'id: 1<br/>nombre: UNIDAD #1<br/>created_at: 2024-09-25 16:28:57<br/>updated_at: 2024-09-25 16:28:57<br/>', NULL, 'UNIDADES DE MEDIDA', '2024-09-25', '16:28:57', '2024-09-25 20:28:57', '2024-09-25 20:28:57'),
(31, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA UNIDAD DE MEDIDA', 'id: 2<br/>nombre: UNIDAD #2<br/>created_at: 2024-09-25 16:29:02<br/>updated_at: 2024-09-25 16:29:02<br/>', NULL, 'UNIDADES DE MEDIDA', '2024-09-25', '16:29:02', '2024-09-25 20:29:02', '2024-09-25 20:29:02'),
(32, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA CATEGORÍA', 'id: 2<br/>nombre: CATEGORIA #2<br/>created_at: 2024-09-25 17:08:52<br/>updated_at: 2024-09-25 17:08:52<br/>', NULL, 'CATEGORIAS', '2024-09-25', '17:08:52', '2024-09-25 21:08:52', '2024-09-25 21:08:52'),
(33, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PRODUCTO', 'id: 2<br/>nombre: PRODUCTO #1<br/>categoria_id: 1<br/>marca_id: 1<br/>unidad_medida_id: 1<br/>precio: 300<br/>stock_min: 10<br/>imagen: 1727284262_2.png<br/>fecha_registro: 2024-09-25<br/>created_at: 2024-09-25 17:11:02<br/>updated_at: 2024-09-25 17:11:02<br/>', NULL, 'PRODUCTOS', '2024-09-25', '17:11:02', '2024-09-25 21:11:02', '2024-09-25 21:11:02'),
(34, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN PRODUCTO', 'id: 2<br/>nombre: PRODUCTO #1<br/>categoria_id: 1<br/>marca_id: 1<br/>unidad_medida_id: 1<br/>precio: 300.00<br/>stock_min: 10<br/>imagen: 1727284262_2.png<br/>fecha_registro: 2024-09-25<br/>created_at: 2024-09-25 17:11:02<br/>updated_at: 2024-09-25 17:11:02<br/>', 'id: 2<br/>nombre: PRODUCTO #1B<br/>categoria_id: 2<br/>marca_id: 2<br/>unidad_medida_id: 2<br/>precio: 250<br/>stock_min: 14<br/>imagen: 1727284285_2.png<br/>fecha_registro: 2024-09-25<br/>created_at: 2024-09-25 17:11:02<br/>updated_at: 2024-09-25 17:11:25<br/>', 'PRODUCTOS', '2024-09-25', '17:11:25', '2024-09-25 21:11:25', '2024-09-25 21:11:25'),
(35, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN PRODUCTO', 'id: 2<br/>nombre: PRODUCTO #1B<br/>categoria_id: 2<br/>marca_id: 2<br/>unidad_medida_id: 2<br/>precio: 250.00<br/>stock_min: 14<br/>imagen: 1727284285_2.png<br/>fecha_registro: 2024-09-25<br/>created_at: 2024-09-25 17:11:02<br/>updated_at: 2024-09-25 17:11:25<br/>', NULL, 'PRODUCTOS', '2024-09-25', '17:12:07', '2024-09-25 21:12:07', '2024-09-25 21:12:07'),
(36, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PRODUCTO', 'id: 1<br/>nombre: PRODUCTO #1<br/>categoria_id: 1<br/>marca_id: 1<br/>unidad_medida_id: 1<br/>precio: 300<br/>stock_min: 15<br/>imagen: 1727284361_1.png<br/>fecha_registro: 2024-09-25<br/>created_at: 2024-09-25 17:12:41<br/>updated_at: 2024-09-25 17:12:41<br/>', NULL, 'PRODUCTOS', '2024-09-25', '17:12:41', '2024-09-25 21:12:41', '2024-09-25 21:12:41');

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kadexs`
--

CREATE TABLE `kadexs` (
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
(21, '2024_09_23_143923_create_kadexs_table', 1);

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
(1, 'PRODUCTO #1', 1, 1, 1, 300.00, 15, '1727284361_1.png', '2024-09-25', '2024-09-25 21:12:41', '2024-09-25 21:12:41');

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
  `fecha_saida` date NOT NULL,
  `tipo_salida_id` bigint UNSIGNED NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_ingresos`
--

CREATE TABLE `tipo_ingresos` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_salidas`
--

CREATE TABLE `tipo_salidas` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categorias_nombre_unique` (`nombre`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clientes_ci_unique` (`ci`);

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
-- Indices de la tabla `kadexs`
--
ALTER TABLE `kadexs`
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
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `ingreso_productos`
--
ALTER TABLE `ingreso_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `kadexs`
--
ALTER TABLE `kadexs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `producto_barras`
--
ALTER TABLE `producto_barras`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_ingresos`
--
ALTER TABLE `tipo_ingresos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_salidas`
--
ALTER TABLE `tipo_salidas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- Filtros para la tabla `kadexs`
--
ALTER TABLE `kadexs`
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
