<?php
class MySQL
{
    private static $instance;	
	
	private $connection_id = 0;
    private $query_id = 0;
    public  $affected_rows = 0;
	private $debug = true;
	public $last_sql;
	private $error;
	
	public static function singleton()
	{
		if (!isset(self::$instance))
			self::$instance = new MySQL(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
			
		return self::$instance;
	}
	
	private function __construct($server, $user, $hash_p, $database)
	{
		$this->connection_id = @mysql_connect($server, $user, $hash_p, true);
		if (!$this->connection_id)
		{
      		exit('Error: Could not connect to Database Server ' . $user . '@' . $server);
    	}

    	if (!@mysql_select_db($database, $this->connection_id))
		{
      		exit('Error: Could not connect to Database ' . $database);
    	}
		
		mysql_query("SET NAMES 'utf8'", $this->connection_id);
		mysql_query("SET CHARACTER SET utf8", $this->connection_id);
		mysql_query("SET CHARACTER_SET_connection_id = utf8", $this->connection_id);
		mysql_query("SET CHARACTER_SET_CLIENT = utf8", $this->connection_id);
		mysql_query('SET CHARACTER_SET_RESULTS = utf8');
		mysql_query("SET SQL_MODE = ''", $this->connection_id);
		
	}
	
	public function __destruct()
	{
		$this->close();
	}
	 
	
	public function get_resource()
	{
		return $this->connection_id;
		
	}

   	public function execute($sql)
	{
		
		$query_id = mysql_query($sql, $this->connection_id);
		$this->last_sql[] = $sql;
		
		if ($query_id)
		{
			if (is_resource($query_id))
			{
				
				$out = $this->fetch_array($query_id); 
				$this->free_result($query_id); 
				return $out; 
    		}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
    	}
  	}
		
	public function select($table, $criteria = array(), $field = "*", $orderby = null)
	{
		
		$sql = $this->sql_select($table, $criteria, $field, $orderby);
		return $this->execute($sql);
	}

	public function select_count($table, $criteria = array())
	{
		
		$sql = $this->sql_count($table, $criteria);
		$query_id = $this->query($sql); 
		$out = $this->fetch($query_id); 
		$this->free_result($query_id); 
		
		return $out; 
	}

	public function select_paged($table, $criteria = array(), $field = "*", $page = 1, $max_regs = 20, $orderby = null)
	{

		$sql = $this->sql_select($table, $criteria, $field, $orderby);
		$sql .= " LIMIT " . ($page - 1) * $max_regs . ", " . $max_regs;
		
		return $this->execute($sql);
	}

	public function select_first($table, $criteria = array(), $order = "1")
	{ 
		$sql = $this->sql_select($table, $criteria, "*", $order);

		$query_id = $this->query($sql); 
		$out = $this->fetch($query_id); 
		$this->free_result($query_id); 
		
		return $out; 
	}		
	
	public function sql_select($table, $criteria = array(), $field = "*", $orderby = "2")
	{
		$sql = "SELECT ";
		if(is_array($field))
			$sql .= implode(",", $field);
		else
			$sql .= $field;
			
		$sql .= " FROM " . $table;
		$sql .= " WHERE 1=1";
		
		foreach($criteria as $field => $value)
		{
			if((stripos($value, "sql:") > -1))
				$sql .= " AND `".$field . "` " .str_replace("sql:", "", $value. "");
			else
				$sql .= " AND `".$field . "` = '" .$value. "'";
		}
			
		if($orderby != "")
			$sql .= " ORDER BY " . $orderby;

		return $sql;
	}

	public function sql_count($table, $criteria = array())
	{
		$sql = "SELECT COUNT(*) AS __TOTAL_RECORDS";
		$sql .= " FROM " . $table;
		$sql .= " WHERE 1=1";
		
		foreach($criteria as $field => $value)
			$sql .= " AND ".$field . " = '" .$value. "'";
			
		return $sql;
	}
	
	public function insert($table, $data)
	{
		$sql = "INSERT INTO " . $table;
		
		$_field = array();
		$_value = array();

		foreach($data as $field => $value)
		{
			$_field[] = "`" . $field . "`";
			if(strtolower($value) == 'null')
				$_value[] = "NULL";
			elseif(strtolower($value) == 'now()')
				$_value[] = "NOW()";
			else
				$_value[] = "'" . $this->escape($value) . "'";
		}
			
		$sql .=  "(" . implode(', ', $_field) . ") VALUES (" . implode(', ', $_value) . ")";
		
		if($this->execute($sql))
			return $this->get_last_id($this->connection_id);
		else
			return false;
		
	}
	
	public function update($table, $data, $criteria = array())
	{
		//$data['views'] = "INCREMENT(1)";
		$sql = "UPDATE " . $table . " SET ";
		$_field = array();
		
		foreach($data as $field => $value)
		{
			if(strtolower($value) == 'null')
				$_field[] = "`" . $field . "`" . " = NULL";
			elseif(strtolower($value) == 'now()')
				$_field[] = "`" . $field . "`" . " = NOW()";
			elseif(preg_match("/^increment\((\-?\d+)\)$/i", $value, $m))				
				$_field[] = "`" . $field . "`" . " = " . $field . " + " . $m[1];
			else			
				$_field[] = "`" . $field . "`" . " = '" .$this->escape($value). "'";
		}
		
		$sql .= implode(', ', $_field) . " WHERE ";
		$sql_criteria = "1=1";
		
		foreach($criteria as $field => $value)
			$sql_criteria .= " AND " . $field . " = '" .$value. "'";
		
		if($sql_criteria != "1=1")
		{
			$sql = $sql . $sql_criteria;
			$this->affected_rows = mysql_affected_rows($this->connection_id);
			
			return $this->execute($sql);
		}
		else
			return false;
	}
	
	public function delete($table, $criteria = array())
	{
		$sql_criteria = "1=1";
		foreach($criteria as $field => $value)
			$sql_criteria .= " AND " . $field . " = '" .$value. "'";

		if($sql_criteria != "1=1")
		{
			$sql = "DELETE FROM " . $table . " WHERE " . $sql_criteria;		
			return $this->execute($sql);
		}
		else
			return false;
		
	}	

	public function delete_many($table, $criteria = array())
	{
		$sql_criteria = "1=1";
		foreach($criteria as $field => $value)
		{
			$ids = (is_array($value) ? implode(",", $value) : $value);

			$sql_criteria .= " AND " . $field . " in (" . $ids . ")";
		}

		if($sql_criteria != "1=1")
		{
			$sql = "DELETE FROM " . $table . " WHERE " . $sql_criteria;		

			return $this->execute($sql);
		}
		else
			return false;
			
	}	
	
	public function escape($value)
	{
		if(get_magic_quotes_runtime())
			$value = stripslashes($value); 

		return @mysql_real_escape_string($value,$this->connection_id);		
	}

	public function escape_array($array)
	{
		$value = is_array($array) ? array_map('escape_array', $array) : stripslashes($array);
		return $value;
	}

	public function affected_rows()
	{
		return mysql_affected_rows($this->connection_id);
	}

	public function get_last_id()
	{	
		return mysql_insert_id($this->connection_id);
	}	

	public function close()
	{
		mysql_close($this->connection_id);
	}
	
	private function free_result($query_id = -1)
	{ 
		if ($query_id != -1)
			$this->query_id = $query_id; 
			
		if($this->query_id != 0)
			@mysql_free_result($this->query_id);
//			$this->show_error("Result ID: <b>$this->query_id</b> could not be freed."); 
			
	}
		
	public function show_error($msg = '')
	{ 
		if(!empty($this->connection_id))
		{
			$this->error = $this->last_sql[sizeof($this->last_sql) - 1]."<br>";
			$this->error .= mysql_error($this->connection_id); 
		} 
		else
		{ 
			$this->error = mysql_error(); 
			$msg="<b>WARNING:</b> No link_id found. Likely not be connected to database.<br />$msg"; 
		} 
	
		// if no debug, done here 
		if(!$this->debug) return; 
		?> 
			<table align="center" border="1" cellspacing="0" style="background:white;color:black;width:80%;"> 
			<tr><th colspan=2>Database Error</th></tr> 
			<tr><td align="right" valign="top">Message:</td><td><?php echo $msg; ?></td></tr> 
			<?php if(!empty($this->error)) echo '<tr><td align="right" valign="top" nowrap>MySQL Error:</td><td>'.$this->error.'</td></tr>'; ?> 
			<tr><td align="right">Date:</td><td><?php echo date("l, F j, Y \a\\t g:i:s A"); ?></td></tr> 
			<?php if(!empty($_SERVER['REQUEST_URI'])) echo '<tr><td align="right">Script:</td><td><a href="'.$_SERVER['REQUEST_URI'].'">'.$_SERVER['REQUEST_URI'].'</a></td></tr>'; ?> 
			<?php if(!empty($_SERVER['HTTP_REFERER'])) echo '<tr><td align="right">Referer:</td><td><a href="'.$_SERVER['HTTP_REFERER'].'">'.$_SERVER['HTTP_REFERER'].'</a></td></tr>'; ?> 
			</table> 
		<?php 
	}
	
	public function query($sql)
	{ 
		$this->last_sql[] = $sql;

		$this->query_id = @mysql_query($sql, $this->connection_id); 
	
		if (!$this->query_id)
		{ 
			$this->show_error("<b>MySQL Query fail:</b> $sql"); 
			return 0; 
		} 
		 
		$this->affected_rows = @mysql_affected_rows($this->connection_id); 
	
		return $this->query_id; 
	}
		
	
	public function fetch($query_id=-1)
	{ 
		if ($query_id!=-1)
		{ 
			$this->query_id=$query_id; 
		} 
	
		if (isset($this->query_id))
		{ 
			$record = @mysql_fetch_assoc($this->query_id); 
		}
		else
		{ 
			$this->show_error("Invalid query_id: <b>$this->query_id</b>. Records could not be fetched."); 
		} 
	
		return $record; 
	}
	
	public function fetch_array($query_id=-1)
	{ 
		if ($query_id!=-1)
		{ 
			$this->query_id=$query_id; 
		} 

		$out = array(); 
	
		while ($row = $this->fetch($query_id))
		{ 
			$out[] = $row; 
		} 
	
		$this->free_result($query_id); 
		return $out; 
	}
	
	
	
	/*
	
	public function fetch_array($sql)
	{ 
		$query_id = $this->query($sql); 
		$out = array(); 
	
		while ($row = $this->fetch($query_id))
		{ 
			$out[] = $row; 
		} 
	
		$this->free_result($query_id); 
		return $out; 
	}
*/	
}
?>