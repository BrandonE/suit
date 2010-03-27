<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>[gettext]SUIT Framework[/gettext] - [c]title[/c]</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/style.css" rel="stylesheet" type="text/css" />
<link href="/pygments.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/jquery.js"></script>
<script type="text/javascript" src="/javascript.js"></script>
</head>
<body>
<div class="header">
    <div class="left">
        <h1 class="title"><a href="[url controller="root" action="template" templatefile="index" /]">SUIT Framework</a></h1>
        <div class="slogan">Scripting Using Integrated Templates</div>
    </div>
    <div class="right">
        <form action="#" method="post">
        <p>
            <input name="search" type="text" /> <input name="submit" type="submit" value="Search" />
        </p>
        </form>
        <form class="languages" action="#" method="post">
        <p>
            <select name="language">
                <option value="default">Default</option>
                [loop key="key" value="value" list="loop.languages"]
                <option value="[c]value.id[/c]">[c]value.title[/c]</option>
                [/loop]
            </select>
            <input type="submit" value="Update" />
        </p>
        </form>
    </div>
</div>
<div class="nav">
    <div class="space"></div>
    <ul>
        <li[if condition="condition.home"] class="selected"[/if]><a href="[url controller="root" action="template" templatefile="index" /]">[gettext]Home[/gettext]</a></li>
        <li[if condition="condition.download"] class="selected"[/if]><a href="[url controller="root" action="template" templatefile="download" /]">[gettext]Download[/gettext]</a></li>
        <li[if condition="condition.docs"] class="selected"[/if]><a href="[url controller="root" action="template" templatefile="docs" /]">[gettext]Docs[/gettext]</a></li>
        <li[if condition="condition.tryit"] class="selected"[/if]><a href="[url controller="root" action="template" templatefile="tryit" /]">[gettext]Try It[/gettext]</a></li>
        <li[if condition="condition.slacks"] class="selected"[/if]><a href="[url controller="root" action="template" templatefile="slacks" /]">[gettext]SLACKS[/gettext]</a></li>
        <li><a href="http://suitframework.svn.sourceforge.net/viewvc/suitframework/" target="_blank">[gettext]SVN[/gettext]</a></li>
    </ul>
</div>
<div class="content">
    <div class="section">
        <h2>[c]title[/c]</h2>
        <p style="text-align: center">
            <strong>[gettext]mm/dd/YY: Something x.x.x has been released.[/gettext]</strong>
        </p>