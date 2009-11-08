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
if (isset($query))
{
$query .= 'CREATE TABLE IF NOT EXISTS `' . addslashes(magic($_POST['db_prefix'])) . 'languages`
(
	`id` bigint(20) NOT NULL auto_increment,
	`title` text NOT NULL,
	`defaults` tinyint(4) NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1tHiSiSaDeLiMeTeR
CREATE TABLE IF NOT EXISTS `' . addslashes(magic($_POST['db_prefix'])) . 'notes`
(
	`content` text NOT NULL
)
ENGINE=MyISAM DEFAULT CHARSET=latin1tHiSiSaDeLiMeTeR
INSERT INTO `' . addslashes(magic($_POST['db_prefix'])) . 'notes` (`content`) VALUES
(\'Write some notes here!\')tHiSiSaDeLiMeTeR
CREATE TABLE IF NOT EXISTS `' . addslashes(magic($_POST['db_prefix'])) . 'phrases`
(
	`id` bigint(20) NOT NULL auto_increment,
	`title` text NOT NULL,
	`content` text NOT NULL,
	`language` bigint(20) NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1tHiSiSaDeLiMeTeR
CREATE TABLE IF NOT EXISTS `' . addslashes(magic($_POST['db_prefix'])) . 'users`
(
	`id` bigint(20) NOT NULL auto_increment,
	`admin` tinyint(4) NOT NULL,
	`username` text NOT NULL,
	`password` text NOT NULL,
	`email` text NOT NULL,
	`language` bigint(20) NOT NULL,
	`recover_string` text NOT NULL,
	`recover_password` text NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1tHiSiSaDeLiMeTeR
INSERT INTO `' . addslashes(magic($_POST['db_prefix'])) . 'users` (`id`, `admin`, `username`, `password`, `email`, `language`, `recover_string`, `recover_password`) VALUES
(\'\', 1, \'' . addslashes(magic($_POST['user_name'])) . '\', \'' . md5(magic($_POST['user_pass']) . $salt) . '\', \'' . addslashes(magic($_POST['user_email'])) . '\', 0, \'\', \'\')tHiSiSaDeLiMeTeR
INSERT INTO `' . addslashes(magic($_POST['db_prefix'])) . 'phrases` (`id`, `title`, `content`, `language`) VALUES
(\'\', \'add\', \'Add\', \'1\'),
(\'\', \'addedsuccessfully\', \'Added Successfully!\', \'1\'),
(\'\', \'admin\', \'Admin\', \'1\'),
(\'\', \'adminwelcome\', \'Welcome!\', \'1\'),
(\'\', \'associatedthemes\', \'Themes associated with this Template\', \'1\'),
(\'\', \'badrequest\', \'Bad Request\', \'1\'),
(\'\', \'cantopenfile\', \'Can\'\'t Open File\', \'1\'),
(\'\', \'changedsuccessfully\', \'Changed Successfully!\', \'1\'),
(\'\', \'changepassword\', \'Change Password\', \'1\'),
(\'\', \'clear\', \'Clear\', \'1\'),
(\'\', \'clearedsuccessfully\', \'Cleared Successfully!\', \'1\'),
(\'\', \'clone\', \'Clone\', \'1\'),
(\'\', \'closingbrace\', \'}\', \'1\'),
(\'\', \'closingbracket\', \']\', \'1\'),
(\'\', \'closingparenthesis\', \')\', \'1\'),
(\'\', \'colon\', \':\', \'1\'),
(\'\', \'content\', \'Content\', \'1\'),
(\'\', \'copyright\', \'&copy; Copyright 2008 <a href="http://wiki.suitframework.com/index.php/The_SUIT_Group">The SUIT Group</a>. All Rights Reserved.\', \'1\'),
(\'\', \'delete\', \'Delete\', \'1\'),
(\'\', \'deleteconfirm\', \'Are you sure you want to delete <template>?\', \'1\'),
(\'\', \'deletedsuccessfully\', \'Deleted Successfully!\', \'1\'),
(\'\', \'duplicatetitle\', \'Duplicate Title.\', \'1\'),
(\'\', \'edit\', \'Edit\', \'1\'),
(\'\', \'editedsuccessfully\', \'Edited Successfully!\', \'1\'),
(\'\', \'email\', \'E-Mail\', \'1\'),
(\'\', \'emailheaders\', \'From: SUIT Framework <admin@brandonevans.org>\', \'1\'),
(\'\', \'emailnotfound\', \'E-Mail not found in our database.\', \'1\'),
(\'\', \'entriesperpage\', \'Entries per Page\', \'1\'),
(\'\', \'errorlog\', \'Error Log\', \'1\'),
(\'\', \'errorlogwelcome\', \'The error log section allows you to shift through the errors triggered by a missing template, missing language, etc. This includes the details on which page it was trigerred, the time and the requested resource.\', \'1\'),
(\'\', \'escape\', \'Escape\', \'1\'),
(\'\', \'escapedsuccessfully\', \'Escaped Successfully!\', \'1\'),
(\'\', \'exclamationmark\', \'!\', \'1\'),
(\'\', \'first\', \'First\', \'1\'),
(\'\', \'home\', \'Home\', \'1\'),
(\'\', \'hyphen\', \'-\', \'1\'),
(\'\', \'infiniteloop\', \'Error: Infinite Loop caused by <template>\', \'1\'),
(\'\', \'inputtitle\', \'Title\', \'1\'),
(\'\', \'key\', \'Key\', \'1\'),
(\'\', \'languages\', \'Languages\', \'1\'),
(\'\', \'last\', \'Last\', \'1\'),
(\'\', \'list\', \'List\', \'1\'),
(\'\', \'loggedin\', \'Logged In\', \'1\'),
(\'\', \'loggedout\', \'Logged Out\', \'1\'),
(\'\', \'logo\', \'Logo\', \'1\'),
(\'\', \'logout\', \'Logout\', \'1\'),
(\'\', \'lostpassword\', \'Lost Password?\', \'1\'),
(\'\', \'lostpassword_body\', \'You have requested to recover your password. Your new password is shown below.

<password>

To activate it, please click the link below.

<base_url>/lostpassword.php?id=<id>&string=<string>\', \'1\'),
(\'\', \'lostpassword_subject\', \'Password Recovery\', \'1\'),
(\'\', \'maildeliveryfailed\', \'Mail Delivery Failed\', \'1\'),
(\'\', \'missingtitle\', \'You must have a title!\', \'1\'),
(\'\', \'nomatch\', \'The inputted password does not match the username <username>!\', \'1\'),
(\'\', \'notauthorized\', \'You are not authorized to view this page!\', \'1\'),
(\'\', \'notes\', \'Notes\', \'1\'),
(\'\', \'openingbrace\', \'{\', \'1\'),
(\'\', \'openingbracket\', \'[\', \'1\'),
(\'\', \'openingparenthesis\', \'(\', \'1\'),
(\'\', \'pages\', \'Pages\', \'1\'),
(\'\', \'password\', \'Password\', \'1\'),
(\'\', \'passwordchanged\', \'Password Changed\', \'1\'),
(\'\', \'passwordexpired\', \'Password Expired\', \'1\'),
(\'\', \'passwordsent\', \'Password Sent\', \'1\'),
(\'\', \'period\', \'.\', \'1\'),
(\'\', \'phpcode\', \'PHP Code\', \'1\'),
(\'\', \'phpinfo\', \'phpinfo\', \'1\'),
(\'\', \'poweredby\', \'Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a>\', \'1\'),
(\'\', \'questionmark\', \'?\', \'1\'),
(\'\', \'recaptcha\', \'Recaptcha\', \'1\'),
(\'\', \'recaptchaincorrect\', \'The reCAPTCHA wasn\'\'t entered correctly.\', \'1\'),
(\'\', \'register\', \'Register\', \'1\'),
(\'\', \'rename\', \'Rename\', \'1\'),
(\'\', \'renamedsuccessfully\', \'Renamed Successfully!\', \'1\'),
(\'\', \'requiredfields\', \'You have not filled out the required fields!\', \'1\'),
(\'\', \'send\', \'Send\', \'1\'),
(\'\', \'sessionexpired\', \'Your session has expired. See POSTDATA below.\', \'1\'),
(\'\', \'templates\', \'Templates\', \'1\'),
(\'\', \'themenotfound\', \'Error: Theme not found.\', \'1\'),
(\'\', \'title\', \'SUIT\', \'1\'),
(\'\', \'undefinederror\', \'Undefined Error\', \'1\'),
(\'\', \'update\', \'Update\', \'1\'),
(\'\', \'updatedsuccessfully\', \'Updated Successfully!\', \'1\'),
(\'\', \'username\', \'Username\', \'1\'),
(\'\', \'users\', \'Users\', \'1\'),
(\'\', \'value\', \'Value\', \'1\'),
(\'\', \'verticalbar\', \'|\', \'1\'),
(\'\', \'wrongpassword\', \'Error: Wrong Password.\', \'1\')tHiSiSaDeLiMeTeR
INSERT INTO `' . addslashes(magic($_POST['db_prefix'])) . 'pages` (`id`, `title`, `template`, `defaults`) VALUES
(\'\', \'admin_errorlog\', \'admin_errorlog\', 0),
(\'\', \'admin_escape\', \'admin_escape\', 0),
(\'\', \'admin_languages\', \'admin_languages\', 0),
(\'\', \'admin_notes\', \'admin_notes\', 0),
(\'\', \'admin_pages\', \'admin_pages\', 0),
(\'\', \'admin_templates\', \'admin_templates\', 0),
(\'\', \'admin_users\', \'admin_users\', 0),
(\'\', \'extract\', \'extract\', 0),
(\'\', \'index\', \'index\', 1),
(\'\', \'lostpassword\', \'lostpassword\', 0),
(\'\', \'password\', \'password\', 0),
(\'\', \'phpinfo\', \'phpinfo\', 0),
(\'\', \'register\', \'register\', 0),
(\'\', \'sandbox\', \'sandbox\', 0)tHiSiSaDeLiMeTeR
INSERT INTO `' . addslashes(magic($_POST['db_prefix'])) . 'languages` (`id`, `title`, `defaults`) VALUES
(\'\', \'English\', \'1\')tHiSiSaDeLiMeTeR
INSERT INTO `' . addslashes(magic($_POST['db_prefix'])) . 'templates` (`id`, `title`, `content`) VALUES
(\'\', \'401\', \'{admin_protect}{header}
401
{footer}\'),
(\'\', \'403\', \'{admin_protect}{header}
403
{footer}\'),
(\'\', \'404\', \'{admin_protect}{header}
404
{footer}\'),
(\'\', \'500\', \'{admin_protect}{header}
500
{footer}\'),
(\'\', \'admin_errorlog\', \'{admin_protect}{header}
[errorlogwelcome]
<br /><br /><limitform>
<br /><br />
<center>
<form action="#" method="post">
<input type="submit" name="errorlog_clear" value="[clear]" />
</form>
</center>
<br /><br />
<div class="errorlog-links"><links></div>
<div style="padding: 1em; margin: 5px;">
<errors>
</div>
<div class="errorlog-links"><links></div>
<br /><br />
<center>
<form action="index.php?page=admin_errorlog" method="post">
<input type="submit" name="errorlog_clear" value="[clear]" />
</form>
</center>
<br /><br /><limitform>
{footer}\'),
(\'\', \'admin_errorlog_entry\', \'<fieldset class="errorlog-entry">
	<legend><time> on <strong><location></strong></legend>
		<p><error></p>
</fieldset>\'),
(\'\', \'admin_errorlog_limit\', \'<form action="admin_errorlog.php" method="get">
[entriesperpage][colon] <input type="text" name="suit_errorlog_limit" value="<currentlimit>" />
<input type="submit" value="[list]" />
</form>\'),
(\'\', \'admin_errorlog_limit_get\', \'&amp;limit=\'),
(\'\', \'admin_errorlog_link\', \'<a href="{path_url}/admin_errorlog.php?start=<start><limit>"><display></a>\'),
(\'\', \'admin_errorlog_links\', \'<First> <1> <2> <3> <4> <5> <Last>\'),
(\'\', \'admin_escape\', \'{admin_protect}{header}
<form action="#" method="post">
<br /><textarea id="code" name="code" rows="40" cols="100" wrap="off" style="width: 100%;"><code></textarea>
<br /><input type="submit" name="escape" value="[escape]" />
</form>
{footer}\'),
(\'\', \'admin_languages\', \'{admin_protect}{header}
{footer}\'),
(\'\', \'admin_menu\', \'<a href="{path_url}/index.php?page=admin_notes">[notes]</a>
<a href="{path_url}/index.php?page=admin_templates">[templates]</a>
<a href="{path_url}/index.php?page=admin_pages">[pages]</a>
<a href="{path_url}/index.php?page=admin_languages">[languages]</a>
<a href="{path_url}/index.php?page=admin_users">[users]</a>
<a href="{path_url}/index.php?page=admin_errorlog">[errorlog]</a>
<a href="{path_url}/index.php?page=admin_escape">[escape]</a>
<a href="{path_url}/index.php?page=phpinfo">[phpinfo]</a>\'),
(\'\', \'admin_notes\', \'{admin_protect}{header}
<welcome>
<br /><br /><form action="#" method="post">
<textarea name="content" rows="20" cols="100" style="width: 100%">
<notes></textarea>
<br /><input type="submit" name="notes" value="[update]" />
</form>
{footer}\'),
(\'\', \'admin_pages\', \'{admin_protect}{header}
Hi!
{footer}
\'),
(\'\', \'admin_protect\', \' \'),
(\'\', \'admin_templates\', \'{admin_protect}{header}
<admin_templates>
{footer}\'),
(\'\', \'admin_templates_add\', \'<message>
<form action="#" method="post">
[inputtitle][colon] <input type="text" name="title" value="<title>" />
<br />[content][colon] <textarea name="content" rows="40" cols="100" wrap="off" style="width: 100%;"><content></textarea>
<br />[phpcode][colon]
<br /><textarea name="code" rows="40" cols="100" wrap="off" style="width: 100%;"><code></textarea>
<br /><input type="submit" name="add" value="[add]" />
</form>\'),
(\'\', \'admin_templates_delete\', \'<message>
<form action="#" method="post">
<input type="hidden" name="template" value="<template>" />
<br /><input type="submit" name="delete" value="[delete]" />
</form>\'),
(\'\', \'admin_templates_edit\', \'<message>
<form action="#" method="post" enctype="multipart/form-data">
<input type="hidden" name="template" value="<template>" />
[inputtitle][colon] <input type="text" name="title" value="<title>" />
<br />[content][colon] <textarea name="content" rows="40" cols="100" wrap="off" style="width: 100%;"><content></textarea>
<br />[phpcode][colon]
<br /><textarea name="code" rows="40" cols="100" wrap="off" style="width: 100%;"><code></textarea>
<br /><input type="submit" name="edit" value="[edit]" />
</form>\'),
(\'\', \'admin_templates_select\', \'<li><div style="float: left"><title></div> <div style="text-align: right;"><a href="{path_url}/index.php?page=admin_templates&amp;cmd=edit&amp;template=<template>">[edit]</a> [verticalbar] <a href="{path_url}/index.php?page=admin_templates&amp;cmd=delete&amp;template=<template>">[delete]</a> [verticalbar] <a href="{path_url}/index.php?page=admin_templates&amp;cmd=add&amp;template=<template>">[clone]</a>
<input type="checkbox" name="delete[openingbracket][closingbracket]" value="<id>" /></div></li>\'),
(\'\', \'admin_templates_select_skeleton\', \'<form action="{path_url}/index.php?page=admin_templates&amp;cmd=delete">
<p style="text-align: center;><input type="submit" name="delete_submit" value="Delete Selected Templates" /></p>
<ul id="suit_templateslist">
<li style="text-align: right;"><a href="index.php?page=admin_templates&amp;cmd=add">[add]</a></li>
<list>
</ul>
<p style="text-align: center;><input type="submit" name="delete_submit" value="Delete Selected Templates" /></p>
</form>
\'),
(\'\', \'admin_users\', \'{admin_protect}{header}
{footer}\'),
(\'\', \'badrequest\', \'{header}
[badrequest]
{footer}
\'),
(\'\', \'closingbrace\', \'[closingbrace]\'),
(\'\', \'config\', \' \'),
(\'\', \'extract\', \'{admin_protect}<pre><extract></pre>\'),
(\'\', \'file\', \'index.php?<file>\'),
(\'\', \'footer\', \'		</div>
		
		<div id="suit_copyright">
                        <p>[poweredby]</p>
			<p>[copyright]</p>
		</div>
</div>

</body>
</html>\'),
(\'\', \'header\', \'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<title>[title]</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
<style type="text/css">
body
{openingbrace}
	background: url({path_url}/images/body-background.gif) repeat;
	font-family: Verdana, Sans-Serif;
	font-size: 15px;
	color: #000;
{closingbrace}

a
{openingbrace}
	color: #333;
	text-decoration: underline;
{closingbrace}

img
{openingbrace}
	border: 0;
{closingbrace}

#suit_wrapper
{openingbrace}
	width: 900px;
	margin: auto;
{closingbrace}

#suit_logo
{openingbrace}
	background: url({path_url}/images/logo-background.gif) no-repeat;
	width: 501px;
	height: 120px;
{closingbrace}

#suit_logo a
{openingbrace}
	height: 100%;
	overflow: hidden;
	display: block;
	text-indent: -999em;
{closingbrace}


#suit_banner, #suit_content, #suit_panel, #suit_copyright
{openingbrace}
	background: #FFF;
{closingbrace}

#suit_banner-top
{openingbrace}
	background: url({path_url}/images/banner-top-background.gif) no-repeat;
	width: 900px;
	height: 29px;
	margin-bottom: 0;
	margin-top: 0;
	border-bottom: none;
{closingbrace}

#suit_banner
{openingbrace}
	margin-top: 0;
	padding: 1em;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-top: none;
{closingbrace}

#suit_banner h1
{openingbrace}
	text-indent: -999em;
{closingbrace}

#suit_topbar
{openingbrace}
	background: #333;
	margin: 0;
	padding: 5px;
	text-align: center;
	font-variant: small-caps;
	border-top: 0px;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-bottom: none;
{closingbrace}

#suit_topbar a:hover
{openingbrace}
	background: #EFEFEF;
	color: #333;
{closingbrace}

#suit_panel
{openingbrace}
	margin: 0;
	padding: 5px;
	text-align: center;
	border-top: 1px solid #000;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-bottom: none;
{closingbrace}

#suit_topbar a
{openingbrace}
	text-decoration: none;
	padding: 5px;
	color: #FFF;
{closingbrace}

#suit_content
{openingbrace}
	border: 1px solid #000;
	padding: 1em;
{closingbrace}

/* Success and Error */
#suit_success
{openingbrace}
	background: #A9D898 url({path_url}/images/success-background.gif) no-repeat;
	border: 1px solid #34AC0A;
	font-size: 96%;
	color: #185005;
	padding: 5px;
{closingbrace}

#suit_success p
{openingbrace}
	padding: 1.5em;
	margin-left: 45px;
{closingbrace}

/* Templates */

ul#suit_templateslist
{openingbrace}
	list-style: none;
	border-top: 1px solid #333;
	margin: 0;
	padding: 0;
{closingbrace}

ul#suit_templateslist li
{openingbrace}
	background: #EFEFEF;
	padding: 5px;
	border-bottom: 1px solid #333;
	border-right: 1px solid #333;
	border-left: 1px solid #333;
{closingbrace}

ul#suit_templateslist a
{openingbrace}
	text-align: right;
{closingbrace}

/* Error Logging */
fieldset
{openingbrace}
	border: 1px solid #333;
{closingbrace}

fieldset.errorlog-entry
{openingbrace}
	background: #EFEFEF url({path_url}/images/errorlog-quote.gif) no-repeat left;
{closingbrace}

fieldset.errorlog-entry p
{openingbrace}
	padding: 1em;
	margin-left: 41px;
{closingbrace}

div.errorlog-links
{openingbrace}
	text-align: center;
{closingbrace}

div.errorlog-links a
{openingbrace}
	padding: 2px 5px 2px 5px;
	margin: 2px;
	border: 1px solid #000;
	text-decoration: none;
	color: #EFEFEF;
	background: #333;
{closingbrace}

div.errorlog-links a:hover
{openingbrace}
	background: #EFEFEF;
	color: #333;
{closingbrace}

#suit_copyright
{openingbrace}
	text-align: center;
	padding: 3px;
	border-top: none;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-bottom: 1px solid #000;
	margin-bottom: 5px;
{closingbrace}

#suit_themeswitcher
{openingbrace}
	text-align: center;
	margin-bottom: 20px;
{closingbrace}
</style>
</head>
<body>
	<div id="suit_wrapper">
		<div id="suit_banner-top"></div>
		<div id="suit_banner">
			<div id="suit_logo"><a href="{path_url}/index.php">SUIT</a></div>
		</div>
		<div id="suit_topbar">
                        {navigation}
		</div>
		<div id="suit_panel">
                        <menu>
		</div>
		
		<div id="suit_content">\'),
(\'\', \'index\', \'{header}
Hi!
{footer}\'),
(\'\', \'init\', \'\'),
(\'\', \'login\', \'<message>
<form action="#" method="post">
<label for="suit_username">[username][colon] <input type="text" name="suit_username" /></label>
<label for="suit_password">[password][colon] <input type="password" name="suit_password" /></label>
<input type="submit" name="suit_login" value="Submit" />
<a href="{path_url}/index.php?page=register">[register]</a>
<a href="{path_url}/index.php?page=lostpassword">[lostpassword]</a>
</form>\'),
(\'\', \'lostpassword\', \'{header}
<message>
<form action="#" method="post">
[email][colon] <input type="text" name="email" />
<br /><input type="submit" name="lostpassword" value="[send]" />
</form>
{footer}\'),
(\'\', \'menu\', \'<a href="{file}&amp;suit_logout=true">[logout]</a>
<a href="{path_url}/index.php?page=password">[changepassword]</a>
<admin_menu>\'),
(\'\', \'navigation\', \'<a href="{path_url}/index.php">[home]</a>\'),
(\'\', \'notauthorized\', \'{header}
[notauthorized]
{footer}
\'),
(\'\', \'openingbrace\', \'[openingbrace]\'),
(\'\', \'password\', \'{header}
<message>
<form action="#" method="post">
Old Password[colon] <input type="password" name="old" />
<br />New Password[colon] <input type="password" name="new" />
<br /><input type="submit" name="password" value="[changepassword]" />
</form>
{footer}\'),
(\'\', \'path_url\', \'<path>\'),
(\'\', \'phpinfo\', \'{admin_protect}\'),
(\'\', \'postdata\', \'{header}
[sessionexpired]
<br /><br /><table border="1">
<tr>
<td>[key]</td>
<td>[value]</td>
</tr>
<list>
</table>
{footer}\'),
(\'\', \'postdata_list\', \'<tr>
<td><key></td>
<td><value></td>
</tr>\'),
(\'\', \'recaptcha\', \'<recaptcha>\'),
(\'\', \'recaptcha_keys\', \'\'),
(\'\', \'recaptcha_lib\', \'\'),
(\'\', \'register\', \'{header}
<message>
<form action="#" method="post">
<table>
<tr>
<td>[username][colon]</td>
<td><input type="text" name="username" /></td>
</tr>
<tr>
<td>[password][colon]</td>
<td><input type="password" name="password" /></td>
</tr>
<tr>
<td>[email][colon]</td>
<td><input type="text" name="email" /></td>
</tr>
<tr>
<td>[recaptcha][colon]</td>
<td>{recaptcha}</td>
</tr>
</table>
<input type="submit" name="register" value="Register" />
</form>
{footer}\'),
(\'\', \'sandbox\', \'<textarea></textarea>
&amp;\'),
(\'\', \'success\', \'<div id="suit_success"><p><message></p></div>\')tHiSiSaDeLiMeTeR';
}
?>