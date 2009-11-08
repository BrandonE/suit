<?php
/**
**@This file is part of The SUIT Framework.

**@SUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.

**@SUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.
**/
$suit->templates->getTemplate('tie');
$tie = &$suit->templates->vars['tie'];
$output = $tie->parsePhrases($output);
if ($tie->loggedIn() == 2)
{
	if (isset($_GET['start']))
	{
		$start = intval($_GET['start']);
	}
	else
	{
		$start = 0;
	}
	if (isset($_GET['limit']))
	{
		$limit = intval($_GET['limit']);
	}
	else
	{
		$limit = 10;
	}
	if (isset($_GET['search']) && (strlen($_GET['search']) >= 4))
	{
		$search = $suit->db->escape($_GET['search']);
	}
	else
	{
		$search = '';
	}
	$orderby_type = 'desc';
	if (isset($_GET['orderby']) && ($_GET['orderby'] == 'asc'))
	{
		$orderby_type = 'asc';
	}
	if (isset($_POST['errorlog_clear']))
	{
		$query = 'TRUNCATE ' . DB_PREFIX . 'errorlog';
		$check = $suit->db->query($query);
		if ($check)
		{
			$tie->redirect($tie->getPhrase('clearedsuccessfully'), 2, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_errorlog');
		}
	}
	if (isset($_POST['limit']) && isset($_POST['limitval']))
	{
		$limitval = intval($_POST['limitval']);
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_errorlog&start=' . $start . '&limit=' . $limitval . '&search=' . $search . '&orderby=' . $orderby_type);
	}
	if (isset($_POST['search']) && isset($_POST['searchval']) && (strlen($_POST['searchval']) >= 4))
	{
		$searchval = $suit->db->escape($_POST['searchval']);
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_errorlog&start=' . $start . '&limit=' . $limit . '&search=' . $searchval . '&orderby=' . $orderby_type);
	}
	$output = $tie->parseTemplates($output);
	$range = $tie->setRange($start, $limit);
	$query = 'SELECT * FROM ' . DB_PREFIX . 'errorlog WHERE 1';
	$list = $tie->createList($query, 'time', $orderby_type, $range, $search, 'content, date, location', $suit->templates->getTemplate('path_url') . '/index.php?page=admin_errorlog', 'id', 0, 0, 1, $start, $limit, 0, $search, 0);
	$output = str_replace('<admin_errorlog>', $list, $output);
}
else
{
	$output = $tie->parseTemplates($output);
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{	
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>