		<if error>
		<p><error></p>
		</if error>
		<p><message></p>
		<form action="#" method="post">
		<loop input>
		<section escape><input type="hidden" name="title[]" value="<title>" /></section escape>
		</loop input>
		<if content>
		<p><input type="checkbox" name="glue" /> [glue]</p>
		</if content>
		<input type="submit" name="<name>" value="[delete]" />
		</form>
<section delimeter>, </section delimeter>