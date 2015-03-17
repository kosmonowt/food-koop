<?php

class Task extends \Eloquent {
	protected $fillable = ["member_id","comment"];

	public function scopeDay($query,$day) {
		return $query->where("date","=",$day);
	}

	public function scopeUntilDay($query,$day) {
		return $query->where("date","<=",$day);
	}

	public function scopeDayAsc($query) {
		return $query->orderBy("date","ASC");
	}

	public function taskType() {
		return $this->belongsTo("TaskType");
	}

}