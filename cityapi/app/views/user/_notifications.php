<?php
/*
    _notifications.php

    Copyright Stefan Fisk 2012
*/

/* var $userId int */
/* var $onlyUnviewed bool */

if (!isset($onlyUnviewed)) {
    $onlyUnviewed = false;
}

$this->beginWidget('application.widgets.OuterBoxWidget', array(
    'class' => 'notifications feed',
    'title' => CHtml::link(Yii::t('app', 'Notifications'), array('notifications')),
));
    $notifications = $onlyUnviewed ? Notification::model()->findAllUnviewed($userId) : Notification::model()->findAllByAttributes(array('user_id' => $userId));

    if (0 !== count($notifications)) {
        ?>
        <ul class="notifications">
            <?php foreach ($notifications as $notification): ?>
                <?php
                if (RecordOperationLog::INSERT !== $notification->log->action) {
                    Yii::log('Unknown notification log action. $notification = ' . $notification->id, 'error', 'app.user._notifications');

                    $notification->viewed = true;
                    $notification->save();

                    continue;
                }
                ?>
                <?php if ('Message' === $notification->log->model): ?>
                    <?php
                    $message = Message::model()->findByPk($notification->log->model_id);

                    if (!$message) {
                        $notification->viewed = true;
                        $notification->save();

                        continue;
                    }
                    ?>
                    <li>
                        <?php
                        $this->renderPartial('//_message', array(
                            'message' => $message,
                            'url' => $this->createUrl('notification', array('id' => $notification->id))
                        ));
                        ?>
                    </li>
                <?php elseif ('Post' === $notification->log->model): ?>
                    <?php
                    $post = Post::model()->findByPk($notification->log->model_id);

                    if (!$post) {
                        $notification->viewed = true;
                        $notification->save();

                        continue;
                    }
                    ?>
                    <li>
                        <?php
                        $url = $this->createUrl('notification', array('id' => $notification->id));

                        if ($post->feed->region) {
                            $feedName = $post->feed->region->name;
                            $feedUrl = $this->createUrl('/resource/list', array('region' => $post->feed->region->id));
                        } else if ($post->feed->location) {
                            $feedName = $post->feed->location->name;
                            $feedUrl = $this->createUrl('/location/view', array('id' => $post->feed->location->id));
                        } else if ($post->feed->project) {
                            $feedName = $post->feed->project->name;
                            $feedUrl = $this->createUrl('/location/view', array('id' => $post->feed->project->location->id, '#' => $post->feed->project->slug));
                        } else {
                            Yii::log('Unknown post feed parent. $post = ' . $post->id, 'error', 'app.user._notifications');
                            $notification->viewed = true;
                            $notification->save();

                            continue;
                        }

                        $this->renderPartial('//_post', array(
                            'post' => $post,
                            'url' => $url,
                            'feedName' => $feedName,
                            'feedUrl' => $feedUrl,
                            'showComments' => false
                        ));
                        ?>
                    </li>
                <?php elseif ('Comment' === $notification->log->model): ?>
                    <?php
                    $comment = Comment::model()->findByPk($notification->log->model_id);

                    if (!$comment) {
                        $notification->viewed = true;
                        $notification->save();

                        continue;
                    }
                    ?>
                    <li>
                        <?php
                        $this->renderPartial('//_comment', array(
                            'comment' => $comment,
                            'url' => $this->createUrl('notification', array('id' => $notification->id))
                        ));
                        ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <?php
        if ($onlyUnviewed) {
            if (0 !== count($notifications)) {
                ?>
                <div class="clear-all">
                    <?php
                    echo CHtml::link(
                        Yii::t('app', 'Clear All'),
                        array('clearNotifications')
                    );
                    ?>
                </div>
                <?php
            }
        }
    } else {
        ?>
        <div class="empty-message">
            <?php
            if ($onlyUnviewed) {
                echo CHtml::encode(Yii::t('app', 'You have no new notifications.'));
            } else {
                echo CHtml::encode(Yii::t('app', 'You have no notifications.'));
            }
            ?>
            <?php
            if ($onlyUnviewed) {
                ?>
                <br><br>
                <?php
                echo CHtml::link(
                    Yii::t('app', 'Show All Notifications'),
                    array('notifications'),
                    array('class' => 'show-all')
                );
            }
            ?>
        </div>
        <?php
    }
$this->endWidget();
