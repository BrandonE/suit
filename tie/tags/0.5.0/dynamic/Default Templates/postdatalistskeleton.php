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
$list = '';
$postdatalist = $suit->templates->getTemplate('postdatalist',$rows);
foreach ($_POST as $key => $value)
{
	$list .= $postdatalist;
	$list = str_replace('{1}', nl2br(htmlentities(stripslashes($key))), $list);
	$list = str_replace('{2}', nl2br(htmlentities(stripslashes($value))), $list);
}
$output = str_replace('{1}', $list, $output);
?>                                                                            
