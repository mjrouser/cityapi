<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

    <div class="row">
        <?php echo $form->label($model,'id'); ?>
        <?php echo $form->textField($model,'id'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'first_name'); ?>
        <?php echo $form->textField($model,'first_name',array('size'=>60,'maxlength'=>128)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'last_name'); ?>
        <?php echo $form->textField($model,'last_name',array('size'=>60,'maxlength'=>128)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'email_address'); ?>
        <?php echo $form->textField($model,'email_address',array('size'=>60,'maxlength'=>128)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'facebook_profile_id'); ?>
        <?php echo $form->textField($model,'facebook_profile_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'registration_time'); ?>
        <?php echo $form->textField($model,'registration_time'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'status'); ?>
        <?php echo $form->textField($model,'status'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'is_admin'); ?>
        <?php echo $form->checkbox($model,'is_admin'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'description'); ?>
        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'image_id'); ?>
        <?php echo $form->textField($model,'image_id'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->