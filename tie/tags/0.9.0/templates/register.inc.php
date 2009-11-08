<?php
/**
**@This file is part of The SUIT Framework.

**@SUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.

**@SUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.
**/
$suit->templates->getTemplate('tie');
$tie = &$suit->templates->vars['tie'];
$output = $tie->parsePhrases($output);
$suit->templates->getTemplate('recaptcha_lib');
$suit->templates->getTemplate('recaptcha_keys');
$privatekey = &$suit->templates->vars['privatekey'];
if ($tie->loggedIn() == 0)
{
	$message = '';
	if (isset($_POST['register']))
	{
		//Validate the email
		if (isset($_POST['email']) && $tie->validateEmail($_POST['email']))
		{
			$email = $tie->magic($_POST['email']);
			//Check if the username is taken.
			$userexists_options = array
			(
				'where' => 'email = \'' . $email . '\''
			);
			$userexists = $suit->db->select(DB_PREFIX . 'users', 'email', $userexists_options);
			if ($userexists)
			{
				$message .= $tie->getPhrase('emailexists');
			}
		}
		else
		{
			//Email Error
			$message .= $tie->getPhrase('emailnotvalid');
		}
		//The username must be at least 7 characters, and it must not exceed 50 characters.
		if ((strlen($_POST['username']) >= 7) && (strlen($_POST['username']) <= 50))
		{
			$username = $suit->db->escape($tie->magic($_POST['username']));
			//Check if the username is taken.
			$userexists_options = array
			(
				'where' => 'username = \'' . $username . '\''
			);
			$userexists = $suit->db->select(DB_PREFIX . 'users', 'username', $userexists_options);
			if ($userexists)
			{
				$message .= $tie->getPhrase('usernametaken');
			}
		}
		else
		{
			//Username error
			$message .= $tie->getPhrase('usernamenotvalid');
		}
		//The password must be at least 7 characters long, and it must not exceed 32 characters.
		if ((strlen($_POST['password']) > 7) && (strlen($_POST['password']) < 32))
		{
			$password = md5($tie->magic($_POST['password']) . DB_SALT);
		}
		else
		{
			//Password error.
			$message .= $tie->getPhrase('passwordnotvalid');
		}
		$language = $suit->db->escape($tie->magic($_POST['language']));
		$options = array
		(
			'where' => 'id = \'' . $language . '\''
		);
		$check = $suit->db->select(DB_PREFIX . 'languages', '*', $options);
		if (!($check || $language == 0))
		{
			$message .= $tie->getPhrase('languagenotvalid');
		}
		if (isset($_POST['recaptcha_challenge_field']) && isset($_POST['recaptcha_response_field']))
		{
			$resp = recaptcha_check_answer($privatekey, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
			if ($resp->is_valid)
			{
			}
			else
			{
				//The value provided for recaptcha is wrong.
				$message .= $tie->getPhrase('recaptchanotvalid');
			}
		}
		else
		{
			//The value provided for recaptcha is wrong.
			$message .= $tie->getPhrase('recaptchanotvalid');
		}
		
		if (!$message)
		{
			$sql = 'INSERT INTO ' . DB_PREFIX . 'users VALUES(\'\', \'0\', \'' . $username . '\', \'' . $password . '\', \'' . $email . '\', \'' . $language . '\', \'\', \'\')';
			$adduser = mysql_query($sql);
			if ($adduser)
			{
				$usercheck_options = array('where' => 'username = \'' . $username . '\' AND password = \'' . $password . '\'');
				$usercheck = $suit->db->select(DB_PREFIX . 'users', '*', $usercheck_options);
				if ($usercheck)
				{
					while ($row = mysql_fetch_assoc($usercheck))
					{
						//Log the user in now.
						setcookie(COOKIE_PREFIX . 'id', $row['id'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
						setcookie(COOKIE_PREFIX . 'pass', $row['password'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
						$tie->redirect($tie->getPhrase('registeredsuccessfully'), 2, $suit->templates->getTemplate('path_url') . '/index.php');
					}
				}
				//Redirect to the index page.
				$suit->templates->getTemplate('notauthorized');
			}
		}
	}
	$output = $tie->parseTemplates($output);
	$languages = $tie->languageForm($tie->language['realid']);
	$array = Array
	(
		array('<message>', $message),
		array('<languages>', $languages)
	);
	$output = $tie->replace($output, $array);
}
else
{
	$suit->templates->getTemplate('notauthorized');
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>