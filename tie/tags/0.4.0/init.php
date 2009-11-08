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

require 'config.php';

/*
Allow us to send header() even when content has been sent to the browser.
*/
ob_start();
/*
Load the core class.
*/
require 'modules/core.class.php';
$suit = new SUIT;
/*
If debug is set to on, then display all errors/warnings in the coding.
*/
if ($suit->debug)
{
	error_reporting(E_ALL);
}
else
{
	error_reporting(0);
}
/*
Load the vital modules required for the basic functionality of SUIT.
*/
$suit->loadModule('mysql', 'mysql');
$suit->loadModule('templates', 'templates', $suit);
$suit->loadModule('language', 'language', $suit);
/*
We can finally start working. Let's connect to MySQL first.
*/
$suit->mysql->connect('' . SQL_HOST . '', '' . SQL_USER . '', '' . SQL_PASS . '');
//User info
$suit->setUserinfo();
?>
