[assign var="test"]First[/assign]
[local]
    [assign var="test"]Second[/assign]
    [local]
        [assign var="test"]Third[/assign]
        <p>[var]test[/var]</p>
    [/local]
    <p>[var]test[/var]</p>
[/local]
<p>[var]test[/var]</p>