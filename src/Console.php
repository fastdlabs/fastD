<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD;

use FastD\Console\ConfigDump;
use FastD\Console\ControllerCreate;
use FastD\Console\ModelCreate;
use FastD\Console\RouteDump;
use FastD\Console\SeedCreate;
use FastD\Console\SeedRun;
use Symfony\Component\Console\Application as Symfony;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * Class AppConsole.
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
            new ModelCreate(),
            new ControllerCreate(),
            new RouteDump(),
            new ConfigDump(),
            new SeedCreate(),
            new SeedRun(),
        ]);

        $this->registerCommands();
    }

    /**
     * Scan commands.
     */
    public function registerCommands()
    {
        foreach (config()->get('consoles', []) as $console) {
            $this->add(new $console());
        }

        if (false !== ($files = glob(app()->getPath().'/src/Console/*.php', GLOB_NOSORT | GLOB_NOESCAPE))) {
            foreach ($files as $file) {
                $command = '\\Console\\'.pathinfo($file, PATHINFO_FILENAME);
                $this->add(new $command());
            }
        }
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        try {
            return parent::doRun($input, $output);
        } catch (\Exception $exception) {
            app()->handleException($exception);
        } catch (\Throwable $exception) {
            app()->handleException(new FatalThrowableError($exception));
        }

        throw $exception;
    }
}
