-- phpMyAdmin SQL Dump
-- version 2.11.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 06, 2008 at 07:43 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bmestudi_templatesystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `errorlog`
--

CREATE TABLE IF NOT EXISTS `errorlog` (
  `id` bigint(20) NOT NULL auto_increment,
  `content` text NOT NULL,
  `date` text NOT NULL,
  `location` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=237 ;

--
-- Dumping data for table `errorlog`
--

INSERT INTO `errorlog` (`id`, `content`, `date`, `location`) VALUES
(1, 'Error: Template asdfdsfdsf not found.', '10/02/08 19:08:09', '/workstation/templatesystem/admin/index.php'),
(2, 'Error: Language Not Found', '10/02/08 19:40:09', '/workstation/templatesystem/admin/templates.php'),
(3, 'Error: Language Not Found', '10/02/08 19:40:09', '/workstation/templatesystem/admin/templates.php'),
(4, 'Error: Language Not Found', '10/02/08 19:40:27', '/workstation/templatesystem/admin/templates.php'),
(5, 'Error: Language Not Found', '10/02/08 19:40:27', '/workstation/templatesystem/admin/templates.php'),
(6, 'Error: Language Not Found', '10/02/08 19:40:38', '/workstation/templatesystem/admin/templates.php'),
(7, 'Error: Language Not Found', '10/02/08 19:40:38', '/workstation/templatesystem/admin/templates.php'),
(8, 'Error: Language Not Found', '10/02/08 19:40:38', '/workstation/templatesystem/admin/templates.php'),
(9, 'Error: Language Not Found', '10/02/08 19:40:38', '/workstation/templatesystem/admin/templates.php'),
(10, 'Error: Template navigation not found.', '10/02/08 20:16:07', '/workstation/templatesystem/admin/templates.php'),
(11, 'Error: Template navigation not found.', '10/02/08 20:16:09', '/workstation/templatesystem/admin/templates.php'),
(12, 'Error: Template menu not found.', '10/02/08 20:17:45', '/workstation/templatesystem/admin/templates.php'),
(13, 'Error: Template menu not found.', '10/02/08 20:17:51', '/workstation/templatesystem/admin/templates.php'),
(14, 'Error: Template menu not found.', '10/02/08 20:17:54', '/workstation/templatesystem/admin/templates.php'),
(15, 'Error: Template menu not found.', '10/02/08 20:17:57', '/workstation/templatesystem/admin/templates.php'),
(16, 'Error: Template menu not found.', '10/02/08 20:18:00', '/workstation/templatesystem/admin/templates.php'),
(17, 'Error: Template blasdfsd not found.', '10/02/08 20:18:50', '/workstation/templatesystem/admin/index.php'),
(18, 'Error: Template menu not found.', '10/02/08 20:20:12', '/workstation/templatesystem/admin/templates.php'),
(19, 'Error: Template menuasfsdf not found.', '10/02/08 20:20:37', '/workstation/templatesystem/admin/templates.php'),
(20, 'Error: Template menuasfsdf not found.', '10/02/08 20:20:42', '/workstation/templatesystem/admin/templates.php'),
(21, 'Error: Template templates not found.', '10/03/08 16:24:20', '/workstation/templatesystem/admin/templates.php'),
(22, 'Error: Template templates not found.', '10/03/08 16:24:21', '/workstation/templatesystem/admin/templates.php'),
(23, 'Error: Template templates not found.', '10/03/08 16:24:22', '/workstation/templatesystem/admin/templates.php'),
(24, 'Error: Template templates not found.', '10/03/08 16:24:22', '/workstation/templatesystem/admin/templates.php'),
(25, 'Error: Template templates not found.', '10/03/08 16:28:42', '/workstation/templatesystem/admin/templates.php'),
(26, 'Error: Template index not found.', '10/03/08 16:29:15', '/workstation/templatesystem/admin/index.php'),
(27, 'Error: Template templates not found.', '10/03/08 16:31:12', '/workstation/templatesystem/admin/templates.php'),
(28, 'Error: Template templates not found.', '10/03/08 16:31:40', '/workstation/templatesystem/admin/templates.php'),
(29, 'Error: Template templates not found.', '10/03/08 16:32:51', '/workstation/templatesystem/admin/templates.php'),
(30, 'Error: Template templates not found.', '10/03/08 16:33:15', '/workstation/templatesystem/admin/templates.php'),
(31, 'Error: Template templates not found.', '10/03/08 16:33:15', '/workstation/templatesystem/admin/templates.php'),
(32, 'Error: Template templates not found.', '10/03/08 16:33:15', '/workstation/templatesystem/admin/templates.php'),
(33, 'Error: Template templates not found.', '10/03/08 16:33:16', '/workstation/templatesystem/admin/templates.php'),
(34, 'Error: Template templates not found.', '10/03/08 16:33:16', '/workstation/templatesystem/admin/templates.php'),
(35, 'Error: Template templates not found.', '10/03/08 16:33:16', '/workstation/templatesystem/admin/templates.php'),
(36, 'Error: Template templates not found.', '10/03/08 16:33:31', '/workstation/templatesystem/admin/templates.php'),
(37, 'Error: Template templates not found.', '10/03/08 16:33:31', '/workstation/templatesystem/admin/templates.php'),
(38, 'Error: Template templates not found.', '10/03/08 16:33:32', '/workstation/templatesystem/admin/templates.php'),
(39, 'Error: Template templates not found.', '10/03/08 16:33:32', '/workstation/templatesystem/admin/templates.php'),
(40, 'Error: Template index not found.', '10/03/08 16:33:34', '/workstation/templatesystem/admin/index.php'),
(41, 'Error: Template templates not found.', '10/03/08 16:34:11', '/workstation/templatesystem/admin/templates.php'),
(42, 'Error: Template templates not found.', '10/03/08 16:34:12', '/workstation/templatesystem/admin/templates.php'),
(43, 'Error: Template templates not found.', '10/03/08 16:34:51', '/workstation/templatesystem/admin/templates.php'),
(44, 'Error: Template templates not found.', '10/03/08 16:34:51', '/workstation/templatesystem/admin/templates.php'),
(45, 'Error: Template index not found.', '10/03/08 16:35:58', '/workstation/templatesystem/admin/index.php'),
(46, 'Error: Template index not found.', '10/03/08 16:35:58', '/workstation/templatesystem/admin/index.php'),
(47, 'Error: Template index not found.', '10/03/08 16:35:59', '/workstation/templatesystem/admin/index.php'),
(48, 'Error: Template templates not found.', '10/03/08 16:36:49', '/workstation/templatesystem/admin/templates.php'),
(49, 'Error: Template templates not found.', '10/03/08 16:38:40', '/workstation/templatesystem/admin/templates.php'),
(50, 'Error: Template templates not found.', '10/03/08 16:41:49', '/workstation/templatesystem/admin/templates.php'),
(51, 'Error: Template admin_templates not found.', '10/03/08 16:41:55', '/workstation/templatesystem/admin/templates.php'),
(52, 'Error: Template admin_templates not found.', '10/03/08 16:42:26', '/workstation/templatesystem/admin/templates.php'),
(53, 'Error: Template admin_templates not found.', '10/03/08 16:56:01', '/workstation/templatesystem/admin/templates.php'),
(54, 'Error: Template admin_templates not found.', '10/03/08 16:56:02', '/workstation/templatesystem/admin/templates.php'),
(55, 'Error: Template admin_templates not found.', '10/03/08 16:56:17', '/workstation/templatesystem/admin/templates.php'),
(56, 'Error: Template admin_templates not found.', '10/03/08 16:56:47', '/workstation/templatesystem/admin/templates.php'),
(57, 'Error: Template admin_templates not found.', '10/03/08 16:57:51', '/workstation/templatesystem/admin/templates.php'),
(58, 'Error: Template admin_templates not found.', '10/03/08 16:57:52', '/workstation/templatesystem/admin/templates.php'),
(59, 'Error: Template admin_templates not found.', '10/03/08 16:59:43', '/workstation/templatesystem/admin/templates.php'),
(60, 'Error: Template admin_templates not found.', '10/03/08 17:01:36', '/workstation/templatesystem/admin/templates.php'),
(61, 'Error: Template login not found.', '10/03/08 17:01:38', '/workstation/templatesystem/admin/index.php'),
(62, 'Error: Template index not found.', '10/03/08 17:01:58', '/workstation/templatesystem/admin/index.php'),
(63, 'Error: Template admin_index not found.', '10/03/08 17:02:24', '/workstation/templatesystem/admin/index.php'),
(64, 'Error: Template admin_index not found.', '10/03/08 17:02:25', '/workstation/templatesystem/admin/index.php'),
(65, 'Error: Template admin_index not found.', '10/03/08 17:03:15', '/workstation/templatesystem/admin/index.php'),
(66, 'Error: Template login not found.', '10/03/08 17:06:15', '/workstation/templatesystem/admin/index.php'),
(67, 'Error: Template login not found.', '10/03/08 17:06:16', '/workstation/templatesystem/admin/index.php'),
(68, 'Error: Template login not found.', '10/03/08 17:13:03', '/workstation/templatesystem/admin/index.php'),
(69, 'Error: Template login not found.', '10/03/08 17:13:04', '/workstation/templatesystem/admin/index.php'),
(70, 'Error: Template login not found.', '10/03/08 17:13:05', '/workstation/templatesystem/admin/index.php'),
(71, 'Error: Template login not found.', '10/03/08 17:13:05', '/workstation/templatesystem/admin/index.php'),
(72, 'Error: Template login not found.', '10/03/08 17:13:06', '/workstation/templatesystem/admin/index.php'),
(73, 'Error: Template login not found.', '10/03/08 17:13:06', '/workstation/templatesystem/admin/index.php'),
(74, 'Error: Template login not found.', '10/03/08 17:13:06', '/workstation/templatesystem/admin/index.php'),
(75, 'Error: Template login not found.', '10/03/08 17:13:07', '/workstation/templatesystem/admin/index.php'),
(76, 'Error: Template login not found.', '10/03/08 17:13:07', '/workstation/templatesystem/admin/index.php'),
(77, 'Error: Template login not found.', '10/03/08 17:13:07', '/workstation/templatesystem/admin/index.php'),
(78, 'Error: Template login not found.', '10/03/08 17:13:07', '/workstation/templatesystem/admin/index.php'),
(79, 'Error: Template login not found.', '10/03/08 17:13:07', '/workstation/templatesystem/admin/index.php'),
(80, 'Error: Template login not found.', '10/03/08 17:13:09', '/workstation/templatesystem/admin/index.php'),
(81, 'Error: Template login not found.', '10/03/08 22:10:09', '/workstation/templatesystem/admin/index.php'),
(82, 'Error: Template admin_login not found.', '10/03/08 22:24:49', '/workstation/templatesystem/admin/index.php'),
(83, 'Error: Template admin_login not found.', '10/03/08 22:32:42', '/workstation/templatesystem/admin/index.php'),
(84, 'Error: Template admin_login not found.', '10/03/08 22:32:58', '/workstation/templatesystem/admin/index.php'),
(85, 'Error: Template templatesmessage not found.', '10/03/08 22:35:46', '/workstation/templatesystem/admin/templates.php'),
(86, 'Error: Template templateslist not found.', '10/03/08 22:35:46', '/workstation/templatesystem/admin/templates.php'),
(87, 'Error: Template selecttemplateset not found.', '10/03/08 22:37:09', '/workstation/templatesystem/admin/templates.php'),
(88, 'Error: Template associatedthemes not found.', '10/03/08 22:37:09', '/workstation/templatesystem/admin/templates.php'),
(89, 'Error: Template languages not found.', '10/03/08 23:41:51', '/workstation/templatesystem/admin/languages.php'),
(90, 'Error: Template index not found.', '10/04/08 10:58:13', '/workstation/templatesystem/index.php'),
(91, 'Error: Template adminlogin not found.', '10/04/08 11:21:09', '/workstation/templatesystem/admin/index.php'),
(92, 'Error: Template adminlogin not found.', '10/04/08 11:21:10', '/workstation/templatesystem/admin/index.php'),
(93, 'Error: Template adminlogin not found.', '10/04/08 11:21:11', '/workstation/templatesystem/admin/index.php'),
(94, 'Error: Template adminlogin not found.', '10/04/08 11:21:11', '/workstation/templatesystem/admin/index.php'),
(95, 'Error: Template adminlogin not found.', '10/04/08 11:21:11', '/workstation/templatesystem/admin/index.php'),
(96, 'Error: Template admin_index not found.', '10/04/08 12:55:57', '/workstation/templatesystem/admin/index.php'),
(97, 'Error: Template admin_index not found.', '10/04/08 12:56:22', '/workstation/templatesystem/admin/index.php'),
(98, 'Error: Template admin_index not found.', '10/04/08 12:56:24', '/workstation/templatesystem/admin/index.php'),
(99, 'Error: Template admin_index not found.', '10/04/08 12:58:05', '/workstation/templatesystem/admin/index.php'),
(100, 'Error: Template admin_index not found.', '10/04/08 12:58:06', '/workstation/templatesystem/admin/index.php'),
(101, 'Error: Template admin_index not found.', '10/04/08 12:58:07', '/workstation/templatesystem/admin/index.php'),
(102, 'Error: Template admin_index not found.', '10/04/08 13:02:35', '/workstation/templatesystem/admin/index.php'),
(103, 'Error: Template admin_index not found.', '10/04/08 13:02:35', '/workstation/templatesystem/admin/index.php'),
(104, 'Error: Template admin_index not found.', '10/04/08 13:02:36', '/workstation/templatesystem/admin/index.php'),
(105, 'Error: Template admin_index not found.', '10/04/08 13:02:36', '/workstation/templatesystem/admin/index.php'),
(106, 'Error: Template admin_index not found.', '10/04/08 13:02:36', '/workstation/templatesystem/admin/index.php'),
(107, 'Error: Template admin_index not found.', '10/04/08 13:02:37', '/workstation/templatesystem/admin/index.php'),
(108, 'Error: Template admin_index not found.', '10/04/08 13:03:02', '/workstation/templatesystem/admin/index.php'),
(109, 'Error: Template admin_index not found.', '10/04/08 13:03:03', '/workstation/templatesystem/admin/index.php'),
(110, 'Error: Template admin_index not found.', '10/04/08 13:03:03', '/workstation/templatesystem/admin/index.php'),
(111, 'Error: Template admin_index not found.', '10/04/08 13:03:04', '/workstation/templatesystem/admin/index.php'),
(112, 'Error: Template admin_index not found.', '10/04/08 13:03:13', '/workstation/templatesystem/admin/index.php'),
(113, 'Error: Template admin_index not found.', '10/04/08 13:03:13', '/workstation/templatesystem/admin/index.php'),
(114, 'Error: Template admin_index not found.', '10/04/08 13:03:13', '/workstation/templatesystem/admin/index.php'),
(115, 'Error: Template admin_index not found.', '10/04/08 13:03:14', '/workstation/templatesystem/admin/index.php'),
(116, 'Error: Template admin_index_post not found.', '10/04/08 13:03:38', '/workstation/templatesystem/admin/index.php'),
(117, 'Error: Template admin_indexcontent not found.', '10/04/08 13:03:38', '/workstation/templatesystem/admin/index.php'),
(118, 'Error: Template admin_index_post not found.', '10/04/08 13:03:39', '/workstation/templatesystem/admin/index.php'),
(119, 'Error: Template admin_indexcontent not found.', '10/04/08 13:03:39', '/workstation/templatesystem/admin/index.php'),
(120, 'Error: Template admin_index_post not found.', '10/04/08 13:03:39', '/workstation/templatesystem/admin/index.php'),
(121, 'Error: Template admin_indexcontent not found.', '10/04/08 13:03:39', '/workstation/templatesystem/admin/index.php'),
(122, 'Error: Template admin_index_post not found.', '10/04/08 13:03:40', '/workstation/templatesystem/admin/index.php'),
(123, 'Error: Template admin_indexcontent not found.', '10/04/08 13:03:40', '/workstation/templatesystem/admin/index.php'),
(124, 'Error: Template admin_index_post not found.', '10/04/08 13:03:53', '/workstation/templatesystem/admin/index.php'),
(125, 'Error: Template admin_indexcontent not found.', '10/04/08 13:03:53', '/workstation/templatesystem/admin/index.php'),
(126, 'Error: Template admin_index_post not found.', '10/04/08 13:04:03', '/workstation/templatesystem/admin/index.php'),
(127, 'Error: Template admin_indexcontent not found.', '10/04/08 13:04:03', '/workstation/templatesystem/admin/index.php'),
(128, 'Error: Template admin_index_post not found.', '10/04/08 13:04:04', '/workstation/templatesystem/admin/index.php'),
(129, 'Error: Template admin_indexcontent not found.', '10/04/08 13:04:04', '/workstation/templatesystem/admin/index.php'),
(130, 'Error: Template admin_index_post not found.', '10/04/08 13:04:05', '/workstation/templatesystem/admin/index.php'),
(131, 'Error: Template admin_indexcontent not found.', '10/04/08 13:04:05', '/workstation/templatesystem/admin/index.php'),
(132, 'Error: Template admin_index_post not found.', '10/04/08 13:04:05', '/workstation/templatesystem/admin/index.php'),
(133, 'Error: Template admin_indexcontent not found.', '10/04/08 13:04:05', '/workstation/templatesystem/admin/index.php'),
(134, 'Error: Template admin_index_post not found.', '10/04/08 13:04:21', '/workstation/templatesystem/admin/index.php'),
(135, 'Error: Template admin_indexcontent not found.', '10/04/08 13:04:21', '/workstation/templatesystem/admin/index.php'),
(136, 'Error: Template admin_index_post not found.', '10/04/08 13:04:21', '/workstation/templatesystem/admin/index.php'),
(137, 'Error: Template admin_indexcontent not found.', '10/04/08 13:04:21', '/workstation/templatesystem/admin/index.php'),
(138, 'Error: Template admin_index_post not found.', '10/04/08 13:04:22', '/workstation/templatesystem/admin/index.php'),
(139, 'Error: Template admin_indexcontent not found.', '10/04/08 13:04:22', '/workstation/templatesystem/admin/index.php'),
(140, 'Error: Template admin_index_post not found.', '10/04/08 13:04:22', '/workstation/templatesystem/admin/index.php'),
(141, 'Error: Template admin_indexcontent not found.', '10/04/08 13:04:22', '/workstation/templatesystem/admin/index.php'),
(142, 'Error: Template admin_index not found.', '10/04/08 13:04:41', '/workstation/templatesystem/admin/index.php'),
(143, 'Error: Template admin_index not found.', '10/04/08 13:04:42', '/workstation/templatesystem/admin/index.php'),
(144, 'Error: Template admin_index not found.', '10/04/08 13:04:48', '/workstation/templatesystem/admin/index.php'),
(145, 'Error: Template admin_index not found.', '10/04/08 13:04:48', '/workstation/templatesystem/admin/index.php'),
(146, 'Error: Template admin_index not found.', '10/04/08 13:04:49', '/workstation/templatesystem/admin/index.php'),
(147, 'Error: Template admin_index not found.', '10/04/08 13:04:49', '/workstation/templatesystem/admin/index.php'),
(148, 'Error: Template admin_index not found.', '10/04/08 13:04:49', '/workstation/templatesystem/admin/index.php'),
(149, 'Error: Template admin_index not found.', '10/04/08 13:04:50', '/workstation/templatesystem/admin/index.php'),
(150, 'Error: Template admin_index not found.', '10/04/08 13:04:50', '/workstation/templatesystem/admin/index.php'),
(151, 'Error: Template admin_index_post not found.', '10/04/08 13:04:53', '/workstation/templatesystem/admin/index.php'),
(152, 'Error: Template admin_indexcontent not found.', '10/04/08 13:04:53', '/workstation/templatesystem/admin/index.php'),
(153, 'Error: Template admin_index_post not found.', '10/04/08 13:05:03', '/workstation/templatesystem/admin/index.php'),
(154, 'Error: Template admin_indexcontent not found.', '10/04/08 13:05:03', '/workstation/templatesystem/admin/index.php'),
(155, 'Error: Template admin_index_post not found.', '10/04/08 13:05:04', '/workstation/templatesystem/admin/index.php'),
(156, 'Error: Template admin_indexcontent not found.', '10/04/08 13:05:04', '/workstation/templatesystem/admin/index.php'),
(157, 'Error: Template admin_index_post not found.', '10/04/08 13:05:11', '/workstation/templatesystem/admin/index.php'),
(158, 'Error: Template admin_indexcontent not found.', '10/04/08 13:05:11', '/workstation/templatesystem/admin/index.php'),
(159, 'Error: Template admin_index_post not found.', '10/04/08 13:05:11', '/workstation/templatesystem/admin/index.php'),
(160, 'Error: Template admin_indexcontent not found.', '10/04/08 13:05:11', '/workstation/templatesystem/admin/index.php'),
(161, 'Error: Template admin_index_post not found.', '10/04/08 13:05:12', '/workstation/templatesystem/admin/index.php'),
(162, 'Error: Template admin_indexcontent not found.', '10/04/08 13:05:12', '/workstation/templatesystem/admin/index.php'),
(163, 'Error: Template admin_index_post not found.', '10/04/08 13:05:12', '/workstation/templatesystem/admin/index.php'),
(164, 'Error: Template admin_indexcontent not found.', '10/04/08 13:05:12', '/workstation/templatesystem/admin/index.php'),
(165, 'Error: Template admin_index_post not found.', '10/04/08 13:05:13', '/workstation/templatesystem/admin/index.php'),
(166, 'Error: Template admin_indexcontent not found.', '10/04/08 13:05:13', '/workstation/templatesystem/admin/index.php'),
(167, 'Error: Template admin_index_post not found.', '10/04/08 13:05:18', '/workstation/templatesystem/admin/index.php'),
(168, 'Error: Template admin_indexcontent not found.', '10/04/08 13:05:18', '/workstation/templatesystem/admin/index.php'),
(169, 'Error: Template admin_index_post not found.', '10/04/08 13:05:19', '/workstation/templatesystem/admin/index.php'),
(170, 'Error: Template admin_indexcontent not found.', '10/04/08 13:05:19', '/workstation/templatesystem/admin/index.php'),
(171, 'Error: Template admin_index_post not found.', '10/04/08 13:05:19', '/workstation/templatesystem/admin/index.php'),
(172, 'Error: Template admin_indexcontent not found.', '10/04/08 13:05:19', '/workstation/templatesystem/admin/index.php'),
(173, 'Error: Template admin_index_post not found.', '10/04/08 13:05:19', '/workstation/templatesystem/admin/index.php'),
(174, 'Error: Template admin_indexcontent not found.', '10/04/08 13:05:19', '/workstation/templatesystem/admin/index.php'),
(175, 'Error: Template admin_index_post not found.', '10/04/08 13:05:20', '/workstation/templatesystem/admin/index.php'),
(176, 'Error: Template admin_indexcontent not found.', '10/04/08 13:05:20', '/workstation/templatesystem/admin/index.php'),
(177, 'Error: Template admin_asdfsd not found.', '10/04/08 22:48:19', '/workstation/templatesystem/admin/index.php'),
(178, 'Error: Template admin_asdfsd not found.', '10/04/08 22:48:31', '/workstation/templatesystem/admin/index.php'),
(179, 'Error: Template admin_asdfsd not found.', '10/04/08 22:49:20', '/workstation/templatesystem/admin/index.php'),
(180, 'Error: Template admin_asdfsd not found.', '10/04/08 22:49:20', '/workstation/templatesystem/admin/index.php'),
(181, 'Error: Template admin_asdfsd not found.', '10/04/08 22:49:23', '/workstation/templatesystem/admin/index.php'),
(182, 'Error: Template admin_panel not found.', '10/04/08 22:49:50', '/workstation/templatesystem/admin/index.php'),
(183, 'Error: Template admin_login not found.', '10/04/08 22:52:58', '/workstation/templatesystem/admin/index.php'),
(184, 'Error: Template 1 not found.', '10/05/08 11:14:18', '/workstation/templatesystem/admin/index.php'),
(185, 'Error: Template 1 not found.', '10/05/08 11:14:18', '/workstation/templatesystem/admin/index.php'),
(186, 'Error: Template 1 not found.', '10/05/08 11:15:26', '/workstation/templatesystem/admin/index.php'),
(187, 'Error: Template 1 not found.', '10/05/08 11:15:26', '/workstation/templatesystem/admin/index.php'),
(188, 'Error: Template 1 not found.', '10/05/08 11:15:27', '/workstation/templatesystem/admin/index.php'),
(189, 'Error: Template 1 not found.', '10/05/08 11:15:27', '/workstation/templatesystem/admin/index.php'),
(190, 'Error: Template panel is dynamic.', '10/05/08 11:35:27', '/workstation/templatesystem/admin/index.php'),
(191, 'Error: Template panel is dynamic.', '10/05/08 11:35:28', '/workstation/templatesystem/admin/index.php'),
(192, 'Error: Template panel is dynamic.', '10/05/08 11:35:58', '/workstation/templatesystem/admin/index.php'),
(193, 'Error: Template login not found.', '10/05/08 11:36:37', '/workstation/templatesystem/admin/index.php'),
(194, 'Error: Template admin_login not found.', '10/05/08 11:36:49', '/workstation/templatesystem/admin/index.php'),
(195, 'Error: Template admin_login not found.', '10/05/08 11:37:28', '/workstation/templatesystem/admin/index.php'),
(196, 'Error: Template admin_login not found.', '10/05/08 11:37:31', '/workstation/templatesystem/admin/index.php'),
(197, 'Error: Template admin_login not found.', '10/05/08 11:38:02', '/workstation/templatesystem/admin/index.php'),
(198, 'Error: Template admin_login not found.', '10/05/08 11:38:31', '/workstation/templatesystem/admin/index.php'),
(199, 'Error: Template admin_login not found.', '10/05/08 11:38:43', '/workstation/templatesystem/admin/index.php'),
(200, 'Error: Template admin_login not found.', '10/05/08 11:38:44', '/workstation/templatesystem/admin/index.php'),
(201, 'Error: Template admin_index_post not found.', '10/05/08 11:43:29', '/workstation/templatesystem/admin/index.php'),
(202, 'Error: Language Not Found', '10/05/08 11:51:57', '/workstation/templatesystem/admin/index.php'),
(203, 'Error: Language Not Found', '10/05/08 11:52:26', '/workstation/templatesystem/admin/index.php'),
(204, 'Error: Language Not Found', '10/05/08 11:53:16', '/workstation/templatesystem/admin/index.php'),
(205, 'Error: Language Not Found', '10/05/08 11:54:04', '/workstation/templatesystem/admin/index.php'),
(206, 'Error: Template flslalslalsla not found.', '10/05/08 17:41:25', '/workstation/templatesystem/index.php'),
(207, 'Error: Template flslalslalsla not found.', '10/05/08 17:45:09', '/workstation/templatesystem/index.php'),
(208, 'Error: Template head not found.', '10/06/08 15:25:04', '/workstation/templatesystem/admin/templates.php'),
(209, 'Error: Template admin_menuprint not found.', '10/06/08 15:40:16', '/workstation/templatesystem/index.php'),
(210, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(211, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(212, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(213, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(214, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(215, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(216, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(217, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(218, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(219, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(220, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(221, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(222, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(223, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(224, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(225, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(226, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(227, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(228, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(229, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(230, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(231, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(232, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(233, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(234, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(235, 'Error: Language Not Found', '10/06/08 16:30:56', '/workstation/templatesystem/admin_templates.php'),
(236, 'Error: Template admin_dynamictemplateupdated not found.', '10/06/08 17:36:55', '/workstation/templatesystem/admin_templates.php');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` bigint(20) NOT NULL auto_increment,
  `path` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `files`
--


-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `defaults` tinyint(4) NOT NULL,
  `parent` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `title`, `content`, `defaults`, `parent`) VALUES
(1, 'English', '', 1, 0),
(2, 'requiredfields', 'You have not filled out the required fields!', 0, 1),
(3, 'nomatch', 'The inputted password does not match the username {1}!', 0, 1),
(4, 'updatedsuccessfully', 'Updated Successfully!', 0, 1),
(5, 'templatenotfound', 'Error: Template {1} not found.', 0, 1),
(6, 'templatestart', 'Template Start: {1}', 0, 1),
(7, 'templateend', 'Template End: {1}', 0, 1),
(8, 'notdynamic', 'Error: Template {1} is not dynamic.', 0, 1),
(9, 'isdynamic', 'Error: Template {1} is dynamic.', 0, 1),
(10, 'title', 'Template Management System', 0, 1),
(11, 'copyright', 'All content is &copy; Brandon Evans.', 0, 1),
(12, 'cantopenfile', 'Can''t Open File', 0, 1),
(13, 'notauthorized', 'You are not authorized to view this page!', 0, 1),
(14, 'adminwelcome', 'Welcome!', 0, 1),
(15, 'associatedthemes', 'Themes associated with this Template:', 0, 1),
(16, 'home', 'Home', 0, 1),
(17, 'logout', 'Logout', 0, 1),
(18, 'templates', 'Templates', 0, 1),
(19, 'languages', 'Languages', 0, 1),
(20, 'admin', 'Admin', 0, 1),
(21, 'dynamictemplates', 'Dynamic Templates', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `dynamic` tinyint(4) NOT NULL,
  `parent` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `title`, `content`, `dynamic`, `parent`) VALUES
(1, 'Default Templates', '', 0, 0),
(2, 'admin_index', '(init)\r\n{header}\r\n(admin_indexcontent)\r\n{footer}', 0, 1),
(3, 'login', '(login_post)\r\n(loginmessage)\r\n<form action="index.php" method="post">\r\n<label for="username">Username: <input type="text" name="username" /></label>\r\n<label for="password">Password: <input type="password" name="password" /></label>\r\n<input type="submit" name="submit" value="Submit" />\r\n</form>', 0, 1),
(4, 'admin_templates', '(init)\r\n(admin_protect)\r\n(admin_templates_post)\r\n{header}\r\n(admin_templateslist)\r\n{footer}', 0, 1),
(5, 'admin_selecttemplateset', '<br /><a href="admin_templates.php?set={1}">{2}</a> | [associatedthemes] {3}', 1, 1),
(6, 'admin_selecttemplate', '<li><a href="admin_templates.php?set={1}&amp;template={2}">{3}</a></li>', 1, 1),
(7, 'admin_edittemplate', '<form action="admin_templates.php?cmd=update" method="post">\r\n<input type="hidden" name="id" value="{1}" />\r\n<input type="text" name="title" value="{2}" />\r\n<br /><textarea name="content" rows="20" cols="100">\r\n{3}</textarea>\r\n<br /><input type="submit" name="submit" value="Update" />\r\n</form>', 1, 1),
(8, 'admin_associatedthemes', '{1}', 1, 1),
(9, 'doctype', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">', 0, 1),
(10, 'admin_languages', '(init)\r\n(admin_protect)\r\n{header}\r\n{footer}', 0, 1),
(11, 'panel', '{1}', 1, 1),
(26, 'admin_menu', '<a href="admin_index.php">[admin]</a>\r\n<a href="admin_templates.php">[templates]</a>\r\n<a href="admin_languages.php">[languages]</a>', 0, 1),
(12, 'header', '{doctype}\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">\r\n<head>\r\n{head}\r\n</head>\r\n<body>\r\n	<div id="wrapper">\r\n		<div id="banner">\r\n\r\n			<h1>[title]</h1>\r\n		</div>\r\n		<div id="topbar">\r\n                        {navigation}\r\n		</div>\r\n		<div id="panel">\r\n                        (panel)\r\n		</div>\r\n		\r\n		<div id="content">', 0, 1),
(13, 'footer', '		</div>\r\n		\r\n		<div id="copyright">\r\n			<p>[copyright]</p>\r\n\r\n		</div>\r\n	</div>\r\n</body>\r\n</html>', 0, 1),
(14, 'loginmessage', '{1}\r\n', 1, 1),
(15, 'admin_templateslist', '{1}', 1, 1),
(16, 'admin_templateupdated', '[updatedsuccessfully]', 1, 1),
(17, 'navigation', '<a href="index.php">[home]</a>', 0, 1),
(18, 'init', '', 1, 1),
(19, 'login_post', '', 1, 1),
(20, 'admin_indexcontent', '{1}', 1, 1),
(25, 'head', '	<title>[title]</title>\r\n	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />\r\n	<style type="text/css">\r\n		body\r\n		{\r\n			font-family: Verdana, Sans-Serif;\r\n			font-size: 99.8%;\r\n			color: #000;\r\n		}\r\n		\r\n		a\r\n		{\r\n			color: #333;\r\n			text-decoration: underline;\r\n		}\r\n		\r\n		#wrapper\r\n		{\r\n			width: 100%;\r\n			margin: auto;\r\n		}\r\n		\r\n		#banner\r\n		{\r\n			margin: 0;\r\n			padding: 1em;\r\n			border-top: 1px solid #000;\r\n			border-left: 1px solid #000;\r\n			border-right: 1px solid #000;\r\n			border-bottom: none;\r\n		}\r\n		\r\n		#banner h1\r\n		{\r\n			font-size: 18px;\r\n		}\r\n		\r\n		#topbar\r\n		{\r\n			margin: 0;\r\n			padding: 5px;\r\n			text-align: center;\r\n			border-top: 1px solid #000;\r\n			border-left: 1px solid #000;\r\n			border-right: 1px solid #000;\r\n			border-bottom: none;\r\n		}\r\n\r\n		#panel\r\n		{\r\n			margin: 0;\r\n			padding: 5px;\r\n			text-align: center;\r\n			border-top: 1px solid #000;\r\n			border-left: 1px solid #000;\r\n			border-right: 1px solid #000;\r\n			border-bottom: none;\r\n		}\r\n		\r\n		#topbar a\r\n		{\r\n			text-decoration: none;\r\n			padding: 5px;\r\n		}\r\n		\r\n		#content\r\n		{\r\n			border: 1px solid #000;\r\n			padding: 1em;\r\n		}\r\n		\r\n		#copyright\r\n		{\r\n			text-align: center;\r\n			margin: 0;\r\n			padding: 1px;\r\n			border-top: none;\r\n			border-left: 1px solid #000;\r\n			border-right: 1px solid #000;\r\n			border-bottom: 1px solid #000;\r\n		}\r\n	</style>', 0, 1),
(21, 'admin_protect', '', 1, 1),
(22, 'admin_templates_post', '', 1, 1),
(23, 'index', '(init)\r\n{header}\r\nHi!\r\n{footer}', 0, 1),
(24, 'menu', '<a href="?logout=true">[logout]</a>\r\n(admin_menuprint)', 0, 1),
(27, 'admin_menuprint', '{1}', 1, 1),
(28, 'admin_selecttemplateskeleton', '{1}\r\n\r\n<br /><ul>{2}</ul>\r\n\r\n{3}\r\n\r\n<br /><ul>{4}</ul>', 1, 1),
(29, 'admin_dynamictemplateupdated', '[updatedsuccessfully]', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` text NOT NULL,
  `template` bigint(20) NOT NULL,
  `defaults` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `title`, `template`, `defaults`) VALUES
(1, 'Default Theme', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL auto_increment,
  `admin` tinyint(4) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `language` bigint(20) NOT NULL,
  `theme` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `admin`, `username`, `password`, `email`, `language`, `theme`) VALUES
(1, 1, 'Brandon', '6e0373f47ff9a3e919da31eab7760f06', 'admin@brandonevans.org', 0, 0),
(2, 1, 'Faltzer', 'fde0c3047902d10492abdfd8f1e5120d', 'faltzermaster@aol.com', 0, 0);
