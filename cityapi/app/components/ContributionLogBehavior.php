<?php
/*
    ContributionLogBehavior.php

    Copyright Stefan Fisk 2012.
*/

// The supported notifications are:
// - Creating Messages
// - Creating Posts
// - Creating Comments

class ContributionLogBehavior extends CActiveRecordBehavior {
    public function afterSave($event) {
        $log = new RecordOperationLog();

        $log->user_id = Yii::app()->user->id;

        $log->model = get_class($this->Owner);
        $log->model_id = $this->Owner->getPrimaryKey();

        $log->action = $this->Owner->isNewRecord ? RecordOperationLog::INSERT : RecordOperationLog::UPDATE;

        $log->save();

        if (RecordOperationLog::INSERT !== $log->action) {
            return;
        }

        $notificationRecipientIds = array();

        if ($this->Owner instanceof Message) {
            $notificationRecipientIds[] = $this->Owner->recipient_id;
        } else if ($this->Owner instanceof Post) {
            foreach ($this->Owner->feed->followers as $follower) {
                $notificationRecipientIds[] = $follower->id;
            }
        } else if ($this->Owner instanceof Comment) {
            $notificationRecipientIds[] = $this->Owner->post->user_id;

            foreach ($this->Owner->post->comments as $comment) {
                $notificationRecipientIds[] = $comment->user_id;
            }

            foreach ($this->Owner->post->feed->followers as $follower) {
                $notificationRecipientIds[] = $follower->id;
            }
        }

        $notificationRecipientIds = array_diff(array_unique($notificationRecipientIds), array($log->user_id));

        foreach ($notificationRecipientIds as $recipientId) {
            $notification = new Notification();
            $notification->record_operation_log_id = $log->id;
            $notification->user_id = $recipientId;
            $notification->save();
        }
    }

    public function afterDelete($event) {
        $log = new RecordOperationLog();
        $log->model = get_class($this->Owner);
        $log->model_id = $this->Owner->getPrimaryKey();

        $log->action = RecordOperationLog::DELETE;

        $log->save();
    }
}
