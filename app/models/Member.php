<?php
use Filter\HasFilters;

class Member extends AppModel {
	
	use SoftDeletingTrait, HasFilters;

	protected $fillable = ["name","email","street","telephone","plz","ort","member_group_id","member_status_id","date_of_entry"];
	protected $table = "members";

	protected static $rules = [
		'name'  => 'required|unique:members,name,:id',
        'email' => 'required|unique:members,email,:id',
        "street" => 'required',
        "telephone" => "required",
        "plz" => "required|digits:5",
        "ort" => "required",
        "member_group_id" => "required|exists:member_groups,id",
        "member_status_id" => "required|exists:member_status,id"
    ];

    protected static $messages = [
        'name.required' => 'Gruppenname angeben.',
        'name.unique' => 'Gruppenname ist bereits vergeben.',
        "email.required" => "Bitte E-Mail Adresse angeben.",
        "email.unique" => "Emailadresse berits vergeben.",
        "street.required" => "Bitte Straße und Hausnummer angeben.",
        'ort.required' => "Bitte Ort angeben.",
        'plz.required' => "Bitte Postleitzahl angeben.",
        "telephone.required" => "Bitte Telefonnummer angeben.",
        'member_group_id.required' => 'Bitte einer Dienstgruppe zuordnen.',
        'member_group_id.exists' => 'Die Dienstgruppe existiert nicht.',
        "member_status_id.required" => "Bitte einen Mitgliedsstatus zuweisen.",
        "member_status_id.exists" => "Der Mitgliedsstatus existiert nicht!",
    ];

    public $input = [
        'name' => 'trim|capfirst',
        'email' => 'trim',
        'street' => 'trim|capfirst',
        'ort' => "trim|capfirst",
        "plz" => "trim",
        'telephone' => 'trim',
    ];



	public function user() {
        return $this->hasMany('User','member_id');
    }

	public function memberGroup() {
        return $this->belongsTo('MemberGroup','member_group_id');
    }

    public function memberLedger() {
        return $this->hasMany("MemberLedger","member_id");
    }

    public function ledgerBalance() {
        return $this->hasOne('MemberLedger')->selectRaw('member_id, TRUNCATE(SUM(balance),2) as ledgerBalance')->where("vwz","NOT LIKE","starteinlage")->groupBy('member_id');
    }

    public function scopePaying($query) {
        return $query->whereNotIn("member_status_id",[2,3]);
    }

    public function getLedgerBalanceAttribute($member) {
        if (!array_key_exists("ledgerBalance", $this->relations)) $this->load("ledgerBalance");
        $related = $this->getRelation("ledgerBalance");
        return ($related) ? (float) $related->ledgerBalance : 0;
    }

}