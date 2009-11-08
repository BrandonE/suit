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
if (isset($files))
{
$files[] = array('config.inc.php', '<?php
//Base Attributes
define(\'PATH_URL\', \'' . addslashes(magic($_POST['path_url'])) . '\');
//Cookies
define(\'COOKIE_PREFIX\', \'' . addslashes(magic($_POST['cookie_prefix'])) . '\');
define(\'COOKIE_PATH\', \'' . addslashes(magic($_POST['cookie_path'])) . '\');
define(\'COOKIE_DOMAIN\', \'' . addslashes(magic($_POST['cookie_domain'])) . '\');
define(\'COOKIE_LENGTH\', \'' . addslashes(magic($_POST['cookie_length'])) . '\');
//DB_SALT
define(\'DB_SALT\', \'' . $salt . '\');
?>');
$files[] = array('401.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('403.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('404.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('500.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_errorlog.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
if ($local->loggedIn() == 2)
{
	function pagelink($check, $start, $display, $db, &$suit, $limit, $link, $limit_get)
	{
		$success = false;
		if ($db == 0)
		{
			if ($check >= 0)
			{
				$success = true;
			}
		}
		else
		{
			$errorlog_options = array(\'orderby\' => \'date\', \'orderby_type\' => \'desc\', \'limit\' => $check . \':1\');
			$errorlog = $suit->db->select(DB_PREFIX . \'errorlog\', \'*\', $errorlog_options);
			if ($errorlog)
			{
				$success = true;
			}
		}
		if ($success)
		{
			if ($start == 0)
			{
				$start = $check;
			}
			$first = $link;
			$array = array
			(
				array(\'<start>\', $start),
				array(\'<limit>\', $limit_get . $limit),
				array(\'<display>\', $display)
			);
			$return = $local->replace($first, $array);
		}
		else
		{
			$return = \'\';
		}
		return $return;
	}
	if (isset($_POST[\'errorlog_clear\']))
	{
		$clear = $suit->db->truncate(DB_PREFIX . \'errorlog\');
		if ($clear)
		{
			$local->redirect($local->getPhrase(\'clearedsuccessfully\'), 2, $suit->templates->getTemplate(\'path_url\') . \'/index.php?page=admin_errorlog\');
		}
	}
	$output = $local->parseTemplates($output);
	$errors = \'\';
	$admin_errorlog_entry = $suit->templates->getTemplate(\'admin_errorlog_entry\');
	if (isset($_GET[\'limit\']))
	{
		$limit = intval($_GET[\'limit\']);
		if (!($limit == intval($limit) && ($limit > 0)))
		{
			$suit->templates->getTemplate(\'badrequest\');
		}
	}
	else
	{
		$limit = 10;
	}
	if (isset($_GET[\'start\']))
	{
		$start = intval($_GET[\'start\']);
		if (!($start == intval($start) && ($start >= 0) && (($start / $limit) == round(($start / $limit)))))
		{
			$suit->templates->getTemplate(\'badrequest\');
		}
	}
	else
	{
		$start = 0;
	}
	$errorlog_options = array(\'orderby\' => \'date\', \'orderby_type\' => \'desc\', \'limit\' => $start . \':\' . $limit);
	$errorlog = $suit->db->select(DB_PREFIX . \'errorlog\', \'*\', $errorlog_options);
	if ($errorlog)
	{
		while ($row = mysql_fetch_assoc($errorlog))
		{
			$errors .= $admin_errorlog_entry;
			$numrows = $suit->db->select(DB_PREFIX . \'errorlog\', \'COUNT(*) AS number\');
			if ($numrows)
			{
				$numrows = mysql_fetch_assoc($numrows);
			}
			$array = array
			(
				array(\'<error>\', wordwrap($row[\'content\'], strlen($row[\'content\'])/2, \'<br />\', true)),
				array(\'<time>\', $row[\'date\']),
				array(\'<location>\', $row[\'location\'])
			);
			$errors = $local->replace($errors, $array);
		}
	}
	$admin_errorlog_links = $suit->templates->getTemplate(\'admin_errorlog_links\');
	$admin_errorlog_link = $suit->templates->getTemplate(\'admin_errorlog_link\');
	$admin_errorlog_limit_get = $suit->templates->getTemplate(\'admin_errorlog_limit_get\');
	$firstmessage = $local->getPhrase(\'first\');
	$lastmessage = $local->getPhrase(\'last\');
	$num = 0;
	$errorlog = $suit->db->select(DB_PREFIX . \'errorlog\', \'*\', \'\');
	if ($errorlog)
	{
		$num = mysql_num_rows($errorlog);
		if (($num / $limit) != (round(($num / $limit))))
		{
			do
			{
				$num--;
			}
			while (($num / $limit) != (round(($num / $limit))));
		}
	}
	$link_1 = pagelink(($start - ($limit * 3)), \'0\', $firstmessage, 0, $suit, $limit, $admin_errorlog_link, $admin_errorlog_limit_get);
	$link_2 = pagelink(($start - ($limit * 2)), 0, (($start / $limit) - 1), 0, $suit, $limit, $admin_errorlog_link, $admin_errorlog_limit_get);
	$link_3 = pagelink(($start - $limit), 0, ($start / $limit), 0, $suit, $limit, $admin_errorlog_link, $admin_errorlog_limit_get);
	$link_4 = ($start / $limit) + 1;
	$link_5 = pagelink(($start + $limit), 0, (($start / $limit) + 2), 1, $suit, $limit, $admin_errorlog_link, $admin_errorlog_limit_get);
	$link_6 = pagelink(($start + ($limit * 2)), 0, (($start / $limit) + 3), 1, $suit, $limit, $admin_errorlog_link, $admin_errorlog_limit_get);
	$link_7 = pagelink(($start + ($limit * 3)), strval($num), $lastmessage, 1, $suit, $limit, $admin_errorlog_link, $admin_errorlog_limit_get);
	$admin_errorlog_limit = $suit->templates->getTemplate(\'admin_errorlog_limit\');
	$admin_errorlog_limit = str_replace(\'<currentlimit>\', $limit, $admin_errorlog_limit);
	$array = Array
	(
		Array(\'<First>\', $link_1),
		Array(\'<1>\', $link_2),
		Array(\'<2>\', $link_3),
		Array(\'<3>\', $link_4),
		Array(\'<4>\', $link_5),
		Array(\'<5>\', $link_6),
		Array(\'<Last>\', $link_7)
	);
	$admin_errorlog_links = $local->replace($admin_errorlog_links, $array);	
	$array = Array
	(
		Array(\'<errors>\', $errors),
		Array(\'<links>\', $admin_errorlog_links),
		Array(\'<limitform>\', $admin_errorlog_limit)
	);
	$output = $local->replace($output, $array);
}
else
{
	$output = $local->parseTemplates($output);
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{	
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_errorlog_entry.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_errorlog_limit.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_errorlog_limit_get.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_errorlog_link.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_errorlog_links.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_escape.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
if ($local->loggedIn($suit) == 2)
{
	if (isset($_POST[\'escape\']) && isset($_POST[\'code\']))
	{
		$code = $local->magic($_POST[\'code\']);
		$array = array
		(
			array(\'{\', \'{openingbrace}\'),
			array(\'}\', \'{closingbrace}\')
		);
		$code = htmlentities($local->replace($code, $array));
	}
	else
	{
		$code = \'\';
	}
	$output = str_replace(\'<code>\', $code, $output);
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_languages.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_menu.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_notes.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
if ($local->loggedIn($suit) == 2)
{
	if (isset($_POST[\'notes\']))
	{
		if (isset($_POST[\'content\']))
		{
			$content = $suit->db->escape($local->magic($_POST[\'content\']));
			$notes = $suit->db->select(DB_PREFIX . \'notes\', \'*\');
			if ($notes)
			{
				$query = \'UPDATE \' . DB_PREFIX . \'notes SET content = \\\'\' . $content . \'\\\'\';
				mysql_query($query);
				$local->redirect($local->getPhrase(\'updatedsuccessfully\'), 2, $suit->templates->getTemplate(\'path_url\') . \'/index.php?page=admin_notes\');
			}
		}
	}
	$output = $local->parseTemplates($output);
	$notes_get = $suit->db->select(DB_PREFIX . \'notes\', \'*\');
	if ($notes_get)
	{
		while ($row = mysql_fetch_assoc($notes_get))
		{
			$notes = $row[\'content\'];
		}
	}
	else
	{
		$notes = \'\';
	}
	$array = array
	(
		array(\'<welcome>\', $local->getPhrase(\'adminwelcome\')),
		array(\'<notes>\', htmlentities($notes))
	);
	$output = $local->replace($output, $array);
}
else
{
	$output = $local->parseTemplates($output);
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_pages.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_protect.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
if ($local->loggedIn() != 2)
{
	//If there was any post data sent prior to being logged out, then we\'ll output the post data saved.
	if (!empty($_POST) && !isset($_POST[\'suit_login\']))
	{
		$output = $suit->templates->getTemplate(\'postdata\');
	}
	else
	{
		$output = $suit->templates->getTemplate(\'notauthorized\');
	}
}
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_templates.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
if ($local->loggedIn() == 2)
{
	if (isset($_POST[\'add\']) && isset($_POST[\'title\']) && isset($_POST[\'content\']) && isset($_POST[\'code\']))
	{
		$title = $suit->db->escape($local->magic($_POST[\'title\']));
		$templatecheck_options = array
		(
			\'where\' => \'title = \\\'\' . $title . \'\\\'\'
		);
		$templatecheck = $suit->db->select(DB_PREFIX . \'templates\', \'*\', $templatecheck_options);
		if (!$templatecheck)
		{		
			if ($title == \'\')
			{
				$error = \'missingtitle\';
			}
		}
		else
		{
			$error = \'duplicatetitle\';
		}
		if (!isset($error))
		{
			$content = $suit->db->escape($local->magic($_POST[\'content\']));
			$query = \'INSERT INTO \' . DB_PREFIX . \'templates VALUES (\\\'\\\', \\\'\' . $title . \'\\\', \\\'\' . $content . \'\\\')\';
			mysql_query($query);
			$code = trim($local->magic($_POST[\'code\']));
			if (stristr(PHP_OS, \'WIN\'))
			{
				$char = "\r\n";
			}
			elseif (stristr(PHP_OS, \'LIN\'))
			{
				$char = "\n";
			}
			elseif (stristr(PHP_OS, \'MAC\'))
			{
				$char = "\r";
			}
			else
			{
				$char = "\n";
			}
			$code = preg_replace(\'/(\\r\\n)|\\r|\\n/\', $char, $code);
			$filepath = $suit->templates->checkFile($title);
			file_put_contents($filepath, $code);
	        	$local->redirect($local->getPhrase(\'addedsuccessfully\'), 2, $suit->templates->getTemplate(\'path_url\') . \'/index.php?page=admin_templates\');
		}
	}
	if (isset($_POST[\'edit\']) && isset($_POST[\'title\']) && $_POST[\'template\'] && isset($_POST[\'content\']) && isset($_POST[\'code\']))
	{
		$title = $suit->db->escape($local->magic($_POST[\'title\']));
		$template = intval($_POST[\'template\']);
		$templatecheck_options = array
		(
			\'where\' => \'id = \\\'\' . $template . \'\\\'\'
		);
		$templatecheck = $suit->db->select(DB_PREFIX . \'templates\', \'*\', $templatecheck_options);
		if ($templatecheck)
		{
			while ($row = mysql_fetch_assoc($templatecheck))
			{
				$templatecheck2_options = array
				(
					\'where\' => \'title = \\\'\' . $title . \'\\\'\'
				);
				$templatecheck2 = $suit->db->select(DB_PREFIX . \'templates\', \'*\', $templatecheck2_options);
				if (!$templatecheck2 || ($title == $row[\'title\']))
				{
					if ($title == \'\')
					{
						$error = \'missingtitle\';
					}
				}
				else
				{
					$error = \'duplicatetitle\';
				}
			}
		}
		else
		{
			$suit->templates->getTemplate(\'badrequest\');
		}
		if (!isset($error))
		{
			$content = $suit->db->escape($local->magic($_POST[\'content\']));
			$query = \'UPDATE \' . DB_PREFIX . \'templates SET content = \\\'\' . $content . \'\\\', title = \\\'\' . $title . \'\\\' WHERE id = \\\'\' . $template . \'\\\'\';	
			mysql_query($query);
			$code = trim($local->magic($_POST[\'code\']));
			if (stristr(PHP_OS, \'WIN\'))
			{
				$char = "\r\n";
			}
			elseif (stristr(PHP_OS, \'LIN\'))
			{
				$char = "\n";
			}
			elseif (stristr(PHP_OS, \'MAC\'))
			{
				$char = "\r";
			}
			else
			{
				$char = "\n";
			}
			$code = preg_replace(\'/(\\r\\n)|\\r|\\n/\', $char, $code);
			$filepath = $suit->templates->checkFile($title);
			file_put_contents($filepath, $code);
			$local->redirect($local->getPhrase(\'editedsuccessfully\'), 2, $suit->templates->getTemplate(\'path_url\') . \'/index.php?page=admin_templates\');
		}
	}
	if (isset($_POST[\'delete\']) && $_GET[\'cmd\'] == \'delete\')
	{
		if (isset($_POST[\'template\']))
		{
			$template = intval($_POST[\'template\']);
			$templatecheck_options = array
			(
				\'where\' => \'id = \\\'\' . $template . \'\\\'\'
			);
			$templatecheck = $suit->db->select(DB_PREFIX . \'templates\', \'*\', $templatecheck_options);
			if ($templatecheck)
			{
				while ($row = mysql_fetch_assoc($templatecheck))
				{
					$query = \'DELETE FROM \' . DB_PREFIX . \'templates WHERE id = \\\'\' . $template . \'\\\'\';
					mysql_query($query);
					$filepath = $suit->templates->checkFile($row[\'title\']);
					unlink($filepath);
				}
			}
			else
			{
				$suit->templates->getTemplate(\'badrequest\');
			}
		}
		else
		{
			$suit->templates->getTemplate(\'badrequest\');
		}
		$local->redirect($local->getPhrase(\'deletedsuccessfully\'), 2, $suit->templates->getTemplate(\'path_url\') . \'/index.php?page=admin_templates\');
	}
	$output = $local->parseTemplates($output);
	//It\'s always safer to set a variable before use.
	$list = \'\';
	$pages = array(\'add\', \'edit\', \'delete\');
	if (!(isset($_GET[\'cmd\']) && in_array($_GET[\'cmd\'], $pages)))
	{
		$templateexists_options = array
		(
			\'orderby\' => \'title\',
			\'orderby_type\' => \'asc\'
		);
		$templateexists = $suit->db->select(DB_PREFIX . \'templates\', \'*\', $templateexists_options);
		if ($templateexists)
		{
			$page = $suit->templates->getTemplate(\'admin_templates_select_skeleton\');
			$admin_templates_select = $suit->templates->getTemplate(\'admin_templates_select\');
			while ($row = mysql_fetch_assoc($templateexists))
			{
				$list .= $admin_templates_select;
				$array = array
				(
					array(\'<title>\', htmlentities($row[\'title\'])),
					array(\'<template>\', $row[\'id\'])
				);
				$list = $local->replace($list, $array);
			}
			$page = str_replace(\'<list>\', $list, $page);
			$list = $page;
		}
		else
		{
			$suit->templates->getTemplate(\'badrequest\');
		}
	}
	else
	{
		if ($_GET[\'cmd\'] == \'add\')
		{
			$templatesetexists_options = array();
			$templatesetexists = $suit->db->select(DB_PREFIX . \'templates\', \'*\', $templatesetexists_options);
			if ($templatesetexists)
			{
				$admin_templates_add = $suit->templates->getTemplate(\'admin_templates_add\');
				$list .= $admin_templates_add;
				if (isset($error))
				{
					//We\'ll use a switch() statement to determine what action to take for these errors.
					//When we have our error, we\'ll load the language string for it.
					switch ($error)
					{
						case \'missingtitle\':
							$lcontent = $local->getPhrase(\'missingtitle\'); break;
						case \'duplicatetitle\':
							$lcontent = $local->getPhrase(\'duplicatetitle\'); break;
						default:
							$lcontent = $local->getPhrase(\'undefinederror\'); break;
					}
					//Replace the value of $list with what we concluded in the error switch() statement.
				}
				else
				{
					$lcontent = \'\';
				}
				if (!isset($error))
				{
					$title = \'\';
					$content = \'\';
					$code = \'\';
					//Template Cloning
					if (isset($_GET[\'template\']))
					{
						$id = intval($_GET[\'template\']);
						$locate_options = array
						(
							\'where\' => \'id = \\\'\' . $id . \'\\\'\'
						);
						$locate = $suit->db->select(DB_PREFIX . \'templates\', \'*\', $locate_options); 
						if ($locate)
						{
							while ($row = mysql_fetch_assoc($locate))
							{
								$title = $row[\'title\'];
								$content = $row[\'content\'];
								$filepath = $suit->templates->checkFile($row[\'title\']);
								$code = file_get_contents($filepath);
							}
						}
					}
				}
				$array = Array
				(
					array(\'<message>\', $lcontent),
					array(\'<content>\', htmlentities($content)),
					array(\'<code>\', htmlentities($code)),
					array(\'<title>\', htmlentities($title))
				);
				$list = $local->replace($list, $array);
			}
			else
			{
				$suit->templates->getTemplate(\'badrequest\');
			}
		}
		if (isset($_GET[\'cmd\']) && ($_GET[\'cmd\'] == \'edit\'))
		{
			if (isset($_GET[\'template\']))
			{
				$template = intval($_GET[\'template\']);
			}
			else
			{
				$suit->templates->getTemplate(\'badrequest\');
			}
			
			$admintemplates_options = array
			(
				\'where\' => \'id = \\\'\' . $template . \'\\\'\'
			);
			$admintemplates = $suit->db->select(DB_PREFIX . \'templates\', \'*\', $admintemplates_options);	
			if ($admintemplates)
			{
				$admin_templates_edit = $suit->templates->getTemplate(\'admin_templates_edit\');
				while ($row = mysql_fetch_assoc($admintemplates))
				{
					$list .= $admin_templates_edit;
					$error = &$suit->templates->vars[\'error\'];
					if (isset($error))
					{
						//We\'ll use a switch() statement to determine what action to take for these errors.
						//When we have our error, we\'ll load the language string for it
						switch ($error)
						{
							case \'missingtitle\':
								$lcontent = $local->getPhrase(\'missingtitle\'); break;
							case \'duplicatetitle\':
								$lcontent = $local->getPhrase(\'duplicatetitle\'); break;
							default:
								$lcontent = $local->getPhrase(\'undefinederror\'); break;
						}
						//Replace the value of $list with what we concluded in the error switch() statement.
					}
					else
					{
						$lcontent = \'\';
					}
					if (!isset($error))
					{
						$content = $row[\'content\'];
					}
					$filepath = $suit->templates->checkFile($row[\'title\']);
					$code = file_get_contents($filepath);
					if (stristr(PHP_OS, \'WIN\'))
					{
						$char = "\r\n";
					}
					elseif (stristr(PHP_OS, \'LIN\'))
					{
						$char = "\n";
					}
					elseif (stristr(PHP_OS, \'MAC\'))
					{
						$char = "\r";
					}
					else
					{
						$char = "\n";
					}
					$code = preg_replace(\'/(\\r\\n)|\\r|\\n/\', $char, $code);					
					$array = array
					(
						array(\'<message>\', $lcontent),
						array(\'<template>\', $row[\'id\']),
						array(\'<title>\', htmlentities($row[\'title\'])),
						array(\'<content>\', htmlentities($content)),
						array(\'<code>\', htmlentities($code))
					);
					$list = $local->replace($list, $array);
				}
			}
			else
			{
				$suit->templates->getTemplate(\'badrequest\');
			}
		}
		if (isset($_GET[\'cmd\']) && ($_GET[\'cmd\'] == \'delete\'))
		{
			if (isset($_GET[\'template\']))
			{
				$template = intval($_GET[\'template\']);
			}
			else
			{
				$suit->templates->getTemplate(\'badrequest\');
				exit;
			}
			
			$admintemplates_options = array
			(
				\'where\' => \'id = \\\'\' . $template . \'\\\'\'
			);
			$admintemplates = $suit->db->select(DB_PREFIX . \'templates\', \'*\', $admintemplates_options);
			if ($admintemplates)
			{
				$admin_templates_delete = $suit->templates->getTemplate(\'admin_templates_delete\');
				while ($row = mysql_fetch_assoc($admintemplates))
				{
					$list .= $admin_templates_delete;
					$lcontent = $local->getPhrase(\'deleteconfirm\');
					$lcontent = str_replace(\'<template>\', $row[\'title\'], $lcontent);
					$array = array
					(
						array(\'<message>\', $lcontent),
						array(\'<template>\', $template)
					);
					$list = $local->replace($list, $array);
				}
			}
			else
			{
				$suit->templates->getTemplate(\'badrequest\');
				exit;
			}
		}
	}
	$output = str_replace(\'<admin_templates>\', $list, $output);
}
else
{
	$output = $local->parseTemplates($output);
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{	
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_templates_add.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_templates_delete.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_templates_edit.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_templates_select.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_templates_select_skeleton.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('admin_users.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('badrequest.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
print $output;
exit;
?>');
$files[] = array('base_url.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
$output = str_replace(\'<base>\', PATH_URL, $output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('closingbrace.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('doctype.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('extract.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
if ($local->loggedIn() == 2)
{
$queries = \'CREATE TABLE IF NOT EXISTS `suit_languages`
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
(\\\'Write some notes here!\\\');
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
(\\\'\\\', 1, \\\'rEpLaCeThIsUsErNaMe\\\', \\\'rEpLaCeThIsPaSsWoRD\\\', \\\'rEpLaCeThIsEmAiL\\\', 0, \\\'\\\', \\\'\\\')tHiSiSaDeLiMeTeR\';
	$query = \'SELECT * FROM \' . DB_PREFIX . \'phrases ORDER BY title\';
	$result = mysql_query($query);
	if ($result)
	{
$queries .= \'
INSERT INTO `suit_phrases` (`id`, `title`, `content`, `language`) VALUES\';
		while ($row = mysql_fetch_assoc($result))
		{
			$title = str_replace(\'\\\'\', \'\\\'\\\'\', $row[\'title\']);
			$content = str_replace(\'\\\'\', \'\\\'\\\'\', $row[\'content\']);
			$language = str_replace(\'\\\'\', \'\\\'\\\'\', $row[\'language\']);
$queries .= \'
(\\\'\\\', \\\'\' . $title . \'\\\', \\\'\' . $content . \'\\\', \\\'\' . $language . \'\\\'),\';
		}
	}
	$queries = substr_replace($queries, \'tHiSiSaDeLiMeTeR\', strlen($queries)-1, 1);
	$query = \'SELECT * FROM \' . DB_PREFIX . \'pages ORDER BY title\';
	$result = mysql_query($query);
	if ($result)
	{
$queries .= \'
INSERT INTO `suit_pages` (`id`, `title`, `template`, `defaults`) VALUES\';
		while ($row = mysql_fetch_assoc($result))
		{
			$title = str_replace(\'\\\'\', \'\\\'\\\'\', $row[\'title\']);
			$template = str_replace(\'\\\'\', \'\\\'\\\'\', $row[\'template\']);
			$defaults = str_replace(\'\\\'\', \'\\\'\\\'\', $row[\'defaults\']);
$queries .= \'
(\\\'\\\', \\\'\' . $title . \'\\\', \\\'\' . $template . \'\\\', \' . $defaults . \'),\';
		}
	}
	$queries = substr_replace($queries, \'tHiSiSaDeLiMeTeR\', strlen($queries)-1, 1);
	$query = \'SELECT * FROM \' . DB_PREFIX . \'languages ORDER BY title\';
	$result = mysql_query($query);
	if ($result)
	{
$queries .= \'
INSERT INTO `suit_languages` (`id`, `title`, `defaults`) VALUES\';
		while ($row = mysql_fetch_assoc($result))
		{
			$title = str_replace(\'\\\'\', \'\\\'\\\'\', $row[\'title\']);
			$defaults = str_replace(\'\\\'\', \'\\\'\\\'\', $row[\'defaults\']);
$queries .= \'
(\\\'\\\', \\\'\' . $title . \'\\\', \\\'\' . $defaults . \'\\\'),\';
		}
	}
	$queries = substr_replace($queries, \'tHiSiSaDeLiMeTeR\', strlen($queries)-1, 1);
	$query = \'SELECT * FROM \' . DB_PREFIX . \'templates ORDER BY title\';
	$result = mysql_query($query);
	if ($result)
	{
$queries .= \'
INSERT INTO `suit_templates` (`id`, `title`, `content`) VALUES\';
		while ($row = mysql_fetch_assoc($result))
		{
			$title = str_replace(\'\\\'\', \'\\\'\\\'\', $row[\'title\']);
			$content = str_replace(\'\\\'\', \'\\\'\\\'\', $row[\'content\']);
$queries .= \'
(\\\'\\\', \\\'\' . $title . \'\\\', \\\'\' . $content . \'\\\'),\';
		}
	}
	$queries = substr_replace($queries, \'tHiSiSaDeLiMeTeR\', strlen($queries)-1, 1);
	$queries = addslashes($queries);
	$queries = str_replace(\'CREATE TABLE IF NOT EXISTS `suit_\', \'CREATE TABLE IF NOT EXISTS `\\\' . addslashes(magic($_POST[\\\'db_prefix\\\'])) . \\\'\', $queries);
	$queries = str_replace(\'INSERT INTO `suit_\', \'INSERT INTO `\\\' . addslashes(magic($_POST[\\\'db_prefix\\\'])) . \\\'\', $queries);
	$queries = str_replace(\'INSERT INTO `suit_\', \'INSERT INTO `\\\' . addslashes(magic($_POST[\\\'db_prefix\\\'])) . \\\'\', $queries);
	$queries = str_replace(\'rEpLaCeThIsUsErNaMe\', \'\\\' . addslashes(magic($_POST[\\\'user_name\\\'])) . \\\'\', $queries);
	$queries = str_replace(\'rEpLaCeThIsPaSsWoRD\', \'\\\' . md5(magic($_POST[\\\'user_pass\\\']) . $salt) . \\\'\', $queries);
	$queries = str_replace(\'rEpLaCeThIsEmAiL\', \'\\\' . addslashes(magic($_POST[\\\'user_email\\\'])) . \\\'\', $queries);
	$queries = str_replace(\'\\"\', \'"\', $queries);
$queries = \'<?php
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
$query .= \\\'\' . $queries . \'\\\';
}
?>\';
$files = \'<?php
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
$files[] = array(\\\'config.inc.php\\\', \\\'<?php
//Base Attributes
define(\' . addslashes(\'\\\'PATH_URL\\\'\') . \', \' . addslashes(\'\\\'\') . \'\\\'rEpLaCeThIsUrL\\\'\' . addslashes(\'\\\'\') . \');
//Cookies
define(\' . addslashes(\'\\\'COOKIE_PREFIX\\\'\') . \', \' . addslashes(\'\\\'\') . \'\\\'rEpLaCeThIsPrEfIx\\\'\' . addslashes(\'\\\'\') . \');
define(\' . addslashes(\'\\\'COOKIE_PATH\\\'\') . \', \' . addslashes(\'\\\'\') . \'\\\'rEpLaCeThIsPaTh\\\'\' . addslashes(\'\\\'\') . \');
define(\' . addslashes(\'\\\'COOKIE_DOMAIN\\\'\') . \', \' . addslashes(\'\\\'\') . \'\\\'rEpLaCeThIsDoMaIn\\\'\' . addslashes(\'\\\'\') . \');
define(\' . addslashes(\'\\\'COOKIE_LENGTH\\\'\') . \', \' . addslashes(\'\\\'\') . \'\\\'rEpLaCeThIsLeNgTh\\\'\' . addslashes(\'\\\'\') . \');
//DB_SALT
define(\' . addslashes(\'\\\'DB_SALT\\\'\') . \', \' . addslashes(\'\\\'\') . \'\\\'rEpLaCeThIsSaLt\\\'\' . addslashes(\'\\\'\') . \');
?>\\\');\';
	$files = str_replace(\'rEpLaCeThIsUrL\', stripslashes(\' . addslashes(magic($_POST[\\\'path_url\\\'])) . \'), $files);
	$files = str_replace(\'rEpLaCeThIsPrEfIx\', stripslashes(\' . addslashes(magic($_POST[\\\'cookie_prefix\\\'])) . \'), $files);
	$files = str_replace(\'rEpLaCeThIsPaTh\', stripslashes(\' . addslashes(magic($_POST[\\\'cookie_path\\\'])) . \'), $files);
	$files = str_replace(\'rEpLaCeThIsDoMaIn\', stripslashes(\' . addslashes(magic($_POST[\\\'cookie_domain\\\'])) . \'), $files);
	$files = str_replace(\'rEpLaCeThIsLeNgTh\', stripslashes(\' . addslashes(magic($_POST[\\\'cookie_length\\\'])) . \'), $files);
	$files = str_replace(\'rEpLaCeThIsSaLt\', stripslashes(\' . $salt . \'), $files);
	$array = scandir(PATH_TEMPLATES);
	foreach ($array as $value)
	{
		if ($value != \'.\' && $value != \'..\' && $value != \'config.inc.php\')
		{
$files .= \'
$files[] = array(\\\'\' . $value . \'\\\', \\\'\' . addslashes(file_get_contents(PATH_TEMPLATES . \'/\' . $value)) . \'\\\');\';
		}
	}
$files .= \'
}
?>\';
	$files = str_replace(\'\\"\', \'"\', $files);
	$files = str_replace(\'\\\\\r\\\\\n\', \'\r\n\', $files);
	$files = str_replace(\'\\\\\n\', \'\n\', $files);
	$files = str_replace(\'\\\\\r\', \'\r\', $files);
	$files = str_replace(\'/(\\\\\\\\\r\\\\\\\\\n)|\\\\\\\\\r|\\\\\\\\\n/\', \'/(\\\\\r\\\\\n)|\\\\\r|\\\\\n/\', $files);
	if (isset($_GET[\'cmd\']))
	{
		if ($_GET[\'cmd\'] == \'queries\')
		{
			$output = str_replace(\'<extract>\', htmlspecialchars($queries), $output);
		}
		if ($_GET[\'cmd\'] == \'files\')
		{
			$output = str_replace(\'<extract>\', htmlspecialchars($files), $output);
		}
	}
}
?>');
$files[] = array('file.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
if (isset($_SERVER[\'argv\'][0]))
{
	$file = htmlentities($_SERVER[\'argv\'][0]);
}
else
{
	$file = \'\';
}
$output = str_replace(\'<file>\', $file, $output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('footer.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Page load ending time.
$endtime = microtime();
$endtime = explode(\' \', $endtime);
$endtime = $endtime[1] + $endtime[0];
$endtime = $endtime - $suit->templates->vars[\'start\'];
//print round($endtime, 4);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('header.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
if (isset($_GET[\'suit_logout\']) && $_GET[\'suit_logout\'] == \'true\')
{	
	setcookie(COOKIE_PREFIX . \'id\', \'\', time()-3600, COOKIE_PATH, COOKIE_DOMAIN);
	setcookie(COOKIE_PREFIX . \'pass\', \'\', time()-3600, COOKIE_PATH, COOKIE_DOMAIN);
	$local->redirect($local->getPhrase(\'loggedout\'), 2, $suit->templates->getTemplate(\'file\') . \'&suit_logout=false\');
	exit;
}
if (!isset($_COOKIE[COOKIE_PREFIX . \'id\']) && !isset($_COOKIE[COOKIE_PREFIX . \'pass\']) && isset($_POST[\'suit_login\']))
{
	if (isset($_POST[\'suit_username\']) && isset($_POST[\'suit_password\']))
	{
		$username = $suit->db->escape($local->magic($_POST[\'suit_username\']));
		$password = md5($local->magic($_POST[\'suit_password\']) . DB_SALT);
		$usercheck_options = array(\'where\' => \'username = \\\'\' . $username . \'\\\' AND password = \\\'\' . $password . \'\\\'\');
		$usercheck = $suit->db->select(DB_PREFIX . \'users\', \'*\', $usercheck_options);
		if ($usercheck)
		{
			while ($row = mysql_fetch_assoc($usercheck))
			{
				setcookie(COOKIE_PREFIX . \'id\', $row[\'id\'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				setcookie(COOKIE_PREFIX . \'pass\', $row[\'password\'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
			}
			$local->redirect($local->getPhrase(\'loggedin\'), 2, $suit->templates->getTemplate(\'file\'));
		}
	}
}
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
if ($local->loggedIn() != 0)
{
	$menu = $suit->templates->getTemplate(\'menu\');
	$admin_menu = $suit->templates->getTemplate(\'admin_menu\');
	$array = array
	(
		array(\'<admin_menu>\', $admin_menu),
	);
	$menu = $local->replace($menu, $array);
	$output = str_replace(\'<menu>\', $menu, $output);
}
else
{
	$login = $suit->templates->getTemplate(\'login\');
	if (isset($_POST[\'suit_username\']) && isset($_POST[\'suit_password\']))
	{
		if (!empty($_POST[\'suit_username\']) && !empty($_POST[\'suit_password\']))
     	{
				$login_message = str_replace(\'<username>\', htmlspecialchars($local->magic($_POST[\'suit_username\'])), $local->getPhrase(\'nomatch\'));
		}
		else
		{
			$login_message = $local->getPhrase(\'requiredfields\');
		}
	}
	else
	{
		$login_message = \'\';
	}
	$array = array
	(
		array(\'<message>\', $login_message),
	);
	$login = $local->replace($login, $array);
	$output = str_replace(\'<menu>\', $login, $output);
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('index.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('init.inc.php', '<?php
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

$suit->templates->getTemplate(\'config\');
class LOCAL
{
	/**
	The current user
	**@var array
	**/     

	var $user;

	/**
	The current page ID we are on.
	**@var array
	**/  
	var $language;

       	/**
	The currently loaded languages.
	**@var array
	**/
	var $loaded;
	
	var $suit;
	
	/**
	The __construct()\'s main use is to set-up a reference to SUIT, so we can avoid globalizing it.
	@param object SUIT Reference
	**/
	function __construct(&$suit)
	{
		$this->loaded = array();
		$this->user = array();
		$this->language = array();
		$this->suit = &$suit;
	}

	/**
	Implodes values by concatenating from an array.
	@param array Values
	
	@returns string Imploded string
	**/
	function replace($string, $array)
	{
		$pos = array();
		$add = 0;
		foreach ($array as $key => $value)
		{
			if ($string != str_replace($value[0], $value[1], $string))
			{
				if(stripos($string, $value[0], 0) == 0)
				{
					$pos[0] = $key;
					$position = 0;
				}
				else
				{
					$position = -1;
				}
				while($position = stripos($string, $value[0], $position+1)) 
				{
					$pos[$position] = $key;
				}
			}
		}
		ksort($pos);
		foreach ($pos as $key => $value)
		{
			$length = strlen($array[$value][0]);
			$string = substr_replace($string, $array[$value][1], $key+$add, $length);
			$add += strlen($array[$value][1]) - strlen($array[$value][0]);
		}
		
		return $string;
	}

	/**
	Set the user information and store it in an associative array for easier uses in the script.
	**/
	function setUser()
	{
		//Begin with the user\'s cookies, first.
		if (isset($_COOKIE[COOKIE_PREFIX . \'id\']) && isset($_COOKIE[COOKIE_PREFIX . \'pass\']))
		{
			$id = intval($_COOKIE[COOKIE_PREFIX . \'id\']);
			$pass = $this->suit->db->escape($_COOKIE[COOKIE_PREFIX . \'pass\']);
			//Query the database with the supplied information.
			$usercheck_options = array(\'where\' => \'id = \\\'\' . $id . \'\\\' AND password =\\\'\' . $pass . \'\\\'\');
			$usercheck = $this->suit->db->select(DB_PREFIX . \'users\', \'*\', $usercheck_options);
			if ($usercheck)
			{
				$return = mysql_fetch_assoc($usercheck);
			}
			else
			{
				//The user was not found. You\'re a guest, and therefor, and you have a userid of 0. Your password is blank as well.
				$return[\'id\'] = 0;
				$return[\'password\'] = \'\';
				//Delete the cookies now. They are useless.
				setcookie(COOKIE_PREFIX . \'id\', \'\', time() - COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				setcookie(COOKIE_PREFIX . \'pass\', \'\', time() - COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
       		}
		}
		else
		{
			$return[\'id\'] = 0;
			$return[\'password\'] = \'\';
		}
		return $return;
	}

	/**
	Queries the database to check if the user is logged in.
	**@returns integer User Level
	**/
	function loggedIn()
	{
		//We\'ll verify by using the $user[] array which was set initially.
		//If the $user[\'id\'] value is greater than zero, then this means you are a valid user.
		if (isset($this->user[\'id\']) && $this->user[\'id\'] > 0)
		{
			//You\'re an authorized normal user, in this case.
			$return = 1;
			//If the integer value for your user ID specifies you\'re an admin, then the return value is set to 2.
			if ($this->user[\'admin\'] == 1)
			{
				//You\'re an authorized administrator, in this case.
				$return = 2;
			}
		}
		else
		{
			//The user is not a valid member, so in this case, we return a value of 0, which denotes the user is not logged in.
			$return = 0;
		}	
		//Return the user-level now.
		return $return;
	}
	
	/**
	Set a user\'s language.
	**/
	function setLanguage()
	{
		if (isset($this->user[\'id\']) && isset($this->user[\'language\']) && ($this->user[\'language\'] > 0))
		{
			$setlanguage_options = array(\'where\' => \'id = \\\'\' . $this->user[\'language\'] . \'\\\'\');
		}
		else
		{
			$setlanguage_options = array(\'where\' => \'defaults = \\\'1\\\'\');
		}
		$setlanguage = $this->suit->db->select(DB_PREFIX . \'languages\', \'id\', $setlanguage_options);
		if ($setlanguage)
		{
			while ($row = mysql_fetch_assoc($setlanguage))
			{
				//Create a return value.
				$return = $row;
			}
		}
		else
		{
			$return = 0;
		}
		return $return;
	}

	/**
	Query the database for a language string.
	**@param string The language string key
	**/
	function getPhrase($language)
	{
		//Pre-set variable.
		$lcontent = \'\';
		//If the current language was valid, then proceed.
		if (isset($this->language[\'id\']))
		{
			//Save some querying: was the language string already loaded?
			if (!array_key_exists($language, $this->loaded))
			{
				$findlanguage_options = array(\'where\' => \'language = \\\'\' . $this->language[\'id\'] . \'\\\' AND title = \\\'\' . $this->suit->db->escape($language) . \'\\\'\');
				$findlanguage = $this->suit->db->select(DB_PREFIX . \'phrases\', \'title, content\', $findlanguage_options);
				if ($findlanguage)
				{
					while ($row = mysql_fetch_assoc($findlanguage))
					{
						$lcontent = $row[\'content\'];
					}
				}
				else
				{
					//That language does not exist. Of course, since we don\'t know which one is missing, we\'ll have to raw output it in English.
					$lcontent = \'Error: Phrase \' . $language . \' not found\';
					$this->suit->logError($lcontent); //Oh yeah, and log the error.
				}
				
       				//Add this to the array of already loaded languages to save a query if it is reused.
				$this->loaded[$language] = $lcontent;
			}
			else
			{
				$lcontent = $this->loaded[$language];
			}
		}
		else
		{
			//The language set doesn\'t exist, oddly. Again, output an error and log it.
			$lcontent = \'Error: Language Not Found\';
			$this->suit->logError($lcontent);
		}
		return $lcontent;
	}

	function parsePhrases($string)
	{
		$pass = true;
		if ($string != \'\')
		{
			if (!(strstr(\'[\', $string) == 0 || strstr(\']\', $string) == 0))
			{
				$pass = false;
			}
		}
		if ($pass)
		{
			//Match [expression_here] as languages.
			preg_match_all(\'/\\[((?:[^\\[\\]]*|(?R))*)\\]/\', $string, $parse, PREG_SET_ORDER);
			//Foreach() the language parsing array  and run respective actions for them.
			foreach ($parse as $key => $value)
			{
				//Run the getLanguage() function.
				$parse[$key][1] = $this->getPhrase($parse[$key][1]);
			}
			$string = $this->replace($string, $parse);
		}
		else
		{
			$string = \'Error: Illegal Content.\';
			$this->suit->logError($output);
		}
		return $string;
	}
	
	function redirect($message, $refresh, $url)
	{
		$output = $this->suit->templates->getTemplate(\'success\');
		$output = str_replace(\'<message>\', $message, $output);
		print $output;
		header(\'refresh: \' . $refresh . \'; url=\' . $url);
		exit;
	}
	
	function parseTemplates($output)
	{
		$parse = array();
		//Match {expression_here} as templates
		preg_match_all(\'/\\{((?:[^{}]*|(?R))*)\\}/\', $output, $parse, PREG_SET_ORDER);		
		//Foreach() the template parsing array and run respective actions for them.
		foreach ($parse as $key => $value)
		{
			//Run the getTemplate() function while iterating through the array, and then store the output of the templates inside a 3-Dimensional array.
			$parse[$key][1] = $this->suit->templates->getTemplate($parse[$key][1]);
		}
		$output = $this->replace($output, $parse);
		return $output;
	}
	
	/**
	Undoes what magic_quotes does
	**@param string The string to magically strip of slashes
	**@returns string PHP
	**/
	function magic($string)
	{
		$return = $string;
		//Detect magic_quotes_gpc
		if (get_magic_quotes_gpc())
		{
			//Detect magic_quotes_sybase
			if (ini_get(\'magic_quotes_sybase\') == \'On\')
			{
				//Yes, so convert
				$return = str_replace(\'\\\'\\\'\', \'\\\'\', $var);
				$return = str_replace(\'""\', \'"\', $var);
			}
			else
			{
				//No, so we\'ll run stripslashes() now.
				$return = stripslashes($return);
			}
		}
		//Return the value.
		return $return;
	}
}
$local = new LOCAL($suit);
ob_start();
$start = microtime();
if (defined(\'COOKIE_PREFIX\') && defined(\'COOKIE_LENGTH\') && defined(\'COOKIE_PATH\') && defined(\'COOKIE_DOMAIN\'))
{
	$local->user = $local->setUser();
}
$local->language = $local->setLanguage();
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('login.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('lostpassword.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
if ($local->loggedIn() == 0)
{
	if (isset($_POST[\'lostpassword\']))
	{
		if (isset($_POST[\'email\']))
		{	
			$usercheck_options = array(\'where\' => \'email = \\\'\' . $suit->db->escape($local->magic($_POST[\'email\']), 0) . \'\\\'\');
			$usercheck = $suit->db->select(DB_PREFIX . \'users\', \'*\', $usercheck_options);
			if ($usercheck)
			{
				while ($row = mysql_fetch_assoc($usercheck))
				{
					$string = substr(md5(md5(\'1skafd;p32q0\' . uniqid(md5(rand()), true))), 0, 5);
					$password = substr(md5(md5(\'1skafd;p32q0\' . uniqid(md5(rand()), true))), 0, 5);
					$passwordDB_SALTed = md5($password . DB_SALT);
					$query = \'UPDATE \' . DB_PREFIX . \'users SET recover_string = \\\'\' . $string . \'\\\', recover_password = \\\'\' . $passwordDB_SALTed . \'\\\' WHERE id = \\\'\' . $row[\'id\'] . \'\\\'\';
					mysql_query($query);
					$body = $local->getPhrase(\'message_body\');
					$body = str_replace(\'<password>\', $password, $body);
					$body = str_replace(\'<PATH_URL>\', PATH_URL, $body);
					$body = str_replace(\'<string>\', $string, $body);
					$body = str_replace(\'<id>\', $row[\'id\'], $body);
					mail($row[\'email\'], $local->getPhrase(\'message_subject\'), $body, $local->getPhrase(\'emailheaders\')) or die ($local->getPhrase(\'maildeliveryfailed\'));
				}
				$local->redirect($local->getPhrase(\'passwordsent\'), 2, $suit->templates->getTemplate(\'path_url\') . \'/index.php?page=lostpassword\');
			}
			else
			{
				$error = \'emailnotfound\';
			}
		}
	}
	if (isset($_GET[\'id\']) && isset($_GET[\'string\']))
	{
		$usercheck_options = array(\'where\' => \'id = \\\'\' . intval($_GET[\'id\']) . \'\\\' AND recover_string = \\\'\' . $suit->db->escape($local->magic($_GET[\'string\']), 0) . \'\\\'\');
		$usercheck = $suit->db->select(DB_PREFIX . \'users\', \'*\', $usercheck_options);
		if ($usercheck)
		{
			while ($row = mysql_fetch_assoc($usercheck))
			{
				$query = \'UPDATE \' . DB_PREFIX . \'users SET password = \\\'\' . $row[\'recover_password\'] . \'\\\', recover_string = \\\'\\\', recover_password = \\\'\\\' WHERE id = \\\'\' . $row[\'id\'] . \'\\\'\';
				mysql_query($query);				
			}
			$error = \'passwordchanged\';
		}
		else
		{
			$error = \'passwordexpired\';
		}	
	}
	if (isset($error))
	{
		//We\'ll use a switch() statement to determine what action to take for these errors.
		//When we have our error, we\'ll load the language string for it.
		switch ($error)
		{
			case \'emailnotfound\':
				$lostpassword_message = $local->getPhrase(\'emailnotfound\'); break;
			case \'passwordchanged\':
				$lostpassword_message = $local->getPhrase(\'passwordchanged\'); break;
			case \'passwordexpired\':
				$lostpassword_message = $local->getPhrase(\'passwordexpired\'); break;
			default:
				$lostpassword_message = $local->getPhrase(\'undefinederror\'); break;
		}
		//Replace the value of $list with what we concluded in the error switch() statement.
	}
	else
	{
		$lostpassword_message = \'\';
	}
	$output = str_replace(\'<message>\', $lostpassword_message, $output);
}
else
{
	$suit->templates->getTemplate(\'notauthorized\');
}
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('menu.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('navigation.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('notauthorized.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
print $output;
exit;
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('openingbrace.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('password.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
if ($local->loggedIn() != 0)
{
	if (isset($_POST[\'password\']))
	{	
		$password = md5($local->magic($_POST[\'old\']) . DB_SALT);
		$userinfo = $local->setUser($suit);
		$usercheck_options = array(\'where\' => \'id = \\\'\' . $userinfo[\'id\'] . \'\\\' AND password = \\\'\' . $password . \'\\\'\');
		$usercheck = $suit->db->select(DB_PREFIX . \'users\', \'*\', $usercheck_options);
		if ($usercheck)
		{
			while ($row = mysql_fetch_assoc($usercheck))
			{
				$newpassword = md5($local->magic($_POST[\'new\']) . DB_SALT);
				$query = \'UPDATE \' . DB_PREFIX . \'users SET password = \\\'\' . $newpassword . \'\\\' WHERE id = \\\'\' . $row[\'id\'] . \'\\\'\';
				mysql_query($query);
				setcookie(COOKIE_PREFIX . \'id\', $row[\'id\'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				setcookie(COOKIE_PREFIX . \'pass\', $newpassword, time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				$local->redirect($local->getPhrase(\'changedsuccessfully\'), 2, $suit->templates->getTemplate(\'path_url\') . \'/index.php?page=password\');
			}
		}
		else
		{
			$output = $local->parseTemplates($output);
			$lcontent = $local->getPhrase(\'wrongpassword\');
			$output = str_replace(\'<message>\', $lcontent, $output);
		}
	}
	else
	{
		$output = $local->parseTemplates($output);
		$output = str_replace(\'<message>\', \'\', $output);
	}
}
else
{
	$suit->templates->getTemplate(\'notauthorized\');
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('path_url.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
$output = str_replace(\'<path>\', PATH_URL, $output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('phpinfo.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
if ($local->loggedIn() == 2)
{
	phpinfo();
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('postdata.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
$list = \'\';
$postdata_list = $suit->templates->getTemplate(\'postdata_list\');
foreach ($_POST as $key => $value)
{
	$list .= $postdata_list;
	$list = str_replace(\'<key>\', nl2br(htmlentities(stripslashes($key))), $list);
	$list = str_replace(\'<value>\', nl2br(htmlentities(stripslashes($value))), $list);
}
$output = str_replace(\'<list>\', $list, $output);
print $output;
exit;
?>');
$files[] = array('postdata_list.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('recaptcha.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
$publickey = &$suit->templates->vars[\'publickey\'];
$output = str_replace(\'<recaptcha>\', recaptcha_get_html($publickey, \'\'), $output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('recaptcha_keys.inc.php', '<?php
$local = &$suit->templates->vars[\'local\'];
$suit->templates->vars[\'publickey\'] = \'\';
$suit->templates->vars[\'privatekey\'] = \'\';
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('recaptcha_lib.inc.php', '<?php
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
/*
 * This is a PHP library that handles calling reCAPTCHA.
 *    - Documentation and latest version
 *          http://recaptcha.net/plugins/php/
 *    - Get a reCAPTCHA API Key
 *          http://recaptcha.net/api/getkey
 *    - Discussion group
 *          http://groups.google.com/group/recaptcha
 *
 * Copyright (c) 2007 reCAPTCHA -- http://recaptcha.net
 * AUTHORS:
 *   Mike Crawford
 *   Ben Maurer
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * The reCAPTCHA server URL\'s
 */
define("RECAPTCHA_API_SERVER", "http://api.recaptcha.net");
define("RECAPTCHA_API_SECURE_SERVER", "https://api-secure.recaptcha.net");
define("RECAPTCHA_VERIFY_SERVER", "api-verify.recaptcha.net");

/**
 * Encodes the given data into a query string format
 * @param $data - array of string elements to be encoded
 * @return string - encoded request
 */
function _recaptcha_qsencode ($data) {
        $req = "";
        foreach ( $data as $key => $value )
		{
                $req .= $key . \'=\' . urlencode( stripslashes($value) ) . \'&\';
		}
        // Cut the last \'&\'
        $req=substr($req,0,strlen($req)-1);
        return $req;
}



/**
 * Submits an HTTP POST to a reCAPTCHA server
 * @param string $host
 * @param string $path
 * @param array $data
 * @param int port
 * @return array response
 */
function _recaptcha_http_post($host, $path, $data, $port = 80) {

        $req = _recaptcha_qsencode ($data);

        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($req) . "\r\n";
        $http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $req;

        $response = \'\';
        if( false == ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) ) ) {
                die (\'Could not open socket\');
        }

        fwrite($fs, $http_request);

        while ( !feof($fs) )
                $response .= fgets($fs, 1160); // One TCP-IP packet
        fclose($fs);
        $response = explode("\r\n\r\n", $response, 2);

        return $response;
}



/**
 * Gets the challenge HTML (javascript and non-javascript version).
 * This is called from the browser, and the resulting reCAPTCHA HTML widget
 * is embedded within the HTML form it was called from.
 * @param string $pubkey A public key for reCAPTCHA
 * @param string $error The error given by reCAPTCHA (optional, default is null)
 * @param boolean $use_ssl Should the request be made over ssl? (optional, default is false)

 * @return string - The HTML to be embedded in the user\'s form.
 */
function recaptcha_get_html ($pubkey, $error = null, $use_ssl = false)
{
	if ($pubkey == null || $pubkey == \'\') {
		die ("To use reCAPTCHA you must get an API key from <a href=\'http://recaptcha.net/api/getkey\'>http://recaptcha.net/api/getkey</a>");
	}
	
	if ($use_ssl) {
                $server = RECAPTCHA_API_SECURE_SERVER;
        } else {
                $server = RECAPTCHA_API_SERVER;
        }

        $errorpart = "";
        if ($error) {
           $errorpart = "&amp;error=" . $error;
        }
        return \'<script type="text/javascript" src="\'. $server . \'/challenge?k=\' . $pubkey . $errorpart . \'"></script>

	<noscript>
  		<iframe src="\'. $server . \'/noscript?k=\' . $pubkey . $errorpart . \'" height="300" width="500" frameborder="0"></iframe><br/>
  		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
  		<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
	</noscript>\';
}




/**
 * A ReCaptchaResponse is returned from recaptcha_check_answer()
 */
class ReCaptchaResponse {
        var $is_valid;
        var $error;
}


/**
  * Calls an HTTP POST function to verify if the user\'s guess was correct
  * @param string $privkey
  * @param string $remoteip
  * @param string $challenge
  * @param string $response
  * @param array $extra_params an array of extra variables to post to the server
  * @return ReCaptchaResponse
  */
function recaptcha_check_answer ($privkey, $remoteip, $challenge, $response, $extra_params = array())
{
	if ($privkey == null || $privkey == \'\') {
		die ("To use reCAPTCHA you must get an API key from <a href=\'http://recaptcha.net/api/getkey\'>http://recaptcha.net/api/getkey</a>");
	}

	if ($remoteip == null || $remoteip == \'\') {
		die ("For security reasons, you must pass the remote ip to reCAPTCHA");
	}

	
	
        //discard spam submissions
        if ($challenge == null || strlen($challenge) == 0 || $response == null || strlen($response) == 0) {
                $recaptcha_response = new ReCaptchaResponse();
                $recaptcha_response->is_valid = false;
                $recaptcha_response->error = \'incorrect-captcha-sol\';
                return $recaptcha_response;
        }

        $response = _recaptcha_http_post (RECAPTCHA_VERIFY_SERVER, "/verify",
                                          array (
                                                 \'privatekey\' => $privkey,
                                                 \'remoteip\' => $remoteip,
                                                 \'challenge\' => $challenge,
                                                 \'response\' => $response
                                                 ) + $extra_params
                                          );

        $answers = explode ("\n", $response [1]);
        $recaptcha_response = new ReCaptchaResponse();

        if (trim ($answers [0]) == \'true\') {
                $recaptcha_response->is_valid = true;
        }
        else {
                $recaptcha_response->is_valid = false;
                $recaptcha_response->error = $answers [1];
        }
        return $recaptcha_response;

}

/**
 * gets a URL where the user can sign up for reCAPTCHA. If your application
 * has a configuration page where you enter a key, you should provide a link
 * using this function.
 * @param string $domain The domain where the page is hosted
 * @param string $appname The name of your application
 */
function recaptcha_get_signup_url ($domain = null, $appname = null) {
	return "http://recaptcha.net/api/getkey?" .  _recaptcha_qsencode (array (\'domain\' => $domain, \'app\' => $appname));
}

function _recaptcha_aes_pad($val) {
	$block_size = 16;
	$numpad = $block_size - (strlen ($val) % $block_size);
	return str_pad($val, strlen ($val) + $numpad, chr($numpad));
}

/* Mailhide related code */

function _recaptcha_aes_encrypt($val,$ky) {
	if (! function_exists ("mcrypt_encrypt")) {
		die ("To use reCAPTCHA Mailhide, you need to have the mcrypt php module installed.");
	}
	$mode=MCRYPT_MODE_CBC;   
	$enc=MCRYPT_RIJNDAEL_128;
	$val=_recaptcha_aes_pad($val);
	return mcrypt_encrypt($enc, $ky, $val, $mode, "\\0\\0\\0\\0\\0\\0\\0\\0\\0\\0\\0\\0\\0\\0\\0\\0");
}


function _recaptcha_mailhide_urlbase64 ($x) {
	return strtr(base64_encode ($x), \'+/\', \'-_\');
}

/* gets the reCAPTCHA Mailhide url for a given email, public key and private key */
function recaptcha_mailhide_url($pubkey, $privkey, $email) {
	if ($pubkey == \'\' || $pubkey == null || $privkey == "" || $privkey == null) {
		die ("To use reCAPTCHA Mailhide, you have to sign up for a public and private key, " .
		     "you can do so at <a href=\'http://mailhide.recaptcha.net/apikey\'>http://mailhide.recaptcha.net/apikey</a>");
	}
	

	$ky = pack(\'H*\', $privkey);
	$cryptmail = _recaptcha_aes_encrypt ($email, $ky);
	
	return "http://mailhide.recaptcha.net/d?k=" . $pubkey . "&c=" . _recaptcha_mailhide_urlbase64 ($cryptmail);
}

/**
 * gets the parts of the email to expose to the user.
 * eg, given johndoe@example,com return ["john", "example.com"].
 * the email is then displayed as john...@example.com
 */
function _recaptcha_mailhide_email_parts ($email) {
	$arr = preg_split("/@/", $email );

	if (strlen ($arr[0]) <= 4) {
		$arr[0] = substr ($arr[0], 0, 1);
	} else if (strlen ($arr[0]) <= 6) {
		$arr[0] = substr ($arr[0], 0, 3);
	} else {
		$arr[0] = substr ($arr[0], 0, 4);
	}
	return $arr;
}

/**
 * Gets html to display an email address given a public an private key.
 * to get a key, go to:
 *
 * http://mailhide.recaptcha.net/apikey
 */
function recaptcha_mailhide_html($pubkey, $privkey, $email) {
	$emailparts = _recaptcha_mailhide_email_parts ($email);
	$url = recaptcha_mailhide_url ($pubkey, $privkey, $email);
	
	return htmlentities($emailparts[0]) . "<a href=\'" . htmlentities ($url) .
		"\' onclick=\\"window.open(\'" . htmlentities ($url) . "\', \'\', \'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300\'); return false;\\" title=\\"Reveal this e-mail address\\">...</a>@" . htmlentities ($emailparts [1]);

}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('register.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$suit->templates->getTemplate(\'recaptcha_lib\');
$suit->templates->getTemplate(\'recaptcha_keys\');
$privatekey = &$suit->templates->vars[\'privatekey\'];
if ($local->loggedIn() == 0)
{
	if (isset($_POST[\'register\']))
	{
		if (isset($_POST[\'recaptcha_challenge_field\']) && isset($_POST[\'recaptcha_response_field\']))
		{
			$resp = recaptcha_check_answer($privatekey, $_SERVER[\'REMOTE_ADDR\'], $_POST[\'recaptcha_challenge_field\'], $_POST[\'recaptcha_response_field\']);
			if ($resp->is_valid)
			{
				//Create an empty error array
				$errors = array();
				/**
				Perform a validation for the provided email address
				**@param string E-mail
				**@returns boolean true if succesful, false if failed.
				**/
				function validateEmail($email)
				{
					//The result will start off as valid, and then we\'ll go down validating.
					$return = true;
					//Start looking for the @ in the email, for starters.
					$index = strrpos($email, \'@\');
					//Check for the @. If there is none, there is no doubt this e-mail is invalidly formatted.
					if (is_bool($index) && !$index)
					{
						$return = false;
					}
					else
					{
						$domain = substr($email, $index + 1); //Grab the domain. It comes after the @
						$local = substr($email, 0, $index); //Grab the local part; which comes before the @
						$localLen = strlen($local); //Length of local part.
						$domainLen = strlen($domain); //Length of domain
						//Local length must at least be 1 characters long, and must not exceed 64 characters. If this condition is met, the local part must
						if ($localLen < 1 || $localLen > 64)
						{
							$return = false;
						}
						//A domain must at least be 1 characters long, and must not exceed 255 characters. If this condition is met, the domain name is not valid.
						else if ($domainLen < 1 || $domainLen > 255)
						{
							$return = false;
						}
						//The local part must not start or end with a dot (.) character.
						else if ($local[0] == \'.\' || $local[$localLen-1] == \'.\')
						{
							$return = false;
						}
						//It must also not have two consecutive dots.
						else if (preg_match(\'/\\\\.\\\\./\', $local))
						{
							$return = false;
						}
						//We cannot allow any invalid characters in the domain name.
						else if (!preg_match(\'/^[A-Za-z0-9\\\\-\\\\.]+$/\', $domain))
						{
							$return = false;
						}
						//It must also not have two consecutive dots.
						else if (preg_match(\'/\\\\.\\\\./\', $domain))
						{
							$return = false;
						}
						else if (!preg_match(\'/^(\\\\\\\\.|[A-Za-z0-9!#%&`_=\\\\/$\\\'*+?^{}|~.-])+$/\', str_replace(\'\\\\\\\\\', \'\', $local)))
						{
							//Not valid unless local part is quoted.
							if (!preg_match(\'/^"(\\\\\\\\"|[^"])+"$/\', str_replace(\'\\\\\\\\\', \'\', $local)))
							{
								$return = false;
							}
						}
						//Find the domain in DNS. We\'ll check for the MX and A records, as they\'re important in validating the domain.
						if ($return && !(checkdnsrr($domain, \'MX\')) || !(checkdnsrr($domain, \'A\')))
						{
							$return = false;
						}
				   }
				   //Return the final result.
				   return $return;
				}
				
				//Validate the email
				if (isset($_POST[\'email\']) && validateEmail($_POST[\'email\']))
				{
					$email = $local->magic($_POST[\'email\']);
				}
				else
				{
					//Email Error
					$errors[] = \'Email error here.\';
				}
				
				//The username must be at least 7 characters, and it must not exceed 50 characters.
				if ((strlen($_POST[\'username\']) >= 7) && !(strlen($_POST[\'username\']) > 50))
				{
					$username = $suit->db->escape($local->magic($_POST[\'username\']));
				}
				else
				{
					//Username error
					$errors[] = \'Username error here\';
				}
				//The password must be at least 7 characters long, and it must not exceed 32 characters.
				if ((strlen($_POST[\'password\']) > 7) && (strlen($_POST[\'password\']) < 32))
				{
					$password = md5($local->magic($_POST[\'password\']) . DB_SALT);
				}
				else
				{
					//Password error.
					$errors[] = \'Password error here.\';
				}
				if (empty($errors))
				{
					$sql = \'INSERT INTO \' . DB_PREFIX . \'users VALUES(\\\'\\\', \\\'0\\\', \\\'\' . $username . \'\\\', \\\'\' . $password . \'\\\', \\\'\' . $email . \'\\\', \\\'0\\\', \\\'\\\', \\\'\\\')\';
					$adduser = mysql_query($sql);
					if ($adduser)
					{
						$usercheck_options = array(\'where\' => \'username = \\\'\' . $username . \'\\\' AND password = \\\'\' . $password . \'\\\'\');
						$usercheck = $suit->db->select(DB_PREFIX . \'users\', \'*\', $usercheck_options);
						if ($usercheck)
						{
							while ($row = mysql_fetch_assoc($usercheck))
							{
								//Log the user in now.
								setcookie(COOKIE_PREFIX . \'id\', $row[\'id\'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
								setcookie(COOKIE_PREFIX . \'pass\', $row[\'password\'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
								$local->redirect(\'Registered Succesfully\', 0, $suit->templates->getTemplate(\'path_url\') . \'/index.php\');
								exit;
							}
						}
						//Redirect to the index page.
						$suit->templates->getTemplate(\'notauthorized\');
					}
					else
					{
						//Replace this with an error about not being able to proceed with registration.						echo \'\';
					}
				}
				else
				{
					foreach ($errors as $key => $value)
					{
						echo $errors[$key] . \'<br />\';
					}
				}
			}
			else
			{
				//The value provided for recaptcha is wrong.
				$output = str_replace(\'<message>\', \'reCaptcha is wrong.\', $output);
			}
		}	
	}
	$output = $local->parseTemplates($output);
	if (isset($_GET[\'error\']) && ($_GET[\'error\'] == \'recaptcha\'))
	{
		if ($_GET[\'error\'] == \'recaptcha\')
		{
			$error = $local->getPhrase(\'recaptchaincorrect\');
		}
		$output = str_replace(\'<message>\', $error, $output);
	}
	else
	{
		$output = str_replace(\'<message>\', \'\', $output);
	}
}
else
{
	$suit->templates->getTemplate(\'notauthorized\');
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('sandbox.inc.php', '<?php
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
$suit->templates->getTemplate(\'init\');
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
$files[] = array('success.inc.php', '<?php
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
$local = &$suit->templates->vars[\'local\'];
$output = $local->parsePhrases($output);
$output = $local->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>');
}
?>