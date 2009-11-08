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
		$('#templates0, #templates1, #templates2').click
		(
			function ()
			{
				$('.replace').hide();
				$('.replacehide').hide();
				$('.replaceshow').show();
				$('.parse').hide();
				$('.parsehide').hide();
				$('.parseshow').show();
				$('.templates').show();
			}
		);

		$('#replace0, #replace1, #replace2').click
		(
			function ()
			{
				$('.templates').hide();
				$('.templatehide').hide();
				$('.templateshow').show();
				$('.parse').hide();
				$('.parsehide').hide();
				$('.parseshow').show();
				$('.replace').show();
			}
		);

		$('#parse0, #parse1, #parse2').click
		(
			function ()
			{
				$('.templates').hide();
				$('.templatehide').hide();
				$('.templateshow').show();
				$('.replace').hide();
				$('.replacehide').hide();
				$('.replaceshow').show();
				$('.parse').show();
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