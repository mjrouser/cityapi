<?php
/*
    about.php

    Copyright Stefan Fisk 2012.
*/

/* @var $this SiteController */
/* @var $form ContactForm */

$this->pageTitle = Yii::t('app', 'About');

$this->beginWidget('application.widgets.OuterBoxWidget', array(
    'class' => 'about',
    'title' => Yii::t('app', 'About'),
    'color' => '#0081AC',
));
    $this->renderPartial('_cityAPIPresentation');
$this->endWidget();

$this->beginWidget('application.widgets.OuterBoxWidget', array(
    'id' => 'contact',
    'class' => 'contact',
    'title' => Yii::t('app', 'Contact CityAPI'),
    'color' => '#0081AC',
));
    if (!Yii::app()->user->hasFlash('contact')) {
        ?>
        <p><?php echo Yii::t('app', 'Please feel free to drop us a line and we’ll respond as fast as we can!'); ?></p>
        <?php
    } else {
        ?>
        <p class="flash-success">
            <?php echo Yii::app()->user->getFlash('contact'); ?>
        </p>
        <?php
    }

    $model = $form;
    CHtml::$afterRequiredLabel = '';
    $form = $this->beginWidget('CActiveForm', array(
        'id'=>'contact-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => true,
            'validateOnType' => true,
        ),
    ));
    ?>
        <div class="row">
            <?php
            echo $form->labelEx($model, 'name');
            echo $form->textField($model, 'name');
            echo $form->error($model, 'name');
            ?>
        </div>
        <div class="row">
            <?php
            echo $form->labelEx($model, 'email');
            echo $form->textField($model, 'email');
            echo $form->error($model, 'email'); 
            ?>
        </div>
        <div class="row">
            <?php
            echo $form->labelEx($model, 'message');
            echo $form->textArea($model, 'message');
            echo $form->error($model, 'message');
            ?>
        </div>
        <div class="row buttons">
            <?php echo CHtml::submitButton(Yii::t('app', 'Send!')); ?>
        </div>
    <?php $this->endWidget(); ?>
    <div class="social">
        <div class="row"><label><?php echo Yii::t('app', 'Socially'); ?></label></div>
        <div class="row">
            <a class="facebook" href="https://www.facebook.com/cityapi" target="_blank"></a>
            <a class="twitter" href="https://twitter.com/CityAPI" target="_blank"></a>
        </div>
    </div>
<?php
$this->endWidget();
