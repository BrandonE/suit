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
$logic = $suit->tie->logistics();
$path = $suit->tie->path(array('check', 'cmd', 'id', 'logout', 'limit', 'order', 'search', 'start', 'logout', 'email', 'string', 'changepassword'));
$communityindex = ($suit->community->config['mod_rewrite']) ?
	$suit->community->config['mod_rewriteurl'] . '/' :
	$path[1];
$communityindexredirect = ($suit->community->config['mod_rewrite']) ?
	$suit->community->config['mod_rewriteurl'] . '/' :
	$path[0];
$suit->vars['communitypath'] = $path[1] . $path[3];
$message = '';
$postdata = false;
$notauthorized = false;
$breadcrumbs = array();
$suit->vars['months'] = array
(
	'01' => 'January',
	'02' => 'February',
	'03' => 'March',
	'04' => 'April',
	'05' => 'May',
	'06' => 'June',
	'07' => 'July',
	'08' => 'August',
	'09' => 'September',
	'10' => 'October',
	'11' => 'November',
	'12' => 'December'
);
$suit->vars['zonelist'] = array
(
	'Kwajalein' => '(GMT-12:00) International Date Line West',
	'Pacific/Midway' => '(GMT-11:00) Midway Island',
	'Pacific/Samoa' => '(GMT-11:00) Samoa',
	'Pacific/Honolulu' => '(GMT-10:00) Hawaii',
	'America/Anchorage' => '(GMT-09:00) Alaska',
	'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US & Canada)',
	'America/Tijuana' => '(GMT-08:00) Tijuana, Baja California',
	'America/Denver' => '(GMT-07:00) Mountain Time (US & Canada)',
	'America/Chihuahua' => '(GMT-07:00) Chihuahua',
	'America/Mazatlan' => '(GMT-07:00) Mazatlan',
	'America/Phoenix' => '(GMT-07:00) Arizona',
	'America/Regina' => '(GMT-06:00) Saskatchewan',
	'America/Tegucigalpa' => '(GMT-06:00) Central America',
	'America/Chicago' => '(GMT-06:00) Central Time (US & Canada)',
	'America/Mexico_City' => '(GMT-06:00) Mexico City',
	'America/Monterrey' => '(GMT-06:00) Monterrey',
	'America/New_York' => '(GMT-05:00) Eastern Time (US & Canada)',
	'America/Bogota' => '(GMT-05:00) Bogota',
	'America/Lima' => '(GMT-05:00) Lima',
	'America/Rio_Branco' => '(GMT-05:00) Rio Branco',
	'America/Indiana/Indianapolis' => '(GMT-05:00) Indiana (East)',
	'America/Caracas' => '(GMT-04:30) Caracas',
	'America/Halifax' => '(GMT-04:00) Atlantic Time (Canada)',
	'America/Manaus' => '(GMT-04:00) Manaus',
	'America/Santiago' => '(GMT-04:00) Santiago',
	'America/La_Paz' => '(GMT-04:00) La Paz',
	'America/St_Johns' => '(GMT-03:30) Newfoundland',
	'America/Argentina/Buenos_Aires' => '(GMT-03:00) Georgetown',
	'America/Sao_Paulo' => '(GMT-03:00) Brasilia',
	'America/Godthab' => '(GMT-03:00) Greenland',
	'America/Montevideo' => '(GMT-03:00) Montevideo',
	'Atlantic/South_Georgia' => '(GMT-02:00) Mid-Atlantic',
	'Atlantic/Azores' => '(GMT-01:00) Azores',
	'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is.',
	'Europe/Dublin' => '(GMT) Dublin',
	'Europe/Lisbon' => '(GMT) Lisbon',
	'Europe/London' => '(GMT) London',
	'Africa/Monrovia' => '(GMT) Monrovia',
	'Atlantic/Reykjavik' => '(GMT) Reykjavik',
	'Africa/Casablanca' => '(GMT) Casablanca',
	'Europe/Belgrade' => '(GMT+01:00) Belgrade',
	'Europe/Bratislava' => '(GMT+01:00) Bratislava',
	'Europe/Budapest' => '(GMT+01:00) Budapest',
	'Europe/Ljubljana' => '(GMT+01:00) Ljubljana',
	'Europe/Prague' => '(GMT+01:00) Prague',
	'Europe/Sarajevo' => '(GMT+01:00) Sarajevo',
	'Europe/Skopje' => '(GMT+01:00) Skopje',
	'Europe/Warsaw' => '(GMT+01:00) Warsaw',
	'Europe/Zagreb' => '(GMT+01:00) Zagreb',
	'Europe/Brussels' => '(GMT+01:00) Brussels',
	'Europe/Copenhagen' => '(GMT+01:00) Copenhagen',
	'Europe/Madrid' => '(GMT+01:00) Madrid',
	'Europe/Paris' => '(GMT+01:00) Paris',
	'Africa/Algiers' => '(GMT+01:00) West Central Africa',
	'Europe/Amsterdam' => '(GMT+01:00) Amsterdam',
	'Europe/Berlin' => '(GMT+01:00) Berlin',
	'Europe/Rome' => '(GMT+01:00) Rome',
	'Europe/Stockholm' => '(GMT+01:00) Stockholm',
	'Europe/Vienna' => '(GMT+01:00) Vienna',
	'Europe/Minsk' => '(GMT+02:00) Minsk',
	'Africa/Cairo' => '(GMT+02:00) Cairo',
	'Europe/Helsinki' => '(GMT+02:00) Helsinki',
	'Europe/Riga' => '(GMT+02:00) Riga',
	'Europe/Sofia' => '(GMT+02:00) Sofia',
	'Europe/Tallinn' => '(GMT+02:00) Tallinn',
	'Europe/Vilnius' => '(GMT+02:00) Vilnius',
	'Europe/Athens' => '(GMT+02:00) Athens',
	'Europe/Bucharest' => '(GMT+02:00) Bucharest',
	'Europe/Istanbul' => '(GMT+02:00) Istanbul',
	'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
	'Asia/Amman' => '(GMT+02:00) Amman',
	'Asia/Beirut' => '(GMT+02:00) Beirut',
	'Africa/Windhoek' => '(GMT+02:00) Windhoek',
	'Africa/Harare' => '(GMT+02:00) Harare',
	'Asia/Kuwait' => '(GMT+03:00) Kuwait',
	'Asia/Riyadh' => '(GMT+03:00) Riyadh',
	'Asia/Baghdad' => '(GMT+03:00) Baghdad',
	'Africa/Nairobi' => '(GMT+03:00) Nairobi',
	'Asia/Tbilisi' => '(GMT+03:00) Tbilisi',
	'Europe/Moscow' => '(GMT+03:00) Moscow',
	'Europe/Volgograd' => '(GMT+03:00) Volgograd',
	'Asia/Tehran' => '(GMT+03:30) Tehran',
	'Asia/Muscat' => '(GMT+04:00) Muscat',
	'Asia/Baku' => '(GMT+04:00) Baku',
	'Asia/Yerevan' => '(GMT+04:00) Yerevan',
	'Asia/Yekaterinburg' => '(GMT+05:00) Ekaterinburg',
	'Asia/Karachi' => '(GMT+05:00) Karachi',
	'Asia/Tashkent' => '(GMT+05:00) Tashkent',
	'Asia/Kolkata' => '(GMT+05:30) Calcutta',
	'Asia/Colombo' => '(GMT+05:30) Sri Jayawardenepura',
	'Asia/Katmandu' => '(GMT+05:45) Kathmandu',
	'Asia/Dhaka' => '(GMT+06:00) Dhaka',
	'Asia/Almaty' => '(GMT+06:00) Almaty',
	'Asia/Novosibirsk' => '(GMT+06:00) Novosibirsk',
	'Asia/Rangoon' => '(GMT+06:30) Yangon (Rangoon)',
	'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
	'Asia/Bangkok' => '(GMT+07:00) Bangkok',
	'Asia/Jakarta' => '(GMT+07:00) Jakarta',
	'Asia/Brunei' => '(GMT+08:00) Beijing',
	'Asia/Chongqing' => '(GMT+08:00) Chongqing',
	'Asia/Hong_Kong' => '(GMT+08:00) Hong Kong',
	'Asia/Urumqi' => '(GMT+08:00) Urumqi',
	'Asia/Irkutsk' => '(GMT+08:00) Irkutsk',
	'Asia/Ulaanbaatar' => '(GMT+08:00) Ulaan Bataar',
	'Asia/Kuala_Lumpur' => '(GMT+08:00) Kuala Lumpur',
	'Asia/Singapore' => '(GMT+08:00) Singapore',
	'Asia/Taipei' => '(GMT+08:00) Taipei',
	'Australia/Perth' => '(GMT+08:00) Perth',
	'Asia/Seoul' => '(GMT+09:00) Seoul',
	'Asia/Tokyo' => '(GMT+09:00) Tokyo',
	'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
	'Australia/Darwin' => '(GMT+09:30) Darwin',
	'Australia/Adelaide' => '(GMT+09:30) Adelaide',
	'Australia/Canberra' => '(GMT+10:00) Canberra',
	'Australia/Melbourne' => '(GMT+10:00) Melbourne',
	'Australia/Sydney' => '(GMT+10:00) Sydney',
	'Australia/Brisbane' => '(GMT+10:00) Brisbane',
	'Australia/Hobart' => '(GMT+10:00) Hobart',
	'Asia/Vladivostok' => '(GMT+10:00) Vladivostok',
	'Pacific/Guam' => '(GMT+10:00) Guam',
	'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
	'Asia/Magadan' => '(GMT+11:00) Magadan',
	'Pacific/Fiji' => '(GMT+12:00) Fiji',
	'Asia/Kamchatka' => '(GMT+12:00) Kamchatka',
	'Pacific/Auckland' => '(GMT+12:00) Auckland',
	'Pacific/Tongatapu' => '(GMT+13:00) Nukualofa'
);
$suit->vars['illegal'] = array
(
	array('?', '*'),
	array('#', '*'),
	array(' ', '-'),
	array('/', '*'),
	array('=', '*'),
	array('&', '*')
);
$index = false;
$suit->vars['separator'] = $suit->getSection('section separator', $content);
$suit->vars['separator'] = (!empty($suit->vars['separator'])) ?
	$suit->vars['separator'][0] :
	'';
