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
class COMMUNITY
{
	public $config;

	public $adodb;

	public $owner;

	public $user = array();

	function __construct(&$owner)
	{
		$this->owner = &$owner;
		include 'adodb/adodb.inc.php';
		$this->adodb = NewADOConnection($this->owner->tie->config['db']['type']);
		$this->adodb->Connect($this->owner->tie->config['db']['host'], $this->owner->tie->config['db']['user'], $this->owner->tie->config['db']['pass'], $this->owner->tie->config['db']['name']);
		$query = 'SELECT * FROM `' . $this->owner->tie->config['db']['prefix'] . 'config`;';
		$result = $this->adodb->GetAll($query);
		foreach ($result as $row)
			$this->config[$row['key']] = $row['value'];
		$this->user = array('u_username' => 0, 'u_password' => '', 'u_timezone' => $this->config['timezone']);
		if (isset($_COOKIE[$this->config['cookieprefix'] . 'username']) && isset($_COOKIE[$this->config['cookieprefix'] . 'password']))
		{
			$query = $this->adodb->Prepare('SELECT u.id `u_id`, u.username `u_username`, u.password `u_password`, u.email `u_email`, u.group `u_group`, u.recover_string `u_recover_string`, u.recover_password `u_recover_password`, u.title `u_title`, u.avatar `u_avatar`, u.signature `u_signature`, u.joined `u_joined`, u.timezone `u_timezone`, u.lastactivity `u_lastactivity`, u.aim `u_aim`, u.icq `u_icq`, u.yahoo `u_yahoo`, u.msn `u_msn`, u.homepage `u_homepage`, u.location `u_location`, u.interests `u_interests`, u.validate_string `u_validate_string`,
			g.id `g_id`, g.title `g_title`, g.admin `g_admin`, g.mod `g_mod`
			FROM `' . $this->owner->tie->config['db']['prefix'] . 'users` AS u
			LEFT JOIN `' . $this->owner->tie->config['db']['prefix'] . 'groups` AS t ON (u.group = t.id)
			LEFT JOIN `' . $this->owner->tie->config['db']['prefix'] . 'groups` AS g ON (g.id = coalesce(t.id, ?))
			WHERE `username` = ? AND `password` = ? AND `validate_string` = \'\';');
			$row = $this->adodb->GetRow($query, array($this->config['group'], $_COOKIE[$this->config['cookieprefix'] . 'username'], $_COOKIE[$this->config['cookieprefix'] . 'password']));
			if (!$row)
			{
				setcookie($this->config['cookieprefix'] . 'username', '', time() - $this->config['cookielength'], $this->config['cookiepath'], $this->config['cookiedomain']);
				setcookie($this->config['cookieprefix'] . 'password', '', time() - $this->config['cookielength'], $this->config['cookiepath'], $this->config['cookiedomain']);
				putenv('TZ=' . $this->config['timezone']);
				mktime(0, 0, 0, 1, 1, 1970);
			}
			else
			{
				$timezone = ($row['u_timezone']) ?
					$row['u_timezone'] :
					$this->config['timezone'];
				putenv('TZ=' . $timezone);
				mktime(0, 0, 0, 1, 1, 1970);
				$row['u_lastactivity'] = mktime();
				$query = $this->adodb->Prepare('UPDATE `' . $this->owner->tie->config['db']['prefix'] . 'users` SET `lastactivity` = ? WHERE `id` = ?;');
				$this->adodb->Execute($query, array($row['u_lastactivity'], $row['u_id']));
				$this->user = $row;
			}
		}
	}

	function __deconstruct()
	{
		$this->adodb->close();
	}

	function breadcrumbs($id)
	{
		$return = array();
		do
		{
			$query = $this->adodb->Prepare('SELECT `id`, `title`, `parent` FROM `' . $this->owner->tie->config['db']['prefix'] . 'forums` WHERE `id` = ?;');
			$row = $this->adodb->GetRow($query, array($id));
			if ($row)
				$return = array_merge
				(
					array
					(
						array
						(
							array
							(
								array('[title]', htmlspecialchars($row['title'])),
								array('[url]', (($this->config['mod_rewrite']) ?
									$this->config['mod_rewriteurl'] . '/forum/' . $row['id'] . '/' . htmlspecialchars($this->owner->replace($this->owner->vars['illegal'], $row['title']) . '/') :
									$this->owner->vars['communitypath'] . 'cmd=forum&amp;id=' . $row['id']))
							),
							array
							(
								$this->owner->parseConditional('if url', true)
							)
						)
					),
					$return
				);
				$id = $row['parent'];
		}
		while ($row);
		return $return;
	}

