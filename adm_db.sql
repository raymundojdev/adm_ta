-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-10-2025 a las 21:57:23
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

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
(1, 1, 1, 'tortillas', 200.00, 'EFECTIVO', '2025-10-30 01:36:00', 'asdsadsa', 'dsadasdad', 'APLICADO', '2025-10-30 07:36:49', '2025-10-30 07:36:49'),
(2, 2, 1, 'tortillas', 250.00, 'EFECTIVO', '2025-10-31 12:17:00', '-', '-', 'APLICADO', '2025-10-31 18:17:34', '2025-10-31 18:17:34');

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

--
-- Índices para tablas volcadas
--

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
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`pag_id`),
  ADD KEY `idx_pag_ped` (`ped_id`),
  ADD KEY `idx_pag_metodo` (`pag_metodo`),
  ADD KEY `idx_pag_estado` (`pag_estado`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`pro_id`),
  ADD UNIQUE KEY `uk_pro_sku` (`pro_sku`),
  ADD KEY `idx_pro_activo` (`pro_activo`),
  ADD KEY `idx_pro_cat` (`cat_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prov_nombre` (`nombre`),
  ADD KEY `idx_prov_rfc` (`rfc`),
  ADD KEY `idx_proveedores_estatus_nombre` (`estatus`,`nombre`);

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `gas_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `pag_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `pro_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `fk_gas_cor` FOREIGN KEY (`cor_id`) REFERENCES `cortes_caja` (`cor_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gas_suc` FOREIGN KEY (`suc_id`) REFERENCES `sucursales` (`suc_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_pedidos` FOREIGN KEY (`ped_id`) REFERENCES `pedidos` (`ped_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_pro_cat` FOREIGN KEY (`cat_id`) REFERENCES `categorias` (`cat_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
