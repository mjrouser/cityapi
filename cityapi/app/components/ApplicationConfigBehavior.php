<?php
/*
    ApplicationConfigBehavior.php

    Copyright Stefan Fisk 2012.
*/

class ApplicationConfigBehavior extends CBehavior {
    public function events() {
        return array_merge(parent::events(), array(
            'onBeginRequest'=>'beginRequest',
        ));
    }
 
    public function beginRequest() {
        Yii::import('application.widgets.LanguageSelector');
        LanguageSelector::setLanguage();

        if (null === Yii::app()->session['region'] || !Region::model()->exists('id = :id', array(':id' => Yii::app()->session['region']))) {
            Yii::app()->session['region'] = Region::model()->getDefault()->id;
            assert(null !== Yii::app()->session['region']);
        }
    }
}
