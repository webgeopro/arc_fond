-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 04 2011 г., 16:47
-- Версия сервера: 5.5.11
-- Версия PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `real-com2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `vac_categories`
--

DROP TABLE IF EXISTS `vac_categories`;
CREATE TABLE IF NOT EXISTS `vac_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT '0',
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=158 ;

--
-- Дамп данных таблицы `vac_categories`
--

INSERT INTO `vac_categories` (`id`, `parent_id`, `name`, `description`) VALUES
(1, 0, 'Название1', ''),
(6, 1, 'Еще одна категория', ''),
(9, 0, 'И еще одна категория', ''),
(10, 0, 'IT компьютеры интернет', ''),
(11, 0, 'Финансы экономика аудит банк', ''),
(12, 0, 'Бухгалтерия кассовый учет', ''),
(13, 0, 'Управленческий персонал администраторы', ''),
(14, 0, 'Секретариат офисный персонал АХО', ''),
(15, 0, 'Продажи торговля дистрибуция', ''),
(16, 0, 'Менеджеры по персоналу, HR-служба', ''),
(17, 0, 'Логистика, снабжение, перевозки, склад', ''),
(18, 0, 'Производство и строительство, рабочие специальности', ''),
(19, 0, 'Юриспруденция', ''),
(20, 0, 'Инженеры, проектировщики', ''),
(21, 0, 'Редакторы, журналисты, переводчики', ''),
(22, 0, 'Маркетинг, PR-служба', ''),
(23, 0, 'Повара, официанты, бармены, сомелье', ''),
(24, 0, 'Учебный, научный отдел', ''),
(25, 0, 'Охрана, служба безопасности, милиция', ''),
(26, 0, 'Дизайн, творческие профессии', ''),
(27, 0, 'Фармация, медицинский персонал', ''),
(28, 0, 'Услуги для населения, персонал для дома, спорт', ''),
(29, 0, 'Прочее, без опыта работы, подработка', ''),
(30, 10, 'программисты', ''),
(31, 10, 'системные администраторы', ''),
(32, 10, 'ит-специалисты', ''),
(33, 10, 'верстальщики', ''),
(34, 10, 'администраторы сайтов', ''),
(35, 11, 'экономисты', ''),
(36, 11, 'финансовые менеджеры', ''),
(37, 11, 'аудиторы', ''),
(38, 11, 'ревизоры', ''),
(39, 11, 'специалисты по кредитованию', ''),
(40, 11, 'банковские служащие', ''),
(41, 11, 'специалисты по страхованию', ''),
(42, 12, 'бухгалтеры', ''),
(43, 12, 'главные бухгалтеры', ''),
(44, 12, 'кассиры', ''),
(45, 13, 'администраторы', ''),
(46, 13, 'региональные представители', ''),
(47, 13, 'директора', ''),
(48, 13, 'супервайзеры', ''),
(49, 13, 'топ-менеджеры', ''),
(50, 13, 'менеджеры проектов', ''),
(51, 13, 'начальники отделов', ''),
(52, 14, 'офис-менеджеры', ''),
(53, 14, 'секретари', ''),
(54, 14, 'операторы', ''),
(55, 14, 'помощники руководителей', ''),
(56, 14, 'операторы 1С', ''),
(57, 14, 'специалисты АХО', ''),
(58, 15, 'менеджеры по продажам', ''),
(59, 15, 'торговые представители', ''),
(60, 15, 'продавцы', ''),
(61, 15, 'мерчендайзеры', ''),
(62, 15, 'менеджеры по работе с клиентами', ''),
(63, 15, 'риелторы', ''),
(64, 15, 'менеджеры по туризму', ''),
(65, 15, 'оценщики', ''),
(66, 15, 'страховые агенты', ''),
(67, 15, 'продакт менеджеры', ''),
(68, 16, 'менеджеры по персоналу', ''),
(69, 16, 'менеджеры по подбору персонала', ''),
(70, 17, 'водители', ''),
(71, 17, 'снабженцы', ''),
(72, 17, 'кладовщики', ''),
(73, 17, 'логисты', ''),
(74, 17, 'экспедиторы', ''),
(75, 17, 'курьеры', ''),
(76, 17, 'товароведы', ''),
(77, 17, 'диспетчеры', ''),
(78, 17, 'комплектовщики', ''),
(79, 17, 'менеджеры по перевозкам', ''),
(80, 17, 'остальные', ''),
(81, 18, 'механики', ''),
(82, 18, 'прорабы', ''),
(83, 18, 'электрики', ''),
(84, 18, 'монтажники', ''),
(85, 18, 'строители', ''),
(86, 18, 'слесари', ''),
(87, 18, 'сварщики', ''),
(88, 18, 'отделочники', ''),
(89, 18, 'сантехники', ''),
(90, 18, 'столяры', ''),
(92, 18, 'остальные', ''),
(93, 19, 'юристы', ''),
(94, 19, 'коллекторы', ''),
(95, 19, 'судебные приставы', ''),
(96, 20, 'инженеры', ''),
(97, 20, 'технологи', ''),
(98, 20, 'проектировщики', ''),
(99, 20, 'конструкторы', ''),
(100, 20, 'энергетики', ''),
(101, 20, 'сметчики', ''),
(102, 20, 'инженеры-электрики', ''),
(103, 20, 'инженеры по охране труда', ''),
(104, 20, 'инженеры по качеству', ''),
(105, 20, 'геодезисты', ''),
(106, 20, 'остальные', ''),
(107, 21, 'журналисты', ''),
(108, 21, 'редакторы', ''),
(109, 21, 'копирайтеры', ''),
(110, 21, 'писатели', ''),
(111, 21, 'прессатташе', ''),
(112, 22, 'маркетологи', ''),
(113, 22, 'pr-менеджеры', ''),
(114, 22, 'менеджеры по рекламе', ''),
(115, 22, 'бренд-менеджеры', ''),
(116, 23, 'повара', ''),
(117, 23, 'официанты', ''),
(118, 23, 'бармены', ''),
(119, 23, 'кондитеры', ''),
(120, 24, 'преподаватели', ''),
(121, 24, 'химики', ''),
(122, 24, 'бизнес-тренеры', ''),
(123, 24, 'лаборанты', ''),
(124, 24, 'тренинг менеджеры', ''),
(125, 25, 'охранники', ''),
(126, 25, 'сторожа', ''),
(127, 25, 'сотрудники службы безопасности', ''),
(128, 25, 'телохранители', ''),
(129, 25, 'милиционеры', ''),
(130, 26, 'дизайнеры', ''),
(131, 26, 'архитекторы', ''),
(132, 26, 'фотографы', ''),
(133, 26, 'флористы', ''),
(134, 26, 'иллюстраторы', ''),
(135, 26, 'модельеры', ''),
(136, 26, 'певцы', ''),
(137, 27, 'медицинские представители', ''),
(138, 27, 'врачи', ''),
(139, 27, 'медработники', ''),
(140, 27, 'массажисты', ''),
(141, 27, 'провизоры', ''),
(142, 27, 'фельдшеры', ''),
(143, 28, 'маникюристы', ''),
(144, 28, 'няни', ''),
(145, 28, 'спортивные тренеры', ''),
(146, 28, 'домработники', ''),
(147, 28, 'косметологи', ''),
(148, 28, 'парикмахеры', ''),
(149, 28, 'воспитатели', ''),
(150, 28, 'портные', ''),
(151, 29, 'промоутеры', ''),
(152, 29, 'наборщики текстов', ''),
(153, 29, 'грузчики', ''),
(154, 29, 'разнорабочие', ''),
(155, 29, 'уборщики', ''),
(156, 29, 'расклейщики объявлений', ''),
(157, 29, 'другие', '');
