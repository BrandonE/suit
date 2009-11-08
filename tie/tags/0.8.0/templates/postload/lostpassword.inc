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
if ($suit->templates->vars['local']->loggedIn() == 0)
{
	if (isset($suit->templates->vars['lostpassword_message']))
	{
		//We'll use a switch() statement to determine what action to take for these errors.
		//When we have our error, we'll load the language string for it.
		switch ($suit->templates->vars['lostpassword_message'])
		{
			case 'emailnotfound':
				$lostpassword_message = $suit->templates->vars['local']->getPhrase('emailnotfound'); break;
			case 'passwordsent':
				$lostpassword_message = $suit->templates->vars['local']->getPhrase('passwordsent'); break;
			case 'passwordchanged':
				$lostpassword_message = $suit->templates->vars['local']->getPhrase('passwordchanged'); break;
			case 'passwordexpired':
				$lostpassword_message = $suit->templates->vars['local']->getPhrase('passwordexpired'); break;
			default:
				$lostpassword_message = $suit->templates->vars['local']->getPhrase('undefinederror'); break;
		}
		//Replace the value of $list with what we concluded in the error switch() statement.
	}
	else
	{
		$lostpassword_message = '';
	}
	$output = str_replace('<message>', $lostpassword_message, $output);
}
?>