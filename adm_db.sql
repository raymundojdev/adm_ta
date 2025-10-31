-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-10-2025 a las 09:10:05
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
-- Base de datos: `adm_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `cat_id` bigint(20) UNSIGNED NOT NULL,
  `cat_nombre` varchar(120) NOT NULL,
  `cat_activa` tinyint(1) NOT NULL DEFAULT 1,
  `cat_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `cat_actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`cat_id`, `cat_nombre`, `cat_activa`, `cat_creado_en`, `cat_actualizado_en`) VALUES
(1, 'Verdura', 1, '2025-10-30 00:29:05', '2025-10-30 01:11:17'),
(2, 'Tacos', 1, '2025-10-31 02:48:08', '2025-10-31 02:48:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `cli_id` bigint(20) UNSIGNED NOT NULL,
  `cli_nombre` varchar(160) NOT NULL,
  `cli_telefono` varchar(20) DEFAULT NULL,
  `cli_email` varchar(160) DEFAULT NULL,
  `cli_puntos` int(11) NOT NULL DEFAULT 0,
  `cli_activo` tinyint(1) NOT NULL DEFAULT 1,
  `cli_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `cli_actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`cli_id`, `cli_nombre`, `cli_telefono`, `cli_email`, `cli_puntos`, `cli_activo`, `cli_creado_en`, `cli_actualizado_en`) VALUES
(1, 'Paul rodriguez', '2292929', 'email@gmail.com', 2, 1, '2025-10-30 01:30:42', '2025-10-30 04:15:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cortes_caja`
--

