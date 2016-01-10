<?php
/*
    showTranslation.php

    Copyright Stefan Fisk 2012
*/

/* @var $to string */
/* @var $translatedText string */

$this->layout = '//layouts/lightbox';

?>
<div class="container">
    <div class="language"><?php echo Yii::t('app', 'Translated text into {language}', array('{language}' => $languageName)); ?></div>
    <div class="text"><?php echo CHtml::encode($translatedText); ?></div>
    <nav>
        <?php
        echo CHtml::button(Yii::t('app', 'Close'), array(
            'class' => 'close',
            'onclick' => new CJavaScriptExpression('window.parent.app.lightbox.close()'),
        ));
        ?>
    </nav>
</div>