	function createForm ($post, $name)
	{
		$nodes = $this->owner->config['parse']['nodes'];
		$nodes[] = $this->owner->parseConditional('if mod_rewrite', ($suit->community->config['mod_rewrite']), 'else mod_rewrite');
		$this->owner->vars['smileypath'] = $this->config['smileypath'];
		$this->owner->vars['notauthorized'] = (!($this->user['u_id'] && (!$post['locked'] && ($post['poster'] == $this->user['u_id'] || $name != 'edit')) || $this->user['g_mod']));
		$community = $this->owner->getTemplate('cape/form');
		$preview = array();
		if ($this->owner->vars['message'] || isset($_POST['preview']))
		{
			$query = $this->adodb->Prepare('SELECT u.id `u_id`, u.signature `u_signature`, u.avatar `u_avatar`, u.username `u_username`, u.title `u_title`, u.posts `u_posts`,
			g.title `g_title`
			FROM `' . $this->owner->tie->config['db']['prefix'] . 'users` AS u
			LEFT JOIN `' . $this->owner->tie->config['db']['prefix'] . 'groups` AS t ON (u.group = t.id)
			LEFT JOIN `' . $this->owner->tie->config['db']['prefix'] . 'groups` AS g ON (g.id = coalesce(t.id, ?))
			WHERE u.id = ?;');
			$row = $this->adodb->GetRow($query, array($this->config['group'], (($name != 'edit') ?
				$this->user['u_id'] :
				$post['poster'])));
			$title = $_POST['title'];
			$content = $_POST['content'];
			foreach ($_POST as $key => $value)
				$row['p_' . $key] = $value;
			if (!isset($row['p_time']))
				$row['p_time'] = mktime();
			$preview[] = array
			(
				$this->createPost($row),
				array
				(
					$this->owner->parseConditional('if avatar', $this->owner->vars['avatar']),
					$this->owner->parseConditional('if signature', (!(isset($_POST['id']) && (!isset($_POST['signature']))) && $this->owner->vars['signature'])),
					$this->owner->parseConditional('if any', false),
					$this->owner->parseConditional('if edited', false),
					$this->owner->parseConditional('if user', (isset($row['u_id'])), 'else user'),
					$this->owner->parseConditional('if group', (isset($row['g_title']) && isset($row['u_id']))),
					$this->owner->parseConditional('if title', (isset($row['u_title'])))
				)
			);
		}
		else
		{
			if ($name == 'edit')
				$content = $post['content'];
			elseif ($name == 'newreply' && isset($_GET['post']))
			{
				$query = $this->adodb->Prepare('SELECT p.id `p_id`, p.time `p_time`, p.content `p_content`,
				u.username `u_username`
				FROM `' . $this->owner->tie->config['db']['prefix'] . 'posts` AS p
				LEFT JOIN `' . $this->owner->tie->config['db']['prefix'] . 'users` AS u ON (p.poster = u.id)
				WHERE p.id = ?;');
				$row = $this->adodb->GetRow($query, array($_GET['post']));
				if ($row)
				{
					$username = ($row['u_username']) ?
						$row['u_username'] :
						array('username' => $this->owner->vars['language']['na']);
					$quote = $this->owner->vars['language']['quote'];
					$this->owner->vars['time'] = date('m/d/y h:i A', $row['p_time']);
					$this->owner->vars['username'] = $username;
					$quotenodes = $nodes;
					$quotenodes[] = $this->owner->parseConditional('if user', ($row['u_username']), 'else user');
					$quote = $this->owner->parse($quotenodes, $quote);
					$content = '[quote=' . $quote . ']' . $row['p_content'] . '[/quote]' . "\r\n" . "\r\n";
				}
			}
			else
				$content = '';
			if ($post['title'])
			{
				if ($name == 'edit')
					$title = $post['title'];
				elseif ($name == 'newreply')
					$title = $this->owner->vars['language']['re'] . $post['title'];
				else
					$title = '';
			}
		}
		$smilies = array();
		$query = 'SELECT `title`, `code` FROM `' . $this->owner->tie->config['db']['prefix'] . 'smilies`;';
		$result = $this->adodb->getAll($query);
		foreach ($result as $row)
			$smilies[] = array
			(
				array
				(
					array('[code]', addslashes($row['code'])),
					array('[smileytitle]', $row['title'])
				),
				array()
			);
		$tags = array();
		$popups = array();
		$dropdowns = array();
		$query = 'SELECT `id`, `tag`, `type`, `style`, `label`, `message1`, `default1`, `message2`, `default2`, `loop`, `separator` FROM `' . $this->owner->tie->config['db']['prefix'] . 'bbcode`;';
		$result = $this->adodb->getAll($query);
		foreach ($result as $row)
			switch ($row['type'])
			{
				case 1:
					$tags[] = array
					(
						array
						(
							array('[id]', addslashes($row['id'])),
							array('[label]', htmlentities($row['label'])),
							array('[style]', htmlentities($row['style'])),
							array('[tag]', addslashes(htmlentities($row['tag'])))
						),
						array()
					);
					break;
				case 2:
					$popups[] = array
					(
						array
						(
							array('[default1]', addslashes(htmlentities($row['default1']))),
							array('[default2]', addslashes(htmlentities($row['default2']))),
							array('[label]', htmlentities($row['label'])),
							array('[message1]', addslashes(htmlentities($row['message1']))),
							array('[message2]', addslashes(htmlentities($row['message2']))),
							array('[separator]', addslashes(htmlentities($row['separator']))),
							array('[style]', htmlentities($row['style'])),
							array('[tag]', addslashes(htmlentities($row['tag'])))
						),
						array
						(
							$this->owner->parseConditional('if loop', $row['loop'], 'else loop')
						)
					);
					break;
				case 3:
					$options = array();
					$query = $this->adodb->Prepare('SELECT `label`, `equal`, `style` FROM `' . $this->owner->tie->config['db']['prefix'] . 'dropdown` WHERE parent = ?;');
					$result2 = $this->adodb->getAll($query, array($row['id']));
					foreach ($result2 as $row2)
						$options[] = array
						(
							array
							(
								array('[equal]', addslashes(htmlentities($row2['equal']))),
								array('[optionlabel]', htmlentities($row2['label'])),
								array('[optionstyle]', htmlentities($row2['style']))
							),
							array()
						);
					$dropdowns[] = array
					(
						array
						(
							array('[id]', addslashes(htmlentities($row['id']))),
							array('[label]', htmlentities($row['label'])),
							array('[style]', htmlentities($row['style'])),
							array('[tag]', addslashes(htmlentities($row['tag'])))
						),
						array
						(
							$this->owner->parseLoop('loop options', $options)
						)
					);
					break;
			}
		$postnodes = $nodes;
		$postnodes[] = $this->owner->parseLoop('loop posts', $preview);
		$nodes[] = $this->owner->parseConditional('if preview', $preview);
		$nodes[] = $this->owner->parseConditional('if formsmilies', !((isset($_POST['id']) && (!isset($_POST['smilies']))) || (!isset($_POST['id']) && isset($post['smilies']) && (!$post['smilies']))));
		$nodes[] = $this->owner->parseConditional('if formsignature', !((isset($_POST['id']) && (!isset($_POST['signature']))) || (!isset($_POST['id']) && isset($post['signature']) && (!$post['signature']))));
		$nodes[] = $this->owner->parseLoop('loop smilies', $smilies);
		$nodes[] = $this->owner->parseLoop('loop tags', $tags);
		$nodes[] = $this->owner->parseLoop('loop popups', $popups);
		$nodes[] = $this->owner->parseLoop('loop dropdowns', $dropdowns);
		$this->owner->vars['contentbox'] = htmlentities($content);
		$this->owner->vars['name'] = $name;
		$this->owner->vars['title'] = htmlentities($title);
		$this->owner->vars['posts'] = $this->owner->parse($postnodes, $this->owner->getTemplate('cape/post'));
		return $this->owner->parse($nodes, $community);
	}

	function createPost($row)
	{
		$content = nl2br(htmlspecialchars($row['p_content']));
		$content = $this->parse($content, isset($row['p_smilies']));
		$signature = nl2br(htmlspecialchars($row['u_signature']));
		$signature = $this->parse($signature, true);
		$nodes = $this->owner->config['parse']['nodes'];
		if (isset($row['p_modified_time']))
		{
			$this->owner->vars['id'] = $row['m_id'];
			$this->owner->vars['name'] = nl2br(htmlspecialchars($row['m_username']));
			$this->owner->vars['time'] = date('m/d/y h:i A', $row['p_modified_time']);
			$nodes[] = $this->owner->parseConditional('if user', (isset($row['m_id'])), 'else user');
			$edited = $this->owner->parse($nodes, $this->owner->vars['language']['edited']);
		}
		$this->owner->vars['time'] = date('m/d/y h:i A', $row['p_time']);
		$time = $this->owner->parse($nodes, $this->owner->vars['language']['postedon']);
		$this->owner->vars['avatar'] = $row['u_avatar'];
		$this->owner->vars['signature'] = $signature;
		return array
		(
			array('[avatar]', htmlspecialchars($row['u_avatar'])),
			array('[content]', $content),
			array('[edited]', $edited),
			array('[grouptitle]', htmlspecialchars($row['g_title'])),
			array('[postid]', $row['p_id']),
			array('[postrewrite]', htmlspecialchars($this->owner->replace($this->owner->vars['illegal'], $row['p_title']))),
			array('[posts]', $row['u_posts']),
			array('[signature]', $signature),
			array('[time]', $time),
			array('[title]', htmlspecialchars($row['p_title'])),
			array('[userid]', htmlspecialchars($row['u_id'])),
			array('[username]', htmlspecialchars($row['u_username'])),
			array('[userrewrite]', htmlspecialchars($this->owner->replace($this->owner->vars['illegal'], $row['u_username']))),
			array('[usertitle]', htmlspecialchars($row['u_title']))
		);
	}

	function parse($content, $usesmilies)
	{
		if ($usesmilies)
		{
			$smilies = array();
			$query = 'SELECT `title`, `code` FROM `' . $this->owner->tie->config['db']['prefix'] . 'smilies`;';
			$result = $this->adodb->getAll($query);
			if ($result)
			{
				$array = array();
				foreach ($result as $row)
					$array[] = array(htmlspecialchars($row['code']), '[img]' . $this->config['smileypath'] . '/' . $row['title'] . '.gif[/img]');
				$content = $this->owner->replace($array, $content);
			}
		}
		$nodes = array();
		$query = 'SELECT `id`, `tag`, `replacement`, `equal` FROM `' . $this->owner->tie->config['db']['prefix'] . 'bbcode`;';
		$result = $this->adodb->getAll($query);
		foreach ($result as $row)
			$nodes[] = array
			(
				'[' . $row['tag'] . (($row['equal']) ? '=' : ']'),
				'[/' . $row['tag'] . ']',
				'if (' . var_export($row['equal'], true) . ')
				{
					$equal = explode(\']\', $case, 2);
					$main = $equal[1];
					$equal = $equal[0];
				}
				else
					$main = $case;
				$id = ' . var_export($row['id'], true) . ';
				$replacement = ' . var_export($row['replacement'], true) . ';
				include $this->config[\'templates\'][\'code\'] . \'/cape/parse.inc.php\';
				$array = array
				(
					array(\'(equal)\', $equal),
					array(\'(main)\', $main)
				);
				return $this->replace($array, $replacement);'
			);
		$content = $this->owner->parse($nodes, $content, array(), '');
		return $content;
	}

	/**
	Perform a validation for the provided email address
	**@param string Email to verify
	**@returns boolean true if succesful, false if failed.
	**/
	public function validateEmail($email)
	{
		//The result will start off as valid, and then we'll go down validating.
		$return = true;
		//Start looking for the @ in the email, for starters.
		$index = strrpos($email, '@');
		//Check for the @. If there is none, there is no doubt this e-mail is invalidly formatted.
		if (is_bool($index) && !$index)
			$return = false;
		else
		{
			$domain = substr($email, $index + 1); //Grab the domain. It comes after the @
			$local = substr($email, 0, $index); //Grab the local part; which comes before the @
			$localLen = strlen($local); //Length of local part.
			$domainLen = strlen($domain); //Length of domain
			//Local length must at least be 1 characters long, and must not exceed 64 characters. If this condition is met, the local part must
			if ($localLen < 1 || $localLen > 64)
				$return = false;
			//A domain must at least be 1 characters long, and must not exceed 255 characters. If this condition is met, the domain name is not valid.
			elseif ($domainLen < 1 || $domainLen > 255)
				$return = false;
			//The local part must not start or end with a dot (.) character.
			elseif ($local[0] == '.' || $local[$localLen - 1] == '.')
				$return = false;
			//It must also not have two consecutive dots.
			elseif (preg_match('/\\.\\./', $local))
				$return = false;
			//We cannot allow any invalid characters in the domain name.
			elseif (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
				$return = false;
			//It must also not have two consecutive dots.
			elseif (preg_match('/\\.\\./', $domain))
				$return = false;
			elseif (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace('\\\\', '', $local)))
				//Not valid unless local part is quoted.
				if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace('\\\\', '', $local)))
					$return = false;
			//Find the domain in DNS. We'll check for the MX and A records, as they're important in validating the domain.
			if ($return && !(checkdnsrr($domain, 'MX')) || !(checkdnsrr($domain, 'A')))
				$return = false;
	   }
	   //Return the final result.
	   return $return;
	}

    public function validateURL($url)
    {
        return (eregi('^((ht|f)tp(s?))\://([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(/\S*)?$', $url));
    }
}
$suit->community = new COMMUNITY($suit);
?>
