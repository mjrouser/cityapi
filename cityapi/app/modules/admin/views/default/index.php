<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
    $this->module->id,
);
?>
<h1><?php echo $this->uniqueId . '/' . $this->action->id; ?></h1>

<div class="row">
    <?php echo CHtml::link(Yii::t('app', 'Regions'), array('region/index')); ?>
</div>

<div class="row">
    <?php echo CHtml::link(Yii::t('app', 'Locations'), array('location/index')); ?>
</div>

<div class="row">
    <?php echo CHtml::link(Yii::t('app', 'Projects'), array('project/index')); ?>
</div>

<div class="row">
    <?php echo CHtml::link(Yii::t('app', 'Posts'), array('post/index')); ?>
</div>

<div class="row">
    <?php echo CHtml::link(Yii::t('app', 'Comments'), array('comment/index')); ?>
</div>

<div class="row">
    <?php echo CHtml::link(Yii::t('app', 'Images'), array('image/index')); ?>
</div>

<div class="row">
    <?php echo CHtml::link(Yii::t('app', 'Users'), array('user/index')); ?>
</div>
