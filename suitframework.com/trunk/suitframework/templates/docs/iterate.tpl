<p><em>[gettext]Available Since:</em> Rulebox for Python (1.0.0)[/gettext]</p>

<p>[gettext]Iterate over any object.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    mixed templating.iterate ( mixed iterable )
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="iterable" href="#iterable">iterable</a></legend>
        <p>[gettext]The object to iterate over.[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
    <p>[gettext]The items in key, value format.[/gettext]</p>
</fieldset>

<fieldset>
    <legend><a id="examples" href="#examples">[gettext]Examples[/gettext]</a></legend>
    <fieldset>
        <legend><a id="basicusage" href="#basicusage">[gettext]Basic Usage[/gettext]</a></legend>
        <fieldset>
            <legend>[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"][skip]from rulebox import templating
iterable = {
    'foo': 'bar'
}
iterable = templating.iterate(iterable)
# Returns: [
#     ('foo', 'bar')
# ][/skip][/transform]
        </fieldset>
    </fieldset>
</fieldset>