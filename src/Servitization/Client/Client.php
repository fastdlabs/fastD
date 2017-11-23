<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Servitization\Client;


/**
 * Class Consumer
 * @package FastD\Servitization\Client
 */
class Client extends \FastD\Swoole\Client
{
    public function single()
    {}

    public function multi()
    {}

    public function async()
    {
    }

    public function promise(array $urls)
    {

    }
}