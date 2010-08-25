<p>[gettext]Why use SUIT? Let's see our alternatives. Firstly, you could have the server-side language print the HTML, CSS, and Javascript. Using PHP as the server-side language, combining all of these elements together yields the following:[/gettext]</p>

<fieldset>
    <legend>combined.php</legend>
    <pre>[transform function="pygments" lexer="html+php"][template]docs/combined.tpl[/template][/transform]</pre>
</fieldset>

<p>[gettext]Would you want to use this? I wouldn't. Instead, you could use a system that has the dynamic code embedded in the static HTML. Similarly, you could embed the CSS and Javascript in the header instead of in the middle of the HTML. PHP can do this by default, and <a href="http://www.makotemplates.org/">Mako</a> can do this for Python:[/gettext]</p>

<fieldset>
    <legend>embedded.php</legend>
    <pre>[transform function="pygments" lexer="html+php"][template]docs/embedded.tpl[/template][/transform]</pre>
</fieldset>

<p>Much better, but one file still contains all 4 of the languages used. With SUIT, we can separate the server-side programming and the template structure into different files just like you can for the CSS design and the Javascript:

<fieldset>
    <legend>separated.php</legend>
    <pre>[transform function="pygments" lexer="php"][template]docs/separatedphp.tpl[/template][/transform]</pre>
</fieldset>

<fieldset>
    <legend>index.tpl</legend>
    <pre>[transform function="pygments" lexer="html"][template]docs/separated.tpl[/template][/transform]</pre>
</fieldset>

<fieldset>
    <legend>style.css</legend>
    <pre>[transform function="pygments" lexer="css"][template]docs/separatedcss.tpl[/template][/transform]</pre>
</fieldset>

<fieldset>
    <legend>javascript.js</legend>
    <pre>[transform function="pygments" lexer="javascript"][template]docs/separatedjs.tpl[/template][/transform]</pre>
</fieldset>

<p>[gettext]To be fair, I must clarify on a couple of things. Firstly, I am aware that what method you use for CSS / Javascript has no relation to what you use for the server-side language. I merely combined the similar methods to help reinforce the differences. Also, you might be wondering if I used static variables, why did I not store them in an external file, or just use static HTML? This example assumes that you would be generating these variables from an actual condition and loop, perhaps by using a database. However you generate the data, the application of it to the document remains the same.[/gettext]</p>

<p>[gettext]Those explanations aside, I believe SUIT will serve you better than the other options. Perhaps this example doesn't convince you. Before making up your mind, see <a href="[url controller="root" action="template" templatefile="docs" parameter1="features" /]">Features</a> for more examples of how SUIT can improve your Web 2.0 application.[/gettext]</p>

<p>[gettext]These options all generate the <a href="/comparison">same page</a>. Take your pick.[/gettext]</p>