-- phpMyAdmin SQL Dump
-- version 2.11.9.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 09, 2009 at 04:54 PM
-- Server version: 5.1.30
-- PHP Version: 5.2.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `brandon_cape`
--

-- --------------------------------------------------------

--
-- Table structure for table `community_bbcode`
--

CREATE TABLE IF NOT EXISTS `community_bbcode` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag` text NOT NULL,
  `replacement` text NOT NULL,
  `equal` tinyint(4) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `style` text NOT NULL,
  `label` text NOT NULL,
  `message1` text NOT NULL,
  `default1` text NOT NULL,
  `message2` text NOT NULL,
  `default2` text NOT NULL,
  `loop` tinyint(4) NOT NULL,
  `separator` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `community_bbcode`
--

INSERT INTO `community_bbcode` (`id`, `tag`, `replacement`, `equal`, `type`, `style`, `label`, `message1`, `default1`, `message2`, `default2`, `loop`, `separator`) VALUES
(1, 'B', '<strong>(main)</strong>', 0, 1, 'font-weight: bold;', 'B', '', '', '', '', 0, ''),
(2, 'I', '<em>(main)</em>', 0, 1, 'font-style: italic;', 'I', '', '', '', '', 0, ''),
(3, 'U', '<span style="text-decoration: underline;">(main)</span>', 0, 1, 'text-decoration: underline;', 'U', '', '', '', '', 0, ''),
(4, 'S', '<del>(main)</del>', 0, 0, '', '', '', '', '', '', 0, ''),
(5, 'IMG', '<img src="(main)" alt="(main)" />', 0, 1, '', 'IMG', '', '', '', '', 0, ''),
(6, 'EMAIL', '<a href="mailto:(equal)" target="_blank">(main)</a>', 1, 2, '', 'EMAIL', 'Enter the email address', '', 'Type how it should be displayed', '', 0, ''),
(7, 'QUOTE', '<fieldset><legend>Quote - (equal)</legend>(main)</fieldset>', 1, 0, '', '', '', '', '', '', 0, ''),
(8, 'COLOR', '<span style="color: (equal);">(main)</span>', 1, 3, '', 'COLOR', '', '', '', '', 0, ''),
(9, 'ALIGN', '<div style="text-align: (equal);">(main)</div>', 1, 0, '', '', '', '', '', '', 0, ''),
(10, 'SIZE', '<span style="font-size: (equal)pt;">(main)</span>', 1, 3, '', 'SIZE', '', '', '', '', 0, ''),
(11, 'FONT', '<span style="font-family: (equal);">(main)</span>', 1, 3, '', 'FONT', '', '', '', '', 0, ''),
(12, 'QUOTE', '<fieldset><legend>Quote</legend>(main)</fieldset>', 0, 1, '', 'QUOTE', '', '', '', '', 0, ''),
(13, 'LIST', '<ul>(main)</ul>', 0, 2, '', 'LIST', 'Enter a list item. Click ''cancel'' or leave blank to end the list', '', '', '', 1, '[*]'),
(14, 'URL', '<a href="(equal)" target="_blank">(main)</a>', 1, 2, '', 'http://', 'Enter the complete URL for the hyperlink', 'http://', 'Enter the title of the webpage', 'My Webpage', 0, ''),
(15, 'CODE', '<fieldset><legend>Code</legend><code>(main)</code></fieldset>', 0, 1, '', 'CODE', '', '', '', '', 0, ''),
(16, 'LIST', '<ol>(main)</ol>', 1, 0, '', '', '', '', '', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `community_config`
--

CREATE TABLE IF NOT EXISTS `community_config` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `key` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `community_config`
--

INSERT INTO `community_config` (`id`, `key`, `value`) VALUES
(1, 'smileypath', 'http://www.domain.tld/url/to/cape/smilies'),
(2, 'cookiedomain', ''),
(3, 'cookielength', '2678400'),
(4, 'cookiepath', ''),
(5, 'cookieprefix', ''),
(6, 'timezone', 'America/New_York'),
(7, 'group', '1'),
(8, 'mod_rewrite', '1'),
(9, 'mod_rewriteurl', 'http://www.domain.tld/url/to/cape');

-- --------------------------------------------------------

--
-- Table structure for table `community_dropdown`
--

CREATE TABLE IF NOT EXISTS `community_dropdown` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `label` text COLLATE utf8_unicode_ci NOT NULL,
  `equal` text COLLATE utf8_unicode_ci NOT NULL,
  `style` text COLLATE utf8_unicode_ci NOT NULL,
  `parent` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `community_dropdown`
--

INSERT INTO `community_dropdown` (`id`, `label`, `equal`, `style`, `parent`) VALUES
(1, 'Blue', 'blue', 'color: blue;', 8),
(2, 'Red', 'red', 'color: red;', 8),
(3, 'Purple', 'purple', 'color: purple;', 8),
(4, 'Orange', 'orange', 'color: orange;', 8),
(5, 'Yellow', 'yellow', 'color: yellow;', 8),
(6, 'Gray', 'gray', 'color: gray;', 8),
(7, 'Green', 'green', 'color: green;', 8),
(8, 'Small', '1', '', 10),
(9, 'Large', '7', '', 10),
(10, 'Largest', '14', '', 10),
(11, 'Arial', 'Arial', 'font-family: Arial;', 11),
(12, 'Times', 'Times', 'font-family: Times;', 11),
(13, 'Courier', 'Courier', 'font-family: Courier;', 11),
(14, 'Impact', 'Impact', 'font-family: Impact;', 11),
(15, 'Geneva', 'Geneva', 'font-family: Geneva;', 11),
(16, 'Optima', 'Optima', 'font-family: Optima;', 11);

-- --------------------------------------------------------

--
-- Table structure for table `community_forums`
--

CREATE TABLE IF NOT EXISTS `community_forums` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `latest` bigint(20) NOT NULL,
  `topics` bigint(20) NOT NULL,
  `posts` bigint(20) NOT NULL,
  `parent` bigint(20) NOT NULL,
  `category` tinyint(20) NOT NULL,
  `locked` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `community_forums`
--

INSERT INTO `community_forums` (`id`, `title`, `description`, `latest`, `topics`, `posts`, `parent`, `category`, `locked`) VALUES
(1, 'Test Category', '', 0, 0, 0, 0, 1, 0),
(2, 'Test Forum', 'Test Description', 1, 1, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `community_groups`
--

CREATE TABLE IF NOT EXISTS `community_groups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `admin` tinyint(4) NOT NULL,
  `mod` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `community_groups`
