<?php
/*
    Notification.php

    Copyright Stefan Fisk 2012.
*/

class Notification extends CActiveRecord {
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'notification';
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'record_operation_log_id' => 'Record Operation Log',
            'viewed' => 'Viewed',
        );
    }

    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'log' => array(self::BELONGS_TO, 'RecordOperationLog', 'record_operation_log_id'),
        );
    }

    public function search() {
        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('time', $this->time);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('record_operation_log_id', $this->record_operation_log_id);
        $criteria->compare('viewed', $this->viewed);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function countOfUnviewed($userId = null) {
        if (null === $userId) {
            $userId = Yii::app()->user->id;
        }

        assert(null !== $userId);

        return $this->count(
            'user_id = :user_id AND FALSE = viewed',
            array(':user_id' => $userId)
        );
    }

    public function findAllUnviewed($userId = null) {
        if (null === $userId) {
            $userId = Yii::app()->user->id;
        }

        assert(null !== $userId);

        return $this->findAll(
            'user_id = :user_id AND FALSE = viewed',
            array(':user_id' => $userId)
        );
    }
}
