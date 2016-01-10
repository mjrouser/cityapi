<?php
/*
    WebUser.php

    Copyright Stefan Fisk 2012.
*/

class WebUser extends CWebUser {
    public $logoutUrl = array('/site/logout');

    public function getModel() {
        if ($this->isGuest) {
            return null;
        }

        return User::model()->findByPk($this->id);
    }

    public function getIsAdmin() {
        return $this->hasState('isAdmin') && $this->getState('isAdmin');
    }
}
