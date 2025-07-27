-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 27, 2025 at 03:54 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edoc`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `aid` int DEFAULT NULL,
  `aemail` varchar(255) NOT NULL,
  `apassword` varchar(255) DEFAULT NULL,
  `pid` int DEFAULT NULL,
  `docid` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aid`, `aemail`, `apassword`, `pid`, `docid`) VALUES
(NULL, 'admin@azzahra.com', 'admin12', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appoid` int NOT NULL,
  `pid` int DEFAULT NULL,
  `apponum` int DEFAULT NULL,
  `scheduleid` int DEFAULT NULL,
  `appodate` date DEFAULT NULL,
  `approval_status` varchar(20) DEFAULT 'Pending'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appoid`, `pid`, `apponum`, `scheduleid`, `appodate`, `approval_status`) VALUES
(61, 35, 3, 39, '2025-07-18', 'Approved'),
(60, 34, 3, 38, '2025-07-18', 'Approved'),
(59, 33, 3, 37, '2025-07-18', 'Approved'),
(58, 33, 3, 40, '2025-07-18', 'Approved'),
(57, 22, 2, 34, '2025-07-18', 'Approved'),
(56, 22, 3, 43, '2025-07-18', 'Approved'),
(55, 22, 2, 42, '2025-07-18', 'Approved'),
(54, 23, 2, 43, '2025-07-18', 'Approved'),
(53, 23, 2, 36, '2025-07-18', 'Approved'),
(52, 24, 2, 38, '2025-07-18', 'Approved'),
(63, 38, 4, 38, '2025-07-21', 'Approved'),
(51, 24, 2, 39, '2025-07-18', 'Approved'),
(50, 25, 2, 37, '2025-07-18', 'Approved'),
(49, 26, 1, 39, '2025-07-18', 'Approved'),
(48, 27, 2, 40, '2025-07-18', 'Approved'),
(47, 27, 1, 36, '2025-07-18', 'Approved'),
(46, 28, 1, 43, '2025-07-18', 'Approved'),
(44, 29, 1, 40, '2025-07-18', 'Approved'),
(43, 29, 1, 37, '2025-07-18', 'Approved'),
(42, 30, 1, 42, '2025-07-18', 'Approved'),
(62, 36, 2, 41, '2025-07-18', 'Approved'),
(41, 31, 1, 38, '2025-07-18', 'Approved'),
(39, 32, 1, 34, '2025-07-18', 'Approved'),
(38, 32, 1, 41, '2025-07-18', 'Approved'),
(40, 31, 1, 35, '2025-07-18', 'Approved'),
(64, 38, 4, 39, '2025-07-26', 'Approved'),
(66, 30, 3, 41, '2025-07-26', 'Approved'),
(67, 30, 3, 34, '2025-07-26', 'Approved'),
(68, 34, 4, 41, '2025-07-26', 'Approved'),
(69, 35, 4, 43, '2025-07-26', 'Approved'),
(70, 35, 4, 37, '2025-07-26', 'Approved'),
(71, 29, 2, 35, '2025-07-26', 'Approved'),
(72, 33, 3, 35, '2025-07-26', 'Approved'),
(73, 25, 4, 35, '2025-07-26', 'Approved'),
(74, 38, 4, 34, '2025-07-26', 'Rejected'),
(75, 38, 3, 36, '2025-07-27', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `docid` int NOT NULL,
  `docemail` varchar(255) DEFAULT NULL,
  `docname` varchar(255) DEFAULT NULL,
  `docpassword` varchar(255) DEFAULT NULL,
  `docnic` varchar(15) DEFAULT NULL,
  `doctel` varchar(15) DEFAULT NULL,
  `specialties` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`docid`, `docemail`, `docname`, `docpassword`, `docnic`, `doctel`, `specialties`) VALUES
