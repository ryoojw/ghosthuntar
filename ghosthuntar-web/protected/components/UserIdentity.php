<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
	
	private $_id;

	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {
	
		$model = User::model()->find('email=?', array($this->username)); // this is actually referring to the email address
		
		if ($model === null)
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		else if (!$model->validatePassword($this->password))
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		else {
		
			$this->_id = $model->id;
			$this->setState('display_name', $model->first_name);
			$this->username = $model->email;
			$this->errorCode = self::ERROR_NONE;
		}
		
		return !$this->errorCode;
	}
	
	public function getId() { return $this->_id; }
}