<?php
use Filter\HasFilters;

class MemberLedger extends AppModel {

	use SoftDeletingTrait, HasFilters;

	protected $fillable = ["date","member_id","balance","vwz"];
	protected $table = "member_ledger";

    protected static $rules = [
        'balance'  => 'required|numeric|not_in:0',
        'vwz' => "required",
        'member_id' => 'required|exists:members,id',
        "date" => "required|date"
    ];

    protected static $messages = [
        "balance.required" => "Betrag ist Pflicht.",
        "balance.not_in" => "Betrag darf nicht 0 sein.",
        "balance.numeric" => "Betrag muss eine Zahl sein.",
        "vwz.required" => "Verwendungszweck angeben.",
        "member_id.required" => "Mitglied auswählen!",
        "member_id.exists" => "Mitglied muss existieren!",
        "date.required" => "Datum eingeben.",
        "date.date" => "Datum ist ungültig."
    ];

    public $input = [
        'vwz' => "trim"
    ];

	public function getDateAttribute($date) {
		return date("d.m.Y",strtotime($date));
	}

	public function scopeStarteinlage($query) {
		return $query->select("balance")->where("vwz","LIKE","Starteinlage");
	}

	public function scopeOwn($query) {
		return $query->where("member_id","=",Auth::user()->member_id);
	}

	public function scopeFrom($query,$member_id) {
		return $query->where("member_id","=",$member_id);
	}

	public function scopeBalance($query) {
		return $query->select(DB::raw("SUM(member_ledger.balance) AS balance"))->where("vwz","NOT LIKE","Starteinlage");
	}

	public function scopeLatest($query,$take = 6) {
		return $query->ordered()->take($take);
	}

	public function scopeOrdered($query) {
		return $query->orderBy("date","asc");
	}
}