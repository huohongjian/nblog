<?php

class Thread {

function get($where=[], $offset=0, $limit=10) {
	$WHERE = str_replace('userid', 'a.userid', DB::where($where));
	$sql = "SELECT a.*, b.name AS username, b.photo as userphoto
			FROM nb_thread AS a
			LEFT JOIN nb_user AS b ON a.userid=b.userid
			$WHERE ORDER BY a.thid DESC
			OFFSET $offset LIMIT $limit";
	return DB::ins()->query($sql)->all();
}

function num($where=[]) {
	$WHERE = DB::where($where);
	$sql = "SELECT count(*) FROM nb_thread $WHERE";
	return DB::ins()->query($sql)->val();
}


function getOne($threadid) {
	$sql = "UPDATE nb_thread SET counter=counter+1 WHERE threadid=$1";
	DB::ins()->query2($sql, [$threadid]);
	$sql = "SELECT a.*, b.name AS username 
			FROM nb_thread AS a
			LEFT JOIN nb_user AS b
			ON a.userid=b.userid
			WHERE threadid=$1";
	return DB::ins()->query2($sql, [$threadid])->one();
}

function getAll() {
	$sql = "SELECT a.*, b.name AS username, b.photo AS userphoto
	   		FROM nb_thread AS a 
	   		LEFT JOIN nb_user AS b
			ON a.userid=b.userid
			ORDER BY a.thid DESC";
	return DB::ins()->query($sql)->all();
}


function getByUserid($id) {
	$sql = "SELECT a.*, b.name AS username, b.photo as userphoto
			FROM nb_thread AS a
			LEFT JOIN nb_user AS b
			ON a.userid=b.userid
			WHERE a.userid=$1
			ORDER BY a.thid DESC";
	return DB::ins()->query2($sql, [$id])->all();
}


function insert($ps) {
	$sql = "UPDATE nb_user SET threadnum=threadnum+1 WHERE userid=$1";
	DB::ins()->query2($sql, [$ps['userid']]);
	$sql = "INSERT INTO nb_thread (threadid, category, title, content, userid) VALUES ($1, $2, $3, $4, $5) RETURNING threadid";
	return DB::ins()->query2($sql, [uniqid(), $ps['category'], $ps['title'], $ps['content'], $ps['userid']])->val();
}


function update($ps) {
	$sql = "UPDATE nb_thread SET category=$1, title=$2, content=$3 WHERE threadid=$4";
	DB::ins()->query2($sql, [$ps['category'], $ps['title'], $ps['content'], $ps['threadid']]);
	return $ps['thread'];
}

}
