<?php
/**
**@This file is part of TIE.
**@TIE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@TIE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with TIE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
if (isset($_POST['languages_update']) && isset($_POST['languages_entry']))
{
	$language = intval($_POST['languages_entry']);
	$suit->getTemplate('languages');
	$languages = $suit->tie->vars['languages'];
	if (isset($languages[$language]) || $language == -1)
	{
		setcookie($suit->tie->config['cookie']['prefix'] . 'language', $language, time() + $suit->tie->config['cookie']['length'], $suit->tie->config['cookie']['path'], $suit->tie->config['cookie']['domain']);
		$suit->tie->navigation->redirect($suit->tie->language['updatedsuccessfully'], $suit->tie->config['redirect']['interval'], $_SERVER['HTTP_REFERER']);
	}
}
$output = $suit->tie->parsePhrases($output);
$output = $suit->tie->parseVariables($output);
$output = $suit->tie->parseTemplates($output);
$languages = '';
$entry = $suit->getTemplate('languageform_entry');
$suit->getTemplate('languages');
$languagesarray = $suit->tie->vars['languages'];
if (is_array($languagesarray))
{
	foreach ($languagesarray as $key => $value)
	{
		$selected = (intval($suit->tie->languageid) == $key) ? ' selected' : '';
		$array = array
		(
			array('<id>', $key),
			array('<selected>', $selected),
			array('<title>', htmlentities($value[0]))
		);
		$languages .= $suit->tie->replace($entry, $array);
	}
}
$output = str_replace('<languages>', $languages, $output);
?>