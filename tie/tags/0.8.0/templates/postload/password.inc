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
if ($suit->templates->vars['local']->loggedIn() != 0)
{
	if (isset($_POST['password']))
	{
		if ((isset($suit->templates->vars['error']) && ($suit->templates->vars['error'] == 'wrongpassword')))
		{
			$lcontent = $suit->templates->vars['local']->getPhrase('wrongpassword');
			$output = str_replace('<message>', $lcontent, $output);
		}
		else
		{
			$lcontent = $suit->templates->vars['local']->getPhrase('changedsuccessfully');
			$success_vars = $suit->templates->getTemplate('success');
			$success = $success_vars['output'];
			$success = str_replace('<message>', $lcontent, $success);
			$output = str_replace('<message>', $success, $output);
		}
	}
	else
	{
		$output = str_replace('<message>', '', $output);
	}
}
?>