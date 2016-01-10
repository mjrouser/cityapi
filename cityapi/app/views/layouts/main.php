<?php
/*
    main.php

    Copyright Stefan Fisk 2012.
*/

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('/scripts/anchor-margin.js');

?><!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="language" content="en" />

        <link rel="shortcut icon" href="<?php echo Yii::app()->baseUrl; ?>/images/favicon.ico">

        <meta name="viewport" content="width=device-width">

        <?php $this->renderPartial('//layouts/_javascriptVariables'); ?>

        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/modernizr.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/styles/main.min.css" />

        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-38640389-1']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>

        <title><?php echo CHtml::encode(implode(Yii::app()->params['titleSeparator'], array($this->pageTitle, $this->currentRegion->name, Yii::app()->name))); ?></title>
    </head>
    <body class="layout-<?php echo end(explode('/', $this->layout)) . ' ' . CHtml::encode(str_replace('/', '-', trim($this->route, '/'))) . ' controller-' . $this->getUniqueId() . ' action-' . $this->getAction()->getId() ?>">
        <div class="container">
            <div class="sticky-footer-wrapper">
                <header>
                    <?php echo CHtml::link('', array('//site/index'), array('class' => 'logo')); ?>
                    <nav>
                        <?php echo CHtml::link(Yii::t('app', 'About'), array('//site/about')); ?>
                        <?php echo CHtml::link(Yii::t('app', 'Help'), array('//site/help')); ?>
                    </nav>
                    <div class="region">
                        <?php echo CHtml::link(CHtml::encode($this->currentRegion->name), array('//location/map', 'id' => $this->currentRegion->id)); ?>
                        <div class="list <?php if (0 === count($this->nonCurrentRegions)) echo 'empty'; ?>">
                            <div class="arrow"></div>
                            <ul>
                                <?php foreach($this->nonCurrentRegions as $nonCurrentRegion): ?>
                                    <li><?php echo CHtml::link(CHtml::encode($nonCurrentRegion->name), array('//location/map', 'id' => $nonCurrentRegion->id)); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <?php echo CHtml::beginForm(array('site/search'), 'get'); ?>
                        <input type="search" placeholder="Search" name="query" value="<?php echo isset($_GET['query']) ? CHtml::encode($_GET['query']) : ''; ?>" />
                    <?php echo CHtml::endForm(); ?>
                    <div class="user">
                        <?php if (Yii::app()->user->isGuest): ?>
                            <?php echo CHtml::link(Yii::t('app', 'Login'), array('//auth/login')); ?>
                        <?php else: ?>
                            <span class="name"><?php echo Yii::t('app', 'Welcome, {name}!', array( '{name}' => CHtml::link(CHtml::encode(Yii::app()->user->name), array('//user/view', 'id' => Yii::app()->user->id)))); ?></span>
                            <?php
                            $countOfUnviewed = Notification::model()->countOfUnviewed();

                            if (0 != $countOfUnviewed) {
                                ?>
                                <span class="notification-count"><?php echo CHtml::link($countOfUnviewed, array('/user/view', 'id' => Yii::app()->user->id, '#' => 'notifications')); ?></span>
                                <?php
                            }
                            ?>
                            <?php echo CHtml::link(Yii::t('app', 'Logout'), array('//auth/logout'), array('class' => 'logout')); ?>
                        <?php endif; ?>
                    </div>
                    <?php $this->widget('application.widgets.LanguageSelector'); ?>
                </header>
                <div class="main" role="main">
                    <?php echo $content; ?>
                </div>
            </div>
            <footer>
                <?php if (isset($this->currentRegion)) echo $this->currentRegion->footer; ?>
            </footer>
        </div>
    </body>
</html>
