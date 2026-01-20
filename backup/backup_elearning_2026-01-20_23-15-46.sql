mysqldump: [Warning] Using a password on the command line interface can be insecure.
-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: e-learning
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
-- Table structure for table `tb_admin`
--

DROP TABLE IF EXISTS `tb_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_admin` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_admin`
--

LOCK TABLES `tb_admin` WRITE;
/*!40000 ALTER TABLE `tb_admin` DISABLE KEYS */;
INSERT INTO `tb_admin` VALUES (1,'Sutan Kumala','Cikampek Karawang','089650007015','sutanhost@gmail.com','admin','21232f297a57a5a743894a0e4a801fc3','admin'),(2,'nur huda','jepara','082331838221','ibnuhasan3@gmail.com','huda2','d41d8cd98f00b204e9800998ecf8427e','huda2');
/*!40000 ALTER TABLE `tb_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_backup`
--

DROP TABLE IF EXISTS `tb_backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_backup` (
  `id_backup` int NOT NULL AUTO_INCREMENT,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ukuran` bigint NOT NULL,
  `tanggal_backup` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_backup`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_backup`
--

LOCK TABLES `tb_backup` WRITE;
/*!40000 ALTER TABLE `tb_backup` DISABLE KEYS */;
INSERT INTO `tb_backup` VALUES (2,'backup_elearning_2026-01-20_21-50-38.sql',25611,'2026-01-20 21:50:39'),(3,'backup_elearning_2026-01-20_21-54-37.sql',25686,'2026-01-20 21:54:38'),(4,'backup_elearning_2026-01-20_22-00-41.sql',25686,'2026-01-20 22:00:41');
/*!40000 ALTER TABLE `tb_backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_berita`
--

DROP TABLE IF EXISTS `tb_berita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_berita` (
  `id_berita` int NOT NULL AUTO_INCREMENT,
  `judul` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_posting` date NOT NULL,
  `penerbit` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','tidak aktif') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_berita`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_berita`
--

LOCK TABLES `tb_berita` WRITE;
/*!40000 ALTER TABLE `tb_berita` DISABLE KEYS */;
INSERT INTO `tb_berita` VALUES (1,'Alma Prayuda Menjadi Hacker','Siapa yang tidak tahu Hack Bae ? ya, ternyata HackBae adalah Alma Prayuda','2019-11-19','admin','aktif'),(2,'COVID-19','Gausah keluar, biar aku aja yang kerumah :)\r\n#DirumahAja','2020-04-14','admin','aktif'),(3,'TUGAS DIRUMAH','adaf','2021-01-25','2','aktif'),(4,'JADWAL ULANGAN HARIAN','JADWAL ULANGAN HARIAN \r\nHARI KAMIS TANGGAL 21 JANUARI 2021','2021-01-25','3','aktif');
/*!40000 ALTER TABLE `tb_berita` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_file_materi`
--

DROP TABLE IF EXISTS `tb_file_materi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_file_materi` (
  `id_materi` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas` int NOT NULL,
  `id_mapel` int NOT NULL,
  `nama_file` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_posting` date NOT NULL,
  `pembuat` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hits` int NOT NULL,
  PRIMARY KEY (`id_materi`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_file_materi`
--

LOCK TABLES `tb_file_materi` WRITE;
/*!40000 ALTER TABLE `tb_file_materi` DISABLE KEYS */;
INSERT INTO `tb_file_materi` VALUES (7,'Contoh Website Bangkrut',5,5,'icon.png','2019-11-19','admin',5),(8,'COVID-19',5,6,'hacker (2).jpg','2020-04-14','2',3),(9,'MATERI AKIDAH AKHLAK',9,5,'CONTOH PATUNG PLASTISIN.docx','2021-01-25','3',4);
/*!40000 ALTER TABLE `tb_file_materi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_jawaban`
--

DROP TABLE IF EXISTS `tb_jawaban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_jawaban` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_tq` int NOT NULL,
  `id_soal` int NOT NULL,
  `id_siswa` int NOT NULL,
  `jawaban` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_jawaban`
--

LOCK TABLES `tb_jawaban` WRITE;
/*!40000 ALTER TABLE `tb_jawaban` DISABLE KEYS */;
INSERT INTO `tb_jawaban` VALUES (59,6,9,10,'Formulir Pendaftaran pada website'),(60,16,10,13,'tuyul, gendruwo, pocong');
/*!40000 ALTER TABLE `tb_jawaban` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_kelas`
--

DROP TABLE IF EXISTS `tb_kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_kelas` (
  `id_kelas` int NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruang` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wali_kelas` int DEFAULT NULL,
  `ketua_kelas` int DEFAULT NULL,
  PRIMARY KEY (`id_kelas`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_kelas`
--

LOCK TABLES `tb_kelas` WRITE;
/*!40000 ALTER TABLE `tb_kelas` DISABLE KEYS */;
INSERT INTO `tb_kelas` VALUES (4,'1','1',3,13),(5,'2','1',1,11),(6,'3','1',3,12),(7,'4','1',2,11),(8,'5','1',1,14),(9,'6','1',2,15);
/*!40000 ALTER TABLE `tb_kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_kelas_ajar`
--

DROP TABLE IF EXISTS `tb_kelas_ajar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_kelas_ajar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_kelas` int NOT NULL,
  `id_pengajar` int NOT NULL,
  `keterangan` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_kelas_ajar`
--

LOCK TABLES `tb_kelas_ajar` WRITE;
/*!40000 ALTER TABLE `tb_kelas_ajar` DISABLE KEYS */;
INSERT INTO `tb_kelas_ajar` VALUES (6,2,1,''),(7,5,2,'Saya guru bhs indonesia RPL'),(8,4,2,'kelas daRING'),(9,10,2,'KELAS DARING BAHASA ARAB'),(10,6,2,'KELAS DARING IPA'),(11,9,2,'KELAS DARING 6'),(12,9,3,'KELAS DARING 6');
/*!40000 ALTER TABLE `tb_kelas_ajar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_mapel`
--

DROP TABLE IF EXISTS `tb_mapel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_mapel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_mapel` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mapel` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_mapel`
--

LOCK TABLES `tb_mapel` WRITE;
/*!40000 ALTER TABLE `tb_mapel` DISABLE KEYS */;
INSERT INTO `tb_mapel` VALUES (5,'AA','AKIDAH AKHLAK'),(6,'BINDO','Bahasa Indonesia'),(7,'IPS','IPS'),(8,'BA','Bahasa Arab'),(9,'PPKN','PPKN'),(10,'IPA','IPA'),(11,'SBDP','SBDP');
/*!40000 ALTER TABLE `tb_mapel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_mapel_ajar`
--

DROP TABLE IF EXISTS `tb_mapel_ajar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_mapel_ajar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_mapel` int NOT NULL,
  `id_kelas` int NOT NULL,
  `id_pengajar` int NOT NULL,
  `keterangan` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_mapel_ajar`
--

LOCK TABLES `tb_mapel_ajar` WRITE;
/*!40000 ALTER TABLE `tb_mapel_ajar` DISABLE KEYS */;
INSERT INTO `tb_mapel_ajar` VALUES (1,6,5,2,'Didikan Saya'),(2,5,9,2,'KELAS DARING AKIDAH AKHLAK'),(3,5,9,3,'KELAS DARING AKIDAH AKHLAK'),(4,6,9,3,''),(5,7,9,3,''),(6,10,9,3,''),(7,9,9,3,''),(8,8,9,3,''),(9,11,9,3,'');
/*!40000 ALTER TABLE `tb_mapel_ajar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_nilai_essay`
--

DROP TABLE IF EXISTS `tb_nilai_essay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_nilai_essay` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_tq` int NOT NULL,
  `id_siswa` int NOT NULL,
  `nilai` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_nilai_essay`
--

LOCK TABLES `tb_nilai_essay` WRITE;
/*!40000 ALTER TABLE `tb_nilai_essay` DISABLE KEYS */;
INSERT INTO `tb_nilai_essay` VALUES (12,6,10,100);
/*!40000 ALTER TABLE `tb_nilai_essay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_nilai_pilgan`
--

DROP TABLE IF EXISTS `tb_nilai_pilgan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_nilai_pilgan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_tq` int NOT NULL,
  `id_siswa` int NOT NULL,
  `benar` int NOT NULL,
  `salah` int NOT NULL,
  `tidak_dikerjakan` int NOT NULL,
  `presentase` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_nilai_pilgan`
--

LOCK TABLES `tb_nilai_pilgan` WRITE;
/*!40000 ALTER TABLE `tb_nilai_pilgan` DISABLE KEYS */;
INSERT INTO `tb_nilai_pilgan` VALUES (33,6,10,2,0,0,100),(35,12,11,2,0,0,100),(36,13,11,1,1,0,50),(37,14,12,2,0,0,100),(38,10,13,0,3,0,0),(39,16,13,0,1,0,0),(40,15,13,1,0,0,100),(41,17,13,1,0,0,100),(42,10,14,0,3,0,0),(43,10,15,0,0,3,0);
/*!40000 ALTER TABLE `tb_nilai_pilgan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_pengajar`
--

DROP TABLE IF EXISTS `tb_pengajar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_pengajar` (
  `id_pengajar` int NOT NULL AUTO_INCREMENT,
  `nip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `agama` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `web` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','tidak aktif') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_pengajar`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_pengajar`
--

LOCK TABLES `tb_pengajar` WRITE;
/*!40000 ALTER TABLE `tb_pengajar` DISABLE KEYS */;
INSERT INTO `tb_pengajar` VALUES (1,'696969','Harto Sujiwo','Tegal','1981-02-02','L','Islam','086969696969','enam@sembilan.com','Cikampek','Guru','anonim.png','https://google.com','sujiwo','3a3e9e8c50dd5bcb2b50ca83ed6ce8d4','sujiwo','aktif'),(2,'8987186','Toto harwo','Tegal','1976-05-05','L','Islam','09819820978','harwo@gmail.com','CIkampek','Guru Indonesia','PicsArt_04-14-03.51.05.jpg','https://facebook.com/harwo','harwo','b576a835519856161c3907af251e42ca','harwo','aktif'),(3,'131425425','Nur Huda','JEPARA','1979-01-04','L','Islam','082331838221','ibnu.hasan3@gmail.com','sukosono','wali kelas','DSC_0516 - Copy2.jpg','','huda2','0075a4e7a2e71083262da135ecdbdd14','huda2','aktif'),(4,'3456789','salwa nadira','JEPARA','2021-01-05','P','Islam','083444','','SUKOSONO','WALI KELAS','Screenshot_2020-11-06-16-08-46.png','','salwa','af003347e9ad13dcb79763e2f66339d5','salwa','aktif');
/*!40000 ALTER TABLE `tb_pengajar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_pengaturan`
--

DROP TABLE IF EXISTS `tb_pengaturan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_pengaturan` (
  `id_pengaturan` int NOT NULL AUTO_INCREMENT,
  `nama_sekolah` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kepala_sekolah` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip_kepsek` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_ajaran` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'logo.png',
  `telp_sekolah` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_sekolah` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_pengaturan`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_pengaturan`
--

LOCK TABLES `tb_pengaturan` WRITE;
/*!40000 ALTER TABLE `tb_pengaturan` DISABLE KEYS */;
INSERT INTO `tb_pengaturan` VALUES (1,'MI SULTAN FATTAH JEPARA','Jalan Kauman RT. 10 RW. 03 Sukosono','Musriah, S.Pd.I','-','2025/2026','logo_1768947623.png','082331838221','misultanfattah@gmail.com');
/*!40000 ALTER TABLE `tb_pengaturan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_siswa`
--

DROP TABLE IF EXISTS `tb_siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_siswa` (
  `id_siswa` int NOT NULL AUTO_INCREMENT,
  `nis` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `agama` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ayah` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ibu` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thn_masuk` int NOT NULL,
  `foto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','tidak aktif') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_siswa`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_siswa`
--

LOCK TABLES `tb_siswa` WRITE;
/*!40000 ALTER TABLE `tb_siswa` DISABLE KEYS */;
INSERT INTO `tb_siswa` VALUES (11,'1000','Sutan Kumala Bulan Pane','JAKARTA','2002-05-22','L','Islam','Gatau','Gatau','082280766074','sutanhost@gmail.com','Cikampek','5',2016,'anonim.png','sutan','2a78fe4d8f5f1e082c811b6a386738fb','sutan','aktif'),(12,'1236969','Garox Asmoking','Garut','2001-12-12','L','Islam','Garuk Garok','Ibu Garox','0895475170719','garox@garox.garox','Cikampek utara','5',2018,'PicsArt_04-14-03.51.05.jpg','garox123','867946d3c1074c0c38f6ae9217619bfb','garox123','aktif'),(13,'123456','Nur Huda','JEPARA','2021-01-25','L','Islam','sarkum','sarni','082331838221','murid@gmail.com','sukosono','9',2017,'DSC_0033a.jpg','huda','0075a4e7a2e71083262da135ecdbdd14','huda','aktif'),(14,'134215346','setan','sungai','2021-01-25','L','Konghucu','tuyul','pocong','082331838221','dewecorp@gmail.com','kuburan','4',2017,'2.jpg','setan','bfadd95f1afd52a903a7227ebdcf7c7c','setan','aktif'),(15,'2234235','tuyul','sungai','2021-01-01','P','Konghucu','gendruwo','suster ngesot','082331838221','dicky@gmail.com','kuburan','4',2017,'anonim.png','tuyul','b62e1067ccff9b737e4b3c5acad9df05','tuyul','aktif');
/*!40000 ALTER TABLE `tb_siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_soal_essay`
--

DROP TABLE IF EXISTS `tb_soal_essay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_soal_essay` (
  `id_essay` int NOT NULL AUTO_INCREMENT,
  `id_tq` int NOT NULL,
  `pertanyaan` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_buat` date NOT NULL,
  PRIMARY KEY (`id_essay`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_soal_essay`
--

LOCK TABLES `tb_soal_essay` WRITE;
/*!40000 ALTER TABLE `tb_soal_essay` DISABLE KEYS */;
INSERT INTO `tb_soal_essay` VALUES (9,6,'Halaman tersebut berfungsi sebagai ?','p1-2.png','2019-10-13'),(10,16,'sebutkan macam-macam setan!','','2021-01-25');
/*!40000 ALTER TABLE `tb_soal_essay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_soal_pilgan`
--

DROP TABLE IF EXISTS `tb_soal_pilgan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_soal_pilgan` (
  `id_pilgan` int NOT NULL AUTO_INCREMENT,
  `id_tq` int NOT NULL,
  `pertanyaan` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pil_a` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `pil_b` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `pil_c` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `pil_d` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `pil_e` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `kunci` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_buat` date NOT NULL,
  PRIMARY KEY (`id_pilgan`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_soal_pilgan`
--

LOCK TABLES `tb_soal_pilgan` WRITE;
/*!40000 ALTER TABLE `tb_soal_pilgan` DISABLE KEYS */;
INSERT INTO `tb_soal_pilgan` VALUES (18,6,'Apa fungsi dari <a href=\" \"> ?','','Untuk membuat huruf menjadi biru','merubah ukuran Font','Membuat Kolom formulir','mengatur Hyperlink pada halaman','melihat gambar','D','2019-10-13'),(20,6,'Fungsi CSS pada website ?','','Mengatur database pada website','Mengatur Desain pada website','Membuat website MVC','Sebagai Framework PHP','Membuat Domain pada Website','B','2019-10-13'),(21,10,'Apa itu SUTAN ?','','Server Up Time Administrator Network','Nama Adminintrator Website Ini','Slide Undo To Access Network','Server Under Tunnel Access Network','Suply Tag Name','B','2019-11-19'),(23,10,'Apa Fungsi :Hover pada CSS ?','','Untuk membuat Koneksi pada Website','Gatau saya','Gatau kamu','Gatau aku','Gatau dia','D','2019-11-19'),(26,10,'Kapan Terakhir kali anda membeli Batagor ?','','Hari ini','Kemarin','Kemarin Lusa','Seminggu yang lalu','Besok','E','2019-11-19'),(28,12,'Siapa Nama Saya ?','','Sutan','Gatau','Gtw','Mbohh','auahh','A','2020-01-14'),(29,12,'jhfklsdfjk','','as','sd','df',',m',',m','B','2020-01-14'),(30,13,'Contoh Soal','','Contoh Jawaban','Contoh Jawaban','Contoh Jawaban','Contoh Jawaban','Contoh Jawaban','B','2020-02-12'),(31,13,'Contoh Soal','','Contoh Jawaban','Contoh Jawaban','Contoh Jawaban','Contoh Jawaban','Contoh Jawaban','B','2020-02-12'),(32,15,'Siapa nama Saya','PicsArt_04-14-03.51.05.jpg','Sutan','Elang','Irfan','Fajar','Alma','A','2020-04-14'),(33,14,'Siapa nama Saya','PicsArt_04-14-03.51.05.jpg','Sutan','Elang','Irfan','Fajar','Alma','A','2020-04-14'),(34,14,'Apa itu Saya','','Aku','Kamu','Dia','Mereka','Kami','A','2020-04-14'),(35,16,'Apakah yang dimaksud dengan setan','','kecil','tidak terlihat','terlihat','hitam','-','D','2021-01-25'),(36,17,'Apa arti tahlil','','laaa ilaaha illallah','hajatan','tidak tahu','bingung','-','A','2021-01-25');
/*!40000 ALTER TABLE `tb_soal_pilgan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_topik_quiz`
--

DROP TABLE IF EXISTS `tb_topik_quiz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_topik_quiz` (
  `id_tq` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas` int NOT NULL,
  `id_mapel` int NOT NULL,
  `tgl_buat` date NOT NULL,
  `pembuat` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_soal` int NOT NULL,
  `info` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','tidak aktif') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_tq`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_topik_quiz`
--

LOCK TABLES `tb_topik_quiz` WRITE;
/*!40000 ALTER TABLE `tb_topik_quiz` DISABLE KEYS */;
INSERT INTO `tb_topik_quiz` VALUES (6,'Ulangan Web Desain',2,5,'2019-10-13','admin',1800,'Jawab Semua Soal dengan Benar dan Teliti !','aktif'),(8,'Latihan UN',5,0,'2019-11-19','admin',600,'Belajar yang Rajin ya, biar bisa jadi HackBae','aktif'),(9,'Latihan UN',4,0,'2019-11-19','admin',600,'Belajar yang Rajin ya, biar bisa jadi HackBae','aktif'),(10,'Latihan UN',4,5,'2019-11-19','admin',1200,'Belajar yang Rajin','aktif'),(12,'Ulangan Akhir Semester',5,5,'2020-01-14','admin',300,'Teliti Ya !!','aktif'),(13,'Ulangan Kenaikan Kelas',5,5,'2020-02-12','admin',5400,'Kerjakan dengan Teliti !','aktif'),(14,'Ulangan Semester',5,5,'2020-04-14','admin',7200,'Jawab dengan Teliti','aktif'),(15,'Ulangan Semester',4,5,'2020-04-14','admin',7200,'Jawab dengan Teliti','aktif'),(16,'TUGAS DIRUMAH',4,6,'2021-01-25','admin',7200,'kerjakan dengan sungguh-sungguh','aktif'),(17,'Ulangan Harian',9,5,'2021-01-25','2',7200,'jujur','aktif'),(18,'Ulangan Harian',9,5,'2021-01-25','3',7200,'kerjakan','tidak aktif');
/*!40000 ALTER TABLE `tb_topik_quiz` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-21  6:15:47
