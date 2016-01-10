<?
/*
    MessageController.php

    Copyright Stefan Fisk 2012.
*/

class MessageController extends Controller {
    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array('create', 'list', 'thread'),
                'users' => array('@'),
            ),

            array(
                'deny',
            ),
        );
    }

    public function actionList() {
        $this->render('list');
    }

    public function actionCreate($recipient) {
        $recipient = $this->loadUser($recipient);

        $message = new Message;
        $message->sender_id = Yii::app()->user->id;
        $message->recipient_id = $recipient->id;

        if(!isset($_POST['Message'])) {
            $this->render('create', array('message' => $message));
            Yii::app()->end();
        }

        $message->attributes = $_POST['Message'];

        if(isset($_POST['ajax'])) {
            echo CActiveForm::validate($message);
            Yii::app()->end();
        }

        if(!$message->save()) {
            $this->render('create', array('message' => $message));
            Yii::app()->end();
        }

        $this->redirect(array('thread', 'recipient' => $message->recipient->id, '#' => 'message-' . $message->id));
    }

    public function actionThread($recipient) {
        $this->render('thread', array('recipient' => $this->loadUser($recipient)));
    }

    public function loadUser($id) {
        $user = User::model()->findByPk($id);

        if(null === $user) {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }

        return $user;
    }
}
