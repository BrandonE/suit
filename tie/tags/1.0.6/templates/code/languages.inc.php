<?php
$languages = array
(
	array('English', 'languages_english', 1)
);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $value)
{
	$suit->tie->vars[$value] = &$$value;
}
?>