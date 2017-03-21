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

/**
 * Class SeedRun
 * @package FastD\Console
 */
class SeedRun extends Migrate
{
    const ENV = 'dev';

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
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
        $path = app()->getPath() . '/database';
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
                "default_database" => static::ENV,
                static::ENV => array(
                    "adapter" => "mysql",
                    "host" => config()->get('database.host'),
                    "name" => config()->get('database.name'),
                    "user" => config()->get('database.user'),
                    "pass" => config()->get('database.pass'),
                    "port" => config()->get('database.port'),
                    'charset' => config()->get('database.charset', 'utf8'),
                )
            )
        )));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function bootstrap(InputInterface $input, OutputInterface $output)
    {
        if (false === $this->booted) {
            parent::bootstrap($input, $output);
            $this->booted = true;
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap($input, $output);

        $config = $this->getConfig()->getEnvironment(static::ENV);
        $adapter = $this->getManager()->getEnvironment(static::ENV)->getAdapter();
        if (!$adapter->hasDatabase($config['name'])) {
            $adapter->createDatabase($config['name']);
        }

        return parent::execute($input, $output);
    }
}

/**
 * Class Migration
 * @package FastD\Console
 */
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
            /** @var AbstractMigration[] $migrations */
            $migrations = array();

            $version = date('Ymd');
            $versionIncrementSeed = mt_rand(1000, 9999 - count($phpFiles));

            foreach ($phpFiles as $filePath) {
                $class = pathinfo($filePath, PATHINFO_FILENAME);
                $fileNames[$class] = basename($filePath);
                require_once $filePath;
                $migration = new $class($version . (++$versionIncrementSeed), $this->getInput(), $this->getOutput());

                if (!($migration instanceof AbstractMigration)) {
                    throw new \InvalidArgumentException(sprintf(
                        'The class "%s" in file "%s" must extend \Phinx\Migration\AbstractMigration',
                        $class,
                        $filePath
                    ));
                }

                $migrations[$migration->getName()] = $migration;
            }

            ksort($migrations);
            $this->setMigrations($migrations);
        }

        return $this->migrations;
    }


}