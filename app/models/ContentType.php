<?php

class ContentType extends \Eloquent {
	protected $fillable = [];
	public $timestamps = false;
	public function contents() { return $this->hasMany('Contents','type_id'); }

}