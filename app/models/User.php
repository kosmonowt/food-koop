<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Filter\HasFilters;

class User extends AppModel implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait, HasFilters;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	protected $fillable = ['user_group_id','username','firstname','lastname','password','email','telephone','member_id'];

    protected $appends = ['isAdmin','isSuperAdmin','name'];

    protected $dynamicHidden = ["last_login" => array("<2"), "user_group_id" => array("<2"), "username" => array("<2"), "isSuperAdmin" => array("<2"), "isAdmin" => array("<2")  ];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	protected static $rules = [
		'user_group_id'  => 'required|exists:user_groups,id',
        'username' => 'required|unique:users,username,:id',
	    'firstname' => "required",
        'lastname' => "required",
        "email" => 'required|unique:users,email,:id',
        "telephone" => "required",
        "member_id" => "required|exists:members,id"
    ];

    protected static $messages = [
        'user_group_id.required' => 'Bitte einer Usergruppe zuordnen.',
        'user_group_id.exists' => 'Die Usergruppe existiert nicht.',
        'username.required' => 'Benutzername angeben.',
        'username.unique' => 'Benutzername ist bereits vergeben.',
        "firstname.required" => "Bitte Vornamen angeben.",
        'lastname.required' => "Bitte Nachnamen angeben.",
        "email.required" => "Bitte E-Mail Adresse angeben.",
        "email.unique" => "Emailadresse berits vergeben.",
        "telephone.required" => "Bitte Telefonnummer angeben.",
        "member_id.required" => "Bitte einer Mitgliedergruppe zuweisen.",
        "member_id.exists" => "Die Mitgliedergruppe existiert nicht!",
    ];

    public $input = [
        'username' => 'trim',
        'password' => 'trim',
        'firstname' => 'trim|capfirst',
        'lastname' => 'trim|capfirst',
        'email' => 'trim',
        'telephone' => "trim"
    ];

    public function member() {
        return $this->belongsTo("member","member_id");
    }

    public function getNameAttribute() {
        return $this->firstname." ".$this->lastname;
    }

    public function getIsAdminAttribute() {
        return ($this->user_group_id > 1);
    }

    public function getIsSuperAdminAttribute() {
        return ($this->user_group_id == 3);
    }

}
