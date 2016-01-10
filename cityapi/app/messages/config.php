<?php
/**
 * This is the configuration for generating message translations
 * for the Yii framework. It is used by the 'yiic message' command.
 */

return array(
    // source Path to start recursive seach
    'sourcePath' => dirname(__FILE__)  . DIRECTORY_SEPARATOR . '..',

    // messages folder Path
    'messagePath' => dirname(__FILE__),

    // languages you want to translate the whole site into
    'languages' => array('se'),

    // specify a file types to look into
    'fileTypes' => array('php'),

    // function search key
    'translator' => 'Yii::t',

    // if you do not need to exclude any file or folders you can remove it ..
    // this is only example and you should modify it to suite your needs.
    'exclude' => array(
        '.git',
        '/index.php',
        'yiic.php',
        '/assets',
        '/blog',
        '/css',
        '/images',
        '/theme',
    ),
);
