<?php
/*
    DefaultController.php

    Copyright Stefan Fisk 2012.
*/

class DefaultController extends Controller {
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionView()
    {
        $this->render('view');
    }
}