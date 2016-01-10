<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'user-form',
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'first_name'); ?>
        <?php echo $form->textField($model,'first_name',array('size'=>60,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'first_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'last_name'); ?>
        <?php echo $form->textField($model,'last_name',array('size'=>60,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'last_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'email_address'); ?>
        <?php echo $form->textField($model,'email_address',array('size'=>60,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'email_address'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'password_hash'); ?>
        <?php echo $form->textField($model,'password_hash',array('size'=>60,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'password_hash'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'facebook_profile_id'); ?>
        <?php echo $form->textField($model,'facebook_profile_id'); ?>
        <?php echo $form->error($model,'facebook_profile_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'registration_time'); ?>
        <?php echo $form->textField($model,'registration_time'); ?>
        <?php echo $form->error($model,'registration_time'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->textField($model,'status'); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'is_admin'); ?>
        <?php echo $form->checkbox($model,'is_admin'); ?>
        <?php echo $form->error($model,'is_admin'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'image_id'); ?>
        <?php echo $form->textField($model,'image_id'); ?>
        <?php echo $form->error($model,'image_id'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->