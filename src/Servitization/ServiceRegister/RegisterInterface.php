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
 * Interface RegisterInterface
 * @package FastD\Servitization\ServiceRegister
 */
interface RegisterInterface
{
    /**
     * @param null $service
     * @return mixed
     */
    public function query($service = null);

    /**
     * @return mixed
     */
    public function publish();
}