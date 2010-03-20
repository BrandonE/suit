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