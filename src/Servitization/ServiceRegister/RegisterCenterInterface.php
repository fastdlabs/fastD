<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Servitization\ServiceRegister;


/**
 * Interface RegisterCenterInterface
 * @package FastD\Servitization\ServiceRegister
 */
interface RegisterCenterInterface
{
    /**
     * @param $key
     * @param $hash
     * @param $value
     * @return mixed
     */
    public function set($key, $hash, $value);

    /**
     * @param $key
     * @return mixed
     */
    public function get($key);
}