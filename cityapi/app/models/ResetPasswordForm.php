<?php
/*
    ResetPasswordForm.php

    Copyright Stefan Fisk 2012.
*/

class ResetPasswordForm extends CFormModel {
    public $password;
    public $passwordConfirmation;

    public function rules() {
        return array(
            array(
                'password',
                'required',
                'message' => Yii::t('app', 'You must enter a new password.'),
            ),

            array('password', 'length', 'min' => 8),
            array('password', 'length', 'max' => 128),
            array('passwordConfirmation', 'compare', 'compareAttribute' => 'password'),
        );
    }

    public function attributeLabels() {
        return array(
            'password' => 'Your new password',
            'passwordConfirmation' => 'Repeat your new password'
        );
    }
}
