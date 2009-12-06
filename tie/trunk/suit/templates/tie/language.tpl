        <form class="languages" action="#" method="post">
        <p>
            <select name="languages_entry">
            <option value="-1">[:language=>default:]</option>
            [loop vars="[:loop=>languages:]"]
            <option value="[|id|]"[if condition="[|selected|]" trim=""] selected="selected"[/if]>[|title|]</option>
            [/loop]
            </select>
            <input type="submit" name="languages_update" value="[:language=>update:]" />
        </p>
        </form>