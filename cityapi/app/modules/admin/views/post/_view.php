<?php
/* @var $this PostController */
/* @var $data Post */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('creation_time')); ?>:</b>
    <?php echo CHtml::encode($data->creation_time); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
    <?php echo CHtml::encode($data->user_id); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('feed_id')); ?>:</b>
    <?php echo CHtml::encode($data->feed_id); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('text')); ?>:</b>
    <?php echo CHtml::encode($data->text); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('image_id')); ?>:</b>
    <?php echo CHtml::encode($data->image_id); ?>
    <br />


</div>