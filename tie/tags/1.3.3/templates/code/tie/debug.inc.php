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
if ($suit->tie->config['flag']['debug'])
{
	$nodes = $suit->config['parse']['nodes'];
	$templates = array();
	$replace = array();
	$parse = array();
	foreach ($suit->log['getTemplate'] as $key => $value)
	{
		$code = array();
		foreach ($value['code'] as $key2 => $value2)
		{
			$code[] = array
			(
				array
				(
					array('[id2]', $key2),
					array('[code]', htmlspecialchars($value2[0])),
					array('[codefile]', htmlspecialchars($value2[2]))
				),
				array
				(
					$suit->parseConditional('if code', ($value2[1]), 'else code'),
					$suit->parseConditional('if codefile', ($value2[2] !== false), 'else codefile')
				)
			);
		}
		$templates[] = array
		(
			array
			(
				array('[content]', htmlspecialchars($value['content'][0])),
				array('[contentfile]', htmlspecialchars($value['content'][2])),
				array('[file]', htmlspecialchars($value['backtrace']['file'])),
				array('[id]', $key),
				array('[line]', htmlspecialchars($value['backtrace']['line'])),
				array('[template]', htmlspecialchars(var_export($value['glue'][0], true))),
				array('[title]', htmlspecialchars($value['glue'][0]))
			),
			array
			(
				$suit->parseConditional('if notfound', (!$value['glue'][1]), 'else notfound'),
				$suit->parseConditional('if infiniteloop', ($value['glue'][2]), 'else infiniteloop'),
				$suit->parseConditional('if content', ($value['content'][1]), 'else content'),
				$suit->parseLoop('loop code', $code),
			)
		);
	}
	foreach ($suit->log['replace'] as $key => $value)
	{
		$step = array();
		foreach ($value['replace'] as $key2 => $value2)
		{
			$step[] = array
			(
				array
				(
					array('[array]', htmlspecialchars(print_r($value2[0], true))),
					array('[id2]', $key2),
					array('[step]', htmlspecialchars($value2[1]))
				),
				array
				(
					$suit->parseConditional('if steperrors', ($value2[2]))
				)
			);
		}
		$replace[] = array
		(
			array
			(
				array('[array]', htmlspecialchars(print_r($value['array'], true))),
				array('[content]', htmlspecialchars($value['return'])),
				array('[file]', htmlspecialchars($value['backtrace']['file'])),
				array('[id]', $key),
				array('[label]', htmlspecialchars(var_export($value['label'], true))),
				array('[line]', htmlspecialchars($value['backtrace']['line'])),
				array('[return]', htmlspecialchars(var_export($value['return'], true))),
				array('[title]', htmlspecialchars($value['label']))
			),
			array
			(
				$suit->parseConditional('if errors', ($value['errors']), 'else errors'),
				$suit->parseLoop('loop step', $step)
			)
		);
	}
	foreach ($suit->log['parse'] as $key => $value)
		$parse[] = array
		(
			array
			(
				array('[before]', htmlspecialchars($value['content'])),
				array('[content]', htmlspecialchars(var_export($value['content'], true))),
				array('[escape]', htmlspecialchars(var_export($value['escape'], true))),
				array('[file]', htmlspecialchars($value['backtrace']['file'])),
				array('[id]', $key),
				array('[label]', htmlspecialchars(var_export($value['label'], true))),
				array('[line]', htmlspecialchars($value['backtrace']['line'])),
				array('[nodes', htmlspecialchars(print_r($value['nodes'], true))),
				array('[replace]', htmlspecialchars(print_r($value['replace'], true))),
				array('[return]', htmlspecialchars($value['return'])),
				array('[title]', htmlspecialchars($value['label']))
			),
			array
			(
				$suit->parseConditional('if errors', ($value['errors']), 'else errors')
			)
		);
	$nodes[] = $suit->parseConditional('else templates', (empty($templates)));
	$nodes[] = $suit->parseConditional('else replace', (empty($replace)));
	$nodes[] = $suit->parseConditional('else parse', (empty($parse)));
	$nodes[] = $suit->parseLoop('loop templates', $templates);
	$nodes[] = $suit->parseLoop('loop replace', $replace);
	$nodes[] = $suit->parseLoop('loop parse', $parse);
	$content = $suit->parse($nodes, $content);
}
else
	$content = '';
?>