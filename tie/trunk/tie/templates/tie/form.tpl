        [if condition="[var json='true']condition.code[/var]" else="true"]
        <form action="#" method="post">
        [if condition="[var json='true']error[/var]"]
        <p>[var]error[/var]</p>
        [/if]
        [/if]
        <p><label for="title">[var]language.inputtitle[/var]</label>: <input type="text" name="title" id="title" value="[var]title[/var]"[if condition="[var json='true']condition.code[/var]"] readonly="readonly"[/if] /></p>
        [if condition="[var json='true']condition.box[/var]"]
        [if condition="[var json='true']condition.code[/var]" else="true"]
        <p><label for="template">[var]language.templates[/var]</label>: <textarea name="template" id="template" rows="20" wrap="off" style="width: 100%;" class="textarea">
[var]template[/var]</textarea></p>
        [/if]
        [if condition="[var json='true']condition.code[/var]"]
        <p><label for="code">[var]language.code[/var]</label>: <textarea id="code" rows="40" cols="100" wrap="off" style="width: 100%;" readonly="readonly">
[var]template[/var]</textarea></p>
        [/if]
        [/if]
        [if condition="[var json='true']condition.code[/var]" else="true"]
        <p>
        <input type="submit" name="[var]name[/var]" value="[var]value[/var]" tabindex="0" />
        [if condition="[var json='true']condition.editing[/var]"]
        <input type="submit" name="editandcontinue" value="[var]language.editandcontinue[/var]" />
        [/if]
        </p>
        </form>
        [/if]