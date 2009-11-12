        [else code]
        <form action="#" method="post">
        [if error]
        <p>[:error:]</p>
        [/if error]
        [/else code]
        <p>[:language=>inputtitle:]: <input type="text" name="title" value="[:title:]"[if code]readonly="readonly"[/if code] /></p>
        [if box]
        [if templates]
        <p>[:language=>templates:]: <textarea name="template" rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea">
[:template:]</textarea></p>
        [/if templates]
        [if code]
        <p>[:language=>code:]: <textarea rows="40" cols="100" wrap="off" style="width: 100%;" readonly="readonly">
[:template:]</textarea></p>
        [/if code]
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