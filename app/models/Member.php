<?php

class Member extends \Eloquent {
	
	use SoftDeletingTrait;

	protected $fillable = ["name","email","street","telephone","plz","ort","member_group_id","member_status_id","date_of_entry"];
	protected $table = "members";

	public function user() {
        return $this->hasMany('User','member_id');
    }

	public function memberGroup() {
        return $this->belongsTo('MemberGroup','member_group_id');
    }    

}