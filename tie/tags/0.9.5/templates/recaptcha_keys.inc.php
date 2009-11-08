<?php
$local = &$suit->templates->vars['local'];
$suit->templates->vars['publickey'] = '';
$suit->templates->vars['privatekey'] = '';
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>