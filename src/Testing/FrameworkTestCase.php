<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/18
 * Time: 下午11:27
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Framework\Tests;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Finder\Finder;

/**
 * Class FrameworkTestCase
 *
 * @package FastD\Framework\Tests
 */
abstract class FrameworkTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Application
     */
    protected static $application;

    /**
     * @param string $env
     */
    public static function kernelBootstrap($env = AppKernel::ENV_DEV)
    {
        static::$application = static::getApplication($env);

        static::$application->boot();
    }

    /**
     * @param string $env
     * @return \Application
     */
    protected static function getApplication($env = AppKernel::ENV_DEV)
    {
        $dir = static::getPhpUnitXmlDir() . '/app';

        $finder = new Finder();

        $finder->name('application.php')->depth(0)->in($dir);
        $results = iterator_to_array($finder);

        if (!count($results)) {
            throw new \RuntimeException(sprintf('Not find kernel application in %s.', $dir));
        }

        $file = current($results);
        $class = $file->getBasename('.php');

        if (!class_exists($class)) {
            include $file->getPath() . DIRECTORY_SEPARATOR . $class . '.php';
        }

        unset($finder);

        return new \Application($env);
    }

    /**
     * Finds the value of the CLI configuration option.
     *
     * @return string The value of the PHPUnit CLI configuration option
     */
    private static function getPhpUnitConfiguration()
    {
        $dir = null;
        $reversedArgs = array_reverse($_SERVER['argv']);
        foreach ($reversedArgs as $argIndex => $testArg) {
            if (preg_match('/^-[^ \-]*c$/', $testArg) || $testArg === '--configuration') {
                $dir = realpath($reversedArgs[$argIndex - 1]);
                break;
            } elseif (strpos($testArg, '--configuration=') === 0) {
                $argPath = substr($testArg, strlen('--configuration='));
                $dir = realpath($argPath);
                break;
            }
        }

        return $dir;
    }

    /**
     * Finds the directory where the phpunit.xml(.dist) is stored.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getPhpUnitXmlDir()
    {
        $dir = static::getPhpUnitConfiguration();
        if (null === $dir &&
            (is_file(getcwd().DIRECTORY_SEPARATOR.'phpunit.xml') ||
                is_file(getcwd().DIRECTORY_SEPARATOR.'phpunit.xml.dist'))) {
            $dir = getcwd();
        }

        // Can't continue
        if (null === $dir) {
            throw new \RuntimeException('Unable to guess the Kernel directory.');
        }

        if (!is_dir($dir)) {
            $dir = dirname($dir);
        }

        return $dir;
    }

    /**
     * Shuts the kernel down if it was used in the test.
     */
    protected static function ensureKernelShutdown()
    {
        if (null !== static::$application) {
            $container = static::$application->getContainer();
            static::$application->shutdown(static::$application);
            $container->reset();
            static::$application->setContainer($container);
        }
    }
    /**
     * Clean up Kernel usage in this test.
     */
    protected function tearDown()
    {
        static::ensureKernelShutdown();
    }
}