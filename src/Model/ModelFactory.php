<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Model;

/**
 * Class ModelFactory.
 */
class ModelFactory
{
    /**
     * @var Model[]
     */
    protected static $models = [];

    /**
     * @param $name
     * @param $key
     *
     * @return Model
     */
    public static function createModel($name, $key = 'default')
    {
        if (!isset(static::$models[$name])) {
            $modelName = 'Model\\'.ucfirst($name).'Model';
            static::$models[$name] = new $modelName(database($key));
        }

        return static::$models[$name];
    }
}
