<?php

class __Init {
    
    public $pgsql;
	public $pps = Array();
	
	function __construct() {
		$this->pgsql = PgSQL::getInstance();
	}
	
	function __set($k, $v) {
	    $this->pps[$k] = $v;
	}
	
	function __get($k) {
	    return $this->pps[$k];
	}
	
	function __clone() {
	    
	}
	
	function escape_string(){
	    if (!get_magic_quotes_gpc()) {
	        foreach ($this->pps as $key=>$value) {
	            if (is_string($value)) {
	                $this->pps[$key] = pg_escape_string($value);
	            }
	        }
	    }
	}
}
?>
