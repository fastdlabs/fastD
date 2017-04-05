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
use PHPUnit_Extensions_Database_DataSet_IDataSet;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;

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
    }

    /**
     * @return Application
     */
    public function createApplication()
    {
        return new Application(getcwd());
    }

    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        return $this->createDefaultDBConnection(database()->pdo);
    }

    /**
     * Returns the test dataset.
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        $path = app()->getPath() . '/database/dataset';

        return new \PHPUnit_Extensions_Database_DataSet_ArrayDataSet([]);
    }
}