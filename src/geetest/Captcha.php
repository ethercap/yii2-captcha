<?php
namespace ethercap\captcha\geetest;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;

class Captcha extends InputWidget
{
    const TYPE_FLOAT = 'float';
    const TYPE_POPUP = 'popup';
    const TYPE_BIND = 'bind';
    const TYPE_CUSTOM = 'custom';

    public $lib = 'geetest';

    public $type = self::TYPE_POPUP;

    public $bindTo;

    public $options = [];

    public $inputOptions = [];

    public $onReady;

    public $onSuccess;

    public $onError;

    public $onClose;

    public $autoInit = true;

    /**
     * @see http://docs.geetest.com/install/client/web-front/ for details
     */
    public $gtOptions = [
        'width' => '100%',
        'product' => self::TYPE_POPUP,
    ];

    public $template = '{image} {input}';

    public function init()
    {
        if (!isset($this->inputOptions['id'])) {
            $this->inputOptions['id'] = $this->getId();
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->inputOptions['id']. 'div';
        }
        $this->gtOptions['product'] = $this->type;
        parent::init();
    }

    public function run()
    {
        $this->registerClientScript();
        if ($this->hasModel()) {
            $input = Html::activeHiddenInput($this->model, $this->attribute, $this->inputOptions);
        } else {
            $input = Html::hiddenInput($this->name, $this->value, $this->inputOptions);
        }
        if ($this->type == self::TYPE_BIND) {
            $image = '';
        } else {
            $image = Html::tag('div', '', $this->options);
        }
        echo strtr($this->template, [
            '{input}' => $input,
            '{image}' => $image,
        ]);
    }

    public function registerClientScript()
    {
        CaptchaAsset::register($this->view);
        $options = $this->getClientOptions();
        $options = empty($options) ? '' : Json::htmlEncode($options);
        $js = '';
        $this->onReady && $js .= ".onReady({$this->onReady})";
        $this->onSuccess && $js .= ".onSuccess({$this->onSuccess})";
        $this->onError && $js .= ".onError({$this->onError})";
        $this->onClose && $js .= ".onClose({$this->onClose})";

        $this->view->registerJs(
            "window.geetest.option = {$options};"
            . ($js ? "window.geetest{$js};" : "")
            . ($this->autoInit? "window.geetest.init();" : "")
        );
    }

    public function getClientOptions()
    {
        $options = [
            'type' => $this->type,
            'inputTo' => $this->inputOptions['id'],
            'options' => $this->getGtOptions(),
        ];
        if ($this->type == self::TYPE_BIND) {
            $options['bindTo'] = $this->bindTo;
        } else {
            $options['appendTo'] = $this->options['id'];
        }
        return $options;
    }

    public function getGtOptions()
    {
        $preData = Yii::$app->{$this->lib}->getPreData();
        $options = [
            'gt' =>  ArrayHelper::getValue($preData, 'gt', ''),
            'challenge' => ArrayHelper::getValue($preData, 'challenge', ''),
            'offline' => !ArrayHelper::getValue($preData, 'success', false),
            'new_captcha' => ArrayHelper::getValue($preData, 'new_captcha', false),
        ];
        return ArrayHelper::merge($options, $this->gtOptions);
    }
}
