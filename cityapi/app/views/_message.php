<?php
/*
    _message.php

    Copyright Stefan Fisk 2012.
*/

/* @var $message Message */
/* @var $url string */

if (!isset($url)) $url = '#message-' . $message->id;

$userImageSize = 106;
?>

<div id="message-<?php echo $message->id; ?>" class="message">
    <img class="user-image" width="<?php echo $userImageSize; ?>" height="<?php echo $userImageSize; ?>" src="<?php echo $message->sender->image ? $message->sender->image->getFileUrl($userImageSize, $userImageSize, Image::COVER) : Yii::app()->baseUrl . '/images/user-image-default.png' ?>">

    <div class="text-container">

        <div class="chrome">
            <div class="left-top"></div>
            <div class="center-top"></div>
            <div class="right-top"></div>
            <div class="right-center"></div>
            <div class="right-bottom"></div>
            <div class="center-bottom"></div>
            <div class="left-bottom"></div>
            <div class="left-center"></div>
        </div>

        <div class="text linkify">
            <h4>
                <?php
                echo Yii::t('app', '{message} from {userName} @ {creationTime}',
                    array(
                        '{message}' => CHtml::link(CHtml::encode(Yii::t('app', 'Message')), $url),
                        '{userName}' => CHtml::link(CHtml::encode($message->sender->full_name), array('//user/view', 'id' => $message->sender->id)),
                        '{creationTime}' => Yii::app()->dateFormatter->format('HH:mm M/d/y', Yii::app()->localTime->utcToLocal($message->creation_time)),
                    )
                );
                ?>
            </h4>

            <?php echo CHtml::encode($message->text); ?>

        </div>

    </div>

</div>