(23, 'syafiqramli@azzahra.com', 'Dr. Syafiq bin Ramli', 'MentalFit99', '820505-12-2222', '019-5544332', 5),
(22, 'noramira@azzahra.com', 'Dr. Nor Amira binti Latif', 'AmiraKidsCare', '900202-03-1111', '016-2233445', 4),
(21, 'khairulnizam@azzahra.com', 'Dr. Khairul Nizam bin Zulkifli', 'NizamPain360', '790707-08-4321', '013-8899776', 3),
(20, 'nuraisyah@azzahra.com', 'Dr. Nur Aisyah binti Mohamad', 'AisyahCare88', '850303-10-5678', '017-6543210', 2),
(19, 'ahmadfaiz@azzahra.com', 'Dr. Ahmad Faiz bin Hassan', 'KlinikFaiz123', '800101-06-1234', '012-3456789', 1);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `pid` int NOT NULL,
  `pemail` varchar(255) DEFAULT NULL,
  `pname` varchar(255) DEFAULT NULL,
  `ppassword` varchar(255) DEFAULT NULL,
  `paddress` varchar(255) DEFAULT NULL,
  `pnic` varchar(15) DEFAULT NULL,
  `pdob` date DEFAULT NULL,
  `ptel` varchar(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`pid`, `pemail`, `pname`, `ppassword`, `paddress`, `pnic`, `pdob`, `ptel`) VALUES
(32, 'faizalharis@mail.com', 'Faizal Haris', 'Faizal*1212', '23, Jalan Seri Damai, Pekan	', '950101-01-3334	', '1995-01-01', '0119090901'),
(31, 'syafiqahanuar@mail.com', 'Syafiqah Anuar', 'Syafiqah$321', '9, Taman Bukit Pelindung, Kuantan	', '980909-09-2223	', '1998-09-09', '0152233554'),
(30, 'amirulhakim@mail.com', 'Amirul Hakim', 'Amirul_55', '6, Kg. Batu Balik, 26600 Pekan	', '921212-12-1112	', '1992-12-12', '0137788990'),
(29, 'nabilasham@mail.com', 'Nabila Shamsuddin', 'NabSecure007', '5, Lorong Melor 3, Kg. Jawa, Pekan	', '990808-08-9876	', '1999-08-08', '0163344556'),
(28, 'zulkarnain@mail.com', 'Zulkarnain Ahmad', 'ZulSafe#321', '75, Taman Cempaka, 26600 Pekan	', '930707-07-2345	', '1993-07-07', '0141122334'),
(27, 'aisyahhumaira@mail.com', 'Aisyah Humaira', 'Humaira@8899', '32, Jalan Aman, Taman Mawar, Pekan	', '940606-06-4567	', '1994-06-06', '0176677885'),
(26, 'danishamir@mail.com', 'Danish Amir', 'Danish2024!', '10, Kg. Permatang Pauh, 26600 Pekan	', '960505-05-7890	', '1996-05-05', '0189988776'),
(25, 'farahnabila@mail.com', 'Farah Nabila', 'Farah@health', '15, Taman Perdana, 26600 Pekan	', '970404-04-4321	', '1997-04-04', '0192233445'),
(24, 'arifhassan@mail.com', 'Muhammad Arif', 'ArifSecure99', '88, Lorong Kemuning, 26600 Pekan	', '950303-03-1111	', '1995-03-03', '0123456789'),
(23, 'nurulizzah@mail.com', 'Nurul	 Izzah', 'Izzah@2025', '7, Taman Semambu, 25050 Kuantan', '980202-08-5678', '1998-02-02', '0138765432'),
(22, 'aimanhakim@mail.com', 'Aiman Hakim', 'Aiman123!', '21, Jalan Seri Indah, 26600 Pekan	', '990101-10-1234	', '1999-01-01', '0112345678'),
(33, 'balqissaad@mail.com', 'Balqis Saad', 'Balqis@Klinik', '77, Jalan Seri Makmur, 26600 Pekan	', '970202-02-4445	', '1997-02-02', '0125656567'),
(34, 'khairulazman@mail.com', 'Khairul Azman	 Aziz', 'AzmanPass#88', '65, Kg. Pulau Rusa, 26600 Pekan	', '931010-10-5556	', '1993-10-10', '0188888777'),
(35, 'haniramli@mail.com', 'Hani Ramli', 'HaniSecure22', '17, Taman Desa Raya, 26600 Pekan', '961111-11-6667	', '1996-11-11', '0172233112'),
(36, 'hakimroslan@mail.com', 'Hakim Roslan', 'Hakim@Doc12', '55, Kg. Ganchong, 26600 Pekan	', '920909-09-7778	', '1992-09-09', '0196677889'),
(38, 'iddinsykr6@gmail.com', 'iddin syakir', 'iddin26_', 'rpt mengkebang', '03062610437', '2003-06-26', '0139260515');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `scheduleid` int NOT NULL,
  `docid` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `scheduledate` date DEFAULT NULL,
  `scheduletime` time DEFAULT NULL,
  `nop` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`scheduleid`, `docid`, `title`, `scheduledate`, `scheduletime`, `nop`) VALUES
