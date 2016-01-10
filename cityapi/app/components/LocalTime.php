<?php
/*
    LocalTime.php

    Copyright Stefan Fisk 2012.
*/

class LocalTime extends CApplicationComponent {
    public function utcToLocal($datetime) {
        $region = Region::model()->findByPk(Yii::app()->session['region']);
        $timeZoneName = $region->time_zone;

        $timezone = new DateTimeZone($timeZoneName);
        $offset = $timezone->getOffset(new DateTime);

        return strtotime($datetime) + $offset;
    }
}
