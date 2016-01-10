<?php
/*
    ProjectChamp.php

    Copyright Stefan Fisk 2012.
*/

class ProjectChamp extends CActiveRecord {
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'project_champ';
    }

    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'project_id' => 'Project',
        );
    }

    public function search() {
        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('project_id', $this->project_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
