<?php

class Reply {

function getOne($id) {
}

function getAll() {


}

function getByThreadid($id, $offset=0, $limit=10) {
	$sql = "SELECT a.*, b.name AS username, b.photo AS userphoto
			FROM nb_reply AS a
		   	LEFT JOIN nb_user AS b
			ON a.userid=b.userid
			WHERE threadid=$1
			ORDER BY replyid DESC
			OFFSET $offset
			LIMIT $limit";
	return DB::ins()->query2($sql, [$id])->all();
}


function insert($ps) {
	$sql = "UPDATE nb_user SET replynum=replynum+1 WHERE userid=$1";
	DB::ins()->query2($sql, [$ps['userid']]);
	$sql = "UPDATE nb_thread SET replynum=replynum+1 WHERE threadid=$1";
	DB::ins()->query2($sql, [$ps['threadid']]);
	$sql = "INSERT INTO nb_reply (threadid, content, userid) VALUES ($1, $2, $3) RETURNING replyid";
	return DB::ins()->query2($sql, [$ps['threadid'], $ps['content'], $ps['userid']])->val();
}


function update($ps) {
	$sql = "UPDATE nb_reply SET content=$2 WHERE replyid=$1";
	DB::ins()->query2($sql, [$ps['replyid'], $ps['content']]);
	return $ps['replyid'];
}



}
