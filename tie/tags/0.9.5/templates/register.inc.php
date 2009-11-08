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
			$email = $_POST['email'];
			//Check if the username is taken.
			$query = 'SELECT email FROM ' . DB_PREFIX  . 'users WHERE email = \'' . $email . '\'';
			$check = $suit->db->query($query);
			if ($check && (mysql_num_rows($check)))
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
			$username = $suit->db->escape($_POST['username']);
			//Check if the username is taken.
			$query = 'SELECT * FROM ' . DB_PREFIX . 'users WHERE username = \'' . $username . '\'';
			$check = $this->suit->db->query($query);
			if ($check && (mysql_num_rows($check)))
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
			$password = md5($_POST['password'] . DB_SALT);
		}
		else
		{
			//Password error.
			$message .= $tie->getPhrase('passwordnotvalid');
		}
		$language = $suit->db->escape($_POST['language']);
		$query = 'SELECT * FROM ' . DB_PREFIX . 'languages WHERE id = \'' . $language . '\'';
		$check = $this->suit->db->query($query);
		if (!($check || $language == 0))
		{
			$message .= $tie->getPhrase('languagenotvalid');
		}
		if (isset($_POST['recaptcha_challenge_field']) && isset($_POST['recaptcha_response_field']))
		{
			$resp = recaptcha_check_answer($privatekey, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
			if (!$resp->is_valid)
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
			$query = 'INSERT INTO ' . DB_PREFIX . 'users (username, password, email, language) VALUES (\'' . $username . '\', \'' . $password . '\', \'' . $email . '\', \'' . $language . '\');';
			$suit->db->query($query);
			//Log the user in now.
			setcookie(COOKIE_PREFIX . 'username', $username, time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
			setcookie(COOKIE_PREFIX . 'password', $password, time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
			$tie->redirect($tie->getPhrase('registeredsuccessfully'), 2, $suit->templates->getTemplate('path_url') . '/index.php');
		}
	}
	$output = $tie->parseTemplates($output);
	$languages = $tie->languageForm($tie->language['realid'], 1);
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
