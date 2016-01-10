<?php
/*
    resetPassword.php

    Copyright Stefan Fisk 2012.
*/

$this->pageTitle = Yii::t('app', 'Reset Password');
$this->layout = '//layouts/main';

?>

<h1><?php Yii::t('app', 'Reset Password'); ?></h1>

<div class="form">
<?php $form = $this->beginWidget('CActiveForm', array(
    'clientOptions' => array(
        'validateOnType' => true,
        'validateOnChange' => true,
        'validateOnSubmit' => true,
    ),
)); ?>
    <div class="row">
        <?php echo $form->error($model,'save'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'passwordConfirmation'); ?>
        <?php echo $form->passwordField($model,'passwordConfirmation'); ?>
        <?php echo $form->error($model,'passwordConfirmation'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Reset Password'); ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
