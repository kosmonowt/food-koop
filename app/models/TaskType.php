<?php
use Filter\HasFilters;

class TaskType extends AppModel {

	use HasFilters;

	protected $fillable = ["name","short_description","long_description","day_of_week","repeat_days","time_start","time_stop","published_start","published_stop","member_group_id","active"];

/*    protected static $rules = [
		'name'  => 'required',
	    'short_description' => "required",
        'long_description' => "required",
        "day_of_week" => "required|numeric|max,10",
        "repeat_days" => "required",
        'time_start' => 'required',
    ];*/

    //     protected static $messages = [
    //     'merchant_id.required' => 'Bitte Händler angeben.',
    //     'merchant_id.exists' => 'Der Händler existiert nicht.',
    //     'product_type_id.required' => 'Bitte Produktart auswählen.',
    //     "product_type_id.exists" => "Diese Produktart ist unbekannt",
    //     'sku.required' => "Bitte die Artikelnummer aus dem Katalog angeben.",
    //     "sku.unique" => "Die SKU existiert bereits!",
    //     "name" => "Bitte eine Bezeichnung zu diesem Produkt angeben.",
    //     "price.required" => "Bitte Preis eingeben.",
    //     "price.numeric" => "Der Preis muss numerisch sein",
    //     "units.required" => "Bitte angeben wieviele Einheiten in der Verpackung enthalten sind.",
    //     "units.integer" => "Die anzahl der Einheiten muss ganzzahlig sein. Bei gebrochenen Mengen bitte die nächstkleinere Einheit wählen."
    // ];

    public function memberGroup() {
    	return $this->belongsTo('MemberGroup','member_group_id');
    }

    public function save(array $options = array()) {
    	
    	$this->active = ( ( ($this->active == 0) || ($this->active == 1) ) || (isset($this->active) && $this->active == "on") ) ;

    	return parent::save($options);
    }

}