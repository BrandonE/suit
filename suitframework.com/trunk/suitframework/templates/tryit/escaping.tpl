[comment]
Below is an unescaped statement. It will be interpreted as normal.
[/comment]

<p>[var]test[/var]</p>

<hr />

[comment]
By adding one escape string behind both the open and closing strings, they
will be treated as plain text.
[/comment]

<p>\[var]test\[/var]</p>

<hr />

[comment]
If you escape the escape strings, they will be treated as plain text and the
open and close strings will be interpreted as normal.
[/comment]

<p>\\[var]test[/var]</p>

<hr />

[comment]
If you escape the escape strings escaping escape strings, they and the open and
close strings will be treated as plain text.

Infinite escape characters can be placed in front of open and close strings,
and a different action will be taken depending on whether it there is an even
or odd count.

Even: The string will be treated normally, and count / 2 strings will be removed.

Odd: The string will be overlooked, and (count + 1) / 2 strings will be removed.
[/comment]

<p>\\\[var]test\\\[/var]</p>

<hr />

[comment]
Below is an unescaped statement using attributes. It will be interpreted as normal.
[/comment]

<p>[var json="true"]test[/var]</p>

<hr />

[comment]
Technically in this case, like with all attributes, the open string is "[var"
while the close string is "]", which when matched will create a new open
string. As such, all three strings must be escaped. They will be treated as
plain text.
[/comment]

<p>\[var json="true"\]test\[/var]</p>

<hr />

[comment]
This is the skip rule. It's used to ignore a section of the template and treat
it as plain text. No escape strings are necessary for this example.
[/comment]

[skip]
<p>[var]test[/var]</p>
<p>[/var][var]</p>
<p>\[var]</p>
<p>[skip][/skip]</p>
[/skip]

<hr />

[comment]
As you can see, even lone open and close strings are ignored. Escape strings
mean nothing in this section. The only thing that needs to be closed is the
skip rule, and if they are nested, then they just aren't counted. However,
you must escape lone open and close skip tags, and any escape string preceding
them will be interpreted as normal. Once the original skip rule is closed by
its close string, the section will be ended.
[/comment]

[skip]
<p>[var]test[/var]</p>
<p>[/var][var]</p>
<p>\[var]</p>
<p>[skip][/skip]</p>
<p>\[/skip]\[skip]</p>
[/skip]