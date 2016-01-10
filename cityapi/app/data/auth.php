<?php
/*
    auth.php

    Copyright Stefan Fisk 2012.
*/

return array(
    // Roles

    'authenticated' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('app', 'Authenticated user'),
        'bizRule' => 'return !Yii::app()->user->isGuest;',
        'data' => '',
        'children' => array(
            'deleteOwnPost',
            'deleteOwnComment',
            'projectChamp'
        )
    ),

    'admin' => array (
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('app', 'Administrator'),
        'bizRule' => 'return !Yii::app()->user->isGuest && Yii::app()->user->isAdmin;',
        'data' => '',
        'children' => array(
            'updateProject',
            'deletePost',
            'deleteComment',
        )
    ),

    'projectChamp' => array (
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('app', 'Project Champ'),
        'bizRule' => <<<'EOD'
            if (isset($params['project'])) {
                return ProjectChamp::model()->exists(
                    'user_id = :user_id && project_id = :project_id',
                    array(
                        ':user_id' => $params['userId'],
                        ':project_id' => $params['project']->id
                    )
                );
            } else if (isset($params['post']) && $params['post']->feed->project) {
                return ProjectChamp::model()->exists('user_id = :user_id && project_id = :project_id', array(':user_id' => $params['userId'], ':project_id' => $params['post']->feed->project->id));
            } else if (isset($params['comment']) && $params['comment']->post->feed->project) {
                return ProjectChamp::model()->exists('user_id = :user_id && project_id = :project_id', array(':user_id' => $params['userId'], ':project_id' => $params['comment']->post->feed->project->id));
            } else {
                return false;
            }
EOD
,
        'data' => '',
        'children' => array(
            'updateProject',
            'deletePost',
            'deleteComment'
        )
    ),

    // Tasks

    'deleteOwnPost' => array(
        'type' => CAuthItem::TYPE_TASK,
        'description' => Yii::t('app', 'Delete a post you\'ve created'),
        'bizRule' => 'return $params[\'post\']->user_id == $params[\'userId\'];',
        'data' => '',
        'children' => array(
            'deletePost'
        )
    ),

    'deleteOwnPostComment' => array(
        'type' => CAuthItem::TYPE_TASK,
        'description' => Yii::t('app', 'Delete a comment to a post you\'ve created'),
        'bizRule' => 'return $params[\'comment\']->post->user_id == $params[\'userId\'];',
        'data' => '',
        'children' => array(
            'deleteComment'
        )
    ),

    'deleteOwnComment' => array(
        'type' => CAuthItem::TYPE_TASK,
        'description' => Yii::t('app', 'Delete a post you\'ve created'),
        'bizRule' => 'return $params[\'comment\']->user_id == $params[\'userId\'];',
        'data' => '',
        'children' => array(
            'deleteComment'
        )
    ),

    // Operations

    'updateProject' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => Yii::t('app', 'Update a project'),
        'bizRule' => '',
        'data' => '',
    ),

    'deletePost' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => Yii::t('app', 'Delete a post'),
        'bizRule' => '',
        'data' => '',
    ),

    'deleteComment' => array(
        'type' => CAuthItem::TYPE_OPERATION,
        'description' => Yii::t('app', 'Delete a comment'),
        'bizRule' => '',
        'data' => '',
    ),
);
