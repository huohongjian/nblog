<?php

class Structure extends __Init {

	function add(){
		$this->escape_string();
		$sql = "INSERT INTO structure (taskid, label, name, unit, rfloat, cfloat, deleted) VALUES
		       ($this->taskid,  '$this->label', '$this->name', '$this->unit', 
		       '$this->rfloat', '$this->cfloat', $this->deleted)
		        RETURNING id";
        return $this->pgsql->fetchVal($sql);
	}
	
	function update(){
		$this->escape_string();
		$sql = "UPDATE structure SET
		          taskid  =  $this->taskid,
		          label   = '$this->label',
		          name    = '$this->name',
		          unit    = '$this->unit',
		          rfloat  =  $this->rfloat,
		          cfloat  =  $this->cfloat,
		          deleted =  $this->deleted
		        WHERE  id =  $this->struid";
		$this->pgsql->query($sql);
		return $this->struid;
	}
		
	function getRow($id){
		$sql = "SELECT * FROM structure WHERE id = $id";
		return $this->pgsql->fetchRow($sql);
	}
	
	function getTableList($taskid) {
	    $sql = "SELECT id, label || '.' || name FROM structure WHERE taskid = $taskid AND NOT deleted ORDER BY label";
	    return $this->pgsql->fetchDF2($sql);
	}
	function getNames($taskid) {
	    $sql = "SELECT label || '.' || name FROM structure WHERE taskid = $taskid ORDER BY label";
	    return $this->pgsql->fetchCol($sql);
	}

	function getByTaskid($taskid) {
	    $sql = "SELECT id, label, name, unit,
	               CASE WHEN rfloat='t'  THEN 'true' ELSE 'false' END rfloat,
	               CASE WHEN cfloat='t'  THEN 'true' ELSE 'false' END cfloat,
	               CASE WHEN deleted='t' THEN 'true' ELSE 'false' END deleted
	           FROM structure WHERE taskid = $taskid ORDER BY label";
	    return $this->pgsql->fetchDF2($sql);
	}
	
	
	
/**************************** update **********************************/
	function updateCHeaders($id, $cheaders) {
	    $sql = "UPDATE structure SET cheaders='$cheaders'::jsonb WHERE id=$id";
	    return $this->pgsql->query($sql);
	}
	
	function updateRHeaders($id, $rheaders) {
	    $sql = "UPDATE structure SET rheaders='$rheaders'::jsonb WHERE id=$id";
	    return $this->pgsql->query($sql);
	}
	
	function updateStyle($id, $style){
	    $sql = "UPDATE structure SET style='$style'::jsonb WHERE id=$id";
	    return $this->pgsql->query($sql);
	}
	
	
}
?>