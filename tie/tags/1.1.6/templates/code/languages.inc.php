<?php
$languages = array
(
	array('English', 'languages_english', true),
	array('Español', 'languages_spanish', false),
	array('中文', 'languages_chinese', false),
	array('Polski', 'languages_polish', false),
	array('Français', 'languages_french', false)
);
$suit->vars = array_merge(compact(array_keys(get_defined_vars())), $suit->vars);
?>