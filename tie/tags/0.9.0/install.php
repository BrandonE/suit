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
$output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<title>SUIT Install - <name></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
<style type="text/css">
body
{
	background: url(images/body-background.gif) repeat;
	font-family: Verdana, Sans-Serif;
	font-size: 15px;
	color: #000;
}

a
{
	color: #333;
	text-decoration: underline;
}

img
{
	border: 0;
}

#suit_wrapper
{
	width: 900px;
	margin: auto;
}

#suit_logo
{
	background: url(images/logo-background.gif) no-repeat;
	width: 501px;
	height: 120px;
}

#suit_logo a
{
	height: 100%;
	overflow: hidden;
	display: block;
	text-indent: -999em;
}


#suit_banner, #suit_content, #suit_panel, #suit_copyright
{
	background: #FFF;
}

#suit_banner-top
{
	background: url(images/banner-top-background.gif) no-repeat;
	width: 900px;
	height: 29px;
	margin-bottom: 0;
	margin-top: 0;
	border-bottom: none;
}

#suit_banner
{
	margin-top: 0;
	padding: 1em;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-top: none;
}

#suit_banner h1
{
	text-indent: -999em;
}

#suit_topbar
{
	background: #333;
	margin: 0;
	padding: 5px;
	text-align: center;
	font-variant: small-caps;
	border-top: 0px;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-bottom: none;
}

#suit_topbar a:hover
{
	background: #EFEFEF;
	color: #333;
}

#suit_panel
{
	margin: 0;
	padding: 5px;
	text-align: center;
	border-top: 1px solid #000;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-bottom: none;
}

#suit_topbar a
{
	text-decoration: none;
	padding: 5px;
	color: #FFF;
}

#suit_content
{
	border: 1px solid #000;
	padding: 1em;
}

#suit_content
{
	padding-top: 20px;
}

/* Success and Error */
#suit_success
{
	background: #A9D898 url(images/success-background.gif) no-repeat;
	border: 1px solid #34AC0A;
	font-size: 96%;
	color: #185005;
	padding: 5px;
}

#suit_success p
{
	padding: 1.5em;
	margin-left: 45px;
}

/* Templates */

ul#suit_templateslist
{
	list-style: none;
	border-top: 1px solid #333;
	margin: 0;
	padding: 0;
}

ul#suit_templateslist li
{
	background: #EFEFEF;
	padding: 5px;
	border-bottom: 1px solid #333;
	border-right: 1px solid #333;
	border-left: 1px solid #333;
}

ul#suit_templateslist a
{
	text-align: right;
}

/* Error Logging */
fieldset
{
	border: 1px solid #333;
}

fieldset.errorlog-entry
{
	background: #EFEFEF url(images/errorlog-quote.gif) no-repeat left;
}

fieldset.errorlog-entry p
{
	padding: 1em;
	margin-left: 41px;
}

div.errorlog-links
{
	text-align: center;
}

div.errorlog-links a
{
	padding: 2px 5px 2px 5px;
	margin: 2px;
	border: 1px solid #000;
	text-decoration: none;
	color: #EFEFEF;
	background: #333;
}

div.errorlog-links a:hover
{
	background: #EFEFEF;
	color: #333;
}

#suit_copyright
{
	text-align: center;
	padding: 3px;
	border-top: none;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-bottom: 1px solid #000;
	margin-bottom: 5px;
}

#suit_themeswitcher
{
	text-align: center;
	margin-bottom: 20px;
}
</style>
</head>
<body>
	<div id="suit_wrapper">
		<div id="suit_banner-top"></div>
		<div id="suit_banner">
			<div id="suit_logo"><a href="install.php">SUIT</a></div>
		</div>
		<div id="suit_topbar">
			<a href="install.php">Install</a><a href="http://www.suitframework.com/">SUIT</a>
		</div>
		<div id="suit_panel">
			<a>Introduction</a>
			<a>Requirements</a>
			<a>Package</a>
			<a>Configuration</a>
			<a>Confirm</a>
			<a>Install</a>
		</div>
		<div id="suit_content">
			<content><form>
		</div>
		
		<div id="suit_copyright">
                        <p>Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a></p>

			<p>&copy; Copyright 2008 <a href="http://wiki.suitframework.com/index.php/The_SUIT_Group">The SUIT Group</a>. All Rights Reserved.</p>
		</div>
</div>

