<?php
/*
    _comment.php

    Copyright Stefan Fisk 2012.
*/

/* @var $comment Comment */
/* @var $url string */

if (!isset($url)) $url = '#comment-' . $comment->id;

Yii::app()->clientScript->registerScriptFile('/scripts/jquery.linkify.js');

$userImageSize = 106;
?>

<div id="comment-<?php echo $comment->id; ?>" class="comment">
    <img class="user-image" width="<?php echo $userImageSize; ?>" height="<?php echo $userImageSize; ?>" src="<?php echo $comment->user->image ? $comment->user->image->getFileUrl($userImageSize, $userImageSize, Image::COVER) : Yii::app()->baseUrl . '/images/user-image-default.png' ?>">
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
                echo Yii::t('app', '{comment} added by {userName} @ {postCreationTime}',
                    array(
                        '{comment}' => CHtml::link(CHtml::encode(Yii::t('app', 'Comment')), $url),
                        '{userName}' => CHtml::link(CHtml::encode($comment->user->full_name), array('//user/view', 'id' => $comment->user->id)),
                        '{postCreationTime}' => Yii::app()->dateFormatter->format('HH:mm M/d/y', Yii::app()->localTime->utcToLocal($comment->creation_time)),
                    )
                );
                ?>
            </h4>
            <?php echo CHtml::encode($comment->text); ?>
            <?php
            echo CHtml::button(Yii::t('app', 'Translate'), array(
                'class' => 'translate',
                'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('//translate/comment', array('id' => $comment->id)) . '");'),
            ));
            ?>
        </div>
        <?php
        if (Yii::app()->user->checkAccess('deleteComment', array('comment' => $comment))) {
            echo CHtml::button(Yii::t('app', 'Delete'), array(
                'class' => 'delete',
                'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('//comment/delete', array('id' => $comment->id)) . '");'),
            ));
        }
        ?>
    </div>
</div>
