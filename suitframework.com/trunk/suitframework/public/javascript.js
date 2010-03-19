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