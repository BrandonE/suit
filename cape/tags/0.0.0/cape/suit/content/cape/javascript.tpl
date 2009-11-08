var stack = [];

$(window).load
(
	function ()
	{
		$('.yesscripthide').hide();
	}
);

$(document).ready
(
	function ()
	{
		$('#closetags').click
		(
			function ()
			{
				var length = stack.length;
				while (stack.length != 0)
					$('#content').val($('#content').val() + stack.pop());
				$('.tagshow').show(); $('.taghide').hide();
				if (length)
					$('#content').focus();
			}
		);
		$('#aim0').click
		(
			function ()
			{
				$('#aim1').show();
				$('#aim0').hide();
				$('.aim1').show();
				$('.aim0').hide();
				$('#aim').focus();
			}
		);
		$('#aim1').click
		(
			function ()
			{
				$('#aim0').show();
				$('#aim1').hide();
				$('.aim0').show();
				$('.aim1').hide();
				$('#aim').val($('#aimtemp').val());
			}
		);
		$('#icq0').click
		(
			function ()
			{
				$('#icq1').show();
				$('#icq0').hide();
				$('.icq1').show();
				$('.icq0').hide();
				$('#icq').focus();
			}
		);
		$('#icq1').click
		(
			function ()
			{
				$('#icq0').show();
				$('#icq1').hide();
				$('.icq0').show();
				$('.icq1').hide();
				$('#icq').val($('#icqtemp').val());
			}
		);
		$('#yahoo0').click
		(
			function ()
			{
				$('#yahoo1').show();
				$('#yahoo0').hide();
				$('.yahoo1').show();
				$('.yahoo0').hide();
				$('#yahoo').focus();
			}
		);
		$('#yahoo1').click
		(
			function ()
			{
				$('#yahoo0').show();
				$('#yahoo1').hide();
				$('.yahoo0').show();
				$('.yahoo1').hide();
				$('#yahoo').val($('#yahootemp').val());
			}
		);
		$('#msn0').click
		(
			function ()
			{
				$('#msn1').show();
				$('#msn0').hide();
				$('.msn1').show();
				$('.msn0').hide();
				$('#msn').focus();
			}
		);
		$('#msn1').click
		(
			function ()
			{
				$('#msn0').show();
				$('#msn1').hide();
				$('.msn0').show();
				$('.msn1').hide();
				$('#msn').val($('#msntemp').val());
			}
		);
		$('#homepage0').click
		(
			function ()
			{
				$('#homepage1').show();
				$('#homepage0').hide();
				$('.homepage1').show();
				$('.homepage0').hide();
				$('#homepage').focus();
			}
		);
		$('#homepage1').click
		(
			function ()
			{
				$('#homepage0').show();
				$('#homepage1').hide();
				$('.homepage0').show();
				$('.homepage1').hide();
				$('#homepage').val($('#homepagetemp').val());
			}
		);
		$('#birthday0').click
		(
			function ()
			{
				$('#birthday1').show();
				$('#birthday0').hide();
				$('.birthday1').show();
				$('.birthday0').hide();
				$('#month').focus();
			}
		);
		$('#birthday1').click
		(
			function ()
			{
				$('#birthday0').show();
				$('#birthday1').hide();
				$('.birthday0').show();
				$('.birthday1').hide();
				$('#month').val($('#monthtemp').val());
				$('#day').val($('#daytemp').val());
				$('#year').val($('#yeartemp').val());
			}
		);
		$('#location0').click
		(
			function ()
			{
				$('#location1').show();
				$('#location0').hide();
				$('.location1').show();
				$('.location0').hide();
				$('#location').focus();
			}
		);
		$('#location1').click
		(
			function ()
			{
				$('#location0').show();
				$('#location1').hide();
				$('.location0').show();
				$('.location1').hide();
				$('#location').val($('#locationtemp').val());
			}
		);
		$('#interests0').click
		(
			function ()
			{
				$('#interests1').show();
				$('#interests0').hide();
				$('.interests1').show();
				$('.interests0').hide();
				$('#interests').focus();
			}
		);
		$('#interests1').click
		(
			function ()
			{
				$('#interests0').show();
				$('#interests1').hide();
				$('.interests0').show();
				$('.interests1').hide();
				$('#interests').val($('#intereststemp').val());
			}
		);
		$('#group0').click
		(
			function ()
			{
				$('#group1').show();
				$('#group0').hide();
				$('.group1').show();
				$('.group0').hide();
				$('#group').focus();
			}
		);
		$('#group1').click
		(
			function ()
			{
				$('#group0').show();
				$('#group1').hide();
				$('.group0').show();
				$('.group1').hide();
				$('#group').val($('#grouptemp').val());
			}
		);
		$('#title0').click
		(
			function ()
			{
				$('#title1').show();
				$('#title0').hide();
				$('.title1').show();
				$('.title0').hide();
				$('#title').focus();
			}
		);
		$('#title1').click
		(
			function ()
			{
				$('#title0').show();
				$('#title1').hide();
				$('.title0').show();
				$('.title1').hide();
				$('#title').val($('#titletemp').val());
			}
		);
		$('#avatar0').click
		(
			function ()
			{
				$('#avatar1').show();
				$('#avatar0').hide();
				$('.avatar1').show();
				$('.avatar0').hide();
				$('#avatar').focus();
			}
		);
		$('#avatar1').click
		(
			function ()
			{
				$('#avatar0').show();
				$('#avatar1').hide();
				$('.avatar0').show();
				$('.avatar1').hide();
				$('#avatar').val($('#avatartemp').val());
			}
		);
		$('#signature0').click
		(
			function ()
			{
				$('#signature1').show();
				$('#signature0').hide();
				$('.signature1').show();
				$('.signature0').hide();
				$('#signature').focus();
			}
		);
		$('#signature1').click
		(
			function ()
			{
				$('#signature0').show();
				$('#signature1').hide();
				$('.signature0').show();
				$('.signature1').hide();
				$('#signature').val($('#signaturetemp').val());
			}
		);
	}
);

