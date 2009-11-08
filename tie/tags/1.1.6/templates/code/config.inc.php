<?php
$config = array
(
	'cookie' => array
	(
		'domain' => '',
		'length' => 3600,
		'path' => '',
		'prefix' => ''
	),
	'flag' => array
	(
		'debug' => true
	),
	'navigation' => array
	(
		'pages' => 2,
		'redirect' => 2
	),
	'parse' => array
	(
		'languages' => array
		(
			'open' => '[',
			'close' => ']'
		),
		'sections' => array
		(
			'open' => '<',
			'close' => '>',
			'end' => '/'
		),
		'templates' => array
		(
			'open' => '{',
			'close' => '}'
		),
		'variables' => array
		(
			'open' => '(',
			'close' => ')'
		)
	)
);
$suit->vars = array_merge(compact(array_keys(get_defined_vars())), $suit->vars);
?>