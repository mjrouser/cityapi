<?php
/*
    UserController.php

    Copyright Stefan Fisk 2012.
*/

class UserController extends Controller {
    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array('view'),
                'users' => array('*'),
            ),

            array(
                'allow',
                'actions' => array('edit', 'notification', 'notifications', 'clearNotifications'),
                'users' => array('@'),
            ),

            array(
                'deny',
            ),
        );
    }

    public function actionView($id) {
        $this->render('view', array(
            'user' => $this->loadUser($id),
        ));
    }

    public function actionEdit($id) {
        $user = $this->loadUser($id);
        $image = new Image;

        if(!isset($_POST['User']) || !isset($_POST['Image'])) {
            $this->render('edit', array(
                'user' => $user,
                'image' => $image
            ));

            Yii::app()->end();
        }

        if(isset($_POST['ajax'])) {
            echo CActiveForm::validate($user);
            Yii::app()->end();
        }

        $user->attributes = $_POST['User'];

        if (isset($_POST['use-facebook-picture'])) {
            $user->setFacebookPicture();
            $this->layout = '//layouts/script';
            $this->renderText('window.parent.location = "' . $this->createUrl('view', array('id' => $user->id)) . '";');
            Yii::app()->end();
        }


        $image->attributes = $_POST['Image'];
        $image->file = CUploadedFile::getInstance($image, 'file');

        if ($image->file) {
            if (!$image->save()) {
                $this->render('edit', array(
                    'project' => $project,
                    'image' => $image,
                ));
                Yii::app()->end();
            }

            $user->image_id = $image->id;
        }

        if (!$user->save()) {
            $image->delete();

            $this->render('edit', array(
                'user' => $user,
                'image' => $image
            ));

            Yii::app()->end();
        }

        $this->layout = '//layouts/script';
        $this->renderText('window.parent.location = "' . $this->createUrl('view', array('id' => $user->id)) . '";');
    }

    public function actionNotification($id) {
        $notification = $this->loadNotification($id);

        if (Yii::app()->user->id !== $notification->user_id) {
            throw new CHttpException(403);
        }

        $notification->viewed = true;
        $notification->save();

        if ('Message' === $notification->log->model) {
            $message = $this->loadMessage($notification->log->model_id);

            $this->redirect(array('/message/thread', 'recipient' => $message->sender->id));
        } else if ('Post' === $notification->log->model) {
            $post = $this->loadPost($notification->log->model_id);

            if ($post->feed->region) {
                $this->redirect(array('/resource/list', 'id' => $post->feed->region->id, '#' => 'post-' . $post->id));
            } else if ($post->feed->location) {
                $this->redirect(array('/location/view', 'id' => $post->feed->location->id, '#' => 'post-' . $post->id));
            } else if ($post->feed->project) {
                $this->redirect(array('/location/view', 'id' => $post->feed->project->location->id, '#' => 'post-' . $post->id));
            } else {
                throw new CHttpException(500);
            }
        } else if ('Comment' === $notification->log->model) {
            $comment = $this->loadComment($notification->log->model_id);

            if ($comment->post->feed->region) {
                $this->redirect(array('/resource/list', 'id' => $comment->post->feed->region->id, '#' => 'comment-' . $comment->id));
            } else if ($comment->post->feed->location) {
                $this->redirect(array('/location/view', 'id' => $comment->post->feed->location->id, '#' => 'comment-' . $comment->id));
            } else if ($comment->post->feed->project) {
                $this->redirect(array('/location/view', 'id' => $comment->post->feed->project->location->id, '#' => 'comment-' . $comment->id));
            } else {
                throw new CHttpException(500);
            }
        } else {
            throw new CHttpException(500);
        }
    }
    public function actionNotifications() {
        $this->render('notifications', array(
            'userId' => Yii::app()->user->id,
        ));
    }

    public function actionClearNotifications() {
        $notifications = Notification::model()->findAllUnviewed();

        foreach($notifications as $notification) {
            $notification->viewed = true;
            $notification->save();
        }

        $this->redirect(array('view', 'id' => Yii::app()->user->id, '#' => 'notifications'));
    }

    public function loadUser($id) {
        $model = User::model()->findByPk($id);

        if ($model === null) {
            throw new CHttpException(404,'The requested page does not exist.');
        }

        return $model;
    }

    public function loadNotification($id) {
        $model = Notification::model()->findByPk($id);

        if ($model === null) {
            throw new CHttpException(404,'The requested page does not exist.');
        }

        return $model;
    }

    public function loadMessage($id) {
        $model = Message::model()->findByPk($id);

        if ($model === null) {
            throw new CHttpException(404,'The requested page does not exist.');
        }

        return $model;
    }

    public function loadPost($id) {
        $model = Post::model()->findByPk($id);

        if ($model === null) {
            throw new CHttpException(404,'The requested page does not exist.');
        }

        return $model;
    }

    public function loadComment($id) {
        $model = Comment::model()->findByPk($id);

        if ($model === null) {
            throw new CHttpException(404,'The requested page does not exist.');
        }

        return $model;
    }
}
