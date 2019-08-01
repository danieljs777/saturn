<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

class PDOHelper
{

    private static $instance;
    private $connection = NULL;
    private $query_id = 0;
    public $affected_rows = 0;
    private $debug = true;
    public $last_sql;
    private $error;
    private $DEVELOPMENT_SERVER = "dev.sigurify";
    public $transaction_running = false;

    public static function singleton()
    {
        if (!isset(self::$instance))
            self::$instance = new PDOHelper(DB_DRIVER, DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);

        return self::$instance;
    }

    private function __construct($driver, $server, $user, $hash_p, $database)
    {
        try
        {
            $this->connection = new \PDO(strtolower($driver) . ":host=$server;dbname=$database", $user, $hash_p);

            if (!$this->connection)
            {
                exit('Error: Could not connect to Database Server ' . $user . '@' . $server);
            }

            $this->connection->exec("SET NAMES 'utf8'");
            $this->connection->exec("SET CHARACTER SET utf8");
            $this->connection->exec("SET CHARACTER_SET_connection = utf8");
            $this->connection->exec("SET CHARACTER_SET_CLIENT = utf8");
            $this->connection->exec('SET CHARACTER_SET_RESULTS = utf8');
            $this->connection->exec("SET SQL_MODE = ''");
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }


    }

    public function __destruct()
    {
        $this->close();
    }

    public function get_connection()
    {
        return $this->connection;
    }

    public function execute($sql)
    {
        $this->last_sql[] = $sql;

        try
        {

            if(strpos(strtoupper($sql), 'SELECT') !== false)
            {
                $query_stt = $this->connection->prepare($sql);

                $query_stt->execute() or $this->show_error($sql . " : " . implode(" ", $query_stt->errorInfo()));

                $data = $query_stt->fetchAll(PDO::FETCH_ASSOC);

                $query_stt->closeCursor();
                $query_stt = null;

                return $data;
            }
            else
            {
                $result = $this->connection->exec($sql);
                
                if($result === FALSE)
                    $this->show_error($sql . " : " . implode(" ", $this->connection->errorInfo()));
            
                return $result;

            }
        }

        catch(PDOException $e)
        {
            die($e->getMessage());
        }        

    }

    public function query($sql)
    {
        $this->last_sql[] = $sql;

        $query_stt = @$this->connection->query($sql) or $this->show_error($sql . " : " . implode(" ", $this->connection->errorInfo()));

        $data = [];
        
        if($query_stt)
        {
            $data = $query_stt->fetchAll(PDO::FETCH_ASSOC);
            $query_stt->closeCursor();
        }

        $query_stt = null;

        return $data;


    }

    public function fetch($sql)
    {
        $this->last_sql[] = $sql;

        $query_stt = @$this->connection->query($sql);// or $this->show_error($sql . " : " . implode(" ", $this->connection->errorInfo()));

        return $query_stt;

        // ->fetch(PDO::FETCH_ASSOC);
        // $query_stt->closeCursor();
        // $query_stt = null;

    }    

    public function select($table, $criteria = array(), $field = "*", $orderby = null)
    {

        $sql = $this->sql_select($table, $criteria, $field, $orderby);

        return $this->query($sql);
    }

    public function select_first($table, $criteria = array(), $field = "*", $order = "1")
    {
        $sql = $this->sql_select($table, $criteria, $field, $order);

        $PDOStt = @$this->connection->query($sql) or $this->show_error($table . " : " . implode(" ", $this->connection->errorInfo()));

        if($PDOStt)
            return $PDOStt->fetch(PDO::FETCH_ASSOC);

    }

    public function select_count($table, $criteria = array())
    {

        $sql = $this->sql_count($table, $criteria);

        $PDOStt = @$this->connection->query($sql) or $this->show_error($table . " : " . implode(" ", $this->connection->errorInfo()));

        if($PDOStt)
            return $PDOStt->fetch(PDO::FETCH_ASSOC);

    }

    public function select_custom_count($sql)
    {
        $_sql = $this->sql_custom_count($sql);

        $PDOStt = @$this->connection->query($sql) or $this->show_error($table . " : " . implode(" ", $this->connection->errorInfo()));

        if($PDOStt)
            return $PDOStt->fetch(PDO::FETCH_ASSOC);

    }    

