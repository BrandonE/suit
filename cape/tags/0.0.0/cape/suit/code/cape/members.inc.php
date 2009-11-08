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
$path = $suit->tie->path(array('check', 'cmd', 'id', 'logout', 'limit', 'order', 'search', 'start', 'logout', 'email', 'string', 'changepassword'));
$communityindex = ($suit->community->config['mod_rewrite']) ?
	$suit->community->config['mod_rewriteurl'] . '/' :
	$path[1];
$logic = true;
$breadcrumbs = array();
$query = 'SELECT u.id `u_id`, u.username `u_username`, u.joined `u_joined`,
g.title `g_title`
FROM `' . $suit->tie->config['db']['prefix'] . 'users` AS u
LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'groups` AS d ON (u.group = d.id)
LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'groups` AS g ON (g.id = coalesce(d.id, ?))';
if ($suit->tie->settings['search'] != '')
{
	$query .= ' WHERE u.username LIKE \'%' . $suit->community->adodb->Prepare($suit->tie->settings['search']) . '%\'';
	$search = ' WHERE `username` LIKE \'%' . $suit->community->adodb->Prepare($suit->tie->settings['search']) . '%\'';
}
else
	$search = '';
$query .= ' ORDER BY u.username ' .  $suit->community->adodb->Escape($suit->tie->settings['order']) . ' LIMIT ?, ?;';
$result = $suit->community->adodb->GetAll($suit->community->adodb->Prepare($query), array($suit->community->config['group'], $suit->tie->settings['start'], $suit->tie->settings['limit']));
$nodes[] = $suit->parseConditional('if delete', ($suit->community->user['g_mod']));
if (!empty($result) || !$suit->tie->settings['start'])
{
	$breadcrumbs[] = array
	(
		array
		(
			array('[title]', $suit->vars['language']['page'] . $suit->vars['page'] . ($suit->tie->settings['start'] / $suit->tie->settings['limit'] + 1)),
		),
		array
		(
			$suit->parseConditional('if url', false)
		)
	);
	$members = array();
	foreach ($result as $row)
		$members[] = array
		(
			array
			(
				array('[group]', htmlspecialchars($row['g_title'])),
				array('[joined]', date('m/d/y h:i A', $row['u_joined'])),
				array('[userid]', htmlspecialchars($row['u_id'])),
				array('[username]', htmlspecialchars($row['u_username'])),
				array('[userrewrite]', htmlspecialchars($suit->replace($suit->vars['illegal'], $row['u_username'])))
			),
			array()
		);
	$query = 'SELECT `id` FROM `' . $suit->tie->config['db']['prefix'] . 'users`' . $search . ';';
	$result = $suit->community->adodb->GetAll($query);
	$nodes[] = $suit->parseLoop('loop members', $members);
	$suit->vars['string'] = 'members';
	$link = $suit->tie->pagination(count($result), NULL, 'cape/pagelink');
	$suit->vars['previous'] = $link['previous'];
	$suit->vars['current'] = $link['current'];
	$suit->vars['next'] = $link['next'];
	$content = $suit->parse($nodes, $content);
	$section = $suit->vars['language']['members'] . $suit->vars['separator'] . $suit->vars['language']['page'] . $suit->vars['page'] . ($suit->tie->settings['start'] / $suit->tie->settings['limit'] + 1);
}
else
{
	$logic = false;
	$breadcrumbs = array();
}
$suit->vars['section'] = $section;
$suit->vars['breadcrumbs'] = $breadcrumbs;
$suit->vars['logic'] = $logic;
?>