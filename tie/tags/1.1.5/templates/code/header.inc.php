<?php
$array = array_merge
(
	$suit->tie->parseTemplates($output),
	$suit->tie->parseLanguages($output),
	$suit->tie->parseVariables($output)
);
$output = $suit->tie->replace($array, $output);
$exclude = array('cmd', 'file', 'limit', 'orderby', 'search', 'section', 'select', 'start');
$templates = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
$dashboard = substr_replace($templates, '', strlen($templates) - 1, 1);
$output = $suit->tie->replace($suit->tie->parseConditional('if admin', isset($suit->tie->vars['isadmin']), $output), $output);
$array = array
(
	array('<dashboard>', htmlentities($dashboard)),
	array('<templates>', htmlentities($templates))
);
$output = $suit->tie->replace($array, $output);
?>