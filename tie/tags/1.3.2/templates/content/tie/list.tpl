		<fieldset>
			<legend>(language=>options)</legend>
{tie/limit}
{tie/search}
		</fieldset>
		[else code]
		<form enctype="multipart/form-data" action="#" method="post">
		[if error]
		<p>(error)</p>
		[/if error]
		<p>
		<input type="submit" name="deletechecked" value="(language=>deletechecked)" />
		<input type="submit" name="exportchecked" value="(language=>exportchecked)" />
		<input type="submit" name="move" value="(language=>move)" />
		(language=>find): <input type="text" name="find" />
		(language=>replacewith): <input type="text" name="replacewith" />
		<input type="submit" name="replace" value="(language=>replace)" />
		</p>
		[/else code]
		<table class="list" cellpadding="0" cellspacing="0">
			<tr class="list-header">
				<td />
				<td />
				<td width="25%">
					{tie/order}[else code] | {tie/checkall} | {tie/uncheckall}[/else code]
				</td>
				<td width="75%" style="text-align: right;">
					[else code]
					<a href="(listpath)cmd=add&amp;start=(start)&amp;limit=(limit)&amp;order=(order)&amp;search=(search)[loop directories]&amp;directory[]=[directory][/loop directories]&amp;title=">(language=>add)</a> |
					<a href="(listpath)cmd=create&amp;start=(start)&amp;limit=(limit)&amp;order=(order)&amp;search=(search)[loop directories]&amp;directory[]=[directory][/loop directories]&amp;title=">(language=>createdirectory)</a>
					<input name="file" type="file" />
					<input type="submit" name="import" value="(language=>import)" />
					[/else code]
				</td>
			</tr>
			[if entries]
			[loop entries]
			<tr class="list-entry[else file]-folder[/else file]">
				<td>
					[else code]
					[if file]<div style="visibility: hidden">[/if file]<input type="radio" name="moveto" value="[title]" />[if file]</div>[/if file]
					[/else code]
				</td>
				<td>
					[else code]
					[if file]
					<input name="entry[]" type="checkbox" value="[title]"[if checked] checked[/if checked] />
					[/if file]
					[else file]
					[else show]<div style="visibility: hidden">[/else show]<input name="directoryentry[]" type="checkbox" value="[title]"[if show][if checked] checked[/if checked][/if show] />[else show]</div>[/else show]
					[/else file]
					[/else code]
				</td>
				<td>
					<span class="list-hidden">
						[if file]
						[if code]
						<a href="(listpath)cmd=view&amp;start=(start)&amp;limit=(limit)&amp;order=(order)&amp;search=(search)[loop entriesdirectories]&amp;directory[]=[directory][/loop entriesdirectories]&amp;title=[title]">(language=>view)</a>
						[/if code]
						[else code]
						<a href="(listpath)cmd=edit&amp;start=(start)&amp;limit=(limit)&amp;order=(order)&amp;search=(search)[loop entriesdirectories]&amp;directory[]=[directory][/loop entriesdirectories]&amp;title=[title]">(language=>edit)</a> |
						<a href="(listpath)cmd=delete&amp;start=(start)&amp;limit=(limit)&amp;order=(order)&amp;search=(search)[loop entriesdirectories]&amp;directory[]=[directory][/loop entriesdirectories]&amp;title[]=[title]">(language=>delete)</a> |
						<a href="(listpath)cmd=add&amp;start=(start)&amp;limit=(limit)&amp;order=(order)&amp;search=(search)[loop entriesdirectories]&amp;directory[]=[directory][/loop entriesdirectories]&amp;title=[title]">(language=>clone)</a>
						<a href="(listpath)cmd=export[loop entriesdirectories]&amp;directory[]=[directory][/loop entriesdirectories]&amp;title[]=[title]">(language=>export)</a>
						[/else code]
						[/if file]
						[else file]
						<a href="(listpath)limit=(limit)&amp;order=(order)&amp;search=(search)[loop entriesdirectories]&amp;directory[]=[directory][/loop entriesdirectories][if show]&amp;directory[]=[title][/if show]">(language=>expand)</a>
						[else code]
						[if show]
						| <a href="(listpath)cmd=rename&amp;start=(start)&amp;limit=(limit)&amp;order=(order)&amp;search=(search)[loop entriesdirectories]&amp;directory[]=[directory][/loop entriesdirectories]&amp;title=[title]">(language=>rename)</a> |
						<a href="(listpath)cmd=delete&amp;start=(start)&amp;limit=(limit)&amp;order=(order)&amp;search=(search)[loop entriesdirectories]&amp;directory[]=[directory][/loop entriesdirectories]&amp;directorytitle[]=[title]">(language=>delete)</a> |
						<a href="(listpath)cmd=copy&amp;start=(start)&amp;limit=(limit)&amp;order=(order)&amp;search=(search)[loop entriesdirectories]&amp;directory[]=[directory][/loop entriesdirectories]&amp;title=[title]">(language=>copy)</a> |
						<a href="(listpath)cmd=export[loop entriesdirectories]&amp;directory[]=[directory][/loop entriesdirectories]&amp;directorytitle[]=[title]">(language=>export)</a>
						[/if show]
						[/else code]
						[/else file]
					</span>
				</td>
				<td>
					[displaytitle]
				</td>
			</tr>
			[/loop entries]
			[/if entries]
			[else entries]
			<tr class="list-entry-folder">
				<td />
				<td />
				<td width="25%" />
				<td width="75%">
					(language=>empty)
				</td>
			</tr>
			[/else entries]
			<tr class="list-footer">
				<td />
				<td />
				<td>(language=>count): (count)</td>
				<td>(language=>pages): (link=>previous) (link=>current) (link=>next)</td>
			</tr>
		</table>
		[else code]
		</form>
		[/else code]
[section highlightstart]<strong>[/section highlightstart]
[section highlightend]</strong>[/section highlightend]
[section page] [/section page]