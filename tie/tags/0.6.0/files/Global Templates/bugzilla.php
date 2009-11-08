<?php       
/**
**@This file is part of The SUIT Framework.

**@SUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.

**@SUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.
**/
function bugzilla ($file, &$suit)
{
	$perl = new Perl();
	chdir('/home/suit/public_html/bugzilla/');
	$bugzilla = file_get_contents($file); 
	ob_start();
	$perl->eval($bugzilla);
	$ob = ob_get_contents();
	ob_end_clean();              
	$css_vars = $suit->templates->getTemplate('css', array());
	$css = $css_vars['output'];
	$top_vars = $suit->templates->getTemplate('top', array());
	$top = $top_vars['output'];
	$footer_vars = $suit->templates->getTemplate('footer', array());
	$footer = $footer_vars['output'];
	$array = array
	(
		array('<css>', $css),
		array('<top>', $top),
		array('<footer>', $footer)
	);
	$ob = $suit->templates->replace($ob, $array);  
	return $ob;
}
?>
