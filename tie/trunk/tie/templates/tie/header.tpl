<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
[execute][template]templates/tie/head.tpl[/template][/execute]
</head>
<body>
<div class="header">
    <div class="left">
        <h1 class="title"><a href="#">[var]language.title[/var]</a></h1>
        <div class="slogan">[var]language.slogan[/var]</div>
    </div>
    <div class="right">
[code]code/tie/language.inc.php[/code]
[execute][template]templates/tie/language.tpl[/template][/execute]
    </div>
</div>
<div class="welcome">
    <div class="menu">
        <b>[var]language.welcome[/var]</b> |
        <a href="[var]path.url[/var]">[var]language.dashboard[/var]</a> |
        <a href="[var]path.url[/var][var]path.urlquerychar[/var]section=templates">[var]language.templates[/var]</a> |
        <a href="[var]path.url[/var][var]path.urlquerychar[/var]section=code">[var]language.code[/var]</a>
    </div>
</div>
<div class="nav">
    <div class="space"></div>
    <ul>
        <li class="selected"><a href="#">[var]language.tie[/var]</a></li>
        <li><a href="http://www.suitframework.com/">[var]language.suit[/var]</a></li>
    </ul>
</div>
<div class="content">