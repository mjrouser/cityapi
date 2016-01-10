<?php
/* @var $this ProjectController */
/* @var $data Project */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('creation_time')); ?>:</b>
    <?php echo CHtml::encode($data->creation_time); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('created_by_user_id')); ?>:</b>
    <?php echo CHtml::encode($data->created_by_user_id); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('location_id')); ?>:</b>
    <?php echo CHtml::encode($data->location_id); ?>
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