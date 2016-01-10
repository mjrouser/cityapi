<?php

class AdminModule extends CWebModule {
    public function init() {
        $this->setImport(array(
            'admin.models.*',
            'admin.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action) {
        if(parent::beforeControllerAction($controller, $action)) {
            if (!Yii::app()->user->isAdmin) {
                throw new CHttpException(403,'You are not authorized to perform this action.');
            }

            return true;
        } else {
            return false;
        }
    }
}
