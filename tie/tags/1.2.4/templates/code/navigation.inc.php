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
	public $settings = array();

	/**
	The __construct()'s main use is to set-up a reference, so we can avoid globalizing it.
	**@param object Reference
	**@param array Navigation Conditions
	**/
	public function __construct(&$owner, $array = false)
	{
		$this->owner = &$owner;
		$array = ($array !== false) ?
			$array :
			$this->owner->config['navigation']['array'];
		//Limit the number of rows.
		$this->settings['start'] = (isset($array['start']) && ($array['start'])) ?
			intval($array['start']) :
			0;
		$this->settings['limit'] = (isset($array['limit']) && ($array['limit'])) ?
			intval($array['limit']) :
			10;
		//Search
		$this->settings['search'] = (isset($array['search']) && ($array['search'])) ?
			$array['search'] :
			'';
		$this->settings['order'] = (isset($array['order']) && (!strcmp($array['order'], 'desc'))) ?
			'desc' :
			'asc';
		$this->settings['order_reverse'] = (isset($array['order']) && (!strcmp($array['order'], 'asc'))) ?
			'asc' :
			'desc';
		//Controls whether or not all checkboxes are selected.
		$this->settings['select'] = (isset($array['select']) && (!strcmp($array['select'], 'true')));
	}

	/**
	Checks for illegal conditions.
	**@param string Bad Request Template
	**@returns none
	**/
	public function logistics($badrequest = false)
	{
		$badrequest = ($badrequest !== false) ?
			strval($badrequest) :
			$this->owner->config['templates']['badrequest'];
		//Check if the starting value is not equal to 0.
		if ($this->settings['start'] != 0)
			//The start value cannot be less than 1.
			if (!(($this->settings['start'] >= 0) && ($this->settings['start'] % $this->settings['limit'] == 0)))
				$this->owner->owner->getTemplate($badrequest);
		//You cannot limit to any less than 1.
		if ($this->settings['limit'] <= 0)
			$this->owner->owner->getTemplate($badrequest);
	}

	/**
	Creates a list of links
	**@param integer Number of Rows
	**@param integer Check to see if button should show.
	**@param integer Starting Row
	**@param string Text to display
	**@param boolean Whether or not the link is in front of the starting link.
	**@param string Pagelink Template
	**/
	private function pageLink($count, $check, $start, $display, $ahead, $navigation_pagelink)
	{
		$return = '';
		$count = intval($count);
		$check = intval($check);
		$start = intval($start);
		$display = strval($display);
		$navigation_pagelink = strval($navigation_pagelink);
		$path = $this->path($_SERVER['SCRIPT_NAME'], array('limit', 'orderby', 'search', 'start')); //Generate the base path.
		$success = false;
		if ($ahead)
		{
			if ($count - 1 >= $check)
				$success = true;
		}
		else
			if ($check >= 0)
				$success = true;
		//We cannot have a negative integer for the starting value.
		if ($start == -1)
			$start = $check;
		if ($success)
		{
			$pagelink = $this->owner->owner->getTemplate($navigation_pagelink);
			$array = array_merge
			(
				array
				(
					array('<display>', $display),
					array('<limit>', $this->settings['limit']),
					array('<order>', $this->settings['order']),
					array('<path>', htmlentities($path)),
					array('<start>', $start),
					array('<search>', $this->settings['search'])
				),
				$this->owner->parseConditional('if current', false, $pagelink, 'else current')
			);
			$return = $this->owner->replace($array, $pagelink);
		}
		return $return;
	}

	/**
	Generates an array to create a pagination.
	**@param integer Number of Results
	**@param integer How many pages on each side
	**@param string Pagelink Template
	**@returns array Pagination Links
	**/
	public function pagination($count, $pages = false, $navigation_pagelink = false)
	{
		$count = intval($count);
		$pages = ($pages !== false) ?
			intval($pages) :
			$this->owner->config['navigation']['pages'];
		$navigation_pagelink = ($navigation_pagelink !== false) ?
			strval($navigation_pagelink) :
			$this->owner->config['templates']['navigation_pagelink'];
		$exclude = array('limit', 'orderby', 'search', 'start');
		$path = $this->path($_SERVER['SCRIPT_NAME'], $exclude);
		$return = array();
		//Create the Current Page's Link
		$return['current'] = $this->owner->owner->getTemplate($navigation_pagelink);
		$array = array_merge
		(
			array
			(
				array('<display>', ($this->settings['start'] / $this->settings['limit']) + 1),
				array('<limit>', $this->settings['limit']),
				array('<order>', $this->settings['order']),
				array('<path>', htmlentities($path)),
				array('<start>', $this->settings['start']),
				array('<search>', $this->settings['search'])
			),
			$this->owner->parseConditional('if current', true, $return['current'], 'else current')
		);
		$return['current'] = $this->owner->replace($array, $return['current']);
		$num = $this->reduce($count - 1);
		//Create the Other Page Links
		$array = array();
		$array[] = $this->pageLink($count, ($this->settings['start'] - ($this->settings['limit'] * ($pages + 1))), 0, $this->owner->language['first'], false, $navigation_pagelink);
		for ($x = $pages; $x != 0; $x--)
			$array[] = $this->pageLink($count, ($this->settings['start'] - ($this->settings['limit'] * $x)), -1, (($this->settings['start'] / $this->settings['limit']) - ($x - 1)), false, $navigation_pagelink);
		$return['previous'] = implode(' ', $array);
		$array = array();
		for ($x = 1; $x <= $pages; $x++)
			$array[] = $this->pageLink($count, ($this->settings['start'] + ($this->settings['limit'] * $x)), -1, (($this->settings['start'] / $this->settings['limit']) + ($x + 1)), true, $navigation_pagelink);
		$array[] = $this->pageLink($count, ($this->settings['start'] + ($this->settings['limit'] * ($pages + 1))), strval($num), $this->owner->language['last'], true, $navigation_pagelink);
		$return['next'] = implode(' ', $array);
		return $return;
	}

	/**
	Generates a URL without the excluded get variables, plus either ? or &.
	**@param string URL
	**@param array Querystrings to exclude
	**@returns string Formatted URL
	**/
	public function path($return, $exclude)
	{
		$return = strval($return);
		$querychar = '?';
		foreach ($_GET as $key => $value)
			if (is_array($exclude))
				if (!in_array($key, $exclude))
				{
					//If the GET value is not excluded, then we may move on concatenating to form a querystring.
					$return .= $querychar . $key . '=' . $value;
					if (!strcmp($querychar, '?'))
						$querychar = '&';
				}
		return $return . $querychar;
	}

	/**
	Sends a Refresh HTTP Header and a message to redirect.
	**@param integer Intervals to wait for redirect
	**@param string Message to display prior to redirect
	**@param string URL to redirect to
	**@param string Redirect Template
	**@returns none
	**/
	public function redirect($url, $message = '', $refresh = false, $navigation_redirect = false)
	{
		$url = strval($url);
		$message = strval($message);
		$refresh = ($refresh !== false) ?
			intval($refresh) :
			$this->owner->config['navigation']['refresh'];
		$navigation_redirect = ($navigation_redirect !== false) ?
			strval($navigation_redirect) :
			$this->owner->config['templates']['navigation_redirect'];
		$content = $this->owner->owner->getTemplate($navigation_redirect);
		if ($message != '')
		{
			//If a redirect message is present, then we will use the template.
			$seconds = str_replace('<seconds>', $refresh, $this->owner->language['seconds']);
			$seconds = $this->owner->replace($this->owner->parseConditional('if s', ($refresh != 1), $seconds), $seconds);
			$array = array_merge
			(
				array
				(
					array('<message>', $message),
					array('<name>', $this->owner->language['redirecting']),
					array('<seconds>', $seconds),
					array('<url>', htmlentities($url))
				),
				$this->owner->parseConditional('section separator', false, $content)
			);
			$content = $this->owner->replace($array, $content);
		}
		else
			//Otherwise, send no content at all.
			$content = '';
		header('refresh: ' . $refresh . '; url=' . $url);
		exit($content);
	}

	/**
	Reduces an integer down to a multiple of the limit.
	**@param integer Integer
	**@returns integer Reduced Integer
	**/
	public function reduce($return, $once = false)
	{
		$return = intval($return);
		if ($return % $this->settings['limit'] || $once)
			//The supplied value is not a multiple of the limit, so subtract 1 from it until it is.
			do
				$return--;
			while ($return % $this->settings['limit']);
		return $return;
	}
}
$suit->tie->navigation = new NAVIGATION($suit->tie);
?>