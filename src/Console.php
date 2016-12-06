<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;

use FastD\Commands\BundleGeneratorCommand;
use FastD\Commands\AssetInstallCommand;
use FastD\Commands\ConfigCacheCommand;
use FastD\Commands\FdbDataSetCommand;
use FastD\Commands\RouteCacheCommand;
use FastD\Commands\FdbReflexCommand;
use FastD\Commands\FdbSchemaCommand;
use FastD\Commands\RouteDumpCommand;
use FastD\Container\Container;
use Symfony\Component\Console\Application;

/**
 * Class AppConsole
 *
 * @package FastD\Framework\Kernel
 */
class Console extends Application
{
    /**
     * @var App
     */
    protected $application;

    /**
     * AppConsole constructor.
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->application = $app;

        parent::__construct();

        $this->scanCommands();
    }

    /**
     * @return App
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->application->getContainer();
    }

    /**
     * @return array
     */
    public function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();

        return array_merge($defaultCommands, [
            new AssetInstallCommand($this->getContainer()),
            new RouteCacheCommand($this->getContainer()),
            new RouteDumpCommand($this->getContainer()),
            new FdbReflexCommand($this->getContainer()),
            new FdbDataSetCommand($this->getContainer()),
            new FdbSchemaCommand($this->getContainer()),
            new BundleGeneratorCommand($this->getContainer()),
            new ConfigCacheCommand($this->getContainer()),
        ]);
    }

    /**
     * @return string
     */
    public function getDefaultCommandName()
    {
        return 'list';
    }

    /**
     * Scan commands.
     *
     * @return void
     */
    public function scanCommands()
    {
        foreach ($this->getApplication()->getBundles() as $bundle) {
            $dir = $bundle->getDir() . '/Commands';
            if (!is_dir($dir)) {
                continue;
            }
            if (false !== ($files = glob($dir . '/*.php', GLOB_NOSORT | GLOB_NOESCAPE))) {
                foreach ($files as $file) {
                    $class = $bundle->getNamespace() . '\\Commands\\' . pathinfo($file, PATHINFO_FILENAME);
                    $command = new $class();
                    if ($command instanceof CommandAware) {
                        $command->setContainer($this->getContainer());
                        $this->addCommand($command);
                    }
                }
            }
        }
    }
}