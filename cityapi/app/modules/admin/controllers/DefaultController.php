<?php
/*
    DefaultController.php

    Copyright Stefan Fisk 2012.
*/

class DefaultController extends AdminController
{
    public function actionIndex()
    {
        $this->render('index');
    }
}