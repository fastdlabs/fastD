<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Console;

use Phinx\Config\Config as MConfig;
use Phinx\Console\Command\Create;
use Phinx\Util\Util;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedCreate extends Create
{
    public function configure()
    {
        parent::configure();
        $path = app()->getPath().'/database/schema';
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $this->setName('seed:create');
        $database = config()->get('database');
        $env = [];
        $keys = array_keys($database);
        $default = $keys[0];
        foreach ($database as $name => $config) {
            $env[$name] = [
                'adapter' => 'mysql',
                'host' => config()->get('database.'.$name.'.host'),
                'name' => config()->get('database.'.$name.'.name'),
                'user' => config()->get('database.'.$name.'.user'),
                'pass' => config()->get('database.'.$name.'.pass'),
                'port' => config()->get('database.'.$name.'.port'),
                'charset' => config()->get('database.'.$name.'.charset', 'utf8'),
            ];
        }
        $this->setConfig(new MConfig(array(
            'paths' => array(
                'migrations' => $path,
                'seeds' => $path,
            ),
            'environments' => array_merge([
                'default_database' => $default,
            ], $env),
        )));
    }

    /**
     * Create the new migration.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap($input, $output);

        // get the migration path from the config
        $path = $this->getMigrationPath($input, $output);

        if (!file_exists($path)) {
            $helper = $this->getHelper('question');
            $question = $this->getCreateMigrationDirectoryQuestion();

            if ($helper->ask($input, $output, $question)) {
                mkdir($path, 0755, true);
            }
        }

        $this->verifyMigrationDirectory($path);

        $path = realpath($path);
        $className = $input->getArgument('name');

        if (!Util::isValidPhinxClassName($className)) {
            throw new \InvalidArgumentException(sprintf(
                'The migration class name "%s" is invalid. Please use CamelCase format.',
                $className
            ));
        }

        if (!Util::isUniqueMigrationClassName($className, $path)) {
            throw new \InvalidArgumentException(sprintf(
                'The migration class name "%s" already exists',
                $className
            ));
        }

        // Compute the file path
        $fileName = $className.'.php';
        $filePath = $path.DIRECTORY_SEPARATOR.$fileName;

        if (is_file($filePath)) {
            throw new \InvalidArgumentException(sprintf(
                'The file "%s" already exists',
                $filePath
            ));
        }

        $contents = $this->getTemplate();

        // inject the class names appropriate to this migration
        $classes = array(
            '$useClassName' => $this->getConfig()->getMigrationBaseClassName(false),
            '$className' => $className,
            '$baseClassName' => $this->getConfig()->getMigrationBaseClassName(true),
        );
        $contents = strtr($contents, $classes);

        if (false === file_put_contents($filePath, $contents)) {
            throw new \RuntimeException(sprintf(
                'The file "%s" could not be written to',
                $path
            ));
        }

        $output->writeln('<info>using migration base class</info> '.$classes['$useClassName']);

        if (!empty($altTemplate)) {
            $output->writeln('<info>using alternative template</info> '.$altTemplate);
        } elseif (!empty($creationClassName)) {
            $output->writeln('<info>using template creation class</info> '.$creationClassName);
        } else {
            $output->writeln('<info>using default template</info>');
        }

        $output->writeln('<info>created</info> '.str_replace(getcwd(), '', $filePath));
    }

    public function getTemplate()
    {
        return '
<?php

use FastD\Model\Migration;
use Phinx\Db\Table;

class $className extends Migration
{
    /**
     * @return Table
     */
    public function setUp()
    {
        
    }
    
    /**
     * The table preinstall dataset.
     *
     * @return mixed
     */
    public function dataSet(Table $table)
    {
        
    }
}';
    }
}
