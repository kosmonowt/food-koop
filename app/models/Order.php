<?php

class Order extends \Eloquent {
	protected $fillable = ["amount","member_id","merchant_id","state","product_id","comment"];

	public function member() {
        return $this->belongsTo('Member','member_id');
    }

    public function merchant() {
    	return $this->belongsTo('Merchant','merchant_id');
    }

    public function product() {
    	return $this->belongsTo("Product","product_id");
    }

    /** ONLY TO USE IN MARKETPLACE!!! **/
    public function product_type() {
        return $this->belongsTo("ProductType","product_type_id");
    }


    public function scopeMy($query) {
        return $query->where("member_id","=",Auth::user()->member_id);
    }

    /**
     * Scope for orders that are not forwarded to merchant yet
     * 
     **/
    public function scopeUnordered($query) {
        return $query->where("order_state_id","<",3);
    }

    public function scopeMarketplace($query) {
        $query->leftJoin("products","orders.product_id","=","products.id");
        $query->groupBy("orders.product_id");
        $query->select(DB::raw('MOD(SUM(orders.amount),products.units) as remainingAmount, SUM(orders.amount) as totalAmount, products.id, products.name, products.units, products.price, products.weight_per_unit, products.unit_unit,products.tare_unit,products.sku,products.product_state_id,products.product_type_id,products.merchant_id'));
        $query->with("merchant");
        $query->with("product_type");
        $query->having('remainingAmount',">","0");
        $query->open();
        return $query;
    }

    public function scopeCompleted($query) {
        return $query->where("order_state_id","=",100);
    }

    public function scopeOpen($query) {
        return $query->where("order_state_id","<",100);
    }

}