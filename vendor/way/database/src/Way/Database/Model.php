<?php namespace Way\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Validation\Validator;

class Model extends Eloquent {

    /**
     * Error message bag
     * 
     * @var Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Validation rules
     * 
     * @var Array
     */
    protected static $rules = array();

    /**
     * Custom messages
     * 
     * @var Array
     */
    protected static $messages = array();

    /**
     * Validator instance
     * 
     * @var Illuminate\Validation\Validators
     */
    protected $validator;

    public function __construct(array $attributes = array(), Validator $validator = null)
    {
        parent::__construct($attributes);

        $this->validator = $validator ?: \App::make('validator');
    }

    /**
     * Listen for save event
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function($model)
        {
            return $model->validate();
        });
    }

    /**
     * Validates current attributes against rules
     */
    public function validate()
    {

        // Failover - we need to reset the rule when we mass assign unique rules.
        // So we memorize the old rule and insert it again after validation.
        $ruleReset = [];

        // if the key's value is greater than 0, then its an existing model
        // so we will replace the placeholder (:id) with the id value
        // otherwise we will just replace it with an empty string
        $replace = ($this->getKey() > 0) ? $this->getKey() : '';

        foreach (static::$rules as $key => $rule)
        {
            $oldRule = $rule;

            $newRule = str_replace(':id', $replace, $rule);
            static::$rules[$key] = $newRule;

            if ($oldRule != $newRule) $ruleReset[$key] = $oldRule;
        }

        $v = $this->validator->make($this->attributes, static::$rules, static::$messages);

        foreach ($ruleReset as $key => $value) {
            // Now reset the rule and insert :id into it, again.
            static::$rules[$key] = $value;
        }

        if ($v->passes())
        {
            return true;
        }

        $this->setErrors($v->messages());

        return false;
    }

    /**
     * Set error message bag
     * 
     * @var Illuminate\Support\MessageBag
     */
    protected function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Retrieve error message bag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Inverse of wasSaved
     */
    public function hasErrors()
    {
        return ! empty($this->errors);
    }

}
