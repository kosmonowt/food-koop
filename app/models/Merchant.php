<?php

class Merchant extends \Eloquent {
	protected $fillable = [];

	public function orders() { return $this->hasMany("Order"); }

}