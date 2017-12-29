<?php

class User {

function getOne($id) {
}

function getAll() {


}


function num() {
	$sql = "SELECT count(*) AS count FROM nb_user";
	return DB::ins()->query($sql)->val();
}


}
