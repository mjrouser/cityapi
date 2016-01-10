<?php
/*
    Location.php

    Copyright Stefan Fisk 2012.
*/

class Location extends CActiveRecord {
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'location';
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
                'creation_time, region_id, longitude, latitude, name, slug, feed_id',
                'required'
            ),

            array(
                'created_by_user_id, region_id, image_id, feed_id',
                'numerical',
                'integerOnly' => true
            ),

            array(
                'longitude, latitude',
                'numerical'
            ),

            array(
                'name, slug',
                'length',
                'max' => 128
            ),

            array(
                'longitude, latitude, name, description',
                'safe',
            ),

            array(
                'id, region_id, longitude, latitude, name, slug, description, image_id, feed_id',
                'safe',
                'on' => 'search'
            ),
        );
    }

    public function relations() {
        return array(
            'createdByUser' => array(self::BELONGS_TO, 'User', 'created_by_user_id'),
            'region' => array(self::BELONGS_TO, 'Region', 'region_id'),
            'image' => array(self::BELONGS_TO, 'Image', 'image_id'),
            'feed' => array(self::BELONGS_TO, 'Feed', 'feed_id'),
            'projects' => array(
                self::HAS_MANY,
                'Project',
                'location_id',
                'with' => 'feed.posts',
                'order' => '-posts.creation_time'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'creation_time' => 'Creation Time',
            'created_by_user_id' => 'Created By User',
            'region_id' => 'Region',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description',
            'image_id' => 'Image',
            'feed_id' => 'Feed',
        );
    }

    public function behaviors() {
        return array(
            'ActiveRecordLogableBehavior' => 'application.components.ContributionLogBehavior',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('creation_time',$this->creation_time,true);
        $criteria->compare('created_by_user_id',$this->created_by_user_id);
        $criteria->compare('region_id',$this->region_id);
        $criteria->compare('longitude',$this->longitude);
        $criteria->compare('latitude',$this->latitude);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('slug',$this->slug,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('image_id',$this->image_id);
        $criteria->compare('feed_id',$this->feed_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    protected function afterDelete() {
        parent::afterDelete();

        if ($this->image) {
            $this->image->delete();
        }
    }
}