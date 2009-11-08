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
$suit->vars['mod_rewriteurl'] = $suit->community->config['mod_rewriteurl'];
$illegal = array
(
	array('?', '*'),
	array('#', '*'),
	array(' ', '-'),
	array('/', '*'),
	array('=', '*'),
	array('&', '*')
);
$suit->vars['username'] = $suit->community->user['u_username'];
$suit->vars['userid'] = $suit->community->user['u_id'];
$suit->vars['userrewrite'] = htmlspecialchars($suit->replace($illegal, $suit->community->user['u_username']));
$welcome = $suit->parse($nodes, $suit->vars['language']['welcomecommunity']);
$suit->vars['welcome'] = $welcome;
$nodes[] = $suit->parseConditional('if login', (!$suit->community->user['u_id']), 'else login');
$nodes[] = $suit->parseConditional('if admin', ($suit->community->user['g_admin']));
$nodes[] = $suit->parseConditional('if index', true, 'else index');
$content = $suit->parse($nodes, $content);
?>