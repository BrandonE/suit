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
class Database
{
	/**
	Determine if we should display any Database errors.
	@var boolean
	**/
	var $show_errors = 1;
	
	/**
	Counts the total number of queries.
	@var integer
	**/
	var $query_count = 0;
	
	/**
	The total amount of queries
	@var string
	**/
	var $query_list = array();
	
	/**
	The current established DB connection.
	@var resource
	**/
	var $connection;
	
	public function libopensql($sql)
	{
		$old_err_reporting = error_reporting(E_ALL ^ E_NOTICE);
		$sqlinfo = $this->__internal_split($sql);
		
		// Basic stuff
		$INFO['COMMAND'] = $sqlinfo[0];
		switch (trim($INFO['COMMAND']))
		{
			case 'SELECT':
				$offset = -1;
				
				$concat = '';
				for ($s=1;$s<count($sqlinfo);$s++)
				{
					if ($sqlinfo[$s] == 'FROM')
						break;
					$concat .= $sqlinfo[$s];
					$offset++;
				}
				
				$INFO['COLUMNS'] = $concat;
				break;
			case 'UPDATE':
				$offset = -2;
				$INFO['COLUMNS'] = '';
				break;
			case 'DELETE':
				$offset = -1;
				$INFO['COLUMNS'] = '';
				break;
			case 'INSERT':
				$offset = -1;
				$INFO['COLUMNS'] = '';
				break;
			default:
				$offset = -2;
				$INFO['COLUMNS'] = '';
				break;
		}
		
		// Get the tables (and their identifiers)
		$INFO['TABLES'] = array();
		$concat = '';
		for ($s=$offset+3;$s<count($sqlinfo);$s++)
		{
			if ($sqlinfo[$s] == 'WHERE' || $sqlinfo[$s] == 'SET' || $sqlinfo[$s] == 'LIMIT' || $sqlinfo[$s] == 'ORDER' || substr($sqlinfo[$s],0,1) == '(')
				break;
			$concat .= $sqlinfo[$s]." ";
		}
		$concat_comma_explode = explode(",",$concat);
		for ($i=0;$i<count($concat_comma_explode);$i++)
		{
			$dat = trim($concat_comma_explode[$i]);
			if (substr_count($dat,' AS ')!=0)
			{
				$sas = explode(' AS ',$concat_comma_explode[$i]);
				if (count($sas) != 2)
					trigger_error("Invalid table list.",E_USER_ERROR);
				$table = trim($sas[0]);
				$ident = trim($sas[1]);
				$INFO['TABLES'][$ident] = $table;
			}
			elseif (substr_count($dat,' as ')!=0)
			{
				$sas = explode(' as ',$concat_comma_explode[$i]);
				if (count($sas) != 2)
					trigger_error("Invalid table list.",E_USER_ERROR);
				$table = trim($sas[0]);
				$ident = trim($sas[1]);
				$INFO['TABLES'][$ident] = $table;
			}
			else
				$INFO['TABLES'][$dat] = $dat;
		}
		
		if ($INFO['COMMAND'] == "INSERT")
		{
			// ### Insert requires special treatment as it is not structured like the others ###
			$at_values = false;
			
			// get columns
			$is_first = true;
			$in_row = false;
			$is_string = false;
			$rownum = -1;
			$current_row = array();
			$base = 0;
			for ($s=3;$s<count($sqlinfo);$s++)
			{
				if ($at_values == false)
				{
					if ($sqlinfo[$s] == 'VALUES')
					{
						$at_values = true;
						$base = 0;
						continue;
					}
					if (trim($sqlinfo[$s])!="VALUES")
					{
						if ($is_first)
						{
							if ($sqlinfo[$s+1] == 'VALUES')
								$INFO['COLUMNS'][$base] = trim(substr(substr($sqlinfo[$s],0,strlen($sqlinfo[$s])-1),1),",");
							else
								$INFO['COLUMNS'][$base] = trim(substr($sqlinfo[$s],1),",");
							$is_first = false;
						}
						else
						{
							if ($sqlinfo[$s+1] == 'VALUES')
								$INFO['COLUMNS'][$base] = trim(substr($sqlinfo[$s],0,strlen($sqlinfo[$s])-1),",");
							else
								$INFO['COLUMNS'][$base] = trim($sqlinfo[$s],",");
						}
						$base++;
					}
				}
				else
				{
					if (!$in_row)
					{
						if (substr($sqlinfo[$s],0,1)=='(')
						{
							$in_row = true;
							$rownum++;
							$current_row = '';
							$INFO['ROWS'][$rownum] = array();
							if (strlen($data)==1)
								$base = 0;
							else
								$base = 0;
							$data = substr($sqlinfo[$s],1);
							//$base = 0;
						}
						else
						{
							//trigger_error("Malformed insert list.",E_USER_ERROR);
						}
					}
					elseif (substr($sqlinfo[$s],0,1)!="'")
					{
						// check for ) and ,
						if (strpos($sqlinfo[$s],")")!=-1)
						{
							if ((strpos($sqlinfo[$s],")")<strpos($sqlinfo[$s],",")) ||
								(strpos($sqlinfo[$s],")")<strpos($sqlinfo[$s],";")))
							{
								// stop row
								$in_row = false;
								
								$data = $current_row;//implode("",$current_row);
								$INFO['ROWS'][$rownum] = $data;
							}
						}
						$data = $sqlinfo[$s];
					}
					else
					{
						//echo $data." is a string...\n";
						$data = $sqlinfo[$s];
					}
					$tdata = trim($data);
					if (!empty($tdata) && $data != NULL)
					{
						$current_row[$INFO['COLUMNS'][$base]] = trim($tdata,",");
						$ttdata = trim($tdata,",");
						if (!empty($ttdata))
							$base++;
					}
					
					// this variable isn't used anymore
					$is_string = !$is_string;
				}
			}
			$return_string = $this->__internal_parse($INFO);
			error_reporting($old_err_reporting);
			return $return_string;
		} // end if INSERT check
		
		// Get the WHERE statement, if it exists
		if (in_array("WHERE",$sqlinfo))
		{
			foreach ($sqlinfo as $k => $i)
				if ($i == "WHERE")
					$pos = $k;
			
			$INFO['WHERE'] = '';
			for ($s=$pos;$s<count($sqlinfo);$s++)
			{
				if ($sqlinfo[$s] == 'SET' || $sqlinfo[$s] == 'LIMIT' || $sqlinfo[$s] == 'ORDER' || $sqlinfo[$s] == ';')
					break;
				if (trim($sqlinfo[$s])!="WHERE")
					$INFO['WHERE'][] = $sqlinfo[$s];
			}
		}
		
		// Get the SET statement, if it exists
		if (in_array("SET",$sqlinfo))
		{
			foreach ($sqlinfo as $k => $i)
				if ($i == "SET")
					$pos = $k;
			
			$INFO['SET'] = '';
			for ($s=$pos;$s<count($sqlinfo);$s++)
			{
				if ($sqlinfo[$s] == 'WHERE' || $sqlinfo[$s] == 'LIMIT' || $sqlinfo[$s] == 'ORDER' || $sqlinfo[$s] == ';')
					break;
				if (trim($sqlinfo[$s])!="SET")
					$INFO['SET'][] = $sqlinfo[$s] . " ";
			}
		}
		
		// Get the LIMIT statement, if it exists
		if (in_array("LIMIT",$sqlinfo))
		{
			foreach ($sqlinfo as $k => $i)
				if ($i == "LIMIT")
					$pos = $k;
			
			$INFO['LIMIT'] = '';
			for ($s=$pos;$s<count($sqlinfo);$s++)
			{
				if ($sqlinfo[$s] == 'SET' || $sqlinfo[$s] == 'WHERE' || $sqlinfo[$s] == 'ORDER' || $sqlinfo[$s] == ';')
					break;
				if (trim($sqlinfo[$s])!="LIMIT")
					$INFO['LIMIT'][] = $sqlinfo[$s] . " ";
			}
		}
		
		// Get the ORDER statement, if it exists
		if (in_array("ORDER",$sqlinfo))
		{
			foreach ($sqlinfo as $k => $i)
				if ($i == "ORDER")
					$pos = $k;
			
			$INFO['ORDER'] = '';
			for ($s=$pos;$s<count($sqlinfo);$s++)
			{
				if ($sqlinfo[$s] == 'SET' || $sqlinfo[$s] == 'LIMIT' || $sqlinfo[$s] == 'WHERE' || $sqlinfo[$s] == ';')
					break;
				if (trim($sqlinfo[$s])!="ORDER")
					$INFO['ORDER'][] = $sqlinfo[$s] . " ";
			}
		}
		
		return $this->__internal_parse($INFO);
	}
	
