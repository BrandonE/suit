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
	Determine if we should display any MySQL errors.
	@var boolean
	**/
	var $show_errors = 1;
	
	/**
	Counts the total number of queries.
	@var integer
	**/
	var $query_count = 0;
	
	/**
	The current established DB connection.
	@var resource
	**/
	var $connection;
	
	/**
	Execute a MySQL Query on the server.
	**@param string MySQL Query
	
	**@returns resource MySQL Query
	**/
	function query($sql)
	{
		if (mysql_query($sql))
		{
			//The MySQL query was executed succesfully. Now we return the query.
			return mysql_query($sql);
			//Up the query count +1.
			++$this->query_count;
		}
		else
		{
			//There was a problem processing the query.
			$this->error($sql);
		}
	}
	
	/**
	Output the Error number, the Error itself and the query attempting to execute it.
	**@param string MySQL Query
	**/
	function error($sql = '')
	{
		$error = mysql_error();
		$error_number = mysql_errno();
		//I plan to implement a line counting system in the future and more advanced SQL Debugging techniques to make it easier for developers.
		print 'Error (#' . $error_number . '): ' . $error . '<br />';
		print 'Query: ' . $sql . '<br />';
	}
	
	/**
	Connects to MySQL with the specified user details.
	**@param string DB Host
	**@param string DB User
	**@param string DB Password
	
	**@returns resource Database Connection Link
	**/
	function connect($host = 'localhost', $user = 'root', $password = '')
	{
		//Create a variable, for future use.
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
		else
		{
			print 'A connection with the MySQL server could not be established with the provided username and password.';
			
			if ($this->show_errors)
			{
				$this->error();
			}
		}
	}
	
	/**
	Select rows from a MySQL Table
	**@param string MySQL Table
	**@param string The fields to select
	**@param array Additional Querying Options
	
	**@returns resource SELECT Query.
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
			
			if (in_array($options['orderby_type'], $valid_types))
			{
				$sql .= ' ORDER BY `'.$options['orderby'].'` '.$options['orderby_type'].'';
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
	
	/**
	Add data into a MySQL Table with the specified information/
	**@param string MySQL Table
	**@param array MySQL Fields
	**@param array MySQL Field Values
	
	**@returns boolean True/False on success/fail
	**/
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
	**@param string The variable to be escaped.
	**@param boolean Escape SQL Wildcards?
	
	**@returns string The escapes string.
	**/
	function escape($value, $wildcards = false) 
	{
		$return_value = $value;
		//Detect magic quotes.
		if (get_magic_quotes_gpc())
		{
			if (ini_get('magic_quotes_sybase')) 
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
	/*
	Unescape
	*/
	function unescape($value)
	{
		return stripslashes($value);
	}
}

$mn = "MySQL";
?>
