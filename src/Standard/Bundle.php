<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Standard;

use ReflectionClass;

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
}