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
     * @var string
     */
    protected $appPath;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var static
     */
    public static $app;

    /**
     * AppKernel constructor.
     *
     * @param $appPath
     */
    public function __construct($appPath)
    {
        $this->appPath = $appPath;

        $this->bootstrap();
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return mixed
     */
    abstract public function bootstrap();

    /**
     * @param ServerRequestInterface $serverRequest
     * @return mixed
     */
    abstract public function run(ServerRequestInterface $serverRequest);
}