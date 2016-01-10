<?php
/*
    create-text.php

    Copyright Stefan Fisk 2012.
*/

$this->layout = '//layouts/lightbox';

?>
<h2><?php echo Yii::t('app', 'Add New Contribution'); ?></h2>
<?php
$form = $this->beginWidget('CActiveForm', array(
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
        <h3><?php echo Yii::t('app', 'Post'); ?></h3>
        <?php
        echo $form->hiddenField($model, 'user_id');
        echo $form->hiddenField($model, 'feed_id');
        echo $form->hiddenField($model, 'type');
        echo $form->textArea($model,'text', array('placeholder' => Yii::t('app', 'Write your post here…')));
        echo $form->error($model, 'text');
        echo $form->checkBox($model, 'urgent', array('id' => 'urgent'));
        echo $form->label($model, 'urgent', array('for' => 'urgent', 'class' => 'checkbox urgent'));
        ?>
    </section>
    <nav>
        <?php
        echo CHtml::submitButton(Yii::t('app', 'Add'), array('class' => 'submit'));

        echo CHtml::button(Yii::t('app', 'Back'), array(
            'class' => 'back',
            'onclick' => new CJavaScriptExpression('window.location = "' . $this->createUrl('create', array('feed' => $model->feed_id)) . '"'),
        ));
        echo CHtml::button(Yii::t('app', 'Cancel'), array(
            'class' => 'cancel',
            'onclick' => new CJavaScriptExpression('window.parent.app.lightbox.close()'),
        ));
        ?>
    </nav>
<?php
$this->endWidget();
