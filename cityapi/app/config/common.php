<?php
/*
    common.php

    Copyright Stefan Fisk 2012.
*/

require_once(dirname(__FILE__) . '/../components/helpers.php');

return CMap::mergeArray(
    array(
        'homeUrl' => array('site/index'),
        'basePath' => dirname(__FILE__) . '/..',
        'name' => 'CityAPI',

        'sourceLanguage' => 'en',

        'preload' => array('log'),

        'import' => array(
            'application.models.*',
            'application.components.*',
            'application.extensions.*',
            'application.extensions.EGMap.*',
            'application.extensions.EWideImage.*',
        ),

        'modules' => array(
            'blog',
            'admin',
            /*
            'gii' => array(
                'class' => 'system.gii.GiiModule',
                'password' => 'Enter Your Password Here',
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters' => array('127.0.0.1','::1'),
            ),
            */
        ),

        'components' => array(
            'user' => array(
                'class' => 'WebUser',
                'allowAutoLogin' => true,
                'loginUrl' => array('auth/login'),
                'logoutUrl' => array('auth/logout')
            ),
            'authManager' =>  array(
                'class' => 'CPhpAuthManager',
                'authFile' => dirname(__FILE__) . '/../data/auth.php',
                'showErrors' => true,
                'defaultRoles' => array('authenticated', 'admin'),
            ),
            'cache' => array(
                'class'=>'system.caching.CDbCache',
                'connectionID' => 'db',
            ),
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning',
                    ),
                ),
            ),
           'urlManager' => array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                'rules' => array(
                    '' => 'site/index',
                    '<action:(register|login|logout|resetPassword)>' => 'auth/<action>',
                    '<action:(about|help|search)>' => 'site/<action>',
                    'user/<id:\d+>' => 'user/view',
                    'blog' => 'blog/default/index',
                    'blog/<id:\d+>' => 'blog/default/view',
                    array(
                        'class' => 'application.components.MapUrlRule',
                    ),
                    array(
                        'class' => 'application.components.SpacesUrlRule',
                    ),
                    array(
                        'class' => 'application.components.ResourcesUrlRule',
                    ),
                    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                    '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
                ),
            ),
            'errorHandler' => array(
                'errorAction' => 'site/error',
            ),
            'localTime' => array(
                'class' => 'LocalTime'
            ),
         ),

        'params' => array(
            'titleSeparator' => ' | ',
            'authCookieDuration' => 2592000, // 30 days
            'translate' => array(
                'languagesExpire' => 3600, // 1 hour
                'translationExpire' => 86400, // 1 day
            ),
            'phpass' => array(
                'iteration_count_log2' => 8,
                'portable_hashes' => false,
            ),
            'images' => array(
                'maxWidth' => 1024,
                'maxHeight' => 1024,
                'defaultCompression' => 80,
                'basePath' => 'upload' . DIRECTORY_SEPARATOR . 'images',
            ),
            'feed' => array(
                'postContentWidth' => 794,
                'postContentHeight' => 150,
            ),
            'userProfile' => array(
                'numberOfRecentContributions' => 8,
            ),
            'microsoft' => array(
                'azure' => array(
                    'clientId' => 'cityapi',
                    'clientSecret' => 'i+hwRfhEMpCXGFHJk6jW+5hzJ5yxarUgfwnzX+QEX1k=',
                ),
            ),
        ),
    ),
    require(dirname(__FILE__) . '/installation.php')
);