</body>
</html>';
error_reporting(E_ALL);
if (file_exists('config.php') && (file_get_contents('config.php') != ''))
{
	echo 'The installer is currently locked. In order to access it, you must remove config.php from your server.';
	exit;
}
/**
Implodes values by concatenating from an array.
@param array Values

@returns string Imploded string
**/
function replace($string, $array)
{
	$pos = array();
	$add = 0;
	foreach ($array as $key => $value)
	{
		if ($string != str_replace($value[0], $value[1], $string))
		{
			if(stripos($string, $value[0], 0) == 0)
			{
				$pos[0] = $key;
				$position = 0;
			}
			else
			{
				$position = -1;
			}
			while($position = stripos($string, $value[0], $position+1)) 
			{
				$pos[$position] = $key;
			}
		}
	}
	ksort($pos);
	foreach ($pos as $key => $value)
	{
		$length = strlen($array[$value][0]);
		$string = substr_replace($string, $array[$value][1], $key+$add, $length);
		$add += strlen($array[$value][1]) - strlen($array[$value][0]);
	}
	
	return $string;
}
/**
Undoes what magic_quotes does
**@param string The string to magically strip of slashes
**@returns string PHP
**/
function magic($string)
{
	$return = $string;
	//Detect magic_quotes_gpc
	if (get_magic_quotes_gpc())
	{
		//Detect magic_quotes_sybase
		if (ini_get('magic_quotes_sybase') == 'On')
		{
			//Yes, so convert
			$return = str_replace('\'\'', '\'', $var);
			$return = str_replace('""', '"', $var);
		}
		else
		{
			//No, so we'll run stripslashes() now.
			$return = stripslashes($return);
		}
	}
	//Return the value.
	return $return;
}
$form = '<form action="install.php?step=<step>" method="post"><postdata><formcontent>
			<p><input value="<button>" type="submit" name="proceed" /></p>
			</form>';
