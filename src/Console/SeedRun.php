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
use Phinx\Console\Command\Migrate;
use Phinx\Migration\AbstractMigration;
use Phinx\Migration\Manager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedRun extends Migrate
{
    protected function loadManager(InputInterface $input, OutputInterface $output)
    {
        if (null === $this->getManager()) {
            $manager = new Migration($this->getConfig(), $input, $output);
            $this->setManager($manager);
        }
    }

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

class Migration extends Manager
{
    /**
     * Gets an array of the database migrations, indexed by migration name (aka creation time) and sorted in ascending
     * order
     *
     * @throws \InvalidArgumentException
     * @return AbstractMigration[]
     */
    public function getMigrations()
    {
        if (null === $this->migrations) {
            $phpFiles = $this->getMigrationFiles();

            // filter the files to only get the ones that match our naming scheme
            $fileNames = array();
            /** @var AbstractMigration[] $versions */
            $versions = array();



            foreach ($phpFiles as $filePath) {
                $class = pathinfo($filePath, PATHINFO_FILENAME);
                $fileNames[$class] = basename($filePath);
                require_once $filePath;
                $version = date('YmdHis') . $class;
                $migration = new $class($version, $this->getInput(), $this->getOutput());

                if (!($migration instanceof AbstractMigration)) {
                    throw new \InvalidArgumentException(sprintf(
                        'The class "%s" in file "%s" must extend \Phinx\Migration\AbstractMigration',
                        $class,
                        $filePath
                    ));
                }
                $versions[$version] = $migration;
            }

            ksort($versions);
            $this->setMigrations($versions);
        }

        return $this->migrations;
    }
}