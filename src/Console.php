<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;


use FastD\Console\Config;
use FastD\Console\Route;
use FastD\Console\SeedCreate;
use FastD\Console\SeedRun;
use Symfony\Component\Console\Application as Symfony;

/**
 * Class AppConsole
 *
 * @package FastD\Framework\Kernel
 */
class Console extends Symfony
{
    /**
     * AppConsole constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app->getName(), Application::VERSION);

        $this->addCommands([
            new Route(),
            new Config(),
            new SeedCreate(),
            new SeedRun(),
        ]);

        $this->registerCommands();
    }

    /**
     * Scan commands.
     *
     * @return void
     */
    public function registerCommands()
    {
        foreach (config()->get('consoles', []) as $console) {
            $this->add(new $console);
        }

        if (false !== ($files = glob(app()->getPath() . '/src/Console/*.php', GLOB_NOSORT | GLOB_NOESCAPE))) {
            foreach ($files as $file) {
                $command = '\\Console\\' . pathinfo($file, PATHINFO_FILENAME);
                $this->add(new $command);
            }
        }
    }
}