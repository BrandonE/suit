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

	public $config = array();

	public $error = '';

	private $evalstring = '';

	private $filepath = '';

	public $log = array
	(
		'getTemplate' => array(),
		'replace' => array(),
		'parse' => array()
	);

	public $vars = array();

	public $version = '1.3.3';

	/**
	http://www.suitframework.com/docs/SUIT+Construct#howitworks
	**/
	public function __construct($config)
  	{
		$this->config = $config;
		if (is_array($this->config) && is_array($this->config['templates']))
		{
			if (phpversion() <= '4.4.9')
				$this->error('PHP Version must be greater than 4.4.9. See http://www.suitframework.com/docs/errors#error1');
			elseif (!is_dir($this->config['templates']['code']) || !is_dir($this->config['templates']['content']) || !is_dir($this->config['templates']['glue']))
				$this->error('One of the template directories does not exist. See http://www.suitframework.com/docs/errors#error4');
		}
		else
			$this->error('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5');
	}

	public function __destruct()
	{
		echo $this->error;
	}

	public function error($error, $plain = NULL, $type = 'Error', $key = 1)
	{
		$plain = strval(isset($plain) ?
			$plain :
			$error);
		if (ini_get('error_reporting'))
		{
			$backtrace = debug_backtrace();
			$this->error .= '<br />
<b>SUIT ' . $type . '</b>:  ' . $error . ' in <b>' . $backtrace[$key]['file'] . '</b> on line <b>' . $backtrace[$key]['line'] . '</b><br />';
			error_log('SUIT ' . $type . ':  ' . $plain . ' in ' . $backtrace[$key]['file'] . ' on line ' . $backtrace[$key]['line']);
		}
		if ($type == 'Error')
			exit;
	}

	/**
	http://www.suitframework.com/docs/escape#howitworks
	**/
	public function escape($symbols, $escape, $return)
	{
		$escape = strval($escape);
		$return = strval($return);
		$replace = array();
		if (is_array($symbols))
		{
			foreach ($symbols as $value)
				$replace[] = array($value, $escape . $value);
			$pos = -1;
			do
			{
				$smallest = false;
				foreach ($symbols as $key => $value)
				{
					$symbolpos = $this->strpos($return, $value, $pos + 1);
					if ($symbolpos !== false)
					{
						if ($symbolpos < $smallest || $smallest === false)
							$smallest = $symbolpos;
					}
					else
						unset($symbols[$key]);
				}
				$pos = ($smallest !== false) ?
					$smallest :
					strlen($return);
				if ($pos !== false)
				{
					$count = 0;
					if ($escape)
					{
						while (abs($start = $pos - $count - strlen($escape)) == $start && substr($return, $start, strlen($escape)) == $escape)
							$count += strlen($escape);
						$count = $count / strlen($escape);
					}
					$return = substr_replace($return, str_repeat($escape, $count * 2), $pos - ($count * strlen($escape)), strlen($escape) * $count);
					$pos += $count * strlen($escape);
				}
			}
			while ($smallest !== false);
		}
		else
		{
			$this->error('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5', NULL, 'Warning');
			$log['errors'] = true;
		}
		return $this->replace($replace, $return);
	}

	private function evalNode($case, $nodes, $replace, $escape)
	{
		${$this->config['variables']['suit']} = &$this;
		return eval($this->evalstring);
	}

	/**
	http://www.suitframework.com/docs/explodeUnescape#howitworks
	**/
	public function explodeUnescape($explode, $escape, $glue)
	{
		$return = array();
		$explode = strval($explode);
		$escape = strval($escape);
		$glue = strval($glue);
		$pos = -1;
		do
		{
			$temppos = $this->strpos($glue, $explode, $pos + 1);
			$pos = ($temppos !== false) ?
				$temppos :
				strlen($glue);
			if ($pos !== false)
			{
				$count = 0;
				if ($escape)
				{
					while (abs($start = $pos - $count - strlen($escape)) == $start && substr($glue, $start, strlen($escape)) == $escape)
						$count += strlen($escape);
					$count = $count / strlen($escape);
				}
				$condition = $count % 2;
				if ($condition)
					$count++;
				if ($count)
				{
					$glue = substr_replace($glue, '', $pos - (($count / 2) * strlen($escape)), ($count / 2) * strlen($escape));
					$pos -= ($count / 2) * strlen($escape);
				}
				if (!$condition)
				{
					$return[] = substr($glue, 0, $pos);
					$glue = substr_replace($glue, '', 0, $pos + strlen($explode));
					$pos = -1;
				}
			}
		}
		while ($temppos !== false);
		return $return;
	}

	private function firstKeySort($a, $b)
	{
		$return = false;
		if (is_array($a) && is_array($b))
		{
			if (array_key_exists(0, $a) && array_key_exists(0, $b))
				$return = (strlen(strval($a[0])) < strlen(strval($b[0])));
		}
		return $return;
	}

	/**
	http://www.suitframework.com/docs/getSection#howitworks
	**/
	public function getSection($string, &$content, $open = NULL, $close = NULL, $end = NULL, $escape = NULL)
	{
		$return = array();
		$string = strval($string);
		$content = strval($content);
		$open = strval((isset($open)) ?
			$open :
			$this->config['parse']['open']);
		$close = strval((isset($close)) ?
			$close :
			$this->config['parse']['close']);
		$end = strval((isset($end)) ?
			$end :
			$this->config['parse']['end']);
		$escape = strval((isset($escape)) ?
			$escape :
			$this->config['parse']['escape']);
		$node = array($open . $string . $close, $open . $end . $string . $close);
		$stack = array();
		$opensmallest = 0;
		$closesmallest = 0;
		$pos = 0;
		do
		{
			if ($opensmallest !== false)
				$opensmallest = $this->strpos($content, $node[0], $pos);
			if ($closesmallest !== false)
				$closesmallest = $this->strpos($content, $node[1], $pos);
			if ($opensmallest < $closesmallest && $opensmallest !== false)
			{
				$pos = $opensmallest;
				if (!$this->parseUnescape($pos, $escape, $content))
					$stack[] = $pos;
				$pos += strlen($node[0]);
			}
			elseif ($closesmallest !== false)
			{
				$pos = $closesmallest;
				if (!$this->parseUnescape($pos, $escape, $content) && !empty($stack))
				{
					$openpop = array_pop($stack);
					$return[] = substr($content, $openpop + strlen($node[0]), $pos - $openpop - strlen($node[0]));
				}
				$pos += strlen($node[1]);
			}
			else
				$pos = false;
		}
		while ($pos !== false);
		return $return;
	}

	/**
	http://www.suitframework.com/docs/getTemplate#howitworks
	**/
	public function getTemplate($template)
	{
		$template = str_replace('../', '', str_replace('..\\', '', strval($template)));
		$return = '';
		$backtrace = debug_backtrace();
		$this->log['getTemplate'][] = array
		(
			'backtrace' => $backtrace[0],
			'code' => array(),
			'content' => array(false, false, false),
			'glue' => array($template, true, false)
		);
		end($this->log['getTemplate']);
		$key = key($this->log['getTemplate']);
		if (!in_array($template, $this->chain))
			if (is_file($this->filepath = $this->config['templates']['glue'] . '/' . $template . '.txt'))
			{
				$array = $this->explodeUnescape('=', '\\', file_get_contents($this->filepath));
				if ($array[0] && is_file($this->filepath = $this->config['templates']['content'] . '/' . str_replace('../', '', str_replace('..\\', '', $array[0])) . '.tpl'))
				{
					$return = file_get_contents($this->filepath);
					$this->log['getTemplate'][$key]['content'] = array($array[0], true, $return);
				}
				else
					$this->log['getTemplate'][$key]['content'] = array($array[0], false, $return);
				unset($array[0]);
				foreach ($array as $value)
				{
					$value = strval($value);
					if ($value && is_file($this->filepath = $this->config['templates']['code'] . '/' . str_replace('../', '', str_replace('..\\', '', $value)) . '.inc.php'))
					{
						$this->log['getTemplate'][$key]['code'][] = array($value, true, false);
						end($this->log['getTemplate'][$key]['code']);
						$key2 = key($this->log['getTemplate'][$key]['code']);
						$this->chain[] = $template;
						$return = $this->includeFile($return);
						array_pop($this->chain);
						$this->log['getTemplate'][$key]['code'][$key2][2] = $return;
					}
					else
						$this->log['getTemplate'][$key]['code'][] = array($value, false, $return);
				}
			}
			else
			{
				$this->error('The following template could not be found:<pre>' . htmlspecialchars($template) . '</pre>See http://www.suitframework.com/docs/errors#error2', 'The following template could not be found: "' . $template . '". See http://www.suitframework.com/docs/errors#error2', 'Warning');
				$this->log['getTemplate'][$key]['glue'][1] = false;
			}
		else
		{
			$this->error('Infinite Loop Caused by <pre>' . htmlspecialchars($template) . '</pre>See http://www.suitframework.com/docs/errors#error3', 'Infinite Loop Caused by "' . $template . '". See http://www.suitframework.com/docs/errors#error3', 'Warning');
			$this->log['getTemplate'][$key]['glue'][2] = true;
		}
		return $return;
	}

	private function includeFile($content)
	{
		${$this->config['variables']['suit']} = &$this;
		${$this->config['variables']['content']} = $content;
		include $this->filepath;
		return ${$this->config['variables']['content']};
	}

	/**
	http://www.suitframework.com/docs/parse#howitworks
	**/
	public function parse($nodes, $return, $replace = array(), $escape = NULL, $label = NULL)
	{
		$return = strval($return);
		$escape = strval((isset($escape)) ?
			$escape :
			$this->config['parse']['escape']);
		$backtrace = debug_backtrace();
		$log = array
		(
			'backtrace' => $backtrace[0],
			'content' => $return,
			'errors' => false,
			'escape' => $escape,
			'label' => $label,
			'nodes' => $nodes,
			'replace' => $replace,
			'return' => ''
		);
		if (is_array($nodes))
		{
			$open = array();
			$close = array();
			foreach ($nodes as $key => $value)
				if (is_array($value))
				{
					if (array_key_exists(0, $value) && is_array($value[0]))
					{
						foreach ($value as $value2)
							$nodes[] = $value2;
						unset($nodes[$key]);
					}
				}
				else
				{
					$this->error('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5', NULL, 'Warning');
					unset($nodes[$key]);
				}
			usort($nodes, array('SUIT', 'firstKeySort'));
			foreach ($nodes as $key => $value)
			{
				$valid = true;
				$nodes[$key][0] = strval($value[0]);
				$nodes[$key][1] = strval($value[1]);
				if ($nodes[$key][0] == $nodes[$key][1])
					$valid = false;
				else
					foreach ($open as $value2)
						if ($this->strpos(strval($value2), $nodes[$key][0]) !== false || !$nodes[$key][0])
						{
							$valid = false;
							break;
						}
				if ($valid)
				{
					$open[] = $nodes[$key][0];
					$open[] = $nodes[$key][1];
				}
				else
				{
					if (!empty($value))
						$this->error('Duplicate opening string. See http://www.suitframework.com/docs/errors#error6', NULL, 'Warning');
					unset($nodes[$key]);
				}
			}
			$nodessearch = $nodes;
			$stack = array();
			$skipnode = false;
			$pos = 0;
			$last = 0;
			do
			{
				$opensmallest = false;
				$closesmallest = false;
				$opennode = array();
				$closenode = array();
				if (!$skipnode)
					foreach ($nodessearch as $key => $value)
					{
						if (!isset($nodessearch[$key][4]))
						{
							$nodepos = $this->strpos($return, $value[0], $pos);
							if ($nodepos !== false)
							{
								if ($nodepos < $opensmallest || $opensmallest === false)
								{
									$opensmallest = $nodepos;
									$opennode = $value;
								}
							}
							else
								$nodessearch[$key][4] = true;
						}
						if (!isset($nodessearch[$key][5]))
						{
							$nodepos = $this->strpos($return, $value[1], $pos);
							if ($nodepos !== false)
							{
								if ($nodepos < $closesmallest || $closesmallest === false)
								{
									$closesmallest = $nodepos;
									$closenode = $value;
								}
							}
							else
								$nodessearch[$key][5] = true;
						}
						if (isset($nodessearch[$key][4]) && isset($nodessearch[$key][5]))
							unset($nodessearch[$key]);
					}
				else
				{
					$closesmallest = $this->strpos($return, $skipnode[1], $pos);
					$closenode = $skipnode;
				}
				if ($opensmallest < $closesmallest && $opensmallest !== false)
				{
					$pos = $opensmallest;
					if (empty($stack))
					{
						$old = substr($return, $last, $pos - $last);
						$new = $this->replace($replace, $old, NULL, 2);
						$return = substr_replace($return, $new, $last, $pos - $last);
						$pos += strlen($new) - strlen($old);
					}
					if (!$this->parseUnescape($pos, $escape, $return))
						$stack[] = array($opennode, $pos);
					$pos += strlen($opennode[0]);
					$last = $pos;
					if ($opennode[3])
						$skipnode = $opennode;
				}
				elseif ($closesmallest !== false)
				{
					$pos = $closesmallest;
					if (!$this->parseUnescape($pos, $escape, $return) && !empty($stack))
					{
						$openpop = array_pop($stack);
						if ($openpop[0][1] == $closenode[1])
						{
							$string = $this->replace($replace, substr($return, $openpop[1] + strlen($openpop[0][0]), $pos - $openpop[1] - strlen($openpop[0][0])), NULL, 2);
							$this->evalstring = $openpop[0][2];
							$string = (array_key_exists(2, $openpop[0])) ?
								$this->evalNode($string, $nodes, $replace, $escape) :
								$openpop[0][0] . $string . $openpop[0][1];
							$return = substr_replace($return, $string, $openpop[1], $pos + strlen($closenode[1]) - $openpop[1]);
							$pos = $openpop[1] + strlen($string);
							$last = $pos;
						}
						else
							$pos += strlen($closenode[1]);
					}
					else
						$pos += strlen($closenode[1]);
					$skipnode = false;
				}
				else
					$pos = false;
			}
			while ($pos !== false);
			$return = substr_replace($return, $this->replace($replace, substr($return, $last), NULL, 2), $last);
			$log['return'] = $return;
		}
		else
		{
			$this->error('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5', NULL, 'Warning');
			$log['errors'] = true;
		}
		if (isset($label))
			$this->log['parse'][] = $log;
		return $return;
	}

	/**
	http://www.suitframework.com/docs/parseConditional#howitworks
	**/
	public function parseConditional($if, $bool, $else = NULL, $open = NULL, $close = NULL, $end = NULL)
	{
		$return = array();
		$if = strval($if);
		$open = strval((isset($open)) ?
			$open :
			$this->config['parse']['open']);
		$close = strval((isset($close)) ?
			$close :
			$this->config['parse']['close']);
		$end = strval((isset($end)) ?
			$end :
			$this->config['parse']['end']);
		$return[] = array
		(
			$open . $if . $close,
			$open . $end . $if . $close,
			($bool) ?
				'return $case;' :
				''
		);
		if (isset($else))
		{
			$else = strval($else);
			$return[] = array
			(
				$open . $else . $close,
				$open . $end . $else . $close,
				($bool) ?
				'' :
				'return $case;'
			);
		}
		return $return;
	}

	/**
	http://www.suitframework.com/docs/parseLoop#howitworks
	**/
	public function parseLoop($string, $array, $implode = '', $open = NULL, $close = NULL, $end = NULL)
	{
		$return = array();
		$string = strval($string);
		$open = strval((isset($open)) ?
			$open :
			$this->config['parse']['open']);
		$close = strval((isset($close)) ?
			$close :
			$this->config['parse']['close']);
		$end = strval((isset($end)) ?
			$end :
			$this->config['parse']['end']);
		if (is_array($array))
			$return = array
			(
				$open . $string . $close,
				$open . $end . $string . $close,
				'$replacements = array();
				$array = ' . var_export($array, true) . ';
				if (array_key_exists(0, $replace) && !is_array($replace[0]))
					$replace = array($replace);
				if (array_key_exists(0, $replace) && is_array($replace[0]) && array_key_exists(0, $replace[0]) && !is_array($replace[0][0]))
					$replace = array($replace);
				foreach ($array as $value)
					if (is_array($value[0]) && is_array($value[1]))
					{
						if (array_key_exists(0, $value[0]) && !is_array($value[0][0]))
							$value[0] = array($value[0]);
						if (array_key_exists(0, $value[0]) && is_array($value[0][0]) && array_key_exists(0, $value[0][0]) && !is_array($value[0][0][0]))
							$value[0] = array($value[0]);
						$replacements[] = $this->parse(array_merge($value[1], $nodes), $case, array_merge($value[0], $replace), $escape, $label);
					}
					else
						$this->error(\'Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5\', NULL, \'Warning\', $key);
				return implode(' . var_export($implode, true) . ', $replacements);',
				true
			);
		else
			$this->error('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5', NULL, 'Warning');
		return $return;
	}

	/**
	http://www.suitframework.com/docs/parseUnescape#howitworks
	**/
	public function parseUnescape(&$pos, $escape, &$content)
	{
		$pos = intval($pos);
		$content = strval($content);
		$escape = strval($escape);
		$count = 0;
		if ($escape)
		{
			while (abs($start = $pos - $count - strlen($escape)) == $start && substr($content, $start, strlen($escape)) == $escape)
				$count += strlen($escape);
			$count = $count / strlen($escape);
		}
		$condition = $count % 2;
		if ($condition)
			$count++;
		$pos -= strlen($escape) * ($count / 2);
		$content = substr_replace($content, '', $pos, strlen($escape) * ($count / 2));
		return $condition;
	}

	/**
	http://www.suitframework.com/docs/replace#howitworks
	**/
	public function replace($array, $return, $label = NULL, $backtracekey = 1)
	{
		$return = strval($return);
		$backtrace = debug_backtrace();
		$log = array
		(
			'array' => $array,
			'backtrace' => $backtrace[$backtracekey - 1],
			'return' => $return,
			'errors' => false,
			'label' => $label,
			'replace' => array()
		);
		if (is_array($array))
		{
			$array = array_values($array);
			if (array_key_exists(0, $array) && !is_array($array[0]))
				$array = array($array);
			if (array_key_exists(0, $array) && is_array($array[0]) && array_key_exists(0, $array[0]) && !is_array($array[0][0]))
				$array = array($array);
			foreach ($array as $key => $value)
			{
				usort($array[$key], array('SUIT', 'firstKeySort'));
				$pos = 0;
				$errors = false;
				do
				{
					$smallest = false;
					$replace = array();
					foreach ($array[$key] as $key2 => $value2)
						if (is_array($value2) && array_key_exists(0, $value2) && array_key_exists(1, $value2))
						{
							$stringpos = $this->strpos($return, strval($value2[0]), $pos);
							if ($stringpos !== false)
							{
								if ($stringpos < $smallest || $smallest === false)
								{
									$smallest = $stringpos;
									$replace = $value2;
								}
							}
							else
								unset($array[$key][$key2]);
						}
						else
						{
							$this->error('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5', NULL, 'Warning', $backtracekey);
							unset($array[$key][$key2]);
							$errors = true;
						}
					$pos = $smallest;
					if ($pos !== false)
					{
						$return = substr_replace($return, strval($replace[1]), $pos, strlen(strval($replace[0])));
						$pos += strlen(strval($replace[1]));
					}
				}
				while ($pos !== false);
				$log['replace'][] = array($value, $return, $errors);
			}
		}
		else
		{
			$this->error('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5', NULL, 'Warning', $backtracekey);
			$log['errors'] = true;
		}
		if (isset($label))
			$this->log['replace'][] = $log;
		return $return;
	}

	public function strpos($haystack, $needle, $offset = 0)
	{
		$haystack = strval($haystack);
		$needle = strval($needle);
		$offset = intval($offset);
		return ($this->config['flag']['insensitive']) ?
			stripos($haystack, $needle, $offset) :
			strpos($haystack, $needle, $offset);
	}
}
?>