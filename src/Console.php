<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;

use FastD\Console\RouteDump;
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
            new RouteDump(),
        ]);

        $this->initializeCommands();
    }

    /**
     * Scan commands.
     *
     * @return void
     */
    public function initializeCommands()
    {
        if (false !== ($files = glob(app()->getAppPath() . '/src/Console/*.php', GLOB_NOSORT | GLOB_NOESCAPE))) {
            foreach ($files as $file) {
                $command = '\\Console\\' . pathinfo($file, PATHINFO_FILENAME);
                $this->add(new $command);
            }
        }
    }
}