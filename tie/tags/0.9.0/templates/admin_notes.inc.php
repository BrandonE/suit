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
if ($tie->loggedIn($suit) == 2)
{
	if (isset($_POST['notes']))
	{
		if (isset($_POST['content']))
		{
			$content = $suit->db->escape($tie->magic($_POST['content']));
			$notes = $suit->db->select(DB_PREFIX . 'notes', '*');
			if ($notes)
			{
				$query = 'UPDATE ' . DB_PREFIX . 'notes SET content = \'' . $content . '\'';
				mysql_query($query);
				$tie->redirect($tie->getPhrase('updatedsuccessfully'), 2, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_notes');
			}
		}
	}
	$output = $tie->parseTemplates($output);
	$notes_get = $suit->db->select(DB_PREFIX . 'notes', '*');
	if ($notes_get)
	{
		while ($row = mysql_fetch_assoc($notes_get))
		{
			$notes = $row['content'];
		}
	}
	else
	{
		$notes = '';
	}
	$array = array
	(
		array('<welcome>', $tie->getPhrase('adminwelcome')),
		array('<notes>', htmlentities($notes))
	);
	$output = $tie->replace($output, $array);
}
else
{
	$output = $tie->parseTemplates($output);
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>