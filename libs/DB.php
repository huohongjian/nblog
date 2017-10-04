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


	private function __construct($cnf='default') {
		$cns =  ['default' =>  'host 	= 127.0.0.1
				 				port 	= 5432
				 				dbname	= nblog
				 				user 	= postgres
				 				password = '
				];
		try {
			$this->conn = @pg_connect($cns[$cnf]);
		} catch (Exception $e) {
			die ($e->getMessage());
		}
		if (!$this->conn) {
			throw new Exception("Could not connect to database server!");
		}
	}


	private function __clone() { }


	static function ins($cnf='default') {
		if (!(self::$instance instanceof self)) {
			self::$instance = new static($cnf);
		}
		return self::$instance;
	}


	function select(string $table, array $where=[], string $exp='', string $fields='*') {
		$sql = $this->clear('SELECT ' . $fields . ' FROM ' . $table)
			 . $this->where($where) . ' ' . $this->clear($exp);
		return $this->query($sql);
	}


	function insert(string $table, array $data, string $exp='') {
		$sql = 'INSERT INTO ' . $this->clear($table)
			 . $this->struck($data)->invals
			 . $this->clear($exp);
		return $this->query($sql);
	}


	function update(string $table, array $data, array $where=[], string $exp='') {
		$sql = 'UPDATE ' . $this->clear($table)
			 . $this->struck($data)->upsets
			 . $this->where($where)
			 . $this->clear($exp);
		return $this->query($sql);
	}

	function upsert(string $table, array $data, string $conflict, string $exp='') {
		$obj = $this->struck($data);
		$sql = 'INSERT INTO ' . $this->clear($table)
			 . $obj->invals
			 . ' ON CONFLICT (' . $this->clear($conflict) . ') do UPDATE SET '
			 . $obj->upsets
			 . $this->clear($exp);
		return $this->query($sql);
	}

	function delete(string $table, array $where=[]) {
		$sql = 'DELETE FROM ' . $this->clear($table) . $this->where($where);
		return $this->query($sql);
	}


	function query($sql) {
		echo $sql;
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



	static function where(array $where, boolean $exEmpty=true) {
		foreach ($where as $k => $v) {
			if (!$exEmpty or $v!='') {
				$s .= ' AND '.self::clear($k).'='.self::escape($v);
			}
		}
		if (empty($s)) {
			return '';
		} else {
			return ' WHERE '. substr($s, 4);
		}
	}


	static function like($k, $v) {
		if ($exEmpty and $v=='') {
			return '';
		} else {
			return self::clear($k) . ' LIKE ' . self::escape('%'.$v.'%');
		}
	}

	static function struck(array $data) {
		foreach ($data as $k => $v) {
			$k = self::clear($k);
			$v = self::escape($v);
			$fields .= ',' . $k;
			$values .= ',' . $v;
			$upsets .= ',' . $k . '=' . $v;
		}
		$fields = substr($fields, 1);
		$values = substr($values, 1);
		$upsets = substr($upsets, 1);
		$invals = ' (' . $fields . ') VALUES (' . $values . ')';

		return (object)[
					'fields'  => $fields,
					'values'  => $values,
					'upsets'  => $upsets,
					'invals'  => $invals
				];
	}
}

