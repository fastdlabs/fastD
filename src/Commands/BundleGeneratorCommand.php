<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Commands;

use FastD\Console\Input\Input;
use FastD\Console\Output\Output;
use FastD\Standard\Commands\CommandAware;

/**
 * Class Generator
 *
 * @package FastD\Framework\Commands
 */
class BundleGeneratorCommand extends CommandAware
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'bundle:generate';
    }

    /**
     * @return void|$this
     */
    public function configure()
    {
        $this->setArgument('bundle');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return void
     */
    public function execute(Input $input, Output $output)
    {
        try {
            $bundle = $input->getArgument('bundle');
        } catch (\Exception $e) {
            $output->writeln('<error>Bundle name is empty or null. Please you try again.</error>');
            exit;
        }

        if (empty($bundle)) {
            $output->writeln('<error>Bundle name is empty or null. Please you try again.</error>');
            exit;
        }

        $bundle = str_replace(':', DIRECTORY_SEPARATOR, $bundle) . 'Bundle';

        $bundle = implode(DIRECTORY_SEPARATOR, array_map(function ($v) {
            return ucfirst($v);
        }, explode(DIRECTORY_SEPARATOR, $bundle)));

        $source = $this->getContainer()->singleton('kernel')->getRootPath() . '/../src';

        $this->builderStructure($source, $bundle, str_replace(DIRECTORY_SEPARATOR, '', $bundle));

        $output->writeln('Building in ' . $source . '  <success>[OK]</success>');
    }

    public function builderStructure($path, $bundle, $fullName)
    {
        $bundlePath = implode(DIRECTORY_SEPARATOR, array(
            $path,
            $bundle
        ));

        foreach ([
                     'Controllers',
                     'Extensions',
                     'Exceptions',
                     'Commands',
                     'Services',
                     'Fixtures',
                     'Resources/views',
                     'Resources/config',
                     'Testing'
                 ] as $dir) {
            $directory = implode(DIRECTORY_SEPARATOR, array(
                $bundlePath,
                $dir
            ));

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
        }

        $bundleArray = explode(DIRECTORY_SEPARATOR, $bundle);

        $controller = sprintf(
            $this->getControllerTemplate(),
            str_replace(DIRECTORY_SEPARATOR, '\\', $bundle),
            strtolower($bundleArray[0]),
            '/' . strtolower(end($bundleArray)),
            strtolower(str_replace(DIRECTORY_SEPARATOR, '_', $bundle)) . '_index'
        );

        $controllerFile = $bundlePath . DIRECTORY_SEPARATOR . 'Controllers/IndexController.php';

        if (!file_exists($controllerFile)) {
            file_put_contents($controllerFile, $controller);
        }

        $bootstrap = sprintf(
            $this->getBootstrapTemplate(),
            str_replace(DIRECTORY_SEPARATOR, '\\', $bundle),
            $fullName
        );

        $bootstrapFile = $bundlePath . DIRECTORY_SEPARATOR . $fullName . '.php';

        if (!file_exists($bootstrapFile)) {
            file_put_contents($bootstrapFile, $bootstrap);
        }

        $routes = $bundlePath . DIRECTORY_SEPARATOR . 'Resources/config/routes.php';
        if (!file_exists($routes)) {
            file_put_contents($routes, '<?php ' . PHP_EOL);
        }

        $configPath = $bundlePath . DIRECTORY_SEPARATOR . 'Resources/config';

        foreach ([
            '/config_dev.php',
            '/config_test.php',
            '/config_prod.php',
                 ] as $value) {
            $config = $configPath . $value;
            if (!file_exists($config)) {
                file_put_contents($config, '<?php return [];' . PHP_EOL);
            }
        }
    }

    public function getControllerTemplate()
    {
        return <<<CONTROLLER
<?php

namespace %s\Controllers;

use FastD\Framework\Bundle\Controllers\Controller;

/**
 * @Route("/%s")
 */
class IndexController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return \$this->response('hello world');
    }
}
CONTROLLER;
    }

    public function getBootstrapTemplate()
    {
        return <<<BUNDLE
<?php

namespace %s;

use FastD\Framework\Bundle\Bundle;

class %s extends Bundle
{

}
BUNDLE;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return '生成 Bundle 骨架';
    }
}