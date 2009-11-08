<?php
$array = array_merge
(
	$suit->tie->parseTemplates($output),
	$suit->tie->parseLanguages($output),
	$suit->tie->parseVariables($output)
);
$output = $suit->tie->replace($array, $output);
$output = str_replace('<name>', $suit->tie->language['error'], $output);
print $output;
exit;
?>