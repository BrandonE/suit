		<else code>
<error>
		<form action="#" method="post">
		<input type="hidden" name="oldtitle" value="<oldtitle>" />
		[inputtitle]: <input type="text" name="title" value="<title>" />
		</else code>
		<if content>
		<br />[content]: <textarea name="content" rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea"><content></textarea>
		</if content>
		<if code>
		<br />[code]: <textarea rows="40" cols="100" wrap="off" style="width: 100%;" readonly="readonly"><code></textarea>
		</if code>
		<if glue>
		<br />[content]: <input type="text" name="content" value="<content>" />
		<br />[code]: <input type="text" name="code" value="<code>" />
		</if glue>
		<else code>
		<br /><input type="submit" name="<name>" value="<value>" tabindex="0" /><if content> <input type="submit" name="escape" value="[escape]" tabindex="1" /></if content>
		</form>
		</else code>