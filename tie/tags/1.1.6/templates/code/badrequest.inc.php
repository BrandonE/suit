<?php
$array = array_merge
(
	$suit->tie->parse($output, 'templates', 'section escape'),
	$suit->tie->parse($output, 'languages', 'section escape'),
	$suit->tie->parse($output, 'variables', 'section escape'),
	$suit->tie->parseConditional('section escape', true, $output)
);
$output = $suit->tie->replace($array, $output);
$output = str_replace('<name>', $suit->tie->language['error'], $output);
print $output;
exit;
?>