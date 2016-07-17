<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/26
 * Time: 下午11:16
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * sf: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Standard;

use FastD\Container\ContainerAware;
use ReflectionClass;
use FastD\Config\Config;

/**
 * Class Bundle
 *
 * @package FastD\Standard
 */
class Bundle
{
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
     * Constructs a ReflectionClass
     *
     * @link  http://php.net/manual/en/reflectionclass.construct.php
     * @since 5.0
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
    public function getPath()
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
}