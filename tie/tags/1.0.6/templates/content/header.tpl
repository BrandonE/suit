{doctype}
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{head}
</head>

<body>
<div id="header">
	<div class="left">
		<h1 class="title"><a href="#">[title]</a></h1>
		<div class="slogan">[slogan]</div>
	</div>
	<div class="right">
		<div>
			<div>
{languageform}
			</div>
		</div>
	</div>
</div>
<div class="welcome">
	<div class="menu">
		<b>[welcome]</b>
		<if admin>
 | 
		<a href="<dashboard>">[dashboard]</a> | 
		<a href="<templates>section=content">[content]</a> | 
		<a href="<templates>section=code">[code]</a> | 
		<a href="<templates>section=glue">[glue]</a>
		</if admin>
	</div>
</div>
<div id="nav">
	<div class="space">&nbsp;</div>
	<ul>
		<li<if admin> class="selected"</if admin>><a href="#">[admin]</a></li>
		<li><a href="http://www.suitframework.com/">[suit]</a></li>
	</ul>
</div>
<div id="content">