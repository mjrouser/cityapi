<?php
/*
    ContactForm.php

    Copyright Stefan Fisk 2012.
*/

class ContactForm extends CFormModel {
	public $name;
	public $email;
	public $message;

	public function rules() {
		return array(
			array(
				'name, email, message',
				'required'
			),

			array(
				'email',
				'email'
			),
		);
	}

    public function attributeLabels() {
        return array(
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'message' => Yii::t('app', 'Message'),
        );
    }
}
