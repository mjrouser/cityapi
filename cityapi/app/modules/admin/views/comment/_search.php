<?php
/* @var $this CommentController */
/* @var $model Comment */
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
        <?php echo $form->label($model,'post_id'); ?>
        <?php echo $form->textField($model,'post_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'user_id'); ?>
        <?php echo $form->textField($model,'user_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'text'); ?>
        <?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->