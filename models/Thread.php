<?php

class Thread {

function getOne($id) {
}

function all() {
	$sql = "SELECT a.*, b.name AS username, b.photo AS userphoto
	   		FROM nb_thread AS a 
	   		LEFT JOIN nb_user AS b
			ON a.userid=b.userid
			ORDER BY a.thid DESC";
	return DB::ins()->query($sql)->all();
}


function insert($ps) {
	$sql = "UPDATE nb_user SET threadnum=threadnum+1 WHERE userid=$1";
	DB::ins()->query2($sql, [$ps['userid']]);
	$sql = "INSERT INTO nb_thread (category, title, content, userid) VALUES ($1, $2, $3, $4) RETURNING threadid";
	return DB::ins()->query2($sql, [$ps['category'], $ps['title'], $ps['content'], $ps['userid']])->val();
}


function update($ps) {
	$sql = "UPDATE nb_thread SET category=$1, title=$2, content=$3 WHERE threadid=$4";
	DB::ins()->query2($sql, [$ps['category'], $ps['title'], $ps['content'], $ps['threadid']]);
	return $ps['thread'];
}

}
