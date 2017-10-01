<?php
/*
* ============================================================
*                DB Class v3.0.0      2017.09.20
* -------------------------------------------------------------
*   Copyright (C) belong to HuoHongJian, all rights reserved.
* =============================================================
*
* 1. DB::get()->...->selectAll();						 	用于一次操作,已escape() & clear()
* 2. DB::getInstance()->query()->fetchAll();				需要escape(),谨防SQL注入
* 3. DB::getInstance()->query2($sql, $arr)->fetchAll();		用于一次操作,无需escape()
* 4. DB::getInstance()->prepare()->execute()->fetchAll();	用于批量操作,无需escape()
*/

class DB {

	private $conn;
	private $queryid;
	private $result;
	static private $instance;

	private $table;
	private $where;
	private $order;
	private $limit;
	private $offset;
	private $conflict;
	private $returning;


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
			$this->conn = @pg_connect($conn);
		} catch (Exception $e) {
			die ($e->getMessage());
		}
		if (!$this->conn) {
			throw new Exception("Could not connect to database server!");
		}
	}

	static function ins() {
		return self::getInstance();
	}

	static function getInstance() {
		if (!(self::$instance instanceof self)) {
			self::$instance = new static;
		}
		return self::$instance;
	}


	static function get($table='') {
		$I = self::getInstance();
		$I->table = self::clear($table);
		unset($I->where, $I->order, $I->limit, $I->offset, $I->returning, $I->conflict);
		return $I;
	}


	function where(array $where) {
		foreach ($where as $k => $v) {
			$s .= ' AND '.$this->clear($k).'='.$this->escape($v);
		}
		$this->where = substr($s, 4);
		return $this;
	}

	function order(string $order) {
		$this->order = $this->clear($order);
		return $this;
	}

	function limit($limit) {
		$this->limit = (int)$limit;
		return $this;
	}

	function offset($offset) {
		$this->offset = (int)$offset;
		return $this;
	}

	function returning(string $returning) {
		$this->returning = $this->clear($returning);
		return $this;
	}

	function conflict(string $conflict) {
		$this->conflict = $this->clear($conflict);
		return $this;
	}

	function select(string $field='*') {
		$sql = 'SELECT '.$this->clear($field).' FROM '.$this->table;
		if (isset($this->where)) { $sql .= ' WHERE '	.$this->where; }
		if (isset($this->order)) { $sql .= ' ORDER BY '	.$this->order; }
		if (isset($this->limit)) { $sql .= ' LIMIT '	.$this->limit; }
		if (isset($this->offset)){ $sql .= ' OFFSET '	.$this->offset;}
		return $this->query($sql);
	}

	function insert(array $arr) {
		$arr = $this->struck($arr);
		$sql = "INSERT INTO $this->table ({$arr->ks}) VALUES ({$arr->vs})";
		if (isset($this->returning)) {
			$sql .= " RETURNING $this->returning";
		}
		return $this->query($sql);
	}

	function update(array $arr) {
		$arr = $this->struck($arr);
		$sql = "UPDATE $this->table SET {$arr->kvs}";
		if (isset($this->where)) {
			$sql .= " WHERE $this->where";
		}
		return $this->query($sql);
	}

	function upsert(array $arr) {
		$arr = $this->struck($arr);
		$sql = "INSERT INTO $this->table ({$arr->ks}) VALUES ({$arr->vs}) 
				ON CONFLICT ({$this->conflict}) do UPDATE SET {$arr->kvs}";
		if ($this->returning) {
			$sql .= " RETURNING $this->returning";
		}
		return $this->query($sql);
	}

	function delete() {
		$sql = "DELETE FROM $this->table";
		if (isset($this->where)) {
			$sql .= " WHERE $this->where";
		}
		return $this->query($sql);
	}


	function query($sql) {
//		echo $sql;
		try {
			$this->result = @pg_query($this->conn, $sql);
			if (!$this->result) {
				throw new Exception("The database query failed! SQL: <p> $sql </p>");
			}
		} catch (Exception $e) {
			die($e->getMessage());
		}
		return $this;
	}

//	It is not necessary to escape, ~= prepare()->execute().
	function query2($sql, array $arr) {
		$this->result = @pg_query_params($this->conn, $sql, $arr);
		return $this;
	}

	function prepare($sql, $stmt='my_query') {
		@pg_prepare($this->conn, $stmt, $sql);
		return $this;
	}

	function execute(array $arr, $stmt='my_query') {
		$this->result = @pg_execute($this->conn, $stmt, $arr);
		return $this;
	}



	function all() {
		return @pg_fetch_all($this->result);
	}

	function arr() {
		$ds = array();
		while($row = @pg_fetch_row($this->result)) {
			$ds[]=$row;
		}
		return $ds;
	}

	//PGSQL_NUM:以编号为键值; PGSQL_ASSOC:以字段名为键值; PGSQL_BOTH:同时用两者为键值;
	function one($row=0, $type=PGSQL_ASSOC) {
		return @pg_fetch_array($this->result, $row, $type);
	}

	function row($row=0, $type=PGSQL_ASSOC) {
		return $this->fetchOne($row, $type);
	}

	function col($col=0){
		$ds = array();
		while($row = @pg_fetch_row($this->result)) {
			$ds[]=$row[$col];
		}
		return $ds;
	}

	function val($row=0, $col=0) {
		return @pg_fetch_result($this->result, $row, $col);
	}

	function obj($row=0, $type=PGSQL_ASSOC) {
		return @pg_fetch_object($this->result, $row, $type);
	}



	//返回 PostgreSQL result 中的行的数目
	function numRows() {
		return @pg_num_rows($this->result);
	}

	function numFields() {
		return @pg_num_fields($this->result);
	}
	//获得被 INSERT，UPDATE 和 DELETE 命令影响到的行的数目
	function affectedRows() {
		return @pg_affected_rows($this->result);
	}

	function close() {
		if ($this->conn) @pg_close();
	}

	function __destruct(){
		$this->close();
	}




	static function clear($str) {
		$str = str_replace(';', '；', 	$str);
		$str = str_replace('--', '——', 	$str);
		$str = str_replace('/', '〡', 	$str);
		return $str;
	}

	static function escape($str) {
		if (is_string($str)) {
			return "'".pg_escape_string($str)."'";
		} else {
			return $str;
		}
	}

	static function struck(array $arr, $glue=',', $exEmpty=true) {
		$ks = array();
		$vs = array();
		$kv = array();
		foreach ($arr as $k => $v) {
			if (!$exEmpty or $v!='') {
				$k = self::clear($k);
				$v = self::escape($v);
				array_push($ks, $k);
				array_push($vs, $v);
				array_push($kv, "$k=$v");
			}
		}
		return (object)[
					'ks'  => implode(',', $ks),
					'vs'  => implode(',', $vs),
					'kvs' => implode(" {$glue} ", $kv)
				];
	}
}

