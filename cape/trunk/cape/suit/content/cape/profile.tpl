(message)
<form action="#" method="post">
<table width="100%" cellspacing="10">
	<tr style="vertical-align: top">
		<td width="50%">
			<table class="list" width="100%" cellspacing="0">
				<tr class="list-entry">
					<td colspan="2"><center>(language=>activestats)</center></td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>posts)</td>
					<td width="50%">(posts) \((postpercent)\)</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>postsperday)</td>
					<td width="50%">(postsperday)</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>joined)</td>
					<td width="50%">(joined)</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>lastactivity)</td>
					<td width="50%">(lastactivity)</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>userslocaltime)</td>
					<td width="50%">(localtime)</td>
				</tr>
			</table>
		</td>
		<td width="50%">
			<table class="list" width="100%" cellspacing="0">
				<tr class="list-entry">
					<td colspan="2"><center>(language=>communicate)</center></td>
				</tr>
				/*<tr class="list-entry">
					<td width="50%">(language=>email)</td>
					<td width="50%">Coming Soon...</td>
				</tr>*/
				<tr class="list-entry">
					<td width="50%">(language=>aim)</td>
					<td width="50%">[if edit]<input type="hidden" id="aimtemp" value="(aimedit)" /><span class="yesscripthide aim1"><input type="text" id="aim" name="aim" value="(aimedit)" /></span><span class="yesscript aim0" style="display: none;">[/if edit](aim)[if edit]</span> <span class="list-hidden"><a class="yesscript aim0" id="aim0" href="#NULL" style="display: none;">(language=>edit)</a><a class="aim1" id="aim1" href="#NULL" style="display: none;">(language=>cancel)</a></span>[/if edit]</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>icq)</td>
					<td width="50%">[if edit]<input type="hidden" id="icqtemp" value="(icqedit)" /><span class="yesscripthide icq1"><input type="text" id="icq" name="icq" value="(icqedit)" /></span><span class="yesscript icq0" style="display: none;">[/if edit](icq)[if edit]</span> <span class="list-hidden"><a class="yesscript icq0" id="icq0" href="#NULL" style="display: none;">(language=>edit)</a><a class="icq1" id="icq1" href="#NULL" style="display: none;">(language=>cancel)</a></span>[/if edit]</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>yahoo)</td>
					<td width="50%">[if edit]<input type="hidden" id="yahootemp" value="(yahooedit)" /><span class="yesscripthide yahoo1"><input type="text" id="yahoo" name="yahoo" value="(yahooedit)" /></span><span class="yesscript yahoo0" style="display: none;">[/if edit](yahoo)[if edit]</span> <span class="list-hidden"><a class="yesscript yahoo0" id="yahoo0" href="#NULL" style="display: none;">(language=>edit)</a><a class="yahoo1" id="yahoo1" href="#NULL" style="display: none;">(language=>cancel)</a></span>[/if edit]</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>msn)</td>
					<td width="50%">[if edit]<input type="hidden" id="msntemp" value="(msnedit)" /><span class="yesscripthide msn1"><input type="text" id="msn" name="msn" value="(msnedit)" /></span><span class="yesscript msn0" style="display: none;">[/if edit](msn)[if edit]</span> <span class="list-hidden"><a class="yesscript msn0" id="msn0" href="#NULL" style="display: none;">(language=>edit)</a><a class="msn1" id="msn1" href="#NULL" style="display: none;">(language=>cancel)</a></span>[/if edit]</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="vertical-align: top">
		<td width="50%">
			<table class="list" width="100%" cellspacing="0">
				<tr class="list-entry">
					<td colspan="2"><center>(language=>information)</center></td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>homepage)</td>
					<td width="50%">[if edit]<input type="hidden" id="homepagetemp" value="(homepageedit)" /><span class="yesscripthide homepage1"><input type="text" id="homepage" name="homepage" value="(homepageedit)" /></span><span class="yesscript homepage0" style="display: none;">[/if edit][if homepage]<a href="(homepage)" target="_blank">[/if homepage](homepage)[if homepage]</a>[/if homepage][if edit]</span> <span class="list-hidden"><a class="yesscript homepage0" id="homepage0" href="#NULL" style="display: none;">(language=>edit)</a><a class="homepage1" id="homepage1" href="#NULL" style="display: none;">(language=>cancel)</a></span>[/if edit]</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>birthday)</td>
					<td width="50%">
						[if edit]
						<input type="hidden" id="monthtemp" value="(month)" />
						<input type="hidden" id="daytemp" value="(day)" />
						<input type="hidden" id="yeartemp" value="(year)" />
						<span class="yesscripthide birthday1">
						<select id="month" name="month">
							<option value="">---</option>
							[loop month]
							<option value="[id]"[if selected] selected[/if selected]>[title]</option>
							[/loop month]
						</select>
						<select id="day" name="day">
							<option value="">---</option>
							[loop day]
							<option value="[id]"[if selected] selected[/if selected]>[id]</option>
							[/loop day]
						</select>
						<select id="year" name="year">
							<option value="">---</option>
							[loop year]
							<option value="[id]"[if selected] selected[/if selected]>[id]</option>
							[/loop year]
						</select>
						</span>
						<span class="yesscript birthday0" style="display: none;">
						[/if edit]
						(birthday) \((age)\)
						[if edit]
						</span>
						<span class="list-hidden"><a class="yesscript birthday0" id="birthday0" href="#NULL" style="display: none;">(language=>edit)</a><a class="birthday1" id="birthday1" href="#NULL" style="display: none;">(language=>cancel)</a></span>
						[/if edit]
					</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>location)</td>
					<td width="50%">[if edit]<input type="hidden" id="locationtemp" value="(locationedit)" /><span class="yesscripthide location1"><input type="text" id="location" name="location" value="(locationedit)" /></span><span class="yesscript location0" style="display: none;">[/if edit](location)[if edit]</span> <span class="list-hidden"><a class="yesscript location0" id="location0" href="#NULL" style="display: none;">(language=>edit)</a><a class="location1" id="location1" href="#NULL" style="display: none;">(language=>cancel)</a></span>[/if edit]</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>interests)</td>
					<td width="50%">[if edit]<input type="hidden" id="intereststemp" value="(interestsedit)" /><span class="yesscripthide interests1"><textarea id="interests" name="interests">
