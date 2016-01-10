<?php
/*
    installation.php

    Copyright Stefan Fisk 2012.
*/

return array(
    'components'=>array(
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=city_api',
            'emulatePrepare' => true,
            'username' => 'city_api',
            'password' => 'qspcuTMSJCwWtrBZ',
            'charset' => 'utf8',
            'tablePrefix' => '',
        ),
    ),
    'params' => array(
        'email' => array(
            'addresses' => array(
                'noReply' => 'no-reply@cityapi.dyn.stefanfisk.com',
                'contactForm' => 'contact@stefanfisk.com',
            ),
        ),
        'facebook' => array(
            'appId' => '276810295763193',
            'secret' => 'e1d8f578fd5051489cd99af162d91a0c'
        ),
    ),
);
