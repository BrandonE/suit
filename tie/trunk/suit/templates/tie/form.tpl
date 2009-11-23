        [else code]
        <form action="#" method="post">
        [if error]
        <p>[:error:]</p>
        [/if error]
        [/else code]
        <p><label for="title">[:language=>inputtitle:]</label>: <input type="text" name="title" id="title" value="[:title:]"[if code]readonly="readonly"[/if code] /></p>
        [if box]
        [if templates]
        <p><label for="template">[:language=>templates:]</label>: <textarea name="template" id="template" rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea">
[:template:]</textarea></p>
        [/if templates]
        [if code]
        <p><label for="code">[:language=>code:]</label>: <textarea id="code" rows="40" cols="100" wrap="off" style="width: 100%;" readonly="readonly">
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