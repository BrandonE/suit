<?php
$username = 'Brandon';
$loggedin = true;
$members = array
(
    array
    (
        'name' => 'Brandon',
        'group' => 'Administrator'
    ),
    array
    (
        'name' => 'Chris',
        'group' => 'Banned'
    )
);

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>My Site</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>';
if ($loggedin)
{
    echo '
<p style="text-align: center;">
    <a href="#" onclick="alert(\'Welcome, ' . $username . '!\');" style="color: blue; font-size: 16pt; text-decoration: none;">Welcome, ' . $username . '!</a>
</p>';
}
else
{
    echo '
<p>Please log in.</p>';
}

echo '
<table width="100%" border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Group</th>
        </tr>
    </thead>
    <tbody>';
foreach ($members as $value)
{
    echo '
        <tr>
            <td>' . $value['name'] . '</td>
            <td>' . $value['group'] . '</td>
        </tr>';
}
echo '
    </tbody>
</table>
</body>
</html>';
?>