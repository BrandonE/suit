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
	$list = '';
	$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_errorlogentry', $layer+1);
	if (isset($_GET) && (isset($_GET['limit'])))
	{
		$limit = $_GET['limit'];
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
	if (isset($_GET) && (isset($_GET['start'])))
	{
		$start = $_GET['start'];
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
			$list .= $layered[$layer+1];
			$numrows = $suit->mysql->select('' . TBL_PREFIX . 'errorlog', 'COUNT(*) AS number');
			
			if ($numrows)
			{
				$numrows = mysql_fetch_assoc($numrows);
			}
			
			$array = Array
			(
				Array
				(
					'{1}', $row['content']
				),
	
				Array
				(
					'{2}', $row['date']
				),

				Array
				(
					'{3}', $row['location']
				)
			);
			$list = $suit->templates->implosion($list, $array);			
		}
	}
	else
	{
		header('refresh: 0; url=./admin_errorlog.php');
		exit;
	}
	$layered[$layer+2] = $suit->templates->getDynamicTemplate('admin_errorlogpages', $layer+2);
	$pages = $layered[$layer+2];
	if (($start - ($limit * 3)) >= 0)
	{
		$layered[$layer+3] = $suit->templates->getDynamicTemplate('admin_errorloglink', $layer+3);
		$first = $layered[$layer+3];
		$lcontent = $suit->language->getLanguage('first');
		$array = Array
		(
			Array
			(
				'{1}', '0'
			),

			Array
			(
				'{2}', '&limit=' . $limit
			),

			Array
			(
				'{3}', $lcontent
			)
		);
		$first = $suit->templates->implosion($first, $array);
	}
	else
	{
		$first = '';
	}

	if (($start - ($limit * 2)) >= 0)
	{
		$layered[$layer+3] = $suit->templates->getDynamicTemplate('admin_errorloglink', $layer+3);
		$second = $layered[$layer+3];
		$array = Array
		(
			Array
			(
				'{1}', $start - ($limit * 2)
			),

			Array
			(
				'{2}', '&limit=' . $limit
			),

			Array
			(
				'{3}', ($start / $limit) - 1
			)
		);
		$second = $suit->templates->implosion($second, $array);
	}
	else
	{
		$second = '';
	}

	if (($start - $limit) >= 0)
	{
		$layered[$layer+3] = $suit->templates->getDynamicTemplate('admin_errorloglink', $layer+3);
		$third = $layered[$layer+3];
		$array = Array
		(
			Array
			(
				'{1}', $start - $limit
			),

			Array
			(
				'{2}', '&limit=' . $limit
			),

			Array
			(
				'{3}', ($start / $limit)
			)
		);
		$third = $suit->templates->implosion($third, $array);
	}
	else
	{
		$third = '';
	}

	$fourth = ($start / $limit) + 1;

	$errorlog_options = array('orderby' => 'date', 'orderby_type' => 'desc', 'limit' => $start + $limit . ':' . $limit);
	$errorlog = $suit->mysql->select('' . TBL_PREFIX . 'errorlog', '*', $errorlog_options);
	if ($errorlog)
	{
		$layered[$layer+3] = $suit->templates->getDynamicTemplate('admin_errorloglink', $layer+3);
		$fifth = $layered[$layer+3];
		$array = Array
		(
			Array
			(
				'{1}', $start + $limit
			),

			Array
			(
				'{2}', '&limit=' . $limit
			),

			Array
			(
				'{3}', ($start / $limit) + 2
			)
		);
		$fifth = $suit->templates->implosion($fifth, $array);
	}
	else
	{
		$fifth = '';
	}

	$errorlog_options = array('orderby' => 'date', 'orderby_type' => 'desc', 'limit' => $start + ($limit * 2) . ':' . $limit);
	$errorlog = $suit->mysql->select('' . TBL_PREFIX . 'errorlog', '*', $errorlog_options);
	if ($errorlog)
	{
		$layered[$layer+3] = $suit->templates->getDynamicTemplate('admin_errorloglink', $layer+3);
		$sixth = $layered[$layer+3];
		$array = Array
		(
			Array
			(
				'{1}', $start + ($limit * 2)
			),

			Array
			(
				'{2}', '&limit=' . $limit
			),

			Array
			(
				'{3}', ($start / $limit) + 3
			)
		);
		$sixth = $suit->templates->implosion($sixth, $array);
	}
	else
	{
		$sixth = '';
	}

	$errorlog_options = array('orderby' => 'date', 'orderby_type' => 'desc', 'limit' => $start + ($limit * 3) . ':1');
	$errorlog = $suit->mysql->select('' . TBL_PREFIX . 'errorlog', '*', $errorlog_options);
	if ($errorlog)
	{
		$errorlog_options = array('orderby' => 'date', 'orderby_type' => 'desc', 'limit' => '1:1');
		$errorlog = $suit->mysql->select('' . TBL_PREFIX . 'errorlog', '*', $errorlog_options);
		if ($errorlog)
		{
			while ($row = mysql_fetch_assoc($errorlog))
			{
				$num = $row['id'];
				if (($num / $limit) != (round(($num / $limit))))
				{
					do
					{
						$num--;
					}
					while (($num / $limit) != (round(($num / $limit))));
				}
				$layered[$layer+3] = $suit->templates->getDynamicTemplate('admin_errorloglink', $layer+3);
				$seventh = $layered[$layer+3];
				$lcontent = $suit->language->getLanguage('last');
				$array = Array
				(
					Array
					(
						'{1}', $num
					),
		
					Array
					(
						'{2}', '&limit=' . $limit
					),
		
					Array
					(
						'{3}', $lcontent
					)
				);
				$seventh = $suit->templates->implosion($seventh, $array);
			}
		}
	}
	else
	{
		$seventh = '';
	}

	$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_errorloglimit', $layer+1);
	$limit2 = $layered[$layer+1];
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

	$output = $layered[$layer];

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
		)
	);
	$output = $suit->templates->implosion($output, $array);

	$layered[$layer] = $output;
}
else
{
	$layered[$layer] = str_replace('{1}', '', $layered[$layer]);
}
?>
