/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50720
Source Host           : 127.0.0.1:3306
Source Database       : raspisanie

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2021-07-14 09:30:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for companies
-- ----------------------------
DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` char(255) DEFAULT NULL,
  `contract_number` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of companies
-- ----------------------------
INSERT INTO `companies` VALUES ('1', 'Ингострах', '456');
INSERT INTO `companies` VALUES ('2', 'СОГАЗ АО', '№123-123-111');
INSERT INTO `companies` VALUES ('3', 'АСТРА-МЕТАЛЛ', '');
INSERT INTO `companies` VALUES ('4', 'Диагностика +', '');
INSERT INTO `companies` VALUES ('5', 'ЦГБ №3 МАУЗ', '');
INSERT INTO `companies` VALUES ('6', 'ВТБ-Страхование СК ООО', '');

-- ----------------------------
-- Table structure for doctors
-- ----------------------------
DROP TABLE IF EXISTS `doctors`;
CREATE TABLE `doctors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `surname` char(255) DEFAULT NULL,
  `patronymic` char(255) DEFAULT NULL,
  `doctor_specialty_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of doctors
-- ----------------------------
INSERT INTO `doctors` VALUES ('1', 'Иван', 'Иванов', 'Иванович', '2');
INSERT INTO `doctors` VALUES ('2', 'Петр', 'Иванов', 'Сергеевич', '2');
INSERT INTO `doctors` VALUES ('3', 'Виктор', 'Кузнецов', 'Александрович', '3');
INSERT INTO `doctors` VALUES ('4', 'Алла', 'Сафина', 'Алексеевна', '4');
INSERT INTO `doctors` VALUES ('5', 'Алексей', 'Сидоров', 'Сидорович', '6');
INSERT INTO `doctors` VALUES ('6', 'Алефтина', 'Степаненко', 'Олеговна', '7');
INSERT INTO `doctors` VALUES ('7', 'Борисович', 'Олег', 'Кац', '8');
INSERT INTO `doctors` VALUES ('8', 'Владлен', 'Сергеенко', 'Борисович', '24');
INSERT INTO `doctors` VALUES ('9', 'Степан', 'Поляков', 'Степанович', '23');
INSERT INTO `doctors` VALUES ('10', 'Олег', 'Максимов', 'Вячеславович', '20');

-- ----------------------------
-- Table structure for doctors_department
-- ----------------------------
DROP TABLE IF EXISTS `doctors_department`;
CREATE TABLE `doctors_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of doctors_department
-- ----------------------------
INSERT INTO `doctors_department` VALUES ('4', 'Хирургическое отделение');
INSERT INTO `doctors_department` VALUES ('5', 'Терапевтическое отделение');
INSERT INTO `doctors_department` VALUES ('6', 'Психиатрическое отделение');
INSERT INTO `doctors_department` VALUES ('7', 'Гастроэнтерологическое отделение');
INSERT INTO `doctors_department` VALUES ('11', 'Психоневрологическое отделение');
INSERT INTO `doctors_department` VALUES ('13', 'ЛДО (Лабораторно-диагностический отдел)');
INSERT INTO `doctors_department` VALUES ('14', 'ОФУД (Отделение функциональной и ультразвуковой диагностики)');
INSERT INTO `doctors_department` VALUES ('15', 'Отделение офтальмологической хирургии');
INSERT INTO `doctors_department` VALUES ('16', 'Приемное отделение');
INSERT INTO `doctors_department` VALUES ('17', 'Отделение общей хирургии');
INSERT INTO `doctors_department` VALUES ('18', 'Поликлиника');
INSERT INTO `doctors_department` VALUES ('19', 'Нейрохирургия');

