<?php

class Article {

function getOne($articleid) {
	$sql = "SELECT * FROM nb_article WHERE articleid=$1";
	return DB::ins()->query2($sql, [$articleid])->one();
}

function getAll() {


}

}