[trim]
<div class="header">
    <div class="left">
        <h1 class="title"><a href="#">[var]language=>NULL[/var]</a></h1>
    </div>
</div>
<div class="nav">
    <div class="space"></div>
    <div class="yesscript" style="display: none;">
        <div class="templates">
            <ul>
                    <li class="selected"><a class="templatestab" href="#NULL">[var]language=>templates[/var]</a></li>
                    <li><a class="parsetab" href="#NULL">[var]language=>parse[/var]</a></li>
                    <li><a class="strpostab" href="#NULL">[var]language=>strpos[/var]</a></li>
            </ul>
        </div>
        <div class="parse" style="display: none;">
            <ul>
                    <li><a class="templatestab" href="#NULL">[var]language=>templates[/var]</a></li>
                    <li class="selected"><a class="parsetab" href="#NULL">[var]language=>parse[/var]</a></li>
                    <li><a class="strpostab" href="#NULL">[var]language=>strpos[/var]</a></li>
            </ul>
        </div>
        <div class="strpos" style="display: none;">
            <ul>
                    <li><a class="templatestab" href="#NULL">[var]language=>templates[/var]</a></li>
                    <li><a class="parsetab" href="#NULL">[var]language=>parse[/var]</a></li>
                    <li class="selected"><a class="strpostab" href="#NULL">[var]language=>strpos[/var]</a></li>
            </ul>
        </div>
    </div>
    <noscript>
        <ul>
                <li class="selected"><a href="#NULL">[var]language=>empty[/var]</a></li>
        </ul>
    </noscript>