-- ----------------------------
-- Table structure for doctors_service
-- ----------------------------
DROP TABLE IF EXISTS `doctors_service`;
CREATE TABLE `doctors_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` char(255) DEFAULT NULL,
  `service_code` char(255) DEFAULT NULL,
  `service_cost` decimal(10,2) NOT NULL,
  `service_date_begin` date DEFAULT NULL,
  `service_date_end` date DEFAULT NULL,
  `doctor_specialty_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of doctors_service
-- ----------------------------
INSERT INTO `doctors_service` VALUES ('1', 'Первичный осмотр', '1', '500.00', '2021-07-01', '2121-07-01', '2');
INSERT INTO `doctors_service` VALUES ('2', 'Повторный осмотр', '2', '250.00', '2021-07-01', '2121-07-01', '2');
INSERT INTO `doctors_service` VALUES ('3', 'Удаление грыжи', '3', '8000.00', '2021-07-01', '2121-07-01', '3');
INSERT INTO `doctors_service` VALUES ('4', 'Удаление инородного тела (1 категория)', '4', '4000.00', '2021-07-01', '2121-07-01', '3');
INSERT INTO `doctors_service` VALUES ('5', 'Первичная консультация', '5', '1000.00', '2021-07-01', '2121-07-01', '4');
INSERT INTO `doctors_service` VALUES ('6', 'Повторная консультация', '6', '800.00', '2021-07-01', '2121-07-01', '4');
INSERT INTO `doctors_service` VALUES ('7', 'Вправление плеча', '', '500.00', '2021-07-01', '2121-07-01', '6');
INSERT INTO `doctors_service` VALUES ('8', 'Фиксация сустава (с учетом стоимости материалов)', '', '500.00', '2021-07-01', '2121-07-01', '6');
INSERT INTO `doctors_service` VALUES ('9', 'Первичный осмотр', '', '500.00', '2021-07-01', '2121-07-01', '7');
INSERT INTO `doctors_service` VALUES ('10', 'Повторный осмотр', '', '450.00', '2021-07-01', '2121-07-01', '7');
INSERT INTO `doctors_service` VALUES ('11', 'Первичный прием врача первой категории', '', '800.00', '2021-07-01', '2121-07-01', '23');
INSERT INTO `doctors_service` VALUES ('12', 'Первичный прием врача высшей категории', '', '1000.00', '2021-07-01', '2121-07-01', '23');
INSERT INTO `doctors_service` VALUES ('13', 'Операция на внешней оболочке мозга', 'A1654-33', '10000.00', '2021-07-01', '2121-07-01', '24');

-- ----------------------------
-- Table structure for doctors_specialty
-- ----------------------------
DROP TABLE IF EXISTS `doctors_specialty`;
CREATE TABLE `doctors_specialty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `specialty_name` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of doctors_specialty
-- ----------------------------
INSERT INTO `doctors_specialty` VALUES ('2', 'Терапевт');
INSERT INTO `doctors_specialty` VALUES ('3', 'Хирург');
INSERT INTO `doctors_specialty` VALUES ('4', 'Гастроэнтеролог');
INSERT INTO `doctors_specialty` VALUES ('6', 'Травмотолог');
INSERT INTO `doctors_specialty` VALUES ('7', 'Уролог');
INSERT INTO `doctors_specialty` VALUES ('8', 'Врач-физиотерапевт');
INSERT INTO `doctors_specialty` VALUES ('9', 'Гнойный хирург');
INSERT INTO `doctors_specialty` VALUES ('10', 'Стоматолог');
INSERT INTO `doctors_specialty` VALUES ('11', 'Магнитно-резонансная томография (МРТ)');
INSERT INTO `doctors_specialty` VALUES ('12', 'Компьютерная Томография (КТ)');
INSERT INTO `doctors_specialty` VALUES ('13', 'Эндоскопист');
INSERT INTO `doctors_specialty` VALUES ('14', 'Врач лабораторной диагностики');
INSERT INTO `doctors_specialty` VALUES ('15', 'Проктолог');
INSERT INTO `doctors_specialty` VALUES ('16', 'Врач ультразвуковой диагностики');
INSERT INTO `doctors_specialty` VALUES ('19', 'Врач функциональной диагностики');
INSERT INTO `doctors_specialty` VALUES ('20', 'Врач-психотерапевт');
INSERT INTO `doctors_specialty` VALUES ('21', 'Врач-офтальмолог');
INSERT INTO `doctors_specialty` VALUES ('22', 'Врач-эндокринолог');
INSERT INTO `doctors_specialty` VALUES ('23', 'Врач-невролог');
INSERT INTO `doctors_specialty` VALUES ('24', 'Врач-нейрохирург');

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` char(255) DEFAULT NULL,
  `schedule_page_permission` char(255) DEFAULT NULL,
  `doctors_page_permission` char(255) DEFAULT NULL,
  `services_page_permission` char(255) NOT NULL,
  `patients_page_permission` char(255) DEFAULT NULL,
  `administration_page_permission` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of groups
-- ----------------------------
INSERT INTO `groups` VALUES ('4', 'Администраторы', 'full_access', 'full_access', 'full_access', 'full_access', 'full_access');
INSERT INTO `groups` VALUES ('9', 'Пользователи', 'only_view', 'only_view', 'only_view', 'only_view', 'no_access');
INSERT INTO `groups` VALUES ('10', 'Операторы', 'only_view', 'only_view', 'only_view', 'only_view', 'only_view');
INSERT INTO `groups` VALUES ('14', 'Нет доступа', 'no_access', 'only_view', 'no_access', 'full_access', 'no_access');

-- ----------------------------
-- Table structure for nurses
-- ----------------------------
DROP TABLE IF EXISTS `nurses`;
CREATE TABLE `nurses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `surname` char(255) DEFAULT NULL,
  `patronymic` char(255) DEFAULT NULL,
  `doctors_department_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nurses
-- ----------------------------
INSERT INTO `nurses` VALUES ('1', 'Светлана', 'Иванова', 'Владимировна', '19');
INSERT INTO `nurses` VALUES ('2', 'Ольга', 'Петрова', 'Петровна', '17');
INSERT INTO `nurses` VALUES ('3', 'Надежда', 'Сидоренко', 'Ибрагимовна', '15');
INSERT INTO `nurses` VALUES ('4', 'Наталья', 'Шувалова', 'Сергеевна', '18');

-- ----------------------------
-- Table structure for patients
-- ----------------------------
DROP TABLE IF EXISTS `patients`;
CREATE TABLE `patients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `surname` char(255) DEFAULT NULL,
  `patronymic` char(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `policy_number` char(255) NOT NULL,
  `policy_date_begin` date NOT NULL,
  `policy_date_end` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of patients
-- ----------------------------
INSERT INTO `patients` VALUES ('1', 'Иван', 'Иванов', 'Иванович', '2004-06-01', '1', '1234567890', '2021-06-01', '2032-06-16');
INSERT INTO `patients` VALUES ('2', 'Петр', 'Петров', 'Симановский', '1965-06-03', '2', '2224567890', '2021-06-02', '2024-06-05');
INSERT INTO `patients` VALUES ('3', 'Артем', 'Мерзляков', 'Вадимович', '2021-06-01', '3', '4444567890', '2021-06-01', '2030-06-27');

-- ----------------------------
-- Table structure for referrals
-- ----------------------------
DROP TABLE IF EXISTS `referrals`;
CREATE TABLE `referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) DEFAULT NULL,
  `medical_card_number` char(255) NOT NULL,
  `referral_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of referrals
-- ----------------------------

-- ----------------------------
-- Table structure for referral_elements
-- ----------------------------
DROP TABLE IF EXISTS `referral_elements`;
CREATE TABLE `referral_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referral_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `nurse_id` int(11) DEFAULT NULL,
  `doctors_department_id` int(11) DEFAULT NULL,
  `diagnosis_mkb` char(255) DEFAULT NULL,
  `warranty_letter_number` char(255) DEFAULT NULL,
  `referral_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of referral_elements
-- ----------------------------

-- ----------------------------
-- Table structure for schedules
-- ----------------------------
DROP TABLE IF EXISTS `schedules`;
CREATE TABLE `schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doctor_id` int(11) DEFAULT NULL,
  `doctor_specialty_id` int(11) DEFAULT NULL,
  `doctor_room` char(10) DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of schedules
-- ----------------------------
INSERT INTO `schedules` VALUES ('1', '1', '2', '1', '2021-07-05');

-- ----------------------------
-- Table structure for schedule_elements
-- ----------------------------
DROP TABLE IF EXISTS `schedule_elements`;
CREATE TABLE `schedule_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) DEFAULT NULL,
  `receipt_minutes` int(2) DEFAULT NULL,
  `receipt_hours` int(2) DEFAULT NULL,
  `time_in_minutes` int(4) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `financing` int(2) DEFAULT NULL,
  `execution_status` int(2) DEFAULT NULL,
  `financing_confirmation` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of schedule_elements
-- ----------------------------
INSERT INTO `schedule_elements` VALUES ('1', '1', '2', '2', '122', '1', '1', '1', '1', '2');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` char(255) DEFAULT NULL,
  `password` char(255) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `token` char(255) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `surname` char(255) DEFAULT NULL,
  `patronymic` char(255) DEFAULT NULL,
  `position` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('4', 'admin', 'd9b1d7db4cd6e70935368a1efb10e377', '4', '', 'Иван', 'Иванов', 'Иванович', 'Администратор системы');
INSERT INTO `users` VALUES ('18', 'user', 'd9b1d7db4cd6e70935368a1efb10e377', '10', '', 'Посетитель', 'Посетитель', 'Посетитель', 'Посетитель');
SET FOREIGN_KEY_CHECKS=1;