(43, 23, 'Obesity Management Session	', '2025-10-20', '09:00:00', 4),
(42, 22, 'Eye & Ear Cleaning for Children	', '2025-10-19', '10:00:00', 5),
(41, 21, 'Male Fertility Counseling', '2025-10-08', '14:00:00', 4),
(40, 20, 'General Women’s Check-up	', '2025-10-06', '11:00:00', 4),
(39, 19, 'Circumcision & Emergency Follow-up	', '2025-09-18', '09:00:00', 5),
(38, 23, 'Mental Health Consultation	', '2025-09-15', '09:00:00', 4),
(37, 22, 'Nebulizer & Pediatric Treatment', '2025-09-11', '14:00:00', 5),
(36, 21, 'Chronic Pain Management', '2025-09-10', '14:00:00', 5),
(35, 20, 'Pregnancy Screening & Health Check', '2025-09-07', '10:00:00', 4),
(34, 19, 'Minor Surgery & Wound Care', '2025-09-06', '08:00:00', 5);

-- --------------------------------------------------------

--
-- Table structure for table `specialties`
--

CREATE TABLE `specialties` (
  `id` int NOT NULL,
  `sname` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `specialties`
--

INSERT INTO `specialties` (`id`, `sname`) VALUES
(1, 'Emergency & Minor Surgery'),
(2, 'Women’s Health'),
(3, 'Pain & Fertility'),
(4, 'Child & Respiratory Care'),
(5, 'Mental Health & Obesity');

-- --------------------------------------------------------

--
-- Table structure for table `webuser`
--

CREATE TABLE `webuser` (
  `email` varchar(255) NOT NULL,
  `usertype` char(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `webuser`
--

INSERT INTO `webuser` (`email`, `usertype`) VALUES
('admin@azzahra.com', 'a'),
('balqissaad@mail.com', 'p'),
('faizalharis@mail.com', 'p'),
('syafiqahanuar@mail.com', 'p'),
('amirulhakim@mail.com', 'p'),
('nabilasham@mail.com', 'p'),
('zulkarnain@mail.com', 'p'),
('aisyahhumaira@mail.com', 'p'),
('danishamir@mail.com', 'p'),
('syafiqramli@azzahra.com', 'd'),
('noramira@azzahra.com', 'd'),
('khairulnizam@azzahra.com', 'd'),
('nuraisyah@azzahra.com', 'd'),
('ahmadfaiz@azzahra.com', 'd'),
('farahnabila@mail.com', 'p'),
('arifhassan@mail.com', 'p'),
('nurulizzah@mail.com', 'p'),
('aimanhakim@mail.com', 'p'),
('khairulazman@mail.com', 'p'),
('haniramli@mail.com', 'p'),
('hakimroslan@mail.com', 'p'),
('iddinsykr6@gmail.com', 'p');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aemail`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appoid`),
  ADD KEY `pid` (`pid`),
  ADD KEY `scheduleid` (`scheduleid`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`docid`),
  ADD KEY `specialties` (`specialties`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`scheduleid`),
  ADD KEY `docid` (`docid`);

--
-- Indexes for table `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webuser`
--
ALTER TABLE `webuser`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appoid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `docid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `pid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `scheduleid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
