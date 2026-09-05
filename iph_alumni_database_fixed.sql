-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: pmarkbdc_alumni
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(150) DEFAULT NULL,
  `user_role` varchar(50) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_action` (`action`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,1,'Mahmudur Rahman Imon','admin','USER_LOGIN','User logged in: admin@iphalumni.org','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26 18:10:33'),(2,1,'Mahmudur Rahman Imon','admin','USER_LOGIN','User logged in: admin@iphalumni.org','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26 18:29:43'),(3,1,'Mahmudur Rahman Imon','admin','USER_LOGIN','User logged in: admin@iphalumni.org','116.206.59.205','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-27 12:13:59'),(4,1,'Mahmudur Rahman Imon','admin','USER_LOGIN','User logged in: admin@iphalumni.org','103.230.105.55','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36','2026-09-03 15:22:05');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alumni_education`
--

DROP TABLE IF EXISTS `alumni_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumni_education` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alumni_profile_id` int(10) unsigned NOT NULL,
  `degree` varchar(200) NOT NULL,
  `institution` varchar(300) NOT NULL,
  `field_of_study` varchar(200) DEFAULT NULL,
  `graduation_year` year(4) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_edu_profile` (`alumni_profile_id`),
  CONSTRAINT `fk_edu_profile` FOREIGN KEY (`alumni_profile_id`) REFERENCES `alumni_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumni_education`
--

