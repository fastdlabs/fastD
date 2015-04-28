<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/18
 * Time: 下午8:40
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Framework\Bundle\Commands;

use Dobee\Console\Commands\Command;
use Dobee\Console\Format\Input;
use Dobee\Console\Format\Output;

class RouteCacheCommand extends Command
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'route:caching';
    }

    /**
     * @return void|$this
     */
    public function configure()
    {
        $this->setDescription('Thank for you use Route caching tool.');
    }

    /**
     * @param Input  $input
     * @param Output $output
     * @return void
     */
    public function execute(Input $input, Output $output)
    {
        $output->writeln('Start caching application router collection.');

        $app = $this->getProvider();

        $router = $app->getContainer()->get('kernel.routing');

        $routes = array();

        foreach ($router->getCollections() as $route) {
            $routes[$route->getName()] = serialize($route);
        }

        $path = $app->getRootPath() . DIRECTORY_SEPARATOR . $router->getCacheName();

        $routes = var_export($routes, true);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        file_put_contents($path, '<?php ' . PHP_EOL . 'return ' . $routes . ';');

        $output->writeln('Configuration caching successful. ', Output::STYLE_SUCCESS);

        $output->writeln('The configuration caching path in: ' . $path, Output::STYLE_SUCCESS);
    }
}