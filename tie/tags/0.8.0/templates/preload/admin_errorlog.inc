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
$suit->templates->getTemplate('init');
$output = $suit->templates->vars['local']->parsePhrases($output);

if ($suit->templates->vars['local']->loggedIn($suit) == 2)

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

	if (isset($_POST['errorlog_clear']))

	{

		$suit->db->truncate(TBL_PREFIX . 'errorlog');

	}

}

?>