-- phpMyAdmin SQL Dump
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 23, 2009 at 08:26 PM
-- Server version: 5.0.67
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `suit_SUIT`
--

-- --------------------------------------------------------

--
-- Table structure for table `docs`
--

CREATE TABLE IF NOT EXISTS `docs` (
  `id` bigint(10) NOT NULL auto_increment,
  `title` text NOT NULL,
  `template` text NOT NULL,
  `category` text NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `title` (`title`,`template`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `docs`
--

INSERT INTO `docs` (`id`, `title`, `template`, `category`) VALUES
(1, 'credits', 'docs_credits', 'General'),
(2, 'error1', 'docs_error1', 'Errors'),
(3, 'error2', 'docs_error2', 'Errors'),
(4, 'error3', 'docs_error3', 'Errors'),
(5, 'error4', 'docs_error4', 'Errors'),
(6, 'error5', 'docs_error5', 'Errors'),
(7, 'error6', 'docs_error6', 'Errors'),
(8, 'error7', 'docs_error7', 'Errors'),
(9, 'error8', 'docs_error8', 'Errors'),
(10, 'error9', 'docs_error9', 'Errors'),
(11, 'error10', 'docs_error10', 'Errors'),
(12, 'error11', 'docs_error11', 'Errors'),
(13, 'error12', 'docs_error12', 'Errors'),
(14, 'error13', 'docs_error13', 'Errors'),
(15, 'error14', 'docs_error14', 'Errors'),
(16, 'error15', 'docs_error15', 'Errors'),
(17, 'error16', 'docs_error16', 'Errors'),
(18, 'error17', 'docs_error17', 'Errors');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`content`) VALUES
('Languages converted, except for the switching concept. Easy fix, just don''t have the time right now. We also probably need a better place to store this thing.\r\n\r\nI know, but how do you propose we prevent this? Also, I need something to display the edited message.\r\n\r\nUse $_POST in brandonsbb functions instead of passing it.\r\n\r\nCommunity fucks up in IE.\r\n\r\npreg_match inside out. This forum has opened my eyes on  how many bugs we have that we overlooked.\r\n\r\nValidation.\r\n\r\nChange Info / Register Confirm Password.\r\n\r\nReplace Recursiveness.\r\n\r\nEnd all SQL with a ; in your cleaning please.\r\n\r\nIRC, for CSS, please.\r\n\r\nDocumentation Index and 404\r\n\r\nSearch, and also, if the search bar is blank, it should show as if the search was not conducted.\r\n\r\nPerhaps planning post completion registrations: Perhaps have a sourceforge page, talk to freenode about having a channel, etc.\r\n\r\nFlat file database\r\n\r\nJavascript that shows how the page is actually built step by step - Well, I thought we could do it by alerting the user during every parse or something and then clear the content and document.write');

-- --------------------------------------------------------

--
-- Table structure for table `suit_errorlog`
--

CREATE TABLE IF NOT EXISTS `suit_errorlog` (
  `id` bigint(20) NOT NULL auto_increment,
  `content` text NOT NULL,
  `time` text NOT NULL,
  `location` text NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `content` (`content`,`location`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `suit_errorlog`
--

INSERT INTO `suit_errorlog` (`id`, `content`, `time`, `location`) VALUES
(1, 'SUIT Error #11: community. See http://www.suitframework.com/docs/error11/', '1232741635', '/index.php?page=community'),
(2, 'SUIT Error #11: admin_languages. See http://www.suitframework.com/docs/error11/', '1232741923', '/index.php?page=admin_languages');

-- --------------------------------------------------------

--
-- Table structure for table `suit_pages`
--

CREATE TABLE IF NOT EXISTS `suit_pages` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` text NOT NULL,
  `template` text NOT NULL,
  `defaults` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `title` (`title`,`template`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `suit_pages`
--

INSERT INTO `suit_pages` (`id`, `title`, `template`, `defaults`) VALUES
(1, 'home', 'home', 1),
(2, 'admin_errorlog', 'admin_errorlog', 0),
(3, 'admin_notes', 'admin_notes', 0),
(5, 'admin_templates', 'admin_templates', 0),
(6, 'phpinfo', 'phpinfo', 0),
(7, 'sandbox', 'sandbox', 0),
(10, 'register', 'register', 0),
(11, 'changeinfo', 'changeinfo', 0),
(12, 'lostpassword', 'lostpassword', 0),
(27, 'extract', 'extract', 0),
(25, 'admin_pages', 'admin_pages', 0),
(26, 'admin_escape', 'admin_escape', 0),
(28, 'admin_users', 'admin_users', 0),
(30, '404', '404', 0),
(31, '401', '401', 0),
(32, '403', '403', 0),
(33, '500', '500', 0),
(35, 'admin_docs', 'admin_docs', 0),
(36, 'docs', 'docs', 0),
(38, 'community', 'community', 0);

-- --------------------------------------------------------

--
-- Table structure for table `suit_templates`
--

CREATE TABLE IF NOT EXISTS `suit_templates` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `title` (`title`,`content`),
  FULLTEXT KEY `title_2` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1335 ;

--
-- Dumping data for table `suit_templates`
--

INSERT INTO `suit_templates` (`id`, `title`, `content`) VALUES
(1278, 'list_search', ''),
(1279, 'pagelink', '<a href="<url>&amp;start=<start>&amp;limit=<limit>&amp;orderby=<orderby>&amp;search=<search>" class="<class>"><display></a>'),
(1280, 'language_default', '<option value="-1">Default</option>'),
(1325, 'community_post', '	<div class="msg2">\r\n		<div class="controls">\r\n			<div class="links">\r\n				<h3 class="post-username"><a href="{path_url}/index.php?page=community&amp;cmd=users&amp;id=<userid>"><username></a></h3>\r\n				[posts][colon] <posts><br />\r\n			</div>\r\n			<div class="info2">\r\n				<h2><a name="post<id>" /><a href="#post<id>"><title></a> <time></h2>\r\n				<content>\r\n				<edited>\r\n				<signature>\r\n				<div class="post-controls">\r\n					<p><a href="{path_url}/index.php?page=community&amp;post=<id>&amp;cmd=edit">[editpost]</a> | <a href="{path_url}/index.php?page=community&amp;post=<id>&amp;cmd=delete">[deletepost]</a> | <a href="{path_url}/index.php?page=community&amp;post=<id>&amp;cmd=newreply">[quotepost]</a></p>\r\n				</div>\r\n			</div>\r\n		</div><br clear="all" />\r\n	</div>'),
(1282, 'language_entry', '<option value="<id>"<selected>><title></option>'),
(1283, 'language', '<select name="language">\r\n<default>\r\n<languages>\r\n</select>'),
(1244, '500', '{header}\r\n	<div class="section">\r\n		<h2>500</h2>\r\n		500\r\n	</div>\r\n{footer}'),
(1247, 'fileentities', 'index.php?<file>'),
(1277, 'list_default', '<b><title></b>'),
(1252, 'notdefault', '<a href="<url>&amp;id=<id>&amp;cmd=default"><title></a>'),
(1240, 'admin_users', '{admin_protect}{header}\r\n	<div class="section">\r\n		<h2>[users]</h2>\r\n		<admin_users>\r\n	</div>\r\n{footer}'),
(1242, '401', '{header}\r\n	<div class="section">\r\n		<h2>401</h2>\r\n		401\r\n	</div>\r\n{footer}'),
(1243, '403', '{header}\r\n	<div class="section">\r\n		<h2>403</h2>\r\n		403\r\n	</div>\r\n{footer}'),
(1218, 'closingbrace', '[closingbrace]'),
(1221, 'config', ' '),
(1222, 'badrequest', '{header}\r\n	<div class="section">\r\n		<h2>[badrequest]</h2>\r\n		[badrequest]\r\n	</div>\r\n{footer}\r\n'),
(1223, 'notauthorized', '{header}\r\n	<div class="section">\r\n		<h2>[notauthorized]</h2>\r\n		[notauthorized]\r\n	</div>\r\n{footer}\r\n'),
(1224, 'file', 'index.php?<file>'),
(1232, 'admin_escape', '{admin_protect}{header}\r\n	<div class="section">\r\n		<h2>[escape]</h2>\r\n		<form action="#" method="post">\r\n		<br /><textarea id="code" name="code" rows="40" cols="100" wrap="off" style="width: 100%;"><code></textarea>\r\n		<br /><input type="submit" name="escape" value="[escape]" />\r\n		</form>\r\n	</div>\r\n{footer}'),
(1202, 'tie', ''),
(1217, 'openingbrace', '[openingbrace]'),
(849, 'recaptcha_lib', ''),
(850, 'recaptcha_keys', ''),
(848, 'recaptcha', '<recaptcha>'),
(26, 'admin_menu', ' | <a href="{path_url}/index.php?page=admin_notes">[notes]</a> [verticalbar] \r\n<a href="{path_url}/index.php?page=admin_templates">[templates]</a> [verticalbar] \r\n<a href="{path_url}/index.php?page=admin_pages">[pages]</a> [verticalbar] \r\n<a href="{path_url}/index.php?page=admin_users">[users]</a> [verticalbar] \r\n<a href="{path_url}/index.php?page=admin_errorlog">[errorlog]</a> [verticalbar] \r\n<a href="{path_url}/index.php?page=admin_escape">[escape]</a> [verticalbar] \r\n<a href="{path_url}/index.php?page=admin_docs">[docs]</a> [verticalbar] \r\n<a href="{path_url}/index.php?page=phpinfo">[phpinfo]</a> [verticalbar] \r\n<a href="{path_url}/index.php?page=extract">[extract]</a>'),
(842, 'register', '{header}\r\n	<div class="section">\r\n		<h2>[register]</h2>\r\n		<message>\r\n		<form action="#" method="post">\r\n		<table>\r\n		<tr>\r\n		<td>[username][colon]</td>\r\n		<td><input type="text" name="username" /></td>\r\n		</tr>\r\n		<tr>\r\n		<td>[password][colon]</td>\r\n		<td><input type="password" name="password" /></td>\r\n		</tr>\r\n		<tr>\r\n		<td>[email][colon]</td>\r\n		<td><input type="text" name="email" /></td>\r\n		</tr>\r\n		<tr>\r\n		<td>[language][colon]</td>\r\n		<td><languages></td>\r\n		</tr>\r\n		<tr>\r\n		<td>[recaptcha][colon]</td>\r\n		<td>{recaptcha}</td>\r\n		</tr>\r\n		</table>\r\n		<input type="submit" name="register" value="Register" />\r\n		</form>\r\n	</div>\r\n{footer}'),
(1322, 'community_list', '<newtopic>\r\n<forums>\r\n<topics>'),
(1323, 'community_topic', '<div class="msg2">\r\n	<div class="controls">\r\n		<div class="links">\r\n			<a href="{path_url}/index.php?page=community&amp;topic=<id>"><title></a>\r\n		</div>\r\n		<div class="info2"></div>\r\n	</div><br clear="all" />\r\n</div>'),
(1303, 'docs_error1', 'Config file could not be found or is empty. Please run install.'),
(1304, 'docs_error2', 'Constants not properly defined.'),
(1305, 'docs_error3', 'PHP Version must be greater than 4.4.9.'),
(1306, 'docs_error4', 'Register Globals MUST be disabled.'),
(1307, 'docs_error5', 'Writable does not exist or is not CHMOD 777.'),
(1308, 'docs_error6', 'There is no RDBMS specified, or the RDBMS is not supported by SUIT.'),
(1309, 'docs_error7', 'The variable $mn is not set in the file, and therefore, it cannot be loaded as a module.'),
(1310, 'docs_error8', 'The module file does not exist.'),
(1311, 'docs_error9', 'Could not log error.'),
(1312, 'docs_error10', 'Templates Directory Not Found.'),
(1313, 'docs_error11', 'Template Not Fond.'),
(1314, 'docs_error12', 'Infinite Loop.'),
(1315, 'docs_error13', 'Database could not be Selected.'),
(1316, 'docs_error14', 'A connection with the DB server could not be established with the provided username and password.'),
(1317, 'docs_error15', 'Not Properly Formatted.'),
(1318, 'docs_error16', 'Phrase Not Found.'),
(1319, 'docs_error17', 'Language Not Found.'),
(39, 'postdata_list', '<tr>\r\n<td><key></td>\r\n<td><value></td>\r\n</tr>'),
(1137, 'changeinfo', '{header}\r\n	<div class="section">\r\n		<h2>[changeinfo]</h2>\r\n		<message>\r\n		<form action="#" method="post">\r\n		<table>\r\n		<tr>\r\n		<td>[username][colon]</td>\r\n		<td><input type="text" name="username" value="<username>" /></td>\r\n		</tr>\r\n		<tr>\r\n		<td>[password][colon]</td>\r\n		<td><input type="password" name="password" /></td>\r\n		</tr>\r\n		<tr>\r\n		<td>[email][colon]</td>\r\n		<td><input type="text" name="email" value="<email>" /></td>\r\n		</tr>\r\n		</table>\r\n		<input type="submit" name="changeinfo" value="[changeinfo]" />\r\n		</form>\r\n	</div>\r\n{footer}'),
(41, 'admin_templates_form', '<message>\r\n<form action="#" method="post">\r\n<input type="hidden" name="id" value="<id>" />\r\n[inputtitle][colon] <input type="text" name="title" value="<title>" />\r\n<br />[content][colon] <textarea name="content" rows="40" cols="100" wrap="off" style="width: 100%;"><content></textarea>\r\n<br />[phpcode][colon]\r\n<br /><textarea name="code" rows="40" cols="100" wrap="off" style="width: 100%;"><code></textarea>\r\n<br /><input type="submit" name="<name>" value="<value>" />\r\n</form>'),
(1241, '404', '{header}\r\n	<div class="section">\r\n		<h2>404</h2>\r\n		404\r\n	</div>\r\n{footer}'),
(1238, 'extract', '{admin_protect}{header}\r\n	<div class="section">\r\n		<h2>[extract]</h2>\r\n		<a href="{path_url}/index.php?page=extract&amp;cmd=db">Database</a>\r\n		<br /><a href="{path_url}/index.php?page=extract&amp;cmd=files">Files</a>\r\n		<languages>\r\n	</div>\r\n{footer}'),
(47, 'delete', '<message>\r\n<form action="#" method="post"><id>\r\n<br /><input type="submit" name="<name>" value="[delete]" />\r\n</form>'),
(1289, 'inputarray', '\r\n<input type="hidden" name="id[openingbracket][closingbracket]" value="<id>" />'),
(1251, 'default', '<b><title></b>'),
(56, 'phpinfo', '{admin_protect}'),
(21, 'admin_protect', ''),
(23, 'home', '{header}\r\n	<div class="section">\r\n		<h2>[home]</h2>\r\n		Hi!\r\n	</div>\r\n{footer}'),
(24, 'menu', '<b><welcome></b> | \r\n<a href="{fileentities}&amp;suit_logout=true">[logout]</a> | \r\n<a href="{path_url}/index.php?page=changeinfo">[changeinfo]</a>\r\n<admin_menu>'),
(28, 'list', '<form action="#" method="post">\r\n[entriesperpage][colon] <input type="text" name="limitval" value="<limitval>" />\r\n<input type="submit" name="limit" value="[list]" />\r\n</form>\r\n<form action="#" method="post">\r\n[search][colon] <input type="text" name="searchval" value="<currentsearch>" />\r\n<input type="submit" name="search" value="[search]" />\r\n</form>\r\n<form action="#" method="post">\r\n<p style="text-align: center;"><input type="submit" name="deleteselected" value="[deleteselected]" /><languages></p>\r\n<div class="container">\r\n	<div class="txt">\r\n		<div class="select"><a href="<url>&amp;start=<start>&amp;limit=<limitval>&amp;orderby=<orderby_type>&amp;search=<currentsearch>"><orderby></a> | <a href="<url>&amp;start=<start>&amp;limit=<limitval>&amp;orderby=<currentorderby>&amp;search=<currentsearch>&amp;select=true">[selectall]</a> [verticalbar] <a href="<url>&amp;start=<start>&amp;limit=<limitval>&amp;orderby=<currentorderby>&amp;search=<currentsearch>&amp;select=false">[deselectall]</a></div>\r\n		<div class="new"><a href="<url>&amp;start=<start>&amp;limit=<limitval>&amp;orderby=<currentorderby>&amp;search=<currentsearch>&amp;<get>=0&amp;cmd=add">[add]</a></div>\r\n	</div>\r\n	<list>\r\n	<div class="txt2">[count][colon] <count>  [verticalbar] [pages][colon] <First> <1> <2> <3> <4> <5> <Last></div>\r\n</div>\r\n</form>\r\n<form action="#" method="post">\r\n[entriesperpage][colon] <input type="text" name="limitval" value="<limitval>" />\r\n<input type="submit" name="limit" value="[list]" />\r\n</form>'),
(945, 'redirect', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml">\r\n<head>\r\n<title>[title]</title>\r\n<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />\r\n<link href="{path_url}/main.css" rel="stylesheet" type="text/css" />\r\n</head>\r\n\r\n<body>\r\n<div id="redirect"><p><message></p>\r\n<p>You are logged in.</p>\r\n<p><seconds></p>\r\n<p><a href="<url>">[clickhere]</a></p></div>\r\n</body>\r\n</html>'),
(38, 'postdata', '{header}\r\n[sessionexpired]\r\n<br /><br /><table border="1">\r\n<tr>\r\n<td>[key]</td>\r\n<td>[value]</td>\r\n</tr>\r\n<list>\r\n</table>\r\n{footer}'),
(13, 'footer', '</div>\r\n\r\n<div id="footer">\r\n	<p><a href="{path_url}/index.php">[home]</a> [verticalbar] <a href="{path_url}/index.php?page=docs">[docs]</a></p>\r\n	<p>[poweredby]</p>\r\n	<p>[copyright]</p>\r\n</div>\r\n</body>\r\n</html>'),
(12, 'header', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml">\r\n<head>\r\n<title>[title]</title>\r\n<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />\r\n<link href="{path_url}/main.css" rel="stylesheet" type="text/css" />\r\n</head>\r\n\r\n<body>\r\n<div id="header">\r\n	<div class="d1"><h1 class="title"><a href="{path_url}/index.php">[title]</a></h1>\r\n	<div class="slogan">[slogan]</div></div>\r\n	<div class="d2"><div>\r\n		<form action="#" method="post">\r\n		<input name="suit_searchval" type="text" class="txtSearch" /> <input name="suit_search" type="submit" class="btnSearch" value="[search]" />\r\n		</form><div>\r\n		<form  class="search" action="#" method="post">\r\n		<p><languages> <input type="submit" name="suit_languages" value="[update]" class="btnUpdate" /></p>\r\n		</form></div></div>\r\n	</div>\r\n</div>\r\n<div class="welcome"><div class="menu"><menu></div></div>\r\n<div id="nav">\r\n	<div class="space">&nbsp;</div>\r\n	<ul>\r\n		<li<home>><a href="{path_url}/index.php">[home]</a></li>\r\n		<li<docs>><a href="{path_url}/docs">[docs]</a></li>\r\n		<li<community>><a href="{path_url}/index.php?page=community">[community]</a></li>\r\n	</ul>\r\n</div>\r\n<div id="content">'),
(1301, 'classselected', ' class="selected"'),
(1139, 'lostpassword', '{header}\r\n	<div class="section">\r\n		<h2>[lostpassword]</h2>\r\n		<message>\r\n		<form action="#" method="post">\r\n		[email][colon] <input type="text" name="email" />\r\n		<br /><input type="submit" name="lostpassword" value="[send]" />\r\n		</form>\r\n	</div>\r\n{footer}'),
(730, 'path_url', '<path>'),
(704, 'admin_errorlog', '{admin_protect}{header}\r\n	<div class="section">\r\n		<h2>[errorlog]</h2>\r\n		<admin_errorlog>\r\n	</div>\r\n{footer}'),
(1291, 'docs', '{header}\r\n	<div class="section">\r\n		<h2>[docs]</h2>\r\n		<docs>\r\n	</div>\r\n{footer}'),
(1227, 'admin_pages', '{admin_protect}{header}\r\n	<div class="section">\r\n		<h2>[pages]</h2>\r\n		<admin_pages>\r\n	</div>\r\n{footer}'),
(1253, 'admin_users_form', '<message>\r\n<form action="#" method="post">\r\n<input type="hidden" name="id" value="<id>" />\r\n[username][colon] <input type="text" name="username" value="<username>" />\r\n<br />[password][colon] <input type="password" name="password" />\r\n<br />[email][colon] <input type="text" name="email" value="<email>" />\r\n<br />[language][colon] <languages>\r\n<br />[admin][colon] <select name="admin">\r\n<option value="0"<no>>No</option>\r\n<option value="1"<yes>>Yes</option>\r\n</select>\r\n<br /><input type="submit" name="<name>" value="<value>" />\r\n</form>'),
(1250, 'admin_pages_form', '<message>\r\n<form action="#" method="post">\r\n<input type="hidden" name="id" value="<id>" />\r\n[inputtitle][colon] <input type="text" name="title" value="<title>" />\r\n<br />[template][colon] <input type="text" name="template" value="<template>" />\r\n<br /><input type="submit" name="<name>" value="<value>" />\r\n</form>'),
(1248, 'sandbox', ''),
(4, 'admin_templates', '{admin_protect}{header}\r\n	<div class="section">\r\n		<h2>[templates]</h2>\r\n		<admin_templates>\r\n	</div>\r\n{footer}'),
(6, 'list_entry', '	<div class="msg2">\r\n		<div class="controls">\r\n			<div class="links"><div class="chk"><input name="entry[openingbracket][closingbracket]" type="checkbox" value="<id>"<checked> /></div>\r\n			<phrase><a href="<url>&amp;start=<start>&amp;limit=<limitval>&amp;orderby=<currentorderby>&amp;search=<currentsearch>&amp;cmd=edit&amp;<get>=<id>">[edit]</a> [verticalbar] <a href="<url>&amp;start=<start>&amp;limit=<limitval>&amp;orderby=<currentorderby>&amp;search=<currentsearch>&amp;cmd=delete&amp;<get>=<id>">[delete]</a> [verticalbar] <a href="<url>&amp;start=<start>&amp;limit=<limitval>&amp;orderby=<currentorderby>&amp;search=<currentsearch>&amp;cmd=add&amp;<get>=<id>">[clone]</a></div>\r\n			<div class="info2"><title></div><br clear="all" />\r\n</div>\r\n	</div>'),
(3, 'login', '<message>\r\n<form action="#" method="post">\r\n<label>[username][colon] <input type="text" name="suit_username" /></label>\r\n<label>[password][colon] <input type="password" name="suit_password" /></label>\r\n<input type="submit" name="suit_login" value="Submit" /> | \r\n<a href="{path_url}/index.php?page=register">[register]</a> | \r\n<a href="{path_url}/index.php?page=lostpassword">[lostpassword]</a>\r\n</form>'),
(954, 'admin_notes', '{admin_protect}{header}\r\n	<div class="section">\r\n		<h2>[notes]</h2>\r\n		<form action="#" method="post">\r\n		<textarea name="content" rows="20" cols="100" style="width: 100%"><notes></textarea>\r\n		<br /><input type="submit" name="notes" value="[update]" />\r\n		</form>\r\n	</div>\r\n{footer}'),
(1284, 'list_phrases', '<a href="<url>&amp;language=<id>">[phrases]</a> [verticalbar] '),
(1285, 'list_errorlog_entry', '<fieldset class="suit_errorlog">\r\n	<legend><time> on <strong><location></strong></legend>\r\n		<p><error></p>\r\n</fieldset>'),
(1286, 'list_errorlog', '[errorlogwelcome]\r\n<form action="#" method="post">\r\n[entriesperpage][colon] <input type="text" name="limitval" value="<limitval>" />\r\n<input type="submit" name="limit" value="[list]" />\r\n</form>\r\n<form action="#" method="post">\r\n[search][colon] <input type="text" name="searchval" value="<currentsearch>" />\r\n<input type="submit" name="search" value="[search]" />\r\n</form>\r\n<form action="#" method="post">\r\n<p style="text-align: center;"><input type="submit" name="errorlog_clear" value="[clear]" /><languages></p>\r\n<list>\r\n<br />\r\n[count][colon] <count>  [verticalbar] [pages][colon] <First> <1> <2> <3> <4> <5> <Last>\r\n<br /><br />\r\n</form>\r\n<form action="#" method="post">\r\n[entriesperpage][colon] <input type="text" name="limitval" value="<limitval>" />\r\n<input type="submit" name="limit" value="[list]" />\r\n</form>'),
(1288, 'list_notdefault', '<a href="<url>&amp;start=<start>&amp;limit=<limitval>&amp;orderby=<currentorderby>&amp;search=<currentsearch>&amp;cmd=edit&amp;<get>=<id>&amp;cmd=default"><title></a>'),
(1320, 'community', '{header}\r\n	<div class="section">\r\n		<h2>[community]</h2>\r\n		<div class="community">\r\n			<div class="breadcrumb"><a href="/">License and Regist-</a> &gt; <a href="/">Problem Officer?</a> &gt; <a href="/">Get the $%$#% out</a></div>\r\n			<community>\r\n		</div>\r\n	</div>\r\n{footer}'),
(1324, 'community_list_posts', '<div class="new-buttons"><newtopic><newreply></div>\r\n\r\n\r\n<posts>'),
(1298, 'docs_404', 'Documentation page not found.'),
(1295, 'admin_docs', '{admin_protect}{header}\r\n	<div class="section">\r\n		<h2>[docs]</h2>\r\n		<admin_docs>\r\n	</div>\r\n{footer}'),
(1296, 'admin_docs_form', '<message>\r\n<form action="#" method="post">\r\n<input type="hidden" name="id" value="<id>" />\r\n[inputtitle][colon] <input type="text" name="title" value="<title>" />\r\n<br />[template][colon] <input type="text" name="template" value="<template>" />\r\n<br />[category][colon] <input type="text" name="category" value="<category>" />\r\n<br /><input type="submit" name="<name>" value="<value>" />\r\n</form>'),
(1297, 'docs_index', 'Hi!'),
(1321, 'community_forum', '<div class="msg2">\r\n	<div class="controls">\r\n		<div class="links">\r\n			<h3><a href="{path_url}/index.php?page=community&amp;forum=<id>"><title></a></h3>\r\n			<p><description></p>\r\n		</div>\r\n		<div class="info2"></div>\r\n	</div><br clear="all" />\r\n</div>'),
(1326, 'community_newtopic', '<div class="community-newtopic"><a href="{path_url}/index.php?page=community&amp;forum=<forum>&amp;cmd=newtopic">[newtopic]</a></div>'),
(1327, 'community_newreply', '<div class="community-newreply"><a href="{path_url}/index.php?page=community&amp;topic=<topic>&amp;cmd=newreply">[newreply]</a></div>'),
(1328, 'docs_credits', 'Brandon Evans\r\n<br />Chris Santiago\r\n<br />James Rhodes\r\n<br />Krishan Rodrigo\r\n<br />Andrew Vigotsky\r\n<br />Andreas Schleifer'),
(1331, 'community_form', '<message>\r\n<preview>\r\n<form action="#" method="post">\r\n<input type="hidden" name="id" value="<id>" />\r\n<input type="text" name="title" value="<title>" />\r\n<br /><textarea name="content" rows="20" cols="100" style="width: 100%"><content></textarea>\r\n<br /><input type="checkbox" name="smilies" value="1" checked /> [smilies]\r\n<br /><input type="checkbox" name="signature" value="1" checked /> [signature]\r\n<br /><input type="submit" name="<name>" value="[submit]" /> <input type="submit" name="preview" value="[preview]" />\r\n</form>'),
(1333, 'community_signature', '				<div class="signature"><signature></div>'),
(1334, 'phrases', '');

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
  `recover_string` text NOT NULL,
  `recover_password` text NOT NULL,
  `signature` text NOT NULL,
  `mod` tinyint(4) NOT NULL,
  `posts` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `admin`, `username`, `password`, `email`, `language`, `recover_string`, `recover_password`, `signature`, `mod`, `posts`) VALUES
(1, 1, 'Brandon', 'ca3f1fb6231b0e0d5003fb2a72094082', 'admin@brandonevans.org', -1, 'c52be', '436d5ac45b8b8f9a54e32f8cc0f2a38e', 'Regards,\r\nBrandon', 1, 62),
(2, 1, 'Faltzer', '8711a585663225d6911ad696336c94a1', 'faltzermaster@aol.com', 1, '', '', '', 1, 6),
(3, 0, 'Reshure25', '1ead18bfdad18bf6a909c2c725b9beaf', 'dynapie@gmail.com', 0, '', '', '', 0, 0),
(4, 0, 'blink182av', '5d187574218f65f9cfef2c488aed0f66', 'blink182av@gmail.com', 0, '', '', '', 0, 0),
(13, 0, 'krishan', '9225f888bc080f3f50e50e407b8809c2', 'krishan.rodrigo@gmail.com', 1, '', '', '', 0, 0),
(14, 0, 'Dave.Robo', 'd91c3376423fca73e194fcf7a6bb3ab0', 'kingken109@hotmail.com', 1, '', '', '', 0, 0);
