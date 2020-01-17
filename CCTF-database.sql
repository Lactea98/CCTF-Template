-- MySQL dump 10.13  Distrib 5.7.28, for Linux (x86_64)
--
-- Host: localhost    Database: CCTF
-- ------------------------------------------------------
-- Server version	5.7.28-0ubuntu0.18.04.4

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `CCTF`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `CCTF` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `CCTF`;

--
-- Table structure for table `announcement`
--

DROP TABLE IF EXISTS `announcement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcement` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `category` text NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcement`
--

LOCK TABLES `announcement` WRITE;
/*!40000 ALTER TABLE `announcement` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `category_name` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES ('Web'),('system'),('misc'),('reversing'),('crypto'),('code');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `challenge`
--

DROP TABLE IF EXISTS `challenge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `challenge` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `title` text,
  `contents` text,
  `flag` text,
  `points` int(11) DEFAULT NULL,
  `bonus` int(11) NOT NULL,
  `decrease` int(11) NOT NULL,
  `attach` text,
  `visible` int(11) DEFAULT NULL,
  `category` text,
  `solved` int(11) NOT NULL,
  `first_solver` varchar(30) NOT NULL,
  `level` varchar(20) DEFAULT NULL,
  `solver_list` text NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `challenge`
--

LOCK TABLES `challenge` WRITE;
/*!40000 ALTER TABLE `challenge` DISABLE KEYS */;
INSERT INTO `challenge` VALUES (1,'위험한 계산기','친구가 sh로 간단한 계산기를 만들었다.\n친구가 외부에서 접속을 할 수 있게 포트를 열어놓았다.\n\n나에게 아래 명령어로 접속하면 계산기를 쓸 수 있다고 한다.\n\nhere: <kbd> nc 18.182.194.54 10000 </kbd>\n\n\nHint1: Basic Command Injection\nHint2: Read flag in /home/universe/flag.txt\n','CASPER{B@ck_quotes_5o_UseFuL!!}',100,10,10,NULL,1,'system',0,'','easy',''),(2,'전자적인 코드 북','Source code Download Link: \nhttps://drive.google.com/file/d/1aOTerQxU-b7EhqjZAeGWwpTWefWHgxUJ/view?usp=sharing\n\n<kbd> nc 18.182.194.54 7202 </kbd>','CASPER{}',100,10,10,NULL,1,'crypto',0,'','normal',''),(3,'Use network Tab','You are getting something value!!\n<kbd> http://18.182.194.54:10011/network </kbd>','CASPER{iD0ntL1keNetwork}',100,10,10,NULL,1,'web',0,'','easy',''),(4,'XML parser','이 서버는 xml 을 분석하여 사용자에게 결과를 출력합니다.\nLink: <kbd> http://18.182.194.54:10013 </kbd>\n\nHint: Read file /var/www/xxe/index.php','CASPER{iConqueredServer!!!}',100,10,10,NULL,1,'web',0,'','easy',''),(5,'Guess My Number :D','내가 생각한 숫자를 맞춰 봐 :D\n\n<kbd> http://18.182.194.54:10011/guessme </kbd>','CASPER{0mg..HowDidYouKnow...}',100,10,10,NULL,1,'web',0,'','easy',''),(6,'얼에스에이','밥은 엘리스의 책상위에 이상한 숫자들을 발견했다.\n\n<img src=\"./images/prob1.png\">\n\n밥은 이를 보고 다음과 같이 생각했다.\n\"첫번째 문장은 암호문일 거야. 나머지는 암호 및 복호화 하기 위한 키 이겠지. \"\n\n<kbd>67 65 83 80 69 82 123 82 83 65 95 115 116 105 49 49 95 83 64 70 101 46 125</kbd>\n<kbd>(e, n) = (7, 33)</kbd>\n<kbd>(d, n) = (3, 33)</kbd>','CASPER{RSA_sti11_S@Fe.}',100,10,10,NULL,1,'crypto',0,'','easy',''),(7,'Request Agent','Request Agent는 사용자가 입력한 URL에 접속하여 상태를 출력합니다.\n\n\n서버는 사용자가 입력한 URL로 접속 한 후, http status code를 반환합니다.\n해커의 공격을 방어하기 위한 몇가지의 보안 장치를 마련 했습니다. ^^\n\n<kbd>http://18.182.194.54:10012</kbd>\n\nHint: 서버는 쿠키안에 flag 값을 가지고 입력한 URL로 접속합니다.','CASPER{Server_is_a_client,too}',100,10,10,NULL,1,'web',0,'','normal',''),(8,'Preview Web Site Agent v 0.1','이 사이트는 Request Agent 와는 뭔가 \"다릅니다\" !!\n\n중국인 칭따오는 Request Agent 사이트를 보고 \"역시 중국인은 Ctrl+c, Ctrl+v가 국룰이지, 홀홀\" 라면서 비슷한 사이트를 만들었다.\n\n하지만 머리가 나빠 제대로 구현도 못하고 이 서버를 그대로 방치해 두었다.\n\n<kbd> http://18.182.194.54:10014 </kbd>\n\n\n','CASPER{H0w_did_y0u_AcCess_L0cal_f1le!?!}',100,10,10,NULL,1,'web',0,'','normal',''),(9,'Reverse My Code','아래 c 코드를 분석하여 Output 값의 이전 값을 추측해보세요\n\nOutput: <kbd> ]_+P[*{_J(Q0M*(V(J+3*?} </kbd>\n\nView code: <a href=\"./challenge/reverseMe.c\" target=\"_blank\">Click</a>','CASPER{Are_y0u_ReVerS3R?}',100,100,10,NULL,1,'code',0,'','easy','');
/*!40000 ALTER TABLE `challenge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `login` int(1) NOT NULL,
  `registration` int(1) NOT NULL,
  `begin_timer` datetime DEFAULT NULL,
  `end_timer` datetime DEFAULT NULL,
  `game_start` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,0,NULL,NULL,0);
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `category` text NOT NULL,
  `nickname` varchar(14) NOT NULL,
  `submit` text NOT NULL,
  `title` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `userid` varchar(100) NOT NULL,
  `userpw` text NOT NULL,
  `nickname` text NOT NULL,
  `points` int(11) NOT NULL,
  `admin` int(11) NOT NULL,
  `visible` int(11) NOT NULL,
  `comment` text NOT NULL,
  `profile` text,
  `last_time` datetime DEFAULT NULL,
  `solved_challenge` text,
  `history` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('admin','$2y$10$nlhoFGGMhEVA/RWIWcRdGOS.K5kvRyHdb7drZiUsN91.5IimcQc7C','admin',0,1,0,'MIC test','/uploads/userImage/uajQ4tkiKT/cat.jpg',NULL,'','');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-17  9:32:07
