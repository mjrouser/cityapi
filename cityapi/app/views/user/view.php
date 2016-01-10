<?php
/*
    view.php

    Copyright Stefan Fisk 2012.
*/

/* @var $this UserController */
/* @var $user User */

$this->pageTitle = CHtml::encode($user->full_name);
$this->layout = '//layouts/tabs';

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('/scripts/jquery.linkify.js');
Yii::app()->clientScript->registerScriptFile('/scripts/lightbox.js');

$this->widget('application.extensions.fancybox.FancyBox', array(
    'target' => '.post.post-type-image .content a'
));

$this->beginWidget('application.widgets.OuterBoxWidget', array(
    'class' => 'user',
    'title' => '<div class="name">' . CHtml::encode($user->full_name) . '</div><div class="registration-time">' . Yii::t('app', 'Registered') . ' ' . Yii::app()->dateFormatter->format('HH:mm M/d/y', Yii::app()->localTime->utcToLocal($user->registration_time)) . '</div>',
    'color' => '#00AEEF',
));
    if (null !== $user->image) {
        ?>
        <img width="106" height="106" src="<?php echo $user->image->getFileUrl(106, 106, Image::COVER); ?>">
        <?php
    } else {
        ?>
        <img width="106" height="106" src="<?php echo Yii::app()->baseUrl; ?>/images/user-image-default.png">
        <?php
    }

    $this->beginWidget('application.widgets.InnerBoxWidget', array(
        'class' => 'description',
        'title' => Yii::t('app', 'About Me'),
        'color' => '#00AEEF',
    ));
        if (!empty($user->description)) {
            echo CHtml::encode($user->description);
            echo CHtml::button(Yii::t('app', 'Translate'), array(
                'class' => 'translate',
                'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('//translate/user', array('id' => $user->id)) . '");'),
            ));
        } else {
            echo Yii::t('app', '{userFirstName} has not yet written a presentation.', array('{userFirstName}' => CHtml::encode($user->first_name)));
        }
    $this->endWidget();

    if (!Yii::app()->user->isGuest) {
        ?>
        <div class="controls">
            <?php
            if ($user->id === Yii::app()->user->id) {
                echo CHtml::button(Yii::t('app', 'Edit Profile'), array(
                    'class' => 'edit',
                    'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('edit', array('id' => $user->id)) . '");'),
                ));
                echo CHtml::link(
                    Yii::t('app', 'Messages'),
                    array('message/list'),
                    array('class' => 'messages')
                );
            } else {
                echo CHtml::link(
                    Yii::t('app', 'Message'),
                    array('message/thread', 'recipient' => $user->id),
                    array('class' => 'message')
                );
            }
            ?>
        </div>
    <?php
    }
$this->endWidget();

?>
<a id="notifications"></a>
<?php
$this->renderPartial('_notifications', array(
    'userId' => $user->id,
    'onlyUnviewed' => true
));

$this->beginWidget('application.widgets.OuterBoxWidget', array(
    'class' => 'recent-contributions feed',
    'title' => Yii::t('app', 'Recent Contributions'),
));
    ?>
    <ul class="posts">
        <?php
        $contributionLogs = RecordOperationLog::model()->findAll(array(
            'condition' => 'user_id = :user_id AND model = :model AND action = :action',
            'params' => array(':user_id' => $user->id, ':model' => get_class(Post::model()), ':action' => RecordOperationLog::INSERT),
            'order' => 'time DESC',
            'limit' => Yii::app()->params['userProfile']['numberOfRecentContributions']
        ));
        ?>
        <?php foreach ($contributionLogs as $contributionLog): ?>
            <?php
            $post = Post::model()->findByPk($contributionLog->model_id);

            if (!$post) {
                continue;
            } else if ($post->feed->region) {
                $url = array('/resource/list', 'id' => $post->feed->region->id, '#' => 'post-' . $post->id);
                $feedName = $post->feed->region->name;
                $feedUrl = $this->createUrl('/resource/list', array('region' => $post->feed->region->id));
            } else if ($post->feed->location) {
                $url = array('/location/view', 'id' => $post->feed->location->id, '#' => 'post-' . $post->id);
                $feedName = $post->feed->location->name;
                $feedUrl = $this->createUrl('/location/view', array('id' => $post->feed->location->id));
            } else if ($post->feed->project) {
                $url = array('/location/view', 'id' => $post->feed->project->location->id, '#' => 'post-' . $post->id);
                $feedName = $post->feed->project->name;
                $feedUrl = $this->createUrl('/location/view', array('id' => $post->feed->project->location->id, '#' => $post->feed->project->slug));
            } else {
                Yii::log('Unknown post feed parent. $post = ' . $post->id, 'error', 'app.user.view');
                continue;
            }
            ?>
            <li>
                <?php
                $this->renderPartial('//_post', array(
                    'post' => $post,
                    'url' => $url,
                    'feedName' => $feedName,
                    'feedUrl' => $feedUrl,
                    'showUsername' => false
                ));
                ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php
$this->endWidget();
