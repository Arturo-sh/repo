-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-10-2022 a las 07:25:07
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_residencia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `razas_ganado`
--

CREATE TABLE `razas_ganado` (
  `id_raza` int(11) NOT NULL,
  `nombre_raza` varchar(200) NOT NULL,
  `descripcion` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `razas_ganado`
--

INSERT INTO `razas_ganado` (`id_raza`, `nombre_raza`, `descripcion`) VALUES
(1, 'raza suiza', 'Ganado de doble proposito, destinado a produccion de leche y carne');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

CREATE TABLE `registros` (
  `id_bovino` int(11) NOT NULL,
  `arete` varchar(100) NOT NULL,
  `id_raza` int(15) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `imagen_bovino` varchar(300) NOT NULL,
  `sexo` varchar(50) NOT NULL,
  `peso` varchar(100) NOT NULL,
  `edad` varchar(100) NOT NULL,
  `fecha_registro` date NOT NULL,
  `color` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `registros`
--

INSERT INTO `registros` (`id_bovino`, `arete`, `id_raza`, `nombre`, `imagen_bovino`, `sexo`, `peso`, `edad`, `fecha_registro`, `color`) VALUES
(1, '1234567891023456', 1, 'Pinta', 'pinta.jpg', 'Hembra', '400 kg', '2 años y medio', '2022-10-17', 'Blanco con negro\r\n');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(15) NOT NULL,
  `nombre_usuario` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `nivel` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `password`, `salt`, `nivel`) VALUES
(1, 'admin', 'be0c222210216a9072941328a1bcec4763a5eeda', 'ajfsg', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacunas`
--

CREATE TABLE `vacunas` (
  `id_vacuna` int(11) NOT NULL,
  `nombre_vacuna` varchar(200) NOT NULL,
  `dosis_aplicada` varchar(200) NOT NULL,
  `fecha_aplicacion` date NOT NULL,
  `codigo_ganado` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `razas_ganado`
--
ALTER TABLE `razas_ganado`
  ADD PRIMARY KEY (`id_raza`);

--
-- Indices de la tabla `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`id_bovino`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  ADD PRIMARY KEY (`id_vacuna`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `razas_ganado`
--
ALTER TABLE `razas_ganado`
  MODIFY `id_raza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `id_bovino` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  MODIFY `id_vacuna` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
