<?php
/*
    AuthController.php

    Copyright Stefan Fisk 2012.
*/

require_once('facebook/src/facebook.php');

class AuthController extends Controller {
    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array('register', 'login', 'loginFacebook', 'resetPassword'),
                'users' => array('*'),
            ),

            array(
                'allow',
                'actions' => array('logout'),
                'users' => array('@'),
            ),

            array(
                'deny',
            ),
        );
    }

    // Registration

    public function actionRegister() {
        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->returnUrl);
        }

        $form = new RegisterForm;

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'register-form') {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }

        if (!isset($_POST['RegisterForm'])) {
            $this->render('register', array('model' => $form));
            Yii::app()->end();
        }

        $form->attributes = $_POST['RegisterForm'];

        if (!$form->validate()) {
            $this->render('register', array('model' => $form));
            Yii::app()->end();
        }

        $passwordHasher = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);

        $newUser = new User;
        $newUser->first_name = $form->firstName;
        $newUser->last_name = $form->lastName;
        $newUser->email_address = $form->emailAddress;
        $newUser->password_hash = $passwordHasher->HashPassword($form->password);
        $newUser->status = User::ACTIVE;

        if (!$newUser->save()) {
            Yii::log('User::save() failed. $errors = ' . print_r($newUser->getErrors(), true), 'error', 'app.auth.register');
            $form->addError('save', 'Failed register the new user.');
            $this->render('register', array('model' => $form));
            Yii::app()->end();
        }

        $identity = new EmailUserIdentity($form->emailAddress, $form->password);

        if (!Yii::app()->user->login($identity)) {
            Yii::log('Failed to login user. $user->id = ' . $user->id, 'error', 'app.auth.verify');
            throw new CHttpException(500);
        }

        $this->redirect(Yii::app()->user->returnUrl);
    }

    // Authentication

    public function actionLogin() {
        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->returnUrl);
        }

        $returnUrlQuery = parse_url(Yii::app()->user->returnUrl, PHP_URL_QUERY);
        $lightbox = (false !== strpos($returnUrlQuery, 'lightbox=1'));

        if ($lightbox) {
            Yii::app()->user->returnUrl = null;
            $this->render('lightboxLoginRequired');
            Yii::app()->end();
        }

        $form = new LoginForm;

        if (isset($_POST['ajax']) && 'login-form' === $_POST['ajax']) {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }

        if (!isset($_POST['LoginForm'])) {
            $this->render('login', array('model' => $form));
            Yii::app()->end();
        }


        $form->attributes = $_POST['LoginForm'];

        if (!$form->validate()) {
            $this->render('login', array('model' => $form));
            Yii::app()->end();
        }

        $identity = new EmailUserIdentity($form->emailAddress, $form->password);
        $identity->authenticate();

        $errorCode = $identity->errorCode;

        if (EmailUserIdentity::ERROR_DISABLED === $errorCode) {
            $this->render('disabled', array('user' => User::model()->findByAttributes(array('email_address' => $form->emailAddress))));
            Yii::app()->end();
        } else if (EmailUserIdentity::ERROR_NOT_FOUND === $errorCode || EmailUserIdentity::ERROR_PASSWORD_INVALID === $errorCode) {
            $form->addError('password', 'Incorrect email address or password.');
        } else if (EmailUserIdentity::ERROR_NONE !== $errorCode) {
            Yii::log('EmailUserIdentity::authenticate() return an unknown error code. $errorCode = ' . $errorCode, 'error', 'app.auth.login');
            throw new CHttpException(500);
        } else {
            Yii::app()->user->login($identity, $form->persistent ? Yii::app()->params['authCookieDuration'] : 0);
            $this->redirect(Yii::app()->user->returnUrl);
        }

        $this->render('login', array('model' => $form));
    }

    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionLoginFacebook($code = null) {
        if (null === $code) {
            $this->renderPartial('facebook');
            Yii::app()->end();
        }

        $facebook = new Facebook(Yii::app()->params['facebook']);

        $profile = $facebook->api('me');

        $user = User::model()->findByAttributes(array('facebook_profile_id' => $profile['id']));

        if (!$user) {
            $user = new User();

            $user->first_name = $profile['first_name'];
            $user->last_name = $profile['last_name'];
            $user->facebook_profile_id = $profile['id'];
            $user->status = User::ACTIVE;

            if (!$user->save()) {
                Yii::log('User::save() failed. $errors = ' . print_r($user->getErrors(), true), 'error', 'app.auth.facebook');
                throw new CHttpException(500);
            }

            $user->setFacebookPicture();
        } else if (User::DISABLED === $user->status) {
            $this->render('disabled', array('user' => $user));
            Yii::app()->end();
        }

        $identity = new FacebookUserIdentity($user);
        assert($identity->authenticate());

        Yii::app()->user->login($identity);

        $this->renderPartial('facebook', array('returnUrl' => Yii::app()->user->returnUrl));
    }

    public function actionResetPassword($id = null, $key = null) {
        if ((null === $id) ^ (null === $key)) {
            throw new CHttpException(400);
        }

        if (null === $id) {
            $form = new ResetPasswordEmailForm;

            if (isset($_POST['ajax'])) {
                echo CActiveForm::validate($form);
                Yii::app()->end();
            }

            if (!isset($_POST['ResetPasswordEmailForm'])) {
                $this->render('resetPasswordEmail', array('model' => $form));
                Yii::app()->end();
            }

            $form->attributes = $_POST['ResetPasswordEmailForm'];

            if (!$form->validate()) {
                $this->render('resetPasswordEmail', array('model' => $form));
                Yii::app()->end();
            }

            $user = User::model()->findByAttributes(array('email_address' => $form->emailAddress));

            if ($user) {
                $user->password_reset_key = sha1(rand(0, 1000));

                $url = $this->createAbsoluteUrl(
                    'resetPassword',
                    array(
                        'id' => $user->id,
                        'key' => $user->password_reset_key
                    )
                );

                $fromEmailAddress = Yii::app()->params['email']['addresses']['noReply'];
                $toEmailAddress = $user->email_address;

                $headers="From: {$fromEmailAddress}\r\nReply-To: {$fromEmailAddress}";
                $subject = Yii::t(
                    'app',
                    '{appName} – Password Reset {emailAddress}',
                    array(
                        '{appName}' => Yii::app()->name,
                        '{emailAddress}' => $user->email_address
                    )
                );
                $body = Yii::t(
                    'app',
                    "Hey {name}\n\nTo reset your password, please follow this link: {url}\n\nIf you did not request a password reset you can ignore this email.",
                    array(
                        '{name}' => $user->first_name,
                        '{url}' => $url
                    )
                );

                mail($toEmailAddress, $subject, $body, $headers);

                $user->save();
            }

            $this->render('resetPasswordEmailSent', array('model' => $form));
            Yii::app()->end();
        }

        $user = User::model()->findByPk($id);

        if (!$user || $key !== $user->password_reset_key) {
            throw new CHttpException(400);
        }

        $form = new ResetPasswordForm;

        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }

        if (!isset($_POST['ResetPasswordForm'])) {
            $this->render('resetPassword', array('model' => $form));
            Yii::app()->end();
        }

        $form->attributes = $_POST['ResetPasswordForm'];

        if (!$form->validate()) {
            $this->render('resetPassword', array('model' => $form));
            Yii::app()->end();
        }

        $passwordHasher = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);

        $user->password_hash = $passwordHasher->HashPassword($form->password);
        $user->password_reset_key = null;

        if (!$user->save()) {
            Yii::log('User::save() failed. $errors = ' . print_r($user->getErrors(), true), 'error', 'app.auth.resetPassword');
            $form->addError('save', 'Failed register the new user.');
            $this->render('resetPassword', array('model' => $form));
            Yii::app()->end();
        }

        Yii::app()->user->setFlash('auth', Yii::t('app', 'Your password has been reset! You can not login using your new password.'));
        $this->redirect('login');
    }

    public function loadUser($id) {
        $model = User::model()->findByPk($id);

        if ($model === null) {
            throw new CHttpException(404,'The requested page does not exist.');
        }

        return $model;
    }
}
