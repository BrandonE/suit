<?php
require 'suit/suit.class.php';
require 'suit/config.inc.php';
$suit = new SUIT($config);
echo $suit->getTemplate('cape/index');
unset($suit);
?>