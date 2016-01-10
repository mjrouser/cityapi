<?php
/*
    Message.php

    Copyright Stefan Fisk 2012.
*/

class Message extends CActiveRecord {
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'message';
    }

    public function rules() {
        return array(
            array(
                'text',
                'required',
                'message' => Yii::t('app', 'Your message cannot be empty.'),
            ),

            array(
                'text',
                'length',
                'max' => 500,
                'tooLong' => Yii::t('app', 'Your message cannot be longer than {max} characters.'),
            ),

            array(
                'text',
                'safe',
            ),
        );
    }

    public function relations() {
        return array(
            'sender' => array(self::BELONGS_TO, 'User', 'sender_id'),
            'recipient' => array(self::BELONGS_TO, 'User', 'recipient_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'creation_time' => 'Creation Time',
            'sender_id' => 'Sender',
            'recipient_id' => 'Recipient',
            'text' => 'Text',
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
        $criteria->compare('creation_time', $this->creation_time);
        $criteria->compare('sender_id', $this->user_id);
        $criteria->compare('recipient_id', $this->feed_id);
        $criteria->compare('text', $this->text, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function beforeSave() {
        if ($this->isNewRecord && null === $this->creation_time) {
            $this->creation_time = new CDbExpression('UTC_TIMESTAMP()');
        }

        return parent::beforeSave();
    }
}
