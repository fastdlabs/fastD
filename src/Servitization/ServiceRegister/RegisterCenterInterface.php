<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Servitization\ServiceRegister;

/**
 * Interface RegisterCenterInterface.
 */
interface RegisterCenterInterface
{
    /**
     * @param $key
     * @param $hash
     * @param $value
     *
     * @return mixed
     */
    public function set($key, $hash, $value);

    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key);
}
