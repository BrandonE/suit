<p>[gettext]If you've read the documentation for <a href="[url controller="root" action="template" templatefile="docs" parameter1="call" /]">call</a> or <a href="[url controller="root" action="template" templatefile="docs" parameter1="transform" /]">transform</a>, you might be wondering why anyone would want to construct their own <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">Rules</a> when they could just use these function rules. Both methods have their pros and cons.[/gettext]</p>

<table width="100%" border="1">
    <thead>
        <tr>
            <th>[gettext]Feature[/gettext]</th>
            <th>[gettext]Applies to Rules?[/gettext]</th>
            <th>[gettext]Applies to the function rules?[/gettext]</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Uses a function or functions to generate or transform a string.</td>
            <td>[gettext]Yes[/gettext]</td>
            <td>[gettext]Yes[/gettext]</td>
        </tr>
        <tr>
            <td>Accesses user defined parameters</td>
            <td>[gettext]No[/gettext]</td>
            <td>[gettext]Yes[/gettext]</td>
        </tr>
        <tr>
            <td>Easiest to set up</td>
            <td>[gettext]No[/gettext]</td>
            <td>[gettext]Yes[/gettext]</td>
        </tr>
        <tr>
            <td>Can be built on without modifying the rules dict</td>
            <td>[gettext]No[/gettext]</td>
            <td>[gettext]Yes[/gettext]</td>
        </tr>
        <tr>
            <td>Shortest syntax</td>
            <td>[gettext]Yes[/gettext]</td>
            <td>[gettext]No[/gettext]</td>
        </tr>
        <tr>
            <td>Can access the parse tree</td>
            <td>[gettext]Yes[/gettext]</td>
            <td>[gettext]No[/gettext]</td>
        </tr>
        <tr>
            <td>Can modify the parser</td>
            <td>[gettext]Yes[/gettext]</td>
            <td>[gettext]No[/gettext]</td>
        </tr>
    </tbody>
</table>

<p>[gettext]Even if you choose to only use call and transform, they are powered by rules, so they are a necessary feature. We at SUIT tend to create rulesets with rules that will undoubtably be used a lot in the context, and use functions for cases unique to our projects. For instance, this site uses a function called "pygments" to highlight the code in our examples. Considering that most sites wouldn't need this, we created a function instead of adding it as a rule for the <a href="[url controller="root" action="template" templatefile="download" /]#rulebox">Templating Ruleset</a>.[/gettext]</p>