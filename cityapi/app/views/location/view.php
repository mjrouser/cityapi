<?php
/*
    view.php

    Copyright Stefan Fisk 2012.
*/

/* @var $this LocationController */
/* @var $location Location */

$this->pageTitle = $location->name;
$this->layout = '//layouts/tabs';
$this->activeTab = 'spaces';

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('/scripts/jquery.linkify.js');
Yii::app()->clientScript->registerScriptFile('/scripts/lightbox.js');

$this->beginWidget('application.widgets.OuterBoxWidget', array(
    'id' => 'feed-' . $location->feed->id,
    'class' => 'location feed',
    'title' => CHtml::encode($location->name),
));
    ?>
    <div class="meta">
        <img class="feed-image" width="106" height="106" src="<?php echo (null !== $location->image) ? $location->image->getFileUrl(106, 106, Image::COVER) : Yii::app()->baseUrl . '/images/location-image-default.png'; ?>">
        <p><?php echo CHtml::encode($location->description); ?></p>
        <?php
        echo CHtml::button(Yii::t('app', 'Translate'), array(
            'class' => 'translate',
            'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('//translate/location', array('id' => $location->id)) . '");'),
        ));
        ?>
    </div>
    <div class="controls">
        <?php
        echo CHtml::button(Yii::t('app', 'Add New Contribution'), array(
            'class' => 'add',
            'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('/post/create', array('feed' => $location->feed->id)) . '");'),
        ));
        $this->widget('application.widgets.FollowButtonWidget', array('feed' => $location->feed));
        ?>
    </div>
    <?php if (0 < count($location->feed->posts)): ?>
        <ul class="posts">
            <?php foreach ($location->feed->posts as $post): ?>
                <li>
                    <?php $this->renderPartial('//_post', array('post' => $post)    ); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php
    $this->widget('application.extensions.fancybox.FancyBox', array(
        'target' => '#feed-' . $location->feed->id . ' .post.post-type-image .content a'
    ));
$this->endWidget();
?>
<div class="controls">
    <?php
    echo CHtml::button(Yii::t('app', 'Add New Project'), array(
        'class' => 'add',
        'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('/project/create', array('location' => $location->id)) . '");'),
    ));
    ?>
</div>
<ul class="projects">
    <?php
    foreach($location->projects as $project) {
        ?>
        <li>
            <a id="<?php echo $project->slug; ?>"></a>
            <?php
            $this->beginWidget('application.widgets.OuterBoxWidget', array(
                'id' => 'feed-' . $project->feed->id,
                'class' => 'project feed status-' . str_replace(" ", "_", $project->status),
                'title' => CHtml::link(CHtml::encode($project->name), '#' . $project->slug),
            ));
            ?>
                <div class="meta linkify">
                    <img class="feed-image" width="106" height="106" src="<?php echo (null !== $project->image) ? $project->image->getFileUrl(106, 106, Image::COVER) : Yii::app()->baseUrl . '/images/project-image-default.png'; ?>">
                    <p><?php echo CHtml::encode($project->description); ?></p>
                    <?php
                    echo CHtml::button(Yii::t('app', 'Translate'), array(
                        'class' => 'translate',
                        'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('//translate/project', array('id' => $project->id)) . '");'),
                    ));
                    ?>
                </div>
                <div class="controls">
                    <?php
                    echo CHtml::button(Yii::t('app', 'Add New Contribution'), array(
                        'class' => 'add',
                        'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('/post/create', array('feed' => $project->feed->id)) . '");'),
                    ));
                    $this->widget('application.widgets.FollowButtonWidget', array('feed' => $project->feed));
                    if (Yii::app()->user->checkAccess('updateProject', array('project' => $project))) {
                        echo CHtml::button(Yii::t('app', 'Edit Project'), array(
                            'class' => 'edit',
                            'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('/project/edit', array('id' => $project->id)) . '");'),
                        ));
                    }
                    ?>
                </div>
                <?php if (0 < count($project->feed->posts)): ?>
                    <ul class="posts">
                        <?php foreach ($project->feed->posts as $post): ?>
                            <li>
                               <?php $this->renderPartial('/_post', array('post' => $post)); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php
                $this->widget('application.extensions.fancybox.FancyBox', array(
                    'target' => '#feed-' . $project->feed->id . ' .post.post-type-image .content a'
                ));
            $this->endWidget();
        ?>
        </li>
        <?php
    }
    ?>
</ul>
