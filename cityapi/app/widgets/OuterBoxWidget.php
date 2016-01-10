<?
/*
    OuterBoxWidget.php

    Copyright Stefan Fisk 2012.
*/

class OuterBoxWidget extends CWidget {
    public $class;
    public $title;
    public $color;

    public function init() {
        ?>
        <div id="<?php echo $this->id; ?>" class="outer-box <?php echo $this->class; ?>">
            <header <?php if ($this->color) echo 'style="background: ' . $this->color . '"';?>>
                <h2><?php echo $this->title; ?></h2>
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
