$(window).load
(
    function ()
    {
        $('.yesscript').show();
        $('.return').hide();
    }
);

$(document).ready
(
    function ()
    {
        $('.text').click
        (
            function ()
            {
                $('.text').hide();
                $('.return').show();
            }
        );

        $('.return').click
        (
            function ()
            {
                $('.text').show();
                $('.return').hide();
            }
        );
    }
);