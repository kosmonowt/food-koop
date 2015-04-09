<?php
class Contents extends AppModel {
	protected $fillable = [];

	public function contentType() { return $this->belongsTo('ContentType','type_id'); }
	public function user() { return $this->belongsTo('User','created_by'); }

	public function scopePublicPost($query) { return $query->where("type_id","=",1); }
	public function scopeDashboardPost($query) { return $query->where("type_id","=",2); }
	public function scopePage($query) { return $query->where("type_id","=",3); }

}