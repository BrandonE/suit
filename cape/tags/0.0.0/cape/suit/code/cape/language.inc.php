<?php
/**
**@This file is part of TIE.
**@TIE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@TIE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with TIE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
$nodes = $suit->config['parse']['nodes'];
if (isset($_POST['languages_update']) && isset($_POST['languages_entry']))
{
	$language = intval($_POST['languages_entry']);
	$suit->getTemplate('languages/main');
	$languages = $suit->vars['languages'];
	if (isset($languages[$language]) || $language == -1)
	{
		setcookie($suit->tie->config['cookie']['prefix'] . 'language', $language, time() + $suit->tie->config['cookie']['length'], $suit->tie->config['cookie']['path'], $suit->tie->config['cookie']['domain']);
		$suit->tie->redirect($_SERVER['HTTP_REFERER'], $suit->vars['language']['updatedsuccessfully'], NULL, 'cape/redirect');
	}
}
$languages = array();
$suit->getTemplate('languages/main');
$languagesarray = $suit->vars['languages'];
asort($languagesarray);
if (is_array($languagesarray))
	foreach ($languagesarray as $key => $value)
		$languages[] = array
		(
			array
			(
				array('[id]', $key),
				array('[title]', htmlspecialchars($value[0]))
			),
			array
			(
				$suit->parseConditional('if selected', (intval($suit->tie->language) == $key))
			)
		);
$nodes[] = $suit->parseLoop('loop languages', $languages);
$content = $suit->parse($nodes, $content);
?>