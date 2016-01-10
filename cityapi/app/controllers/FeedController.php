<?
/*
    FeedController.php

    Copyright Stefan Fisk 2012.
*/

class FeedController extends Controller {
    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array('follow', 'unfollow'),
                'users' => array('@'),
            ),

            array(
                'deny',
            ),
        );
    }


    public function actionFollow($id) {
        $feed = $this->loadModel($id);
        
        $followed = FeedFollower::model()->exists(
            'user_id = :user_id AND feed_id = :feed_id',
            array(
                ':user_id' => Yii::app()->user->id,
                ':feed_id' => $feed->id,
            )
        );

        if (!$followed) {
            $follower = new FeedFollower();
            $follower->user_id = Yii::app()->user->id;
            $follower->feed_id = $feed->id;
            $followed = $follower->save();
        }

        echo function_exists('json_encode') ? json_encode($followed) : CJSON::encode($followed);
    }
    public function actionUnfollow($id) {
        $feed = $this->loadModel($id);
        
        $feedFollower = FeedFollower::model()->findByAttributes(array(
            'user_id' => Yii::app()->user->id,
            'feed_id' => $feed->id,
        ));

        echo function_exists('json_encode') ? json_encode(!$feedFollower || $feedFollower->delete()) : CJSON::encode(!$feedFollower || $feedFollower->delete());
    }

    public function loadModel($id) {
        $model = Feed::model()->findByPk($id);

        if(null === $model) {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }

        return $model;
    }
}
