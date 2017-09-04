<?php

class Task extends __Init {

	function add(){
		$this->escape_string();
		$sql = "INSERT INTO task(name, title, taskcatid, subjectid, 
		          periodid, rangeid, remark, deleted) VALUES
		          ('$this->name','$this->title', $this->taskcatid, $this->subjectid, 
		          $this->periodid, $this->rangeid, '$this->remark', $this->deleted)
		        RETURNING id";
        return $this->pgsql->fetchVal($sql);
	}
	
	function update(){
		$this->escape_string();
        $sql = "UPDATE task SET
                    name        = '$this->name',
                    title       = '$this->title',
                    taskcatid   = $this->taskcatid,
                    subjectid   = $this->subjectid,
                    periodid    = $this->periodid,
                    rangeid     = $this->rangeid,
                    remark      = '$this->remark',
                    deleted     = $this->deleted
                WHERE id = $this->taskid";
        $this->pgsql->query($sql);
        return $this->taskid;
	}
	
	function getAll(){
	    $sql = "SELECT id, name, title, taskcatid, subjectid, periodid, rangeid, remark,
	               CASE WHEN deleted='t'  THEN 'true' ELSE 'false' END deleted
	               FROM task ORDER BY id";
	    return $this->pgsql->fetchDF2($sql);
	}
	
	function getRowById($id) {
	    $sql = "SELECT * FROM task WHERE id = $id";
	    return $this->pgsql->fetchRow($sql);
	}
	
	function getInfo($id){
	    $sql = "SELECT name, title, taskcatid, subjectid, periodid, rangeid, remark
	            FROM task
	            WHERE id=$id" ;
	    return $this->pgsql->fetchDF2($sql);
	}
	function getTaskName($id){
	    $sql = "SELECT name FROM task WHERE id=$id";
	    return $this->pgsql->fetchVal($sql);
	}

	function getTaskList() {
//	    $sql = "SELECT id, row_number() OVER(ORDER BY id) || '.', name FROM task";
	    $sql = "SELECT id, name FROM task WHERE NOT deleted ORDER BY id";
	    return $this->pgsql->fetchDF2($sql);
	}
	
	
}
?>