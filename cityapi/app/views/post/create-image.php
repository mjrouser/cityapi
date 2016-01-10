<?php
/*
    create-image.php

    Copyright Stefan Fisk 2012.
*/

$this->layout = '//layouts/lightbox';

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('/scripts/file-input.js');

?>
<h2><?php echo Yii::t('app', 'Add New Contribution'); ?></h2>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array('enctype'=>'multipart/form-data'),
));
?>
    <section>
        <h3><?php echo Yii::t('app', 'Image'); ?></h3>
        <?php
        echo $form->hiddenField($post, 'user_id');
        echo $form->hiddenField($post, 'feed_id');
        ?>
        <div class="container">
            <?php
            echo CHtml::label(Yii::t('app', 'Select an image file on your computer.'), 'Post[image_file]');
            echo $form->fileField($image, 'file');
            echo CHtml::label('', 'Image[file]');
            echo CHtml::button(Yii::t('app', 'Choose file…'), array(
                'class' => 'file',
                'onclick' => new CJavaScriptExpression('$(this).siblings("input[type=file]")[0].click();'),
            ));
            echo $form->error($image, 'file');
            ?>
        </div>
    </section>
    <nav>
        <?php
        echo CHtml::submitButton(Yii::t('app', 'Add'), array('class' => 'submit'));
        echo CHtml::button(Yii::t('app', 'Back'), array(
            'class' => 'back',
            'onclick' => new CJavaScriptExpression('window.location = "' . $this->createUrl('create', array('feed' => $post->feed_id)) . '"'),
        ));
        echo CHtml::button(Yii::t('app', 'Cancel'), array(
            'class' => 'cancel',
            'onclick' => new CJavaScriptExpression('window.parent.app.lightbox.close()'),
        ));
        ?>
</nav>
<?php
$this->endWidget();
