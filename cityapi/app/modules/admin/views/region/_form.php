<?php
/* @var $this RegionController */
/* @var $model Region */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'region-form',
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'southwest_longitude'); ?>
        <?php echo $form->textField($model,'southwest_longitude'); ?>
        <?php echo $form->error($model,'southwest_longitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'southwest_latitude'); ?>
        <?php echo $form->textField($model,'southwest_latitude'); ?>
        <?php echo $form->error($model,'southwest_latitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'northeast_longitude'); ?>
        <?php echo $form->textField($model,'northeast_longitude'); ?>
        <?php echo $form->error($model,'northeast_longitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'northeast_latitude'); ?>
        <?php echo $form->textField($model,'northeast_latitude'); ?>
        <?php echo $form->error($model,'northeast_latitude'); ?>
    </div>

    <div class="row">
        <?php
        $timeZoneIdentifiers = DateTimeZone::listIdentifiers(DateTimeZone::AFRICA | DateTimeZone::AMERICA | DateTimeZone::ANTARCTICA | DateTimeZone::ASIA | DateTimeZone::ATLANTIC | DateTimeZone::EUROPE | DateTimeZone::INDIAN | DateTimeZone::PACIFIC);
        $timeZones = array();

        foreach ($timeZoneIdentifiers as $timeZoneIdentifier) {
            $timeZones[$timeZoneIdentifier] = $timeZoneIdentifier;
        }

        ?>
        <?php echo $form->labelEx($model,'time_zone'); ?>
        <?php echo $form->dropDownList($model, 'time_zone', $timeZones); ?>
        <?php echo $form->error($model,'time_zone'); ?>
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
        <?php echo $form->labelEx($model,'footer'); ?>
        <?php echo $form->textArea($model,'footer',array('cols'=>60, 'rows'=>10)); ?>
        <?php echo $form->error($model,'footer'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->