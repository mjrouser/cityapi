<?php
/* @var $this LocationController */
/* @var $data Location */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('region_id')); ?>:</b>
    <?php echo CHtml::encode($data->region_id); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('longitude')); ?>:</b>
    <?php echo CHtml::encode($data->longitude); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('latitude')); ?>:</b>
    <?php echo CHtml::encode($data->latitude); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
    <?php echo CHtml::encode($data->name); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('slug')); ?>:</b>
    <?php echo CHtml::encode($data->name); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('image_id')); ?>:</b>
    <?php echo CHtml::encode($data->image_id); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('feed_id')); ?>:</b>
    <?php echo CHtml::encode($data->feed_id); ?>
    <br />

</div>