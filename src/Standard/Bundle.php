<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Standard;

use FastD\Annotation\Annotation;
use FastD\Container\ContainerAware;
use ReflectionClass;

/**
 * Class Bundle
 *
 * @package FastD\Standard
 */
abstract class Bundle
{
    use ContainerAware;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $shortName;

    /**
     * Bundle constructor.
     */
    public function __construct()
    {
        $reflection = new ReflectionClass($this);

        $this->path = dirname($reflection->getFileName());

        $this->namespace = $reflection->getNamespaceName();

        $this->name = $reflection->getName();

        $this->shortName = $reflection->getShortName();

        unset($reflection);
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get namespace. Alias getNamespaceName
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @return void
     */
    public function setUp()
    {
        $this->setUpConfiguration();
        $this->setUpRoutes();
    }

    /**
     * @return void
     */
    protected function setUpRoutes()
    {
        $routing = $this->container->singleton('kernel.routing');

        $namespace = $this->getNamespace() . '\\Http\\Controllers\\';

        if (false !== ($files = glob($this->getDir() . '/Http/Controllers/*.php', GLOB_NOSORT | GLOB_NOESCAPE))) {
            foreach ($files as $file) {
                $className = $namespace . pathinfo($file, PATHINFO_FILENAME);
                if (class_exists($className)) {
                    $annotation = new Annotation($className);
                    $annotation->execute([
                        'route' => function ($class, $method, $args) use ($routing) {
                            $routing->addRoute(
                                isset($args['method']) ? $args['method'] : 'ANY',
                                $args[0],
                                [$class, $method],
                                isset($args['name']) ? $args['name'] : $args[0],
                                isset($args['defaults']) ? $args['defaults'] : []
                            );
                        },
                    ]);
                    unset($annotation);
                }
            }
        }
    }

    /**
     * @return void
     */
    protected function setUpConfiguration()
    {
        $env = $this->container->singleton('kernel')->getEnvironment();

        if (file_exists($file = $this->getDir() . '/Resources/config/config_' . $env . '.php')) {
            $this->container->singleton('kernel.config')->load($file);
            unset($file);
        }
    }
}