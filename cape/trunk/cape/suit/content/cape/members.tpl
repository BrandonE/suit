{cape/limit}
{cape/search}
{cape/order}[if delete] | {cape/checkall} | {cape/uncheckall}[/if delete]
<p>(previous) (current) (next)</p>
<table class="list" width="100%" cellspacing="0">
	<tr class="list-entry">
		<td>(language=>username)</td>
		<td>(language=>group)</td>
		<td>(language=>joined)</td>
	</tr>
	[loop members]
	<tr class="list-entry">
		<td><a href="[if mod_rewrite](mod_rewriteurl)/profile/[userid]/[userrewrite]/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=profile&amp;id=[userid][/else mod_rewrite]">[username]</a></td>
		<td>[group]</td>
		<td>[joined]</td>
	</tr>
	[/loop members]
</table>
<p>(previous) (current) (next)</p>