        [if condition="[var json='true']error[/var]"]
        <p>[var]error[/var]</p>
        [/if]
        <p>[execute][var]message[/var][/execute]</p>
        <form action="#" method="post">
        <input type="submit" name="[var]name[/var]" value="[var]value[/var]" />
        </form>