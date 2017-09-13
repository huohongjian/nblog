<?php
/**
* ============================================================
*         DB Class v2.1_2017.09.05
* -------------------------------------------------------------
* Copyright (C) HuoHongJian All Rights Reserved.
* =============================================================
*/

class DB {

	private $linkid;
	private $queryid;
	private $result;
	static private $instance;

	private $table 	= null;
	private $where 	= null;
	private $order 	= null;
	private $limit 	= null;
	private $returning = null;
	private $fields    = null;
	private $conflict  = null;


	private function __construct() {
		$this->connect();
	}


	private function __clone() { }


	private function connect() {
		$conn = 'host 	= 127.0.0.1
				 port 	= 5432
				 dbname	= nblog
				 user 	= postgres
				 password = ';
		try {
			$this->linkid = @pg_connect($conn);
		} catch (Exception $e) {
			die ($e->getMessage());
		}
		if (!$this->linkid) {
			throw new Exception("Could not connect to database server!");
		}
	}


	static function getInstance() {
		if (!(self::$instance instanceof self)) {
			self::$instance = new static;
		}
		return self::$instance;
	}

	static function get($table) {
		$ins = self::getInstance();
		$ins->table = $table;
		$ins->where = null;
		$ins->order = null;
		$ins->limit = null;
		$ins->returnning = null;
		$ins->fields 	 = null;
		$ins->conflict 	 = null;
		return $ins;
	}

	function where($where, $andor='AND') {
		if (is_array($where)) {
			$a = array();
			foreach ($where as $k => $v) {
				if (is_string($v)) {
					$v = "'" . pg_escape_string($v) . "'";
				}
				array_push($a, "$k = $v");
			}
			$this->where = implode(" {$andor} ", $a);
		} else {
			$this->where = $where;
		}
		return $this;
	}

	function order($order) {
		$this->order = $order;
		return $this;
	}

	function limit($limit) {
		$this->limit = $limit;
		return $this;
	}

	function returning($returning) {
		$this->returning = $returning;
		return $this;
	}

	function fields($fields) {
		$this->fields = $fields;
		return $this;
	}

	function conflict($conflict) {
		$this->conflict = $conflict;
		return $this;
	}

	private function selectSQL($fields=array('*')) {
		$field = implode(',', $fields);
		$sql = "SELECT $field FROM $this->table";
		if (!is_null($this->where)) { $sql .= " WHERE $this->where"; }
		if (!is_null($this->order)) { $sql .= " ORDER BY $this->order"; }
		if (!is_null($this->limit)) { $sql .= " LIMIT $this->order"; }
		return $sql;
	}

	function select($fields=array('*')) {
		$sql = $this->selectSQL($fields);
		return $this->fetchAll($sql);
	}

	function selectAll($fields, $emptyReturn=null) {
		return $this->select($fields, $emptyReturn);
	}

	function selectOne($fields=array('*'), $row=0, $type=PGSQL_ASSOC) {
		$sql = $this->selectSQL($fields);
		return $this->fetchOne($sql, $row, $type);
	}

	function selectRow($fields, $row=0, $type=PGSQL_ASSOC, $emptyReturn=null) {
		return $this->selectOne($fields, $row, $type, $emptyReturn);
	}

	function selectCol($fields, $col=0) {
		$sql = $this->selectSQL($fields);
		return $this->fetchCol($sql, $col);
	}

	function selectVal($fields, $row=0, $col=0) {
		$sql = $this->selectSQL($fields);
		return $this->fetchVal($sql, $row, $col);
	}


	private function structKVS($datas) {
		$ks = array();
		$vs = array();
		$kv = array();
		$escape = !get_magic_quotes_gpc();
		foreach ($datas as $k => $v) {
			if (is_string($v)) {
				if ($escape) {
					$v = "'".pg_escape_string($v)."'";
				}
			}
			array_push($ks, $k);
			array_push($vs, $v);
			array_push($kv, $k . " = " . $v);
		}
		return array('fields' => implode(',', $ks),
					 'values' => implode(',', $vs),
					 'set'    => implode(',', $kv));
	}


	function insert($datas) {
		$kvs = $this->structKVS($datas);
		$sql = "INSERT INTO $this->table ( {$kvs['fields']} ) VALUES ( {$kvs['values']} )";
		if (!is_null($this->returning)) {
			$sql .= " RETURNING " . $this->returning;
		}
		return $this->fetchVal($sql);
	}


	function update($datas) {
		$kvs = $this->structKVS($datas);
		$sql = "UPDATE $this->table SET {$kvs['set']}";
		if (!is_null($this->where)) {
			$sql .= " WHERE $this->where";
		}
		$this->query($sql);
		return $this->affectdRows();
	}


	function upsert($datas) {
		$kvs = $this->structKVS($datas);
		$sql = "INSERT INTO $this->table ({$kvs['fields']}) VALUES ({$kvs['values']}) 
				ON CONFLICT ({$this->conflict}) do UPDATE SET {$kvs['set']}";
		if ($this->returning) {
			$sql .= " RETURNING $this->returning";
		}
		return $this->fetchVal($sql);
	}


	function delete() {
		$sql = "DELETE FROM $this->table";
		if (!is_null($this->where)) {
			$sql .= " WHERE $this->where";
		}
		$this->query($sql);
		return $this->affectdRows();
	}




	function query($sql) {
		try {
			$this->result = pg_query($this->linkid, $sql);
			if (!$this->result) {
				throw new Exception("The database query failed! SQL: <p> $sql </p>");
			}
		} catch (Exception $e) {
			die($e->getMessage());
		}
		return $this->result;
	}


	function fetch($sql) {
		return @pg_fetch_all($this->query($sql));
	}

	function fetchAll($sql) {
		return $this->fetch($sql);
	}


	//PGSQL_NUM:以编号为键值; PGSQL_ASSOC:以字段名为键值; PGSQL_BOTH:同时用两者为键值;
	function fetchOne($sql, $row=0, $type=PGSQL_ASSOC) {
		return @pg_fetch_array($this->query($sql), $row, $type);
	}


	function fetchRow($sql, $row=0, $type=PGSQL_ASSOC) {
		return $this->fetchOne($sql, $row, $type);
	}


	function fetchCol($sql, $col=0){
		$ds=null;
		while($row = @pg_fetch_row($this->query($sql))){
			$ds[]=$row[$col];
		}
		return $ds;
	}


	function fetchVal($sql, $row=0, $col=0) {
		return @pg_fetch_result($this->query($sql), $row, $col);
	}


	function fetchObj($sql, $row=0, $type=PGSQL_ASSOC) {
		return @pg_fetch_object($this->query($sql), $row, $type);
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


	function close() {
		if ($this->linkid) @pg_close();
	}


	function __destruct(){
		$this->close();
	}
}

