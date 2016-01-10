<?php
/*
    list.php

    Copyright Stefan Fisk 2012.
*/

/* @var $this CommentController */

$this->pageTitle = CHtml::encode(Yii::t('app', 'Messages'));
$this->layout = '//layouts/tabs';

$this->beginWidget('application.widgets.OuterBoxWidget', array(
    'class' => 'messages',
    'title' => Yii::t('app', 'Conversations'),
    'color' => '#00AEEF',
));
?>
    <ol class="threads">
        <?php
        $lastMessages = Message::model()->findAllBySql(
<<<EOD
    SELECT
        *
    FROM (
        SELECT
            *,
            CASE
                WHEN sender_id = :user_id THEN recipient_id
                ELSE sender_id
            END AS thread_recipient_id
        FROM (
            SELECT
                *
            FROM message
            WHERE sender_id = :user_id OR recipient_id = :user_id
            ORDER BY creation_time DESC
        )
        AS s
        GROUP BY thread_recipient_id
    )
    AS s
    ORDER BY creation_time DESC
EOD
    ,
            array(':user_id' => Yii::app()->user->id)
        );

        foreach($lastMessages as $message) {
            $sent = $message->sender_id === Yii::app()->user->id;
            $threadRecipient = $sent ? $message->recipient : $message->sender;
            ?>
            <li>
                <img width="106" height="106" src="<?php echo $threadRecipient->image ? $threadRecipient->image->getFileUrl(106, 106, Image::COVER) : Yii::app()->baseUrl . '/images/user-image-default.png' ?>">
                <?php
                $this->beginWidget('application.widgets.InnerBoxWidget', array(
                    'class' => 'thread',
                    'title' => Yii::t(
                        'app', '{sent}{userName} @ {creationTime}',
                        array(
                            '{sent}' => $sent ? '<span class="sent">↩</span>' : '',
                            '{userName}' => CHtml::link(CHtml::encode($threadRecipient->full_name), array('/message/thread', 'recipient' => $threadRecipient->id, '#' => 'message-' . $message->id)),
                            '{creationTime}' => Yii::app()->dateFormatter->format('HH:mm M/d/y', Yii::app()->localTime->utcToLocal($message->creation_time))
                        )
                    ),
                    'color' => '#00AEEF',
                ));
                ?>
                    <?php echo CHtml::encode($message->text); ?>
                <?php $this->endWidget(); ?>
            </li>
            <?php
        }
        ?>
    </ol>
<?php
$this->endWidget();
