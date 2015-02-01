<?php

class Order extends \Eloquent {
	protected $fillable = ["amount","member_id","merchant_id","state"];

	public function member() {
        return $this->belongsTo('Member','member_id');
    }

    public function merchant() {
    	return $this->belongsTo('Merchant','merchant_id');
    }

    public function product() {
    	return $this->belongsTo("Product","product_id");
    }

}