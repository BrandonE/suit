<?php
/**
**@This file is part of TIE.
**@TIE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@TIE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with TIE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class NAVIGATION
{
	public $owner;

	public $settings = array();

	/**
	http://www.suitframework.com/docs/Navigation+Construct#howitworks
	**/
	public function __construct(&$owner, $array = false, $limit = false)
	{
		$this->owner = &$owner;
		$array = ($array !== false) ?
			$array :
			$this->owner->config['navigation']['array'];
		$limit = intval(($limit !== false) ?
			$limit :
			$this->owner->config['navigation']['limit']);
		$this->settings['start'] = (isset($array['start'])) ?
			intval($array['start']) :
			0;
		$this->settings['limit'] = (isset($array['limit'])) ?
			intval($array['limit']) :
			$limit;
		$this->settings['search'] = (isset($array['search'])) ?
			$array['search'] :
			'';
		$this->settings['order'] = (isset($array['order']) && $array['order'] == 'desc') ?
			'desc' :
			'asc';
		$this->settings['order_reverse'] = (isset($array['order']) && $array['order'] == 'asc') ?
			'asc' :
			'desc';
		$this->settings['check'] = (isset($array['check']) && $array['check'] == 'true');
	}

	/**
	http://www.suitframework.com/docs/logistics#howitworks
	**/
	public function logistics($badrequest = false)
	{
		$badrequest = strval(($badrequest !== false) ?
			$badrequest :
			$this->owner->config['templates']['badrequest']);
		if ($this->settings['start'] != 0)
			if (!(($this->settings['start'] >= 0) && ($this->settings['start'] % $this->settings['limit'] == 0)))
				$this->owner->owner->getTemplate($badrequest);
		if ($this->settings['limit'] <= 0)
			$this->owner->owner->getTemplate($badrequest);
	}

	private function pageLink($count, $check, $start, $display, $ahead, $navigation_pagelink)
	{
		$return = '';
		$count = intval($count);
		$check = intval($check);
		$start = intval($start);
		$display = strval($display);
		$navigation_pagelink = strval($navigation_pagelink);
		$path = $this->path($_SERVER['SCRIPT_NAME'], array('check', 'limit', 'orderby', 'search', 'start'));
		$success = false;
		if ($ahead)
		{
			if ($count - 1 >= $check)
				$success = true;
		}
		else
			if ($check >= 0)
				$success = true;
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
				$this->owner->parseConditional('if checked', ($this->settings['check']), $pagelink, 'else checked'),
				$this->owner->parseConditional('if current', false, $pagelink, 'else current')
			);
			$return = $this->owner->replace($array, $pagelink);
		}
		return $return;
	}

	/**
	http://www.suitframework.com/docs/pagination#howitworks
	**/
	public function pagination($count, $pages = false, $navigation_pagelink = false)
	{
		$count = intval($count);
		$pages = intval(($pages !== false) ?
			$pages :
			$this->owner->config['navigation']['pages']);
		$navigation_pagelink = strval(($navigation_pagelink !== false) ?
			$navigation_pagelink :
			$this->owner->config['templates']['navigation_pagelink']);
		$exclude = array('limit', 'orderby', 'search', 'start');
		$path = $this->path($_SERVER['SCRIPT_NAME'], $exclude);
		$return = array();
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
			$this->owner->parseConditional('if checked', ($this->settings['check']), $return['current'], 'else checked'),
			$this->owner->parseConditional('if current', true, $return['current'], 'else current')
		);
		$return['current'] = $this->owner->replace($array, $return['current']);
		$num = $this->reduce($count - 1);
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
	http://www.suitframework.com/docs/path#howitworks
	**/
	public function path($return, $exclude)
	{
		$return = strval($return);
		$querychar = '?';
		foreach ($_GET as $key => $value)
			if (is_array($exclude))
			{
				if (!in_array($key, $exclude))
				{
					if (is_array($value))
					{
						foreach ($value as $value2)
						{
							$return .= $querychar . $key . '[]=' . $value2;
							if ($querychar == '?')
								$querychar = '&';
						}
					}
					else
					{
						$return .= $querychar . $key . '=' . $value;
						if ($querychar == '?')
							$querychar = '&';
					}
				}
			}
			else
				$this->warning($this->language['invalidtypearray']);
		return $return . $querychar;
	}

	/**
	http://www.suitframework.com/docs/redirect#howitworks
	**/
	public function redirect($url, $message = '', $refresh = false, $navigation_redirect = false)
	{
		$url = strval($url);
		$message = strval($message);
		$refresh = intval(($refresh !== false) ?
			$refresh :
			$this->owner->config['navigation']['refresh']);
		$navigation_redirect = strval(($navigation_redirect !== false) ?
			$navigation_redirect :
			$this->owner->config['templates']['navigation_redirect']);
		$content = $this->owner->owner->getTemplate($navigation_redirect);
		if ($refresh)
		{
			$seconds = str_replace('<seconds>', $refresh, $this->owner->language['seconds']);
			$seconds = $this->owner->replace($this->owner->parseConditional('if s', ($refresh != 1), $seconds), $seconds);
			$array = array
			(
				$this->owner->parseConditional('if message', $message, $content),
				array_merge
				(
					array
					(
						array('<message>', $message),
						array('<name>', $this->owner->language['redirecting']),
						array('<seconds>', $seconds),
						array('<url>', htmlentities($url))
					),
					$this->owner->parseConditional('section separator', false, $content)
				)
			);
			$content = $this->owner->replace($array, $content);
		}
		else
			$content = '';
		header('refresh: ' . $refresh . '; url=' . $url);
		exit($content);
	}

	/**
	http://www.suitframework.com/docs/reduce#howitworks
	**/
	public function reduce($return, $once = false)
	{
		$return = intval($return);
		if ($return % $this->settings['limit'] || $once)
			do
				$return--;
			while ($return % $this->settings['limit']);
		return $return;
	}
}
$suit->tie->navigation = new NAVIGATION($suit->tie);
?>