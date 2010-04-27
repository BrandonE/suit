[trim]
[call function="header" /]
[call function="slacks" /]
[assign var="condition.slacks"]true[/assign]
[assign var="title"][gettext]SLACKS[/gettext][/assign]
[execute][template]header.tpl[/template][/execute]
        [if condition="log.contents"]
        [local]
        [loop value="value" iterable="log.contents"]
[execute][template]contents.tpl[/template][/execute]
        [/loop]
        [/local]
        [/if]
        [if condition="log.contents" not="true"]
        <form action="#" method="post" enctype="multipart/form-data">
        [if condition="condition.error"]
        <p><strong>[gettext]The provided site has not been configured for use with SLACKS.[/gettext]</strong></p>
        [/if]
        <p>[gettext]<strong>SLACKS</strong> (SLACKS Lets Application Coders Know SUIT) can be used to show how a page has been built by SUIT.[/gettext]</p>
        <p><a href="#install">[gettext]Click here for details on how to make a SUIT page compatible with SLACKS.[/gettext]</a>
        <p>[gettext]To use SLACKS, provide a URL to the SLACKS compatible page you would like to view:[/gettext]</p>
        <input type="text" name="url"[if condition="condition.referrer"] value="[c]referrer[/c]"[/if] />
        <input type="submit" name="submit" value="Submit" />
        <p>[gettext]Alternatively, you can upload a file containing the JSON encoded tree:[/gettext]</p>
        <input type="file" name="file" />
        <input type="submit" name="submit" value="Upload" />
        </form>
        [/if]
[execute][template]footer.tpl[/template][/execute]
[/trim]