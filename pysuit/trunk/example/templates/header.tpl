<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>[var]language=>title[/var] - [var]language=>example[/var]</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="pygments.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="header">
    <div class="left">
        <h1 class="title"><a href="#">[var]language=>title[/var]</a></h1>
        <div class="slogan">[var]language=>slogan[/var]</div>
    </div>
    <div class="right">
        <form class="languages" action="#" method="get">
        <p>
            <select name="language">
            <option value="default">[var]language=>default[/var]</option>
            [loop vars="[var json='true']loop=>languages[/var]"]
            <option value="[loopvar]name[/loopvar]"[if condition="[loopvar json='true']selected[/loopvar]"] selected="selected"[/if]>[loopvar]title[/loopvar]</option>
            [/loop]
            </select>
            <input type="submit" value="[var]language=>update[/var]" />
        </p>
        </form>
    </div>
</div>
<div class="nav">
    <div class="space"></div>
    <ul>
        <li class="selected"><a href="#">[var]language=>example[/var]</a></li>
        <li><a href="http://www.suitframework.com/">[var]language=>suit[/var]</a></li>
    </ul>
</div>
<div class="content">