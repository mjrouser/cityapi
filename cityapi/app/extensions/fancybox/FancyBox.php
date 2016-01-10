<?php

class FancyBox extends CWidget
{
    public $id;
    public $target;
    public $easingEnabled = false;
    public $mouseEnabled=true;
    public $config = null;
    
    public function init() {
        if(!isset($this->id)) {
            $this->id = $this->getId();
        }

        $this->publishAssets();
    }
    
    public function run() {
        $config = CJavaScript::encode($this->config);
        Yii::app()->clientScript->registerScript($this->getId(), "$('$this->target').fancybox($config);\n");
    }

    public function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);

        if(is_dir($assets)) {
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.fancybox.pack.js', CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerCssFile($baseUrl . '/jquery.fancybox.css');

            if ($this->mouseEnabled) {
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.mousewheel.pack.js', CClientScript::POS_HEAD);
            }
            // if easing enbled register the js
            if ($this->easingEnabled) {
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.easing-1.3.pack.js', CClientScript::POS_HEAD);
            }
        } else {
            throw new Exception('FancyBox - Error: Couldn\'t find assets to publish.');
        }
    }
}