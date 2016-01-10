<?php
/*
    tabs.php

    Copyright Stefan Fisk 2012.
*/

$this->beginContent('//layouts/main');

?>
<div class="tabs">
    <?php
    if ('map' === $this->activeTab) {
        echo CHtml::link($this->pageTitle, array('location/map'), array('class' => 'tab map active'));
    } else {
        echo CHtml::link(Yii::t('app', 'Map'), array('location/map'), array('class' => 'tab map'));
    }
    ?>
    <?php
    if ('spaces' === $this->activeTab) {
        echo CHtml::link($this->pageTitle, array($this->route, 'id' => Yii::app()->getRequest()->getQuery('id')), array('class' => 'tab spaces active'));
    } else {
        echo CHtml::link(Yii::t('app', 'Spaces'), array('location/list'), array('class' => 'tab spaces'));
    }
    ?>
    <?php
    if ('resources' === $this->activeTab) {
        echo CHtml::link($this->pageTitle, array('resource/list'), array('class' => 'tab resources active'));
    } else {
        echo CHtml::link(Yii::t('app', 'Resources'), array('resource/list'), array('class' => 'tab resources'));
    }
    ?>
</div>
<div class="content">
    <?php echo $content; ?>
</div><!-- content -->
<?php $this->endContent(); ?>
