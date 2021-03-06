<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'post-form',
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
        <?php echo $form->labelEx($model,'user_id'); ?>
        <?php echo CHtml::activeDropDownList($model, 'user_id', CHtml::listData(User::model()->findAll(), 'id', 'id')); ?>
        <?php echo $form->error($model,'user_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'feed_id'); ?>
        <?php echo $form->textField($model,'feed_id'); ?>
        <?php echo $form->error($model,'feed_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'text'); ?>
        <?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'text'); ?>
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