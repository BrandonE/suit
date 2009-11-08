<?php

if ($suit->loggedIn() == 0)
{
	if (isset($_POST['lostpassword']))
	{	
		$usercheck_options = array('where' => 'email = \'' . $suit->db->escape($_POST['email'], 0) . '\'');
		
		$usercheck = $suit->db->select(TBL_PREFIX . 'users', '*', $usercheck_options);
		
		if ($usercheck)
		{
			while ($row = mysql_fetch_assoc($usercheck))
			{
				$string = substr(md5(md5('1skafd;p32q0' . uniqid(md5(rand()), true))), 0, 5);
				$password = substr(md5(md5('1skafd;p32q0' . uniqid(md5(rand()), true))), 0, 5);
				
				$getsalt = $suit->db->select(TBL_PREFIX . 'salt', '*', '');
				if ($getsalt)
				{
					while ($row2 = mysql_fetch_assoc($getsalt))
					{
						$salt = $row2['content'];
					}
				}
			
				$passwordsalted = md5($password . $salt);

				$query = 'UPDATE ' . TBL_PREFIX . 'users SET recover_string = \'' . $string . '\', recover_password = \'' . $passwordsalted . '\' WHERE id = \'' . $row['id'] . '\'';
				mysql_query($query);

				$body = $suit->languages->getLanguage('lostpassword_body');
				$body = str_replace('<password>', $password, $body);
				$body = str_replace('<base_url>', BASE_URL, $body);
				$body = str_replace('<string>', $string, $body);
				$body = str_replace('<id>', $row['id'], $body);
				mail($row['email'], $suit->languages->getLanguage('lostpassword_subject'), $body, $suit->languages->getLanguage('emailheaders')) or die ($suit->languages->getLanguage('maildeliveryfailed'));
			}
			header('refresh: 0; url=?message=passwordsent');
			exit;
		}
		else
		{
			header('refresh: 0; url=?message=emailnotfound');
			exit;
		}
	}

	if (isset($_GET['id']) && isset($_GET['string']))
	{
		$usercheck_options = array('where' => 'id = \'' . $suit->db->escape($_GET['id'], 0) . '\' AND recover_string = \'' . $suit->db->escape($_GET['string'], 0) . '\'');
		
		$usercheck = $suit->db->select(TBL_PREFIX . 'users', '*', $usercheck_options);
		
		if ($usercheck)
		{
			while ($row = mysql_fetch_assoc($usercheck))
			{
				$query = 'UPDATE ' . TBL_PREFIX . 'users SET password = \'' . $row['recover_password'] . '\', recover_string = \'\', recover_password = \'\' WHERE id = \'' . $row['id'] . '\'';
				mysql_query($query);				
			}
			header('refresh: 0; url=?message=passwordchanged');
			exit;
		}
		else
		{
			header('refresh: 0; url=?message=passwordexpired');
			exit;
		}	
	}

	if (isset($_GET['message']))
	{
		//We'll use a switch() statement to determine what action to take for these errors.
		//When we have our error, we'll load the language string for it.
		switch ($_GET['message'])
		{
			case 'emailnotfound':
				$message = $suit->languages->getLanguage('emailnotfound'); break;
			case 'passwordsent':
				$message = $suit->languages->getLanguage('passwordsent'); break;
			case 'passwordchanged':
				$message = $suit->languages->getLanguage('passwordchanged'); break;
			case 'passwordexpired':
				$message = $suit->languages->getLanguage('passwordexpired'); break;
			default:
				$message = $suit->languages->getLanguage('undefinederror'); break;
		}
		//Replace the value of $list with what we concluded in the error switch() statement.
	}
	else
	{
		$message = '';
	}

	$output = str_replace('<message>', $message, $output);
}
else
{
	header('refresh: 0; url=./index.php');
	exit;
}

?>
