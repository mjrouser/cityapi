<?php
/* @var $this LocationController */
/* @var $model Location */
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
        <?php echo $form->label($model,'region_id'); ?>
        <?php echo CHtml::activeDropDownList($model, 'region_id', CHtml::listData(Region::model()->findAll(), 'id', 'name')); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'longitude'); ?>
        <?php echo $form->textField($model,'longitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'latitude'); ?>
        <?php echo $form->textField($model,'latitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'slug'); ?>
        <?php echo $form->textField($model,'slug',array('size'=>60,'maxlength'=>128)); ?>
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