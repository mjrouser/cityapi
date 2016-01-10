<?php
/*
    RegisterForm.php

    Copyright Stefan Fisk 2012.
*/

class RegisterForm extends CFormModel
{
    public $firstName;
    public $lastName;
    public $emailAddress;
    public $password;
    public $passwordConfirmation;

    public function rules() {
        return array(
            array('firstName, lastName, emailAddress, password, passwordConfirmation', 'required'),
            array('emailAddress', 'email'),
            array(
                'emailAddress',
                'unique',
                'className' => 'User',
                'attributeName' => 'email_address',
                'caseSensitive' => false
            ),
            array('password', 'length', 'min' => 8),
            array('password', 'length', 'max' => 128),
            array('passwordConfirmation', 'compare', 'compareAttribute' => 'password'),
        );
    }

    public function attributeLabels() {
        return array(
            'firstName' => 'Your first name',
            'lastName' => 'Your last name',
            'emailAddress' => 'Your email address',
            'password' => 'Your new password',
            'passwordConfirmation' => 'Repeat your new password'
        );
    }
}
