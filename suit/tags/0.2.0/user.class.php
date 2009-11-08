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
class UserSystem extends SUIT
{
	/**
	Grabs a user from the database based on ID
	@param int User's ID
	@returns resource The query to grab the user for use in mysql_fetch_assoc() or mysql_fetch_array().
	**/
	function fetchUser($id)
	{
		$id = intval($id);
		$fetchuser_options = array('where' => 'id = \'' . $id . '\'');
		$fetchuser = parent::MySQL::select('' . TBL_PREFIX . 'users', 'id', $fetchuser_options);
		
		if ($fetchuser)
		{
			return $fetchuser;
		}
		else
		{
			return false;
		}
	}
}
$mn = "UserSystem";
?>