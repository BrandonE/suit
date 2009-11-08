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

//Allow us to send a header() even when content has been sent to the browser.
ob_start();
//We'll set-up a microtime variable to track the page loading.
$start = microtime();
//Load up the configuration.
require 'config.php';
//Load the core class.
include 'modules/core.class.php';
$suit = new SUIT(FLAG_DEBUG, $start);
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
$suit->loadModule('templates', 'templates', $suit);
$suit->loadModule('languages', 'languages', $suit);

//Was a database type defined? If so, make sure it's a supported RDBMS.
$dbms = array('mysql', 'postgresql');
if (defined('DB_TYPE') && in_array(DB_TYPE, $dbms))
{
	//Load the database module.
	$suit->loadModule('db', 'db');
	//Connect to MySQL, specifying what is in the configuration file.
	$suit->db->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
}
else
{
	echo 'There is no RDBMS specified, or the RDBMS is not supported by SUIT.';
}

//Set Info
$suit->user = $suit->setUser();
$suit->file = $suit->setFile($id);
$suit->theme = $suit->setTheme();
$suit->template = $suit->setTemplate();
$suit->language = $suit->setLanguage();
?>
