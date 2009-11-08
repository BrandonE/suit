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
		$this->settings['start'] = (isset($array['start'])) ? intval($array['start']) : 0;
		$this->settings['limit'] = (isset($array['limit'])) ? intval($array['limit']) : 10;
		//Search
		$this->settings['search'] = (isset($array['search']) && ($array['search'])) ? $array['search'] : '';
		$this->settings['orderby_type'] = (isset($array['orderby']) && (!strcmp($array['orderby'], 'desc'))) ? 'desc' : 'asc';
		$this->settings['orderby_type_reverse'] = (isset($v['orderby']) && (!strcmp($array['orderby'], 'asc'))) ? 'asc' : 'desc';
		//Controls whether or not all checkboxes are selected.
		$this->settings['select'] = (isset($array['select']) && (!strcmp($array['select'], 'true')));
	}
	
	public function logistics()
	{
		if ($this->settings['start'] != 0)
		{
			if (!(($this->settings['start'] >= 0) && ($this->settings['start'] % $this->settings['limit'] == 0)))
			{
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
	Creates List of Links
	**@param integerCheck
	**@param integerStart
	**@param string Text to Display
	**@param integerDB Check
	**@param integerLimit
	**@param string Template
	**@returns string List of Links
	**/
	public function pageLink($count, $check, $start, $display, $db)
	{
		$exclude = array('limit', 'orderby', 'search', 'start');
		$path = $this->path($_SERVER['SCRIPT_NAME'], $exclude);
		$pagelink = $this->owner->owner->getTemplate('navigation_pagelink');
		$pagelink = str_replace('<class>', 'link', $pagelink);
		$success = false;
		if ($db != true)
		{
			if ($check >= 0)
			{
				$success = true;
			}
		}
		else
		{
			if ($count-1 >= $check)
			{
				$success = true;
			}
		}
		if (!strcmp($start, -1))
		{
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
		$return = ($success) ? $this->owner->replace($pagelink, $array) : '';
		return $return;
	}
	
	public function pagination($count)
	{
		$exclude = array('limit', 'orderby', 'search', 'start');
		$path = $this->path($_SERVER['SCRIPT_NAME'], $exclude);
		$return = array();
		$return[4] = $this->owner->owner->getTemplate('navigation_pagelink');
		$array = array
		(
			array('<class>', 'current'),
			array('<display>', ($this->settings['start'] / $this->settings['limit']) + 1),
			array('<limit>', $this->settings['limit']),
			array('<orderby>', $this->settings['orderby_type']),
			array('<path>', htmlentities($path)),
			array('<start>', $this->settings['start']),
			array('<search>', $this->settings['search'])
		);
		$return[4] = $this->owner->replace($return[4], $array);
		$num = $this->reduce($count);
		$return[1] = $this->pageLink($count, ($this->settings['start'] - ($this->settings['limit'] * 3)), 0, $this->owner->language['first'], false);
		$return[2] = $this->pageLink($count, ($this->settings['start'] - ($this->settings['limit'] * 2)), -1, (($this->settings['start'] / $this->settings['limit']) - 1), false);
		$return[3] = $this->pageLink($count, ($this->settings['start'] - $this->settings['limit']), -1, ($this->settings['start'] / $this->settings['limit']), false);
		$return[5] = $this->pageLink($count, ($this->settings['start'] + $this->settings['limit']), -1, (($this->settings['start'] / $this->settings['limit']) + 2), true);
		$return[6] = $this->pageLink($count, ($this->settings['start'] + ($this->settings['limit'] * 2)), -1, (($this->settings['start'] / $this->settings['limit']) + 3), true);
		$return[7] = $this->pageLink($count, ($this->settings['start'] + ($this->settings['limit'] * 3)), strval($num), $this->owner->language['last'], true);
		return $return;
	}
	
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
	
	public function redirect($message, $refresh, $url)
	{
		$output = $this->owner->owner->getTemplate('navigation_redirect');
		if ($message != '')
		{
			$seconds = $this->owner->language['seconds'];
			$s = ($refresh != 0) ? 's' : '';
			$array = array
			(
				array('<s>', $s),
				array('<seconds>', $refresh)
			);
			$seconds = $this->owner->replace($seconds, $array);
			$output = str_replace('<message>', $message, $output);
			$array = array
			(
				array('<message>', $message),
				array('<name>', $this->owner->language['redirecting']),
				array('<seconds>', $seconds),
				array('<url>', htmlentities($url))
			);
			$output = $this->owner->replace($output, $array);
		}
		else
		{
			$output = '';
		}
		header('refresh: ' . $refresh . '; url=' . $url);
		echo $output;
		exit;
	}
	
	public function reduce($return)
	{
		if ($return != 0)
		{
			if ($return % $this->settings['limit'] == 0)
			{
				$return--;
			}
			if ($return % $this->settings['limit'])
			{
				do
				{
					$return--;
				}
				while ($return % $this->settings['limit']);
			}
		}
		return $return;
	}
}
$suit->tie->navigation = new NAVIGATION($suit->tie);
?>