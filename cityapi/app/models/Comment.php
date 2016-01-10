<?php
/*
    Comment.php

    Copyright Stefan Fisk 2012.
*/

class Comment extends CActiveRecord {
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'comment';
    }

    public function rules() {
        return array(
            array(
                'creation_time',
                'default',
                'value' => new CDbExpression('UTC_TIMESTAMP()'),
                'setOnEmpty' => false,
                'on' => 'insert'
            ),

            array(
                'user_id, post_id',
                'required'
            ),
            array(
                'user_id, post_id',
                'numerical',
                'integerOnly' => true
            ),

            array(
                'text',
                'required',
                'message' => Yii::t('app', 'Your comment cannot be empty.'),
            ),
            array(
                'text',
                'length',
                'max' => 500,
                'tooLong' => Yii::t('app', 'Your comment cannot be longer than {max} characters.'),
            ),

            array(
                'text',
                'safe',
            ),
        );
    }

    public function relations() {
        return array(
            'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'creation_time' => 'Creation Time',
            'post_id' => 'Post',
            'user_id' => 'User',
            'text' => 'Text'
        );
    }

    public function behaviors() {
        return array(
            'ActiveRecordLogableBehavior' => 'application.components.ContributionLogBehavior',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('creation_time', $this->creation_time,true);
        $criteria->compare('post_id', $this->post_id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('text', $this->text);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}
