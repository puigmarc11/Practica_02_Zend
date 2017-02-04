-- phpMyAdmin SQL Dump
-- version 3.3.5
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Temps de generació: 03-02-2017 a les 19:25:25
-- Versió del servidor: 5.1.49
-- Versió de PHP : 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de dades: `practicazend`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `alumnes`
--

CREATE TABLE IF NOT EXISTS `alumnes` (
  `dni` varchar(9) NOT NULL,
  `password` varchar(9) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `correu` varchar(50) NOT NULL,
  PRIMARY KEY (`dni`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `alumnes`
--

INSERT INTO `alumnes` (`dni`, `password`, `nom`, `correu`) VALUES
('11111111G', '77123374G', 'Marc', 'marcpuig123@gmail.com'),
('77123370G', '77123374G', 'Marc', 'marcpuig123@gmail.com'),
('77123371G', '77123374G', 'Marc', 'marcpuig123@gmail.com'),
('77123372G', '77123374G', 'Marc', 'marcpuig123@gmail.com'),
('77123373G', '77123374G', 'Marc', 'marcpuig123@gmail.com'),
('77123374G', '77123374G', 'Marc', 'marcpuig123@gmail.com'),
('77123375G', '77123374G', 'Marc', 'marcpuig123@gmail.com'),
('77123376G', '77123374G', 'Marc', 'marcpuig123@gmail.com'),
('77123377G', '77123374G', 'Marc', 'marcpuig123@gmail.com'),
('77123378G', '77123374G', 'Marc', 'marcpuig123@gmail.com'),
('77123379G', '77123374G', 'Marc', 'marcpuig123@gmail.com');
