<?php
/* @var $this RegionController */
/* @var $model Region */
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
        <?php echo $form->label($model,'southwest_longitude'); ?>
        <?php echo $form->textField($model,'southwest_longitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'southwest_latitude'); ?>
        <?php echo $form->textField($model,'southwest_latitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'northeast_longitude'); ?>
        <?php echo $form->textField($model,'northeast_longitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'northeast_latitude'); ?>
        <?php echo $form->textField($model,'northeast_latitude'); ?>
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
        <?php echo $form->label($model,'footer'); ?>
        <?php echo $form->textField($model,'footer'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->