<?php
/*
    list.php

    Copyright Stefan Fisk 2012.
*/

/* @var $this LocationController */
/* @var $region Region */

$this->pageTitle = Yii::t('app', 'Spaces');
$this->layout = '//layouts/tabs';
$this->activeTab = 'spaces';

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('/scripts/jquery.linkify.js');
Yii::app()->clientScript->registerScriptFile('/scripts/lightbox.js');

foreach($region->locations as $location) {
    $this->beginWidget('application.widgets.OuterBoxWidget', array(
        'class' => 'location',
        'title' => CHtml::link(CHtml::encode($location->name), array('//location/view', 'id' => $location->id)),
        'color' => '#00AEEF',
    ));

    ?>
        <ol class="projects">
            <?php
            foreach($location->projects as $project) {
                ?>
                <li>
                    <?php if (null !== $project->image): ?>
                        <img width="106" height="106" src="<?php echo $project->image->getFileUrl(106, 106, Image::COVER); ?>">
                    <?php else: ?>
                        <img width="106" height="106" src="<?php echo Yii::app()->baseUrl; ?>/images/project-image-default.png">
                    <?php endif; ?>
                    <?php
                    $this->beginWidget('application.widgets.InnerBoxWidget', array(
                        'class' => 'project status-' . str_replace(" ", "_", $project->status),
                        'title' => Yii::t(
                            'app', '{projectName}! Posted by {userName} @ {projectCreationTime}',
                            array(
                                '{projectName}' => CHtml::link(CHtml::encode($project->name), array('//location/view', 'id' => $location->id, '#' => $project->slug)),
                                '{userName}' => CHtml::link(CHtml::encode($project->createdByUser->full_name), array('//user/view', 'id' => $project->createdByUser->id)),
                                '{projectCreationTime}' => Yii::app()->dateFormatter->format('HH:mm M/d/y', Yii::app()->localTime->utcToLocal($project->creation_time))
                            )
                        ),
                    ));
                    ?>
                        <?php echo CHtml::encode($project->description); ?>
                        <?php
                        echo CHtml::button(Yii::t('app', 'Translate'), array(
                            'class' => 'translate',
                            'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('//translate/project', array('id' => $project->id)) . '");'),
                        ));
                        ?>
                    <?php $this->endWidget(); ?>
                </li>
                <?php
            }
            ?>
        </ol>
    <?php
    $this->endWidget();
}
