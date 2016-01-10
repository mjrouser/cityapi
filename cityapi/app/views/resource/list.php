<?php
/*
    list.php

    Copyright Stefan Fisk 2012.
*/

/* @var $this LocationController */
/* @var $region Region */
/* @var $type Post.resource_type */

$this->pageTitle = Yii::t('app', 'Resources');
$this->layout = '//layouts/tabs';
$this->activeTab = 'resources';

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('/scripts/lightbox.js');

$resource_type = $type;

// Region posts

$criteria = new CDbCriteria();
$criteria->addColumnCondition(array('type' => Post::RESOURCE));
if (null !== $resource_type) {
    $criteria->addColumnCondition(array('resource_type' => $resource_type));
}
$criteria->addColumnCondition(array('feed_id' => $region->feed_id));

$regionResources = Post::model()->findAll($criteria);
$regionResources = __::map($regionResources, function($post) { return array('post' => $post, 'scope' => 'region'); });

// Location posts

$locationFeedIds = array();

foreach($region->locations as $location) {
    $locationFeedIds[] = $location->feed_id;
}

$criteria = new CDbCriteria();
$criteria->addColumnCondition(array('type' => Post::RESOURCE));
if (null !== $resource_type) {
    $criteria->addColumnCondition(array('resource_type' => $resource_type));
}
$criteria->addInCondition('feed_id', $locationFeedIds);

$locationResources = Post::model()->findAll($criteria);
$locationResources = __::map($locationResources, function($post) { return array('post' => $post, 'scope' => 'location'); });

// Project posts

$projectFeedIds = array();

foreach($region->locations as $location) {
    foreach($location->projects as $project) {
        $projectFeedIds[] = $project->feed_id;
    }
}

$criteria = new CDbCriteria();
$criteria->addColumnCondition(array('type' => Post::RESOURCE));
if (null !== $resource_type) {
    $criteria->addColumnCondition(array('resource_type' => $resource_type));
}
$criteria->addInCondition('feed_id', $projectFeedIds);

$projectResources = Post::model()->findAll($criteria);
$projectResources = __::map($projectResources, function($post) { return array('post' => $post, 'scope' => 'project'); });

// All posts

$resources = __::sortBy(array_merge($regionResources, $locationResources, $projectResources), function($post) { return -strtotime($post['post']->creation_time); });

// Render the view

$title = '<a href="' . $this->createUrl('list', array('region' => $region->id)) . '">';
$title .= Yii::t('app', 'Resources');
$title .= '</a>';


$title .= 'skill' === $resource_type ? '<a class="type selected" ' : '<a class="type" ';
$title .= 'href="' . $this->createUrl('list', array('region' => $region->id, 'type' => 'skill')) . '">';
$title .= Yii::t('app',  'Skills');
$title .= '</a>';

$title .= 'material' === $resource_type ? '<a class="type selected" ' : '<a class="type" ';
$title .= 'href="' . $this->createUrl('list', array('region' => $region->id, 'type' => 'material')) . '">';
$title .= Yii::t('app',  'Materials');
$title .= '</a>';

$title .= 'tool' === $resource_type ? '<a class="type selected" ' : '<a class="type" ';
$title .= 'href="' . $this->createUrl('list', array('region' => $region->id, 'type' => 'tool')) . '">';
$title .= Yii::t('app',  'Tools');
$title .= '</a>';

$this->beginWidget('application.widgets.OuterBoxWidget', array(
    'class' => 'feed resources',
    'title' => $title,
    'color' => '#AFAFAF',
));
    ?>
    <div class="controls">
        <?php
        echo CHtml::button(Yii::t('app', 'Add New Resource'), array(
            'class' => 'add',
            'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('create', array('region' => $region->id)) . '");'),
        ));
        ?>
    </div>
    <?php
    if (0 !== count($resources)) {
        ?>
        <ul class="posts">
            <?php foreach($resources as $resource): ?>
                <li>
                    <?php
                    $post = $resource['post'];
                    $scope = $resource['scope'];
                    $class = 'scope-' . $scope;

                    if ('region' === $scope) {
                        $feedName = null;
                        $feedUrl = null;
                    } else if ('location' === $scope) {
                        $feedName = $post->feed->location->name;
                        $feedUrl = $this->createUrl('//location/view', array('id' => $post->feed->location->id, '#' => 'post-' . $post->id));
                    } else if ('project' === $scope) {
                        $feedName = $post->feed->project->name;
                        $feedUrl = $this->createUrl('//location/view', array('id' => $post->feed->project->location->id, '#' => 'post-' . $post->feed->project->slug));
                    }

                    $this->renderPartial('//_post', array(
                        'post' => $post,
                        'class' => $class,
                        'feedName' => $feedName,
                        'feedUrl' => $feedUrl,
                        'createCommentUrl' => $this->createUrl('createComment', array('post' => $post->id))
                    ));
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    } else {
        if (null === $type) {
            ?>
            <div class="empty-message"><?php echo Yii::t('app', 'Noone has added any resources yet. Why don\'t you be the first!'); ?></div>
            <?php
        } else {
            ?>
            <div class="empty-message"><?php echo Yii::t('app', 'Noone has added any ' . $resource_type . 's yet. Why don\'t you be the first!'); ?></div>
            <?php
        }
    }
$this->endWidget();
