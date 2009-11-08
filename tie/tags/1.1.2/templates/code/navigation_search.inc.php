<?php
$exclude = array('limit', 'orderby', 'search', 'select', 'start');
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
if (isset($_POST['navigation_search_submit']) && isset($_POST['navigation_search_value']))
{
	$suit->tie->navigation->redirect(0, $path . 'start=0&limit=' . $suit->tie->navigation->settings['limit'] . '&orderby=' . $suit->tie->navigation->settings['orderby_type'] . '&search=' . urlencode($_POST['navigation_search_value']));
}
$output = $suit->tie->parseVariables($output);
$output = $suit->tie->parseLanguages($output);
$output = $suit->tie->parseTemplates($output);
$output = str_replace('<search>', $suit->tie->navigation->settings['search'], $output);
?>