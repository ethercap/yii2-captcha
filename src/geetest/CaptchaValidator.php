<?php
namespace ethercap\captcha\geetest;

use Yii;

class CaptchaValidator extends \yii\validators\Validator
{
    public $lib = "geetest";

    public $error;

    public $skipOnEmpty = false;

    public $enableClientValidation = false;

    public function init()
    {
        parent::init();
        if ($this->error === null) {
            $this->error = "验证码错误，请重试";
        }
    }

    protected function validateValue($value)
    {
        $result = Yii::$app->{$this->lib}->check($value);
        if (!$result) {
            return [$this->error, []];
        }
        return null;
    }
}
