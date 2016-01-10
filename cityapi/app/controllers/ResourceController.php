<?php
/*
    ResourceController.php

    Copyright Stefan Fisk 2012.
*/

class ResourceController extends Controller {
    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array('list', 'view', 'viewComment'),
                'users' => array('*'),
            ),

            array(
                'allow',
                'actions' => array('create', 'createComment'),
                'users' => array('@'),
            ),

            array(
                'deny',
            ),
        );
    }

    public function actionList($region, $type = null) {
        $region = $this->loadRegion($region);

        $this->currentRegion = $region;

        $this->render('list', array(
            'region' => $region,
            'type' => $type
        ));
    }

    public function actionCreate($region) {
        $region = $this->loadRegion($region);

        $post = new Post(Post::RESOURCE);
        $post->type = Post::RESOURCE;
        $post->user_id = Yii::app()->user->id;
        $post->feed_id = $region->feed_id;
        $post->resource_type = POST::SKILL;

        if(!isset($_POST['Post'])) {
            $this->render('create', array('model' => $post));
            Yii::app()->end();
        }

        if(isset($_POST['ajax'])) {
            echo CActiveForm::validate($post);
            Yii::app()->end();
        }

        $post->attributes = $_POST['Post'];

        if(!$post->save()) {
            $this->render('create', array('model' => $post));
            Yii::app()->end();
        }

        $this->layout = '//layouts/script';
        $this->renderText('window.parent.location = "' . $this->createUrl('view', array('id' => $post->id)) . '";');
    }

    public function actionView($id) {
        $post = $this->loadPost($id);

        $this->redirect(array('list', '#' => 'post-' . $post->id));
    }

    public function actionCreateComment($post = null) {
        $comment = new Comment;
        $comment->user_id = Yii::app()->user->id;
        $comment->post_id = $post;

        if(isset($_POST['ajax'])) {
            echo CActiveForm::validate($comment);
            Yii::app()->end();
        }

        if(isset($_POST['Comment'])) {
            $comment->attributes = $_POST['Comment'];

            if($comment->save()) {
                $this->layout = '//layouts/script';
                $this->renderText('window.parent.location = "' . $this->createUrl('viewComment', array('id' => $comment->id, 'region' => $this->currentRegion->id)) . '";');
                Yii::app()->end();
            }
        }

        $this->render('createComment', array(
            'model' => $comment,
        ));
    }

    public function actionViewComment($region, $id) {
        $this->redirect(array('list', 'id' => $region, '#' => 'comment-' . $id));
    }

    public function loadRegion($id) {
        $model = Region::model()->findByPk($id);
        
        if(null === $model) {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }

        return $model;
    }

    public function loadPost($id) {
        $model = Post::model()->findByPk($id);

        if(null === $model) {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }

        return $model;
    }
}
