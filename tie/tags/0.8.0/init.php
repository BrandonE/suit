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
if (defined('PATH_HOME'))
{
	//Load the core class.
	include PATH_HOME . '/modules/core.class.php';
	if (defined('FLAG_DEBUG'))
	{
		$suit = new SUIT(FLAG_DEBUG);
		//If debug mode is on, then we'll show all errors that go on in the coding.
		if ($suit->debug)
		{
			error_reporting(E_ALL);
		}
		else
		{
			error_reporting(0);
		}
		
		//Load the vital modules required for the basic functionality of SUIT.
		if (defined('PATH_HOME'))
		{
			$suit->loadModule('templates', 'templates', $suit);
			//Was a database type defined? If so, make sure it's a supported RDBMS.
			$dbms = array('mysql', 'postgresql');
			if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS') && defined('DB_PORT'))
			{
				if (defined('DB_TYPE') && in_array(DB_TYPE, $dbms))
				{
					//Load the database module.
					$suit->loadModule('db', 'db');
					//Connect to the database, specifying what is in the configuration file.
					$suit->db->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				}
				else
				{
					echo 'There is no RDBMS specified, or the RDBMS is not supported by SUIT';
					exit;
				}
			}
			else
			{
				echo 'The MySQL constants are not defined';
			}
		}
		else
		{
			echo 'PATH_HOME not defined';
			exit;
		}
		
		//Set Info
		if (defined('TBL_PREFIX'))
		{
			$suit->page = $suit->setPage($page);
		}
		else
		{
			echo 'TBL_PREFIX not defined';
			exit;
		}
	}
	else
	{
		echo 'FLAG_DEBUG not defined';
		exit;
	}
}
else
{
	echo 'Illegal Access';
	exit;
}
?>
