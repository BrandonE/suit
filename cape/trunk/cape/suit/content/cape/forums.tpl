			[if navigation]
			[if loggedin]
			<div class="community-newbuttons">
				[else forumlocked]<div class="community-newbutton"><a href="[if mod_rewrite](mod_rewriteurl)/newtopic/(forum)/(forumrewrite)/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=newtopic&amp;id=(id)[/else mod_rewrite]">(language=>newtopic)</a></div>[/else forumlocked]
				[if forumlocked]<div class="community-newbuttonlocked">(language=>forumlocked)</div>[/if forumlocked]
			</div>
			[/if loggedin]
			[if forums]
			<table class="list" cellpadding="0" cellspacing="0">
				<tr class="list-entry">
					<td colspan="4"><center>[else navigation]<a href="[if mod_rewrite](mod_rewriteurl)/forum/(categoryid)/(categoryrewrite)/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=forum&amp;id=(categoryid)[/else mod_rewrite]">[/else navigation](categorytitle)[else navigation]</a>[/else navigation]</center></td>
				</tr>
				<tr class="list-entry">
					<td>(language=>forum)</td>
					<td>(language=>topics)</td>
					<td>(language=>posts)</td>
					<td>(language=>lastpostinfo)</td>
				</tr>
				[loop forums]
				<tr class="list-entry">
					<td>
						<h3><a href="[if mod_rewrite](mod_rewriteurl)/forum/[id]/[forumrewrite]/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=forum&amp;id=[id][/else mod_rewrite]">[title]</a></h3>
						[if description]<p>[description]</p>[/if description]
					</td>
					<td>
						<p>[topics]</p>
					</td>
					<td>
						<p>[posts]</p>
					</td>
					<td>
						<p>[if latest][time][/if latest][else latest](language=>na)[/else latest]</p>
						<p>(language=>in): [if latest]<a href="[if mod_rewrite](mod_rewriteurl)/topic/[topicid]/[topicrewrite]/[start]/[limit]/#post[postid][/if mod_rewrite][else mod_rewrite](communitypath)cmd=topic&amp;id=[topicid]&amp;start=[start]&amp;limit=[limit]#post[postid][/else mod_rewrite]">[topictitle]</a>[/if latest][else latest](language=>na)[/else latest]</p>
						<p>(language=>by): [if latest]<a href="[if mod_rewrite](mod_rewriteurl)/profile/[userid]/[userrewrite]/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=profile&amp;id=[userid][/else mod_rewrite]">[user]</a>[/if latest][else latest](language=>na)[/else latest]</p>
					</td>
				</tr>
				[/loop forums]
			</table>
			[/if forums]
			{cape/limit}
			{cape/search}
			{cape/order}
			<p>(previous) (current) (next)</p>
			[/if navigation]
			[else navigation]
			[loop categories]
			<table class="list" cellpadding="0" cellspacing="0">
				<tr class="list-entry">
					<td colspan="4"><center><a href="[if mod_rewrite](mod_rewriteurl)/forum/[categoryid]/[categoryrewrite]/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=forum&amp;id=[categoryid][/else mod_rewrite]">[categorytitle]</a></center></td>
				</tr>
				<tr class="list-entry">
					<td>(language=>forum)</td>
					<td>(language=>topics)</td>
					<td>(language=>posts)</td>
					<td>(language=>lastpostinfo)</td>
				</tr>
				[loop forums]
				<tr class="list-entry">
					<td>
						<h3><a href="[if mod_rewrite](mod_rewriteurl)/forum/[id]/[forumrewrite]/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=forum&amp;id=[id][/else mod_rewrite]">[title]</a></h3>
						[if description]<p>[description]</p>[/if description]
					</td>
					<td>
						<p>[topics]</p>
					</td>
					<td>
						<p>[posts]</p>
					</td>
					<td>
						<p>[if latest][time][/if latest][else latest](language=>na)[/else latest]</p>
						<p>(language=>in): [if latest]<a href="[if mod_rewrite](mod_rewriteurl)/topic/[topicid]/[topicrewrite]/[start]/[limit]/#post[postid][/if mod_rewrite][else mod_rewrite](communitypath)cmd=topic&amp;id=[topicid]&amp;start=[start]&amp;limit=[limit]#post[postid][/else mod_rewrite]">[topictitle]</a>[/if latest][else latest](language=>na)[/else latest]</p>
						<p>(language=>by): [if latest]<a href="[if mod_rewrite](mod_rewriteurl)/profile/[userid]/[userrewrite]/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=profile&amp;id=[userid][/else mod_rewrite]">[user]</a>[/if latest][else latest](language=>na)[/else latest]</p>
					</td>
				</tr>
				[/loop forums]
			</table>
			[/loop categories]
			[/else navigation]
			[if topics]
			<table class="list" cellpadding="0" cellspacing="0">
				<tr class="list-entry">
					<td>(language=>topictitle)</td>
					<td>(language=>topicstarter)</td>
					<td>(language=>replies)</td>
					<td>(language=>lastpostinfo)</td>
				</tr>
				[loop topics]
				<tr class="list-entry">
					<td>
						<a href="[if mod_rewrite](mod_rewriteurl)/topic/[id]/[topicrewrite]/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=topic&amp;id=[id][/else mod_rewrite]">[title]</a>
					</td>
					<td>
						<a href="[if mod_rewrite](mod_rewriteurl)/profile/[userid]/[userrewrite]/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=profile&amp;id=[userid][/else mod_rewrite]">[user]</a>
					</td>
					<td>
						<p>[replies]</p>
					</td>
					<td>
						<p>[time]</p>
						<a href="[if mod_rewrite](mod_rewriteurl)/topic/[id]/[topicrewrite]/[start]/[limit]/#post[postid][/if mod_rewrite][else mod_rewrite](communitypath)cmd=topic&amp;id=[id]&amp;start=[start]&amp;limit=[limit]#post[postid][/else mod_rewrite]">(language=>lastpost)</a>: <a href="[if mod_rewrite](mod_rewriteurl)/profile/[latestuserid]/[latestuserrewrite]/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=profile&amp;id=[latestuserid][/else mod_rewrite]">[latestuser]</a>
					</td>
				</tr>
				[/loop topics]
			</table>
			[/if topics]
			[if navigation]
			<p>(previous) (current) (next)</p>
			[if loggedin]
			<div class="community-newbuttons">
				[else forumlocked]<div class="community-newbutton"><a href="[if mod_rewrite](mod_rewriteurl)/newtopic/(forum)/(forumrewrite)/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=newtopic&amp;id=(id)[/else mod_rewrite]">(language=>newtopic)</a></div>[/else forumlocked]
				[if forumlocked]<div class="community-newbuttonlocked">(language=>forumlocked)</div>[/if forumlocked]
			</div>
			[/if loggedin]
			[/if navigation]