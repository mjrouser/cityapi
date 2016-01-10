<?php
/*
    map.php

    Copyright Stefan Fisk 2012.
*/

/* @var $this MapController */
/* @var $region Region */

$this->pageTitle = Yii::t('app', 'Map');
$this->layout = '//layouts/tabs';
$this->activeTab = 'map';

// Map

Yii::import('application.extensions.egmap.*');

$map = new EGMap();
$map->setJsName('map');
$map->width = '100%';
$map->height = 470;
$map->zoom = 8;
$map->setCenter(39.737827146489174, 3.2830574338912477);
$map->mapTypeControl = false;
$map->panControl = false;
$map->streetViewControl = false;
$map->scrollwheel = false;

$fitBounds = "map.fitBounds(new google.maps.LatLngBounds(new google.maps.LatLng($region->southwest_latitude, $region->southwest_longitude), new google.maps.LatLng($region->northeast_latitude, $region->northeast_longitude)));";

foreach($region->locations as $location) {
    $marker = new EGMapMarker($location->latitude, $location->longitude);

    // the setter does not perform the special handling of 'title' that the constructor gets via setOptions().
    $marker->title = "'$location->name'";
    $marker->icon = new EGMapMarkerImage(Yii::app()->request->baseUrl . '/images/map-marker-blue.png');
    $marker->icon->setSize(59, 54);
    $marker->icon->setAnchor(59 / 2, 54);

    $infoBox = new EGMapInfoBox($this->renderPartial('_mapInfoBox', array('location' => $location), true));

    $infoBox->closeBoxMargin = '"17px 17px 2px 2px"';
    $infoBox->infoBoxClearance = new EGMapSize(1,1);
    $infoBox->enableEventPropagation ='"floatPane"';
    $infoBox->pixelOffset = new EGMapSize('-10','30');

    $marker->addHtmlInfoBox($infoBox);

    $map->addMarker($marker);
}

$map->renderMap(array($fitBounds));

// List
?>
<ol class="locations">
    <?php
    foreach($region->locations as $location) {
        ?>
        <li>
            <?php if (null !== $location->image): ?>
                <img width="106" height="106" src="<?php echo $location->image->getFileUrl(106, 106, Image::COVER); ?>">
            <?php else: ?>
                <img width="106" height="106" src="<?php echo Yii::app()->baseUrl; ?>/images/location-image-default.png">
            <?php endif; ?>
            <?php
            $this->beginWidget('application.widgets.InnerBoxWidget', array(
                'class' => 'location',
                'title' => Yii::t(
                    'app', '{locationName}! created by {userName} @ {locationCreationTime}',
                    array(
                        '{locationName}' => CHtml::link(CHtml::encode($location->name), array('//location/view', 'id' => $location->id)),
                        '{userName}' => CHtml::link(CHtml::encode($location->createdByUser->full_name), array('//user/view', 'id' => $location->createdByUser->id)),
                        '{locationCreationTime}' => Yii::app()->dateFormatter->format('HH:mm M/d/y', Yii::app()->localTime->utcToLocal($location->creation_time))
                    )
                ),
                'color' => '#00AEEF',
            ));
            ?>
                <?php echo CHtml::encode($location->description); ?>
            <?php $this->endWidget(); ?>
        </li>
        <?php
    }
    ?>
</ol>