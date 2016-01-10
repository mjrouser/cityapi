<?php
/*
    User.php

    Copyright Stefan Fisk 2012.
*/

class User extends CActiveRecord {
    const DISABLED = 0;
    const ACTIVE = 1;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'user';
    }

    public function rules() {
        return array(
            array(
                'registration_time',
                'default',
                'value' => new CDbExpression('UTC_TIMESTAMP()'),
                'setOnEmpty' => false,
                'on' => 'insert'
            ),

            array(
                'first_name, last_name, registration_time, status',
                'required'
            ),

            array(
                'facebook_profile_id, status, image_id',
                'numerical',
                'integerOnly' => true
            ),

            array(
                'first_name, last_name, email_address, password_hash',
                'length',
                'max' => 128
            ),

            array('first_name, last_name, email_address, password_hash', 'EmptyNullValidator'),

            array(
                'description',
                'length',
                'max' => 500,
                'tooLong' => Yii::t('app', 'Your presentation cannot be longer than {max} characters.'),
            ),

            array(
                'description',
                'safe'
            ),

            array(
                'id, first_name, last_name, email_address, password_hash, facebook_profile_id, registration_time, status, is_admin, presentation, image_id',
                'safe',
                'on' => 'search'
            ),
        );
    }

    public function relations() {
        return array(
            'comments' => array(self::HAS_MANY, 'Comment', 'user_id'),
            'posts' => array(self::HAS_MANY, 'Post', 'user_id'),
            'projects' => array(self::HAS_MANY, 'Project', 'created_by_user_id'),
            'image' => array(self::BELONGS_TO, 'Image', 'image_id'),
            'champedProjects' => array(self::MANY_MANY, 'Project', 'project_champ(user_id, project_id)'),
            'followedFeeds' => array(self::MANY_MANY, 'Feed', 'feed_follower(user_id, feed_id)'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email_address' => 'Email Address',
            'password_hash' => 'Password Hash',
            'password_reset_key' => 'Password Reset Key',
            'facebook_profile_id' => 'Facebook Profile',
            'registration_time' => 'Registration Time',
            'status' => 'Status',
            'is_admin' => 'Is Administrator',
            'language' => 'Language',
            'description' => 'description',
            'image_id' => 'Image',
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
        $criteria->compare('first_name', $this->first_name,true);
        $criteria->compare('last_name', $this->last_name,true);
        $criteria->compare('email_address', $this->email_address,true);
        $criteria->compare('password_hash', $this->password_hash,true);
        $criteria->compare('facebook_profile_id', $this->facebook_profile_id);
        $criteria->compare('registration_time', $this->registration_time,true);
        $criteria->compare('status', $this->status);
        $criteria->compare('is_admin', $this->is_admin);
        $criteria->compare('language', $this->language);
        $criteria->compare('description', $this->description,true);
        $criteria->compare('image_id', $this->image_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    protected function afterFind() {
        parent::afterFind();

        if (self::DISABLED == $this->status) {
            $this->status = self::DISABLED;
        } else if (self::ACTIVE == $this->status) {
            $this->status = self::ACTIVE;
        } else {
            $this->status = null;
        }
    }

    public function getfull_name() {
        return $this->first_name . ' ' . $this->last_name;
    }

    protected function afterDelete() {
        parent::afterDelete();

        if ($this->image) {
            $this->image->delete();
        }
    }

    public function setFacebookPicture() {
        if (!$this->facebook_profile_id) {
            return false;
        }

        try {
            $headers = get_headers('http://graph.facebook.com/' . $this->facebook_profile_id . '/picture?type=large', 1);
            if (!isset($headers['Location'])) {
                error_log('no Location: header');
                error_log(print_r($headers, true));
                return false;
            }

            $image = new Image();

            $image->fileUrl = $headers['Location'];

            if (!@$image->save()) {
                Yii::log('Image::save() failed. $errors = ' . print_r($image->getErrors(), true), 'error', 'app.user.facebookPicture');

                return false;
            }

            $this->image_id = $image->id;

            if (!$this->save()) {
                Yii::log('User::save() failed. $errors = ' . print_r($this->getErrors(), true), 'error', 'app.user.facebookPicture');

                return false;
            }
        }
        catch (Exception $exception) {
            error_log(print_r($exception, true));
            return false;
        }

        return true;
    }
}
