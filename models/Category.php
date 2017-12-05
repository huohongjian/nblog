<?php

class Category {

function getOne() {
}

function getAll($fields="*") {
	$field = DB::clear($fields);
	$sql = "SELECT $fields FROM nb_category ORDER BY categoryid";
	return DB::ins()->query($sql)->all();
}



}
