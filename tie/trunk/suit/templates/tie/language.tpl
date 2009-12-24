        <form class="languages" action="#" method="post">
        <p>
            <select name="languages_entry">
            <option value="-1">[var]language=>default[/var]</option>
            [loop vars="[var serialize=\"true\"]loop=>languages[/var]"]
            <option value="[loopvar]id[/loopvar]"[if condition="[loopvar]selected[/loopvar]" trim="false"] selected="selected"[/if]>[loopvar]title[/loopvar]</option>
            [/loop]
            </select>
            <input type="submit" name="languages_update" value="[var]language=>update[/var]" />
        </p>
        </form>