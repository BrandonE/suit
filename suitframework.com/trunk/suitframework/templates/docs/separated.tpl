[trim]
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>My Site</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="javascript.js"></script> 
</head>
<body>
[if condition="loggedin"]
<p class="centered">
    <a href="#" onclick="welcome('[var]username[/var]');">Welcome, [var]username[/var]!</a>
</p>
[/if]
[if condition="loggedin" not="true"]
<p>Please log in.</p>
[/if]
<table width="100%" border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Group</th>
        </tr>
    </thead>
    <tbody>
        [loop value="value" iterable="members"]
        <tr>
            <td>[var]value.name[/var]</td>
            <td>[var]value.group[/var]</td>
        </tr>
        [/loop]
    </tbody>
</table>
</body>
</html>
[/trim]