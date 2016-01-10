<?php
/*
    edit.php

    Copyright Stefan Fisk 2012.
*/

/* @var $project Project */

$this->layout = '//layouts/lightbox';

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('/scripts/lightbox.js');
Yii::app()->clientScript->registerScriptFile('/scripts/file-input.js');

?>
<h2><?php echo Yii::t('app', 'Edit Project'); ?></h2>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'validateOnType' => true,
    ),
    'htmlOptions' => array('enctype'=>'multipart/form-data'),
));
?>
    <section class="name-description">
        <h3><?php echo Yii::t('app', 'Title and Description'); ?></h3>
        <div class="errorMessages">
            <?php
            echo $form->error($project, 'description');
            echo $form->error($project, 'name');
            ?>
        </div>
        <?php
        echo $form->textField($project, 'name', array('placeholder' => Yii::t('app', 'Title')));
        echo $form->textArea($project,'description', array('placeholder' => Yii::t('app', 'Description')));
        ?>
    </section>
    <section class="image inactive">
        <h3><?php echo Yii::t('app', 'Project Image'); ?></h3>
        <img width="106" height="106" src="<?php if ($project->image) echo $project->image->getFileUrl(106, 106, Image::COVER); ?>">
        <?php
        echo CHtml::label(Yii::t('app', 'Select an image file on your computer.'), 'Post[image_file]');
        echo $form->fileField($image, 'file');
        echo CHtml::label('', 'Image[file]');
        echo CHtml::button(Yii::t('app', 'Choose file…'), array(
            'class' => 'file',
            'onclick' => new CJavaScriptExpression('$(this).siblings("input[type=file]")[0].click();'),
        ));
        echo $form->error($image, 'file');
        ?>
    </section>
    <section class="champs inactive">
        <h3><?php echo Yii::t('app', 'Champs'); ?></h3>
        <?php
        echo CHtml::hiddenField('', '', array('id' => 'Project_champs'));
        Yii::app()->getClientScript()->registerScript('Project_champs', <<<'EOD'
            $('[name="Project[champs][]"]').click(function() {
                $('#Project_champs').val(Math.random()).change();
            });
EOD
);
        ?>
        <?php echo $form->error($project, 'champs'); ?>
        <ul>
            <?php
            $users = User::model()->findAllBySql(
<<<EOD
SELECT user.*
FROM project
    JOIN user
    ON project.feed_id = :feed_id AND project.created_by_user_id = user.id

UNION DISTINCT

SELECT user.*
FROM project_champ
    JOIN project
    ON project.feed_id = :feed_id AND project_champ.project_id = project.id
    JOIN user
    ON project_champ.user_id = user.id

UNION DISTINCT

SELECT user.*
FROM post
JOIN user
ON feed_id = :feed_id AND post.user_id = user.id

UNION DISTINCT

SELECT user.*
FROM comment
    JOIN post
    ON post.feed_id = :feed_id AND comment.post_id = post.id
    JOIN user
    ON comment.user_id = user.id

ORDER BY first_name, last_name, registration_time
EOD
                ,
                array(':feed_id' => $project->feed_id)
            );

            foreach ($users as $user) {
                ?>
                <li class="champ">
                    <input id="champ-checkbox-<?php echo $user->id; ?>" type="checkbox" name="Project[champs][]" value="<?php echo $user->id; ?>" <?php if (Yii::app()->user->checkAccess('projectChamp', array('project' => $project, 'userId' => $user->id))) echo 'checked="checked"'; ?>>
                    <label for="champ-checkbox-<?php echo $user->id; ?>">
                        <img width="106" height="106" src="<?php echo $user->image ? $user->image->getFileUrl(106, 106, Image::COVER) : Yii::app()->baseUrl . '/images/user-image-default.png'; ?>">
                        <div class="name"><?php echo CHtml::encode($user->full_name); ?></div>
                        <div class="is-champ"><?echo Yii::t('app', 'Champ!'); ?></div>
                    </label>
                </li>
                <?php
            }
            ?>
        </ul>
    </section>
    <section class="status inactive">
        <h3><?php echo Yii::t('app', 'Status'); ?></h3>
        <div class="automatic">
            <label><?php echo Yii::t('app', 'Reactive Status'); ?></label>
        </div>
        <ul class="statuses">
            <?php
            foreach(array(Project::STARTED, Project::UNDER_CONSTRUCTION, Project::FINISHED, Project::ONGOING) as $status) {
                ?>
                <li class="status <?php echo str_replace(" ", "_", $status); ?> <?php if ($status === $project->status) echo 'current'; ?>">
                    <input id="status-radio-<?php echo $status; ?>" type="radio" name="Project[status]" value="<?php echo $status; ?>" <?php if ($status === $project->status) echo 'checked="checked"'; ?>>
                    <label for="status-radio-<?php echo $status; ?>" class="name"><div class="text"><?php echo Yii::t('app', $status); ?></div></label>
                </li>
                <?php
            }
            foreach(array(Project::LOW_ACTIVITY, Project::NO_ACTIVITY, Project::ABANDONED) as $status) {
                ?>
                <li class="status <?php echo str_replace(" ", "_", $status); ?> <?php if ($status === $project->status) echo 'current'; ?>">
                    <input type="radio" name="status" value="<?php echo $status; ?>" <?php if ($status === $project->status) echo 'checked="checked"'; ?>>
                    <div class="name"><div class="text"><?php echo Yii::t('app', $status); ?></div>
                </li>
                <?php
            }
            ?>
        </ul>
    </section>
    <nav>
        <?php
        echo CHtml::button(Yii::t('app', 'Back'), array(
            'class' => 'back',
            'onclick' => new CJavaScriptExpression('app.lightbox.back()'),
            'style' => 'display: none;',
        ));
        echo CHtml::button(Yii::t('app', 'Next'), array(
            'class' => 'next',
            'onclick' => new CJavaScriptExpression('app.lightbox.next()'),
        ));
        echo CHtml::submitButton(Yii::t('app', 'Save'), array(
            'class' => 'submit',
            'style' => 'display: none;',
        ));
        echo CHtml::button(Yii::t('app', 'Cancel'), array(
            'class' => 'cancel',
            'onclick' => new CJavaScriptExpression('window.parent.app.lightbox.close()'),
        ));
        ?>
    </nav>
<?php
$this->endWidget();
