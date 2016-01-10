<?php
/*
    notifications.php

    Copyright Stefan Fisk 2012.
*/

/* var $userId int */

$this->pageTitle = CHtml::encode(Yii::t('app', 'Notifications'));
$this->layout = '//layouts/tabs';

$this->renderPartial('_notifications', array('userId' => $userId));
