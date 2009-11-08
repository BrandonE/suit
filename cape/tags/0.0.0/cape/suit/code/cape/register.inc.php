<?php
/**
**@This file is part of CAPE.
**@CAPE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@CAPE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with CAPE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
$nodes = $suit->config['parse']['nodes'];
if ($suit->vars['message'])
{
	$suit->vars['username'] = htmlspecialchars($_POST['username']);
	$suit->vars['email'] = htmlspecialchars($_POST['email']);
	$suit->vars['homepage'] = htmlspecialchars($_POST['homepage']);
	$suit->vars['location'] = htmlspecialchars($_POST['location']);
	$suit->vars['interests'] = htmlspecialchars($_POST['interests']);
	$suit->vars['title'] = htmlspecialchars($_POST['title']);
	$suit->vars['avatar'] = htmlspecialchars($_POST['avatar']);
	$suit->vars['signature'] = htmlspecialchars($_POST['signature']);
	$suit->vars['aim'] = htmlspecialchars($_POST['aim']);
	$suit->vars['icq'] = htmlspecialchars($_POST['icq']);
	$suit->vars['yahoo'] = htmlspecialchars($_POST['yahoo']);
	$suit->vars['msn'] = htmlspecialchars($_POST['msn']);
}
$month = array();
$day = array();
$year = array();
foreach ($suit->vars['months'] as $key => $value)
	$month[] = array
	(
		array
		(
			array('[id]', $key),
			array('[title]', $value)
		),
		array
		(
			$suit->parseConditional('if selected', ($_POST['month'] == $key))
		)
	);
for ($x = 1; $x <= 31; $x++)
	$day[] = array
	(
		array('[id]', (($x < 10) ?
			'0' :
			'') . $x),
		array
		(
			$suit->parseConditional('if selected', ($_POST['day'] == $x))
		)
	);
for ($x = intval(date('Y', mktime())); $x >= 1910; $x--)
	$year[] = array
	(
		array('[id]', $x),
		array
		(
			$suit->parseConditional('if selected', ($_POST['year'] == $x))
		)
	);
$zones = array();
foreach ($suit->vars['zonelist'] as $key => $value)
	$zones[] = array
	(
		array
		(
			array('[value]', $key),
			array('[label]', htmlspecialchars($value))
		),
		array
		(
			$suit->parseConditional('if selected', ($_POST['timezone'] == $key))
		)
	);
$nodes[] = $suit->parseLoop('loop month', $month);
$nodes[] = $suit->parseLoop('loop day', $day);
$nodes[] = $suit->parseLoop('loop year', $year);
$nodes[] = $suit->parseLoop('loop zones', $zones);
$content = $suit->parse($nodes, $content);
?>
