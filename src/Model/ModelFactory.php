<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Model;


/**
 * Class ModelFactory
 * @package FastD\ServiceProvider
 */
class ModelFactory
{
    /**
     * @var Model[]
     */
    protected static $models = [];

    /**
     * @param $name
     * @return Model
     */
    public static function createModel($name)
    {
        if (!isset(static::$models[$name])) {
            $modelName = 'Model\\' . ucfirst($name) . 'Model';
            static::$models[$name] = new $modelName(database());
        }

        return static::$models[$name];
    }
}