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
	if (isset($_POST['clear']))
	{
		header('refresh: 0; url=./admin_errorlog.php?cmd=clear&dummy=1');
		exit;
	}
	$list = '';
	$admin_errorlogentry = $suit->templates->getTemplate('admin_errorlogentry', $rows);
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
	$errorlog = $suit->mysql->select('' . TBL_PREFIX . 'errorlog', '*', $errorlog_options);
	if ($errorlog)
	{
		while ($row = mysql_fetch_assoc($errorlog))
		{
			$list .= $admin_errorlogentry;
			$numrows = $suit->mysql->select(TBL_PREFIX . 'errorlog', 'COUNT(*) AS number');
			
			if ($numrows)
			{
				$numrows = mysql_fetch_assoc($numrows);
			}
			
			$array = array
			(
				array('{1}', wordwrap($row['content'], strlen($row['content'])/2, '<br />', true)),
				array('{2}', $row['date']),
				array('{3}', $row['location'])
			);
			
			$list = $suit->templates->implosion($list, $array);			
		}
	}
	$admin_errorlogpages = $suit->templates->getTemplate('admin_errorlogpages', $rows);
	$pages = $admin_errorlogpages;
	if (($start - ($limit * 3)) >= 0)
	{
		$admin_errorloglink = $suit->templates->getTemplate('admin_errorloglink', $rows);
		$first = $admin_errorloglink;
		$lcontent = $suit->language->getLanguage('first');
		$array = array
		(
			array('{1}', '0'),
			array('{2}', '&amp;limit=' . $limit),
			array('{3}', $lcontent)
		);
		
		$first = $suit->templates->implosion($first, $array);
	}
	else
	{
		$first = '';
	}

	if (($start - ($limit * 2)) >= 0)
	{
		$admin_errorloglink = $suit->templates->getTemplate('admin_errorloglink', $rows);
		$second = $admin_errorloglink;
		$array = Array
		(
			array('{1}', $start - ($limit * 2)),
			array('{2}', '&amp;limit=' . $limit),
			array('{3}', ($start / $limit) - 1)
		);
		$second = $suit->templates->implosion($second, $array);
	}
	else
	{
		$second = '';
	}

	if (($start - $limit) >= 0)
	{
		$admin_errorloglink = $suit->templates->getTemplate('admin_errorloglink', $rows);
		$third = $admin_errorloglink;
		$array = array
		(
			array('{1}', $start - $limit),
			array('{2}', '&amp;limit=' . $limit),
			array('{3}', ($start / $limit))
		);
		$third = $suit->templates->implosion($third, $array);
	}
	else
	{
		$third = '';
	}

	$fourth = ($start / $limit) + 1;

	$errorlog_options = array(
	'orderby' => 'date', 
	'orderby_type' => 'desc', 
	'limit' => $start + $limit . ':' . $limit
	);
	
	$errorlog = $suit->mysql->select(TBL_PREFIX . 'errorlog', '*', $errorlog_options);
	if ($errorlog)
	{
		$admin_errorloglink = $suit->templates->getTemplate('admin_errorloglink', $rows);
		$fifth = $admin_errorloglink;
		$array = Array
		(
			array('{1}', $start + $limit),
			array('{2}', '&amp;limit=' . $limit),
			array('{3}', ($start / $limit) + 2)
		);
		$fifth = $suit->templates->implosion($fifth, $array);
	}
	else
	{
		$fifth = '';
	}

	$errorlog_options = array(
	'orderby' => 'date',
	'orderby_type' => 'desc',
	'limit' => $start + ($limit * 2) . ':' . $limit
	);
	$errorlog = $suit->mysql->select(TBL_PREFIX . 'errorlog', '*', $errorlog_options);
	if ($errorlog)
	{
		$admin_errorloglink = $suit->templates->getTemplate('admin_errorloglink', $rows);
		$sixth = $admin_errorloglink;
		$array = Array
		(
			array('{1}', $start + ($limit * 2)),
			array('{2}', '&amp;limit=' . $limit),
			array('{3}', ($start / $limit) + 3)
		);
		$sixth = $suit->templates->implosion($sixth, $array);
	}
	else
	{
		$sixth = '';
	}

	$errorlog_options = array(
	'orderby' => 'date', 
	'orderby_type' => 'desc',
	'limit' => $start + ($limit * 3) . ':1'
	);
	$errorlog = $suit->mysql->select(TBL_PREFIX . 'errorlog', '*', $errorlog_options);
	if ($errorlog)
	{
		$errorlog = $suit->mysql->select(TBL_PREFIX . 'errorlog', '*', '');
		if ($errorlog)
		{
			while ($row = mysql_fetch_assoc($errorlog))
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
				$admin_errorloglink = $suit->templates->getTemplate('admin_errorloglink', $rows);
				$seventh = $admin_errorloglink;
				$lcontent = $suit->language->getLanguage('last', $rows);
				$array = Array
				(
					array('{1}', $num),
					array('{2}', '&amp;limit=' . $limit),
					array('{3}', $lcontent)
				);
				$seventh = $suit->templates->implosion($seventh, $array);
			}
		}
	}
	else
	{
		$seventh = '';
	}

	$admin_errorloglimit = $suit->templates->getTemplate('admin_errorloglimit', $rows);
	$limit2 = $admin_errorloglimit;
	$limit2 = str_replace('{1}', $limit, $limit2);

	$array = Array
	(
		Array
		(
			'{1}', $first
		),

		Array
		(
			'{2}', $second
		),

		Array
		(
			'{3}', $third
		),

		Array
		(
			'{4}', $fourth
		),

		Array
		(
			'{5}', $fifth
		),

		Array
		(
			'{6}', $sixth
		),

		Array
		(
			'{7}', $seventh
		)
	);
	$pages = $suit->templates->implosion($pages, $array);	
	if (isset($_GET['cmd']))
	{
		$lcontent = $suit->language->getLanguage('clearedsuccessfully');
	}
	else
	{
		$lcontent = '';
	}
	$array = Array
	(
		Array
		(
			'{1}', $list
		),

		Array
		(
			'{2}', $pages
		),

		Array
		(
			'{3}', $limit2
		),

		Array
		(
			'{4}', $lcontent
		)
	);
	$output = $suit->templates->implosion($output, $array);
}
else
{
	$output = str_replace('{1}', '', $output);
}
?>