	private function __internal_parse($INFO)
	{
		switch (DB_TYPE)
		{
			case 'mysql':
				// MySQL Engine
				
				switch ($INFO['COMMAND'])
				{
					case 'SELECT':
						$result = $INFO['COMMAND']." ";
						$result .= $INFO['COLUMNS'];
						$result .= " FROM \n";
						break;
					case 'UPDATE':
						$result = $INFO['COMMAND']." ";
						foreach ($INFO['TABLES'] as $ident => $table)
						{
							$result .= $table;
							break;
						}
						$result .= "";
						break;
					case 'DELETE':
						$result = $INFO['COMMAND']." FROM \n";
						break;
					case 'INSERT':
						$result = $INFO['COMMAND']." INTO ";
						foreach ($INFO['TABLES'] as $ident => $table)
						{
							$result .= $table;
							break;
						}
						$result .= "\n";
						// Insert requires special treatment as it is not structured like the others ###
						
						$result .= "( ";
						for ($i=0;$i<count($INFO['COLUMNS']);$i++)
						{
							$result .= $INFO['COLUMNS'][$i];
							if ($i!=count($INFO['COLUMNS'])-1)
								$result .= ", ";
						}
						$result .= " )";
						
						if (isset($INFO['ROWS']))
						{
							$result .= "\nVALUES\n";
							foreach ($INFO['ROWS'] as $rowid => $row)
							{
								$result .= "( ";
								$i = 0;
								foreach ($row as $column => $key)
								{
									$result .= $key;
									if ($i!=count($row)-1)
										$result .= ", ";
									$i++;
								}
								$result .= " )";
								if ($rowid != count($INFO['ROWS'])-1)
									$result .= ",";
								$result .= "\n";
							}
						}
						$result .= ";";
						return $result;
						break;
					default:
						$result = $INFO['COMMAND']." \n";
						break;
				}
				if ($INFO['COMMAND'] == "SELECT")
				{
					$i=0;
					foreach ($INFO['TABLES'] as $ident => $table)
					{
						$result .= $table . " AS " . $ident;
						if ($i!=count($INFO['TABLES'])-1)
							$result .= " , \n";
						$i++;
					}
				}
				else if ($INFO['COMMAND'] != "UPDATE")
				{
					foreach ($INFO['TABLES'] as $ident => $table)
					{
						$result .= $table;
						break;
					}
				}
				if (isset($INFO['SET']))
				{
					$result .= "\nSET\n  ";
					foreach ($INFO['SET'] as $data)
					{
						$result .= trim($data)." ";
						if (trim($data) == ",")
							$result .= "\n  ";
					}
				}
				if (isset($INFO['WHERE']))
				{
					$result .= "\nWHERE\n  ";
					foreach ($INFO['WHERE'] as $data)
					{
						$result .= trim($data)." ";
						if (trim($data) == "AND" || trim($data) == "OR")
							$result .= "\n  ";
					}
				}
				if (isset($INFO['ORDER']))
				{
					$result .= "\nORDER\n  ";
					foreach ($INFO['ORDER'] as $data)
					{
						$result .= trim($data)." ";
						if (trim($data) == ",")
							$result .= "\n  ";
					}
				}
				if (isset($INFO['LIMIT']))
				{
					$result .= "\nLIMIT ";
					foreach ($INFO['LIMIT'] as $data)
					{
						$result .= trim($data);
					}
				}
				if (substr(trim($result),strlen(trim($result))-1,1) != ";")
					$result .= ";";
				
				return $result;
				
				break;
		}
	}
	
