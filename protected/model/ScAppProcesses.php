<?php
Doo::loadModel('base/ScAppProcessesBase');

class ScAppProcesses extends ScAppProcessesBase{
    public function getStatus($process){
		$this->process_name = $process;
		$opt['select'] = 'manual_flag';
		$opt['limit'] = 1;
		return Doo::db()->find($this, $opt);
    }
		
	public function sendPulse($process){
		$this->process_name = $process;
		$rs = Doo::db()->find($this,array('limit'=>1));
		$this->id = $rs->id;
		$this->last_pulse = date(Doo::conf()->date_format_db);
		Doo::db()->update($this);
    }
}