LOCK TABLES `alumni_education` WRITE;
/*!40000 ALTER TABLE `alumni_education` DISABLE KEYS */;
INSERT INTO `alumni_education` VALUES (1,1,'BSc in Health Technology','Institute Of Public Health','Laboratory',2026,NULL,0,'2026-07-23 17:45:35','2026-07-23 17:45:35'),(2,1,'HSC','National College','Science',2018,NULL,0,'2026-07-23 17:45:56','2026-07-23 17:45:56'),(3,1,'SSC','Khilgaon High School','Science',2016,NULL,0,'2026-07-23 17:46:11','2026-07-23 17:46:11'),(5,1,'BSc in Health Technology','Institute Of Public Health','Laboratory',NULL,NULL,1,'2026-07-26 18:19:08','2026-07-26 18:19:08');
/*!40000 ALTER TABLE `alumni_education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alumni_employment`
--

DROP TABLE IF EXISTS `alumni_employment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumni_employment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alumni_profile_id` int(10) unsigned NOT NULL,
  `job_title` varchar(200) NOT NULL,
  `organization` varchar(300) NOT NULL,
  `department` varchar(200) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `start_year` year(4) DEFAULT NULL,
  `end_year` year(4) DEFAULT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_emp_profile` (`alumni_profile_id`),
  KEY `idx_emp_current` (`is_current`),
  CONSTRAINT `fk_emp_profile` FOREIGN KEY (`alumni_profile_id`) REFERENCES `alumni_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumni_employment`
--

LOCK TABLES `alumni_employment` WRITE;
/*!40000 ALTER TABLE `alumni_employment` DISABLE KEYS */;
INSERT INTO `alumni_employment` VALUES (1,1,'Web Developer','Application Mentors Ltd.','IT','Dhaka, Bangladesh',2022,NULL,1,'','2026-07-23 17:46:52','2026-07-26 18:19:08');
/*!40000 ALTER TABLE `alumni_employment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alumni_profiles`
--

DROP TABLE IF EXISTS `alumni_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumni_profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `student_reference_id` int(11) DEFAULT NULL,
  `organization_id` int(10) unsigned NOT NULL DEFAULT 1,
  `batch_year` year(4) DEFAULT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `nid_number` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','other','prefer_not_to_say') DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `proof_document` varchar(255) DEFAULT NULL,
  `current_location` varchar(200) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `spouse_name` varchar(200) DEFAULT NULL,
  `children_info` text DEFAULT NULL,
  `status` enum('pending','under_review','verified','approved','rejected') NOT NULL DEFAULT 'pending',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `is_mentor` tinyint(1) DEFAULT 0,
  `mentor_bio` text DEFAULT NULL,
  `mentor_expertise` varchar(255) DEFAULT NULL,
  `verification_notes` text DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `verified_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `location_type` varchar(20) NOT NULL DEFAULT 'bangladesh',
  `thana_upazila` varchar(100) DEFAULT NULL,
  `province_city` varchar(100) DEFAULT NULL,
  `activity_type` varchar(20) NOT NULL DEFAULT 'work',
  `hall_hostel` varchar(150) DEFAULT NULL,
  `session_years` varchar(50) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `experience_years` varchar(50) DEFAULT NULL,
  `willing_to_mentor` tinyint(1) DEFAULT 0,
  `job_referral` tinyint(1) DEFAULT 0,
  `contribution_areas` text DEFAULT NULL,
  `google_scholar_url` varchar(255) DEFAULT NULL,
  `researchgate_url` varchar(255) DEFAULT NULL,
  `permanent_district` varchar(100) DEFAULT NULL,
  `permanent_upazila` varchar(100) DEFAULT NULL,
  `emergency_contact_name` varchar(150) DEFAULT NULL,
  `emergency_contact_phone` varchar(50) DEFAULT NULL,
  `publications` text DEFAULT NULL,
  `awards_recognition` text DEFAULT NULL,
  `association_roles` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_alumni_user` (`user_id`),
  KEY `idx_alumni_batch` (`batch_year`),
  KEY `idx_alumni_status` (`status`),
  KEY `idx_alumni_featured` (`is_featured`),
  KEY `idx_alumni_org` (`organization_id`),
  KEY `idx_student_ref_id` (`student_reference_id`),
  CONSTRAINT `fk_alumni_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumni_profiles`
--

LOCK TABLES `alumni_profiles` WRITE;
/*!40000 ALTER TABLE `alumni_profiles` DISABLE KEYS */;
INSERT INTO `alumni_profiles` VALUES (1,1,94,1,2018,'6','+8801811332204',NULL,'2000-06-15','male','A+','Full Stack Developer','avatar_1_2aebef12.png',NULL,'Dhaka','Bangladesh','https://www.imonmrahman.com','https://www.linkedin.com/in/mahmudur-rahman-imon','https://www.facebook.com/facebook.imon',NULL,NULL,'verified',0,1,0,NULL,NULL,NULL,NULL,NULL,'2026-07-23 17:42:47','2026-07-26 14:19:08',NULL,'bangladesh','Vatara','','work','','','','','',1,1,'','','','','','','','','','');
/*!40000 ALTER TABLE `alumni_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alumni_skills`
--

DROP TABLE IF EXISTS `alumni_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumni_skills` (
  `alumni_profile_id` int(10) unsigned NOT NULL,
  `skill_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`alumni_profile_id`,`skill_id`),
  KEY `fk_as_skill` (`skill_id`),
  CONSTRAINT `fk_as_profile` FOREIGN KEY (`alumni_profile_id`) REFERENCES `alumni_profiles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_as_skill` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumni_skills`
--

LOCK TABLES `alumni_skills` WRITE;
/*!40000 ALTER TABLE `alumni_skills` DISABLE KEYS */;
/*!40000 ALTER TABLE `alumni_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `approval_history`
--

DROP TABLE IF EXISTS `approval_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `approval_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alumni_profile_id` int(10) unsigned NOT NULL,
  `actor_id` int(10) unsigned NOT NULL,
  `action` varchar(50) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ah_profile` (`alumni_profile_id`),
  KEY `fk_ah_actor` (`actor_id`),
  CONSTRAINT `fk_ah_actor` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ah_profile` FOREIGN KEY (`alumni_profile_id`) REFERENCES `alumni_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approval_history`
--

LOCK TABLES `approval_history` WRITE;
/*!40000 ALTER TABLE `approval_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `approval_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `association_expenses`
--

DROP TABLE IF EXISTS `association_expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `association_expenses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'General',
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `expense_date` date NOT NULL,
  `voucher_no` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `association_expenses`
--

LOCK TABLES `association_expenses` WRITE;
/*!40000 ALTER TABLE `association_expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `association_expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `association_funds`
--

DROP TABLE IF EXISTS `association_funds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `association_funds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `source` varchar(150) DEFAULT 'General Fund',
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fund_date` date NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `association_funds`
--

LOCK TABLES `association_funds` WRITE;
/*!40000 ALTER TABLE `association_funds` DISABLE KEYS */;
/*!40000 ALTER TABLE `association_funds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_al_user` (`user_id`),
  KEY `idx_al_action` (`action`),
  CONSTRAINT `fk_al_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'login','User logged in','::1',NULL,'2026-07-23 15:51:06'),(2,1,'login','User logged in','::1',NULL,'2026-07-25 18:59:16'),(3,1,'login','User logged in','::1',NULL,'2026-07-26 11:52:14'),(4,1,'logout','User logged out','::1',NULL,'2026-07-26 18:09:50'),(5,1,'login','User logged in','::1',NULL,'2026-07-26 18:10:33'),(6,1,'logout','User logged out','::1',NULL,'2026-07-26 18:26:43'),(7,1,'login','User logged in','::1',NULL,'2026-07-26 18:29:43'),(8,1,'login','User logged in','116.206.59.205',NULL,'2026-07-27 12:13:59');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `committee_members`
--

DROP TABLE IF EXISTS `committee_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `committee_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `committee_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `committee_type` enum('executive','advisory','special') NOT NULL DEFAULT 'executive',
  `designation` varchar(200) NOT NULL,
  `sort_order` smallint(6) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `can_manage_finance` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_cm_user` (`user_id`),
  KEY `idx_cm_committee` (`committee_id`),
  CONSTRAINT `fk_cm_committee` FOREIGN KEY (`committee_id`) REFERENCES `committees` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_cm_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `committee_members`
--

LOCK TABLES `committee_members` WRITE;
/*!40000 ALTER TABLE `committee_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `committee_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `committees`
--

DROP TABLE IF EXISTS `committees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `committees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(10) unsigned NOT NULL DEFAULT 1,
  `name` varchar(200) NOT NULL,
  `type` enum('executive','advisory','special') NOT NULL DEFAULT 'executive',
  `term_start` year(4) DEFAULT NULL,
  `term_end` year(4) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `committees`
--

LOCK TABLES `committees` WRITE;
/*!40000 ALTER TABLE `committees` DISABLE KEYS */;
/*!40000 ALTER TABLE `committees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `subject` varchar(300) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `replied_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_requests`
--

DROP TABLE IF EXISTS `contact_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alumni_profile_id` int(11) NOT NULL,
  `requester_name` varchar(150) NOT NULL,
  `requester_email` varchar(150) NOT NULL,
  `requester_phone` varchar(50) DEFAULT NULL,
  `discussion_topic` varchar(255) NOT NULL,
  `brief_message` text NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `accepted_contact_method` varchar(50) DEFAULT NULL,
  `accepted_contact_details` text DEFAULT NULL,
  `instruction_note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_alumni_profile` (`alumni_profile_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_requests`
--

LOCK TABLES `contact_requests` WRITE;
/*!40000 ALTER TABLE `contact_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_field_values`
--

DROP TABLE IF EXISTS `custom_field_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_field_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `custom_field_id` int(10) unsigned NOT NULL,
  `alumni_profile_id` int(10) unsigned NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cfv` (`custom_field_id`,`alumni_profile_id`),
  KEY `fk_cfv_profile` (`alumni_profile_id`),
  CONSTRAINT `fk_cfv_field` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_fields` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cfv_profile` FOREIGN KEY (`alumni_profile_id`) REFERENCES `alumni_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_field_values`
--

LOCK TABLES `custom_field_values` WRITE;
/*!40000 ALTER TABLE `custom_field_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_field_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_fields`
--

DROP TABLE IF EXISTS `custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(10) unsigned NOT NULL DEFAULT 1,
  `label` varchar(200) NOT NULL,
  `field_key` varchar(100) NOT NULL,
  `field_type` enum('text','textarea','select','checkbox','radio','date','file','number') NOT NULL DEFAULT 'text',
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(6) NOT NULL DEFAULT 0,
  `placeholder` varchar(200) DEFAULT NULL,
  `help_text` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cf_key` (`organization_id`,`field_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_fields`
--

LOCK TABLES `custom_fields` WRITE;
/*!40000 ALTER TABLE `custom_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donations`
--

DROP TABLE IF EXISTS `donations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(5) NOT NULL DEFAULT 'BDT',
  `purpose` varchar(300) DEFAULT 'General Fund',
  `message` text DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(200) DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_donation_status` (`status`),
  KEY `fk_donation_user` (`user_id`),
  CONSTRAINT `fk_donation_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donations`
--

LOCK TABLES `donations` WRITE;
/*!40000 ALTER TABLE `donations` DISABLE KEYS */;
/*!40000 ALTER TABLE `donations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `downloads`
--

DROP TABLE IF EXISTS `downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `downloads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(300) NOT NULL,
  `description` text DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT 'General',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(6) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `downloads`
--

LOCK TABLES `downloads` WRITE;
/*!40000 ALTER TABLE `downloads` DISABLE KEYS */;
/*!40000 ALTER TABLE `downloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_broadcasts`
--

DROP TABLE IF EXISTS `email_broadcasts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_broadcasts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `recipient_group` varchar(50) DEFAULT 'all',
  `body` text NOT NULL,
  `sent_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_broadcasts`
--

LOCK TABLES `email_broadcasts` WRITE;
/*!40000 ALTER TABLE `email_broadcasts` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_broadcasts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_expenses`
--

DROP TABLE IF EXISTS `event_expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `spent_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_expenses`
--

LOCK TABLES `event_expenses` WRITE;
/*!40000 ALTER TABLE `event_expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_registrations`
--

DROP TABLE IF EXISTS `event_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_registrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `pass_code` varchar(64) DEFAULT NULL,
  `payment_status` enum('free','pending','paid','failed') NOT NULL DEFAULT 'free',
  `payment_method` varchar(50) DEFAULT NULL,
  `trx_id` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `paid_at` datetime DEFAULT NULL,
  `status` enum('registered','attended','cancelled') NOT NULL DEFAULT 'registered',
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_er_event_user` (`event_id`,`user_id`),
  UNIQUE KEY `pass_code` (`pass_code`),
  KEY `fk_er_user` (`user_id`),
  CONSTRAINT `fk_er_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_er_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_registrations`
--

LOCK TABLES `event_registrations` WRITE;
/*!40000 ALTER TABLE `event_registrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(10) unsigned NOT NULL DEFAULT 1,
  `title` varchar(300) NOT NULL,
  `slug` varchar(320) NOT NULL,
  `description` longtext DEFAULT NULL,
  `venue` varchar(300) DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','cancelled') NOT NULL DEFAULT 'draft',
  `registration_type` enum('free','paid') NOT NULL DEFAULT 'free',
  `ticket_fee` decimal(10,2) DEFAULT 0.00,
  `allowed_roles` varchar(255) DEFAULT 'all',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `max_attendees` int(10) unsigned DEFAULT NULL,
  `registration_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_crowdfunding` tinyint(1) NOT NULL DEFAULT 0,
  `crowdfunding_goal` decimal(10,2) DEFAULT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT 0,
  `online_link` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_event_date` (`event_date`),
  KEY `idx_event_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(100) DEFAULT 'General',
  `sort_order` smallint(6) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,'Who can join the IPH Alumni Association?','Any graduate of the Institute of Public Health (IPH) can register. Your identity will be verified by our committee.','General',1,1,'2026-07-23 15:28:23','2026-07-23 15:28:23',NULL),(2,'How long does verification take?','Usually within 48 hours of registration on business days.','General',2,1,'2026-07-23 15:28:23','2026-07-23 15:28:23',NULL),(3,'What is the difference between Annual and Lifetime membership?','Annual membership renews every year (৳500/yr). Lifetime membership is a one-time payment (৳5,000) with no renewal and includes additional privileges like voting rights.','General',3,1,'2026-07-23 15:28:23','2026-07-23 15:28:23',NULL),(4,'Is my profile visible to everyone?','Only verified alumni profiles are visible in the public directory. You can control your privacy settings from your portal.','General',4,1,'2026-07-23 15:28:23','2026-07-23 15:28:23',NULL),(5,'How do I update my profile information?','Log into your alumni portal and go to My Profile to edit your information.','General',5,1,'2026-07-23 15:28:23','2026-07-23 15:28:23',NULL),(6,'How do I get a QR membership ID card?','After your membership is approved, your QR ID is available in Portal → Membership → View QR ID.','General',6,1,'2026-07-23 15:28:23','2026-07-23 15:28:23',NULL);
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_albums`
--

DROP TABLE IF EXISTS `gallery_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_albums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(10) unsigned NOT NULL DEFAULT 1,
  `title` varchar(300) NOT NULL,
  `description` text DEFAULT NULL,
  `event_id` int(10) unsigned DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `album_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_album_event` (`event_id`),
  CONSTRAINT `fk_album_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_albums`
--

LOCK TABLES `gallery_albums` WRITE;
/*!40000 ALTER TABLE `gallery_albums` DISABLE KEYS */;
INSERT INTO `gallery_albums` VALUES (1,1,'Memories & Stories','Images of a lot of memories and lovable stories regarding IPH Campus Life.',NULL,NULL,'published','2026-04-01','2026-07-23 16:11:43','2026-07-23 17:13:31',NULL),(2,1,'IPH Day Celebrations','Some of celebration image regarding IPH Day',NULL,NULL,'published','2026-04-15','2026-07-23 17:02:43','2026-07-23 17:02:43',NULL),(3,1,'L1 Batch','Some images of L1 Batch',NULL,NULL,'published','2026-04-15','2026-07-23 17:03:32','2026-07-23 17:03:32',NULL);
/*!40000 ALTER TABLE `gallery_albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_photos`
--

DROP TABLE IF EXISTS `gallery_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_photos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `caption` varchar(500) DEFAULT NULL,
  `sort_order` smallint(6) NOT NULL DEFAULT 0,
  `uploaded_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_photo_album` (`album_id`),
  CONSTRAINT `fk_photo_album` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_photos`
--

LOCK TABLES `gallery_photos` WRITE;
/*!40000 ALTER TABLE `gallery_photos` DISABLE KEYS */;
INSERT INTO `gallery_photos` VALUES (4,1,'gallery_1_1784801744_d7957fed.jpg',NULL,0,NULL,'2026-07-23 16:15:44'),(5,1,'gallery_1_1784801748_c7b5c21d.jpg',NULL,0,NULL,'2026-07-23 16:15:48'),(6,1,'gallery_1_1784801750_b1161429.jpg',NULL,0,NULL,'2026-07-23 16:15:50'),(7,1,'gallery_1_1784802098_201e393a.jpg',NULL,0,NULL,'2026-07-23 16:21:38'),(8,1,'gallery_1_1784804503_05ae18bc.jpg',NULL,0,NULL,'2026-07-23 17:01:43'),(9,1,'gallery_1_1784804503_670d155d.jpg',NULL,0,NULL,'2026-07-23 17:01:43'),(10,1,'gallery_1_1784804503_0718338c.jpg',NULL,0,NULL,'2026-07-23 17:01:43'),(11,1,'gallery_1_1784804503_91ad61f5.jpg',NULL,0,NULL,'2026-07-23 17:01:43'),(12,1,'gallery_1_1784804503_9ceb5fd8.jpg',NULL,0,NULL,'2026-07-23 17:01:43'),(13,2,'gallery_2_1784804576_6fb46e5c.jpg',NULL,0,NULL,'2026-07-23 17:02:56'),(14,2,'gallery_2_1784804576_253b1b97.jpg',NULL,0,NULL,'2026-07-23 17:02:56'),(15,2,'gallery_2_1784804576_6fea0fe9.jpg',NULL,0,NULL,'2026-07-23 17:02:56'),(16,2,'gallery_2_1784804576_7c97e58e.jpg',NULL,0,NULL,'2026-07-23 17:02:56'),(17,2,'gallery_2_1784804576_2f0368fd.jpg',NULL,0,NULL,'2026-07-23 17:02:56'),(18,2,'gallery_2_1784804576_3be7abeb.jpg',NULL,0,NULL,'2026-07-23 17:02:56'),(19,2,'gallery_2_1784804576_855097e0.jpg',NULL,0,NULL,'2026-07-23 17:02:56'),(20,3,'gallery_3_1784804630_3e964775.jpg',NULL,0,NULL,'2026-07-23 17:03:50'),(21,3,'gallery_3_1784804630_66c429f9.jpg',NULL,0,NULL,'2026-07-23 17:03:50'),(22,3,'gallery_3_1784804630_e5c6b97b.jpg',NULL,0,NULL,'2026-07-23 17:03:50'),(23,3,'gallery_3_1784804630_1ae89659.jpg',NULL,0,NULL,'2026-07-23 17:03:50'),(24,3,'gallery_3_1784804630_60559561.jpg',NULL,0,NULL,'2026-07-23 17:03:50'),(25,3,'gallery_3_1784804630_46922eee.jpg',NULL,0,NULL,'2026-07-23 17:03:50'),(26,3,'gallery_3_1784804630_0d779218.jpg',NULL,0,NULL,'2026-07-23 17:03:50'),(27,3,'gallery_3_1784804630_af3e20bf.jpg',NULL,0,NULL,'2026-07-23 17:03:50');
/*!40000 ALTER TABLE `gallery_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_applications`
--

DROP TABLE IF EXISTS `job_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `student_reference_id` int(11) DEFAULT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `applicant_email` varchar(255) NOT NULL,
  `applicant_phone` varchar(100) DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `status` enum('submitted','reviewed','shortlisted','rejected') NOT NULL DEFAULT 'submitted',
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_id` (`job_id`),
  KEY `user_id` (`user_id`),
  KEY `student_reference_id` (`student_reference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_applications`
--

LOCK TABLES `job_applications` WRITE;
/*!40000 ALTER TABLE `job_applications` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `job_type` varchar(50) DEFAULT 'Full-time',
  `location` varchar(255) DEFAULT NULL,
  `salary_range` varchar(100) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `how_to_apply` text DEFAULT NULL,
  `apply_type` enum('portal','external_link','email') NOT NULL DEFAULT 'portal',
  `apply_link` varchar(500) DEFAULT NULL,
  `apply_email` varchar(255) DEFAULT NULL,
  `visibility` enum('members','public') NOT NULL DEFAULT 'members',
  `status` enum('active','closed') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `visibility` (`visibility`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,1,'Medical Technologist (Lab)','Meem Medical Center','Full-time','Bashundhara C/A, Dhaka','18000 - 20000','2026-08-01','Key Responsibilities:\r\n1. Sample Collection & Testing: Gather blood, urine, and tissue samples following standard safety protocols, and run tests in fields like biochemistry, hematology, or microbiology.\r\n2. Equipment Maintenance: Clean, operate, calibrate, and troubleshoot laboratory instruments to ensure accurate results.\r\n3. Reporting & Recording: Document test findings, update registers, and prepare accurate reports for doctors.\r\n4. Inventory Management: Store reagents, track expiration dates, and manage laboratory stock supplies.','Qualifications: Diploma or B.Sc. in Medical Technology (Laboratory) from a recognized institute.\r\nExperience: Ranging from 1 to 3+ years of practical work in hospitals, clinics, or NGO health projects (such as DGHS or BRAC initiatives).Soft Skills: Basic computer literacy (MS Office), clear communication, and adherence to medical ethics.',NULL,'portal',NULL,NULL,'public','active','2026-07-26 09:52:38','2026-07-26 10:09:23',NULL);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membership_payments`
--

DROP TABLE IF EXISTS `membership_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membership_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `membership_id` int(10) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(5) NOT NULL DEFAULT 'BDT',
  `method` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(200) DEFAULT NULL,
  `payment_slip` varchar(255) DEFAULT NULL,
  `status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_mp_membership` (`membership_id`),
  CONSTRAINT `fk_mp_membership` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membership_payments`
--

LOCK TABLES `membership_payments` WRITE;
/*!40000 ALTER TABLE `membership_payments` DISABLE KEYS */;
INSERT INTO `membership_payments` VALUES (1,1,500.00,'BDT',NULL,NULL,NULL,'pending',NULL,'2026-07-23 17:51:17','2026-07-23 17:51:17');
/*!40000 ALTER TABLE `membership_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membership_types`
--

DROP TABLE IF EXISTS `membership_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membership_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(10) unsigned NOT NULL DEFAULT 1,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `features` text DEFAULT NULL,
  `badge_text` varchar(50) DEFAULT NULL,
  `btn_text` varchar(100) DEFAULT 'Choose Tier',
  `is_featured` tinyint(1) DEFAULT 0,
  `duration_months` smallint(6) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membership_types`
--

LOCK TABLES `membership_types` WRITE;
/*!40000 ALTER TABLE `membership_types` DISABLE KEYS */;
INSERT INTO `membership_types` VALUES (1,1,'Annual','annual',NULL,500.00,'Directory listing with verified badge\nQR-enabled member ID card\nEvent invitations & access',NULL,'Start with Annual',0,12,1,1,'2026-07-23 15:28:23','2026-07-26 18:48:03'),(2,1,'Lifetime','lifetime',NULL,5000.00,'Everything in Annual\nLifetime membership certificate\nPriority seating at events\nVoting rights at general meetings','MOST POPULAR','Become a Lifetime Member',1,NULL,1,2,'2026-07-23 15:28:23','2026-07-26 18:48:03'),(3,1,'Honorary','honorary',NULL,0.00,'Nominated by the executive committee\nSpecial recognition on the About page\nNo annual renewal fee',NULL,'Nominate Someone',0,NULL,1,3,'2026-07-23 15:28:23','2026-07-26 18:48:03');
/*!40000 ALTER TABLE `membership_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `memberships`
--

DROP TABLE IF EXISTS `memberships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memberships` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alumni_profile_id` int(10) unsigned NOT NULL,
  `membership_type_id` int(10) unsigned NOT NULL,
  `status` enum('pending','active','expired','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `membership_number` varchar(50) DEFAULT NULL,
  `qr_code` varchar(100) DEFAULT NULL,
  `proof_document` varchar(255) DEFAULT NULL,
  `certificate_issued` tinyint(1) NOT NULL DEFAULT 0,
  `approved_at` datetime DEFAULT NULL,
  `approved_by` int(10) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `membership_number` (`membership_number`),
  KEY `idx_mem_profile` (`alumni_profile_id`),
  KEY `idx_mem_status` (`status`),
  KEY `fk_mem_type` (`membership_type_id`),
  CONSTRAINT `fk_mem_profile` FOREIGN KEY (`alumni_profile_id`) REFERENCES `alumni_profiles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_mem_type` FOREIGN KEY (`membership_type_id`) REFERENCES `membership_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `memberships`
--

LOCK TABLES `memberships` WRITE;
/*!40000 ALTER TABLE `memberships` DISABLE KEYS */;
INSERT INTO `memberships` VALUES (1,1,1,'cancelled','2026-07-23','2027-07-23','IPH--0001','87126586f99f04532ae73acea8eded73',NULL,0,'2026-07-23 17:52:48',NULL,NULL,'2026-07-23 17:51:17','2026-07-23 18:01:10','2026-07-23 18:01:10');
/*!40000 ALTER TABLE `memberships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mentorship_requests`
--

DROP TABLE IF EXISTS `mentorship_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mentorship_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mentor_id` int(11) NOT NULL,
  `mentee_id` int(11) NOT NULL,
  `topic` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','accepted','completed','declined') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mentorship_requests`
--

LOCK TABLES `mentorship_requests` WRITE;
/*!40000 ALTER TABLE `mentorship_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `mentorship_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(10) unsigned NOT NULL DEFAULT 1,
  `author_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(300) NOT NULL,
  `category` enum('news','press_release','notice','resolution') NOT NULL DEFAULT 'news',
  `slug` varchar(320) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `attachment_file` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_news_status` (`status`),
  KEY `idx_news_published_at` (`published_at`),
  KEY `idx_news_featured` (`is_featured`),
  KEY `fk_news_author` (`author_id`),
  CONSTRAINT `fk_news_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,1,1,'Test Notice','notice','test-notice',NULL,'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library in London, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset\'s Body Type sheets. It has survived not only many decades, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised thanks to these sheets and more recently with desktop publishing software like Aldus PageMaker and Microsoft Word including versions of Lorem Ipsum.',NULL,NULL,'published',0,'2026-07-26 11:34:50',0,'2026-07-26 11:34:50','2026-07-26 15:39:31',NULL);
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notice_signatories`
--

DROP TABLE IF EXISTS `notice_signatories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notice_signatories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `designation_title` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notice_signatories`
--

LOCK TABLES `notice_signatories` WRITE;
/*!40000 ALTER TABLE `notice_signatories` DISABLE KEYS */;
/*!40000 ALTER TABLE `notice_signatories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `type` varchar(100) NOT NULL,
  `title` varchar(300) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notif_user` (`user_id`),
  KEY `idx_notif_is_read` (`is_read`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,1,'membership_applied','Membership Application Submitted','Your Annual membership application has been submitted. You will be notified once approved.',NULL,1,NULL,'2026-07-23 17:51:17'),(2,1,'','নতুন ব্লগ: Test Blog','নতুন একটি ব্লগ পোস্ট প্রকাশিত হয়েছে: Test Blog','/stories/test-blog-480',1,NULL,'2026-07-26 17:52:55'),(3,1,'','নতুন যোগাযোগ অনুরোধ: Mohammad Tajul Islam','Mohammad Tajul Islam আপনার সাথে ডিরেক্টরি থেকে যোগাযোগের অনুরোধ করেছেন।','/portal/contact-requests',0,NULL,'2026-07-26 18:27:52');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organizations`
--

DROP TABLE IF EXISTS `organizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organizations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT 'IPH Alumni Association',
  `short_name` varchar(80) NOT NULL DEFAULT 'IPH Alumni',
  `slug` varchar(100) NOT NULL,
  `domain` varchar(150) DEFAULT NULL,
  `founded` year(4) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organizations`
--

LOCK TABLES `organizations` WRITE;
/*!40000 ALTER TABLE `organizations` DISABLE KEYS */;
INSERT INTO `organizations` VALUES (1,'IPH Alumni Association','IPH Alumni','iph-alumni','iphalumni.org',2025,NULL,'info@iphalumni.org',NULL,NULL,1,'2026-07-23 15:28:23','2026-07-23 15:28:23',NULL);
/*!40000 ALTER TABLE `organizations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_sections`
--

DROP TABLE IF EXISTS `page_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_sections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(10) unsigned NOT NULL DEFAULT 1,
  `section_key` varchar(100) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `order_index` smallint(6) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`config`)),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ps_key` (`organization_id`,`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_sections`
--

LOCK TABLES `page_sections` WRITE;
/*!40000 ALTER TABLE `page_sections` DISABLE KEYS */;
INSERT INTO `page_sections` VALUES (1,1,'hero','Hero Section',1,1,NULL,'2026-07-23 15:28:23','2026-07-23 15:28:23'),(2,1,'stats','Statistics Strip',2,1,NULL,'2026-07-23 15:28:23','2026-07-23 15:28:23'),(3,1,'directory','Alumni Directory Preview',3,1,NULL,'2026-07-23 15:28:23','2026-07-23 15:28:23'),(4,1,'stories','Success Stories',4,1,NULL,'2026-07-23 15:28:23','2026-07-23 15:28:23'),(5,1,'membership','Membership Tiers',5,1,NULL,'2026-07-23 15:28:23','2026-07-23 15:28:23'),(6,1,'events','Upcoming Events',6,1,NULL,'2026-07-23 15:28:23','2026-07-23 15:28:23'),(7,1,'news','Latest News',7,1,NULL,'2026-07-23 15:28:23','2026-07-23 15:28:23'),(8,1,'cta','Call to Action',8,1,NULL,'2026-07-23 15:28:23','2026-07-23 15:28:23');
/*!40000 ALTER TABLE `page_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'string',
  `group` varchar(50) DEFAULT 'general',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','IPH Alumni Association','string','general','2026-07-23 15:28:24','2026-07-23 18:13:20'),(2,'site_tagline','Institute of Public Health — Alumni Network','string','general','2026-07-23 15:28:24','2026-07-23 18:13:20'),(3,'site_email','info@iphalumni.org','string','general','2026-07-23 15:28:24','2026-07-23 18:13:20'),(4,'site_phone','01811332204','string','general','2026-07-23 15:28:24','2026-07-23 18:13:20'),(5,'site_address','Institute of Public Health, Dhaka, Bangladesh','string','general','2026-07-23 15:28:24','2026-07-23 18:13:20'),(6,'site_founded','2025','string','general','2026-07-23 15:28:24','2026-07-23 18:13:20'),(7,'site_logo','','string','appearance','2026-07-23 15:28:24','2026-07-23 15:28:24'),(8,'membership_annual_fee','500','string','membership','2026-07-23 15:28:24','2026-07-23 15:28:24'),(9,'membership_lifetime_fee','5000','string','membership','2026-07-23 15:28:24','2026-07-23 15:28:24'),(10,'facebook_url','','string','social','2026-07-23 15:28:24','2026-07-23 15:28:24'),(11,'linkedin_url','','string','social','2026-07-23 15:28:24','2026-07-23 15:28:24'),(12,'footer_text','© 2025 IPH Alumni Association. All rights reserved.','string','general','2026-07-23 15:28:24','2026-07-23 15:28:24'),(13,'maintenance_mode','0','string','system','2026-07-23 15:28:24','2026-07-23 15:28:24'),(38,'payment_instructions','Bkash/Nagad Personal: 01111223344\r\nBank Details: IPH Alumni Association, A/C: 22446688990, Dutch-Bangla Bank Ltd.','string','membership','2026-07-23 18:08:23','2026-07-23 18:13:20'),(46,'mail_from_address','contact@iphalumni.org','string','general','2026-07-26 18:46:27','2026-07-26 18:46:27');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skills`
--

LOCK TABLES `skills` WRITE;
/*!40000 ALTER TABLE `skills` DISABLE KEYS */;
/*!40000 ALTER TABLE `skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students_reference`
--

DROP TABLE IF EXISTS `students_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students_reference` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `roll` int(11) DEFAULT NULL,
  `name_english` varchar(255) NOT NULL,
  `name_bangla` varchar(255) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `guardian_mobile` varchar(50) DEFAULT NULL,
  `batch` varchar(50) NOT NULL,
  `department` varchar(255) NOT NULL,
  `session` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_sr_batch` (`batch`),
  KEY `idx_sr_mobile` (`mobile`),
  KEY `idx_sr_name_eng` (`name_english`)
) ENGINE=InnoDB AUTO_INCREMENT=431 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students_reference`
--

LOCK TABLES `students_reference` WRITE;
/*!40000 ALTER TABLE `students_reference` DISABLE KEYS */;
INSERT INTO `students_reference` VALUES (1,1,'Md. Aminul Islam','মো: আমিনুল ইসলাম','01752250653','01722916702','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(2,2,'Anjuman Ara','আঞ্জুমান আরা','01931503865','01923222582','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(3,3,'Mahmudul Hasan','মাহমুদুল হাসান','01724484142','01729112779','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(4,4,'Md. Rabiul Islam','মো: রবিউল ইসলাম','01745298353','01721877353','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(5,5,'Toushi Chakma','তৌষি চাকমা','01556437752','01556773297','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(6,6,'Mohiul Islam','মহিউল ইসলাম','01762866720','01749022936','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(7,7,'Md. Johurul Islam','মো: জহুরুল ইসলাম','01717722576','01785316085','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(8,8,'Md. Helal Uddin','মো: হেলাল উদ্দিন','01716504038','01715687087','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(9,9,'Md. Sheikh Sadi','মো: শেখ সাদী','01756288393','01718176477','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(10,10,'Rashedur Rahman','রাশেদুর রহমান','01717120669','01788263046','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(11,11,'Jagonnath Chandra Mojumder','জগন্নাথ চন্দ্র মজুমদার','01818330422','01814330423','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(12,12,'Sojib Miah','সজীব মিয়া','01705359707','01795839263','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(13,13,'Aurin Chakma','অরিন চাকমা','01775191557','01553761586','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(14,14,'Md. Asaduzzaman','মো: আসাদুজ্জামান','01718361414','01712476788','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(15,15,'Md. Shamim Iqbal','মো: শামীম ইকবাল','01740651108','01712491565','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(16,16,'ABM Akhteruzzaman Talukder','এবিএম আক্তারুজ্জামান তালুকদার','01712325614','01928493109','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(17,17,'Md. Rafiqul Islam','মো: রফিকুল ইসলাম','01916308050','01687633800','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(18,18,'Md. Rezaul Karim','মোহাম্মদ রেজাউল করিম','01715295331','01710336944','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(19,19,'Md. Reazul Islam','মো: রিয়াজুল ইসলাম','01923600251','01712728718','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(20,20,'Md. Shahozaman','মো: শাহজামান','01749187292','01918991847','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(21,21,'Md. Wasim Khan','মো: ওয়াসিম খান','01754937298','01986386750','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(22,22,'Md. Uzzal Hoassain','মোহাম্মদ উজ্জল হোসাইন','01726261817','01726906035','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(23,23,'Md. Sahed jahangir','মোঃ সাহেদ জাহাঙ্গীর','01911373345','01736206251','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(24,24,'Md. Faisal Ahmed','মো: ফয়সাল আহমেদ','01745757474','01915805489','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(25,25,'Zafor Ahammad','জাফর আহাম্মদ','01718619960','01913462829','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(26,26,'Md. Zabed Jahangir','মো: জাবেদ জাহাঙ্গীর','01822856737','01675007359','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(27,27,'Mrs. Afroja Banu','মোসাম্মৎ আফরোজা বানু','01821873808','01712211294','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(28,28,'Rowshan Ara Nipa','রওশন আরা নিপা','01772422667','01766665836','L-1','BSc in Health Technology (Laboratory)','2015-16','2026-07-25 19:09:19'),(29,1,'Uchala Chy','উচহ্লা চৌধুরী','01822686545','01825382444','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(30,2,'Sompa Rani','সম্পা রানী মাহাতো','01726623589','01721319565','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(31,3,'Md. Gias Uddin','মোহাম্মদ গিয়াস উদ্দিন','01623663320','01816874365','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(32,4,'Jewel Paul','জুয়েল পাল','01553006738','01517113516','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(33,5,'Aurbindha Sutradhor','অরবিন্দ সুত্রধর','01911230068','01718320448','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(34,6,'Md. Raju Ahmed','মো: রাজু আহম্মেদ','01795382737','01703369644','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(35,7,'Bapu Moni Chakma','বাপু মনি চাকমা','01552734858','01553003150','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(36,8,'Jannat Akter','জান্নাত আক্তার','01748144565','01711370412','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(37,9,'Ruhi Das','রুহি দাস','01911641733','01915655619','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(38,10,'Md. Razibul Hasan','মো: রাজিবুল হাসান','01711386765','01740556446','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(39,11,'Md. Ali Arshad Mukta','মো: আলী আরশাদ মুক্তা','01937890376','01819112416','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(40,12,'Keya Papiya','কেয়া পাপিয়া','01797952167','01719770053','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(41,13,'Md. Nurul Alam','মো: নুরুল আলম','01712424651','01749485353','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(42,14,'Md. Shahin Alam','মো: শাহীন আলম','01787992757','01737613506','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(43,15,'Md. Kamrul Hasan','মো: কামরুল হাসান','01762957161','01736587208','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(44,16,'Md. Selim Hossain','মো: সেলিম হোসেন','01737739189','01774638219','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(45,17,'Sharif Mohammad Ulla','শরীফ মোহাম্মদ উল্যা','01711176011','01715420722','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(46,18,'Rabeya Khatun','রাবেয়া খাতুন','01855941185','01855941186','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(47,19,'Md. Saiful Islam','মো: সাইফুল ইসলাম (স্বপন)','01774383491','01722159571','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(48,20,'Joynita Dewan','জয়নীতা দেওয়ান','01552650581','01820323396','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(49,21,'Shila Aktar','শীলা আক্তার','01521212560','01762291164','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(50,22,'Tariq Mahmud','তারিক মাহমুদ','01711006673','01712143365','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(51,23,'Md. Al Mumin Islam','মো: আল মুমীন ইসলাম','01745073204','01745679380','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(52,24,'Md. Nazrul Islam','মো: নজরুল ইসলাম','01917422206','01715272056','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(53,25,'Rita Sarkar','রীতা সরকার','01712526919','01725727142','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(54,26,'Md. Ahsanur Rahman','মো: আহসানুর রহমান','01711032913','01731797591','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(55,27,'Munira Akter','মনিরা আক্তার','01913380618','01630981781','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(56,28,'Taposh Kumar','তাপস কুমার','01722659196','01744375538','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(57,29,'Md. Tuhin Islam','মো: তুহিন ইসলাম','01764194661','01936523951','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(58,30,'Tushar Singha','তুষার সিংহ','01679884948','01916523588','L-2','BSc in Health Technology (Laboratory)','2016-17','2026-07-25 19:09:19'),(59,1,'Md. Golam Rabbani','মোঃ গোলাম রব্বানী','01993648895','01706869522','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(60,2,'Kishore Chandra Bhuiya','কিশোর চন্দ্র ভূঁইয়া','01620433667','01746407748','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(61,3,'Soma','সোমা','01877672312','01748921526','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(62,4,'Mst. Liza Aktar','মোসাঃ লিজা আক্তার','0151673634','0191337885','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(63,5,'Ferdosi Aktar','ফেরদৌসী আক্তার','01852119250','01792494115','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(64,6,'Afrin Ahmmed','আফরিন আহমেদ','01956928949','01832093191','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(65,7,'Md. Akhirul Islam','মোঃ আখিরুল ইসলাম','01763281775','01784894036','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(66,8,'Md. Mehedi Hasan Rabbi','মোঃ মেহেদী হাসান রাব্বি','01767613920','01726014332','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(67,9,'Tanzia Aktar','তানজিয়া আক্তার','01839934118','01714689136','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(68,10,'Abdullah Al Tahsin Chowdhury','আব্দুল্লাহ আল-তাহসীন চৌধুরী','01724323003','01753282020','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(69,11,'Rimpa Bairagi','রিম্পা বৈরাগী','01788806869','01827068525','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(70,12,'Tania Afroz','তানিয়া আফরোজ','01729617763','01748598971','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(71,13,'Md. Anwar Hossain','মোঃ আনোয়ার হোসাইন','01777652147','01728317360','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(72,14,'Zubayer Hasan','যুবায়ের হাসান','01558163951','01612346582','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(73,15,'Saikot Sarkar','সৈকত সরকার','01965202247','01818750280','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(74,16,'Md. Nahid Hasan','মোঃ নাহিদ হাসান','01788121221','01731291882','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(75,17,'Sumaiya Aktar','সুমাইয়া আক্তার','01903165467','01766995489','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(76,18,'Sulota Islam','সুলতা ইসলাম','01776096717','0174398839','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(77,19,'Prithu Chakma','পৃথু চাকমা','01551625984','01812539793','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(79,21,'Samiya Sultan Asha','সামিয়া সুলতানা এশা','01908026562','01774007802','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(80,22,'Gopal Priyo Chakma','গোপাল প্রিয় চাকমা','01553374100','10556498441','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(81,23,'Md. Hasanuzzaman','মোঃ হাসানুজ্জামান','01719404446','01749400451','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(82,24,'Poritos Chakma','পরিতোষ চাকমা','01559888488','01704462915','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(83,25,'Priyanka Sarkar','প্রিয়াংকা সরকার','01635517712','01719526881','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(84,26,'Md. Tarik Rahman','মোঃ তারিক রহমান','01757514108','01720094245','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(85,27,'Md. Utul Islam','মোঃ উতুল ইসলাম','01790849719','01706750338','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(86,28,'Md. Sahabuddin Khan','মোঃ শাহাবুদ্দিন খান','01828600087','01728395023','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(87,29,'Sumaiya Binte Owahab','সুমাইয়া বিনতে ওয়াহাব','01637543444','01718009424','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(88,30,'Razia Sultana','রাজিয়া সুলতানা','01704788841','01711383872','L-3','BSc in Health Technology (Laboratory)','2017-18','2026-07-25 19:09:19'),(89,1,'Md. Minhaz Parvez','মোঃ মিনহাজ পারভেজ','01787340501','01753990551','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(90,2,'Ayesha Akter Bithi','আয়েশা আক্তার বিথী','01909684494','01849806058','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(91,3,'Sifun Noor Talukder Shihab','সাইফুন নূর তালুকদার শিহাব','01751559677','01716825429','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(92,4,'Sonya Akter Sithila','সোনিয়া আক্তার সিথিলা','01624423674','01792403047','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(93,5,'Aminul Islam Suhag','আমিনুল ইসলাম সোহাগ','01734541231','01886037304','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(94,6,'Mahmudur Rahman','মাহমুদুর রহমান','01811332204','01713067968','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(95,7,'Md. Abir Hossain','মোঃ আবির হোসাইন','01775518574','01760898094','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(96,8,'Md. Didar Hossain','মোঃ দিদার হোসেন','01851716074','01876324250','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(97,9,'Nadim Ahmed','নাদিম আহমেদ','01796413803','01745396365','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(98,10,'Shamima Akter','শামিমা আক্তার','01705674501','01731687130','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(99,11,'Md. Sohel Rana Hafiz','মোঃ সোহেল রানা হাফিজ','01918491050','01752027878','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(100,12,'Junaed Ahmed','জুনায়েদ আহমেদ','01789971454','01763202337','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(101,13,'Md. Ziaur Rahman','মোঃ জিয়াউর রহমান','01780668158','01916003598','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(102,14,'Mithun Chandra Paul','মিঠুন চন্দ্র পাল','01746295934','01762966472','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(103,15,'Jannatul Fardous Bristi','জান্নাতুল ফেরদাউস বৃষ্টি','01872608921','01719752880','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(104,16,'Md. Zohurul Islam','মোঃ জহুরুল ইসলাম','01755756571','01755385132','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(105,17,'Eti Mondal','ইতি মন্ডল','01736875137','01740991545','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(106,18,'Obaidur Rahman','ওবায়দুর রহমান','01640734687','01918969185','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(107,19,'Mitu Khanam','মিতু খানম','01786086961','01933133474','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(108,20,'Shah Niaz','শাহ নিয়াজ','01751828888','01780344097','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(109,21,'Sheikh Mohammad Talha','শেখ মোহাম্মদ তালহা','01825209397','01835151287','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(110,22,'Md. Abdul Latif','মোঃ আব্দুল লতিফ','01770147088','01780139026','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(111,23,'Mohammad Safiul Alam','মোহাম্মদ শফিউল আলম','01517262825','01827155795','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(112,24,'Ujjala Chakma','উজ্জ্বলা চাকমা','01554536897','01554471680','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(113,25,'Bodrun Nahar','বদরুন নাহার','01766450035','01925145101','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(114,26,'Md. Abdur Rahman','মোঃ আব্দুর রহমান','01713809128','01828829780','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(115,27,'Md. Mehedi Hasan','মোঃ মেহেদী হাসান','01776895958','01718724861','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(116,28,'Rayhan Morshed','রায়হান মোর্শেদ','01710005953','01724590407','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(117,29,'Md. Helal Uddin','মোঃ হেলাল উদ্দিন','01756569858','01778949672','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(118,30,'Md. Saiful Islam','মোঃ সাইফুল ইসলাম','01630373901','01856915462','L-4','BSc in Health Technology (Laboratory)','2018-19','2026-07-25 19:09:19'),(119,1,'Md. Rubel Hossain','মোঃ রুবেল হোসেন','01784873144','01725929961','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(120,2,'Md. Sabbir Ahmed','মোঃ সাব্বির আহমেদ','01785326232','01734866864','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(121,3,'Mst. Sirazum Munira','মোছাঃ সিরাজুম মনিরা','01318927562','01714558337','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(122,4,'Mariha Uddin Sakal','মারিহা উদ্দিন সকাল','01749643205','01718097057','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(123,5,'Talha Khan','তালহা খান','01713688535','01920584373','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(124,6,'Hossain Ali Mollah','হোসেন আলী মোল্লা','01980426284','01881954591','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(125,7,'Murshid Kuly Khan','মুর্শিদ কুলী খান','01706062727','01798040438','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(126,8,'Md. Jihad Hosan','মোঃ জিহাদ হোসেন','01617171987','01713614605','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(127,9,'Mithila Akter','মিথিলা আক্তার','01760234903','01683312550','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(128,10,'Sonia Akter','সোনিয়া আক্তার','01630277101','01614772686','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(129,11,'Md. Matiar Rahman','মোঃ মতিয়ার রহমান','01704396935','01792807699','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(130,12,'Md. Omar Faruque','মোঃ ওমর ফারুক','01747398049','01757962709','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(131,13,'Mst. Israt Jahan','মোসাঃ ইসরাত জাহান','01770524883','01731494638','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(132,14,'Arpita Das','অর্পিতা দাস','01757941316','01731494638','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(133,15,'Md. Joynal Abadin Mamun','মোঃ জয়নাল আবেদিন মামুন','01745130886','01714806037','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(134,16,'Md. Sakibur Rahman Sopan','মোঃ সাকিবুর রহমান সোপান','01981320743','01731265797','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(135,17,'Mst. Mursheda Khatun','মোছাঃ মুর্শিদা খাতুন','01732639252','01773333727','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(136,18,'Md. Masud Rana','মোঃ মাসুদ রানা','01791059802','01740915167','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(137,19,'Mst. Aklima Khatun','মোছাঃ আকলিমা খাতুন','01734493577','01793894822','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(138,20,'Tahmina Akter','তাহমিনা আক্তার','01875335118','01822900724','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(139,21,'Bijon Singha','বিজন সিংহ','01731940136','01679861596','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(140,22,'Md. Shawkat Ali','মোঃ শওকত আলী','01712460905','01752768556','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(141,23,'Lina Roy','লিনা রায়','01940993180','01721161094','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(142,24,'Md. Masud Alam','মোঃ মাসুদ আলম','01866712959','01723068150','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(143,25,'Rakiba Sultana Keya','রাকিবা সুলতানা কেয়া','01312741575','01731975760','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(144,26,'Ayan Roy','অয়ন রায়','01759642563','01746924588','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(145,27,'Md. Lutfur Rahman','মোঃ লুৎফুর রহমান','01715487725','01860828020','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(146,28,'Md. Alauddin','মোঃ আলাউদ্দিন','01771052324','01316170625','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(147,29,'Md. Abu Bakkar Siddik','মোঃ আবু বকর সিদ্দিক','01727140701','01786529319','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(148,30,'Maharuba Akter','মেহেরুবা আক্তার','01951504527','01717961084','L-5','BSc in Health Technology (Laboratory)','2019-20','2026-07-25 19:09:20'),(149,1,'Ashikul Islam','আশিকুল ইসলাম','01906703672','01717386696','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(150,2,'Naiem Shahria','নাইম শাহরিয়া','01890386500','01726100001','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(151,3,'Masud Hasan','মাসুদ হাসান','01794146475','01778307237','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(152,4,'Aminul Hoque','আমিনুল হক','01908156059','01824590461','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(153,5,'Minhajil Menon','মিনহাজীল মেনন','01748691138','01718566860','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(154,6,'Azizun Nahar Tanni','আজিজুন নাহার তন্নী','01862918825','01613051455','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(155,7,'Rinku Dhali','রিংকু ঢালী','01628381964','01581712759','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(156,8,'Md. Jubayer Hossain','মোঃ জুবায়ের হোসাইন','01310122922','01782483966','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(157,9,'Md. Tarak Aziz','মোঃ তারেক আজিজ','01602713819','01760621057','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(158,10,'Ramkaisna Ray','রামকৃষ্ণ রায়','01602559430','01928146292','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(159,11,'Sajidul Islam Sakib','সাজিদুল ইসলাম সাকিব','01642727294','01838597580','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(160,12,'Md. Shahinur Rahman','মোঃ শাহিনুর রহমান','01954800046','01311269213','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(161,13,'Zinnatun Nessa Akhi','জিন্নাতুন নেছা আঁখি','01733840415','01711223325','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(162,14,'Mst. Ayesha Akter','মোছাঃ আয়েশা আক্তার','01745766325','01734039940','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(163,15,'Mst. Nirjona Khatun','মোছাঃ নির্জনা খাতুন','01920473757','01920723156','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(164,16,'Mohammad Shohidul Hoque','মোহাম্মদ সহিদুল হক','01712530918','01780255901','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(165,17,'Anik Mazumder','অনিক মজুমদার','01686928490','01886928490','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(166,18,'Tasfia Maharun Pretha','তাসফিয়া মেহেরুন প্রথা','01912266251','01712011957','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(167,19,'Jannatul Farah','জান্নাতুল ফারাহ','01858094925','01819803522','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(168,20,'Sumaya Afrin Swety','সুমাইয়া আফরীন সুইটি','01726474078','01793403831','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(169,21,'Ontora Akter','অন্তরা আক্তার','01774626174','01792255141','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(170,22,'Tasnova Jaman','তাসনুবা জামান','01775231266','01711074154','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(171,23,'Nuchaiba Rahman','নুছাইবা রহমান','01977249527','01927249527','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(172,24,'Mahamudul Alam','মাহমুদুল আলম','01749160416','01732175516','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(173,25,'Nusrat Jahan Jerin','নুসরাত জাহান জেরিন','01644354303','01991498186','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(174,26,'Mst. Afiea Akter Mim','মোছাঃ আফিয়া আক্তার মীম','01300683870','01745326323','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(175,27,'Sanjida Akter','সানজিদা আক্তার','01326269326','01782973486','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(176,28,'Maria Jahan','মারিয়া জাহান','01830717466','01716370742','L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(177,29,'—','—',NULL,NULL,'L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(178,30,'—','—',NULL,NULL,'L-6','BSc in Health Technology (Laboratory)','2022-23','2026-07-25 19:09:20'),(179,1,'Sadiya Sultana Purnima','সাদিয়া সুলতানা পূর্ণিমা','01754472328','01556778171','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(180,2,'Md. Rabiul Aual','মোঃ রবিউল আউয়াল','01762696673','01783593055','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(181,3,'Mst. Mayna Aktar Ritu','মোছাঃ ময়না আক্তার রিতু','01402857155','01581782499','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(182,4,'Samin Sadat','সামীন সাদাত','01537716577','01728437776','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(183,5,'Shishir Das','শিশির দাস','01533841880','01712155422','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(184,6,'Md. Arafat Hossain Siam','মোঃ আরাফাত হোসাইন সিয়াম','01406299303','01923140072','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(185,7,'Tapati Rani Paul Tithi','তপতী রানী পাল তিথি','01707021681','01714567867','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(186,8,'Nusrat Jahan Noon','নুসরাত জাহান নুন','01821315317','01718517337','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(187,9,'Rafia Masud','রাফিয়া মাসুদ','01610998681','01857790650','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(188,10,'Md. Rezowan Islam','মোঃ রেজওয়ান ইসলাম','01629290180','01708930893','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(189,11,'Sumaiya Afrin','সুমাইয়া আফরিন','01327960366','01714259914','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(190,12,'Rojina Akter','রোজিনা আক্তার','01934065789','01752790219','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(191,13,'Amrin Shibbir','আমরিন শিব্বির','01813782110','01813394254','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(192,14,'Suraiya Zahan Tanjina','সুরাইয়া জাহান তানজিনা','01942036270','01862593336','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(193,15,'Md. Samrat Sarkar','মোঃ সম্রাট সরকার','01882289644','01819026944','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(194,16,'Saruwar Hossen','সারোয়ার হোসেন','01727446978','01757204243','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(195,17,'Josna Afroj','জোসনা আফরোজ','01778607164','01739110666','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(196,18,'Abu Raiyan','আবু রাইয়ান','01929799171','01711983364','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(197,19,'Ahasanul Haque','এহসানুল হক','01767622051','01752799589','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(198,20,'Umme Habiba','উম্মে হাবিবা','01850798862','01828840232','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(199,21,'Md. Touhidul Islam','মোঃ তৌহিদুল ইসলাম','01937975247','01961837458','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(200,22,'Mst. Doly Khatun','মোছাঃ ডলি খাতুন','01837251950','01768691613','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(201,23,'Mst. Taslima Sultana','মোছাঃ তাছলিমা সুলতানা (তিথি)','01308924152','01738404517','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(202,24,'Kantj Fatema','কানিজ ফাতেমা','01623628868','01729362246','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(203,25,'Md. Sakibul Hasan','মোঃ ছাকিবুল হাসান','01980788130','01721657148','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(204,26,'Sadiya Akiher','সাদিয়া আক্তার','01322659135','01307271196','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(205,27,'Khadizatul Kubra','খাদিজাতুল কুবরা','01320584222','01317043633','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(206,28,'Nafisa Tabassum','নাফিসা তাবাসসুম','01644274151','01630823002','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(207,29,'Yeasin Arafat','ইয়াছিন আরাফাত','01615826447','01811619297','L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(208,30,'—','—',NULL,NULL,'L-7','BSc in Health Technology (Laboratory)','2023-24','2026-07-25 19:09:20'),(209,1,'Arifa Arobi','আরিফা আরবী','01853319881','01818721290','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(210,2,'Mst. Zallatul Ferdous Tuli','মোছাঃ জল্লাতুল ফেরদৌস তুলি','01724052659','01733478813','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(211,3,'Sharifa Khattun','শরিফা খাতুন','01753764904','01717056360','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(212,4,'Tanjila Khanom Sravonti','তানজিলা খানম শ্রাবন্তী','01980354724','01821314384','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(213,5,'Prodip Mondal','প্রদীপ মন্ডল','01308268879','01318664569','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(214,6,'Prachi Das','প্রাচী দাস','01303015741','01764520887','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(215,7,'Mst. Tasnim Jabin Setu','মোছাঃ তাসলিম জাবিন সেতু','01797149677','01311900983','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(216,8,'Affifa Islam Sinha','আফিফা ইসলাম সিনহা','01786835922','01944767636','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(217,9,'Tisha Akther','তিশা আক্তার','01311487209','01973604902','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(218,10,'Mosa. Afrin Akhter Asha','মোছাঃ আফরিন আক্তার আশা','01750321788','01747443292','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(219,11,'Nhu Nobi Ulla Reyaj','নূহ নবী উল্লাহ রিয়াজ','01867915935','01743825017','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(220,12,'Kingkor Kumar Mondal','কিংকর কুমার মন্ডল','01788300250','01540332876','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(221,13,'Sadia Afrin Tonni','সাদিয়া আফরিন তন্নী','01406579443','01734865843','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(222,14,'Mst. Merajul Jallat','মোছাঃ মেরাজুল জান্নাত','01767808741','01767808116','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(223,15,'Subrata Dev Nath','সুব্রত দেব নাথ','01533791755','01717188559','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(224,16,'H Amidul Hoque','হামিদুল হক','01837595015','01825440122','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(225,17,'Md. Ibrahim Hossain','মোঃ ইব্রাহীম হোসেন','01738143453','01734700443','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(226,18,'Mst.Fatema','মোছাঃ ফাতেমা','01316320440','01759968542','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(227,19,'Mosa Hafsa Akter Nisi','মোসাঃ হাফসা আক্তার নিশি','01312266597','01729646232','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(228,20,'Md. Fozle Rabbi','মোঃ ফজলে রাব্বী','01765511722','01986989237','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(229,21,'Shahnaj Akther','শাহনাজ আক্তার','01302748363','01751736450','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(230,22,'Sabuj Misuj','সবুজ মিশ্রী','01967334279','01408697668','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(231,23,'Ajmeer Hossain','আজমির হোসেন','01306636773','01336303761','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(232,24,'Md. Sobuz Mia','মোঃ সবুজ মিয়া','01810226547','01822346895','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(233,25,'Mahpara Karim','মাহপারা করিম','01700573441','01721769083','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(234,26,'Md. Ariful Islam','মোঃ আরিফুল ইসলাম','01302100984','01719860289','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(235,27,'Priya Akter','প্রিয়া আক্তার','01846786955','01875700837','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(236,28,'Md.Mantu Mia','মোঃ মন্টু মিয়া','01919880227','01742461228','L-8','BSc in Health Technology (Laboratory)','2024-25','2026-07-25 19:09:20'),(237,1,'Raisa Tabassum','রাইসা তাবাস্সুম','01728776422','01912389441','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(238,2,'Eramoni','ইরামনি','01870812076','01713743669','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(239,3,'Shuhaima Jannat Khusboo','সুহাইমা জান্নাত খুশবু','01910049210','01714140610','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(240,4,'Prapty Ghosh','প্রাপ্তি ঘোষ','01732831639','01739207905','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(241,5,'Khandaker Arfan Abid','খন্দকার আরফান আবিদ','01905901558','01925168551','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(242,6,'Rushmia Islam Rinve','রুসমিয়া ইসলাম রিনভী','01609290164','01765839437','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(243,7,'Tasnim','তাসনিম','01999058583','01753171515','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(244,8,'Md. Selim Reza','মোঃ সেলিম রেজা','01722807542','01912585276','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(245,9,'Muhtasim Hossain Pritom','মোহতাসিম হোসেন প্রীতম','01626778818','01912159264','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(246,10,'Pritom Roy','প্রিতম রায়','01780963303','01732795019','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(247,11,'Taneya','তনেয়া','01817656733','01740977430','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(248,12,'Md. Tamjid Shah','মোঃ তামজীদ শাহ','01540013350','01896298243','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(249,13,'Rimo Akter','রিমু আক্তার','01887063116','01746343416','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(250,14,'Jannat Ara','জান্নাত আরা','01813078485','01778780442','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(251,15,'Rakib Hasan','রাকিব হাসান','01629008563','01721978109','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(252,16,'Md. Khadimul Islam','মোঃ খাদিমুল ইসলাম','01734457918','01722666851','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(253,17,'Arafat Akter','আরাফাত আক্তার','01571380111','01612569190','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(254,18,'Md. Morsalin Islam','মোঃ মোরসালিন ইসলাম','01632269614','01716280797','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(255,19,'Most. Taslima Akter','মোছাঃ তাছলিমা আক্তার','01892469909','01774326470','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(256,20,'Md. Taybur Rahman','মোঃ তৈয়বুর রহমান','01959762447','01744822349','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(257,21,'Akram Hossain','আকরাম হোসাইন','01727022332','01745324315','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(258,22,'Meher Afroj Rajkumari','মেহের আফরোজ রাজকুমারী','01747053416','01865133262','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(259,23,'Rokeya Akter','রোকেয়া আক্তার','01323528105','01785414197','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(260,24,'Maria Islam','মারিয়া ইসলাম','01743959900','01714273266','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(261,25,'Munshi Tanvir Ahmed','মুন্সী তানভীর আহমেদ','01858263597','01718023934','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(262,26,'Rahat Ibne Momen','রাহাত ইবনে মোমেন','01706273774','01718466092','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(263,27,'Md. Shourov Mia','মোঃ সৌরভ মিয়া','01737498636','01708351179','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(264,28,'Mst. Rumpa Khatun','মোছাঃ রুম্পা খাতুন','01301062698','01763897099','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(265,29,'Rakib Mia','রাকিব মিয়া','01878812886','01930159684','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(266,30,'Mst. Sumona Islam Bonna','মোছাঃ সুমনা ইসলাম বন্যা','01727367723','01780674983','L-9','BSc in Health Technology (Laboratory)','2025-26','2026-07-25 19:09:20'),(267,1,'Dhruba Sheuli Arka','ধ্রুব শিউলী অর্ক','01770643077','01715195279','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(268,2,'Abu Bakar Shaikh','আবু বকর শেখ','01622438363','01914823978','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(269,3,'Ahmed Imtiaz','আহমদ ইমতিয়াজ','01947365814','01720032114','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(270,4,'Sharmin Sultana','শারমিন সুলতানা','01758023745','01836630985','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(271,5,'Md. Shakil Ahmed','মোঃ শাকিল আহমেদ','01787499552','01723604644','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(272,6,'Shakil Mahamud Emon','শাকিল মাহামুদ ইমন','01844216336','01844134501','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(273,7,'Md. Abdur Rahman','মোঃ আব্দুর রহমান','01704516020','01766001227','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(274,8,'Md. Al - Jakaria Rupom','মোঃ আল-জাকারিয়া রূপম','01863828511','01744911671','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(275,9,'Salmoon M. Sazzad','সালমুন এম. সাজ্জাদ','01536208396','01916146666','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(276,10,'Zarin Tasnim Taseen','যারীন তাসনিম তাসীন','01534227094','01715226144','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(277,11,'Md. Sakib Hasan','মোঃ সাকিব হাসান','01307732868','01625339434','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(278,12,'Kona Akter','কনা আক্তার','01690274808','01762836596','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(279,13,'Rashida Yasmin Moni','রাশিদা ইয়াসমিন মনি','01854778661','01716270816','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(280,14,'Md. Nazrul Islam','মোঃ নজরুল ইসলাম','01772147635','01822009635','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(281,15,'Md. Kabirul Islam','মোঃ কবিরুল ইসলাম','01782287841','01749030304','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(282,16,'Gulzar Rahman','গুলজার রহমান','01732349661','01770142020','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(283,17,'Bulbul Ahmed','বুলবুল আহমেদ','01726976235','01741186260','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(284,18,'Md. Noor Alam','মোঃ নূর আলম','01717743369','01792744077','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(285,19,'Md. Abul Hasnat','মোঃ আবুল হাসনাত','01673476394','01719722685','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(286,20,'Md. Nur - E - Alam','মোঃ নূরে আলম','01746986621','01740358623','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(287,21,'Mohammad Ziaur Rahman','মুহাম্মদ জিয়াউর রহমান','01924991411','01731176460','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(288,22,'Md. Abu Yousup','মোঃ আবু ইউছুপ','01714483289','01956000354','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(289,23,'Muhammad Rakibur Rahman','মোহাম্মদ রাকিবুর রহমান','01778571775','01782908490','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(290,24,'Md. Al Amin','মোঃ আল আমিন','01912311053','01962710516','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(291,25,'Mohammad Mojibur Rahman','মোহাম্মদ মজিবুর রহমান','01716416341','01915355059','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(292,26,'Anik Das','অনিক দাস','01780797527','01726082145','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(293,27,'Lubna Akter Nili','লুবনা আক্তার নিলি','01516041550','01718006439','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(294,28,'Md. Abdulla Khan','মোঃ আব্দুল্লা খান','01956159201','01955793427','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(295,29,'Shirin Akter','শিরীন আক্তার','01684681008','01685890012','F-1','BSc in Health Technology (Food Safety)','2019-20','2026-07-25 19:09:20'),(296,1,'Mahady Hasan Maruf','মেহেদী হাসান মারুফ','01998220254','01954870842','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(297,2,'Jannatul Kabir Mim','জান্নাতুল কবির মিম','01841019137','01601187203','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(298,3,'Md. Nahid Akter Peash (FF)','মোঃ নাহিদ আক্তার পিয়াস','01611256227','01721393979','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(299,4,'Most. Morsalina Mony','মোছাঃ মোরছালিনা মনি','01686455643','01777168963','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(300,5,'Md. Somrat Hossen','মোঃ সম্রাট হোসেন','01609835064','01914559516','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(301,6,'Md. Kamruzzaman Musha','মোঃ কামরুজ্জামান মুসা','01518957681','01723927658','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(302,7,'Nusrat Jahan Sami','নুসরাত জাহান সামি','01937393189','01716809824','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(303,8,'Md. Amanullah Asif','মোঃ আমানুল্লাহ আসিফ','01314513394','01727943542','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(304,9,'Mahmudul Hasan','মাহমুদুল হাসান','01790305367','01302704541','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(305,10,'Md. Al Hasan Pervez','মোঃ আল হাসান পারভেজ','01743746288','01926188092','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(306,11,'Shabbir Ahmad','সাব্বির আহমেদ','01907584154','01874231813','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(307,12,'Md. Imran Mondol','মোঃ ইমরান মন্ডল','01648795358','01703336132','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(308,13,'Maksud Alam','মাকসুদ আলম','01602699709','01978954847','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(309,14,'Md. Masudur Rahman','মোঃ মাসুদুর রহমান','01856034461','01634548051','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(310,15,'Umme Habiba','উম্মে হাবিবা','01850798862','01828840232','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(311,16,'Md Aub Zehad','মোঃ আবু জেহাদ','01723446364','01718830555','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(312,17,'Surayea Yasmin','সুরাইয়া ইয়াসমীন','01752153322','01734599936','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(313,18,'Hamidul Hoque','হামিদুল হক','01837595015','01825440122','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(314,19,'Ratan Chandra Pal','রতন চন্দ্র পাল','01737651710','01768910963','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(315,20,'Mohima Akter','মহিমা আক্তার','01992859027','01717247498','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(316,21,'Faizun Nesa Shimul','ফাইজুন নেসা শিমুল','01681732677','01683059453','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(317,22,'Halimatuz Sadia','হালিমা তুজ সাদিয়া','01607787203','01776783474','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(318,23,'Md. Abdullah Al Noman','মোঃ আব্দুল্লাহ আল নোমান','01576599786','01814916378','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(319,24,'Md. Safiqul Islam','মোঃ শফিকুল ইসলাম','01736637078','01778396201','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(320,25,'Md. Abdur Rahman','মুহাম্মদ আব্দুর রহমান','01992787242','01742707290','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(321,26,'Mst. Israt Jahan Nabila','মোসাঃ ইসরাত জাহান নাবিলা','01706112114','01782832605','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(322,27,'Md. Shohag Hossaen Swadhin','মোঃ সোহাগ হোসেন স্বাধীন','01789879947','01796516161','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(323,28,'Md. Habibur Rahman Patwary','মোঃ হাবিবুর রহমান পাটওয়ারী','01818850227','01859962364','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(324,29,'Md. Naim Islam','মোঃ নাঈম ইসলাম','01987527990','01915180059','F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(325,30,'—','—',NULL,NULL,'F-2','BSc in Health Technology (Food Safety)','2022-23','2026-07-25 19:09:20'),(326,1,'Syeda Shahruba Tasnim','সৈয়দা শেহরুবা তাসনীম','01727758452','01819258882','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:20'),(327,2,'Manik Mistri Darson','মানিক মিস্ত্রী দর্শন','01518994746','01870748706','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(328,3,'Md. Saewan','মোঃ ছাফওয়ান','01872860038','01710930699','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(329,4,'Luna Akter','লুনা আক্তার','01306870766','01633439816','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(330,5,'Ashok Chandra Kha','অশোক চন্দ্র খাঁ','01860137634','01731252740','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(331,6,'Arshi Ayan','আরশী আয়ান','01681681033','01711970260','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(332,7,'Ankita Ghosh','অঙ্কিতা ঘোষ','01403807480','01718736093','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(333,8,'Gulam Ahmed Rifat','গোলাম আহম্মেদ রিফাত','01889371695','01918007356','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(334,9,'Mishirunnahar','মিশিরুন্নাহার','01994376212','01721676164','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(335,10,'Zakiya Sultana Zoya','জাকিয়া সুলতানা জয়া','01609164039','01936024423','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(336,11,'Srity Noor Hanisa','স্মৃতি নূর হানিসা',NULL,'01871390233','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(337,12,'Md. Raj Mia','মোঃ রাজ মিয়া','01521734925','01749994705','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(338,13,'Md. Ibrahim Khalil','মোঃ ইব্রাহিম খলিল','01862719709','01881587018','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(339,14,'Suraiya Mustarin','সুরাইয়া মুস্তারিন','01795088943','01721660567','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(340,15,'Israt Jahan Mim','ইসরাত জাহান মিম','01874668194','01983952353','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(341,16,'Nusrat Jahan','নুসরাত জাহান','01783599978','01726245045','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(342,17,'Md. Abdur Rahim','মোঃ আব্দুর রহিম','01737325943','01921282943','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(343,18,'Ummey Badoura Sultana','উম্মে বেদৌরা সুলতানা','01716844947','01718657889','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(344,19,'Mohammad Nayan Mia','মোহাম্মদ নয়ন মিয়া','01714736006','01755026588','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(345,20,'Mst. Mastura Mabiath Joya','মোছাঃ মাসতুরা মাবিয়াত জয়া','01639673666','01718799793','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(346,21,'Shova Aktar Shelley','শোভা আক্তার শেলী','01712666080','01712251601','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(347,22,'Ummay Farjana Khanam','উম্মে ফারজানা খানম','01960558382','01779702623','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(348,23,'Fyroze Raisa Ridme','ফাইরুজ রাইসা রিদমি','01630885722','01716383243','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(349,24,'Md. Hafizur Rahman','মোঃ হাফিজুর রহমান','01975123624','01916414707','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(350,25,'Monoze Chakma','মনোজ চাকমা','01613181859','01553759324','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(351,26,'Shoheli Aktari','সোহেলী আক্তারী','01787409491','01721589777','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(352,27,'Md. Shakil Mia','মোঃ শাকিল মিয়া','01329049871','01723837739','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(353,28,'Mst. Jarin Tasnim Shova','মোছাঃ জারিন তাসনিম শোভা','01822512979','01749120368','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(354,29,'Soleman Hossain','সোলেমান হোসেন','01837143425','01849462384','F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(355,30,'—','—',NULL,NULL,'F-3','BSc in Health Technology (Food Safety)','2023-24','2026-07-25 19:09:21'),(356,1,'Morshedul Alam','মোরশেদুল আলম','01871805076','01893667114','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(357,2,'Mst. Jonain Tasnim Topa','মোছাঃ জুনাইন তাসলিম তপা','01304455233','01715529438','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(358,3,'Fariha Farbin','ফারিয়া ফারবিন','01737077516','01725335856','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(359,4,'Himika Akhier Sanika','হিমিকা আক্তার সানিকা','01772703596','01783665666','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(360,5,'Md. Arman Hossain','মোঃ আরমান হোসাইন','01846255110','01816099639','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(361,6,'Nahid Hasan','নাহিদ হাসান','01721285924','01710747763','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(362,7,'Zinat Rahman','জিনাত রহমান','01883532496','01778333975','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(363,8,'Tahsin Saba','তাহসিন সাবা','01537740910','01531930417','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(364,9,'Mst. Hanufa Akter Hasi','মোছাঃ হানুফা আক্তার হাসি','01739389103','01716400339','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(365,10,'Nishat Tasnim','নিশাত তাসনিম','01644277541','01714652623','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(366,11,'Md. Taj Sarker','মোঃ তাজ সরকার','01759413595','01820572985','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(367,12,'Tabassum Nishat','তাবাসসুম নিশাত','01772139919','01819887660','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(368,13,'Md. Israfil Hossain','মোঃ ইসরাফিল হোসেন','01751452015','01716416341','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(369,14,'Mst.Reshma Khatun','মোছাঃ রেশমা খাতুন','01739039078','01712596364','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(370,15,'Khairul Islal','খায়রুল ইসলাম','01924656058','01909177280','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(371,16,'Mohammad Azad Miah','মোহাম্মদ আজাদ মিয়া','01710677419','01740582918','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(372,17,'Sonia Akter','সোনিয়া আক্তার','01742220933','01712888418','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(373,18,'Sohag Chandra Roni','সোহাগ চন্দ্র রনি','01725976799','01814761413','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(374,19,'Md. Al-Amin','মোঃ আল-আমিন','01726702004','01787961242','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(375,20,'Md. Wahiduzzaman Molla','মোঃ অহিদুজ্জামান মোল্যা','01726418590','01911435088','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(376,21,'Sanzida Sultana','সানজিদা সুলতানা','01731704317','01983863330','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(377,22,'Musfikur Rahman','মুশফিকুর রহমান','01732382423','01746294086','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(378,23,'Md. Omar Faruk','মোঃ ওমর ফারুক','01914112646','01716416341','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(379,24,'Md. Ashraful Alam','মোঃ আশরাফুল আলম','01723089248','01827577122','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(380,25,'Md. Annur Rahaman','মোঃ আন-নূর রহমান','01760308969','01759590148','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(381,26,'Md. Mamunur Rashid','মোঃ মামুনুর রশিদ','01977598386','01736598386','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(382,27,'Md. Shamim Bhuia','মোঃ শামিম ভূঁইয়া','01717431053','01716221936','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(383,28,'Mst. Nusrat Jahan Nisa Mony','মোছাঃ সুসরাত জাহান নিশ মনি','01783385776','01778355297','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(384,29,'Md Sharif Islam Joy','মোঃ শরীফ ইসলাম জয়','01819248542','01745030393','F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(385,30,'—','—',NULL,NULL,'F-4','BSc in Health Technology (Food Safety)','2024-25','2026-07-25 19:09:21'),(386,1,'Hridta Saha Shuchi','হৃদিতা সাহা শুচি','01944219690','01723678504','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(387,2,'Adiba Nazmin','আদিবা নাজমিন','01819667532','01305101549','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(388,3,'Abdur Rahman Shihab','আব্দুর রহমান শিহাব','01650284850','01792594914','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(389,4,'Rafiya Karim Rifa','রাফিয়া করিম রিফা','01795131507','01715571919','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(390,5,'Hasin Akhlak','হাসিন আখলাক','01918499576','01746083844','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(391,6,'Soumik Roy Karmakar','সৌমিক রায় কর্মকার','01648072752','01819434037','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(392,7,'Jenia Afroz Jeon','জিনিয়া আফরোজ জিয়ন','01793538795','01755502863','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(393,8,'Samira Jaman','সামিরা জামান','01908289040','01713902215','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(394,9,'Amit Kumar Roy','অমিত কুমার রায়','01707895655','01303930342','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(395,10,'Sultana Razia Tuli','সুলতানা রাজিয়া তুলি','01746765092','01774887830','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(396,11,'Selina Akter','সেলিনা আক্তার','01749683689','01316317489','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(397,12,'Md. Abuzar Gaffar','মোঃ আবুজার গাফফার','01916800244','01744813684','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(398,13,'Mir AL Rashed Hasan','মীর আল রাশেদ হাসান','01711511252','01711514333','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(399,14,'S.M. Mokteruzzaman','এস. এম. মোক্তারুজ্জামান','01713902557','01710027920','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(400,15,'Md. Sayful Islam','মোঃ সাইফুল ইসলাম','01615370515','01814950202','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(401,16,'Rinku Kumar Sutradar','রিংকু কুমার সূত্রধর','01820194347','01819869169','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(402,17,'Md. Nafiz Uddin','মোঃ নাফিজ উদ্দীন','01733943190','01716559750','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(403,18,'Shushanto Kumar Sharkar','সুশান্ত কুমার সরকার','01727523126','01323108397','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(404,19,'Abu Sayed Khan','আবু সাইদ খান','01845302029','01768959286','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(405,20,'Md. Sarfaraj Khan','মোঃ সরফরাজ খান','01718404625','01310632626','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(406,21,'Md. Jalal Hossain','মোঃ জালাল হোসাইন','01724660326','01929498386','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(407,22,'Mehedi Hasan','মেহেদী হাসান','01725287010','01861085383','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(408,23,'Md. Shaifuddin','মোঃ সাইফুদ্দিন','01818297239','01618297239','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(409,24,'Partha Deb Nath','পার্থ দেবনাথ','01313729569','01907092759','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(410,25,'Oishi Bikrom','ঐশি বিক্রম','01716987952','01734558104','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(411,26,'Nabiha Tahsin Barsha','নাবিহা তাহসীন বর্ষা','01957746724','01915150519','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(412,27,'Shirazul Islam','সিরাজুল ইসলাম','01723057334','01733939709','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(413,28,'Ripat Parvin Zeba','মোঃ রহিমুল্লাহ','01915519946','01718272227','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(414,29,'—','—',NULL,NULL,'F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(415,30,'—','—',NULL,NULL,'F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(416,NULL,'প্রোগ্রাম','সেশন',NULL,NULL,'F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(417,1,'BSc Health Technology (Laboratory)','2015-16','1','28','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(418,2,'BSc Health Technology (Laboratory)','2016-17','2','30','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(419,3,'BSc Health Technology (Laboratory)','2017-18','3','30','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(420,4,'BSc Health Technology (Laboratory)','2018-19','4','30','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(421,5,'BSc Health Technology (Laboratory)','2019-20','5','30','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(422,6,'BSc Health Technology (Laboratory)','2022-23','6','28','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(423,7,'BSc Health Technology (Laboratory)','2023-24','7','29','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(424,8,'BSc Health Technology (Laboratory)','2024-25','8','28','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(425,9,'BSc Health Technology (Laboratory)','2025-26','9','30','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(426,10,'BSc Health Technology (Food Safety)','2019-20','1','29','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(427,11,'Health Technology (Food Safety)','2022-23','2','29','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(428,12,'Health Technology (Food Safety)','2023-24','3','29','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(429,13,'Health Technology (Food Safety)','2024-25','4','29','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21'),(430,14,'Health Technology (Food Safety)','2025-26','5','28','F-5','BSc in Health Technology (Food Safety)','2025-26','2026-07-25 19:09:21');
/*!40000 ALTER TABLE `students_reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `success_stories`
--

DROP TABLE IF EXISTS `success_stories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `success_stories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(300) NOT NULL,
  `slug` varchar(320) NOT NULL,
  `batch_year` varchar(50) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','draft','published','rejected') NOT NULL DEFAULT 'pending',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_story_profile` (`profile_id`),
  CONSTRAINT `fk_story_profile` FOREIGN KEY (`profile_id`) REFERENCES `alumni_profiles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `success_stories`
--

LOCK TABLES `success_stories` WRITE;
/*!40000 ALTER TABLE `success_stories` DISABLE KEYS */;
INSERT INTO `success_stories` VALUES (1,NULL,'Leading public health initiatives across Bangladesh','leading-public-health-initiatives-across-bangladesh','2010','From a single district health office to national policy advisory.','Dr. Arif Rahman, graduating in Batch 2010, has successfully pioneered the decentralization of health policy implementation, starting as a district health officer and now serving as a senior advisor to the national regulatory board. His framework has optimized supply chains across 40+ districts.',NULL,'published',1,'2026-07-23 17:29:57','2026-07-23 17:29:57',NULL),(2,NULL,'Research published in WHO Bulletin','research-published-in-who-bulletin','2015','Now heading an epidemiology research lab in Dhaka.','Dr. Tasnim Ara, Batch 2015, published her landmark study on vaccine distribution logistics in developing areas. The research, featured in the World Health Organization (WHO) Bulletin, has provided a roadmap for modern epidemiological modeling.',NULL,'published',1,'2026-07-23 17:29:57','2026-07-23 17:29:57',NULL),(3,NULL,'Training 300+ health workers annually','training-300-health-workers-annually','2020','Runs the certification track the association endorses.','Faisal Karim, Batch 2020, launched the Health Worker Empowerment Initiative, which conducts hands-on training clinics and issues certifications. Currently, the initiative empowers over 300 professionals every year to deliver primary care.',NULL,'published',1,'2026-07-23 17:29:57','2026-07-23 17:29:57',NULL),(4,1,'Test Blog','test-blog-480','L-4','HTEC এর যাত্রা শুরু হলো যেভাবে','IPH এর শিক্ষার্থীদের হাত ধরে যেভাবে শুরু হলো HTEC','story_1785066615_8b23a4d2.png','published',0,'2026-07-26 17:25:52','2026-07-26 17:52:52',NULL);
/*!40000 ALTER TABLE `success_stories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `universities`
--

DROP TABLE IF EXISTS `universities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `universities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(100) NOT NULL DEFAULT 'Bangladesh',
  `name` varchar(300) NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_country_univ` (`country`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `universities`
--

LOCK TABLES `universities` WRITE;
/*!40000 ALTER TABLE `universities` DISABLE KEYS */;
INSERT INTO `universities` VALUES (1,'Bangladesh','University of Dhaka (DU)',NULL,'2026-07-26 12:19:53'),(2,'Bangladesh','Bangladesh University of Engineering and Technology (BUET)',NULL,'2026-07-26 12:19:53'),(3,'Bangladesh','Institute of Public Health (IPH)',NULL,'2026-07-26 12:19:54'),(4,'Bangladesh','Dhaka Medical College (DMC)',NULL,'2026-07-26 12:19:54'),(5,'Bangladesh','Chittagong Medical College (CMC)',NULL,'2026-07-26 12:19:54'),(6,'Bangladesh','Jahangirnagar University (JU)',NULL,'2026-07-26 12:19:54'),(7,'Bangladesh','Rajshahi University (RU)',NULL,'2026-07-26 12:19:54'),(8,'Bangladesh','Shahjalal University of Science and Technology (SUST)',NULL,'2026-07-26 12:19:54'),(9,'Bangladesh','Bangladesh Agricultural University (BAU)',NULL,'2026-07-26 12:19:54'),(10,'Bangladesh','Bangabandhu Sheikh Mujib Medical University (BSMMU)',NULL,'2026-07-26 12:19:54'),(11,'Bangladesh','National Institute of Preventive and Social Medicine (NIPSOM)',NULL,'2026-07-26 12:19:54'),(12,'Bangladesh','North South University (NSU)',NULL,'2026-07-26 12:19:54'),(13,'Bangladesh','BRAC University',NULL,'2026-07-26 12:19:54'),(14,'Bangladesh','Independent University, Bangladesh (IUB)',NULL,'2026-07-26 12:19:54'),(15,'Bangladesh','East West University (EWU)',NULL,'2026-07-26 12:19:54'),(16,'Bangladesh','Ahsanullah University of Science and Technology (AUST)',NULL,'2026-07-26 12:19:54'),(17,'United States','Harvard University',NULL,'2026-07-26 12:19:54'),(18,'United States','Massachusetts Institute of Technology (MIT)',NULL,'2026-07-26 12:19:54'),(19,'United States','Stanford University',NULL,'2026-07-26 12:19:54'),(20,'United States','Columbia University',NULL,'2026-07-26 12:19:54'),(21,'United States','University of California, Berkeley',NULL,'2026-07-26 12:19:54'),(22,'United States','Johns Hopkins University',NULL,'2026-07-26 12:19:54'),(23,'United States','Yale University',NULL,'2026-07-26 12:19:54'),(24,'United States','New York University (NYU)',NULL,'2026-07-26 12:19:54'),(25,'United Kingdom','University of Oxford',NULL,'2026-07-26 12:19:54'),(26,'United Kingdom','University of Cambridge',NULL,'2026-07-26 12:19:54'),(27,'United Kingdom','Imperial College London',NULL,'2026-07-26 12:19:54'),(28,'United Kingdom','University College London (UCL)',NULL,'2026-07-26 12:19:54'),(29,'United Kingdom','University of Manchester',NULL,'2026-07-26 12:19:54'),(30,'Canada','University of Toronto',NULL,'2026-07-26 12:19:54'),(31,'Canada','University of British Columbia (UBC)',NULL,'2026-07-26 12:19:54'),(32,'Canada','McGill University',NULL,'2026-07-26 12:19:54'),(33,'Canada','University of Waterloo',NULL,'2026-07-26 12:19:54'),(34,'Australia','The University of Melbourne',NULL,'2026-07-26 12:19:54'),(35,'Australia','The University of Sydney',NULL,'2026-07-26 12:19:54'),(36,'Australia','Australian National University (ANU)',NULL,'2026-07-26 12:19:54'),(37,'Australia','The University of Queensland',NULL,'2026-07-26 12:19:54'),(38,'Japan','The University of Tokyo',NULL,'2026-07-26 12:19:54'),(39,'Japan','Kyoto University',NULL,'2026-07-26 12:19:54'),(40,'Japan','Osaka University',NULL,'2026-07-26 12:19:54'),(41,'Germany','Technical University of Munich (TUM)',NULL,'2026-07-26 12:19:54'),(42,'Germany','Ludwig Maximilian University of Munich (LMU)',NULL,'2026-07-26 12:19:54'),(43,'Germany','Heidelberg University',NULL,'2026-07-26 12:19:54'),(44,'Spain','University of Barcelona',NULL,'2026-07-26 12:26:07'),(45,'Spain','Autonomous University of Madrid',NULL,'2026-07-26 12:26:07'),(46,'Spain','Complutense University of Madrid',NULL,'2026-07-26 12:26:07'),(47,'Spain','Polytechnic University of Catalonia',NULL,'2026-07-26 12:26:07'),(48,'Spain','IE University',NULL,'2026-07-26 12:26:07'),(49,'Spain','University of Navarra',NULL,'2026-07-26 12:26:07'),(50,'Spain','University of Valencia',NULL,'2026-07-26 12:26:07'),(51,'Spain','Pompeu Fabra University',NULL,'2026-07-26 12:26:07'),(52,'France','Sorbonne University',NULL,'2026-07-26 12:26:07'),(53,'France','PSL University (Paris Sciences et Lettres)',NULL,'2026-07-26 12:26:07'),(54,'France','École Polytechnique',NULL,'2026-07-26 12:26:07'),(55,'France','Université Paris-Saclay',NULL,'2026-07-26 12:26:07'),(56,'France','HEC Paris',NULL,'2026-07-26 12:26:07'),(57,'France','Sciences Po',NULL,'2026-07-26 12:26:07'),(58,'France','University of Strasbourg',NULL,'2026-07-26 12:26:07'),(59,'Italy','Sapienza University of Rome',NULL,'2026-07-26 12:26:07'),(60,'Italy','University of Bologna',NULL,'2026-07-26 12:26:07'),(61,'Italy','Polytechnic University of Milan',NULL,'2026-07-26 12:26:07'),(62,'Italy','University of Padua',NULL,'2026-07-26 12:26:07'),(63,'Italy','University of Milan',NULL,'2026-07-26 12:26:07'),(64,'Netherlands','University of Amsterdam',NULL,'2026-07-26 12:26:07'),(65,'Netherlands','Delft University of Technology (TU Delft)',NULL,'2026-07-26 12:26:08'),(66,'Netherlands','Leiden University',NULL,'2026-07-26 12:26:08'),(67,'Netherlands','Utrecht University',NULL,'2026-07-26 12:26:08'),(68,'Netherlands','Erasmus University Rotterdam',NULL,'2026-07-26 12:26:08'),(69,'Sweden','Karolinska Institute',NULL,'2026-07-26 12:26:08'),(70,'Sweden','KTH Royal Institute of Technology',NULL,'2026-07-26 12:26:08'),(71,'Sweden','Lund University',NULL,'2026-07-26 12:26:08'),(72,'Sweden','Uppsala University',NULL,'2026-07-26 12:26:08'),(73,'Sweden','Stockholm University',NULL,'2026-07-26 12:26:08'),(74,'Switzerland','ETH Zurich',NULL,'2026-07-26 12:26:08'),(75,'Switzerland','EPFL (École Polytechnique Fédérale de Lausanne)',NULL,'2026-07-26 12:26:08'),(76,'Switzerland','University of Zurich',NULL,'2026-07-26 12:26:08'),(77,'Switzerland','University of Geneva',NULL,'2026-07-26 12:26:08'),(78,'Austria','University of Vienna',NULL,'2026-07-26 12:26:08'),(79,'Austria','Vienna University of Technology',NULL,'2026-07-26 12:26:08'),(80,'Russia','Lomonosov Moscow State University',NULL,'2026-07-26 12:26:08'),(81,'Russia','Saint Petersburg State University',NULL,'2026-07-26 12:26:08'),(82,'Russia','HSE University',NULL,'2026-07-26 12:26:08'),(83,'Turkey','Middle East Technical University (METU)',NULL,'2026-07-26 12:26:08'),(84,'Turkey','Boğaziçi University',NULL,'2026-07-26 12:26:08'),(85,'Turkey','Istanbul Technical University',NULL,'2026-07-26 12:26:08'),(86,'Turkey','Bilkent University',NULL,'2026-07-26 12:26:08'),(87,'South Korea','Seoul National University (SNU)',NULL,'2026-07-26 12:26:08'),(88,'South Korea','KAIST (Korea Advanced Institute of Science & Technology)',NULL,'2026-07-26 12:26:08'),(89,'South Korea','Yonsei University',NULL,'2026-07-26 12:26:08'),(90,'South Korea','Korea University',NULL,'2026-07-26 12:26:08'),(91,'South Korea','POSTECH',NULL,'2026-07-26 12:26:08'),(92,'China','Tsinghua University',NULL,'2026-07-26 12:26:08'),(93,'China','Peking University',NULL,'2026-07-26 12:26:08'),(94,'China','Fudan University',NULL,'2026-07-26 12:26:08'),(95,'China','Zhejiang University',NULL,'2026-07-26 12:26:08'),(96,'China','Shanghai Jiao Tong University',NULL,'2026-07-26 12:26:08'),(97,'India','Indian Institute of Technology (IIT)',NULL,'2026-07-26 12:26:08'),(98,'India','Indian Institute of Science (IISc Bengaluru)',NULL,'2026-07-26 12:26:08'),(99,'India','University of Delhi',NULL,'2026-07-26 12:26:08'),(100,'India','Jawaharlal Nehru University (JNU)',NULL,'2026-07-26 12:26:08'),(101,'Pakistan','Quaid-i-Azam University',NULL,'2026-07-26 12:26:08'),(102,'Pakistan','National University of Sciences and Technology (NUST)',NULL,'2026-07-26 12:26:08'),(103,'Pakistan','LUMS (Lahore University of Management Sciences)',NULL,'2026-07-26 12:26:08'),(104,'Pakistan','University of the Punjab',NULL,'2026-07-26 12:26:08'),(105,'Bangladesh','Institute Of Public Health',1,'2026-07-26 18:19:08');
/*!40000 ALTER TABLE `universities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(10) unsigned NOT NULL DEFAULT 1,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','editor','committee','alumni','guest') NOT NULL DEFAULT 'alumni',
  `signature_image` varchar(255) DEFAULT NULL,
  `status` enum('active','pending','suspended','banned') NOT NULL DEFAULT 'pending',
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `login_attempts` tinyint(4) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_status` (`status`),
  KEY `idx_users_org` (`organization_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'Mahmudur Rahman Imon','admin@iphalumni.org','$2y$12$YnsEpvn/8vZbtBbXTYWmGOETzOvOtULScmxIm65GGy2GJv1970hgq','admin',NULL,'active','avatar_1_2aebef12.png',NULL,NULL,NULL,NULL,0,NULL,'2026-07-23 15:28:23','2026-09-03 15:22:05',NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `yearly_budgets`
--

DROP TABLE IF EXISTS `yearly_budgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `yearly_budgets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fiscal_year` varchar(50) NOT NULL,
  `category` varchar(100) NOT NULL,
  `allocated_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `yearly_budgets`
--

LOCK TABLES `yearly_budgets` WRITE;
/*!40000 ALTER TABLE `yearly_budgets` DISABLE KEYS */;
/*!40000 ALTER TABLE `yearly_budgets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-04 13:24:36
