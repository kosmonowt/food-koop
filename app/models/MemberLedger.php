<?php

class MemberLedger extends \Eloquent {

	use SoftDeletingTrait;

	protected $fillable = [];
	protected $table = "member_ledger";
}