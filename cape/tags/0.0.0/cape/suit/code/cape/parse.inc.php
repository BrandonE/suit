<?php
/**
**@This file is part of CAPE.
**@CAPE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@CAPE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with CAPE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The CAPE Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
if (in_array($id, array(8, 9, 10, 11)))
{
	$explode = explode(';', $equal, 2);
	$equal = $explode[0];
}
if (in_array($id, array(13, 16)))
{
	if (in_array($equal, array('1', 'a', 'A', 'i', 'I')))
	{
		$main = str_replace('<br />', '', $main);
		$main = explode('[*]', $main);
		foreach ($main as $key => $value)
			if ($key != 0)
				$main[$key] = '<li>' . $value . '</li>';
		$main = implode('', $main);
	}
	else
		$replacement = '[list=' . $equal . ']' . $main . '[/list]';
}
if ($id == 10)
{
	$equal = intval($equal) + 6;
	if ($equal > 30)
		$equal = 30;
}
?>