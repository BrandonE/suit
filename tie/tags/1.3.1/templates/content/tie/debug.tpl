<a name="debug" />
<div class="header">
	<div class="left">
		<h1 class="title"><a href="#">(language=>debug)</a></h1>
	</div>
</div>
<div class="nav">
	<div class="space"></div>
	<div class="yesscript" style="display: none;">
		<div class="templates">
			<ul>
					<li class="selected"><a id="templates0" href="#debug">(language=>templates)</a></li>
					<li><a id="replace0" href="#debug">(language=>replacedebug)</a></li>
					<li><a id="parse0" href="#debug">(language=>parse)</a></li>
			</ul>
		</div>
		<div class="replace" style="display: none;">
			<ul>
					<li><a id="templates1" href="#debug">(language=>templates)</a></li>
					<li class="selected"><a id="replace1" href="#debug">(language=>replacedebug)</a></li>
					<li><a id="parse1" href="#debug">(language=>parse)</a></li>
			</ul>
		</div>
		<div class="parse" style="display: none;">
			<ul>
					<li><a id="templates2" href="#debug">(language=>templates)</a></li>
					<li><a id="replace2" href="#debug">(language=>replacedebug)</a></li>
					<li class="selected"><a id="parse2" href="#debug">(language=>parse)</a></li>
			</ul>
		</div>
	</div>
</div>
<div class="content">
	<div class="section">
		<div class="yesscript" style="display: none;">
			<div class="templates">
				<h2>(language=>templates)</h2>
				<table class="list" cellpadding="0" cellspacing="0">
					[loop templates]
					<tr class="list-entry-folder">
						<td width="25%">
							<span class="list-hidden">
								<a class="templateshow" id="template[id]0" href="#NULL" onclick="tab\('template[id]', true\);">(language=>expand)</a>
								<a class="templatehide" id="template[id]1" href="#NULL" onclick="tab\('template[id]', false\);" style="display: none;">(language=>collapse)</a>
							</span>
						</td>
						<td width="75%">
							[else notfound]
							[else infiniteloop]
							[title]
							[/else infiniteloop]
							[/else notfound]
							[if notfound]
							"[title]" - (language=>notfound)
							[/if notfound]
							[if infiniteloop]
							"[title]" - (language=>infiniteloop)
							[/if infiniteloop]
						</td>
					</tr>
					<tr class="list-entry templatehide template[id]" style="display: none;">
						<td>
							[if content]
							<span class="list-hidden">
								<a class="templateboxshow template[id]boxshow" id="template[id]contentbox0" href="#template[id]contentbox" onclick="box\('template', '[id]', 'content', true\);">(language=>expand)</a>
								<a class="templateboxhide template[id]boxhide" id="template[id]contentbox1" href="#NULL" onclick="box\('template', '[id]', 'content', false\);" style="display: none;">(language=>collapse)</a>
							</span>
							[/if content]
						</td>
						<td>
							(language=>content):
							[if content]
							[content]
							[/if content]
							[else content]
							"[content]" - (language=>notfound)
							[/else content]
						</td>
					</tr>
					[loop code]
					<tr class="list-entry templatehide template[id]" style="display: none;">
						<td>
							[if content]
							[if code]
							<span class="list-hidden">
								<a class="templateboxshow template[id]boxshow" id="template[id]code[id2]box0" href="#template[id]code[id2]box" onclick="box\('template', '[id]', 'code[id2]', true\);">(language=>expand)</a>
								<a class="templateboxhide template[id]boxhide" id="template[id]code[id2]box1" href="#NULL" onclick="box\('template', '[id]', 'code[id2]', false\);" style="display: none;">(language=>collapse)</a>
							</span>
							[/if code]
							[/if content]
						</td>
						<td>
							(language=>code):
							[if code]
							[code]
							[/if code]
							[else code]
							"[code]" - (language=>notfound)
							[/else code]
						</td>
					</tr>
					[/loop code]
					<tr class="list-entry templatehide template[id]" style="display: none;">
						<td>
							<span class="list-hidden">
								<a class="templateboxshow template[id]boxshow" id="template[id]signaturebox0" href="#template[id]signaturebox" onclick="box\('template', '[id]', 'signature', true\);">(language=>expand)</a>
								<a class="templateboxhide template[id]boxhide" id="template[id]signaturebox1" href="#NULL" onclick="box\('template', '[id]', 'signature', false\);" style="display: none;">(language=>collapse)</a>
							</span>
						</td>
						<td>
							(language=>signature)
						</td>
					</tr>
					<tr class="list-entry templatehide template[id]" style="display: none;">
						<td />
						<td>
							(language=>file): [file]
						</td>
					</tr>
					<tr class="list-entry templatehide template[id]" style="display: none;">
						<td />
						<td>
							(language=>line): [line]
						</td>
					</tr>
					[/loop templates]
					[else templates]
					<tr class="list-entry-folder">
						<td width="25%" />
						<td width="75%">
							(language=>empty)
						</td>
					</tr>
					[/else templates]
				</table>
				[loop templates]
				[if content]
				<p class="templatehide templateboxhide template[id]boxhide" id="template[id]contentbox" style="display: none;">
					<textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[contentfile]</textarea>
				</p>
				[/if content]
				[loop code]
				[if content]
				[if code]
				<p class="templatehide templateboxhide template[id]boxhide" id="template[id]code[id2]box" style="display: none;">
					<textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[if codefile][codefile][/if codefile][else codefile](language=>na)[/else codefile]</textarea>
				</p>
				[/if code]
				[/if content]
				[/loop code]
				<p class="templatehide templateboxhide template[id]boxhide" id="template[id]signaturebox" style="display: none;">
					<textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
