<?php
/*
    main.php

    Copyright Stefan Fisk 2012.
*/

return CMap::mergeArray(
    require(dirname(__FILE__) . '/common.php'),
    array(
        'behaviors' => array(
            'ApplicationConfigBehavior'
        ),
    )
);
