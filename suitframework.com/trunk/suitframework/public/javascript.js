function display(dict)
{
    for (var i = 0; i < dict['show'].length; i++)
    {
        $('#' + dict['show'][i]).show();
    }
    for (var i = 0; i < dict['hide'].length; i++)
    {
        $('#' + dict['hide'][i]).hide();
    }
}

function toggle(id)
{
    if ($('#template' + (id + 1)).length)
    {
        $('#template' + (id + 1)).show();
    }
    else
    {
        $('#template0').show();
    }
    $('#template' + id).hide();
}

$(document).ready
(
    function ()
    {
        $('.yesscript').show();
        $('.noscript').hide();
        $('#htmllink').click
        (
            function ()
            {
                $('#html').hide();
                $('#text').show();
            }
        );
        $('#phplink').click
        (
            function ()
            {
                $('#php').hide();
                $('#python').show();
            }
        );
        $('#pythonlink').click
        (
            function ()
            {
                $('#php').show();
                $('#python').hide();
            }
        );
        $('#phplink2').click
        (
            function ()
            {
                $('#php2').hide();
                $('#python2').show();
            }
        );
        $('#pythonlink2').click
        (
            function ()
            {
                $('#php2').show();
                $('#python2').hide();
            }
        );
        $('#textlink').click
        (
            function ()
            {
                $('#html').show();
                $('#text').hide();
            }
        );
    }
);