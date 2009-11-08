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
	if (isset($_POST['addset']) && $_GET['cmd'] == 'setadded')
	{
		$title = html_entity_decode($suit->mysql->escape($_POST['title']));
		
		$templatecheck_options = array('where' => 'title = \'' . $title . '\'');
		
		$templatecheck = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if (!$templatecheck)
		{		
			if (!empty($title))
			{
				$query = 'INSERT INTO ' . TBL_PREFIX . 'templates VALUES (\'\', \'' . $title . '\', \'\', \'0\', \'0\')';
				mysql_query($query);
				$filepath = PATH_HOME . 'dynamic/' . $title . '';
				$filepath2 = PATH_HOME . 'cache/templates/' . $title . '';
				//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
				//A cache directory will also be created in addition.
				if ((!is_dir($filepath)) && (!is_dir($filepath2)))
				{
					mkdir($filepath); //Dynamic
					mkdir($filepath2); //Cache
					chmod($filepath, 0777);
					chmod($filepath, 0777);
				}
			}
			else
			{
				header('refresh: 0; url=./admin_templates.php?cmd=addset&error=missingtitle');
				exit;
			}
		}
		else
		{
			header('refresh: 0; url=./admin_templates.php?cmd=addset&error=duplicatetitle');
			exit;
		}
	}
	
	if (isset($_POST['renameset']) && $_GET['cmd'] == 'setrenamed')
	{
		$set = $suit->mysql->escape($_POST['set']);
		$title = html_entity_decode($suit->mysql->escape($_POST['title']));

		$templatecheck_options = array('where' => 'id = \'' . $set . '\'');
		
		$templatecheck = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if ($templatecheck)
		{
			while ($row = mysql_fetch_assoc($templatecheck))
			{
				$templatecheck2_options = array('where' => 'title = \'' . $title . '\'');
				
				$templatecheck2 = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $templatecheck2_options);
				
				if (!$templatecheck2 || ($title == $row['title']))
				{		
					if ($title != '')
					{
						$filepath = PATH_HOME . 'dynamic/';
						$filepath2 = PATH_HOME  . 'cache/templates/';
						//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
						if ((is_dir($filepath . $row['title'])) && (is_dir($filepath2 . $row['title'])))
						{
							rename($filepath . $row['title'], $filepath . $title);
							rename($filepath2 . $row['title'], $filepath . $title);				
						}
						else
						{
							mkdir($filepath . $title);
							mkdir($filepath2 . $title);
							chmod($filepath . $title, 0777);
							chmod($filepath2 . $title, 0777);
						}

						$query = 'UPDATE ' . TBL_PREFIX . 'templates SET title = \'' . $title . '\' WHERE id = \'' . $set . '\'';
						mysql_query($query);
					}
					else
					{
						header('refresh: 0; url=./admin_templates.php?cmd=renameset&set=' . $set . '&error=missingtitle');
						exit;
					}
				}
				else
				{
					header('refresh: 0; url=./admin_templates.php?cmd=renameset&set=' . $set . '&error=duplicatetitle');
					exit;
				}
			}
		}
		else
		{
			header('refresh: 0; url=./admin_templates.php');
			exit;
		}
	}
	if (isset($_POST['deleteset']) && $_GET['cmd'] == 'setdeleted')
	{
		$set = $suit->mysql->escape($_POST['set']);
		
		$templatecheck_options = array('where' => 'id = \'' . $set . '\'');
		
		$templatecheck = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if ($templatecheck)
		{
			while ($row = mysql_fetch_assoc($templatecheck))
			{
				$query = 'DELETE FROM ' . TBL_PREFIX . 'templates WHERE parent = \'' . $set . '\'';
				mysql_query($query);
				
				$query = 'DELETE FROM ' . TBL_PREFIX . 'templates WHERE id = \'' . $set . '\'';
				mysql_query($query);
				
				$filepath = PATH_HOME . 'dynamic/' . $row['title'] . '';
				$filepath2 = PATH_HOME . 'cache/templates/' . $row['title'] . '';
				//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
				if (is_dir($filepath))
				{
					$lcontent = $suit->language->getLanguage('cantopenfile');
					
					//Start off with opening up a handler.
					$path = opendir($filepath);
					//If the handler succesfully opened, we may continue.
					if ($path)
					{
						//Form a while() loop that ensures that it only runs until the directory can no longer be read--non-existant, basically.
						while (($file = readdir($path)) !== false)
						{
							//Is the element requested a sub-directory?
							if (is_dir($filepath . '/' . $file)) 
							{
								//If the element lacks a . or a .., then we can assume it is a directory. In this case, delete it.
								if ($file !== '.' && $file !== '..')
								{
									rmdir($filepath . '/' . $file);
								}
							}
							else 
							{
								//It doesn't seem like the case, so we will delete it.
								unlink($filepath . '/' . $file);
							}
						}
						//We're done working, now time to close the handler and proceed to the final step.
						closedir($path);
					}
					else
					{
						die($lcontent);
					}
					//The directory is empty, so now we may safely delete it with rmdir()
					rmdir($filepath);
				}
				
				//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
				if (is_dir($filepath2))
				{
					$lcontent = $suit->language->getLanguage('cantopenfile');
					
					//Start off with opening up a handler.
					$path2 = opendir($filepath2);
					//If the handler succesfully opened, we may continue.
					if ($path2)
					{
						//Form a while() loop that ensures that it only runs until the directory can no longer be read--non-existant, basically.
						while (($file2 = readdir($path2)) !== false)
						{
							//Is the element requested a sub-directory?
							if (is_dir($filepath2 . '/' . $file2)) 
							{
								//If the element lacks a . or a .., then we can assume it is a directory. In this case, delete it.
								if ($file2 !== '.' && $file2 !== '..')
								{
									rmdir($filepath2 . '/' . $file2);
								}
							}
							else 
							{
								//It doesn't seem like the case, so we will delete it.
								unlink($filepath2 . '/' . $file2);
							}
						}
						//We're done working, now time to close the handler and proceed to the final step.
						closedir($path2);
					}
					else
					{
						die($lcontent);
					}
					//The directory is empty, so now we may safely delete it with rmdir()
					rmdir($filepath2);
				}
			}
		}
		else
		{
			header('refresh: 0; url=./admin_templates.php');
			exit;
		}
	}
	if (isset($_POST['cloneset']) && $_GET['cmd'] == 'setcloned')
	{
		$set = $suit->mysql->escape($_POST['set']);
		$title = html_entity_decode($suit->mysql->escape($_POST['title']));

		$templatecheck_options = array('where' => 'title = \'' . $title . '\'');
		
		$templatecheck = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if (!$templatecheck)
		{		
			if ($title != '')
			{
				$query = 'INSERT INTO ' . TBL_PREFIX . 'templates VALUES (\'\', \'' . $title . '\', \'\', \'0\', \'0\')';
				mysql_query($query);
		
				$templatecheck_options = array(
				'where' => 'id = \'' . $set . '\''
				);
				
				$templatecheck = $suit->mysql->select(
				TBL_PREFIX . 'templates',
				'*',
				$templatecheck_options
				);
				
				if ($templatecheck)
				{
					while ($row = mysql_fetch_assoc($templatecheck))
					{
						$templatecheck2_options = array('where' => 'title = \'' . $title . '\'');
						$templatecheck2 = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $templatecheck2_options);
						
						if ($templatecheck2)
						{
							while ($row2 = mysql_fetch_assoc($templatecheck2))
							{
								$templatecheck3_options = array('where' => 'parent = \'' . $row['id'] . '\'');
								
								$templatecheck3 = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $templatecheck3_options);
								
								if ($templatecheck3)
								{
									while ($row3 = mysql_fetch_assoc($templatecheck3))
									{
										$query = 'INSERT INTO ' . TBL_PREFIX . 'templates VALUES (\'\', \'' . $row3['title'] . '\', \'' . $row3['content'] . '\', \'' . $row3['dynamic'] . '\', \'' . $row2['id'] . '\')';
										mysql_query($query);
									}
								}
							}
						}
						
						$filepath = PATH_HOME . 'dynamic';
						$filepath2 = PATH_HOME . 'cache';
						//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
						//We'll begin working with the dynamic directory.
						if (is_dir($filepath . '/' . $row['title']))
						{
							$lcontent = $suit->language->getLanguage('cantopenfile', ID, PASS);
		
							mkdir($filepath . '/' . $title);
							chmod($filepath . '/' . $title, 0777);
							//Start off with opening up a handler.
							$path = opendir($filepath . '/' . $row['title']);
							//If the handler succesfully opened, we may continue.
							if ($path)
							{
								//Form a while() loop that ensures that it only runs until the directory can no longer be read--non-existant, basically.
								while (($file = readdir($path)) !== false)
								{
									//Is the element requested a sub-directory?
									if (is_dir($filepath . '/' . $file)) 
									{
										//If the element lacks a . or a .., then we can assume it is a directory. In this case, delete it.
										if ($file !== '.' && $file !== '..')
										{
											mkdir($filepath . '/' . $title . '/' . $file);
											chmod($filepath . '/' . $title . '/' . $file, 0777);
										}
									}
									else 
									{
										//It doesn't seem like the case, so we will delete it.
										copy($filepath . '/' . $row['title'] . '/' . $file, $filepath . '/' . $title . '/' . $file);
										chmod($filepath . '/' . $title . '/' . $file, 0666); //CHMOD the file to be writable by our script.
									}
								}
								//We're done working, now time to close the handler and proceed to the final step.
								closedir($path);
							}
							else
							{
								die($lcontent);
							}
						}
						//Let's work with the cache directory now.
						if (is_dir($filepath2 . '/' . $row['title']))
						{
							$lcontent = $suit->language->getLanguage('cantopenfile', ID, PASS);
							
							mkdir($filepath2 . '/' . $title);
							chmod($filepath2 . '/' . $title, 0777);
							//Start off with opening up a handler.
							$path2 = opendir($filepath2 . '/' . $row['title']);
							//If the handler succesfully opened, we may continue.
							if ($path2)
							{
								//Form a while() loop that ensures that it only runs until the directory can no longer be read--non-existant, basically.
								while (($file2 = readdir($path2)) !== false)
								{
									//Is the element requested a sub-directory?
									if (is_dir($filepath2 . '/' . $file2)) 
									{
										//If the element lacks a . or a .., then we can assume it is a directory. In this case, delete it.
										if ($file2 !== '.' && $file2 !== '..')
										{
											mkdir($filepath2 . '/' . $title . '/' . $file2);
											chmod($filepath2 . '/' . $title . '/' . $file2, 0777);
										}
									}
									else 
									{
										//It doesn't seem like the case, so we will delete it.
										copy($filepath2 . '/' . $row['title'] . '/' . $file2, $filepath2 . '/' . $title . '/' . $file2);
										chmod($filepath2 . '/' . $title . '/' . $file2, 0666); //CHMOD the file to be writable by our script.
									}
								}
								//We're done working, now time to close the handler and proceed to the final step.
								closedir($path2);
							}
							else
							{
								die($lcontent);
							}
						}
					}
				}
			}
			else
			{
				header('refresh: 0; url=./admin_templates.php?cmd=cloneset&set=' . $set . '&error=missingtitle');
				exit;
			}
		}
		else
		{
			header('refresh: 0; url=./admin_templates.php?cmd=cloneset&set=' . $set . '&error=duplicatetitle');
			exit;
		}
	}
	if (isset($_POST['add']) && $_GET['cmd'] == 'addtemplate')
	{
		$set = $suit->mysql->escape($_POST['set']);
		$dynamic = $suit->mysql->escape($_POST['dynamic']);
		$content = html_entity_decode($suit->mysql->escape($_POST['content']));
		$title = html_entity_decode($suit->mysql->escape($_POST['title']));
		
		$templatecheck_options = array('where' => 'title = \'' . $title . '\' AND parent = \'' . $set . '\'');
		
		$templatecheck = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if (!$templatecheck)
		{		
			if ($title != '')
			{
				$query = 'INSERT INTO ' . TBL_PREFIX . 'templates VALUES (\'\', \'' . $title . '\', \'' . $content . '\', \'' . $dynamic . '\', \'' . $set . '\')';
				mysql_query($query);
		
				if ($dynamic == '1')
				{
					//Query the database for the title of the set, which will be the folder from where the dynamic templates are grabbed.
					$templatefolder_options = array('where' => 'id = \'' . $set . '\'');
					$templatefolder = $suit->mysql->select('' . TBL_PREFIX . 'templates', '*', $templatefolder_options);
					if ($templatefolder)
					{
						while ($row = mysql_fetch_assoc($templatefolder))
						{
							$filepath = PATH_HOME . 'dynamic/' . $row['title'] . '';
							//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
							if (!is_dir($filepath))
							{
								mkdir($filepath);
								mkdir($filepath2);
								chmod($filepath, 0777);
								chmod($filepath2, 0777);
							}
							//Concatanate the current path with to form the files.
							$filepath = $filepath . '/' . $title . '.php';
							//Looks like it doesn't. Let's create the missing file, and report the error to the user.
							$lcontent = $suit->language->getLanguage('cantopenfile', ID, PASS);
							$create_dynamic = fopen($filepath, 'w') or die($lcontent);
							fclose($create_dynamic);
							//Move on to the CHMODing.
							chmod($filepath, 0666); //CHMOD the file to be writable by our script.
						}
					}
				}
			}
			else
			{
				header('refresh: 0; url=./admin_templates.php?cmd=add&set=' . $set . '&dynamic=' . $dynamic . '&error=missingtitle');
				exit;
			}
		}
		else
		{
			header('refresh: 0; url=./admin_templates.php?cmd=add&set=' . $set . '&dynamic=' . $dynamic . '&error=duplicatetitle');
			exit;
		}
	}
	if (isset($_POST['edit']) && $_GET['cmd'] == 'edittemplate')
	{
		$set = $suit->mysql->escape($_POST['set']);
		$template = $suit->mysql->escape($_POST['template']);
		$dynamic = $suit->mysql->escape($_POST['dynamic']);
		$content = html_entity_decode($suit->mysql->escape($_POST['content']));
		$title = html_entity_decode($suit->mysql->escape($_POST['title']));
		
		$templatecheck_options = array(
		'where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\''
		);
		
		$templatecheck = $suit->mysql->select(
		TBL_PREFIX . 'templates',
		'*',
		$templatecheck_options
		);
		
		if ($templatecheck)
		{
			while ($row = mysql_fetch_assoc($templatecheck))
			{
				$templatecheck2_options = array(
				'where' => 'title = \'' . $title . '\''
				);
				
				$templatecheck2 = $suit->mysql->select(
				TBL_PREFIX . 'templates',
				'*',
				$templatecheck2_options
				);
				
				if (!$templatecheck2 || ($title == $row['title']))
				{
					if ($title != '')
					{
						$query = 'UPDATE ' . TBL_PREFIX . 'templates SET content = \'' . $content . '\', title = \'' . $title . '\', dynamic = \'' . $dynamic . '\' WHERE id = \'' . $template . '\'';
						mysql_query($query);
						//Query the database for the title of the set, which will be the folder from where the dynamic templates are grabbed.
						$templatefolder_options = array('where' => 'id = \'' . $set . '\'');
						$templatefolder = $suit->mysql->select('' . TBL_PREFIX . 'templates', '*', $templatefolder_options);
						if ($templatefolder)
						{
							while ($row2 = mysql_fetch_assoc($templatefolder))
							{
								if ($dynamic == '1')
								{
									$filepath = PATH_HOME . 'dynamic/' . $row2['title'] . '';
									//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
									if (!is_dir($filepath))
									{
										mkdir($filepath);
										chmod($filepath, 0777);
									}
									if (file_exists($filepath . '/' . $row['title'] . '.php'))
									{
										$lcontent = $suit->language->getLanguage('cantopenfile', ID, PASS);
										rename($filepath . '/' . $row['title'] . '.php', $filepath . '/' . $title . '.php') or die($lcontent);
									}
									else
									{
										$filepath = $filepath . '/' . $title . '.php';
										//Looks like it doesn't. Let's create the missing file, and report the error to the user.
										$lcontent = $suit->language->getLanguage('cantopenfile', ID, PASS);
										$create_dynamic = fopen($filepath, 'w') or die($lcontent);
										fclose($create_dynamic);
										chmod($filepath, 0666); //CHMOD the file to be writable by our script.
									}
								}
								else
								{
									$filepath = PATH_HOME . 'dynamic/' . $row2['title'] . '';
									//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
									if (is_dir($filepath))
									{
										//Concatanate the current path with to form the file.
										$filepath = $filepath . '/' . $row['title'] . '.php';
										
										if (file_exists($filepath))
										{
											$lcontent = $suit->language->getLanguage('cantopenfile', ID, PASS);
											unlink($filepath) or die($lcontent);
										}
									}
								}
							}
						}
					}
					else
					{
						header('refresh: 0; url=./admin_templates.php?cmd=edit&set=' . $_POST['set'] . '&template=' . $_POST['template'] . '&error=missingtitle');
						exit;
					}
				}
				else
				{
					header('refresh: 0; url=./admin_templates.php?cmd=edit&set=' . $set . '&template=' . $_POST['template'] . '&error=duplicatetitle');
					exit;
				}
			}
		}
		else
		{
			header('refresh: 0; url=./admin_templates.php');
			exit;
		}
	}
	if (isset($_POST['delete']) && $_GET['cmd'] == 'deletetemplate')
	{
		$set = $suit->mysql->escape($_POST['set']);
		$template = $suit->mysql->escape($_POST['template']);
		
		$templatecheck_options = array(
		'where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\''
		);
		
		$templatecheck = $suit->mysql->select(
		TBL_PREFIX . 'templates',
		'*',
		$templatecheck_options
		);
		
		if ($templatecheck)
		{
			while ($row = mysql_fetch_assoc($templatecheck))
			{
				$query = 'DELETE FROM ' . TBL_PREFIX . 'templates WHERE id = \'' . $template . '\' AND parent = \'' . $set . '\'';
				mysql_query($query);

				if ($row['dynamic'] == '1')
				{
					//Query the database for the title of the set, which will be the folder from where the dynamic templates are grabbed.
					$templatefolder_options = array('where' => 'id = \'' . $set . '\'');
					$templatefolder = $suit->mysql->select('' . TBL_PREFIX . 'templates', '*', $templatefolder_options);
					if ($templatefolder)
					{
						while ($row2 = mysql_fetch_assoc($templatefolder))
						{
							$filepath = PATH_HOME . 'dynamic/' . $row2['title'] . '';
							//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
							if (is_dir($filepath))
							{
								//Concatanate the current path with to form the file.
								$filepath = $filepath . '/' . $row['title'] . '.php';
								
								$lcontent = $suit->language->getLanguage('cantopenfile', ID, PASS);
								unlink($filepath) or die($lcontent);
							}
						}
					}
				}
			}
		}
		else
		{
			header('refresh: 0; url=./admin_templates.php');
			exit;
		}
	}
	if (isset($_POST['clone']) && $_GET['cmd'] == 'clonetemplate')
	{
		$set = $suit->mysql->escape($_POST['set']);
		$template = $suit->mysql->escape($_POST['template']);
		$dynamic = $suit->mysql->escape($_POST['dynamic']);
		$content = html_entity_decode($suit->mysql->escape($_POST['content']));
		$title = html_entity_decode($suit->mysql->escape($_POST['title']));

		$templatecheck_options = array(
		'where' => 'title = \'' . $title . '\' AND parent = \'' . $set . '\''
		);
		
		$templatecheck = $suit->mysql->select(
		TBL_PREFIX . 'templates',
		'*',
		$templatecheck_options
		);
		
		if (!$templatecheck)
		{
			$templatecheck_options = array(
			'where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\''
			);
			
			$templatecheck = $suit->mysql->select(
			TBL_PREFIX . 'templates',
			'*',
			$templatecheck_options
			);
			
			if ($templatecheck)
			{
				while ($row = mysql_fetch_assoc($templatecheck))
				{		
					if ($title != '')
					{
						$query = 'INSERT INTO ' . TBL_PREFIX . 'templates VALUES (\'\', \'' . $title . '\', \'' . $content . '\', \'' . $dynamic . '\', \'' . $set . '\')';
						mysql_query($query);
				
						if ($dynamic == '1')
						{
							//Query the database for the title of the set, which will be the folder from where the dynamic templates are grabbed.
							$templatefolder_options = array('where' => 'id = \'' . $set . '\'');
							$templatefolder = $suit->mysql->select('' . TBL_PREFIX . 'templates', '*', $templatefolder_options);
							if ($templatefolder)
							{
								while ($row2 = mysql_fetch_assoc($templatefolder))
								{
									$filepath = PATH_HOME . 'dynamic/' . $row2['title'] . '';
									//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
									if (is_dir($filepath))
									{
										//Concatanate the current path with to form the file.
										$oldfilepath = $filepath . '/' . $row['title'] . '.php';
										$filepath = $filepath . '/' . $title . '.php';
										//Looks like it doesn't. Let's create the missing file, and report the error to the user.
										if (!copy($oldfilepath, $filepath))
										{
											$lcontent = $suit->language->getLanguage('cantopenfile', ID, PASS);
											die($lcontent);
										}
										chmod($filepath, 0666); //CHMOD the file to be writable by our script.
									}
								}
							}
						}
					}
					else
					{
						header('refresh: 0; url=./admin_templates.php?cmd=add&set=' . $_POST['set'] . '&dynamic=' . $_POST['dynamic'] . '&error=missingtitle');
						exit;
					}
				}
			}
		}
		else
		{
			header('refresh: 0; url=./admin_templates.php?cmd=add&set=' . $_POST['set'] . '&dynamic=' . $_POST['dynamic'] . '&error=duplicatetitle');
			exit;
		}
	}
	if (isset($_POST['cache']) && $_GET['cmd'] == 'cache')
	{
		$set = $suit->mysql->escape($_POST['set']);
		
		$templatecheck_options = array('where' => 'id = \'' . $set . '\'');
		
		$templatecheck = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if ($templatecheck)
		{
			while ($row = mysql_fetch_assoc($templatecheck))
			{	
				$filepath = PATH_HOME . 'cache/templates/' . $row['title'] . '';
				//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
				if (!is_dir($filepath))
				{
					mkdir($filepath);
					chmod($filepath, 0777);
				}
				$lcontent = $suit->language->getLanguage('cantopenfile');
				
				//Start off with opening up a handler.
				$path = opendir($filepath);
				//If the handler succesfully opened, we may continue.
				if ($path)
				{
					//Form a while() loop that ensures that it only runs until the directory can no longer be read--non-existant, basically.
					while (($file = readdir($path)) !== false)
					{
						//Is the element requested a sub-directory?
						if (is_dir($filepath . '/' . $file)) 
						{
							//If the element lacks a . or a .., then we can assume it is a directory. In this case, delete it.
							if ($file !== '.' && $file !== '..')
							{
								rmdir($filepath . '/' . $file);
							}
						}
						else 
						{
							//It doesn't seem like the case, so we will delete it.
							unlink($filepath . '/' . $file);
						}
					}
					//We're done working, now time to close the handler and proceed to the final step.
					closedir($path);
				}
				else
				{
					die($lcontent);
				}
				//The directory is empty, so now we may safely delete it with rmdir()
			}
		}
		else
		{
			header('refresh: 0; url=./admin_templates.php');
			exit;
		}
	}
}
?>
