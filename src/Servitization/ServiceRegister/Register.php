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
 * Class Register
 * @package FastD\Servitization\ServiceRegister
 */
class Register implements RegisterInterface
{
    /**
     * @var RegisterCenterInterface
     */
    protected $registerCenter;

    /**
     * Register constructor.
     * @param RegisterCenterInterface $registerCenter
     */
    public function __construct(RegisterCenterInterface $registerCenter)
    {
        $this->registerCenter = $registerCenter;
    }

    /**
     * @param null $service
     * @return mixed
     */
    public function query($service = null)
    {

    }

    /**
     * @return mixed
     */
    public function publish()
    {

    }
}