<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;


use FastD\Servitization\ServiceRegister\RedisRegisterCenter;
use FastD\Servitization\ServiceRegister\Register;

class RegisterCenter
{
    protected $register;

    public function __construct(Application $application)
    {
        $this->register = new Register(new RedisRegisterCenter());
    }

    public function start()
    {
        $this->register->start();
    }
}