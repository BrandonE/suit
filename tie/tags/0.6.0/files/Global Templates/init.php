<?php/****@This file is part of The SUIT Framework.**@SUIT is free software: you can redistribute it and/or modify**@it under the terms of the GNU General Public License as published by**@the Free Software Foundation, either version 3 of the License, or**@(at your option) any later version.**@SUIT is distributed in the hope that it will be useful,**@but WITHOUT ANY WARRANTY; without even the implied warranty of**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the**@GNU General Public License for more details.**@You should have received a copy of the GNU General Public License**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.**/
if (isset($_GET['logout']) && $_GET['logout'] == true)
{	
	setcookie(COOKIE_PREFIX . 'id', '', time()-3600, COOKIE_PATH, COOKIE_DOMAIN);
	setcookie(COOKIE_PREFIX . 'pass', '', time()-3600, COOKIE_PATH, COOKIE_DOMAIN);
	header('refresh: 0; url=' . basename($_SERVER['SCRIPT_NAME']));
	exit;
}

//Theme Switcher.
//Only logged in users may switch themes.
if (isset($_GET['theme']) && $suit->loggedIn() >= 1)
{
	$theme = intval($_GET['theme']); //Integers only!
	
	//Since intval() might be converting anything other than a number, and by default it converts to 0 if it is not an integer, we'll only process integers.
	if ($theme > 0)
	{
		$theme = $suit->db->select(TBL_PREFIX . 'themes', 'id', $theme_options);
		
		if ($theme)
		{
			//The theme exists. Set the user's theme by storing the value of it in the database.
			if ($theme)
			{
				while ($theme = mysql_fetch_assoc($id))
				{
					$settheme = $suit->db->query('UPDATE ' . TBL_PREFIX . 'users SET theme = \''. $theme .'\' WHERE  = \'' . $suit->userinfo['id'] . '\'');
					
					if ($settheme)
					{
						header('refresh: 0; url='. basename($_SERVER['SCRIPT_FILENAME']) . '');
						exit;
					}
				}
			}
		}
	}
}
?>
