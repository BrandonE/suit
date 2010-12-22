<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>[gettext]SUIT Framework[/gettext] - [c]title[/c]</title>
<meta name="description" content="SUIT Framework (Scripting Using Integrated Templates), an open-source web templating framework with some innovative features. It provides a lightweight and powerful solution to this problem by allowing developers to define their own syntax for transforming templates by using rules." />
<meta name="keywords" content="suit, framework, scripting, using, integrated, templates, open, source, web, templating, syntax, rules, php, python" />
<meta name="author" content="Brandon Evans and Chris Santiago" />
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<link href="/style.css" rel="stylesheet" type="text/css" />
<link href="/pygments.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/jquery.js"></script>
<script type="text/javascript" src="/javascript.js"></script>
</head>
<body>
<div class="header">
    <div class="left">
        <h1 class="title"><a href="[url controller="root" action="template" templatefile="index" /]">[gettext]SUIT Framework[/gettext]</a></h1>
        <div class="slogan">[gettext]Scripting Using Integrated Templates[/gettext]</div>
    </div>
    <div class="right">
        <form action="#" method="post">
        <p>
            <input name="search" type="text" /> <input name="submit" type="submit" value="[gettext]Search[/gettext]" />
        </p>
        </form>
        <form class="languages" action="#" method="post">
        <p>
            <select name="language">
                <option value="">[gettext]English[/gettext]</option>
                [loop key="key" value="value" iterable="loop.languages"]
                <option value="[c]key[/c]"[if condition="value.selected"] selected="selected"[/if]>[c]value.title[/c]</option>
                [/loop]
            </select>
            <input type="submit" value="[gettext]Update[/gettext]" />
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
        <li><a href="https://sourceforge.net/projects/suitframework/forums" target="_blank">[gettext]Forums[/gettext]</a></li>
    </ul>
</div>
<div class="content">
    <div class="section">
        <h2>[c]title[/c]</h2>
        <p style="text-align: center">
            <strong><a href="[url controller="root" action="template" templatefile="docs" parameter1="changelog" /]">[gettext]12/21/2010: SUIT 2.0.2 and Rulebox 1.1.1 released.[/gettext]</a></strong>
        </p>