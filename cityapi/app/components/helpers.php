<?php
/*
    helpers.php

    Copyright Stefan Fisk 2012.
*/

function slugify($string) {
    $string = preg_replace('~[^\\pL\d]+~u', '-', $string);
    $string = trim($string, '-');
    $string = toASCII($string);
    $string = strtolower($string);
    $string = preg_replace('~[^-\w]+~', '', $string);

    if (empty($string)) {
        return null;
    }

    return $string;
}

function toASCII($string) {
    return strtr(
        utf8_decode($string),
        utf8_decode('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
        'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
}