CREATE TABLE `cortes_caja` (
  `cor_id` bigint(20) UNSIGNED NOT NULL,
  `suc_id` bigint(20) UNSIGNED NOT NULL,
  `usr_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cor_turno` enum('MAÑANA','TARDE','NOCHE') NOT NULL DEFAULT 'MAÑANA',
  `cor_inicio` datetime NOT NULL,
  `cor_fin` datetime DEFAULT NULL,
  `cor_fondo_inicial` decimal(12,2) NOT NULL DEFAULT 0.00,
  `cor_total_efectivo` decimal(12,2) DEFAULT NULL,
  `cor_total_tarjeta` decimal(12,2) DEFAULT NULL,
  `cor_total_transfer` decimal(12,2) DEFAULT NULL,
  `cor_total_mixto` decimal(12,2) DEFAULT NULL,
  `cor_total_sistema` decimal(12,2) DEFAULT NULL,
  `cor_gastos` decimal(12,2) DEFAULT NULL,
  `cor_ingresos_extra` decimal(12,2) DEFAULT NULL,
  `cor_total_declarado` decimal(12,2) DEFAULT NULL,
  `cor_diferencia` decimal(12,2) DEFAULT NULL,
  `cor_observaciones` varchar(255) DEFAULT NULL,
  `cor_estado` enum('ABIERTO','CERRADO') NOT NULL DEFAULT 'ABIERTO',
  `cor_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `cor_actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cortes_caja`
--

INSERT INTO `cortes_caja` (`cor_id`, `suc_id`, `usr_id`, `cor_turno`, `cor_inicio`, `cor_fin`, `cor_fondo_inicial`, `cor_total_efectivo`, `cor_total_tarjeta`, `cor_total_transfer`, `cor_total_mixto`, `cor_total_sistema`, `cor_gastos`, `cor_ingresos_extra`, `cor_total_declarado`, `cor_diferencia`, `cor_observaciones`, `cor_estado`, `cor_creado_en`, `cor_actualizado_en`) VALUES
(1, 1, NULL, 'TARDE', '2025-10-30 01:20:00', '2025-10-30 07:28:00', 1500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 2000.00, 100.00, 6040.00, 6440.00, '', 'CERRADO', '2025-10-30 07:20:30', '2025-10-30 07:29:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `detv_id` bigint(20) UNSIGNED NOT NULL,
  `ven_id` bigint(20) UNSIGNED NOT NULL,
  `pro_id` bigint(20) UNSIGNED NOT NULL,
  `detv_cantidad` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `detv_precio_unitario` decimal(10,2) NOT NULL DEFAULT 0.00,
  `detv_total` decimal(10,2) GENERATED ALWAYS AS (`detv_cantidad` * `detv_precio_unitario`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `gas_id` bigint(20) UNSIGNED NOT NULL,
  `suc_id` bigint(20) UNSIGNED NOT NULL,
  `cor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gas_concepto` varchar(160) NOT NULL,
  `gas_monto` decimal(12,2) NOT NULL,
  `gas_metodo` enum('EFECTIVO','TARJETA','TRANSFERENCIA','OTRO') NOT NULL DEFAULT 'EFECTIVO',
  `gas_fecha` datetime NOT NULL,
  `gas_comprobante` varchar(255) DEFAULT NULL,
  `gas_nota` varchar(255) DEFAULT NULL,
  `gas_estado` enum('APLICADO','ANULADO') NOT NULL DEFAULT 'APLICADO',
  `gas_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `gas_actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`gas_id`, `suc_id`, `cor_id`, `gas_concepto`, `gas_monto`, `gas_metodo`, `gas_fecha`, `gas_comprobante`, `gas_nota`, `gas_estado`, `gas_creado_en`, `gas_actualizado_en`) VALUES
(1, 1, 1, 'tortillas', 200.00, 'EFECTIVO', '2025-10-30 01:36:00', 'asdsadsa', 'dsadasdad', 'APLICADO', '2025-10-30 07:36:49', '2025-10-30 07:36:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metas_tacos`
--

CREATE TABLE `metas_tacos` (
  `met_id` bigint(20) UNSIGNED NOT NULL,
  `suc_id` bigint(20) UNSIGNED NOT NULL,
  `cat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `met_fecha` date NOT NULL,
  `met_cantidad` int(10) UNSIGNED NOT NULL,
  `met_nota` varchar(160) DEFAULT NULL,
  `met_activa` tinyint(1) NOT NULL DEFAULT 1,
  `met_cat_id_norm` bigint(20) UNSIGNED GENERATED ALWAYS AS (ifnull(`cat_id`,0)) STORED,
  `met_pro_id_norm` bigint(20) UNSIGNED GENERATED ALWAYS AS (ifnull(`pro_id`,0)) STORED,
  `met_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `met_actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metas_tacos`
--

INSERT INTO `metas_tacos` (`met_id`, `suc_id`, `cat_id`, `pro_id`, `met_fecha`, `met_cantidad`, `met_nota`, `met_activa`, `met_creado_en`, `met_actualizado_en`) VALUES
(1, 2, 2, 1, '2025-10-30', 100, NULL, 1, '2025-10-31 03:34:21', '2025-10-31 03:52:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `pag_id` bigint(20) UNSIGNED NOT NULL,
  `ped_id` bigint(20) UNSIGNED NOT NULL,
  `pag_monto` decimal(12,2) NOT NULL,
  `pag_metodo` enum('EFECTIVO','TARJETA','TRANSFERENCIA','MIXTO') NOT NULL DEFAULT 'EFECTIVO',
  `pag_recibido` decimal(12,2) DEFAULT NULL,
  `pag_cambio` decimal(12,2) DEFAULT NULL,
  `pag_referencia` varchar(80) DEFAULT NULL,
  `pag_estado` enum('APLICADO','ANULADO') NOT NULL DEFAULT 'APLICADO',
  `pag_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `pag_actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`pag_id`, `ped_id`, `pag_monto`, `pag_metodo`, `pag_recibido`, `pag_cambio`, `pag_referencia`, `pag_estado`, `pag_creado_en`, `pag_actualizado_en`) VALUES
(1, 1, 200.00, 'EFECTIVO', 200.00, 0.00, '1000', 'APLICADO', '2025-10-30 07:07:21', '2025-10-30 07:07:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `ped_id` bigint(20) UNSIGNED NOT NULL,
  `cli_id` bigint(20) UNSIGNED DEFAULT NULL,
  `suc_id` bigint(20) UNSIGNED NOT NULL,
  `ped_folio` varchar(40) NOT NULL,
  `ped_tipo` enum('MOSTRADOR','ONLINE') NOT NULL DEFAULT 'MOSTRADOR',
  `ped_estado` enum('PENDIENTE','PAGADO','CANCELADO') NOT NULL DEFAULT 'PENDIENTE',
  `ped_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `ped_puntos_generados` int(11) NOT NULL DEFAULT 0,
  `ped_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `ped_actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`ped_id`, `cli_id`, `suc_id`, `ped_folio`, `ped_tipo`, `ped_estado`, `ped_total`, `ped_puntos_generados`, `ped_creado_en`, `ped_actualizado_en`) VALUES
(1, 1, 2, '1000', 'MOSTRADOR', 'PAGADO', 200.00, 1, '2025-10-30 04:15:54', '2025-10-30 07:08:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_detalles`
--

CREATE TABLE `pedidos_detalles` (
  `pde_id` bigint(20) UNSIGNED NOT NULL,
  `ped_id` bigint(20) UNSIGNED NOT NULL,
  `pro_id` bigint(20) UNSIGNED NOT NULL,
  `pde_cantidad` int(11) NOT NULL DEFAULT 1,
  `pde_precio` decimal(12,2) NOT NULL DEFAULT 0.00,
  `pde_subtotal` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_detalles`
--

INSERT INTO `pedidos_detalles` (`pde_id`, `ped_id`, `pro_id`, `pde_cantidad`, `pde_precio`, `pde_subtotal`) VALUES
(3, 1, 1, 1, 200.00, 200.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precios_productos`
--

CREATE TABLE `precios_productos` (
  `ppr_id` bigint(20) UNSIGNED NOT NULL,
  `pro_id` bigint(20) UNSIGNED NOT NULL,
  `suc_id` bigint(20) UNSIGNED NOT NULL,
  `ppr_precio` decimal(12,2) NOT NULL,
  `ppr_vigente_desde` datetime NOT NULL DEFAULT current_timestamp(),
  `ppr_vigente_hasta` datetime DEFAULT NULL,
  `ppr_activo` tinyint(1) NOT NULL DEFAULT 1,
  `ppr_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `ppr_actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `pro_id` bigint(20) UNSIGNED NOT NULL,
  `pro_sku` varchar(64) NOT NULL,
  `pro_nombre` varchar(160) NOT NULL,
  `cat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pro_imagen` varchar(255) DEFAULT NULL,
  `pro_activo` tinyint(1) NOT NULL DEFAULT 1,
  `pro_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `pro_actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`pro_id`, `pro_sku`, `pro_nombre`, `cat_id`, `pro_imagen`, `pro_activo`, `pro_creado_en`, `pro_actualizado_en`) VALUES
(1, '1000', 'taco de lengua', 1, NULL, 1, '2025-10-30 00:29:32', '2025-10-30 00:29:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promociones`
--

CREATE TABLE `promociones` (
  `prm_id` bigint(20) UNSIGNED NOT NULL,
  `prm_nombre` varchar(160) NOT NULL,
  `prm_tipo` enum('porcentaje','fijo','combo') NOT NULL DEFAULT 'porcentaje',
  `prm_valor` decimal(12,2) NOT NULL DEFAULT 0.00,
  `prm_activa` tinyint(1) NOT NULL DEFAULT 1,
  `prm_inicio` date DEFAULT NULL,
  `prm_fin` date DEFAULT NULL,
  `prm_codigo` varchar(60) DEFAULT NULL,
  `prm_descripcion` varchar(255) DEFAULT NULL,
  `prm_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `prm_actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promociones`
--

INSERT INTO `promociones` (`prm_id`, `prm_nombre`, `prm_tipo`, `prm_valor`, `prm_activa`, `prm_inicio`, `prm_fin`, `prm_codigo`, `prm_descripcion`, `prm_creado_en`, `prm_actualizado_en`) VALUES
(1, 'Promo Llenador', 'combo', 155.00, 1, '2025-10-29', '2025-10-30', NULL, '3 TACOS DE CABEZA + CALDO DE RES + AGUA DE SABOR', '2025-10-30 00:35:01', '2025-10-30 01:18:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` bigint(20) NOT NULL,
  `nombre` varchar(160) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `rfc` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `estatus` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  `fecha_alta` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `telefono`, `email`, `rfc`, `direccion`, `estatus`, `fecha_alta`) VALUES
(1, 'Tortillería La Esquina', '229-000-0001', NULL, NULL, NULL, 'ACTIVO', '2025-10-28 21:44:58'),
(2, 'Carnes Don Pepe', '229-000-0002', NULL, NULL, NULL, 'ACTIVO', '2025-10-28 21:44:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntos_clientes`
--

CREATE TABLE `puntos_clientes` (
  `pcli_id` bigint(20) UNSIGNED NOT NULL,
  `cli_id` bigint(20) UNSIGNED NOT NULL,
  `pcli_fecha` date NOT NULL,
  `pcli_puntos` int(10) UNSIGNED NOT NULL,
  `pcli_descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursales`
--

CREATE TABLE `sucursales` (
  `suc_id` bigint(20) UNSIGNED NOT NULL,
  `suc_nombre` varchar(120) NOT NULL,
  `suc_direccion` varchar(255) DEFAULT NULL,
  `suc_activa` tinyint(1) NOT NULL DEFAULT 1,
  `suc_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `suc_actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sucursales`
--

INSERT INTO `sucursales` (`suc_id`, `suc_nombre`, `suc_direccion`, `suc_activa`, `suc_creado_en`, `suc_actualizado_en`) VALUES
(1, 'Sucursal Centro', 'Av. Principal 123, Centro', 1, '2025-10-30 00:12:18', '2025-10-30 00:12:18'),
(2, 'Sucursal Norte', 'Calle 45 #210, Col. Norte', 1, '2025-10-30 00:12:18', '2025-10-30 00:12:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `usuario` text NOT NULL,
  `password` varchar(500) NOT NULL,
  `perfil` text NOT NULL,
  `departamento` varchar(300) NOT NULL,
  `curp` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `perfil`, `departamento`, `curp`, `fecha`) VALUES
(1, 'fernando', 'nando', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 'Area de sistemas modficado', 'PEJL920101HDFLRN01', '2025-03-23 02:51:07'),
(101, 'Marcos', 'HCLM', '$2y$10$v/uNdJaY1EyDHb4.xLpcYua6Q4LixKPiAotwBVQsCY7ik/cxndUVG', 'Jefe de cuartel', '', '', '2025-04-24 16:32:45'),
(102, 'Juana', 'LJJ', '$2y$10$0akAGep9qNuweuGRMacxqebpHG3Ri8PmMtyRFui/66KIc8p0YeYAy', 'Jefe de cuartel', '', '', '2025-04-24 16:32:45'),
(104, 'Lupe', 'GPGL', '$2y$10$JT/SSigsnqVKHIdDz3aYh.Ng0nYhhOSAEL1AJCdGC279xAiixVs0i', 'Jefe de cuartel', '', '', '2025-04-24 17:13:56'),
(105, 'Marcos', 'HCLM', '$2y$10$dS9qhUTwIS/FTK8zHatDlO.qH6lc025h/MFhlt9phFjDQCTIaSC2q', 'Jefe de cuartel', '', '', '2025-04-24 17:13:56'),
(106, 'Juana', 'LJJ', '$2y$10$lPG.E2PIJpU7Um/Ui7Cj6OM26cdOYt3vq.ek5o3AkvV2BZKHH7IHu', 'Jefe de cuartel', '', '', '2025-04-24 17:13:56'),
(107, 'Florencia', 'SBF', '$2y$10$8UL2ovU2Ck3kzaPlFThfyOmcln2iLcWe4CW33aFS0KiGipsJBUF/S', 'Jefe de cuartel', '', '', '2025-04-24 17:13:56'),
(108, 'Raul', 'raul', '12345', 'Administrador', '', '', '2025-10-21 23:56:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `ven_id` bigint(20) UNSIGNED NOT NULL,
  `suc_id` bigint(20) UNSIGNED NOT NULL,
  `cli_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ven_fecha` date NOT NULL,
  `ven_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ven_tacos_vendidos` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ven_puntos_otorgados` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ven_met_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ven_activa` tinyint(1) NOT NULL DEFAULT 1,
  `ven_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `ven_actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`ven_id`, `suc_id`, `cli_id`, `ven_fecha`, `ven_total`, `ven_tacos_vendidos`, `ven_puntos_otorgados`, `ven_met_id`, `ven_activa`, `ven_creado_en`, `ven_actualizado_en`) VALUES
(1, 1, 1, '2025-10-31', 200.00, 10, 5, NULL, 1, '2025-10-31 07:41:08', '2025-10-31 07:41:08'),
(2, 2, 1, '2025-10-17', 100.00, 50, 5, NULL, 1, '2025-10-31 07:47:16', '2025-10-31 07:47:16');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `uk_cat_nombre` (`cat_nombre`),
  ADD KEY `idx_cat_activa` (`cat_activa`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cli_id`),
  ADD UNIQUE KEY `uk_cli_tel` (`cli_telefono`),
  ADD UNIQUE KEY `uk_cli_email` (`cli_email`),
  ADD KEY `idx_cli_activo` (`cli_activo`);

--
-- Indices de la tabla `cortes_caja`
--
ALTER TABLE `cortes_caja`
  ADD PRIMARY KEY (`cor_id`),
  ADD KEY `idx_cor_suc` (`suc_id`),
  ADD KEY `idx_cor_estado` (`cor_estado`),
  ADD KEY `idx_cor_rango` (`cor_inicio`,`cor_fin`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`detv_id`),
  ADD KEY `fk_detalle_ventas_ven_id` (`ven_id`),
  ADD KEY `fk_detalle_ventas_pro_id` (`pro_id`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`gas_id`),
  ADD KEY `idx_gas_suc` (`suc_id`),
  ADD KEY `idx_gas_cor` (`cor_id`),
  ADD KEY `idx_gas_fecha` (`gas_fecha`),
  ADD KEY `idx_gas_estado` (`gas_estado`);

--
-- Indices de la tabla `metas_tacos`
--
ALTER TABLE `metas_tacos`
  ADD PRIMARY KEY (`met_id`),
  ADD UNIQUE KEY `uk_metas_tacos` (`suc_id`,`met_fecha`,`met_cat_id_norm`,`met_pro_id_norm`),
  ADD KEY `idx_metas_tacos_suc_id` (`suc_id`),
  ADD KEY `idx_metas_tacos_met_fecha` (`met_fecha`),
  ADD KEY `fk_metas_tacos_cat_id` (`cat_id`),
  ADD KEY `fk_metas_tacos_pro_id` (`pro_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`pag_id`),
  ADD KEY `idx_pag_ped` (`ped_id`),
  ADD KEY `idx_pag_metodo` (`pag_metodo`),
  ADD KEY `idx_pag_estado` (`pag_estado`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`ped_id`),
  ADD UNIQUE KEY `uk_ped_folio` (`ped_folio`),
  ADD KEY `idx_ped_estado` (`ped_estado`),
  ADD KEY `idx_ped_suc` (`suc_id`),
  ADD KEY `idx_ped_cli` (`cli_id`);

--
-- Indices de la tabla `pedidos_detalles`
--
ALTER TABLE `pedidos_detalles`
  ADD PRIMARY KEY (`pde_id`),
  ADD KEY `idx_pde_ped` (`ped_id`),
  ADD KEY `idx_pde_pro` (`pro_id`);

--
-- Indices de la tabla `precios_productos`
--
ALTER TABLE `precios_productos`
  ADD PRIMARY KEY (`ppr_id`),
  ADD UNIQUE KEY `uk_ppr_prod_suc_activo` (`pro_id`,`suc_id`,`ppr_activo`),
  ADD KEY `idx_ppr_suc` (`suc_id`),
  ADD KEY `idx_ppr_vigencia` (`ppr_activo`,`ppr_vigente_desde`,`ppr_vigente_hasta`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`pro_id`),
  ADD UNIQUE KEY `uk_pro_sku` (`pro_sku`),
  ADD KEY `idx_pro_activo` (`pro_activo`),
  ADD KEY `idx_pro_cat` (`cat_id`);

--
-- Indices de la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`prm_id`),
  ADD UNIQUE KEY `uk_prm_codigo` (`prm_codigo`),
  ADD KEY `idx_prm_activa` (`prm_activa`),
  ADD KEY `idx_prm_vigencia` (`prm_inicio`,`prm_fin`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prov_nombre` (`nombre`),
  ADD KEY `idx_prov_rfc` (`rfc`),
  ADD KEY `idx_proveedores_estatus_nombre` (`estatus`,`nombre`);

--
-- Indices de la tabla `puntos_clientes`
--
ALTER TABLE `puntos_clientes`
  ADD PRIMARY KEY (`pcli_id`),
  ADD KEY `fk_puntos_clientes_cli_id` (`cli_id`);

--
-- Indices de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  ADD PRIMARY KEY (`suc_id`),
  ADD KEY `idx_suc_activa` (`suc_activa`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`ven_id`),
  ADD KEY `fk_ventas_suc_id` (`suc_id`),
  ADD KEY `fk_ventas_cli_id` (`cli_id`),
  ADD KEY `fk_ventas_met_id` (`ven_met_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `cat_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cli_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cortes_caja`
--
ALTER TABLE `cortes_caja`
  MODIFY `cor_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `detv_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `gas_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `metas_tacos`
--
ALTER TABLE `metas_tacos`
  MODIFY `met_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `pag_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `ped_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedidos_detalles`
--
ALTER TABLE `pedidos_detalles`
  MODIFY `pde_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `precios_productos`
--
ALTER TABLE `precios_productos`
  MODIFY `ppr_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `pro_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `promociones`
--
ALTER TABLE `promociones`
  MODIFY `prm_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `puntos_clientes`
--
ALTER TABLE `puntos_clientes`
  MODIFY `pcli_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  MODIFY `suc_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `ven_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cortes_caja`
--
ALTER TABLE `cortes_caja`
  ADD CONSTRAINT `fk_cor_suc` FOREIGN KEY (`suc_id`) REFERENCES `sucursales` (`suc_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `fk_detalle_ventas_pro_id` FOREIGN KEY (`pro_id`) REFERENCES `productos` (`pro_id`),
  ADD CONSTRAINT `fk_detalle_ventas_ven_id` FOREIGN KEY (`ven_id`) REFERENCES `ventas` (`ven_id`);

--
-- Filtros para la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `fk_gas_cor` FOREIGN KEY (`cor_id`) REFERENCES `cortes_caja` (`cor_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gas_suc` FOREIGN KEY (`suc_id`) REFERENCES `sucursales` (`suc_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `metas_tacos`
--
ALTER TABLE `metas_tacos`
  ADD CONSTRAINT `fk_metas_tacos_cat_id` FOREIGN KEY (`cat_id`) REFERENCES `categorias` (`cat_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_metas_tacos_pro_id` FOREIGN KEY (`pro_id`) REFERENCES `productos` (`pro_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_metas_tacos_suc_id` FOREIGN KEY (`suc_id`) REFERENCES `sucursales` (`suc_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_pedidos` FOREIGN KEY (`ped_id`) REFERENCES `pedidos` (`ped_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_ped_cli` FOREIGN KEY (`cli_id`) REFERENCES `clientes` (`cli_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ped_suc` FOREIGN KEY (`suc_id`) REFERENCES `sucursales` (`suc_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos_detalles`
--
ALTER TABLE `pedidos_detalles`
  ADD CONSTRAINT `fk_pde_ped` FOREIGN KEY (`ped_id`) REFERENCES `pedidos` (`ped_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pde_pro` FOREIGN KEY (`pro_id`) REFERENCES `productos` (`pro_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `precios_productos`
--
ALTER TABLE `precios_productos`
  ADD CONSTRAINT `fk_ppr_pro` FOREIGN KEY (`pro_id`) REFERENCES `productos` (`pro_id`),
  ADD CONSTRAINT `fk_ppr_suc` FOREIGN KEY (`suc_id`) REFERENCES `sucursales` (`suc_id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_pro_cat` FOREIGN KEY (`cat_id`) REFERENCES `categorias` (`cat_id`);

--
-- Filtros para la tabla `puntos_clientes`
--
ALTER TABLE `puntos_clientes`
  ADD CONSTRAINT `fk_puntos_clientes_cli_id` FOREIGN KEY (`cli_id`) REFERENCES `clientes` (`cli_id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_ventas_cli_id` FOREIGN KEY (`cli_id`) REFERENCES `clientes` (`cli_id`),
  ADD CONSTRAINT `fk_ventas_met_id` FOREIGN KEY (`ven_met_id`) REFERENCES `metas_tacos` (`met_id`),
  ADD CONSTRAINT `fk_ventas_suc_id` FOREIGN KEY (`suc_id`) REFERENCES `sucursales` (`suc_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
