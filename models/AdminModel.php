<?php



class AdminModel extends __InitModel {


	public function list() {
		$sql = "SELECT * FROM nblog_user";
		return $this->pgsql->fetchAll($sql);
	}


}
