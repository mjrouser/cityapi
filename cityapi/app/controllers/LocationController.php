<?php
/*
    LocationController.php

    Copyright Stefan Fisk 2012.
*/

class LocationController extends Controller {
    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array('map', 'list', 'view'),
                'users' => array('*'),
            ),

            array(
                'deny',
            ),
        );
    }

    public function actionMap($id) {
        $region = $this->loadRegion($id);
        $this->currentRegion = $region;
        $this->render('map', array('region' => $region));
    }

    public function actionList($id) {
        $region = $this->loadRegion($id);
        $this->currentRegion = $region;
        $this->render('list', array('region' => $region));
    }

    public function actionView($id) {
        $location = $this->loadLocation($id);
        $this->currentRegion = $location->region;
        $this->render('view', array('location' => $location));
    }

    public function loadRegion($id) {
        $model = Region::model()->findByPk($id);

        if(null === $model) {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }

        return $model;
    }

    public function loadLocation($id) {
        $model = Location::model()->findByPk($id);

        if(null === $model) {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }

        return $model;
    }
}
