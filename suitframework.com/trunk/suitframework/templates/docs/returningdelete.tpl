<p><em>[gettext]Available Since:</em> Rulebox for Python (1.0.0)[/gettext]</p>

<p>[gettext]Delete contents of a tree to break references.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    void templating.returningdelete ( list tree, int limit )
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="tree" href="#tree">tree</a></legend>
        <p>[gettext]The contents of the tree.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="limit" href="#limit">limit</a></legend>
        <p>[gettext]What the length the tree contents should be limited to.[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
    <p>[gettext]Nothing. The contents of ``tree`` are modified.[/gettext]</p>
</fieldset>

<fieldset>
    <legend><a id="examples" href="#examples">[gettext]Examples[/gettext]</a></legend>
    <fieldset>
        <legend><a id="basicusage" href="#basicusage">[gettext]Basic Usage[/gettext]</a></legend>
        <fieldset>
            <legend>[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"][skip]from rulebox import templating
tree = ['1', '2', '3', '4']
templating.returningdelete(tree, 1)
# Returns: None. tree is now ['1'][/skip][/transform]
        </fieldset>
    </fieldset>
</fieldset>