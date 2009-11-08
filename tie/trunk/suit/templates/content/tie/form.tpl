        [else code]
        <form action="#" method="post">
        [if box]
        [if glue]
        <fieldset>
            <legend>[:language=>options:]</legend>
        <p>
        [:language=>codeboxes:]: <input type="text" name="boxes" value="[:boxes:]" />
        <input type="submit" name="boxes_submit" value="[:language=>display:]" />
        </p>
        </fieldset>
        [/if glue]
        [/if box]
        [if error]
        <p>[:error:]</p>
        [/if error]
        [/else code]
        <p>[:language=>inputtitle:]: <input type="text" name="title" value="[:title:]"[if code]readonly="readonly"[/if code] /></p>
        [if box]
        [if content]
        <p>[:language=>content:]: <textarea name="content" rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea">
[:content:]</textarea></p>
        [/if content]
        [if code]
        <p>[:language=>code:]: <textarea rows="40" cols="100" wrap="off" style="width: 100%;" readonly="readonly">
[:content:]</textarea></p>
        [/if code]
        [if glue]
        <p>[:language=>content:]: <input type="text" name="content" value="[:content:]" /></p>
        [loop code]
        <p>[:language=>code:] [|number|]: <input type="text" name="code[]" value="[|code|]" /></p>
        [/loop code]
        [/if glue]
        [/if box]
        [else code]
        <p>
        <input type="submit" name="[:name:]" value="[:value:]" tabindex="0" />
        [if editing]
        <input type="submit" name="editandcontinue" value="[:language=>editandcontinue:]" />
        [/if editing]
        </p>
        </form>
        [/else code]