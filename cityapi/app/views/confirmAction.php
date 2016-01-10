<?php
/*
    confirmAction.php

    Copyright Stefan Fisk 2012.
*/

/* @var $title string */
/* @var $message string */
/* @var $action string */

$this->layout = '//layouts/lightbox';
?>

<div class="confirm-action">
    <?php $form = $this->beginWidget('CActiveForm'); ?>
        <h1><?php echo $title; ?></h1>
        <p><?php echo $message; ?></p>
        <nav>
            <?php
            echo CHtml::submitButton($action, array('class' => 'submit'));
            echo CHtml::button(Yii::t('app', 'Cancel'), array(
                'class' => 'cancel',
                'onclick' => new CJavaScriptExpression('window.parent.app.lightbox.close()'),
            ));
            ?>
        </nav>
    <?php $this->endWidget(); ?>
</div>
