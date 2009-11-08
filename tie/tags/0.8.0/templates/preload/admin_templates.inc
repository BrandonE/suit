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
$suit->templates->getTemplate('init');
$output = $suit->templates->vars['local']->parsePhrases($output);

if ($suit->templates->vars['local']->loggedIn() == 2)
{
	if (isset($_POST['add']) && $_GET['cmd'] == 'add')
	{
		if (isset($_POST['title']))
		{
			$title = html_entity_decode($suit->db->escape($_POST['title']));
			$templatecheck_options = array('where' => 'title = \'' . $title . '\'');
			$templatecheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
			if (!$templatecheck)
			{		
				if ($title == '')
				{
					$error = 'missingtitle';
				}
			}
			else
			{
				$error = 'duplicatetitle';
			}
		}
		else
		{
			$suit->templates->getTemplate('badrequest');
		}
		if (isset($_POST['content']) && isset($title))
		{
			$content = html_entity_decode($suit->db->escape($_POST['content']));
			if (!isset($error))
			{
				$query = 'INSERT INTO ' . TBL_PREFIX . 'templates VALUES (\'\', \'' . $title . '\', \'' . $content . '\')';
				mysql_query($query);
			}
		}
		if (isset($_POST['preload']) && isset($title))
		{
			$preload = trim(html_entity_decode($_POST['preload']));
			if (!isset($error))
			{
				$filepath = $suit->templates->checkFile($title, 'preload');
				file_put_contents($filepath, $preload);
			}
		}
		if (isset($_POST['postload']) && isset($title))
		{
			$postload = trim(html_entity_decode($_POST['postload']));
			if (!isset($error))
			{
				$filepath = $suit->templates->checkFile($title, 'postload');
				file_put_contents($filepath, $postload);
			}
		}
		if (!isset($error))
		{
	        	header('refresh: 0; url=./admin_templates.php?cmd=added');
	        	exit;
		}
	}


	if (isset($_POST['edit']) && $_GET['cmd'] == 'edit')
	{
		if (isset($_POST['title']) && isset($_POST['template']))
		{
			$title = html_entity_decode($suit->db->escape($_POST['title']));
			$template = intval($_POST['template']);
			$templatecheck_options = array
			(
	  			'where' => 'id = \'' . $template . '\''
			);
			$templatecheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
			if ($templatecheck)
			{
				while ($row = mysql_fetch_assoc($templatecheck))
				{
					$templatecheck2_options = array('where' => 'title = \'' . $title . '\'');
					$templatecheck2 = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck2_options);
					if (!$templatecheck2 || ($title == $row['title']))
					{
						if ($title == '')
						{
							$error = 'missingtitle';
						}
					}
					else
					{
						$error = 'duplicatetitle';
					}
				}
			}
			else
			{
				$suit->templates->getTemplate('badrequest');
			}
		}
		else
		{
			$suit->templates->getTemplate('badrequest');
		}
		
		if (isset($_POST['content']) && isset($title))
		{
			$content = html_entity_decode($suit->db->escape($_POST['content']));
			if (!isset($error))
			{
				$query = 'UPDATE ' . TBL_PREFIX . 'templates SET content = \'' . $content . '\', title = \'' . $title . '\' WHERE id = \'' . $template . '\'';	
				mysql_query($query);
			}
		}

		if (isset($_POST['preload']) && isset($title))
		{
			$preload = trim(html_entity_decode($_POST['preload']));
			if (!isset($error))
			{
				$filepath = $suit->templates->checkFile($title, 'preload');
				file_put_contents($filepath, $preload);
			}
		}
		if (isset($_POST['postload']) && isset($title))
		{
			$postload = trim(html_entity_decode($_POST['postload']));
			if (!isset($error))
			{
				$filepath = $suit->templates->checkFile($title, 'postload');
				file_put_contents($filepath, $postload);
			}
		}
		
		if (!isset($error))
		{
			header('refresh: 0; url=./admin_templates.php?cmd=edited');
           		exit;
		}
	}
	if (isset($_POST['delete']) && $_GET['cmd'] == 'delete')

	{
		if (isset($_POST['template']))
		{

			$template = intval($_POST['template']);
			$templatecheck_options = array('where' => 'id = \'' . $template . '\'');
			$templatecheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
			if ($templatecheck)
			{
				while ($row = mysql_fetch_assoc($templatecheck))
				{
					$query = 'DELETE FROM ' . TBL_PREFIX . 'templates WHERE id = \'' . $template . '\'';
					mysql_query($query);
					$filepath = $suit->templates->checkFile($row['title'], 'preload');
					unlink($filepath);
					$filepath = $suit->templates->checkFile($row['title'], 'postload');
					unlink($filepath);
				}
			}
			else
			{
				$suit->templates->getTemplate('badrequest');
			}
		}
		else
		{
			$suit->templates->getTemplate('badrequest');
		}
	        header('refresh: 0; url=./admin_templates.php?cmd=deleted');
	        exit;
	}
}
?>
