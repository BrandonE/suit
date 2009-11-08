<?php
$array = array_merge
(
	$suit->tie->parse('$this->owner->getTemplate($case)', $suit->tie->config['parse']['templates']['open'], $suit->tie->config['parse']['templates']['close'], $output, 'section escape'),
	$suit->tie->parse('$this->language[$case]', $suit->tie->config['parse']['languages']['open'], $suit->tie->config['parse']['languages']['close'], $output, 'section escape'),
	$suit->tie->parse('$this->owner->vars[$case]', $suit->tie->config['parse']['variables']['open'], $suit->tie->config['parse']['variables']['close'], $output, 'section escape'),
	$suit->tie->parseConditional('section escape', true, $output)
);
$output = $suit->tie->replace($array, $output);
$output = str_replace('<name>', $suit->tie->language['error'], $output);
print $output;
exit;
?>