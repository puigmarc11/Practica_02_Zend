-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Temps de generació: 07-02-2017 a les 23:48:42
-- Versió del servidor: 5.7.11
-- Versió de PHP: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dades: `practicazend`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `alumnes`
--

CREATE TABLE `alumnes` (
  `dni` varchar(9) NOT NULL,
  `password` varchar(9) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `correu` varchar(50) NOT NULL,
  `cumpleanys` date NOT NULL,
  `codi_seguretat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `alumnes`
--

INSERT INTO `alumnes` (`dni`, `password`, `nom`, `correu`, `cumpleanys`, `codi_seguretat`) VALUES
('11111111A', '11111111A', 'Anna', '', '2010-05-12', 'aa713fa341f6786c39b587498449a999'),
('22222222B', '22222222B', 'Albert', 'marcpuig123@gmail.com', '2010-01-23', '24ed200dcfcc1b04fad9d6e361e41ac9'),
('33333333C', '33333333C', 'Maria', '', '2010-12-28', '51c9e6f87ce2a2bdc6e2be26ee89e825'),
('44444444D', '44444444D', 'Marta', '', '2010-11-01', 'a8a5d22acb383aae55937a6936e120b0'),
('55555555E', '55555555E', 'Jordi', '', '2010-06-14', '4e18967c55baab1033250c9f8b0016b1'),
('66666666F', '66666666F', 'Pere', '', '2010-06-13', '39375e8559373aede34f6f15b8dab4dc'),
('77123374G', '77123374G', 'Marc', 'marcpuig123@gmail.com', '2010-05-05', '8a8e04cd95933b42915d5f7897d0f96f'),
('77777777G', '77777777G', 'Carles', '', '2010-07-17', '4b794d8229db8f33a386b3cbba9eeeee'),
('88888888H', '88888888H', 'Monica', '', '2010-10-02', '8a5bfb060ee1f97ecba56d60c049b52d');

-- --------------------------------------------------------

--
-- Estructura de la taula `festa`
--

CREATE TABLE `festa` (
  `id` int(11) NOT NULL,
  `lloc` varchar(100) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `festa`
--

INSERT INTO `festa` (`id`, `lloc`, `data`) VALUES
(39, 'Sant pau', '2017-02-23 02:00:00');

-- --------------------------------------------------------

--
-- Estructura de la taula `organitzadors`
--

CREATE TABLE `organitzadors` (
  `id_festa` int(11) NOT NULL,
  `id_organitzador` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `organitzadors`
--

INSERT INTO `organitzadors` (`id_festa`, `id_organitzador`) VALUES
(39, '11111111A');

-- --------------------------------------------------------

--
-- Estructura de la taula `participant`
--

CREATE TABLE `participant` (
  `id_festa` int(11) NOT NULL,
  `id_participant` varchar(9) NOT NULL,
  `acceptat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `participant`
--

INSERT INTO `participant` (`id_festa`, `id_participant`, `acceptat`) VALUES
(39, '11111111A', 1),
(39, '22222222B', 1),
(39, '33333333C', 0),
(39, '44444444D', 0),
(39, '55555555E', 0),
(39, '66666666F', 0),
(39, '77123374G', 1),
(39, '77777777G', 0),
(39, '88888888H', 0);

--
-- Indexos per taules bolcades
--

--
-- Index de la taula `alumnes`
--
ALTER TABLE `alumnes`
  ADD PRIMARY KEY (`dni`);

--
-- Index de la taula `festa`
--
ALTER TABLE `festa`
  ADD PRIMARY KEY (`id`);

--
-- Index de la taula `organitzadors`
--
ALTER TABLE `organitzadors`
  ADD PRIMARY KEY (`id_festa`,`id_organitzador`),
  ADD KEY `id_organitzador` (`id_organitzador`);

--
-- Index de la taula `participant`
--
ALTER TABLE `participant`
  ADD PRIMARY KEY (`id_festa`,`id_participant`),
  ADD KEY `id_participant` (`id_participant`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `festa`
--
ALTER TABLE `festa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- Restriccions per taules bolcades
--

--
-- Restriccions per la taula `organitzadors`
--
ALTER TABLE `organitzadors`
  ADD CONSTRAINT `organitzadors_ibfk_1` FOREIGN KEY (`id_festa`) REFERENCES `festa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `organitzadors_ibfk_2` FOREIGN KEY (`id_organitzador`) REFERENCES `alumnes` (`dni`);

--
-- Restriccions per la taula `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `participant_ibfk_1` FOREIGN KEY (`id_festa`) REFERENCES `festa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `participant_ibfk_2` FOREIGN KEY (`id_participant`) REFERENCES `alumnes` (`dni`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
