<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Servitization\ServiceRegister;


use FastD\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RedisRegisterCenter
 * @package FastD\Servitization\ServiceRegister
 */
class RedisRegisterCenter implements RegisterCenterInterface
{
    protected $redis;

    public function __construct()
    {
        $host = config()->get('rpc.register.host', '0.0.0.0:9555');
    }

    public function set()
    {
        // TODO: Implement set() method.
    }

    public function get()
    {
        // TODO: Implement get() method.
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return Response
     */
    public function doRequest(ServerRequestInterface $serverRequest)
    {
        // TODO: Implement doRequest() method.
    }
}