-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Temps de generació: 13-01-2012 a les 11:41:00
-- Versió del servidor: 5.5.16
-- Versió de PHP : 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de dades: `tmbc_eshop`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `ts_clients`
--

CREATE TABLE IF NOT EXISTS `ts_clients` (
  `id_client` int(11) NOT NULL AUTO_INCREMENT,
  `id_grup` int(11) NOT NULL,
  `usuari` varchar(20) NOT NULL,
  `contrasenya` char(32) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `cognoms` varchar(100) NOT NULL,
  `correu` varchar(30) NOT NULL,
  `data_registre` datetime NOT NULL,
  `data_seen` datetime NOT NULL,
  `tipus_email` enum('html','text') NOT NULL DEFAULT 'html',
  `llista_correu` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 desactivat / 1 activat',
  `codiactivacio` char(32) NOT NULL,
  `estat` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 -> deshabilitat | 1-> habilitat',
  UNIQUE KEY `id` (`id_client`),
  UNIQUE KEY `correu` (`correu`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `ts_clients_grups`
--

CREATE TABLE IF NOT EXISTS `ts_clients_grups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nivell` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Bolcant dades de la taula `ts_clients_grups`
--

INSERT INTO `ts_clients_grups` (`id`, `nivell`) VALUES
(1, 'client'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Estructura de la taula `ts_comandes`
--

CREATE TABLE IF NOT EXISTS `ts_comandes` (
  `id_comanda` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) NOT NULL,
  `id_transportista` int(11) NOT NULL DEFAULT '0',
  `id_dir_entrega` int(11) NOT NULL,
  `id_dir_facturacio` int(11) NOT NULL,
  `id_tipus_pagament` int(11) NOT NULL,
  `import_base` decimal(22,4) NOT NULL,
  `import_impostos` decimal(22,4) NOT NULL,
  `import_transport` decimal(22,4) NOT NULL,
  `import_total` decimal(22,4) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `data_entrega` datetime NOT NULL,
  `data_afegit` datetime NOT NULL,
  UNIQUE KEY `id_comanda` (`id_comanda`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `ts_comandes_estats`
--

CREATE TABLE IF NOT EXISTS `ts_comandes_estats` (
  `id_estat` int(11) NOT NULL,
  `id_idioma` int(11) NOT NULL,
  `nom` varchar(64) NOT NULL,
  UNIQUE KEY `id_estat` (`id_estat`,`id_idioma`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Bolcant dades de la taula `ts_comandes_estats`
--

INSERT INTO `ts_comandes_estats` (`id_estat`, `id_idioma`, `nom`) VALUES
(1, 1, 'Esperant pagament'),
(2, 1, 'Pagament acceptat'),
(3, 1, 'Preparant enviament'),
(4, 1, 'Enviat'),
(5, 1, 'Entregat'),
(6, 1, 'Cancel·lat'),
(1, 2, 'Esperando pago'),
(2, 2, 'Pago aceptado'),
(3, 2, 'Preparando envio'),
(4, 2, 'Enviado'),
(5, 2, 'Entregado'),
(6, 2, 'Cancelado');

-- --------------------------------------------------------

--
-- Estructura de la taula `ts_comandes_moviments`
--

CREATE TABLE IF NOT EXISTS `ts_comandes_moviments` (
  `id_cmoviment` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuari` int(11) NOT NULL,
  `id_comanda` int(11) NOT NULL,
  `id_estat` int(11) NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id_cmoviment`),
  UNIQUE KEY `id_cmoviment` (`id_cmoviment`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `ts_comandes_productes`
--

CREATE TABLE IF NOT EXISTS `ts_comandes_productes` (
  `id_cp` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id comanda producte',
  `id_comanda` int(11) NOT NULL,
  `id_producte` int(11) NOT NULL,
  `id_producte_atr` int(11) NOT NULL,
  `producte_nom` varchar(255) NOT NULL,
  `producte_qtt` int(11) NOT NULL,
  `import_base` decimal(22,4) NOT NULL,
  `import_impostos` decimal(22,4) NOT NULL,
  `import_total` decimal(22,4) NOT NULL,
  `impost_nom` varchar(16) NOT NULL,
  `impost_perc` decimal(10,3) NOT NULL,
  UNIQUE KEY `id_cp` (`id_cp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `ts_config`
--

CREATE TABLE IF NOT EXISTS `ts_config` (
  `id` int(11) NOT NULL,
  `nom_tenda` varchar(100) NOT NULL,
  `idioma_default` int(11) NOT NULL,
  `idioma_admin_default` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Bolcant dades de la taula `ts_config`
--

INSERT INTO `ts_config` (`id`, `nom_tenda`, `idioma_default`, `idioma_admin_default`) VALUES
(1, 'TMBC E-Shop', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de la taula `ts_direccions`
--

CREATE TABLE IF NOT EXISTS `ts_direccions` (
  `id_direccio` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `alias` varchar(32) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `cognoms` varchar(100) NOT NULL,
  `adr` varchar(200) NOT NULL,
  `codipostal` varchar(16) NOT NULL,
  `municipi` varchar(100) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `pais` varchar(32) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `mobil` varchar(20) NOT NULL,
  `correu` varchar(30) NOT NULL,
  `nif` varchar(20) NOT NULL,
  `data_registre` datetime NOT NULL,
  `data_seen` datetime NOT NULL,
  `estat` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 -> deshabilitat | 1-> habilitat',
  UNIQUE KEY `id_direccio` (`id_direccio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `ts_idiomes`
--

CREATE TABLE IF NOT EXISTS `ts_idiomes` (
  `id_idioma` int(11) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `iso` varchar(2) NOT NULL,
  `locale` varchar(10) NOT NULL,
  `format_data` varchar(20) NOT NULL DEFAULT 'Y-m-d H:i:s',
  UNIQUE KEY `id_idioma` (`id_idioma`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Bolcant dades de la taula `ts_idiomes`
--

INSERT INTO `ts_idiomes` (`id_idioma`, `nom`, `iso`, `locale`, `format_data`) VALUES
(1, 'Català', 'ca', 'ca_ES', 'd-m-Y H:i:s'),
(2, 'Español', 'es', 'es_ES', 'd-m-Y H:i:s');

-- --------------------------------------------------------

--
-- Estructura de la taula `ts_tipus_pagaments`
--

CREATE TABLE IF NOT EXISTS `ts_tipus_pagaments` (
  `id_tp` int(11) NOT NULL,
  `id_idioma` int(11) NOT NULL,
  `nom` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Bolcant dades de la taula `ts_tipus_pagaments`
--

INSERT INTO `ts_tipus_pagaments` (`id_tp`, `id_idioma`, `nom`) VALUES
(1, 1, 'Transferència bancària'),
(1, 2, 'Transferencia bancaria'),
(2, 1, 'Contra reembossament'),
(2, 2, 'Contra reembolso');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
