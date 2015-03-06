<?php

class Product extends \Eloquent {

	use SoftDeletingTrait;

	protected $fillable = ["merchant_id","product_type_id","sku","name","comment","price","units","weight_per_unit","unit_unit","tare_unit"];

	protected $appends = array('taxrate');


	protected $rules = array(
                'merchant_id'  => 'required|exists:merchants,id',
                'product_type_id' => 'required|exists:product_types,id',
                'sku' => "required|unique:products",
                'name' => "required",
                "price" => "required|numeric",
                "units" => "required|integer"
            	);

	public function product_type() {
        return $this->belongsTo("ProductType","product_type_id");
    }

	public function isValid() {
        return Validator::make(
            $this->toArray(),
            $this->rules
        )->passes();
    }

    public function getValidationErrors() {

    }

	public function getTaxrateAttribute() {
		return ProductType::find($this->product_type_id)->tax;
	}

}

Order::creating(function($order) {
	$product_id = $order->product_id;
	if (!Order::where("product_id","=",$product_id)->count()) {
		$order->comment = (strlen($order->comment)) ? $order->comment . " Erstbestellung." : "Erstbestellung.";
	}
	return $order;
});