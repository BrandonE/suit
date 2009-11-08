			[loop posts]
			<table class="list" cellpadding="0" cellspacing="0">
				<tr class="list-entry">
					<td width="25%" style="vertical-align: top;">
						<h3 class="post-username">[if user]<a href="[if mod_rewrite](mod_rewriteurl)/profile/[userid]/[userrewrite]/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=profile&amp;id=[userid][/else mod_rewrite]">[username]</a>[/if user][else user](language=>na)[/else user]</h3>
						[if title]<p>[usertitle]</p>[/if title]
						[if group]<p>[grouptitle]</p>[/if group]
						[if avatar]<p><img src="[avatar]" alt="[username]" /></p>[/if avatar]
						(language=>posts): [posts]<br />
					</td>
					<td width="75%">
						<span style="font-size:14pt;"><a name="post[postid]" /><a href="#post[postid]">[title]</a></span>
						<br /><span style="font-size:8pt;">[time]</span>
						<div class="post">
							[content]
							[if edited]
							<p><em>[edited]</em></p>
							[/if edited]
						</div>
						[if signature]
						<div class="signature">[signature]</div>
						[/if signature]
						[if any]
						<div class="post-controls">
							<p>[if edit]<a href="[if mod_rewrite](mod_rewriteurl)/edit/[postid]/[postrewrite]/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=edit&amp;id=[postid][/else mod_rewrite]">(language=>editpost)</a>[if quote] | [/if quote][/if edit][if quote]<a href="[if mod_rewrite](mod_rewriteurl)/newreply/(id)/(topicrewrite)/[postid]/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=edit&amp;id=(id)&amp;post=[postid][/else mod_rewrite]">(language=>quotepost)</a>[/if quote]</p>
						</div>
						[/if any]
					</td>
				</tr>
			</table>
			[/loop posts]