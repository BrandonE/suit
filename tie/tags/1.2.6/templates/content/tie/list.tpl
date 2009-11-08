		<fieldset>
			<legend>[options]</legend>
{navigation/limit}
{navigation/search}
		</fieldset>
		<else code>
		<form enctype="multipart/form-data" action="#" method="post">
		<if error>
		<p><error></p>
		</if error>
		<table cellpadding="0" cellspacing="0" width="100%" style="margin: 0 0 8px;">
			<tr>
				<td>
					<input type="submit" name="deletechecked" value="[deletechecked]" />
					<input type="submit" name="exportchecked" value="[exportchecked]" />
					<input type="submit" name="move" value="[move]" />
				</td>
				<td style="text-align: right;">
					<input name="file" type="file" />
					<input type="submit" name="import" value="[import]" />
				</td>
			</tr>
		</table>
		</else code>
		<table class="list" cellpadding="0" cellspacing="0">
			<tr class="list-header">
				<td>
				<td>
				<td width="25%">
					{navigation/order}<else code> {navigation/checkall} {navigation/uncheckall}</else code>
				</td>
				<td width="70%" style="text-align: right;">
					<else code>
					<a href="<path>cmd=add&amp;start=<start>&amp;limit=<limit>&amp;order=<order>&amp;search=<search><loop directories>&amp;directory\[]=<directory></loop directories>&amp;title=">[add]</a>
					<a href="<path>cmd=create&amp;start=<start>&amp;limit=<limit>&amp;order=<order>&amp;search=<search><loop directories>&amp;directory\[]=<directory></loop directories>&amp;title=">[createdirectory]</a>
					</else code>
				</td>
			</tr>
			<loop entries>
			<tr class="list-entry<else file>-folder</else file>">
				<td>
					<else entries_code>
					<if file><div style="visibility: hidden"></if file><input type="radio" name="moveto" value="<title>" /><if file></div></if file>
					</else entries_code>
				</td>
				<td>
					<else entries_code>
					<if file>
					<input name="entry\[]" type="checkbox" value="<title>"<if entries_checked> checked</if entries_checked> />
					</if file>
					<else file>
					<else show><div style="visibility: hidden"></else show><input name="directoryentry\[]" type="checkbox" value="<title>"<if entries_checked> checked</if entries_checked> /><else show></div></else show>
					</else file>
					</else entries_code>
				</td>
				<td>
					<if entries_code>
					<if file>
					<a href="<path>cmd=view&amp;start=<start>&amp;limit=<limit>&amp;order=<order>&amp;search=<search><loop directories>&amp;directory\[]=<directory></loop directories>&amp;title=<title>">[view]</a>
					</if file>
					</if entries_code>
					<else file>
					<a href="<path>limit=<limit>&amp;order=<order>&amp;search=<search><loop directories>&amp;directory\[]=<directory></loop directories><if show>&amp;directory\[]=<title></if show>">[expand]</a>
					</else file>
					<else entries_code>
					<if file>
					<a href="<path>cmd=edit&amp;start=<start>&amp;limit=<limit>&amp;order=<order>&amp;search=<search><loop directories>&amp;directory\[]=<directory></loop directories>&amp;title=<title>">[edit]</a>
					<a href="<path>cmd=delete&amp;start=<start>&amp;limit=<limit>&amp;order=<order>&amp;search=<search><loop directories>&amp;directory\[]=<directory></loop directories>&amp;title\[]=<title>">[delete]</a>
					<a href="<path>cmd=add&amp;start=<start>&amp;limit=<limit>&amp;order=<order>&amp;search=<search><loop directories>&amp;directory\[]=<directory></loop directories>&amp;title=<title>">[clone]</a>
					<a href="<path>cmd=export<loop directories>&amp;directory\[]=<directory></loop directories>&amp;title\[]=<title>">[export]</a>
					</if file>
					<else file>
					<if show>
					<a href="<path>cmd=rename&amp;start=<start>&amp;limit=<limit>&amp;order=<order>&amp;search=<search><loop directories>&amp;directory\[]=<directory></loop directories>&amp;title=<title>">[rename]</a>
					<a href="<path>cmd=delete&amp;start=<start>&amp;limit=<limit>&amp;order=<order>&amp;search=<search><loop directories>&amp;directory\[]=<directory></loop directories>&amp;directorytitle\[]=<title>">[delete]</a>
					<a href="<path>cmd=copy&amp;start=<start>&amp;limit=<limit>&amp;order=<order>&amp;search=<search><loop directories>&amp;directory\[]=<directory></loop directories>&amp;title=<title>">[copy]</a>
					<a href="<path>cmd=export<loop directories>&amp;directory\[]=<directory></loop directories>&amp;directorytitle\[]=<title>">[export]</a>
					</if show>
					</else file>
					</else entries_code>
				</td>
				<td>
					<displaytitle>
				</td>
			</tr>
			</loop entries>
			<tr class="list-footer">
				<td />
				<td />
				<td>[count]: <count></td>
				<td>[pages]: <previous> <current> <next></td>
			</tr>
		</table>
		<else code>
		</form>
		</else code>
<section highlightstart><strong></section highlightstart>
<section highlightend></strong></section highlightend>
<section page> </section page>