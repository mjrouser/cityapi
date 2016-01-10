<?php
/* @var $this ProjectController */
/* @var $model Project */
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
        <?php echo $form->label($model,'creation_time'); ?>
        <?php echo $form->textField($model,'creation_time'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'created_by_user_id'); ?>
        <?php echo CHtml::activeDropDownList($model, 'created_by_user_id', CHtml::listData(User::model()->findAll(), 'id', 'id')); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'location_id'); ?>
        <?php echo CHtml::activeDropDownList($model, 'location_id', CHtml::listData(Location::model()->findAll(), 'id', 'name')); ?>
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