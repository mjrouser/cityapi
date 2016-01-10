<?php
/*
    create.php

    Copyright Stefan Fisk 2012.
*/

$this->layout = '//layouts/tabs';

$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'validateOnType' => true,
    ),
));
        echo $form->textArea($message,'text', array('placeholder' => Yii::t('app', 'Write your message here…')));
        echo $form->error($message, 'text');
        echo CHtml::submitButton(Yii::t('app', 'Send'), array('class' => 'submit'));
$this->endWidget();
