<?php
namespace ethercap\captcha\geetest;

use Yii;
use yii\base\Action;
use yii\web\Response;

class PreprocessAction extends Action
{
    public $lib = 'geetest';

    public function run()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Yii::$app->{$this->lib}->getPreData();
    }
}
