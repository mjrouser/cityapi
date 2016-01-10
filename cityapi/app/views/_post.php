<?php
/*
    _post.php

    Copyright Stefan Fisk 2012.
*/

/* @var $post Post */
/* @var $class string */
/* @var $url string */
/* @var $feedName string */
/* @var $feedUrl string */
/* @var showUsername boolean */
/* @var showComments boolean */
/* @var $createCommentUrl */

if (!isset($class)) $class = '';
if (!isset($url)) $url = '#post-' . $post->id;
if (!isset($feedName)) $feedName = null;
if (!isset($feedName)) $feedUrl = null;
if (!isset($showUsername)) $showUsername = true;
if (!isset($showComments)) $showComments = true;
if (!isset($createCommentUrl)) $createCommentUrl = $this->createUrl('/comment/create', array('post' => $post->id));

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('/scripts/jquery.linkify.js');
Yii::app()->clientScript->registerScriptFile('/scripts/jquery.youtubeEmbed.js');

$userImageSize = 106;

// Title

$postCreationTime = Yii::app()->dateFormatter->format('HH:mm M/d/y', Yii::app()->localTime->utcToLocal($post->creation_time));
$titleParams = array(
    '{feedLink}' => CHtml::link(CHtml::encode($feedName), $feedUrl),
    '{userLink}' => CHtml::link(CHtml::encode($post->user->full_name), array('//user/view', 'id' => $post->user->id)),
    '{postCreationTime}' => Yii::app()->dateFormatter->format('HH:mm M/d/y', Yii::app()->localTime->utcToLocal($post->creation_time))
);

 if (Post::TEXT === $post->type) {
    $titleParams['{postLink}'] = CHtml::link(CHtml::encode(Yii::t('app', 'Posted')), $url);
} else if (Post::IMAGE == $post->type) {
    $titleParams['{postLink}'] = CHtml::link(CHtml::encode(Yii::t('app', 'Image')), $url);
} else if (Post::RESOURCE === $post->type) {
    $titleParams['{postLink}'] = CHtml::link(CHtml::encode(ucfirst(Yii::t('app', $post->resource_type))), $url);
} else {
    Yii::log('Unknown post type. $post = ' . $post->type, 'error', 'app.user.view');
    return;
}

if (!$feedName && !$showUsername) {
    if (Post::TEXT === $post->type) {
        $title = Yii::t('app', '{postLink} @ {postCreationTime}', $titleParams);
    } else {
        $title = Yii::t('app', '{postLink} added @ {postCreationTime}', $titleParams);
    }
} else if ($feedName && !$showUsername) {
    if (Post::TEXT === $post->type) {
        $title = Yii::t('app', '{postLink} to {feedLink} @ {postCreationTime}', $titleParams);
    } else {
        $title = Yii::t('app', '{postLink} added to {feedLink} @ {postCreationTime}', $titleParams);
    }
} else if (!$feedName && $showUsername) {
    if (Post::TEXT === $post->type) {
        $title = Yii::t('app', '{postLink} by {userLink} @ {postCreationTime}', $titleParams);
    } else {
        $title = Yii::t('app', '{postLink} added by {userLink} @ {postCreationTime}', $titleParams);
    }
} else if ($feedName && $showUsername) {
    if (Post::TEXT === $post->type) {
        $title = Yii::t('app', '{postLink} to {feedLink} by {userLink} @ {postCreationTime}', $titleParams);
    } else {
        $title = Yii::t('app', '{postLink} added to {feedLink} by {userLink} @ {postCreationTime}', $titleParams);
    }
}



// Content

ob_start();

if (Post::TEXT === $post->type) {
    ?>
    <div class="text-container linkify youtube-embed">
        <?php echo CHtml::encode($post->text); ?>
        <?php
        echo CHtml::button(Yii::t('app', 'Translate'), array(
            'class' => 'translate',
            'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('//translate/post', array('id' => $post->id)) . '");'),
        ));
        ?>
    </div>
    <?php
} else if (Post::IMAGE === $post->type) {
    $zoom = Yii::app()->params['feed']['postContentWidth'] / $post->image->width;
    $width = Yii::app()->params['feed']['postContentWidth'];
    $height = $post->image->height * $zoom;

    ?>
    <div class="image-container">
    <?php
        echo CHtml::link(
            CHtml::image(
                $post->image->fileUrl,
                null,
                array(
                    'style' =>
                        'width: ' . $width . 'px;' .
                        'margin-top: -' . $height / 2 . 'px;',
                )
            ),
            $post->image->fileUrl
        );
    ?>
    </div>
    <?php
} else if (Post::RESOURCE === $post->type) {
    ?>
    <div class="text-container linkify">
        <?php echo CHtml::encode($post->text); ?>
        <?php
        echo CHtml::button(Yii::t('app', 'Translate'), array(
            'class' => 'translate',
            'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('//translate/post', array('id' => $post->id)) . '");'),
        ));
        ?>
    </div>
    <?php
}

$content = ob_get_clean();

// Comments

ob_start();

?>
<ul class="comments">
    <?php foreach($post->comments as $comment): ?>
        <li>
            <?php $this->renderPartial('//_comment', array('comment' => $comment)); ?>
        </li>
    <?php endforeach; ?>
</ul>

<div class="comment-controls">
    <?php
    echo CHtml::button(Yii::t('app', 'Add comment'), array(
        'class' => 'add',
        'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $createCommentUrl . '");'),
    ));
    ?>
</div>

<?php
$comments = ob_get_clean();

// Put it all together

?>
<div id="post-<?php echo $post->id; ?>" class="<?php echo 'post post-type-' . $post->type . ' status-' . str_replace(' ', '_', $post->status) . ' ' . $class; ?>">
    <img class="user-image" width="106" height="106" src="<?php echo $post->user->image ? $post->user->image->getFileUrl(106, 106, Image::COVER) : Yii::app()->baseUrl . '/images/user-image-default.png' ?>">

    <?php
    $deleteButton = CHtml::button(Yii::t('app', 'Delete'), array(
        'class' => 'delete',
        'onclick' => new CJavaScriptExpression('app.lightbox.open("' . $this->createUrl('//post/delete', array('id' => $post->id)) . '");'),
    ));

    $this->renderPartial('//_innerBox', array(
        'title' => $title,
        'buttons' => Yii::app()->user->checkAccess('deletePost', array('post' => $post)) ? $deleteButton : '',
        'content' => $showComments ? $content . $comments : $content,
    ));
    ?>
</div>