\([template]\)</textarea>
				</p>
				[/loop templates]
			</div>
			<div class="replace" style="display: none;">
			<h2>(language=>replacedebug)</h2>
				<table class="list" cellpadding="0" cellspacing="0">
					[loop replace]
					<tr class="list-entry-folder">
						<td width="25%">
							<span class="list-hidden">
								<a class="replaceshow" id="replace[id]0" href="#NULL" onclick="tab\('replace[id]', true\);">(language=>expand)</a>
								<a class="replacehide" id="replace[id]1" href="#NULL" onclick="tab\('replace[id]', false\);" style="display: none;">(language=>collapse)</a>
							</span>
						</td>
						<td width="75%">
							[title]
							[if errors]
							- (language=>errors)
							[/if errors]
						</td>
					</tr>
					<tr class="list-entry replacehide replace[id]" style="display: none;">
						<td>
							<span class="list-hidden">
								<a class="replaceboxshow replace[id]boxshow" id="replace[id]contentbox0" href="#replace[id]contentbox" onclick="box\('replace', '[id]', 'content', true\);">(language=>expand)</a>
								<a class="replaceboxhide replace[id]boxhide" id="replace[id]contentbox1" href="#NULL" onclick="box\('replace', '[id]', 'content', false\);" style="display: none;">(language=>collapse)</a>
							</span>
						</td>
						<td>
							(language=>content)
						</td>
					</tr>
					[loop step]
					<tr class="list-entry replacehide replace[id]" style="display: none;">
						<td>
							<span class="list-hidden">
								<a class="replaceboxshow replace[id]boxshow" id="replace[id]step[id2]box0" href="#replace[id]step[id2]box" onclick="box\('replace', '[id]', 'step[id2]', true\);">(language=>content)</a>
								<a class="replaceboxhide replace[id]boxhide" id="replace[id]step[id2]box1" href="#NULL" onclick="box\('replace', '[id]', 'step[id2]', false\);" style="display: none;">(language=>collapse)</a>
								<a class="replaceboxshow replace[id]boxshow" id="replace[id]array[id2]box0" href="#replace[id]array[id2]box" onclick="box\('replace', '[id]', 'array[id2]', true\);">(language=>array)</a>
								<a class="replaceboxhide replace[id]boxhide" id="replace[id]array[id2]box1" href="#NULL" onclick="box\('replace', '[id]', 'array[id2]', false\);" style="display: none;">(language=>collapse)</a>
							</span>
						</td>
						<td>
							(language=>replacedebug)
							[if steperrors]
							- (language=>errors)
							[/if steperrors]
						</td>
					</tr>
					[/loop step]
					<tr class="list-entry replacehide replace[id]" style="display: none;">
						<td>
							<span class="list-hidden">
								<a class="replaceboxshow replace[id]boxshow" id="replace[id]signaturebox0" href="#replace[id]signaturebox" onclick="box\('replace', '[id]', 'signature', true\);">(language=>expand)</a>
								<a class="replaceboxhide replace[id]boxhide" id="replace[id]signaturebox1" href="#NULL" onclick="box\('replace', '[id]', 'signature', false\);" style="display: none;">(language=>collapse)</a>
							</span>
						</td>
						<td>
							(language=>signature)
						</td>
					</tr>
					<tr class="list-entry replacehide replace[id]" style="display: none;">
						<td />
						<td>
							(language=>file): [file]
						</td>
					</tr>
					<tr class="list-entry replacehide replace[id]" style="display: none;">
						<td />
						<td>
							(language=>line): [line]
						</td>
					</tr>
					[/loop replace]
					[else replace]
					<tr class="list-entry-folder">
						<td width="25%" />
						<td width="75%">
							(language=>empty)
						</td>
					</tr>
					[/else replace]
				</table>
				[loop replace]
				<p class="replacehide replaceboxhide replace[id]boxhide" id="replace[id]contentbox" style="display: none;">
					<textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[content]</textarea>
				</p>
				[loop step]
				<p class="replacehide replaceboxhide replace[id]boxhide" id="replace[id]step[id2]box" style="display: none;">
					<textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[step]</textarea>
				</p>
				<p class="replacehide replaceboxhide replace[id]boxhide" id="replace[id]array[id2]box" style="display: none;">
					<textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[array]</textarea>
				</p>
				[/loop step]
				<p class="replacehide replaceboxhide replace[id]boxhide" id="replace[id]signaturebox" style="display: none;">
					<textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
