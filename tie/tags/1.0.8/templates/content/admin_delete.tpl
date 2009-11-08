<error>
		<form action="#" method="post">
		<loop input>
		<input type="hidden" name="title{openingbracket}{closingbracket}" value="<title>" />
		</loop input>
		<if content>
		<p><input type="checkbox" name="glue" /> [glue]</p>
		</if content>
		<input type="submit" name="<name>" value="[delete]" />
		</form>