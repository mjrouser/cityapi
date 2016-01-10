<?
/*
    CommentController.php

    Copyright Stefan Fisk 2012.
*/

class CommentController extends Controller {
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
                'actions' => array('create', 'delete'),
                'users' => array('@'),
            ),

            array(
                'deny',
            ),
        );
    }

    public function actionCreate($post = null) {
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
                $this->renderText('window.parent.location = "' . $this->createUrl('view', array('id' => $comment->id)) . '";');
                Yii::app()->end();
            }
        }

        $this->render('create', array(
            'model' => $comment,
        ));
    }

    public function actionView($id) {
        $comment = $this->loadComment($id);

        if (null !== $comment->post->feed->location) {
            $this->redirect(array('location/view', 'id' => $comment->post->feed->location->id, '#' => 'comment-' . $comment->id));
        } else if (null !== $comment->post->feed->project) {
            $this->redirect(array('location/view', 'id' => $comment->post->feed->project->location->id, '#' => 'comment-' . $comment->id));
        }

        throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionDelete($id) {
        $comment = $this->loadComment($id);

        if (!Yii::app()->user->checkAccess('deleteComment', array('comment' => $comment))) {
            throw new CHttpException(403);
        }

        if(!Yii::app()->request->isPostRequest) {
            $this->render('//confirmAction', array(
                'title' => Yii::t('app', 'Do you really want to delete this comment?'),
                'message' => Yii::t('app', 'This action cannot be undone.'),
                'action' => Yii::t('app', 'Delete')
            ));

            Yii::app()->end();
        }

        $comment->delete();

        $this->renderText('window.parent.location.reload();');
    }

    public function loadComment($id) {
        $comment = Comment::model()->findByPk($id);

        if(null === $comment) {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }

        return $comment;
    }
}
