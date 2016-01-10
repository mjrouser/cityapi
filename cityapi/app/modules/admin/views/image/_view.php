<?php
/* @var $this ImageController */
/* @var $data Image */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('width')); ?>:</b>
    <?php echo CHtml::encode($data->width); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('height')); ?>:</b>
    <?php echo CHtml::encode($data->height); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('size')); ?>:</b>
    <?php echo CHtml::encode($data->size); ?>
    <br />

    <div>
        <?php
        echo CHtml::image($data->fileUrl, null, array(
            'style' => 'max-width: 300px'
        ));
        ?>
    </div>

    <br />


</div>