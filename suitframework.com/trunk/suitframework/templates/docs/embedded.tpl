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
<p>Please log in.</p>
<?php
endif;
?>
<fieldset>
    <legend>Who's Online?</legend>
    <p>
<?php
foreach ($members as $key => $value):
    if ($value['admin']):
?>
            <strong>
<?php
    endif;
    if ($value['banned']):
?>
            <del>
<?php
    endif;
?>
            <?php echo $value['name']; ?>

<?
    if ($value['admin']):
?>
            </strong>
<?php
    endif;
    if ($value['banned']):
?>
            </del>
<?php
    endif;
    if ($key != count($members) - 1):
?>
            ,
<?php
    endif;
endforeach;
?>
    </p>
</fieldset>
</body>
</html>