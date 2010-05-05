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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>My Site</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
a
{
    color: blue;
    font-size: 16pt;
    text-decoration: none;
}

.centered
{
    text-align: center;
}
</style>
<script type="text/javascript">
function welcome(username)
{
    alert('Welcome, ' + username + '!');
}
</script>
</head>
<body>
<?php
if ($loggedin):
?>
<p class="centered">
    <a href="#" onclick="welcome('<?php echo $username; ?>');">Welcome, <?php echo $username; ?>!</a>
</p>
<?php
else:
?>
<p>Please log in.</p>'
<?php
endif;
?>
<table width="100%" border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Group</th>
        </tr>
    </thead>
    <tbody>
<?php
foreach ($members as $value):
?>
        <tr>
            <td><?php echo $value['name'] ?></td>
            <td><?php echo $value['group'] ?></td>
        </tr>
<?php
endforeach;
?>
    </tbody>
</table>
</body>
</html>