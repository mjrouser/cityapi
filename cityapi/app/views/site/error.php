<?php
/*
    error.php

    Copyright Stefan Fisk 2012.
*/

/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::t('app', 'Error');
$this->layout = '//layouts/message';
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>