<?php
date_default_timezone_set("PRC");
session_start();

class Session {
    
	static private $life = 60 * 60 * 6;
	static private $data = false;

	static function get($key='', $reload=false) {
		if (self::$data===false or $reload===true) {
			$time = date("Y-m-d H:i:s", time() - self::$life);
			$SID  = session_id();
			$sql  = "UPDATE nb_session SET logintime = current_timestamp 
					 WHERE sessionid='$SID' AND logintime>'$time' 
					 RETURNING data";
			$v = DB::ins()->query($sql)->val();
			self::$data = json_decode($v, true);
		}
		return $key=='' ? self::$data : self::$data[$key];
	}
	

	static function set(array $params) {
		self::gc();
		$SID  = session_id();
		$json = pg_escape_string(json_encode($params));
	    $sql  = "SELECT nb_session_upsert('$SID', '$json')";
	    return DB::ins()->query($sql);
	}


	static function unset($key) {
		self::gc();
		$SID = session_id();
		$key = pg_escape_string($key);
		$sql = "UPDATE nb_session SET data=data-'$key' WHERE sessionid='$SID'";
		return DB::ins()->query($sql);
	}


	static function clear() {
		self::gc();
		$SID  = session_id();
		$time = date("Y-m-d H:i:s", time() - self::$life);
		$sql  = "DELETE FROM nb_session WHERE sessionid='$SID' OR logintime<'{$time}'";
		return DB::ins()->query($sql);
	}
	
	
	static function sum(){
		self::gc();
		$sql = 'SELECT count(*) FROM nb_session';
		return DB::ins()->query($sql)->val();
	}
	
	
	static function gc() {
		$time = date("Y-m-d H:i:s", time() - self::$life);
		$sql  = "DELETE FROM nb_session WHERE logintime<'{$time}'";
		return DB::ins()->query($sql);
	}
}


