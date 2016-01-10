<?php
/*
    Post.php

    Copyright Stefan Fisk 2012.
*/

class Post extends CActiveRecord {
    const TEXT = 'text';
    const IMAGE = 'image';
    const RESOURCE = 'resource';

    const TOOL = 'tool';
    const MATERIAL = 'material';
    const SKILL = 'skill';

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'post';
    }

    public function rules() {
        return array(
            array(
                'user_id, feed_id, type',
                'required'
            ),
            array(
                'user_id, feed_id',
                'numerical',
                'integerOnly' => true
            ),

            // TEXT

            array(
                'type',
                'default',
                'value' => self::TEXT,
                'setOnEmpty' => true,
                'on' => self::TEXT
            ),

            array(
                'type',
                'compare',
                'operator' => '==',
                'compareValue' => self::TEXT,
                'on' => self::TEXT,
            ),

            array(
                'urgent',
                'default',
                'value' => 0,
                'setOnEmpty' => true,
                'on' => self::TEXT
            ),

            array(
                'text',
                'required',
                'message' => Yii::t('app', 'Your post cannot be empty.'),
                'on' => self::TEXT
            ),
            array(
                'text',
                'length',
                'max' => 500,
                'tooLong' => Yii::t('app', 'Your post cannot be longer than {max} characters.'),
                'on' => self::TEXT
            ),

            array(
                'text',
                'safe',
                'on' => self::TEXT
            ),

            // IMAGE

            array(
                'type',
                'default',
                'value' => self::IMAGE,
                'setOnEmpty' => true,
                'on' => self::IMAGE
            ),

            array(
                'type',
                'compare',
                'operator' => '==',
                'compareValue' => self::IMAGE,
                'on' => self::IMAGE,
            ),

            array(
                'image_id',
                'required',
                'on' => self::IMAGE,
            ),

            // RESOURCE

            array(
                'type',
                'default',
                'value' => self::RESOURCE,
                'setOnEmpty' => true,
                'on' => self::RESOURCE
            ),

            array(
                'type',
                'compare',
                'operator' => '==',
                'compareValue' => self::RESOURCE,
                'on' => self::RESOURCE,
            ),

            array(
                'resource_type',
                'required',
                'on' => self::RESOURCE,
            ),

            array(
                'text',
                'required',
                'message' => Yii::t('app', 'Your description cannot be empty.'),
                'on' => self::RESOURCE,
            ),
            array(
                'text',
                'length',
                'max' => 140,
                'tooLong' => Yii::t('app', 'Your description cannot be longer than {max} characters.'),
                'on' => self::RESOURCE,
            ),

            array(
                'text',
                'safe',
                'on' => self::RESOURCE,
            ),
        );
    }

    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'feed' => array(self::BELONGS_TO, 'Feed', 'feed_id'),
            'image' => array(self::BELONGS_TO, 'Image', 'image_id'),

            'comments' => array(
                self::HAS_MANY,
                'Comment',
                'post_id',
                'order' => 'creation_time'
            ),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'creation_time' => Yii::t('app', 'Creation Time'),
            'user_id' => Yii::t('app', 'User'),
            'feed_id' => Yii::t('app', 'Feed'),
            'text' => Yii::t('app', 'Text'),
            'urgent' => Yii::t('app', 'Urgent'),
            'resource_type' => Yii::t('app', 'Resource Type'),
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
        $criteria->compare('type', $this->type);
        $criteria->compare('creation_time', $this->creation_time);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('feed_id', $this->feed_id);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('urgent', $this->urgent);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    protected function beforeSave() {
        if ($this->isNewRecord && null === $this->creation_time) {
            $this->creation_time = new CDbExpression('UTC_TIMESTAMP()');
        }

        return parent::beforeSave();
    }

    protected function afterDelete() {
        parent::afterDelete();

        if ($this->image) {
            $this->image->delete();
        }
    }
}
