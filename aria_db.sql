-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-10-2025 a las 09:04:32
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
-- Base de datos: `aria_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `home_content`
--

CREATE TABLE `home_content` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(50) NOT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_desc` varchar(300) DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `home_content`
--

INSERT INTO `home_content` (`id`, `slug`, `seo_title`, `seo_desc`, `og_image`, `content`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'PRUEBA', 'PRUEBA', 'PRUEBA', 'PRUEBA', '{\"hero\":{\"titulo\":\"DFSDF\",\"subtitulo\":\"DSFDS\",\"imagen_fondo\":\"FSDSF\",\"cta1\":{\"texto\":\"SDFSDF\",\"url\":\"DSFSDFSDF\"},\"cta2\":{\"texto\":\"FSDFSD\",\"url\":\"FSDFDSF\"}},\"about\":{\"badge\":\"FSDF\",\"titulo\":\"SDFDSF\",\"texto\":\"SDFDSFSDF\",\"imagen\":\"FSDFSDFDS\"},\"features\":[{\"titulo\":\"FSDFSD\",\"descripcion\":\"FDSFSD\",\"icono\":\"FDSFDSFDS\",\"url\":\"FSDFSDF\"}],\"metrics\":[{\"numero\":\"FSDFS\",\"label\":\"FDSFSDF\"}],\"services\":[{\"titulo\":\"SDFFDSS\",\"icono\":\"DFSDF\",\"url\":\"FDSFS\"},{\"titulo\":\"SDFS\",\"icono\":\"SDFDS\",\"url\":\"SDFDSF\"}],\"testimonials\":[{\"cita\":\"SDFDSF\",\"autor\":\"SDFSDF\",\"cargo\":\"SFSDF\",\"foto\":\"SDFDS\"}]}', 1, '2025-10-22 04:14:50', '2025-10-22 04:14:50');

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
(88, 'Carlos', 'APOD', '$2y$10$vF1JvDcPXmwPxVYwRyN4X.N5hSfFdAPZHTY4gNP97Lq/JE7YURTse', 'Jefe de cuartel', '', '', '2025-04-07 02:44:04'),
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
-- Indices de la tabla `home_content`
--
ALTER TABLE `home_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `home_content`
--
ALTER TABLE `home_content`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
