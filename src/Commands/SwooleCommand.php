<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Commands;

use FastD\App;
use FastD\AppServer;
use FastD\Console\Input\Input;
use FastD\Console\Output\Output;
use FastD\Standard\Commands\CommandAware;
use FastD\Console\Input\InputOption;
use FastD\Console\Input\InputArgument;
use FastD\Swoole\Console\Service;

class SwooleCommand extends CommandAware
{
    /**
     * @return string
     */
    public function getDescription()
    {
        return <<<EOF
Swoole Server.
EOF;

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'swoole:server';
    }

    /**
     * @return void
     */
    public function configure()
    {
        $this
            ->setArgument('action', InputArgument::REQUIRED)
            ->setOption('host', '-h')
            ->setOption('port', '-p')
            ->setOption('conf', '-c')
            ->setOption('daemon', '-d', InputOption::VALUE_NONE)
        ;
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return int
     */
    public function execute(Input $input, Output $output)
    {
        $config = [];

        if (null !== $input->getOption('conf')) {
            $conf = $input->getOption('conf');
            switch (pathinfo($conf, PATHINFO_EXTENSION)) {
                case 'ini':
                    $config = parse_ini_file($conf, true);
                    break;
                default:
                    $config = include $conf;
            }
        }

        if ($input->hasOption('daemon')) {
            $config['daemonize'] = true;
        }

        if ($input->hasOption('host')) {
            $config['host'] = $input->getOption('host');
        }

        if ($input->hasOption('port')) {
            $config['port'] = $input->getOption('port');
        }

        $server = new AppServer(new App($config));

        $service = Service::server($server);

        switch ($input->getArgument('action')) {
            case 'status':
                $service->status();
                break;
            case 'start':
                $service->start();
                break;
            case 'stop':
                $service->shutdown();
                break;
            case 'reload':
                $service->reload();
                break;
            case 'watch':
                $service->watch(['.']);
                break;
            default:
                echo "php server {start|stop|status|reload|watch}";
        }
    }
}