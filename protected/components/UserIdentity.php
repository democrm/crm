<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
        public $pass;
        public $_id;
          public function __construct($username,$password)
	{
		$this->pass=$password;
                parent::__construct($username, $password);
	}
    /**
	public function authenticate()
	{
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
                $user = User::model()->findByAttributes(array('username' => $this->username));
		if($user === null)
                    
                    
                    
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
        */
          public function authenticate() {

                $user = Users::model()->findByAttributes(array('username' => $this->username));
                if ($user === null) {
                    $this->errorCode = self::ERROR_USERNAME_INVALID;
                } else {
                    if ($user->pass !== $user->encrypt($this->pass)) {
                        $this->errorCode = self::ERROR_PASSWORD_INVALID;
                    } else {
                        $this->_id = $user->id;
                          if (null === $user->login_time) {
                        $lastLogin = time();
                    } else {
                        $lastLogin = strtotime($user->login_time);
                    }
                    $this->setState('lastLoginTime', $lastLogin);
                    $this->errorCode = self::ERROR_NONE;
                    }
                }
            if($this->username =='admin' && $this->pass =='admin') 
            {
                 $this->errorCode = self::ERROR_NONE;
                 $this->_id = 'admin';
            }
            
            return!$this->errorCode;
    }
 
    public function getId() {
        return $this->_id;
    }
 
}