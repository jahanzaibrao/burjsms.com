<?php
Doo::loadModel('base/ScImportTasksBase');

class ScImportTasks extends ScImportTasksBase{
    
    public function addTask($udata){
        $query = "INSERT INTO `sc_import_tasks` (`admin_id`,`table_id`,`file_name`,`total_records`,`records_done`,`filetype`,`uploaded_on`,`status`) VALUES ";
		foreach ($udata as $data){
		
		$query .= "(";
		$query .= $data['admin_id'].",";
		$query .= $data['table_id'].",";
		$query .= "'".$data['file_name']."',";
		$query .= "0,";
		$query .= "0,";
		$query .= "'".$data['filetype']."',";
		$query .= "'".$data['uploaded_on']."',";
		$query .= "0),";
		
		}
		$query = substr($query, 0, strlen($query)-1);
		$rs = Doo::db()->query($query, null, 2);
    }
}