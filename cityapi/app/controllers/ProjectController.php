<?php
/*
    ProjectController.php

    Copyright Stefan Fisk 2012.
*/

class ProjectController extends Controller {
    public $layout = '//layouts/script';

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array('view'),
                'users' => array('*'),
            ),

            array(
                'allow',
                'actions' => array('create', 'edit'),
                'users' => array('@'),
            ),

            array(
                'deny',
            ),
        );
    }

    public function actionCreate($location = null) {
        $project = new Project();
        $project->location_id = $location;
        $project->created_by_user_id = Yii::app()->user->id;
        $project->champs = array($project->created_by_user_id);

        if(!isset($_POST['Project'])) {
            $this->render('create', array(
                'model' => $project,
            ));
            Yii::app()->end();
        }

        $project->attributes = $_POST['Project'];
        $project->slug = slugify($project->name);

        if(isset($_POST['ajax'])) {
            echo CActiveForm::validate($project, null, false);
            Yii::app()->end();
        }

        if(!$project->validate()) {
            $this->render('create', array(
                'model' => $project,
            ));
            Yii::app()->end();
        }

        $feed = new Feed;
        $feed->followers = array($project->created_by_user_id);
        $feed->save();
        $project->feed_id = $feed->id;

        if (!$project->save()) {
            Yii::log('Project::save() failed. $errors = ' . print_r($project->getErrors(), true), 'error', 'app.project.create');
            $form->addError('save', 'Failed save the new project.');
            $this->render('create', array('model' => $project));
            $feed->delete();
            Yii::app()->end();
        }

        $this->renderText('window.parent.location = "' . $this->createUrl('view', array('id' => $project->id)) . '";');
    }

    public function actionView($id) {
        $project = $this->loadModel($id);

        $this->redirect(array('location/view', 'id' => $project->location->id, '#' => $project->slug));
    }

    public function actionEdit($id) {
        $project = $this->loadModel($id);
        $image = new Image;

        if (!Yii::app()->user->checkAccess('updateProject', array('project' => $project))) {
            throw new CHttpException(403);
        }

        if(!isset($_POST['Project'])) {
            $this->render('edit', array(
                'project' => $project,
                'image' => $image,
            ));
            Yii::app()->end();
        }

        $project->attributes = $_POST['Project'];
        $project->slug = slugify($project->name);
        if (!isset($_POST['Project']['champs'])) {
            $project->champs = null;
        }

        if(isset($_POST['ajax'])) {
            echo CActiveForm::validate($project, null, false);
            Yii::app()->end();
        }

        if(!$project->validate()) {
            $this->render('edit', array(
                'project' => $project,
                'image' => $image,
            ));
            Yii::app()->end();
        }

        $image->attributes = $_POST['Image'];
        $image->file = CUploadedFile::getInstance($image, 'file');

        if ($image->file) {
            if (!$image->save()) {
                $this->render('edit', array(
                    'project' => $project,
                    'image' => $image,
                ));
                Yii::app()->end();
            }

            $project->image_id = $image->id;
        }

        if (!$project->save()) {
            $image->delete();

            Yii::log('Project::save() failed. $errors = ' . print_r($project->getErrors(), true), 'error', 'app.project.edit');
            $form->addError('save', 'Failed save the changes to the project.');
            $this->render('edit', array(
                'project' => $project,
                'image' => $image,
            ));
            Yii::app()->end();
        }

        $this->renderText('window.parent.location = "' . $this->createUrl('view', array('id' => $project->id)) . '";');
    }

    public function loadModel($id) {
        $model = Project::model()->findByPk($id);

        if(null === $model) {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }

        return $model;
    }
}
