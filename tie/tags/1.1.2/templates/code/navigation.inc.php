<?php
/**
**@This file is part of TIE.
**@TIE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@TIE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with TIE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class NAVIGATION
{
	/**
	Owner
	**@var object
	**/
	public $owner;

	/**
	Settings
	**@var array
	**/
	public $settings;

	/**
	The __construct()'s main use is to set-up a reference, so we can avoid globalizing it.
	**@param object Reference
	**/
	function __construct(&$owner, $array = '')
	{
		$this->owner = &$owner;
		$array = ($array) ? $array : $_GET;
		//Limit the number of rows.
		$this->settings['start'] = (isset($array['start']) && ($array['start'])) ? intval($array['start']) : 0;
		$this->settings['limit'] = (isset($array['limit']) && ($array['limit'])) ? intval($array['limit']) : 10;
		//Search
		$this->settings['search'] = (isset($array['search']) && ($array['search'])) ? $array['search'] : '';
		$this->settings['orderby_type'] = (isset($array['orderby']) && (!strcmp($array['orderby'], 'desc'))) ? 'desc' : 'asc';
		$this->settings['orderby_type_reverse'] = (isset($v['orderby']) && (!strcmp($array['orderby'], 'asc'))) ? 'asc' : 'desc';
		//Controls whether or not all checkboxes are selected.
		$this->settings['select'] = (isset($array['select']) && (!strcmp($array['select'], 'true')));
	}

	/**
	Checks for illegal conditions.
	**@returns none
	**/
	public function logistics()
	{
		//Check if the starting value is not equal to 0.
		if ($this->settings['start'] != 0)
		{
			if (!(($this->settings['start'] >= 0) && ($this->settings['start'] % $this->settings['limit'] == 0)))
			{
				//The start value cannot be less than 1
				$this->owner->owner->getTemplate('badrequest');
			}
		}
		if ($this->settings['limit'] <= 0)
		{
			//You can't limit to any less than 1.
			$this->owner->owner->getTemplate('badrequest');
		}
	}

	/**
	Creates a list of links
	**@param integer Number of Rows
	**@param integer Resultset Check
	**@param integer Starting Row
	**@param string Text to display
	**@param boolean Database Usage
	**/
	private function pageLink($count, $check, $start, $display, $ahead)
	{
		$exclude = array('limit', 'orderby', 'search', 'start'); //Exclude these querystrings from the base path.
		$path = $this->path($_SERVER['SCRIPT_NAME'], $exclude); //Generate the base path.
		$pagelink = $this->owner->owner->getTemplate('navigation_pagelink');
		$pagelink = $this->owner->parseConditional('current', false, $pagelink);
		$success = false;
		if ($ahead)
		{
			if ($count - 1 >= $check)
			{
				$success = true;
			}
		}
		else
		{
			if ($check >= 0)
			{
				$success = true;
			}
		}
		if ($start == -1)
		{
			//We cannot have a negative integer for the starting value.
			$start = $check;
		}
		$array = array
		(
			array('<display>', $display),
			array('<limit>', $this->settings['limit']),
			array('<orderby>', $this->settings['orderby_type']),
			array('<path>', htmlentities($path)),
			array('<start>', $start),
			array('<search>', $this->settings['search'])
		);
		//If everything was a success, return it. Otherwise, nothing is returned at all.
		$return = ($success) ? $this->owner->replace($array, $pagelink) : '';
		return $return;
	}

	/**
	Generates an array to create a pagination.
	**@param integer Number of Results
	**@returns array Pagination Links
	**/
	public function pagination($count)
	{
		$exclude = array('limit', 'orderby', 'search', 'start');
		$path = $this->path($_SERVER['SCRIPT_NAME'], $exclude);
		$return = array();
		//Create the Current Page's Link
		$return[4] = $this->owner->owner->getTemplate('navigation_pagelink');
		$array = array
		(
			array('<display>', ($this->settings['start'] / $this->settings['limit']) + 1),
			array('<limit>', $this->settings['limit']),
			array('<orderby>', $this->settings['orderby_type']),
			array('<path>', htmlentities($path)),
			array('<start>', $this->settings['start']),
			array('<search>', $this->settings['search'])
		);
		$return[4] = $this->owner->replace($array, $return[4]);
		$return[4] = $this->owner->parseConditional('current', true, $return[4]);
		$num = $this->reduce($count);
		//Create the Other Page Links
		$return[1] = $this->pageLink($count, ($this->settings['start'] - ($this->settings['limit'] * 3)), 0, $this->owner->language['first'], false);
		$return[2] = $this->pageLink($count, ($this->settings['start'] - ($this->settings['limit'] * 2)), -1, (($this->settings['start'] / $this->settings['limit']) - 1), false);
		$return[3] = $this->pageLink($count, ($this->settings['start'] - $this->settings['limit']), -1, ($this->settings['start'] / $this->settings['limit']), false);
		$return[5] = $this->pageLink($count, ($this->settings['start'] + $this->settings['limit']), -1, (($this->settings['start'] / $this->settings['limit']) + 2), true);
		$return[6] = $this->pageLink($count, ($this->settings['start'] + ($this->settings['limit'] * 2)), -1, (($this->settings['start'] / $this->settings['limit']) + 3), true);
		$return[7] = $this->pageLink($count, ($this->settings['start'] + ($this->settings['limit'] * 3)), strval($num), $this->owner->language['last'], true);
		return $return;
	}

	/**
	Generates a querystring based on the supplied path.
	**@param string URL
	**@param array Querystrings to exclude
	**@returns string Formatted URL
	**/
	public function path($return, $exclude)
	{
		$querychar = '?';
		foreach ($_GET as $key => $value)
		{
			if (!in_array($key, $exclude))
			{
				//If the GET value is not excluded, then we may move on concatenating to form a querystring.
				$return .= $querychar . $key . '=' . $value;
				if (!strcmp($querychar, '?'))
				{
					$querychar = '&';
				}
			}
		}
		return $return . $querychar;
	}

	/**
	Sends a Refresh HTTP Header and a message to redirect.
	**@param string Message to display prior to redirect
	**@param integer Intervals to wait for redirect
	**@param string URL to redirect to
	**@returns none
	**/
	public function redirect($refresh, $url, $message = '')
	{
		$output = $this->owner->owner->getTemplate('navigation_redirect');
		if ($message != '')
		{
			//If a redirect message is present, then we will use the template.
			$seconds = $this->owner->language['seconds'];
			$seconds = str_replace('<seconds>', $refresh, $seconds);
			$seconds = $this->owner->parseConditional('s', ($refresh != 1), $seconds);
			$array = array
			(
				array('<message>', $message),
				array('<name>', $this->owner->language['redirecting']),
				array('<seconds>', $seconds),
				array('<url>', htmlentities($url))
			);
			$output = $this->owner->replace($array, $output);
		}
		else
		{
			//Otherwise, send no content at all.
			$output = '';
		}
		header('refresh: ' . $refresh . '; url=' . $url);
		exit($output);
	}

	/**
	Reduces an integer down to a multiple of a limit.
	**@param integer Remainder
	**/
	public function reduce($return)
	{
		if ($return % $this->settings['limit'])
		{
			//The supplied value is not a multiple of the limit, so subtract 1 from it until it is.
			do
			{
				$return--;
			}
			while ($return % $this->settings['limit']);
		}
		return $return;
	}
}
$suit->tie->navigation = new NAVIGATION($suit->tie);
?>