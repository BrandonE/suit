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
if ($suit->loggedIn() == 2)

{

	function pagelink($check, $start, $display, $db, &$suit, $limit, $link, $limit_get)

	{

		$success = false;

		if ($db == 0)

		{

			if ($check >= 0)

			{

				$success = true;

			}

		}

		else

		{

			$errorlog_options = array('orderby' => 'date', 'orderby_type' => 'desc', 'limit' => $check . ':1');

			$errorlog = $suit->db->select(TBL_PREFIX . 'errorlog', '*', $errorlog_options);

			if ($errorlog)

			{

				$success = true;

			}

		}

		if ($success)

		{

			if ($start == 0)

			{

				$start = $check;

			}

			$first = $link;

			$array = array

			(

				array('<start>', $start),

				array('<limit>', $limit_get . $limit),

				array('<display>', $display)

			);

			$return = $suit->templates->replace($first, $array);

		}

		else

		{

			$return = '';

		}

		return $return;

	}



	if (isset($_POST['errorlog_clear']) && isset($_GET['cmd']) && ($_GET['cmd'] == 'clear'))

	{

		$suit->db->truncate(TBL_PREFIX . 'errorlog');

		header('refresh: 0; url=./admin_errorlog.php?cmd=clear&dummy=1');

		exit;

	}



	$errors = '';

	$admin_errorlog_entry_vars = $suit->templates->getTemplate('admin_errorlog_entry', $chains);

	$admin_errorlog_entry = $admin_errorlog_entry_vars['output'];

	

	if (isset($_GET['limit']))

	{

		$limit = intval($_GET['limit']);

		if (!($limit == intval($limit) && ($limit > 0)))

		{

			header('refresh: 0; url=./admin_errorlog.php');

			exit;

		}

	}

	else

	{

		$limit = 10;

	}

	

	if (isset($_GET['start']))

	{

		$start = intval($_GET['start']);

		if (!($start == intval($start) && ($start >= 0) && (($start / $limit) == round(($start / $limit)))))

		{

			header('refresh: 0; url=./admin_errorlog.php');

			exit;

		}

	}

	else

	{

		$start = 0;

	}

	

	$errorlog_options = array('orderby' => 'date', 'orderby_type' => 'desc', 'limit' => $start . ':' . $limit);

	$errorlog = $suit->db->select('' . TBL_PREFIX . 'errorlog', '*', $errorlog_options);

	if ($errorlog)

	{

		while ($row = mysql_fetch_assoc($errorlog))

		{

			$errors .= $admin_errorlog_entry;

			$numrows = $suit->db->select(TBL_PREFIX . 'errorlog', 'COUNT(*) AS number');

			

			if ($numrows)

			{

				$numrows = mysql_fetch_assoc($numrows);

			}

			

			$array = array

			(

				array('<error>', wordwrap($row['content'], strlen($row['content'])/2, '<br />', true)),

				array('<time>', $row['date']),

				array('<location>', $row['location'])

			);

			

			$errors = $suit->templates->replace($errors, $array);			

		}

	}

	$admin_errorlog_links_vars = $suit->templates->getTemplate('admin_errorlog_links', $chains);

	$admin_errorlog_links = $admin_errorlog_links_vars['output'];



	$admin_errorlog_link_vars = $suit->templates->getTemplate('admin_errorlog_link', $chains);

	$admin_errorlog_link = $admin_errorlog_link_vars['output'];

	$admin_errorlog_limit_get_vars = $suit->templates->getTemplate('admin_errorlog_limit_get', $chains);

	$admin_errorlog_limit_get = $admin_errorlog_limit_get_vars['output'];



	$firstmessage = $suit->languages->getLanguage('first');

	$lastmessage = $suit->languages->getLanguage('last');

	$num = 0;



	$errorlog = $suit->db->select(TBL_PREFIX . 'errorlog', '*', '');

	if ($errorlog)

	{

		$num = mysql_num_rows($errorlog);

		if (($num / $limit) != (round(($num / $limit))))

		{

			do

			{

				$num--;

			}

			while (($num / $limit) != (round(($num / $limit))));

		}

	}



	$link_1 = pagelink(($start - ($limit * 3)), '0', $firstmessage, 0, $suit, $limit, $admin_errorlog_link, $admin_errorlog_limit_get);

	$link_2 = pagelink(($start - ($limit * 2)), 0, (($start / $limit) - 1), 0, $suit, $limit, $admin_errorlog_link, $admin_errorlog_limit_get);

	$link_3 = pagelink(($start - $limit), 0, ($start / $limit), 0, $suit, $limit, $admin_errorlog_link, $admin_errorlog_limit_get);

	$link_4 = ($start / $limit) + 1;

	$link_5 = pagelink(($start + $limit), 0, (($start / $limit) + 2), 1, $suit, $limit, $admin_errorlog_link, $admin_errorlog_limit_get);

	$link_6 = pagelink(($start + ($limit * 2)), 0, (($start / $limit) + 3), 1, $suit, $limit, $admin_errorlog_link, $admin_errorlog_limit_get);

	$link_7 = pagelink(($start + ($limit * 3)), strval($num), $lastmessage, 1, $suit, $limit, $admin_errorlog_link, $admin_errorlog_limit_get);



	$admin_errorlog_limit_vars = $suit->templates->getTemplate('admin_errorlog_limit', $chains);

	$admin_errorlog_limit = $admin_errorlog_limit_vars['output'];

	$admin_errorlog_limit = str_replace('<currentlimit>', $limit, $admin_errorlog_limit);



	$array = Array

	(

		Array('<First>', $link_1),

		Array('<1>', $link_2),

		Array('<2>', $link_3),

		Array('<3>', $link_4),

		Array('<4>', $link_5),

		Array('<5>', $link_6),

		Array('<Last>', $link_7)

	);

	$admin_errorlog_links = $suit->templates->replace($admin_errorlog_links, $array);	

	if (isset($_GET['cmd']))

	{

		$success_vars = $suit->templates->getTemplate('success', $chains);

		$success = $success_vars['output'];

		$clearedmessage = $suit->languages->getLanguage('clearedsuccessfully');

		$clearedmessage = str_replace('<1>', $clearedmessage, $success);

	}

	else

	{

		$clearedmessage = '';

	}

	$array = Array

	(

		Array('<errors>', $errors),

		Array('<links>', $admin_errorlog_links),

		Array('<limitform>', $admin_errorlog_limit),

		Array('<clearedmessage>', $clearedmessage)

	);

	$output = $suit->templates->replace($output, $array);

}

else

{

	$output = str_replace('<1>', '', $output);

}

?>