	private function __internal_split($sql)
	{
		// Trim extra spaces.
		$sql = trim($sql);
		
		// If we don't have any ', we can just return the array splited by spaces.
		if (substr_count($sql,"'")==0)
			return explode(" ", $sql);
		
		// Continue with '
		$result = array();
		$c = 0;
		for ($i = 0; $i < strlen($sql); $i++)
		{
			$char = substr($sql,$i,1);
			if ($char == "'" && $i != 0)
			{
				// split the string if needed
				if (substr($sql,$i-1,1)!="\\")
				{
					$c++;
					continue; // don't add the ', because it's already put back in by the other code
				}
			}
			$result[$c] .= $char;
		}
		//$result = explode("'", $sql);
		
		// Every second element is the content of the 'string with spaces' so we have to re-add the ' signs...   
		for($i = 1; $i < count($result); $i = $i+2)
			$result[$i] = "'".$result[$i]."'";
		
		// The rows which don't start with a ' will be splitted on single spaces...    
		for($i = 0; $i < count($result); ++$i)
			if(substr($result[$i], 0, 1) != "'")
				$result[$i] = explode(' ', trim($result[$i]));
		
		// Extract the sub-arrays and butild them in the result array so that the result array is only on single array with strings...
		$result2 = array();
		for($i = 0; $i < count($result); ++$i)
		{
			if(is_array($result[$i]))
				for($j = 0; $j < count($result[$i]); ++$j)
					$result2[] = $result[$i][$j];
			else
				$result2[] = $result[$i];
		}
		
		return $result2;
	}
	
