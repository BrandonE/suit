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
if ($suit->loggedIn() == 2)
{
	$admin_index_loggedin_vars = $suit->templates->getTemplate('admin_index_loggedin', array());
	$admin_index_loggedin = $admin_index_loggedin_vars['output'];
	if (isset($_GET['cmd']) && ($_GET['cmd'] == 'updatenotes'))
	{
		$success_vars = $suit->templates->getTemplate('success', array());
		$updatedmessage = $success_vars['output'];
		$updatedmessage = str_replace('<1>', $suit->languages->getLanguage('updatedsuccessfully'), $updatedmessage);		
	}
	else
	{
		$updatedmessage = '';
	}
	$admin_index_notes_vars = $suit->templates->getTemplate('admin_index_notes', array());
	$admin_index_notes = $admin_index_notes_vars['output'];
	
	$notes = $suit->db->select(TBL_PREFIX . 'notes', '*');
	if ($notes)
	{
		while ($row = mysql_fetch_assoc($notes))
		{
			$admin_index_notes = str_replace('<notes>', $row['content'], $admin_index_notes);
		}
	}
	else
	{
		$admin_index_notes = str_replace('<notes>', '', $admin_index_notes);
	}
	$array = array
	(
		array('<updatedmessage>', $updatedmessage),
		array('<welcome>', $suit->languages->getLanguage('adminwelcome')),
		array('<notes>', $admin_index_notes)
	);
	$admin_index_loggedin = $suit->templates->replace($admin_index_loggedin, $array);
	$output = str_replace('<content>', $admin_index_loggedin, $output);
}
else
{
	$lcontent = $lcontent = $suit->languages->getLanguage('notauthorized');
	$output = str_replace('<content>', $lcontent, $output);
}
?>
