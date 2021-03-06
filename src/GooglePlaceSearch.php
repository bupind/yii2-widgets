<?php
/**
 * @link https://github.com/webtoolsnz/yii2-widgets
 * @copyright Copyright (c) 2015 Webtools Ltd
 */

namespace webtoolsnz\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;

/**
 * Class GooglePlaceSearch
 * @author Byron Adams <byron@webtools.co.nz>
 * @package webtoolsnz\widgets
 */
class GooglePlaceSearch extends InputWidget
{
    public $key;

    public $clientOptions = [];

    public $options = [];

    public $country = 'nz';

    public $map = ['selector' => null];

    public function init()
    {
        parent::init();

        $this->clientOptions = ArrayHelper::merge([
            'country' => $this->country,
            'map' => $this->map,
            'autocomplete' => [
                'componentRestrictions' => ['country' => $this->country],
            ],


        ], $this->clientOptions);

        Html::addCssClass($this->options, 'form-control');
    }

    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textInput($this->name, $this->value, $this->options);
        }

        $this->registerClientScript();
    }

    protected function registerClientScript()
    {
        $view = $this->getView();
        $key = $this->key ? $this->key : ArrayHelper::getValue(Yii::$app->params, 'google.api.key', null);
        GooglePlaceSearchAsset::$apiKey = $key;
        GooglePlaceSearchAsset::register($view);

        $id = $this->options['id'];
        $options = Json::encode($this->clientOptions);

        $view->registerJs("jQuery('#$id').googlePlaceSearch($options);");
    }
}