    public function select_paged($table, $criteria = array(), $field = "*", $page = 1, $max_regs = 20, $orderby = null)
    {

        $sql = $this->sql_select($table, $criteria, $field, $orderby);
        $sql .= " LIMIT " . ($page - 1) * $max_regs . ", " . $max_regs;

        return $this->query($sql);
    }

    public function select_custom_paged($sql, $page = 1, $max_regs = 20, $orderby = null)
    {

        $sql .= " LIMIT " . ($page - 1) * $max_regs . ", " . $max_regs;
        
        return $this->query($sql);
    }    

    // #################################################################################
    // SQL Builders

    public function sql_select($table, $criteria = array(), $field = "*", $orderby = "2")
    {
        $sql = "SELECT ";
        if (is_array($field))
            $sql .= implode(",", $field);
        else
            $sql .= $field;

        $sql .= " FROM " . $table;
        $sql .= " WHERE 1=1";

        if (!is_array($criteria))
        {
            if ($_SERVER['SERVER_NAME'] == $this->DEVELOPMENT_SERVER && $this->debug)
            {
                echo "WARNING: criteria is not an array!";
                echo "<br>" . $sql . "<br>";
                print_r($criteria);
            }
        }
        else
        {

            foreach ($criteria as $field => $value)
            {
                if (stripos($field, "sql:") > -1)
                    $sql .= " AND " . str_replace('.', '`.`', str_replace("sql:", "", $field)) . " ";
                else
                    $sql .= " AND `" . str_replace('.', '`.`', $field) . "` ";

                if ((stripos($value, "sql:") > -1))
                    $sql .= str_replace("sql:", "", $value . "");
                else
                    $sql .= "= '" . $value . "'";
            }
        }

        if ($orderby != "")
            $sql .= " ORDER BY " . $orderby;

        return $sql;
    }

    public function sql_count($table, $criteria = array())
    {
        $sql = "SELECT COUNT(*) AS __TOTAL_RECORDS";
        $sql .= " FROM " . $table;
        $sql .= " WHERE 1=1";

        foreach ($criteria as $field => $value)
        {
            if ((stripos($value, "sql:") > -1))
                $sql .= " AND `" . $field . "` " . str_replace("sql:", "", $value . "");
            else
                $sql .= " AND `" . $field . "` = '" . $value . "'";
        }

        return $sql;
    }

    public function sql_custom_count($sql)
    {
        $_sql = "SELECT COUNT(*) AS __TOTAL_RECORDS";
        $_sql .= " FROM ( " . $sql . ") t1";

        return $_sql;
    }    

    public function insert($table, $data)
    {
        $sql = "INSERT INTO " . $table;

        $_field = array();
        $_value = array();

        foreach ($data as $field => $value)
        {
            if (is_object($value))
            {
                echo __FILE__ . " error: " . __LINE__;
                print_r($value);
                die();
            }

            $_field[] = "`" . $field . "`";
            if (strtoupper($value) == 'NULL')
                $_value[] = "NULL";
            elseif (strtoupper($value) == 'NOW()')
                $_value[] = "NOW()";
            elseif (is_numeric($value))
                $_value[] = $value;
            else
                $_value[] = "'" . $this->escape($value) . "'";
        }

        $sql .= "(" . implode(', ', $_field) . ") VALUES (" . implode(', ', $_value) . ")";

        //echo "\r\n" . $sql;

        if ($this->execute($sql))
            return $this->get_last_id();
        else
            return false;
    }

