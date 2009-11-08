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
class MySQL
{
	/**
	Execute a MySQL Query on the server.
	**@param string
	**/
	function query($sql)
	{
		if (mysql_query($sql))
		{
			return mysql_query($sql);
		}
		else
		{
			print 'Error (#' . mysql_errno() . '): ' . mysql_error() . '<br />';
			print 'Query: ' . $sql . '<br />';
		}
	}
	
	function connect($host = 'localhost', $user = 'root', $password = '')
	{
		$conn = mysql_connect($host, $user, $password);
		
		if ($conn)
		{
			//Create a resource link
			$this->connection = $conn;
			//Select the MySQL Database from configuration file.
			mysql_select_db('' . SQL_DB . '', $this->connection);
			//In-case the user wants to put it to use. ;)
			return $this->connection;
		}
	}
	
	/**
	Select rows from a MySQL Table
	**@param string
	**@param string
	**@param array
	**/
	function select($table, $fields, $options = array())
	{	
		$sql = 'SELECT ' . $fields . ' FROM `'. $table .'`';
		
		if (isset($options['where']))
		{
			$sql .= ' WHERE '.$options['where'].'';
		}
		
		if (isset($options['orderby']) && isset($options['orderby_type']))
		{
			$valid_types = array('asc', 'desc');
			
			if (in_array(strtoupper($options['orderby_type']), $valid_types))
			{
				$sql .= ' ORDER BY `'.$field.'`';
			}
		}
		
		if (isset($options['limit']))
		{
			list($start, $end) = explode(':', $options['limit'], 2);
			$sql .= ' LIMIT '.$start.', '.$end.'';
		}
		
		//End the SQL Query, and prepare for querying.
		$sql .= ';';
		$query = $this->query($sql);
		//If the query ran succesfully and returned at least 1 result, then it shall return the $result.
		if ($query)
		{
			if (mysql_num_rows($query) > 0)
			{
				return $query;
			}
		}
	}
	
	function insert($table, $fields, $values)
	{		
		$sql = 'INSERT INTO `'. $table .'`';
		
		//Gather the fields to begin the insert, and set the values to fill in the fields with
		$fields = '`'.implode('`,`', array_keys($fields)).'`';
		$values = '\''.implode("','", $values).'\'';
		//Now we'll use the above information to form our insert query.
		$sql .= '(' . $fields . ') VALUES(' . $values .')';
		//End the SQL Query by appending a semi-colon, and run the query.
		$sql .= ';';
		$query = $this->query($sql);
		//If the query ran succesfully, it shall return true. On the other hand, if the query failed, then it will return false.
		if ($query)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	Escapes a string
	**@param string
	**@param bool
	**@param booll
	**@return string
	**/
	function escape($value, $wildcards = false) 
	{
		$return_value = $value;
		//Detect magic quotes.
		if (get_magic_quotes_gpc())
		{
			if(ini_get('magic_quotes_sybase')) 
			{
				$return_value = str_replace("''", "'", $return_value);
			} 
			else 
			{
				$return_value = stripslashes($return_value);
			}
		}
		//Escape wildcards for SQL injection protection on LIKE, GRANT, and REVOKE commands.
		if ($wildcards == true)
		{
			$return_value = str_replace('%','\%',$return_value);
			$return_value = str_replace('_','\_',$return_value);
		}
		//Finally, return the end result with the addition of mysql string escaping.
		$return_value = htmlspecialchars($return_value, ENT_QUOTES);
		return mysql_real_escape_string($return_value);
	}
}

$mn = "MySQL";
?>
