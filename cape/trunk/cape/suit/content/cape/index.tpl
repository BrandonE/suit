{cape/header}
	<div class="section">
		<h2>(section)</h2>
		<div class="community">
			[if message]
(message)
			[/if message]
			<p><a href="[if mod_rewrite](mod_rewriteurl)/members/[/if mod_rewrite][else mod_rewrite](communitypath)cmd=members[/else mod_rewrite]">(language=>members)</a></p>
			<div class="breadcrumb">
				[loop breadcrumbs]
				[if url]<a href="[url]">[/if url][title][if url]</a>[/if url]
				[/loop breadcrumbs]
			</div>
(community)
		</div>
	</div>
{cape/footer}
[section separator] - [/section separator]
[section page] [/section page]
[section breadcrumbseparator] > [/section breadcrumbseparator]