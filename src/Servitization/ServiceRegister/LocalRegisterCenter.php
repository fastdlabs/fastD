<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Servitization\ServiceRegister;


class LocalRegisterCenter implements RegisterCenterInterface
{
    public function __construct()
    {

    }

    /**
     * @param $key
     * @param $hash
     * @param $value
     * @return mixed
     */
    public function set($key, $hash, $value)
    {
        // TODO: Implement set() method.
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        // TODO: Implement get() method.
    }
}