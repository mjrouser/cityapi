<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('first_name')); ?>:</b>
    <?php echo CHtml::encode($data->first_name); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('last_name')); ?>:</b>
    <?php echo CHtml::encode($data->last_name); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('email_address')); ?>:</b>
    <?php echo CHtml::encode($data->email_address); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('password_hash')); ?>:</b>
    <?php echo CHtml::encode($data->password_hash); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('facebook_profile_id')); ?>:</b>
    <?php echo CHtml::encode($data->facebook_profile_id); ?>
    <br />

    <?php /*
    <b><?php echo CHtml::encode($data->getAttributeLabel('registration_time')); ?>:</b>
    <?php echo CHtml::encode($data->registration_time); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
    <?php echo CHtml::encode($data->status); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
    <?php echo CHtml::encode($data->presentation); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('image_id')); ?>:</b>
    <?php echo CHtml::encode($data->image_id); ?>
    <br />

    */ ?>

</div>