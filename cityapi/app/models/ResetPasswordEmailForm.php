<?php
/*
    ResetPasswordEmailForm.php

    Copyright Stefan Fisk 2012.
*/

class ResetPasswordEmailForm extends CFormModel {
    public $emailAddress;

    public function rules() {
        return array(
            array(
                'emailAddress',
                'userExists',
                'message' => Yii::t('app', 'There is no user registered with that email address.'),
            ),
        );
    }

    public function userExists($attribute, $params) {
        if (!User::model()->exists('email_address = :email_address', array(':email_address' => $this->$attribute))) {
            $this->addError($attribute, $params['message']);
        }
    }

    public function attributeLabels() {
        return array(
            'emailAddress' => Yii::t('app', 'The email address you used when registering your account.'),
        );
    }
}
