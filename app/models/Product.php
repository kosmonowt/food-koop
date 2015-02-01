<?php

class Product extends \Eloquent {

	use SoftDeletingTrait;

	protected $fillable = ["merchant_id","product_type_id","product_state_id","sku","name","comment","price","units","weight_per_unit","unit_unit","tare_unit"];
}