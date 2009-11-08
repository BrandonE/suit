<?php/****@This file is part of The SUIT Framework.**@SUIT is free software: you can redistribute it and/or modify**@it under the terms of the GNU General Public License as published by**@the Free Software Foundation, either version 3 of the License, or**@(at your option) any later version.**@SUIT is distributed in the hope that it will be useful,**@but WITHOUT ANY WARRANTY; without even the implied warranty of**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the**@GNU General Public License for more details.**@You should have received a copy of the GNU General Public License**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.**/
//No guests using the styleswitcher.
if ($suit->loggedIn() > 0)
{	
	//Select themes.
	$themeselect_options = array('orderby' => 'title', 'orderby_type' => 'asc');
	$themeselect = $suit->db->select(TBL_PREFIX . 'themes', 'id, title', $themeselect_options);
	//Grab the template for the option fields.
	$themeswitcher_field_vars = $suit->templates->getTemplate('themeswitcher_field', $chains);
	$themeswitcher_field = $themeswitcher_field_vars['output'];
	$list = '';

	if ($themeselect)
	{
		//We found some themes.
		while ($row = mysql_fetch_assoc($themeselect))
		{	
			$list .= $themeswitcher_field;
			
			if ($suit->user['theme'] == $row['id'])
			{
				$selected = ' selected';
			}
			else
			{
				$selected = '';
			}
			
			$array = array
			(
				array('<1>', $row['id']),
				array('<2>', $row['title']),
				array('<3>', $selected)
			);
			$list = $suit->templates->replace($list, $array);
		}
	}
	else
	{
                $list .= $themeswitcher_field;
		//No themes at the moment.
		$array = array
		(
			array('<1>', '0'),
			array('<2>', $lcontent),
			array('<3>', '')
		);
                $list = $suit->templates->replace($list, $array);
	}
	
	if ($suit->user['theme'] == 0)
	{
                $selected = ' selected';
	}
        else
        {
                $selected = '';
        }

	$array = array
	(
		array('<1>', basename($_SERVER['SCRIPT_NAME'])), //This should be the action attribute for the form, will add afterwards.
		array('<2>', $list),
        array('<3>', $selected)
	);
	
	$output = $suit->templates->replace($output, $array);
}
?>