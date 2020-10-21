-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 10-10-2020 a las 02:28:17
-- Versión del servidor: 5.5.8
-- Versión de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `pventa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_almacen`
--

CREATE TABLE IF NOT EXISTS `t_almacen` (
  `id_almacen` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `entrada` double NOT NULL,
  `salida` double NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id_almacen`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `t_almacen`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_categorias`
--

CREATE TABLE IF NOT EXISTS `t_categorias` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(30) NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `t_categorias`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_cliente`
--

CREATE TABLE IF NOT EXISTS `t_cliente` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `cliente` varchar(255) NOT NULL,
  `rfc` varchar(18) NOT NULL,
  `contacto` varchar(60) NOT NULL,
  `telefono1` varchar(20) NOT NULL,
  `correo` varchar(60) NOT NULL,
  `telefono2` varchar(20) NOT NULL,
  `correo2` varchar(60) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `id_colonia` int(11) NOT NULL,
  `id_codpos` int(11) NOT NULL,
  `calle` varchar(40) NOT NULL,
  `Noext` varchar(5) NOT NULL,
  `Noint` varchar(5) NOT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `t_cliente`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_codpos`
--

CREATE TABLE IF NOT EXISTS `t_codpos` (
  `id_codpos` int(11) NOT NULL AUTO_INCREMENT,
  `codpos` varchar(5) NOT NULL,
  `id_colonia` int(11) NOT NULL,
  PRIMARY KEY (`id_codpos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `t_codpos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_colonias`
--

CREATE TABLE IF NOT EXISTS `t_colonias` (
  `id_colonia` int(11) NOT NULL AUTO_INCREMENT,
  `colonia` varchar(60) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  PRIMARY KEY (`id_colonia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `t_colonias`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_compras`
--

CREATE TABLE IF NOT EXISTS `t_compras` (
  `id_compra` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `precompra` double NOT NULL,
  `cantidad` double NOT NULL,
  PRIMARY KEY (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `t_compras`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_estado`
--

CREATE TABLE IF NOT EXISTS `t_estado` (
  `id_estado` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(60) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO t_estado VALUES (null, "Aguascalientes"),
(null, "Aguascalientes"),
(null, "Baja California"),
(null, "Baja California Sur"),
(null, "Campeche"),
(null, "Chiapas"),
(null, "Chihuahua"),
(null, "Ciudad de México"),
(null, "Coahuila"),
(null, "Colima"),
(null, "Durango"),
(null, "Estado de México"),
(null, "Guanajuato"),
(null, "Guerrero"),
(null, "Hidalgo"),
(null, "Jalisco"),
(null, "Michoacán"),
(null, "Morelos"),
(null, "Nayarit"),
(null, "Nuevo León"),
(null, "Oaxaca"),
(null, "Puebla"),
(null, "Querétaro"),
(null, "Quintana Roo"),
(null, "San Luis Potosí"),
(null, "Sinaloa"),
(null, "Sonora"),
(null, "Tabasco"),
(null, "Tamaulipas"),
(null, "Tlaxcala"),
(null, "Veracruz"),
(null, "Yucatán"),
(null, "Zacatecas");
--
-- Volcar la base de datos para la tabla `t_estado`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_imagenes`
--

CREATE TABLE IF NOT EXISTS `t_imagenes` (
  `id_imagenes` int(11) NOT NULL AUTO_INCREMENT,
  `imagen` varchar(60) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `tipo` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_imagenes`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `t_imagenes`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_municipio`
--

CREATE TABLE IF NOT EXISTS `t_municipio` (
  `id_municipio` int(11) NOT NULL AUTO_INCREMENT,
  `municipio` varchar(60) NOT NULL,
  `id_estado` int(11) NOT NULL,
  PRIMARY KEY (`id_municipio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `t_municipio`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_pedido`
--

CREATE TABLE IF NOT EXISTS `t_pedido` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `cantidad` double NOT NULL,
  `id_venta` int(11) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `t_pedido`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_producto`
--

CREATE TABLE IF NOT EXISTS `t_producto` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `producto` varchar(150) NOT NULL,
  `preven` double NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` varchar(30) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `t_producto`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_proveedor`
--

CREATE TABLE IF NOT EXISTS `t_proveedor` (
  `id_proveedor` int(11) NOT NULL AUTO_INCREMENT,
  `proveedor` varchar(255) NOT NULL,
  `contacto` varchar(60) NOT NULL,
  `telefono1` varchar(20) NOT NULL,
  `correo1` varchar(60) NOT NULL,
  `telefono2` varchar(20) NOT NULL,
  `correo2` varchar(60) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `id_colonia` int(11) NOT NULL,
  `id_codpos` int(11) NOT NULL,
  `calle` varchar(40) NOT NULL,
  `Noext` varchar(5) NOT NULL,
  `Noint` varchar(5) NOT NULL,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `t_proveedor`
--

INSERT INTO `t_proveedor` (`id_proveedor`, `proveedor`, `contacto`, `telefono1`, `correo1`, `telefono2`, `correo2`, `id_estado`, `id_municipio`, `id_colonia`, `id_codpos`, `calle`, `Noext`, `Noint`) VALUES
(1, 'ALFAWEB', '', '', '', '', '', 0, 0, 0, 0, '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_usuarios`
--

CREATE TABLE IF NOT EXISTS `t_usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `correo` varchar(60) NOT NULL,
  `contrapass` varchar(255) NOT NULL,
  `nevel` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `t_usuarios`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_ventas`
--

CREATE TABLE IF NOT EXISTS `t_ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `facrem` tinyint(4) NOT NULL,
  `monto` double NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `pdfventa` varchar(60) NOT NULL,
  PRIMARY KEY (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `t_ventas`
--
