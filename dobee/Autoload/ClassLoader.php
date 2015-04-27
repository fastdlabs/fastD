<?php
/**
 * Created by PhpStorm.
 * User: JanHuang
 * Date: 2015/3/14
 * Time: 21:22
 * Email: bboyjanhuang@gmail.com
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace Dobee\Autoload;

/**
 * Class ClassLoader
 *
 * @package Dobee\Compiler
 */
class ClassLoader
{
    /**
     * @var self
     */
    protected static $loader;

    /**
     * @var array
     */
    protected $classMaps = array();

    /**
     * @var array
     */
    protected $loadClasses = array();

    /**
     * @param array $maps
     * @return ClassLoader
     */
    public static function getLoader(array $maps = array())
    {
        if (null === self::$loader) {
            self::$loader = new self($maps);
        }

        self::$loader->registerClassLoader(true);

        return self::$loader;
    }

    /**
     * @param array $maps
     */
    protected function __construct(array $maps = array())
    {
        $this->classMaps = $maps;
    }

    /**
     * @return array
     */
    public function getLoadClasses()
    {
        return $this->loadClasses;
    }

    /**
     * @param array $maps
     * @return $this
     */
    public function setClassMaps(array $maps = array())
    {
        $this->classMaps = array_merge($this->classMaps, $maps);

        return $this;
    }

    /**
     * @return array
     */
    public function getClassMaps()
    {
        return $this->classMaps;
    }

    /**
     * @param bool $prepend
     * @return void
     */
    public function registerClassLoader($prepend = false)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }

    /**
     * @param $class
     * @return int | mixed
     */
    public function loadClass($class)
    {
        foreach ($this->classMaps as $namespace => $paths) {
            $filePath = $class;
            if (!empty($namespace) && 0 === ($pos = strpos($class, $namespace))) {
                $filePath = substr($class, strlen($namespace));
            }

            foreach ($paths as $path) {
                $file = $path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $filePath) . '.php';
                if (file_exists($file))     {
                    $this->loadClasses[] = $class;
                    return include $file;
                }
            }
        }

        throw new \InvalidArgumentException(sprintf('Class "%s" is not exists.', $class));
    }

    /**
     * @unregister spl autoload
     */
    public function unRegisterClassLoader()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
     * @unregister spl autoload
     */
    public function __destruct()
    {
        $this->unRegisterClassLoader();
    }

    /**
     * @return ClassLoader
     */
    final public function __clone()
    {
        return self::getLoader();
    }
}