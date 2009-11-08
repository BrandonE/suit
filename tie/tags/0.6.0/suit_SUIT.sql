-- phpMyAdmin SQL Dump
-- version 2.11.9.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 24, 2008 at 05:17 PM
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
-- Table structure for table `mw_archive`
--

CREATE TABLE IF NOT EXISTS `mw_archive` (
  `ar_namespace` int(11) NOT NULL default '0',
  `ar_title` varbinary(255) NOT NULL default '',
  `ar_text` mediumblob NOT NULL,
  `ar_comment` tinyblob NOT NULL,
  `ar_user` int(10) unsigned NOT NULL default '0',
  `ar_user_text` varbinary(255) NOT NULL,
  `ar_timestamp` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `ar_minor_edit` tinyint(4) NOT NULL default '0',
  `ar_flags` tinyblob NOT NULL,
  `ar_rev_id` int(10) unsigned default NULL,
  `ar_text_id` int(10) unsigned default NULL,
  `ar_deleted` tinyint(3) unsigned NOT NULL default '0',
  `ar_len` int(10) unsigned default NULL,
  `ar_page_id` int(10) unsigned default NULL,
  `ar_parent_id` int(10) unsigned default NULL,
  KEY `name_title_timestamp` (`ar_namespace`,`ar_title`,`ar_timestamp`),
  KEY `usertext_timestamp` (`ar_user_text`,`ar_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_archive`
--

INSERT INTO `mw_archive` (`ar_namespace`, `ar_title`, `ar_text`, `ar_comment`, `ar_user`, `ar_user_text`, `ar_timestamp`, `ar_minor_edit`, `ar_flags`, `ar_rev_id`, `ar_text_id`, `ar_deleted`, `ar_len`, `ar_page_id`, `ar_parent_id`) VALUES
(0, 'SUIT_Group', '', 0x4e657720706167653a205b687474703a2f2f7777772e6272616e646f6e6576616e732e6f72672f204272616e646f6e204576616e735d205b687474703a2f2f7777772e66616c747a65722e6e65742f2043687269732053616e746961676f202846616c747a6572295d20416e64726577205669676f74736b792028626c696e6b313832617629204e69656c732057686174277320686973206c617374206e616d65202852657368757265323529, 1, 'Brandon', '20081122230348', 0, '', 5, 5, 0, 163, 3, NULL),
(0, 'SUIT_Group', '', '', 1, 'Brandon', '20081122230418', 0, '', 6, 6, 0, 187, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mw_category`
--

CREATE TABLE IF NOT EXISTS `mw_category` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `cat_title` varbinary(255) NOT NULL,
  `cat_pages` int(11) NOT NULL default '0',
  `cat_subcats` int(11) NOT NULL default '0',
  `cat_files` int(11) NOT NULL default '0',
  `cat_hidden` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cat_id`),
  UNIQUE KEY `cat_title` (`cat_title`),
  KEY `cat_pages` (`cat_pages`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mw_category`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_categorylinks`
--

CREATE TABLE IF NOT EXISTS `mw_categorylinks` (
  `cl_from` int(10) unsigned NOT NULL default '0',
  `cl_to` varbinary(255) NOT NULL default '',
  `cl_sortkey` varbinary(70) NOT NULL default '',
  `cl_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  UNIQUE KEY `cl_from` (`cl_from`,`cl_to`),
  KEY `cl_sortkey` (`cl_to`,`cl_sortkey`,`cl_from`),
  KEY `cl_timestamp` (`cl_to`,`cl_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_categorylinks`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_externallinks`
--

CREATE TABLE IF NOT EXISTS `mw_externallinks` (
  `el_from` int(10) unsigned NOT NULL default '0',
  `el_to` blob NOT NULL,
  `el_index` blob NOT NULL,
  KEY `el_from` (`el_from`,`el_to`(40)),
  KEY `el_to` (`el_to`(60),`el_from`),
  KEY `el_index` (`el_index`(60))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_externallinks`
--

INSERT INTO `mw_externallinks` (`el_from`, `el_to`, `el_index`) VALUES
(4, 0x687474703a2f2f7777772e726573687572652e636f6d2f, 0x687474703a2f2f636f6d2e726573687572652e7777772e2f),
(4, 0x687474703a2f2f7777772e6272616e646f6e6576616e732e6f72672f, 0x687474703a2f2f6f72672e6272616e646f6e6576616e732e7777772e2f),
(4, 0x687474703a2f2f7777772e66616c747a65722e6e65742f, 0x687474703a2f2f6e65742e66616c747a65722e7777772e2f);

-- --------------------------------------------------------

--
-- Table structure for table `mw_filearchive`
--

CREATE TABLE IF NOT EXISTS `mw_filearchive` (
  `fa_id` int(11) NOT NULL auto_increment,
  `fa_name` varbinary(255) NOT NULL default '',
  `fa_archive_name` varbinary(255) default '',
  `fa_storage_group` varbinary(16) default NULL,
  `fa_storage_key` varbinary(64) default '',
  `fa_deleted_user` int(11) default NULL,
  `fa_deleted_timestamp` binary(14) default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `fa_deleted_reason` blob,
  `fa_size` int(10) unsigned default '0',
  `fa_width` int(11) default '0',
  `fa_height` int(11) default '0',
  `fa_metadata` mediumblob,
  `fa_bits` int(11) default '0',
  `fa_media_type` enum('UNKNOWN','BITMAP','DRAWING','AUDIO','VIDEO','MULTIMEDIA','OFFICE','TEXT','EXECUTABLE','ARCHIVE') character set binary default NULL,
  `fa_major_mime` enum('unknown','application','audio','image','text','video','message','model','multipart') character set binary default 'unknown',
  `fa_minor_mime` varbinary(32) default 'unknown',
  `fa_description` tinyblob,
  `fa_user` int(10) unsigned default '0',
  `fa_user_text` varbinary(255) default NULL,
  `fa_timestamp` binary(14) default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `fa_deleted` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fa_id`),
  KEY `fa_name` (`fa_name`,`fa_timestamp`),
  KEY `fa_storage_group` (`fa_storage_group`,`fa_storage_key`),
  KEY `fa_deleted_timestamp` (`fa_deleted_timestamp`),
  KEY `fa_user_timestamp` (`fa_user_text`,`fa_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mw_filearchive`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_hitcounter`
--

CREATE TABLE IF NOT EXISTS `mw_hitcounter` (
  `hc_id` int(10) unsigned NOT NULL
) ENGINE=MEMORY DEFAULT CHARSET=utf8 MAX_ROWS=25000;

--
-- Dumping data for table `mw_hitcounter`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_image`
--

CREATE TABLE IF NOT EXISTS `mw_image` (
  `img_name` varbinary(255) NOT NULL default '',
  `img_size` int(10) unsigned NOT NULL default '0',
  `img_width` int(11) NOT NULL default '0',
  `img_height` int(11) NOT NULL default '0',
  `img_metadata` mediumblob NOT NULL,
  `img_bits` int(11) NOT NULL default '0',
  `img_media_type` enum('UNKNOWN','BITMAP','DRAWING','AUDIO','VIDEO','MULTIMEDIA','OFFICE','TEXT','EXECUTABLE','ARCHIVE') character set binary default NULL,
  `img_major_mime` enum('unknown','application','audio','image','text','video','message','model','multipart') character set binary NOT NULL default 'unknown',
  `img_minor_mime` varbinary(32) NOT NULL default 'unknown',
  `img_description` tinyblob NOT NULL,
  `img_user` int(10) unsigned NOT NULL default '0',
  `img_user_text` varbinary(255) NOT NULL,
  `img_timestamp` varbinary(14) NOT NULL default '',
  `img_sha1` varbinary(32) NOT NULL default '',
  PRIMARY KEY  (`img_name`),
  KEY `img_usertext_timestamp` (`img_user_text`,`img_timestamp`),
  KEY `img_size` (`img_size`),
  KEY `img_timestamp` (`img_timestamp`),
  KEY `img_sha1` (`img_sha1`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_image`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_imagelinks`
--

CREATE TABLE IF NOT EXISTS `mw_imagelinks` (
  `il_from` int(10) unsigned NOT NULL default '0',
  `il_to` varbinary(255) NOT NULL default '',
  UNIQUE KEY `il_from` (`il_from`,`il_to`),
  KEY `il_to` (`il_to`,`il_from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_imagelinks`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_interwiki`
--

CREATE TABLE IF NOT EXISTS `mw_interwiki` (
  `iw_prefix` varbinary(32) NOT NULL,
  `iw_url` blob NOT NULL,
  `iw_local` tinyint(1) NOT NULL,
  `iw_trans` tinyint(4) NOT NULL default '0',
  UNIQUE KEY `iw_prefix` (`iw_prefix`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_interwiki`
--

INSERT INTO `mw_interwiki` (`iw_prefix`, `iw_url`, `iw_local`, `iw_trans`) VALUES
('acronym', 0x687474703a2f2f7777772e6163726f6e796d66696e6465722e636f6d2f61662d71756572792e6173703f537472696e673d6578616374264163726f6e796d3d2431, 0, 0),
('advogato', 0x687474703a2f2f7777772e6164766f6761746f2e6f72672f2431, 0, 0),
('annotationwiki', 0x687474703a2f2f7777772e7365656477696b692e636f6d2f706167652e63666d3f77696b6969643d33363826646f633d2431, 0, 0),
('arxiv', 0x687474703a2f2f7777772e61727869762e6f72672f6162732f2431, 0, 0),
('c2find', 0x687474703a2f2f63322e636f6d2f6367692f77696b693f46696e64506167652676616c75653d2431, 0, 0),
('cache', 0x687474703a2f2f7777772e676f6f676c652e636f6d2f7365617263683f713d63616368653a2431, 0, 0),
('codersbase', 0x687474703a2f2f7777772e636f64657273626173652e636f6d2f696e6465782e7068702f2431, 0, 0),
('commons', 0x687474703a2f2f636f6d6d6f6e732e77696b696d656469612e6f72672f77696b692f2431, 0, 0),
('corpknowpedia', 0x687474703a2f2f636f72706b6e6f7770656469612e6f72672f77696b692f696e6465782e7068702f2431, 0, 0),
('dictionary', 0x687474703a2f2f7777772e646963742e6f72672f62696e2f446963743f44617461626173653d2a26466f726d3d44696374312653747261746567793d2a2651756572793d2431, 0, 0),
('disinfopedia', 0x687474703a2f2f7777772e646973696e666f70656469612e6f72672f77696b692e7068746d6c3f7469746c653d2431, 0, 0),
('docbook', 0x687474703a2f2f77696b692e646f63626f6f6b2e6f72672f746f7069632f2431, 0, 0),
('drumcorpswiki', 0x687474703a2f2f7777772e6472756d636f72707377696b692e636f6d2f696e6465782e7068702f2431, 0, 0),
('dwjwiki', 0x687474703a2f2f7777772e737562657269632e6e65742f6367692d62696e2f64776a2f77696b692e6367693f2431, 0, 0),
('efnetceewiki', 0x687474703a2f2f7075726c2e6e65742f77696b692f632f2431, 0, 0),
('efnetcppwiki', 0x687474703a2f2f7075726c2e6e65742f77696b692f6370702f2431, 0, 0),
('efnetpythonwiki', 0x687474703a2f2f7075726c2e6e65742f77696b692f707974686f6e2f2431, 0, 0),
('efnetxmlwiki', 0x687474703a2f2f7075726c2e6e65742f77696b692f786d6c2f2431, 0, 0),
('eljwiki', 0x687474703a2f2f656c6a2e736f75726365666f7267652e6e65742f70687077696b692f696e6465782e7068702f2431, 0, 0),
('emacswiki', 0x687474703a2f2f7777772e656d61637377696b692e6f72672f6367692d62696e2f77696b692e706c3f2431, 0, 0),
('elibre', 0x687474703a2f2f656e6369636c6f70656469612e75732e65732f696e6465782e7068702f2431, 0, 0),
('eokulturcentro', 0x687474703a2f2f6573706572616e746f2e746f756c6f7573652e667265652e66722f77616b6b612e7068703f77696b693d2431, 0, 0),
('foldoc', 0x687474703a2f2f666f6c646f632e6f72672f3f2431, 0, 0),
('foxwiki', 0x687474703a2f2f666f782e77696b69732e636f6d2f77632e646c6c3f57696b697e2431, 0, 0),
('freebsdman', 0x687474703a2f2f7777772e467265654253442e6f72672f6367692f6d616e2e6367693f6170726f706f733d312671756572793d2431, 0, 0),
('gej', 0x687474703a2f2f7777772e6573706572616e746f2e64652f6367692d62696e2f616b746976696b696f2f77696b692e706c3f2431, 0, 0),
('gentoo-wiki', 0x687474703a2f2f67656e746f6f2d77696b692e636f6d2f2431, 0, 0),
('google', 0x687474703a2f2f7777772e676f6f676c652e636f6d2f7365617263683f713d2431, 0, 0),
('googlegroups', 0x687474703a2f2f67726f7570732e676f6f676c652e636f6d2f67726f7570733f713d2431, 0, 0),
('gotamac', 0x687474703a2f2f7777772e676f742d612d6d61632e6f72672f2431, 0, 0),
('hammondwiki', 0x687474703a2f2f7777772e64616972696b692e6f72672f48616d6d6f6e6457696b692f2431, 0, 0),
('hewikisource', 0x687474703a2f2f68652e77696b69736f757263652e6f72672f77696b692f2431, 1, 0),
('hrwiki', 0x687474703a2f2f7777772e687277696b692e6f72672f696e6465782e7068702f2431, 0, 0),
('imdb', 0x687474703a2f2f75732e696d64622e636f6d2f5469746c653f2431, 0, 0),
('infosecpedia', 0x687474703a2f2f7777772e696e666f73656370656469612e6f72672f70656469612f696e6465782e7068702f2431, 0, 0),
('jargonfile', 0x687474703a2f2f73756e69722e6f72672f617070732f6d6574612e706c3f77696b693d4a6172676f6e46696c652672656469726563743d2431, 0, 0),
('jspwiki', 0x687474703a2f2f7777772e6a737077696b692e6f72672f77696b692f2431, 0, 0),
('keiki', 0x687474703a2f2f6b65692e6b692f656e2f2431, 0, 0),
('kmwiki', 0x687474703a2f2f6b6d77696b692e77696b697370616365732e636f6d2f2431, 0, 0),
('linuxwiki', 0x687474703a2f2f6c696e757877696b692e64652f2431, 0, 0),
('lojban', 0x687474703a2f2f7777772e6c6f6a62616e2e6f72672f74696b692f74696b692d696e6465782e7068703f706167653d2431, 0, 0),
('lqwiki', 0x687474703a2f2f77696b692e6c696e75787175657374696f6e732e6f72672f77696b692f2431, 0, 0),
('lugkr', 0x687474703a2f2f6c75672d6b722e736f75726365666f7267652e6e65742f6367692d62696e2f6c756777696b692e706c3f2431, 0, 0),
('mathsongswiki', 0x687474703a2f2f5365656457696b692e636f6d2f706167652e63666d3f77696b6969643d32333726646f633d2431, 0, 0),
('meatball', 0x687474703a2f2f7777772e7573656d6f642e636f6d2f6367692d62696e2f6d622e706c3f2431, 0, 0),
('mediazilla', 0x687474703a2f2f6275677a696c6c612e77696b6970656469612e6f72672f2431, 1, 0),
('mediawikiwiki', 0x687474703a2f2f7777772e6d6564696177696b692e6f72672f77696b692f2431, 0, 0),
('memoryalpha', 0x687474703a2f2f7777772e6d656d6f72792d616c7068612e6f72672f656e2f696e6465782e7068702f2431, 0, 0),
('metawiki', 0x687474703a2f2f73756e69722e6f72672f617070732f6d6574612e706c3f2431, 0, 0),
('metawikipedia', 0x687474703a2f2f6d6574612e77696b696d656469612e6f72672f77696b692f2431, 0, 0),
('moinmoin', 0x687474703a2f2f7075726c2e6e65742f77696b692f6d6f696e2f2431, 0, 0),
('mozillawiki', 0x687474703a2f2f77696b692e6d6f7a696c6c612e6f72672f696e6465782e7068702f2431, 0, 0),
('oeis', 0x687474703a2f2f7777772e72657365617263682e6174742e636f6d2f6367692d62696e2f6163636573732e6367692f61732f6e6a61732f73657175656e6365732f656973412e6367693f416e756d3d2431, 0, 0),
('openfacts', 0x687474703a2f2f6f70656e66616374732e6265726c696f732e64652f696e6465782e7068746d6c3f7469746c653d2431, 0, 0),
('openwiki', 0x687474703a2f2f6f70656e77696b692e636f6d2f3f2431, 0, 0),
('orgpatterns', 0x687474703a2f2f7777772e62656c6c2d6c6162732e636f6d2f6367692d757365722f4f72675061747465726e732f4f72675061747465726e733f2431, 0, 0),
('patwiki', 0x687474703a2f2f67617573732e666669692e6f72672f2431, 0, 0),
('pmeg', 0x687474703a2f2f7777772e62657274696c6f772e636f6d2f706d65672f24312e706870, 0, 0),
('ppr', 0x687474703a2f2f63322e636f6d2f6367692f77696b693f2431, 0, 0),
('pythoninfo', 0x687474703a2f2f77696b692e707974686f6e2e6f72672f6d6f696e2f2431, 0, 0),
('rfc', 0x687474703a2f2f7777772e7266632d656469746f722e6f72672f7266632f72666324312e747874, 0, 0),
('s23wiki', 0x687474703a2f2f69732d726f6f742e64652f77696b692f696e6465782e7068702f2431, 0, 0),
('seattlewiki', 0x687474703a2f2f73656174746c652e77696b69612e636f6d2f77696b692f2431, 0, 0),
('seattlewireless', 0x687474703a2f2f73656174746c65776972656c6573732e6e65742f3f2431, 0, 0),
('senseislibrary', 0x687474703a2f2f73656e736569732e786d702e6e65742f3f2431, 0, 0),
('slashdot', 0x687474703a2f2f736c617368646f742e6f72672f61727469636c652e706c3f7369643d2431, 0, 0),
('sourceforge', 0x687474703a2f2f736f75726365666f7267652e6e65742f2431, 0, 0),
('squeak', 0x687474703a2f2f77696b692e73717565616b2e6f72672f73717565616b2f2431, 0, 0),
('susning', 0x687474703a2f2f7777772e7375736e696e672e6e752f2431, 0, 0),
('svgwiki', 0x687474703a2f2f77696b692e7376672e6f72672f2431, 0, 0),
('tavi', 0x687474703a2f2f746176692e736f75726365666f7267652e6e65742f2431, 0, 0),
('tejo', 0x687474703a2f2f7777772e74656a6f2e6f72672f76696b696f2f2431, 0, 0),
('tmbw', 0x687474703a2f2f7777772e746d62772e6e65742f77696b692f2431, 0, 0),
('tmnet', 0x687474703a2f2f7777772e746563686e6f6d616e69666573746f732e6e65742f3f2431, 0, 0),
('tmwiki', 0x687474703a2f2f7777772e45617379546f7069634d6170732e636f6d2f3f706167653d2431, 0, 0),
('theopedia', 0x687474703a2f2f7777772e7468656f70656469612e636f6d2f2431, 0, 0),
('twiki', 0x687474703a2f2f7477696b692e6f72672f6367692d62696e2f766965772f2431, 0, 0),
('uea', 0x687474703a2f2f7777772e74656a6f2e6f72672f7565612f2431, 0, 0),
('unreal', 0x687474703a2f2f77696b692e6265796f6e64756e7265616c2e636f6d2f77696b692f2431, 0, 0),
('usemod', 0x687474703a2f2f7777772e7573656d6f642e636f6d2f6367692d62696e2f77696b692e706c3f2431, 0, 0),
('vinismo', 0x687474703a2f2f76696e69736d6f2e636f6d2f656e2f2431, 0, 0),
('webseitzwiki', 0x687474703a2f2f776562736569747a2e666c7578656e742e636f6d2f77696b692f2431, 0, 0),
('why', 0x687474703a2f2f636c75626c65742e636f6d2f632f632f7768793f2431, 0, 0),
('wiki', 0x687474703a2f2f63322e636f6d2f6367692f77696b693f2431, 0, 0),
('wikia', 0x687474703a2f2f7777772e77696b69612e636f6d2f77696b692f2431, 0, 0),
('wikibooks', 0x687474703a2f2f656e2e77696b69626f6f6b732e6f72672f77696b692f2431, 1, 0),
('wikicities', 0x687474703a2f2f7777772e77696b696369746965732e636f6d2f696e6465782e7068702f2431, 0, 0),
('wikif1', 0x687474703a2f2f7777772e77696b6966312e6f72672f2431, 0, 0),
('wikihow', 0x687474703a2f2f7777772e77696b69686f772e636f6d2f2431, 0, 0),
('wikinfo', 0x687474703a2f2f7777772e77696b696e666f2e6f72672f696e6465782e7068702f2431, 0, 0),
('wikimedia', 0x687474703a2f2f77696b696d65646961666f756e646174696f6e2e6f72672f77696b692f2431, 0, 0),
('wikiquote', 0x687474703a2f2f656e2e77696b6971756f74652e6f72672f77696b692f2431, 1, 0),
('wikinews', 0x687474703a2f2f656e2e77696b696e6577732e6f72672f77696b692f2431, 1, 0),
('wikisource', 0x687474703a2f2f736f75726365732e77696b6970656469612e6f72672f77696b692f2431, 1, 0),
('wikispecies', 0x687474703a2f2f737065636965732e77696b6970656469612e6f72672f77696b692f2431, 1, 0),
('wikitravel', 0x687474703a2f2f77696b6974726176656c2e6f72672f656e2f2431, 0, 0),
('wiktionary', 0x687474703a2f2f656e2e77696b74696f6e6172792e6f72672f77696b692f2431, 1, 0),
('wikipedia', 0x687474703a2f2f656e2e77696b6970656469612e6f72672f77696b692f2431, 1, 0),
('wlug', 0x687474703a2f2f7777772e776c75672e6f72672e6e7a2f2431, 0, 0),
('zwiki', 0x687474703a2f2f7a77696b692e6f72672f2431, 0, 0),
('zzz wiki', 0x687474703a2f2f77696b692e7a7a7a2e65652f696e6465782e7068702f2431, 0, 0),
('wikt', 0x687474703a2f2f656e2e77696b74696f6e6172792e6f72672f77696b692f2431, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mw_ipblocks`
--

CREATE TABLE IF NOT EXISTS `mw_ipblocks` (
  `ipb_id` int(11) NOT NULL auto_increment,
  `ipb_address` tinyblob NOT NULL,
  `ipb_user` int(10) unsigned NOT NULL default '0',
  `ipb_by` int(10) unsigned NOT NULL default '0',
  `ipb_by_text` varbinary(255) NOT NULL default '',
  `ipb_reason` tinyblob NOT NULL,
  `ipb_timestamp` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `ipb_auto` tinyint(1) NOT NULL default '0',
  `ipb_anon_only` tinyint(1) NOT NULL default '0',
  `ipb_create_account` tinyint(1) NOT NULL default '1',
  `ipb_enable_autoblock` tinyint(1) NOT NULL default '1',
  `ipb_expiry` varbinary(14) NOT NULL default '',
  `ipb_range_start` tinyblob NOT NULL,
  `ipb_range_end` tinyblob NOT NULL,
  `ipb_deleted` tinyint(1) NOT NULL default '0',
  `ipb_block_email` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ipb_id`),
  UNIQUE KEY `ipb_address` (`ipb_address`(255),`ipb_user`,`ipb_auto`,`ipb_anon_only`),
  KEY `ipb_user` (`ipb_user`),
  KEY `ipb_range` (`ipb_range_start`(8),`ipb_range_end`(8)),
  KEY `ipb_timestamp` (`ipb_timestamp`),
  KEY `ipb_expiry` (`ipb_expiry`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mw_ipblocks`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_job`
--

CREATE TABLE IF NOT EXISTS `mw_job` (
  `job_id` int(10) unsigned NOT NULL auto_increment,
  `job_cmd` varbinary(60) NOT NULL default '',
  `job_namespace` int(11) NOT NULL,
  `job_title` varbinary(255) NOT NULL,
  `job_params` blob NOT NULL,
  PRIMARY KEY  (`job_id`),
  KEY `job_cmd` (`job_cmd`,`job_namespace`,`job_title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mw_job`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_langlinks`
--

CREATE TABLE IF NOT EXISTS `mw_langlinks` (
  `ll_from` int(10) unsigned NOT NULL default '0',
  `ll_lang` varbinary(20) NOT NULL default '',
  `ll_title` varbinary(255) NOT NULL default '',
  UNIQUE KEY `ll_from` (`ll_from`,`ll_lang`),
  KEY `ll_lang` (`ll_lang`,`ll_title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_langlinks`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_logging`
--

CREATE TABLE IF NOT EXISTS `mw_logging` (
  `log_id` int(10) unsigned NOT NULL auto_increment,
  `log_type` varbinary(10) NOT NULL default '',
  `log_action` varbinary(10) NOT NULL default '',
  `log_timestamp` binary(14) NOT NULL default '19700101000000',
  `log_user` int(10) unsigned NOT NULL default '0',
  `log_namespace` int(11) NOT NULL default '0',
  `log_title` varbinary(255) NOT NULL default '',
  `log_comment` varbinary(255) NOT NULL default '',
  `log_params` blob NOT NULL,
  `log_deleted` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`log_id`),
  KEY `type_time` (`log_type`,`log_timestamp`),
  KEY `user_time` (`log_user`,`log_timestamp`),
  KEY `page_time` (`log_namespace`,`log_title`,`log_timestamp`),
  KEY `times` (`log_timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `mw_logging`
--

INSERT INTO `mw_logging` (`log_id`, `log_type`, `log_action`, `log_timestamp`, `log_user`, `log_namespace`, `log_title`, `log_comment`, `log_params`, `log_deleted`) VALUES
(1, 'patrol', 'patrol', '20081108163045', 1, 0, 'SUIT_Functions', '', 0x320a300a31, 0),
(2, 'patrol', 'patrol', '20081122230348', 1, 0, 'SUIT_Group', '', 0x350a300a31, 0),
(3, 'patrol', 'patrol', '20081122230418', 1, 0, 'SUIT_Group', '', 0x360a350a31, 0),
(4, 'patrol', 'patrol', '20081122230541', 1, 0, 'The_SUIT_Group', '', 0x370a300a31, 0),
(5, 'delete', 'delete', '20081122230613', 1, 0, 'SUIT_Group', 'Author request: content was: ''[http://www.brandonevans.org/ Brandon Evans]<br /> [http://www.faltzer.net/ Chris Santiago (Faltzer)]<br /> Andrew Vigotsky (blink182av)<br /> Niels What''s his la...'' (and the only contributor was ''[[Special:Contributions/Bra', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mw_math`
--

CREATE TABLE IF NOT EXISTS `mw_math` (
  `math_inputhash` varbinary(16) NOT NULL,
  `math_outputhash` varbinary(16) NOT NULL,
  `math_html_conservativeness` tinyint(4) NOT NULL,
  `math_html` blob,
  `math_mathml` blob,
  UNIQUE KEY `math_inputhash` (`math_inputhash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_math`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_objectcache`
--

CREATE TABLE IF NOT EXISTS `mw_objectcache` (
  `keyname` varbinary(255) NOT NULL default '',
  `value` mediumblob,
  `exptime` datetime default NULL,
  UNIQUE KEY `keyname` (`keyname`),
  KEY `exptime` (`exptime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_objectcache`
--

INSERT INTO `mw_objectcache` (`keyname`, `value`, `exptime`) VALUES
('suit_SUIT-mw_:rcfeed:atom:limit:50:minor:', 0xed5c5b6fdb36147e4e7e05e70171d2d692e55cab580ebaa01d0ab41dd6a42bb0202868e9d82622532a49d975b1e73def37ee978ca4e48b9c8b65276d19790f4124913a3cdff9ce213fd271b8eb3c771acfdd4af3e44b3f4403609c44d4ab3856bd8280fa514068d7ab24a2533baa9cb43675b71a17a310780f4020318ac1ab08f8226c9ff30aea31e878959e10b16bdb4372452c9e10d161b80fc3885d597ed4b7f915a1dc9657fd88da1d80c092af9e38077b7a04f500c951289fda190eade1ae15b1aedda8d7f7ed1722ea57541f37c4ca3da095d6e6c6469304adbb472634802f56dc8b4f041121786731f80487ee7bf0818ad39eb4067c0bf7e363e58587e5384d5b5a55c6f51badb30fafcf11aaa1f40de4a7afa00ba0974d3beda23a87845e2106a157e110762a5998701c87c4c74286d856b69f4a08c542b682e3157bce131c0a60140ba8ccb2d6134bfb60df387a365e12077288a025893aaa394eadb1775e77dcfd437777ffcfa63d6e553d79d24e0376ceb07f85440f503fe2423a9b0bad887493720a112aaf09473a699af6c48232d7050a4c0267adb71010fc51f5772c67d76a34ed69dbe6e646535a67a3d512e6bc079f540a7cfa954549ac031e904ec73bd297511890c08b190ce6b246be8674e6e8d76e4e9407a367495753de6e62ae71ded875eb87eefe419eb994bc7e1fb351e6aaf6b2b5158ae378ab2b8ed585adaf369569752b703b04a4270e6feb731289e3b624bd2b5da341cd8fc288b968d823028e517ad70e65fb71da555bca0cc956e4879873afaae0d4a41757c0aac8beab931f5121595fd0eb1ea60443031c922ef5aa228aabe3a6b42d5090788c6563a37a9f18fcfbf73fbf850130993003a2266a1d68117ca3e1de67a320cc51d4413a199ea14603bd8b06d06f4b3f54aaccf990deb2ec368dcd3827665c4b4769a4bfb200a7373acab22c80465357dec87bd47067c77a6883b38e5f77fb7a9aa85634f7beec7973bc5df433c05ca05147a6528d93afe022dec761086c26f8ca5c4006eaf2626619ecc869e22b308b82b0d1698fc9c9f00c5341703742dbafd2c69d4b5de2d2409bc93455d7637c99c5eb6e971ba099bcbea0018321fa837423c1af4668bbad5603e7a881073b8f8cc10784b22c57b52500763a784580ef08841c7dec6151e5484910e98c40542ec068fbbdd4c20983c6fece4a0c3d5d0280dff15704a02ea5ecbe3633a6320b4d2f6b84aac9726a64b63a590a550b8e34b3a4cde5e2b39a1397b38315cc9e9f6a356d124de49f2b15a45659219aea5334d6a7a8569bbc6b6bc1a26e95ccd45a47eb359c889e94914d85ac75706439875260d6f72de7a869eb674d3bebb2d1b433a1793fc9799386abdf2d37bfb7d42ce2e2229979e03abb4bcacc0042907d519a251717aa01a700d2478b1cd53fbeda8e7972cb2026cf18042a589e73839aa030cc9ea626d39b69c4d1368d041ac9adf1901121a5e2ce3489a7bd748a619dd89799b9173a6d24419f13e0c24599d04443cc5d549d2dc236c33488280c30e57a538c7e499fa097ead18d8b237a9835162d3bd1a31b2606cbb2aa685b7aac3776110d471a2c23ed44d6a8022cf15e8c3799a7e3264913b725d299dd456ec7d1d6f390742ec65dd09ddaf9cdc8dc6c389e41ee28f02caedfaab46fdba21d3e9edde4bcab8bca7cdfdd73962cf331a52e2a5311dca61eee99d8f70dd1d6f85e79f170ba7c627215a13879797911f6e36a5b17cbab84eae5854f0b66afc0d23d79ed3b2edf055cbdb5b69dc3f3fa9e5ac2f7965dc2ff3f292ae349914e8667c839fc512745ce439f14398fe3a4283f7b187c5e50d85133e3ec79e83462803ccfe0181772d2ccd3962717177e08987e882f2f0d3e537992ee39573bd150dc5819cabf664f378a203796b630c2c1db28484228397353a039f20ae23798bfee4bc622567af6529873dc15c06e32735d085ed3f233a761ce33b718bbb1cc71101f3830423b51c9c9cb90be964873fc158d80b16af08dc49ce0aef98ab098a3c6964af6490e9c62bf5762853166c9cae1cd954cf148184ca618035d0b2a2768e7882c16056369e46b4523bf85c6a2513096c618330e6b44640e6f8ecae29130568fbc1d9dfdfec6783152c04b63cbc58f28055f94b75034395606337f405500bab1bc41b94f3752d6e0dad9c662d8e632c67d1c977845ca28d328f39c2d066e2c691202b0d2cf8e29ca1c6905801b4bdae704d8a8ec9c699039ca16c33696310ee11a889014e5dc766b21706349132ca13e16a55fd2c63873c415026f2c75095d0f3532c699a3ae107863b7c4e7d08f439978dcf86d71414f8d2d1242fd3009e01529f31f014c48b266e0ce29c162613096c735f9b065ca64d93f6d19235d0f2e2770e73f6e2914066379d4a7d3ebc4640ef0f583fac7cd265fafaae4b75465d13018cd630ffa6b43a2c27a8dc1850130963e06129bbf16ec655073e41581ff5dbf599d7da1ecce2f67e97f1dd5aa1cff07, '2038-01-19 03:14:07'),
('suit_SUIT-mw_:pcache:idhash:1-0!1!0!!en!2', 0x7554db6eda4010cdb3bf62d887aa950a78dd90cb72e9436f414a13a4d0e6315aec01af62ef5abbeb008df8f7ce1a904cd45ac2d8733b67cecef85ef044b099b40eed7dedabda339170f1eac440b0728e1bcf864e5c5c72c146d564b4502bba4d1e7e4de7f0d5a47589da4baf8c1ef51713fa913b1af529b09a7c3716a406a5bd35599d8620f00642ea47b0b8448b3ac560f239c248424ec631eb2b9de1a657e5d567af7c81e369abc03b595643d93c8e3153be79b798154a3f8f3983b490ce8d99c635837d366ba7c37b6d3c6cd1c3da2aef517f6093b67fd497931ecc73e5e0513d2bc8a58305a206745e2e0ae572cc60497d05c6556d2be310cc12b283124aaf0efd499d812c0ab30e26a9b75093bea1d7c01aa4f52a2dd09138606cb6f7a48698a845ed1b4d94ef354246a34eb70bd11dae6733285449d9162b637d34a37f6b52748e0869932155a8b51790f479dc5cd1cc38dfc54d15d8289d16350539f50705c4fd24bebee48304165b8f2e9a63591592a0a55d35adfc27eedba642edd40bb5df8c0c2c6bbd97f6001e07f0a8db9d447be60ff20503f8313e952969b7563e8767dc82ab957f0a9275cbf593a81aaf5019099f0bde8d3bbc13773aa83b49a3a857653889b282248eaf384fcef955f2890f20e08539e5e734b4b752af6ab9c25b1a0ac78652c4e275473e1ae1f20bf5b8325661db71191ca4be54dadd17d94fb952291b2e441c2ac64d16d19a13f811e3143e58af28ec375a475284575a1edebbe89db363897998c6e33a91a1d9aa40f64892764e1162f3e09aa56c4f261b06e76e77ac7638ae761749cb3ecd5a9ed0deb42441dad141a86f1b8f56cbe25f42d1c03de001baa5c49df941638d767b62bd41994d3d966fe9ec3f2837c6b4cb5f93e3515a4d9bf1c67a007c4b65664d85b4316dfe096977d6fe6a9d65ca51ebdb46e71372f3e3d09c1e1ebf882f391f7036dcfd05, '2008-11-25 18:23:15'),
('suit_SUIT-mw_:rcfeed:rss:limit:50:minor:', 0xed5c5d73da38147d6e7f85969d0949b7d8987c310e26d366db4e679aee6c936e1f32998eb02fe0c148ae2443d3d9e77ddedfb8bf6425d9060c693009db55a10f9948967c75cf3dbad691c070d7691eef1fba95d6e9e7618446c07848895771ac7a0501f16910929e574944b7d6ac9cb61feb6e352e6e22e07d0081c44d0c5e45c06761fb9c57509f41d7abf485885ddb1e8783d0e24928ba0c0f614cd9c0f2e9d0e68390705b968694d85d80c092b79e3a47077a04c6f9d48f86f2430e49b81bf813bb71c2228bb29e1df83644300422b8ed588e5d693f7ed4f2fb98108864f1514b842282f6c5fbd79708d5d03bf06557a43af480a32b20d72d3beda23a472119b4ef763d24017cb6e27e6c5fc4e0873872539b67a9c996ad6d286301709f85b19030da970cfb0324fa8086940bc48a6e08aa9bd4882824b21c72a4a3d2b2678d680fe51d09ee411b881c2aafa8961e10605850d63e8720c41f943119917dabd1b2a76da90d2e9e276114fc8a05b4cf29798a1a07e82d1da146bdde4475c73d6cbafb4df4eafc528d31db59dd1e0a18aa421edb4be9ba8eef2b469378269cabc5f354dfe7496b1f95b58fdada0e1ec62741d8ed7a4d5da45110065ecc60340d7431d23b913889777ae244156c5d7aac3aa9aac09d08909ebbdecea7848a938ea4a527072241cda711652e1af725be1394d63a916c3f49bb6a4b9921d98a7c1919ee559573b5216603605564dfd5c9a74448d697f47a8029c1d00847618f785541e36ade94b6050a128fb16c6c541f12837ffefafbb728002627f1285439aa032d82ff68b877d928087344bba8b1efd68fe57c6da8f90ac38ef4434dda391fd22acbaa696cf23931e35a3a4a23fd970538ade828cb0906844e5d7923eba8e1ce8eb56e83b38e2fbabd384d542b9abb5ff6bc3dde2efa19602ed0a82ba7528d875fc0457c88a308d84cf095b9201ca9e2559ec6e3b1d5c591f802cc22206c74d667f27175818908718fa2dd9769e3deb54e5869a0c3e43455e51c5f6671d1edcd066826afcf48c0608cfe087b54f0c10ddaeda8e7aad36ce0d1de77c6e01aa1acca556d0580dd2ebe27c0b721441c7de86351e5488904b53823229753b4fb4ecab18441e370ef5e0cfdb20200bfebdf13802a4ae5b7f0644c85109a166b21510fcba991d9ec6429542d1fd299256dae169ffb39713d3b58c9d9f353ada64da2890673a5c613c0088ef4d255739c9a945f13d555ab4deeb5b56051d50521f8a815271d2dc92eb0c8d7c354bfe925d23d3c4af55bde4deb24dff519681d78d4b49c63a90deb8796d394d6a72daaa352e64a5297d6c297381ab845e9d6b22756a4453b578d0bf2717dd2f136d9585f553606723b21204029eb5757aa01a79b9af4d2b261f59faf8c7a52878bc93506811ada736e510704c6d9d5d4645a994606ed122ad08ddc6d8d5928a4f4db9b4eca692f3d65b09ea8d799b96789e85325d53e25c0858b32e188c698bba83a9b541d864940098c30e17a67859ea757d00b75e9d6c50ead67cd44ab3eb8d12d896e595615ed4a8ff5568a92e8468365612791b35a019678aff23ddb59de2469e2b6443ab35b28ec203afab9229d8be53e4b77ea143717734fb7fc89b06ac21eb9cefe9d099bb1b1a6545d3d4dbfcd2eef78d574cda971d1264de6afadea0f9ca00f0dd14e5e575eac4f2f4f4cde47c04d6e5e5d1cad9aa387ee81f30d73f401cbe9cb84e81588af65499d589be6e9c18fd3981fa7315d543f902bd753e41cff5fa731ceba4f639cefe334a698e506efc94b3b6a669c3d0f9d5106c8f30c8e712927cd3cd1787275e54780c9fbf8fadae0738b27e93ef07ea7068a1b2b43f9e7ec094219e4c6d216511c9cd3208960c3999b022d905712bfc1fcf55e3046d9c6b397c29ce3ae04769399eb41f09a6c3e731ae63c73cbb11bcb1c07f19e030b49976e387919d2d7126981bfb21130560dbec9be7261bc222ce7a8b1a9927d5a0267d8ef6fb0c2c859b20a780b29533e1206932972a05b41e504ed1c91e5a2602c8d7cab68e45fa1b16c148ca531c68cc3161159c05ba0b27c248cd523e73717bfbf315e8c94f0d2d874f12921e08bcd4d144d8e95c12c1e5095806e2c6fb0d9a71b296bb070b6b11cb6b98c711fc71bbc226594699445ce96033796340901d8c63f1d539405d24a003796b44f09b09b4de74c832c50b61cb6b18c7188b64084a428e7b65b4b811b4b9a6009f1b1d8f8252dc75920ae147863a94bc876a8911c6781ba52e08ddd125fc2308ee4c4e3c66f8b4b7a6a6c9284c48f92005e869bfc25800949d60cdc3925582e0cc6f2b8251fb64c99dcf44f5b72a4dbc1e504eefcc72da5c2602c8ffa747a9b982c005e3ca8ffbed9e4db9595fc2b5959360c46f3d887e1d690a8b02e30b83400c6d2c74062f3b782bd0c6a81bc32f0bfe9dbcbfad767d2f747b25f9f51af94b80777bf0c99bd52b6ce9721675e99baed452b7935ff51a196cd386f574efe05, '2038-01-19 03:14:07'),
('suit_SUIT-mw_:pcache:idhash:2-0!1!0!!en!2', 0xbd596d6fdb3610ee67ff0a460386f6836d49b6f3423bde87205d03f42543dcedc330148cc5d85c254a15a9385e90ffbe2325ca524205911c3480639977f7dc43f2ee78a6bf60cfc7ce2549054dbf6432c9a4837d0fdf0b3cc14eb4a077d299c2f3e1e4103bb3647ef5f56281de677c2959cc456f364ce6bd9924d721452c387564bc74d032244214cf228b22926e4f9db3984bcaa570e63399c22b98cf02766b8c24932105d1da9f1bc5d9103ecc86a0041eb210fe85ac021dd25b1af63db021689dd29b53e797b33855182221bca2c8b3e89aa6cedc9b0d95648e1e2b4835c7b9322e34664302af90bdc0e547c25719593de3d67fdead0168effad3f6ea8f8fcd7e47cffbd5d6ed9d2e6894844452d1ec78fcbce312e1a9f3a1dee5a10a8da18a91a18e2b70b44c592291dc2614800065f82fb925f9a83347ec06bddd301ec49b8158c79b45bc5cc4ab5548dfa17b744b5204aeafd43818a253e4281d676a241f58408d640dcf20a981bc7d37450f08b86a6f408f204e22aae259079b62afe2b43a571a3029a8ce1067fe77b97843e048ef06c93af94d87fba9caa56f652efd4aa2644af4f3a942d09f0b9853cf41b98d730e22540c6394b350ea8ac93fd6a58f36fd352541c838a86a0b54ae3d50d7c9051b3f6f26aa6c06100e847f4dac3453aae0bf2b9a85534e3725e5aa397acb6389b654a24dca2424fa3b675e88d40c7a3a145ec0268c49f0290eb2907625b443b071da495bd25a9da7699c762795dbdb29e5b2b6845634b8e07b10d2f60d84b4ac152141e557386a2ef84ddc955305c246ab1033105798e5c5c5646fa56effa40cf61b3278c7a44d161babb6996cec062bca690a65f88c2cd75d52c80e64db8e9ac24b43a5022fcdf37e2c4b183b47b93b885b3214afc3503ccf50ecc13051cddd6b70ac01d958d6141a93cf742e3f29f3460d9957d0689376daa46dce69a3c132e61c3c7758fe9abdf5fccc452f8d8b1c8f763ca62ad6362eb4cd095560892549bac465d5dcca454bda91611c8e8eeebb949bdbc8e49276647e6434dd76e6a2ad6d54b4a01d1341c37d823737b7d7b5b075e8ca14f21e4e94ce740c808d9091b5a394f13da3d800d82819596341ad7e2bfb494575dc50542b54da14d6d2ac6d712d0d21f5966116d0f7acd357022b8e3d8f4bf14b436487bd6fd7d580f46a6d57155f9a0f7bf22c711a1a2f236ecf51771bafc2b286d4d8d874672a5e6935c5f3ab29f6594d65bca6d1fe041548133b256b4f2da5f0b0dc8f5981612356881e97d7deeca0df47bdcf747379894216419903cd3895bd4b784fe32515029a201e07142de38c4b8c2643cfd57fbdcb58c83ebd835216a0a26420c1fea318b943df3d39f2263ebade02b15eb9a1245d6511e5b241effc2ea15cb05b8a7430a6e8a628d4c6b9ab9cf7fa7dc3fc8adc52e5dce82f75a9d830b946dfe916898cc96faae2f7a3cd379c682966c19a8835f6fbee8177e01e1c507ee0233507c9222a242c38f25df7d8f3fc91eb4e4ec62748f95337c4de183b9169fd3fc28e08674ab08bef1f40e681ec0ce6b88a5346ab82232588b924d01e7d09834f64c596cef41abb0ad1d556406b01ce8d8fba7b357a0c6a7fd254a8d36daaafadbdc1e160ec1888850a0573910d03eaed509135243d7ccfc023c1fe58dd7b2b2ff543d99932cdc88779580f0aa3b033356d4429f171ad172ac73d5c6dd74a4f23dc54eced642a85b4843ec24fe3bf141e634bdadab1c553ec3abf5a757c3ae3bcf57d3a5e5f5c0ff6e4f1f54e291be1fa75944da02fc74ac1043fbee62bb90301fbf545a9e162ebd5c1d34da3359f235cffb266f558fb8e6cf5282c1e7d5cbb46cdc71f1e4c88975dd72eb5fccaf8455091a89cbb8800bcaaadb2f7fc4ed29493d096bd5005af8a0eb29a9e9fe3df4918eac0ad8c7e80c6ee42d2e8319dfcf7a50f715c853f01c15f24e58caf1e8d160ef5e8d8e4a84e51085ef3fb845a092f4ffbe233388332a1de6152babfcc1ff55d799efbc56f15b9aae74c1f144667ece3ddded9f07d8def77c79f146165031f69f05177f0934a22db1c401d7d30417099c6096472ad84fb60f0a6faf3e19b800980dbeab25b0b8b853943eab5dc3b72c7eee158cde47f, '2008-11-24 00:59:49'),
('suit_SUIT-mw_:pcache:idhash:3-0!1!0!!en!2', 0x9554516fda3010de33bfe2c8cbda0748e2424b0d45daaa6e45ea5a3458fb5899e448ac2676643ba4b4ea7f9f1d600aa8ddb43c24cadd677f9fbfbbf31d0d09f5a64c695477a5294ae35112d2574dfbd4cbe7f86cbca1a6a7a701f546c578c42055b8bcf052630aeafb555575178a89580a5c31a1bb5225be0751c6b4bef0ec6254826560dc3660b8c9f05f4b1566179e904b9965b2f2c65f3708b8729091cfc6a385027fdc7a4fc89265e6055557a0f90f0dfbab0ee82f53c535cc98309c25128ebe6dc0c70d255f44acb0827b9e48a39fd670b4c8b8780a0784ad8eb7905b8e9986879499cf1a52bba1956640b01ce1e827eab45448fa3bf0c82fecabdde940eb16abe914329e736385155299d6d47e958c506ba940c8182192a53014fa7e18d44f6b2ab5e9e073618d032ea2acb420cd5f9042e093e0fc2cec1358ac0dead61cf322630681a9a4cc51980f7057cf050acd570845dd27b02c4564b82dcb963c70e4ad4e67dcda289fb1153af21d3e62518a507193c213ae4197dc3cce7e4de69dbc7aa4459da53c4e994ee949276887eda0dd46d126e0ce60788edab0bc001204833024849c04bd70008ecf3567d8b39d7ac34452b2046facf9da1b321ad0d7379b0b6deed29e31918a633371e6125218c685becbe21f2ce191375cd0c0ed18d4abacacb925df71ecd3bbe8c0c2ee51696b85fbb51313764fbb3d6fb7c5dc75db6e866ca01e2527f640648ddd16a319278df8246e649cf8496e8fdb443b1baeb6edfe8781b8512656e85fc76ec86968b59113fae168d4909da3b6336758b7c09e65b7f23bcb3254ebbde835b27862303f3cd9e6bab996b2e9c5b94d3c3025b8480ea25bc2c3e24e952c5099bde2126bf2a7e69df629e6dabab8ae0bb2276ebeebae77aa7cd2b3557efb0d, '2008-11-23 23:04:18'),
('suit_SUIT-mw_:pcache:idhash:4-0!1!0!!en!2', 0x9554db4edb4010edb3bf62e297c243e20bb9c026446a112d9128444d0a8f68e34cec15f6aeb5bb8e09887fefae9354760454f8c19667cecc393b97bd254148dc29950ae56da1f342bb240cc88b223de266737cd2ee5091413f20ee281f8f28241257e76ea2754e3caf2ccbce4252be141cd794ab8e90b1e7429452a5ce5d138c92d314b44d039ae914ff172a313d77b958893415a53bfebe45c0a5858c3c3a1e2d247863e72d212b9aea67941d8efa131a9a5107f41789640a66946b466301473fb6e0e39a926f7c29b1843b160bad1e3770b448197f0c4e43ba3efe40ac449514123b91c83e21b6197520f68661aae03ea1faab82c4e8364935709a211cfdde0686bd4aba33f272f36ab5dbe0dc60399d42ca32a64dc25c48ed4ccd578a08951212b8582244a2e09a40d70bfcea71a642e9363ee5a63bc078941606a4d83312f0bdd03f1b04bd10161b8dca996396a754235019171972fd0eeef22947aed81a21af861156058f3433bddf91fb96dc69b7c7ce56f98caed192eff1118d128492e9041e7103aa60fa61f667326f67e503c92b2f61cb84aa8474db7e2b68f9ad16f25608f60c9a65a834cd72087dff3408c2ae1ff4fa275db07c760382ae59876bcae382c6786d3aacdc21253e7979353eb31cd98539632c24c3ba63601d826bcab8ba4d97bf68cc2277b820becde8575146d6dc90ef399af4d67a6a607728952985fd356b1974fa9dca55a598db29d92faa31d84fdf8a3d10596177cda8dbc39a7db2ac79acf849668e5b47db325ceec6f41fc389bd2f4223f4c3dd1e3212186de1097977ffde8634a6be82ec8b6e867786d59434aa7a237ed23445b96958af902e271ab3c3c36fafbd2b21eae53a338e7b2a39e3f181754778d8ffa914394adde87f68faf0a57eb77e5932650abda97ad61037df0fe0c12084e1893fe8f5dde1eb5f, '2008-11-25 01:56:34'),
('suit_SUIT-mw_:rcfeed:atom:timestamp', 0x2bb63234b152323230b03034343231303435373655b20600, '2008-11-25 01:57:35'),
('suit_SUIT-mw_:rcfeed:rss:timestamp', 0x2bb63234b152323230b030343432313034b530b650b20600, '2008-11-25 01:58:38'),
('suit_SUIT-mw_:messages:en', 0x4bb432b4aa2eb632b7520a730d0af6f4f753b2ceb432b4ae0500, '2008-11-25 18:23:15');

-- --------------------------------------------------------

--
-- Table structure for table `mw_oldimage`
--

CREATE TABLE IF NOT EXISTS `mw_oldimage` (
  `oi_name` varbinary(255) NOT NULL default '',
  `oi_archive_name` varbinary(255) NOT NULL default '',
  `oi_size` int(10) unsigned NOT NULL default '0',
  `oi_width` int(11) NOT NULL default '0',
  `oi_height` int(11) NOT NULL default '0',
  `oi_bits` int(11) NOT NULL default '0',
  `oi_description` tinyblob NOT NULL,
  `oi_user` int(10) unsigned NOT NULL default '0',
  `oi_user_text` varbinary(255) NOT NULL,
  `oi_timestamp` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `oi_metadata` mediumblob NOT NULL,
  `oi_media_type` enum('UNKNOWN','BITMAP','DRAWING','AUDIO','VIDEO','MULTIMEDIA','OFFICE','TEXT','EXECUTABLE','ARCHIVE') character set binary default NULL,
  `oi_major_mime` enum('unknown','application','audio','image','text','video','message','model','multipart') character set binary NOT NULL default 'unknown',
  `oi_minor_mime` varbinary(32) NOT NULL default 'unknown',
  `oi_deleted` tinyint(3) unsigned NOT NULL default '0',
  `oi_sha1` varbinary(32) NOT NULL default '',
  KEY `oi_usertext_timestamp` (`oi_user_text`,`oi_timestamp`),
  KEY `oi_name_timestamp` (`oi_name`,`oi_timestamp`),
  KEY `oi_name_archive_name` (`oi_name`,`oi_archive_name`(14)),
  KEY `oi_sha1` (`oi_sha1`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_oldimage`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_page`
--

CREATE TABLE IF NOT EXISTS `mw_page` (
  `page_id` int(10) unsigned NOT NULL auto_increment,
  `page_namespace` int(11) NOT NULL,
  `page_title` varbinary(255) NOT NULL,
  `page_restrictions` tinyblob NOT NULL,
  `page_counter` bigint(20) unsigned NOT NULL default '0',
  `page_is_redirect` tinyint(3) unsigned NOT NULL default '0',
  `page_is_new` tinyint(3) unsigned NOT NULL default '0',
  `page_random` double unsigned NOT NULL,
  `page_touched` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `page_latest` int(10) unsigned NOT NULL,
  `page_len` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`page_id`),
  UNIQUE KEY `name_title` (`page_namespace`,`page_title`),
  KEY `page_random` (`page_random`),
  KEY `page_len` (`page_len`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `mw_page`
--

INSERT INTO `mw_page` (`page_id`, `page_namespace`, `page_title`, `page_restrictions`, `page_counter`, `page_is_redirect`, `page_is_new`, `page_random`, `page_touched`, `page_latest`, `page_len`) VALUES
(1, 0, 'Main_Page', '', 91, 0, 0, 0.421974920214, '20081116071151', 3, 237),
(2, 0, 'SUIT_Functions', '', 12, 0, 0, 0.617614762989, '20081117040643', 4, 849),
(4, 0, 'The_SUIT_Group', '', 8, 0, 0, 0.997054984524, '20081122230756', 8, 207);

-- --------------------------------------------------------

--
-- Table structure for table `mw_pagelinks`
--

CREATE TABLE IF NOT EXISTS `mw_pagelinks` (
  `pl_from` int(10) unsigned NOT NULL default '0',
  `pl_namespace` int(11) NOT NULL default '0',
  `pl_title` varbinary(255) NOT NULL default '',
  UNIQUE KEY `pl_from` (`pl_from`,`pl_namespace`,`pl_title`),
  KEY `pl_namespace` (`pl_namespace`,`pl_title`,`pl_from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_pagelinks`
--

INSERT INTO `mw_pagelinks` (`pl_from`, `pl_namespace`, `pl_title`) VALUES
(1, 0, 'Introduction'),
(2, 0, 'Core.cleanUp'),
(2, 0, 'Core.loadModule'),
(2, 0, 'Core.logError'),
(2, 0, 'Core.loggedIn'),
(2, 0, 'Core.setUserInfo'),
(2, 0, 'Language.generateCache'),
(2, 0, 'Language.getLanguage'),
(2, 0, 'Language.parseLanguage'),
(2, 0, 'Language.setLanguage'),
(2, 0, 'MySQL.connect'),
(2, 0, 'MySQL.error'),
(2, 0, 'MySQL.escape'),
(2, 0, 'MySQL.insert'),
(2, 0, 'MySQL.query'),
(2, 0, 'MySQL.select'),
(2, 0, 'MySQL.truncate'),
(2, 0, 'MySQL.unescape'),
(2, 0, 'Templates.generateCache'),
(2, 0, 'Templates.getTemplate'),
(2, 0, 'Templates.includeFile'),
(2, 0, 'Templates.parseTemplate'),
(2, 0, 'Templates.replace'),
(2, 0, 'Templates.setTemplate'),
(2, 0, 'Templates.setTheme');

-- --------------------------------------------------------

--
-- Table structure for table `mw_page_props`
--

CREATE TABLE IF NOT EXISTS `mw_page_props` (
  `pp_page` int(11) NOT NULL,
  `pp_propname` varbinary(60) NOT NULL,
  `pp_value` blob NOT NULL,
  PRIMARY KEY  (`pp_page`,`pp_propname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_page_props`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_page_restrictions`
--

CREATE TABLE IF NOT EXISTS `mw_page_restrictions` (
  `pr_page` int(11) NOT NULL,
  `pr_type` varbinary(60) NOT NULL,
  `pr_level` varbinary(60) NOT NULL,
  `pr_cascade` tinyint(4) NOT NULL,
  `pr_user` int(11) default NULL,
  `pr_expiry` varbinary(14) default NULL,
  `pr_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`pr_page`,`pr_type`),
  UNIQUE KEY `pr_id` (`pr_id`),
  KEY `pr_typelevel` (`pr_type`,`pr_level`),
  KEY `pr_level` (`pr_level`),
  KEY `pr_cascade` (`pr_cascade`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mw_page_restrictions`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_protected_titles`
--

CREATE TABLE IF NOT EXISTS `mw_protected_titles` (
  `pt_namespace` int(11) NOT NULL,
  `pt_title` varbinary(255) NOT NULL,
  `pt_user` int(10) unsigned NOT NULL,
  `pt_reason` tinyblob,
  `pt_timestamp` binary(14) NOT NULL,
  `pt_expiry` varbinary(14) NOT NULL default '',
  `pt_create_perm` varbinary(60) NOT NULL,
  PRIMARY KEY  (`pt_namespace`,`pt_title`),
  KEY `pt_timestamp` (`pt_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_protected_titles`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_querycache`
--

CREATE TABLE IF NOT EXISTS `mw_querycache` (
  `qc_type` varbinary(32) NOT NULL,
  `qc_value` int(10) unsigned NOT NULL default '0',
  `qc_namespace` int(11) NOT NULL default '0',
  `qc_title` varbinary(255) NOT NULL default '',
  KEY `qc_type` (`qc_type`,`qc_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_querycache`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_querycachetwo`
--

CREATE TABLE IF NOT EXISTS `mw_querycachetwo` (
  `qcc_type` varbinary(32) NOT NULL,
  `qcc_value` int(10) unsigned NOT NULL default '0',
  `qcc_namespace` int(11) NOT NULL default '0',
  `qcc_title` varbinary(255) NOT NULL default '',
  `qcc_namespacetwo` int(11) NOT NULL default '0',
  `qcc_titletwo` varbinary(255) NOT NULL default '',
  KEY `qcc_type` (`qcc_type`,`qcc_value`),
  KEY `qcc_title` (`qcc_type`,`qcc_namespace`,`qcc_title`),
  KEY `qcc_titletwo` (`qcc_type`,`qcc_namespacetwo`,`qcc_titletwo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_querycachetwo`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_querycache_info`
--

CREATE TABLE IF NOT EXISTS `mw_querycache_info` (
  `qci_type` varbinary(32) NOT NULL default '',
  `qci_timestamp` binary(14) NOT NULL default '19700101000000',
  UNIQUE KEY `qci_type` (`qci_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_querycache_info`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_recentchanges`
--

CREATE TABLE IF NOT EXISTS `mw_recentchanges` (
  `rc_id` int(11) NOT NULL auto_increment,
  `rc_timestamp` varbinary(14) NOT NULL default '',
  `rc_cur_time` varbinary(14) NOT NULL default '',
  `rc_user` int(10) unsigned NOT NULL default '0',
  `rc_user_text` varbinary(255) NOT NULL,
  `rc_namespace` int(11) NOT NULL default '0',
  `rc_title` varbinary(255) NOT NULL default '',
  `rc_comment` varbinary(255) NOT NULL default '',
  `rc_minor` tinyint(3) unsigned NOT NULL default '0',
  `rc_bot` tinyint(3) unsigned NOT NULL default '0',
  `rc_new` tinyint(3) unsigned NOT NULL default '0',
  `rc_cur_id` int(10) unsigned NOT NULL default '0',
  `rc_this_oldid` int(10) unsigned NOT NULL default '0',
  `rc_last_oldid` int(10) unsigned NOT NULL default '0',
  `rc_type` tinyint(3) unsigned NOT NULL default '0',
  `rc_moved_to_ns` tinyint(3) unsigned NOT NULL default '0',
  `rc_moved_to_title` varbinary(255) NOT NULL default '',
  `rc_patrolled` tinyint(3) unsigned NOT NULL default '0',
  `rc_ip` varbinary(40) NOT NULL default '',
  `rc_old_len` int(11) default NULL,
  `rc_new_len` int(11) default NULL,
  `rc_deleted` tinyint(3) unsigned NOT NULL default '0',
  `rc_logid` int(10) unsigned NOT NULL default '0',
  `rc_log_type` varbinary(255) default NULL,
  `rc_log_action` varbinary(255) default NULL,
  `rc_params` blob,
  PRIMARY KEY  (`rc_id`),
  KEY `rc_timestamp` (`rc_timestamp`),
  KEY `rc_namespace_title` (`rc_namespace`,`rc_title`),
  KEY `rc_cur_id` (`rc_cur_id`),
  KEY `new_name_timestamp` (`rc_new`,`rc_namespace`,`rc_timestamp`),
  KEY `rc_ip` (`rc_ip`),
  KEY `rc_ns_usertext` (`rc_namespace`,`rc_user_text`),
  KEY `rc_user_text` (`rc_user_text`,`rc_timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `mw_recentchanges`
--

INSERT INTO `mw_recentchanges` (`rc_id`, `rc_timestamp`, `rc_cur_time`, `rc_user`, `rc_user_text`, `rc_namespace`, `rc_title`, `rc_comment`, `rc_minor`, `rc_bot`, `rc_new`, `rc_cur_id`, `rc_this_oldid`, `rc_last_oldid`, `rc_type`, `rc_moved_to_ns`, `rc_moved_to_title`, `rc_patrolled`, `rc_ip`, `rc_old_len`, `rc_new_len`, `rc_deleted`, `rc_logid`, `rc_log_type`, `rc_log_action`, `rc_params`) VALUES
(1, '20081108163045', '20081108163045', 1, 'Brandon', 0, 'SUIT_Functions', 'New page: SUIT Functions == Core == *[[cleanUp]] *[[loadModule]] *[[logError]] *[[loggedIn]] *[[setUserinfo]] == Language == *[[generateCache]] *[[getLanguage]] *[[setLanguage]] *[[parseLanguage]] =...', 0, 0, 1, 2, 2, 0, 1, 0, '', 1, '69.115.232.220', 0, 429, 0, 0, NULL, '', ''),
(2, '20081116071151', '20081116071151', 2, 'Faltzer', 0, 'Main_Page', '', 0, 0, 0, 1, 3, 1, 0, 0, '', 0, '68.173.205.18', 448, 237, 0, 0, NULL, '', ''),
(3, '20081117040643', '20081117040643', 2, 'Faltzer', 0, 'SUIT_Functions', '', 0, 0, 0, 2, 4, 2, 0, 0, '', 0, '68.173.205.18', 429, 849, 0, 0, NULL, '', ''),
(7, '20081122230613', '20081122230613', 1, 'Brandon', 0, 'SUIT_Group', 'Author request: content was: ''[http://www.brandonevans.org/ Brandon Evans]<br /> [http://www.faltzer.net/ Chris Santiago (Faltzer)]<br /> Andrew Vigotsky (blink182av)<br /> Niels What''s his la...'' (and the only contributor was ''[[Special:Contributions/Bra', 0, 0, 0, 0, 0, 0, 3, 0, '', 1, '69.115.232.220', NULL, NULL, 0, 5, 'delete', 'delete', ''),
(6, '20081122230541', '20081122230541', 1, 'Brandon', 0, 'The_SUIT_Group', 'New page: [http://www.brandonevans.org/ Brandon Evans]<br /> [http://www.faltzer.net/ Chris Santiago (Faltzer)]<br /> Andrew Vigotsky (blink182av)<br /> Niels What''s his last name (Reshure25)', 0, 0, 1, 4, 7, 0, 1, 0, '', 1, '69.115.232.220', 0, 181, 0, 0, NULL, '', ''),
(8, '20081122230756', '20081122230756', 0, '68.173.205.18', 0, 'The_SUIT_Group', '', 0, 0, 0, 4, 8, 7, 0, 0, '', 0, '68.173.205.18', 181, 207, 0, 0, NULL, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `mw_redirect`
--

CREATE TABLE IF NOT EXISTS `mw_redirect` (
  `rd_from` int(10) unsigned NOT NULL default '0',
  `rd_namespace` int(11) NOT NULL default '0',
  `rd_title` varbinary(255) NOT NULL default '',
  PRIMARY KEY  (`rd_from`),
  KEY `rd_ns_title` (`rd_namespace`,`rd_title`,`rd_from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mw_redirect`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_revision`
--

CREATE TABLE IF NOT EXISTS `mw_revision` (
  `rev_id` int(10) unsigned NOT NULL auto_increment,
  `rev_page` int(10) unsigned NOT NULL,
  `rev_text_id` int(10) unsigned NOT NULL,
  `rev_comment` tinyblob NOT NULL,
  `rev_user` int(10) unsigned NOT NULL default '0',
  `rev_user_text` varbinary(255) NOT NULL default '',
  `rev_timestamp` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `rev_minor_edit` tinyint(3) unsigned NOT NULL default '0',
  `rev_deleted` tinyint(3) unsigned NOT NULL default '0',
  `rev_len` int(10) unsigned default NULL,
  `rev_parent_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`rev_page`,`rev_id`),
  UNIQUE KEY `rev_id` (`rev_id`),
  KEY `rev_timestamp` (`rev_timestamp`),
  KEY `page_timestamp` (`rev_page`,`rev_timestamp`),
  KEY `user_timestamp` (`rev_user`,`rev_timestamp`),
  KEY `usertext_timestamp` (`rev_user_text`,`rev_timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 MAX_ROWS=10000000 AVG_ROW_LENGTH=1024 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `mw_revision`
--

INSERT INTO `mw_revision` (`rev_id`, `rev_page`, `rev_text_id`, `rev_comment`, `rev_user`, `rev_user_text`, `rev_timestamp`, `rev_minor_edit`, `rev_deleted`, `rev_len`, `rev_parent_id`) VALUES
(1, 1, 1, '', 0, 'MediaWiki default', '20081026133856', 0, 0, 448, 0),
(2, 2, 2, 0x4e657720706167653a20535549542046756e6374696f6e73203d3d20436f7265203d3d202a5b5b636c65616e55705d5d202a5b5b6c6f61644d6f64756c655d5d202a5b5b6c6f674572726f725d5d202a5b5b6c6f67676564496e5d5d202a5b5b73657455736572696e666f5d5d203d3d204c616e6775616765203d3d202a5b5b67656e657261746543616368655d5d202a5b5b6765744c616e67756167655d5d202a5b5b7365744c616e67756167655d5d202a5b5b70617273654c616e67756167655d5d203d2e2e2e, 1, 'Brandon', '20081108163045', 0, 0, 429, 0),
(3, 1, 3, '', 2, 'Faltzer', '20081116071151', 0, 0, 237, 1),
(4, 2, 4, '', 2, 'Faltzer', '20081117040643', 0, 0, 849, 2),
(8, 4, 8, '', 0, '68.173.205.18', '20081122230756', 0, 0, 207, 7),
(7, 4, 7, 0x4e657720706167653a205b687474703a2f2f7777772e6272616e646f6e6576616e732e6f72672f204272616e646f6e204576616e735d3c6272202f3e205b687474703a2f2f7777772e66616c747a65722e6e65742f2043687269732053616e746961676f202846616c747a6572295d3c6272202f3e20416e64726577205669676f74736b792028626c696e6b3138326176293c6272202f3e204e69656c732057686174277320686973206c617374206e616d65202852657368757265323529, 1, 'Brandon', '20081122230541', 0, 0, 181, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mw_searchindex`
--

CREATE TABLE IF NOT EXISTS `mw_searchindex` (
  `si_page` int(10) unsigned NOT NULL,
  `si_title` varchar(255) NOT NULL default '',
  `si_text` mediumtext NOT NULL,
  UNIQUE KEY `si_page` (`si_page`),
  FULLTEXT KEY `si_title` (`si_title`),
  FULLTEXT KEY `si_text` (`si_text`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mw_searchindex`
--

INSERT INTO `mw_searchindex` (`si_page`, `si_title`, `si_text`) VALUES
(2, 'suit functions', ' suit functions core core core core cleanup cleanup core loadmodule loadmodule core logerror logerror core loggedin loggedin core setuserinfo setuserinfo language language language language generatecache generatecache language getlanguage getlanguage language setlanguage setlanguage language parselanguage parselanguage mysql mysql mysql mysql connect connect mysql error error mysql escape escape mysql insert insert mysql query query mysql select select mysql truncate truncate mysql unescape unescape templates templates templates templates includefile includefile templates generatecache generatecache templates gettemplate gettemplate templates parsetemplate parsetemplate templates settemplate settemplate templates settheme settheme templates replace replace '),
(1, 'main page', '  suit documentation  for an introduction to suit reference to the introduction this wiki has been established for the purpose of documenting suit and allowing any user to edit articles in order to contribute to it '),
(3, 'suit group', ' brandon evans chris santiago faltzer andrew vigotsky blink182av niels what what''s his last name reshure25 '),
(4, 'the suit group', ' brandon evans chris santiago faltzer andrew vigotsky blink182av niels what what''s his last name reshure25 ');

-- --------------------------------------------------------

--
-- Table structure for table `mw_site_stats`
--

CREATE TABLE IF NOT EXISTS `mw_site_stats` (
  `ss_row_id` int(10) unsigned NOT NULL,
  `ss_total_views` bigint(20) unsigned default '0',
  `ss_total_edits` bigint(20) unsigned default '0',
  `ss_good_articles` bigint(20) unsigned default '0',
  `ss_total_pages` bigint(20) default '-1',
  `ss_users` bigint(20) default '-1',
  `ss_admins` int(11) default '-1',
  `ss_images` int(11) default '0',
  UNIQUE KEY `ss_row_id` (`ss_row_id`)
) ENGINE=MyISAM DEFAULT CHARSET=binary;

--
-- Dumping data for table `mw_site_stats`
--

INSERT INTO `mw_site_stats` (`ss_row_id`, `ss_total_views`, `ss_total_edits`, `ss_good_articles`, `ss_total_pages`, `ss_users`, `ss_admins`, `ss_images`) VALUES
(1, 114, 9, 2, 3, 2, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mw_templatelinks`
--

CREATE TABLE IF NOT EXISTS `mw_templatelinks` (
  `tl_from` int(10) unsigned NOT NULL default '0',
  `tl_namespace` int(11) NOT NULL default '0',
  `tl_title` varbinary(255) NOT NULL default '',
  UNIQUE KEY `tl_from` (`tl_from`,`tl_namespace`,`tl_title`),
  KEY `tl_namespace` (`tl_namespace`,`tl_title`,`tl_from`)
) ENGINE=MyISAM DEFAULT CHARSET=binary;

--
-- Dumping data for table `mw_templatelinks`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_text`
--

CREATE TABLE IF NOT EXISTS `mw_text` (
  `old_id` int(10) unsigned NOT NULL auto_increment,
  `old_text` mediumblob NOT NULL,
  `old_flags` tinyblob NOT NULL,
  PRIMARY KEY  (`old_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=binary MAX_ROWS=10000000 AVG_ROW_LENGTH=10240 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `mw_text`
--

INSERT INTO `mw_text` (`old_id`, `old_text`, `old_flags`) VALUES
(1, 0x3c6269673e2727274d6564696157696b6920686173206265656e207375636365737366756c6c7920696e7374616c6c65642e2727273c2f6269673e0a0a436f6e73756c7420746865205b687474703a2f2f6d6574612e77696b696d656469612e6f72672f77696b692f48656c703a436f6e74656e7473205573657227732047756964655d20666f7220696e666f726d6174696f6e206f6e207573696e67207468652077696b6920736f6674776172652e0a0a3d3d2047657474696e672073746172746564203d3d0a2a205b687474703a2f2f7777772e6d6564696177696b692e6f72672f77696b692f4d616e75616c3a436f6e66696775726174696f6e5f73657474696e677320436f6e66696775726174696f6e2073657474696e6773206c6973745d0a2a205b687474703a2f2f7777772e6d6564696177696b692e6f72672f77696b692f4d616e75616c3a464151204d6564696157696b69204641515d0a2a205b687474703a2f2f6c697374732e77696b696d656469612e6f72672f6d61696c6d616e2f6c697374696e666f2f6d6564696177696b692d616e6e6f756e6365204d6564696157696b692072656c65617365206d61696c696e67206c6973745d, 0x7574662d38),
(2, 0x535549542046756e6374696f6e730a3d3d20436f7265203d3d0a2a5b5b636c65616e55705d5d0a2a5b5b6c6f61644d6f64756c655d5d0a2a5b5b6c6f674572726f725d5d0a2a5b5b6c6f67676564496e5d5d0a2a5b5b73657455736572696e666f5d5d0a3d3d204c616e6775616765203d3d0a2a5b5b67656e657261746543616368655d5d0a2a5b5b6765744c616e67756167655d5d0a2a5b5b7365744c616e67756167655d5d0a2a5b5b70617273654c616e67756167655d5d0a3d3d204d7953514c203d3d0a2a5b5b636f6e6e6563745d5d0a2a5b5b6572726f725d5d0a2a5b5b6573636170655d5d0a2a5b5b696e736572745d5d0a2a5b5b71756572795d5d0a2a5b5b73656c6563745d5d0a2a5b5b7472756e636174655d5d0a2a5b5b756e6573636170655d5d0a3d3d2054656d706c61746573203d3d0a2a5b5b696e636c75646546696c655d5d0a2a5b5b67656e657261746543616368655d5d0a2a5b5b67657454656d706c6174655d5d0a2a5b5b706172736554656d706c6174655d5d0a2a5b5b73657454656d706c6174655d5d0a2a5b5b7365745468656d655d5d0a2a5b5b7265706c6163655d5d, 0x7574662d38),
(3, 0x3c6269673e2727275355495420446f63756d656e746174696f6e2727273c2f6269673e0a0a466f7220616e20696e74726f64756374696f6e20746f20535549542c207265666572656e636520746f20746865205b5b496e74726f64756374696f6e5d5d2e20546869732057696b6920686173206265656e2065737461626c697368656420666f722074686520707572706f7365206f6620646f63756d656e74696e6720535549542c20616e6420616c6c6f77696e6720616e79207573657220746f20656469742061727469636c657320696e206f7264657220746f20636f6e7472696275746520746f2069742e, 0x7574662d38),
(4, 0x535549542046756e6374696f6e730a3d3d20436f7265203d3d0a2a5b5b436f72652e636c65616e55707c636c65616e55705d5d0a2a5b5b436f72652e6c6f61644d6f64756c657c6c6f61644d6f64756c655d5d0a2a5b5b436f72652e6c6f674572726f727c6c6f674572726f725d5d0a2a5b5b436f72652e6c6f67676564496e7c6c6f67676564496e5d5d0a2a5b5b436f72652e73657455736572496e666f7c73657455736572696e666f5d5d0a3d3d204c616e6775616765203d3d0a2a5b5b4c616e67756167652e67656e657261746543616368657c67656e657261746543616368655d5d0a2a5b5b4c616e67756167652e6765744c616e67756167657c6765744c616e67756167655d5d0a2a5b5b4c616e67756167652e7365744c616e67756167657c7365744c616e67756167655d5d0a2a5b5b4c616e67756167652e70617273654c616e67756167657c70617273654c616e67756167655d5d0a3d3d204d7953514c203d3d0a2a5b5b4d7953514c2e636f6e6e6563747c636f6e6e6563745d5d0a2a5b5b4d7953514c2e6572726f727c6572726f725d5d0a2a5b5b4d7953514c2e6573636170657c6573636170655d5d0a2a5b5b4d7953514c2e696e736572747c696e736572745d5d0a2a5b5b4d7953514c2e71756572797c71756572795d5d0a2a5b5b4d7953514c2e73656c6563747c73656c6563745d5d0a2a5b5b4d7953514c2e7472756e636174657c7472756e636174655d5d0a2a5b5b4d7953514c2e756e6573636170657c756e6573636170655d5d0a3d3d2054656d706c61746573203d3d0a2a5b5b54656d706c617465732e696e636c75646546696c657c696e636c75646546696c655d5d0a2a5b5b54656d706c617465732e67656e657261746543616368657c67656e657261746543616368655d5d0a2a5b5b54656d706c617465732e67657454656d706c6174657c67657454656d706c6174655d5d0a2a5b5b54656d706c617465732e706172736554656d706c6174657c706172736554656d706c6174655d5d0a2a5b5b54656d706c617465732e73657454656d706c6174657c73657454656d706c6174655d5d0a2a5b5b54656d706c617465732e7365745468656d657c7365745468656d655d5d0a2a5b5b54656d706c617465732e7265706c6163657c7265706c6163655d5d, 0x7574662d38),
(5, 0x5b687474703a2f2f7777772e6272616e646f6e6576616e732e6f72672f204272616e646f6e204576616e735d0a5b687474703a2f2f7777772e66616c747a65722e6e65742f2043687269732053616e746961676f202846616c747a6572295d0a416e64726577205669676f74736b792028626c696e6b3138326176290a4e69656c732057686174277320686973206c617374206e616d65202852657368757265323529, 0x7574662d38),
(6, 0x5b687474703a2f2f7777772e6272616e646f6e6576616e732e6f72672f204272616e646f6e204576616e735d3c6272202f3e0a5b687474703a2f2f7777772e66616c747a65722e6e65742f2043687269732053616e746961676f202846616c747a6572295d3c6272202f3e0a416e64726577205669676f74736b792028626c696e6b3138326176293c6272202f3e0a4e69656c732057686174277320686973206c617374206e616d652028526573687572653235293c6272202f3e, 0x7574662d38),
(7, 0x5b687474703a2f2f7777772e6272616e646f6e6576616e732e6f72672f204272616e646f6e204576616e735d3c6272202f3e0a5b687474703a2f2f7777772e66616c747a65722e6e65742f2043687269732053616e746961676f202846616c747a6572295d3c6272202f3e0a416e64726577205669676f74736b792028626c696e6b3138326176293c6272202f3e0a4e69656c732057686174277320686973206c617374206e616d65202852657368757265323529, 0x7574662d38),
(8, 0x5b687474703a2f2f7777772e6272616e646f6e6576616e732e6f72672f204272616e646f6e204576616e735d3c6272202f3e0a5b687474703a2f2f7777772e66616c747a65722e6e65742f2043687269732053616e746961676f202846616c747a6572295d3c6272202f3e0a416e64726577205669676f74736b792028626c696e6b3138326176293c6272202f3e0a5b687474703a2f2f7777772e726573687572652e636f6d2f204e69656c732057686174277320686973206c617374206e616d652028526573687572653235295d, 0x7574662d38);

-- --------------------------------------------------------

--
-- Table structure for table `mw_trackbacks`
--

CREATE TABLE IF NOT EXISTS `mw_trackbacks` (
  `tb_id` int(11) NOT NULL auto_increment,
  `tb_page` int(11) default NULL,
  `tb_title` varbinary(255) NOT NULL,
  `tb_url` blob NOT NULL,
  `tb_ex` blob,
  `tb_name` varbinary(255) default NULL,
  PRIMARY KEY  (`tb_id`),
  KEY `tb_page` (`tb_page`)
) ENGINE=MyISAM DEFAULT CHARSET=binary AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mw_trackbacks`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_transcache`
--

CREATE TABLE IF NOT EXISTS `mw_transcache` (
  `tc_url` varbinary(255) NOT NULL,
  `tc_contents` blob,
  `tc_time` int(11) NOT NULL,
  UNIQUE KEY `tc_url_idx` (`tc_url`)
) ENGINE=MyISAM DEFAULT CHARSET=binary;

--
-- Dumping data for table `mw_transcache`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_updatelog`
--

CREATE TABLE IF NOT EXISTS `mw_updatelog` (
  `ul_key` varbinary(255) NOT NULL,
  PRIMARY KEY  (`ul_key`)
) ENGINE=MyISAM DEFAULT CHARSET=binary;

--
-- Dumping data for table `mw_updatelog`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_user`
--

CREATE TABLE IF NOT EXISTS `mw_user` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `user_name` varbinary(255) NOT NULL default '',
  `user_real_name` varbinary(255) NOT NULL default '',
  `user_password` tinyblob NOT NULL,
  `user_newpassword` tinyblob NOT NULL,
  `user_newpass_time` binary(14) default NULL,
  `user_email` tinyblob NOT NULL,
  `user_options` blob NOT NULL,
  `user_touched` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `user_token` binary(32) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `user_email_authenticated` binary(14) default NULL,
  `user_email_token` binary(32) default NULL,
  `user_email_token_expires` binary(14) default NULL,
  `user_registration` binary(14) default NULL,
  `user_editcount` int(11) default NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `user_email_token` (`user_email_token`)
) ENGINE=MyISAM  DEFAULT CHARSET=binary AUTO_INCREMENT=3 ;

--
-- Dumping data for table `mw_user`
--

INSERT INTO `mw_user` (`user_id`, `user_name`, `user_real_name`, `user_password`, `user_newpassword`, `user_newpass_time`, `user_email`, `user_options`, `user_touched`, `user_token`, `user_email_authenticated`, `user_email_token`, `user_email_token_expires`, `user_registration`, `user_editcount`) VALUES
(1, 'Brandon', '', 0x3a423a34323431343837633a6337663732663938376165313732653839343034343435636334653630646131, '', NULL, '', 0x717569636b6261723d310a756e6465726c696e653d320a636f6c733d38300a726f77733d32350a7365617263686c696d69743d32300a636f6e746578746c696e65733d350a636f6e7465787463686172733d35300a64697361626c65737567676573743d0a616a61787365617263683d0a736b696e3d6d6f6e6f626f6f6b0a6d6174683d310a7573656e657772633d300a7263646179733d370a72636c696d69743d35300a776c6c696d69743d3235300a686964656d696e6f723d300a686967686c6967687462726f6b656e3d310a737475627468726573686f6c643d300a707265766965776f6e746f703d310a707265766965776f6e66697273743d300a6564697473656374696f6e3d310a6564697473656374696f6e6f6e7269676874636c69636b3d300a656469746f6e64626c636c69636b3d300a6564697477696474683d300a73686f77746f633d310a73686f77746f6f6c6261723d310a6d696e6f7264656661756c743d300a646174653d64656661756c740a696d61676573697a653d320a7468756d6273697a653d320a72656d656d62657270617373776f72643d300a656e6f74696677617463686c69737470616765733d300a656e6f7469667573657274616c6b70616765733d310a656e6f7469666d696e6f7265646974733d300a656e6f74696672657665616c616464723d300a73686f776e756d626572737761746368696e673d300a66616e63797369673d300a65787465726e616c656469746f723d300a65787465726e616c646966663d300a73686f776a756d706c696e6b733d310a6e756d62657268656164696e67733d300a7573656c697665707265766965773d300a77617463686c697374646179733d330a657874656e6477617463686c6973743d300a77617463686c697374686964656d696e6f723d300a77617463686c69737468696465626f74733d300a77617463686c697374686964656f776e3d300a77617463686372656174696f6e733d300a776174636864656661756c743d300a77617463686d6f7665733d300a776174636864656c6574696f6e3d300a76617269616e743d0a6c616e67756167653d656e0a7365617263684e73303d310a6e69636b6e616d653d4272616e646f6e0a74696d65636f7272656374696f6e3d0a7365617263684e73313d300a7365617263684e73323d300a7365617263684e73333d300a7365617263684e73343d300a7365617263684e73353d300a7365617263684e73363d300a7365617263684e73373d300a7365617263684e73383d300a7365617263684e73393d300a7365617263684e7331303d300a7365617263684e7331313d300a7365617263684e7331323d300a7365617263684e7331333d300a7365617263684e7331343d300a7365617263684e7331353d300a64697361626c656d61696c3d300a6a7573746966793d300a6e6f63616368653d300a666f7263656564697473756d6d6172793d300a63636d656f6e656d61696c733d300a646966666f6e6c793d300a73686f7768696464656e636174733d30, '20081122230546', '56791cbadb07c4923a4368882fe167b1', NULL, '\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0', NULL, '20081026133856', 4),
(2, 'Faltzer', 'Chris Santiago', 0x3a423a35326639626562663a3161323635393536666637326466653363626237326534303732383338336234, '', NULL, 0x66616c747a65726d617374657240616f6c2e636f6d, 0x717569636b6261723d310a756e6465726c696e653d320a636f6c733d38300a726f77733d32350a7365617263686c696d69743d32300a636f6e746578746c696e65733d350a636f6e7465787463686172733d35300a64697361626c65737567676573743d0a616a61787365617263683d0a736b696e3d6d6f6465726e0a6d6174683d310a7573656e657772633d300a7263646179733d370a72636c696d69743d35300a776c6c696d69743d3235300a686964656d696e6f723d300a686967686c6967687462726f6b656e3d310a737475627468726573686f6c643d300a707265766965776f6e746f703d310a707265766965776f6e66697273743d300a6564697473656374696f6e3d310a6564697473656374696f6e6f6e7269676874636c69636b3d300a656469746f6e64626c636c69636b3d300a6564697477696474683d300a73686f77746f633d310a73686f77746f6f6c6261723d310a6d696e6f7264656661756c743d300a646174653d64656661756c740a696d61676573697a653d320a7468756d6273697a653d320a72656d656d62657270617373776f72643d310a656e6f74696677617463686c69737470616765733d300a656e6f7469667573657274616c6b70616765733d300a656e6f7469666d696e6f7265646974733d300a656e6f74696672657665616c616464723d300a73686f776e756d626572737761746368696e673d300a66616e63797369673d300a65787465726e616c656469746f723d300a65787465726e616c646966663d300a73686f776a756d706c696e6b733d310a6e756d62657268656164696e67733d300a7573656c697665707265766965773d300a77617463686c697374646179733d330a657874656e6477617463686c6973743d300a77617463686c697374686964656d696e6f723d300a77617463686c69737468696465626f74733d300a77617463686c697374686964656f776e3d300a77617463686372656174696f6e733d300a776174636864656661756c743d300a77617463686d6f7665733d300a776174636864656c6574696f6e3d300a76617269616e743d0a6c616e67756167653d656e0a7365617263684e73303d310a6e69636b6e616d653d0a74696d65636f7272656374696f6e3d0a7365617263684e73313d300a7365617263684e73323d300a7365617263684e73333d300a7365617263684e73343d300a7365617263684e73353d300a7365617263684e73363d300a7365617263684e73373d300a7365617263684e73383d300a7365617263684e73393d300a7365617263684e7331303d300a7365617263684e7331313d300a7365617263684e7331323d300a7365617263684e7331333d300a7365617263684e7331343d300a7365617263684e7331353d300a64697361626c656d61696c3d310a6a7573746966793d300a6e6f63616368653d300a666f7263656564697473756d6d6172793d300a63636d656f6e656d61696c733d300a646966666f6e6c793d300a73686f7768696464656e636174733d30, '20081117040648', '3ae95a3f1514fa8334a35051dd469c83', '20081117034409', '831f7da1fb829f93ba52a5f5f03631d5', '20081123070736', '20081116070736', 2);

-- --------------------------------------------------------

--
-- Table structure for table `mw_user_groups`
--

CREATE TABLE IF NOT EXISTS `mw_user_groups` (
  `ug_user` int(10) unsigned NOT NULL default '0',
  `ug_group` varbinary(16) NOT NULL default '',
  PRIMARY KEY  (`ug_user`,`ug_group`),
  KEY `ug_group` (`ug_group`)
) ENGINE=MyISAM DEFAULT CHARSET=binary;

--
-- Dumping data for table `mw_user_groups`
--

INSERT INTO `mw_user_groups` (`ug_user`, `ug_group`) VALUES
(1, 'bureaucrat'),
(1, 'sysop');

-- --------------------------------------------------------

--
-- Table structure for table `mw_user_newtalk`
--

CREATE TABLE IF NOT EXISTS `mw_user_newtalk` (
  `user_id` int(11) NOT NULL default '0',
  `user_ip` varbinary(40) NOT NULL default '',
  `user_last_timestamp` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  KEY `user_id` (`user_id`),
  KEY `user_ip` (`user_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=binary;

--
-- Dumping data for table `mw_user_newtalk`
--


-- --------------------------------------------------------

--
-- Table structure for table `mw_watchlist`
--

CREATE TABLE IF NOT EXISTS `mw_watchlist` (
  `wl_user` int(10) unsigned NOT NULL,
  `wl_namespace` int(11) NOT NULL default '0',
  `wl_title` varbinary(255) NOT NULL default '',
  `wl_notificationtimestamp` varbinary(14) default NULL,
  UNIQUE KEY `wl_user` (`wl_user`,`wl_namespace`,`wl_title`),
  KEY `namespace_title` (`wl_namespace`,`wl_title`)
) ENGINE=MyISAM DEFAULT CHARSET=binary;

--
-- Dumping data for table `mw_watchlist`
--


-- --------------------------------------------------------

--
-- Table structure for table `pun_bans`
--

CREATE TABLE IF NOT EXISTS `pun_bans` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(200) character set utf8 default NULL,
  `ip` varchar(255) character set utf8 default NULL,
  `email` varchar(50) character set utf8 default NULL,
  `message` varchar(255) character set utf8 default NULL,
  `expire` int(10) unsigned default NULL,
  `ban_creator` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `pun_bans`
--


-- --------------------------------------------------------

--
-- Table structure for table `pun_categories`
--

CREATE TABLE IF NOT EXISTS `pun_categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cat_name` varchar(80) character set utf8 NOT NULL default 'New Category',
  `disp_position` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pun_categories`
--

INSERT INTO `pun_categories` (`id`, `cat_name`, `disp_position`) VALUES
(1, 'SUIT Framework', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pun_censoring`
--

CREATE TABLE IF NOT EXISTS `pun_censoring` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `search_for` varchar(60) character set utf8 NOT NULL,
  `replace_with` varchar(60) character set utf8 NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pun_censoring`
--

INSERT INTO `pun_censoring` (`id`, `search_for`, `replace_with`) VALUES
(1, 'fuck', '****');

-- --------------------------------------------------------

--
-- Table structure for table `pun_config`
--

CREATE TABLE IF NOT EXISTS `pun_config` (
  `conf_name` varchar(255) NOT NULL default '',
  `conf_value` text,
  PRIMARY KEY  (`conf_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pun_config`
--

INSERT INTO `pun_config` (`conf_name`, `conf_value`) VALUES
('o_cur_version', '1.3'),
('o_database_revision', '3'),
('o_board_title', 'PunBB'),
('o_board_desc', 'PunBB'),
('o_default_timezone', '0'),
('o_time_format', 'H:i:s'),
('o_date_format', 'Y-m-d'),
('o_check_for_updates', '1'),
('o_check_for_versions', '1'),
('o_timeout_visit', '1800'),
('o_timeout_online', '300'),
('o_redirect_delay', '1'),
('o_show_version', '0'),
('o_show_user_info', '1'),
('o_show_post_count', '1'),
('o_signatures', '1'),
('o_smilies', '1'),
('o_smilies_sig', '1'),
('o_make_links', '1'),
('o_default_lang', 'English'),
('o_default_style', 'Oxygen'),
('o_default_user_group', '3'),
('o_topic_review', '15'),
('o_disp_topics_default', '30'),
('o_disp_posts_default', '25'),
('o_indent_num_spaces', '4'),
('o_quote_depth', '3'),
('o_quickpost', '1'),
('o_users_online', '1'),
('o_censoring', '0'),
('o_ranks', '1'),
('o_show_dot', '0'),
('o_topic_views', '1'),
('o_quickjump', '1'),
('o_gzip', '0'),
('o_additional_navlinks', ''),
('o_report_method', '0'),
('o_regs_report', '0'),
('o_default_email_setting', '1'),
('o_mailing_list', 'admin@brandonevans.org'),
('o_avatars', '1'),
('o_avatars_dir', 'img/avatars'),
('o_avatars_width', '60'),
('o_avatars_height', '60'),
('o_avatars_size', '10240'),
('o_search_all_forums', '1'),
('o_sef', 'Default'),
('o_admin_email', 'admin@brandonevans.org'),
('o_webmaster_email', 'admin@brandonevans.org'),
('o_subscriptions', '1'),
('o_smtp_host', NULL),
('o_smtp_user', NULL),
('o_smtp_pass', NULL),
('o_smtp_ssl', '0'),
('o_regs_allow', '1'),
('o_regs_verify', '0'),
('o_announcement', '0'),
('o_announcement_heading', 'Sample announcement'),
('o_announcement_message', '<p>Enter your announcement here.</p>'),
('o_rules', '0'),
('o_rules_message', 'Enter your rules here.'),
('o_maintenance', '0'),
('o_maintenance_message', 'The forums are temporarily down for maintenance. Please try again in a few minutes.<br />\n<br />\n/Administrator'),
('o_default_dst', '0'),
('p_message_bbcode', '1'),
('p_message_img_tag', '1'),
('p_message_all_caps', '1'),
('p_subject_all_caps', '1'),
('p_sig_all_caps', '1'),
('p_sig_bbcode', '1'),
('p_sig_img_tag', '0'),
('p_sig_length', '400'),
('p_sig_lines', '4'),
('p_allow_banned_email', '1'),
('p_allow_dupe_email', '0'),
('p_force_guest_email', '1');

-- --------------------------------------------------------

--
-- Table structure for table `pun_extensions`
--

CREATE TABLE IF NOT EXISTS `pun_extensions` (
  `id` varchar(150) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `version` varchar(25) NOT NULL default '',
  `description` text,
  `author` varchar(50) NOT NULL default '',
  `uninstall` text,
  `uninstall_note` text,
  `disabled` tinyint(1) NOT NULL default '0',
  `dependencies` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pun_extensions`
--

INSERT INTO `pun_extensions` (`id`, `title`, `version`, `description`, `author`, `uninstall`, `uninstall_note`, `disabled`, `dependencies`) VALUES
('pun_repository', 'PunBB Repository', '1.2.1', 'Feel free to download and install extensions from PunBB repository.', 'PunBB Development Team', NULL, NULL, 0, '||');

-- --------------------------------------------------------

--
-- Table structure for table `pun_extension_hooks`
--

CREATE TABLE IF NOT EXISTS `pun_extension_hooks` (
  `id` varchar(150) NOT NULL default '',
  `extension_id` varchar(50) NOT NULL default '',
  `code` text,
  `installed` int(10) unsigned NOT NULL default '0',
  `priority` tinyint(1) unsigned NOT NULL default '5',
  PRIMARY KEY  (`id`,`extension_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pun_extension_hooks`
--

INSERT INTO `pun_extension_hooks` (`id`, `extension_id`, `code`, `installed`, `priority`) VALUES
('aex_section_manage_end', 'pun_repository', 'if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php''))\n	include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php'';\nelse\n	include $ext_info[''path''].''/lang/English/pun_repository.php'';\n\nrequire_once $ext_info[''path''].''/pun_repository.php'';\n\n($hook = get_hook(''pun_repository_pre_display_ext_list'')) ? eval($hook) : null;\n\n?>\n	<div class="main-subhead">\n		<h2 class="hn"><span><?php echo $lang_pun_repository[''PunBB Repository''] ?></span></h2>\n	</div>\n	<div class="main-content main-extensions">\n		<p class="content-options options"><a href="<?php echo $base_url ?>/admin/extensions.php?pun_repository_update&amp;csrf_token=<?php echo generate_form_token(''pun_repository_update'') ?>"><?php echo $lang_pun_repository[''Clear cache''] ?></a></p>\n<?php\n\nif (!defined(''PUN_REPOSITORY_EXTENSIONS_LOADED'') && file_exists(FORUM_CACHE_DIR.''cache_pun_repository.php''))\n	include FORUM_CACHE_DIR.''cache_pun_repository.php'';\n\nif (!defined(''FORUM_EXT_VERSIONS_LOADED'') && file_exists(FORUM_CACHE_DIR.''cache_ext_version_notifications.php''))\n	include FORUM_CACHE_DIR.''cache_ext_version_notifications.php'';\n\n// Regenerate cache only if automatic updates are enabled and if the cache is more than 12 hours old\nif (!defined(''PUN_REPOSITORY_EXTENSIONS_LOADED'') || !defined(''FORUM_EXT_VERSIONS_LOADED'') || ($pun_repository_extensions_timestamp < $forum_ext_versions_update_cache))\n{\n	$pun_repository_error = '''';\n\n	if (pun_repository_generate_cache($pun_repository_error))\n	{\n		require FORUM_CACHE_DIR.''cache_pun_repository.php'';\n	}\n	else\n	{\n\n		?>\n		<div class="ct-box warn-box">\n			<p class="warn"><?php echo $pun_repository_error ?></p>\n		</div>\n		<?php\n\n		// Stop processing hook\n		return;\n	}\n}\n\n$pun_repository_parsed = array();\n$pun_repository_skipped = array();\n\n// Display information about extensions in repository\nforeach ($pun_repository_extensions as $pun_repository_ext)\n{\n	// Skip installed extensions\n	if (isset($inst_exts[$pun_repository_ext[''id'']]))\n	{\n		$pun_repository_skipped[''installed''][] = $pun_repository_ext[''id''];\n		continue;\n	}\n\n	// Skip uploaded extensions (including incorrect ones)\n	if (is_dir(FORUM_ROOT.''extensions/''.$pun_repository_ext[''id'']))\n	{\n		$pun_repository_skipped[''has_dir''][] = $pun_repository_ext[''id''];\n		continue;\n	}\n\n	// Check for unresolved dependencies\n	if (isset($pun_repository_ext[''dependencies'']))\n		$pun_repository_ext[''dependencies''] = pun_repository_check_dependencies($inst_exts, $pun_repository_ext[''dependencies'']);\n\n	if (empty($pun_repository_ext[''dependencies''][''unresolved'']))\n	{\n		// ''Download and install'' link\n		$pun_repository_ext[''options''] = array(''<a href="''.$base_url.''/admin/extensions.php?pun_repository_download_and_install=''.$pun_repository_ext[''id''].''&amp;csrf_token=''.generate_form_token(''pun_repository_download_and_install_''.$pun_repository_ext[''id'']).''">''.$lang_pun_repository[''Download and install''].''</a>'');\n	}\n	else\n		$pun_repository_ext[''options''] = array();\n\n	$pun_repository_parsed[] = $pun_repository_ext[''id''];\n\n	// Direct links to archives\n	$pun_repository_ext[''download_links''] = array();\n	foreach (array(''zip'', ''tgz'', ''7z'') as $pun_repository_archive_type)\n		$pun_repository_ext[''download_links''][] = ''<a href="''.PUN_REPOSITORY_URL.''/''.$pun_repository_ext[''id''].''/''.$pun_repository_ext[''id''].''.''.$pun_repository_archive_type.''">''.$pun_repository_archive_type.''</a>'';\n\n	($hook = get_hook(''pun_repository_pre_display_ext_info'')) ? eval($hook) : null;\n\n	// Let''s ptint it all out\n?>\n		<div class="ct-box info-box extension available" id="<?php echo $pun_repository_ext[''id''] ?>">\n			<h3 class="ct-legend hn"><span><?php echo forum_htmlencode($pun_repository_ext[''title'']).'' ''.$pun_repository_ext[''version''] ?></span></h3>\n			<p><?php echo forum_htmlencode($pun_repository_ext[''description'']) ?></p>\n<?php\n\n	// List extension dependencies\n	if (!empty($pun_repository_ext[''dependencies''][''dependency'']))\n		echo ''\n			<p>'', $lang_pun_repository[''Dependencies:''], '' '', implode('', '', $pun_repository_ext[''dependencies''][''dependency'']), ''</p>'';\n\n?>\n			<p><?php echo $lang_pun_repository[''Direct download links:''], '' '', implode('' '', $pun_repository_ext[''download_links'']) ?></p>\n<?php\n\n	// List unresolved dependencies\n	if (!empty($pun_repository_ext[''dependencies''][''unresolved'']))\n		echo ''\n			<div class="ct-box warn-box">\n				<p class="warn">'', $lang_pun_repository[''Resolve dependencies:''], '' '', implode('', '', array_map(create_function(''$dep'', ''return \\''<a href="#\\''.$dep.\\''">\\''.$dep.\\''</a>\\'';''), $pun_repository_ext[''dependencies''][''unresolved''])), ''</p>\n			</div>'';\n\n	// Actions\n	if (!empty($pun_repository_ext[''options'']))\n		echo ''\n			<p class="options">'', implode('' '', $pun_repository_ext[''options'']), ''</p>'';\n\n?>\n		</div>\n<?php\n\n}\n\n?>\n		<div class="ct-box warn-box">\n			<p class="warn"><?php echo $lang_pun_repository[''Files mode and owner''] ?></p>\n		</div>\n<?php\n\nif (empty($pun_repository_parsed) && (count($pun_repository_skipped[''installed'']) > 0 || count($pun_repository_skipped[''has_dir'']) > 0))\n{\n	($hook = get_hook(''pun_repository_no_extensions'')) ? eval($hook) : null;\n\n	?>\n		<div class="ct-box info-box">\n			<p><?php echo $lang_pun_repository[''All installed or downloaded''] ?></p>\n		</div>\n	<?php\n\n}\n\n($hook = get_hook(''pun_repository_after_ext_list'')) ? eval($hook) : null;\n\n?>\n	</div>\n<?php', 1227304466, 5),
('aex_new_action', 'pun_repository', '// Clear pun_repository cache\nif (isset($_GET[''pun_repository_update'']))\n{\n	// Validate CSRF token\n	if (!isset($_POST[''csrf_token'']) && (!isset($_GET[''csrf_token'']) || $_GET[''csrf_token''] !== generate_form_token(''pun_repository_update'')))\n		csrf_confirm_form();\n\n	if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php''))\n		include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php'';\n	else\n		include $ext_info[''path''].''/lang/English/pun_repository.php'';\n\n	@unlink(FORUM_CACHE_DIR.''cache_pun_repository.php'');\n	if (file_exists(FORUM_CACHE_DIR.''cache_pun_repository.php''))\n		message($lang_pun_repository[''Unable to remove cached file''], '''', $lang_pun_repository[''PunBB Repository'']);\n\n	redirect($base_url.''/admin/extensions.php?section=manage'', $lang_pun_repository[''Cache has been successfully cleared'']);\n}\n\nif (isset($_GET[''pun_repository_download_and_install'']))\n{\n	$ext_id = preg_replace(''/[^0-9a-z_]/'', '''', $_GET[''pun_repository_download_and_install'']);\n\n	// Validate CSRF token\n	if (!isset($_POST[''csrf_token'']) && (!isset($_GET[''csrf_token'']) || $_GET[''csrf_token''] !== generate_form_token(''pun_repository_download_and_install_''.$ext_id)))\n		csrf_confirm_form();\n\n	// TODO: Should we check again for unresolved dependencies here?\n\n	if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php''))\n		include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php'';\n	else\n		include $ext_info[''path''].''/lang/English/pun_repository.php'';\n\n	require_once $ext_info[''path''].''/pun_repository.php'';\n\n	($hook = get_hook(''pun_repository_download_and_install_start'')) ? eval($hook) : null;\n\n	// Download extension\n	$pun_repository_error = pun_repository_download_extension($ext_id, $ext_data);\n\n	if ($pun_repository_error == '''')\n	{\n		if (empty($ext_data))\n			redirect($base_url.''/admin/extensions.php?section=manage'', $lang_pun_repository[''Incorrect manifest.xml'']);\n\n		// Validate manifest\n		$errors = validate_manifest($ext_data, $ext_id);\n		if (!empty($errors))\n			redirect($base_url.''/admin/extensions.php?section=manage'', $lang_pun_repository[''Incorrect manifest.xml'']);\n\n		// Everything is OK. Start installation.\n		redirect($base_url.''/admin/extensions.php?install=''.urlencode($ext_id), $lang_pun_repository[''Download successful'']);\n	}\n\n	($hook = get_hook(''pun_repository_download_and_install_end'')) ? eval($hook) : null;\n}\n\n// Handling the download and update extension action\nif (isset($_GET[''pun_repository_download_and_update'']))\n{\n	$ext_id = preg_replace(''/[^0-9a-z_]/'', '''', $_GET[''pun_repository_download_and_update'']);\n\n	// Validate CSRF token\n	if (!isset($_POST[''csrf_token'']) && (!isset($_GET[''csrf_token'']) || $_GET[''csrf_token''] !== generate_form_token(''pun_repository_download_and_update_''.$ext_id)))\n		csrf_confirm_form();\n\n	if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php''))\n		include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php'';\n	else\n		include $ext_info[''path''].''/lang/English/pun_repository.php'';\n\n	require_once $ext_info[''path''].''/pun_repository.php'';\n\n	$pun_repository_error = '''';\n\n	($hook = get_hook(''pun_repository_download_and_update_start'')) ? eval($hook) : null;\n\n	@pun_repository_rm_recursive(FORUM_ROOT.''extensions/''.$ext_id.''.old'');\n\n	// Check dependancies\n	$query = array(\n		''SELECT''	=> ''e.id'',\n		''FROM''		=> ''extensions AS e'',\n		''WHERE''		=> ''e.disabled=0 AND e.dependencies LIKE \\''%|''.$forum_db->escape($ext_id).''|%\\''''\n	);\n\n	($hook = get_hook(''aex_qr_get_disable_dependencies'')) ? eval($hook) : null;\n	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);\n\n	if ($forum_db->num_rows($result) != 0)\n	{\n		$dependency = $forum_db->fetch_assoc($result);\n		$pun_repository_error = sprintf($lang_admin[''Disable dependency''], $dependency[''id'']);\n	}\n\n	if ($pun_repository_error == '''' && ($ext_id != $ext_info[''id'']))\n	{\n		// Disable extension\n		$query = array(\n			''UPDATE''	=> ''extensions'',\n			''SET''		=> ''disabled=1'',\n			''WHERE''		=> ''id=\\''''.$forum_db->escape($ext_id).''\\''''\n		);\n\n		($hook = get_hook(''aex_qr_update_disabled_status'')) ? eval($hook) : null;\n		$forum_db->query_build($query) or error(__FILE__, __LINE__);\n\n		// Regenerate the hooks cache\n		require_once FORUM_ROOT.''include/cache.php'';\n		generate_hooks_cache();\n	}\n\n	if ($pun_repository_error == '''')\n	{\n		if ($ext_id == $ext_info[''id''])\n		{\n			// Hey! That''s me!\n			// All the necessary files should be included before renaming old directory\n			// NOTE: Self-updating is to be tested more in real-life conditions\n			if (!defined(''PUN_REPOSITORY_TAR_EXTRACT_INCLUDED''))\n				require $ext_info[''path''].''/pun_repository_tar_extract.php'';\n		}\n\n		// Rename old extension dir\n		if (is_writable(FORUM_ROOT.''extensions/''.$ext_id) && @rename(FORUM_ROOT.''extensions/''.$ext_id, FORUM_ROOT.''extensions/''.$ext_id.''.old''))\n			$pun_repository_error = pun_repository_download_extension($ext_id, $ext_data); // Download extension\n		else\n			$pun_repository_error = sprintf($lang_pun_repository[''Unable to rename old dir''], FORUM_ROOT.''extensions/''.$ext_id);\n	}\n\n	if ($pun_repository_error == '''')\n	{\n		// Do we have extension dat at all? :-)\n		if (empty($ext_data))\n			$errors = array(true);\n\n		// Validate manifest\n		if (empty($errors))\n			$errors = validate_manifest($ext_data, $ext_id);\n\n		if (!empty($errors))\n			$pun_repository_error = $lang_pun_repository[''Incorrect manifest.xml''];\n	}\n\n	if ($pun_repository_error == '''')\n	{\n		($hook = get_hook(''pun_repository_download_and_update_ok'')) ? eval($hook) : null;\n\n		// Everything is OK. Start installation.\n		pun_repository_rm_recursive(FORUM_ROOT.''extensions/''.$ext_id.''.old'');\n		redirect($base_url.''/admin/extensions.php?install=''.urlencode($ext_id), $lang_pun_repository[''Download successful'']);\n	}\n\n	($hook = get_hook(''pun_repository_download_and_update_error'')) ? eval($hook) : null;\n\n	// Get old version back\n	@pun_repository_rm_recursive(FORUM_ROOT.''extensions/''.$ext_id);\n	@rename(FORUM_ROOT.''extensions/''.$ext_id.''.old'', FORUM_ROOT.''extensions/''.$ext_id);\n\n	// Enable extension\n	$query = array(\n		''UPDATE''	=> ''extensions'',\n		''SET''		=> ''disabled=0'',\n		''WHERE''		=> ''id=\\''''.$forum_db->escape($ext_id).''\\''''\n	);\n\n	($hook = get_hook(''aex_qr_update_enabled_status'')) ? eval($hook) : null;\n	$forum_db->query_build($query) or error(__FILE__, __LINE__);\n\n	// Regenerate the hooks cache\n	require_once FORUM_ROOT.''include/cache.php'';\n	generate_hooks_cache();\n\n	($hook = get_hook(''pun_repository_download_and_update_end'')) ? eval($hook) : null;\n}\n\n// Do we have some error?\nif (!empty($pun_repository_error))\n{\n	// Setup breadcrumbs\n	$forum_page[''crumbs''] = array(\n		array($forum_config[''o_board_title''], forum_link($forum_url[''index''])),\n		array($lang_admin_common[''Forum administration''], forum_link($forum_url[''admin_index''])),\n		array($lang_admin_common[''Extensions''], forum_link($forum_url[''admin_extensions_manage''])),\n		array($lang_admin_common[''Install extensions''], forum_link($forum_url[''admin_extensions_install''])),\n		$lang_pun_repository[''PunBB Repository'']\n	);\n\n	($hook = get_hook(''pun_repository__pre_header_load'')) ? eval($hook) : null;\n\n	define(''FORUM_PAGE_SECTION'', ''extensions'');\n	define(''FORUM_PAGE'', ''admin-extensions-pun-repository'');\n	require FORUM_ROOT.''header.php'';\n\n	// START SUBST - <!-- forum_main -->\n	ob_start();\n\n	($hook = get_hook(''pun_repository_display_error_output_start'')) ? eval($hook) : null;\n\n?>\n	<div class="main-subhead">\n		<h2 class="hn"><span><?php echo $lang_pun_repository[''PunBB Repository''] ?></span></h2>\n	</div>\n	<div class="main-content">\n		<div class="ct-box warn-box">\n			<p class="warn"><?php echo $pun_repository_error ?></p>\n		</div>\n	</div>\n</div>\n<?php\n\n	($hook = get_hook(''pun_repository_display_error_pre_ob_end'')) ? eval($hook) : null;\n\n	$tpl_temp = trim(ob_get_contents());\n	$tpl_main = str_replace(''<!-- forum_main -->'', $tpl_temp, $tpl_main);\n	ob_end_clean();\n	// END SUBST - <!-- forum_main -->\n\n	require FORUM_ROOT.''footer.php'';\n}', 1227304466, 5),
('aex_section_manage_pre_header_load', 'pun_repository', 'if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php''))\n	include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php'';\nelse\n	include $ext_info[''path''].''/lang/English/pun_repository.php'';\n\nrequire_once $ext_info[''path''].''/pun_repository.php'';\n\nif (!defined(''PUN_REPOSITORY_EXTENSIONS_LOADED'') && file_exists(FORUM_CACHE_DIR.''cache_pun_repository.php''))\n	include FORUM_CACHE_DIR.''cache_pun_repository.php'';\n\nif (!defined(''FORUM_EXT_VERSIONS_LOADED'') && file_exists(FORUM_CACHE_DIR.''cache_ext_version_notifications.php''))\n	include FORUM_CACHE_DIR.''cache_ext_version_notifications.php'';\n\n// Regenerate cache only if automatic updates are enabled and if the cache is more than 12 hours old\nif (!defined(''PUN_REPOSITORY_EXTENSIONS_LOADED'') || !defined(''FORUM_EXT_VERSIONS_LOADED'') || ($pun_repository_extensions_timestamp < $forum_ext_versions_update_cache))\n{\n	if (pun_repository_generate_cache($pun_repository_error))\n		require FORUM_CACHE_DIR.''cache_pun_repository.php'';\n}', 1227304466, 5),
('aex_section_manage_pre_ext_actions', 'pun_repository', 'if (defined(''PUN_REPOSITORY_EXTENSIONS_LOADED'') && isset($pun_repository_extensions[$id]) && version_compare($ext[''version''], $pun_repository_extensions[$id][''version''], ''<'') && is_writable(FORUM_ROOT.''extensions/''.$id))\n{\n	// Check for unresolved dependencies\n	if (isset($pun_repository_extensions[$id][''dependencies'']))\n		$pun_repository_extensions[$id][''dependencies''] = pun_repository_check_dependencies($inst_exts, $pun_repository_extensions[$id][''dependencies'']);\n\n	if (empty($pun_repository_extensions[$id][''dependencies''][''unresolved'']))\n		$forum_page[''ext_actions''][] = ''<a href="''.$base_url.''/admin/extensions.php?pun_repository_download_and_update=''.$id.''&amp;csrf_token=''.generate_form_token(''pun_repository_download_and_update_''.$id).''">''.$lang_pun_repository[''Download and update''].''</a>'';\n}', 1227304466, 5),
('co_common', 'pun_repository', '$pun_extensions_used = array_merge(isset($pun_extensions_used) ? $pun_extensions_used : array(), array($ext_info[''id'']));', 1227304466, 5),
('ft_about_end', 'pun_repository', 'if (!defined(''PUN_EXTENSIONS_USED'') && !empty($pun_extensions_used))\n{\n	define(''PUN_EXTENSIONS_USED'', 1);\n	echo ''<p id="extensions-used">Currently used extensions: ''.implode('', '', $pun_extensions_used).''. Copyright &copy; 2008 <a href="http://punbb.informer.com/">PunBB</a></p>'';\n}', 1227304466, 10);

-- --------------------------------------------------------

--
-- Table structure for table `pun_forums`
--

CREATE TABLE IF NOT EXISTS `pun_forums` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `forum_name` varchar(80) character set utf8 NOT NULL default 'New forum',
  `forum_desc` text character set utf8,
  `redirect_url` varchar(100) character set utf8 default NULL,
  `moderators` text character set utf8,
  `num_topics` mediumint(8) unsigned NOT NULL default '0',
  `num_posts` mediumint(8) unsigned NOT NULL default '0',
  `last_post` int(10) unsigned default NULL,
  `last_post_id` int(10) unsigned default NULL,
  `last_poster` varchar(200) character set utf8 default NULL,
  `sort_by` tinyint(1) NOT NULL default '0',
  `disp_position` int(10) NOT NULL default '0',
  `cat_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `pun_forums`
--

INSERT INTO `pun_forums` (`id`, `forum_name`, `forum_desc`, `redirect_url`, `moderators`, `num_topics`, `num_posts`, `last_post`, `last_post_id`, `last_poster`, `sort_by`, `disp_position`, `cat_id`) VALUES
(1, 'SUIT Announcements', 'Announcements pertaining to the site, SUIT releases and the like shall be posted here for reference.', NULL, NULL, 0, 0, NULL, NULL, NULL, 0, 0, 1),
(2, 'Help/Questions', 'Please read the <a href="http://docs.suitframework.com/">Documentation</a> before posting a question on this forum.<br /><br />\nIf you have any questions pertaining to SUIT, then post them here.', NULL, NULL, 0, 0, NULL, NULL, NULL, 0, 1, 1),
(3, 'Feedback and Suggestions', 'We value your feedback. We want to hear what you think about SUIT Framework, or post your suggestions for the future.', NULL, NULL, 0, 0, NULL, NULL, NULL, 0, 2, 1),
(5, 'Staff Only', 'Staff Only', NULL, NULL, 1, 30, 1227308732, 45, 'Brandon', 0, 5, 1),
(6, 'Misc.', 'Talk about other programming languages or anything else that comes to mind.', NULL, NULL, 0, 0, NULL, NULL, NULL, 0, 4, 1),
(7, 'Websites', 'Show off your websites using SUIT.', NULL, NULL, 0, 0, NULL, NULL, NULL, 0, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pun_forum_perms`
--

CREATE TABLE IF NOT EXISTS `pun_forum_perms` (
  `group_id` int(10) NOT NULL default '0',
  `forum_id` int(10) NOT NULL default '0',
  `read_forum` tinyint(1) NOT NULL default '1',
  `post_replies` tinyint(1) NOT NULL default '1',
  `post_topics` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`group_id`,`forum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pun_forum_perms`
--

INSERT INTO `pun_forum_perms` (`group_id`, `forum_id`, `read_forum`, `post_replies`, `post_topics`) VALUES
(3, 5, 0, 0, 0),
(4, 5, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pun_groups`
--

CREATE TABLE IF NOT EXISTS `pun_groups` (
  `g_id` int(10) unsigned NOT NULL auto_increment,
  `g_title` varchar(50) character set utf8 NOT NULL,
  `g_user_title` varchar(50) character set utf8 default NULL,
  `g_moderator` tinyint(1) NOT NULL default '0',
  `g_mod_edit_users` tinyint(1) NOT NULL default '0',
  `g_mod_rename_users` tinyint(1) NOT NULL default '0',
  `g_mod_change_passwords` tinyint(1) NOT NULL default '0',
  `g_mod_ban_users` tinyint(1) NOT NULL default '0',
  `g_read_board` tinyint(1) NOT NULL default '1',
  `g_view_users` tinyint(1) NOT NULL default '1',
  `g_post_replies` tinyint(1) NOT NULL default '1',
  `g_post_topics` tinyint(1) NOT NULL default '1',
  `g_post_polls` tinyint(1) NOT NULL default '1',
  `g_edit_posts` tinyint(1) NOT NULL default '1',
  `g_delete_posts` tinyint(1) NOT NULL default '1',
  `g_delete_topics` tinyint(1) NOT NULL default '1',
  `g_set_title` tinyint(1) NOT NULL default '1',
  `g_search` tinyint(1) NOT NULL default '1',
  `g_search_users` tinyint(1) NOT NULL default '1',
  `g_edit_subjects_interval` smallint(6) NOT NULL default '30',
  `g_post_flood` smallint(6) NOT NULL default '30',
  `g_search_flood` smallint(6) NOT NULL default '60',
  PRIMARY KEY  (`g_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pun_groups`
--

INSERT INTO `pun_groups` (`g_id`, `g_title`, `g_user_title`, `g_moderator`, `g_mod_edit_users`, `g_mod_rename_users`, `g_mod_change_passwords`, `g_mod_ban_users`, `g_read_board`, `g_view_users`, `g_post_replies`, `g_post_topics`, `g_post_polls`, `g_edit_posts`, `g_delete_posts`, `g_delete_topics`, `g_set_title`, `g_search`, `g_search_users`, `g_edit_subjects_interval`, `g_post_flood`, `g_search_flood`) VALUES
(1, 'Administrators', 'Administrator', 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0),
(2, 'Moderators', 'Moderator', 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0),
(3, 'Guest', NULL, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0),
(4, 'Members', NULL, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 300, 60, 30);

-- --------------------------------------------------------

--
-- Table structure for table `pun_online`
--

CREATE TABLE IF NOT EXISTS `pun_online` (
  `user_id` int(10) unsigned NOT NULL default '1',
  `ident` varchar(200) character set utf8 NOT NULL,
  `logged` int(10) unsigned NOT NULL default '0',
  `idle` tinyint(1) NOT NULL default '0',
  `csrf_token` varchar(40) character set utf8 NOT NULL,
  `prev_url` varchar(255) character set utf8 default NULL,
  `last_post` int(10) unsigned default NULL,
  `last_search` int(10) unsigned default NULL,
  UNIQUE KEY `pun_online_user_id_ident_idx` (`user_id`,`ident`),
  KEY `pun_online_user_id_idx` (`user_id`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pun_online`
--

INSERT INTO `pun_online` (`user_id`, `ident`, `logged`, `idle`, `csrf_token`, `prev_url`, `last_post`, `last_search`) VALUES
(1, '68.173.205.18', 1227563788, 0, '1f2f5d2869fb004b07febc599320c567c47cae7e', 'http://www.suitframework.com/forumnew/search.php?action=show_recent', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pun_posts`
--

CREATE TABLE IF NOT EXISTS `pun_posts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `poster` varchar(200) character set utf8 NOT NULL,
  `poster_id` int(10) unsigned NOT NULL default '1',
  `poster_ip` varchar(39) character set utf8 default NULL,
  `poster_email` varchar(80) character set utf8 default NULL,
  `message` text character set utf8,
  `hide_smilies` tinyint(1) NOT NULL default '0',
  `posted` int(10) unsigned NOT NULL default '0',
  `edited` int(10) unsigned default NULL,
  `edited_by` varchar(200) character set utf8 default NULL,
  `topic_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pun_posts_topic_id_idx` (`topic_id`),
  KEY `pun_posts_multi_idx` (`poster_id`,`topic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `pun_posts`
--

INSERT INTO `pun_posts` (`id`, `poster`, `poster_id`, `poster_ip`, `poster_email`, `message`, `hide_smilies`, `posted`, `edited`, `edited_by`, `topic_id`) VALUES
(17, 'Faltzer', 3, '68.173.205.18', NULL, '[quote=Brandon]By the way, if you''re gonna have an MM icon, at least animate it right.[/quote]\nYeah, because you [b]definitely[/b] have a MM character as your avatar. Oh wait.', 0, 1225927730, NULL, NULL, 2),
(16, 'Brandon', 2, '69.115.232.220', NULL, 'Also, it seems like the background loads REALLY slowly in my school computer. I replaced it with the BG Color. We can discuss an alternative later.', 0, 1225915089, NULL, NULL, 2),
(15, 'Brandon', 2, '166.109.124.231', NULL, 'By the way, if you''re gonna have an MM icon, at least animate it right.', 0, 1225904160, NULL, NULL, 2),
(13, 'Brandon', 2, '69.115.232.220', NULL, 'Fixed error log. Please fix overall aesthetics. We should have a finished layout soon. Afterward we need to make the frontend.', 0, 1225810786, NULL, NULL, 2),
(14, 'Faltzer', 3, '68.173.205.18', NULL, 'Didn''t get to finish the interface because of sleep, though will complete fully tomorrow.', 0, 1225866193, NULL, NULL, 2),
(12, 'Brandon', 2, '69.115.232.220', NULL, 'Edit below, reply to chat.\n\nFeatures:\n\n-File Manager\n-Installation Restrictions\n-Enhanced Layout\n-Custom Editor\n-Import/Export Templates, Languages, and Themes, most likely in XML Format.\n\nBugs:\n\n-Permissions\n-.php', 0, 1225776407, NULL, NULL, 2),
(18, 'Brandon', 2, '69.115.232.220', NULL, 'I could reanimate it for you if you''d like.', 0, 1225934023, NULL, NULL, 2),
(19, 'Faltzer', 3, '68.173.205.18', NULL, 'Doing some Aesthetics right now. I noticed in the CSS we still used that [openingparenthesis] crap. Shouldn''t we remove parenthesis now that we don''t have that dynamicy stuff?\n\n\nEDIT: Err. I dunno what I did to the admin_notesmessage template that the <1> isn''t replaced by a success message. Either way, it shouldn''t even be replaced until POST Data has been sent.\n\nIt must seem like the replacement is gone, because when I last edited it with the template editor it was fine a moment ago. Please check this out. :/\n\n\nEDIT 2: pun_post_silentedit doesn''t exist as a language btw.', 0, 1225964126, NULL, NULL, 2),
(20, 'Brandon', 2, '69.115.232.220', NULL, 'You can remove openingparenthesis from CSS and JS. Other places (If applicable), leave them.\n\nFixed and Fixed.\n\nLastly, remember, this is a FRAMEWORK. The user will be completely changing the layout. There is no reason for us to be using files with such a filesize. Make them GIF''s or something.\n\nEdit: I did. Still, in IE, on it''s first load, the BG doesn''t seem to completely load in IE, and in Firefox, there is a weird background overlap.\n\nNow make my corners rounded in IE.\n\nP.S: [img]http://img355.imageshack.us/img355/645/99588702ov7.gif[/img]\n\nEdit2: You should probably change the banner to say SUIT. SUIT is a framework, but the thing is not called the SUIT Framework. The admin center will be called the tools you need to get started with it. :P', 0, 1225984018, NULL, NULL, 2),
(21, 'Brandon', 2, '69.115.232.220', NULL, 'OK Chris, no more jokes. Your work has been slipping terribly. This interface was one of the few jobs I have given you this week, and you deleted all of the file managing inside the templates post by accident. Oh, my bad, you left one. But...\n\n[b]You put the DELETE FILE inside the EDIT TEMPLATE POST.[/b]\n\nIf I edited admin_templatescontent, we''d have to redo the entire thing.\n\nPlease, if you want to be a part of this project, work the way you used to. It''s been disappointing before, but this is just ridiculous.', 0, 1226000184, NULL, NULL, 2),
(22, 'Brandon', 2, '69.115.232.220', NULL, 'Other than language transfer and PunBB stuff, registration is done.', 0, 1226001905, NULL, NULL, 2),
(23, 'Faltzer', 3, '68.173.205.18', NULL, 'I''m Sorry. I''ve been unmotivated to continue this project after these conundrums have been coming up. I''ll make an attempt to step it up.\n\n\nAlso, I''m going to fix the GIF slip-ups you''ve done with the imagery. :/', 0, 1226006397, NULL, NULL, 2),
(24, 'Brandon', 2, '69.115.232.220', NULL, 'How were they slip-ups? They look just as good to me.\n\nAlso, go on MSN.', 0, 1226006451, NULL, NULL, 2),
(25, 'Faltzer', 3, '68.173.205.18', NULL, 'Well, only the success one was a slip-up, since there was no transparency on it. Adding transparency to it was going to worsen the quality of it, so I just kept the background of the DIV tacked to it instead.', 0, 1226006674, NULL, NULL, 2),
(26, 'Faltzer', 3, '68.173.205.18', NULL, 'Oh, and by the way, the logo was meant to be a DIV. If you wanted to make it clickable, I could''ve done it. Doing so now.', 0, 1226006748, NULL, NULL, 2),
(27, 'Brandon', 2, '69.115.232.220', NULL, 'Old function on, new function will be finished tomorrow. You may continue working.', 0, 1226029115, NULL, NULL, 2),
(31, 'Brandon', 2, '69.115.232.220', NULL, 'Template Count = 74. Isn''t it great when we realize our inefficiencies and fix them? I hope this will inspire you to clean up the files itself...', 0, 1226457930, NULL, NULL, 2),
(32, 'Brandon', 2, '166.109.124.226', NULL, 'A little premature decision...it seems that x_content is necessary just because we''re embedding it in multiple places. We obviously don''t want a nested template, so I just added it for index, errorlog, and templates as it''s going to be in the PunBB Admin.', 0, 1226500105, NULL, NULL, 2),
(33, 'Brandon', 2, '69.115.232.220', NULL, 'Change Password complete. I have the theory for retrieving the password. I was going to suggest having it send a link with the id and md5 as get variables, have it log-in using that, and then be directed to the change password. Won''t start until you tell me that is a valid idea for it.\n\nDid a bit of misc. cleaning. Fixed the apparently broken caching system.', 0, 1226523734, NULL, NULL, 2),
(34, 'Faltzer', 3, '68.173.205.18', NULL, 'You realize that that''s a very bad idea, especially once hackers can bruteforce encryption, right? This is why implementing session id''s is going to be required for this if you plan to do it as such. But in general, it''s inefficient.\n\nI suggest when you forget your password, you should input the email under which it was done, it sends an email with a new password, making sure the DB is updated before sending the new unencrypted password string.', 0, 1226524666, NULL, NULL, 2),
(35, 'Brandon', 2, '69.115.232.220', NULL, 'Verdict: E-Mail sent with a random string, string logs you in, string expires, user is redirected to change their password.', 0, 1226529068, NULL, NULL, 2),
(41, 'Faltzer', 3, '68.173.205.18', NULL, '[b]Completed[/b]\nAdding & Renaming Sets\nSet Simple deletion (recursive deletion does not work, since the loop never ends. :\\)\n\n[b]Needs Completion/Fixing[/b]\nAdding/Editing the template file code (We need to talk about this, which is why I have to wait until you come for this, since I only just realized one thing. :\\)\nCloning (Can be based off recursive deletion, which is currently, indisposed)\nRecursive deletion (Some looping non-sense occurs.)', 0, 1227092949, NULL, NULL, 2),
(36, 'Brandon', 2, '166.109.124.226', NULL, 'I started and must finish making it so when templates are submitted, it puts everything you put back into the boxes so you don''t have to rewrite everything.', 0, 1226674630, NULL, NULL, 2),
(37, 'Brandon', 2, '69.115.232.220', NULL, 'While cleaning up error log, I noticed that the errors are no longer being logged. Get on that...', 0, 1226694290, NULL, NULL, 2),
(38, 'Faltzer', 3, '68.173.205.18', NULL, 'While thinking about the permissions issue, I thought of using FTP over PHP file creation functions If you think about it, it is way more efficient than the file functions we currently use.\n\nFor one, files can actually be created without ownership/permission problems, since the owner and group will obviously be the same user, thus, giving him non-obtrusive privileges to the FTP, and giving the script permission to do it''s thing. It gives more control and power to the user in general, really.\n\nIt''s so worth thinking about this, because it''d be more beneficial in the long run for both us and the users, because we''re using an efficient protocol that was meant for file transferring.', 0, 1226728631, NULL, NULL, 2),
(39, 'Brandon', 2, '69.115.232.220', NULL, 'FTP is something I''ve always thought about. Though, the thing about that is, I really don''t know much about it. Honestly, if FTP was secure enough, I''d say let them update the files from the CP. That would be awesome. But I don''t know if that''s a secure thing to do. If you want to look into it, by all means, do so.', 0, 1226764777, NULL, NULL, 2),
(40, 'Faltzer', 3, '68.173.205.18', NULL, 'I can do it if you really have that much a problem with it. -_-;', 0, 1226766574, NULL, NULL, 2),
(43, 'Brandon', 2, '69.115.232.220', NULL, 'http://forum.suitframework.com/admin_index.php', 0, 1227231586, NULL, NULL, 2),
(44, 'Brandon', 2, '69.115.232.220', NULL, 'Although Bugzilla still could work, George mentioned this:\n\nhttp://bugs.php.net/source.php?url=/bug.php\n\nIf we could construct a DB for this, I think it''d work nicely as it''s what used on php.net.', 0, 1227238842, NULL, NULL, 2),
(45, 'Brandon', 2, '69.115.232.220', NULL, 'By the way, how can a reputable forum system NOT make ANY form of upgrader? Converting this shit will take a while.', 0, 1227308732, NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pun_ranks`
--

CREATE TABLE IF NOT EXISTS `pun_ranks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `rank` varchar(50) character set utf8 NOT NULL,
  `min_posts` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `pun_ranks`
--

INSERT INTO `pun_ranks` (`id`, `rank`, `min_posts`) VALUES
(1, 'New member', 0),
(2, 'Member', 10);

-- --------------------------------------------------------

--
-- Table structure for table `pun_reports`
--

CREATE TABLE IF NOT EXISTS `pun_reports` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `post_id` int(10) unsigned NOT NULL default '0',
  `topic_id` int(10) unsigned NOT NULL default '0',
  `forum_id` int(10) unsigned NOT NULL default '0',
  `reported_by` int(10) unsigned NOT NULL default '0',
  `created` int(10) unsigned NOT NULL default '0',
  `message` text character set utf8,
  `zapped` int(10) unsigned default NULL,
  `zapped_by` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `pun_reports_zapped_idx` (`zapped`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pun_reports`
--

INSERT INTO `pun_reports` (`id`, `post_id`, `topic_id`, `forum_id`, `reported_by`, `created`, `message`, `zapped`, `zapped_by`) VALUES
(1, 8, 1, 1, 1, 1225332439, 'NIGGER NIGGER JOHNS JOHNS', 1225394028, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pun_search_cache`
--

CREATE TABLE IF NOT EXISTS `pun_search_cache` (
  `id` int(10) unsigned NOT NULL default '0',
  `ident` varchar(200) character set utf8 NOT NULL,
  `search_data` text character set utf8,
  PRIMARY KEY  (`id`),
  KEY `pun_search_cache_ident_idx` (`ident`(8))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pun_search_cache`
--

INSERT INTO `pun_search_cache` (`id`, `ident`, `search_data`) VALUES
(10551880, '80.60.80.66', 'a:5:{s:14:"search_results";s:1:"1";s:8:"num_hits";i:1;s:7:"sort_by";i:4;s:8:"sort_dir";s:4:"DESC";s:7:"show_as";s:6:"topics";}');

-- --------------------------------------------------------

--
-- Table structure for table `pun_search_matches`
--

CREATE TABLE IF NOT EXISTS `pun_search_matches` (
  `post_id` int(10) unsigned NOT NULL default '0',
  `word_id` int(10) unsigned NOT NULL default '0',
  `subject_match` tinyint(1) NOT NULL default '0',
  KEY `pun_search_matches_word_id_idx` (`word_id`),
  KEY `pun_search_matches_post_id_idx` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pun_search_matches`
--

INSERT INTO `pun_search_matches` (`post_id`, `word_id`, `subject_match`) VALUES
(13, 74, 0),
(13, 73, 0),
(13, 72, 0),
(13, 71, 0),
(13, 70, 0),
(13, 69, 0),
(12, 66, 1),
(12, 67, 1),
(12, 35, 0),
(12, 36, 0),
(12, 37, 0),
(12, 38, 0),
(12, 39, 0),
(12, 40, 0),
(12, 41, 0),
(12, 42, 0),
(12, 43, 0),
(12, 44, 0),
(12, 45, 0),
(12, 46, 0),
(12, 47, 0),
(12, 48, 0),
(12, 49, 0),
(12, 50, 0),
(12, 51, 0),
(12, 52, 0),
(12, 53, 0),
(12, 54, 0),
(12, 55, 0),
(12, 56, 0),
(12, 57, 0),
(12, 58, 0),
(12, 417, 0),
(41, 485, 0),
(13, 68, 0),
(13, 45, 0),
(13, 62, 0),
(41, 488, 0),
(41, 293, 0),
(13, 75, 0),
(13, 76, 0),
(13, 77, 0),
(13, 78, 0),
(13, 79, 0),
(13, 80, 0),
(13, 81, 0),
(13, 82, 0),
(14, 81, 0),
(14, 83, 0),
(14, 84, 0),
(14, 85, 0),
(14, 86, 0),
(14, 87, 0),
(14, 88, 0),
(14, 89, 0),
(14, 90, 0),
(14, 91, 0),
(14, 92, 0),
(14, 93, 0),
(15, 75, 0),
(15, 81, 0),
(15, 94, 0),
(15, 95, 0),
(15, 96, 0),
(15, 97, 0),
(15, 98, 0),
(15, 99, 0),
(15, 100, 0),
(16, 81, 0),
(16, 101, 0),
(16, 102, 0),
(16, 103, 0),
(16, 104, 0),
(16, 105, 0),
(16, 106, 0),
(16, 107, 0),
(16, 108, 0),
(16, 109, 0),
(16, 110, 0),
(16, 111, 0),
(16, 112, 0),
(16, 113, 0),
(16, 114, 0),
(16, 115, 0),
(16, 116, 0),
(17, 75, 0),
(17, 81, 0),
(17, 94, 0),
(17, 95, 0),
(17, 96, 0),
(17, 97, 0),
(17, 98, 0),
(17, 99, 0),
(17, 100, 0),
(17, 117, 0),
(17, 124, 0),
(17, 119, 0),
(17, 87, 0),
(17, 122, 0),
(17, 121, 0),
(17, 125, 0),
(17, 120, 0),
(17, 123, 0),
(18, 126, 0),
(18, 128, 0),
(18, 103, 0),
(18, 127, 0),
(18, 119, 0),
(18, 129, 0),
(19, 73, 0),
(19, 75, 0),
(19, 81, 0),
(19, 100, 0),
(19, 130, 0),
(19, 131, 0),
(19, 132, 0),
(19, 133, 0),
(19, 134, 0),
(19, 135, 0),
(19, 136, 0),
(19, 137, 0),
(19, 138, 0),
(19, 139, 0),
(19, 140, 0),
(19, 141, 0),
(19, 142, 0),
(19, 143, 0),
(19, 144, 0),
(19, 145, 0),
(19, 47, 0),
(19, 35, 0),
(19, 70, 0),
(19, 87, 0),
(19, 94, 0),
(19, 103, 0),
(19, 110, 0),
(19, 111, 0),
(19, 146, 0),
(19, 147, 0),
(19, 148, 0),
(19, 149, 0),
(19, 150, 0),
(19, 151, 0),
(19, 152, 0),
(19, 153, 0),
(19, 154, 0),
(19, 155, 0),
(19, 156, 0),
(19, 157, 0),
(19, 158, 0),
(19, 159, 0),
(19, 160, 0),
(19, 161, 0),
(19, 162, 0),
(19, 163, 0),
(19, 164, 0),
(19, 165, 0),
(19, 166, 0),
(19, 167, 0),
(19, 168, 0),
(19, 169, 0),
(19, 170, 0),
(19, 171, 0),
(19, 172, 0),
(19, 173, 0),
(19, 174, 0),
(19, 175, 0),
(19, 176, 0),
(19, 177, 0),
(19, 178, 0),
(19, 179, 0),
(19, 180, 0),
(19, 181, 0),
(20, 52, 0),
(20, 45, 0),
(20, 68, 0),
(20, 80, 0),
(20, 81, 0),
(20, 90, 0),
(20, 111, 0),
(20, 113, 0),
(20, 119, 0),
(20, 128, 0),
(20, 134, 0),
(20, 138, 0),
(20, 141, 0),
(20, 175, 0),
(20, 182, 0),
(20, 183, 0),
(20, 184, 0),
(20, 185, 0),
(20, 186, 0),
(20, 187, 0),
(20, 188, 0),
(20, 189, 0),
(20, 190, 0),
(20, 191, 0),
(20, 192, 0),
(20, 193, 0),
(20, 194, 0),
(20, 195, 0),
(20, 196, 0),
(20, 197, 0),
(20, 198, 0),
(20, 199, 0),
(20, 200, 0),
(20, 201, 0),
(20, 202, 0),
(20, 104, 0),
(20, 149, 0),
(20, 178, 0),
(20, 35, 0),
(20, 206, 0),
(20, 204, 0),
(20, 203, 0),
(20, 205, 0),
(20, 208, 0),
(20, 164, 0),
(20, 135, 0),
(20, 207, 0),
(20, 209, 0),
(20, 211, 0),
(20, 132, 0),
(20, 210, 0),
(20, 212, 0),
(20, 223, 0),
(20, 216, 0),
(20, 219, 0),
(20, 222, 0),
(20, 224, 0),
(20, 215, 0),
(20, 213, 0),
(20, 84, 0),
(20, 227, 0),
(20, 79, 0),
(20, 221, 0),
(20, 214, 0),
(20, 217, 0),
(20, 74, 0),
(20, 226, 0),
(20, 218, 0),
(20, 220, 0),
(20, 225, 0),
(21, 52, 0),
(21, 50, 0),
(21, 35, 0),
(21, 66, 0),
(21, 70, 0),
(21, 75, 0),
(21, 81, 0),
(21, 86, 0),
(21, 94, 0),
(21, 119, 0),
(21, 123, 0),
(21, 136, 0),
(21, 151, 0),
(21, 158, 0),
(21, 160, 0),
(21, 161, 0),
(21, 169, 0),
(21, 170, 0),
(21, 175, 0),
(21, 203, 0),
(21, 219, 0),
(21, 220, 0),
(21, 228, 0),
(21, 229, 0),
(21, 230, 0),
(21, 231, 0),
(21, 232, 0),
(21, 233, 0),
(21, 234, 0),
(21, 235, 0),
(21, 236, 0),
(21, 237, 0),
(21, 238, 0),
(21, 239, 0),
(21, 240, 0),
(21, 241, 0),
(21, 242, 0),
(21, 243, 0),
(21, 244, 0),
(21, 245, 0),
(21, 246, 0),
(21, 247, 0),
(21, 248, 0),
(21, 249, 0),
(21, 250, 0),
(21, 251, 0),
(21, 252, 0),
(21, 253, 0),
(21, 254, 0),
(21, 255, 0),
(21, 256, 0),
(21, 257, 0),
(21, 258, 0),
(22, 52, 0),
(22, 263, 0),
(22, 180, 0),
(22, 184, 0),
(22, 261, 0),
(22, 262, 0),
(22, 145, 0),
(22, 259, 0),
(22, 260, 0),
(23, 71, 0),
(23, 75, 0),
(23, 80, 0),
(23, 81, 0),
(23, 101, 0),
(23, 111, 0),
(23, 161, 0),
(23, 175, 0),
(23, 253, 0),
(23, 263, 0),
(23, 264, 0),
(23, 265, 0),
(23, 266, 0),
(23, 267, 0),
(23, 268, 0),
(23, 269, 0),
(23, 270, 0),
(23, 271, 0),
(23, 272, 0),
(23, 273, 0),
(23, 274, 0),
(23, 275, 0),
(23, 276, 0),
(23, 277, 0),
(23, 278, 0),
(23, 279, 0),
(23, 280, 0),
(24, 101, 0),
(24, 285, 0),
(24, 281, 0),
(24, 257, 0),
(24, 284, 0),
(24, 286, 0),
(24, 287, 0),
(24, 278, 0),
(24, 283, 0),
(24, 282, 0),
(25, 293, 0),
(25, 104, 0),
(25, 297, 0),
(25, 276, 0),
(25, 299, 0),
(25, 227, 0),
(25, 257, 0),
(25, 296, 0),
(25, 233, 0),
(25, 289, 0),
(25, 295, 0),
(25, 291, 0),
(25, 290, 0),
(25, 153, 0),
(25, 298, 0),
(25, 81, 0),
(25, 195, 0),
(25, 292, 0),
(25, 170, 0),
(25, 288, 0),
(25, 294, 0),
(26, 52, 0),
(26, 303, 0),
(26, 304, 0),
(26, 297, 0),
(26, 130, 0),
(26, 263, 0),
(26, 227, 0),
(26, 300, 0),
(26, 80, 0),
(26, 301, 0),
(26, 132, 0),
(26, 81, 0),
(26, 302, 0),
(26, 170, 0),
(26, 94, 0),
(26, 119, 0),
(27, 267, 0),
(27, 76, 0),
(27, 306, 0),
(27, 308, 0),
(27, 307, 0),
(27, 305, 0),
(27, 93, 0),
(27, 90, 0),
(27, 309, 0),
(27, 119, 0),
(31, 322, 0),
(31, 52, 0),
(31, 329, 0),
(31, 321, 0),
(31, 198, 0),
(31, 71, 0),
(31, 323, 0),
(31, 327, 0),
(31, 326, 0),
(31, 328, 0),
(31, 152, 0),
(31, 330, 0),
(31, 325, 0),
(31, 324, 0),
(31, 151, 0),
(31, 81, 0),
(31, 188, 0),
(31, 175, 0),
(31, 167, 0),
(31, 90, 0),
(31, 119, 0),
(32, 340, 0),
(32, 223, 0),
(32, 52, 0),
(32, 87, 0),
(32, 333, 0),
(32, 143, 0),
(32, 336, 0),
(32, 342, 0),
(32, 128, 0),
(32, 276, 0),
(32, 341, 0),
(32, 203, 0),
(32, 257, 0),
(32, 331, 0),
(32, 337, 0),
(32, 335, 0),
(32, 339, 0),
(32, 338, 0),
(32, 185, 0),
(32, 332, 0),
(32, 261, 0),
(32, 102, 0),
(32, 151, 0),
(32, 50, 0),
(32, 137, 0),
(32, 81, 0),
(32, 251, 0),
(32, 282, 0),
(32, 334, 0),
(33, 52, 0),
(33, 68, 0),
(33, 75, 0),
(33, 81, 0),
(33, 84, 0),
(33, 91, 0),
(33, 111, 0),
(33, 119, 0),
(33, 128, 0),
(33, 137, 0),
(33, 149, 0),
(33, 157, 0),
(33, 170, 0),
(33, 197, 0),
(33, 215, 0),
(33, 227, 0),
(33, 276, 0),
(33, 343, 0),
(33, 344, 0),
(33, 345, 0),
(33, 346, 0),
(33, 347, 0),
(33, 348, 0),
(33, 349, 0),
(33, 350, 0),
(33, 351, 0),
(33, 352, 0),
(33, 353, 0),
(33, 354, 0),
(33, 355, 0),
(33, 356, 0),
(33, 357, 0),
(33, 358, 0),
(33, 359, 0),
(33, 360, 0),
(33, 361, 0),
(33, 362, 0),
(33, 363, 0),
(33, 364, 0),
(33, 365, 0),
(33, 366, 0),
(34, 74, 0),
(34, 81, 0),
(34, 100, 0),
(34, 111, 0),
(34, 119, 0),
(34, 123, 0),
(34, 128, 0),
(34, 137, 0),
(34, 167, 0),
(34, 170, 0),
(34, 175, 0),
(34, 394, 0),
(34, 393, 0),
(34, 244, 0),
(34, 256, 0),
(34, 263, 0),
(34, 276, 0),
(34, 113, 0),
(34, 307, 0),
(34, 324, 0),
(34, 343, 0),
(34, 346, 0),
(34, 359, 0),
(34, 367, 0),
(34, 368, 0),
(34, 369, 0),
(34, 370, 0),
(34, 371, 0),
(34, 219, 0),
(34, 392, 0),
(34, 374, 0),
(34, 375, 0),
(34, 376, 0),
(34, 377, 0),
(34, 378, 0),
(34, 379, 0),
(34, 380, 0),
(34, 381, 0),
(34, 382, 0),
(34, 383, 0),
(34, 384, 0),
(34, 385, 0),
(34, 386, 0),
(34, 387, 0),
(34, 388, 0),
(34, 389, 0),
(34, 390, 0),
(34, 391, 0),
(34, 395, 0),
(34, 203, 0),
(34, 199, 0),
(35, 215, 0),
(35, 397, 0),
(35, 400, 0),
(35, 399, 0),
(35, 343, 0),
(35, 398, 0),
(35, 401, 0),
(35, 162, 0),
(35, 391, 0),
(35, 402, 0),
(35, 192, 0),
(35, 396, 0),
(35, 111, 0),
(35, 119, 0),
(12, 473, 0),
(36, 52, 0),
(36, 404, 0),
(36, 408, 0),
(36, 410, 0),
(36, 143, 0),
(36, 407, 0),
(36, 85, 0),
(36, 75, 0),
(36, 409, 0),
(36, 386, 0),
(36, 163, 0),
(36, 246, 0),
(36, 406, 0),
(36, 411, 0),
(36, 226, 0),
(36, 405, 0),
(36, 50, 0),
(36, 81, 0),
(36, 167, 0),
(36, 119, 0),
(37, 404, 0),
(37, 415, 0),
(37, 362, 0),
(37, 69, 0),
(37, 413, 0),
(37, 84, 0),
(37, 62, 0),
(37, 416, 0),
(37, 414, 0),
(37, 133, 0),
(37, 137, 0),
(37, 81, 0),
(37, 412, 0),
(38, 63, 0),
(38, 52, 0),
(38, 81, 0),
(38, 87, 0),
(38, 90, 0),
(38, 94, 0),
(38, 106, 0),
(38, 113, 0),
(38, 119, 0),
(38, 128, 0),
(38, 137, 0),
(38, 170, 0),
(38, 175, 0),
(38, 192, 0),
(38, 197, 0),
(38, 198, 0),
(38, 203, 0),
(38, 220, 0),
(38, 229, 0),
(38, 233, 0),
(38, 240, 0),
(38, 259, 0),
(38, 282, 0),
(38, 291, 0),
(38, 301, 0),
(38, 338, 0),
(38, 394, 0),
(38, 412, 0),
(38, 418, 0),
(38, 419, 0),
(38, 420, 0),
(38, 421, 0),
(38, 422, 0),
(38, 423, 0),
(38, 424, 0),
(38, 425, 0),
(38, 426, 0),
(38, 427, 0),
(38, 428, 0),
(38, 429, 0),
(38, 430, 0),
(38, 431, 0),
(38, 432, 0),
(38, 433, 0),
(38, 434, 0),
(38, 435, 0),
(38, 436, 0),
(38, 437, 0),
(38, 438, 0),
(38, 439, 0),
(38, 440, 0),
(38, 441, 0),
(38, 442, 0),
(38, 443, 0),
(38, 444, 0),
(38, 445, 0),
(38, 446, 0),
(38, 447, 0),
(38, 448, 0),
(38, 449, 0),
(38, 450, 0),
(38, 451, 0),
(38, 452, 0),
(38, 453, 0),
(38, 454, 0),
(38, 455, 0),
(38, 456, 0),
(38, 457, 0),
(39, 419, 0),
(39, 239, 0),
(39, 458, 0),
(39, 468, 0),
(39, 219, 0),
(39, 466, 0),
(39, 469, 0),
(39, 143, 0),
(39, 463, 0),
(39, 198, 0),
(39, 182, 0),
(39, 423, 0),
(39, 461, 0),
(39, 409, 0),
(39, 227, 0),
(39, 265, 0),
(39, 459, 0),
(39, 464, 0),
(39, 284, 0),
(39, 470, 0),
(39, 460, 0),
(39, 106, 0),
(39, 217, 0),
(39, 462, 0),
(39, 471, 0),
(39, 202, 0),
(39, 137, 0),
(39, 367, 0),
(39, 81, 0),
(39, 188, 0),
(39, 220, 0),
(39, 89, 0),
(39, 422, 0),
(39, 465, 0),
(39, 251, 0),
(39, 170, 0),
(39, 467, 0),
(39, 119, 0),
(40, 113, 0),
(40, 75, 0),
(40, 227, 0),
(40, 460, 0),
(40, 472, 0),
(40, 106, 0),
(40, 137, 0),
(40, 111, 0),
(40, 119, 0),
(41, 474, 0),
(41, 487, 0),
(41, 479, 0),
(41, 481, 0),
(41, 484, 0),
(41, 483, 0),
(41, 240, 0),
(41, 60, 0),
(41, 486, 0),
(41, 482, 0),
(41, 221, 0),
(41, 480, 0),
(41, 475, 0),
(41, 477, 0),
(41, 476, 0),
(41, 478, 0),
(41, 291, 0),
(41, 151, 0),
(41, 81, 0),
(41, 66, 0),
(41, 419, 0),
(41, 493, 0),
(41, 113, 0),
(41, 491, 0),
(41, 430, 0),
(41, 489, 0),
(41, 128, 0),
(41, 75, 0),
(41, 495, 0),
(41, 257, 0),
(41, 496, 0),
(41, 79, 0),
(41, 497, 0),
(41, 498, 0),
(41, 494, 0),
(41, 233, 0),
(41, 289, 0),
(41, 492, 0),
(41, 131, 0),
(41, 490, 0),
(41, 220, 0),
(41, 175, 0),
(41, 157, 0),
(41, 125, 0),
(41, 384, 0),
(41, 374, 0),
(41, 119, 0),
(44, 203, 0),
(44, 450, 0),
(44, 507, 0),
(44, 128, 0),
(44, 126, 0),
(44, 509, 0),
(44, 506, 0),
(44, 505, 0),
(44, 508, 0),
(44, 510, 0),
(44, 511, 0),
(44, 135, 0),
(44, 428, 0),
(44, 175, 0),
(44, 136, 0),
(44, 148, 0),
(44, 66, 0),
(45, 514, 0),
(45, 113, 0),
(45, 517, 0),
(45, 515, 0),
(45, 513, 0),
(45, 281, 0),
(45, 80, 0),
(45, 221, 0),
(45, 512, 0),
(45, 518, 0),
(45, 366, 0),
(45, 519, 0),
(45, 81, 0),
(45, 175, 0),
(45, 516, 0),
(45, 94, 0),
(45, 412, 0),
(45, 90, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pun_search_words`
--

CREATE TABLE IF NOT EXISTS `pun_search_words` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `word` varchar(20) character set utf8 collate utf8_bin NOT NULL,
  PRIMARY KEY  (`word`),
  KEY `pun_search_words_id_idx` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=520 ;

--
-- Dumping data for table `pun_search_words`
--

INSERT INTO `pun_search_words` (`id`, `word`) VALUES
(65, 'conundrum'),
(64, '-implosion'),
(63, 'problems'),
(62, 'log'),
(61, '-error'),
(60, 'loop'),
(59, '-infinite'),
(58, 'bugs'),
(57, 'format'),
(56, 'xml'),
(55, 'likely'),
(54, 'most'),
(53, 'themes'),
(52, 'and'),
(51, 'languages'),
(50, 'templates'),
(49, 'export'),
(48, '-import'),
(47, 'editor'),
(46, '-custom'),
(45, 'layout'),
(44, '-enhanced'),
(43, 'restrictions'),
(24, 'test'),
(42, '-installation'),
(27, 'blah'),
(41, 'manager'),
(40, '-file'),
(39, 'features'),
(38, 'chat'),
(37, 'reply'),
(36, 'below'),
(35, 'edit'),
(66, 'work'),
(67, 'thread'),
(68, 'fixed'),
(69, 'error'),
(70, 'please'),
(71, 'fix'),
(72, 'overall'),
(73, 'aesthetics'),
(74, 'should'),
(75, 'have'),
(76, 'finished'),
(77, 'soon'),
(78, 'afterward'),
(79, 'need'),
(80, 'make'),
(81, 'the'),
(82, 'frontend'),
(83, 'didnt'),
(84, 'get'),
(85, 'finish'),
(86, 'interface'),
(87, 'because'),
(88, 'sleep'),
(89, 'though'),
(90, 'will'),
(91, 'complete'),
(92, 'fully'),
(93, 'tomorrow'),
(94, 'way'),
(95, 'youre'),
(96, 'gonna'),
(97, 'icon'),
(98, 'least'),
(99, 'animate'),
(100, 'right'),
(101, 'also'),
(102, 'seems'),
(103, 'like'),
(104, 'background'),
(105, 'loads'),
(106, 'really'),
(107, 'slowly'),
(108, 'school'),
(109, 'computer'),
(110, 'replaced'),
(111, 'with'),
(112, 'color'),
(113, 'can'),
(114, 'discuss'),
(115, 'alternative'),
(116, 'later'),
(117, 'brandon'),
(118, 'fuck'),
(119, 'you'),
(120, 'yeah'),
(121, 'definitely'),
(122, 'character'),
(123, 'your'),
(124, 'avatar'),
(125, 'wait'),
(126, 'could'),
(127, 'reanimate'),
(128, 'for'),
(129, 'youd'),
(130, 'doing'),
(131, 'some'),
(132, 'now'),
(133, 'noticed'),
(134, 'css'),
(135, 'still'),
(136, 'used'),
(137, 'that'),
(138, 'openingparenthesis'),
(139, 'crap'),
(140, 'shouldnt'),
(141, 'remove'),
(142, 'parenthesis'),
(143, 'dont'),
(144, 'dynamicy'),
(145, 'stuff'),
(146, 'err'),
(147, 'dunno'),
(148, 'what'),
(149, 'did'),
(150, 'adminnotesmessage'),
(151, 'template'),
(152, 'isnt'),
(153, 'success'),
(154, 'message'),
(155, 'either'),
(156, 'even'),
(157, 'until'),
(158, 'post'),
(159, 'data'),
(160, 'has'),
(161, 'been'),
(162, 'sent'),
(163, 'must'),
(164, 'seem'),
(165, 'replacement'),
(166, 'gone'),
(167, 'when'),
(168, 'last'),
(169, 'edited'),
(170, 'was'),
(171, 'fine'),
(172, 'moment'),
(173, 'ago'),
(174, 'check'),
(175, 'this'),
(176, 'out'),
(177, 'punpostsilentedit'),
(178, 'doesnt'),
(179, 'exist'),
(180, 'language'),
(181, 'btw'),
(182, 'from'),
(183, 'js'),
(184, 'other'),
(185, 'places'),
(186, 'applicable'),
(187, 'leave'),
(188, 'them'),
(189, 'lastly'),
(190, 'remember'),
(191, 'framework'),
(192, 'user'),
(193, 'completely'),
(194, 'changing'),
(195, 'there'),
(196, 'reason'),
(197, 'using'),
(198, 'files'),
(199, 'such'),
(200, 'filesize'),
(201, 'gifs'),
(202, 'something'),
(203, 'its'),
(204, 'first'),
(205, 'load'),
(206, 'firefox'),
(207, 'weird'),
(208, 'overlap'),
(209, 'corners'),
(210, 'rounded'),
(211, 'ie'),
(212, 'p.s'),
(213, 'edit2'),
(214, 'probably'),
(215, 'change'),
(216, 'banner'),
(217, 'say'),
(218, 'suit'),
(219, 'but'),
(220, 'thing'),
(221, 'not'),
(222, 'called'),
(223, 'admin'),
(224, 'center'),
(225, 'tools'),
(226, 'started'),
(227, 'it'),
(228, 'chris'),
(229, 'more'),
(230, 'jokes'),
(231, 'slipping'),
(232, 'terribly'),
(233, 'one'),
(234, 'few'),
(235, 'jobs'),
(236, 'given'),
(237, 'week'),
(238, 'deleted'),
(239, 'all'),
(240, 'file'),
(241, 'managing'),
(242, 'inside'),
(243, 'accident'),
(244, 'bad'),
(245, 'left'),
(246, 'put'),
(247, 'delete'),
(248, 'wed'),
(249, 'redo'),
(250, 'entire'),
(251, 'want'),
(252, 'part'),
(253, 'project'),
(254, 'to'),
(255, 'disappointing'),
(256, 'before'),
(257, 'just'),
(258, 'ridiculous'),
(259, 'than'),
(260, 'transfer'),
(261, 'punbb'),
(262, 'registration'),
(263, 'done'),
(264, 'sorry'),
(265, 'ive'),
(266, 'unmotivated'),
(267, 'continue'),
(268, 'after'),
(269, 'these'),
(270, 'conundrums'),
(271, 'coming'),
(272, 'up'),
(273, 'ill'),
(274, 'attempt'),
(275, 'step'),
(276, 'going'),
(277, 'gif'),
(278, 'slip-ups'),
(279, 'youve'),
(280, 'imagery'),
(281, 'how'),
(282, 'were'),
(283, 'they'),
(284, 'look'),
(285, 'good'),
(286, 'me'),
(287, 'msn'),
(288, 'well'),
(289, 'only'),
(290, 'slip-up'),
(291, 'since'),
(292, 'transparency'),
(293, 'adding'),
(294, 'worsen'),
(295, 'quality'),
(296, 'kept'),
(297, 'div'),
(298, 'tacked'),
(299, 'instead'),
(300, 'logo'),
(301, 'meant'),
(302, 'wanted'),
(303, 'clickable'),
(304, 'couldve'),
(305, 'old'),
(306, 'function'),
(307, 'new'),
(308, 'may'),
(309, 'working'),
(313, '1337'),
(314, 'hacker'),
(321, 'count'),
(322, '74'),
(323, 'great'),
(324, 'realize'),
(325, 'our'),
(326, 'inefficiencies'),
(327, 'hope'),
(328, 'inspire'),
(329, 'clean'),
(330, 'itself'),
(331, 'little'),
(332, 'premature'),
(333, 'decision...it'),
(334, 'xcontent'),
(335, 'necessary'),
(336, 'embedding'),
(337, 'multiple'),
(338, 'obviously'),
(339, 'nested'),
(340, 'added'),
(341, 'index'),
(342, 'errorlog'),
(343, 'password'),
(344, 'theory'),
(345, 'retrieving'),
(346, 'suggest'),
(347, 'having'),
(348, 'send'),
(349, 'link'),
(350, 'md5'),
(351, 'variables'),
(352, 'log-in'),
(353, 'then'),
(354, 'directed'),
(355, 'wont'),
(356, 'start'),
(357, 'tell'),
(358, 'valid'),
(359, 'idea'),
(360, 'bit'),
(361, 'misc'),
(362, 'cleaning'),
(363, 'apparently'),
(364, 'broken'),
(365, 'caching'),
(366, 'system'),
(367, 'thats'),
(368, 'very'),
(369, 'especially'),
(370, 'once'),
(371, 'hackers'),
(372, 'find'),
(373, 'mp3ing'),
(374, 'why'),
(375, 'implementing'),
(376, 'session'),
(377, 'ids'),
(378, 'required'),
(379, 'plan'),
(380, 'forget'),
(381, 'input'),
(382, 'email'),
(383, 'under'),
(384, 'which'),
(385, 'sends'),
(386, 'making'),
(387, 'sure'),
(388, 'updated'),
(389, 'sending'),
(390, 'unencrypted'),
(391, 'string'),
(392, 'bruteforce'),
(393, 'encryption'),
(394, 'general'),
(395, 'inefficient'),
(396, 'verdict'),
(397, 'e-mail'),
(398, 'random'),
(399, 'logs'),
(400, 'expires'),
(401, 'redirected'),
(402, 'their'),
(403, '-pemrissions'),
(404, 'are'),
(405, 'submitted'),
(406, 'puts'),
(407, 'everything'),
(408, 'back'),
(409, 'into'),
(410, 'boxes'),
(411, 'rewrite'),
(412, 'while'),
(413, 'errors'),
(414, 'longer'),
(415, 'being'),
(416, 'logged'),
(417, '-.php'),
(418, 'thinking'),
(419, 'about'),
(420, 'permissions'),
(421, 'issue'),
(422, 'thought'),
(423, 'ftp'),
(424, 'over'),
(425, 'php'),
(426, 'creation'),
(427, 'functions'),
(428, 'think'),
(429, 'efficient'),
(430, 'currently'),
(431, 'use'),
(432, 'actually'),
(433, 'created'),
(434, 'without'),
(435, 'ownership'),
(436, 'permission'),
(437, 'owner'),
(438, 'group'),
(439, 'same'),
(440, 'thus'),
(441, 'giving'),
(442, 'him'),
(443, 'non-obtrusive'),
(444, 'privileges'),
(445, 'script'),
(446, 'gives'),
(447, 'control'),
(448, 'power'),
(449, 'worth'),
(450, 'itd'),
(451, 'beneficial'),
(452, 'long'),
(453, 'run'),
(454, 'both'),
(455, 'users'),
(456, 'protocol'),
(457, 'transferring'),
(458, 'always'),
(459, 'know'),
(460, 'much'),
(461, 'honestly'),
(462, 'secure'),
(463, 'enough'),
(464, 'let'),
(465, 'update'),
(466, 'cp'),
(467, 'would'),
(468, 'awesome'),
(469, 'do'),
(470, 'means'),
(471, 'so'),
(472, 'problem'),
(473, '-permissions'),
(474, 'completed'),
(475, 'renaming'),
(476, 'sets'),
(477, 'set'),
(478, 'simple'),
(479, 'deletion'),
(480, 'recursive'),
(481, 'does'),
(482, 'never'),
(483, 'ends'),
(484, 'editing'),
(485, 'code'),
(486, 'needs'),
(487, 'completion'),
(488, 'cloning'),
(489, 'fixing'),
(490, 'talk'),
(491, 'come'),
(492, 'realized'),
(493, 'based'),
(494, 'off'),
(495, 'indisposed'),
(496, 'looping'),
(497, 'non-sense'),
(498, 'occurs'),
(510, 'nicely'),
(509, 'construct'),
(508, 'mentioned'),
(507, 'george'),
(506, 'bugzilla'),
(505, 'although'),
(511, 'php.net'),
(512, 'reputable'),
(513, 'forum'),
(514, 'any'),
(515, 'form'),
(516, 'upgrader'),
(517, 'converting'),
(518, 'shit'),
(519, 'take');

-- --------------------------------------------------------

--
-- Table structure for table `pun_subscriptions`
--

CREATE TABLE IF NOT EXISTS `pun_subscriptions` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `topic_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pun_subscriptions`
--


-- --------------------------------------------------------

--
-- Table structure for table `pun_topics`
--

CREATE TABLE IF NOT EXISTS `pun_topics` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `poster` varchar(200) character set utf8 NOT NULL,
  `subject` varchar(255) character set utf8 NOT NULL,
  `posted` int(10) unsigned NOT NULL default '0',
  `first_post_id` int(10) unsigned NOT NULL default '0',
  `last_post` int(10) unsigned NOT NULL default '0',
  `last_post_id` int(10) unsigned NOT NULL default '0',
  `last_poster` varchar(200) character set utf8 default NULL,
  `num_views` mediumint(8) unsigned NOT NULL default '0',
  `num_replies` mediumint(8) unsigned NOT NULL default '0',
  `closed` tinyint(1) NOT NULL default '0',
  `sticky` tinyint(1) NOT NULL default '0',
  `moved_to` int(10) unsigned default NULL,
  `forum_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pun_topics_forum_id_idx` (`forum_id`),
  KEY `pun_topics_moved_to_idx` (`moved_to`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pun_topics`
--

INSERT INTO `pun_topics` (`id`, `poster`, `subject`, `posted`, `first_post_id`, `last_post`, `last_post_id`, `last_poster`, `num_views`, `num_replies`, `closed`, `sticky`, `moved_to`, `forum_id`) VALUES
(2, 'Brandon', 'Work Thread', 1225776407, 0, 1227308732, 45, 'Brandon', 200, 29, 0, 0, NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `suit_errorlog`
--

CREATE TABLE IF NOT EXISTS `suit_errorlog` (
  `id` bigint(20) NOT NULL auto_increment,
  `content` text NOT NULL,
  `date` text NOT NULL,
  `location` text NOT NULL,
  `errorcount` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `suit_errorlog`
--


-- --------------------------------------------------------

--
-- Table structure for table `suit_files`
--

CREATE TABLE IF NOT EXISTS `suit_files` (
  `id` bigint(20) NOT NULL auto_increment,
  `template` text NOT NULL,
  `path` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `suit_files`
--

INSERT INTO `suit_files` (`id`, `template`, `path`) VALUES
(1, 'index', '/home/suit/public_html/index.php'),
(2, 'admin_errorlog', '/home/suit/public_html/admin_errorlog.php'),
(3, 'admin_index', '/home/suit/public_html/admin_index.php'),
(4, 'admin_languages', '/home/suit/public_html/admin_languages.php'),
(5, 'admin_templates', '/home/suit/public_html/admin_templates.php'),
(6, 'phpinfo', '/home/suit/public_html/phpinfo.php'),
(7, 'sandbox', '/home/suit/public_html/sandbox.php'),
(8, 'punbb', '/home/suit/public_html/forum/include/common.php'),
(10, 'register', '/home/suit/public_html/register.php'),
(11, 'password', '/home/suit/public_html/password.php'),
(12, 'lostpassword', '/home/suit/public_html/lostpassword.php'),
(13, 'bugzilla_index', '/home/suit/public_html/bugs/index.php'),
(14, 'bugzilla_attachment', '/home/suit/public_html/bugs/attachment.php'),
(15, 'mediawiki', '/home/suit/public_html/wiki/index.php'),
(16, 'bugzilla_buglist', '/home/suit/public_html/bugs/buglist.php');

-- --------------------------------------------------------

--
-- Table structure for table `suit_languages`
--

CREATE TABLE IF NOT EXISTS `suit_languages` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `defaults` tinyint(4) NOT NULL,
  `parent` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1114 ;

--
-- Dumping data for table `suit_languages`
--

INSERT INTO `suit_languages` (`id`, `title`, `content`, `defaults`, `parent`) VALUES
(1, 'English', '', 1, 0),
(2, 'requiredfields', 'You have not filled out the required fields!', 0, 1),
(3, 'nomatch', 'The inputted password does not match the username <1>!', 0, 1),
(4, 'editedsuccessfully', 'Edited Successfully!', 0, 1),
(5, 'templatenotfound', 'Error: Template <1> not found.', 0, 1),
(67, 'pun_common_multibyte', 'false', 0, 1),
(64, 'forum', 'Forum', 0, 1),
(66, 'pun_common_encoding', 'iso-8859-1', 0, 1),
(65, 'pun_common_direction', 'ltr', 0, 1),
(10, 'title', 'SUIT', 0, 1),
(11, 'copyright', '&copy; Copyright 2008 <a href="http://wiki.suitframework.com/index.php/The_SUIT_Group">The SUIT Group</a>. All Rights Reserved.', 0, 1),
(12, 'cantopenfile', 'Can''t Open File', 0, 1),
(13, 'notauthorized', 'You are not authorized to view this page!', 0, 1),
(14, 'adminwelcome', 'Welcome!', 0, 1),
(15, 'associatedthemes', 'Themes associated with this Template', 0, 1),
(16, 'home', 'Home', 0, 1),
(17, 'logout', 'Logout', 0, 1),
(18, 'templates', 'Templates', 0, 1),
(19, 'languages', 'Languages', 0, 1),
(20, 'admin', 'Admin', 0, 1),
(1092, 'pun_post_silentedit', 'Silent edit (don''t display "Edited by ..." in topic view)', 0, 1),
(22, 'phpcode', 'PHP Code', 0, 1),
(23, 'username', 'Username', 0, 1),
(24, 'password', 'Password', 0, 1),
(25, 'edit', 'Edit', 0, 1),
(26, 'add', 'Add', 0, 1),
(27, 'delete', 'Delete', 0, 1),
(28, 'openingbracket', '[', 0, 1),
(29, 'closingbracket', ']', 0, 1),
(30, 'openingbrace', '{', 0, 1),
(31, 'closingbrace', '}', 0, 1),
(32, 'openingparenthesis', '(', 0, 1),
(33, 'closingparenthesis', ')', 0, 1),
(34, 'key', 'Key', 0, 1),
(35, 'value', 'Value', 0, 1),
(36, 'sessionexpired', 'Your session has expired. See POSTDATA below.', 0, 1),
(37, 'addedsuccessfully', 'Added Successfully!', 0, 1),
(38, 'missingtitle', 'You must have a title!', 0, 1),
(39, 'deleteconfirm', 'Are you sure you want to delete <1>?', 0, 1),
(40, 'deletedsuccessfully', 'Deleted Successfully!', 0, 1),
(41, 'duplicatetitle', 'Duplicate Title.', 0, 1),
(42, 'updatedsuccessfully', 'Updated Successfully!', 0, 1),
(43, 'update', 'Update', 0, 1),
(44, 'colon', ':', 0, 1),
(45, 'hyphen', '-', 0, 1),
(46, 'verticalbar', '|', 0, 1),
(47, 'exclamationmark', '!', 0, 1),
(48, 'questionmark', '?', 0, 1),
(49, 'period', '.', 0, 1),
(50, 'illegalcontent', 'Error: Illegal Content.', 0, 1),
(51, 'clone', 'Clone', 0, 1),
(52, 'clonedsuccessfully', 'Cloned Successfully!', 0, 1),
(53, 'rename', 'Rename', 0, 1),
(54, 'renamedsuccessfully', 'Renamed Successfully!', 0, 1),
(55, 'errorlog', 'Error Log', 0, 1),
(56, 'errorlogwelcome', 'The error log section allows you to shift through the errors triggered by a missing template, missing language, etc. This includes the details on which page it was trigerred, the time and the requested resource.', 0, 1),
(57, 'first', 'First', 0, 1),
(58, 'last', 'Last', 0, 1),
(59, 'entriesperpage', 'Entries per Page', 0, 1),
(60, 'list', 'List', 0, 1),
(61, 'poweredby', 'Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a>', 0, 1),
(62, 'inputtitle', 'Title', 0, 1),
(63, 'content', 'Content', 0, 1),
(68, 'pun_common_badrequest', 'Bad request. The link you followed is incorrect or outdated.', 0, 1),
(69, 'pun_common_noview', 'You do not have permission to view these forums.', 0, 1),
(70, 'pun_common_nopermission', 'You do not have permission to access this page.', 0, 1),
(71, 'pun_common_badreferrer', 'Bad HTTP_REFERER. You were referred to this page from an unauthorized source. If the problem persists please make sure that ''Base URL'' is correctly set in Admin/Options and that you are visiting the forum by navigating to that URL. More information regarding the referrer check can be found in the PunBB documentation.', 0, 1),
(72, 'pun_common_newicon', 'There are new posts', 0, 1),
(73, 'pun_common_normalicon', '<!-- -->', 0, 1),
(74, 'pun_common_closedicon', 'This topic is closed', 0, 1),
(75, 'pun_common_redirecticon', 'Redirected forum', 0, 1),
(76, 'pun_common_index', 'Index', 0, 1),
(77, 'pun_common_userlist', 'User list', 0, 1),
(78, 'pun_common_rules', 'Rules', 0, 1),
(79, 'pun_common_search', 'Search', 0, 1),
(80, 'pun_common_register', 'Register', 0, 1),
(81, 'pun_common_login', 'Login', 0, 1),
(82, 'pun_common_notloggedin', 'You are not logged in.', 0, 1),
(83, 'pun_common_profile', 'Profile', 0, 1),
(84, 'pun_common_logout', 'Logout', 0, 1),
(85, 'pun_common_loggedinas', 'Logged in as', 0, 1),
(86, 'pun_common_admin', 'Administration', 0, 1),
(87, 'pun_common_lastvisit', 'Last visit', 0, 1),
(88, 'pun_common_shownewposts', 'Show new posts since last visit', 0, 1),
(89, 'pun_common_announcement', 'Announcement', 0, 1),
(90, 'pun_common_options', 'Options', 0, 1),
(91, 'pun_common_actions', 'Actions', 0, 1),
(92, 'pun_common_submit', 'Submit', 0, 1),
(93, 'pun_common_banmessage', 'You are banned from this forum.', 0, 1),
(94, 'pun_common_banmessage2', 'The ban expires at the end of', 0, 1),
(95, 'pun_common_banmessage3', 'The administrator or moderator that banned you left the following message:', 0, 1),
(96, 'pun_common_banmessage4', 'Please direct any inquiries to the forum administrator at', 0, 1),
(97, 'pun_common_never', 'Never', 0, 1),
(98, 'pun_common_today', 'Today', 0, 1),
(99, 'pun_common_yesterday', 'Yesterday', 0, 1),
(100, 'pun_common_info', 'Info', 0, 1),
(101, 'pun_common_goback', 'Go back', 0, 1),
(102, 'pun_common_maintenance', 'Maintenance', 0, 1),
(103, 'pun_common_redirecting', 'Redirecting', 0, 1),
(104, 'pun_common_clickredirect', 'Click here if you do not want to wait any longer (or if your browser does not automatically forward you)', 0, 1),
(105, 'pun_common_on', 'on', 0, 1),
(106, 'pun_common_off', 'off', 0, 1),
(107, 'pun_common_invalide-mail', 'The e-mail address you entered is invalid.', 0, 1),
(108, 'pun_common_requiredfield', 'is a required field in this form.', 0, 1),
(109, 'pun_common_lastpost', 'Last post', 0, 1),
(110, 'pun_common_by', 'by', 0, 1),
(111, 'pun_common_newposts', 'New&nbsp;posts', 0, 1),
(112, 'pun_common_newpostsinfo', 'Go to the first new post in this topic.', 0, 1),
(113, 'pun_common_username', 'Username', 0, 1),
(114, 'pun_common_password', 'Password', 0, 1),
(115, 'pun_common_e-mail', 'E-mail', 0, 1),
(116, 'pun_common_sende-mail', 'Send e-mail', 0, 1),
(117, 'pun_common_moderatedby', 'Moderated by', 0, 1),
(118, 'pun_common_registered', 'Registered', 0, 1),
(119, 'pun_common_subject', 'Subject', 0, 1),
(120, 'pun_common_message', 'Message', 0, 1),
(121, 'pun_common_topic', 'Topic', 0, 1),
(122, 'pun_common_forum', 'Forum', 0, 1),
(123, 'pun_common_posts', 'Posts', 0, 1),
(124, 'pun_common_replies', 'Replies', 0, 1),
(125, 'pun_common_author', 'Author', 0, 1),
(126, 'pun_common_pages', 'Pages', 0, 1),
(127, 'pun_common_bbcode', 'BBCode', 0, 1),
(128, 'pun_common_imgtag', '[img] tag', 0, 1),
(129, 'pun_common_smilies', 'Smilies', 0, 1),
(130, 'pun_common_and', 'and', 0, 1),
(131, 'pun_common_imagelink', 'image', 0, 1),
(132, 'pun_common_wrote', 'wrote', 0, 1),
(133, 'pun_common_code', 'Code', 0, 1),
(134, 'pun_common_mailer', 'Mailer', 0, 1),
(135, 'pun_common_importantinformation', 'Important information', 0, 1),
(136, 'pun_common_writemessagelegend', 'Write your message and submit', 0, 1),
(137, 'pun_common_title', 'Title', 0, 1),
(138, 'pun_common_member', 'Member', 0, 1),
(139, 'pun_common_moderator', 'Moderator', 0, 1),
(140, 'pun_common_administrator', 'Administrator', 0, 1),
(141, 'pun_common_banned', 'Banned', 0, 1),
(142, 'pun_common_guest', 'Guest', 0, 1),
(143, 'pun_common_bbcodeerror', 'The BBCode syntax in the message is incorrect.', 0, 1),
(144, 'pun_common_bbcodeerror1', 'Missing start tag for [/quote].', 0, 1),
(145, 'pun_common_bbcodeerror2', 'Missing end tag for [code].', 0, 1),
(146, 'pun_common_bbcodeerror3', 'Missing start tag for [/code].', 0, 1),
(147, 'pun_common_bbcodeerror4', 'Missing one or more end tags for [quote].', 0, 1),
(148, 'pun_common_bbcodeerror5', 'Missing one or more start tags for [/quote].', 0, 1),
(149, 'pun_common_markallasread', 'Mark all topics as read', 0, 1),
(150, 'pun_common_linkseparator', '', 0, 1),
(151, 'pun_common_boardfooter', 'Board footer', 0, 1),
(152, 'pun_common_searchlinks', 'Search links', 0, 1),
(153, 'pun_common_showrecentposts', 'Show recent posts', 0, 1),
(154, 'pun_common_showunansweredposts', 'Show unanswered posts', 0, 1),
(155, 'pun_common_showyourposts', 'Show your posts', 0, 1),
(156, 'pun_common_showsubscriptions', 'Show your subscribed topics', 0, 1),
(157, 'pun_common_jumpto', 'Jump to', 0, 1),
(158, 'pun_common_go', ' Go ', 0, 1),
(159, 'pun_common_movetopic', 'Move topic', 0, 1),
(160, 'pun_common_opentopic', 'Open topic', 0, 1),
(161, 'pun_common_closetopic', 'Close topic', 0, 1),
(162, 'pun_common_unsticktopic', 'Unstick topic', 0, 1),
(163, 'pun_common_sticktopic', 'Stick topic', 0, 1),
(164, 'pun_common_moderateforum', 'Moderate forum', 0, 1),
(165, 'pun_common_deleteposts', 'Delete multiple posts', 0, 1),
(166, 'pun_common_debugtable', 'Debug information', 0, 1),
(167, 'pun_common_rssdescactive', 'The most recently active topics at', 0, 1),
(168, 'pun_common_rssdescnew', 'The newest topics at', 0, 1),
(169, 'pun_common_posted', 'Posted', 0, 1),
(170, 'pun_delete_deletepost', 'Delete post', 0, 1),
(171, 'pun_delete_warning', 'Warning! If this is the first post in the topic, the whole topic will be deleted.', 0, 1),
(172, 'pun_delete_delete', 'Delete', 0, 1),
(173, 'pun_delete_postdelredirect', 'Post deleted. Redirecting &hellip;', 0, 1),
(174, 'pun_delete_topicdelredirect', 'Topic deleted. Redirecting &hellip;', 0, 1),
(175, 'pun_forum_posttopic', 'Post new topic', 0, 1),
(176, 'pun_forum_views', 'Views', 0, 1),
(177, 'pun_forum_moved', 'Moved', 0, 1),
(178, 'pun_forum_sticky', 'Sticky', 0, 1),
(179, 'pun_forum_emptyforum', 'Forum is empty.', 0, 1),
(180, 'pun_help_help', 'Help', 0, 1),
(181, 'pun_help_produces', 'produces', 0, 1),
(182, 'pun_help_bbcodeinfo1', 'BBCode is a collection of formatting tags that are used to change the look of text in this forum. BBCode is based on the same principal as, and is very similar to, HTML. Below is a list of all the available BBCodes and instructions on how to use them.', 0, 1),
(183, 'pun_help_bbcodeinfo2', 'Administrators have the ability to enable or disable BBCode. You can tell if BBCode is enabled or disabled out in the left margin whenever you post a message or edit your signature.', 0, 1),
(184, 'pun_help_textstyle', 'Text style', 0, 1),
(185, 'pun_help_textstyleinfo', 'The following tags change the appearance of text:', 0, 1),
(186, 'pun_help_boldtext', 'Bold text', 0, 1),
(187, 'pun_help_underlinedtext', 'Underlined text', 0, 1),
(188, 'pun_help_italictext', 'Italic text', 0, 1),
(189, 'pun_help_redtext', 'Red text', 0, 1),
(190, 'pun_help_bluetext', 'Blue text', 0, 1),
(191, 'pun_help_linksandimages', 'Links and images', 0, 1),
(192, 'pun_help_linksinfo', 'You can create links to other documents or to e-mail addresses using the following tags:', 0, 1),
(193, 'pun_help_mye-mailaddress', 'My e-mail address', 0, 1),
(194, 'pun_help_imagesinfo', 'If you want to display an image you can use the img tag.', 0, 1),
(195, 'pun_help_quotes', 'Quotes', 0, 1),
(196, 'pun_help_quotesinfo', 'If you want to quote someone, you should use the quote tag.', 0, 1),
(197, 'pun_help_quotetext', 'This is the text i want to quote.', 0, 1),
(198, 'pun_help_producesquotebox', 'produces a quote box like this:', 0, 1),
(199, 'pun_help_code', 'Code', 0, 1),
(200, 'pun_help_codeinfo', 'When displaying source code you should make sure that you use the code tag. Text displayed with the code tag will use a monospaced font and will not be affected by other tags.', 0, 1),
(201, 'pun_help_codetext', 'This is some code.', 0, 1),
(202, 'pun_help_producescodebox', 'produces a code box like this:', 0, 1),
(203, 'pun_help_nestedtags', 'Nested tags', 0, 1),
(204, 'pun_help_nestedtagsinfo', 'BBCode can be nested to create more advanced formatting. For example:', 0, 1),
(205, 'pun_help_bold,underlinedtext', 'Bold, underlined text', 0, 1),
(206, 'pun_help_smiliesinfo', 'If you like (and if it is enabled), the forum can convert a series of smilies to images representations of that smiley. This forum recognizes the following smilies and replaces them with images:', 0, 1),
(207, 'pun_index_topics', 'Topics', 0, 1),
(208, 'pun_index_moderators', 'Moderators', 0, 1),
(209, 'pun_index_linkto', 'Link to', 0, 1),
(210, 'pun_index_emptyboard', 'Board is empty.', 0, 1),
(211, 'pun_index_newestuser', 'Newest registered user', 0, 1),
(212, 'pun_index_usersonline', 'Registered users online', 0, 1),
(213, 'pun_index_guestsonline', 'Guests online', 0, 1),
(214, 'pun_index_noofusers', 'Total number of registered users', 0, 1),
(215, 'pun_index_nooftopics', 'Total number of topics', 0, 1),
(216, 'pun_index_noofposts', 'Total number of posts', 0, 1),
(217, 'pun_index_online', 'Online', 0, 1),
(218, 'pun_index_boardinfo', 'Board information', 0, 1),
(219, 'pun_index_boardstats', 'Board statistics', 0, 1),
(220, 'pun_index_userinfo', 'User information', 0, 1),
(221, 'pun_login_wronguser/pass', 'Wrong username and/or password.', 0, 1),
(222, 'pun_login_forgottenpass', 'Forgotten your password?', 0, 1),
(223, 'pun_login_loginredirect', 'Logged in successfully. Redirecting &hellip;', 0, 1),
(224, 'pun_login_logoutredirect', 'Logged out. Redirecting &hellip;', 0, 1),
(225, 'pun_login_noe-mailmatch', 'There is no user registered with the e-mail address', 0, 1),
(226, 'pun_login_requestpass', 'Request password', 0, 1),
(227, 'pun_login_requestpasslegend', 'Enter the e-mail address with which you registered', 0, 1),
(228, 'pun_login_requestpassinfo', 'A new password together with a link to activate the new password will be sent to that address.', 0, 1),
(229, 'pun_login_notregistered', 'Not registered yet?', 0, 1),
(230, 'pun_login_loginlegend', 'Enter your username and password below', 0, 1),
(231, 'pun_login_logininfo', 'If you have not registered or have forgotten your password click on the appropriate link below.', 0, 1),
(232, 'pun_login_forgetmail', 'An e-mail has been sent to the specified address with instructions on how to change your password. If it does not arrive you can contact the forum administrator at', 0, 1),
(233, 'pun_misc_markreadredirect', 'All topics and forums have been marked as read. Redirecting &hellip;', 0, 1),
(234, 'pun_misc_forme-maildisabled', 'The user you are trying to send an e-mail to has disabled form e-mail.', 0, 1),
(235, 'pun_misc_noe-mailsubject', 'You must enter a subject.', 0, 1),
(236, 'pun_misc_noe-mailmessage', 'You must enter a message.', 0, 1),
(237, 'pun_misc_toolonge-mailmessage', 'Messages cannot be longer than 65535 characters (64 KB).', 0, 1),
(238, 'pun_misc_e-mailsentredirect', 'E-mail sent. Redirecting &hellip;', 0, 1),
(239, 'pun_misc_sende-mailto', 'Send e-mail to', 0, 1),
(240, 'pun_misc_e-mailsubject', 'Subject', 0, 1),
(241, 'pun_misc_e-mailmessage', 'Message', 0, 1),
(242, 'pun_misc_e-maildisclosurenote', 'Please note that by using this form, your e-mail address will be disclosed to the recipient.', 0, 1),
(243, 'pun_misc_writee-mail', 'Write and submit your e-mail message', 0, 1),
(244, 'pun_misc_noreason', 'You must enter a reason.', 0, 1),
(245, 'pun_misc_reportredirect', 'Post reported. Redirecting &hellip;', 0, 1),
(246, 'pun_misc_reportpost', 'Report post', 0, 1),
(247, 'pun_misc_reason', 'Reason', 0, 1),
(248, 'pun_misc_reasondesc', 'Please enter a short reason why you are reporting this post', 0, 1),
(249, 'pun_misc_alreadysubscribed', 'You are already subscribed to this topic.', 0, 1),
(250, 'pun_misc_subscriberedirect', 'Your subscription has been added. Redirecting &hellip;', 0, 1),
(251, 'pun_misc_notsubscribed', 'You are not subscribed to this topic.', 0, 1),
(252, 'pun_misc_unsubscriberedirect', 'Your subscription has been removed. Redirecting &hellip;', 0, 1),
(253, 'pun_misc_moderate', 'Moderate', 0, 1),
(254, 'pun_misc_select', 'Select', 0, 1),
(255, 'pun_misc_move', 'Move', 0, 1),
(256, 'pun_misc_delete', 'Delete', 0, 1),
(257, 'pun_misc_open', 'Open', 0, 1),
(258, 'pun_misc_close', 'Close', 0, 1),
(259, 'pun_misc_movetopic', 'Move topic', 0, 1),
(260, 'pun_misc_movetopics', 'Move topics', 0, 1),
(261, 'pun_misc_movelegend', 'Select destination of move', 0, 1),
(262, 'pun_misc_moveto', 'Move to', 0, 1),
(263, 'pun_misc_leaveredirect', 'Leave redirect topic(s)', 0, 1),
(264, 'pun_misc_movetopicredirect', 'Topic moved. Redirecting &hellip;', 0, 1),
(265, 'pun_misc_movetopicsredirect', 'Topics moved. Redirecting &hellip;', 0, 1),
(266, 'pun_misc_confirmdeletelegend', 'Please confirm deletion', 0, 1),
(267, 'pun_misc_deletetopics', 'Delete topics', 0, 1),
(268, 'pun_misc_deletetopicscomply', 'Are you sure you want to delete the selected topics?', 0, 1),
(269, 'pun_misc_deletetopicsredirect', 'Topics deleted. Redirecting &hellip;', 0, 1),
(270, 'pun_misc_opentopicredirect', 'Topic opened. Redirecting &hellip;', 0, 1),
(271, 'pun_misc_opentopicsredirect', 'Topics opened. Redirecting &hellip;', 0, 1),
(272, 'pun_misc_closetopicredirect', 'Topic closed. Redirecting &hellip;', 0, 1),
(273, 'pun_misc_closetopicsredirect', 'Topics closed. Redirecting &hellip;', 0, 1),
(274, 'pun_misc_notopicsselected', 'You must select at least one topic for move/delete/open/close.', 0, 1),
(275, 'pun_misc_sticktopicredirect', 'Topic sticked. Redirecting &hellip;', 0, 1),
(276, 'pun_misc_unsticktopicredirect', 'Topic unsticked. Redirecting &hellip;', 0, 1),
(277, 'pun_misc_deleteposts', 'Delete posts', 0, 1),
(278, 'pun_misc_deletepostscomply', 'Are you sure you want to delete the selected posts?', 0, 1),
(279, 'pun_misc_deletepostsredirect', 'Posts deleted. Redirecting &hellip;', 0, 1),
(280, 'pun_misc_nopostsselected', 'You must select at least one post to be deleted.', 0, 1),
(281, 'pun_post_nosubject', 'Topics must contain a subject.', 0, 1),
(282, 'pun_post_toolongsubject', 'Subjects cannot be longer than 70 characters.', 0, 1),
(283, 'pun_post_nomessage', 'You must enter a message.', 0, 1),
(284, 'pun_post_toolongmessage', 'Posts cannot be longer that 65535 characters (64 KB).', 0, 1),
(285, 'pun_post_posterrors', 'Post errors', 0, 1),
(286, 'pun_post_posterrorsinfo', 'The following errors need to be corrected before the message can be posted:', 0, 1),
(287, 'pun_post_postpreview', 'Post preview', 0, 1),
(288, 'pun_post_guestname', 'Name', 0, 1),
(289, 'pun_post_postredirect', 'Post entered. Redirecting &hellip;', 0, 1),
(290, 'pun_post_postareply', 'Post a reply', 0, 1),
(291, 'pun_post_postnewtopic', 'Post new topic', 0, 1),
(292, 'pun_post_hidesmilies', 'Never show smilies as icons for this post', 0, 1),
(293, 'pun_post_subscribe', 'Subscribe to this topic', 0, 1),
(294, 'pun_post_topicreview', 'Topic review (newest first)', 0, 1),
(295, 'pun_post_floodstart', 'At least', 0, 1),
(296, 'pun_post_floodend', 'seconds have to pass between posts. Please wait a little while and try posting again.', 0, 1),
(297, 'pun_post_preview', 'Preview', 0, 1),
(298, 'pun_post_editpostlegend', 'Edit the post and submit changes', 0, 1),
(299, 'pun_post_editpost', 'Edit post', 0, 1),
(300, 'pun_post_editredirect', 'Post updated. Redirecting &hellip;', 0, 1),
(301, 'pun_prof_reg_e-maillegend', 'Enter a valid e-mail address', 0, 1),
(302, 'pun_prof_reg_e-maillegend2', 'Enter and confirm a valid e-mail address', 0, 1),
(303, 'pun_prof_reg_localisationlegend', 'Set your localisation options', 0, 1),
(304, 'pun_prof_reg_timezone', 'Timezone', 0, 1),
(305, 'pun_prof_reg_timezoneinfo', 'For the forum to display times correctly you must select your local timezone.', 0, 1),
(306, 'pun_prof_reg_language', 'Language', 0, 1),
(307, 'pun_prof_reg_languageinfo', 'You can choose which language you wish to use to view the forum.', 0, 1),
(308, 'pun_prof_reg_e-mailsettinginfo', 'Select whether you want your e-mail address to be viewable to other users or not and if you want other users to be able to send you e-mail via the forum (form e-mail) or not.', 0, 1),
(309, 'pun_prof_reg_e-mailsetting1', 'Display your e-mail address.', 0, 1),
(310, 'pun_prof_reg_e-mailsetting2', 'Hide your e-mail address but allow form e-mail.', 0, 1),
(311, 'pun_prof_reg_e-mailsetting3', 'Hide your e-mail address and disallow form e-mail.', 0, 1),
(312, 'pun_prof_reg_privacyoptionslegend', 'Set your privacy options', 0, 1),
(313, 'pun_prof_reg_saveuser/pass', 'Save username and password between visits.', 0, 1),
(314, 'pun_prof_reg_saveuser/passinfo', 'This option sets whether the forum should "remember" you between visits. If enabled, you will not have to login every time you visit the forum. You will be logged in automatically. Recommended.', 0, 1),
(315, 'pun_prof_reg_confirmpass', 'Confirm password', 0, 1),
(316, 'pun_prof_reg_usernametooshort', 'Usernames must be at least 2 characters long. Please choose another (longer) username.', 0, 1),
(317, 'pun_prof_reg_usernameguest', 'The username guest is reserved. Please choose another username.', 0, 1),
(318, 'pun_prof_reg_usernameip', 'Usernames may not be in the form of an IP address. Please choose another username.', 0, 1),
(319, 'pun_prof_reg_usernamebbcode', 'Usernames may not contain any of the text formatting tags (BBCode) that the forum uses. Please choose another username.', 0, 1),
(320, 'pun_prof_reg_dupeusername', 'Someone else has already registered with that username. Please choose another username.', 0, 1),
(321, 'pun_prof_reg_passtooshort', 'Passwords must be at least 4 characters long. Please choose another (longer) password.', 0, 1),
(322, 'pun_prof_reg_passnotmatch', 'Passwords do not match. Please go back and correct.', 0, 1),
(323, 'pun_prof_reg_bannede-mail', 'The e-mail address you entered is banned in this forum. Please choose another e-mail address.', 0, 1),
(324, 'pun_prof_reg_dupee-mail', 'Someone else is already registered with that e-mail address. Please choose another e-mail address.', 0, 1),
(325, 'pun_prof_reg_sigtoolong', 'Signatures cannot be longer than', 0, 1),
(326, 'pun_prof_reg_characters', 'characters', 0, 1),
(327, 'pun_prof_reg_sigtoomanylines', 'Signatures cannot have more than', 0, 1),
(328, 'pun_prof_reg_lines', 'lines', 0, 1),
(329, 'pun_prof_reg_signaturequote/code', 'The quote and code BBCodes are not allowed in signatures. Please go back and correct.', 0, 1),
(330, 'pun_prof_reg_badicq', 'You entered an invalid ICQ UIN. Please go back and correct.', 0, 1),
(331, 'pun_profile_profilemenu', 'Profile menu', 0, 1),
(332, 'pun_profile_sectionessentials', 'Essentials', 0, 1),
(333, 'pun_profile_sectionpersonal', 'Personal', 0, 1),
(334, 'pun_profile_sectionmessaging', 'Messaging', 0, 1),
(335, 'pun_profile_sectionpersonality', 'Personality', 0, 1),
(336, 'pun_profile_sectiondisplay', 'Display', 0, 1),
(337, 'pun_profile_sectionprivacy', 'Privacy', 0, 1),
(338, 'pun_profile_sectionadmin', 'Administration', 0, 1),
(339, 'pun_profile_usernameandpasslegend', 'Enter your username and password', 0, 1),
(340, 'pun_profile_personaldetailslegend', 'Enter your personal details', 0, 1),
(341, 'pun_profile_contactdetailslegend', 'Enter your messaging details', 0, 1),
(342, 'pun_profile_optionsdisplay', 'Set your display options', 0, 1),
(343, 'pun_profile_optionspost', 'Set your post viewing options', 0, 1),
(344, 'pun_profile_useractivity', 'User activity', 0, 1),
(345, 'pun_profile_paginateinfo', 'Enter the number of topics and posts you wish to view on each page.', 0, 1),
(346, 'pun_profile_passkeybad', 'The specified password activation key was incorrect or has expired. Please re-request a new password. If that fails, contact the forum administrator at', 0, 1),
(347, 'pun_profile_passupdated', 'Your password has been updated. You can now login with your new password.', 0, 1),
(348, 'pun_profile_passupdatedredirect', 'Password updated. Redirecting &hellip;', 0, 1),
(349, 'pun_profile_wrongpass', 'Wrong old password.', 0, 1),
(350, 'pun_profile_changepass', 'Change password', 0, 1),
(351, 'pun_profile_changepasslegend', 'Enter and confirm your new password', 0, 1),
(352, 'pun_profile_oldpass', 'Old password', 0, 1),
(353, 'pun_profile_newpass', 'New password', 0, 1),
(354, 'pun_profile_confirmnewpass', 'Confirm new password', 0, 1),
(355, 'pun_profile_e-mailkeybad', 'The specified e-mail activation key was incorrect or has expired. Please re-request change of e-mail address. If that fails, contact the forum administrator at', 0, 1),
(356, 'pun_profile_e-mailupdated', 'Your e-mail address has been updated.', 0, 1),
(357, 'pun_profile_e-maillegend', 'Enter your new e-mail address', 0, 1),
(358, 'pun_profile_e-mailinstructions', 'An e-mail will be sent to your new address with an activation link. You must click the link in the e-mail you receive to activate the new address.', 0, 1),
(359, 'pun_profile_changee-mail', 'Change e-mail address', 0, 1),
(360, 'pun_profile_newe-mail', 'New e-mail', 0, 1),
(361, 'pun_profile_avatarsdisabled', 'The administrator has disabled avatar support.', 0, 1),
(362, 'pun_profile_partialupload', 'The selected file was only partially uploaded. Please try again.', 0, 1),
(363, 'pun_profile_notmpdirectory', 'PHP was unable to save the uploaded file to a temporary location.', 0, 1),
(364, 'pun_profile_nofile', 'You did not select a file for upload.', 0, 1),
(365, 'pun_profile_badtype', 'The file you tried to upload is not of an allowed type. Allowed types are gif, jpeg and png.', 0, 1),
(366, 'pun_profile_toowideorhigh', 'The file you tried to upload is wider and/or higher than the maximum allowed', 0, 1),
(367, 'pun_profile_toolarge', 'The file you tried to upload is larger than the maximum allowed', 0, 1),
(368, 'pun_profile_pixels', 'pixels', 0, 1),
(369, 'pun_profile_bytes', 'bytes', 0, 1),
(370, 'pun_profile_movefailed', 'The server was unable to save the uploaded file. Please contact the forum administrator at', 0, 1),
(371, 'pun_profile_unknownfailure', 'An unknown error occurred. Please try again.', 0, 1),
(372, 'pun_profile_avataruploadredirect', 'Avatar uploaded. Redirecting &hellip;', 0, 1),
(373, 'pun_profile_avatardeletedredirect', 'Avatar deleted. Redirecting &hellip;', 0, 1),
(374, 'pun_profile_avatardesc', 'An avatar is a small image that will be displayed under your username in your posts. It must not be any bigger than', 0, 1),
(375, 'pun_profile_uploadavatar', 'Upload avatar', 0, 1),
(376, 'pun_profile_uploadavatarlegend', 'Enter an avatar file to upload', 0, 1),
(377, 'pun_profile_deleteavatar', 'Delete avatar', 0, 1),
(378, 'pun_profile_file', 'File', 0, 1),
(379, 'pun_profile_upload', 'Upload', 0, 1),
(380, 'pun_profile_dupeusername', 'Someone else has already registered with that username. Please go back and try a different username.', 0, 1),
(381, 'pun_profile_forbiddentitle', 'The title you entered contains a forbidden word. You must choose a different title.', 0, 1),
(382, 'pun_profile_profileredirect', 'Profile updated. Redirecting &hellip;', 0, 1),
(383, 'pun_profile_unknown', '(Unknown)', 0, 1),
(384, 'pun_profile_private', '(Private)', 0, 1),
(385, 'pun_profile_noavatar', '(No avatar)', 0, 1),
(386, 'pun_profile_showposts', 'Show all posts', 0, 1),
(387, 'pun_profile_realname', 'Real name', 0, 1),
(388, 'pun_profile_location', 'Location', 0, 1),
(389, 'pun_profile_website', 'Website', 0, 1),
(390, 'pun_profile_jabber', 'Jabber', 0, 1),
(391, 'pun_profile_icq', 'ICQ', 0, 1),
(392, 'pun_profile_msn', 'MSN Messenger', 0, 1),
(393, 'pun_profile_aolim', 'AOL IM', 0, 1),
(394, 'pun_profile_yahoo', 'Yahoo! Messenger', 0, 1),
(395, 'pun_profile_avatar', 'Avatar', 0, 1),
(396, 'pun_profile_signature', 'Signature', 0, 1),
(397, 'pun_profile_sigmaxlength', 'Max length', 0, 1),
(398, 'pun_profile_sigmaxlines', 'Max lines', 0, 1),
(399, 'pun_profile_avatarlegend', 'Set your avatar display options', 0, 1),
(400, 'pun_profile_changeavatar', 'Change avatar', 0, 1),
(401, 'pun_profile_useavatar', 'Use avatar.', 0, 1),
(402, 'pun_profile_signaturelegend', 'Compose your signature', 0, 1),
(403, 'pun_profile_sigpreview', 'Current signature preview:', 0, 1),
(404, 'pun_profile_nosig', 'No signature currently stored in profile.', 0, 1),
(405, 'pun_profile_topicsperpage', 'Topics', 0, 1),
(406, 'pun_profile_topicsperpageinfo', 'This setting controls how many topics are displayed per page when you view a forum. If you are uncertain about what to use, you can just leave it blank and the forum default will be used.', 0, 1),
(407, 'pun_profile_postsperpage', 'Posts', 0, 1),
(408, 'pun_profile_postsperpageinfo', 'This setting controls how many posts are displayed per page when you view a topic. If you are uncertain about what to use, you can just leave it blank and the forum default will be used.', 0, 1),
(409, 'pun_profile_leaveblank', 'Leave blank to use forum default.', 0, 1),
(410, 'pun_profile_notifyfull', 'Include post in subscription e-mails.', 0, 1),
(411, 'pun_profile_notifyfullinfo', 'With this enabled, a plain text version of the new post will be included in subscription notification e-mails.', 0, 1),
(412, 'pun_profile_showsmilies', 'Show smilies as graphic icons', 0, 1),
(413, 'pun_profile_showsmiliesinfo', 'If you enable this option, small images will be displayed instead of text smilies.', 0, 1),
(414, 'pun_profile_showimages', 'Show images in posts.', 0, 1),
(415, 'pun_profile_showimagessigs', 'Show images in user signatures.', 0, 1),
(416, 'pun_profile_showavatars', 'Show user avatars in posts.', 0, 1),
(417, 'pun_profile_showavatarsinfo', 'This option toggles whether user avatar images will be displayed in posts or not.', 0, 1),
(418, 'pun_profile_showsigs', 'Show user signatures.', 0, 1),
(419, 'pun_profile_showsigsinfo', 'Enable if you would like to see user signatures.', 0, 1),
(420, 'pun_profile_stylelegend', 'Select your preferred style', 0, 1),
(421, 'pun_profile_styleinfo', 'If you like you can use a different visual style for this forum.', 0, 1),
(422, 'pun_profile_adminnote', 'Admin note', 0, 1),
(423, 'pun_profile_paginationlegend', 'Enter your pagination options', 0, 1),
(424, 'pun_profile_postdisplaylegend', 'Set your options for viewing posts', 0, 1),
(425, 'pun_profile_postdisplayinfo', 'If you are on a slow connection, disabling these options, particularly showing images in posts and signatures, will make pages load faster.', 0, 1),
(426, 'pun_profile_instructions', 'When you update your profile, you will be redirected back to this page.', 0, 1),
(427, 'pun_profile_groupmembershiplegend', 'Choose user group', 0, 1),
(428, 'pun_profile_save', 'Save', 0, 1),
(429, 'pun_profile_setmodslegend', 'Set moderator access', 0, 1),
(430, 'pun_profile_moderatorin', 'Moderator in', 0, 1),
(431, 'pun_profile_moderatorininfo', 'Choose what forums this user should be allowed to moderate. Note: This only applies to moderators. Administrators always have full permissions in all forums.', 0, 1),
(432, 'pun_profile_updateforums', 'Update forums', 0, 1),
(433, 'pun_profile_deletebanlegend', 'Delete (administrators only) or ban user', 0, 1),
(434, 'pun_profile_deleteuser', 'Delete user', 0, 1),
(435, 'pun_profile_banuser', 'Ban user', 0, 1),
(436, 'pun_profile_confirmdeletelegend', 'Important: read before deleting user', 0, 1),
(437, 'pun_profile_confirmdeleteuser', 'Confirm delete user', 0, 1),
(438, 'pun_profile_confirmationinfo', 'Please confirm that you want to delete the user', 0, 1),
(439, 'pun_profile_deletewarning', 'Warning! Deleted users and/or posts cannot be restored. If you choose not to delete the posts made by this user, the posts can only be deleted manually at a later time.', 0, 1),
(440, 'pun_profile_deleteposts', 'Delete any posts and topics this user has made.', 0, 1),
(441, 'pun_profile_delete', 'Delete', 0, 1),
(442, 'pun_profile_userdeleteredirect', 'User deleted. Redirecting &hellip;', 0, 1),
(443, 'pun_profile_groupmembershipredirect', 'Group membership saved. Redirecting &hellip;', 0, 1),
(444, 'pun_profile_updateforumsredirect', 'Forum moderator rights updated. Redirecting &hellip;', 0, 1),
(445, 'pun_profile_banredirect', 'Redirecting &hellip;', 0, 1),
(446, 'pun_register_nonewregs', 'This forum is not accepting new registrations.', 0, 1),
(447, 'pun_register_regcancelredirect', 'Registration cancelled. Redirecting &hellip;', 0, 1),
(448, 'pun_register_forumrules', 'Forum rules', 0, 1),
(449, 'pun_register_ruleslegend', 'You must agree to the following in order to register', 0, 1),
(450, 'pun_register_agree', 'Agree', 0, 1),
(451, 'pun_register_cancel', 'Cancel', 0, 1),
(452, 'pun_register_register', 'Register', 0, 1),
(453, 'pun_register_usernamecensor', 'The username you entered contains one or more censored words. Please choose a different username.', 0, 1),
(454, 'pun_register_usernamedupe1', 'Someone is already registered with the username', 0, 1),
(455, 'pun_register_usernamedupe2', 'The username you entered is too similar. The username must differ from that by at least one alphanumerical character (a-z or 0-9). Please choose a different username.', 0, 1),
(456, 'pun_register_e-mailnotmatch', 'E-mail addresses do not match. Please go back and correct.', 0, 1),
(457, 'pun_register_regcomplete', 'Registration complete. Logging in and redirecting &hellip;', 0, 1),
(458, 'pun_register_desc1', 'Registration will grant you access to a number of features and capabilities otherwise unavailable. These functions include the ability to edit and delete posts, design your own signature that accompanies your posts and much more. If you have any questions regarding this forum you should ask an administrator.', 0, 1),
(459, 'pun_register_desc2', 'Below is a form you must fill out in order to register. Once you are registered you should visit your profile and review the different settings you can change. The fields below only make up a small part of all the settings you can alter in your profile.', 0, 1),
(460, 'pun_register_usernamelegend', 'Please enter a username between 2 and 25 characters long', 0, 1),
(461, 'pun_register_passlegend1', 'Please enter and confirm your chosen password', 0, 1),
(462, 'pun_register_passlegend2', 'Please read the instructions below', 0, 1),
(463, 'pun_register_passinfo', 'Passwords can be between 4 and 16 characters long. Passwords are case sensitive.', 0, 1),
(464, 'pun_register_e-mailinfo', 'You must enter a valid e-mail address as your randomly generated password will be sent to that address.', 0, 1),
(465, 'pun_register_confirme-mail', 'Confirm e-mail address', 0, 1),
(466, 'pun_search_usersearch', 'User search', 0, 1),
(467, 'pun_search_nosearchpermission', 'You do not have permission to use the search feature.', 0, 1),
(468, 'pun_search_search', 'Search', 0, 1),
(469, 'pun_search_searchcriterialegend', 'Enter your search criteria', 0, 1),
(470, 'pun_search_searchinfo', 'To search by keyword, enter a term or terms to search for. Separate terms with spaces. Use AND, OR and NOT to refine your search. To search by author enter the username of the author whose posts you wish to search for. Use wildcard character * for partial matches.', 0, 1),
(471, 'pun_search_keywordsearch', 'Keyword search', 0, 1),
(472, 'pun_search_authorsearch', 'Author search', 0, 1),
(473, 'pun_search_searchinlegend', 'Select where to search', 0, 1),
(474, 'pun_search_searchininfo', 'Choose in which forum you would like to search and if you want to search in topic subjects, message text or both.', 0, 1),
(475, 'pun_search_forumsearch', 'Forum', 0, 1),
(476, 'pun_search_allforums', 'All forums', 0, 1),
(477, 'pun_search_searchin', 'Search in', 0, 1),
(478, 'pun_search_messageandsubject', 'Message text and topic subject', 0, 1),
(479, 'pun_search_messageonly', 'Message text only', 0, 1),
(480, 'pun_search_topiconly', 'Topic subject only', 0, 1),
(481, 'pun_search_sortby', 'Sort by', 0, 1),
(482, 'pun_search_sortorder', 'Sort order', 0, 1),
(483, 'pun_search_searchresultslegend', 'Select how to view search results', 0, 1),
(484, 'pun_search_searchresultsinfo', 'You can choose how you wish to sort and show your results.', 0, 1),
(485, 'pun_search_sortbyposttime', 'Post time', 0, 1),
(486, 'pun_search_sortbyauthor', 'Author', 0, 1),
(487, 'pun_search_sortbysubject', 'Subject', 0, 1),
(488, 'pun_search_sortbyforum', 'Forum', 0, 1),
(489, 'pun_search_ascending', 'Ascending', 0, 1),
(490, 'pun_search_descending', 'Descending', 0, 1),
(491, 'pun_search_showas', 'Show results as', 0, 1),
(492, 'pun_search_showastopics', 'Topics', 0, 1),
(493, 'pun_search_showasposts', 'Posts', 0, 1),
(494, 'pun_search_searchresults', 'Search results', 0, 1),
(495, 'pun_search_noterms', 'You have to enter at least one keyword and/or an author to search for.', 0, 1),
(496, 'pun_search_nohits', 'Your search returned no hits.', 0, 1),
(497, 'pun_search_nouserposts', 'There are no posts by this user in this forum.', 0, 1),
(498, 'pun_search_nosubscriptions', 'You are currently not subscribed to any topics.', 0, 1),
(499, 'pun_search_nonewposts', 'There are no topics with new posts since your last visit.', 0, 1),
(500, 'pun_search_norecentposts', 'No new posts have been made within the last 24 hours.', 0, 1),
(501, 'pun_search_nounanswered', 'There are no unanswered posts in this forum.', 0, 1),
(502, 'pun_search_gotopost', 'Go to post', 0, 1),
(503, 'pun_topic_postreply', 'Post reply', 0, 1),
(504, 'pun_topic_topicclosed', 'Topic closed', 0, 1),
(505, 'pun_topic_from', 'From', 0, 1),
(506, 'pun_topic_note', 'Note', 0, 1),
(507, 'pun_topic_website', 'Website', 0, 1),
(508, 'pun_topic_guest', 'Guest', 0, 1),
(509, 'pun_topic_online', 'Online', 0, 1),
(510, 'pun_topic_offline', 'Offline', 0, 1),
(511, 'pun_topic_lastedit', 'Last edited by', 0, 1),
(512, 'pun_topic_report', 'Report', 0, 1),
(513, 'pun_topic_delete', 'Delete', 0, 1),
(514, 'pun_topic_edit', 'Edit', 0, 1),
(515, 'pun_topic_quote', 'Quote', 0, 1),
(516, 'pun_topic_issubscribed', 'You are currently subscribed to this topic', 0, 1),
(517, 'pun_topic_unsubscribe', 'Unsubscribe', 0, 1),
(518, 'pun_topic_subscribe', 'Subscribe to this topic', 0, 1),
(519, 'pun_topic_quickpost', 'Quick post', 0, 1),
(520, 'pun_topic_linkseparator', ' | ', 0, 1),
(521, 'pun_topic_modcontrols', 'Moderator controls', 0, 1),
(522, 'pun_ul_userfindlegend', 'Find and sort users', 0, 1),
(523, 'pun_ul_usersearchinfo', 'Enter a username to search for and/or a user group to filter by. The username field can be left blank. Use the wildcard character * for partial matches. Sort users by name, date registered or number of posts and in ascending/descending order.', 0, 1),
(524, 'pun_ul_usergroup', 'User group', 0, 1),
(525, 'pun_ul_noofposts', 'No. of posts', 0, 1),
(526, 'pun_ul_allusers', 'All', 0, 1),
(527, 'pun_mail_activate_email', 'Subject: Change e-mail address requested\r\n\r\nHello <username>,\r\n\r\nYou have requested to have a new e-mail address assigned to your account in the discussion forum at <base_url>. If you didn''t request this or if you don''t want to change your e-mail address you should just ignore this message. Only if you visit the activation page below will your e-mail address be changed. In order for the activation page to work, you must be logged in to the forum.\r\n\r\nTo change your e-mail address, please visit the following page:\r\n<activation_url>\r\n\r\n-- \r\n<board_mailer>\r\n(Do not reply to this message)', 0, 1),
(528, 'pun_mail_activate_password', 'Subject: New password requested\r\n\r\nHello <username>,\r\n\r\nYou have requested to have a new password assigned to your account in the discussion forum at <base_url>. If you didn''t request this or if you don''t want to change your password you should just ignore this message. Only if you visit the activation page below will your password be changed.\r\n\r\nYour new password is: <new_password>\r\n\r\nTo change your password, please visit the following page:\r\n<activation_url>\r\n\r\n-- \r\n<board_mailer>\r\n(Do not reply to this message)', 0, 1),
(529, 'pun_mail_form_email', 'Subject: <mail_subject>\r\n\r\n<sender> from <board_title> has sent you a message. You can reply to <sender> by replying to this e-mail.\r\n\r\nThe message reads as follows:\r\n-----------------------------------------------------------------------\r\n\r\n<mail_message>\r\n\r\n-----------------------------------------------------------------------\r\n\r\n-- \r\n<board_mailer>', 0, 1),
(530, 'pun_mail_new_reply', 'Subject: Reply to topic: <topic_subject>\r\n\r\n<replier> has replied to the topic <topic_subject> to which you are subscribed. There may be more new replies, but this is the only notification you will receive until you visit the board again.\r\n\r\nThe post is located at <post_url>\r\n\r\nYou can unsubscribe by going to <unsubscribe_url>\r\n\r\n-- \r\n<board_mailer>\r\n(Do not reply to this message)', 0, 1),
(531, 'pun_mail_new_reply_full', 'Subject: Reply to topic: <topic_subject>\r\n\r\n<replier> has replied to the topic <topic_subject> to which you are subscribed. There may be more new replies, but this is the only notification you will receive until you visit the board again.\r\n\r\nThe message reads as follows:\r\n-----------------------------------------------------------------------\r\n\r\n<message>\r\n\r\n-----------------------------------------------------------------------\r\n\r\nThe post is located at <post_url>\r\n\r\nYou can unsubscribe by going to <unsubscribe_url>\r\n\r\n-- \r\n<board_mailer>\r\n(Do not reply to this message)', 0, 1),
(532, 'pun_mail_welcome', 'Subject: Welcome to <board_title>!\r\n\r\nThank you for registering in the forums at <base_url>. Your account details are:\r\n\r\nUsername: <username>\r\nPassword: <password>\r\n\r\nLogin at <login_url> to activate the account.\r\n\r\n--\r\n<board_mailer>\r\n(Do not reply to this message)', 0, 1),
(533, 'pun_stopwords', 'about\r\nafter\r\nago\r\nall\r\nalmost\r\nalong\r\nalso\r\nany\r\nanybody\r\nanywhere\r\nare\r\narent\r\naround\r\nask\r\nbeen\r\nbefore\r\nbeing\r\nbetween\r\nbut\r\ncame\r\ncan\r\ncant\r\ncome\r\ncould\r\ncouldnt\r\ndid\r\ndidnt\r\ndoes\r\ndoesnt\r\ndont\r\neach\r\neither\r\nelse\r\neven\r\nevery\r\neverybody\r\neveryone\r\nfind\r\nfor\r\nfrom\r\nget\r\ngoing\r\ngone\r\ngot\r\nhad\r\nhas\r\nhave\r\nhavent\r\nhaving\r\nher\r\nhere\r\nhers\r\nhim\r\nhis\r\nhow\r\nill\r\ninto\r\nisnt\r\nits\r\nive\r\njust\r\nknow\r\nless\r\nlike\r\nmake\r\nmany\r\nmay\r\nmore\r\nmost\r\nmuch\r\nmust\r\nnear\r\nnever\r\nnone\r\nnothing\r\nnow\r\noff\r\noften\r\nonce\r\none\r\nonly\r\nother\r\nour\r\nours\r\nout\r\nover\r\nplease\r\nrather\r\nreally\r\nsaid\r\nsee\r\nshe\r\nshould\r\nsmall\r\nsome\r\nsomething\r\nsometime\r\nsomewhere\r\ntake\r\nthan\r\nthank\r\nthanks\r\nthat\r\nthats\r\nthe\r\ntheir\r\ntheirs\r\nthem\r\nthen\r\nthere\r\nthese\r\nthey\r\nthing\r\nthink\r\nthis\r\nthose\r\nthough\r\nthrough\r\nthus\r\ntoo\r\ntrue\r\ntwo\r\nunder\r\nuntil\r\nupon\r\nuse\r\nvery\r\nwant\r\nwas\r\nway\r\nwell\r\nwere\r\nwhat\r\nwhen\r\nwhere\r\nwhich\r\nwho\r\nwhom\r\nwhose\r\nwhy\r\nwill\r\nwith\r\nwithin\r\nwithout\r\nwould\r\nyes\r\nyet\r\nyou\r\nyour\r\nyours', 0, 1),
(538, 'clearedsuccessfully', 'Cleared Successfully!', 0, 1),
(537, 'clear', 'Clear', 0, 1),
(539, 'pun_profile_notactivated', 'This user hasn''t activated his/her account yet. The account is activated when he/she logs in the first time.', 0, 1),
(540, 'pun_profile_toolargeini', 'The selected file was too large to upload. The server didn''t allow the upload.', 0, 1),
(541, 'pun_profile_signatureinfo', 'A signature is a small piece of text that is attached to your posts. In it, you can enter just about anything you like. Perhaps you would like to enter your favourite quote or your star sign. It''s up to you! In your signature you can use BBCode if it is allowed in this particular forum. You can see the features that are allowed/enabled listed below whenever you edit your signature.', 0, 1),
(542, 'pun_profile_showimagessigsinfo', 'Disable this if you don''t want to see images in signatures (i.e. images displayed with the [img]-tag).', 0, 1),
(543, 'pun_profile_showimagesinfo', 'Disable this if you don''t want to see images in posts (i.e. images displayed with the [img]-tag).', 0, 1),
(544, 'pun_profile_activatee-mailsent', 'An email has been sent to the specified address with instructions on how to activate the new e-mail address. If it doesn''t arrive you can contact the forum administrator at', 0, 1),
(545, 'pun_profile_avatarinfo', 'An avatar is a small image that will be displayed with all your posts. You can upload an avatar by clicking the link below. The checkbox ''Use avatar'' below must be checked in order for the avatar to be visible in your posts.', 0, 1),
(546, 'pun_prof_reg_usernamereservedchars', 'Usernames may not contain all the characters '', " and [ or ] at once. Please choose another username.', 0, 1),
(547, 'pun_register_rege-mail', 'Thank you for registering. Your password has been sent to the specified address. If it doesn''t arrive you can contact the forum administrator at', 0, 1),
(548, 'themenotfound', 'Error: Theme not found.', 0, 1),
(549, 'clearcache', 'Clear Cache', 0, 1),
(1090, 'register', 'Register', 0, 1),
(1091, 'email', 'E-Mail', 0, 1),
(1093, 'logo', 'Logo', 0, 1),
(1094, 'recaptchaincorrect', 'The reCAPTCHA wasn''t entered correctly.', 0, 1),
(1095, 'recaptcha', 'Recaptcha', 0, 1),
(1096, 'changepassword', 'Change Password', 0, 1),
(1097, 'wrongpassword', 'Error: Wrong Password.', 0, 1),
(1098, 'changedsuccessfully', 'Changed Successfully!', 0, 1),
(1099, 'send', 'Send', 0, 1),
(1100, 'undefinederror', 'Undefined Error', 0, 1),
(1101, 'lostpassword', 'Lost Password?', 0, 1),
(1102, 'emailnotfound', 'E-Mail not found in our database.', 0, 1),
(1103, 'maildeliveryfailed', 'Mail Delivery Failed', 0, 1),
(1104, 'passwordsent', 'Password Sent', 0, 1),
(1105, 'lostpassword_subject', 'Password Recovery', 0, 1),
(1106, 'lostpassword_body', 'You have requested to recover your password. Your new password is shown below.\r\n\r\n<password>\r\n\r\nTo activate it, please click the link below.\r\n\r\n<base_url>/lostpassword.php?id=<id>&string=<string>', 0, 1),
(1107, 'emailheaders', 'From: SUIT Framework <admin@brandonevans.org>', 0, 1),
(1108, 'passwordchanged', 'Password Changed', 0, 1),
(1109, 'passwordexpired', 'Password Expired', 0, 1),
(1110, 'infiniteloop', 'Error: Infinite Loop caused by <template>', 0, 1),
(1111, 'pun_help_quotesinfo2', 'If you don''t want to quote anyone in particular, you can use the quote tag without specifying a name.', 0, 1),
(1112, 'wiki', 'Wiki', 0, 1),
(1113, 'bugs', 'Bugs', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `suit_notes`
--

CREATE TABLE IF NOT EXISTS `suit_notes` (
  `content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suit_notes`
--

INSERT INTO `suit_notes` (`content`) VALUES
('With an exception to the upper links not be in the exact right places, glitching out when zooming in and out, and the blank spot where the logo was, I''m proud of your work on the wiki. Maybe you can move the navigation bar up.\r\n\r\nThank you. Tried, but I can''t put my finger on why it won''t go up regardless of the margin or top: values I set it to.. By the way, that editor thing. I wedged it on to edit and adding templates, tested it and it works. Not sure what I am missing though....');

-- --------------------------------------------------------

--
-- Table structure for table `suit_salt`
--

CREATE TABLE IF NOT EXISTS `suit_salt` (
  `content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suit_salt`
--

INSERT INTO `suit_salt` (`content`) VALUES
('fcb2a');

-- --------------------------------------------------------

--
-- Table structure for table `suit_templates`
--

CREATE TABLE IF NOT EXISTS `suit_templates` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `parent` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1199 ;

--
-- Dumping data for table `suit_templates`
--

INSERT INTO `suit_templates` (`id`, `title`, `content`, `parent`) VALUES
(1, 'Global Templates', '', 0),
(954, 'admin_index', '{header}\r\n{admin_index_content}\r\n{footer}', 1),
(3, 'login', '<1>\r\n<form action="<2>?dummy=1" method="post">\r\n<label for="suit_username">[username][colon] <input type="text" name="suit_username" /></label>\r\n<label for="suit_password">[password][colon] <input type="password" name="suit_password" /></label>\r\n<input type="submit" name="suit_login" value="Submit" />\r\n<a href="{base_url}/register.php">[register]</a>\r\n<a href="{base_url}/lostpassword.php">[lostpassword]</a>\r\n</form>', 1),
(4, 'admin_templates', '{admin_protect}{header}\r\n{admin_templates_content}\r\n{footer}', 1),
(5, 'admin_templates_sselect', '<li><div style="float: left;"><a href="{base_url}/admin_templates.php?cmd=view&amp;set=<1>"><2></a> - [associatedthemes][colon] <3></div> <div style="float: right;"><a href="{base_url}/admin_templates.php?cmd=renameset&amp;set=<1>">[rename]</a> [verticalbar] <a href="{base_url}/admin_templates.php?cmd=deleteset&amp;set=<1>">[delete]</a> [verticalbar] <a href="{base_url}/admin_templates.php?cmd=cloneset&amp;set=<1>">[clone]</a></div>\r\n<br />\r\n</li>', 1),
(6, 'admin_templates_tselect', '<li><div style="float: left"><1></div> <div style="text-align: right;"><a href="{base_url}/admin_templates.php?cmd=edit&amp;set=<2>&amp;template=<3>">[edit]</a> [verticalbar] <a href="{base_url}/admin_templates.php?cmd=delete&amp;set=<2>&amp;template=<3>">[delete]</a> [verticalbar] <a href="{base_url}/admin_templates.php?cmd=clone&amp;set=<2>&amp;template=<3>">[clone]</a></div></li>', 1),
(7, 'admin_templates_tedit', '<1>\r\n<form action="admin_templates.php?cmd=edittemplate" method="post">\r\n<input type="hidden" name="set" value="<2>" />\r\n<input type="hidden" name="template" value="<3>" />\r\n[inputtitle][colon] <input type="text" name="title" value="<4>" />\r\n<br />[content][colon] <textarea name="content" rows="20" cols="100" style="width: 100%">\r\n<5></textarea>\r\n<6>\r\n<br /><input type="submit" name="edit" value="[edit]" />\r\n</form>\r\n', 1),
(818, 'punbb_minmax', '// minmax.js - written by Andrew Clover <and@doxdesk.com>\r\n// Adapted for PunBB by Rickard Andersson and Paul Sullivan\r\n\r\n/*@cc_on\r\n@if (@_win32 && @_jscript_version>4)\r\n\r\nvar minmax_elements;\r\n\r\nfunction minmax_bind(el) [openingbrace]\r\n	var em, ms;\r\n	var st= el.style, cs= el.currentStyle;\r\n\r\n	if (minmax_elements==window.undefined) [openingbrace]\r\n		if (!document.body || !document.body.currentStyle) return;\r\n		minmax_elements= new Array();\r\n		window.attachEvent(''onresize'', minmax_delayout);\r\n	[closingbrace]\r\n\r\n	if (cs[openingbracket]''max-width''[closingbracket])\r\n		st[openingbracket]''maxWidth''[closingbracket])= cs[openingbracket]''max-width''[closingbracket]);\r\n\r\n	ms= cs[openingbracket]''maxWidth''[closingbracket]);\r\n	if (ms && ms!=''auto'' && ms!=''none'' && ms!=''0'' && ms!='''') [openingbrace]\r\n		st.minmaxWidth= cs.width;\r\n		minmax_elements[openingbracket]minmax_elements.length[closingbracket]= el;\r\n		minmax_delayout();\r\n	[closingbrace]\r\n[closingbrace]\r\n\r\nvar minmax_delaying= false;\r\nfunction minmax_delayout() [openingbrace]\r\n	if (minmax_delaying) return;\r\n	minmax_delaying= true;\r\n	window.setTimeout(minmax_layout, 0);\r\n[closingbrace]\r\n\r\nfunction minmax_stopdelaying() [openingbrace]\r\n	minmax_delaying= false;\r\n[closingbrace]\r\n\r\nfunction minmax_layout() [openingbrace]\r\n	window.setTimeout(minmax_stopdelaying, 100);\r\n	var i, el, st, cs, optimal, inrange;\r\n	for (i= minmax_elements.length; i-->0;) [openingbrace]\r\n		el= minmax_elements[openingbracket]i[closingbracket]; st= el.style; cs= el.currentStyle;\r\n\r\n		st.width= st.minmaxWidth; optimal= el.offsetWidth;\r\n		inrange= true;\r\n		if (inrange && cs.minWidth && cs.minWidth!=''0'' && cs.minWidth!=''auto'' && cs.minWidth!='''') [openingbrace]\r\n			st.width= cs.minWidth;\r\n			inrange= (el.offsetWidth<optimal);\r\n		[closingbrace]\r\n		if (inrange && cs.maxWidth && cs.maxWidth!=''none'' && cs.maxWidth!=''auto'' && cs.maxWidth!='''') [openingbrace]\r\n			st.width= cs.maxWidth;\r\n			inrange= (el.offsetWidth>optimal);\r\n		[closingbrace]\r\n		if (inrange) st.width= st.minmaxWidth;\r\n	[closingbrace]\r\n[closingbrace]\r\n\r\nvar minmax_SCANDELAY= 500;\r\n\r\nfunction minmax_scan() [openingbrace]\r\n	var el;\r\n	for (var i= 0; i<document.all.length; i++) [openingbrace]\r\n		el= document.all[openingbracket]i[closingbracket];\r\n		if (!el.minmax_bound) [openingbrace]\r\n			el.minmax_bound= true;\r\n			minmax_bind(el);\r\n		[closingbrace]\r\n	[closingbrace]\r\n[closingbrace]\r\n\r\nvar minmax_scanner;\r\nfunction minmax_stop() [openingbrace]\r\n	window.clearInterval(minmax_scanner);\r\n	minmax_scan();\r\n[closingbrace]\r\n\r\nminmax_scan();\r\nminmax_scanner= window.setInterval(minmax_scan, minmax_SCANDELAY);\r\nwindow.attachEvent(''onload'', minmax_stop);\r\n\r\n@end @*/', 1),
(817, 'punbb_css_admin', '#adminconsole .block2 [openingbrace]MARGIN-TOP: 12px[closingbrace]\r\n\r\n/*** Admin Main Content ***/\r\n* HTML #adstats DD [openingbrace]HEIGHT: 1%[closingbrace]\r\n#adstats DD [openingbrace]MARGIN-LEFT: 14em; PADDING: 3px; MARGIN-BOTTOM: 5px; LINE-HEIGHT: 1.5em[closingbrace]\r\n#adstats DT [openingbrace]FLOAT:left; WIDTH: 13em; PADDING: 3px; line-height: 1.5em[closingbrace]\r\n#adstats [openingbrace]PADDING: 15px 15px 5px 10px[closingbrace]\r\n#adintro [openingbrace]PADDING: 5px[closingbrace]\r\n#adintro P [openingbrace]PADDING: 10px[closingbrace]\r\n#adstats DL [openingbrace]PADDING: 5px 0 10px 5px[closingbrace]\r\n\r\n#adminconsole FIELDSET TD [openingbrace]TEXT-ALIGN: left; PADDING: 4px; WHITE-SPACE: normal[closingbrace]\r\n#adminconsole FIELDSET TH [openingbrace]TEXT-ALIGN: left; PADDING: 4px; WHITE-SPACE: normal[closingbrace]\r\n#adminconsole FIELDSET TD SPAN, #adminconsole FIELDSET TH SPAN [openingbrace]DISPLAY: block; FONT-SIZE: 1em; FONT-WEIGHT: normal[closingbrace]\r\n#adminconsole TH [openingbrace]WIDTH: 15em; FONT-WEIGHT: bold[closingbrace]\r\n#adminconsole INPUT, #adminconsole SELECT, #adminconsole TEXTAREA [openingbrace]MARGIN-BOTTOM: 0; MARGIN-TOP: 0; FONT-WEIGHT: normal[closingbrace]\r\n#adminconsole TABLE.aligntop TH, #adminconsole TABLE.aligntop TD [openingbrace]VERTICAL-ALIGN: top[closingbrace]\r\n#adminconsole TABLE.aligntop TH [openingbrace]PADDING-TOP: 0.7em[closingbrace]\r\n#adminconsole TD, #adminconsole TH [openingbrace]BORDER-STYLE: solid; BORDER-WIDTH: 3px 0px 3px 0px[closingbrace]\r\n#adminconsole P [openingbrace]PADDING-BOTTOM: 6px[closingbrace]\r\n#adminconsole .topspace [openingbrace]PADDING-TOP: 6px[closingbrace]\r\n#adminconsole P.submittop, #adminconsole P.submitend [openingbrace]TEXT-ALIGN: center[closingbrace]\r\n#adminconsole TH.hidehead [openingbrace]COLOR: #f1f1f1[closingbrace]\r\n#adminconsole THEAD TH [openingbrace]PADDING-BOTTOM: 0px[closingbrace]\r\n#adminconsole P.linkactions [openingbrace]FONT-WEIGHT: bold; PADDING-LEFT: 5px[closingbrace]\r\n#adminconsole TH INPUT, #adminconsole DIV.fsetsubmit [openingbrace]MARGIN-TOP: 6px[closingbrace]\r\n\r\n/*** Particular table settings ***/\r\n#categoryedit .tcl [openingbrace]WIDTH: 25%[closingbrace]\r\n#censoring .tcl, #censoring .tc2, #ranks .tcl, #ranks .tc2 [openingbrace]WIDTH: 20%[closingbrace]\r\nTABLE#forumperms TH, TABLE#forumperms TD [openingbrace]WHITE-SPACE: normal; WIDTH: auto; TEXT-ALIGN: center[closingbrace]\r\nTABLE#forumperms .atcl [openingbrace]TEXT-ALIGN: left; WIDTH: 15em; WHITE-SPACE: nowrap[closingbrace]\r\n#adminconsole TD.nodefault [openingbrace]BACKGROUND-COLOR: #D59B9B[closingbrace]\r\n\r\n/*** User Search Result Tables ***/\r\n#users2 TH, #users2 TH [openingbrace]TEXT-ALIGN: left[closingbrace]\r\n#users2 .tcl, #users2 .tc3, #users2 .tc5 [openingbrace]WIDTH: 15%; TEXT-ALIGN: left[closingbrace]\r\n#users2 .tc2 [openingbrace]WIDTH: 22%; TEXT-ALIGN: left[closingbrace]\r\n#users2 .tc4 [openingbrace]WIDTH: 8%[closingbrace]\r\n#users2 .tc4 [openingbrace]TEXT-ALIGN: center[closingbrace]\r\n#users2 .tcr [openingbrace]WHITE-SPACE: nowrap[closingbrace]\r\n#adminconsole #linkst, #adminconsole #linksb A [openingbrace]FONT-WEIGHT: bold[closingbrace]\r\n\r\n/*** Plugins ***/\r\n#exampleplugin .inbox [openingbrace]PADDING: 6px 6px 0px 6px[closingbrace]', 1),
(721, 'punbb_admin', '<doctype>\r\n\r\n<html xmlns="http://www.w3.org/1999/xhtml" <!-- forum_local -->>\r\n<head>\r\n<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />\r\n<css>\r\n<!-- forum_head -->\r\n</head>\r\n<body>\r\n<top>\r\n\r\n<div id="brd-wrap" class="brd">\r\n<div <!-- forum_page -->>\r\n\r\n<div id="brd-head" class="gen-content">\r\n	<!-- forum_skip -->\r\n	<!-- forum_title -->\r\n	<!-- forum_desc -->\r\n</div>\r\n\r\n<div id="brd-navlinks" class="gen-content">\r\n	<!-- forum_navlinks -->\r\n	<!-- forum_admod -->\r\n</div>\r\n\r\n<div id="brd-visit" class="gen-content">\r\n	<!-- forum_welcome -->\r\n	<!-- forum_visit -->\r\n</div>\r\n\r\n<div class="hr"><hr /></div>\r\n\r\n<div id="brd-main">\r\n	<!-- forum_main_title -->\r\n	<!-- forum_crumbs_top -->\r\n	<!-- forum_main_pagepost_top -->\r\n	<!-- forum_admin_menu -->\r\n	<!-- forum_admin_submenu -->\r\n	<!-- forum_main -->\r\n	<!-- forum_main_pagepost_end -->\r\n	<!-- forum_crumbs_end -->\r\n</div>\r\n\r\n<div class="hr"><hr /></div>\r\n\r\n<div id="brd-about" class="gen-content">\r\n	<!-- forum_about -->\r\n</div>\r\n\r\n<!-- forum_debug -->\r\n\r\n</div>\r\n</div>\r\n\r\n<footer>', 1),
(720, 'punbb_help', '<doctype>\r\n\r\n<html xmlns="http://www.w3.org/1999/xhtml" dir="<pun_content_direction>">\r\n<head>\r\n<meta http-equiv="Content-Type" content="text/html; charset=<pun_char_encoding>" />\r\n<css>\r\n<pun_head>\r\n<style type="text/css">\r\n<punbb_css>\r\n</style>\r\n</head>\r\n<body>\r\n<top>\r\n\r\n<div id="punwrap">\r\n<div id="helpfile" class="pun">\r\n\r\n<pun_main>\r\n\r\n</div>\r\n</div>\r\n\r\n<footer>', 1),
(719, 'punbb_main', '<doctype>\r\n\r\n<html xmlns="http://www.w3.org/1999/xhtml" <!-- forum_local -->>\r\n<head>\r\n<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />\r\n<css>\r\n<!-- forum_head -->\r\n</head>\r\n<body>\r\n<top>\r\n<div id="brd-wrap" class="brd">\r\n\r\n<div id="brd-head" class="gen-content">\r\n	<!-- forum_skip -->\r\n	<!-- forum_title -->\r\n	<!-- forum_desc -->\r\n</div>\r\n\r\n<div id="brd-navlinks" class="gen-content">\r\n	<!-- forum_navlinks -->\r\n	<!-- forum_admod -->\r\n</div>\r\n\r\n<div id="brd-visit" class="gen-content">\r\n	<!-- forum_welcome -->\r\n	<!-- forum_visit -->\r\n</div>\r\n\r\n<!-- forum_announcement -->\r\n\r\n<div class="hr"><hr /></div>\r\n\r\n<div id="brd-main">\r\n	<!-- forum_main_title -->\r\n	<!-- forum_crumbs_top -->\r\n	<!-- forum_main_menu -->\r\n	<!-- forum_main_pagepost_top -->\r\n	<!-- forum_main -->\r\n	<!-- forum_main_pagepost_end -->\r\n	<!-- forum_crumbs_end -->\r\n</div>\r\n<!-- forum_qpost -->\r\n\r\n<!-- forum_info -->\r\n\r\n<div class="hr"><hr /></div>\r\n\r\n<div id="brd-about" class="gen-content">\r\n	<!-- forum_about -->\r\n</div>\r\n\r\n<!-- forum_debug -->\r\n\r\n</div>\r\n<footer>', 1),
(713, 'admin_errorlog_link', '<a href="{base_url}/admin_errorlog.php?start=<start><limit>"><display></a>', 1),
(724, 'punbb_maintenance', '<doctype>\r\n\r\n<html xmlns="http://www.w3.org/1999/xhtml" <!-- forum_local -->>\r\n<head>\r\n<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />\r\n<!-- forum_head -->\r\n</head>\r\n<body>\r\n\r\n<div id="brd-wrap" class="brd-page">\r\n<div id="brd-maint" class="brd">\r\n\r\n<!-- forum_maint_main -->\r\n\r\n</div>\r\n</div>\r\n\r\n</body>\r\n</html>', 1),
(722, 'punbb', '<punbb>', 1),
(725, 'punbb_redirect', '<doctype>\r\n\r\n<html xmlns="http://www.w3.org/1999/xhtml" <!-- forum_local -->>\r\n<head>\r\n<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />\r\n<css>\r\n<!-- forum_head -->\r\n</head>\r\n<body>\r\n\r\n<top>\r\n\r\n<div id="brd-wrap" class="brd-page">\r\n<div id="brd-redirect" class="brd">\r\n\r\n<!-- forum_redir_main -->\r\n\r\n<!-- forum_debug -->\r\n\r\n</div>\r\n</div>\r\n\r\n<footer>', 1),
(727, 'css', '<link rel="stylesheet" type="text/css" href="{base_url}/stylesheet.css" />', 1),
(1194, 'bugzilla_buglist', '<bugzilla>', 1),
(1191, 'bugzilla', '', 1),
(1192, 'bugzilla_index', '<bugzilla>', 1),
(1193, 'bugzilla_attachment', '<bugzilla>', 1),
(1139, 'lostpassword', '{header}\r\n<message>\r\n<form action="lostpassword.php?cmd=lostpassword" method="post">\r\n[email][colon] <input type="text" name="email" />\r\n<br /><input type="submit" name="lostpassword" value="[send]" />\r\n</form>\r\n{footer}', 1),
(730, 'base_url', '<1>', 1),
(731, 'mediawiki_output', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html xmlns="<xhtmldefaultnamespace>" <xhtmlnamespaces>xml:lang="<lang>" lang="<lang>" dir="<dir>">\r\n	<head>\r\n		<meta http-equiv="Content-Type" content="<mimetype>; charset=<charset>" />\r\n		<headlinks>\r\n		<title><pagetitle></title>\r\n		<style type="text/css" media="screen, projection">/*<![CDATA[*/\r\n			@import "<stylepath>/common/shared.css?<wgStyleVersion>";\r\n			@import "<stylepath>/<stylename>/main.css?<wgStyleVersion>";\r\n		/*]]>*/</style>\r\n		<link rel="stylesheet" type="text/css" <printable> href="<printcss>?<wgStyleVersion>" />\r\n		<IE50> <IE55> <IE60> <IE70><!--[if lt IE 7]><IE><meta http-equiv="imagetoolbar" content="no" /><![endif]-->\r\n		\r\n<data>\r\n                \r\n		<script type="<jsmimetype>" src="<stylepath>/common/wikibits.js?<wgStyleVersion>"><!-- wikibits js --></script>\r\n		<!-- Head Scripts -->\r\n<headscripts>\r\n<jsvarurl>\r\n<pagecss>\r\n<usercss>\r\n<userjs>\r\n<userjsprev>\r\n<trackbackhtml>\r\n	</head>\r\n<body<body_ondblclick>\r\n<body_onload>\r\n class="mediawiki <nsclass> <dir> <pageclass>">\r\n	<div id="globalWrapper">\r\n		<div id="column-content">\r\n	<div id="content">\r\n		<a name="top" id="top"></a>\r\n		<sitenotice>\r\n		<h1 class="firstHeading"><title></h1>\r\n		<div id="bodyContent">\r\n			<h3 id="siteSub"><tagline></h3>\r\n			<div id="contentSub"><subtitle></div>\r\n			<undelete>\r\n			<newtalk>\r\n			<showjumplinks>\r\n			<!-- start content -->\r\n			<bodytext>\r\n			<catlinks>\r\n			<!-- end content -->\r\n			<div class="visualClear"></div>\r\n		</div>\r\n	</div>\r\n		</div>\r\n		<div id="column-one">\r\n	<div id="p-cactions" class="portlet">\r\n		<h5><views></h5>\r\n		<div class="pBody">\r\n			<ul>\r\n			<contentactions>\r\n			</ul>\r\n		</div>\r\n	</div>\r\n	<div class="portlet" id="p-personal">\r\n		<h5><personaltools></h5>\r\n		<div class="pBody">\r\n			<ul>\r\n			<personal_urls>\r\n			</ul>\r\n		</div>\r\n	</div>\r\n	<div class="portlet" id="p-logo">\r\n		<a style="background-image: url(<logopath>);" href="<navurls>"<n-mainpage>></a>\r\n	</div>\r\n	<script type="<jsmimetypes>"> if (window.isMSIE55) fixalpha(); </script>\r\n<sidebar>\r\n		</div><!-- end of the left (by default at least) column -->\r\n			<div class="visualClear"></div>\r\n			<div id="footer">\r\n<poweredbyico>\r\n<copyrightico>\r\n			<ul id="f-list">\r\n<footerlinks>\r\n			</ul>\r\n		</div>\r\n</div>\r\n<bottomscripts>\r\n<reporttime>\r\n<debug>\r\n<!-- Debug output:\r\n<debug2>\r\n\r\n-->\r\n</body></html>', 1),
(1134, 'admin_templates_content', '<1>', 1),
(816, 'punbb_css', '/****************************************************************/\r\n/* 1. IMPORTED STYLESHEETS */\r\n/****************************************************************/\r\n\r\n/****************************************************************/\r\n/* 1. INITIAL SETTINGS */\r\n/****************************************************************/\r\n\r\n.pun TABLE, .pun DIV, .pun FORM, .pun P, .pun H1, .pun H2, .pun H3,\r\n.pun H4, .pun PRE, .pun BLOCKQUOTE, .pun UL, .pun OL, .pun LI, .pun DL,\r\n.pun DT, .pun DD, .pun TH, .pun TD, .pun FIELDSET, .pun IMG [openingbrace]\r\n	MARGIN: 0px;\r\n	PADDING: 0px;\r\n	FONT-WEIGHT: normal;\r\n	LIST-STYLE: none;\r\n[closingbrace]\r\n\r\n.pun IMG [openingbrace]BORDER: none[closingbrace]\r\n\r\n.pun INPUT, .pun SELECT, .pun TEXTAREA, .pun OPTGROUP [openingbrace]MARGIN: 0[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 2. STRUCTURAL SETTINGS - VERY IMPORTANT - DO NOT CHANGE */\r\n/****************************************************************/\r\n\r\n/* 2.1 Clearing floats and invisible items */\r\n.pun .clearer, .pun .nosize [openingbrace]\r\n	HEIGHT: 0;\r\n	WIDTH: 0;\r\n	LINE-HEIGHT: 0;\r\n	FONT-SIZE: 0;\r\n	OVERFLOW: hidden\r\n[closingbrace]\r\n\r\n.pun .clearer, .pun .clearb [openingbrace]CLEAR: both[closingbrace]\r\n.pun .nosize [openingbrace]POSITION: absolute; LEFT: -10000px[closingbrace]\r\n\r\n/* 2.2 Overflow settings for posts */\r\n\r\nDIV.blockpost DIV.box, DIV.postleft, DIV.postsignature, DIV.postmsg [openingbrace]OVERFLOW: hidden[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 3. BUG FIXES - VERY IMPORTANT - DO NOT CHANGE */\r\n/****************************************************************/\r\n\r\n/* 3.1 This attempts to eliminate rounding errors in Gecko browsers. */\r\n\r\nDIV>DIV>DIV.postfootleft, DIV>DIV>DIV.postfootright [openingbrace]PADDING-TOP: 1px; MARGIN-TOP: -1px[closingbrace]\r\n\r\n/* 3.2 This is only visible to IE6 Windows and cures various bugs. Do not alter comments */\r\n\r\n/* Begin IE6Win Fix \\*/\r\n* HTML .inbox, * HTML .inform, * HTML .pun, * HTML .intd, * HTML .tclcon [openingbrace]HEIGHT: 1px[closingbrace]\r\n* HTML .inbox DIV.postmsg [openingbrace]WIDTH: 98%[closingbrace]\r\n/* End of IE6Win Fix */\r\n\r\n/* 3.3 This is the equivelant of 3.2 but for IE7. It is visible to other browsers\r\nbut does no harm */\r\n\r\n/*Begin IE7Win Fix */\r\n.pun, .pun .inbox, .pun .inform, .pun .intd, .pun .tclcon [openingbrace]min-height: 1px[closingbrace]\r\n/* End of IE7Win Fix */\r\n\r\n/****************************************************************/\r\n/* 4. HIDDEN ELEMENTS */\r\n/****************************************************************/\r\n\r\n/* These are hidden in normal display. Add comments to make them visible */\r\n\r\n#brdfooter H2, #brdstats H2, #brdstats .conl DT, #brdstats .conr DT,\r\n#modcontrols DT, #searchlinks DT, DIV.postright H3 [openingbrace]\r\n	POSITION: absolute;\r\n	DISPLAY: block;\r\n	OVERFLOW: hidden;\r\n	WIDTH: 1em;\r\n	LEFT: -999em\r\n[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 5. BOX CONTAINERS AND FLOATS */\r\n/****************************************************************/\r\n\r\n/* 5.1. Setup all left and right content using floats. */  \r\n\r\n.conr [openingbrace]\r\n	FLOAT: right;\r\n	TEXT-ALIGN: right;\r\n	CLEAR: right;\r\n	WIDTH: 40%\r\n[closingbrace]\r\n\r\n.conl [openingbrace]\r\n	FLOAT: left;\r\n	WIDTH: 55%;\r\n	OVERFLOW: hidden;\r\n	WHITE-SPACE: nowrap\r\n[closingbrace]\r\n\r\nLABEL.conl [openingbrace]\r\n	WIDTH: auto;\r\n	OVERFLOW: visible;\r\n	MARGIN-RIGHT: 10px\r\n[closingbrace]\r\n\r\n/* 5.2 Set up page numbering and posts links */\r\n\r\nDIV.linkst .conl, DIV.linksb .conl, DIV.postlinksb .conl [openingbrace]WIDTH:18em[closingbrace]\r\n\r\nDIV.linkst .conr, DIV.linksb .conr, DIV.postlinksb .conr [openingbrace]WIDTH:16em[closingbrace]\r\n\r\nFORM DIV.linksb .conr [openingbrace]WIDTH: 32em[closingbrace]\r\n\r\n/* 5.3 Keep breadcrumbs from shifting to the right when wrapping */\r\n\r\n.linkst UL, linksb UL, .postlinksb UL [openingbrace]MARGIN-LEFT: 18em[closingbrace]\r\n\r\n/* 5.4 Settings for Profile and Admin interface.*/\r\n\r\nDIV.block2col [openingbrace]PADDING-BOTTOM: 1px[closingbrace]\r\n\r\nDIV.block2col DIV.blockform, DIV.block2col DIV.block, #viewprofile DD [openingbrace]MARGIN-LEFT: 14em[closingbrace]\r\n\r\nDIV.blockmenu, #viewprofile DT [openingbrace]\r\n	FLOAT:left;\r\n	WIDTH: 13em\r\n[closingbrace]\r\n\r\n#profileavatar IMG [openingbrace]\r\n	FLOAT: right;\r\n	MARGIN-LEFT: 1em\r\n[closingbrace]\r\n\r\n#viewprofile DL [openingbrace]FLOAT: left; WIDTH: 100%; OVERFLOW: hidden[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 6. TABLE SETUP */\r\n/****************************************************************/\r\n\r\n/* 6.1 Table Basic Setup */\r\n\r\n.pun TABLE [openingbrace]WIDTH: 100%[closingbrace]\r\n\r\n/* 6.2 Fixed Table Setup */\r\n\r\n#punindex TABLE, #vf TABLE [openingbrace]TABLE-LAYOUT: fixed[closingbrace]\r\n\r\n.tcl [openingbrace]TEXT-ALIGN: left; WIDTH: 50%[closingbrace]\r\n\r\n.tc2, .tc3, .tcmod [openingbrace]WIDTH: 9%; TEXT-ALIGN: center[closingbrace]\r\n\r\n.tcr [openingbrace]WIDTH: 32%; TEXT-ALIGN: left[closingbrace]\r\n\r\n#punsearch #vf .tcl, #punmoderate #vf .tcl [openingbrace]WIDTH: 41%[closingbrace]\r\n\r\n#punsearch #vf .tc2 [openingbrace]WIDTH: 18%; TEXT-ALIGN: left[closingbrace]\r\n\r\n.tcl, .tcr [openingbrace]OVERFLOW: HIDDEN[closingbrace]\r\n\r\n/* 6.3 Other Table Setup */\r\n\r\n#users1 .tcl [openingbrace]WIDTH: 40%[closingbrace]\r\n\r\n#users1 .tcr [openingbrace]WIDTH: 25%[closingbrace]\r\n\r\n#users1 .tc2 [openingbrace]WIDTH: 25%; TEXT-ALIGN: left[closingbrace]\r\n\r\n#users1 .tc3 [openingbrace]WIDTH: 10%; TEXT-ALIGN: center[closingbrace]\r\n\r\n#debug .tcr [openingbrace]WIDTH: 85%; WHITE-SPACE: normal[closingbrace]\r\n\r\n#punindex TD.tcr SPAN.byuser [openingbrace]DISPLAY: block[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 7. VIEWTOPIC SETUP */\r\n/****************************************************************/\r\n\r\n/* 7.1 This is the basic structure. */\r\n\r\nDIV.postleft, DIV.postfootleft [openingbrace]\r\n	FLOAT:left;\r\n	WIDTH: 18em;\r\n	OVERFLOW: hidden;\r\n	POSITION: relative;\r\n[closingbrace]\r\n	\r\nDIV.postright, DIV.postfootright [openingbrace]\r\n	BORDER-LEFT-WIDTH: 18em;\r\n	BORDER-LEFT-STYLE: solid\r\n[closingbrace]\r\n\r\nDIV.postfootright, P.multidelete [openingbrace]TEXT-ALIGN: right[closingbrace]\r\n\r\nDIV.blockpost>DIV>DIV.inbox [openingbrace]PADDING-BOTTOM: 1px[closingbrace]\r\n\r\n/* 7.3 This is the div which actually contains the post and is inside .postright */\r\n\r\nDIV.postmsg [openingbrace]WIDTH:100%[closingbrace]\r\n\r\n/* 7.4 These items control overflow and scrolling within posts. */\r\n\r\nDIV.incqbox [openingbrace]WIDTH: 100%; OVERFLOW: hidden[closingbrace]\r\nDIV.scrollbox [openingbrace]WIDTH: 100%; OVERFLOW: auto[closingbrace]\r\nIMG.postimg [openingbrace]max-width: 100%[closingbrace]\r\nA .postimg [openingbrace]max-width: 100%[closingbrace]\r\n\r\n/* 7.5 Turn off the poster information column for preview */\r\n\r\n#postpreview DIV.postright [openingbrace]BORDER-LEFT: none[closingbrace]\r\n\r\n/* 7.6 Create the horizontal line above signatures */\r\n\r\nDIV.postsignature HR [openingbrace]\r\n	MARGIN-LEFT: 0px;\r\n	WIDTH: 200px;\r\n	TEXT-ALIGN: left;\r\n	HEIGHT: 1px;\r\n	BORDER:none\r\n[closingbrace]\r\n\r\n/* 7.7 Maximum height for search results as posts. Position go to post link */\r\n\r\nDIV.searchposts DIV.postmsg [openingbrace]HEIGHT: 8em[closingbrace]\r\nDIV.searchposts DD P [openingbrace]PADDING-TOP: 3em[closingbrace]\r\n\r\n/* 7.8 Class for bbcode [openingbracket]u[closingbracket] */\r\n\r\nSPAN.bbu [openingbrace]TEXT-DECORATION: underline[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 8. LISTS SPECIAL SETTINGS */\r\n/****************************************************************/\r\n\r\n/* 8.1 Horizontal display of online list, main navigation menu and breadcrumbs */\r\n\r\n#onlinelist DD, #onlinelist DT, #brdmenu LI, DIV.linkst LI, DIV.linksb LI, DIV.postlinksb LI,\r\nDIV.postfootright LI, UL.bblinks LI [openingbrace]\r\n	DISPLAY: inline;\r\n	HEIGHT: 0\r\n[closingbrace]\r\n\r\n/* 8.2 Turn on square icon for posterror list */\r\n\r\n#posterror UL LI [openingbrace]LIST-STYLE: square inside[closingbrace]\r\n\r\n/* 8.3 Right alignment of descriptions in ordinary member view of other members profiles */\r\n\r\n#viewprofile DT [openingbrace]TEXT-ALIGN: right[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 9. FORM SETTINGS */\r\n/****************************************************************/\r\n\r\n/* 9.1 Makes textareas and long text inputs shrink with page */\r\n\r\nDIV.txtarea [openingbrace]WIDTH: 75%[closingbrace]\r\n\r\nDIV.txtarea TEXTAREA, INPUT.longinput [openingbrace]WIDTH: 100%[closingbrace]\r\n\r\n.pun LABEL [openingbrace]DISPLAY: block[closingbrace]\r\n\r\n#qjump SELECT [openingbrace]WIDTH: 50%[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 10. HELP FILES AND MISC. */\r\n/****************************************************************/\r\n\r\n/* 10.1 Put some space between sections of the help file */\r\n\r\n#helpfile H2 [openingbrace]MARGIN-TOP: 12px[closingbrace]\r\n\r\n/* 10.2 Internal padding */\r\n\r\n#helpfile DIV.box [openingbrace]PADDING: 10px[closingbrace]\r\n\r\n/* 10.3 Other templates */\r\n\r\n#punredirect DIV.block, #punmaint DIV.block [openingbrace]MARGIN: 50px 20% 12px 20%[closingbrace]\r\n/****************************************************************/\r\n/* 1. BACKGROUND AND TEXT COLOURS */\r\n/****************************************************************/\r\n\r\n/* 1.1 Default background colour and text colour */\r\n\r\nBODY [openingbrace]BACKGROUND-COLOR: #FFF[closingbrace]\r\n\r\n.pun [openingbrace]COLOR: #333[closingbrace]\r\n\r\nDIV.box, .pun BLOCKQUOTE, DIV.codebox, #adminconsole FIELDSET TH, .rowodd, .roweven [openingbrace]BACKGROUND-COLOR: #F1F1F1[closingbrace]\r\n#adminconsole TD, #adminconsole TH [openingbrace]BORDER-COLOR: #F1F1F1[closingbrace]\r\n\r\n/* 1. 2 Darker background colours */\r\n\r\nTD.tc2, TD.tc3, TD.tcmod, #postpreview, #viewprofile DD, DIV.forminfo,\r\n#adminconsole FIELDSET TD, DIV.blockmenu DIV.box, #adstats DD [openingbrace]BACKGROUND-COLOR: #DEDFDF[closingbrace]\r\n\r\n/* 1.3 Main headers and navigation bar background and text colour */\r\n\r\n.pun H2, #brdmenu [openingbrace]BACKGROUND-COLOR: #0066B9; COLOR: #FFF[closingbrace]\r\n\r\n/* 1.4 Table header rows */\r\n\r\n.pun TH [openingbrace]BACKGROUND-COLOR: #D1D1D1[closingbrace]\r\n\r\n/* 1.5 Fieldset legend text colour */\r\n\r\n.pun LEGEND [openingbrace]COLOR: #005CB1[closingbrace]\r\n\r\n/* 1.6 Highlighted text for various items */\r\n\r\n.pun DIV.blockmenu LI.isactive A, #posterror LI STRONG [openingbrace]COLOR: #333[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 2. POST BACKGROUNDS AND TEXT */\r\n/****************************************************************/\r\n\r\n/* 2.1 This is the setup for posts. */\r\n\r\nDIV.blockpost DIV.box, DIV.postright, DIV.postfootright [openingbrace]BACKGROUND-COLOR: #DEDFDF[closingbrace]\r\nDIV.postright, DIV.postfootright [openingbrace]BORDER-LEFT-COLOR: #f1f1f1[closingbrace]\r\nDIV.postleft, DIV.postfootleft, DIV.blockpost LABEL [openingbrace]BACKGROUND-COLOR: #F1F1F1[closingbrace]\r\n\r\n/* 2.2 Background for post headers and text colour for post numbers in viewtopic */\r\n\r\nDIV.blockpost H2 [openingbrace]BACKGROUND-COLOR: #006FC9[closingbrace]\r\nDIV.blockpost H2 SPAN.conr [openingbrace]COLOR: #AABDCD[closingbrace]\r\n\r\n/* 2.3 This is the line above the signature in posts. Colour and background should be the same */\r\n\r\n.pun HR [openingbrace]BACKGROUND-COLOR: #333; COLOR: #333[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 3. BORDER COLOURS */\r\n/****************************************************************/\r\n\r\n/* 3.1 All external borders */\r\n\r\nDIV.box [openingbrace]BORDER-COLOR: #0066B9[closingbrace]\r\n\r\n/* 3.2 Makes the top border of posts match the colour used for post headers */\r\n\r\nDIV.blockpost DIV.box [openingbrace]BORDER-COLOR: #006fC9 #0066B9 #0066B9[closingbrace]\r\n\r\n/* 3.3 Table internal borders. By default TH is same as background so border is invisible */\r\n\r\n.pun TD [openingbrace]BORDER-COLOR: #BBCEDE[closingbrace]\r\n.pun TH [openingbrace]BORDER-COLOR: #D1D1D1[closingbrace]\r\n\r\n/* 3.4 Creates the inset border for quote boxes, code boxes and form info boxes */\r\n\r\n.pun BLOCKQUOTE, DIV.codebox, DIV.forminfo, DIV.blockpost LABEL [openingbrace]BORDER-COLOR: #ACA899 #FFF #FFF #ACA899[closingbrace]\r\n\r\n/* 3.5 Gecko''s default fieldset borders are really nasty so this gives them a colour\r\nwithout interferring with IE''s rather nice default */\r\n\r\n.pun DIV>FIELDSET [openingbrace]BORDER-COLOR: #ACA899[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 4. LINK COLOURS */\r\n/****************************************************************/\r\n\r\n/* 4.1 This is the default for all links */\r\n\r\n.pun A:link, .pun A:visited [openingbrace]COLOR: #005CB1[closingbrace]\r\n.pun A:hover [openingbrace]COLOR: #B42000[closingbrace]\r\n\r\n/* 4.2 This is the colour for links in header rows and the navigation bar */\r\n\r\n.pun H2 A:link, .pun H2 A:visited, #brdmenu A:link, #brdmenu A:visited [openingbrace]COLOR: #FFF[closingbrace]\r\n.pun H2 A:hover, #brdmenu A:hover [openingbrace]COLOR: #FFF[closingbrace]\r\n\r\n/* 4.3 This is for closed topics and "hot" links */\r\n\r\nLI.postreport A:link, LI.postreport A:visited, TR.iclosed TD.tcl A:link, TR.iclosed TD.tcl A:visited [openingbrace]COLOR: #888[closingbrace]\r\nLI.postreport A:hover, TR.iclosed TD.tcl A:hover [openingbrace]COLOR: #AAA[closingbrace]\r\nLI.maintenancelink A:link, LI.maintenancelink A:visited [openingbrace]COLOR: #B42000[closingbrace]\r\nLI.maintenancelink A:hover [openingbrace]COLOR: #B42000[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 5. POST STATUS INDICATORS */\r\n/****************************************************************/\r\n\r\n/* These are the post status indicators which appear at the left of some tables. \r\n.inew = new posts, .iredirect = redirect forums, .iclosed = closed topics and\r\n.isticky = sticky topics. The default is "icon". By default only .inew is different.*/\r\n\r\nDIV.icon [openingbrace]BORDER-COLOR: #E6E6E6 #DEDEDE #DADADA #E2E2E2[closingbrace]\r\nTR.iredirect DIV.icon [openingbrace]BORDER-COLOR: #F1F1F1 #F1F1F1 #F1F1F1 #F1F1F1[closingbrace]\r\nDIV.inew [openingbrace]BORDER-COLOR: #0080D7 #0065C0 #0058B3 #0072CA[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 2. TEXT SETTINGS */\r\n/****************************************************************/\r\n\r\n/* 2.1 This sets the default Font Group */\r\n\r\n.pun, .pun INPUT, .pun SELECT, .pun TEXTAREA, .pun OPTGROUP [openingbrace]\r\n	FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif\r\n[closingbrace]\r\n\r\n.pun [openingbrace]FONT-SIZE: 11px; LINE-HEIGHT: normal[closingbrace]\r\n\r\n/* IEWin Font Size only - to allow IEWin to zoom. Do not remove comments \\*/\r\n* HTML .pun [openingbrace]FONT-SIZE: 68.75%[closingbrace]\r\n/* End IE Win Font Size */\r\n\r\n/* Set font size for tables because IE requires it */\r\n.pun TABLE, .pun INPUT, .pun SELECT, .pun OPTGROUP, .pun TEXTAREA, DIV.postmsg P.postedit [openingbrace]FONT-SIZE: 1em[closingbrace]\r\n\r\n/* 2.2 Set the font-size for preformatted text i.e in code boxes */\r\n\r\n.pun PRE [openingbrace]FONT-FAMILY: monaco, "Bitstream Vera Sans Mono", "Courier New", courier, monospace[closingbrace]\r\n\r\n/* 2.3 Font size for headers */\r\n\r\n.pun H2, .pun H4 [openingbrace]FONT-SIZE: 1em[closingbrace]\r\n.pun H3 [openingbrace]FONT-SIZE: 1.1em[closingbrace]\r\n#brdtitle H1 [openingbrace]FONT-SIZE: 1.4em[closingbrace]\r\n\r\n/* 2.4 Larger text for particular items */\r\n\r\nDIV.postmsg P [openingbrace]LINE-HEIGHT: 1.4[closingbrace]\r\nDIV.postleft DT [openingbrace]FONT-SIZE: 1.1em[closingbrace]\r\n.pun PRE [openingbrace]FONT-SIZE: 1.2em[closingbrace]\r\n\r\n/* 2.5 Bold text */\r\n\r\nDIV.postleft DT, DIV.postmsg H4, TD.tcl H3, DIV.forminfo H3, P.postlink, DIV.linkst LI,\r\nDIV.linksb LI, DIV.postlinksb LI, .blockmenu LI, #brdtitle H1, .pun SPAN.warntext, .pun P.warntext [openingbrace]FONT-WEIGHT: bold[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 3. LINKS */\r\n/****************************************************************/\r\n\r\n/* 3.1 Remove underlining for main menu, post header links, post links and vertical menus */\r\n\r\n#brdmenu A:link, #brdmenu A:visited, .blockpost DT A:link, .blockpost DT A:visited, .blockpost H2 A:link,\r\n.blockpost H2 A:visited, .postlink A:link, .postlink A:visited, .postfootright A:link, .postfootright A:visited,\r\n.blockmenu A:link, .blockmenu A:visited [openingbrace]\r\n	TEXT-DECORATION: none\r\n[closingbrace]\r\n\r\n/* 3.2 Underline on hover for links in headers and main menu */\r\n\r\n#brdmenu A:hover, .blockpost H2 A:hover [openingbrace]TEXT-DECORATION: underline[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 4. BORDER WIDTH AND STYLE */\r\n/****************************************************************/\r\n\r\n/* 4.1 By default borders are 1px solid */\r\n\r\nDIV.box, .pun TD, .pun TH, .pun BLOCKQUOTE, DIV.codebox, DIV.forminfo, DIV.blockpost LABEL [openingbrace]\r\n	BORDER-STYLE: solid;\r\n	BORDER-WIDTH: 1px\r\n[closingbrace]\r\n\r\n/* 4.2 Special settings for the board header. */\r\n\r\n#brdheader DIV.box [openingbrace]BORDER-TOP-WIDTH: 4px[closingbrace]\r\n\r\n/* 4.3 Borders for table cells */\r\n\r\n.pun TD, .pun TH [openingbrace]\r\n	BORDER-BOTTOM: none;\r\n	BORDER-RIGHT: none\r\n[closingbrace]\r\n\r\n.pun .tcl [openingbrace]BORDER-LEFT: none[closingbrace]\r\n\r\n/* 4.4 Special setting for fieldsets to preserve IE defaults */\r\n\r\nDIV>FIELDSET [openingbrace]\r\n	BORDER-STYLE: solid;\r\n	BORDER-WIDTH: 1px\r\n[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 5. VERTICAL AND PAGE SPACING */\r\n/****************************************************************/\r\n\r\n/* 5.1 Page margins */\r\n\r\n/*HTML, BODY [openingbrace]MARGIN: 0; PADDING: 0[closingbrace]*/\r\n#punwrap [openingbrace]margin:12px 20px[closingbrace]\r\n\r\n/* 5.2 Creates vertical space between main board elements (Margins) */\r\n\r\nDIV.blocktable, DIV.block, DIV.blockform, DIV.block2col, #postreview [openingbrace]MARGIN-BOTTOM: 12px[closingbrace]\r\n#punindex DIV.blocktable, DIV.blockpost [openingbrace]MARGIN-BOTTOM: 6px[closingbrace]\r\nDIV.block2col DIV.blockform, DIV.block2col DIV.block [openingbrace]MARGIN-BOTTOM: 0px[closingbrace]\r\n\r\n/* 5.3 Remove space above breadcrumbs, postlinks and pagelinks with a negative top margin */\r\n\r\nDIV.linkst, DIV.linksb [openingbrace]MARGIN-TOP: -12px[closingbrace]\r\nDIV.postlinksb [openingbrace]MARGIN-TOP: -6px[closingbrace]\r\n\r\n/* 5.4 Put a 12px gap above the board information box in index because the category tables only\r\nhave a 6px space beneath them */\r\n\r\n#brdstats [openingbrace]MARGIN-TOP: 12px[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 6. SPACING AROUND CONTENT */\r\n/****************************************************************/\r\n\r\n/* 6.1 Default padding for main items */\r\n\r\nDIV.block DIV.inbox, DIV.blockmenu DIV.inbox [openingbrace]PADDING: 3px 6px[closingbrace]\r\n.pun P, .pun UL, .pun DL, DIV.blockmenu LI, .pun LABEL, #announce DIV.inbox DIV [openingbrace]PADDING: 3px 0[closingbrace]\r\n.pun H2 [openingbrace]PADDING: 4px 6px[closingbrace]\r\n\r\n/* 6.2 Special spacing for various elements */\r\n\r\n.pun H1 [openingbrace]PADDING: 3px 0px 0px 0[closingbrace]\r\n#brdtitle P [openingbrace]PADDING-TOP: 0px[closingbrace]\r\nDIV.linkst [openingbrace]PADDING: 8px 6px 3px 6px[closingbrace]\r\nDIV.linksb, DIV.postlinksb [openingbrace]PADDING: 3px 6px 8px 6px[closingbrace]\r\n#brdwelcome, #brdfooter DL A, DIV.blockmenu LI, DIV.rbox INPUT  [openingbrace]LINE-HEIGHT: 1.4em[closingbrace]\r\n#viewprofile DT, #viewprofile DD [openingbrace]PADDING: 0 3px; LINE-HEIGHT: 2em[closingbrace]\r\n\r\n/* 6.4 Create some horizontal spacing for various elements */\r\n\r\n#brdmenu LI, DIV.rbox INPUT, DIV.blockform P INPUT  [openingbrace]MARGIN-RIGHT: 12px[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 7. SPACING FOR TABLES */\r\n/****************************************************************/\r\n\r\n.pun TH, .pun TD [openingbrace]PADDING: 4px 6px[closingbrace]\r\n.pun TD P [openingbrace]PADDING: 5px 0 0 0[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 8. SPACING FOR POSTS */\r\n/****************************************************************/\r\n\r\n/* 8.1 Padding around left and right columns in viewtopic */\r\n\r\nDIV.postleft DL, DIV.postright [openingbrace]PADDING: 6px[closingbrace]\r\n\r\n/* 8.2 Extra spacing for poster contact details and avatar */\r\n\r\nDD.usercontacts, DD.postavatar [openingbrace]MARGIN-TOP: 5px[closingbrace]\r\nDD.postavatar [openingbrace]MARGIN-BOTTOM: 5px[closingbrace]\r\n\r\n/* 8.3 Extra top spacing for signatures and edited by */\r\n\r\nDIV.postsignature, DIV.postmsg P.postedit [openingbrace]PADDING-TOP: 15px[closingbrace]\r\n\r\n/* 8.4 Spacing for code and quote boxes */\r\n\r\nDIV.postmsg H4 [openingbrace]MARGIN-BOTTOM: 10px[closingbrace]\r\n.pun BLOCKQUOTE, DIV.codebox [openingbrace]MARGIN: 5px 15px 15px 15px; PADDING: 8px[closingbrace]\r\n\r\n/* 8.5 Padding for the action links and online indicator in viewtopic */\r\n\r\nDIV.postfootleft P, DIV.postfootright UL, DIV.postfootright DIV [openingbrace]PADDING: 10px 6px 5px 6px[closingbrace]\r\n\r\n/* 8.6 This is the input on moderators multi-delete view */\r\n\r\nDIV.blockpost INPUT, DIV.blockpost LABEL [openingbrace]\r\n	PADDING: 3px;\r\n	DISPLAY: inline\r\n[closingbrace]\r\n\r\nP.multidelete [openingbrace]\r\n	PADDING-TOP: 15px;\r\n	PADDING-BOTTOM: 5px\r\n[closingbrace]\r\n\r\n/* 8.7 Make sure paragraphs in posts don''t get any padding */\r\n\r\nDIV.postmsg P [openingbrace]PADDING: 0[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 9. SPECIAL SPACING FOR FORMS */\r\n/****************************************************************/\r\n\r\n/* 9.1 Padding around fieldsets */\r\n\r\nDIV.blockform FORM, DIV.fakeform [openingbrace]PADDING: 20px 20px 15px 20px[closingbrace]\r\nDIV.inform [openingbrace]PADDING-BOTTOM: 12px[closingbrace]\r\n\r\n/* 9.2 Padding inside fieldsets */\r\n\r\n.pun FIELDSET [openingbrace]PADDING: 0px 12px 0px 12px[closingbrace]\r\nDIV.infldset [openingbrace]PADDING: 9px 0px 12px 0[closingbrace]\r\n.pun LEGEND [openingbrace]PADDING: 0px 6px[closingbrace]\r\n\r\n/* 9.3 The information box at the top of the registration form and elsewhere */\r\n\r\nDIV.forminfo [openingbrace]\r\n	MARGIN-BOTTOM: 12px;\r\n	PADDING: 9px 10px\r\n[closingbrace]\r\n\r\n/* 9.4 BBCode help links in post forms */\r\n\r\nUL.bblinks LI [openingbrace]PADDING-RIGHT: 20px[closingbrace]\r\n\r\nUL.bblinks [openingbrace]PADDING-BOTTOM: 10px; PADDING-LEFT: 4px[closingbrace]\r\n\r\n/* 9.5 Horizontal positioning for the submit button on forms */\r\n\r\nDIV.blockform P INPUT [openingbrace]MARGIN-LEFT: 12px[closingbrace]\r\n\r\n/****************************************************************/\r\n/* 10. POST STATUS INDICATORS */\r\n/****************************************************************/\r\n\r\n/* 10.1 These are the post status indicators which appear at the left of some tables. \r\n.inew = new posts, .iredirect = redirect forums, .iclosed = closed topics and\r\n.isticky = sticky topics. By default only .inew is different from the default.*/\r\n\r\nDIV.icon [openingbrace]\r\n	FLOAT: left;\r\n	MARGIN-TOP: 0.1em;\r\n	MARGIN-LEFT: 0.2em;\r\n	DISPLAY: block;\r\n	BORDER-WIDTH: 0.6em 0.6em 0.6em 0.6em;\r\n	BORDER-STYLE: solid\r\n[closingbrace]\r\n\r\nDIV.searchposts DIV.icon [openingbrace]MARGIN-LEFT: 0[closingbrace]\r\n\r\n/* 10.2 Class .tclcon is a div inside the first column of tables with post indicators. The\r\nmargin creates space for the post status indicator */\r\n\r\nTD DIV.tclcon [openingbrace]MARGIN-LEFT: 2.3em[closingbrace]', 1),
(708, 'admin_errorlog_entry', '<fieldset class="errorlog-entry">\r\n	<legend><time> on <strong><location></strong></legend>\r\n		<p><error></p>\r\n</fieldset>', 1),
(714, 'admin_errorlog_limit', '<form action="admin_errorlog.php" method="get">\r\n[entriesperpage][colon] <input type="text" name="suit_errorlog_limit" value="<currentlimit>" />\r\n<input type="submit" value="[list]" />\r\n</form>', 1),
(712, 'admin_errorlog_links', '<First> <1> <2> <3> <4> <5> <Last>', 1),
(97, 'admin_templates_sdelete', '<1>\r\n<form action="admin_templates.php?cmd=setdeleted" method="post">\r\n<input type="hidden" name="set" value="<2>" />\r\n<br /><input type="submit" name="deleteset" value="[delete]" />\r\n</form>', 1),
(98, 'admin_templates_sdeleted', '<1><br />', 1),
(113, 'admin_templates_scloned', '<1><br />', 1),
(732, 'mediawiki', '<mediawiki>', 1),
(704, 'admin_errorlog', '{admin_protect}{header}\r\n{admin_errorlog_content}\r\n{footer}', 1),
(1132, 'admin_errorlog_content', '<clearedmessage>\r\n\r\n<br />[errorlogwelcome]\r\n<br /><br /><limitform>\r\n<br /><br />\r\n<center>\r\n<form action="admin_errorlog.php?cmd=clear" method="post">\r\n<input type="submit" name="errorlog_clear" value="[clear]" />\r\n</form>\r\n</center>\r\n<br /><br />\r\n<div class="errorlog-links"><links></div>\r\n<div style="padding: 1em; margin: 5px;">\r\n<errors>\r\n</div>\r\n<div class="errorlog-links"><links></div>\r\n<br /><br />\r\n<center>\r\n<form action="admin_errorlog.php?cmd=clear" method="post">\r\n<input type="submit" name="errorlog_clear" value="[clear]" />\r\n</form>\r\n</center>\r\n<br /><br /><limitform>', 1),
(39, 'postdata_list', '<tr>\r\n<td><1></td>\r\n<td><2></td>\r\n</tr>', 1),
(1137, 'password', '{header}\r\n<1>\r\n<form action="password.php?cmd=password" method="post">\r\nOld Password[colon] <input type="password" name="old" />\r\n<br />New Password[colon] <input type="password" name="new" />\r\n<br /><input type="submit" name="password" value="[changepassword]" />\r\n</form>\r\n{footer}', 1),
(41, 'admin_templates_tadd', '<1>\r\n<form action="admin_templates.php?cmd=addtemplate" method="post">\r\n<input type="hidden" name="set" value="<2>" />\r\n[inputtitle][colon] <input type="text" name="title" />\r\n<br />[content][colon] <textarea name="content" rows="20" cols="100" style="width: 100%"><3></textarea>\r\n<br />PHP Code[colon]\r\n<br /><textarea name="phpcode" rows="20" cols="100" style="width: 100%;"><3></textarea>\r\n<br /><input type="submit" name="add" value="[add]" />\r\n</form>', 1),
(42, 'admin_templates_tadded', '<div id="suit_success"><p>[addedsuccessfully]</p></div>', 1),
(47, 'admin_templates_tdelete', '<1>\r\n<form action="admin_templates.php?cmd=deletetemplate" method="post">\r\n<input type="hidden" name="set" value="<2>" />\r\n<input type="hidden" name="template" value="<3>" />\r\n<br /><input type="submit" name="delete" value="[delete]" />\r\n</form>', 1),
(48, 'admin_templates_tdeleted', '<div id="suit_success"><p>[deletedsuccessfully]</p></div>', 1),
(54, 'sandbox', 'Sandbox', 1),
(56, 'phpinfo', '', 1),
(57, 'admin_templates_tclone', '<1>\r\n<form action="admin_templates.php?cmd=clonetemplate" method="post">\r\n<input type="hidden" name="set" value="<2>" />\r\n<input type="hidden" name="template" value="<3>" />\r\n[inputtitle][colon] <input type="text" name="title" value="<4>" />\r\n<br />[content][colon] <textarea name="content" rows="20" cols="100" style="width: 100%">\r\n<5></textarea>\r\n<br /><input type="submit" name="clone" value="[clone]" />\r\n</form>\r\n<6>', 1),
(64, 'admin_templates_tcloned', '<div id="suit_success"><p>[clonedsuccessfully]</p></div>', 1),
(65, 'admin_templates_sadd', '<1>\r\n<form action="admin_templates.php?cmd=setadded" method="post">\r\n[inputtitle][colon] <input type="text" name="title" />\r\n<br /><input type="submit" name="addset" value="[add]" />\r\n</form>', 1),
(68, 'admin_templates_sselect_skeleton', '<1>\r\n\r\n<ul id="suit_templateslist">\r\n<li style="text-align: right;"><a href="admin_templates.php?cmd=addset">[add]</a></li>\r\n<2>\r\n</ul>', 1),
(73, 'admin_templates_sadded', '<1><br />', 1),
(95, 'admin_templates_srename', '<1>\r\n<form action="admin_templates.php?cmd=setrenamed" method="post">\r\n<input type="hidden" name="set" value="<2>" />\r\n[inputtitle][colon] <input type="text" name="title" value="<3>" />\r\n<br /><input type="submit" name="renameset" value="[rename]" />\r\n</form>', 1),
(112, 'admin_templates_sclone', '<1>\r\n<form action="admin_templates.php?cmd=setcloned" method="post">\r\n<input type="hidden" name="set" value="<2>" />\r\n[inputtitle][colon] <input type="text" name="title" value="<3>" />\r\n<br /><input type="submit" name="cloneset" value="[clone]" />\r\n</form>', 1),
(96, 'admin_templates_srenamed', '<1><br />', 1),
(16, 'admin_templates_tedited', '<1><br />', 1),
(17, 'navigation', '<a href="{base_url}/index.php">[home]</a><a href="http://forum.suitframework.com/">[forum]</a><a href="http://wiki.suitframework.com/">[wiki]</a><a href="http://bugs.suitframework.com/">[bugs]</a>', 1),
(25, 'head', '	<title>[title]</title>\r\n	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	\r\n	{css}', 1),
(21, 'admin_protect', '', 1),
(23, 'index', '{header}\r\nHi!\r\n{footer}', 1),
(24, 'menu', '<a href="<1>?suit_logout=true">[logout]</a>\r\n<a href="{base_url}/password.php">[changepassword]</a>\r\n<2>', 1),
(28, 'admin_templates_tselect_skeleton', '<div style="text-align: center;">\r\n<form action="admin_templates.php?cmd=cache" method="post">\r\n<p><input type="submit" name="cache" value="[clearcache]" /></p>\r\n</form>\r\n</div>\r\n\r\n\r\n<ul id="suit_templateslist">\r\n<li style="text-align: right;"><a href="admin_templates.php?cmd=add&amp;set=<1>">[add]</a></li>\r\n<2>\r\n</ul>\r\n\r\n<div style="text-align: center;">\r\n<form action="admin_templates.php?cmd=cache" method="post">\r\n<p><input type="submit" name="cache" value="[clearcache]" /></p>\r\n</form>\r\n</div>', 1),
(1138, 'admin_errorlog_limit_get', '&amp;limit=', 1),
(31, 'admin_templates_code', '<br />[phpcode]\r\n<br /><br /><textarea name="phpcode" rows="20" cols="100" style="width: 100%"><1></textarea>', 1),
(34, 'admin_index_loggedin', '<updatedmessage>\r\n<welcome>\r\n<notes>', 1),
(33, 'admin_index_notes', '<br /><br /><form action="admin_index.php" method="post">\r\n<textarea name="content" rows="20" cols="100" style="width: 100%">\r\n<notes></textarea>\r\n<br /><input type="submit" name="notes" value="[update]" />\r\n</form>', 1),
(945, 'success', '<div id="suit_success"><p><1></p></div>', 1),
(38, 'postdata', '{header}\r\n[sessionexpired]\r\n<br /><br /><table border="1">\r\n<tr>\r\n<td>[key]</td>\r\n<td>[value]</td>\r\n</tr>\r\n<1>\r\n</table>\r\n{footer}', 1),
(726, 'top', '	<div id="suit_wrapper">\r\n		<div id="suit_banner-top"></div>\r\n		<div id="suit_banner">\r\n			<div id="suit_logo"><a href="{base_url}/index.php">SUIT</a></div>\r\n		</div>\r\n		<div id="suit_topbar">\r\n                        {navigation}\r\n		</div>\r\n		<div id="suit_panel">\r\n                        <1>\r\n		</div>\r\n		\r\n		<div id="suit_content">', 1),
(13, 'footer', '		</div>\r\n		\r\n		<div id="suit_copyright">\r\n                        <p>[poweredby]</p>\r\n			<p>[copyright]</p>\r\n		</div>\r\n<1>\r\n</div>\r\n\r\n</body>\r\n</html>', 1),
(12, 'header', '{doctype}\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">\r\n<head>\r\n{head}\r\n</head>\r\n<body>\r\n{top}', 1),
(10, 'admin_languages', '{admin_protect}{header}\r\n{footer}', 1),
(26, 'admin_menu', '<a href="{base_url}/admin_index.php">[admin]</a>\r\n<a href="{base_url}/admin_templates.php">[templates]</a>\r\n<a href="{base_url}/admin_languages.php">[languages]</a>\r\n<a href="{base_url}/admin_errorlog.php">[errorlog]</a>', 1),
(8, 'admin_templates_themes', '<1><2>', 1),
(9, 'doctype', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">', 1),
(827, 'admin_templates_cache', '<div id="success"><p>[clearedsuccessfully]</p></div>\r\n', 1),
(842, 'register', '{header}\r\n<1>\r\n<form action="register.php" method="post">\r\n<table>\r\n<tr>\r\n<td>[username][colon]</td>\r\n<td><input type="text" name="username" /></td>\r\n</tr>\r\n<tr>\r\n<td>[password][colon]</td>\r\n<td><input type="password" name="password" /></td>\r\n</tr>\r\n<tr>\r\n<td>[email][colon]</td>\r\n<td><input type="text" name="email" /></td>\r\n</tr>\r\n<tr>\r\n<td>[recaptcha][colon]</td>\r\n<td>{recaptcha}</td>\r\n</tr>\r\n</table>\r\n<input type="submit" name="register" value="Register" />\r\n</form>\r\n{footer}', 1),
(848, 'recaptcha', '<1>', 1),
(849, 'recaptcha_lib', '', 1),
(850, 'recaptcha_keys', '', 1),
(851, 'themeswitcher', '<div id="suit_themeswitcher">\r\n	<form action="<1>" method="get" onchange="submit(this.form)">\r\n		<p>\r\n			<select name="suit_theme">\r\n				<optgroup label="Theme Chooser">\r\n                                        <option value="0"<3>>Default</option>\r\n					<2>\r\n				</optgroup>\r\n			</select>\r\n		</p>\r\n	</form>\r\n</div>', 1),
(953, 'themeswitcher_field', '<option value="<1>"<3>><2></option>', 1),
(2, 'Recycle Bin', '', 0),
(1131, 'Default Templates', '', 0),
(1135, 'admin_index_content', '<content>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `suit_themes`
--

CREATE TABLE IF NOT EXISTS `suit_themes` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` text NOT NULL,
  `templateset` bigint(20) NOT NULL,
  `defaults` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `suit_themes`
--

INSERT INTO `suit_themes` (`id`, `title`, `templateset`, `defaults`) VALUES
(1, 'Default Theme', 1131, 1),
(2, 'lulz', 1131, 0);

-- --------------------------------------------------------

--
-- Table structure for table `suit_users`
--

CREATE TABLE IF NOT EXISTS `suit_users` (
  `id` bigint(20) NOT NULL auto_increment,
  `admin` tinyint(4) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `theme` bigint(20) NOT NULL,
  `language` bigint(20) NOT NULL,
  `recover_string` text NOT NULL,
  `recover_password` text NOT NULL,
  `group_id` int(10) NOT NULL default '4',
  `salt` varchar(12) character set utf8 default NULL,
  `title` varchar(50) character set utf8 default NULL,
  `realname` varchar(40) character set utf8 default NULL,
  `url` varchar(100) character set utf8 default NULL,
  `jabber` varchar(75) character set utf8 default NULL,
  `icq` varchar(12) character set utf8 default NULL,
  `msn` varchar(50) character set utf8 default NULL,
  `aim` varchar(30) character set utf8 default NULL,
  `yahoo` varchar(30) character set utf8 default NULL,
  `location` varchar(30) character set utf8 default NULL,
  `signature` text character set utf8 NOT NULL,
  `disp_topics` tinyint(3) unsigned default NULL,
  `disp_posts` tinyint(3) unsigned default NULL,
  `email_setting` tinyint(1) NOT NULL default '1',
  `notify_with_post` tinyint(1) NOT NULL default '0',
  `auto_notify` tinyint(1) NOT NULL default '0',
  `show_smilies` tinyint(1) NOT NULL default '1',
  `show_img` tinyint(1) NOT NULL default '1',
  `show_img_sig` tinyint(1) NOT NULL default '1',
  `show_avatars` tinyint(1) NOT NULL default '1',
  `show_sig` tinyint(1) NOT NULL default '1',
  `access_keys` tinyint(1) NOT NULL default '0',
  `timezone` float NOT NULL default '0',
  `dst` tinyint(1) NOT NULL default '0',
  `time_format` int(10) unsigned NOT NULL default '0',
  `date_format` int(10) unsigned NOT NULL default '0',
  `num_posts` int(10) unsigned NOT NULL default '0',
  `last_post` int(10) unsigned default NULL,
  `last_search` int(10) unsigned default NULL,
  `last_email_sent` int(10) unsigned default NULL,
  `registered` int(10) unsigned NOT NULL default '0',
  `registration_ip` varchar(15) character set utf8 NOT NULL default '0.0.0.0',
  `last_visit` int(10) unsigned NOT NULL default '0',
  `admin_note` varchar(30) character set utf8 default NULL,
  `activate_string` varchar(50) character set utf8 default NULL,
  `activate_key` varchar(8) character set utf8 default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `suit_users`
--

INSERT INTO `suit_users` (`id`, `admin`, `username`, `password`, `email`, `theme`, `language`, `recover_string`, `recover_password`, `group_id`, `salt`, `title`, `realname`, `url`, `jabber`, `icq`, `msn`, `aim`, `yahoo`, `location`, `signature`, `disp_topics`, `disp_posts`, `email_setting`, `notify_with_post`, `auto_notify`, `show_smilies`, `show_img`, `show_img_sig`, `show_avatars`, `show_sig`, `access_keys`, `timezone`, `dst`, `time_format`, `date_format`, `num_posts`, `last_post`, `last_search`, `last_email_sent`, `registered`, `registration_ip`, `last_visit`, `admin_note`, `activate_string`, `activate_key`) VALUES
(2, 1, 'Brandon', 'ca3f1fb6231b0e0d5003fb2a72094082', 'admin@brandonevans.org', 0, 0, '', '', 1, NULL, NULL, 'Brandon Evans', 'http://www.brandonevans.org/', NULL, NULL, 'admin@brandonevans.org', 'BrandMan211', 'BrandMan211', 'New York', '[url=http://www.brandonevans.org]Brandon Evans.org[/url]', NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 22, 1227308732, NULL, NULL, 1226006346, '0.0.0.0', 1227380623, NULL, '01332adf569b95d00469a601aa058379', '75ggYnWm'),
(3, 1, 'Faltzer', '390ccab0de481ee7a5b410e6b95a733d ', 'faltzermaster@aol.com', 1, 0, '', '', 1, NULL, 'Tu Lo Abe', 'Chris Santiago', 'http://faltzer.net/', NULL, NULL, NULL, NULL, NULL, 'Glendale, New York', '', 3, 3, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 13, 1227092949, NULL, NULL, 1226006346, '0.0.0.0', 1227383896, NULL, NULL, NULL),
(1, 0, 'Guest', 'Guest', 'Guest', 0, 0, '', '', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, '0.0.0.0', 1225393096, NULL, NULL, NULL),
(4, 1, 'Reshure25', '1ead18bfdad18bf6a909c2c725b9beaf', 'dynapie@gmail.com', 0, 0, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 1226006346, '0.0.0.0', 1226086483, NULL, NULL, NULL),
(5, 0, 'Test', 'ad359227107bcd48378c7e9fed53bbc2', 'test@test.com', 0, 0, '', '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 1226006346, '0.0.0.0', 1226001873, NULL, NULL, NULL),
(6, 0, 'Test2', '9af54541afd7f0a726ae688067abf99a', 'test2@test2.com', 0, 0, '', '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 1226006346, '69.115.232.220', 1226006398, NULL, NULL, NULL),
(7, 0, 'Space Yoshi X', 'a6c7a0dfaab381ec870c182a07f4d513', 'spaceyoshix@hotmail.com', 0, 0, '', '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 0, 0, 0, 1, 1, 1, 1, 1, 0, -5, 0, 0, 0, 1, 1226115088, NULL, NULL, 1226114898, '74.216.6.37', 1226115108, NULL, NULL, NULL),
(11, 1, 'blink182av', '5d187574218f65f9cfef2c488aed0f66', 'blink182av@gmail.com', 0, 0, '', '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 1226531699, '69.115.232.222', 1226599642, NULL, NULL, NULL),
(12, 0, 'Memory', '48055cc456b648aa042c35a8abd8c840', 'memo-chan@live.com', 0, 0, '', '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 1226698931, '67.165.231.189', 1226698931, NULL, NULL, NULL),
(13, 0, 'hach-que', 'b6a2c71e7422df670278d31eaa85a4fa', 'jrhodes@roket-productions.com', 0, 0, '', '', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 1, 1227145698, NULL, NULL, 1227145566, '144.137.8.103', 1227146004, NULL, NULL, NULL);
