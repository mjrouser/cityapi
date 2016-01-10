<?php
/*
    Image.php

    Copyright Stefan Fisk 2012.
*/

/**
 * This is the model class for table "image".
 *
 * The followings are the available columns in table 'image':
 * @property integer $id
 * @property integer $width
 * @property integer $height
 *
 * The followings are the available model relations:
 * @property Location[] $locations
 * @property Post[] $posts
 * @property Project[] $projects
 * @property Region[] $regions
 * @property User[] $users
 */
class Image extends CActiveRecord
{
    const COVER = 'cover';
    const CONTAIN = 'contain';

    public $file = null;
    public $url = null;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Image the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'image';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array(
                'type, width, height, size',
                'required'
            ),

            array(
                'type, width, height, size',
                'numerical',
                'integerOnly' => true
            ),

            array(
                'id, type, width, height, size',
                'safe',
                'on' => 'search'
            ),

            array(
                'file',
                'file',
                'types' => 'jpg, jpeg, gif, png',
                'allowEmpty' => true,
            ),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'locations' => array(self::HAS_MANY, 'Location', 'image_id'),
            'posts' => array(self::HAS_MANY, 'Post', 'image_id'),
            'projects' => array(self::HAS_MANY, 'Project', 'image_id'),
            'regions' => array(self::HAS_MANY, 'Region', 'image_id'),
            'users' => array(self::HAS_MANY, 'User', 'image_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'width' => 'Width',
            'height' => 'Height',
            'size' => 'Size'
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

        $criteria->compare('id', $this->id);
        $criteria->compare('size', $this->size);
        $criteria->compare('width', $this->width);
        $criteria->compare('height', $this->height);
        $criteria->compare('size', $this->size);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function save($runValidation = true, $attributes = NULL) {
        if ($this->isNewRecord && ($this->file || $this->url)) {
            $transaction = $this->dbConnection->beginTransaction();

            if ($this->file) {
                $filename = $this->file->tempName;
            } else {
                $filename = $this->url;
            }

            $imageInfo = getimagesize($filename);
            $this->size = 0;
            $this->width = 0;
            $this->height = 0;

            if (null === $imageInfo) {
                $this->addError('file', Yii::t('app', 'The image could not be read.'));
                return false;
            }

            $this->type = $imageInfo[2];

            if (!parent::save($runValidation, $attributes)) {
                return false;
            }

            $image = EWideImage::load($filename)->resizeDown(Yii::app()->params['images']['maxWidth'], Yii::app()->params['images']['maxHeight'], 'inside');

            if (IMAGETYPE_JPEG == $this->type) {
                $image->saveToFile($this->path(), Yii::app()->params['images']['defaultCompression']);
            } else {
                $image->saveToFile($this->path());
            }

            if (!file_exists($this->path())) {
                $transaction->rollBack();
                Yii::log('Failed to save the file.', 'error', 'app.models.image.save');
                throw new CHttpException(500);
            }

            $imageInfo = getimagesize($this->path());
            $this->size = filesize($this->path());
            $this->width = $imageInfo[0];
            $this->height = $imageInfo[1];

            if (!parent::save($runValidation, $attributes)) {
                unlink($this->path());
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();

            return true;
        } else {
            parent::save($runValidation, $attributes);
        }
    }

    public function afterDelete() {
        parent::afterDelete();

        $directory = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . Yii::app()->params['images']['basePath'];

        $handler = opendir($directory);

        while ($filename = readdir($handler)) {
            if ($filename == "." || $filename == "..") {
                continue;
            }


            if (!preg_match('/' . $this->id . '(_.*)?' . image_type_to_extension($this->type, true) . '/i', $filename)) {
                continue;
            }

            @unlink($directory . DIRECTORY_SEPARATOR . $filename);
        }
    }

    private function filename($width = null, $height = null, $fit = null) {
        if (null === $width && null === $height && null === $fit) {
            return strtr(
                '{id}{extension}',
                array(
                    '{id}' => $this->id,
                    '{extension}' => image_type_to_extension($this->type, true),
                )
            );
        } else {
            return strtr(
                '{id}_{width}x{height}_{fit}{extension}',
                array(
                    '{id}' => $this->id,
                    '{width}' => $width ? $width : '',
                    '{height}' => $height ? $height : '',
                    '{fit}' => $fit ? $fit : '',
                    '{extension}' => image_type_to_extension($this->type, true),
                )
            );
        }
    }

    private function path($width = null, $height = null, $fit = null) {
        return strtr(
            '{webroot}/{basePath}/{filename}',
            array(
                '{webroot}' => Yii::getPathOfAlias('webroot'),
                '{basePath}' => Yii::app()->params['images']['basePath'],
                '{filename}' => $this->filename($width, $height, $fit),
            )
        );
    }

    public function getFileUrl($width = null, $height = null, $fit = null) {
        assert(null === $fit || self::COVER === $fit || self::CONTAIN === $fit);

        $path = $this->path($width, $height, $fit);

        if (!file_exists($path)) {
            $originalPath = $this->path();

            set_error_handler(
                create_function(
                    '$severity, $message, $file, $line',
                    'throw new ErrorException($message, $severity, $severity, $file, $line);'
                )
            );

            try {
                $image = EWideImage::load($originalPath);

                if (self::COVER === $fit) {
                    $image = $image->resize($width, $height, 'outside')->crop('center', 'center', $width, $height);
                } else {
                    $image = $image->resize($width, $height, 'fill');
                }

                $image->saveToFile($path);
            } catch(Exception $exception) {
                Yii::log('Failed to resize image: ' . print_r(array('id' => $this->id, 'width' => $width, 'height' => $height, 'fit' => $fit), true), 'error', 'app.image.getUrl');
            }

            restore_error_handler();
        }

        return strtr(
            '{baseUrl}/{basePath}/{filename}',
            array(
                '{baseUrl}' => Yii::app()->baseUrl,
                '{basePath}' => Yii::app()->params['images']['basePath'],
                '{filename}' => $this->filename($width, $height, $fit),
            )
        );
    }
}