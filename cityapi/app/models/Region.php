<?php
/*
    Region.php

    Copyright Stefan Fisk 2012.
*/

class Region extends CActiveRecord {
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'region';
    }

    public function rules() {
        return array(
            array(
                'southwest_longitude, southwest_latitude, northeast_longitude, northeast_latitude, time_zone, name, slug, feed_id, footer',
                'required'
            ),

            array(
                'feed_id',
                'numerical',
                'integerOnly' => true
            ),

            array(
                'southwest_longitude, southwest_latitude, northeast_longitude, northeast_latitude',
                'numerical'
            ),

            array(
                'name, slug, time_zone',
                'length',
                'max' => 128
            ),

            array(
                'id, southwest_longitude, southwest_latitude, northeast_longitude, northeast_latitude, time_zone, name, slug, description, feed_id, footer',
                'safe',
                'on' => 'search'
            ),
        );
    }

    public function relations() {
        return array(
            'locations' => array(self::HAS_MANY, 'Location', 'region_id'),
            'feed' => array(self::BELONGS_TO, 'Feed', 'feed_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'southwest_longitude' => 'Southwest Longitude',
            'southwest_latitude' => 'Southwest Latitude',
            'northeast_longitude' => 'Northeast Longitude',
            'northeast_latitude' => 'Northeast Latitude',
            'time_zone' => 'Time Zone',
            'name' => 'name',
            'slug' => 'slug',
            'description' => 'Description',
            'feed_id' => 'Feed',
            'footer' => 'Footer HTML',
        );
    }

    public function behaviors() {
        return array(
            'ActiveRecordLogableBehavior' => 'application.components.ContributionLogBehavior',
        );
    }

    public function search() {
        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('southwest_longitude', $this->southwest_longitude);
        $criteria->compare('southwest_latitude', $this->southwest_latitude);
        $criteria->compare('northeast_longitude', $this->northeast_longitude);
        $criteria->compare('northeast_latitude', $this->northeast_latitude);
        $criteria->compare('time_zone', $this->time_zone);
        $criteria->compare('name', $this->name,true);
        $criteria->compare('slug', $this->slug,true);
        $criteria->compare('description', $this->description,true);
        $criteria->compare('feed_id', $this->feed_id);
        $criteria->compare('footer', $this->footer);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function getDefault() {
        return $this->find();
    }
}