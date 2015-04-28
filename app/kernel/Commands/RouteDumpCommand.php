<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/18
 * Time: 下午4:32
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
use Dobee\Routing\RouteException;
use Dobee\Routing\Router;

class RouteDumpCommand extends Command
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'route:dump';
    }

    /**
     * @return void|$this
     */
    public function configure()
    {
        $this
            ->setArguments('route', null)
            ->setDescription('Thank for you use routing dump tool.')
        ;
    }

    /**
     * @param Input  $input
     * @param Output $output
     * @return void
     */
    public function execute(Input $input, Output $output)
    {
        $app = $this->getProvider();

        $router = $app->getContainer()->get('kernel.routing');

        $output->writeln('');

        if ('' == $input->get('route')) {
            $this->showRouteCollections($router, $output);
        } else {
            $route = $router->getRoute($input->get('route'));
            $output->write('Route [');
            $output->write('"' . $input->get('route') . '"', Output::STYLE_SUCCESS);
            $output->writeln(']');
            $output->writeln("Name:\t\t" . $route->getName());
            $output->writeln("Group:\t\t" . str_replace('//', '/', $route->getGroup()));
            $output->writeln("Path:\t\t" . $route->getRoute());
            $output->writeln("Method:\t\t" . implode(', ', $route->getMethod()));
            $output->writeln("Format:\t\t" . implode(', ', $route->getFormat()));
            $output->writeln("Callback:\t" . $route->getCallback());
            $output->writeln("Defaults:\t" . implode(', ', $route->getDefaults()));
            $output->writeln("Requirements:\t" . implode(', ', $route->getRequirements()));
            $output->writeln("Path-Regex:\t" . $route->getPathRegex());
        }

        $output->writeln('');
    }

    public function showRouteCollections(Router $router, Output $output)
    {
        $length = 30;
        $output->writeln("Name" . str_repeat(' ', 26) . "Method" . str_repeat(' ', 24) . "Group" . str_repeat(' ', 25) . "Path", Output::STYLE_SUCCESS);
        foreach ($router->getCollections() as $name => $route) {
            $method = is_array($route->getMethod()) ? implode(', ', $route->getMethod()) : $route->getMethod();
            $group = str_replace('//', '/', $route->getGroup());
            $group = empty($group) ? '/' : $group;
            $output->writeln(
                $route->getName() . str_repeat(' ', ($length - strlen($route->getName()))) .
                $method . str_repeat(' ', ($length - strlen($method))) .
                $group . str_repeat(' ', ($length - strlen($group))) .
                $route->getRoute()
            );
        }
    }
}