-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 21-03-2021 a las 21:13:32
-- Versión del servidor: 5.7.33-log-cll-lve
-- Versión de PHP: 7.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `digita85_manhattan`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acl`
--

CREATE TABLE `acl` (
  `ai` int(10) UNSIGNED NOT NULL,
  `action_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acl_actions`
--

CREATE TABLE `acl_actions` (
  `action_id` int(10) UNSIGNED NOT NULL,
  `action_code` varchar(100) NOT NULL COMMENT 'No periods allowed!',
  `action_desc` varchar(100) NOT NULL COMMENT 'Human readable description',
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acl_categories`
--

CREATE TABLE `acl_categories` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_code` varchar(100) NOT NULL COMMENT 'No periods allowed!',
  `category_desc` varchar(100) NOT NULL COMMENT 'Human readable description'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_sessions`
--

CREATE TABLE `auth_sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(60) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `auth_sessions`
--

INSERT INTO `auth_sessions` (`id`, `user_id`, `login_time`, `modified_at`, `ip_address`, `user_agent`) VALUES
('c13b1f540ddeac0fbc6742ccd694ef06fd862d6c', 3360140621, '2021-03-21 23:05:24', '2021-03-21 23:05:24', '177.247.79.140', 'Chrome 89.0.4389.90 on Windows 10'),
('158e254cead0ff15b40655b618b277f5e1750962', 3360140621, '2021-03-21 18:36:37', '2021-03-21 18:42:44', '187.146.156.175', 'Safari 602.1 on iOS'),
('4cd1969e2e2cd4cc9fc7ef832a808d63e5c70022', 1689119523, '2021-03-21 18:37:16', '2021-03-21 19:04:36', '187.146.156.175', 'Chrome 81.0.4044.138 on Android'),
('b4668ff28de3dda3f2f5313bda16a4dcd1d11e58', 1689119523, '2021-03-21 05:42:51', '2021-03-21 06:31:51', '177.247.79.140', 'Chrome 89.0.4389.90 on Android'),
('bb28039b482920c02a42232fb4b8b17c9e4b5e2e', 3360140621, '2021-03-21 05:54:28', '2021-03-21 06:25:57', '177.247.79.140', 'Chrome 89.0.4389.90 on Windows 10'),
('ad047fb81195254bdd71985cf7f8610796df4db2', 2300056648, '2021-03-21 04:09:53', '2021-03-21 05:48:31', '177.247.79.140', 'Chrome 89.0.4389.90 on Windows 10'),
('6be54cdee44f086fdf58cc526088344400ca578c', 3217175010, '2021-03-21 18:55:26', '2021-03-21 18:55:26', '187.146.156.175', 'Chrome 89.0.4389.90 on Windows 10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `carrito` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id`, `id_mesa`, `carrito`) VALUES
(36, 8, '[]'),
(37, 10, '[{\"name\":\"xx lager\",\"price\":25,\"count\":1},{\"name\":\"Indio\",\"price\":25,\"count\":1},{\"name\":\"heineken\",\"price\":28,\"count\":1}]'),
(38, 12, '[{\"name\":\"botana cocina\",\"price\":59,\"count\":1}]'),
(39, 9, '[{\"name\":\"botana cocina\",\"price\":177,\"count\":3}]'),
(41, 11, '[{\"name\":\"botana cocina\",\"price\":118,\"count\":2}]'),
(42, 13, '[{\"name\":\"Indio\",\"price\":25,\"count\":1},{\"name\":\"xx lager\",\"price\":25,\"count\":1},{\"name\":\"tequila 1800 700ml\",\"price\":1200,\"count\":1},{\"name\":\"botana cocina\",\"price\":177,\"count\":3}]'),
(43, 14, '[{\"name\":\"Indio\",\"price\":75,\"count\":3},{\"name\":\"heineken\",\"price\":28,\"count\":1},{\"name\":\"xx lager\",\"price\":25,\"count\":1}]'),
(44, 15, '[{\"name\":\"botana cocina\",\"price\":767,\"count\":13}]'),
(45, 17, '[{\"name\":\"cafe americano\",\"price\":75,\"count\":\"3\"},{\"name\":\"botana cocina\",\"price\":177,\"count\":3},{\"name\":\"Nuggets\",\"price\":165,\"count\":3}]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(255) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'cervezas'),
(2, 'botanas'),
(3, 'tequilas'),
(4, 'brandy'),
(5, 'ron'),
(6, 'vodkas'),
(7, 'wisky'),
(8, 'bebidas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `telefono` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `telefono`) VALUES
(1, 'david ramirez', '2811160339'),
(2, 'camachin', '2811014787'),
(3, 'dsadadad', '54354334');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cocina`
--

CREATE TABLE `cocina` (
  `id` int(11) NOT NULL,
  `producto` varchar(200) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '1',
  `mesa` varchar(90) NOT NULL,
  `status` int(90) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cocina`
--

INSERT INTO `cocina` (`id`, `producto`, `cantidad`, `mesa`, `status`) VALUES
(1, 'botana cocina', 1, 'M1', 5),
(2, 'botana cocina', 1, 'M1', 5),
(3, 'botana cocina', 1, 'M1', 2),
(4, 'botana cocina', 1, '2', 2),
(5, 'botana cocina', 1, '2', 1),
(6, 'botana cocina', 1, '2', 1),
(7, 'botana cocina', 1, '5', 1),
(8, 'Nuggets', 1, '5', 1),
(9, 'botana cocina', 1, '5', 1),
(10, 'Nuggets', 1, '5', 1),
(11, 'Nuggets', 1, '5', 1),
(12, 'botana cocina', 1, '5', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `denied_access`
--

CREATE TABLE `denied_access` (
  `ai` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `reason_code` tinyint(1) UNSIGNED DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles`
--

CREATE TABLE `detalles` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `producto` varchar(90) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(9,1) NOT NULL,
  `importe` decimal(9,1) NOT NULL,
  `costo` decimal(9,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalles`
--

INSERT INTO `detalles` (`id`, `id_venta`, `producto`, `cantidad`, `precio`, `importe`, `costo`) VALUES
(343, 84, 'xx lager', 3, 25.0, 75.0, NULL),
(344, 84, 'Indio', 1, 25.0, 25.0, NULL),
(345, 84, 'alitas bbq', 1, 55.0, 55.0, NULL),
(346, 84, 'nuggets', 1, 55.0, 55.0, NULL),
(347, 85, 'heineken', 3, 28.0, 84.0, NULL),
(348, 85, 'Indio', 3, 25.0, 75.0, NULL),
(349, 85, 'xx lager', 3, 25.0, 75.0, NULL),
(350, 86, 'xx lager', 5, 25.0, 125.0, NULL),
(351, 86, 'Indio', 2, 25.0, 50.0, NULL),
(352, 86, 'heineken', 5, 28.0, 140.0, NULL),
(353, 86, 'Chivas Regal 12 Años Botella 750 ML', 2, 2500.0, 5000.0, NULL),
(354, 87, 'xx lager', 3, 25.0, 75.0, NULL),
(355, 87, 'Indio', 2, 25.0, 50.0, NULL),
(356, 87, 'heineken', 4, 28.0, 112.0, NULL),
(357, 88, 'xx lager', 3, 25.0, 75.0, NULL),
(358, 88, 'Indio', 2, 25.0, 50.0, NULL),
(359, 88, 'heineken', 4, 28.0, 112.0, NULL),
(360, 89, 'xx lager', 3, 25.0, 75.0, NULL),
(361, 89, 'Indio', 2, 25.0, 50.0, NULL),
(362, 89, 'heineken', 4, 28.0, 112.0, NULL),
(363, 90, 'xx lager', 2, 25.0, 50.0, NULL),
(364, 90, 'Indio', 1, 25.0, 25.0, NULL),
(365, 90, 'heineken', 1, 28.0, 28.0, NULL),
(366, 91, 'xx lager', 4, 25.0, 100.0, NULL),
(367, 91, 'Indio', 1, 25.0, 25.0, NULL),
(368, 92, 'xx lager', 3, 25.0, 75.0, NULL),
(369, 92, 'Indio', 1, 25.0, 25.0, NULL),
(370, 93, 'alitas abanero', 1, 55.0, 55.0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ips_on_hold`
--

CREATE TABLE `ips_on_hold` (
  `ai` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_errors`
--

CREATE TABLE `login_errors` (
  `ai` int(10) UNSIGNED NOT NULL,
  `username_or_email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `login_errors`
--

INSERT INTO `login_errors` (`ai`, `username_or_email`, `ip_address`, `time`) VALUES
(12, 'mesero', '177.247.79.140', '2021-03-21 05:51:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `seccion` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `alias` varchar(200) NOT NULL,
  `cuadrara` tinyint(1) NOT NULL,
  `arriba` int(11) NOT NULL DEFAULT '200',
  `izquierda` int(11) NOT NULL DEFAULT '400',
  `alto` int(11) NOT NULL DEFAULT '60',
  `ancho` int(11) NOT NULL DEFAULT '60'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `seccion`, `nombre`, `alias`, `cuadrara`, `arriba`, `izquierda`, `alto`, `ancho`) VALUES
(8, 2, '1', '', 1, 396, 378, 98, 520),
(9, 2, '2', '', 0, 114, 133, 66, 66),
(10, 2, '3', '', 0, 127, 1088, 60, 60),
(11, 1, 'M1', '', 0, 203, 247, 60, 60),
(12, 2, 'm5', '', 1, 91, 861, 60, 60),
(13, 3, 'M1', 'David', 1, 337, 517, 83, 136),
(14, 1, 'm2', '', 1, 98, 1099, 60, 60),
(15, 3, 'm2', '', 0, 106, 923, 73, 77),
(16, 2, 'm4', '', 0, 200, 400, 60, 60),
(17, 4, '5', 'pepe', 1, 200, 400, 60, 60),
(18, 4, '6', '', 0, 200, 400, 60, 60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id` int(11) NOT NULL,
  `metodo` varchar(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id`, `metodo`) VALUES
(1, 'efectivo'),
(2, 'transferencia'),
(4, 'tarjeta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_metodo` int(11) NOT NULL,
  `importe` decimal(9,1) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `id_venta`, `id_metodo`, `importe`, `fecha`) VALUES
(87, 84, 1, 210.0, '2020-10-30 05:36:24'),
(88, 85, 1, 234.0, '2020-10-30 05:37:53'),
(89, 86, 1, 5315.0, '2020-10-30 05:43:21'),
(90, 89, 1, 0.0, '2020-10-30 05:56:09'),
(91, 90, 1, 0.0, '2020-10-30 05:58:17'),
(92, 91, 1, 0.0, '2020-10-30 06:04:23'),
(93, 92, 1, 0.0, '2020-10-30 06:07:21'),
(94, 93, 1, 55.0, '2020-11-02 03:40:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `precio` decimal(9,1) NOT NULL,
  `costo` decimal(9,1) NOT NULL,
  `imagen` varchar(120) NOT NULL,
  `inventariable` tinyint(1) DEFAULT '0',
  `stock` int(11) NOT NULL,
  `id_categoria` int(255) DEFAULT NULL,
  `stock_minimo` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `costo`, `imagen`, `inventariable`, `stock`, `id_categoria`, `stock_minimo`) VALUES
(6, 'xx lager', 25.0, 20.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/xx_lager.jpg', 1, 50, 1, 20),
(7, 'Indio', 25.0, 20.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/indio.jpg', 1, 50, 1, 20),
(8, 'heineken', 28.0, 20.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/heineken.jpg', 1, 50, 1, 20),
(9, 'alitas abanero', 55.0, 30.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/alitas_abanero.jpg', 0, 0, 2, 0),
(10, 'alitas bbq', 55.0, 30.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/alitas_bbq.png', 0, 0, 2, 0),
(11, 'costillitas bbq', 55.0, 40.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/costillas_bbq.jpg', 0, 0, 2, 0),
(12, 'costillas habanero', 55.0, 30.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/costillas_habanero.png', 0, 0, 2, 0),
(13, 'nacho', 55.0, 25.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/nachos.jpg', 0, 0, 2, 0),
(15, 'papas', 55.0, 20.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/papas.PNG', 0, 0, 2, 0),
(16, 'Chivas Regal 12 Años Botella 750 ML', 2500.0, 2000.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/Chivas_Regal_12_Anos_Botella_750_Ml.PNG', 1, 10, 4, 5),
(18, 'Nuggets', 55.0, 30.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/e203f027275e8abdb255cd7c2950146b.PNG', 0, 5, 2, 0),
(21, 'torres 20 700ml', 1500.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/1dbdc14f7b5744fe92b7e604e3d3ee24.PNG', 0, 0, 4, 0),
(22, 'torres 10 700ml', 550.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/1736a7837beb2188a19872b25203947b.PNG', 0, 0, 4, 0),
(23, 'terry 700ml', 550.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/69b2f9973f953ffc58aa1bbfe09b06db.PNG', 0, 0, 4, 0),
(24, 'torres 15 700ml', 900.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/b7a0c73a2d758fd7589d08b851dc4afb.PNG', 0, 0, 4, 0),
(25, 'bacardi blanco 750ml', 350.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/84eb6192b4533b6e48b5cc2727ac8fc2.PNG', 0, 0, 5, 0),
(26, 'cuervo especial 990ml', 400.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/a1226ccf34bba2011a55d89344e2a0e4.PNG', 0, 0, 3, 0),
(27, 'cuervo tradicional 700ml', 450.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/c395e9cbbddbf64557844ad7c93b9e43.PNG', 0, 0, 3, 0),
(28, 'don julio 750ml', 850.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/6230901d5778c0973b17c5ff558fb868.PNG', 0, 0, 3, 0),
(29, 'herradura reposado 700ml', 800.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/12a1801cac17b2b73b2d0df97a294edf.PNG', 0, 0, 3, 0),
(30, 'tequila 1800 700ml', 1200.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/5afa90d68a630bb9c92ddec24a1be39c.PNG', 0, 0, 3, 0),
(31, 'absolute azul 750ml', 500.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/5cc79318456409e1db36665fa469c933.PNG', 0, 0, 6, 0),
(32, 'smirnoff 750ml', 450.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/2821de60278f9af9c3e156eebbe755bf.PNG', 0, 0, 6, 0),
(33, 'botana cocina', 59.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/a6f5fdc6b4957a6ee6a86a3d4137a10f.jpg', 0, 20, 2, 5),
(34, 'cafe americano', 25.0, 0.0, 'https://manhattan.digitalestudio.com.mx/assets/img/productos/0349adcaa3b3b63bee334b5764982ead.jpg', 0, 32, 8, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seccion`
--

CREATE TABLE `seccion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `seccion`
--

INSERT INTO `seccion` (`id`, `nombre`) VALUES
(1, 'canta-bar'),
(2, 'terraza-café'),
(3, 'sala vip'),
(4, 'cafeteria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `username_or_email_on_hold`
--

CREATE TABLE `username_or_email_on_hold` (
  `ai` int(10) UNSIGNED NOT NULL,
  `username_or_email` varchar(255) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(12) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `auth_level` tinyint(3) UNSIGNED NOT NULL,
  `banned` enum('0','1') NOT NULL DEFAULT '0',
  `passwd` varchar(60) NOT NULL,
  `passwd_recovery_code` varchar(60) DEFAULT NULL,
  `passwd_recovery_date` datetime DEFAULT NULL,
  `passwd_modified_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `auth_level`, `banned`, `passwd`, `passwd_recovery_code`, `passwd_recovery_date`, `passwd_modified_at`, `last_login`, `created_at`, `modified_at`) VALUES
(1689119523, 'cocina', '2412312623', 1, '0', '$2y$11$n2kAdZ.9yCLGWMBznvhUJ.vTj4M5UHCTJYKkTVa2yKOYLLGxwMpoK', NULL, NULL, NULL, '2021-03-21 18:37:16', '2021-03-21 05:41:49', '2021-03-21 18:37:16'),
(2300056648, 'superAdmin', 'ramzdave3@gmail.com', 9, '0', '$2y$11$EeCnEviLPuhyQed2Prq8guCHiz4.sS5i2q0iaSqs85iQH35SwPGlS', NULL, NULL, NULL, '2021-03-21 04:09:53', '2020-10-20 19:52:27', '2021-03-21 04:09:53'),
(2342284535, 'admin', '2309138993', 9, '0', '$2y$11$8j3sj.n9HAMTqxRwCz/g3.qSGv1goKjLjo8A0kahEAudQjB9FpUzy', NULL, NULL, NULL, '2021-03-21 18:33:26', '2021-03-21 05:42:19', '2021-03-21 18:33:26'),
(3217175010, 'usuario1', '1882635982', 6, '0', '$2y$11$ba2KdbaWVnfePR9to/kKpuQSDFS/3VgCkwyVItD0seRYL7j3PynT2', NULL, NULL, NULL, '2021-03-21 18:55:26', '2021-03-21 18:55:01', '2021-03-21 18:55:26'),
(3360140621, 'mesero', '3408129263', 6, '0', '$2y$11$rJA8a11ZdzwZ8XujO8oxc.Q0IQwE4E93pUVltDDehvp4zK9H5.k1i', NULL, NULL, NULL, '2021-03-21 23:05:24', '2021-03-21 05:53:41', '2021-03-21 23:05:24');

--
-- Disparadores `users`
--
DELIMITER $$
CREATE TRIGGER `ca_passwd_trigger` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF ((NEW.passwd <=> OLD.passwd) = 0) THEN
        SET NEW.passwd_modified_at = NOW();
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL DEFAULT '1',
  `id_usuario` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `pagada` tinyint(1) NOT NULL DEFAULT '0',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `id_cliente`, `id_usuario`, `id_mesa`, `pagada`, `fecha`) VALUES
(84, 0, 2147483647, 8, 1, '2020-10-30 05:36:24'),
(85, 0, 2147483647, 8, 1, '2020-10-30 05:37:53'),
(86, 0, 2147483647, 8, 1, '2020-10-30 05:43:21'),
(87, 0, 2147483647, 8, 0, '2020-10-30 05:47:20'),
(88, 0, 2147483647, 8, 0, '2020-10-30 05:52:43'),
(89, 0, 2147483647, 8, 0, '2020-10-30 05:56:09'),
(90, 0, 2147483647, 8, 0, '2020-10-30 05:58:17'),
(91, 0, 2147483647, 8, 0, '2020-10-30 06:04:23'),
(92, 0, 2147483647, 8, 0, '2020-10-30 06:07:21'),
(93, 0, 2147483647, 9, 1, '2020-11-02 03:40:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_a_credito`
--

CREATE TABLE `ventas_a_credito` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acl`
--
ALTER TABLE `acl`
  ADD PRIMARY KEY (`ai`),
  ADD KEY `action_id` (`action_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `acl_actions`
--
ALTER TABLE `acl_actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indices de la tabla `acl_categories`
--
ALTER TABLE `acl_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_code` (`category_code`),
  ADD UNIQUE KEY `category_desc` (`category_desc`);

--
-- Indices de la tabla `auth_sessions`
--
ALTER TABLE `auth_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cocina`
--
ALTER TABLE `cocina`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `denied_access`
--
ALTER TABLE `denied_access`
  ADD PRIMARY KEY (`ai`);

--
-- Indices de la tabla `detalles`
--
ALTER TABLE `detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta-detalle` (`id_venta`);

--
-- Indices de la tabla `ips_on_hold`
--
ALTER TABLE `ips_on_hold`
  ADD PRIMARY KEY (`ai`);

--
-- Indices de la tabla `login_errors`
--
ALTER TABLE `login_errors`
  ADD PRIMARY KEY (`ai`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pago-venta` (`id_venta`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `seccion`
--
ALTER TABLE `seccion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `username_or_email_on_hold`
--
ALTER TABLE `username_or_email_on_hold`
  ADD PRIMARY KEY (`ai`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas_a_credito`
--
ALTER TABLE `ventas_a_credito`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acl`
--
ALTER TABLE `acl`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `acl_actions`
--
ALTER TABLE `acl_actions`
  MODIFY `action_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `acl_categories`
--
ALTER TABLE `acl_categories`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cocina`
--
ALTER TABLE `cocina`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `denied_access`
--
ALTER TABLE `denied_access`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles`
--
ALTER TABLE `detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=371;

--
-- AUTO_INCREMENT de la tabla `ips_on_hold`
--
ALTER TABLE `ips_on_hold`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `login_errors`
--
ALTER TABLE `login_errors`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `seccion`
--
ALTER TABLE `seccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `username_or_email_on_hold`
--
ALTER TABLE `username_or_email_on_hold`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT de la tabla `ventas_a_credito`
--
ALTER TABLE `ventas_a_credito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `acl`
--
ALTER TABLE `acl`
  ADD CONSTRAINT `acl_ibfk_1` FOREIGN KEY (`action_id`) REFERENCES `acl_actions` (`action_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acl_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `acl_actions`
--
ALTER TABLE `acl_actions`
  ADD CONSTRAINT `acl_actions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `acl_categories` (`category_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalles`
--
ALTER TABLE `detalles`
  ADD CONSTRAINT `venta-detalle` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pago-venta` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
