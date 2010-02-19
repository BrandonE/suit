<?php
/**
**@This program is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@This program is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with this program.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class SUIT
{
	public $chain = array();

	private $filepath;

	private $content;
	
	private $suit;

	public $templates;

	public $vars = array();

	/**
	http://www.suitframework.com/docs/SUIT+Construct#howitworks
	**/
	public function __construct($templates, $suit = 'suit', $content = 'content')
  	{
		$this->templates = $templates;
		$this->suit = $suit;
		$this->content = $content;
		if (phpversion() <= '4.4.9')
			$this->error('SUIT Error: PHP Version must be greater than 4.4.9. See http://www.suitframework.com/docs/error1/');
		elseif (!is_dir($this->templates) || !is_dir($this->templates . '/content') || !is_dir($this->templates . '/code') || !is_dir($this->templates . '/glue'))
			$this->error('SUIT Error: Templates directory or it\'s required one of it\'s required subdirectories does not exist. See http://www.suitframework.com/docs/error4/');
	}

	private function error($error, $type = 'Error')
	{
		if (ini_get('error_reporting'))
		{
			$backtrace = debug_backtrace();
			echo '<br />
<b>SUIT ' . $type . '</b>:  ' . $error . ' in <b>' . $backtrace[1]['file'] . '</b> on line <b>' . $backtrace[1]['line'] . '</b><br />';
			error_log('SUIT ' . $type . ':  ' . $error . ' in ' . $backtrace[1]['file'] . ' on line ' . $backtrace[1]['line']);
		}
		if ($type == 'Error')
			exit;
	}

	/**
	http://www.suitframework.com/docs/getTemplate#howitworks
	**/
	public function getTemplate($template)
	{
		$template = str_replace('../', '', str_replace('..\\', '', strval($template)));
		$return = '';
		if (!in_array($template, $this->chain))
			if (file_exists($this->filepath = $this->templates . '/glue/' . $template . '.txt'))
			{
				$array = explode('=', file_get_contents($this->filepath));
				$array = $this->glueUnescape($array);
				if (isset($array[0]) && ($array[0]))
				{
					if (file_exists($this->filepath = $this->templates . '/content/' . str_replace('../', '', str_replace('..\\', '', strval($array[0]))) . '.tpl'))
						$return = file_get_contents($this->filepath);
					unset($array[0]);
				}
				foreach ($array as $value)
					if ($value)
						if (file_exists($this->filepath = $this->templates . '/code/' . str_replace('../', '', str_replace('..\\', '', strval($value))) . '.inc.php'))
						{
							$this->chain[$template] = $template;
							$return = $this->includeFile($return);
							unset($this->chain[$template]);
						}
			}
			else
				$this->error('Template ' . $template . ' Not Found. See http://www.suitframework.com/docs/error2/', 'Warning');
		else
			$this->error('Infinite Loop Caused by ' . $template . '. See http://www.suitframework.com/docs/error3/', 'Warning');
		return $return;
	}

	/**
	http://www.suitframework.com/docs/glueUnescape#howitworks
	**/
	public function glueUnescape($return)
	{
		foreach ($return as $key => $value)
			do
			{
				$count = 0;
				while (isset($return[$key][strlen($return[$key]) - ($count + 1)]) && ($return[$key][strlen($return[$key]) - ($count + 1)] == '\\'))
					$count++;
				$condition = $count % 2;
				if ($condition)
					$count++;
				if ($count)
					$return[$key] = substr($return[$key], 0, -($count / 2));
				if ($condition)
				{
					$return[$key] = $return[$key] . '=' . $return[$key+1];
					unset($return[$key+1]);
					$return = array_values($return);
				}
				elseif (strstr($return[$key], '\\\\'))
					$return[$key] = str_replace('\\\\', '\\', substr($return[$key], 0, strlen($return[$key]) - ($count / 2))) . substr($return[$key], strlen($return[$key]) - ($count / 2));
			}
			while ($condition);
		return $return;
	}

	private function includeFile($content)
	{
		${$this->suit} = &$this;
		${$this->content} = $content;
		include $this->filepath;
		return ${$this->content};
	}
}
?>