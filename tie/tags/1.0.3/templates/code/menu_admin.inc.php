<?php
$output = $suit->tie->parseVariables($output);
$output = $suit->tie->parsePhrases($output);
$output = $suit->tie->parseTemplates($output);
$exclude = array('cmd', 'limit', 'orderby', 'search', 'select', 'start', 'template');
$templates = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
$dashboard = substr_replace($templates, '', strlen($templates)-1, 1);
$array = array
(
	array('<dashboard>', $dashboard),
	array('<templates>', $templates)
);
$output = $suit->tie->replace($output, $array);
?>