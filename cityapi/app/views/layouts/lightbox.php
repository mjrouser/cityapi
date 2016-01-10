<?php
/*
    lightbox.php

    Copyright Stefan Fisk 2012.
*/
?><!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="language" content="en" />

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
        <?php echo $content; ?>
    </body>
</html>
