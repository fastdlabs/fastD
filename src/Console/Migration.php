<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Console;


use Phinx\Config\Config as PC;
use Phinx\Console\Command\Migrate;

class Migration extends Migrate
{
    public function configure()
    {
        parent::configure();

        $this->setName('db:migration');

        $migrationPath = app()->getPath() . "/db/migrations";
        $seeds = app()->getPath() . "/db/seeds";

        if (!file_exists($migrationPath)) {
            mkdir($migrationPath, 0755, true);
        }
        if (!file_exists($seeds)) {
            mkdir($seeds, 0755, true);
        }

        $this->setConfig(new PC([
            'paths' => [
                'migrations' => $migrationPath,
                'seeds' => $seeds
            ],
            'environments' => [
                'default_database' => 'dev',
                'dev' => [
                    'adapter' => 'mysql',
                    'name' => config()->get('database.name'),
                    'host' => config()->get('database.host'),
                    'port' => config()->get('database.port'),
                    'user' => config()->get('database.user'),
                    'pass' => config()->get('database.pass'),
                ]
            ]
        ]));
    }
}