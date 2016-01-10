<?php
/*
    FeedFollower.php

    Copyright Stefan Fisk 2012.
*/

class FeedFollower extends CActiveRecord {
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'feed_follower';
    }

    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'feed' => array(self::BELONGS_TO, 'Feed', 'feed_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'feed_id' => 'Feed',
        );
    }

    public function search() {
        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('feed_id', $this->feed);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
