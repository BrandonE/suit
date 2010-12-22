<p>[gettext]06/06/2010: SUIT 2.0.0 and Rulebox 1.0.0 released. These versions were the first to be developed using SVN. You can see all of the differences between it and its predecessor by reading revisions 25 through 142.[/gettext]</p>

<p>[gettext]07/31/2010: SUIT 2.0.1 and Rulebox 1.1.0 released. Applied the 80 character rule to the PHP files to match the Python files. Fixed bug with <a href="[url controller="root" action="template" templatefile="docs" parameter1="try" /]">try</a> that caused the variable to be undefined initially if no exception was thrown. Made SUIT PHP's parse function cache correctly. Before, it was loading the cache, but not returning it. The static variable "delimiter" has been added to <a href="[url controller="root" action="template" templatefile="docs" parameter1="call" /]">call</a> and <a href="[url controller="root" action="template" templatefile="docs" parameter1="transform" /]">transform</a>.[/gettext]</p>

<p>
    [gettext]12/21/2010: SUIT 2.0.2 and Rulebox 1.1.1 released:[/gettext]
    <ul>
        <li>[gettext]Fixed copyrights. For some reason, it said it was using the GPL instead of the LGPL, and Rulebox wasn't part of a package.[/gettext]</li>
        <li>[gettext]Ran 2to3.py on all of the Python files, which supposedly makes SUIT compatible with Python 3.[/gettext]</li>
        <li>[gettext]Apparently, json is preferred over simplejson, so now it'll try to import json, and if it fails, it'll import simplejson.[/gettext]</li>
        <li>[gettext]Made it so that Rulebox for PHP can use [skip][call /] and [transform][/skip] with actual kwargs instead of just an array.[/gettext]</li>
        <li>[gettext]Applied Chris' new entities formatting to templating.py of Rulebox Python.[/gettext]</li>
    </ul>
</p>