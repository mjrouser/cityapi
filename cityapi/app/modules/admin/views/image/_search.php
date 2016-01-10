<?php
/* @var $this ImageController */
/* @var $model Image */
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
        <?php echo $form->label($model,'width'); ?>
        <?php echo $form->textField($model,'width'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'height'); ?>
        <?php echo $form->textField($model,'height'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'size'); ?>
        <?php echo $form->textField($model,'size'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->