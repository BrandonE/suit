        <fieldset>
            <legend>[var]language=>options[/var]</legend>
[code]code/tie/list.inc.php[/code]
[execute][template]templates/tie/list.tpl[/template][/execute]
[code]code/tie/search.inc.php[/code]
[execute][template]templates/tie/search.tpl[/template][/execute]
        </fieldset>
        [if condition="[var json='true']condition=>code[/var]" else="true"]
        <form enctype="multipart/form-data" action="#" method="post">
        [if condition="[var json='true']error[/var]"]
        <p>[var]error[/var]</p>
        [/if]
        <p>
            <input type="submit" name="deletechecked" value="[var]language=>deletechecked[/var]" />
            <input type="submit" name="exportchecked" value="[var]language=>exportchecked[/var]" />
            <input type="submit" name="move" value="[var]language=>move[/var]" />
            <label for="find">[var]language=>find[/var]</label>: <input type="text" name="find" id="find" />
            <label for="replacewith">[var]language=>replacewith[/var]</label>: <input type="text" name="replacewith" id="replacewith" />
            <input type="submit" name="replace" value="[var]language=>replace[/var]" />
        </p>
        [/if]
        <table class="list" cellpadding="0" cellspacing="0">
            <tr class="list-header">
                <td />
                <td />
                <td width="25%">
                    [code]code/tie/order.inc.php[/code]
                    [code]code/tie/all.inc.php[/code]
                    [execute][template]templates/tie/order.tpl[/template][/execute][if condition="[var json='true']condition=>code[/var]" else="true"] | [execute][template]templates/tie/checkall.tpl[/template][/execute] | [execute][template]templates/tie/uncheckall.tpl[/template][/execute][/if]
                </td>
                <td width="75%" style="text-align: right;">
                    [if condition="[var json='true']condition=>code[/var]" else="true"]
                    <a href="[var]path=>url[/var][var]path=>urlquerychar[/var]cmd=add&amp;start=[var]start[/var]&amp;list=[var]list[/var]&amp;order=[var]order[/var]&amp;search=[var]search[/var][loop vars="[var json='true']loop=>directories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop]&amp;title=">[var]language=>add[/var]</a> |
                    <a href="[var]path=>url[/var][var]path=>urlquerychar[/var]cmd=create&amp;start=[var]start[/var]&amp;list=[var]list[/var]&amp;order=[var]order[/var]&amp;search=[var]search[/var][loop vars="[var json='true']loop=>directories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop]&amp;title=">[var]language=>createdirectory[/var]</a>
                    <input name="file" type="file" />
                    <input type="submit" name="import" value="[var]language=>import[/var]" />
                    [/if]
                </td>
            </tr>
            [if condition="[var json='true']condition=>entries[/var]"]
            [loop vars="[var json='true']loop=>entries[/var]"]
            <tr class="list-entry[if condition="[loopvar json='true']file[/loopvar]" else="true"]-folder[/if]">
                <td>
                    [if condition="[var json='true']condition=>code[/var]" else="true"]
                    [if condition="[loopvar json='true']file[/loopvar]"]<div style="visibility: hidden">[/if]<input type="radio" name="moveto" value="[loopvar]title[/loopvar]" />[if condition="[loopvar json='true']file[/loopvar]"]</div>[/if]
                    [/if]
                </td>
                <td>
                    [if condition="[var json='true']condition=>code[/var]" else="true"]
                    [if condition="[loopvar json='true']file[/loopvar]"]
                    <input name="entry[]" id="[loopvar]title[/loopvar]" type="checkbox" value="[loopvar]title[/loopvar]"[if condition="[var json='true']condition=>checked[/var]"] checked="checked"[/if] />
                    [/if]
                    [if condition="[loopvar json='true']file[/loopvar]" else="true"]
                    [if condition="[loopvar json='true']up[/loopvar]"]<div style="visibility: hidden">[/if]<input name="directoryentry[]" id="directory[loopvar]title[/loopvar]" type="checkbox" value="[loopvar]title[/loopvar]"[if condition="[var json='true']condition=>checked[/var]"][if condition="[loopvar json='true']up[/loopvar]" else="true"] checked="checked"[/if][/if] />[if condition="[loopvar json='true']up[/loopvar]"]</div>[/if]
                    [/if]
                    [/if]
                </td>
                <td>
                    <span class="list-hidden">
                        [if condition="[loopvar json='true']file[/loopvar]"]
                        [if condition="[var json='true']condition=>code[/var]"]
                        <a href="[var]path=>url[/var][var]path=>urlquerychar[/var]cmd=view&amp;start=[var]start[/var]&amp;list=[var]list[/var]&amp;order=[var]order[/var]&amp;search=[var]search[/var][loop vars="[var json='true']loop=>directories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop]&amp;title=[loopvar]title[/loopvar]">[var]language=>view[/var]</a>
                        [/if]
                        [if condition="[var json='true']condition=>code[/var]" else="true"]
                        <a href="[var]path=>url[/var][var]path=>urlquerychar[/var]cmd=edit&amp;start=[var]start[/var]&amp;list=[var]list[/var]&amp;order=[var]order[/var]&amp;search=[var]search[/var][loop vars="[var json='true']loop=>directories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop]&amp;title=[loopvar]title[/loopvar]">[var]language=>edit[/var]</a> |
                        <a href="[var]path=>url[/var][var]path=>urlquerychar[/var]cmd=delete&amp;start=[var]start[/var]&amp;list=[var]list[/var]&amp;order=[var]order[/var]&amp;search=[var]search[/var][loop vars="[var json='true']loop=>directories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop]&amp;title[]=[loopvar]title[/loopvar]">[var]language=>delete[/var]</a> |
                        <a href="[var]path=>url[/var][var]path=>urlquerychar[/var]cmd=add&amp;start=[var]start[/var]&amp;list=[var]list[/var]&amp;order=[var]order[/var]&amp;search=[var]search[/var][loop vars="[var json='true']loop=>directories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop]&amp;title=[loopvar]title[/loopvar]">[var]language=>clone[/var]</a> |
                        <a href="[var]path=>url[/var][var]path=>urlquerychar[/var]cmd=export[loop vars="[var json='true']loop=>directories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop]&amp;title[]=[loopvar]title[/loopvar]">[var]language=>export[/var]</a>
                        [/if]
                        [/if]
                        [if condition="[loopvar json='true']file[/loopvar]" else="true"]
                        <a href="[var]path=>url[/var][var]path=>urlquerychar[/var]list=[var]list[/var]&amp;order=[var]order[/var]&amp;search=[var]search[/var][if condition="[loopvar json='true']up[/loopvar]"][loop vars="[var json='true']loop=>updirectories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop][/if][if condition="[loopvar json='true']up[/loopvar]" else="true"][loop vars="[var json='true']loop=>directories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop]&amp;directory[]=[loopvar]title[/loopvar][/if]">[var]language=>expand[/var]</a>
                        [if condition="[var json='true']condition=>code[/var]" else="true"]
                        [if condition="[loopvar json='true']up[/loopvar]" else="true"]
                        | <a href="[var]path=>url[/var][var]path=>urlquerychar[/var]cmd=rename&amp;start=[var]start[/var]&amp;list=[var]list[/var]&amp;order=[var]order[/var]&amp;search=[var]search[/var][loop vars="[var json='true']loop=>directories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop]&amp;title=[loopvar]title[/loopvar]">[var]language=>rename[/var]</a> |
                        <a href="[var]path=>url[/var][var]path=>urlquerychar[/var]cmd=delete&amp;start=[var]start[/var]&amp;list=[var]list[/var]&amp;order=[var]order[/var]&amp;search=[var]search[/var][loop vars="[var json='true']loop=>directories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop]&amp;directorytitle[]=[loopvar]title[/loopvar]">[var]language=>delete[/var]</a> |
                        <a href="[var]path=>url[/var][var]path=>urlquerychar[/var]cmd=copy&amp;start=[var]start[/var]&amp;list=[var]list[/var]&amp;order=[var]order[/var]&amp;search=[var]search[/var][loop vars="[var json='true']loop=>directories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop]&amp;title=[loopvar]title[/loopvar]">[var]language=>copy[/var]</a> |
                        <a href="[var]path=>url[/var][var]path=>urlquerychar[/var]cmd=export[loop vars="[var json='true']loop=>directories[/var]"]&amp;directory[]=[loopvar]directory[/loopvar][/loop]&amp;directorytitle[]=[loopvar]title[/loopvar]">[var]language=>export[/var]</a>
                        [/if]
                        [/if]
                        [/if]
                    </span>
                </td>
                <td>
                    [if condition="[loopvar json='true']up[/loopvar]" else="true"]<label for="[if condition="[loopvar json='true']file[/loopvar]" else="true"]directory[/if][loopvar]title[/loopvar]">[/if]
                    [replace search="[var]highlight[/var]" replace="<strong>[var]highlight[/var]</strong>"][loopvar]displaytitle[/loopvar][/replace]
                    [if condition="[loopvar json='true']up[/loopvar]" else="true"]</label>[/if]
                </td>
            </tr>
            [/loop]
            [/if]
            [if condition="[var json='true']condition=>entries[/var]" else="true"]
            <tr class="list-entry-folder">
                <td />
                <td />
                <td width="25%" />
                <td width="75%">
                    [var]language=>empty[/var]
                </td>
            </tr>
            [/if]
            <tr class="list-footer">
                <td />
                <td />
                <td>[var]language=>count[/var]: [var]count[/var]</td>
                <td>[var]language=>pages[/var]: [var]link=>previous[/var] [var]link=>current[/var] [var]link=>next[/var]</td>
            </tr>
        </table>
        [if condition="[var json='true']condition=>code[/var]" else="true"]
        </form>
        [/if]