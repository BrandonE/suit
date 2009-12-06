        <fieldset>
            <legend>[:language=>options:]</legend>
[!tie/list=>tie/list=>tie/parse!]
[!tie/search=>tie/search=>tie/parse!]
        </fieldset>
        [if condition="[:condition=>code:]" else="true"]
        <form enctype="multipart/form-data" action="#" method="post">
        [if condition="[:condition=>error:]"]
        <p>[:error:]</p>
        [/if]
        <p>
            <input type="submit" name="deletechecked" value="[:language=>deletechecked:]" />
            <input type="submit" name="exportchecked" value="[:language=>exportchecked:]" />
            <input type="submit" name="move" value="[:language=>move:]" />
            <label for="find">[:language=>find:]</label>: <input type="text" name="find" id="find" />
            <label for="replacewith">[:language=>replacewith:]</label>: <input type="text" name="replacewith" id="replacewith" />
            <input type="submit" name="replace" value="[:language=>replace:]" />
        </p>
        [/if]
        <table class="list" cellpadding="0" cellspacing="0">
            <tr class="list-header">
                <td />
                <td />
                <td width="25%">
                    [!tie/order=>tie/order!][if condition="[:condition=>code:]" else="true"] | [!tie/checkall=>tie/all=>tie/parse!] | [!tie/uncheckall=>tie/all=>tie/parse!][/if]
                </td>
                <td width="75%" style="text-align: right;">
                    [if condition="[:condition=>code:]" else="true"]
                    <a href="[:path=>url:][:path=>urlquerychar:]cmd=add&amp;start=[:start:]&amp;list=[:list:]&amp;order=[:order:]&amp;search=[:search:][loop vars="[:loop=>directories:]"]&amp;directory[]=[|directory|][/loop]&amp;title=">[:language=>add:]</a> |
                    <a href="[:path=>url:][:path=>urlquerychar:]cmd=create&amp;start=[:start:]&amp;list=[:list:]&amp;order=[:order:]&amp;search=[:search:][loop vars="[:loop=>directories:]"]&amp;directory[]=[|directory|][/loop]&amp;title=">[:language=>createdirectory:]</a>
                    <input name="file" type="file" />
                    <input type="submit" name="import" value="[:language=>import:]" />
                    [/if]
                </td>
            </tr>
            [if condition="entries"]
            [loop vars="[:loop=>entries:]"]
            <tr class="list-entry[if condition="[|file|]" else="true"]-folder[/if]">
                <td>
                    [if condition="[:condition=>code:]" else="true"]
                    [if condition="[|file|]"]<div style="visibility: hidden">[/if]<input type="radio" name="moveto" value="[|title|]" />[if condition="[|file|]"]</div>[/if]
                    [/if]
                </td>
                <td>
                    [if condition="[:condition=>code:]" else="true"]
                    [if condition="[|file|]"]
                    <input name="entry[]" id="[|title|]" type="checkbox" value="[|title|]"[if condition="[:condition=>checked:]" trim=""] checked="checked"[/if] />
                    [/if]
                    [if condition="[|file|]" else="true"]
                    [if condition="[|up|]"]<div style="visibility: hidden">[/if]<input name="directoryentry[]" id="directory[|title|]" type="checkbox" value="[|title|]"[if condition="[:condition=>checked:]"][if condition="[|up|]" else="true" trim=""] checked="checked"[/if][/if] />[if condition="[|up|]"]</div>[/if]
                    [/if]
                    [/if]
                </td>
                <td>
                    <span class="list-hidden">
                        [if condition="[|file|]"]
                        [if condition="[:condition=>code:]"]
                        <a href="[:path=>url:][:path=>urlquerychar:]cmd=view&amp;start=[:start:]&amp;list=[:list:]&amp;order=[:order:]&amp;search=[:search:][loop vars="[:loop=>directories:]"]&amp;directory[]=[|directory|][/loop]&amp;title=[|title|]">[:language=>view:]</a>
                        [/if]
                        [if condition="[:condition=>code:]" else="true"]
                        <a href="[:path=>url:][:path=>urlquerychar:]cmd=edit&amp;start=[:start:]&amp;list=[:list:]&amp;order=[:order:]&amp;search=[:search:][loop vars="[:loop=>directories:]"]&amp;directory[]=[|directory|][/loop]&amp;title=[|title|]">[:language=>edit:]</a> |
                        <a href="[:path=>url:][:path=>urlquerychar:]cmd=delete&amp;start=[:start:]&amp;list=[:list:]&amp;order=[:order:]&amp;search=[:search:][loop vars="[:loop=>directories:]"]&amp;directory[]=[|directory|][/loop]&amp;title[]=[|title|]">[:language=>delete:]</a> |
                        <a href="[:path=>url:][:path=>urlquerychar:]cmd=add&amp;start=[:start:]&amp;list=[:list:]&amp;order=[:order:]&amp;search=[:search:][loop vars="[:loop=>directories:]"]&amp;directory[]=[|directory|][/loop]&amp;title=[|title|]">[:language=>clone:]</a> |
                        <a href="[:path=>url:][:path=>urlquerychar:]cmd=export[loop vars="[:loop=>directories:]"]&amp;directory[]=[|directory|][/loop]&amp;title[]=[|title|]">[:language=>export:]</a>
                        [/if]
                        [/if]
                        [if condition="[|file|]" else="true"]
                        <a href="[:path=>url:][:path=>urlquerychar:]list=[:list:]&amp;order=[:order:]&amp;search=[:search:][if condition="[|up|]"][loop vars="[:loop=>updirectories:]"]&amp;directory[]=[|directory|][/loop][/if][if condition="[|up|]" else="true"][loop vars="[:loop=>directories:]"]&amp;directory[]=[|directory|][/loop]&amp;directory[]=[|title|][/if]">[:language=>expand:]</a>
                        [if condition="[:condition=>code:]" else="true"]
                        [if condition="[|up|]" else="true"]
                        | <a href="[:path=>url:][:path=>urlquerychar:]cmd=rename&amp;start=[:start:]&amp;list=[:list:]&amp;order=[:order:]&amp;search=[:search:][loop vars="[:loop=>directories:]"]&amp;directory[]=[|directory|][/loop]&amp;title=[|title|]">[:language=>rename:]</a> |
                        <a href="[:path=>url:][:path=>urlquerychar:]cmd=delete&amp;start=[:start:]&amp;list=[:list:]&amp;order=[:order:]&amp;search=[:search:][loop vars="[:loop=>directories:]"]&amp;directory[]=[|directory|][/loop]&amp;directorytitle[]=[|title|]">[:language=>delete:]</a> |
                        <a href="[:path=>url:][:path=>urlquerychar:]cmd=copy&amp;start=[:start:]&amp;list=[:list:]&amp;order=[:order:]&amp;search=[:search:][loop vars="[:loop=>directories:]"]&amp;directory[]=[|directory|][/loop]&amp;title=[|title|]">[:language=>copy:]</a> |
                        <a href="[:path=>url:][:path=>urlquerychar:]cmd=export[loop vars="[:loop=>directories:]"]&amp;directory[]=[|directory|][/loop]&amp;directorytitle[]=[|title|]">[:language=>export:]</a>
                        [/if]
                        [/if]
                        [/if]
                    </span>
                </td>
                <td>
                    [if condition="[|up|]" else="true"]<label for="[if condition="[|file|]" else="true"]directory[/if][|title|]">[/if][|displaytitle|][if condition="[|up|]" else="true"]</label>[/if]
                </td>
            </tr>
            [/loop]
            [/if]
            [if condition="entries" else="true"]
            <tr class="list-entry-folder">
                <td />
                <td />
                <td width="25%" />
                <td width="75%">
                    [:language=>empty:]
                </td>
            </tr>
            [/if]
            <tr class="list-footer">
                <td />
                <td />
                <td>[:language=>count:]: [:count:]</td>
                <td>[:language=>pages:]: [:link=>previous:] [:link=>current:] [:link=>next:]</td>
            </tr>
        </table>
        [if condition="[:condition=>code:]" else="true"]
        </form>
        [/if]
[section highlightstart]<strong>[/section highlightstart]
[section highlightend]</strong>[/section highlightend]
[section page] [/section page]