//Current Step.
if (isset($_GET['step']))
{
	$step = intval($_GET['step']);
}
else
{
	$step = '0';
}
$last = 5;
$postcheck = Array
(
	array('proceed', '1'),
	array('requirements', '2'),
	array('package', '3'),
	array('db_host', '4'),
	array('db_name', '4'),
	array('db_user', '4'),
	array('db_pass', '4'),
	array('db_port', '4'),
	array('db_prefix', '4'),
	array('flag_debug', '4'),
	array('path_home', '4'),
	array('path_templates', '4'),
	array('path_url', '4'),
	array('cookie_prefix', '4'),
	array('cookie_path', '4'),
	array('cookie_domain', '4'),
	array('cookie_length', '4'),
	array('user_name', '4'),
	array('user_pass', '4'),
	array('user_email', '4'),
	array('install', '5')
);
$suitform = Array
(
	array('db_type', 'Database Type', ''),
	array('db_host', 'Database Host', 'localhost'),
	array('db_name', 'Database Name', ''),
	array('db_user', 'Database User', 'root'),
	array('db_pass', 'Database Pass', ''),
	array('db_port', 'Database Port', ''),
	array('db_prefix', 'Database Prefix', 'suit_'),
	array('flag_debug', 'Flag Debug', ''),
	array('path_home', 'Path Home', ''),
	array('path_templates', 'Path Templates', ''),
	array('path_url', 'Path URL', ''),
	array('cookie_prefix', 'Cookie Prefix', ''),
	array('cookie_path', 'Cookie Path', ''),
	array('cookie_domain', 'Cookie Domain', ''),
	array('cookie_length', 'Cookie Length', ''),
	array('user_name', 'Admin Username', ''),
	array('user_pass', 'Admin Password', ''),
	array('user_email', 'Admin E-Mail', '')
);
$tieform = Array
(
	'path_url',
	'cookie_prefix',
	'cookie_path',
	'cookie_domain',
	'cookie_length',
	'user_name',
	'user_pass',
	'user_email'
);
foreach ($postcheck as $value)
{
	if (!isset($_POST[$value[0]]) && $step >= $value[1])
	{
		if (!(in_array($value[0], $tieform) && isset($_POST['package']) && (magic($_POST['package'] == 'SUIT'))))
		$fail = true;
		$pagename = 'Error';
		$content = '	Error: Missing Postdata';
	}
}
if (!isset($fail))
{
	switch ($step)
	{
		case '0':
			$pagename = 'Introduction';
			$content = 'Welcome to the installation!';
			$formcontent = '';
			break;
		case '1':
			$pagename = 'Requirements';
			$content = 'Requirements
			<table border="1">
			<tr>
			<td>Requirement</td>
			<td>Pass</td>
			</tr>
			<php5>
			<registerglobals>
			<config>
			<magicquotes>
			</table>';
			$requirements = Array
			(
				array('php5', 'PHP 5 or Higher', (phpversion() > '4.4.9'), 1),
				array('registerglobals', 'Register Globals Disabled', (!ini_get('register_globals')), 1),
				array('config', 'config.php CHMOD 666', (substr(sprintf('%o', fileperms('config.php')), -4) == '0666'), 1),
				array('magicquotes', 'Magic Quotes Disabled', (get_magic_quotes_gpc() == 0), 0)
			);
			foreach ($requirements as $value)
			{
				$requirement = '<tr>
			<td><label></td>
			<td><message></td>
			</tr>';
				if ($value[2])
				{
					if ($value[3])
					{
						$message = 'YES - PASS';
					}
					else
					{
						$message = 'YES - RECOMMENDED';
					}
				}
				else
				{
					if ($value[3])
					{
						$message = 'NO - FAIL';
						$fail = true;
					}
					else
					{
						$message = 'NO - NOT RECOMMENDED';
					}
				}
				$array = Array
				(
					array('<label>', $value[1]),
					array('<message>', $message)
				);
				$requirement = replace($requirement, $array);
				$content = str_replace('<' . $value[0] . '>', $requirement, $content);	
			}
			$formcontent = '
			<input type=\'hidden\' name=\'requirements\' value=\'true\' />';
			break;
		case '2':
			$pagename = 'Package';
			$content = 'Choose your package:';
			$formcontent = '
			<input type="radio" name="package" value="SUITTIE" checked /> SUIT + TIE (Recommended)
			<br /><input type="radio" name="package" value="SUIT" /> SUIT';
			break;
		case '3':
			$pagename = 'Configuration';
			$content = 'Type your Information';
			$formcontent = '
			<table border="1">
			<tr>
			<td>Name</td>
			<td>Value</td>
			</tr>
			<tr>
			<td>Database Type</td>
			<td>
			<select name="db_type">
			<option value="mysql">MySQL</option>
			</select>
			</td>
			</tr>
			<db_host>
			<db_name>
			<db_user>
			<tr>
			<td>Database Password</td>
			<td>
			<input type="password" name="db_pass" />
			</td>
			</tr>
			<db_port>
			<db_prefix>
			<tr>
			<td>Flag Debug</td>
			<td>
			<select name="flag_debug">
			<option value="true">True</option>
			<option value="false">False</option>
			</select>
			</td>
			</tr>
			<path_home>
			<path_templates>
			<path_url>
			<cookie_prefix>
			<cookie_path>
			<cookie_domain>
			<cookie_length>
			<user_name>
			<tr>
			<td>Admin Password</td>
			<td>
			<input type="password" name="user_pass" />
			</td>
			</tr>
			<user_email>
			</table>';
			$array = Array();
			foreach ($suitform as $value)
			{
				if (!(magic($_POST['package']) == 'SUIT' && in_array($value[0], $tieform)))
				{
					$element = '<tr>
				<td><label></td>
				<td>
				<input type="text" name="<element>" value="<elementvalue>" />
				</td>
				</tr>';
					if (isset($_POST[$value[0]]))
					{
						$elementvalue = magic($_POST[$value[0]]);
					}
					else
					{
						$elementvalue = $value[2];
					}
					$array2 = Array
					(
						array('<element>', $value[0]),
						array('<label>', $value[1]),
						array('<elementvalue>', $elementvalue)
					);
					$element = replace($element, $array2);
				}
				else
				{
					$element = '';
				}
				$array[] = array('<' . $value[0] . '>', $element);
			}
			$formcontent = replace($formcontent, $array);
			$array = Array();
			if (isset($_POST['db_type']))
			{
				$array[] = array('<option value="' . magic($_POST['db_type']) . '">', '<option value="' . magic($_POST['db_type']) . '" selected>');
			}
			if (isset($_POST['flag_debug']))
			{
				$array[] = array('<option value="' . magic($_POST['flag_debug']) . '">', '<option value="' . magic($_POST['flag_debug']) . '" selected>');
			}
			$formcontent = replace($formcontent, $array);
			break;
		case '4':

			$errors = array();
			if (!(is_dir(magic($_POST['path_home'])) && (file_exists(magic($_POST['path_home']) . '/' . 'core.class.php') && file_exists(magic($_POST['path_home']) . '/' . 'templates.class.php') && file_exists(magic($_POST['path_home']) . '/' . 'db.class.php'))))
			{
				$errors[] = 'SUIT Folder does not exist or isn\'t populated';
			}
			if (!(is_dir(magic($_POST['path_templates'])) && (substr(sprintf('%o', fileperms(magic($_POST['path_templates']))), -4) == '0777')))
			{
				$errors[] = 'Templates Folder does not exist or not CHMOD 777';
			}
			if (magic($_POST['db_type'] == 'mysql'))
			{
				//Create a variable, for future use.
				$conn = @mysql_connect(magic($_POST['db_host']), magic($_POST['db_user']), magic($_POST['db_pass']), magic($_POST['db_port']));
				//Connection has succeeded.
				if ($conn)
				{
					//Select the MySQL Database, and supply the link as a second argument.
					$connected = true;
					mysql_select_db(magic($_POST['db_name']), $conn) or ($connected = false);
					if (!$connected)
					{
						$errors[] = 'Database could not be Selected';
					}
					mysql_close($conn);
				}
			}
			if (!(isset($connected) && ($connected)))
			{
				$errors[] = 'No Connection Made';
			}
			if (magic($_POST['package']) == 'SUITTIE')
			{
				//The username must be at least 7 characters, and it must not exceed 50 characters.
				if (!((strlen($_POST['username']) >= 7) && (strlen($_POST['username']) <= 50)))
				{
					$message .= $tie->getPhrase('usernamenotvalid');
				}
				//The password must be at least 7 characters long, and it must not exceed 32 characters.
				if (!((strlen($_POST['password']) > 7) && (strlen($_POST['password']) < 32)))
				{
					$message .= $tie->getPhrase('passwordnotvalid');
				}
			}
			if (!empty($errors))
			{
				$content = '';
				$pagename = 'Error';
				foreach ($errors as $value)
				{
					$content .= '<br />Error: ' . $value;
				}
				$formcontent = '';
				$error = true;
			}
			else
			{
				$content = 'Config Passed! Is this Correct?
			<br />
			<table border="1">
			<tr>
			<td>Name</td>
			<td>Value</td>
			</tr>
			<db_type>
			<db_host>
			<db_name>
			<db_user>
			<db_pass>
			<db_port>
			<db_prefix>
			<flag_debug>
			<path_home>
			<path_templates>
			<path_url>
			<cookie_prefix>
			<cookie_path>
			<cookie_domain>
			<cookie_length>
			<user_name>
			<user_pass>
			<user_email>
			</table>';
				$pagename = 'Confirm';
				$array = Array();
				foreach ($suitform as $value)
				{
					if (!(magic($_POST['package']) == 'SUIT' && in_array($value[0], $tieform)))
					{
						$element = '<tr>
			<td><label></td>
			<td>
			<element>
			</td>
			</tr>';
						if ($value[0] != 'db_pass' && $value[0] != 'user_pass')
						{
							$elementvalue = magic($_POST[$value[0]]);
						}
						else
						{
							$elementvalue = '';
							for ($x=0; $x != strlen(magic($_POST[$value[0]])); $x++)
							{
								$elementvalue .= '*';
							}
						}
						$array2 = Array
						(
							array('<element>', htmlentities($elementvalue)),
							array('<label>', $value[1])
						);
						$element = replace($element, $array2);
					}
					else
					{
						$element = '';
					}
					$array[] = array('<' . $value[0] . '>', $element);
				}
				$content = replace($content, $array);
			}
			$formcontent = '
			<input type=\'hidden\' name=\'install\' value=\'true\' />';
			break;
		case '5':
			if (substr(sprintf('%o', fileperms('config.php')), -4) == '0666')
			{
				if (is_dir(magic($_POST['path_home'])) && (file_exists(magic($_POST['path_home']) . '/' . 'core.class.php') && file_exists(magic($_POST['path_home']) . '/' . 'templates.class.php') && file_exists(magic($_POST['path_home']) . '/' . 'db.class.php')))
				{
					if (is_dir(magic($_POST['path_templates'])) && (substr(sprintf('%o', fileperms(magic($_POST['path_templates']))), -4) == '0777'))
					{
						if (magic($_POST['package']) == 'SUITTIE')
						{
							$salt = addslashes(substr(md5(md5('1skafd;p32q0' . uniqid(md5(rand()), true))), 0, 5));
						}
						if (magic($_POST['db_type']) == 'mysql')
						{
							//Create a variable, for future use.
							$conn = @mysql_connect(magic($_POST['db_host']), magic($_POST['db_user']), magic($_POST['db_pass']), magic($_POST['db_port']));
							//Connection has succeeded.
							if ($conn)
							{
								//Select the MySQL Database, and supply the link as a second argument.
								mysql_select_db(magic($_POST['db_name']), $conn) or die('Database could not be Selected');
								$connected = true;
$query = 'CREATE TABLE IF NOT EXISTS `' . magic($_POST['db_prefix']) . 'errorlog`
(
	`id` bigint(20) NOT NULL auto_increment,
	`content` text NOT NULL,
	`date` text NOT NULL,
	`location` text NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1tHiSiSaDeLiMeTeR
CREATE TABLE IF NOT EXISTS `' . magic($_POST['db_prefix']) . 'pages`
(
	`id` bigint(20) NOT NULL auto_increment,
	`title` text NOT NULL,
	`template` text NOT NULL,
	`defaults` tinyint(4) NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1tHiSiSaDeLiMeTeR
CREATE TABLE IF NOT EXISTS `' . magic($_POST['db_prefix']) . 'templates`
(
	`id` bigint(20) NOT NULL auto_increment,
	`title` text NOT NULL,
	`content` text NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1tHiSiSaDeLiMeTeR';
								if (magic($_POST['package']) == 'SUITTIE')
								{
require 'queries.php';
								}
								$queries = explode('tHiSiSaDeLiMeTeR', $query);
								foreach ($queries as $value)
								{
									mysql_query($value);
								}
								mysql_close($conn);
							}
						}
						if ($connected)
						{
							if (magic($_POST['package']) == 'SUITTIE')
							{
								$files = Array
								(
								);
require 'files.php';
								foreach ($files as $value)
								{
									touch(magic($_POST['path_templates']) . '/' . $value[0]);
									file_put_contents(magic($_POST['path_templates']) . '/' . $value[0], $value[1]);
									chmod(magic($_POST['path_templates']) . '/' . $value[0], 0666);
								}
							}
file_put_contents('config.php', '<?php
//DB Settings.
define(\'DB_TYPE\', \'' . magic($_POST['db_type']) . '\');
define(\'DB_HOST\', \'' . magic($_POST['db_host']) . '\');
define(\'DB_NAME\', \'' . magic($_POST['db_name']) . '\');
define(\'DB_USER\', \'' . magic($_POST['db_user']) . '\');
define(\'DB_PASS\', \'' . magic($_POST['db_pass']) . '\');
define(\'DB_PORT\', ' . magic($_POST['db_port']) . ');
define(\'DB_PREFIX\', \'' . magic($_POST['db_prefix']) . '\');
//Flags
define(\'FLAG_DEBUG\', ' . magic($_POST['flag_debug']) . ');
//Paths
define(\'PATH_HOME\', \'' . magic($_POST['path_home']) . '\');
define(\'PATH_TEMPLATES\', \'' . magic($_POST['path_templates']) . '\');
?>');
						}
						else
						{
							die('Database could not be Selected');
						}
					}
					else
					{
						die('Templates Folder does not exist or not CHMOD 777');
					}
				}
				else
				{
					die('SUIT Folder does not exist or isn\'t populated');
				}
			}
			else
			{
				die('config.php does not exist or not CHMOD 666');
			}
			$pagename = 'Install';
			$content = 'Success!';
			$formcontent = '';
			break;
		default:
			$pagename = 'Error';
			$content = 'Error: Step Not Found';
			$formcontent = '';
			$error = true;
			break;
	}
}
if (!(file_exists('config.php') && (file_get_contents('config.php') != '')) && !isset($fail))
{
	if (!isset($error))
	{
		$stepform = $step+1;
		if ($step == $last-1)
		{
			$button = 'Install';
		}
		else
		{
			$button = 'Next Step';
		}
	}
	else
	{
		$stepform = $step-1;
		$button = 'Back';
	}
	$postdata = '';
	foreach ($_POST as $name => $value)
	{
		$postdata .= '
			<input type="hidden" name="' . $name . '" value="' . magic($value) . '" />';
	}
	$array = Array
	(
		array('<step>', $stepform),
		array('<button>', $button),
		array('<postdata>', $postdata),
		array('<formcontent>', $formcontent)
	);
	$form = replace($form, $array);
}
else
{
	$form = '';
}
$array = Array
(
	array('<name>', $pagename),
	array('<a>' . $pagename, '<a style="font-weight:bold;">' . $pagename),
	array('<content>', $content),
	array('<form>', $form)
);
$output = replace($output, $array);
print $output;
?>
