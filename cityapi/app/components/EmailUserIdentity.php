<?php
/*
    EmailUserIdentity.php

    Copyright Stefan Fisk 2012.
*/

class EmailUserIdentity extends CBaseUserIdentity {
    const ERROR_NOT_FOUND = 10;
    const ERROR_PASSWORD_INVALID = 11;
    const ERROR_DISABLED = 12;
    const ERROR_PASSWORD_NOT_SET = 13;

    private $_user;
    private $_password;

    public function __construct($emailAddress, $password) {
        $this->_user = User::model()->find('LOWER(email_address)=?', array(strtolower($emailAddress)));
        $this->_password = $password;
        $this->setState('isAdmin', false);
    }

    public function authenticate() {
        $passwordHasher = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);

        if (null === $this->_user){
            $this->errorCode = self::ERROR_NOT_FOUND;
        } else if(User::DISABLED === $this->_user->status) {
            $this->errorCode = self::ERROR_DISABLED;
        } else if (null === $this->_user->password_hash) {
            $this->errorCode = self::ERROR_PASSWORD_NOT_SET;
        } else if($passwordHasher->CheckPassword($this->_user->password_hash, $this->_password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode = self::ERROR_NONE;
        }

        if (self::ERROR_NONE !== $this->errorCode) {
            return $this->errorCode;
        }

        $this->setState('isAdmin', $this->_user->is_admin);

        return self::ERROR_NONE;
    }

    public function getId() {
        return null !== $this->_user ? $this->_user->id : null;
    }
    public function getName() {
        return null !== $this->_user ? $this->_user->first_name : null;
    }
}
