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
if ($system->loggedIn() == 2)
{
	$system->templates->getTemplate('admin_menu', $id, $pass, $layer+1, 0);
	$GLOBALS['layered'][$layer] = str_replace('{1}', $GLOBALS['layered'][$layer+1], $GLOBALS['layered'][$layer]);
}
else
{
	$GLOBALS['layered'][$layer] = str_replace('{1}', '', $GLOBALS['layered'][$layer]);
}
?>
