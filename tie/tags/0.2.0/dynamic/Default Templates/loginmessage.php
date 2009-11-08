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
if (isset($GLOBALS['message']))
{
	$lcontent = $suit->language->getLanguage($GLOBALS['message'], $id, $pass);
	$layered[$layer] = str_replace('{1}', $lcontent, $layered[$layer]);
	
	if ($GLOBALS['message'] = 'nomatch')
	{
		$layered[$layer] = str_replace('{1}', htmlspecialchars($_POST['username']), $layered[$layer]);
	}
}
else
{
	$layered[$layer] = str_replace('{1}', '', $layered[$layer]);
}
?>
