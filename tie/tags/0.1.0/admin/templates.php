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
require '../inc/adminheader.php';

if (loggedIn() == 2)
{
	if (isset($_POST['submit']))
	{
		$template = $system->mysql->escape($_POST['id']);
		$content = $system->mysql->escape($_POST['content']);
		$title = $system->mysql->escape($_POST['title']);
		
		$templatecheck_options = array(
		'where' => 'id = \'' . $template . '\''
		);
		
		$templatecheck = $system->mysql->select(
		'templates',
		'*',
		$templatecheck_options
		);
		
		if ($templatecheck)
		{
			while ($row = mysql_fetch_assoc($templatecheck))
			{
				$query = 'UPDATE ' . $tbl_prefix . 'templates SET content = \'' . $content . '\', title = \'' . $title . '\' WHERE id = \'' . $template . '\'';
				mysql_query($query);
			}
		}
		
		$message = 'updatedsuccessfully';
	}
	
	$system->templates->getTemplate('templates', 1, $adminid, $adminpass, 0, 0);
}
else
{
	header('refresh: 0; url=./index.php');
	exit;
}

print $content;

require '../inc/adminfooter.php';
?>
