<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\web;

use Yii;
use yii\base\Object;

/**
 * CompositeUrlRule represents a collection of related URL rules.
 *
 * These URL rules are typically created for a common purpose (e.g. RESTful API for a resource).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
abstract class CompositeUrlRule extends Object implements UrlRuleInterface
{
    /**
     * @var UrlRuleInterface[] the URL rules contained in this composite rule.
     * This property is set in [[init()]] by the return value of [[createRules()]].
     */
    protected $rules = [];

    /**
     * Creates the URL rules that should be contained within this composite rule.
     * @return UrlRuleInterface[] the URL rules
     */
    abstract protected function createRules();

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->rules = $this->createRules();
    }

    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        foreach ($this->rules as $rule) {
            /** @var \yii\web\UrlRule $rule */
            if (($result = $rule->parseRequest($manager, $request)) !== false) {
                Yii::trace("Request parsed with URL rule: {$rule->name}", __METHOD__);

                return $result;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        foreach ($this->rules as $rule) {
            /** @var \yii\web\UrlRule $rule */
            if (($url = $rule->createUrl($manager, $route, $params)) !== false) {
                return $url;
            }
        }

        return false;
    }
}
