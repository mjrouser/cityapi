<?php
/*
    _innerBox.php

    Copyright Stefan Fisk 2012.
*/

/* @var $id string */
/* @var $class string */
/* @var $title string */
/* @var $content string*/
/* @var $buttons string */

if (!isset($id)) $id = '';
if (!isset($class)) $class = '';
?>

<div id="<?php echo $id; ?>" class="inner-box <?php echo $class; ?>">
    <header>

        <h3><?php echo $title; ?></h3>

        <div class="buttons">
            <?php if (isset($buttons)) echo $buttons ?>
        </div>

        <div class="shadow">
            <div class="left"></div>
            <div class="middle"></div>
            <div class="right"></div>
        </div>

    </header>

    <div class="content">
        <?php echo $content; ?>
    </div>

</div>
