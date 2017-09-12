<?php
date_default_timezone_set("PRC");

session_start();
// class Session
class SS {
    
    static private $pgsql;
	static private $life = 60 * 240;
	static private $sessionid;
	
// 	function construct() {		
// 		self::gc();
		
// 		session_start();
// 		$this->sessionid = session_id();
// //		$this->sessionid = getallheaders()['Cookie'];
// 	}

	
	static function read($key=NULL) {
		$time = date("Y-m-d H:i:s", time() - self::$life);
		// $sql  = "UPDATE c_session SET logintime = current_timestamp WHERE sessionid='$this->sessionid' AND logintime>'$time';
		//          SELECT data FROM c_session WHERE sessionid='$this->sessionid' AND logintime>'$time'";
		$sql = "UPDATE nb_session SET logintime = current_timestamp WHERE sessionid='".session_id()."' AND logintime>'$time' RETURNING data";
		$data = DB::fetchVal($sql);
		$obj  = json_decode($data, TRUE);  //$returnArray=false 返回对象
		if ($key) {
		    return $obj[$key];
		} else {
		    return $obj;
		}
	}
	
	
	static function write($json) {
		self::gc();
	    $json = pg_escape_string($json);
	    $sql  = "SELECT nb_session_upsert('".sesstion_id()."', '$json')";
	    return DB::fetchVal($sql);
	}
	
	
	static function gc() {
		$time = date("Y-m-d H:i:s", time() - self::$life);
		$sql = "DELETE FROM nb_session WHERE logintime<'$time'";
		DB::query($sql);
	}
	
	
	static function getAllSession(){
		return DB::get("nb_session").order('logintime ASC').select();
	}
}


