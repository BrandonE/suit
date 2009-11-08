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
$suit->templates->getTemplate('init');
$output = $suit->templates->vars['local']->parsePhrases($output);
$suit->templates->getTemplate('recaptcha_lib');
$suit->templates->getTemplate('recaptcha_keys');
if ($suit->templates->vars['local']->loggedIn() == 0)
{
	if (isset($_POST['register']))
	{
		if (isset($_POST['recaptcha_challenge_field']) && isset($_POST['recaptcha_response_field']))
		{
			$resp = recaptcha_check_answer($suit->templates->vars['privatekey'], $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
			if($resp->is_valid)
			{
				//Create an empty error array
				$errors = array();
				
				/**
				**@param
				**@returns boolean True if succesful, false if failed.
				**/
				function validateEmail($email)
				{
					//The result will start off as valid, and then we'll go down validating.
					$return = true;
					//Start looking for the @ in the email, for starters.
					$index = strrpos($email, '@');
					//Check for the @. If there is none, there is no doubt this e-mail is invalidly formatted.
					if (is_bool($index) && !$index)
					{
						$return = false;
					}
					else
					{
						$domain = substr($email, $index + 1); //Grab the domain. It comes after the @
						$local = substr($email, 0, $index); //Grab the local part; which comes before the @
						$localLen = strlen($local); //Length of local part.
						$domainLen = strlen($domain); //Length of domain
						//Local length must at least be 1 characters long, and must not exceed 64 characters. If this condition is met, the local part must
						if ($localLen < 1 || $localLen > 64)
						{
							$return = false;
						}
						//A domain must at least be 1 characters long, and must not exceed 255 characters. If this condition is met, the domain name is not valid.
						else if ($domainLen < 1 || $domainLen > 255)
						{
							$return = false;
						}
						//The local part must not start or end with a dot (.) character.
						else if ($local[0] == '.' || $local[$localLen-1] == '.')
						{
							$return = false;
						}
						//It must also not have two consecutive dots.
						else if (preg_match('/\\.\\./', $local))
						{
							$return = false;
						}
						//We cannot allow any invalid characters in the domain name.
						else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
						{
							$return = false;
						}
						//It must also not have two consecutive dots.
						else if (preg_match('/\\.\\./', $domain))
						{
							$return = false;
						}
						else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace('\\\\', '', $local)))
						{
							//Not valid unless local part is quoted.
							if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace('\\\\', '', $local)))
							{
								$return = false;
							}
						}
						//Find the domain in DNS. We'll check for the MX and A records, as they're important in validating the domain.
						if ($return && !(checkdnsrr($domain, 'MX')) || !(checkdnsrr($domain, 'A')))
						{
							$return = false;
						}
				   }
				   //Return the final result.
				   return $return;
				}
				
				//reCAPTCHA
				if (!$resp->is_valid)
				{
					$errors[] = 'Recaptcha invalid';
				}
				
				//Validate the email
				if (isset($_POST['email']) && validateEmail($_POST['email']))
				{
					$email = $_POST['email'];
				}
				else
				{
					//Email Error
					$errors[] = 'Email error here.';
				}
				
				//The username must be at least 7 characters, and it must not exceed 50 characters.
				if ((strlen($_POST['username']) >= 7) && !(strlen($_POST['username']) > 50))
				{
					$username = $suit->db->escape($_POST['username']);
				}
				else
				{
					//Username error
					$errors[] = 'Username error here';
				}
				
				//The password must be at least 7 characters long, and it must not exceed 32 characters.
				if ((strlen($_POST['password']) > 7) && (strlen($_POST['password']) < 32))
				{
					$password = md5($_POST['password'] . SALT);
				}
				else
				{
					//Password error.
					$errors[] = 'Password error here. Current length is ' . strlen($_POST['password']);
				}
				
				if (empty($errors))
				{
					$sql = 'INSERT INTO ' . TBL_PREFIX . 'users VALUES(\'\', \'0\', \'' . $username . '\', \'' . $password . '\', \'' . $email . '\', \'0\', \'\', \'\')';
					$adduser = mysql_query($sql);
					if ($adduser)
					{
						$usercheck_options = array('where' => 'username = \'' . $username . '\' AND password = \'' . $password . '\'');
						$usercheck = $suit->db->select(TBL_PREFIX . 'users', '*', $usercheck_options);
						if ($usercheck)
						{
							while ($row = mysql_fetch_assoc($usercheck))
							{
								//Log the user in now.
								setcookie(COOKIE_PREFIX . 'id', $row['id'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
								setcookie(COOKIE_PREFIX . 'pass', $row['password'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
							}
						}
						//Redirect to the index page.
						$suit->templates->getTemplate('notauthorized');
					}
					else
					{
						//Replace this with an error about not being able to proceed with registration.
						echo 'registration error';
					}
				}
				else
				{
					foreach ($errors as $key => $value)
					{
						echo $errors[$key] . '<br />';
					}
				}
			}
		}
		//The value provided for recaptcha is wrong.
		header('refresh: 0; url=./register.php?error=recaptcha');
		exit;
	}
}
else
{
	$suit->templates->getTemplate('notauthorized');
}
?>