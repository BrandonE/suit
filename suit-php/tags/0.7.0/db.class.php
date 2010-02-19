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
	var $show_errors;
	
	/**
	Counts the total number of queries.
	@var integer
	**/
	var $query_count;
	
	/**
	The total amount of queries
	@var string
	**/
	var $query_list;
	
	/**
	The current established DB connection.
	@var resource
	**/
	var $connection;
	
	
	function __construct()
	{
		$this->show_errors = 1;
		$this->query_count = 0;
		$this->query_list = array();
	}
	
	/**
	Connects to MySQL with the specified user details.
	**@param string DB Host
	**@param string DB User
	**@param string DB Password
	**@param string DB Name
	**@param integer DBI Port Number; assumes 3306 by default.
	**@returns none
	**/
	function connect($host = 'localhost', $user = 'root', $password = '', $database, $port = 3306)
	{
		//Create a variable, for future use.
		$conn = mysql_connect($host, $user, $password, $port);
		
		//Connection has succeeded.
		if ($conn)
		{
			//Create a reference for the established link.
			$this->connection = &$conn;
			//Select the MySQL Database, and supply the link as a second argument.
			mysql_select_db($database, $this->connection);
		}
		else
		{
			//Connection has failed, now inform the user about this.
			print 'A connection with the MySQL server could not be established with the provided username and password.';
			exit;
		}
	}
	
	/**
	Execute a MySQL Query on the server.
	**@param string MySQL Query
	
	**@returns resource MySQL Query
	**/
	function query($sql)
	{
		$result = mysql_query($sql);
		if ($result)
		{
			//Add to the query count.
			$this->query_count += 1;
			//Store this query in the array
			$this->query_list[] = $sql;
			//The MySQL query was executed succesfully. Now we return the query.
			return $result;
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
	Select rows from a MySQL Table
	**@param string MySQL Table
	**@param string The fields to select
	**@param array Additional Querying Options
	
	**@returns resource SELECT Query.
	**/
	function select($table, $fields, $options = array())
	{
		//Begin the query with the usual select fields from table, and we can either add more to it, or leave as is.
		$sql = 'SELECT ' . $fields . ' FROM `'. $table .'`';
		
		//The WHERE Clause
		if (isset($options['where']))
		{
			$sql .= ' WHERE ' . $options['where'];
		}
		
		if (isset($options['orderby']))
		{
			$valid_types = array('asc', 'desc'); //Set the valid ordering types.
			
			$sql .= ' ORDER BY `' . $options['orderby'] . '`';
			 
			if (isset($options['orderby_type']) && in_array(strtolower($options['orderby_type']), $valid_types))
			{
				//The ordering type is valid; so far so good.
				$sql .= ' ' . strtoupper($options['orderby_type']) . '';
			}
		}
		//Limitiing options.
		if (isset($options['limit']))
		{
			//Explodes the contents of the limit options key into an array, and list() them. Limit the explode to 2 maximum, in order to keep the query clean.
			list($start, $end) = explode(':', $options['limit'], 2);
			$sql .= ' LIMIT ' . $start . ', ' . $end;
		}
		
		//End the SQL Query, and prepare for querying.
		$sql .= ';';
		$query = $this->query($sql);
		//If the query ran succesfully and returned at least 1 result, then it shall return the $result.
		if ($query && mysql_num_rows($query) != 0)
		{
			//Commonly used for while() loops and for fetching arrays of collected results.
			//It will always evaluate to true, so it poses no problem.
			return $query;
		}
		else
		{
			//This query didn't go well.
			return false;
		}
	}
	
	/**
	Add data into a MySQL Table with the specified information/
	**@param string MySQL Table
	**@param array MySQL Fields
	**@param array MySQL Field Values
	
	**@returns boolean True/False on success/fail
	**/
	function insert($table, $fields = '', $values)
	{		
		$sql = 'INSERT INTO `'. $table .'`';
		
		//Gather the fields to begin the insert, and set the values to fill in the fields with
		if (is_array($fields))
		{
			$fields = implode(',', $fields);
			$fields = '(' . $fields . ')';
		}
		
		$values = '\'' . implode("','", $values) . '\'';
		//Now we'll use the above information to form our insert query.
		$sql .= $fields . ' VALUES(' . $values .')';
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
	Select rows from a MySQL Table
	**@param string MySQL Table
	**@param array Querying Options,
	
	**@returns resource SELECT Query.
	**/
	function update($table, $options = array())
	{
		//Begin the query with the usual select fields from table, and we can either add more to it, or leave as is.
		$sql = 'UPDATE `' . $table . '`';
		
		//The SET clause, making sure it is always set before appending anything else.
		if (isset($options['set']))
		{
			$sql .= ' SET '.$options['where'].'';
			//The WHERE Clause
			if (isset($options['where']))
			{
				$sql .= ' WHERE '.$options['where'].'';
			}
			
			if (isset($options['orderby']))
			{
				$valid_types = array('asc', 'desc'); //Set the valid ordering types.
				
				$sql .= ' ORDER BY `'.$options['orderby'].'`';
				 
				if (isset($options['orderby_type']) && in_array($options['orderby_type'], $valid_types))
				{
					//The ordering type is valid; so far so good.
					$sql .= ' '.$options['orderby_type'].'';
				}
			}
			//Limitiing options.
			if (isset($options['limit']))
			{
				//Explodes the contents of the limit options key into an array, and list() them. Limit the explode to 2 maximum, in order to keep the query clean.
				list($start, $end) = explode(':', $options['limit'], 2);
				$sql .= ' LIMIT '.$start.', '.$end.'';
			}
		}
		//End the SQL Query, and prepare for querying.
		$sql .= ';';
		$query = $this->query($sql);
		//If the query ran succesfully and returned at least 1 result, then it shall return the $result.
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
	Empties a table
	**@param string MySQL Table
	
	**@returns boolean True if succesful, False if it failed.
	**/
	function truncate($table)
	{
		if (isset($table))
		{
			$sql = 'TRUNCATE `' . $table . '`';
			$query = $this->query($sql);
			
			if ($query)
			{
				return true;
			}
			else
			{
				return false;
			}
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
		$return = '';
		//Detect magic quotes.
		if (get_magic_quotes_gpc())
		{
			if (ini_get('magic_quotes_sybase')) 
			{
				$value = str_replace("''", "'", $value);
			} 
			else 
			{
				$value = stripslashes($value);
			}
		}
		//Escape wildcards for SQL injection protection on LIKE, GRANT, and REVOKE commands.
		if ($wildcards == true)
		{
			$value = str_replace('%', '\%', $value);
			$value = str_replace('_', '\_', $value);
		}
		//Finally, return the end result with the addition of mysql string escaping.
		$return = htmlentities($value);
		return mysql_real_escape_string($return);
	}
	
	/**
	Unescapes a string
	**@param string Value to unescape.
	
	**@areturns string Unescaped Value
	**/
	function unescape($value)
	{
		return stripslashes($value);
	}
}

$mn = "MySQL";
?>
