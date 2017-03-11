<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Test;


use FastD\Application;
use FastD\Testing\WebTestCase;

/**
 * Class TestCase
 * @package FastD\Test
 */
class TestCase extends WebTestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Set up unit.
     */
    public function setUp()
    {
        $this->app = $this->createApplication();
        parent::setUp();
    }

    /**
     * @return Application
     */
    public function createApplication()
    {
        return new Application(getcwd());
    }

    /**
     * @return \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
     */
    protected function getConnection()
    {
        return $this->createDefaultDBConnection(database()->pdo, app()->getName());
    }

    /**
     * @return \PHPUnit_Extensions_Database_DataSet_CompositeDataSet
     */
    protected function getDataSet()
    {
        $compositeDs = new \PHPUnit_Extensions_Database_DataSet_CompositeDataSet();

        $path = app()->getPath() . '/database/dataset/*.yml';

        if (false !== ($files = glob($path, GLOB_NOSORT | GLOB_NOESCAPE))) {
            foreach ($files as $file) {
                $ds = $this->createArrayDataSet([pathinfo($file, PATHINFO_FILENAME) => load($file),]);
                $compositeDs->addDataSet($ds);
            }
        }

        return $compositeDs;
    }
}