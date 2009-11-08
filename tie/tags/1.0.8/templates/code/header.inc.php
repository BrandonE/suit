<?php
$output = $suit->tie->parseVariables($output);
$output = $suit->tie->parsePhrases($output);
$output = $suit->tie->parseTemplates($output);
$exclude = array('cmd', 'file', 'limit', 'orderby', 'search', 'section', 'select', 'start');
$templates = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
$dashboard = substr_replace($templates, '', strlen($templates)-1, 1);
$array = array
(
	array('<dashboard>', htmlentities($dashboard)),
	array('<templates>', htmlentities($templates))
);
$output = $suit->tie->replace($output, $array);
$output = $suit->tie->parseConditional('admin', isset($suit->tie->vars['isadmin']), $output);
?>