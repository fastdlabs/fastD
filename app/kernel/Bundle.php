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

namespace Kernel;

/**
 * Class Bundle
 * @package Dobee\Kernel\Framework\Bundles
 */
class Bundle
{
    /**
     * @var string
     */
    private $rootPath;

    /**
     * Constructor. Initialize bundle and reflection self class.
     */
    public function __construct()
    {
        $this->reflection = new \ReflectionClass($this);
    }

    /**
     * @return string
     */
    public function getRootPath()
    {
        if (null === $this->rootPath) {
            $this->rootPath = dirname($this->reflection->getFileName());
        }

        return $this->rootPath;
    }

    /**
     * @return string
     */
    public function getControllerPath()
    {
        return $this->getRootPath() . '/Controllers';
    }

    /**
     * @return string
     */
    public function getRepositoryPath()
    {
        return $this->getRootPath() . '/Repository';
    }

    /**
     * @return string
     */
    public function getResourcesPath()
    {
        return $this->getRootPath() . '/Resources';
    }

    /**
     * @return string
     */
    public function getConfigurationPath()
    {
        return $this->getRootPath() . '/Resources/config';
    }

    /**
     * @return string
     */
    public function getViewsPath()
    {
        return $this->getRootPath() . '/Resources/views';
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->reflection->getNamespaceName();
    }

    public function getShortName()
    {
        return $this->reflection->getShortName();
    }

    public function getName()
    {
        return $this->reflection->getName();
    }
}