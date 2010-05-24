<?php
$username = 'Brandon';
$loggedin = true;
$members = array
(
    array
    (
        'name' => 'Brandon',
        'admin' => true,
        'banned' => false
    ),
    array
    (
        'name' => 'Chris',
        'admin' => false,
        'banned' => true
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
<fieldset>
    <legend>Who\'s Online?</legend>
    <p>';
foreach ($members as $key => $value)
{
    if ($value['admin'])
    {
        echo '
        <strong>';
    }
    if ($value['banned'])
    {
        echo '
        <del>';
    }
    echo '
        ' . $value['name'];
    if ($value['admin'])
    {
        echo '
        </strong>';
    }
    if ($value['banned'])
    {
        echo '
        </del>';
    }
    if ($key != count($members) - 1)
    {
        echo ',';
    }
}
echo '
    </p>
</fieldset>
</body>
</html>';
?>