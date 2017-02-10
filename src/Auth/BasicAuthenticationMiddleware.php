<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Auth;

use FastD\Http\JsonResponse;
use FastD\Http\Response;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\ServerMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Middleware\HttpBasicAuthentication;

/**
 * Class BasicAuthenticationMiddleware
 * @package FastD\Auth
 */
class BasicAuthenticationMiddleware extends ServerMiddleware
{
    public $defaultJson = [
        'msg' => 'not allow access',
        'code' => 401
    ];

    /**
     * @param ServerRequestInterface $serverRequest
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $serverRequest, DelegateInterface $delegate)
    {
        $options = config()->get('basic.auth', []);

        $auth = new HttpBasicAuthentication($options);

        return $auth($serverRequest, json(isset($options['json']) ? $options['json'] : $this->defaultJson), $delegate);
    }
}