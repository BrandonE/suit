		<else code>
		<form action="#" method="post">
		<if glue>
		<fieldset>
			<legend>[options]</legend>
		<p>
		[codeboxes]: <input type="text" name="boxes" value="<boxes>" />
		<input type="submit" name="boxes_submit" value="[display]" />
		</p>
		</fieldset>
		</if glue>
		<if error>
		<p><error></p>
		</if error>
		</else code>
		<p>[inputtitle]: <input type="text" name="title" value="<title>"<if code>readonly="readonly"</if code> /></p>
		<if content>
		<p>[content]: <textarea name="content" rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea">
<content></textarea></p>
		</if content>
		<if code>
		<p>[code]: <textarea rows="40" cols="100" wrap="off" style="width: 100%;" readonly="readonly">
<content></textarea></p>
		</if code>
		<if glue>
		<p>[content]: <input type="text" name="content" value="<content>" /></p>
		<loop code>
		<p>[code] <number>: <input type="text" name="code\[]" value="<code>" /></p>
		</loop code>
		</if glue>
		<else code>
		<p>
		<input type="submit" name="<name>" value="<value>" tabindex="0" />
		<if editing>
		<input type="submit" name="editandcontinue" value="[editandcontinue]" />
		</if editing>
		</p>
		</form>
		</else code>