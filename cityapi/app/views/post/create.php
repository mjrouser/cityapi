<?php
/*
    create.php

    Copyright Stefan Fisk 2012.
*/

$this->layout = '//layouts/lightbox';

?>
<h2><?php echo Yii::t('app', 'Add New Contribution'); ?></h2>
<section class="text selected" data-type="text">
    <h3><?php echo Yii::t('app', 'Post'); ?></h3>
    <?php echo CHtml::link('', array('/post/createText', 'feed' => $feed)); ?>
</section>
<span class="or-1"><?php echo Yii::t('app', 'or'); ?></span>
<section class="image" data-type="image">
    <h3><?php echo Yii::t('app', 'Image'); ?></h3>
    <?php echo CHtml::link('', array('/post/createImage', 'feed' => $feed)); ?>
</section>
<span class="or-2"><?php echo Yii::t('app', 'or'); ?></span>
<section class="resource" data-type="resource">
    <h3><?php echo Yii::t('app', 'Resource'); ?></h3>
    <?php echo CHtml::link('', array('/post/createResource', 'feed' => $feed)); ?>
</section>
<nav>
    <?
    echo CHtml::button(Yii::t('app', 'Cancel'), array(
        'class' => 'cancel',
        'onclick' => new CJavaScriptExpression('window.parent.app.lightbox.close()'),
    ));
    ?>
</nav>