<?php
/* @var $this ProjectController */
/* @var $model Project */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'project-form',
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'creation_time'); ?>
        <?php echo $form->textField($model,'creation_time'); ?>
        <?php echo $form->error($model,'creation_time'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'created_by_user_id'); ?>
        <?php echo CHtml::activeDropDownList($model, 'created_by_user_id', CHtml::listData(User::model()->findAll(), 'id', 'id')); ?>
        <?php echo $form->error($model,'created_by_user_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'location_id'); ?>
        <?php echo CHtml::activeDropDownList($model, 'location_id', CHtml::listData(Location::model()->findAll(), 'id', 'name')); ?>
        <?php echo $form->error($model,'location_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'slug'); ?>
        <?php echo $form->textField($model,'slug',array('size'=>60,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'slug'); ?>
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