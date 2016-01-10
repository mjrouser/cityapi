<?php
/*
    login.php

    Copyright Stefan Fisk 2012.
*/

$this->pageTitle = Yii::t('app', 'Login');

?>

<h1><?php echo Yii::t('app', 'Login'); ?></h1>

<?php if(Yii::app()->user->hasFlash('auth')):?>
    <div class="info">
        <?php echo Yii::app()->user->getFlash('auth'); ?>
    </div>
<?php endif; ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'emailAddress'); ?>
        <?php echo $form->textField($model,'emailAddress'); ?>
        <?php echo $form->error($model,'emailAddress'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

    <div class="row persistent">
        <?php echo $form->checkBox($model,'persistent'); ?>
        <?php echo $form->label($model,'persistent'); ?>
        <?php echo $form->error($model,'persistent'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Login'); ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<div>
    <?php echo CHtml::link(Yii::t('app', 'Register'), array('register')); ?>
</div>
<div>
    <?php echo CHtml::link(Yii::t('app', 'Reset Password'), array('resetPassword')); ?>
</div>
<?php echo CHtml::button(Yii::t('app', 'Login via Facebook'), array('class' => 'login-via-facebook', 'onclick' => 'openFacebookLoginWindow();')); ?>
<div class="terms-of-service">
    <?php echo CHtml::link(Yii::t('app', 'Terms of Service'), array('/site/termsOfService'), array('target' => '_blank')); ?>
</div>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
    FB.init({
        appId: "<?php echo Yii::app()->params['facebook']['appId']; ?>",
        status: true,
        cookie: true,
        xfbml: true,
        oauth : true
    });
    function openFacebookLoginWindow() {
        var width = 500;
        var height = 300;
        var left = parseInt((screen.availWidth - width) / 2);
        var top = parseInt((screen.availHeight - height) / 2);

        <?php
            $facebook = new Facebook(Yii::app()->params['facebook']);
            $loginUrl = $facebook->getLoginUrl(array(
                'redirect_uri' => $this->createAbsoluteUrl('loginFacebook'),
                'display' => 'popup'
            ));
        ?>
        window.open("<?php echo $loginUrl ?>", "_blank", "width=" + width + ",height=" + height + ",status,resizable,left=" + left + ",top=" + top + "screenX=" + left + ",screenY=" + top + ",modal=yes,alwaysRaised=yes");
    }
</script>
