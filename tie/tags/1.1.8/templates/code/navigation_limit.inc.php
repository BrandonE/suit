<?php
$exclude = array('limit', 'orderby', 'search', 'select', 'start');
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
if (isset($_POST['navigation_limit_submit']) && isset($_POST['navigation_limit_value']))
{
	$suit->tie->navigation->redirect(0, $path . 'start=0&limit=' . intval($_POST['navigation_limit_value']) . '&orderby=' . $suit->tie->navigation->settings['orderby_type'] . '&search=' . $suit->tie->navigation->settings['search']);
}
$output = str_replace('<limit>', $suit->tie->navigation->settings['limit'], $output);
?>