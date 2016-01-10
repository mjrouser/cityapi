<?php
/*
    Project.php

    Copyright Stefan Fisk 2012.
*/

class Project extends CActiveRecord {
    const STARTED = 'started';
    const UNDER_CONSTRUCTION = 'under construction';
    const FINISHED = 'finished';
    const ONGOING = 'ongoing';
    const LOW_ACTIVITY = 'low activity';
    const NO_ACTIVITY = 'no activity';
    const ABANDONED = 'abandoned';
    const URGENT = 'urgent';

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'project';
    }

    public function rules() {
        return array(
            array(
                'name',
                'required',
                'message' => Yii::t('app', 'The title cannot be empty.'),
            ),
            array(
                'name',
                'length',
                'max' => 128
            ),

            array(
                'name',
                'uniqueSlug',
                'message' => Yii::t('app', 'The already exists a project with this title at this location.'),
            ),

            array(
                'description',
                'required',
                'message' => Yii::t('app', 'The description cannot be empty.'),
            ),
            array(
                'description',
                'length',
                'max' => 280
            ),

            array(
                'champs',
                'RelationCountValidator',
                'allowEmpty' => false,
                'min' => 1,
                'tooFew' => Yii::t('app', 'There must be at least one champ.'),
            ),

            array(
                'name, description, status, champs',
                'safe',
            ),

            array(
                'id, creation_time, created_by_user_id, location_id, name, slug, description, image_id, feed_id',
                'safe',
                'on' => 'search'
            ),
        );
    }

    public function uniqueSlug($attribute, $params) {
        if ($this->isNewRecord) {
            if (Project::model()->exists('location_id = :location_id AND slug = :slug', array(':location_id' => $this->location_id, ':slug' => $this->slug))) {
                $this->addError($attribute, $params['message']);
            }
        } else {
            if (Project::model()->exists('id != :project_id AND location_id = :location_id AND slug = :slug', array(':project_id' => $this->id, ':location_id' => $this->location_id, ':slug' => $this->slug))) {
                $this->addError($attribute, $params['message']);
            }
        }
    }

    public function relations() {
        return array(
            'createdByUser' => array(self::BELONGS_TO, 'User', 'created_by_user_id'),
            'location' => array(self::BELONGS_TO, 'Location', 'location_id'),
            'image' => array(self::BELONGS_TO, 'Image', 'image_id'),
            'feed' => array(self::BELONGS_TO, 'Feed', 'feed_id'),
            'champs' => array(self::MANY_MANY, 'User', 'project_champ(project_id, user_id)'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'creation_time' => 'Creation Time',
            'created_by_user_id' => 'Created By User',
            'location_id' => 'Location',
            'name' => 'name',
            'slug' => 'slug',
            'description' => 'Description',
            'image_id' => 'Image',
            'feed_id' => 'Feed',
        );
    }

    public function search() {
        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('creation_time', $this->creation_time, true);
        $criteria->compare('created_by_user_id', $this->created_by_user_id);
        $criteria->compare('location_id', $this->location_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('slug', $this->slug, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('image_id', $this->image_id);
        $criteria->compare('feed_id', $this->feed_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors(){
        return array(
            'CAdvancedArBehavior' => array('class' => 'application.components.CAdvancedArBehavior'),
            'ActiveRecordLogableBehavior' => 'application.components.ContributionLogBehavior',
        );
    }

    protected function beforeSave() {
        if ($this->isNewRecord) {
            if (null === $this->creation_time) {
                $this->creation_time = new CDbExpression('UTC_TIMESTAMP()');
            }

            if (null === $this->status) {
                $this->status = self::STARTED;
            }
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
