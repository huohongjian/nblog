<?php
date_default_timezone_set("PRC");
session_start();

class Session {
    
	static private $life = 60 * 60 * 6;

	static function all() {
		$time = date("Y-m-d H:i:s", time() - self::$life);
		$SID  = session_id();
		$sql  = "UPDATE nb_session SET logintime = current_timestamp 
				 WHERE sessionid='$SID' AND logintime>'$time' 
				 RETURNING data";
		$json = DB::getInstance()->query($sql)->fetchVal();
		return json_decode($json, true);
	}

	static function get($key) {
		$time = date("Y-m-d H:i:s", time() - self::$life);
		$SID  = session_id();
		$sql  = "UPDATE nb_session SET logintime = current_timestamp 
				 WHERE sessionid=$1 AND logintime>$2 
				 RETURNING data->>$3";
		return DB::getInstance()->query2($sql, [$SID, $time, $key])->fetchVal();
	}
	

	static function set(array $kvs) {
		$SID  = session_id();
		$json = pg_escape_string(json_encode($kvs));
	    $sql  = "SELECT nb_session_upsert('$SID', '$json')";
	    return DB::getInstance()->query($sql);
	}


	static function unset($key) {
		self::gc();
		$SID = session_id();
		$key = pg_escape_string($key);
		$sql = "UPDATE nb_session SET data=data-'$key' WHERE sessionid='$SID'";
		return DB::getInstance()->query($sql);
	}
	
	
	static function gc() {
		$time = date("Y-m-d H:i:s", time() - self::$life);
		$sql  = "DELETE FROM nb_session WHERE logintime<'{$time}'";
		return DB::getInstance()->query($sql);
	}
	
	
	static function sum(){
		self::gc();
		return DB::get('nb_session').selectVal('count(*)');
	}

}


