function box(id, phase)
{
    switch (phase)
    {
        case 0:
            show = 'contents';
            hide = 'original';
            break;
        case 1:
            show = 'case';
            hide = 'contents';
            break;
        default:
            show = 'original';
            hide = 'case';
    }
    if ($('#' + show + id).length == 0)
    {
        show = 'contents';
    }
    $('#' + show + id).show();
    $('#' + hide + id).hide();
}
$(document).ready
(
    function ()
    {
        $('.yesscript').show();
    }
);