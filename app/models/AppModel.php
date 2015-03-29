<?php
/**
 * Core Model file for this app.
 * This brings functionality to add dynamic hidden fields respecting the user that is calling the model
 * 
 **/
class AppModel extends Model {


	protected $dynamicHidden = array(); // Dynamic hidden field set by user_group

	public function scopeDynamicHide($query) {

		// If attribue dynamicHidden is filled
		if (count($this->dynamicHidden)) {

			$hidden = $this->getHidden();	// Init general hidden attributes

			$userGroupId = Auth::user()->user_group_id; // Get UserGroupId

			$operators = [">","<"];	// For now we can manage this operators. (only one char)

			foreach ($this->dynamicHidden as $key => $value) {
				
				// Check if UserGroupId is in "hidden" - array add "key" to $hidden
				if (in_array($userGroupId, $value)) {
					$hidden[] = $key;
					continue;
					// Done, next value.
				}

				// If usergroup is not in hidden
				$operator = substr($value[0], 0,1);

				// Check if operator is one of the chars from above
				if ( in_array($operator,$operators) ) {
					// Read operand (group_id) from following chars.
					$operand = substr($value[0],1);
					if ( (($operator == "<") && $userGroupId < $operand) ||  (($operator == ">") && $userGroupId > $operand) ) $hidden[] = $key;

				}
			
			}

			$this->setHidden($hidden);

		}

		return $query;

	}

	public function getStaticAttribute() {
		return false;
	}


}