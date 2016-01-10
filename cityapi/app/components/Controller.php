<?php
/*
    Controller.php

    Copyright Stefan Fisk 2012.
*/

class Controller extends CController {
    public $layout = '//layouts/main';
    public $activeTab = '';

    private $_currentRegion;
    private $_nonCurrentRegions;

    public function getCurrentRegion() {
        if (null === $this->_currentRegion) {
            assert(null !== Yii::app()->session['region']);

            $this->_currentRegion = Region::model()->findByPk(Yii::app()->session['region']);
        }

        return $this->_currentRegion;
    }
    public function setCurrentRegion($region) {
        assert(null !== $region);
        assert(!$region->isNewRecord);

        $this->_currentRegion = $region;
        Yii::app()->session['region'] = $region->id;
        $this->_nonCurrentRegions = null;
    }

    public function getNonCurrentRegions() {
        if (null === $this->_nonCurrentRegions) {
            $this->_nonCurrentRegions = Region::model()->findAll('id != :regionId', array(':regionId' => $this->currentRegion->id));
        }

        return $this->_nonCurrentRegions;
    }
}
