<?php
/*
    help.php

    Copyright Stefan Fisk 2012.
*/

/* @var $this SiteController */

$this->pageTitle = Yii::t('app', 'Help');
$this->layout = '//layouts/message';

?>
<h1><?php echo Yii::t('app', 'Help'); ?></h1>
<p>Vi kommer att lägga upp en FAQ här inom kort.</p>
<p>Tills dess att den är färdig så kan du använda formuläret på <?php echo CHtml::link('Om oss-sidan', array('/site/about')); ?> för att kontakta oss angående hjälp med hur man använder sidan.</p>
