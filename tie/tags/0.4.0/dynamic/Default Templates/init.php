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
if (isset($_GET['logout']) && $_GET['logout'] == true)
{	
	setcookie('id', '', time()-3600, '' . COOKIE_PATH . '', '' . COOKIE_DOMAIN . '');
	setcookie('pass', '', time()-3600, '' . COOKIE_PATH . '', '' . COOKIE_DOMAIN . '');
	header('refresh: 0; url=' . $_SERVER['PHP_SELF']);
	exit;
}
?>