--

INSERT INTO `community_groups` (`id`, `title`, `admin`, `mod`) VALUES
(1, 'Members', 0, 0),
(2, 'Administrators', 1, 1),
(3, 'Global Moderators', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `community_notes`
--

CREATE TABLE IF NOT EXISTS `community_notes` (
  `content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `community_notes`
--

INSERT INTO `community_notes` (`content`) VALUES
('$suit = new SUIT (''content'', ''code'', ''glue''). Actually, merge the directories into a config.\r\n\r\nirc://chat.freenode.net/#suitframework ?\r\n\r\nCheck if forums category exists before showing...kind of a thing that''ll be important when some form of administration exists.\r\n\r\nMass Delete Checkboxes, doesn''t matter because hidden for anybody but Admins.\r\n\r\nTopic Subscription. Auto subscribe private topics.\r\n\r\nThreaded Mode\r\n\r\nEmail through profile.\r\n\r\nAvatar Uploads.\r\n\r\nWord Wrap.\r\n\r\nAllow private topics in any forum, record the posts, record the replies, but it won''t change the latest post of the parent forum, for obvious reasons\r\n\r\nAnd a notification on tag (This will EVENTUALLY be replaced with an automatic subscription, but that''s way in the future)\r\n\r\nCAPE - Community Application Proven Effective.\r\n\r\nPrivate Topics.\r\n\r\nOverwrite system.\r\n\r\nHow it works.\r\n\r\narray_merge(compact(array_keys(get_defined_vars())), $suit->vars); Convert the remainder.');

-- --------------------------------------------------------

--
-- Table structure for table `community_posts`
--

CREATE TABLE IF NOT EXISTS `community_posts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `poster` bigint(20) NOT NULL,
  `time` int(10) NOT NULL,
  `parent` bigint(20) NOT NULL,
  `topic` tinyint(4) NOT NULL,
  `latest` bigint(20) NOT NULL,
  `re` bigint(20) NOT NULL,
  `replies` bigint(20) NOT NULL,
  `smilies` tinyint(4) NOT NULL,
  `signature` tinyint(4) NOT NULL,
  `modified_time` int(10) NOT NULL,
  `modified_user` bigint(20) NOT NULL,
  `locked` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `community_posts`
--

INSERT INTO `community_posts` (`id`, `title`, `content`, `poster`, `time`, `parent`, `topic`, `latest`, `re`, `replies`, `smilies`, `signature`, `modified_time`, `modified_user`, `locked`) VALUES
(1, 'Test Topic', 'Test Post', 1, 1251766878, 2, 1, 1, 2, 0, 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `community_private`
--

CREATE TABLE IF NOT EXISTS `community_private` (
  `id` bigint(20) NOT NULL,
  `user` bigint(20) NOT NULL,
  `parent` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `community_private`
--


-- --------------------------------------------------------

--
-- Table structure for table `community_smilies`
--

CREATE TABLE IF NOT EXISTS `community_smilies` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `code` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `community_smilies`
--

INSERT INTO `community_smilies` (`id`, `title`, `code`) VALUES
(1, 'smile', ':)'),
(2, 'biggrin', ':D'),
(3, 'cool', 'B)'),
(4, 'disgusted', '>_<'),
(5, 'mad', '>:('),
(6, 'sad', ':('),
(7, 'shocked', ':o'),
(8, 'tongue', ':P'),
(9, 'wink', ';)');

-- --------------------------------------------------------

--
-- Table structure for table `community_subscriptions`
--

CREATE TABLE IF NOT EXISTS `community_subscriptions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user` bigint(20) NOT NULL,
  `parent` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `community_subscriptions`
--


-- --------------------------------------------------------

--
-- Table structure for table `community_users`
--

CREATE TABLE IF NOT EXISTS `community_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `group` bigint(20) NOT NULL,
  `recover_string` text NOT NULL,
  `recover_password` text NOT NULL,
  `change_string` text NOT NULL,
  `change_email` text NOT NULL,
  `title` text NOT NULL,
  `avatar` text NOT NULL,
  `signature` text NOT NULL,
  `joined` int(20) NOT NULL,
  `timezone` text NOT NULL,
  `lastactivity` int(11) NOT NULL,
  `posts` bigint(20) NOT NULL,
  `aim` text NOT NULL,
  `icq` text NOT NULL,
  `yahoo` text NOT NULL,
  `msn` text NOT NULL,
  `homepage` text NOT NULL,
  `birthday` text NOT NULL,
  `location` text NOT NULL,
  `interests` text NOT NULL,
  `validate_string` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `community_users`
--

INSERT INTO `community_users` (`id`, `username`, `password`, `email`, `group`, `recover_string`, `recover_password`, `change_string`, `change_email`, `title`, `avatar`, `signature`, `joined`, `timezone`, `lastactivity`, `posts`, `aim`, `icq`, `yahoo`, `msn`, `homepage`, `birthday`, `location`, `interests`, `validate_string`) VALUES
(1, 'Test User', 'ca2c38ca101b867483dfbfb24c121209', '', 0, '', '', '', '', '', '', '', 1251766058, '', 1252532908, 1, '', '', '', '', '', '', '', '', '');
