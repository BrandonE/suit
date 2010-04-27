    </div>
</div>
<div class="footer">
    <p>
        <a href="[url controller="root" action="template" templatefile="index" /]">[gettext]Home[/gettext]</a> |
        <a href="[url controller="root" action="template" templatefile="download" /]">[gettext]Download[/gettext]</a> |
        <a href="[url controller="root" action="template" templatefile="docs" /]">[gettext]Docs[/gettext]</a> |
        <a href="[url controller="root" action="template" templatefile="tryit" /]">[gettext]Try It[/gettext]</a> |
        <a href="[url controller="root" action="template" templatefile="slacks" /]">[gettext]SLACKS[/gettext]</a> |
        <a href="http://suitframework.svn.sourceforge.net/viewvc/suitframework/" target="_blank">[gettext]SVN[/gettext]</a>
    </p>
    <p>Powered by <a target="_blank" href="http://www.suitframework.com/">SUIT</a></p>
    <p>Copyright &copy; 2008-2010 Brandon Evans and Chris Santiago. All Rights Reserved.</p>
	<p><a href="https://lists.sourceforge.net/lists/listinfo/suitframework-news">[gettext]Subscribe to our Mailing List[/gettext]</a></p>
	[if condition="condition.slacks" not="true"]
    <p><a href="http://www.suitframework.com/slacks/?referrer=true">[gettext]Debug this page using SLACKS[/gettext]</a></p>
    <form action="#" method="post">
    [loop key="key" value="value" iterable="slackslog"]
    <input type="hidden" name="[c]key[/c]" value="[c]value[/c]" />
    [/loop]
    <p><input type="submit" name="slacks" value="Download SLACKS log" /></p>
    </form>
    [/if]
    <p>
		<a href="http://www.suitframework.com/" target="_blank"><img src="/images/poweredby/suit.png" alt="SUIT" /></a>
		<a href="http://php.net/" target="_blank"><img src="/images/poweredby/php.gif" alt="PHP" /></a>
		<a href="http://www.python.org/" target="_blank"><img src="/images/poweredby/python.png" alt="Python" /></a>
		<a href="http://mysql.com/" target="_blank"><img src="/images/poweredby/mysql.png" alt="MySQL" /></a>
	</p>
	<p>
		<a href="http://twitter.com/suitframework" target="_blank"><img src="/images/news/twitter.png" alt="Twitter" /></a>
		<a href="http://www.facebook.com/pages/SUIT-Framework/137182976758" target="_blank"><img src="/images/news/facebook.png" alt="Facebook" /></a>
	</p>
	<script type="text/javascript"><!--
	google_ad_client = "pub-4664609413658128";
	/* 728x90, created 9/30/09 */
	google_ad_slot = "6999371140";
	google_ad_width = 728;
	google_ad_height = 90;
	//-->
	</script>
	<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
</div>
</body>
</html>