<?php
/*
    create.php

    Copyright Stefan Fisk 2012.
*/

$this->layout = '//layouts/lightbox';

?>
<h2><?php echo Yii::t('app', 'New Project'); ?></h2>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'validateOnType' => true,
    ),
));
?>
    <section>
        <div class="errorMessages">
            <?php
            echo $form->error($model, 'description');
            echo $form->error($model, 'name');
            ?>
        </div>
        <?php
        echo $form->textField($model, 'name', array('placeholder' => Yii::t('app', 'Title')));
        echo $form->textArea($model,'description', array('placeholder' => Yii::t('app', 'Description')));
        ?>
    </section>
    <nav>
        <?php
        echo CHtml::submitButton(Yii::t('app', 'Add'), array('class' => 'submit'));
        echo CHtml::button(Yii::t('app', 'Cancel'), array(
            'class' => 'cancel',
            'onclick' => new CJavaScriptExpression('window.parent.app.lightbox.close()'),
        ));
        ?>
    </nav>
<?php
$this->endWidget();
