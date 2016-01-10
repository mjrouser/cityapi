<?php
/*
    selectLanguage.php

    Copyright Stefan Fisk 2012
*/

/* @var $languages array(':code' => ':name') */
/* @var $defaultLanguage string */
/* @var $text string */

$this->layout = '//layouts/lightbox';

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('/scripts/select.js');

?>
<div class="container">
    <form method="GET">
        <input type="hidden" value="test">
        <label for="to"><?php echo Yii::t('app', 'Translate text into'); ?></label>
        <span class="select-wrapper languages">
            <div class="text-container">
                <span class="vertical-aligner"></span>
                <span class="text"></span>
            </div>
            <select name="to">
                <?php foreach($languages as $code => $name): ?>
                    <option value="<?php echo $code; ?>" <?php if ($defaultLanguage === $code) echo 'selected="selected"'; ?>><?php echo $name; ?></options>
                <?php endforeach; ?>
            </select>
        </span>
        <div class="text"><?php echo CHtml::encode($text); ?></div>
        <nav>
            <?php
            echo CHtml::submitButton(Yii::t('app', 'Translate'), array('class' => 'translate'));
            echo CHtml::button(Yii::t('app', 'Cancel'), array(
                'class' => 'cancel',
                'onclick' => new CJavaScriptExpression('window.parent.app.lightbox.close()'),
            ));
            ?>
        </nav>
    </form>
</div>
