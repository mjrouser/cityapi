<?
/*
    PostController.php

    Copyright Stefan Fisk 2012.
*/

class PostController extends Controller {
    public $layout = '//layouts/script';

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
                'actions' => array('create', 'createText', 'createImage', 'createResource', 'delete'),
                'users' => array('@'),
            ),

            array(
                'deny',
            ),
        );
    }

    public function actionCreate($feed) {
        $this->render('create', array('feed' => $feed));
    }

    public function actionCreateText($feed) {
        $feed = $this->loadFeed($feed);

        $post = new Post(Post::TEXT);
        $post->type = Post::TEXT;
        $post->user_id = Yii::app()->user->id;
        $post->feed_id = $feed->id;
        if ($feed->project) {
            $post->status = $feed->project->status;
        }

        $this->performAjaxValidation($post);

        if(isset($_POST['Post'])) {
            $post->attributes = $_POST['Post'];

            if ($_POST['Post']['urgent']) {
                $post->status = Project::URGENT;
            }

            if($post->save()) {
                $this->renderText('window.parent.location = "' . $this->createUrl('view', array('id' => $post->id)) . '";');
                Yii::app()->end();
            }
        }

        $this->render('create-text', array(
            'model' => $post,
        ));
    }

    public function actionCreateImage($feed) {
        $feed = $this->loadFeed($feed);

        $post = new Post(Post::IMAGE);
        $post->type = Post::IMAGE;
        $post->user_id = Yii::app()->user->id;
        $post->feed_id = $feed->id;
        if ($feed->project) {
            $post->status = $feed->project->status;
        }

        $image = new Image;

        if(!isset($_POST['Post']) || !isset($_POST['Image'])) {
            $this->render('create-image', array(
                'post' => $post,
                'image' => $image
            ));

            Yii::app()->end();
        }

        $post->attributes = $_POST['Post'];
        $image->attributes = $_POST['Image'];
        $image->file = CUploadedFile::getInstance($image, 'file');

        if (!$image->save()) {
            $this->render('create-image', array(
                'post' => $post,
                'image' => $image
            ));

            Yii::app()->end();
        }

        $post->attributes = $_POST['Post'];
        $post->image_id = $image->id;

        if (!$post->save()) {
            $image->delete();

            $this->render('create-image', array(
                'post' => $post,
                'image' => $image
            ));

            Yii::app()->end();
        }

        $this->renderText('window.parent.location = "' . $this->createUrl('view', array('id' => $post->id)) . '";');
    }

    public function actionCreateResource($feed) {
        $feed = $this->loadFeed($feed);

        $post = new Post(Post::RESOURCE);
        $post->type = Post::RESOURCE;
        $post->user_id = Yii::app()->user->id;
        $post->feed_id = $feed->id;
        $post->resource_type = POST::SKILL;
        if ($feed->project) {
            $post->status = $feed->project->status;
        }

        $this->performAjaxValidation($post);

        if(isset($_POST['Post'])) {
            $post->attributes = $_POST['Post'];

            if($post->save()) {
                $this->renderText('window.parent.location = "' . $this->createUrl('view', array('id' => $post->id)) . '";');
                Yii::app()->end();
            }
        }

        $this->render('create-resource', array(
            'model' => $post,
        ));
    }

    protected function performAjaxValidation($post) {
        if(isset($_POST['ajax'])) {
            echo CActiveForm::validate($post);
            Yii::app()->end();
        }
    }

    public function actionView($id) {
        $post = $this->loadPost($id);

        if (null !== $post->feed->location) {
            $this->redirect(array('location/view', 'id' => $post->feed->location->id, '#' => 'post-' . $post->id));
        } else if (null !== $post->feed->project) {
            $this->redirect(array('location/view', 'id' => $post->feed->project->location->id, '#' => 'post-' . $post->id));
        }

        throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionDelete($id) {
        $post = $this->loadPost($id);

        if (!Yii::app()->user->checkAccess('deletePost', array('post' => $post))) {
            throw new CHttpException(403);
        }

        if(!Yii::app()->request->isPostRequest) {
            $this->render('//confirmAction', array(
                'title' => Yii::t('app', 'Do you really want to delete this post?'),
                'message' => Yii::t('app', 'This action cannot be undone.'),
                'action' => Yii::t('app', 'Delete')
            ));

            Yii::app()->end();
        }

        $post->delete();

        $this->renderText('window.parent.location.reload();');
    }

    public function loadPost($id) {
        $post = Post::model()->findByPk($id);

        if(null === $post) {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }

        return $post;
    }

    public function loadFeed($id) {
        $post = Feed::model()->findByPk($id);

        if(null === $post) {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }

        return $post;
    }
}
