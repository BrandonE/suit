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
if (!(file_exists('config.php') && (file_get_contents('config.php') != '')))
{
	print 'SUIT Error #1. See http://www.suitframework.com/docs/error1/';
	exit;
}
else
{
	require 'config.php';
}
require PATH_HOME . '/core.class.php';
$suit = new SUIT('suit');
$suit->init($_GET['page']);
if (isset($suit->page['template']))
{
	print $suit->templates->getTemplate($suit->page['template']);
}
else
{
	print 'Invalid Page Name';
	exit;
}
$suit->cleanUp();
?>