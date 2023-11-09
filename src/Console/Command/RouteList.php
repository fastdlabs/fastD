<?php

namespace FastD\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteList extends Command
{
    public function configure()
    {
        $this
            ->setName('route')
            ->setHelp('Show all route')
            ->setDescription('Show you defined routes.')
            ->addArgument('method', InputArgument::OPTIONAL, 'Route request method.')
            ->addArgument('path', InputArgument::OPTIONAL, 'Route path.')
            ->addOption('data', 'd', InputOption::VALUE_OPTIONAL, 'Path request data.')
            ->addOption('all', 'a', InputOption::VALUE_OPTIONAL, 'Test all routes')
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
        $this->render($input, $output);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function render(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $rows = [];
        $table->setHeaders(array('Path', 'Method', 'Callback', 'Middleware'));
        foreach (app()->get('router')->aliasMap as $routes) {
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

                $callback = $route->getCallback();
                if (is_object($callback)) {
                    $callback = get_class($callback);
                } elseif (is_array($callback)) {
                    if (is_object($callback[0])) {
                        $callback[0] = get_class($callback[0]);
                    }
                    $callback = implode('@', $callback);
                }
                $rows[] = [
                    $route->getPath(),
                    $route->getMethod(),
                    $callback,
                    implode(',', $m),
                ];
            }
        }

        $table->setRows($rows);

        $table->render();
    }
}
