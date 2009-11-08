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
$query = $suit->community->adodb->Prepare('SELECT t.title `t_title`, t.locked `t_locked`,
f.id `f_id`, f.title `f_title`, f.locked `f_locked`,
s.id `s_id`
FROM `' . $suit->tie->config['db']['prefix'] . 'posts` AS t
LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'forums` AS f ON (t.parent = f.id)
LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'subscriptions` AS s ON (s.parent = t.id AND s.user = ?)
WHERE t.id = ? AND t.topic = \'1\';');
$topic = $suit->community->adodb->GetRow($query, array($suit->community->user['u_id'], intval($_GET['id'])));
if ($topic)
{
	$breadcrumbs = $suit->community->breadcrumbs($topic['f_id']);
	$breadcrumbs[] = array
	(
		array
		(
			array('[title]', htmlspecialchars($topic['t_title'])),
		),
		array
		(
			$suit->parseConditional('if url', false)
		)
	);
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
	$posts = array();
	$query = 'SELECT p.id `p_id`, p.content `p_content`, p.smilies `p_smilies`, p.signature `p_signature`, p.modified_time `p_modified_time`, p.time `p_time`, p.title `p_title`, p.modified_user `p_modified_user`, p.poster `p_poster`,
	u.id `u_id`, u.signature `u_signature`, u.avatar `u_avatar`, u.username `u_username`, u.title `u_title`, u.posts `u_posts`,
	m.id `m_id`, m.username `m_username`,
	g.title `g_title`
	FROM `' . $suit->tie->config['db']['prefix'] . 'posts` AS p
	LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'users` AS u ON (p.poster = u.id)
	LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'users` AS m ON (p.modified_user = m.id)
	LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'groups` AS d ON (u.group = d.id)
	LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'groups` AS g ON (g.id = coalesce(d.id, ?))
	WHERE ((p.id = ? AND p.topic = \'1\') OR p.parent = ?)';
	if ($suit->tie->settings['search'] != '')
	{
		$query .= ' AND p.content LIKE \'%' . $suit->community->adodb->Prepare($suit->tie->settings['search']) . '%\'';
		$search = ' AND `content` LIKE \'%' . $suit->community->adodb->Prepare($suit->tie->settings['search']) . '%\'';
	}
	else
		$search = '';
	$query .= ' ORDER BY p.time ' .  $suit->community->adodb->Escape($suit->tie->settings['order']) . ' LIMIT ?, ?;';
	$result = $suit->community->adodb->GetAll($suit->community->adodb->Prepare($query), array($suit->community->config['group'], intval($_GET['id']), intval($_GET['id']), $suit->tie->settings['start'], $suit->tie->settings['limit']));
	$suit->vars['id'] = $_GET['id'];
	$suit->vars['topicrewrite'] = htmlspecialchars($suit->replace($suit->vars['illegal'], $topic['t_title']));
	$nodes[] = $suit->parseConditional('if delete', ($suit->community->user['g_mod']));
	if (!empty($result) || !$suit->tie->settings['start'])
	{
		foreach ($result as $row)
			$posts[] = array
			(
				$suit->community->createPost($row),
				array
				(
					$suit->parseConditional('if any', ($suit->community->user['u_id'] && !$row['p_locked'] || $suit->community->user['g_mod'])),
					$suit->parseConditional('if avatar', ($row['u_avatar'])),
					$suit->parseConditional('if signature', ($row['p_signature'] && $row['u_signature'])),
					$suit->parseConditional('if edited', ($row['p_modified_time'])),
					$suit->parseConditional('if edit', ($suit->community->user['u_id'] && (!$row['p_locked'] && $row['p_poster'] == $suit->community->user['u_id']) || $suit->community->user['g_mod'])),
					$suit->parseConditional('if quote', ($suit->community->user['u_id'] && !$row['p_locked'] || $suit->community->user['g_mod'])),
					$suit->parseConditional('if user', (isset($row['u_id'])), 'else user'),
					$suit->parseConditional('if group', (isset($row['g_title']) && isset($row['u_id']))),
					$suit->parseConditional('if title', (isset($row['u_title'])))
				)
			);
		$query = $suit->community->adodb->Prepare('SELECT `id` FROM `' . $suit->tie->config['db']['prefix'] . 'posts` WHERE ((`id` = ? AND `topic` = \'1\') OR `parent` = ?)' . $search . ';');
		$result = $suit->community->adodb->GetAll($query, array(intval($_GET['id']), intval($_GET['id'])));
		$nodes[] = $suit->parseLoop('loop posts', $posts);
		$suit->vars['string'] = 'topic/' . $_GET['id'] . '/' . htmlentities($suit->replace($suit->vars['illegal'], $topic['t_title']));
		$link = $suit->tie->pagination(count($result), NULL, 'cape/pagelink');
		$suit->vars['previous'] = $link['previous'];
		$suit->vars['current'] = $link['current'];
		$suit->vars['next'] = $link['next'];
		$suit->vars['forum'] = $topic['f_id'];
		$suit->vars['forumrewrite'] = htmlspecialchars($suit->replace($suit->vars['illegal'], $topic['f_title']));
		$suit->vars['id'] = $_GET['id'];
		$suit->vars['posts'] = $suit->parse($nodes, $suit->getTemplate('cape/post'));
		$suit->vars['topic'] = htmlspecialchars($topic['t_title']);
		$suit->vars['topicrewrite'] = htmlspecialchars($suit->replace($suit->vars['illegal'], $topic['t_title']));
		$nodes[] = $suit->parseConditional('if loggedin', ($suit->community->user['u_id']));
		$nodes[] = $suit->parseConditional('if subscribe', (!$topic['s_id']), 'else subscribe');
		$nodes[] = $suit->parseConditional('if topiclocked', ($topic['t_locked'] && !$suit->community->user['g_mod']), 'else topiclocked');
		$nodes[] = $suit->parseConditional('if forumlocked', ($topic['f_locked'] && !$suit->community->user['g_mod']), 'else forumlocked');
		$content = $suit->parse($nodes, $content);
		$section = $topic['t_title'] . $suit->vars['separator'] . $suit->vars['language']['page'] . $suit->vars['page'] . ($suit->tie->settings['start'] / $suit->tie->settings['limit'] + 1);
	}
	else
	{
		$logic = false;
		$breadcrumbs = array();
	}
}
else
{
	$content = $suit->vars['language']['topicnotfound'];
	$section = $suit->vars['language']['topicnotfound'];
	$breadcrumbs[] = array
	(
		array
		(
			array('[title]', $suit->vars['language']['topicnotfound'])
		),
		array
		(
			$suit->parseConditional('if url', false)
		)
	);
}
$suit->vars['section'] = $section;
$suit->vars['breadcrumbs'] = $breadcrumbs;
$suit->vars['logic'] = $logic;
?>