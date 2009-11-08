<?php

if ($suit->loggedIn() == 2)
{	
	/**
	Adds a directory
	**@param string Directory to create.
	**/
	function adddir($dir)
	{
		if ((!is_dir($dir)))
		{
			mkdir($dir); //Files
			chmod($dir, 777);
		}
	}
	
	/**
	Renames a directory
	**@param string Old Directory
	**@param string New Directory
	**@param string SUIT Reference
	**/
	function renamedir($old, $new, &$suit)
	{
		//We don't want the old directory to be the same directory we'll be renaming too,
		if ($old != $new)
		{
			//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
			if (is_dir($old))
			{
				//Adds the new directory.
				adddir($new);
				//It helps to first set the language string for filehandler errors.
				$lcontent = $suit->languages->getLanguage('cantopenfile');
				//Start off with opening up a handler.
				$path = opendir($old);
				//If the handler succesfully opened, we may continue.
				if ($path)
				{
					//Form a while() loop that ensures that it only runs until the directory can no longer be read--non-existant, basically.
					while (($file = readdir($path)) !== false)
					{
						//Is the element requested a sub-directory?
						if (is_dir($old . '/' . $file)) 
						{
							//If the element lacks a . or a .., then we can assume it is a directory. In this case, copy anything that isn't either of those.
							if ($file !== '.' && $file !== '..')
							{
								copy($old . '/' . $file, $new . '/' . $file);
							}
						}
						else 
						{
							//It doesn't seem like the case, so we will delete it.
							copy($old . '/' . $file, $new . '/' . $file);
						}
					}
					//Now let's delete the old directory.
					deletedir($old, $suit);
				}
			}
			else
			{
				//Just add the new dir if it doesn't exist.
				adddir($new);
			}
		}
	}
	
	/**
	Deletes a directory
	**@param string Directory
	**@param string SUIT Reference
	**/
	function deletedir($dir, &$suit)
	{
		//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
		if (is_dir($dir))
		{
			$lcontent = $suit->languages->getLanguage('cantopenfile');
			
			//Start off with opening up a handler.
			$path = opendir($dir);
			//If the handler succesfully opened, we may continue.
			if ($path)
			{
				//Form a while() loop that ensures that it only runs until the directory can no longer be read--non-existant, basically.
				while (($file = readdir($path)) !== false)
				{
					//Is the element requested a sub-directory?
					if (is_dir($dir . '/' . $file)) 
					{
						//If the element lacks a . or a .., then we can assume it is a directory. In this case, delete it.
						if ($file !== '.' && $file !== '..')
						{
							rmdir($dir . '/' . $file);
						}
					}
					else 
					{
						//It doesn't seem like the case, so we will delete it.
						unlink($dir . '/' . $file);
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
			rmdir($dir);
		}
	}
	
	/**
	Clones a directory, along with all of the files within it.
	**@param string Old Directory
	**@param string New Directory
	**@param string SUIT Reference
	**/
	function clonedir($from, $to, &$suit)
	{
		adddir($to);
		//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
		//We'll begin working with the files directory.
		if (is_dir($from))
		{
			$lcontent = $suit->languages->getLanguage('cantopenfile');

			//Start off with opening up a handler.
			$path = opendir($from);
			//If the handler succesfully opened, we may continue.
			if ($path)
			{
				//Form a while() loop that ensures that it only runs until the directory can no longer be read--non-existant, basically.
				while (($file = readdir($path)) !== false)
				{
					//Is the element requested a sub-directory?
					if (is_dir($from . '/' . $file)) 
					{
						//If the element lacks a . or a .., then we can assume it is a directory. In this case, delete it.
						if ($file !== '.' && $file !== '..')
						{
							adddir($to . '/' . $title . '/' . $file);
						}
					}
					else 
					{
						//It doesn't seem like the case, so we will delete it.
						copy($from . '/' . $file, $to . '/' . $file);
						chmod($to . '/' . $file, 0666); //CHMOD the file to be writable by our script.
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
	}
	if (isset($_POST['addset']) && $_GET['cmd'] == 'setadded')
	{
		$title = html_entity_decode($suit->db->escape($_POST['title']));
		
		$templatecheck_options = array('where' => 'title = \'' . $title . '\' AND parent = \'0\'');
		
		$templatecheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if (!$templatecheck)
		{		
			if (!empty($title))
			{
				$query = 'INSERT INTO ' . TBL_PREFIX . 'templates VALUES (\'\', \'' . $title . '\', \'\', \'0\')';
				mysql_query($query);
				$filepath = PATH_HOME . '/files/' . $title;
				$filepath2 = PATH_HOME . '/cache/templates/' . $title;
				adddir($filepath);
				adddir($filepath);
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
		$set = intval($_POST['set']);
		$title = html_entity_decode($suit->db->escape($_POST['title']));
		
		$templatecheck_options = array('where' => 'id = \'' . $set . '\'');
		
		$templatecheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if ($templatecheck)
		{
			while ($row = mysql_fetch_assoc($templatecheck))
			{
				if ($row['id'] != 1 && $row['id'] != 2)
				{
					if ($title != $row['title'])
					{
						$templatecheck2_options = array('where' => 'title = \'' . $title . '\'');
						
						$templatecheck2 = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck2_options);
						
						if (!$templatecheck2 || ($title == $row['title']))
						{
							if ($title != '')
							{
								$filepath = PATH_HOME . '/files/';
								$filepath2 = PATH_HOME  . 'cache/templates/';
								
								renamedir($filepath . $row['title'], $filepath . $title, $suit);
								renamedir($filepath2 . $row['title'], $filepath2 . $title, $suit);
								
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
		$set = intval($_POST['set']);
		
		$templatecheck_options = array('where' => 'id = \'' . $set . '\'');
		
		$templatecheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if ($templatecheck)
		{
			while ($row = mysql_fetch_assoc($templatecheck))
			{
				if ($row['id'] != 1 && $row['id'] != 2)
				{
					$query = 'DELETE FROM ' . TBL_PREFIX . 'templates WHERE parent = \'' . $set . '\'';
					mysql_query($query);
					
					$query = 'DELETE FROM ' . TBL_PREFIX . 'templates WHERE id = \'' . $set . '\'';
					mysql_query($query);
					
					$filepath = PATH_HOME . '/files/' . $row['title'] . '';
					$filepath2 = PATH_HOME . '/cache/templates/' . $row['title'] . '';
					
					deletedir($filepath, $suit);
					deletedir($filepath2, $suit);
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
		$set = $suit->db->escape($_POST['set']);
		$title = html_entity_decode($suit->db->escape($_POST['title']));

		$templatecheck_options = array('where' => 'title = \'' . $title . '\'');
		
		$templatecheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if (!$templatecheck)
		{	
			if ($title != '')
			{	
				$templatecheck_options = array('where' => 'id = \'' . $set . '\'');
				
				$templatecheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
				
				if ($templatecheck)
				{
					while ($row = mysql_fetch_assoc($templatecheck))
					{
						if ($row['id'] != 1 && $row['id'] != 2)
						{
							$query = 'INSERT INTO ' . TBL_PREFIX . 'templates VALUES (\'\', \'' . $title . '\', \'\', \'0\')';
							mysql_query($query);
							$templatecheck2_options = array('where' => 'title = \'' . $title . '\'');
							$templatecheck2 = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck2_options);
							
							if ($templatecheck2)
							{
								while ($row2 = mysql_fetch_assoc($templatecheck2))
								{
									$templatecheck3_options = array('where' => 'parent = \'' . $row['id'] . '\'');
									
									$templatecheck3 = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck3_options);
									
									if ($templatecheck3)
									{
										while ($row3 = mysql_fetch_assoc($templatecheck3))
										{
											$query = 'INSERT INTO ' . TBL_PREFIX . 'templates VALUES (\'\', \'' . $row3['title'] . '\', \'' . $row3['content'] . '\', \'' . $row2['id'] . '\')';
											mysql_query($query);
										}
									}
								}
							}
							
							$filepath = PATH_HOME . '/files';
							$filepath2 = PATH_HOME . '/cache';
							clonedir($filepath . '/' . $row['title'], $filepath . '/' . $title, $suit);
							clonedir($filepath2 . '/' . $row['title'], $filepath2 . '/' . $title, $suit);
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
		$set = $suit->db->escape($_POST['set']);
		$content = html_entity_decode($suit->db->escape($_POST['content']));
		$phpcode = html_entity_decode($_POST['phpcode']);
		$title = html_entity_decode($suit->db->escape($_POST['title']));
		
		$templatecheck_options = array('where' => 'title = \'' . $title . '\' AND parent = \'' . $set . '\'');
		
		$templatecheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if (!$templatecheck)
		{		
			if ($title != '')
			{
				$query = 'INSERT INTO ' . TBL_PREFIX . 'templates VALUES (\'\', \'' . $title . '\', \'' . $content . '\', \'' . $set . '\')';
				mysql_query($query);
				//Query the database for the title of the set, which will be the folder from where the dynamic templates are grabbed.
				$templatefolder_options = array('where' => 'id = \'' . $set . '\'');
				$templatefolder = $suit->db->select('' . TBL_PREFIX . 'templates', '*', $templatefolder_options);
				if ($templatefolder)
				{
					while ($row = mysql_fetch_assoc($templatefolder))
					{
						$filepath = PATH_HOME . '/files/' . $row['title'] . '';
						//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
						if (!is_dir($filepath))
						{
							adddir($filepath);
							adddir($filepath2);
						}
						//Concatanate the current path with to form the files.
						$filepath = $filepath . '/' . $title . '.php';
						//Looks like it doesn't. Let's create the missing file, and report the error to the user.
						$lcontent = $suit->languages->getLanguage('cantopenfile');
						$create_dynamic = fopen($filepath, 'w') or die($lcontent);
						fwrite($create_dynamic, $phpcode); //Write our code to the file.
						fclose($create_dynamic);
						//Move on to the CHMODing.
						chmod($filepath, 0666); //CHMOD the file to be writable by our script.
					}
				}
			}
			else
			{
				header('refresh: 0; url=./admin_templates.php?cmd=add&set=' . $set . '&content=' . base64_encode($_POST['content']) . '&error=missingtitle');
				exit;
			}
		}
		else
		{
			header('refresh: 0; url=./admin_templates.php?cmd=add&set=' . $set . '&content=' . base64_encode($_POST['content']) . '&error=duplicatetitle');
			exit;
		}
	}
	if (isset($_POST['edit']) && $_GET['cmd'] == 'edittemplate')
	{
		$set = $suit->db->escape($_POST['set']);
		$template = $suit->db->escape($_POST['template']);
		$content = html_entity_decode($suit->db->escape($_POST['content']));
		$phpcode = html_entity_decode($_POST['phpcode']);
		$title = html_entity_decode($suit->db->escape($_POST['title']));
		
		$templatecheck_options = array(
		'where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\''
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
					if ($title != '')
					{
						$query = 'UPDATE ' . TBL_PREFIX . 'templates SET content = \'' . $content . '\', title = \'' . $title . '\' WHERE id = \'' . $template . '\'';
						mysql_query($query);
						//Query the database for the title of the set, which will be the folder from where the file templates are grabbed.
						$templatefolder_options = array('where' => 'id = \'' . $set . '\'');
						$templatefolder = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatefolder_options);
						if ($templatefolder)
						{
							while ($row2 = mysql_fetch_assoc($templatefolder))
							{
								$filepath = PATH_HOME . '/files/' . $row2['title'] . '';
								//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
								if (!is_dir($filepath))
								{
									adddir($filepath);
								}
								
								if (file_exists($filepath . '/' . $row['title'] . '.php'))
								{
									$lcontent = $suit->languages->getLanguage('cantopenfile');
									//File editing time.
									$create_dynamic = fopen($filepath . '/' . $row['title'] . '.php', 'w');
									fwrite($create_dynamic, $phpcode);
									fclose($create_dynamic);
									rename($filepath . '/' . $row['title'] . '.php', $filepath . '/' . $title . '.php') or die($lcontent);
								}
								else
								{
									$filepath = $filepath . '/' . $title . '.php';
									//Looks like it doesn't. Let's create the missing file, and report the error to the user.
									$lcontent = $suit->languages->getLanguage('cantopenfile');
									//File editing time.
									$create_dynamic = fopen($filepath . '/' . $row['title'] . '.php', 'w');
									fwrite($create_dynamic, $phpcode);
									fclose($create_dynamic);
									chmod($filepath, 0666); //CHMOD the file to be writable by our script.
								}
							}
						}
					}
					else
					{
						header('refresh: 0; url=./admin_templates.php?cmd=edit&set=' . intval($_POST['set']) . '&template=' . intval($_POST['template']) . '&content=' . base64_encode($_POST['content']) . '&error=missingtitle');
						exit;
					}
				}
				else
				{
					header('refresh: 0; url=./admin_templates.php?cmd=edit&set=' . intval($_POST['set']) . '&template=' . intval($_POST['template']) . '&content=' . base64_encode($_POST['content']) . '&error=duplicatetitle');
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
		$set = intval($_POST['set']);
		$template = intval($_POST['template']);
		
		$templatecheck_options = array('where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\'');
		
		$templatecheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if ($templatecheck)
		{
			while ($row = mysql_fetch_assoc($templatecheck))
			{
				$query = 'DELETE FROM ' . TBL_PREFIX . 'templates WHERE id = \'' . $template . '\' AND parent = \'' . $set . '\'';
				mysql_query($query);
				//Query the database for the title of the set, which will be the folder from where the dynamic templates are grabbed.
				$templatefolder_options = array('where' => 'id = \'' . $set . '\'');
				$templatefolder = $suit->db->select('' . TBL_PREFIX . 'templates', '*', $templatefolder_options);
				if ($templatefolder)
				{
					while ($row2 = mysql_fetch_assoc($templatefolder))
					{
						$filepath = PATH_HOME . '/files/' . $row2['title'] . '';
						//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
						if (is_dir($filepath))
						{
							//Concatanate the current path with to form the file.
							$filepath = $filepath . '/' . $row['title'] . '.php';
							
							$lcontent = $suit->languages->getLanguage('cantopenfile');
							if (file_exists($filepath))
							{
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
		$set = $suit->db->escape($_POST['set']);
		$template = $suit->db->escape($_POST['template']);
		$content = html_entity_decode($suit->db->escape($_POST['content']));
		$title = html_entity_decode($suit->db->escape($_POST['title']));

		$templatecheck_options = array('where' => 'title = \'' . $title . '\' AND parent = \'' . $set . '\'');
		
		$templatecheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
		
		if (!$templatecheck)
		{
			$templatecheck_options = array(
			'where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\''
			);
			
			$templatecheck = $suit->db->select(
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
						$query = 'INSERT INTO ' . TBL_PREFIX . 'templates VALUES (\'\', \'' . $title . '\', \'' . $content . '\', \'' . $set . '\')';
						mysql_query($query);

						//Query the database for the title of the set, which will be the folder from where the dynamic templates are grabbed.
						$templatefolder_options = array('where' => 'id = \'' . $set . '\'');
						$templatefolder = $suit->db->select('' . TBL_PREFIX . 'templates', '*', $templatefolder_options);
						if ($templatefolder)
						{
							while ($row2 = mysql_fetch_assoc($templatefolder))
							{
								$filepath = PATH_HOME . '/files/' . $row2['title'] . '';
								//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
								if (is_dir($filepath))
								{
									//Concatanate the current path with to form the file.
									$oldfilepath = $filepath . '/' . $row['title'] . '.php';
									$filepath = $filepath . '/' . $title . '.php';
									//Looks like it doesn't. Let's create the missing file, and report the error to the user.
									if (!copy($oldfilepath, $filepath))
									{
										$lcontent = $suit->languages->getLanguage('cantopenfile');
										die($lcontent);
									}
									chmod($filepath, 0666); //CHMOD the file to be writable by our script.
								}
							}
						}
					}
					else
					{
						header('refresh: 0; url=./admin_templates.php?cmd=clone&set=' . intval($_POST['set']) . '&template=' . intval($_POST['template']) . '&content=' . base64_encode($_POST['content']) . '&error=missingtitle');
						exit;
					}
				}
			}
		}
		else
		{
			header('refresh: 0; url=./admin_templates.php?cmd=clone&set=' . intval($_POST['set']) . '&template=' . intval($_POST['template']) . '&content=' . base64_encode($_POST['content']) . '&error=duplicatetitle');
			exit;
		}
	}
	if (isset($_POST['cache']) && $_GET['cmd'] == 'cache')
	{	
		$filepath = PATH_HOME . '/cache/templates/';
		//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
		if (!is_dir($filepath))
		{
			adddir($filepath);
		}
		$lcontent = $suit->languages->getLanguage('cantopenfile');
		
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
	}

	//It's always safer to set a variable before use.
	$list = '';
	//The valid list of pages that a user can pass to the $_GET['cmd'] variable.
	$pages = array('edittemplate', 'addtemplate', 'deletetemplate', 'clonetemplate', 'addset', 'cache');
	
	if (isset($_GET['cmd']) && in_array($_GET['cmd'], $pages))
	{
		switch ($_GET['cmd'])
		{
			case 'addtemplate':
				$post = 'add';
				$template = 'admin_templates_tadded';
				$language = 'addedsuccessfully';
				break;

			case 'edittemplate':
				$post = 'edit';
				$template = 'admin_templates_tedited';
				$language = 'editedsuccessfully';
				break;

			case 'deletetemplate':
				$post = 'delete';
				$template = 'admin_templates_tdeleted';
				$language = 'deletedsuccessfully';
				break;

			case 'clonetemplate':
				$post = 'clone';
				$template = 'admin_templates_tcloned';
				$language = 'clonedsuccessfully';
				break;
			case 'cache':
				$post = 'cache';
				$template = 'admin_templates_cache';
				$language = 'clearedsuccessfully';
				break;
		}

		if ($_GET['cmd'] != 'addset')
		{
			if (isset($_POST[$post]))
			{
				header('refresh: 0; url=./admin_templates.php?cmd=' . $_GET['cmd'] . '&submitted=1');
				exit;
			}
			else
			{
				$lcontent = $suit->languages->getLanguage($language);
				$content_vars = $suit->templates->getTemplate($template, $chains);
				$content = $content_vars['output'];
				$success_vars = $suit->templates->getTemplate('success', $chains);
				$success = $success_vars['output'];
				$success = str_replace('<1>', $lcontent, $success);
				$list .= str_replace('<1>', $success, $content);
			}
		}
		else
		{
			/**
			Addition of template sets.
			**/
			//Load the template for adding the set.
			$admin_templates_sadd_vars = $suit->templates->getTemplate('admin_templates_sadd', $chains);
			$list .= $admin_templates_sadd_vars['output'];
			//Check for any errors.
			if (isset($_GET['error']))
			{
				//We'll use a switch() statement to determine what action to take for these errors.
				//When we have our error, we'll load the language string for it.
				switch ($_GET['error'])
				{
					case 'missingtitle':
						$lcontent = $suit->languages->getLanguage('missingtitle'); break;
					case 'duplicatetitle':
						$lcontent = $suit->languages->getLanguage('duplicatetitle'); break;
					default:
						$lcontent = $suit->languages->getLanguage('undefinederror'); break;
				}
				//Replace the value of $list with what we concluded in the error switch() statement.
				$list = str_replace('<1>', $lcontent, $list);
			}
			else
			{
				$list = str_replace('<1>', '', $list);
			}
		}
	}
	else
	{
		if (!isset($_GET['set']))
		{
			$pages = array('setadded', 'setrenamed', 'setdeleted', 'setcloned');
			
			if (isset($_GET['cmd']) && in_array($_GET['cmd'], $pages))
			{
				switch ($_GET['cmd'])
				{
					case 'setadded':
						$post = 'addset';
						$template = 'admin_templates_sadded';
						$language = 'addedsuccessfully';
						break;

					case 'setrenamed':
						$post = 'renameset';
						$template = 'admin_templates_srenamed';
						$language = 'renamedsuccessfully';
						break;

					case 'setdeleted':
						$post = 'deleteset';
						$template = 'admin_templates_sdeleted';
						$language = 'deletedsuccessfully';
						break;

					case 'setcloned':
						$post = 'cloneset';
						$template = 'admin_templates_scloned';
						$language = 'clonedsuccessfully';
						break;
				}

				if (isset($_POST[$post]))
				{
					header('refresh: 0; url=./admin_templates.php?cmd=' . $_GET['cmd'] . '&submitted=1');
					exit;
				}
				else
				{
					$lcontent = $suit->languages->getLanguage($language);
					$content_vars = $suit->templates->getTemplate($template, $chains);
					$content = $content_vars['output'];
					$success_vars = $suit->templates->getTemplate('success', $chains);
					$success = $success_vars['output'];
					$success = str_replace('<1>', $lcontent, $success);
					$message = str_replace('<1>', $success, $content);
				}
			}
			else
			{
				$message = '';
			}
			
			$admin_templates_sselect_skeleton_vars = $suit->templates->getTemplate('admin_templates_sselect_skeleton', $chains);
			$admin_templates_sselect_skeleton = $admin_templates_sselect_skeleton_vars['output'];
			$page = $admin_templates_sselect_skeleton;
			
			$parentget_options = array(
			'where' => 'parent = \'0\'',
			);
			
			$parentget = $suit->db->select(TBL_PREFIX . 'templates', '*', $parentget_options);
			
			if ($parentget)
			{
				while ($row = mysql_fetch_assoc($parentget))
				{
					$admin_templates_sselect_vars = $suit->templates->getTemplate('admin_templates_sselect', $chains);
					$admin_templates_sselect = $admin_templates_sselect_vars['output'];
					$list .= $admin_templates_sselect;
					$adminthemes_options = array('where' => 'templateset = \'' . $row['id'] . '\'');
					
					$adminthemes = $suit->db->select(TBL_PREFIX . 'themes', '*', $adminthemes_options);
					
					if ($adminthemes)
					{
						while ($row2 = mysql_fetch_assoc($adminthemes))
						{
							$admin_templates_themes_vars = $suit->templates->getTemplate('admin_templates_themes', $chains);
							$admin_templates_themes = $admin_templates_themes_vars['output'];
                                                        $admin_templates_themes = str_replace('<1>', htmlspecialchars($row2['title']), $admin_templates_themes);
							$themes .= $admin_templates_themes;
						}
					}
					else
					{
						$themes = '';
					}
					$array = Array
					(
						array('<1>', $row['id']),
						array('<2>', htmlspecialchars($row['title'])),
						array('<3>', $themes)
					);
					$list = $suit->templates->replace($list, $array);
				}
			}
			$array = array
			(
				array('<1>', $message),
				array('<2>', $list)
			);
			$page = $suit->templates->replace($page, $array);			
			$list = $page;
		}
		else
		{
			if (!isset($_GET['template']))
			{
				$pages = array('view', 'add', 'renameset', 'deleteset', 'cloneset', 'cache');
				if (isset($_GET['cmd']) && in_array($_GET['cmd'], $pages))
				{
					if ($_GET['cmd'] == 'view')
					{
						$set = intval($_GET['set']);
						$templatesetexists_options = array('where' => 'id = \'' . $set . '\' AND parent = \'0\'');
						$templatesetexists = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatesetexists_options);
						
						if ($templatesetexists)
						{
							$page_vars = $suit->templates->getTemplate('admin_templates_tselect_skeleton', $chains);
							$page = $page_vars['output'];
							$admin_get_options = array('where' => 'parent = \'' . $set . '\'', 'orderby' => 'title');
							
							$admin_get = $suit->db->select(TBL_PREFIX . 'templates', '*', $admin_get_options);
							if ($admin_get)
							{
								while ($row = mysql_fetch_assoc($admin_get))
								{
									$admin_templates_tselect_vars = $suit->templates->getTemplate('admin_templates_tselect', $chains);
									$list .= $admin_templates_tselect_vars['output'];
									$array = array
									(
										array('<1>', htmlspecialchars($row['title'])),
										array('<2>', $set),
										array('<3>', $row['id'])
									);
									$list = $suit->templates->replace($list, $array);
								}
							}
							$array = array
							(
								array('<1>', $set),
								array('<2>', $list)
							);
							$page = $suit->templates->replace($page, $array);
							$list = $page;
						}
						else
						{
							header('refresh: 0; url=./admin_templates.php');
							exit;
						}
					}
					if ($_GET['cmd'] == 'add')
					{
						$set = $suit->db->escape($_GET['set']);
						$templatesetexists_options = array('where' => 'id = \'' . $set . '\' AND parent = \'0\'');
						$templatesetexists = $suit->db->select('' . TBL_PREFIX . 'templates', '*', $templatesetexists_options);
						
						if ($templatesetexists)
						{
							$admin_templates_tadd_vars = $suit->templates->getTemplate('admin_templates_tadd', $chains);
							$admin_templates_tadd = $admin_templates_tadd_vars['output'];
							
							$list .= $admin_templates_tadd;
							if (isset($_GET['error']))
							{
								//We'll use a switch() statement to determine what action to take for these errors.
								//When we have our error, we'll load the language string for it.
								switch ($_GET['error'])
								{
									case 'missingtitle':
										$lcontent = $suit->languages->getLanguage('missingtitle'); break;
									case 'duplicatetitle':
										$lcontent = $suit->languages->getLanguage('duplicatetitle'); break;
									default:
										$lcontent = $suit->languages->getLanguage('undefinederror'); break;
								}
								//Replace the value of $list with what we concluded in the error switch() statement.
							}
							else
							{
								$lcontent = '';
							}

							if (isset($_GET['content']))
							{
								$content = base64_decode($_GET['content']);
							}
							else
							{
								$content = '';
							}

							$array = Array
							(
								array('<1>', $lcontent),
								array('<2>', $set),
								array('<3>', htmlentities($content))
							);
							$list = $suit->templates->replace($list, $array);
						}
						else
						{
							header('refresh: 0; url=./admin_templates.php');
							exit;
						}
					}
					if ($_GET['cmd'] == 'renameset')
					{
						$set = $suit->db->escape($_GET['set']);
						$setcheck_options = array(
						'where' => 'id = \'' . $set . '\''
						);
						
						$setcheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $setcheck_options);
						
						if ($setcheck)
						{
							while ($row = mysql_fetch_assoc($setcheck))
							{
								$admin_templates_srename_vars = $suit->templates->getTemplate('admin_templates_srename', $chains);
								$admin_templates_srename = $admin_templates_srename_vars['output'];
								$list .= $admin_templates_srename;
								if (isset($_GET['error']))
								{
									//We'll use a switch() statement to determine what action to take for these errors.
									//When we have our error, we'll load the language string for it.
									switch ($_GET['error'])
									{
										case 'missingtitle':
											$lcontent = $suit->languages->getLanguage('missingtitle'); break;
										case 'duplicatetitle':
											$lcontent = $suit->languages->getLanguage('duplicatetitle'); break;
										default:
											$lcontent = $suit->languages->getLanguage('undefinederror'); break;
									}
									//Replace the value of $list with what we concluded in the error switch() statement.
								}
								else
								{
									$lcontent = '';
								}
								
								$array = Array
								(
									array('<1>', $lcontent),
									array('<2>', $_GET['set']),
									array('<3>', htmlentities($row['title']))
								);
								
								$list = $suit->templates->replace($list, $array);
							}
						}
						else
						{
							header('refresh: 0; url=./admin_templates.php');
							exit;
						}
					}
					if ($_GET['cmd'] == 'deleteset')
					{
						$set = intval($_GET['set']);
						
						$admintemplates_options = array('where' => 'id = \'' . $set . '\'');
						$admintemplates = $suit->db->select(TBL_PREFIX . 'templates', '*', $admintemplates_options);
						
						if ($admintemplates)
						{
							while ($row = mysql_fetch_assoc($admintemplates))
							{
								$admin_templates_sdelete_vars = $suit->templates->getTemplate('admin_templates_sdelete', $chains);
								$list .= $admin_templates_sdelete_vars['output'];
								$lcontent = $suit->languages->getLanguage('deleteconfirm');
								$lcontent = str_replace('<1>', $row['title'], $lcontent);
								$array = array
								(
									array('<1>', $lcontent),
									array('<2>', $set)
								);
								$list = $suit->templates->replace($list, $array);
							}
						}
						else
						{
							header('refresh: 0; url=./admin_templates.php');
							exit;
						}
					}
					if ($_GET['cmd'] == 'cloneset')
					{
						$set = intval($_GET['set']);
						$setcheck_options = array(
						'where' => 'id = \'' . $set . '\''
						);
						
						$setcheck = $suit->db->select(TBL_PREFIX . 'templates', '*', $setcheck_options);
						
						if ($setcheck)
						{
							while ($row = mysql_fetch_assoc($setcheck))
							{
								$admin_templates_sclone_vars = $suit->templates->getTemplate('admin_templates_sclone', $chains);
								$list .= $admin_templates_sclone_vars['output'];
								if (isset($_GET['error']))
								{
									//We'll use a switch() statement to determine what action to take for these errors.
									//When we have our error, we'll load the language string for it.
									switch ($_GET['error'])
									{
										case 'missingtitle':
											$lcontent = $suit->languages->getLanguage('missingtitle'); break;
										case 'duplicatetitle':
											$lcontent = $suit->languages->getLanguage('duplicatetitle'); break;
										default:
											$lcontent = $suit->languages->getLanguage('undefinederror'); break;
									}
									//Replace the value of $list with what we concluded in the error switch() statement.
								}
								$array = array
								(
									array('<1>', $lcontent),
									array('<2>', $set),
									array('<3>', htmlentities($row['title']))
								);
								$list = $suit->templates->replace($list, $array);
							}
						}
						else
						{
							header('refresh: 0; url=./admin_templates.php');
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
			else
			{
				if ($_GET['cmd'] == 'edit')
				{
					$set = intval($_GET['set']);
					$template = intval($_GET['template']);
					
					$admintemplates_options = array(
					'where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\''
					);
					
					$admintemplates = $suit->db->select(TBL_PREFIX . 'templates', '*', $admintemplates_options);
					
					if ($admintemplates)
					{
						while ($row = mysql_fetch_assoc($admintemplates))
						{
							$admintemplateset_options = array(
							'where' => 'id = \'' . $set . '\''
							);
							
							$admintemplateset = $suit->db->select(TBL_PREFIX . 'templates', '*',$admintemplateset_options);
							
							if ($admintemplateset)
							{
								while ($row2 = mysql_fetch_assoc($admintemplateset))
								{
									$admin_templates_tedit_vars = $suit->templates->getTemplate('admin_templates_tedit', $chains);
									$list .= $admin_templates_tedit_vars['output'];
									$admin_templates_code_vars = $suit->templates->getTemplate('admin_templates_code', $chains);
									$admin_templates_code = $admin_templates_code_vars['output'];
									$file = PATH_HOME . '/files/' . $row2['title'] . '/' . $row['title'] . '.php';
									if (!file_exists($file))
									{
										//The file doesn't exist. We'll go ahead and create an empty file.
										$create_dynamic = fopen($file, 'w') or die($lcontent);
										fclose($create_dynamic);
										chmod($file, 0666); //CHMOD the file to be writable by our script.
									}
									//Re-create a file handler by opening up the previous file we were playing with.
									$fh = fopen($file, 'r');
									
									//Grab the file size, and then store the contents of it inside of the $code variable.
									if (filesize($file))
									{
										$code = fread($fh, filesize($file));
									}
									else
									{
										$code = '';
									}
									//We're done; let's close the file out now.
									fclose($fh);
									$admin_templates_code = str_replace('<1>', htmlentities($code), $admin_templates_code);

									if (isset($_GET['error']))
									{
										//We'll use a switch() statement to determine what action to take for these errors.
										//When we have our error, we'll load the language string for it.
										switch ($_GET['error'])
										{
											case 'missingtitle':
												$lcontent = $suit->languages->getLanguage('missingtitle'); break;
											case 'duplicatetitle':
												$lcontent = $suit->languages->getLanguage('duplicatetitle'); break;
											default:
												$lcontent = $suit->languages->getLanguage('undefinederror'); break;
										}
										//Replace the value of $list with what we concluded in the error switch() statement.
									}
									else
									{
										$lcontent = '';
									}

									if (isset($_GET['content']))
									{
										$content = base64_decode($_GET['content']);
									}
									else
									{
										$content = $row['content'];
									}

									$array = array
									(
										array('<1>', $lcontent),
										array('<2>', $row['parent']),
										array('<3>', $row['id']),
										array('<4>', htmlentities($row['title'])),
										array('<5>', htmlentities($content)),
										array('<6>', $admin_templates_code)
									);
									$list = $suit->templates->replace($list, $array);
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
				if ($_GET['cmd'] == 'delete')
				{
					$set = $suit->db->escape($_GET['set']);
					$template = $suit->db->escape($_GET['template']);
					
					$admintemplates_options = array(
					'where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\''
					);
					
					$admintemplates = $suit->db->select(TBL_PREFIX . 'templates', '*', $admintemplates_options);
					
					if ($admintemplates)
					{
						while ($row = mysql_fetch_assoc($admintemplates))
						{
							$admin_templates_tdelete_vars = $suit->templates->getTemplate('admin_templates_tdelete', $chains);
							$list .= $admin_templates_tdelete_vars['output'];
							$lcontent = $suit->languages->getLanguage('deleteconfirm');
							$lcontent = str_replace('<1>', $row['title'], $lcontent);
							$array = array
							(
								array('<1>', $lcontent),
								array('<2>', $set),
								array('<3>', $template)
							);
							$list = $suit->templates->replace($list, $array);
						}
					}
					else
					{
						header('refresh: 0; url=./admin_templates.php');
						exit;
					}
				}
				
				if ($_GET['cmd'] == 'clone')
				{
					$set = $suit->db->escape($_GET['set']);
					$template = $suit->db->escape($_GET['template']);
					
					$admintemplates_options = array('where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\'');
					$admintemplates = $suit->db->select(TBL_PREFIX . 'templates', '*', $admintemplates_options);
					
					if ($admintemplates)
					{
						while ($row = mysql_fetch_assoc($admintemplates))
						{
							$admintemplateset_options = array('where' => 'id = \'' . $set . '\'');
							$admintemplateset = $suit->db->select(TBL_PREFIX . 'templates', '*', $admintemplateset_options);
							
							if ($admintemplateset)
							{
								while ($row2 = mysql_fetch_assoc($admintemplateset))
								{
									$admin_templates_tclone_vars = $suit->templates->getTemplate('admin_templates_tclone', $chains);
									$list .= $admin_templates_tclone_vars['output'];
									$admin_templates_code_vars = $suit->templates->getTemplate('admin_templates_code', $chains);
									$admin_templates_code = $admin_templates_code_vars['output'];
									$file = PATH_HOME . '/files/' . $row2['title'] . '/' . $row['title'] . '.php';
									if (!file_exists($file))
									{
										//The file doesn't exist. We'll go ahead and create an empty file.
										$create_dynamic = fopen($file, 'w') or die($lcontent);
										fclose($create_dynamic);
										chmod($file, 0666); //CHMOD the file to be writable by our script.
									}
									//Re-create a file handler by opening up the previous file we were playing with.
									$fh = fopen($file, 'r');
									
									//Grab the file size, and then store the contents of it inside of the $code variable.
									if (filesize($file))
									{
										$code = fread($fh, filesize($file));
									}
									else
									{
										$code = '';
									}
									//We're done; let's close the file out now.
									fclose($fh);
									$admin_templates_code = str_replace('<1>', htmlentities($code), $admin_templates_code);
									
									if (isset($_GET['error']))
									{
										//We'll use a switch() statement to determine what action to take for these errors.
										//When we have our error, we'll load the language string for it.
										switch ($_GET['error'])
										{
											case 'missingtitle':
												$lcontent = $suit->languages->getLanguage('missingtitle'); break;
											case 'duplicatetitle':
												$lcontent = $suit->languages->getLanguage('duplicatetitle'); break;
											default:
												$lcontent = $suit->languages->getLanguage('undefinederror'); break;
										}
										//Replace the value of $list with what we concluded in the error switch() statement.
									}
									else
									{
										$lcontent = '';
									}

									if (isset($_GET['content']))
									{
										$content = base64_decode($_GET['content']);
									}
									else
									{
										$content = $row['content'];
									}
									
									$array = array
									(
										array('<1>', $lcontent),
										array('<2>', $row['parent']),
										array('<3>', $row['id']),
										array('<4>', htmlentities($row['title'])),
										array('<5>', htmlentities($content)),
										array('<6>', $admin_templates_code)
									);
									$list = $suit->templates->replace($list, $array);
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
			}
		}
	}
	$output = str_replace('<1>', $list, $output);
}
else
{
	$output = str_replace('<1>', '', $output);
}
?>

