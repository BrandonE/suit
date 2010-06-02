[trim]
[call function="header" /]
[assign var="condition.download"]true[/assign]
[assign var="title"][gettext]Download[/gettext][/assign]
[execute][template]header.tpl[/template][/execute]
        <a href="#suit" id="suit"><h3>[gettext]SUIT Framework[/gettext]</h3></a>
        <p>[gettext]The main package. It holds a file containing the functions for using SUIT.[/gettext]</p>
        <p>[gettext]For SUIT for PHP, you need to include it and <a href="[url controller="root" action="template" templatefile="docs" parameter1="suitconstruct" /]">initialize</a> it, while in SUIT for Python you merely need to import it. In addition, SUIT for Python can also be set up through <a href="#easy_install">easy_install</a>.[/gettext]</p>
        <ul>
            <li><a href="#phpsuitdownload">[gettext]Download SUIT 2.0.0 for PHP[/gettext]</a></li>
            <li><a href="#pythonsuitdownload">[gettext]Download SUIT 2.0.0 for Python[/gettext]</a></li>
        </ul>
        <a href="#pylons" id="pylons"><h3>SUIT for Pylons</h3></a>
        <p>[gettext]An integration for using SUIT as the Pylons templating engine of choice.[/gettext]</p>
        <ul>
            <li><a href="#pylonssuitdownload">[gettext]Download SUIT 2.0.0 for Pylons[/gettext]</a></li>
        </ul>
        <a href="#rulebox" id="rulebox"><h3>Rulebox</h3></a>
        <p>[gettext]A package containing various sets of rules for use with SUIT. If you believe you have a set of rules that can be used for several occassions, post it <a href="#forumurl">here</a> and it may be included into the package.[/gettext]</p>
        <p>[gettext] For Rulebox for PHP, you need to include it and initialize the various Rulesets included (<a href="[url controller="root" action="template" templatefile="docs" parameter1="templatingconstruct" /]">Templating</a>, <a href="[url controller="root" action="template" templatefile="docs" parameter1="bbcodeconstruct" /]">BBCode</a>), while in Rulebox for Python you merely need to import it. In addition, Rulebox for Python can also be set up through <a href="#easy_install">easy_install</a>.[/gettext]</p>
        <ul>
            <li><a href="#phpruleboxdownload">[gettext]Download Rulebox 1.0.0 for PHP[/gettext]</a></li>
            <li><a href="#pythonruleboxdownload">[gettext]Download Rulebox 1.0.0 for Python[/gettext]</a></li>
        </ul>
        <a href="#tie" id="tie"><h3>TIE (Discontinued)</h3></a>
        <p>[gettext]A template manager for SUIT for PHP. It stands for Template Integration Environment. The latest version uses SUIT 1.3.4 and should only be used for educational purposes.[/gettext]</p>
        <p><a href="#install">[gettext]Click here for details on how to install TIE.[/gettext]</a></p>
        <ul>
            <li><a href="http://sourceforge.net/projects/suitframework/files/TIE/TIE_1.3.4.zip/download">[gettext]Download TIE 1.3.4[/gettext]</a></li>
        </ul>
        <a href="#cape" id="cape"><h3>CAPE (Discontinued)</h3></a>
        <p>[gettext]A community application made with SUIT for PHP to get an idea how a forum using SUIT would work. The latest version uses SUIT for PHP 1.3.2 and should only be used for educational purposes. For a forum system still in development using SUIT, see <a href="#paraboards">Parasol Boards</a>.[/gettext]</p>
        <p><a href="#install">[gettext]Click here for details on how to install CAPE.[/gettext]</a></p>
        <ul>
            <li><a href="http://sourceforge.net/projects/suitframework/files/CAPE/CAPE_0.0.0.zip/download">[gettext]Download CAPE 0.0.0[/gettext]</a></li>
        </ul>
[execute][template]footer.tpl[/template][/execute]
[/trim]