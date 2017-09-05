<?php
/**
 * ============================================================
 *         PgSQL Class v2.1_2017.09.05
 * -------------------------------------------------------------
 * Copyright (C) HuoHongJian All Rights Reserved.
 * =============================================================
 */

class DB {
    
    static private $linkid;
    static private $queryid;
    static private $result;
    static private $querycount = 0;
    static private $instance;
    static $table;

    
    static private function connect() {
	if (! self::$linkid) {
		$conn = 'host     = 127.0.0.1
                 port     = 5432
                 dbname   = nblog
                 user     = postgres
                 password = ';
		try {
			self::$linkid = @pg_connect($conn);
			if (! self::$linkid) { throw new Exception("Could not connect to database server!"); }
		} catch (Exception $e) { die ($e->getMessage()); }

	}
	return self::$linkid;
    }


    static function get($table) {
	self::$table = $table;
	return DB;
    }

    function all() {
	return self::fetchAll("select * from ".self::$table );
    }
    
    static function query($sql) {
	    self::connect();
        try {
            self::$result = @pg_query(self::$linkid, $sql);
            if (! self::$result) {
                throw new Exception("The database query failed! SQL: <p> $sql </p>");
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
        self::$querycount ++;
        return self::$result;
    }


    static function fetchAll($sql) {
        self::query($sql);
        return @pg_fetch_all(self::$result);
    }


    //PGSQL_NUM:以编号为键值; PGSQL_ASSOC:以字段名为键值; PGSQL_BOTH:同时用两者为键值;
    function fetchOne($sql, $row=0, $type=PGSQL_ASSOC) {
        self::query($sql);
        return @pg_fetch_array(self::$result, $row, $type);
    }


    function fetchRow($sql, $row=0, $type=PGSQL_ASSOC) {
        return self::fetchOne($sql, $row, $type);
    }
    

    function fetchCol($sql, $col=0){
        self::query($sql);
        $ds=null;
        while($row = @pg_fetch_row(self::$result)){
            $ds[]=$row[$col];
        }
        return $ds;
    }
    
   
    function fetchVal($sql, $row=0, $col=0) {
       self::query($sql);
        return @pg_fetch_result(self::$result, $row, $col);
    }


    function fetchObj($sql, $row=0, $type=PGSQL_ASSOC) {
        self::query($sql);
        return @pg_fetch_object(self::$result, $row, $type);
    }


    function numRows() {
        return @pg_num_rows(self::$result);
    }
    
    
    function numFields() {
        return @pg_num_fields(self::$result);
    }

    
    function affectdRows() {
        return @pg_affected_rows(self::$linkid);
    }
    
    
    function numQueries() {
        return self::$querycount;
    }
    
    
    function close() {
        if (self::$linkid) @pg_close();
    }

}
?>