\([array], [return], [label]\)</textarea>
				</p>
				[/loop replace]
			</div>
			<div class="parse" style="display: none;">
			<h2>(language=>parse)</h2>
				<table class="list" cellpadding="0" cellspacing="0">
					[loop parse]
					<tr class="list-entry-folder">
						<td width="25%">
							<span class="list-hidden">
								<a class="parseshow" id="parse[id]0" href="#NULL" onclick="tab\('parse[id]', true\);">(language=>expand)</a>
								<a class="parsehide" id="parse[id]1" href="#NULL" onclick="tab\('parse[id]', false\);" style="display: none;">(language=>collapse)</a>
							</span>
						</td>
						<td width="75%">
							[title]
							[if errors]
							- (language=>errors)
							[/if errors]
						</td>
					</tr>
					<tr class="list-entry parsehide parse[id]" style="display: none;">
						<td>
							<span class="list-hidden">
								<a class="parseboxshow parse[id]boxshow" id="parse[id]contentbox0" href="#parse[id]contentbox" onclick="box\('parse', '[id]', 'content', true\);">(language=>expand)</a>
								<a class="parseboxhide parse[id]boxhide" id="parse[id]contentbox1" href="#NULL" onclick="box\('parse', '[id]', 'content', false\);" style="display: none;">(language=>collapse)</a>
							</span>
						</td>
						<td>
							(language=>content)
						</td>
					</tr>
					<tr class="list-entry parsehide parse[id]" style="display: none;">
						<td>
							<span class="list-hidden">
								<a class="parseboxshow parse[id]boxshow" id="parse[id]returnbox0" href="#parse[id]returnbox" onclick="box\('parse', '[id]', 'return', true\);">(language=>expand)</a>
								<a class="parseboxhide parse[id]boxhide" id="parse[id]returnbox1" href="#NULL" onclick="box\('parse', '[id]', 'return', false\);" style="display: none;">(language=>collapse)</a>
							</span>
						</td>
						<td>
							(language=>parse)
						</td>
					</tr>
					<tr class="list-entry parsehide parse[id]" style="display: none;">
						<td>
							<span class="list-hidden">
								<a class="parseboxshow parse[id]boxshow" id="parse[id]treebox0" href="#parse[id]treebox" onclick="box\('parse', '[id]', 'tree', true\);">(language=>expand)</a>
								<a class="parseboxhide parse[id]boxhide" id="parse[id]treebox1" href="#NULL" onclick="box\('parse', '[id]', 'tree', false\);" style="display: none;">(language=>collapse)</a>
							</span>
						</td>
						<td>
							(language=>tree)
						</td>
					</tr>
					<tr class="list-entry parsehide parse[id]" style="display: none;">
						<td>
							<span class="list-hidden">
								<a class="parseboxshow parse[id]boxshow" id="parse[id]signaturebox0" href="#parse[id]signaturebox" onclick="box\('parse', '[id]', 'signature', true\);">(language=>expand)</a>
								<a class="parseboxhide parse[id]boxhide" id="parse[id]signaturebox1" href="#NULL" onclick="box\('parse', '[id]', 'signature', false\);" style="display: none;">(language=>collapse)</a>
							</span>
						</td>
						<td>
							(language=>signature)
						</td>
					</tr>
					<tr class="list-entry parsehide parse[id]" style="display: none;">
						<td />
						<td>
							(language=>file): [file]
						</td>
					</tr>
					<tr class="list-entry parsehide parse[id]" style="display: none;">
						<td />
						<td>
							(language=>line): [line]
						</td>
					</tr>
					[/loop parse]
					[else parse]
					<tr class="list-entry-folder">
						<td width="25%" />
						<td width="75%">
							(language=>empty)
						</td>
					</tr>
					[/else parse]
				</table>
				[loop parse]
				<p class="parsehide parseboxhide parse[id]boxhide" id="parse[id]contentbox" style="display: none;">
					<textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[before]</textarea>
				</p>
				<p class="parsehide parseboxhide parse[id]boxhide" id="parse[id]returnbox" style="display: none;">
					<textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[return]</textarea>
				</p>
				<p class="parsehide parseboxhide parse[id]boxhide" id="parse[id]treebox" style="display: none;">
					<textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
[tree]</textarea>
				</p>
				<p class="parsehide parseboxhide parse[id]boxhide" id="parse[id]signaturebox" style="display: none;">
					<textarea rows="40" cols="100" wrap="off" style="width: 100%;" class="textarea" readonly="readonly">
\([nodes], [content], [replace], [escape], [match], [label]\)</textarea>
				</p>
				[/loop parse]
			</div>
		</div>
		<noscript>
			<center>
				<p>
					(language=>enablejavascript)
				</p>
			</center>
		</noscript>
	</div>
</div>