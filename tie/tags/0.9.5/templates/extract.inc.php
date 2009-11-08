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
	function insert(&$suit, $db, $table, $query, $columns)
	{
		$check = $suit->db->query($query);
		if (mysql_num_rows($check))
		{
			$fields = 'array(';
			foreach ($columns as $value)
			{
				$fields .= '\'' . $value . '\', ';
			}
			$fields = substr_replace($fields, '', strlen($fields)-2, 2);
			$fields .= ')';
$db .= '
	$db[] = array
	(
		\'insert\',
		\'' . $table . '\',
		' . $fields . ',
		array
		(';
			while ($row = $suit->db->fetch($check))
			{
$db .= '
			array(';
				foreach ($columns as $value)
				{
					$db .= '\'' . str_replace('\"', '"', addslashes($row[$value])) . '\', ';
				}
				$db = substr_replace($db, '', strlen($db)-2, 2);
				$db .= '),';
			}
			$db = substr_replace($db, '', strlen($db)-1, 1);
$db .= '
		)
	);';
		}
		return $db;
	}
	if (isset($_GET['cmd']))
	{
		if ($_GET['cmd'] == 'db')
		{
$output = '<?php
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
if (isset($db))
{';
			if (isset($_GET['language']))
			{
				$query = 'SELECT * FROM ' . DB_PREFIX . 'phrases WHERE language = \'' . $_GET['language'] . '\' ORDER BY title';
				$output = insert($suit, $output, 'phrases', $query, array('title', 'content', 'language'));
			}
			else
			{
					$options = array
					(
						'orderby' => 'title'
					);
					$query = 'SELECT * FROM ' . DB_PREFIX . 'pages ORDER BY title';
					$output = insert($suit, $output, 'pages', $query, array('title', 'template', 'defaults'));
					$query = 'SELECT * FROM ' . DB_PREFIX . 'templates ORDER BY title';
					$output = insert($suit, $output, 'templates', $query, array('title', 'content'));
			}
$output .= '
}
?>';
		}
		elseif ($_GET['cmd'] == 'files')
		{
$output = '<?php
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
{';
	$array = scandir(PATH_WRITABLE . '/templates');
			foreach ($array as $value)
			{
				if ($value != '.' && $value != '..' && $value != 'config.inc.php')
				{
$output .= '
	$files[] = array
	(
		\'' . $value . '\', \'' . addslashes(file_get_contents(PATH_WRITABLE . '/templates/' . $value)) . '\'
	);';
				}
			}
$output .= '
}
?>';
			$output = str_replace('\"', '"', $output);
			$output = str_replace('\\\r\\\n', '\r\n', $output);
			$output = str_replace('\\\n', '\n', $output);
			$output = str_replace('\\\r', '\r', $output);
			$output = str_replace('/(\\\\\r\\\\\n)|\\\\\r|\\\\\n/', '/(\\\r\\\n)|\\\r|\\\n/', $output);
		}
		$output = '<pre>' . htmlspecialchars($output) . '</pre>';
	}
	else
	{
		$languages = '';
		$query = 'SELECT id, title FROM ' . DB_PREFIX . 'languages ORDER BY title';
		$check = $suit->db->query($query);
		if (mysql_num_rows($check))
		{
			while ($row = $suit->db->fetch($check))
			{
				$languages .= '<br /><a href="' . $suit->templates->getTemplate('path_url') . '/index.php?page=extract&cmd=db&language=' . $row['id'] . '">' . $row['title'] . '</a>';
			}
		}
		$output = str_replace('<languages>', $languages, $output);
	}
}
?>