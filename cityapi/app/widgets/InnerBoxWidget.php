<?
/*
    InnerBoxWidget.php

    Copyright Stefan Fisk 2012.
*/

class InnerBoxWidget extends CWidget {
    public $class;
    public $title;
    public $color = null;

    public function init() {
        ?>
        <div id="<?php echo $this->id; ?>" class="inner-box <?php echo $this->class; ?>">
            <header <?php if ($this->color) echo 'style="background: ' . $this->color . '"';?>>
                <h3><?php echo $this->title; ?></h3>
                <div class="shadow">
                    <div class="left"></div>
                    <div class="middle"></div>
                    <div class="right"></div>
                </div>
            </header>
            <div class="content">
        <?php
    }

    public function run(){
        ?>
            </div>
        </div>
        <?php
    }
}
