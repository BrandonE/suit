<?php
$array = array_merge
(
	$suit->tie->parse($output, '$this->owner->getTemplate($case)', $suit->tie->config['parse']['templates']['open'], $suit->tie->config['parse']['templates']['close'], 'section escape'),
	$suit->tie->parse($output, '$this->language[$case]', $suit->tie->config['parse']['languages']['open'], $suit->tie->config['parse']['languages']['close'], 'section escape'),
	$suit->tie->parse($output, '$this->owner->vars[$case]', $suit->tie->config['parse']['variables']['open'], $suit->tie->config['parse']['variables']['close'], 'section escape'),
	$suit->tie->parseConditional('section escape', true, $output)
);
$output = $suit->tie->replace($array, $output);
$exclude = array('cmd', 'file', 'limit', 'orderby', 'search', 'section', 'select', 'start');
$templates = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
$dashboard = substr_replace($templates, '', strlen($templates) - 1, 1);
$output = $suit->tie->replace($suit->tie->parseConditional('if admin', isset($suit->vars['isadmin']), $output), $output);
$array = array
(
	array('<dashboard>', htmlentities($dashboard)),
	array('<templates>', htmlentities($templates))
);
$output = $suit->tie->replace($array, $output);
?>