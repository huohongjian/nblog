<?php



class ArticleModel extends __InitModel {

	private $table = 'nblog_article';

	public function list() {
		$sql = "SELECT articleid, title FROM $this->table ORDER BY articleid";
		return $this->pgsql->fetchAll($sql);
	}


	public function add() {
		$this->escape_string();
		$sql = "INSERT INTO $this->table (title, publish, content) 
		        VALUES
		          	('$this->title', $this->publish, '$this->content')
		        RETURNING articleid";
        return $this->pgsql->fetchVal($sql);
	}


}
