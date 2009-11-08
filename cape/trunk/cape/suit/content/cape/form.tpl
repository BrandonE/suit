(message)
					[if preview]
					(posts)
					[/if preview]
			<table style="width: 100%;">
				<tr>
					<td style="width: 25%;">
						[loop smilies]
						<span class="yesscript" style="display: none;[style]">
							<a href="#NULL" onclick="smiley\('[code]'\);"><img title="[smileytitle]" alt="[smileytitle]" src="(smileypath)/[smileytitle].gif" /></a>
						</span>
						<noscript>
							<img title="[smileytitle]" alt="[smileytitle]" src="(smileypath)/[smileytitle].gif" />
						</noscript>
						[/loop smilies]
					</td>
					<td style="width: 75%;">
						<div class="yesscript" style="display: none;[style]">
							[loop tags]
							<input type="button" class="tagshow" id="tag[id]0" onclick="tag\('[id]', '[tag]', true\);" style="[style]" value="[label]" />
							<input type="button" class="taghide" id="tag[id]1" onclick="tag\('[id]', '[tag]', false\);" style="display: none;[style]" value="[label]*" />
							[/loop tags]
							[loop popups]
							<input type="button" onclick="[else loop]popup\('[message1]', '[default1]', '[message2]', '[default2]', '[tag]'\);[/else loop][if loop]popuploop\('[message1]', '[default1]', '[separator]', '[tag]'\);[/if loop]" style="[style]" value="[label]" />
							[/loop popups]
							[loop dropdowns]
							<select>
								<option id="dropdown[id]" style="[style]">[label]</option>
								[loop options]
								<option onclick="dropdown\('[id]', '[tag]', '[equal]'\);" style="[optionstyle]">[optionlabel]</option>
								[/loop options]
							</select>
							[/loop dropdowns]
							<a id="closetags" href="#NULL">(language=>closetags)</a>
						</div>
						<noscript><p>(language=>enablejavascript)</p></noscript>
						<form name="form" action="#" method="post">
						<p>(language=>inputtitle): <input type="text" name="title" value="(title)" /></p>
						<p>(language=>content): <textarea name="content" id="content" rows="20" style="width: 100%">
(contentbox)</textarea></p>
						<p><input type="checkbox" name="smilies" value="1"[if formsmilies] checked[/if formsmilies] /> (language=>smilies)</p>
						<p><input type="checkbox" name="signature" value="1"[if formsignature] checked[/if formsignature] /> (language=>signature)</p>
						<p><input type="submit" name="(name)" value="(language=>submit)" /> <input type="submit" name="preview" value="(language=>preview)" /></p>
						</form>
					</td>
				</tr>
			</table>