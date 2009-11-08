{navigation_limit}
{navigation_search}
		<form enctype="multipart/form-data" action="#" method="post">
		<error>
		<p style="text-align: center;">
		<input type="submit" name="deleteselected" value="[deleteselected]" />
		<input type="submit" name="exportselected" value="[exportselected]" />
		<input name="file" type="file" />
		<input type="checkbox" name="overwrite" /> Overwrite
		<input type="submit" name="import" value="[import]" />
		</p>
		<table class="list" cellpadding="0" cellspacing="0">
			<tr class="list-header">
				<td width="25%">{navigation_orderby} {navigation_selectall} {navigation_deselectall}</td>
				<td width="75%" style="text-align: right;"><a href="<path>cmd=add&amp;start=<start>&amp;limit=<limit>&amp;orderby=<orderby>&amp;search=<search>&amp;template=">[add]</a></td>
			</tr>
<entries>
			<tr class="list-footer">
				<td width="25%">[count]: <count></td>
				<td width="75%">[pages]: <First> <1> <2> <3> <4> <5> <Last></td>
			</tr>
		</table>
		</form>