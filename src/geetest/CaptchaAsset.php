<?php
namespace ethercap\captcha\geetest;

use Yii;
class CaptchaAsset extends \yii\web\AssetBundle
{
    public $js = [
        '//static.geetest.com/static/tools/gt.js',
        'js/gt-client.js',
    ];

    public $css = [
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init() {
        $this->sourcePath = __DIR__. "/assets";
        parent::init();
    }
}
