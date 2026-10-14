-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: helpdesk
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint unsigned NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attachments_ticket_id_foreign` (`ticket_id`),
  CONSTRAINT `attachments_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attachments`
--

LOCK TABLES `attachments` WRITE;
/*!40000 ALTER TABLE `attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Sprzęt','Problemy z komputerami, monitorami i innym sprzętem biurowym.','2026-10-14 21:08:07','2026-10-14 21:08:07'),(2,'Oprogramowanie','Instalacja, aktywacja i błędy w działaniu programów.','2026-10-14 21:08:07','2026-10-14 21:08:07'),(3,'Sieć i Internet','Połączenie z Internetem, Wi-Fi, VPN i zasoby sieciowe.','2026-10-14 21:08:08','2026-10-14 21:08:08'),(4,'Konta i dostęp','Hasła, blokady kont oraz uprawnienia do systemów.','2026-10-14 21:08:08','2026-10-14 21:08:08'),(5,'Drukarki','Drukarki, skanery i urządzenia wielofunkcyjne.','2026-10-14 21:08:08','2026-10-14 21:08:08'),(6,'Inne','Pozostałe pytania i prośby.','2026-10-14 21:08:08','2026-10-14 21:08:08');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_09_211439_create_categories_table',1),(5,'2026_10_09_211505_create_tickets_table',1),(6,'2026_10_09_211519_create_ticket_comments_table',1),(7,'2026_10_09_211530_create_ticket_histories_table',1),(8,'2026_10_09_211541_create_attachments_table',1),(9,'2026_10_09_211552_add_role_and_is_active_to_users_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_comments`
--

DROP TABLE IF EXISTS `ticket_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticket_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_comments_ticket_id_foreign` (`ticket_id`),
  KEY `ticket_comments_user_id_foreign` (`user_id`),
  CONSTRAINT `ticket_comments_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ticket_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_comments`
--

LOCK TABLES `ticket_comments` WRITE;
/*!40000 ALTER TABLE `ticket_comments` DISABLE KEYS */;
INSERT INTO `ticket_comments` VALUES (1,1,2,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-09-03 05:08:10','2026-09-03 05:08:10'),(2,2,6,'Sprawdziłem – wszystko działa, dziękuję za pomoc!','2026-10-11 21:08:10','2026-10-11 21:08:10'),(3,3,9,'Problem wrócił, proszę o ponowne sprawdzenie.','2026-09-04 23:08:10','2026-09-04 23:08:10'),(4,5,3,'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?','2026-08-17 21:08:10','2026-08-17 21:08:10'),(5,5,9,'Dziękuję za szybką reakcję. Problem występuje od wczoraj rano.','2026-08-18 12:08:10','2026-08-18 12:08:10'),(6,5,3,'Sprawdziłem ustawienia i wykonałem restart usługi. Proszę o test i informację zwrotną.','2026-08-19 02:08:10','2026-08-19 02:08:10'),(7,7,3,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-08-18 22:08:10','2026-08-18 22:08:10'),(8,7,6,'Komunikat o błędzie pojawia się za każdym razem po uruchomieniu programu.','2026-08-19 15:08:10','2026-08-19 15:08:10'),(9,8,3,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-08-28 04:08:10','2026-08-28 04:08:10'),(10,8,9,'Problem wrócił, proszę o ponowne sprawdzenie.','2026-08-28 22:08:10','2026-08-28 22:08:10'),(11,8,3,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-08-29 18:08:10','2026-08-29 18:08:10'),(12,9,4,'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?','2026-09-12 15:08:10','2026-09-12 15:08:10'),(13,10,7,'Komunikat o błędzie pojawia się za każdym razem po uruchomieniu programu.','2026-09-24 07:08:10','2026-09-24 07:08:10'),(14,11,4,'Sprawdziłem ustawienia i wykonałem restart usługi. Proszę o test i informację zwrotną.','2026-10-11 00:08:10','2026-10-11 00:08:10'),(15,11,8,'Próbowałem restartu, niestety bez rezultatu.','2026-10-11 02:08:10','2026-10-11 02:08:10'),(16,11,4,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-10-11 14:08:10','2026-10-11 14:08:10'),(17,12,4,'Sprawdziłem ustawienia i wykonałem restart usługi. Proszę o test i informację zwrotną.','2026-08-21 05:08:10','2026-08-21 05:08:10'),(18,12,7,'Próbowałem restartu, niestety bez rezultatu.','2026-08-21 15:08:10','2026-08-21 15:08:10'),(19,13,3,'Wymieniłem uszkodzony element. Proszę potwierdzić, że wszystko działa poprawnie.','2026-09-07 04:08:10','2026-09-07 04:08:10'),(20,13,7,'Komunikat o błędzie pojawia się za każdym razem po uruchomieniu programu.','2026-09-07 22:08:10','2026-09-07 22:08:10'),(21,14,2,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-09-17 17:08:10','2026-09-17 17:08:10'),(22,14,7,'Sprawdziłem – wszystko działa, dziękuję za pomoc!','2026-09-18 07:08:10','2026-09-18 07:08:10'),(23,15,3,'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?','2026-09-18 00:08:10','2026-09-18 00:08:10'),(24,15,7,'Problem wrócił, proszę o ponowne sprawdzenie.','2026-09-18 17:08:10','2026-09-18 17:08:10'),(25,16,3,'Sprawdziłem ustawienia i wykonałem restart usługi. Proszę o test i informację zwrotną.','2026-09-30 23:08:10','2026-09-30 23:08:10'),(26,16,7,'Sprawdziłem – wszystko działa, dziękuję za pomoc!','2026-10-01 14:08:10','2026-10-01 14:08:10'),(27,16,3,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-10-01 19:08:10','2026-10-01 19:08:10'),(28,17,4,'Sprawdziłem ustawienia i wykonałem restart usługi. Proszę o test i informację zwrotną.','2026-09-04 04:08:10','2026-09-04 04:08:10'),(29,17,9,'Sprawdziłem – wszystko działa, dziękuję za pomoc!','2026-09-04 11:08:10','2026-09-04 11:08:10'),(30,17,4,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-09-05 05:08:10','2026-09-05 05:08:10'),(31,17,9,'Komunikat o błędzie pojawia się za każdym razem po uruchomieniu programu.','2026-09-05 09:08:10','2026-09-05 09:08:10'),(32,18,3,'Sprawdziłem ustawienia i wykonałem restart usługi. Proszę o test i informację zwrotną.','2026-10-06 23:08:10','2026-10-06 23:08:10'),(33,18,5,'Dziękuję za szybką reakcję. Problem występuje od wczoraj rano.','2026-10-07 11:08:10','2026-10-07 11:08:10'),(34,18,3,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-10-08 06:08:10','2026-10-08 06:08:10'),(35,18,5,'Próbowałem restartu, niestety bez rezultatu.','2026-10-08 13:08:10','2026-10-08 13:08:10'),(36,19,2,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-09-04 01:08:10','2026-09-04 01:08:10'),(37,20,2,'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?','2026-08-23 10:08:10','2026-08-23 10:08:10'),(38,21,4,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-08-31 08:08:10','2026-08-31 08:08:10'),(39,21,7,'Problem wrócił, proszę o ponowne sprawdzenie.','2026-09-01 04:08:10','2026-09-01 04:08:10'),(40,21,4,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-09-01 14:08:10','2026-09-01 14:08:10'),(41,22,3,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-08-23 03:08:10','2026-08-23 03:08:10'),(42,23,2,'Sprawdziłem ustawienia i wykonałem restart usługi. Proszę o test i informację zwrotną.','2026-09-12 12:08:10','2026-09-12 12:08:10'),(43,23,5,'Dziękuję za szybką reakcję. Problem występuje od wczoraj rano.','2026-09-12 13:08:10','2026-09-12 13:08:10'),(44,23,2,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-09-13 03:08:10','2026-09-13 03:08:10'),(45,23,5,'Próbowałem restartu, niestety bez rezultatu.','2026-09-13 20:08:10','2026-09-13 20:08:10'),(46,24,2,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-10-12 10:08:10','2026-10-12 10:08:10'),(47,24,5,'Komunikat o błędzie pojawia się za każdym razem po uruchomieniu programu.','2026-10-12 15:08:10','2026-10-12 15:08:10'),(48,24,2,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-10-13 00:08:10','2026-10-13 00:08:10'),(49,25,3,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-08-21 16:08:10','2026-08-21 16:08:10'),(50,25,8,'Sprawdziłem – wszystko działa, dziękuję za pomoc!','2026-08-22 10:08:10','2026-08-22 10:08:10'),(51,25,3,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-08-23 01:08:10','2026-08-23 01:08:10'),(52,25,8,'Sprawdziłem – wszystko działa, dziękuję za pomoc!','2026-08-23 19:08:10','2026-08-23 19:08:10'),(53,26,2,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-09-14 01:08:11','2026-09-14 01:08:11'),(54,26,5,'Sprawdziłem – wszystko działa, dziękuję za pomoc!','2026-09-14 16:08:11','2026-09-14 16:08:11'),(55,26,2,'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?','2026-09-15 00:08:11','2026-09-15 00:08:11'),(56,27,4,'Wymieniłem uszkodzony element. Proszę potwierdzić, że wszystko działa poprawnie.','2026-09-17 12:08:11','2026-09-17 12:08:11'),(57,27,8,'Komunikat o błędzie pojawia się za każdym razem po uruchomieniu programu.','2026-09-18 06:08:11','2026-09-18 06:08:11'),(58,28,3,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-09-15 18:08:11','2026-09-15 18:08:11'),(59,29,5,'Komunikat o błędzie pojawia się za każdym razem po uruchomieniu programu.','2026-10-09 11:08:11','2026-10-09 11:08:11'),(60,30,3,'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?','2026-08-27 04:08:11','2026-08-27 04:08:11'),(61,30,5,'Dziękuję za szybką reakcję. Problem występuje od wczoraj rano.','2026-08-27 15:08:11','2026-08-27 15:08:11'),(62,30,3,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-08-28 06:08:11','2026-08-28 06:08:11'),(63,30,5,'Problem wrócił, proszę o ponowne sprawdzenie.','2026-08-28 08:08:11','2026-08-28 08:08:11'),(64,31,3,'Sprawdziłem ustawienia i wykonałem restart usługi. Proszę o test i informację zwrotną.','2026-08-30 03:08:11','2026-08-30 03:08:11'),(65,31,9,'Problem wrócił, proszę o ponowne sprawdzenie.','2026-08-30 04:08:11','2026-08-30 04:08:11'),(66,31,3,'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?','2026-08-30 20:08:11','2026-08-30 20:08:11'),(67,32,2,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-08-30 23:08:11','2026-08-30 23:08:11'),(68,32,5,'Komunikat o błędzie pojawia się za każdym razem po uruchomieniu programu.','2026-08-31 14:08:11','2026-08-31 14:08:11'),(69,33,2,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-08-30 14:08:11','2026-08-30 14:08:11'),(70,34,4,'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?','2026-09-03 21:08:11','2026-09-03 21:08:11'),(71,34,8,'Dziękuję za szybką reakcję. Problem występuje od wczoraj rano.','2026-09-04 12:08:11','2026-09-04 12:08:11'),(72,35,8,'Próbowałem restartu, niestety bez rezultatu.','2026-09-15 01:08:11','2026-09-15 01:08:11'),(73,36,4,'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?','2026-08-21 14:08:11','2026-08-21 14:08:11'),(74,38,2,'Wymieniłem uszkodzony element. Proszę potwierdzić, że wszystko działa poprawnie.','2026-09-14 18:08:11','2026-09-14 18:08:11'),(75,38,7,'Problem wrócił, proszę o ponowne sprawdzenie.','2026-09-15 12:08:11','2026-09-15 12:08:11'),(76,39,4,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-08-28 04:08:11','2026-08-28 04:08:11'),(77,39,7,'Dziękuję za szybką reakcję. Problem występuje od wczoraj rano.','2026-08-28 05:08:11','2026-08-28 05:08:11'),(78,39,4,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-08-28 11:08:11','2026-08-28 11:08:11'),(79,39,7,'Sprawdziłem – wszystko działa, dziękuję za pomoc!','2026-08-29 07:08:11','2026-08-29 07:08:11'),(80,40,5,'Próbowałem restartu, niestety bez rezultatu.','2026-08-28 03:08:11','2026-08-28 03:08:11'),(81,41,7,'Komunikat o błędzie pojawia się za każdym razem po uruchomieniu programu.','2026-09-08 03:08:11','2026-09-08 03:08:11'),(82,42,2,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-08-22 19:08:11','2026-08-22 19:08:11'),(83,42,8,'Komunikat o błędzie pojawia się za każdym razem po uruchomieniu programu.','2026-08-23 14:08:11','2026-08-23 14:08:11'),(84,42,2,'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?','2026-08-24 09:08:11','2026-08-24 09:08:11'),(85,43,3,'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.','2026-09-09 18:08:11','2026-09-09 18:08:11'),(86,44,3,'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?','2026-08-17 07:08:11','2026-08-17 07:08:11'),(87,44,9,'Problem wrócił, proszę o ponowne sprawdzenie.','2026-08-17 11:08:11','2026-08-17 11:08:11'),(88,45,3,'Sprawdziłem ustawienia i wykonałem restart usługi. Proszę o test i informację zwrotną.','2026-08-30 07:08:11','2026-08-30 07:08:11'),(89,45,5,'Komunikat o błędzie pojawia się za każdym razem po uruchomieniu programu.','2026-08-30 13:08:11','2026-08-30 13:08:11'),(90,45,3,'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?','2026-08-30 14:08:11','2026-08-30 14:08:11'),(91,46,7,'Dziękuję za szybką reakcję. Problem występuje od wczoraj rano.','2026-09-26 06:08:11','2026-09-26 06:08:11'),(92,47,2,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-08-29 17:08:11','2026-08-29 17:08:11'),(93,47,9,'Problem wrócił, proszę o ponowne sprawdzenie.','2026-08-30 06:08:11','2026-08-30 06:08:11'),(94,48,3,'Wymieniłem uszkodzony element. Proszę potwierdzić, że wszystko działa poprawnie.','2026-09-19 08:08:11','2026-09-19 08:08:11'),(95,48,5,'Próbowałem restartu, niestety bez rezultatu.','2026-09-20 02:08:11','2026-09-20 02:08:11'),(96,48,3,'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.','2026-09-20 18:08:11','2026-09-20 18:08:11'),(97,48,5,'Dziękuję za szybką reakcję. Problem występuje od wczoraj rano.','2026-09-21 05:08:11','2026-09-21 05:08:11'),(98,49,3,'Wymieniłem uszkodzony element. Proszę potwierdzić, że wszystko działa poprawnie.','2026-10-12 10:08:11','2026-10-12 10:08:11'),(99,50,9,'Sprawdziłem – wszystko działa, dziękuję za pomoc!','2026-09-05 19:08:11','2026-09-05 19:08:11');
/*!40000 ALTER TABLE `ticket_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_history`
--

DROP TABLE IF EXISTS `ticket_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticket_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `field` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_history_ticket_id_foreign` (`ticket_id`),
  KEY `ticket_history_user_id_foreign` (`user_id`),
  CONSTRAINT `ticket_history_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ticket_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_history`
--

LOCK TABLES `ticket_history` WRITE;
/*!40000 ALTER TABLE `ticket_history` DISABLE KEYS */;
INSERT INTO `ticket_history` VALUES (1,1,2,'agent',NULL,'Piotr Nowak','2026-09-03 03:08:10','2026-09-03 03:08:10'),(2,1,2,'status','Nowe','W realizacji','2026-09-03 20:08:10','2026-09-03 20:08:10'),(3,5,3,'agent',NULL,'Katarzyna Wiśniewska','2026-08-17 01:08:10','2026-08-17 01:08:10'),(4,5,3,'status','Nowe','W realizacji','2026-08-19 03:08:10','2026-08-19 03:08:10'),(5,7,3,'agent',NULL,'Katarzyna Wiśniewska','2026-08-18 03:08:10','2026-08-18 03:08:10'),(6,7,3,'status','Nowe','W realizacji','2026-08-20 11:08:10','2026-08-20 11:08:10'),(7,8,3,'agent',NULL,'Katarzyna Wiśniewska','2026-08-27 18:08:10','2026-08-27 18:08:10'),(8,8,3,'status','Nowe','W realizacji','2026-08-29 23:08:10','2026-08-29 23:08:10'),(9,8,3,'status','W realizacji','Rozwiązane','2026-08-30 15:08:10','2026-08-30 15:08:10'),(10,8,9,'status','Rozwiązane','Zamknięte','2026-08-30 17:08:10','2026-08-30 17:08:10'),(11,9,4,'agent',NULL,'Tomasz Zieliński','2026-09-11 20:08:10','2026-09-11 20:08:10'),(12,9,4,'status','Nowe','W realizacji','2026-09-13 04:08:10','2026-09-13 04:08:10'),(13,9,4,'status','W realizacji','Rozwiązane','2026-09-13 17:08:10','2026-09-13 17:08:10'),(14,9,8,'status','Rozwiązane','Zamknięte','2026-09-13 22:08:10','2026-09-13 22:08:10'),(15,11,4,'agent',NULL,'Tomasz Zieliński','2026-10-10 07:08:10','2026-10-10 07:08:10'),(16,11,4,'status','Nowe','W realizacji','2026-10-11 20:08:10','2026-10-11 20:08:10'),(17,11,4,'status','W realizacji','Oczekuje na odpowiedź','2026-10-12 04:08:10','2026-10-12 04:08:10'),(18,12,4,'agent',NULL,'Tomasz Zieliński','2026-08-20 09:08:10','2026-08-20 09:08:10'),(19,12,4,'status','Nowe','W realizacji','2026-08-22 02:08:10','2026-08-22 02:08:10'),(20,12,4,'status','W realizacji','Rozwiązane','2026-08-22 18:08:10','2026-08-22 18:08:10'),(21,13,3,'agent',NULL,'Katarzyna Wiśniewska','2026-09-07 00:08:10','2026-09-07 00:08:10'),(22,13,3,'status','Nowe','W realizacji','2026-09-08 13:08:10','2026-09-08 13:08:10'),(23,13,3,'status','W realizacji','Rozwiązane','2026-09-08 18:08:10','2026-09-08 18:08:10'),(24,14,2,'agent',NULL,'Piotr Nowak','2026-09-16 20:08:10','2026-09-16 20:08:10'),(25,14,2,'priority','Średni','Niski','2026-09-17 11:08:10','2026-09-17 11:08:10'),(26,14,2,'status','Nowe','W realizacji','2026-09-18 13:08:10','2026-09-18 13:08:10'),(27,14,2,'status','W realizacji','Rozwiązane','2026-09-18 22:08:10','2026-09-18 22:08:10'),(28,15,3,'agent',NULL,'Katarzyna Wiśniewska','2026-09-17 20:08:10','2026-09-17 20:08:10'),(29,15,3,'status','Nowe','W realizacji','2026-09-19 09:08:10','2026-09-19 09:08:10'),(30,16,3,'agent',NULL,'Katarzyna Wiśniewska','2026-09-30 06:08:10','2026-09-30 06:08:10'),(31,16,3,'status','Nowe','W realizacji','2026-10-02 11:08:10','2026-10-02 11:08:10'),(32,16,3,'status','W realizacji','Rozwiązane','2026-10-02 19:08:10','2026-10-02 19:08:10'),(33,17,4,'agent',NULL,'Tomasz Zieliński','2026-09-03 07:08:10','2026-09-03 07:08:10'),(34,17,4,'priority','Średni','Krytyczny','2026-09-03 13:08:10','2026-09-03 13:08:10'),(35,17,4,'status','Nowe','W realizacji','2026-09-05 21:08:10','2026-09-05 21:08:10'),(36,18,3,'agent',NULL,'Katarzyna Wiśniewska','2026-10-06 02:08:10','2026-10-06 02:08:10'),(37,18,3,'priority','Średni','Niski','2026-10-06 11:08:10','2026-10-06 11:08:10'),(38,18,3,'status','Nowe','W realizacji','2026-10-08 23:08:10','2026-10-08 23:08:10'),(39,18,3,'status','W realizacji','Oczekuje na odpowiedź','2026-10-09 01:08:10','2026-10-09 01:08:10'),(40,19,2,'agent',NULL,'Piotr Nowak','2026-09-03 08:08:10','2026-09-03 08:08:10'),(41,19,2,'status','Nowe','W realizacji','2026-09-04 15:08:10','2026-09-04 15:08:10'),(42,20,2,'agent',NULL,'Piotr Nowak','2026-08-23 06:08:10','2026-08-23 06:08:10'),(43,20,2,'status','Nowe','W realizacji','2026-08-23 14:08:10','2026-08-23 14:08:10'),(44,20,2,'status','W realizacji','Oczekuje na odpowiedź','2026-08-24 08:08:10','2026-08-24 08:08:10'),(45,21,4,'agent',NULL,'Tomasz Zieliński','2026-08-30 22:08:10','2026-08-30 22:08:10'),(46,21,4,'status','Nowe','W realizacji','2026-09-02 04:08:10','2026-09-02 04:08:10'),(47,21,4,'status','W realizacji','Rozwiązane','2026-09-02 16:08:10','2026-09-02 16:08:10'),(48,21,7,'status','Rozwiązane','Zamknięte','2026-09-02 18:08:10','2026-09-02 18:08:10'),(49,22,3,'agent',NULL,'Katarzyna Wiśniewska','2026-08-22 21:08:10','2026-08-22 21:08:10'),(50,22,3,'status','Nowe','W realizacji','2026-08-23 09:08:10','2026-08-23 09:08:10'),(51,22,3,'status','W realizacji','Rozwiązane','2026-08-23 10:08:10','2026-08-23 10:08:10'),(52,23,2,'agent',NULL,'Piotr Nowak','2026-09-11 21:08:10','2026-09-11 21:08:10'),(53,23,2,'status','Nowe','W realizacji','2026-09-14 07:08:10','2026-09-14 07:08:10'),(54,23,2,'status','W realizacji','Rozwiązane','2026-09-14 11:08:10','2026-09-14 11:08:10'),(55,23,5,'status','Rozwiązane','Zamknięte','2026-09-15 04:08:10','2026-09-15 04:08:10'),(56,24,2,'agent',NULL,'Piotr Nowak','2026-10-11 18:08:10','2026-10-11 18:08:10'),(57,24,2,'priority','Średni','Krytyczny','2026-10-11 19:08:10','2026-10-11 19:08:10'),(58,24,2,'status','Nowe','W realizacji','2026-10-13 18:08:10','2026-10-13 18:08:10'),(59,24,2,'status','W realizacji','Oczekuje na odpowiedź','2026-10-14 09:08:10','2026-10-14 09:08:10'),(60,25,3,'agent',NULL,'Katarzyna Wiśniewska','2026-08-21 01:08:10','2026-08-21 01:08:10'),(61,25,3,'status','Nowe','W realizacji','2026-08-23 21:08:10','2026-08-23 21:08:10'),(62,25,3,'status','W realizacji','Rozwiązane','2026-08-24 04:08:10','2026-08-24 04:08:10'),(63,26,2,'agent',NULL,'Piotr Nowak','2026-09-13 16:08:11','2026-09-13 16:08:11'),(64,26,2,'priority','Średni','Wysoki','2026-09-13 19:08:11','2026-09-13 19:08:11'),(65,26,2,'status','Nowe','W realizacji','2026-09-15 06:08:11','2026-09-15 06:08:11'),(66,26,2,'status','W realizacji','Rozwiązane','2026-09-15 10:08:11','2026-09-15 10:08:11'),(67,26,5,'status','Rozwiązane','Zamknięte','2026-09-16 03:08:11','2026-09-16 03:08:11'),(68,27,4,'agent',NULL,'Tomasz Zieliński','2026-09-17 11:08:11','2026-09-17 11:08:11'),(69,27,4,'status','Nowe','W realizacji','2026-09-19 00:08:11','2026-09-19 00:08:11'),(70,28,3,'agent',NULL,'Katarzyna Wiśniewska','2026-09-15 11:08:11','2026-09-15 11:08:11'),(71,28,3,'status','Nowe','W realizacji','2026-09-15 20:08:11','2026-09-15 20:08:11'),(72,28,3,'status','W realizacji','Oczekuje na odpowiedź','2026-09-15 23:08:11','2026-09-15 23:08:11'),(73,30,3,'agent',NULL,'Katarzyna Wiśniewska','2026-08-26 11:08:11','2026-08-26 11:08:11'),(74,30,3,'status','Nowe','W realizacji','2026-08-28 21:08:11','2026-08-28 21:08:11'),(75,30,3,'status','W realizacji','Oczekuje na odpowiedź','2026-08-29 08:08:11','2026-08-29 08:08:11'),(76,31,3,'agent',NULL,'Katarzyna Wiśniewska','2026-08-29 22:08:11','2026-08-29 22:08:11'),(77,31,3,'status','Nowe','W realizacji','2026-08-30 21:08:11','2026-08-30 21:08:11'),(78,31,3,'status','W realizacji','Rozwiązane','2026-08-30 22:08:11','2026-08-30 22:08:11'),(79,32,2,'agent',NULL,'Piotr Nowak','2026-08-30 20:08:11','2026-08-30 20:08:11'),(80,32,2,'status','Nowe','W realizacji','2026-09-01 04:08:11','2026-09-01 04:08:11'),(81,32,2,'status','W realizacji','Rozwiązane','2026-09-01 21:08:11','2026-09-01 21:08:11'),(82,32,5,'status','Rozwiązane','Zamknięte','2026-09-02 17:08:11','2026-09-02 17:08:11'),(83,33,2,'agent',NULL,'Piotr Nowak','2026-08-30 10:08:11','2026-08-30 10:08:11'),(84,33,2,'status','Nowe','W realizacji','2026-08-30 19:08:11','2026-08-30 19:08:11'),(85,33,2,'status','W realizacji','Rozwiązane','2026-08-31 01:08:11','2026-08-31 01:08:11'),(86,33,5,'status','Rozwiązane','Zamknięte','2026-08-31 08:08:11','2026-08-31 08:08:11'),(87,34,4,'agent',NULL,'Tomasz Zieliński','2026-09-03 19:08:11','2026-09-03 19:08:11'),(88,34,4,'status','Nowe','W realizacji','2026-09-05 04:08:11','2026-09-05 04:08:11'),(89,34,4,'status','W realizacji','Rozwiązane','2026-09-05 19:08:11','2026-09-05 19:08:11'),(90,34,8,'status','Rozwiązane','Zamknięte','2026-09-06 09:08:11','2026-09-06 09:08:11'),(91,36,4,'agent',NULL,'Tomasz Zieliński','2026-08-21 11:08:11','2026-08-21 11:08:11'),(92,36,4,'status','Nowe','W realizacji','2026-08-21 19:08:11','2026-08-21 19:08:11'),(93,36,4,'status','W realizacji','Oczekuje na odpowiedź','2026-08-22 12:08:11','2026-08-22 12:08:11'),(94,38,2,'agent',NULL,'Piotr Nowak','2026-09-14 09:08:11','2026-09-14 09:08:11'),(95,38,2,'status','Nowe','W realizacji','2026-09-15 13:08:11','2026-09-15 13:08:11'),(96,39,4,'agent',NULL,'Tomasz Zieliński','2026-08-27 19:08:11','2026-08-27 19:08:11'),(97,39,4,'status','Nowe','W realizacji','2026-08-29 11:08:11','2026-08-29 11:08:11'),(98,42,2,'agent',NULL,'Piotr Nowak','2026-08-22 07:08:11','2026-08-22 07:08:11'),(99,42,2,'status','Nowe','W realizacji','2026-08-24 11:08:11','2026-08-24 11:08:11'),(100,43,3,'agent',NULL,'Katarzyna Wiśniewska','2026-09-09 13:08:11','2026-09-09 13:08:11'),(101,43,3,'status','Nowe','W realizacji','2026-09-10 00:08:11','2026-09-10 00:08:11'),(102,44,3,'agent',NULL,'Katarzyna Wiśniewska','2026-08-15 23:08:11','2026-08-15 23:08:11'),(103,44,3,'priority','Średni','Wysoki','2026-08-16 11:08:11','2026-08-16 11:08:11'),(104,44,3,'status','Nowe','W realizacji','2026-08-18 05:08:11','2026-08-18 05:08:11'),(105,44,3,'status','W realizacji','Rozwiązane','2026-08-18 16:08:11','2026-08-18 16:08:11'),(106,45,3,'agent',NULL,'Katarzyna Wiśniewska','2026-08-29 23:08:11','2026-08-29 23:08:11'),(107,45,3,'status','Nowe','W realizacji','2026-08-30 20:08:11','2026-08-30 20:08:11'),(108,47,2,'agent',NULL,'Piotr Nowak','2026-08-29 13:08:11','2026-08-29 13:08:11'),(109,47,2,'status','Nowe','W realizacji','2026-08-31 01:08:11','2026-08-31 01:08:11'),(110,47,2,'status','W realizacji','Rozwiązane','2026-08-31 14:08:11','2026-08-31 14:08:11'),(111,47,9,'status','Rozwiązane','Zamknięte','2026-09-01 01:08:11','2026-09-01 01:08:11'),(112,48,3,'agent',NULL,'Katarzyna Wiśniewska','2026-09-19 06:08:11','2026-09-19 06:08:11'),(113,48,3,'status','Nowe','W realizacji','2026-09-21 16:08:11','2026-09-21 16:08:11'),(114,48,3,'status','W realizacji','Oczekuje na odpowiedź','2026-09-21 21:08:11','2026-09-21 21:08:11'),(115,49,3,'agent',NULL,'Katarzyna Wiśniewska','2026-10-11 14:08:11','2026-10-11 14:08:11'),(116,49,3,'status','Nowe','W realizacji','2026-10-13 02:08:11','2026-10-13 02:08:11'),(117,49,3,'status','W realizacji','Rozwiązane','2026-10-13 20:08:11','2026-10-13 20:08:11'),(118,49,8,'status','Rozwiązane','Zamknięte','2026-10-14 10:08:11','2026-10-14 10:08:11');
/*!40000 ALTER TABLE `ticket_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `agent_id` bigint unsigned DEFAULT NULL,
  `category_id` bigint unsigned NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Średni',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Nowe',
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tickets_user_id_foreign` (`user_id`),
  KEY `tickets_agent_id_foreign` (`agent_id`),
  KEY `tickets_category_id_foreign` (`category_id`),
  KEY `tickets_status_index` (`status`),
  KEY `tickets_priority_index` (`priority`),
  CONSTRAINT `tickets_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tickets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (1,6,2,1,'Komputer nie uruchamia się (sala 10)','Po naciśnięciu przycisku zasilania komputer nie reaguje, kontrolki nie świecą.','Średni','W realizacji',NULL,'2026-09-02 09:08:10','2026-09-03 20:08:10'),(2,6,NULL,1,'Monitor miga i wyłącza się (sala 16)','Obraz na monitorze co kilka minut znika, a po chwili wraca.','Krytyczny','Nowe',NULL,'2026-10-11 14:08:10','2026-10-11 21:08:10'),(3,9,NULL,1,'Wymiana myszy i klawiatury (sala 39)','Klawiatura ma uszkodzone klawisze, a mysz działa z przerwami. Proszę o wymianę zestawu.','Niski','Nowe',NULL,'2026-09-04 11:08:10','2026-09-04 23:08:10'),(4,5,NULL,1,'Laptop bardzo głośno pracuje (sala 7)','Wentylator laptopa działa na maksymalnych obrotach, a obudowa mocno się nagrzewa.','Krytyczny','Nowe',NULL,'2026-10-01 18:08:10','2026-10-01 18:08:10'),(5,9,3,2,'Brak możliwości instalacji programu (sala 29)','System wyświetla komunikat o braku uprawnień przy instalacji wymaganego programu.','Niski','W realizacji',NULL,'2026-08-16 08:08:10','2026-08-19 03:08:10'),(6,9,NULL,2,'Pakiet Office wymaga aktywacji (sala 4)','Przy otwieraniu dokumentów pojawia się informacja o wygaśnięciu licencji.','Niski','Nowe',NULL,'2026-09-19 02:08:10','2026-09-19 02:08:10'),(7,6,3,2,'Program księgowy zawiesza się (sala 26)','Aplikacja przestaje odpowiadać podczas generowania raportów miesięcznych.','Średni','W realizacji',NULL,'2026-08-17 19:08:10','2026-08-20 11:08:10'),(8,9,3,2,'Aktualizacja systemu kończy się błędem (sala 22)','Aktualizacja zatrzymuje się na 30 procentach i komputer wraca do poprzedniej wersji.','Krytyczny','Zamknięte','2026-08-30 17:08:10','2026-08-27 04:08:10','2026-08-30 17:08:10'),(9,8,4,2,'Przeglądarka nie otwiera stron firmowych (sala 13)','Strony wewnętrzne wyświetlają błąd certyfikatu bezpieczeństwa.','Wysoki','Zamknięte','2026-09-13 22:08:10','2026-09-11 15:08:10','2026-09-13 22:08:10'),(10,7,NULL,3,'Brak dostępu do Internetu w pokoju (sala 34)','Od rana żaden komputer w pokoju nie ma połączenia z Internetem.','Wysoki','Nowe',NULL,'2026-09-23 21:08:10','2026-09-24 07:08:10'),(11,8,4,3,'Bardzo wolne połączenie Wi-Fi (sala 38)','Sieć bezprzewodowa działa wolno, a strony ładują się po kilkadziesiąt sekund.','Niski','Oczekuje na odpowiedź',NULL,'2026-10-09 12:08:10','2026-10-12 04:08:10'),(12,7,4,3,'Brak dostępu do dysku sieciowego (sala 33)','Folder wspólny nie otwiera się, system wyświetla komunikat o braku dostępu do ścieżki sieciowej.','Wysoki','Rozwiązane',NULL,'2026-08-20 05:08:10','2026-08-22 18:08:10'),(13,7,3,3,'Nie działa połączenie VPN (sala 1)','Klient VPN łączy się, ale po chwili połączenie jest zrywane.','Niski','Rozwiązane',NULL,'2026-09-06 09:08:10','2026-09-08 18:08:10'),(14,7,2,3,'Prośba o dodatkowe gniazdo sieciowe (sala 38)','W nowym stanowisku pracy brakuje gniazda sieciowego do podłączenia komputera.','Niski','Rozwiązane',NULL,'2026-09-16 16:08:10','2026-09-18 22:08:10'),(15,7,3,4,'Reset hasła do konta (sala 13)','Zapomniałem hasła do konta domenowego i nie mogę zalogować się do komputera.','Krytyczny','W realizacji',NULL,'2026-09-17 09:08:10','2026-09-19 09:08:10'),(16,7,3,4,'Konto zostało zablokowane (sala 39)','Po kilku nieudanych próbach logowania konto zostało zablokowane.','Niski','Rozwiązane',NULL,'2026-09-29 23:08:10','2026-10-02 19:08:10'),(17,9,4,4,'Prośba o dostęp do systemu kadrowego (sala 26)','Do realizacji nowych zadań potrzebuję dostępu do systemu kadrowego w trybie odczytu.','Krytyczny','W realizacji',NULL,'2026-09-02 20:08:10','2026-09-05 21:08:10'),(18,5,3,4,'Nowy pracownik – założenie konta (sala 24)','Prosimy o utworzenie konta pocztowego i domenowego dla nowego pracownika działu sprzedaży.','Niski','Oczekuje na odpowiedź',NULL,'2026-10-05 14:08:10','2026-10-09 01:08:10'),(19,6,2,4,'Brak dostępu do skrzynki wspólnej (sala 7)','Skrzynka e-mail działu nie wyświetla się w programie pocztowym.','Wysoki','W realizacji',NULL,'2026-09-03 05:08:10','2026-09-04 15:08:10'),(20,8,2,5,'Drukarka nie drukuje (sala 1)','Dokumenty trafiają do kolejki wydruku, ale drukarka ich nie drukuje.','Średni','Oczekuje na odpowiedź',NULL,'2026-08-22 22:08:10','2026-08-24 08:08:10'),(21,7,4,5,'Zacięcie papieru w drukarce (sala 20)','Drukarka sygnalizuje zacięcie papieru, choć w podajniku nic nie widać.','Średni','Zamknięte','2026-09-02 18:08:10','2026-08-30 19:08:10','2026-09-02 18:08:10'),(22,5,3,5,'Wydruki są blade i z paskami (sala 5)','Na kartkach pojawiają się poziome pasy, a tekst jest słabo widoczny.','Wysoki','Rozwiązane',NULL,'2026-08-22 20:08:10','2026-08-23 10:08:10'),(23,5,2,5,'Skaner nie wysyła plików na e-mail (sala 2)','Urządzenie wielofunkcyjne skanuje dokument, ale wiadomość z plikiem nie dociera.','Wysoki','Zamknięte','2026-09-15 04:08:10','2026-09-11 18:08:10','2026-09-15 04:08:10'),(24,5,2,6,'Prośba o dodatkowy monitor (sala 12)','Do pracy z arkuszami potrzebuję drugiego monitora przy stanowisku.','Krytyczny','Oczekuje na odpowiedź',NULL,'2026-10-11 13:08:10','2026-10-14 09:08:10'),(25,8,3,6,'Pytanie o zasady korzystania z poczty (sala 39)','Proszę o informację, jaki jest maksymalny rozmiar załącznika w wiadomościach e-mail.','Średni','Rozwiązane',NULL,'2026-08-20 07:08:10','2026-08-24 04:08:10'),(26,5,2,1,'Komputer nie uruchamia się (sala 38)','Po naciśnięciu przycisku zasilania komputer nie reaguje, kontrolki nie świecą.','Wysoki','Zamknięte','2026-09-16 03:08:11','2026-09-13 10:08:11','2026-09-16 03:08:11'),(27,8,4,1,'Monitor miga i wyłącza się (sala 15)','Obraz na monitorze co kilka minut znika, a po chwili wraca.','Średni','W realizacji',NULL,'2026-09-17 04:08:11','2026-09-19 00:08:11'),(28,5,3,1,'Wymiana myszy i klawiatury (sala 20)','Klawiatura ma uszkodzone klawisze, a mysz działa z przerwami. Proszę o wymianę zestawu.','Średni','Oczekuje na odpowiedź',NULL,'2026-09-15 10:08:11','2026-09-15 23:08:11'),(29,5,NULL,1,'Laptop bardzo głośno pracuje (sala 27)','Wentylator laptopa działa na maksymalnych obrotach, a obudowa mocno się nagrzewa.','Średni','Nowe',NULL,'2026-10-09 00:08:11','2026-10-09 11:08:11'),(30,5,3,2,'Brak możliwości instalacji programu (sala 28)','System wyświetla komunikat o braku uprawnień przy instalacji wymaganego programu.','Niski','Oczekuje na odpowiedź',NULL,'2026-08-26 10:08:11','2026-08-29 08:08:11'),(31,9,3,2,'Pakiet Office wymaga aktywacji (sala 16)','Przy otwieraniu dokumentów pojawia się informacja o wygaśnięciu licencji.','Niski','Rozwiązane',NULL,'2026-08-29 13:08:11','2026-08-30 22:08:11'),(32,5,2,2,'Program księgowy zawiesza się (sala 12)','Aplikacja przestaje odpowiadać podczas generowania raportów miesięcznych.','Krytyczny','Zamknięte','2026-09-02 17:08:11','2026-08-30 00:08:11','2026-09-02 17:08:11'),(33,5,2,2,'Aktualizacja systemu kończy się błędem (sala 32)','Aktualizacja zatrzymuje się na 30 procentach i komputer wraca do poprzedniej wersji.','Niski','Zamknięte','2026-08-31 08:08:11','2026-08-30 09:08:11','2026-08-31 08:08:11'),(34,8,4,2,'Przeglądarka nie otwiera stron firmowych (sala 19)','Strony wewnętrzne wyświetlają błąd certyfikatu bezpieczeństwa.','Wysoki','Zamknięte','2026-09-06 09:08:11','2026-09-03 17:08:11','2026-09-06 09:08:11'),(35,8,NULL,3,'Brak dostępu do Internetu w pokoju (sala 35)','Od rana żaden komputer w pokoju nie ma połączenia z Internetem.','Krytyczny','Nowe',NULL,'2026-09-14 12:08:11','2026-09-15 01:08:11'),(36,8,4,3,'Bardzo wolne połączenie Wi-Fi (sala 15)','Sieć bezprzewodowa działa wolno, a strony ładują się po kilkadziesiąt sekund.','Niski','Oczekuje na odpowiedź',NULL,'2026-08-21 02:08:11','2026-08-22 12:08:11'),(37,8,NULL,3,'Brak dostępu do dysku sieciowego (sala 22)','Folder wspólny nie otwiera się, system wyświetla komunikat o braku dostępu do ścieżki sieciowej.','Niski','Nowe',NULL,'2026-09-15 01:08:11','2026-09-15 01:08:11'),(38,7,2,3,'Nie działa połączenie VPN (sala 22)','Klient VPN łączy się, ale po chwili połączenie jest zrywane.','Krytyczny','W realizacji',NULL,'2026-09-13 14:08:11','2026-09-15 13:08:11'),(39,7,4,3,'Prośba o dodatkowe gniazdo sieciowe (sala 13)','W nowym stanowisku pracy brakuje gniazda sieciowego do podłączenia komputera.','Wysoki','W realizacji',NULL,'2026-08-27 12:08:11','2026-08-29 11:08:11'),(40,5,NULL,4,'Reset hasła do konta (sala 18)','Zapomniałem hasła do konta domenowego i nie mogę zalogować się do komputera.','Wysoki','Nowe',NULL,'2026-08-27 13:08:11','2026-08-28 03:08:11'),(41,7,NULL,4,'Konto zostało zablokowane (sala 23)','Po kilku nieudanych próbach logowania konto zostało zablokowane.','Średni','Nowe',NULL,'2026-09-07 22:08:11','2026-09-08 03:08:11'),(42,8,2,4,'Prośba o dostęp do systemu kadrowego (sala 39)','Do realizacji nowych zadań potrzebuję dostępu do systemu kadrowego w trybie odczytu.','Krytyczny','W realizacji',NULL,'2026-08-21 13:08:11','2026-08-24 11:08:11'),(43,5,3,4,'Nowy pracownik – założenie konta (sala 1)','Prosimy o utworzenie konta pocztowego i domenowego dla nowego pracownika działu sprzedaży.','Wysoki','W realizacji',NULL,'2026-09-09 00:08:11','2026-09-10 00:08:11'),(44,9,3,4,'Brak dostępu do skrzynki wspólnej (sala 30)','Skrzynka e-mail działu nie wyświetla się w programie pocztowym.','Wysoki','Rozwiązane',NULL,'2026-08-15 07:08:11','2026-08-18 16:08:11'),(45,5,3,5,'Drukarka nie drukuje (sala 29)','Dokumenty trafiają do kolejki wydruku, ale drukarka ich nie drukuje.','Krytyczny','W realizacji',NULL,'2026-08-29 13:08:11','2026-08-30 20:08:11'),(46,7,NULL,5,'Zacięcie papieru w drukarce (sala 24)','Drukarka sygnalizuje zacięcie papieru, choć w podajniku nic nie widać.','Wysoki','Nowe',NULL,'2026-09-26 02:08:11','2026-09-26 06:08:11'),(47,9,2,5,'Wydruki są blade i z paskami (sala 35)','Na kartkach pojawiają się poziome pasy, a tekst jest słabo widoczny.','Niski','Zamknięte','2026-09-01 01:08:11','2026-08-29 12:08:11','2026-09-01 01:08:11'),(48,5,3,5,'Skaner nie wysyła plików na e-mail (sala 11)','Urządzenie wielofunkcyjne skanuje dokument, ale wiadomość z plikiem nie dociera.','Niski','Oczekuje na odpowiedź',NULL,'2026-09-18 20:08:11','2026-09-21 21:08:11'),(49,8,3,6,'Prośba o dodatkowy monitor (sala 36)','Do pracy z arkuszami potrzebuję drugiego monitora przy stanowisku.','Krytyczny','Zamknięte','2026-10-14 10:08:11','2026-10-10 19:08:11','2026-10-14 10:08:11'),(50,9,NULL,6,'Pytanie o zasady korzystania z poczty (sala 7)','Proszę o informację, jaki jest maksymalny rozmiar załącznika w wiadomościach e-mail.','Średni','Nowe',NULL,'2026-09-05 11:08:11','2026-09-05 19:08:11');
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Anna Kowalska','admin@helpdesk.pl',NULL,'$2y$12$Q2b1PklxIHZubLVqGWm0r.SRC5F0BNs4GhCvryP743Ib/DIR2c012','admin',1,NULL,'2026-10-14 21:08:08','2026-10-14 21:08:08'),(2,'Piotr Nowak','technik1@helpdesk.pl',NULL,'$2y$12$4Blbjy4EEXRCvBNmtWvbQO.WGPZA7oxSQnbLP/dZMxuXY4CYpWr7q','agent',1,NULL,'2026-10-14 21:08:08','2026-10-14 21:08:08'),(3,'Katarzyna Wiśniewska','technik2@helpdesk.pl',NULL,'$2y$12$qfAELSjhl2OewzwcaDElz.zrvBFtUh4FgTP2Az2GO/g5bET9z5Uvu','agent',1,NULL,'2026-10-14 21:08:08','2026-10-14 21:08:08'),(4,'Tomasz Zieliński','technik3@helpdesk.pl',NULL,'$2y$12$OBEzi0k2t9XreoVwDDm7G.mhyDVQzwqgB/./36jp3X8go180GAmOu','agent',1,NULL,'2026-10-14 21:08:09','2026-10-14 21:08:09'),(5,'Marek Lewandowski','uzytkownik1@helpdesk.pl',NULL,'$2y$12$CFxV3hqw1yclJuQnjNEeWuuj5hdxoivh82l/h7YhAKikknKyELGjG','user',1,NULL,'2026-10-14 21:08:09','2026-10-14 21:08:09'),(6,'Joanna Kamińska','uzytkownik2@helpdesk.pl',NULL,'$2y$12$GdlcRC2LNw0Ztt9M9xTFd.5vq3qxy.5X0h36DQldbtA80cGvmxl.e','user',1,NULL,'2026-10-14 21:08:09','2026-10-14 21:08:09'),(7,'Michał Wójcik','uzytkownik3@helpdesk.pl',NULL,'$2y$12$Rwlpsf383IWRRQs27x6v0eAwiK7XxtYWZb5by5oDTtF3rbRLImE2K','user',1,NULL,'2026-10-14 21:08:09','2026-10-14 21:08:09'),(8,'Agnieszka Dąbrowska','uzytkownik4@helpdesk.pl',NULL,'$2y$12$zOIS7RNRZGnHlAqmzgwDgesl7Wce3hPtFgQwXrp1aY0wiIsNFAt5m','user',1,NULL,'2026-10-14 21:08:09','2026-10-14 21:08:09'),(9,'Paweł Kozłowski','uzytkownik5@helpdesk.pl',NULL,'$2y$12$OsR03JrJR5LPm3okOVzkIO2zBembZKaXux5r4vXjiW2Le5Jr2bs7S','user',0,NULL,'2026-10-14 21:08:10','2026-10-14 21:08:10');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-14 23:10:17
