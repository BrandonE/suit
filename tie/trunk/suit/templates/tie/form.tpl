        [if condition="[:condition=>code:]" else="true"]
        <form action="#" method="post">
        [if condition="[:condition=>error:]"]
        <p>[:error:]</p>
        [/if]
        [/if]
        <p><label for="title">[:language=>inputtitle:]</label>: <input type="text" name="title" id="title" value="[:title:]"[if condition="[:condition=>code:]" trim=""] readonly="readonly"[/if] /></p>
        [if condition="[:condition=>box:]"]
        [if condition="[:condition=>code:]" else="true"]
        <p><label for="template">[:language=>templates:]</label>: <textarea name="template" id="template" rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea">
[:template:]</textarea></p>
        [/if]
        [if condition="[:condition=>code:]"]
        <p><label for="code">[:language=>code:]</label>: <textarea id="code" rows="40" cols="100" wrap="off" style="width: 100%;" readonly="readonly">
[:template:]</textarea></p>
        [/if]
        [/if]
        [if condition="[:condition=>code:]" else="true"]
        <p>
        <input type="submit" name="[:name:]" value="[:value:]" tabindex="0" />
        [if condition="[:condition=>editing:]"]
        <input type="submit" name="editandcontinue" value="[:language=>editandcontinue:]" />
        [/if]
        </p>
        </form>
        [/if]