    public function update($table, $data, $criteria = array())
    {
        //$data['views'] = "INCREMENT(1)";
        $sql = "UPDATE " . $table . " SET ";
        $_field = array();

        foreach ($data as $field => $value)
        {
            if (strtoupper($value) == 'NULL')
                $_field[] = "`" . $field . "`" . " = NULL";
            elseif (strtoupper($value) == 'NOW()')
                $_field[] = "`" . $field . "`" . " = NOW()";
            elseif (preg_match("/^increment\((\-?\d+)\)$/i", $value, $m))
                $_field[] = "`" . $field . "`" . " = " . $field . " + " . $m[1];
            elseif (is_numeric($value))
                $_field[] = "`" . $field . "`" . " = " . $value;            
            else
                $_field[] = "`" . $field . "`" . " = '" . $this->escape($value) . "'";
        }

        $sql .= implode(', ', $_field) . " WHERE ";
        $sql_criteria = "1=1";

        foreach ($criteria as $field => $value)
        {
            if (is_numeric($value))
                $sql_criteria .= " AND " . $field . " = " . $value;
            else
                $sql_criteria .= " AND " . $field . " = '" . $value . "'";
        }

        if ($sql_criteria != "1=1")
        {
            $sql = $sql . $sql_criteria;
            
            $result = $this->execute($sql);
            
            return ;
        }
        else
            return false;
    }

    public function delete($table, $criteria = array())
    {
        $sql_criteria = "1=1";
        foreach ($criteria as $field => $value)
            $sql_criteria .= " AND " . $field . " = '" . $this->escape($value) . "'";

        if ($sql_criteria != "1=1")
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
        foreach ($criteria as $field => $value)
        {
            $ids = (is_array($value) ? implode(",", $value) : $value);

            $sql_criteria .= " AND " . $field . " in (" . $ids . ")";
        }

        if ($sql_criteria != "1=1")
        {
            $sql = "DELETE FROM " . $table . " WHERE " . $sql_criteria;

            return $this->execute($sql);
        }
        else
            return false;
    }

    public function escape($value)
    {
        $_value = $this->connection->quote($value);

        $_value = str_replace("'", "`", $value);
        $_value = str_replace(";", "", $value);

        return $_value;
    }


    public function affected_rows($PDOStt)
    {
        return $PDOStt->rowCount();;
    }

    public function get_last_id()
    {
        return $this->connection->lastInsertId();
    }

    public function close()
    {
        $this->connection = null;
    }

    public function show_error($msg = '')
    {
        if (!empty($this->connection))
        {
            $this->error = $msg;
            //$this->error = $this->last_sql[sizeof($this->last_sql) - 1]."<br>";
        }
        else
        {
            $msg = "<b>WARNING:</b> No link_id found. Likely not be connected to database.<br />$msg";
        }

        //System::sendmail(SMTP_ADMIN, "Relatório de erro gerado em " . date("d/m/Y H:i:s"), "error.report.php", array("message" => $this->last_sql[sizeof($this->last_sql) - 1] . " : " . $this->error . " <br> " . $msg));

        if ($_SERVER['SERVER_NAME'] == $this->DEVELOPMENT_SERVER && $this->debug)
        {
            debug_print_backtrace();
            ?> 
            <table align="center" border="1" cellspacing="0" style="background:white;color:black;width:80%;"> 
                <tr><th colspan=2>Database Error</th></tr> 
                <tr><td align="right" valign="top">Message:</td><td><?php echo $this->error; ?></td></tr> 
            <?php if (!empty($this->error)) echo '<tr><td align="right" valign="top" nowrap>Database Error:</td><td>' . $this->last_sql[sizeof($this->last_sql) - 1] . '</td></tr>'; ?> 
                <tr><td align="right">Date:</td><td><?php echo date("l, F j, Y \a\\t g:i:s A"); ?></td></tr> 
            <?php if (!empty($_SERVER['REQUEST_URI'])) echo '<tr><td align="right">Script:</td><td><a href="' . $_SERVER['REQUEST_URI'] . '">' . $_SERVER['REQUEST_URI'] . '</a></td></tr>'; ?> 
            <?php if (!empty($_SERVER['HTTP_REFERER'])) echo '<tr><td align="right">Referer:</td><td><a href="' . $_SERVER['HTTP_REFERER'] . '">' . $_SERVER['HTTP_REFERER'] . '</a></td></tr>'; ?> 
            </table> 
            <?php        
        }
        else
        {
            Log::verbose($msg . ":\n" . $this->last_sql[sizeof($this->last_sql) - 1] . "\n\n\n", "db.log");

            return $msg;
        }

        die();
    }



}
