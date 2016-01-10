<?php
/*
    RecordOperationLog.php

    Copyright Stefan Fisk 2012.
*/

class RecordOperationLog extends CActiveRecord {
    const INSERT = 'insert';
    const UPDATE = 'update';
    const DELETE = 'delete';

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'record_operation_log';
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'time' => 'Time',
            'action' => 'Action',
            'model' => 'Model',
            'model_id' => 'Model Id',
        );
    }


    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    public function search() {
        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('time', $this->time);
        $criteria->compare('action', $this->action);
        $criteria->compare('model', $this->model);
        $criteria->compare('model_id', $this->model_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function beforeSave() {
        if ($this->isNewRecord && !$this->time) {
            $this->time = new CDbExpression('UTC_TIMESTAMP()');
        }

        return parent::beforeSave();
    }
}
