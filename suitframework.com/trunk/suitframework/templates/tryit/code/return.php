<?php
class Helpers
{
    public function currenttime()
    {
        return date('m/d/Y H:M:S');
    }

    public function pygments($lexer, $string)
    {
        /*
        SUIT's Try It Editor is powered by Python, and the PHP examples
        attempt to show the equivalent of the Python code. However, there is no
        PHP version of Pygments, so we'll just revert back to the text version.
        */
        return '<pre>' . htmlentities($string) . '</pre>';
    }
}

$templating->var->username = 'Brandon';
$templating->var->loggedin = true;
$templating->var->members = array
(
    array
    (
        'name' => 'Brandon',
        'admin' => true,
        'banned' => false
    ),
    array
    (
        'name' => 'Chris',
        'admin' => false,
        'banned' => true
    )
);
$templating->var->owner = 'Brandon';
$templating->var->ownerfull = 'Brandon Evans';
?>