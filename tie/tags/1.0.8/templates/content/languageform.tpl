				<form class="languages" action="#" method="post">
				<p>
				<select name="languages_entry">
				<option value="-1">Default</option>
				<loop languages>
				<option value="<id>"<if <id>> selected</if <id>>><title></option>
				</loop languages>
				</select>
				<input type="submit" name="languages_update" value="[update]" class="btnUpdate" />
				</p>
				</form>