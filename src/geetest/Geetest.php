<?php
namespace ethercap\captcha\geetest;

use Yii;
use GeetestLib;
use yii\helpers\ArrayHelper;

class Geetest extends \yii\base\Component
{
    public $gtId;

    public $gtKey;

    protected $sdk;

    public function init()
    {
        $this->sdk = new GeetestLib($this->gtId, $this->gtKey);
        parent::init();
    }

    public function getConfig()
    {
        return [
            "user_id" => ArrayHelper::getValue(Yii::$app, 'user.id'),
            "client_type" => 'h5',
            "ip_address" => Yii::$app->request->userIP,
        ];
    }

    public function getPreData() {
        $status = $this->sdk->pre_process($this->config, 1);
        $session = Yii::$app->getSession();
        $session->open();
        $session['gtserver'] = $status;
        return $this->sdk->get_response();
    }

    public function checkCaptcha($challenge, $validate, $seccode) {
        $session = Yii::$app->getSession();
        $session->open();
        if ($session['gtserver'] == 1) {
            return $this->sdk->success_validate($challenge, $validate, $seccode, $this->config);
        } else {
            return $this->sdk->fail_validate($challenge, $validate, $seccode, $this->config);
        }
    }

    public function check($value) {
        $data = json_decode($value, true);
        $challenge = ArrayHelper::getValue($data, 'challenge');
        $validate = ArrayHelper::getValue($data, 'validate');
        $seccode = ArrayHelper::getValue($data, 'seccode');
        return $this->checkCaptcha($challenge, $validate, $seccode);
    }
}
