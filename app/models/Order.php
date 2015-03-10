<?php
use Filter\HasFilters;

class Order extends Model {

    use HasFilters;

    protected $fillable = ["amount","member_id","merchant_id","state","product_id","comment"];
    
    protected static $rules = [
        'amount'  => 'required|integer|min:1',
    ];

    protected static $messages = [
        "amount.required" => "Bitte die gewünschte Anzahl eingeben.",
        "amount.integer" => "Die Menge muss ganzzahlig sein.",
        "amount.min" => "Die Menge muss mindestens 1 sein."
    ];

    public $input = [
        'amount' => 'trim',
        'comment' => "trim"
    ];




    /** CONSTRAINTS **/
	public function member() { return $this->belongsTo('Member','member_id'); }
    public function merchant() { return $this->belongsTo('Merchant','merchant_id'); }
    public function product() { return $this->belongsTo("Product","product_id"); }
    /** ONLY TO USE IN MARKETPLACE!!! **/
    public function product_type() { return $this->belongsTo("ProductType","product_type_id"); }
    public function scopeMy($query) { return $query->where("member_id","=",Auth::user()->member_id); }
    /** Orders that are not forwarded to merchant yet **/
    public function scopeUnordered($query) { return $query->where("order_state_id","<",3); }

    /**
     * Query used for members to see what order can be joined in with.
     **/
    public function scopeMarketplace($query) {
        $query->byProduct();
        $query->having('remainingAmount',">","0");
        $query->open();
        return $query;
    }

    /**
     * Query used for order-team to provide verbose information about order.
     **/
    public function scopeByProductVerbose($query) {
        $query->byProduct();
        $query->addSelect(DB::raw(
            'CEIL(SUM(orders.amount) / products.units) as bulkToOrder, '.
            'COUNT(orders.id) as countOrders, '.
            'MIN(orders.created_at) as earliestOrder, '.
            'MAX(orders.created_at) as latestOrder, '.
            'TRUNCATE(products.units * products.price * (1+product_types.tax/100),2) as totalForBulk'
            ));
        return $query;
    }

    public function scopeByProduct($query) {
        $query->leftJoin("products","orders.product_id","=","products.id");
        $query->leftJoin("product_types","products.product_type_id","=","product_types.id");
        $query->with("product_type");
        $query->groupBy("orders.product_id");
        $query->select(DB::raw(
            'MOD(SUM(orders.amount),products.units) as remainingAmount, '.
            '(orders.amount / products.units * 100) as demand, '.
            'SUM(orders.amount) as totalAmount, '.
            'product_types.tax as taxrate, '.
            'products.id, products.name, products.units, products.price, products.weight_per_unit, products.unit_unit, products.tare_unit, products.sku, products.product_state_id, products.product_type_id, products.merchant_id, '.
            "orders.order_state_id as order_state_id"));
        $query->with("merchant");
        return $query;
    }


    /** Orders not yet ordered (ordered by customer but not at merchant) **/
    public function scopeWaiting($query) { return $query->where("order_state_id","<=",2); }
    /** Listed and to be ordered in next step **/
    public function scopeListed($query) { return $query->where("order_state_id","=",3); }
    /** Orders requested at merchant **/
    public function scopePending($query) { return $query->where("order_state_id","=",4); }
    /** Orders processed completely **/
    public function scopeCompleted($query) { return $query->where("order_state_id","=",100); }
    /** All orders NOT processed completely **/
    public function scopeOpen($query) { return $query->where("order_state_id","<",100); }

}