<?php
/*
    SiteController.php

    Copyright Stefan Fisk 2012.
*/

class SiteController extends Controller {
    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array('index', 'help', 'about', 'search', 'termsOfService', 'error'),
                'users' => array('*'),
            ),

            array(
                'deny',
            ),
        );
    }

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionIndex() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('//location/map'));
        } else {
            $this->redirect(array('//user/view', 'id' => Yii::app()->user->id));
        }
    }

    public function actionHelp() {
        $this->render('help');
    }

    public function actionTermsOfService() {
        $this->render('termsOfService');
    }

    public function actionAbout() {
        $form = new ContactForm;

        if(isset($_POST['ContactForm'])) {
            $form->attributes = $_POST['ContactForm'];

            if($form->validate()) {
                $from = Yii::app()->params['email']['addresses']['noReply'];
                $headers = "From: {$from}";
                $body = 'Name: ' . $form->name . "\n" . 'Email: ' . $form->email . "\n" . 'Message: ' . $form->message;

                mail(Yii::app()->params['email']['addresses']['contactForm'], 'Message from Contact Form', $body, $headers);
                Yii::app()->user->setFlash('contact', Yii::t('app', 'Thank you for contacting us! We will respond to you as soon as possible.'));
                $this->refresh(true, '#contact');
            }
        }

        $this->render('about', array('form' => $form));
    }

    public function actionSearch($query) {
        $query = preg_replace('/[^\p{L}]+/u', ' ', $query);
        $query = trim($query);
        $query = preg_replace('/\s+/', ' ', $query);

        if (3 > strlen($query)) {
            $this->render('search', array(
                'query' => $query,
                'errorMessage' => Yii::t('app', 'The search string must be atleast three characters long.'),
            ));

            Yii::app()->end();
         }

        $limit = 100;

        $results = array();

        // Users
        $criteria = new CDbCriteria();
        $criteria->addSearchCondition('first_name', $query, true, 'OR');
        $criteria->addSearchCondition('last_name', $query, true, 'OR');
        $criteria->addSearchCondition('description', $query, true, 'OR');
        $criteria->order = 'registration_time';
        $criteria->limit = $limit;

        $users = User::model()->findAll($criteria);

        foreach($users as $user) {
            $results[] = array(
                'name' => Yii::t('app', '{userName} – Registered @ {registrationTime}', array(
                    '{userName}' => $user->full_name,
                    '{registrationTime}' => $user->registration_time,
                )),
                'description' => $user->description,
                'url' => $this->createUrl('//user/view', array('id' => $user->id)),
                'time' => $user->registration_time,
            );
        }

        // Locations
        $criteria = new CDbCriteria();
        $criteria->addSearchCondition('name', $query, true, 'OR');
        $criteria->addSearchCondition('description', $query, true, 'OR');
        $criteria->order = 'creation_time';
        $criteria->limit = $limit;

        $locations = Location::model()->findAll($criteria);

        foreach($locations as $location) {
            $nameParams = array(
                '{placeName}' => $location->name,
                '{creationTime}' => $location->creation_time,
            );

            if ($location->createdByUser) {
                $nameParams['{userName}'] = $location->createdByUser->full_name;
                $name = Yii::t('app', '{placeName} tagged by {userName} @ {creationTime}', $nameParams);
            } else {
                $name = Yii::t('app', '{placeName} tagged @ {creationTime}', $nameParams);
            }

            $results[] = array(
                'name' => $name,
                'description' => $location->description,
                'url' => $this->createUrl('//location/view', array('id' => $location->id)),
                'time' => $location->creation_time,
            );
        }

        // Projects
        $criteria = new CDbCriteria();
        $criteria->addSearchCondition('name', $query, true, 'OR');
        $criteria->addSearchCondition('description', $query, true, 'OR');
        $criteria->order = 'creation_time';
        $criteria->limit = $limit;

        $projects = Project::model()->findAll($criteria);

        foreach($projects as $project) {
            $results[] = array(
                'name' => $project->name,
                'description' => $project->description,
                'url' => $this->createUrl('//location/view', array('id' => $project->location->id, '#' => $project->slug)),
                'time' => $project->creation_time,
            );
        }

        // Posts
        $criteria = new CDbCriteria();
        $criteria->addSearchCondition('text', $query, true, 'OR');
        $criteria->order = 'creation_time';
        $criteria->limit = $limit;

        $posts = Post::model()->findAll($criteria);

        foreach($posts as $post) {
            $nameParams = array(
                '{userName}' => $post->user->full_name,
                '{creationTime}' => $post->creation_time,
            );

            if ($post->feed->region) {
                $url = $this->createUrl('/resource/list', array('id' => $post->feed->region->id, '#' => 'post-' . $post->id));
                $nameParams['{feedName}'] = $post->feed->region->name;
            } else if ($post->feed->location) {
                $url = $this->createUrl('/location/view', array('id' => $post->feed->location->id, '#' => 'post-' . $post->id));
                $nameParams['{feedName}'] = $post->feed->location->name;
            } else if ($post->feed->project) {
                $url = $this->createUrl('/location/view', array('id' => $post->feed->project->location->id, '#' => 'post-' . $post->id));
                $nameParams['{feedName}'] = $post->feed->project->name;
            } else {
                continue;
            }

            if (Post::TEXT === $post->type) {
                $name = Yii::t('app', 'Posted to {feedName} by {userName} @ {creationTime}', $nameParams);
            } else if (Post::IMAGE == $post->type) {
                $name = Yii::t('app', 'Image added to {feedName} by {userName} @ {creationTime}', $nameParams);
            } else if (Post::RESOURCE === $post->type) {
                $nameParams['{resourceType}'] = ucfirst(Yii::t('app', $post->resource_type));
                $name = Yii::t('app', '{resourceType} added to {feedName} by {userName} @ {creationTime}', $nameParams);
            } else {
                continue;
            }

            $results[] = array(
                'name' => $name,
                'description' => $post->text,
                'url' => $url,
                'time' => $post->creation_time,
            );
        }

        // Sort by
        $results = __::sortBy($results, function($result) {
            return -strtotime($result['time']);
        });

        $this->render('search', array(
            'query' => $query,
            'results' => $results
        ));
    }

    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }
}
