<?php
/*
    login.php

    Copyright Stefan Fisk 2012.
*/

$this->pageTitle = Yii::t('app', 'Login');
$this->layout = '//layouts/lightbox';

?>

<h1><?php echo Yii::t('app', 'Login Required'); ?></h1>
<p><?php echo Yii::t('app', 'You must be logged in to perform this action.'); ?></p>
<nav>

    <?php
    echo CHtml::button(Yii::t('app', 'Login'), array(
        'class' => 'login',
        'onclick' => new CJavaScriptExpression('window.parent.location = "' . $this->createUrl('/auth/login') . '";'),
    ));
    echo CHtml::button(Yii::t('app', 'Cancel'), array(
        'class' => 'cancel',
        'onclick' => new CJavaScriptExpression('window.parent.app.lightbox.close()'),
    ));
    ?>
</nav>