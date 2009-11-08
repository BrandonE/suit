<?php
$exclude = array('limit', 'orderby', 'search', 'start');
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
$output = $suit->tie->parseVariables($output);
$output = $suit->tie->parsePhrases($output);
$output = $suit->tie->parseTemplates($output);
$link = (!strcmp($suit->tie->navigation->settings['orderby_type'], 'desc')) ? 'asc' : 'desc';
$text = $suit->tie->language[$link];
$array = array
(
	array('<limit>', $suit->tie->navigation->settings['limit']),
	array('<link>', $link),
	array('<orderby>', $suit->tie->navigation->settings['orderby_type']),
	array('<path>', htmlentities($path)),
	array('<search>', $suit->tie->navigation->settings['search']),
	array('<start>', $suit->tie->navigation->settings['start']),
	array('<text>', $text)
);
$output = $suit->tie->replace($output, $array);
?>