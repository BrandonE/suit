<?php/****@This file is part of The SUIT Framework.**@SUIT is free software: you can redistribute it and/or modify**@it under the terms of the GNU General Public License as published by**@the Free Software Foundation, either version 3 of the License, or**@(at your option) any later version.**@SUIT is distributed in the hope that it will be useful,**@but WITHOUT ANY WARRANTY; without even the implied warranty of**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the**@GNU General Public License for more details.**@You should have received a copy of the GNU General Public License**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.**/

if ($suit->loggedIn() == 0)
{
	$suit->templates->getTemplate('recaptcha_lib');
	$recaptcha_keys_vars = $suit->templates->getTemplate('recaptcha_keys');
	
	if (isset($_POST['register']))
	{
		$resp = recaptcha_check_answer($recaptcha_keys_vars['privatekey'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		if($resp->is_valid)
		{
			$username = $suit->db->escape($_POST['username']);
			$email = $suit->db->escape($_POST['email']);
			$getsalt = $suit->db->select(TBL_PREFIX . 'salt', '*', '');
			
			if ($getsalt)
			{
				while ($row = mysql_fetch_assoc($getsalt))
				{
					$salt = $row['content'];
				}
			}
		
			$password = md5($_POST['password'] . $salt);
			$registered = time();
			$ip = $suit->db->escape($_SERVER['REMOTE_ADDR']);
			
			$query = 'INSERT INTO ' . TBL_PREFIX . 'users (username, password, email, registered, registration_ip, last_visit) VALUES (\'' . $username . '\', \'' . $password . '\', \'' . $email . '\', \'' . $registered . '\', \'' . $ip .  '\', \'' . $registered . '\')';
			mysql_query($query);

			$usercheck_options = array('where' => 'username = \'' . $username . '\' AND password = \'' . $password . '\'');

			$usercheck = $suit->db->select(TBL_PREFIX . 'users', '*', $usercheck_options);
			
			if ($usercheck)
			{
				while ($row = mysql_fetch_assoc($usercheck))
				{
					setcookie(COOKIE_PREFIX . 'id', $row['id'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
					setcookie(COOKIE_PREFIX . 'pass', $row['password'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				}
			}

			header('refresh: 0; url=./index.php');
			exit;
		}
		else
		{
			header('refresh: 0; url=./register.php?error=recaptcha');
			exit;
		}
	}

	if (isset($_GET['error']) && ($_GET['error'] == 'recaptcha'))
	{
		if ($_GET['error'] == 'recaptcha')
		{
			$error = $suit->languages->getLanguage('recaptchaincorrect');
		}
		$output = str_replace('<1>', $error, $output);
	}
	else
	{
		$output = str_replace('<1>', '', $output);
	}
}
else
{
	header('refresh: 0; url=./index.php');
	exit;
}

?>
