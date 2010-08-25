<p>[gettext]One of the most common criticisms of projects like SUIT is that by adding "unnecessary" complication, they cause scripts to load slower than they would normally. While I'd like to say SUIT's parser works so efficiently that it doesn't take any extra time to process, it does slow scripts down by a couple fractions of a millisecond. While some might consider this negligble, others might consider it an excuse to avoid using SUIT along with its standards. For those people, SUIT has caching functions.[/gettext]</p>

<p>[gettext]By default, SUIT caches the results to <a href="[url controller="root" action="template" templatefile="docs" parameter1="tokens" /]">tokens</a> and <a href="[url controller="root" action="template" templatefile="docs" parameter1="parse" /]">parse</a>. This effictively eliminates the additional processing time whenever you <a href="[url controller="root" action="template" templatefile="docs" parameter1="execute" /]">execute</a> the same template with the same <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">rules</a>.[/gettext]</p>

<p>[gettext]Now, if your application runs on a constant Python process, that means this cache will not reset when you refresh the page. But, what if you want to take full effect of the caching features running a PHP application or a Python application that doesn't run constantly? Luckily, the SUIT's cache attribute can be accessed, encoded by JSON, stored to a file, loaded when you refresh, and decoded to use. See an example of this below:[/gettext]</p>

<fieldset id="php">
    <legend class="yesscript" style="display: none"><a href="#null" id="phplink">[gettext]PHP - Click to toggle[/gettext]</a></legend>
    <legend class="noscript">[gettext]PHP[/gettext]</legend>
    [transform function="pygments" lexer="php"]<?php
$cache = file_get_contents('cache.json');
if ($cache)
{
    $suit->cache = json_decode($cache);
}
// Execute the string.
$string = $suit->execute($rules, $string);
// If the cache has grown, update the cache file.
$newcache = json_encode($suit->cache);
if ($cache != $newcache)
{
    file_put_contents('cache.json', $newcache);
}
?>[/transform]
</fieldset>
<fieldset class="noscript" id="python">
    <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
    <legend class="noscript">[gettext]Python[/gettext]</legend>
    [transform function="pygments" lexer="python"]import json
cache = open('cache.json').read()
if cache:
    suit.cache = json.loads(cache)
# Execute the string.
string = suit.execute(rules, string)
# If the cache has grown, update the cache file.
newcache = json.dumps(suit.cache)
if cache != newcache:
    open('cache.json').write(newcache)[/transform]
</fieldset>

<p>[gettext]Some systems create a cache file for every template parsed. Not SUIT. Set up the cache like this, and you're good to go. Now, once you parse every template with every ruleset you use on you application, the cache should stay the same size. Changing the data that SUIT substitutes into these templates will not grow the cache. However, if you modify a template or change the ruleset that you use to transform it, the cache will start growing again. Because of this, it wouldn't be a bad idea to empty the cache file every once in a while.[/gettext]</p>