<?php
/*
    LoginForm.php

    Copyright Stefan Fisk 2012.
*/

class LoginForm extends CFormModel
{
    public $emailAddress;
    public $password;
    public $persistent;

    public function rules() {
        return array(
            array('emailAddress, password', 'required'),
            array('emailAddress', 'email'),
            array('persistent', 'boolean'),
        );
    }

    public function attributeLabels() {
        return array(
            'emailAddress' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'persistent' => Yii::t('app', 'Keep me logged in.'),
        );
    }
}
