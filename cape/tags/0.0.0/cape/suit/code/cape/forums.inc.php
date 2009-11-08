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
$path = $suit->tie->path(array('check', 'cmd', 'id', 'logout', 'limit', 'order', 'search', 'start', 'post', 'logout', 'email', 'string', 'changepassword'));
$communityindex = ($suit->community->config['mod_rewrite']) ?
	$suit->community->config['mod_rewriteurl'] . '/' :
	$path[1];
$forum = (isset($_GET['id'])) ?
	$_GET['id'] :
	0;
$topics = array();
$error = false;
$logic = true;
$navigation = true;
$forumlocked = false;
if ($forum)
{
	$breadcrumbs[] = array
	(
		array
		(
			array('[title]', $suit->vars['language']['communityindex']),
			array('[url]', $communityindex)
		),
		array
		(
			$suit->parseConditional('if url', true)
		)
	);
	$query = $suit->community->adodb->Prepare('SELECT `id`, `title`, `parent`, `locked`, `category` FROM `' . $suit->tie->config['db']['prefix'] . 'forums` WHERE `id` = ?;');
	$row = $suit->community->adodb->GetRow($query, array($forum));
	if ($row)
	{
		$forumlocked = $row['locked'];
		$breadcrumbs = array_merge
		(
			$breadcrumbs,
			$suit->community->breadcrumbs($row['parent'])
		);
		$breadcrumbs[] = array
		(
			array
			(
				array('[title]', htmlspecialchars($row['title']))
			),
			array
			(
				$suit->parseConditional('if url', false)
			)
		);
		if (!$row['category'])
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
			$section = $row['title'] . $suit->vars['separator'] . $suit->vars['language']['page'] . $suit->vars['page'] . ($suit->tie->settings['start'] / $suit->tie->settings['limit'] + 1);
			$query = 'SELECT t.id `t_id`, t.title `t_title`, t.replies `t_replies`,
			tu.id `tu_id`, tu.username `tu_username`,
			l.id `l_id`, l.time `l_time`,
			lu.id `lu_id`, lu.username `lu_username`
			FROM `' . $suit->tie->config['db']['prefix'] . 'posts` AS t
			LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'users` AS tu ON (t.poster = tu.id)
			LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'posts` AS l ON (t.latest = l.id)
			LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'users` AS lu ON (l.poster = lu.id)
			WHERE t.parent = ? AND t.topic =\'1\'';
			if ($suit->tie->settings['search'] != '')
			{
				$query .= ' AND t.title LIKE \'%' . $suit->community->adodb->Prepare($suit->tie->settings['search']) . '%\'';
				$search = ' AND `title` LIKE \'%' . $suit->community->adodb->Prepare($suit->tie->settings['search']) . '%\'';
			}
			else
				$search = '';
			$query .= 'ORDER BY l.time ' . $suit->community->adodb->Escape($suit->tie->settings['order_reverse']) . ' LIMIT ?, ?;';
			$suit->tie->settings['order'] = $suit->tie->settings['order_reverse'];
			$result = $suit->community->adodb->GetAll($suit->community->adodb->Prepare($query), array($forum, $suit->tie->settings['start'], $suit->tie->settings['limit']));
			$query = $suit->community->adodb->Prepare('SELECT `id` FROM `' . $suit->tie->config['db']['prefix'] . 'posts` WHERE `parent` = ? AND `topic` =\'1\'' . $search . ';');
			$pages = $suit->community->adodb->GetAll($query, array($forum));
			$suit->vars['forumrewrite'] = htmlspecialchars($suit->replace($suit->vars['illegal'], $row['title']));
			$suit->vars['string'] = 'forum/' . $_GET['id'] . '/' . htmlentities($suit->replace($suit->vars['illegal'], $row['title']));
			$link = $suit->tie->pagination(count($pages), NULL, 'cape/pagelink');
			$suit->vars['previous'] = $link['previous'];
			$suit->vars['current'] = $link['current'];
			$suit->vars['next'] = $link['next'];
			$forumtitle = $row['title'];
			if (!empty($result) || !$suit->tie->settings['start'])
				foreach ($result as $row)
					$topics[] = array
					(
						array
						(
							array('[id]', intval($row['t_id'])),
							array('[latestuser]', htmlspecialchars($row['lu_username'])),
							array('[latestuserid]', intval($row['lu_id'])),
							array('[latestuserrewrite]', htmlspecialchars($suit->replace($suit->vars['illegal'], $row['lu_username']))),
							array('[limit]', intval($suit->tie->settings['limit'])),
							array('[postid]', intval($row['l_id'])),
							array('[replies]', intval($row['t_replies'])),
							array('[start]', $suit->tie->reduce($row['t_replies'] + 1, true)),
							array('[time]', date('m/d/y h:i A', $row['l_time'])),
							array('[title]', htmlspecialchars($row['t_title'])),
							array('[topicrewrite]', htmlspecialchars($suit->replace($suit->vars['illegal'], $row['t_title']))),
							array('[user]', htmlspecialchars($row['tu_username'])),
							array('[userid]', intval($row['tu_id'])),
							array('[userid]', intval($row['tu_id'])),
							array('[userrewrite]', htmlspecialchars($suit->replace($suit->vars['illegal'], $row['tu_username']))),
						),
						array()
					);
			else
			{
				$logic = false;
				$breadcrumbs = array();
			}
		}
		else
		{
			$section = $row['title'];
			$navigation = false;
			$category = $row;
		}
	}
	else
	{
		$content = $suit->vars['language']['forumnotfound'];
		$section = $suit->vars['language']['forumnotfound'];
		$breadcrumbs[] = array
		(
			array
			(
				array('[title]', $suit->vars['language']['forumnotfound'])
			),
			array
			(
				$suit->parseConditional('if url', false)
			)
		);
		$error = true;
	}
}
else
{
	$breadcrumbs[] = array
	(
		array
		(
			array('[title]', $suit->vars['language']['communityindex'])
		),
		array
		(
			$suit->parseConditional('if url', false)
		)
	);
	$section = $suit->vars['language']['communityindex'];
}
if (!$error && $logic)
{
	$query = $suit->community->adodb->Prepare('SELECT f.id `f_id`, f.title `f_title`, f.description `f_description`, f.topics `f_topics`, f.posts `f_posts`,
	l.id `l_id`, l.time `l_time`, l.title `l_title`, l.replies `l_replies`, l.topic `l_topic`,
	u.id `u_id`, u.username `u_username`,
	t.id `t_id`, t.title `t_title`, t.replies `t_replies`
	FROM `' . $suit->tie->config['db']['prefix'] . 'forums` AS f
	LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'posts` AS l ON (f.latest = l.id)
	LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'users` AS u ON (l.poster = u.id)
	LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'posts` AS t ON (l.parent = t.id)
	' . (($navigation) ?
			'WHERE f.parent = ? AND f.category = \'1\';' :
			'WHERE f.id = ?'));
	$result = $suit->community->adodb->GetAll($query, array($forum));
	if ($navigation && $forum)
	{
		$forums = array();
		foreach ($result as $row)
		{
			if ($row['l_topic'])
			{
				$row['t_replies'] = $row['l_replies'];
				$row['t_id'] = $row['l_id'];
				$row['t_title'] = $row['l_title'];
			}
			$forums[] = array
			(
				array
				(
					array('[description]', nl2br(htmlspecialchars($row['f_description']))),
					array('[forumrewrite]', htmlspecialchars($suit->replace($suit->vars['illegal'], $row['f_title']))),
					array('[id]', intval($row['f_id'])),
					array('[limit]', intval($suit->tie->settings['limit'])),
					array('[postid]', intval($row['l_id'])),
					array('[posts]', intval($row['f_posts'])),
					array('[start]', $suit->tie->reduce($row['t_replies'] + 1, true)),
					array('[time]', date('m/d/y h:i A', $row['l_time'])),
					array('[title]', htmlspecialchars($row['f_title'])),
					array('[topicid]', intval($row['t_id'])),
					array('[topicrewrite]', htmlspecialchars($suit->replace($suit->vars['illegal'], $row['t_title']))),
					array('[topics]', intval($row['f_topics'])),
					array('[topictitle]', htmlspecialchars($row['t_title'])),
					array('[user]', htmlspecialchars($row['u_username'])),
					array('[userid]', intval($row['u_id'])),
					array('[userrewrite]', htmlspecialchars($suit->replace($suit->vars['illegal'], $row['u_username'])))
				),
				array
				(
					$suit->parseConditional('if description', ($row['f_description'])),
					$suit->parseConditional('if latest', isset($row['l_id']), 'else latest')
				)
			);
		}
		$suit->vars['categoryid'] = intval($category['id']);
		$suit->vars['categoryrewrite'] = htmlspecialchars($suit->replace($suit->vars['illegal'], $category['title']));
		if ($navigation)
		{
			$suit->vars['forumtitle'] = htmlspecialchars($forumtitle);
			$suit->vars['categorytitle'] = $suit->parse($nodes, $suit->vars['language']['forumsin']);
		}
		else
			$suit->vars['categorytitle'] = htmlspecialchars($category['title']);
		$nodes[] = $suit->parseConditional('if forums', (!empty($forums)));
		$nodes[] = $suit->parseLoop('loop forums', $forums);
	}
	else
	{
		$categories = array();
		foreach ($result as $row)
		{
			$forums = array();
			$query = $suit->community->adodb->Prepare('SELECT f.id `f_id`, f.title `f_title`, f.description `f_description`, f.topics `f_topics`, f.posts `f_posts`,
			l.id `l_id`, l.time `l_time`, l.title `l_title`, l.replies `l_replies`, l.topic `l_topic`,
			u.id `u_id`, u.username `u_username`,
			t.id `t_id`, t.title `t_title`, t.replies `t_replies`
			FROM `' . $suit->tie->config['db']['prefix'] . 'forums` AS f
			LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'posts` AS l ON (f.latest = l.id)
			LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'users` AS u ON (l.poster = u.id)
			LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'posts` AS t ON (l.parent = t.id)
			WHERE f.parent = ?;');
			$result2 = $suit->community->adodb->GetAll($query, array($row['f_id']));
			foreach ($result2 as $row2)
			{
				if ($row2['l_topic'])
				{
					$row2['t_replies'] = $row2['l_replies'];
					$row2['t_id'] = $row2['l_id'];
					$row2['t_title'] = $row2['l_title'];
				}
				$forums[] = array
				(
					array
					(
						array('[description]', nl2br(htmlspecialchars($row2['f_description']))),
						array('[forumrewrite]', htmlspecialchars($suit->replace($suit->vars['illegal'], $row2['f_title']))),
						array('[id]', intval($row2['f_id'])),
						array('[limit]', intval($suit->tie->settings['limit'])),
						array('[postid]', intval($row2['l_id'])),
						array('[posts]', intval($row2['f_posts'])),
						array('[start]', $suit->tie->reduce($row2['t_replies'] + 1, true)),
						array('[time]', date('m/d/y h:i A', $row2['l_time'])),
						array('[title]', htmlspecialchars($row2['f_title'])),
						array('[topicid]', intval($row2['t_id'])),
						array('[topicrewrite]', htmlspecialchars($suit->replace($suit->vars['illegal'], $row2['t_title']))),
						array('[topics]', intval($row2['f_topics'])),
						array('[topictitle]', htmlspecialchars($row2['t_title'])),
						array('[user]', htmlspecialchars($row2['u_username'])),
						array('[userid]', intval($row2['u_id'])),
						array('[userrewrite]', htmlspecialchars($suit->replace($suit->vars['illegal'], $row2['u_username'])))
					),
					array
					(
						$suit->parseConditional('if description', ($row2['f_description'])),
						$suit->parseConditional('if latest', isset($row2['l_id']), 'else latest')
					)
				);
			}
			$categories[] = array
			(
				array
				(
					array('[categoryid]', intval($row['f_id'])),
					array('[categoryrewrite]', htmlspecialchars($suit->replace($suit->vars['illegal'], $row['f_title']))),
					array('[categorytitle]', htmlspecialchars($row['f_title']))
				),
				array
				(
					$suit->parseLoop('loop forums', $forums)
				)
			);
		}
		$nodes[] = $suit->parseLoop('loop categories', $categories);
	}
	$nodes[] = $suit->parseConditional('if navigation', ($navigation && $forum), 'else navigation');
	$nodes[] = $suit->parseConditional('if topics', (!empty($topics)));
	$nodes[] = $suit->parseConditional('if loggedin', ($suit->community->user['u_id'] && $forum));
	$nodes[] = $suit->parseConditional('if forumlocked', ($forumlocked && !$suit->community->user['g_mod']), 'else forumlocked');
	$nodes[] = $suit->parseLoop('loop topics', $topics);
	$suit->vars['forum'] = $forum;
	$content = $suit->parse($nodes, $content);
}
$suit->vars['section'] = $section;
$suit->vars['breadcrumbs'] = $breadcrumbs;
$suit->vars['logic'] = $logic;
?>