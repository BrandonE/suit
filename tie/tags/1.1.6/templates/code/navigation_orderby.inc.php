<?php
$array = array_merge
(
	$suit->tie->parse($output, '$this->owner->getTemplate($case)', $suit->tie->config['parse']['templates']['open'], $suit->tie->config['parse']['templates']['close'], 'section escape'),
	$suit->tie->parse($output, '$this->language[$case]', $suit->tie->config['parse']['languages']['open'], $suit->tie->config['parse']['languages']['close'], 'section escape'),
	$suit->tie->parse($output, '$this->owner->vars[$case]', $suit->tie->config['parse']['variables']['open'], $suit->tie->config['parse']['variables']['close'], 'section escape'),
	$suit->tie->parseConditional('section escape', true, $output)
);
$output = $suit->tie->replace($array, $output);
$exclude = array('limit', 'orderby', 'search', 'start');
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
$array = array_merge
(
	array
	(
		array('<limit>', $suit->tie->navigation->settings['limit']),
		array('<orderby>', $suit->tie->navigation->settings['orderby_type']),
		array('<path>', htmlentities($path)),
		array('<search>', $suit->tie->navigation->settings['search']),
		array('<start>', $suit->tie->navigation->settings['start'])
	),
	$suit->tie->parseConditional('if link', (!strcmp($suit->tie->navigation->settings['orderby_type'], 'desc')), $output, 'else link')
);
$output = $suit->tie->replace($array, $output);
?>