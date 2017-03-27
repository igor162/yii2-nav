<?php

/**
 * Created by PhpStorm.
 * User: igor
 * Date: 12.03.17
 * Time: 22:41
 */

namespace igor162\nav;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Class NavX
 * @package dmstr\widgets\nav
 */

class Nav extends \kartik\nav\NavX
{
    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function renderItems()
    {
        $items = [];
        foreach ($this->items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                continue;
            }
            if (isset($item['small'])) {
                $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
                $small = $item['small'];
                $small = is_array($small) ? $small : ['label' => $small];
                $color = isset($small['color']) ? $small['color'] : "label-danger";
                $class = isset($small['class']) ? $small['class'] : "label pull-right";
                $class = $class.' '.$color;
                $item['label'] = ($encodeLabel ? Html::encode($item['label']) : $item['label']) . Html::tag("small", ArrayHelper::remove($small, 'label'), ["class" => $class]);
                $item['encode'] = false;
            }

            $items[] = $this->renderItem($item);
        }

        return Html::tag('ul', implode("\n", $items), $this->options);
    }

}
