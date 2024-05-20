-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 20. Feb 2024 um 17:26
-- Server-Version: 10.4.25-MariaDB
-- PHP-Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `dbarbeitszeitaufzeichnung`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblkunden`
--

CREATE TABLE `tblkunden` (
  `KundenID` int(11) NOT NULL,
  `KundenName` varchar(128) NOT NULL,
  `Farbe` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `tblkunden`
--

INSERT INTO `tblkunden` (`KundenID`, `KundenName`, `Farbe`) VALUES
(1, 'Kunde A', 'rot'),
(2, 'Kunde B', 'blau');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbltaetigkeit`
--

CREATE TABLE `tbltaetigkeit` (
  `TaetigkeitID` int(11) NOT NULL,
  `Taetigkeit` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `tbltaetigkeit`
--

INSERT INTO `tbltaetigkeit` (`TaetigkeitID`, `Taetigkeit`) VALUES
(1, 'Design'),
(2, 'Programmierung'),
(3, 'Schulung');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblzeiten`
--

CREATE TABLE `tblzeiten` (
  `ZeitenID` int(11) NOT NULL,
  `fkKunde` int(11) NOT NULL,
  `Start` datetime NOT NULL,
  `Ende` datetime NOT NULL,
  `fkTaetigkeit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `tblzeiten`
--

INSERT INTO `tblzeiten` (`ZeitenID`, `fkKunde`, `Start`, `Ende`, `fkTaetigkeit`) VALUES
(1, 1, '2024-02-19 08:00:00', '2024-02-19 16:00:00', 2),
(4, 2, '2024-02-20 17:04:00', '2024-02-20 17:08:00', 2),
(5, 2, '2024-02-20 17:16:00', '2024-02-20 17:23:00', 3);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tblkunden`
--
ALTER TABLE `tblkunden`
  ADD PRIMARY KEY (`KundenID`);

--
-- Indizes für die Tabelle `tbltaetigkeit`
--
ALTER TABLE `tbltaetigkeit`
  ADD PRIMARY KEY (`TaetigkeitID`);

--
-- Indizes für die Tabelle `tblzeiten`
--
ALTER TABLE `tblzeiten`
  ADD PRIMARY KEY (`ZeitenID`),
  ADD KEY `fkKunde` (`fkKunde`),
  ADD KEY `fkTaetigkeit` (`fkTaetigkeit`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tblkunden`
--
ALTER TABLE `tblkunden`
  MODIFY `KundenID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `tbltaetigkeit`
--
ALTER TABLE `tbltaetigkeit`
  MODIFY `TaetigkeitID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `tblzeiten`
--
ALTER TABLE `tblzeiten`
  MODIFY `ZeitenID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `tblzeiten`
--
ALTER TABLE `tblzeiten`
  ADD CONSTRAINT `tblzeiten_ibfk_1` FOREIGN KEY (`fkKunde`) REFERENCES `tblkunden` (`KundenID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tblzeiten_ibfk_2` FOREIGN KEY (`fkTaetigkeit`) REFERENCES `tbltaetigkeit` (`TaetigkeitID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
