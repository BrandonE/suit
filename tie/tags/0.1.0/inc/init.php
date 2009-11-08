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
require $PATH_HOME. 'inc/core.class.php';
require $PATH_HOME. 'inc/functions.php';
/*
If debug is set to on, then display all errors/warnings.
*/
if ($system->debug)
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
$system = new System;
$system->loadModule('mysql', 'mysql');
$system->loadModule('templates', 'templates');
$system->loadModule('language', 'language');
?>
