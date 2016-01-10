<?php
/*
    resetPasswordEmailSent.php

    Copyright Stefan Fisk 2012.
*/

$this->pageTitle = Yii::t('app', 'Reset Password');
$this->layout = '//layouts/main';

?>
<h1><?php echo Yii::t('app', 'Reset Password'); ?></h1>
<p><?php echo Yii::t('app', 'An email has been sent to {emailAddress} to verify that it is correct.', array('{emailAddress}' => CHtml::encode($model->emailAddress))); ?></p>
