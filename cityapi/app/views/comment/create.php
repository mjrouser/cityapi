<?php
/*
    create.php

    Copyright Stefan Fisk 2012.
*/

$this->layout = '//layouts/lightbox';

?>
<h2><?php echo Yii::t('app', 'New comment'); ?></h2>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => array('//comment/create'),
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'validateOnType' => true,
    ),
));
?>
    <section>
        <h3><?php echo Yii::t('app', 'Comment'); ?></h3>
        <?php
        echo $form->hiddenField($model, 'user_id');
        echo $form->hiddenField($model, 'post_id');
        echo $form->textArea($model,'text', array('placeholder' => Yii::t('app', 'Write your comment here…')));
        echo $form->error($model, 'text');
        ?>
    </section>
    <nav>
        <?php
        echo CHtml::submitButton(Yii::t('app', 'Send'), array('class' => 'submit'));
        echo CHtml::button(Yii::t('app', 'Cancel'), array(
            'class' => 'cancel',
            'onclick' => new CJavaScriptExpression('window.parent.app.lightbox.close()'),
        ));
        ?>
    </nav>
<?php
$this->endWidget();
