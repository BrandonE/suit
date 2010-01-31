$(document).ready
(
    function ()
    {
        $('.yesscript').show();
        $('.before').click
        (
            function ()
            {
                $('.original').show();
                $('.contents').hide();
                $('.case').hide();
            }
        );

        $('.tree').click
        (
            function ()
            {
                $('.original').hide();
                $('.contents').show();
                $('.case').hide();
            }
        );

        $('.after').click
        (
            function ()
            {
                $('.original').hide();
                $('.contents').hide();
                $('.case').show();
            }
        );
    }
);