function smiley (smiley)
{
	$('#content').val($('#content').val() + ' ' + smiley + ' ');
	$('#content').focus();
}

function tag (id, tag, open)
{
	if (open)
	{
		var txt = $('#content').val().substring(document.getElementById('content').selectionStart, document.getElementById('content').selectionEnd);
		if (txt)
		{
			start = document.getElementById('content').selectionStart;
			end = document.getElementById('content').selectionEnd;
			first = $('#content').val().substring(0, start);
			last = $('#content').val().substring(end);
			$('#content').val(first + '[' + tag + ']' + txt + '[/' + tag + ']' + last);
			document.getElementById('content').setSelectionRange(start, end + tag.length * 2 + 5);
		}
		else
		{
			$('#tag' + id + '0').hide();
			$('#tag' + id + '1').show();
			$('#content').val($('#content').val() + '[' + tag + ']');
			stack.push('[/' + tag + ']');
		}
	}
	else
	{
		$('#tag' + id + '0').show();
		$('#tag' + id + '1').hide();
		$('#content').val($('#content').val() + '[/' + tag + ']');
		stack.pop();
	}
	$('#content').focus();
}

function popup (message1, default1, message2, default2, tag)
{
	var equal = prompt(message1, default1);
	if (equal != null && equal != '')
	{
		var txt = $('#content').val().substring(document.getElementById('content').selectionStart, document.getElementById('content').selectionEnd);
		if (txt)
		{
			start = document.getElementById('content').selectionStart;
			end = document.getElementById('content').selectionEnd;
			first = $('#content').val().substring(0, start);
			last = $('#content').val().substring(end);
			$('#content').val(first + '[' + tag + '=' + equal + ']' + txt + '[/' + tag + ']' + last);
			document.getElementById('content').setSelectionRange(start, end + tag.length * 2 + equal.length + 6);
			$('#content').focus();
		}
		else
		{
			var main = prompt(message2, default2);
			if (main != null && main != '')
			{
				$('#content').val($('#content').val() + '[' + tag + '=' + equal + ']' + main + '[/' + tag + ']');
				$('#content').focus();
			}
		}
	}
}

function popuploop (message1, default1, separator, tag)
{
	var array = [];
	var response = '';
	do
	{
		response = prompt(message1, default1);
		if (response != null && response != '')
			array.push(separator + response);
	}
	while (response != null && response != '');
	if (array.length)
	{
		$('#content').val($('#content').val() + '[' + tag + ']\n' + array.join('\n') + '\n[/' + tag + ']');
		$('#content').focus();
	}
}

function dropdown (id, tag, equal)
{
	$('#dropdown' + id).attr
	(
		{ 
			selected: 'true'
		}
	);
	var txt = $('#content').val().substring(document.getElementById('content').selectionStart, document.getElementById('content').selectionEnd);
	if (txt)
	{
		start = document.getElementById('content').selectionStart;
		end = document.getElementById('content').selectionEnd;
		first = $('#content').val().substring(0, start);
		last = $('#content').val().substring(end);
		$('#content').val(first + '[' + tag + '=' + equal + ']' + txt + '[/' + tag + ']' + last);
		document.getElementById('content').setSelectionRange(start, end + tag.length * 2 + equal.length + 6);
		$('#content').focus();
	}
	else
	{
		$('#content').val($('#content').val() + '[' + tag + '=' + equal + ']');
		stack.push('[/' + tag + ']');
	}
	$('#content').focus();
}