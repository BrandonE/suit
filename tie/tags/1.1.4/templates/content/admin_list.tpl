{navigation_limit}
{navigation_search}
		<else code>
		<form enctype="multipart/form-data" action="#" method="post">
		<if error>
		<center><p><error></p></center>
		</if error>
		<p style="text-align: center;">
		<input type="submit" name="deleteselected" value="[deleteselected]" />
		<input type="submit" name="exportselected" value="[exportselected]" />
		<input name="file" type="file" />
		<if content>
		<input type="checkbox" name="glue" /> [glue]
		</if content>
		<input type="checkbox" name="overwrite" /> [overwrite]
		<input type="submit" name="import" value="[import]" />
		</p>
		</else code>
		<table class="list" cellpadding="0" cellspacing="0">
			<else code>
			<tr class="list-header">
				<td width="25%">{navigation_orderby} {navigation_selectall} {navigation_deselectall}</td>
				<td width="75%" style="text-align: right;"><a href="<path>cmd=add&amp;start=<start>&amp;limit=<limit>&amp;orderby=<orderby>&amp;search=<search>&amp;file=">[add]</a></td>
			</tr>
			</else code>
			<loop entries>
			<tr class="list-entry">
				<td width="25%">
					<if code>
					<a href="<path>cmd=view&amp;start=<start>&amp;limit=<limit>&amp;orderby=<orderby>&amp;search=<search>&amp;file=<title>">[view]</a>
					</if code>
					<else code>
					<input name="entry{openingbracket}{closingbracket}" type="checkbox" value="<title>"<if checked> checked</if checked> /> <a href="<path>cmd=edit&amp;start=<start>&amp;limit=<limit>&amp;orderby=<orderby>&amp;search=<search>&amp;file=<title>">[edit]</a> <a href="<path>cmd=delete&amp;start=<start>&amp;limit=<limit>&amp;orderby=<orderby>&amp;search=<search>&amp;file=<title>">[delete]</a> <a href="<path>cmd=add&amp;start=<start>&amp;limit=<limit>&amp;orderby=<orderby>&amp;search=<search>&amp;file=<title>">[clone]</a> <a href="<path>cmd=export&amp;file=<title>">[export]</a>
					</else code>
				</td>
				<td width="75%"><displaytitle></td>
			</tr>
			</loop entries>
			<tr class="list-footer">
				<td width="25%">[count]: <count></td>
				<td width="75%">[pages]: <previous> <current> <next></td>
			</tr>
		</table>
		<else code>
		</form>
		</else code>