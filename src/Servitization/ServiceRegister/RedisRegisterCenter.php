<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Servitization\ServiceRegister;


class RedisRegisterCenter implements RegisterCenterInterface
{
    protected $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    public function set()
    {
        // TODO: Implement set() method.
    }

    public function get()
    {
        // TODO: Implement get() method.
    }
}