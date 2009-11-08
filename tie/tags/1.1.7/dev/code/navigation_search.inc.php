<?php
$exclude = array('limit', 'orderby', 'search', 'select', 'start');
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
if (isset($_POST['navigation_search_submit']) && isset($_POST['navigation_search_value']))
{
	$suit->tie->navigation->redirect(0, $path . 'start=0&limit=' . $suit->tie->navigation->settings['limit'] . '&orderby=' . $suit->tie->navigation->settings['orderby_type'] . '&search=' . urlencode($_POST['navigation_search_value']));
}
$array = array_merge
(
	$suit->tie->parse('$this->owner->getTemplate($case)', $suit->tie->config['parse']['templates']['open'], $suit->tie->config['parse']['templates']['close'], $output, 'section escape'),
	$suit->tie->parse('$this->language[$case]', $suit->tie->config['parse']['languages']['open'], $suit->tie->config['parse']['languages']['close'], $output, 'section escape'),
	$suit->tie->parse('$this->owner->vars[$case]', $suit->tie->config['parse']['variables']['open'], $suit->tie->config['parse']['variables']['close'], $output, 'section escape'),
	$suit->tie->parseConditional('section escape', true, $output)
);
$output = $suit->tie->replace($array, $output);
$output = str_replace('<search>', $suit->tie->navigation->settings['search'], $output);
?>