	/**
	Escapes a string
	**@param string The variable to be escaped.
	**@param boolean Escape SQL Wildcards?
	
	**@returns string The escapes string.
	**/
	function escape($value, $wildcards = false) 
	{
		switch (DB_TYPE)
		{
			case 'mysql':
				//Escape wildcards for SQL injection protection on LIKE, GRANT, and REVOKE commands.
				if ($wildcards === true)
				{
					$value = str_replace('%', '\%', $value);
					$value = str_replace('_', '\_', $value);
				}
				//Finally, return the end result with the addition of mysql string escaping.
				return mysql_real_escape_string($value);
				break;
			default:
				return $value;
				break;
		}
	}
	
	/**
	Escapes a string
	**@param string The variable to be escaped.
	**@param boolean Escape SQL Wildcards?
	
	**@returns string The escapes string.
	**/
	function query($sql) 
	{
		$sql = $this->libopensql($sql);
		switch (DB_TYPE)
		{
			case 'mysql':
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
				break;
		}
	}
	
	/**
	Fetches results from
	**@param object Resultset
	**@returns array Associative Array of Data
	**/
	function fetch($result)
	{
		switch (DB_TYPE)
		{
			case 'mysql':
				return mysql_fetch_assoc($result);
				break;
			case 'flatfile':
				$return = current($result);
				next($result);
				return $return;
				break;
		}
	}

	/**
	Connects to Database with the specified user details.
	**@param string DB Host
	**@param string DB User
	**@param string DB Password
	**@param string DB Name
	**@param integer DBI Port Number; assumes 3306 by default.
	**@returns none
	**/
	function connect($host = 'localhost', $user = 'root', $password = '', $database, $port = 3306)
	{
		switch (DB_TYPE)
		{
			case 'mysql':
				//Create a variable, for future use.
				$conn = mysql_connect($host, $user, $password, $port);
				
				//Connection has succeeded.
				if ($conn)
				{
					//Create a reference for the established link.
					$this->connection = &$conn;
					//Select the MySQL Database, and supply the link as a second argument.
					mysql_select_db($database, $this->connection) or die('SUIT Error #13. See http://www.suitframework.com/docs/error13/');
				}
				else
				{
					//Connection has failed, now inform the user about this.
					die('SUIT Error #14. See http://www.suitframework.com/docs/error14/');
				}
				break;
		}
	}
	
	function close()
	{
		switch (DB_TYPE)
		{
			case 'mysql':
				mysql_close($this->connection);
				break;
		}
	}
	
	/**
	Output the Error number, the Error itself and the query attempting to execute it.
	**@param string Database Query
	**/
	function error($sql = '')
	{
		switch (DB_TYPE)
		{
			case 'mysql':
				$error = mysql_error();
				$error_number = mysql_errno();
				$backtrace = debug_backtrace();
				//I plan to implement a line counting system in the future and more advanced SQL Debugging techniques to make it easier for developers.
				print 'Error (#' . $error_number . '): ' . $error . '<br />';
				print 'Query: ' . $sql . '<br />';
				print 'File: ' . $backtrace[2]['file'] . '<br />';
				print 'Line: ' . $backtrace[2]['line'] . '<br />';
				break;
		}
	}
}

$mn = 'Database';
?>
