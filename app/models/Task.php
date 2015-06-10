<?php

class Task extends AppModel {
	protected $fillable = ["member_id","comment"];

	public function scopeDay($query,$day) {
		return $query->where("date","=",$day);
	}

	public function scopeUntilDay($query,$day) {
		return $query->where("date","<=",$day);
	}

	public function scopeUpcoming($query) {
		return $query->where("date",">=",date('Y-m-d'));
	}

	public function scopeDayAsc($query) {
		return $query->orderBy("date","ASC");
	}

	public function scopeOwn($query) {
		return $query->where("member_id","=",Auth::user()->member_id);
	}

	public function scopeUnassigned($query) {
		return $this->whereNull("member_id");
	}

	public function taskType() {
		return $this->belongsTo("TaskType");
	}

}