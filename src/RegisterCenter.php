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

/**
 * Class RegisterCenter
 * @package FastD
 */
class RegisterCenter
{
    /**
     * @var Register
     */
    protected $register;

    /**
     * RegisterCenter constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->register = new Register(new RedisRegisterCenter());
    }

    /**
     *
     */
    public function start()
    {
        $this->register->start();
    }
}