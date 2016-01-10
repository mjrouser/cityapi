<?php
/*
    FacebookUserIdentity.php

    Copyright Stefan Fisk 2012.
*/

class FacebookUserIdentity extends CBaseUserIdentity {
    private $_user;

    public function __construct($user) {
        assert(null !== $user);
        $this->_user = $user;
    }

    public function authenticate() {
        $this->setState('isAdmin', $this->_user->is_admin);

        return true;
    }

    public function getId() {
        return null !== $this->_user ? $this->_user->id : null;
    }
    public function getName() {
        return null !== $this->_user ? $this->_user->first_name : null;
    }
}
