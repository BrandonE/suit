<?php
$array = array_merge
(
	$suit->tie->parseTemplates($output),
	$suit->tie->parseLanguages($output),
	$suit->tie->parseVariables($output)
);
$output = $suit->tie->replace($array, $output);
$exclude = array('limit', 'orderby', 'search', 'start');
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
$array = array
(
	array('<limit>', $suit->tie->navigation->settings['limit']),
	array('<orderby>', $suit->tie->navigation->settings['orderby_type']),
	array('<path>', htmlentities($path)),
	array('<search>', $suit->tie->navigation->settings['search']),
	array('<start>', $suit->tie->navigation->settings['start']),
);
$output = $suit->tie->replace($array, $output);
?>