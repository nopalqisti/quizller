-- Database: quizller
-- Modified version with Admin, Teacher, and Student roles

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
-- Password for all: admin123 (hashed with SHA256)
--

INSERT INTO `admins` (`username`, `password`) VALUES
('opal', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9'),
('ardi', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9'),
('fina', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9'),
('rifki', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`name`) VALUES
('Kelas X IPA 1'),
('Kelas X IPA 2'),
('Kelas XI IPA 1'),
('Kelas XII IPA 1');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers`
-- Password for all: teacher123 (hashed with SHA256)
--

INSERT INTO `teachers` (`name`, `email`, `password`) VALUES
('Budi Santoso', 'budi@teacher.com', 'c21b160bd3ed770f49e706f6b6596e08c00b67379ba0b5b8c84d2b8f3da89f44'),
('Siti Nurhaliza', 'siti@teacher.com', 'c21b160bd3ed770f49e706f6b6596e08c00b67379ba0b5b8c84d2b8f3da89f44'),
('Ahmad Hidayat', 'ahmad@teacher.com', 'c21b160bd3ed770f49e706f6b6596e08c00b67379ba0b5b8c84d2b8f3da89f44'),
('Dewi Lestari', 'dewi@teacher.com', 'c21b160bd3ed770f49e706f6b6596e08c00b67379ba0b5b8c84d2b8f3da89f44'),
('Eko Prasetyo', 'eko@teacher.com', 'c21b160bd3ed770f49e706f6b6596e08c00b67379ba0b5b8c84d2b8f3da89f44');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`),
  KEY `students_fk_class` (`class_id`),
  CONSTRAINT `students_fk_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
-- Password for all: student123 (hashed with SHA256)
--

INSERT INTO `students` (`student_id`, `name`, `password`, `class_id`) VALUES
('S001', 'Andi Wijaya', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 1),
('S002', 'Rina Kusuma', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 1),
('S003', 'Joko Susanto', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 1),
('S004', 'Lina Marlina', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 1),
('S005', 'Rudi Hartono', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 1),
('S006', 'Maya Sari', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 2),
('S007', 'Agus Salim', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 2),
('S008', 'Fitri Handayani', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 2),
('S009', 'Doni Kurniawan', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 2),
('S010', 'Nina Anggraini', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 2),
('S011', 'Hadi Purnomo', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 3),
('S012', 'Sari Rahayu', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 3),
('S013', 'Bambang Sutrisno', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 3),
('S014', 'Tuti Wulandari', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 3),
('S015', 'Yanto Saputra', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 3),
('S016', 'Indah Permatasari', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 4),
('S017', 'Fajar Ramadhan', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 4),
('S018', 'Lia Amelia', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 4),
('S019', 'Wahyu Nugroho', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 4),
('S020', 'Ratna Dewi', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 4);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`name`) VALUES
('PENDING'),
('RUNNING'),
('COMPLETED');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `status_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `total_questions` int(11) NOT NULL DEFAULT 0,
  `class_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tests_fk0` (`teacher_id`),
  KEY `tests_fk1` (`status_id`),
  KEY `tests_fk2` (`class_id`),
  CONSTRAINT `tests_fk0` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`),
  CONSTRAINT `tests_fk1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  CONSTRAINT `tests_fk2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `optionA` varchar(255) NOT NULL,
  `optionB` varchar(255) NOT NULL,
  `optionC` varchar(255) NOT NULL,
  `optionD` varchar(255) NOT NULL,
  `correctAns` varchar(1) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `question_test_mapping`
--

CREATE TABLE `question_test_mapping` (
  `question_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  PRIMARY KEY (`question_id`,`test_id`),
  KEY `question_test_mapping_fk1` (`test_id`),
  CONSTRAINT `question_test_mapping_fk0` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `question_test_mapping_fk1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` varchar(1) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `sa_fk_student` (`student_id`),
  KEY `sa_fk_test` (`test_id`),
  KEY `sa_fk_question` (`question_id`),
  CONSTRAINT `sa_fk_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sa_fk_test` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sa_fk_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `student_test_results`
--

CREATE TABLE `student_test_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_test` (`student_id`, `test_id`),
  KEY `str_fk_student` (`student_id`),
  KEY `str_fk_test` (`test_id`),
  CONSTRAINT `str_fk_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `str_fk_test` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
