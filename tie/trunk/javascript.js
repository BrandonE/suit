$(window).load
(
    function ()
    {
        $('.yesscript').show();
    }
);

$(document).ready
(
    function ()
    {
        $('.parsetab').click
        (
            function ()
            {
                $('.parse').show();
                $('.strpos').hide();
            }
        );

        $('.strpostab').click
        (
            function ()
            {
                $('.parse').hide();
                $('.parsehide').hide();
                $('.parseshow').show();
                $('.strpos').show();
            }
        );

    }
);

function tab (string, expand)
{
    $('.' + string + 'boxhide').hide();
    $('.' + string + 'boxshow').show();
    if (expand)
    {
        $('#' + string + '0').hide();
        $('#' + string + '1').show();
        $('.' + string).show();
    }
    else
    {
        $('#' + string + '0').show();
        $('#' + string + '1').hide();
        $('.' + string).hide();
    }
}

function box (string, string2, string3, expand)
{
    $('.' + string + 'boxhide').hide();
    $('.' + string + 'boxshow').show();
    $('.' + string + string2 + 'boxhide').hide();
    $('.' + string + string2 + 'boxshow').show();
    if (expand)
    {
        $('#' + string + string2 + string3 + 'box0').hide();
        $('#' + string + string2 + string3 + 'box1').show();
        $('#' + string + string2 + string3 + 'box').show();
    }
    else
    {
        $('#' + string + string2 + string3 + 'box0').show();
        $('#' + string + string2 + string3 + 'box1').hide();
        $('#' + string + string2 + string3 + 'box').hide();
    }
}