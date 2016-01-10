<?
/*
    FollowButtonWidget.php

    Copyright Stefan Fisk 2012.
*/

class FollowButtonWidget extends CWidget {
    public $feed;

    public function init() {
        if (Yii::app()->user->isGuest) {
            return;
        }

        Yii::app()->clientScript->registerScriptFile('/scripts/feed.js');

        $isFollowing = $this->feed->isFollowing(Yii::app()->user->id);

        echo CHtml::button(Yii::t('app', 'Follow'), array(
            'class' => 'follow',
            'onclick' => new CJavaScriptExpression('app.feed.follow.call(this, ' . $this->feed->id . ')'),
            'style' => $isFollowing ? 'display: none;' : '',
        ));
        echo CHtml::button(Yii::t('app', 'Unfollow'), array(
            'class' => 'unfollow',
            'onclick' => new CJavaScriptExpression('app.feed.unfollow.call(this, ' . $this->feed->id . ')'),
            'style' => $isFollowing ? '' : 'display: none;',
        ));
    }
}
