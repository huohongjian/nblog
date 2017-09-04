<?php
/**
 * ============================================================
 *         PgSQL Class v2.1_2017.09.05
 * -------------------------------------------------------------
 * Copyright (C) HuoHongJian All Rights Reserved.
 * =============================================================
 */

class PgSQL {
    
    private $linkid;
    private $queryid;
    private $result;
    private $querycount = 0;
    static private $instance;
    
    private function __construct() {
        $this->connect();
    }
    
    private function __clone() { }
    
    
    private function connect() {
        $conn = 'host     = 127.0.0.1
                 port     = 5432
                 dbname   = nblog
                 user     = postgres
                 password = ';
        try {
            $this->linkid = @pg_connect($conn);
            if (!$this->linkid) { throw new Exception("Could not connect to database server!"); }
        } catch (Exception $e) { die ($e->getMessage()); }
    }
    

    static function getInstance() {
        if (!(self::$instance instanceof self)) {
            self::$instance = new static;
        }
        return self::$instance;
    }
    
    
    function query($sql) {
        try {
            $this->result = pg_query($this->linkid, $sql);
            if (!$this->result) {
                throw new Exception("The database query failed! SQL:  <br />");
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
        $this->querycount ++;
        return $this->result;
    }


    function all($sql) {
        $this->query($sql);
        return @pg_fetch_all($this->result);
    }


    //PGSQL_NUM:以编号为键值; PGSQL_ASSOC:以字段名为键值; PGSQL_BOTH:同时用两者为键值;
    function one($sql, $row=0, $type=PGSQL_ASSOC) {
        $this->query($sql);
        return @pg_fetch_array($this->result, $row, $type);
    }


    function row($sql, $row=0, $type=PGSQL_ASSOC) {
        return $this->one($sql, $row, $type);
    }
    

    function col($sql, $col=0){
        $this->query($sql);
        $ds=null;
        while($row = @pg_fetch_row($this->result)){
            $ds[]=$row[$col];
        }
        return $ds;
    }
    
   
    function val($sql, $row=0, $col=0) {
        $this->query($sql);
        return @pg_fetch_result($this->result, $row, $col);
    }


    function obj($sql, $row=0, $type=PGSQL_ASSOC) {
        $this->query($sql);
        return @pg_fetch_object($this->result, $row, $type);
    }


    function numRows() {
        return @pg_num_rows($this->result);
    }
    
    
    function numFields() {
        return @pg_num_fields($this->result);
    }

    
    function affectdRows() {
        return @pg_affected_rows($this->linkid);
    }
    
    
    function numQueries() {
        return $this->querycount;
    }
    
    
    function close() {
        if ($this->linkid) @pg_close();
    }
    
    
    function __destruct(){
        $this->close();
    }
}
?>