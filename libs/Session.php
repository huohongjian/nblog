<?php
date_default_timezone_set("PRC");
session_start();

class Session {
    
	static private $life = 60 * 60 * 4;

	static function get($key) {
		$time = date("Y-m-d H:i:s", time() - self::$life);
		$SID  = session_id();
		$sql  = "UPDATE nb_session SET logintime = current_timestamp 
			     WHERE sessionid='{$SID}' AND logintime>'$time' 
			     RETURNING data->>'{$key}'";
		return DB::getInstance()->fetchVal($sql);
	}
	
	
	static function set(array $arr) {
		self::gc();
		$json = json_encode($arr);
		$SID  = session_id();
	    $sql  = "SELECT nb_session_upsert('$SID', '$json')";
	    return DB::getInstance()->fetchVal($sql);
	}


	static function unset($key) {
		$sql = "UPDATE nb_session SET data=data-'$key'";
		return DB::getInstance()->query($sql);
	}
	
	
	static function gc() {
		$time = date("Y-m-d H:i:s", time() - self::$life);
		$sql  = "DELETE FROM nb_session WHERE logintime<'{$time}'";
		return DB::getInstance()->query($sql);
	}
	
	
	static function getAllSession(){
		return DB::get("nb_session").order('logintime ASC').select();
	}
}


