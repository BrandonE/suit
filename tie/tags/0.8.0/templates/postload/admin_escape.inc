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
if ($suit->templates->vars['local']->loggedIn($suit) == 2)
{
	if (isset($_POST['escape']) && isset($_POST['code']))
	{
		$code = html_entity_decode($_POST['code']);
		$array = array
		(
			array('{', '{openingbrace}'),
			array('}', '{closingbrace}')
		);
		$code = htmlentities($suit->templates->replace($code, $array));
		$success = $suit->templates->getTemplate('success');
		$message = $suit->templates->vars['local']->getPhrase('escapedsuccessfully');
		$message = str_replace('<message>', $message, $success);
	}
	else
	{
		$code = '';
		$message = '';
	}
	$array = array
	(
		array('<message>', $message),
		array('<code>', $code)
	);
	$output = $suit->templates->replace($output, $array);
}
?>