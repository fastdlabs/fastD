<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Servitization\ServiceRegister;


interface RegisterCenterInterface
{
    public function set($key, $hash, $value);

    public function get($key);
}