(interestsedit)</textarea></span><span class="yesscript interests0" style="display: none;">[/if edit](interests)[if edit]</span> <span class="list-hidden"><a class="yesscript interests0" id="interests0" href="#NULL" style="display: none;">(language=>edit)</a><a class="interests1" id="interests1" href="#NULL" style="display: none;">(language=>cancel)</a></span>[/if edit]</td>
				</tr>
			</table>
		</td>
		<td width="50%">
			<table class="list" width="100%" cellspacing="0">
				<tr class="list-entry">
					<td colspan="2"><center>(language=>postingdetails)</center></td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>group)</td>
					<td width="50%">
						[if admin]
						<input type="hidden" id="grouptemp" value="(groupedit)" />
						<span class="yesscripthide group1">
						<select id="group" name="group">
							<option value="0">(language=>default)</option>
							[loop group]
							<option value="[id]"[if selected] selected[/if selected]>[title]</option>
							[/loop group]
						</select>
						</span>
						<span class="yesscript group0" style="display: none;">
						[/if admin]
						(group)
						[if admin]
						</span>
						<span class="list-hidden"><a class="yesscript group0" id="group0" href="#NULL" style="display: none;">(language=>edit)</a><a class="group1" id="group1" href="#NULL" style="display: none;">(language=>cancel)</a></span>
						[/if admin]
					</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>inputtitle)</td>
					<td width="50%">[if edit]<input type="hidden" id="titletemp" value="(titleedit)" /><span class="yesscripthide title1"><input type="text" id="title" name="title" value="(titleedit)" /></span><span class="yesscript title0" style="display: none;">[/if edit](title)[if edit]</span> <span class="list-hidden"><a class="yesscript title0" id="title0" href="#NULL" style="display: none;">(language=>edit)</a><a class="title1" id="title1" href="#NULL" style="display: none;">(language=>cancel)</a></span>[/if edit]</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>avatar)</td>
					<td width="50%">[if edit]<input type="hidden" id="avatartemp" value="(avatar)" /><span class="yesscripthide avatar1"><input type="text" id="avatar" name="avatar" value="(avatar)" /></span><span class="yesscript avatar0" style="display: none;">[/if edit]<img src="(avatar)" alt="(username)" />[if edit]</span> <span class="list-hidden"><a class="yesscript avatar0" id="avatar0" href="#NULL" style="display: none;">(language=>edit)</a><a class="avatar1" id="avatar1" href="#NULL" style="display: none;">(language=>cancel)</a></span>[/if edit]</td>
				</tr>
				<tr class="list-entry">
					<td width="50%">(language=>signature)</td>
					<td width="50%">[if edit]<input type="hidden" id="signaturetemp" value="(signatureedit)" /><span class="yesscripthide signature1"><textarea id="signature" name="signature">
(signatureedit)</textarea></span><span class="yesscript signature0" style="display: none;">[/if edit](signature)[if edit]</span> <span class="list-hidden"><a class="yesscript signature0" id="signature0" href="#NULL" style="display: none;">(language=>edit)</a><a class="signature1" id="signature1" href="#NULL" style="display: none;">(language=>cancel)</a></span>[/if edit]</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
[if edit]
<table class="list" width="100%" cellspacing="0">
	<tr class="list-entry">
		<td colspan="2"><center>(language=>settings)</center></td>
	</tr>
	[if admin]
	<tr class="list-entry">
		<td width="50%">(language=>username)</td>
		<td width="50%"><input type="text" name="username" value="(usernameedit)" /></td>
	</tr>
	[/if admin]
	<tr class="list-entry">
		<td width="50%">(language=>password)</td>
		<td width="50%"><input type="password" name="password" /></td>
	</tr>
	<tr class="list-entry">
		<td width="50%">(language=>email)</td>
		<td width="50%"><input type="text" name="email" /></td>
	</tr>
	<tr class="list-entry">
		<td width="50%">(language=>timezone):</td>
		<td width="50%">
			<select name="timezone">
				<option value="">(language=>default)</option>
				[loop zones]
				<option value="[value]"[if selected] selected="true"[/if selected]>[label]</option>
				[/loop zones]
			</select>
		</td>
	</tr>
</table>
<center><input type="submit" name="profile" value="(language=>update)" /></center>[/if edit]
</form>