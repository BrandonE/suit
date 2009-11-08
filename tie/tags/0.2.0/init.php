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
/*
Load the configuration and the core, to allow functionality.
*/
require $PATH_HOME. 'modules/core.class.php';
$suit = new SUIT;
/*
If debug is set to on, then display all errors/warnings.
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
Load the modules required.
*/

$suit->loadModule('mysql', 'mysql');
$suit->loadModule('templates', 'templates');
$suit->loadModule('language', 'language');

global $layered, $id, $pass;

$suit->mysql->connect('' . SQL_HOST . '', '' . SQL_USER . '', '' . SQL_PASS . '');
?>
