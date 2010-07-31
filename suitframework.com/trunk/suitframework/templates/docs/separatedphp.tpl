<?php
require 'suit.class.php';
require 'templating.class.php';
$suit = new SUIT();
$templating = new Templating($suit);
$template = file_get_contents('index.tpl');

$templating->var->username = 'Brandon';
$templating->var->loggedin = true;
$templating->var->members = array
(
    array
    (
        'name' => 'Brandon',
        'admin' => true,
        'banned' => false
    ),
    array
    (
        'name' => 'Chris',
        'admin' => false,
        'banned' => true
    )
);
echo $suit->execute($templating->rules, $template);
?>