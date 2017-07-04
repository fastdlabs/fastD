<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Route.
 */
class RouteDump extends Command
{
    public function configure()
    {
        $this
            ->setName('route:dump')
            ->setHelp('Show all route')
            ->setDescription('Show you defined routes.')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $rows = [];
        $table->setHeaders(array('Path', 'Method', 'Callback', 'Middleware'));
        foreach (route()->aliasMap as $routes) {
            foreach ($routes as $route) {
                $m = [];
                $middleware = $route->getMiddleware();
                if (is_array($middleware)) {
                    foreach ($middleware as $value) {
                        if (is_object($value)) {
                            $m[] = get_class($value);
                        } else {
                            $m[] = $value;
                        }
                    }
                } elseif (is_object($middleware)) {
                    $m[] = get_class($middleware);
                }
                $rows[] = [
                    $route->getPath(),
                    $route->getMethod(),
                    $route->getCallback(),
                    implode(',', $m),
                ];
            }
        }

        $table->setRows($rows);

        $table->render();
    }
}
