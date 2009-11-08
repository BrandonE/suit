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
$nodes[] = $suit->parseConditional('if mod_rewrite', ($suit->community->config['mod_rewrite']), 'else mod_rewrite');
$breadcrumbs = array();
$query = $suit->community->adodb->Prepare('SELECT u.id `u_id`, u.username `u_username`, u.password `u_password`, u.email `u_email`, u.group `u_group`, u.recover_string `u_recover_string`, u.recover_password `u_recover_password`, u.title `u_title`, u.avatar `u_avatar`, u.signature `u_signature`, u.joined `u_joined`, u.timezone `u_timezone`, u.lastactivity `u_lastactivity`, u.posts `u_posts`, u.aim `u_aim`, u.icq `u_icq`, u.yahoo `u_yahoo`, u.msn `u_msn`, u.homepage `u_homepage`, u.birthday `u_birthday`, u.location `u_location`, u.interests `u_interests`, u.validate_string `u_validate_string`,
g.title `g_title`
FROM `' . $suit->tie->config['db']['prefix'] . 'users` AS u
LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'groups` AS d ON (u.group = d.id)
LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'groups` AS g ON (g.id = coalesce(d.id, ?))
WHERE u.id = ?;');
$row = $suit->community->adodb->GetRow($query, array($suit->community->config['group'], intval($_GET['id'])));
if ($row)
{
	$nodes[] = $suit->parseConditional('if edit', ($suit->community->user['u_id'] == $row['u_id'] || $suit->community->user['g_admin']));
	$nodes[] = $suit->parseConditional('if admin', ($suit->community->user['g_admin']));
	$query = 'SELECT COUNT(*) AS posts FROM `' . $suit->tie->config['db']['prefix'] . 'posts`';
	$total = $suit->community->adodb->GetRow($query);
	$suit->vars['posts'] = $row['u_posts'];
	$suit->vars['postpercent'] = ($total['posts']) ?
		round($row['u_posts'] / $total['posts'] * 100, 2) :
		0;
	$suit->vars['postpercent'] = $suit->parse($nodes, $suit->vars['language']['oftotal']);
	$suit->vars['joined'] = date('m/d/y h:i A', $row['u_joined']);
	$suit->vars['avatar'] = htmlspecialchars($row['u_avatar']);
	$suit->vars['signature'] = $suit->community->parse($row['u_signature'], true);
	$suit->vars['signatureedit'] = htmlspecialchars($row['u_signature']);
	$suit->vars['username'] = htmlspecialchars($row['u_username']);
	$suit->vars['usernameedit'] = htmlspecialchars($row['u_username']);
	$now = mktime();
	$days = intval(gregoriantojd(date('m', $now), date('d', $now), date('Y', $now)) - gregoriantojd(date('m', $row['u_joined']), date('d', $row['u_joined']), date('Y', $row['u_joined'])));
	$suit->vars['postsperday'] = ($days) ?
		round($row['u_posts'] / $days) :
		$row['u_posts'];
	$suit->vars['lastactivity'] = date('m/d/y h:i A', $row['u_lastactivity']);
	$suit->vars['aim'] = ($row['u_aim']) ?
		htmlspecialchars($row['u_aim']) :
		$suit->vars['language']['na'];
	$suit->vars['aimedit'] = htmlspecialchars($row['u_aim']);
	$suit->vars['icq'] = ($row['u_icq']) ?
		htmlspecialchars($row['u_icq']) :
		$suit->vars['language']['na'];
	$suit->vars['icqedit'] = htmlspecialchars($row['u_icq']);
	$suit->vars['yahoo'] = ($row['u_yahoo']) ?
		htmlspecialchars($row['u_yahoo']) :
		$suit->vars['language']['na'];
	$suit->vars['yahooedit'] = htmlspecialchars($row['u_yahoo']);
	$suit->vars['msn'] = ($row['u_msn']) ?
		htmlspecialchars($row['u_msn']) :
		$suit->vars['language']['na'];
	$suit->vars['msnedit'] = htmlspecialchars($row['u_msn']);
	$suit->vars['homepage'] = ($row['u_homepage']) ?
		htmlspecialchars($row['u_homepage']) :
		$suit->vars['language']['na'];
	$suit->vars['homepageedit'] = htmlspecialchars($row['u_homepage']);
	$suit->vars['birthday'] = ($row['u_birthday']) ?
		$row['u_birthday'] :
		$suit->vars['language']['na'];
	$birthday = explode('/', $row['u_birthday']);
	$suit->vars['month'] = $birthday[0];
	$suit->vars['day'] = $birthday[1];
	$suit->vars['year'] = $birthday[2];
	if ($row['u_birthday'])
	{
		$age = date('Y') - intval($birthday[2]);
		$month_diff = date('m') - intval($birthday[0]);
		$day_diff = date('d') - intval($birthday[1]);
		if ($day_diff < 0 || $month_diff < 0)
			$age--;
		$suit->vars['age'] = $age;
	}
	else
		$suit->vars['age'] = $suit->vars['language']['na'];
	$suit->vars['age'] = $suit->parse($nodes, $suit->vars['language']['yearsold']);
	$suit->vars['location'] = ($row['u_location']) ?
		htmlspecialchars($row['u_location']) :
		$suit->vars['language']['na'];
	$suit->vars['locationedit'] = htmlspecialchars($row['u_location']);
	$suit->vars['interests'] = ($row['u_interests']) ?
		nl2br(htmlspecialchars($row['u_interests'])) :
		$suit->vars['language']['na'];
	$suit->vars['interestsedit'] = htmlspecialchars($row['u_interests']);
	$suit->vars['group'] = ($row['g_title']) ?
		htmlspecialchars($row['g_title']) :
		$suit->vars['language']['na'];
	$suit->vars['groupedit'] = htmlspecialchars($row['u_group']);
	$suit->vars['title'] = ($row['u_title']) ?
		htmlspecialchars($row['u_title']) :
		$suit->vars['language']['na'];
	$suit->vars['titleedit'] = htmlspecialchars($row['u_title']);
	$timezone = ($row['u_timezone']) ?
		$row['u_timezone'] :
		$suit->community->config['timezone'];
	putenv('TZ=' . $timezone);
	mktime(0, 0, 0, 1, 1, 1970);
	$suit->vars['localtime'] = date('m/d/y h:i A', mktime());
	putenv('TZ=' . $suit->community->user['u_timezone']);
	mktime(0, 0, 0, 1, 1, 1970);
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
				$suit->parseConditional('if selected', (intval($birthday[0]) == intval($key)))
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
				$suit->parseConditional('if selected', (intval($birthday[1]) == intval($x)))
			)
		);
	for ($x = intval(date('Y', $now)); $x >= 1910; $x--)
		$year[] = array
		(
			array('[id]', $x),
			array
			(
				$suit->parseConditional('if selected', (intval($birthday[2]) == intval($x)))
			)
		);
	$group = array();
	$query = 'SELECT `id`, `title` FROM `' . $suit->tie->config['db']['prefix'] . 'groups`;';
	$result = $suit->community->adodb->GetAll($query);
	foreach ($result as $row2)
		$group[] = array
		(
			array
			(
				array('[id]', $row2['id']),
				array('[title]', htmlspecialchars($row2['title']))
			),
			array
			(
				$suit->parseConditional('if selected', ($row2['id'] == $row['u_group']))
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
				$suit->parseConditional('if selected', ($row['u_timezone'] == $key))
			)
		);
	$nodes[] = $suit->parseConditional('if homepage', $row['u_homepage']);
	$nodes[] = $suit->parseLoop('loop month', $month);
	$nodes[] = $suit->parseLoop('loop day', $day);
	$nodes[] = $suit->parseLoop('loop year', $year);
	$nodes[] = $suit->parseLoop('loop group', $group);
	$nodes[] = $suit->parseLoop('loop zones', $zones);
	$content = $suit->parse($nodes, $content);
	$breadcrumbs[] = array
	(
		array
		(
			array('[title]', $row['u_username'])
		),
		array
		(
			$suit->parseConditional('if url', false)
		)
	);
	$section = $row['u_username'];
}
else
{
	$content = $suit->vars['language']['usernotfound'];
	$section = $suit->vars['language']['usernotfound'];
	$breadcrumbs[] = array
	(
		array
		(
			array('[title]', $suit->vars['language']['usernotfound'])
		),
		array
		(
			$suit->parseConditional('if url', false)
		)
	);
}
$suit->vars['section'] = $section;
$suit->vars['breadcrumbs'] = $breadcrumbs;
?>
