<?php
/*
    register.php

    Copyright Stefan Fisk 2012.
*/

$this->pageTitle=Yii::app()->name . ' - Register';

$returnUrlQuery = parse_url(Yii::app()->user->returnUrl, PHP_URL_QUERY);
$lightbox = (false !== strpos($returnUrlQuery, 'lightbox=1'));

if ($lightbox) {
    $this->layout = '//layouts/lightbox';
}

?>

<h1>Register</h1>

<p>Please fill out the following form with your personal details:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'register-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>
    <div class="row">
        <?php echo $form->error($model,'save'); ?>
    </div>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <div class="row">
        <?php echo $form->labelEx($model,'firstName'); ?>
        <?php echo $form->textField($model,'firstName'); ?>
        <?php echo $form->error($model,'firstName'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'lastName'); ?>
        <?php echo $form->textField($model,'lastName'); ?>
        <?php echo $form->error($model,'lastName'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'emailAddress'); ?>
        <?php echo $form->textField($model,'emailAddress'); ?>
        <?php echo $form->error($model,'emailAddress'); ?>
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
        <?php echo CHtml::submitButton('Register'); ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<div class="terms-of-service">
    <?php echo CHtml::link(Yii::t('app', 'Terms of Service'), array('/site/termsOfService'), array('target' => '_blank')); ?>
</div>

<?php
if ($lightbox) {
    echo CHtml::button(Yii::t('app', 'Cancel'), array(
        'class' => 'cancel',
        'onclick' => new CJavaScriptExpression('window.parent.app.lightbox.close()'),
    ));
}
?>
