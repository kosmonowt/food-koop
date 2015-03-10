<?php
use Filter\HasFilters;

class Product extends Model {

	use SoftDeletingTrait, HasFilters;

	protected $fillable = ["merchant_id","product_type_id","sku","name","comment","price","units","weight_per_unit","unit_unit","tare_unit"];

	protected $appends = array('taxrate','standardProduct','blocked','merchantName',"productTypeName");

    protected static $rules = [
		'merchant_id'  => 'required|exists:merchants,id',
        'product_type_id' => 'required|exists:product_types,id',
	    'sku' => "required|unique:products,sku,:id",
        'name' => "required",
        "price" => "required|numeric",
        "units" => "required|integer"
    ];

    protected static $messages = [
        'merchant_id.required' => 'Bitte Händler angeben.',
        'merchant_id.exists' => 'Der Händler existiert nicht.',
        'product_type_id.required' => 'Bitte Produktart auswählen.',
        "product_type_id.exists" => "Diese Produktart ist unbekannt",
        'sku.required' => "Bitte die Artikelnummer aus dem Katalog angeben.",
        "sku.unique" => "Die SKU existiert bereits!",
        "name" => "Bitte eine Bezeichnung zu diesem Produkt angeben.",
        "price.required" => "Bitte Preis eingeben.",
        "price.numeric" => "Der Preis muss numerisch sein",
        "units.required" => "Bitte angeben wieviele Einheiten in der Verpackung enthalten sind.",
        "units.integer" => "Die anzahl der Einheiten muss ganzzahlig sein. Bei gebrochenen Mengen bitte die nächstkleinere Einheit wählen."
    ];

    public $input = [
        'sku' => 'trim',
        'name' => 'trim',
        'comment' => 'trim',
        'price' => 'trim',
        'units' => 'trim',
        'weight_per_unit' => "trim",
        "unit_unit" => "trim",
        "tare_unit" => "trim"
    ];

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

    public function getMerchantNameAttribute() {
        return Merchant::find($this->merchant_id)->name;
    }

	public function getTaxrateAttribute() {
		return ProductType::find($this->product_type_id)->tax;
	}

    public function getProductTypeNameAttribute() {
        return ProductType::find($this->product_type_id)->name;
    }

    public function getStandardProductAttribute() {
        return $this->product_state_id == 3;
    }

    public function getBlockedAttribute() {
        return $this->product_state_id == 2;
    }

    public function scopeOrderCount($query) {
        $query->leftJoin("orders","products.id","=","orders.product_id");
        $query->addSelect(DB::raw('products.*, COUNT(orders.id) as countOrders'));
        $query->groupBy("products.id");
        return $query;        
    }

    public function scopeMostPopular($query) {
        $query->orderCount();
        $query->orderBy("countOrders","DESC");
        return $query;
    }

    public function scopeStandardProduct($query) {
        $query->orderCount();
        return $query-where("product_state_id","=","3");
    }

}