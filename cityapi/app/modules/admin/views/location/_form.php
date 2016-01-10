<?php
/* @var $this LocationController */
/* @var $model Location */
/* @var $form CActiveForm */
?>

<div class="form">
<?php

if ($model->region) {
    Yii::import('application.extensions.egmap.*');
    Yii::app()->clientScript->registerCoreScript('jquery');

    $map = new EGMap();
    $map->setJsName('map');
    $map->width = '100%';
    $map->height = 470;
    $map->zoom = 8;
    $map->setCenter(39.737827146489174, 3.2830574338912477);
    $map->mapTypeControl = false;
    $map->panControl = false;
    $map->streetViewControl = false;

    $latitudeAttributeName = 'latitude';
    $longitudeAttributeName = 'longitude';

    $marker = new EGMapMarker(
        $model->latitude,
        $model->longitude,
        array(
        ),
        'marker',
        array(
            'dragevent' => new EGMapEvent(
                'dragend',
                strtr(
                    'function(event){$("input[name=\"{latName}\"]").val(event.latLng.lat());$("input[name=\"{lngName}\"]").val(event.latLng.lng());}',
                    array(
                        '{latName}' => CHtml::resolveName($model, $latitudeAttributeName),
                        '{lngName}' => CHtml::resolveName($model, $longitudeAttributeName),
                    )
                ),
                false,
                EGMapEvent::TYPE_EVENT_DEFAULT
            ),
        )
    );

    // the setter does not perform the special handling of 'title' that the constructor gets via setOptions().
    $marker->title = "'$model->name'";
    $marker->icon = new EGMapMarkerImage(Yii::app()->request->baseUrl . '/images/map-marker-blue.png');
    $marker->icon->setSize(59, 54);
    $marker->icon->setAnchor(59 / 2, 54);
    $marker->draggable = true;

    $map->addMarker($marker);

    $fitBounds = strtr(
        'map.fitBounds(new google.maps.LatLngBounds(new google.maps.LatLng({sw.lat}, {sw.lng}), new google.maps.LatLng({ne.lat}, {ne.lng})));',
        array(
            '{sw.lat}' => $model->region->southwest_latitude,
            '{sw.lng}' => $model->region->southwest_longitude,
            '{ne.lat}' => $model->region->northeast_latitude,
            '{ne.lng}' => $model->region->northeast_longitude,
        )
    );

    $map->renderMap(array($fitBounds));
}

?>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'location-form',
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'created_by_user_id'); ?>
        <?php echo CHtml::activeDropDownList($model, 'created_by_user_id', CHtml::listData(User::model()->findAll(), 'id', 'full_name')); ?>
        <?php echo $form->error($model,'created_by_user_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'region_id'); ?>
        <?php echo CHtml::activeDropDownList($model, 'region_id', CHtml::listData(Region::model()->findAll(), 'id', 'name')); ?>
        <?php echo $form->error($model,'region_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'longitude'); ?>
        <?php echo $form->textField($model,'longitude'); ?>
        <?php echo $form->error($model,'longitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'latitude'); ?>
        <?php echo $form->textField($model,'latitude'); ?>
        <?php echo $form->error($model,'latitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'slug'); ?>
        <?php echo $form->textField($model,'slug',array('size'=>60,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'slug'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'image_id'); ?>
        <?php echo $form->textField($model,'image_id'); ?>
        <?php echo $form->error($model,'image_id'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->