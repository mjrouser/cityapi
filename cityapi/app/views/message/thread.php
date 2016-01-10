<?php
/*
    thread.php

    Copyright Stefan Fisk 2012.
*/

/* @var $this CommentController */
/* @var $recipient User */

$this->pageTitle = CHtml::encode($recipient->full_name . Yii::app()->params['titleSeparator'] . Yii::t('app', 'Messages'));
$this->layout = '//layouts/tabs';

Yii::app()->clientScript->registerScriptFile('/scripts/jquery.linkify.js');

$this->beginWidget('application.widgets.OuterBoxWidget', array(
    'class' => 'thread',
    'title' => Yii::t('app', 'Conversation with {userName}', array(
        '{userName}' => CHtml::link(CHtml::encode($recipient->full_name), array('//user/view', 'id' => $recipient->id)),
    )),
    'color' => '#00AEEF',
));
?>
    <ul class="messages">
        <?php
        $messages = Message::model()->findAll(
            '(sender_id = :user1_id OR recipient_id = :user1_id) AND (sender_id = :user2_id OR recipient_id = :user2_id)',
            array(
                ':user1_id' => Yii::app()->user->id,
                ':user2_id' => $recipient->id
            ),
            array('order' => 'creation_time')
        );
        ?>
        <?php foreach($messages as $message): ?>
            <li>
                <?php $this->renderPartial('//_message', array('message' => $message)); ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php
    $newMessage = new Message;
    $newMessage->sender_id = Yii::app()->user->id;
    $newMessage->recipient_id = $recipient->id;

    $form = $this->beginWidget('CActiveForm', array(
        'action' => array('create', 'recipient' => $recipient->id),
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => true,
            'validateOnType' => true,
        ),
        'htmlOptions'=>array(
            'class' => 'send',
        ),
    ));
        echo $form->textArea($newMessage,'text', array('placeholder' => Yii::t('app', 'Write your message here…')));
        echo $form->error($newMessage, 'text');
        echo CHtml::submitButton(Yii::t('app', 'Send'), array('class' => 'submit'));
    $this->endWidget();
$this->endWidget();
