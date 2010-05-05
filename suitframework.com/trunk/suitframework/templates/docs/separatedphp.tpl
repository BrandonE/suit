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
        'group' => 'Administrator'
    ),
    array
    (
        'name' => 'Chris',
        'group' => 'Banned'
    )
);
echo $suit->execute($templating->rules, $template);
?>