$suit->vars['page'] = $suit->getSection('section page', $content);
$suit->vars['page'] = (!empty($suit->vars['page'])) ?
	$suit->vars['page'][0] :
	'';
$suit->vars['breadcrumbseparator'] = $suit->getSection('section breadcrumbseparator', $content);
$suit->vars['breadcrumbseparator'] = (!empty($suit->vars['breadcrumbseparator'])) ?
	$suit->vars['breadcrumbseparator'][0] :
	'';
if ($logic)
{
	if (isset($_POST['login']))
		if (!$suit->community->user['u_id'])
		{
			$query = $suit->community->adodb->Prepare('SELECT `username`, `password`, `validate_string` FROM `' . $suit->tie->config['db']['prefix'] . 'users` WHERE `username` = ? AND `password` = ?;');
			$row = $suit->community->adodb->GetRow($query, array(strval($_POST['username']), md5($_POST['password'] . $suit->tie->config['db']['salt'])));
			if ($row && !$row['validate_string'])
			{
				setcookie($suit->community->config['cookieprefix'] . 'username', $row['username'], time() + $suit->community->config['cookielength'], $suit->community->config['cookiepath'], $suit->community->config['cookiedomain']);
				setcookie($suit->community->config['cookieprefix'] . 'password', $row['password'], time() + $suit->community->config['cookielength'], $suit->community->config['cookiepath'], $suit->community->config['cookiedomain']);
				$url = ($_POST['index'] == 'true') ?
					$communityindex :
					$_SERVER['HTTP_REFERER'];
				$suit->tie->redirect($url, $suit->vars['language']['loggedin'], NULL, 'cape/redirect');
			}
			else
				$message = ($_POST['username'] && $_POST['password']) ?
					(
						($row) ?
							$suit->vars['language']['validateemail'] :
							$suit->vars['language']['nomatch']
					) :
					$suit->vars['language']['requiredfields'];
		}
		else
			$postdata = true;
	elseif (isset($_POST['lostpassword']))
		if (!$suit->community->user['u_id'])
		{
			$query = $suit->community->adodb->Prepare('SELECT `id`, `email` FROM `' . $suit->tie->config['db']['prefix'] . 'users` WHERE `email` = ?;');
			$row = $suit->community->adodb->GetRow($query, array(strval($_POST['email'])));
			if ($row)
			{
				$string = substr(md5(md5('1skafd;p32q0' . uniqid(md5(rand()), true))), 0, 5);
				$password = substr(md5(md5('1skafd;p32q0' . uniqid(md5(rand()), true))), 0, 5);
				$query = $suit->community->adodb->Prepare('UPDATE `' . $suit->tie->config['db']['prefix'] . 'users` SET `recover_string` = ?, `recover_password` = ? WHERE `id` = ?;');
				$suit->community->adodb->Execute($query, array($string, md5($password . $suit->tie->config['db']['salt']), $row['id']));
				$suit->vars['password'] = $password;
				$suit->vars['string'] = $string;
				$suit->vars['id'] = $row['id'];
				$suit->vars['path'] = 'http://' . $_SERVER['HTTP_HOST'] . $path[0] . $path[2];
				$body = $suit->parse($nodes, $suit->vars['language']['lostpassword_body']);
				if (mail($row['email'], $suit->vars['language']['lostpassword_subject'], $body, $suit->vars['language']['emailheaders']))
					$suit->tie->redirect($communityindexredirect, $suit->vars['language']['passwordsent'], NULL, 'cape/redirect');
				else
					$message = $suit->vars['language']['emailnotsent'];
			}
			else
				$message = $suit->vars['language']['emailnotfound'];
		}
		else
			$postdata = true;
	elseif (isset($_POST['notes']))
		if ($suit->community->user['g_admin'])
		{
			$query = 'SELECT `content` FROM `' . $suit->tie->config['db']['prefix'] . 'notes`;';
			$row = $suit->community->adodb->GetRow($query);
			if ($row)
			{
				$query = $suit->community->adodb->Prepare('UPDATE `' . $suit->tie->config['db']['prefix'] . 'notes` SET `content` = ?;');
				$suit->community->adodb->Execute($query, array($_POST['content']));
				$suit->tie->redirect((($suit->community->config['mod_rewrite']) ?
					$suit->community->config['mod_rewriteurl'] . '/notes/':
					$path[0] . $path[2] . 'cmd=notes'), $suit->vars['language']['updatedsuccessfully'], NULL, 'cape/redirect');
			}
		}
		else
			$postdata = true;
	elseif (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['newtopic']) || isset($_POST['newreply']) || isset($_POST['edit']))
	{
		$query = $suit->community->adodb->Prepare((isset($_POST['newtopic'])) ?
			'SELECT `id`, `locked` FROM `' . $suit->tie->config['db']['prefix'] . 'forums` WHERE `id` = ?;' :
			'SELECT `topic`, `locked`, `poster` FROM `' . $suit->tie->config['db']['prefix'] . 'posts` WHERE `id` = ?;');
		$row = $suit->community->adodb->GetRow($query, array(intval($_GET['id'])));
		if ($row && $row['topic'] || !isset($_POST['newreply']))
			if ($suit->community->user['u_id'] && (!$row['locked'] && ($row['poster'] == $suit->community->user['u_id'] || !isset($_POST['edit']))) || $suit->community->user['g_mod'])
			{
				$smilies = (isset($_POST['smilies'])) ?
					$_POST['smilies'] :
					'';
				$signature = (isset($_POST['signature'])) ?
					$_POST['signature'] :
					'';
				if (!$_POST['title'])
					$message .= $suit->vars['language']['missingtitle'];
				if (strlen(trim($_POST['content'])) < 5)
					$message .= $suit->vars['language']['posttooshort'];
				if (!$message)
				{
					if (isset($_POST['newtopic']) || isset($_POST['newreply']))
					{
						$re = $_GET['id'];
						if (isset($_POST['newreply']) && isset($_GET['post']))
						{
							$query = $suit->community->adodb->Prepare('SELECT `id` FROM `' . $suit->tie->config['db']['prefix'] . 'posts` WHERE `id` = ? AND ((id = ? AND topic = \'1\') OR parent = ?);');
							$row = $suit->community->adodb->GetRow($query, array(intval($_GET['post']), intval($_GET['id']), intval($_GET['id'])));
							if ($row)
								$re = $row['id'];
						}
						$query = $suit->community->adodb->Prepare((isset($_POST['newtopic'])) ?
							'INSERT INTO `' . $suit->tie->config['db']['prefix'] . 'posts` (`title`, `content`, `poster`, `time`, `parent`, `re`, `topic`, `smilies`, `signature`) VALUES (?, ?, ?, ?, ?, ?, \'1\', ?, ?);' :
							'INSERT INTO `' . $suit->tie->config['db']['prefix'] . 'posts` (`title`, `content`, `poster`, `time`, `parent`, `re`, `smilies`, `signature`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
						$suit->community->adodb->Execute($query, array($_POST['title'], $_POST['content'], $suit->community->user['u_id'], mktime(), $_GET['id'], $re, $smilies, $signature));
						$query = $suit->community->adodb->Prepare('UPDATE `' . $suit->tie->config['db']['prefix'] . 'users` SET `posts` = `posts` + 1 WHERE `id` = ?;');
						$suit->community->adodb->Execute($query, array($suit->community->user['u_id']));
						$query = 'SELECT * FROM ' . $suit->tie->config['db']['prefix'] . 'posts ORDER BY time DESC LIMIT 0, 1;';
						$row = $suit->community->adodb->GetRow($query);
					}
					elseif (isset($_POST['edit']))
					{
						$query = $suit->community->adodb->Prepare('UPDATE `' . $suit->tie->config['db']['prefix'] . 'posts` SET `title` = ?, `content` = ?, `modified_time` = ?, `modified_user` = ?, `smilies` = ?, `signature` = ? WHERE `id` = ?;');
						$suit->community->adodb->Execute($query, array($_POST['title'], $_POST['content'], mktime(), $suit->community->user['u_id'], $smilies, $signature, intval($_GET['id'])));
						$query = $suit->community->adodb->Prepare('SELECT `id`, `parent`, `topic` FROM `' . $suit->tie->config['db']['prefix'] . 'posts` WHERE `id` = ?;');
						$row = $suit->community->adodb->GetRow($query, array(intval($_GET['id'])));
					}
					if ($row)
					{
						$topicid = ($row['topic']) ?
							$row['id'] :
							$row['parent'];
						$query = $suit->community->adodb->Prepare('SELECT `id`, `title` FROM `' . $suit->tie->config['db']['prefix'] . 'posts` WHERE id = ?');
						$topic = $suit->community->adodb->GetRow($query, array($topicid));
						if (isset($_POST['newtopic']) || isset($_POST['newreply']))
						{
							$query = $suit->community->adodb->Prepare('SELECT `id` FROM `' . $suit->tie->config['db']['prefix'] . 'posts` WHERE (`id` = ? AND `topic` = \'1\') OR `parent` = ?;');
							$result = $suit->community->adodb->GetAll($query, array($topic['id'], $topic['id']));
						}
						elseif (isset($_POST['edit']))
						{
							$query = $suit->community->adodb->Prepare('SELECT `id` FROM `' . $suit->tie->config['db']['prefix'] . 'posts` WHERE (`id` = ? AND `topic` = \'1\') OR `parent` = ? AND `id` <= ?;');
							$result = $suit->community->adodb->GetAll($query, array($topic['id'], $topic['id'], $row['id']));
						}
						$start = $suit->tie->reduce(count($result), true);
						$limit = $suit->tie->settings['limit'];
						if (isset($_POST['newtopic']) || isset($_POST['newreply']))
						{
							$query = $suit->community->adodb->Prepare('UPDATE `' . $suit->tie->config['db']['prefix'] . 'posts` SET `latest` = ?, `replies` = ? WHERE `id` = ?;');
							$suit->community->adodb->Execute($query, array($row['id'], count($result) - 1, $topic['id']));
							$query = $suit->community->adodb->Prepare('SELECT f.id
							FROM `' . $suit->tie->config['db']['prefix'] . 'posts` AS t
							LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'forums` AS f ON (t.parent = f.id)
							WHERE t.id = ?;');
							$forum = $suit->community->adodb->GetRow($query, array($topic['id']));
							$extra = (isset($_POST['newtopic'])) ?
								', `topics` = `topics` + 1' :
								'';
							$query = $suit->community->adodb->Prepare('UPDATE `' . $suit->tie->config['db']['prefix'] . 'forums` SET `latest` = ?' . $extra . ', `posts` = `posts` + 1 WHERE `id` = ?;');
							$suit->community->adodb->Execute($query, array($row['id'], $forum['id']));
						}
						$suit->tie->settings['start'] = 10;
						$mailstart = $suit->tie->reduce(count($result), true);
						if (isset($_POST['newreply']))
						{
							$query = $suit->community->adodb->Prepare('SELECT u.email `u_email`
							FROM `' . $suit->tie->config['db']['prefix'] . 'subscriptions` AS s
							LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'users` AS u ON (s.user = u.id)
							WHERE s.parent = ? AND  s.user != ?;');
							$result = $suit->community->adodb->GetAll($query, array($topic['id'], $suit->community->user['u_id']));
							foreach ($result as $row2)
							{
								$suit->vars['id'] = $row['id'];
								$suit->vars['start'] = $mailstart;
								$suit->vars['topic'] = $topic['id'];
								$suit->vars['topicid'] = $topic['id'];
								$suit->vars['topicrewrite'] = $suit->replace($suit->vars['illegal'], $topic['title']);
								$suit->vars['topictitle'] = $topic['title'];
								$suit->vars['username'] = $suit->community->user['u_username'];
								$suit->vars['path'] = 'http://' . $_SERVER['HTTP_HOST'] . $path[0] . $path[2];
								$body = $suit->parse($nodes, $suit->vars['language']['subscription_body']);
								mail($row2['u_email'], $suit->vars['language']['subscription_subject'], $body, $suit->vars['language']['emailheaders']);
							}
						}
						$suit->tie->redirect((($suit->community->config['mod_rewrite']) ?
							$suit->community->config['mod_rewriteurl'] . '/topic/' . $topic['id'] . '/' . $suit->replace($suit->vars['illegal'], $topic['title']) . '/' . $start . '/' . $limit . '/#post' . $row['id'] :
							$path[0] . $path[2] . 'cmd=topic&id=' . $topic['id'] . '&start=' . $start . '&limit=' . $limit . '#post' . $row['id']), $suit->vars['language']['postedsuccessfully'], NULL, 'cape/redirect');
					}
				}
			}
			else
				$postdata = true;
	}
	elseif (isset($_POST['profile']))
	{
		$query = $suit->community->adodb->Prepare('SELECT `id`, `username`, `email` FROM `' . $suit->tie->config['db']['prefix'] . 'users` WHERE `id` = ?;');
		$row = $suit->community->adodb->GetRow($query, array(intval($_GET['id'])));
		if ($row && ($suit->community->user['u_id'] == $row['id'] || $suit->community->user['g_admin']))
		{
            if ($_POST['homepage'])
            {
                if ($suit->community->validateURL($_POST['homepage']))
                    $homepage = htmlspecialchars($_POST['homepage']);
                else
                    $message .= $suit->vars['language']['homepagenotvalid'];
            }
            else
                $homepage = '';
            if ($_POST['month'])
            {
                if (array_key_exists($_POST['month'], $suit->vars['months']))
                    $month = $_POST['month'];
                else
                    $message .= $suit->vars['language']['birthmonthnotvalid'];
            }
            else
                $month = '';
            if ($_POST['year'])
            {
                if (intval($_POST['year']) >= 1910)
                    $year = intval($_POST['year']);
                else
                    $message .= $suit->vars['language']['birthyearnotvalid'];
            }
            else
                $year = '';
            if ($_POST['day'])
            {
                if (intval($_POST['day']) <= 31)
                    $day = intval($_POST['day']);
                else
                    $message .= $suit->vars['language']['birthdaynotvalid'];
            }
            else
                $day = '';
            if ($month && $day)
            {
                if (($month == 02 && $day > 28))
                    $message .= $suit->vars['language']['birthdaynotvalid'];
            }
            if (!(($month && $day && $year) || (!$month && !$day && !$year)))
                $message .= $suit->vars['language']['birthdayemptyfields'];
            if (isset($_POST['timezone']) && array_key_exists($_POST['timezone'], $suit->vars['zonelist']))
                $timezone = $_POST['timezone'];
            else
                $timezone = '';
			if (!$message)
			{
				$birthday = (!$month && !$day && !$year) ?
					'' :
					$month . '/' . $day . '/' . $year;
				$query = 'UPDATE `' . $suit->tie->config['db']['prefix'] . 'users` SET `aim` = ?, `icq` = ?, `yahoo` = ?, `msn` = ?, `homepage` = ?, `birthday` = ?, `location` = ?, `interests` = ?, `title` = ?, `avatar` = ?, `signature` = ?, `timezone` = ?';
				$params = array($_POST['aim'], $_POST['icq'], $_POST['yahoo'], $_POST['msn'], $_POST['homepage'], $birthday, $_POST['location'], $_POST['interests'], $_POST['title'], $_POST['avatar'], $_POST['signature'], $_POST['timezone']);
				if ($suit->community->user['g_admin'])
				{
					$query.= ', `username` = ?, `group` = ?';
					$params[] = $_POST['username'];
					$params[] = $_POST['group'];
					$username = $_POST['username'];
				}
				else
					$username = $row['username'];
				if ($_POST['password'])
				{
					$query.= ', `password` = ?';
					$params[] = md5($_POST['password'] . $suit->tie->config['db']['salt']);
				}
				if ($_POST['email'])
				{
					$string = substr(md5(md5('1skafd;p32q0' . uniqid(md5(rand()), true))), 0, 5);
					$query.= ', `change_string` = ?, `change_email` = ?';
					$params[] = $string;
					$params[] = $_POST['email'];
					$suit->vars['string'] = $string;
					$suit->vars['id'] = $row['id'];
					$suit->vars['path'] = 'http://' . $_SERVER['HTTP_HOST'] . $path[0] . $path[2];
					$body = $suit->parse($nodes, $suit->vars['language']['change_body']);
					if (!mail($row['email'], $suit->vars['language']['change_subject'], $body, $suit->vars['language']['emailheaders']))
						$message = $suit->vars['language']['emailnotsent'];
					$redirect = $suit->vars['language']['validateemail'];
				}
				else
					$redirect = $suit->vars['language']['updatedsuccessfully'];
				$query .= ' WHERE `id` = ?;';
				$params[] = $row['id'];
				$suit->community->adodb->Execute($suit->community->adodb->Prepare($query), $params);
				if (!$message)
					$suit->tie->redirect((($suit->community->config['mod_rewrite']) ?
						$suit->community->config['mod_rewriteurl'] . '/profile/' . $row['id'] . '/' . $suit->replace($suit->vars['illegal'], $username) . '/' :
						$path[0] . $path[2] . 'cmd=profile&id=' . $row['id']), $redirect, NULL, 'cape/redirect');
			}
		}
		else
			$postdata = true;
	}
	elseif (isset($_POST['register']))
		if (!$suit->community->user['u_id'])
		{
			if (isset($_POST['email']) && $suit->community->validateEmail($_POST['email']))
			{
				$query = $suit->community->adodb->Prepare('SELECT `email` FROM `' . $suit->tie->config['db']['prefix'] . 'users` WHERE `email` = ? AND `validate_string` = \'\';');
				$row = $suit->community->adodb->GetRow($query, array(strval($_POST['email'])));
				if ($row)
					$message .= $suit->vars['language']['emailexists'];
			}
			else
				$message .= $suit->vars['language']['emailnotvalid'];
			if ((strlen(trim($_POST['username'])) >= 7) && (strlen(trim($_POST['username'])) <= 50))
			{
				$query = $suit->community->adodb->Prepare('SELECT * FROM `' . $suit->tie->config['db']['prefix'] . 'users` WHERE `username` = ? AND `validate_string` = \'\';');
				$row = $suit->community->adodb->GetRow($query, array(strval($_POST['username'])));
				if ($row)
					$message .= $suit->vars['language']['usernametaken'];
			}
			else
				$message .= $suit->vars['language']['usernamenotvalid'];
			if ((strlen($_POST['password']) >= 7) && (strlen($_POST['password']) <= 32))
				$password = md5($_POST['password'] . $suit->tie->config['db']['salt']);
			else
				$message .= $suit->vars['language']['passwordnotvalid'];
            if ($_POST['homepage'])
            {
                if ($suit->community->validateURL($_POST['homepage']))
                    $homepage = htmlspecialchars($_POST['homepage']);
                else
                    $message .= $suit->vars['language']['homepagenotvalid'];
            }
            else
                $homepage = '';
            if ($_POST['month'])
            {
                if (array_key_exists($_POST['month'], $suit->vars['months']))
                    $month = $_POST['month'];
                else
                    $message .= $suit->vars['language']['birthmonthnotvalid'];
            }
            else
                $month = '';
            if ($_POST['year'])
            {
                if (intval($_POST['year']) >= 1910)
                    $year = intval($_POST['year']);
                else
                    $message .= $suit->vars['language']['birthyearnotvalid'];
            }
            else
                $year = '';
            if ($_POST['day'])
            {
                if (intval($_POST['day']) <= 31)
                    $day = intval($_POST['day']);
                else
                    $message .= $suit->vars['language']['birthdaynotvalid'];
            }
            else
                $day = '';
            if ($month && $day)
            {
                if (($month == 02 && $day > 28))
                    $message .= $suit->vars['language']['birthdaynotvalid'];
            }
            if (!(($month && $day && $year) || (!$month && !$day && !$year)))
                $message .= $suit->vars['language']['birthdayemptyfields'];
            if (isset($_POST['timezone']) && array_key_exists($_POST['timezone'], $suit->vars['zonelist']))
                $timezone = $_POST['timezone'];
            else
                $timezone = '';
			if (isset($_POST['recaptcha_challenge_field']) && isset($_POST['recaptcha_response_field']))
			{
				$resp = recaptcha_check_answer($suit->vars['privatekey'], $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
				if (!$resp->is_valid)
					$message .= $suit->vars['language']['recaptchanotvalid'];
			}
			else
				$message .= $suit->vars['language']['recaptchanotvalid'];
			if (!$message)
			{
				$string = substr(md5(md5('1skafd;p32q0' . uniqid(md5(rand()), true))), 0, 5);
				$suit->vars['email'] = urlencode($_POST['email']);
				$suit->vars['string'] = urlencode($string);
				$suit->vars['path'] = 'http://' . $_SERVER['HTTP_HOST'] . $path[0] . $path[2];
				$body = $suit->parse($nodes, $suit->vars['language']['register_body']);
				if (mail($_POST['email'], $suit->vars['language']['validate'], $body, $suit->vars['language']['emailheaders']))
				{
					$query = $suit->community->adodb->Prepare('SELECT `email` FROM `' . $suit->tie->config['db']['prefix'] . 'users` WHERE `email` = ?;');
					$row = $suit->community->adodb->GetRow($query, array(strval($_POST['email'])));
					$birthday = (!$month && !$day && !$year) ?
						'' :
						$month . '/' . $day . '/' . $year;
					$query = $suit->community->adodb->Prepare(($row) ?
						'UPDATE `' . $suit->tie->config['db']['prefix'] . 'users` SET `validate_string` = ?, `username` = ?, `password` = ?, `timezone` = ?, `aim` = ?, `icq` = ?, `yahoo` = ?, `msn` = ?, `homepage` = ?, `birthday` = ?, `location` = ?, `interests` = ?, `title` = ?, `avatar` = ?, `signature` = ?, `joined` = ? WHERE `email` = ?;' :
						'INSERT INTO `' . $suit->tie->config['db']['prefix'] . 'users` (`validate_string`, `username`, `password`, `timezone`, `aim`, `icq`, `yahoo`, `msn`, `homepage`, `birthday`, `location`, `interests`, `title`, `avatar`, `signature`, `joined`, `email`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
					$suit->community->adodb->Execute($query, array($string, $_POST['username'], $password, $timezone, $_POST['aim'], $_POST['icq'], $_POST['yahoo'], $_POST['msn'], $homepage, $birthday, $_POST['location'], $_POST['interests'], $_POST['title'], $_POST['avatar'], $_POST['signature'], mktime(), strval($_POST['email'])));
					$suit->tie->redirect($communityindexredirect, $suit->vars['language']['validateemail'], NULL, 'cape/redirect');
				}
				else
					$message .= $suit->vars['language']['emailnotsent'];
			}
		}
		else
			$postdata = true;
	$suit->vars['message'] = $message;
	if (!$postdata)
		switch ($_GET['cmd'])
		{
			case 'change':
				$query = $suit->community->adodb->Prepare('SELECT `id` FROM `' . $suit->tie->config['db']['prefix'] . 'users` WHERE `id` = ? AND `change_string` = ?;');
				$row = $suit->community->adodb->GetRow($query, array(intval($_GET['id']), strval($_GET['string'])));
				if ($row)
				{
					$query = $suit->community->adodb->Prepare('UPDATE `' . $suit->tie->config['db']['prefix'] . 'users` SET `email` = `change_email`, `change_email` = \'\', `change_string` = \'\' WHERE `id` = ?;');
					$suit->community->adodb->Execute($query, array(intval($_GET['id'])));
					$suit->tie->redirect($communityindexredirect, $suit->vars['language']['validatedsuccessfully'], NULL, 'cape/redirect');
				}
				else
					$suit->tie->redirect($communityindexredirect, $suit->vars['language']['emailexpired'], NULL, 'cape/redirect');
				break;
			case 'edit':
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
				$index = true;
				$query = $suit->community->adodb->Prepare('SELECT * FROM `' . $suit->tie->config['db']['prefix'] . 'posts` WHERE `id` = ?;');
				$row = $suit->community->adodb->GetRow($query, array(intval($_GET['id'])));
				if ($row)
				{
					$breadrow = (!$row['topic']) ?
						$suit->community->adodb->GetRow($query, array($row['parent'])) :
						$row;
					if ($row)
					{
						$breadcrumbs = array_merge
						(
							$breadcrumbs,
							$suit->community->breadcrumbs($breadrow['parent'])
						);
						$breadcrumbs[] = array
						(
							array
							(
								array('[title]', $breadrow['title']),
								array('[url]', 	(($suit->community->config['mod_rewrite']) ?
									$suit->community->config['mod_rewriteurl'] . '/topic/' . $breadrow['id'] . '/' . htmlspecialchars($suit->replace($suit->vars['illegal'], $row['title'])) . '/' :
									$path[1] . $path[3] . 'cmd=topic&id=' . $breadrow['id']))
							),
							array
							(
								$suit->parseConditional('if url', true)
							)
						);
						if ($_GET['cmd'] == 'edit')
						{
							$community = $suit->community->createForm($row, 'edit');
							$notauthorized = $suit->vars['notauthorized'];
							$section = $suit->vars['language']['edit'];
							$breadcrumbs[] = array
							(
								array
								(
									array('[title]', $suit->vars['language']['edit'])
								),
								array
								(
									$suit->parseConditional('if url', false)
								)
							);
						}
					}
				}
				else
				{
					$community = $suit->vars['language']['postnotfound'];
					$section = $suit->vars['language']['postnotfound'];
					$breadcrumbs[] = array
					(
						array
						(
							array('[title]', $suit->vars['language']['postnotfound'])
						),
						array
						(
							$suit->parseConditional('if url', false)
						)
					);
				}
				break;
			case 'logout':
				if ($suit->community->user['u_id'])
				{
					setcookie($suit->community->config['cookieprefix'] . 'username', '', time() - $suit->community->config['cookielength'], $suit->community->config['cookiepath'], $suit->community->config['cookiedomain']);
					setcookie($suit->community->config['cookieprefix'] . 'password', '', time() - $suit->community->config['cookielength'], $suit->community->config['cookiepath'], $suit->community->config['cookiedomain']);
					$url = ($_GET['index'] == 'true') ?
						$communityindex :
						$_SERVER['HTTP_REFERER'];
					$suit->tie->redirect($url, $suit->vars['language']['loggedout'], NULL, 'cape/redirect');
				}
				else
					$notauthorized = true;
				break;
			case 'lostpassword':
				if (!$suit->community->user['u_id'])
				{
					if (isset($_GET['id']) && isset($_GET['string']))
					{
						$query = $suit->community->adodb->Prepare('SELECT `id` FROM `' . $suit->tie->config['db']['prefix'] . 'users` WHERE `id` = ? AND `recover_string` = ?;');
						$row = $suit->community->adodb->GetRow($query, array(intval($_GET['id']), strval($_GET['string'])));
						if ($row)
						{
							$query = $suit->community->adodb->Prepare('UPDATE `' . $suit->tie->config['db']['prefix'] . 'users` SET `password` = `recover_password`, `recover_password` = \'\', `recover_string` = \'\' WHERE `id` = ?;');
							$suit->community->adodb->Execute($query, array(intval($_GET['id'])));
							$suit->tie->redirect($communityindexredirect, $suit->vars['language']['validatedsuccessfully'], NULL, 'cape/redirect');
						}
						else
							$suit->tie->redirect($communityindexredirect, $suit->vars['language']['passwordexpired'], NULL, 'cape/redirect');
					}
					$community = $suit->getTemplate('cape/lostpassword');
					$section = $suit->vars['language']['lostpassword'];
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
					$breadcrumbs[] = array
					(
						array
						(
							array('[title]', $suit->vars['language']['lostpassword'])
						),
						array
						(
							$suit->parseConditional('if url', false)
						)
					);
					$index = true;
				}
				else
					$notauthorized = true;
				break;
			case 'members':
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
				$breadcrumbs[] = array
				(
					array
					(
						array('[title]', $suit->vars['language']['members'])
					),
					array
					(
						$suit->parseConditional('if url', false)
					)
				);
				$community = $suit->getTemplate('cape/members');
				$section = $suit->vars['section'];
				$logic = $suit->vars['logic'];
				if ($logic)
					$breadcrumbs = array_merge($breadcrumbs, $suit->vars['breadcrumbs']);
				else
					$breadcrumbs = $suit->vars['breadcrumbs'];
				break;
			case 'newreply':
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
				$index = true;
				$query = $suit->community->adodb->Prepare('SELECT `id`, `title`, `parent` FROM `' . $suit->tie->config['db']['prefix'] . 'posts` WHERE `id` = ? AND `topic` = \'1\';');
				$row = $suit->community->adodb->GetRow($query, array(intval($_GET['id'])));
				if ($row)
				{
					$breadcrumbs = array_merge
					(
						$breadcrumbs,
						$suit->community->breadcrumbs($row['parent'])
					);
					$community = $suit->community->createForm($row, 'newreply');
					$notauthorized = $suit->vars['notauthorized'];
					$section = $suit->vars['language']['newreply'];
					$breadcrumbs[] = array
					(
						array
						(
							array('[title]', htmlspecialchars($row['title'])),
							array('[url]', (($suit->community->config['mod_rewrite']) ?
								$suit->community->config['mod_rewriteurl'] . '/topic/' . $row['id'] . '/' . htmlspecialchars($suit->replace($suit->vars['illegal'], $row['title'])) . '/' :
								$path[1] . $path[3] . 'cmd=topic&amp;id=' . $row['id']))
						),
						array
						(
							$suit->parseConditional('if url', true)
						)
					);
					$breadcrumbs[] = array
					(
						array
						(
							array('[title]', $suit->vars['language']['newreply'])
						),
						array
						(
							$suit->parseConditional('if url', false)
						)
					);
				}
				else
				{
					$community = $suit->vars['language']['topicnotfound'];
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
				break;
			case 'newtopic':
				$query = $suit->community->adodb->Prepare('SELECT * FROM `' . $suit->tie->config['db']['prefix'] . 'forums` WHERE `id` = ?;');
				$row = $suit->community->adodb->GetRow($query, array(intval($_GET['id'])));
				if ($row)
				{
					$forumlocked = $row['locked'];
					$community = $suit->community->createForm($row, 'newtopic');
					$notauthorized = $suit->vars['notauthorized'];
					$section = $suit->vars['language']['newtopic'];
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
					$index = true;
					$breadcrumbs = array_merge
					(
						$breadcrumbs,
						$suit->community->breadcrumbs($row['parent'])
					);
					$breadcrumbs[] = array
					(
						array
						(
							array('[title]', htmlspecialchars($row['title'])),
							array('[url]', (($suit->community->config['mod_rewrite']) ?
								$suit->community->config['mod_rewriteurl'] . '/forum/' . $row['id'] . '/' . htmlspecialchars($suit->replace($suit->vars['illegal'], $row['title'])) . '/' :
								$path[1] . $path[3] . 'cmd=forum&amp;id=' . $row['id']))
						),
						array
						(
							$suit->parseConditional('if url', true)
						)
					);
					$breadcrumbs[] = array
					(
						array
						(
							array('[title]', $suit->vars['language']['newtopic'])
						),
						array
						(
							$suit->parseConditional('if url', false)
						)
					);
				}
				else
				{
					$community = $suit->vars['language']['forumnotfound'];
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
				break;
			case 'notes':
				if ($suit->community->user['g_admin'])
				{
					$community = $suit->getTemplate('cape/notes');
					$section = $suit->vars['language']['notes'];
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
					$breadcrumbs[] = array
					(
						array
						(
							array('[title]', $suit->vars['language']['notes'])
						),
						array
						(
							$suit->parseConditional('if url', false)
						)
					);
					$index = true;
				}
				else
					$notauthorized = true;
				break;
			case 'profile':
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
				$breadcrumbs[] = array
				(
					array
					(
						array('[title]', $suit->vars['language']['viewingprofile'])
					),
					array
					(
						$suit->parseConditional('if url', false)
					)
				);
				$community = $suit->getTemplate('cape/profile');
				$section = $suit->vars['section'];
				$breadcrumbs = array_merge($breadcrumbs, $suit->vars['breadcrumbs']);
				break;
			case 'recache':
				if ($suit->community->user['g_admin'])
				{
					$forums = array();
					$users = array();
					$query = 'SELECT `id`, `parent`, `poster` FROM `' . $suit->tie->config['db']['prefix'] . 'posts` WHERE `topic` = \'1\';';
					$result = $suit->community->adodb->GetAll($query);
					foreach ($result as $row)
					{
						$replies = 0;
						$forums[$row['parent']]['topics']++;
						$users[$row['poster']]['posts']++;
						$query = $suit->community->adodb->Prepare('SELECT `id`, `poster`, `time` FROM `' . $suit->tie->config['db']['prefix'] . 'posts` WHERE `parent` = ? ORDER BY `time`;');
						$result2 = $suit->community->adodb->GetAll($query, array(intval($row['id'])));
						if ($result2)
						{
							foreach ($result2 as $row2)
							{
								$replies++;
								$forums[$row['parent']]['posts']++;
								$users[$row2['poster']]['posts']++;
							}
							$last = array_pop($result2);
							$latest = $last['id'];
							if ($last['time'] > $forums[$row['parent']]['latesttime'])
							{
								$forums[$row['parent']]['latest'] = $last['id'];
								$forums[$row['parent']]['latesttime'] = $last['time'];
							}
						}
						$query = $suit->community->adodb->Prepare('UPDATE `' . $suit->tie->config['db']['prefix'] . 'posts` SET `replies` = ?, `latest` = ? WHERE `id` = ?;');
						$suit->community->adodb->Execute($query, array($replies, $latest, $row['id']));
					}
					$query = 'SELECT `id` FROM `' . $suit->tie->config['db']['prefix'] . 'forums`;';
					$result = $suit->community->adodb->GetAll($query);
					foreach ($result as $row)
					{
						if (!isset($forums[$row['id']]['topics']))
							$forums[$row['id']]['topics'] = 0;
						if (!isset($forums[$row['id']]['posts']))
							$forums[$row['id']]['posts'] = 0;
						if (!isset($forums[$row['id']]['latest']))
							$forums[$row['id']]['latest'] = 0;
						$query = $suit->community->adodb->Prepare('UPDATE `' . $suit->tie->config['db']['prefix'] . 'forums` SET `topics` = ?, `posts` = ?, `latest` = ? WHERE `id` = ?;');
						$suit->community->adodb->Execute($query, array($forums[$row['id']]['topics'], $forums[$row['id']]['topics'] + $forums[$row['id']]['posts'], $forums[$row['id']]['latest'], $row['id']));
					}
					$query = 'SELECT `id` FROM `' . $suit->tie->config['db']['prefix'] . 'users`;';
					$result = $suit->community->adodb->GetAll($query);
					foreach ($result as $row)
					{
						if (!isset($users[$row['id']]['posts']))
							$users[$row['id']]['posts'] = 0;
						$query = $suit->community->adodb->Prepare('UPDATE `' . $suit->tie->config['db']['prefix'] . 'users` SET `posts` = ? WHERE `id` = ?;');
						$suit->community->adodb->Execute($query, array($users[$row['id']]['posts'], $row['id']));
					}
					$suit->tie->redirect($_SERVER['HTTP_REFERER'], $suit->vars['language']['recachedsuccessfully'], NULL, 'cape/redirect');
				}
				else
					$notauthorized = true;
				break;
			case 'register':
				if (!$suit->community->user['u_id'])
				{
					$community = $suit->getTemplate('cape/register');
					$section = $suit->vars['language']['register'];
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
					$breadcrumbs[] = array
					(
						array
						(
							array('[title]', $suit->vars['language']['register'])
						),
						array
						(
							$suit->parseConditional('if url', false)
						)
					);
					$index = true;
				}
				else
					$notauthorized = true;
				break;
			case 'subscribe':
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
				if ($suit->community->user['u_id'])
				{
					$query = $suit->community->adodb->Prepare('SELECT t.id `t_id`, t.title `t_title`,
					s.id `s_id`
					FROM `' . $suit->tie->config['db']['prefix'] . 'posts` AS t
					LEFT JOIN `' . $suit->tie->config['db']['prefix'] . 'subscriptions` AS s ON (s.parent = t.id AND s.user = ?)
					WHERE t.id = ? AND t.topic = \'1\';');
					$row = $suit->community->adodb->GetRow($query, array($suit->community->user['u_id'], intval($_GET['id'])));
					if ($row)
					{
						if ($row['s_id'])
						{
							$query = $suit->community->adodb->Prepare('DELETE FROM `' . $suit->tie->config['db']['prefix'] . 'subscriptions` WHERE `id` = ?;');
							$suit->community->adodb->Execute($query, array($row['s_id']));
							$suit->tie->redirect((($suit->community->config['mod_rewrite']) ?
								$suit->community->config['mod_rewriteurl'] . '/topic/' . $row['t_id'] . '/' . $suit->replace($suit->vars['illegal'], $row['t_title']) . '/' :
								$path[0] . $path[2] . 'cmd=topic&id=' . $row['t_id']), $suit->vars['language']['unsubscribedsuccessfully'], NULL, 'cape/redirect');
						}
						else
						{
							$query = $suit->community->adodb->Prepare('INSERT INTO `' . $suit->tie->config['db']['prefix'] . 'subscriptions` (`user`, `parent`) VALUES (?, ?);');
							$suit->community->adodb->Execute($query, array($suit->community->user['u_id'], $row['t_id']));
							$suit->tie->redirect((($suit->community->config['mod_rewrite']) ?
								$suit->community->config['mod_rewriteurl'] . '/topic/' . $row['t_id'] . '/' . $suit->replace($suit->vars['illegal'], $row['t_title']) . '/' :
								$path[0] . $path[2] . 'cmd=topic&id=' . $row['t_id']), $suit->vars['language']['subscribedsuccessfully'], NULL, 'cape/redirect');
						}
					}
					else
					{
						$community = $suit->vars['language']['topicnotfound'];
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
				}
				else
					$notauthorized = true;
				break;
			case 'topic':
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
				$community = $suit->getTemplate('cape/posts');
				$section = $suit->vars['section'];
				$logic = $suit->vars['logic'];
				if ($logic)
					$breadcrumbs = array_merge($breadcrumbs, $suit->vars['breadcrumbs']);
				else
					$breadcrumbs = $suit->vars['breadcrumbs'];
				break;
			case 'validate':
				if (!$suit->community->user['u_id'])
				{
					$query = $suit->community->adodb->Prepare('SELECT `email` FROM `' . $suit->tie->config['db']['prefix'] . 'users` WHERE `email` = ? AND `validate_string` = ?;');
					$row = $suit->community->adodb->GetRow($query, array(strval($_GET['email']), strval($_GET['string'])));
					if ($row)
					{
						$query = $suit->community->adodb->Prepare('UPDATE `' . $suit->tie->config['db']['prefix'] . 'users` SET `validate_string` = \'\' WHERE `email` = ?;');
						$suit->community->adodb->Execute($query, array(strval($_GET['email'])));
						$suit->tie->redirect($communityindexredirect, $suit->vars['language']['validatedsuccessfully'], NULL, 'cape/redirect');
					}
					else
						$suit->tie->redirect($communityindexredirect, $suit->vars['language']['passwordexpired'], NULL, 'cape/redirect');
				}
				else
					$notauthorized = true;
				break;
			default:
				$community = $suit->getTemplate('cape/forums');
				$section = $suit->vars['section'];
				$logic = $suit->vars['logic'];
				if ($logic)
					$breadcrumbs = array_merge($breadcrumbs, $suit->vars['breadcrumbs']);
				else
					$breadcrumbs = $suit->vars['breadcrumbs'];
				break;
	}
}
if (!$logic)
{
	$community = $suit->vars['language']['badrequest'];
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
	$breadcrumbs[] = array
	(
		array
		(
			array('[title]', $suit->vars['language']['badrequest'])
		),
		array
		(
			$suit->parseConditional('if url', false)
		)
	);
	$section = $suit->vars['language']['badrequest'];
}
elseif ($postdata)
{
	$community = $suit->getTemplate('cape/postdata');
	$entries = array();
	foreach ($_POST as $key => $value)
	{
		$entries[] = array
		(
			array
			(
				array('[key]', nl2br(htmlentities($key))),
				array('[value]', nl2br(htmlentities($value)))
			),
			array()
		);
	}
	$suit->vars['name'] = $suit->vars['language']['notauthorized'];
	$nodes[] = $suit->parseLoop('loop entries', $entries);
	$community = $suit->parse($nodes, $community);
	$breadcrumbs[] = array
	(
		array
		(
			array('[title]', $suit->vars['language']['community']),
			array('[url]', $path[1])
		),
		array
		(
			$suit->parseConditional('if url', true)
		)
	);
	$breadcrumbs[] = array
	(
		array
		(
			array('[title]', $suit->vars['language']['notauthorized'])
		),
		array
		(
			$suit->parseConditional('if url', false)
		)
	);
	$section = $suit->vars['language']['notauthorized'];
}
elseif ($notauthorized)
{
	$community = $suit->vars['language']['notauthorized'];
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
	$breadcrumbs[] = array
	(
		array
		(
			array('[title]', $suit->vars['language']['notauthorized'])
		),
		array
		(
			$suit->parseConditional('if url', false)
		)
	);
	$section = $suit->vars['language']['notauthorized'];
}
$name = array($suit->vars['language']['community']);
foreach ($breadcrumbs as $key => $value)
	if ($key)
		$name[] = $value[0][0][1];
$suit->vars['community'] = $community;
$suit->vars['name'] = implode($suit->vars['separator'], $name);
$suit->vars['section'] = htmlspecialchars($section);
$suit->vars['message'] = $message;
$nodes[] = $suit->parseConditional('if index', ($index), 'else index');
$nodes[] = $suit->parseConditional('if message', (isset($_POST['login'])));
$nodes[] = $suit->parseConditional('section separator', false);
$nodes[] = $suit->parseConditional('section page', false);
$nodes[] = $suit->parseConditional('section breadcrumbseparator', false);
$nodes[] = $suit->parseLoop('loop breadcrumbs', $breadcrumbs, $suit->vars['breadcrumbseparator']);
$content = $suit->parse($nodes, $content);
?>