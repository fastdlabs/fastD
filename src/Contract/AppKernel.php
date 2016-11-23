<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Contract;

use FastD\Container\Container;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AppKernel
 *
 * @package FastD\Contract
 */
abstract class AppKernel
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var static
     */
    protected static $app;

    /**
     * @param array $bootstrap
     * @return static
     */
    public static function app(array $bootstrap = [])
    {
        if (null === static::$app) {
            static::$app = new static();
            static::$app->bootstrap($bootstrap);
        }

        return static::$app;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param array $bootstrap
     * @return mixed
     */
    abstract public function bootstrap(array $bootstrap);

    /**
     * @param ServerRequestInterface $serverRequest
     * @return mixed
     */
    abstract public function run(ServerRequestInterface $serverRequest);
}