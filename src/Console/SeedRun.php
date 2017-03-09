<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Console;


use Phinx\Config\Config as MConfig;

class SeedRun extends \Phinx\Console\Command\SeedRun
{
    public function configure()
    {
        parent::configure();
        $path = app()->getPath() . '/database/seeds';
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $this->setName('seed:run');
        $this->setConfig(new MConfig(array(
            "paths" => array(
                "migrations" => $path,
                "seeds" => $path,
            ),
            "environments" => array(
                "default_database" => "dev",
                "dev" => array(
                    "adapter" => "mysql",
                    "host" => config()->get('database.host'),
                    "name" => config()->get('database.name'),
                    "user" => config()->get('database.user'),
                    "pass" => config()->get('database.pass'),
                    "port" => config()->get('database.port')
                )
            )
        )));
    }
}