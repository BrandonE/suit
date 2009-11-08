{cape/limit}
{cape/search}
{cape/order}[if delete] | {cape/checkall} | {cape/uncheckall}[/if delete]
[if loggedin]
 | <a href="[if mod_rewrite](mod_rewriteurl)/subscribe/(id)/(topicrewrite)/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=subscribe&amp;id=(id)[/else mod_rewrite]">[if subscribe](language=>subscribe)[/if subscribe][else subscribe](language=>unsubscribe)[/else subscribe]</a>
<div class="new-buttons">
	[else forumlocked]<div class="community-newbutton"><a href="[if mod_rewrite](mod_rewriteurl)/newtopic/(forum)/(forumrewrite)/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=newtopic&amp;id=(id)[/else mod_rewrite]">(language=>newtopic)</a></div>[/else forumlocked]
	[if forumlocked]<div class="community-newbuttonlocked">(language=>forumlocked)</div>[/if forumlocked]
	[else topiclocked]<div class="community-newbutton"><a href="[if mod_rewrite](mod_rewriteurl)/newreply/(id)/(topicrewrite)/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=newreply&amp;id=(id)[/else mod_rewrite]">(language=>newreply)</a></div>[/else topiclocked]
	[if topiclocked]<div class="community-newbuttonlocked">(language=>topiclocked)</div>[/if topiclocked]
</div>
[/if loggedin]
<p>(previous) (current) (next)</p>
				(posts)
[if loggedin]
<center>
<form action="[if mod_rewrite](mod_rewriteurl)/newreply/(id)/(topicrewrite)/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=forum&amp;id=(forum)&amp;cmd=newtopic[/else mod_rewrite]" method="post">
<input type="hidden" name="title" value="(language=>re)(topic)" />
<p>(language=>content): <textarea name="content" rows="10" style="width: 50%">
</textarea></p>
<p><input type="checkbox" name="smilies" value="1" checked /> (language=>smilies) <input type="checkbox" name="signature" value="1" checked /> (language=>signature)</p>
<p><input type="submit" name="newreply" value="(language=>submit)" /> <input type="submit" name="preview" value="(language=>preview)" /></p>
</form>
</center>
[/if loggedin]
<p>(previous) (current) (next)</p>
[if loggedin]
<div class="new-buttons">
	[else forumlocked]<div class="community-newbutton"><a href="[if mod_rewrite](mod_rewriteurl)/newtopic/(forum)/(forumrewrite)/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=newtopic&amp;id=(id)[/else mod_rewrite]">(language=>newtopic)</a></div>[/else forumlocked]
	[if forumlocked]<div class="community-newbuttonlocked">(language=>forumlocked)</div>[/if forumlocked]
	[else topiclocked]<div class="community-newbutton"><a href="[if mod_rewrite](mod_rewriteurl)/newreply/(id)/(topicrewrite)/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=newreply&amp;id=(id)[/else mod_rewrite]">(language=>newreply)</a></div>[/else topiclocked]
	[if topiclocked]<div class="community-newbuttonlocked">(language=>topiclocked)</div>[/if topiclocked]
</div>
[/if loggedin]