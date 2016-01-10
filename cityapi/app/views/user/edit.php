<?php
/*
    edit.php

    Copyright Stefan Fisk 2012.
*/

$this->layout = '//layouts/lightbox';

Yii::app()->clientScript->registerScriptFile('/scripts/file-input.js');

?>

<h2><?php echo Yii::t('app', 'Edit Profile'); ?></h2>
<?php

$form = $this->beginWidget('CActiveForm', array(
    'action' => array('//user/edit', 'id' => $user->id),
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'validateOnType' => true,
    ),
    'htmlOptions' => array('enctype'=>'multipart/form-data'),
));
    ?>
    <div class="image">
        <h3><?php echo Yii::t('app', 'Profile Photo'); ?></h3>
        <?php
        echo CHtml::label(Yii::t('app', 'Select an image file on your computer.'), 'Post[image_file]');
        echo $form->fileField($image, 'file');
        echo CHtml::label('', 'Image[file]');
        echo CHtml::button(Yii::t('app', 'Choose file…'), array(
            'class' => 'file',
            'onclick' => new CJavaScriptExpression('$(this).siblings("input[type=file]")[0].click();'),
        ));
        echo $form->error($image, 'file');
        ?><div class="or"><?php echo Yii::t('app', 'or'); ?></div><?php
        echo CHtml::submitButton(Yii::t('app', 'Use your Facebook profile picture'), array('name' => 'use-facebook-picture', 'class' => 'use-facebook-picture'));
        ?>
    </div>
    <div class="description">
        <h3><?php echo Yii::t('app', 'About Me'); ?></h3>
        <?php
        echo $form->textArea($user, 'description', array('placeholder' => Yii::t('app', 'Write a short presentation of yourself here…')));
        echo $form->error($user, 'description');
        ?>
    </div>
    <?php
    echo CHtml::submitButton(Yii::t('app', 'Save'), array('class' => 'submit'));
$this->endWidget();

echo CHtml::button(Yii::t('app', 'Cancel'), array(
    'class' => 'cancel',
    'onclick' => new CJavaScriptExpression('window.parent.app.lightbox.close()'),
));
