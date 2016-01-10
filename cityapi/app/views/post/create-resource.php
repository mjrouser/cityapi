<?php
/*
    create-resource.php

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
    <h3><?php echo Yii::t('app', 'Resource'); ?></h3>
    <?php
    echo $form->hiddenField($model, 'user_id');
    echo $form->hiddenField($model, 'feed_id');
    echo $form->hiddenField($model, 'type');

    echo $form->textArea($model,'text', array('placeholder' => Yii::t('app', 'Description (140 characters max!)')));
    echo $form->error($model, 'text');
    ?>
    <div class="resource-type">
        <?php
        echo $form->radioButtonList(
            $model,
            'resource_type',
            array(
                Post::SKILL => Yii::t('app', 'Skill'),
                Post::MATERIAL => Yii::t('app', 'Material'),
                Post::TOOL => Yii::t('app', 'Tool'),
            ),
            array(
                'template' => '<div class="radio">{input}{label}</div>',
                'separator' => '',
                'container' => ''
            )
        );
        ?>
    </div>
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
