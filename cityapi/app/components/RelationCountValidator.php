<?php
/*
    RelationCountValidator.php

    Copyright Stefan Fisk 2012.
 */

class RelationCountValidator extends CValidator {
    /**
     * @var integer maximum count. Defaults to null, meaning no maximum limit.
     */
    public $max;
    /**
     * @var integer minimum coumt. Defaults to null, meaning no minimum limit.
     */
    public $min;
    /**
     * @var integer exact count. Defaults to null, meaning no exact length limit.
     */
    public $is;
    /**
     * @var string user-defined error message used when the value is too few.
     */
    public $tooFew;
    /**
     * @var string user-defined error message used when the value is too many.
     */
    public $tooMany;
    /**
     * @var boolean whether the attribute value can be null or empty. Defaults to true,
     * meaning that if the attribute is empty, it is considered valid.
     */
    public $allowEmpty = true;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute) {
        $value = $object->$attribute;

        if($this->allowEmpty && $this->isEmpty($value)) {
            return;
        }

        $count = count($value);

        if($this->min !== null && $count < $this->min) {
            $message = $this->tooFew !== null ? $this->tooFew : Yii::t('app', '{attribute} is too short (minimum is {min} objects).');
            $this->addError($object, $attribute, $message, array('{min}' => $this->min));
        }

        if($this->max !== null && $count > $this->max) {
            $message = $this->tooMany !== null ? $this->tooMany : Yii::t('app', '{attribute} is too long (maximum is {max} objects).');
            $this->addError($object, $attribute, $message, array('{max}' => $this->max));
        }

        if($this->is !== null && $count !== $this->is) {
            $message = $this->is !== null ? $this->is : Yii::t('app', '{attribute} is of the wrong length (should be {length} objects).');
            $this->addError($object, $attribute, $message, array('{length}' => $this->is));
        }
    }
}
