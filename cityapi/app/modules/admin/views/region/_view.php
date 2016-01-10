<?php
/* @var $this RegionController */
/* @var $data Region */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('southwest_longitude')); ?>:</b>
    <?php echo CHtml::encode($data->southwest_longitude); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('southwest_latitude')); ?>:</b>
    <?php echo CHtml::encode($data->southwest_latitude); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('northeast_longitude')); ?>:</b>
    <?php echo CHtml::encode($data->northeast_longitude); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('northeast_latitude')); ?>:</b>
    <?php echo CHtml::encode($data->northeast_latitude); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('time_zone')); ?>:</b>
    <?php echo CHtml::encode($data->time_zone); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
    <?php echo CHtml::encode($data->name); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('slug')); ?>:</b>
    <?php echo CHtml::encode($data->name); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('footer')); ?>:</b>
    <?php echo CHtml::encode($data->footer); ?>
    <br />

</div>