</div>
<div class="template">
    <div class="section">
        <div class="yesscript" style="display: none;">
            <div class="templates">
                <h2>[var]language=>templates[/var]</h2>
                <table class="list" cellpadding="0" cellspacing="0">
                    [loop vars="[var json=\"true\"]loop=>templates[/var]"]
                    <tr class="list-entry-folder">
                        <td width="25%">
                            <span class="list-hidden">
                                <a class="templateshow" id="template[loopvar]id[/loopvar]0" href="#NULL" onclick="tab('template[loopvar]id[/loopvar]', true);">[var]language=>expand[/var]</a>
                                <a class="templatehide" id="template[loopvar]id[/loopvar]1" href="#NULL" onclick="tab('template[loopvar]id[/loopvar]', false);" style="display: none;">[var]language=>collapse[/var]</a>
                            </span>
                        </td>
                        <td width="75%">
                            [loopvar]title[/loopvar]
                        </td>
                    </tr>
                    <tr class="list-entry templatehide template[loopvar]id[/loopvar]" style="display: none;">
                        <td>
                            <span class="list-hidden">
                                <a class="templateboxshow template[loopvar]id[/loopvar]boxshow" id="template[loopvar]id[/loopvar]templatebox0" href="#template[loopvar]id[/loopvar]templatebox" onclick="box('template', '[loopvar]id[/loopvar]', 'template', true);">[var]language=>expand[/var]</a>
                                <a class="templateboxhide template[loopvar]id[/loopvar]boxhide" id="template[loopvar]id[/loopvar]templatebox1" href="#NULL" onclick="box('template', '[loopvar]id[/loopvar]', 'template', false);" style="display: none;">[var]language=>collapse[/var]</a>
                            </span>
                        </td>
                        <td>
                            [var]language=>template[/var]
                        </td>
                    </tr>
                    [loop vars="[loopvar json=\"true\"]code[/loopvar]"]
                    <tr class="list-entry templatehide template[loopvar]id[/loopvar]" style="display: none;">
                        <td>
                            [if condition="[loopvar json=\"true\"]ifcode[/loopvar]"]
                            <span class="list-hidden">
                                <a class="templateboxshow template[loopvar]id[/loopvar]boxshow" id="template[loopvar]id[/loopvar]code[loopvar]id2[/loopvar]box0" href="#template[loopvar]id[/loopvar]code[loopvar]id2[/loopvar]box" onclick="box('template', '[loopvar]id[/loopvar]', 'code[loopvar]id2[/loopvar]', true);">[var]language=>expand[/var]</a>
                                <a class="templateboxhide template[loopvar]id[/loopvar]boxhide" id="template[loopvar]id[/loopvar]code[loopvar]id2[/loopvar]box1" href="#NULL" onclick="box('template', '[loopvar]id[/loopvar]', 'code[loopvar]id2[/loopvar]', false);" style="display: none;">[var]language=>collapse[/var]</a>
                            </span>
                            [/if]
                        </td>
                        <td>
                            [var]language=>code[/var]:
                            [if condition="[loopvar json=\"true\"]ifcode[/loopvar]"]
                            [loopvar]codename[/loopvar]
                            [/if]
                            [if condition="[loopvar json=\"true\"]ifcode[/loopvar]" else="true"]
                            "[loopvar]codename[/loopvar]" - [var]language=>notfound[/var]
                            [/if]
                        </td>
                    </tr>
                    [/loop]
                    <tr class="list-entry templatehide template[loopvar]id[/loopvar]" style="display: none;">
                        <td />
                        <td>
                            [var]language=>file[/var]: [loopvar]file[/loopvar]
                        </td>
                    </tr>
                    <tr class="list-entry templatehide template[loopvar]id[/loopvar]" style="display: none;">
                        <td />
                        <td>
                            [var]language=>line[/var]: [loopvar]line[/loopvar]
                        </td>
                    </tr>
                    [/loop]
                    [if condition="[var json=\"true\"]condition=>templates[/var]" else="true"]
                    <tr class="list-entry-folder">
                        <td width="25%" />
                        <td width="75%">
                            [var]language=>empty[/var]
                        </td>
                    </tr>
                    [/if]
                </table>
                [loop vars="[var json=\"true\"]loop=>templates[/var]"]
                <p class="templatehide templateboxhide template[loopvar]id[/loopvar]boxhide" id="template[loopvar]id[/loopvar]templatebox" style="display: none;">
                    <textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[loopvar]template[/loopvar]</textarea>
                </p>
                [loop vars="[loopvar json=\"true\"]code[/loopvar]"]
                [if condition="[loopvar json=\"true\"]ifcode[/loopvar]"]
                <p class="templatehide templateboxhide template[loopvar]id[/loopvar]boxhide" id="template[loopvar]id[/loopvar]code[loopvar]id2[/loopvar]box" style="display: none;">
                    <textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[if condition="[loopvar json=\"true\"]ifcodefile[/loopvar]"][loopvar]codefile[/loopvar][/if][if condition="[loopvar json=\"true\"]ifcodefile[/loopvar]" else="true"][var]language=>na[/var][/if]</textarea>
                </p>
                [/if]
                [/loop]
                [/loop]
            </div>
            <div class="parse" style="display: none;">
            <h2>[var]language=>parse[/var]</h2>
                <table class="list" cellpadding="0" cellspacing="0">
                    [loop vars="[var json=\"true\"]loop=>parse[/var]"]
                    <tr class="list-entry-folder">
                        <td width="25%">
                            <span class="list-hidden">
                                <a class="parseshow" id="parse[loopvar]id[/loopvar]0" href="#NULL" onclick="tab('parse[loopvar]id[/loopvar]', true);">[var]language=>expand[/var]</a>
                                <a class="parsehide" id="parse[loopvar]id[/loopvar]1" href="#NULL" onclick="tab('parse[loopvar]id[/loopvar]', false);" style="display: none;">[var]language=>collapse[/var]</a>
                            </span>
                        </td>
                        <td width="75%">
                            [loopvar]title[/loopvar]
                        </td>
                    </tr>
                    <tr class="list-entry parsehide parse[loopvar]id[/loopvar]" style="display: none;">
                        <td>
                            <span class="list-hidden">
                                <a class="parseboxshow parse[loopvar]id[/loopvar]boxshow" id="parse[loopvar]id[/loopvar]templatebox0" href="#parse[loopvar]id[/loopvar]templatebox" onclick="box('parse', '[loopvar]id[/loopvar]', 'template', true);">[var]language=>expand[/var]</a>
                                <a class="parseboxhide parse[loopvar]id[/loopvar]boxhide" id="parse[loopvar]id[/loopvar]templatebox1" href="#NULL" onclick="box('parse', '[loopvar]id[/loopvar]', 'template', false);" style="display: none;">[var]language=>collapse[/var]</a>
                            </span>
                        </td>
                        <td>
                            [var]language=>template[/var]
                        </td>
                    </tr>
                    <tr class="list-entry parsehide parse[loopvar]id[/loopvar]" style="display: none;">
                        <td>
                            <span class="list-hidden">
                                <a class="parseboxshow parse[loopvar]id[/loopvar]boxshow" id="parse[loopvar]id[/loopvar]returnbox0" href="#parse[loopvar]id[/loopvar]returnbox" onclick="box('parse', '[loopvar]id[/loopvar]', 'return', true);">[var]language=>expand[/var]</a>
                                <a class="parseboxhide parse[loopvar]id[/loopvar]boxhide" id="parse[loopvar]id[/loopvar]returnbox1" href="#NULL" onclick="box('parse', '[loopvar]id[/loopvar]', 'return', false);" style="display: none;">[var]language=>collapse[/var]</a>
                            </span>
                        </td>
                        <td>
                            [var]language=>parse[/var]
                        </td>
                    </tr>
                    [if condition="[loopvar json=\"true\"]ifranges[/loopvar]"]
                    <tr class="list-entry parsehide parse[loopvar]id[/loopvar]" style="display: none;">
                        <td>
                            <span class="list-hidden">
                                <a class="parseboxshow parse[loopvar]id[/loopvar]boxshow" id="parse[loopvar]id[/loopvar]rangesbox0" href="#parse[loopvar]id[/loopvar]rangesbox" onclick="box('parse', '[loopvar]id[/loopvar]', 'ranges', true);">[var]language=>expand[/var]</a>
                                <a class="parseboxhide parse[loopvar]id[/loopvar]boxhide" id="parse[loopvar]id[/loopvar]rangesbox1" href="#NULL" onclick="box('parse', '[loopvar]id[/loopvar]', 'ranges', false);" style="display: none;">[var]language=>collapse[/var]</a>
                            </span>
                        </td>
                        <td>
                            [var]language=>ranges[/var]
                        </td>
                    </tr>
                    [/if]
                    <tr class="list-entry parsehide parse[loopvar]id[/loopvar]" style="display: none;">
                        <td />
                        <td>
                            [var]language=>file[/var]: [loopvar]file[/loopvar]
                        </td>
                    </tr>
                    <tr class="list-entry parsehide parse[loopvar]id[/loopvar]" style="display: none;">
                        <td />
                        <td>
                            [var]language=>line[/var]: [loopvar]line[/loopvar]
                        </td>
                    </tr>
                    [/loop]
                    [if condition="[var json=\"true\"]condition=>parse[/var]" else="true"]
                    <tr class="list-entry-folder">
                        <td width="25%" />
                        <td width="75%">
                            [var]language=>empty[/var]
                        </td>
                    </tr>
                    [/if]
                </table>
                [loop vars="[var json=\"true\"]loop=>parse[/var]"]
                <p class="parsehide parseboxhide parse[loopvar]id[/loopvar]boxhide" id="parse[loopvar]id[/loopvar]templatebox" style="display: none;">
                    <textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[loopvar]before[/loopvar]</textarea>
                </p>
                <p class="parsehide parseboxhide parse[loopvar]id[/loopvar]boxhide" id="parse[loopvar]id[/loopvar]returnbox" style="display: none;">
                    <textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[loopvar]return[/loopvar]</textarea>
                </p>
                [if condition="[loopvar json=\"true\"]ifranges[/loopvar]"]
                <p class="parsehide parseboxhide parse[loopvar]id[/loopvar]boxhide" id="parse[loopvar]id[/loopvar]rangesbox" style="display: none;">
                    <textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[loopvar]ranges[/loopvar]</textarea>
                </p>
                [/if]
                [/loop]
            </div>
            <div class="strpos" style="display: none;">
                <h2>[var]language=>strpos[/var]</h2>
                <table class="dashboard">
                    <tr>
                        <th>[var]language=>strpos[/var]</th>
                    </tr>
                    <tr>
                        <td class="category">[var]language=>function[/var]</td>
                        <td class="category">[var]language=>call[/var]</td>
                        <td class="category">[var]language=>cache[/var]</td>
                    </tr>
                    <tr>
                        <td class="category">[var]language=>escape[/var]</td>
                        <td>[var]escapecall[/var]</td>
                        <td>[var]escapecache[/var]</td>
                    </tr>
                    <tr>
                        <td class="category">[var]language=>explodeunescape[/var]</td>
                        <td>[var]explodeunescapecall[/var]</td>
                        <td>[var]explodeunescapecache[/var]</td>
                    </tr>
                    <tr>
                        <td class="category">[var]language=>parse[/var]</td>
                        <td>[var]parsecall[/var]</td>
                        <td>[var]parsecache[/var]</td>
                    </tr>
                    <tr>
                        <td class="category">[var]language=>total[/var]</td>
                        <td>[var]totalcall[/var]</td>
                        <td>[var]totalcache[/var]</td>
                    </tr>
                </table>
            </div>
        </div>
        <noscript>
            <center>
                <p>
                    [var]language=>enablejavascript[/var]
                </p>
            </center>
        </noscript>
    </div>
</div>
[/trim]