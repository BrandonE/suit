		<table class="dashboard">
			<tr>
				<th>[dashboardinfo]</th>
			</tr>
			<tr>
				<td class="category">[servertype]</td>
				<td><servertype></td>
				<td class="category">[phpversion]</td>
				<td><phpversion></td>
				<td class="category">[currenttieversion]</td>
				<td><if currenttieversion><strong style="color: red;"></if currenttieversion><currenttieversion><if currenttieversion></strong></if currenttieversion></td>
				<td class="category">[latesttieversion]</td>
				<td><latesttieversion></td>
			</tr>
			<tr>
				<td class="category">[magicquotesgpc]</td>
				<td><if magicquotesgpc><strong style="color: red;">[on]</strong></if magicquotesgpc><else magicquotesgpc>[off]</else magicquotesgpc></td>
				<td class="category">[magicquotesruntime]</td>
				<td><if magicquotesruntime><strong style="color: red;">[on]</strong></if magicquotesruntime><else magicquotesruntime>[off]</else magicquotesruntime></td>
				<td class="category">[magicquotessybase]</td>
				<td><if magicquotessybase><strong style="color: red;">[on]</strong></if magicquotessybase><else magicquotessybase>[off]</else magicquotessybase></td>
				<td class="category">[registerglobals]</td>
				<td><if registerglobals><strong style="color: red;">[on]</strong></if registerglobals><else registerglobals>[off]</else registerglobals></td>
			</tr>
			<tr>
				<td class="category">[fileuploads]</td>
				<td><if fileuploads>[on]</if fileuploads><else fileuploads>[off]</else fileuploads></td>
				<td class="category">[uploadmaxfilesize]</td>
				<td><upload_max_filesize></td>
				<td class="category">[postmaxsize]</td>
				<td><post_max_size></td>
				<td class="category">[simplexmlinstalled]</td>
				<td><if simplexmlinstalled>[on]</if simplexmlinstalled><else simplexmlinstalled><strong style="color: red;">[off]</strong></else simplexmlinstalled></td>
			</tr>
		</table>