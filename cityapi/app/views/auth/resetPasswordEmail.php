<?php
/*
    resetPasswordEmail.php

    Copyright Stefan Fisk 2012.
*/

$this->pageTitle = Yii::t('app', 'Reset Password');
$this->layout = '//layouts/main';

?>

<h1><?php Yii::t('app', 'Reset Password'); ?></h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'clientOptions'=>array(
        'validateOnChange'=>true,
        'validateOnSubmit'=>true,
    ),
)); ?>
    <div class="row">
        <?php echo $form->error($model,'save'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'emailAddress'); ?>
        <?php echo $form->textField($model,'emailAddress'); ?>
        <?php echo $form->error($model,'emailAddress'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Reset Password'); ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
