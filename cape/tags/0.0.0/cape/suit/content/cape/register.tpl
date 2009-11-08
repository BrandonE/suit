(message)
		<form action="#" method="post">
		<table>
			<tr>
				<td>*(language=>username):</td>
				<td><input type="text" name="username" value="(username)" /></td>
			</tr>
			<tr>
				<td>*(language=>password):</td>
				<td><input type="password" name="password" /></td>
			</tr>
			<tr>
				<td>*(language=>email):</td>
				<td><input type="text" name="email" value="(email)" /></td>
			</tr>
			<tr>
				<td>(language=>timezone):</td>
				<td>
					<select name="timezone">
						<option value="">(language=>default)</option>
						[loop zones]
						<option value="[value]"[if selected] selected="true"[/if selected]>[label]</option>
						[/loop zones]
					</select>
				</td>
			</tr>
			<tr>
				<td>(language=>homepage):</td>
				<td><input type="text" name="homepage" value="(homepage)" /></td>
			</tr>
			<tr>
				<td>(language=>birthday):</td>
				<td>
					<select name="month">
						<option value="">---</option>
						[loop month]
						<option value="[id]"[if selected] selected[/if selected]>[title]</option>
						[/loop month]
					</select>
					<select name="day">
						<option value="">---</option>
						[loop day]
						<option value="[id]"[if selected] selected[/if selected]>[id]</option>
						[/loop day]
					</select>
					<select name="year">
						<option value="">---</option>
						[loop year]
						<option value="[id]"[if selected] selected[/if selected]>[id]</option>
						[/loop year]
					</select>
				</td>
			</tr>
			<tr>
				<td>(language=>location):</td>
				<td><input type="text" name="location" value="(location)" /></td>
			</tr>
			<tr>
				<td>(language=>interests):</td>
				<td><textarea name="interests">
(interests)</textarea></td>
			</tr>
			<tr>
				<td>(language=>inputtitle):</td>
				<td><input type="text" name="title" value="(title)" /></td>
			</tr>
			<tr>
				<td>(language=>avatar):</td>
				<td><input type="text" name="avatar" value="(avatar)" /></td>
			</tr>
			<tr>
				<td>(language=>signature):</td>
				<td><textarea name="signature">
(signature)</textarea></td>
			</tr>
			<tr>
				<td>(language=>aim):</td>
				<td><input type="text" name="aim" value="(aim)" /></td>
			</tr>
			<tr>
				<td>(language=>icq):</td>
				<td><input type="text" name="icq" value="(icq)" /></td>
			</tr>
			<tr>
				<td>(language=>yahoo):</td>
				<td><input type="text" name="yahoo" value="(yahoo)" /></td>
			</tr>
			<tr>
				<td>(language=>msn):</td>
				<td><input type="text" name="msn" value="(msn)" /></td>
			</tr>
			<tr>
				<td>*(language=>recaptcha):</td>
				<td>
					{cape/recaptcha}
				</td>
			</tr>
		</table>
		<input type="submit" name="register" value="Register" />
		</form>