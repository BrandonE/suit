<?php
/**
**@This file is part of The SUIT Framework.

**@SUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.

**@SUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.
**/
$suit->templates->getTemplate('tie');
$tie = &$suit->templates->vars['tie'];
$output = $tie->parsePhrases($output);
$output = $tie->parseTemplates($output);
if ($tie->loggedIn() == 2)
{
$queries = 'CREATE TABLE IF NOT EXISTS `suit_languages`
(
	`id` bigint(20) NOT NULL auto_increment,
	`title` text NOT NULL,
	`defaults` tinyint(4) NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1tHiSiSaDeLiMeTeR
CREATE TABLE IF NOT EXISTS `suit_notes`
(
	`content` text NOT NULL
)
ENGINE=MyISAM DEFAULT CHARSET=latin1tHiSiSaDeLiMeTeR
INSERT INTO `suit_notes` (`content`) VALUES
(\'Write some notes here!\')tHiSiSaDeLiMeTeR
CREATE TABLE IF NOT EXISTS `suit_phrases`
(
	`id` bigint(20) NOT NULL auto_increment,
	`title` text NOT NULL,
	`content` text NOT NULL,
	`language` bigint(20) NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1tHiSiSaDeLiMeTeR
CREATE TABLE IF NOT EXISTS `suit_users`
(
	`id` bigint(20) NOT NULL auto_increment,
	`admin` tinyint(4) NOT NULL,
	`username` text NOT NULL,
	`password` text NOT NULL,
	`email` text NOT NULL,
	`language` bigint(20) NOT NULL,
	`recover_string` text NOT NULL,
	`recover_password` text NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1tHiSiSaDeLiMeTeR
INSERT INTO `suit_users` (`id`, `admin`, `username`, `password`, `email`, `language`, `recover_string`, `recover_password`) VALUES
(\'\', 1, \'rEpLaCeThIsUsErNaMe\', \'rEpLaCeThIsPaSsWoRD\', \'rEpLaCeThIsEmAiL\', 0, \'\', \'\')tHiSiSaDeLiMeTeR';
	$query = 'SELECT * FROM ' . DB_PREFIX . 'phrases ORDER BY title';
	$result = mysql_query($query);
	if ($result)
	{
$queries .= '
INSERT INTO `suit_phrases` (`id`, `title`, `content`, `language`) VALUES';
		while ($row = mysql_fetch_assoc($result))
		{
			$title = str_replace('\'', '\'\'', $row['title']);
			$content = str_replace('\'', '\'\'', $row['content']);
			$language = str_replace('\'', '\'\'', $row['language']);
$queries .= '
(\'\', \'' . $title . '\', \'' . $content . '\', \'' . $language . '\'),';
		}
	}
	$queries = substr_replace($queries, 'tHiSiSaDeLiMeTeR', strlen($queries)-1, 1);
	$query = 'SELECT * FROM ' . DB_PREFIX . 'pages ORDER BY title';
	$result = mysql_query($query);
	if ($result)
	{
$queries .= '
INSERT INTO `suit_pages` (`id`, `title`, `template`, `defaults`) VALUES';
		while ($row = mysql_fetch_assoc($result))
		{
			$title = str_replace('\'', '\'\'', $row['title']);
			$template = str_replace('\'', '\'\'', $row['template']);
			$defaults = str_replace('\'', '\'\'', $row['defaults']);
$queries .= '
(\'\', \'' . $title . '\', \'' . $template . '\', ' . $defaults . '),';
		}
	}
	$queries = substr_replace($queries, 'tHiSiSaDeLiMeTeR', strlen($queries)-1, 1);
	$query = 'SELECT * FROM ' . DB_PREFIX . 'languages ORDER BY title';
	$result = mysql_query($query);
	if ($result)
	{
$queries .= '
INSERT INTO `suit_languages` (`id`, `title`, `defaults`) VALUES';
		while ($row = mysql_fetch_assoc($result))
		{
			$title = str_replace('\'', '\'\'', $row['title']);
			$defaults = str_replace('\'', '\'\'', $row['defaults']);
$queries .= '
(\'\', \'' . $title . '\', \'' . $defaults . '\'),';
		}
	}
	$queries = substr_replace($queries, 'tHiSiSaDeLiMeTeR', strlen($queries)-1, 1);
	$query = 'SELECT * FROM ' . DB_PREFIX . 'templates ORDER BY title';
	$result = mysql_query($query);
	if ($result)
	{
$queries .= '
INSERT INTO `suit_templates` (`id`, `title`, `content`) VALUES';
		while ($row = mysql_fetch_assoc($result))
		{
			$title = str_replace('\'', '\'\'', $row['title']);
			$content = str_replace('\'', '\'\'', $row['content']);
$queries .= '
(\'\', \'' . $title . '\', \'' . $content . '\'),';
		}
	}
	$queries = substr_replace($queries, 'tHiSiSaDeLiMeTeR', strlen($queries)-1, 1);
	$queries = addslashes($queries);
	$queries = str_replace('CREATE TABLE IF NOT EXISTS `suit_', 'CREATE TABLE IF NOT EXISTS `\' . addslashes(magic($_POST[\'db_prefix\'])) . \'', $queries);
	$queries = str_replace('INSERT INTO `suit_', 'INSERT INTO `\' . addslashes(magic($_POST[\'db_prefix\'])) . \'', $queries);
	$queries = str_replace('INSERT INTO `suit_', 'INSERT INTO `\' . addslashes(magic($_POST[\'db_prefix\'])) . \'', $queries);
	$queries = str_replace('rEpLaCeThIsUsErNaMe', '\' . addslashes(magic($_POST[\'user_name\'])) . \'', $queries);
	$queries = str_replace('rEpLaCeThIsPaSsWoRD', '\' . md5(magic($_POST[\'user_pass\']) . $salt) . \'', $queries);
	$queries = str_replace('rEpLaCeThIsEmAiL', '\' . addslashes(magic($_POST[\'user_email\'])) . \'', $queries);
	$queries = str_replace('\"', '"', $queries);
$queries = '<?php
/**
**@This file is part of The SUIT Framework.

**@SUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.

**@SUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.
**/
if (isset($query))
{
$query .= \'' . $queries . '\';
}
?>';
$files = '<?php
/**
**@This file is part of The SUIT Framework.

**@SUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.

**@SUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.
**/
if (isset($files))
{
$files[] = array(\'config.inc.php\', \'<?php
//Base Attributes
define(' . addslashes('\'PATH_URL\'') . ', ' . addslashes('\'') . '\'rEpLaCeThIsUrL\'' . addslashes('\'') . ');
//Cookies
define(' . addslashes('\'COOKIE_PREFIX\'') . ', ' . addslashes('\'') . '\'rEpLaCeThIsPrEfIx\'' . addslashes('\'') . ');
define(' . addslashes('\'COOKIE_PATH\'') . ', ' . addslashes('\'') . '\'rEpLaCeThIsPaTh\'' . addslashes('\'') . ');
define(' . addslashes('\'COOKIE_DOMAIN\'') . ', ' . addslashes('\'') . '\'rEpLaCeThIsDoMaIn\'' . addslashes('\'') . ');
define(' . addslashes('\'COOKIE_LENGTH\'') . ', ' . addslashes('\'') . '\'rEpLaCeThIsLeNgTh\'' . addslashes('\'') . ');
//DB_SALT
define(' . addslashes('\'DB_SALT\'') . ', ' . addslashes('\'') . '\'rEpLaCeThIsSaLt\'' . addslashes('\'') . ');
?>\');';
	$files = str_replace('rEpLaCeThIsUrL', stripslashes(' . addslashes(magic($_POST[\'path_url\'])) . '), $files);
	$files = str_replace('rEpLaCeThIsPrEfIx', stripslashes(' . addslashes(magic($_POST[\'cookie_prefix\'])) . '), $files);
	$files = str_replace('rEpLaCeThIsPaTh', stripslashes(' . addslashes(magic($_POST[\'cookie_path\'])) . '), $files);
	$files = str_replace('rEpLaCeThIsDoMaIn', stripslashes(' . addslashes(magic($_POST[\'cookie_domain\'])) . '), $files);
	$files = str_replace('rEpLaCeThIsLeNgTh', stripslashes(' . addslashes(magic($_POST[\'cookie_length\'])) . '), $files);
	$files = str_replace('rEpLaCeThIsSaLt', stripslashes(' . $salt . '), $files);
	$array = scandir(PATH_TEMPLATES);
	foreach ($array as $value)
	{
		if ($value != '.' && $value != '..' && $value != 'config.inc.php')
		{
$files .= '
$files[] = array(\'' . $value . '\', \'' . addslashes(file_get_contents(PATH_TEMPLATES . '/' . $value)) . '\');';
		}
	}
$files .= '
}
?>';
	$files = str_replace('\"', '"', $files);
	$files = str_replace('\\\r\\\n', '\r\n', $files);
	$files = str_replace('\\\n', '\n', $files);
	$files = str_replace('\\\r', '\r', $files);
	$files = str_replace('/(\\\\\r\\\\\n)|\\\\\r|\\\\\n/', '/(\\\r\\\n)|\\\r|\\\n/', $files);
	if (isset($_GET['cmd']))
	{
		if ($_GET['cmd'] == 'queries')
		{
			$output = str_replace('<extract>', htmlspecialchars($queries), $output);
		}
		if ($_GET['cmd'] == 'files')
		{
			$output = str_replace('<extract>', htmlspecialchars($files), $output);
		}
	}
}
?>