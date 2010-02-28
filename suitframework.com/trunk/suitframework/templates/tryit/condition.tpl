[if condition="[var json='true']installed[/var]"]
<p>The program has been installed.</p>
[/if]
[if condition="[var json='true']installed[/var]" else="true"]
<p>The program has not been installed.</p>
[/if]