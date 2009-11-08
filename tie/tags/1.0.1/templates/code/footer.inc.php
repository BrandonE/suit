<?php
$output = $suit->tie->parseVariables($output);
$output = $suit->tie->parsePhrases($output);
$output = $suit->tie->parseTemplates($output);
$exclude = array('cmd', 'limit', 'orderby', 'search', 'select', 'start', 'template');
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
$path = substr_replace($path, '', strlen($path)-1, 1);
$output = str_replace('<admin>', $path, $output);
?>