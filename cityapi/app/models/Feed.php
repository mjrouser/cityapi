<?php
/*
    Feed.php

    Copyright Stefan Fisk 2012.
*/

class Feed extends CActiveRecord {
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'feed';
    }

    public function rules() {
        return array(
            array(
                'id',
                'safe',
                'on' => 'search'
            ),
        );
    }

    public function relations() {
        return array(
            'location' => array(self::HAS_ONE, 'Location', 'feed_id'),
            'project' => array(self::HAS_ONE, 'Project', 'feed_id'),
            'region' => array(self::HAS_ONE, 'Region', 'feed_id'),

            'posts' => array(
                self::HAS_MANY,
                'Post',
                'feed_id',
                'order' => '-posts.creation_time'
            ),

            'followers' => array(self::MANY_MANY, 'User', 'feed_follower(feed_id, user_id)'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
        );
    }

    public function behaviors(){
        return array(
            'CAdvancedArBehavior' => array('class' => 'application.components.CAdvancedArBehavior'),
            'ActiveRecordLogableBehavior' => 'application.components.ContributionLogBehavior',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function isFollowing($userId) {
        return FeedFollower::model()->exists('user_id = :user_id && feed_id = :feed_id', array(':user_id' => $userId, ':feed_id' => $this->id));
    }
}
