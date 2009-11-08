		<else code>
		<if error>
		<p><error></p>
		</if error>
		<form action="#" method="post">
		<input type="hidden" name="oldtitle" value="<oldtitle>" />
		<p>[inputtitle]: <input type="text" name="title" value="<title>" /></p>
		</else code>
		<if content>
		<p>[content]: <textarea name="content" rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea">
<content></textarea></p>
		</if content>
		<if code>
		<p>[code]: <textarea rows="40" cols="100" wrap="off" style="width: 100%;" readonly="readonly">
<code></textarea></p>
		</if code>
		<if glue>
		<p>[content]: <input type="text" name="content" value="<content>" /></p>
		<p>[code]: <input type="text" name="code" value="<code>" /></p>
		</if glue>
		<else code>
		<if content>
		<p><input type="checkbox" name="glue" /> [glue]</p>
		</if content>
		<p>
		<input type="submit" name="<name>" value="<value>" tabindex="0" />
		<if editing>
		<input type="submit" name="editandcontinue" value="[editandcontinue]" />
		</if editing>
		</p>
		</form>
		</else code>